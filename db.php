<?php
date_default_timezone_set("Asia/Taipei");	//系統時區（TimeZone），即使作業系統已經設定時區，但 PHP 仍可能不理會系統預設值
class DBHandle {
	// private $dbh;

	function __construct() {
		// require_once=>引入檔案，可避免重複引入
		require_once __DIR__.'/config.php';
		$this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$this->mysqli->query("SET NAMES utf8");  //將資料設為utf8格式（才能讀取中文），需要資料庫的編碼相同
        if (!$this->mysqli){ die('Error: d0001');}//輸出一條消息，並退出當前腳本
	}
 

	function __destruct() {
		// 析構函數會在到某個對象的所有引用都被刪除或者當對像被顯式銷毀時執行
		$this->mysqli->close();//關閉mysql連接
	}

	// 登入時，檢查使用者是否存在
	public function checkUser($user_data) {
		// PHP Associative Arrays:Associative arrays are arrays that use named keys that you assign to them.
		$res = array('status' => false,
					'msg' => '');
		$acc = $user_data['username'];
		$pwd = $this->getPasswordHash($user_data['password']);
		$sql = "SELECT id,username FROM `users` WHERE username='$acc' AND password='$pwd'";
		// $this->mysqli->query() 函數執行一條 MySQL 查詢。
		// $this->mysqli->query() 在執行成功時返回 TRUE，出錯時返回 FALSE，非 FALSE 的返回值意味著查詢是合法的並能夠被服務器執行。這並不說明任何有關影響到的或返回的行數。很有可能一條查詢執行成功了但並未影響到或並未返回任何行。		
		$result = $this->mysqli->query($sql);
		// num_rows 函式來統計從 MysQL 資料庫中撈出數筆資料
		if ($result->num_rows == 1){
			// array mysql_fetch_array ( resource $result , int $result_type )	，第一個參數 $result 就是 $this->mysqli->query 的結果集，必要項目，第二個參數 $result_type 則為控制儲存鍵名的關鍵
			// mysql_fetch_array => PHP mysql_fetch_array 函式屬於 mysql_fetch_row 的擴展函式，增加了一個參數，可以將 $this->mysqli->query 結果集，以數字索引或關連索引（以字段做為鍵名）等方式，儲存為 PHP Array 的結果形式
			$user = $result->fetch_array();// fetch_array() 來把資料從資料表中讀出，而這個功能會把資料存入陣列之中，而陣列中的Key值將會以數列與實際Key值來儲存。
			$res['userid'] = $user['id'];
			$res['username'] = $user['username'];
			$res['status'] = true;
			$res['msg'] = '登入成功，請等待自動導向';
		}else{
			$res['msg'] = '登入失敗，請確認帳號密碼';
		}

		return $res;
	}

	// 更新密碼時確認User ID
	public function checkUserByUID($uid) {
		$res = array('status' => false,
					'username' => '');
		$uid = (int)$uid;

		$sql = "SELECT username FROM `users` WHERE id=$uid";
		$result = $this->mysqli->query($sql);
		if ($result->num_rows == 1){
			$user = $result->fetch_array();
			$res['username'] = $user['username'];
			$res['status'] = true;
		}

		return $res;
	}
// 點/取消愛心
	public function updateLike($data) {
		$res = array('status' => false,
					'msg' => '');

		$postid = (int)$data['postid'];
		$userid = (int)$data['userid'];
		$like = ($data['like']==1)?1:0;
		// $unixtime = $this->get_total_millisecond();
		// $date = date("Y-m-d H:i:s",$unixtime/1000);
		$unixtime = strtotime("now") ;
		$date = date("Y-m-d H:i:s",$unixtime);
		// author_id:點愛心的使用者
		$sql = "SELECT id FROM `likes` WHERE post_id=$postid AND author_id=$userid";//使用者曾點過此篇PO文愛心
		$result = $this->mysqli->query($sql);
		if ($result->num_rows > 0){
			//存在 - 更新	
			$sql = "UPDATE `likes` SET `is_like`='$like', `updated_at`='$date' WHERE `post_id`=$postid AND `author_id`=$userid";
			$result = $this->mysqli->query($sql);
			if ($this->mysqli->affected_rows > 0) {
				$res['status'] = true;
				$res['msg'] = ($like)?'發送愛心成功':'收回愛心成功';
			}else{
				$res['msg'] = 'update fail';
			}
		}else if($like){//使用者第一次對此篇PO文點愛心
			$sql = "INSERT INTO `likes` values ('', '$postid', '$userid', '$like', '$date', '$date')";
			$result = $this->mysqli->query($sql);
			if ($result) {
				$res['status'] = true;
				$res['msg'] = '發送愛心成功';
			}
		}else{
			$res['msg'] = '發送愛心失敗';
		}

		$this->updatePostLikes($postid);	//更新該篇貼文愛心數量

		return $res;
	}

	// 刪除貼文
	public function deletePost($post_data) {
		$res = array('status' => false,
					'msg' => '');

		$postid = $post_data['postid'];
		$userid = $post_data['userid'];
		// $unixtime = $this->get_total_millisecond();
		$unixtime = strtotime("now") ;
		$date = date("Y-m-d H:i:s",$unixtime);

		$sql = "UPDATE `posts` SET `visible`=0, `updated_at`='$date' WHERE `id`=$postid AND `author_id`=$userid";
		$result = $this->mysqli->query($sql);
		if ($this->mysqli->affected_rows > 0) {//affected_rows:回傳影響的資料筆數。Gets the number of affected rows in a previous MySQL operation
			$res['status'] = true;
			$res['msg'] = '貼文刪除成功，請等待自動導向';
		}else{
			$res['msg'] = '貼文刪除失敗';
		}
		

		return $res;
	}

	// 更新貼文
	public function updatePost($post_data) {
		$res = array('status' => false,
					'msg' => '');

		$postid = $post_data['postid'];
		$userid = $post_data['userid'];
		$content = $post_data['content'];
		// $unixtime = $this->get_total_millisecond();
		$unixtime = strtotime("now") ;
		$date = date("Y-m-d H:i:s",$unixtime);

		$sql = "UPDATE `posts` SET `content`='$content', `updated_at`='$date' WHERE `id`=$postid AND `author_id`=$userid AND `visible`=1";
		$result = $this->mysqli->query($sql);
		if ($this->mysqli->affected_rows > 0) {
			$res['status'] = true;
			$res['msg'] = '貼文更新成功，請等待自動導向';
		}else{
			$res['msg'] = '貼文更新失敗';
		}
		

		return $res;
	}

	// 更新使用者密碼
	public function updateUser($user_data) {
		$res = array('status' => false,
					'msg' => '');

		$userid = $user_data['userid'];
		$old_password = $this->getPasswordHash($user_data['old_password']);
		$new_password = $this->getPasswordHash($user_data['new_password']);
		// $unixtime = $this->get_total_millisecond();
		// $unixtime = strtotime("now") ;
		// $date = date("Y-m-d H:i:s",$unixtime);

		$sql = "UPDATE `users` SET `password`='$new_password' WHERE `id`=$userid AND `password`='$old_password'";
		$result = $this->mysqli->query($sql);
		if ($this->mysqli->affected_rows > 0) {//mysqli_affected_rows — Gets the number of affected rows in a previous MySQL operation
			$res['status'] = true;
			$res['msg'] = '個人資料更新成功';
		}else{
			$res['msg'] = '個人資料更新失敗';
		}
		

		return $res;
	}
// 更新該篇貼文愛心數量
	public function updatePostLikes($post_id) {
		$res = array('status' => false,
					'msg' => '');

		$sql = "SELECT id FROM `likes` WHERE `post_id`=$post_id AND `is_like`=1";

		$result = $this->mysqli->query($sql);
		$likes = $result->num_rows;//此篇PO文被點幾次愛心
		// $unixtime = $this->get_total_millisecond();
		$unixtime = strtotime("now") ;
		// $date = date("Y-m-d H:i:s",$unixtime);
		
		if ($likes >= 0){	
			//存在 - 更新
			$sql = "UPDATE `posts` SET `likes`='$likes' WHERE `id`=$post_id";
			$result = $this->mysqli->query($sql);
			if ($result) {
				$res['status'] = true;
			}
		}

		return $res;
	}
// 儲存圖片
	public function insertImage($data) {
		$res = array('status' => false,
					'msg' => '');
		$img_name = $data['name'];//亂數產生的檔名
		$img_data = $data['data'];//圖片讀為字符串，在MySQL存為Blob的二進位格式，BLOB數據類型是一種大型的二進制對象，可以保存可變數量的數據
		// echo "<script>console.log( ' " . $img_data	.  "' );</script>"; 
		// $unixtime = $this->get_total_millisecond();//1571931993842
		// $unixtime = strtotime("now") ;
		// $unixtime=1584784063
		$unixtime = strtotime("now") ;

		// $date = date("Y-m-d H:i:s",$unixtime/1000);//2019-10-24 23:46:33
		// $date =2020-03-21 02:47:43
		$date = date("Y-m-d H:i:s",$unixtime);
		// echo "<script>console.log( 'Debug Objects: " . $unixtime	.  "' );</script>";
		// echo "<script>console.log( 'Debug Objects: " . $date	.  "' );</script>"; 
		// Blob是一個二進制的對象，它是一個可以存儲大量數據的容器(如圖片，音樂等等)，且能容納不同大小的數據
		$sql = "INSERT INTO `images` values ('', '$img_name', '$img_data', '$date')";
		$result = $this->mysqli->query($sql);
		if ($result) {
			$res['status'] = true;
			$res['msg'] = '圖片寫入資料庫成功';
		}else{
			$res['msg'] = '圖片寫入資料庫失敗';
		}
		
		return $res;
    }
// 留言
	public function insertComment($comment_data) {
		$res = array('status' => false,
					'msg' => '');
		
		$postid = (int)$comment_data['postid'];
		$userid = (int)$comment_data['userid'];
		$username = $comment_data['username'];
		$content = $comment_data['content'];
		$visible = 1;
		// $unixtime = $this->get_total_millisecond();
		// $date = date("Y-m-d H:i:s",$unixtime/1000);
		$unixtime = strtotime("now") ;
		$date = date("Y-m-d H:i:s",$unixtime);

		$sql = "INSERT INTO `comments` values ('', '$postid', '$userid', '$username', '$content', '$visible', '$date', '$date')";
		$result = $this->mysqli->query($sql);
		if ($result) {
			$res['name'] = $username;
			$res['content'] = $content;
			$res['date'] = $date;
			$res['status'] = true;
			$res['msg'] = '回覆成功，請等待自動導向';
		}else{
			$res['msg'] = '回覆失敗';
		}
		
		return $res;
    }

		//儲存PO文
	public function insertPost($post_data) {
		$res = array('status' => false,
					'msg' => '');
		$userid = (int)$post_data['userid'];
		$username = $post_data['username'];
		$img_filename = $post_data['img_filename'];
		$content = $post_data['content'];
		$likes = 0;//預設沒點Like
		$visible = 1;//預設PO文被看見
		// $unixtime = $this->get_total_millisecond();
		// $date = date("Y-m-d H:i:s",$unixtime/1000);
		$unixtime = strtotime("now") ;
		$date = date("Y-m-d H:i:s",$unixtime);

		$sql = "INSERT INTO `posts` values ('', '$userid', '$username','$img_filename', '$content', '$likes', '$visible', '', '$date')";
		// $sql = "INSERT INTO `posts` values ('', '$userid', '$username','$img_filename', '$content', '$likes', '$visible', '', '$date')";
		$result = $this->mysqli->query($sql);
		if ($result) {
			$res['postid'] = $this->mysqli->insert_id;
			$res['status'] = true;
			$res['msg'] = '發布成功，請等待自動導向';
		}else{
			$res['msg'] = '發布失敗';
		}
		
		return $res;
    }

		// 取所有PO文
	public function getPosts($data) {
		$res = array('status' => false,
					'msg' => '');
					
		$userid = $data['userid'];
		// DESC =>大到小排序，ORDER BY預設ASC(小到大)
		// 判斷作者是否有對自己PO的文章按愛心


		$sql = "SELECT p.id,p.author_id,p.author_name,p.img_filename,p.content,p.likes,p.updated_at,p.created_at,l.is_like as islike 
		FROM (SELECT * FROM `posts` WHERE `visible`=1) as p 
		LEFT JOIN (SELECT * FROM `likes` WHERE `author_id`='$userid') as l on p.id = l.post_id ORDER BY p.id DESC";

		// $sql = "SELECT p.id,p.author_id,p.author_name,p.img_filename,p.content,p.likes,p.updated_at,p.created_at,l.is_like as islike 
		// FROM (SELECT * FROM `posts` WHERE `visible`=1) as p 
		// LEFT JOIN (SELECT * FROM `likes` WHERE `author_id`='$userid') as l on p.id = l.post_id ORDER BY p.id DESC";

		

		$result = $this->mysqli->query($sql);
		if ($result) {
			$post_data = array();
			while($item = $result->fetch_array()){//取得每一個貼文
				$comment_data = array();
				$r = $this->getCommentsPostID($item['id']);//取得每一個貼文底下的留言
				
				if ($r['status']){
					$comment_data = $r['comment_data'];//該篇文章所有留言
				}
				array_push($post_data, array(
					      'id' => $item['id'],										//貼文id
								'name' => $item['author_name'],					//貼文作者
								'img' => $item['img_filename'],					//貼文圖檔名
								'content' => $item['content'],					//貼文內容
								'owner' => $item['author_id'] == $userid,//該篇貼文擁有者為1，非擁有者為0
								'islike' => ($item['islike'] == 1)?1:0,//有點該篇貼文愛心為1，沒有為0
								'likes' => (int)$item['likes'],//該篇貼文被點愛心總數
								// 'last_date' =>  date("Y-m-d H:i:s", strtotime($item['updated_at'])/1000),// strtotime 函數的功能是可以將任何英文格式的日期轉換為 Unix 時間戳（timestamp）
								'last_date' =>  $item['updated_at'],
								'date' => $item['created_at'],
								'comments' => $comment_data
				));
			}
			$res['post_data'] = $post_data;
			$res['post_count'] = count($post_data);//幾篇貼文
			$res['status'] = true;
			$res['msg'] = '取得貼文資料成功';
		}else{
			$res['msg'] = '取得貼文資料失敗';
		}

		return $res;
    }
// 取得該篇貼文
	public function getPost($data) {
		$res = array('status' => false,
					'msg' => '');
					
		$postid = $data['postid'];
		$userid = $data['userid'];
		$sql = "SELECT img_filename, content FROM `posts` WHERE `author_id`='$userid' AND `id`='$postid' AND `visible`=1";
		$result = $this->mysqli->query($sql);
		if ($result->num_rows == 1) {
			$item = $result->fetch_array();
			$res['img'] = $item['img_filename'];
			$res['content'] = $item['content'];
			$res['status'] = true;
			$res['msg'] = '取得貼文資料成功';
		}else{
			$res['msg'] = '取得貼文資料失敗';
		}

		return $res;
    }

		// 上傳完成後，讀取照片
	public function getImage($filename) {
		$res = array('status' => false,
					'msg' => '');
					
		$sql = "SELECT data FROM `images` WHERE `name`='$filename'";
		$result = $this->mysqli->query($sql);
		if ($result->num_rows == 1) {
			$data = $result->fetch_assoc();
			$res['image'] = $data['data'];
			$res['status'] = true;
			$res['msg'] = '取得圖片資料成功';
		}else{
			$res['msg'] = '取得圖片資料失敗';
		}

		return $res;
    }
//取得每一個貼文底下的留言
	public function getCommentsPostID($post_id) {
		$res = array('status' => false,
					'msg' => '');
		// 取得該篇文章底下的所有留言，依照留言順序排序
		// ASC 代表結果會以由小往大的順序列出，先留言的id較小
		$sql = "SELECT * FROM `comments` WHERE post_id='$post_id' AND visible='1' ORDER BY `id` ASC;";
		$result = $this->mysqli->query($sql);//mysqli->query: Returns FALSE on failure. For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE.
		if ($result) {
			$comment_data = array();
			// 把每篇留言push to array
			while($item = $result->fetch_array()){
				array_push($comment_data, array(
								'name' => $item['author_name'],
								'content' => $item['content'],
								'date' => $item['created_at']
				));
			}
			$res['comment_data'] = $comment_data;//該篇文章所有留言
			$res['comment_count'] = count($comment_data);//count:陣列元素數量，該篇文章有幾篇留言
			$res['status'] = true;
			$res['msg'] = '取得回覆資料成功';
		}else{
			$res['msg'] = '取得回覆資料失敗';
		}

		return $res;
    }

		// 建立使用者
	public function createUser($user_data) {
		$res = array('status' => false,
					'msg' => '');
		$acc = $user_data['username'];
		$pwd = $this->getPasswordHash($user_data['password']);

		if ($this->userIsExist($acc)){
			$res['msg'] = '使用者名稱已存在，請嘗試其他名稱';
		}else{
			$sql = "INSERT INTO `users` values ('', '$acc', '$pwd', NOW())";//MySQL要把''換成NULL
			$result = $this->mysqli->query($sql);
			if ($result) {
				$res['userid'] = $this->mysqli->insert_id;//當使用資料庫新增語法，會回傳ID值。Returns the auto generated id used in the latest query。函數返回上一步 INSERT 操作產生的 ID。
				$res['username'] = $user_data['username'];
				$res['status'] = true;
				$res['msg'] = '使用者建立成功，請等待自動導向';
			}else{
				$res['msg'] = '使用者建立失敗';
			}
		}

		return $res;
    }
// 判斷使用者是否存在
	public function userIsExist($username) {
		$sql = "SELECT username FROM `users` WHERE username='$username'";
		$result = $this->mysqli->query($sql);
		// Gets the number of rows in a result，num_rows 函式用來統計 MySQL SELECT 結果集的行數
		return ($result->num_rows > 0)?true:false;
    }
// Hash密碼
	private function getPasswordHash($password) {
		// if $password='test',$salt='123',then,$password.$salt='test123'
		// 一樣的psssword和salt值不會改變，hash => ecd71870d1963316a97e3ac3408c9835ad8cf0f3c1bc703527c30265534f75ae
		// 程式會先在密碼後面加上「鹽」再進行加密
    	$salt = DB_PASSWORD_HASHSALT;//A salt is a (short) string that is added to the string you want to encrypt or hash		
    	return hash('sha256', $password.$salt);//PHP 直接做了 hash() 來用，直接指定要用哪個雜湊演算法即可。
	}

	public function get_total_millisecond(){
		// PHP 的 time 或 mktime 函數僅能返回至秒，而 microtime 函數可以返回至小數點以下的毫秒，例如 time 可以返回 1415335769 這樣的 Unix 時間戳，而 microtime 可以返回 1415335769.36 
		// 傳統的情況下，要取得 1415335769.37 這樣的結果，必須再透過其它函數如 explode 的處理
		$time = explode (" ", microtime () );	//explode:字串切割為多個部份並儲存為 PHP Array 陣列。microtime:返回時間戳的微秒數
		// Debug Objects: Array
		// Debug Objects: 0.32240600
		// Debug Objects: 1571908023
		// echo "<script>console.log( 'Debug Objects: " . $time	.  "' );</script>";
    // echo "<script>console.log( 'Debug Objects: " . $time[0]	.  "' );</script>"; 
    // echo "<script>console.log( 'Debug Objects: " . $time[1]	.  "' );</script>";
		// 1.把0.32240600 =>322
		// 2.不夠左邊補0，最多取3個，322不用捕0就是三位數
		// 3.將322放到1571908023後面變成1571908023322
		$time = $time [1] . str_pad((int)($time [0] * 1000), 3, "0", STR_PAD_LEFT); //str_pad() 函数把字符串填充为新的长度。
		// Debug Objects: 1571908023322
		// Debug Objects: 1
		// Debug Objects: 5
		//  echo "<script>console.log( 'Debug Objects: " . $time	.  "' );</script>";
    // echo "<script>console.log( 'Debug Objects: " . $time[0]	.  "' );</script>"; 
    // echo "<script>console.log( 'Debug Objects: " . $time[1]	.  "' );</script>";
		$time2 = explode ( ".", $time ); 
		// Debug Objects: Array
		// Debug Objects: 1571908023322
		// Debug Objects: 
		//  echo "<script>console.log( 'Debug Objects: " . $time2	.  "' );</script>";
    // echo "<script>console.log( 'Debug Objects: " . $time2[0]	.  "' );</script>"; 
    // echo "<script>console.log( 'Debug Objects: " . $time2[1]	.  "' );</script>";
		$time = $time2 [0];
		return $time;
	}
}
?>

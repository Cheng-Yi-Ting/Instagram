<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') die();   //$_SERVER['REQUEST_METHOD'] #訪問頁面時的請求方法。例如：「GET」、「HEAD」，「POST」，「PUT」
    session_start();//使用 session 來記錄用戶的資訊前，要先用 session_start() 這個函式，告訴系統準備開始使用，session_start() 一定要放在網頁的最上方還沒有輸出任何東西之前，如果前面出現了任何的輸出會造成錯誤
    //讀取POST過來的資料，php://input allows you to read raw POST data
    //file_get_contents 接收 POST 的資料，語法:file_get_contents(path,include_path,context,start,max_length)，path:读取的文件，include_path:可选。如果也想在 include_path 中搜寻文件的话，可以将该参数设为 "1"。
    $json = json_decode(file_get_contents('php://input'), TRUE);//json_decode :將json轉成陣列或object。file_get_contents（）函數是用於將文件的內容讀入到一個字串中

    // isset()函數是用來判斷變數是不是有存在，如果有就回傳 1(true)，如果沒有就回傳空值  ，變數值為NULL的時候，isset會把變數當成不存在，變數值為0的時候，isset判斷的是變數，所以回傳true，變數值為空字串的時候，isset判斷的是變數，所以回傳true
    if (!isset($json['username']) |
        !isset($json['password'])){
        die('Error: l0001');
    }

    require_once __DIR__.'/../db.php';
    $dbh = new DBHandle();
    $user_data['username'] = substr($json['username'],0 , 32);  // substr() 函數的用途是用來取得部分字串內容，substr(string,start,length)
    $user_data['password'] = $json['password'];

    //strlen:字串長度
    if (strlen($user_data['username']) > 0 &
        strlen($user_data['password']) > 0){
        $result = $dbh->checkUser($user_data);
    }else{
        die('Error: l0002');
    }

    if ($result['status']){
        $_SESSION['userid'] = $result['userid'];    //使用 $_SESSION["變數名稱"]取得 session 的值，儲存在伺服器端
        $_SESSION['username'] = $result['username'];
    }

    $res = array('status' => $result['status'],
                'msg' => $result['msg']);
    //顯示登入成功/失敗訊息
    echo json_encode($res); //資料轉json格式 ，e.g:echo json_encode(array('id' => '123', 'data' => 'abc')); 輸出:{"id":"123","data":"abc"}
?>

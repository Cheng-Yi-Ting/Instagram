<?php
    session_start();

    if (!isset($_SESSION['userid']) |
        !isset($_SESSION['username'])){
        header("location: ./login.php");
        exit;
    }

    if (!isset($_POST['post_id'])){
        die();
    }

    require_once __DIR__.'/db.php';
    $dbh = new DBHandle();

    $post_id = (int)$_POST['post_id'];//  POST 函式取得剛剛 HTML 表單中 post_id 欄位的值
    $data['postid'] = $post_id;
    $data['userid'] = (int)$_SESSION['userid'];

    $result = $dbh->checkUserByUID($data['userid']);
    if ($result['status'] == true &
        $result['username'] == $_SESSION['username']){
        $result = $dbh->getPost($data);
        if ($result['status'] == true){
            $filename = $result['img'];
            $content = $result['content'];
        }else{
            die();
        }
    }else{
        die();
    }
?>

<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <title>Instagram - Update Post</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <link rel="shortcut icon" href="./favicon.ico">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <div class="container">
      <?php 
        require_once './header.php';
        NavHeader::show('');
      ?>
      <div class="row justify-content-center">
          <div class="upload_container col-lg-6">
    <!-- Bootstrap中modal產生互動式視窗 -->
<?php
        echo '<h1 class="upload_title">編輯貼文</h1>';
        echo "<img class='col-lg-4' src='./img/?n=$filename'/>";
        echo '<h4 class="upload_title">更新內文</h4>
             <textarea id="post_content" class="col-lg-12" rows="4" cols="50">'.$content.'</textarea>
             <input type="hidden" id="img_name" value="'.$filename.'" />
             <div class="form-group row">
                <div class="col-sm-12">
                    <span class="float-right">
                        <input class="btn default_btn post_btn" type="button" name="button" value="更新" onclick="updatePost('.$post_id.')" />
                        <button type="button" class="btn default_btn delete_btn" data-toggle="modal" data-target="#myModal">刪除</button>
                        <a class="btn cancel_btn" href="./">取消</a>
                    </span>
                </div>
            </div>';
?>

    <!-- 動態視窗插件透過資料屬性或 JavaScript 切換您隱藏的內容。它將 .modal-open 加到 <body> 以覆蓋預設的滾動行為，並生成一個 .modal-backdrop 來提供點擊區域，以便在點擊動態視窗外面時移除顯示的動態視窗。 -->

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">刪除貼文</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                    <!-- .sr-only:視覺上被隱藏的內容，向非視覺使用者傳遞額外的視覺資訊或提示（比如通過使用顏色表示含義）的情形中 -->
                    </button>
                
            </div>
            <div class="modal-body">
                <p>刪除後將不會顯示在貼文首頁!!是否確定刪除</p>
            </div>
        <div class="modal-footer">
        <!--  data-dismiss="modal"，模態彈窗裡面加上這個按鈕，那麼點擊則會關閉當前彈窗 -->
            <button type="button" class="btn cancel_btn" data-dismiss="modal">取消</button>
            <button type="button" class="btn default_btn post_btn" data-dismiss="modal" onclick="deletePost(<?php echo $post_id; ?>)">確定</button>
        </div>
        </div>
    </div>
    </div>
</div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/1.12.3/jquery.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/config.js"></script>
    <script src="./js/post_app.js"></script>
</body>
</html>

<!-- PHP htmlspecialchars 函數的功能是用來轉換 HTML 特殊符號為僅能顯示用的編碼，舉例來說，HTML 的大於（>）小於（<）符號、單引號（'）或雙引號（""）都可以轉換為僅能閱讀的 HTML 符號，這是什麼意思呢？就是將 HTML 符號變成不可執行的符號，例如有人利用網站表單輸入一些清除資料庫的語法或塞入後門程式，通常都會用到一些特殊符號 -->

<!-- ENT_QUOTES：雙引號與單引號都要轉換。 -->

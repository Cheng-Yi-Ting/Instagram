<?php
    session_start();
    if (!isset($_SESSION['userid']) |
        !isset($_SESSION['username'])){
        header("location: ./login.php");    //頁面跳轉
        exit;   //離開PHP程式了，所以下面的程式都不會執行
    }
?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <title>Instagram</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta http-equiv="expires" content="0" /> 

    <link rel="shortcut icon" href="./favicon.ico">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <div class="container">
      <?php 
        require_once './header.php';
        NavHeader::show('plus');    //php呼叫類別的內部靜態成員，或者是類別之間呼叫就要用::
      ?>
      <div class="row justify-content-center">
          <div class="upload_container col-lg-6">

<?php
    if (isset($_FILES["file"])){
        // $_FILES["file"]["error"]：如果檔案上傳有錯誤，可以顯示錯誤代碼。
        // ($_FILES["file"]["error"] <= 0 代表檔案上傳沒有錯誤
        
        if ($_FILES["file"]["error"] <= 0){
            // 取得檔案的副檔名
            // $_FILES["file"]["name"]：上傳檔案的原始名稱，PATHINFO_EXTENSION:取得檔案副檔名
            // e.g: $ext=png
            $ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
            //允許圖片副檔名
            $allowedExts = array("gif", "jpeg", "jpg", "png");
            // 檔名:亂數產生字串，md5()的函式會傳回32字元長的字串
             //uniqid:函數基於以微秒計的當前時間，生成一個唯一的ID。傳回13個字元長的字串，rand返回隨機整數
            // The uniqid() function generates a unique ID based on the microtime (the current time in microseconds).
            // Note: The generated ID from this function does not guarantee uniqueness of the return value! To generate an extremely difficult to predict ID, use the md5() function.
            //e.g: $filename=56c94810a10db6987bbe18e4eaca119d.jpg
            // php 產生唯一的ID或字串(uniqid)，也可用來亂數產生檔案名稱
            $filename = md5(uniqid(rand())).'.'.$ext;  

            // print($filename);
            // $_FILES["file"]["tmp_name"]：上傳檔案後的暫存資料夾位置。
            // 使用addslashes()函數避免出現數據格式錯誤，addslashes:在每個單/雙引號（"）前添加\：
            // file_get_contents — Reads entire file into a string
            $data = array('name' => $filename,
                        'data' => addslashes(file_get_contents($_FILES["file"]["tmp_name"])));

            // print($_FILES["file"]["tmp_name"]);
            // echo $_FILES["file"]["tmp_name"]; => C:\xampp\tmp\php6027.tmp
            // in_array(); 主要的功能在於查詢是否存在，若存在則回傳TRUE，若是不存在則回傳FLASE
            // 如果上傳的檔案沒有圖片副檔名
            if (!in_array($ext, $allowedExts)){
                echo '<div id="alertMsg">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <span class="msg">不允許的檔案類型</span>
                        </div>
                     </div>';
                require_once './upload_form.php';
                // 圖片超過85kb限制，85kb=87040bits
            }else if ($_FILES["file"]["size"] > 87040){ 
                echo '<div id="alertMsg">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <span class="msg">檔案大小超過85KB</span>
                        </div>
                     </div>';
                require_once './upload_form.php';
            }else{
                require_once __DIR__.'/db.php';
                $dbh = new DBHandle();
                // BLOB是一種MySQL資料類型，稱為二進制大物件，將圖片訊息插入MySQL BLOB字段中。
                $result = $dbh->insertImage($data);
                if($result['status']){
     
                    echo '<h1 class="upload_title">準備發布</h1>';
                    echo '<div class="col-lg-12"><span class="upload_description">您所上傳的圖片已存入資料庫<br>檔案名稱: '.$_FILES["file"]["name"].'</span></div>';
                    echo "<img class='col-lg-4' src='./img/index.php?n=$filename'/>";
                    echo '<h4 class="upload_title">編輯內文</h4>';
                    echo '<textarea id="post_content" class="col-lg-12" rows="4" cols="50"></textarea>';
                    echo '<input type="hidden" id="img_name" value="'.$filename.'" />';
                    echo '<div class="form-group row">
                            <div class="col-sm-12">
                                <span class="float-right">
                                   <input class="btn default_btn post_btn" type="button" name="button" value="發布" onclick="post()" />
                                   <a class="btn cancel_btn" href="./">取消</a>
                                </span>
                            </div>
                         </div>';
                }
            }
        }else{
            require_once './upload_form.php';
        }
    }else{
        require_once './upload_form.php';
    }
?>
    </div>
</div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/1.12.3/jquery.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/config.js"></script>
    <script src="./js/post_app.js"></script>
</body>
</html>

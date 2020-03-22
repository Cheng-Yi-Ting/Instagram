<?php
    session_start();

    if (!isset($_SESSION['userid']) |
        !isset($_SESSION['username'])){
        // 沒有登入(session)導向至登入頁面
        header("location: ./login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <title>Instagram -Intro</title>
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
        NavHeader::show('intro');
      ?>
        <div class="jumbotron p-3">

            <h3 class="display-5">
                <i class="fa fa-info-circle"></i>
                首頁
            </h3>
            <hr class="my-2">
             <p class="lead">顯示所有文章、圖片及使用者於各篇文章之評論，使用者可對每篇文章點擊愛心，文章及使用者留言依照日期時間排序。</p>
        </div>
        <div class="jumbotron p-3">
            <h3 class="display-5">
                <i class="fa fa-info-circle"></i>
                上傳檔案
            </h3>
            <hr class="my-2">
             <p class="lead">使用者可於上傳圖片時進行文章撰寫。</p>
        </div>
        <div class="jumbotron p-3">
            <h3 class="display-5">
                <i class="fa fa-info-circle"></i>
                修改資料
            </h3>
            <hr class="my-2">
             <p class="lead">使用者可修改密碼及已發表之文章內容。</p>
        </div>
    </div>


    
    <script src="https://cdn.bootcss.com/jquery/1.12.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <!-- <script src="./js/config.js"></script> -->
    <!-- <script src="./js/app.js"></script> -->
</body>
</html>

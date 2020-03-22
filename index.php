<?php
    session_start();

    if (!isset($_SESSION['userid']) |
        !isset($_SESSION['username'])){
        header("location: ./login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <title>Instagram</title>
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
        NavHeader::show('home');
      ?>
      <div id="post-container" class="row justify-content-center">

      </div>
    </div>


    
    <script src="https://cdn.bootcss.com/jquery/1.12.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/config.js"></script>
    <script src="./js/app.js"></script>
</body>
</html>

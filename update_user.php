<?php
    session_start();

    if (!isset($_SESSION['userid']) |
        !isset($_SESSION['username'])){
        header("location: ./login.php");
        exit;
    }

    $username = substr($_SESSION['username'], 0, 32);
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
        NavHeader::show('user');
      ?>
        <div class="row justify-content-center">
            <div class="update_container col-lg-6">
                <h1 class="update_title">修改個人資料</h1>
                <br>
                <form name="update-user" class="update-user" onsubmit="return updateUser(this)">
                    <div class="form-group row">
                        <label class="col-sm-3 form-control-label">名字</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?php echo $username; ?>" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="old-password" class="col-sm-3 form-control-label">舊密碼</label>
                        <div class="col-sm-9">
                        <input type="password" class="form-control" name="old-password" placeholder="Old Password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="new-password" class="col-sm-3 form-control-label">新密碼</label>
                        <div class="col-sm-9">
                        <input type="password" class="form-control" name="new-password" placeholder="New Password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="confirm-password" class="col-sm-3 form-control-label">確認密碼</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="confirm-password" placeholder="Confirm Password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <span class="float-right">
                                <input class="btn default_btn post_btn" type="submit" name="submit" value="更新" />
                                <a class="btn cancel_btn" href="./">取消</a>
                            </span>
                        </div>
                    </div>
                    </form>
            </div>
        </div>
    </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/1.12.3/jquery.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/config.js"></script>
    <script src="./js/update_user_app.js"></script>
</body>
</html>

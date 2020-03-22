<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <title>Instagram - Login</title>
    <meta charset="utf-8">
    <!-- 調整手機瀏覽器的螢幕解析度 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- X-UA-Compatible設置IE兼容模式 -->
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- <link rel="shortcut icon" href="./favicon.ico"> -->

    <!-- 網頁標題圖示 -->
     <link rel="shortcut icon" href="./ig.png">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/login_styles.css">
</head>
<body>
<!-- 。Bootstrap 4 grid syste。
.col-lg- (large devices - screen width equal to or greater than 992px).col-lg- (large devices - screen width equal to or greater than 992px) -->

<div class="container  col-lg-4">
    <!-- bootstrap 內建 class:card,card-header和card-block會自動產生padding -->
    <!-- <div class="card card-inverse card-login"> -->
    <div class="card  card-login ">
        <!-- <div class="card-header"> -->
        <div class="card-header">
            <div class="row">
                <!-- login和register各佔6等份 -->
                <span class="active col-lg-6" id="login-form-link">登入</span>
                <span class="col-lg-6" id="register-form-link">註冊</span>
            </div>
            <hr />
        </div>
        <!-- 帳密區塊 -->
        <div class="card-block">
            <div class="row">
                <div class="col-lg-12" >
                    <form id="login-form" role="form" style="display: block;">
                        <!-- 登入 -->
                        <!-- tabindex:鍵盤輸入tab跳換的順序 -->
                        <!-- bootstrap form-group,form-control,btn btn-warning屬性 -->
                        <div class="form-group">
                            <input type="text" name="username" id="username" tabindex="1" class="form-control"
                            placeholder="Username" value="" />
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" id="password" tabindex="2" class="form-control"
                            placeholder="Password" />
                        </div>
                        <!--登入按鈕-->
                        <div class="form-group">
                            <div class="row">
                                <!-- <div class="col-lg-6 offset-lg-3"> -->
                                <div class="col-lg-6 offset-lg-3">
                                    <input type="button" name="login-submit" id="login-submit" tabindex="3"class="form-control btn btn-warning" onclick="login()" value="確認" />
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="register-form" role="form" style="display: none;">
                        <div class="form-group">
                            <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="" />
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" />
                        </div>
                        <div class="form-group">
                            <input type="password" name="confirm-password" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password" />
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6 offset-lg-3">
                                    <input type="button" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-warning" onclick="register()" value="確認" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- <br> -->
    
</div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/1.12.3/jquery.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/config.js"></script>
    <script src="./js/login_app.js"></script>
</body>
</html>

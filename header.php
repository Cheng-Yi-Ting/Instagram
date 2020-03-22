<?php
class NavHeader {
    public static function show($active){
        echo '<div class="header clearfix">
                <nav>
                    <ul class="nav nav-pills float-right">
                        <li class="nav-item">
                        <a class="nav-btn'.(($active == 'home')?' active':' ').'" href="./"><i class="fa fa-comments" aria-hidden="true"></i></span></a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-btn'.(($active == 'plus')?' active':' ').'" href="./post.php"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-btn'.(($active == 'user')?' active':' ').'" href="./update_user.php"><i class="fa fa-user-circle" aria-hidden="true"></i></a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-btn'.(($active == 'intro')?' active':' ').'" href="./intro.php"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-btn" href="./logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
                        </li>
                    </ul>
                </nav>
                
                <div class="logo"><a href="./"><img class="logo_img" src="./img/ig.png"><h3 class="text-muted">Instagram</h3></a></div>
            </div>';
    }
}


// public methods and properties are accessible only after instantiating class and is called via "->" sign. public static methods and properties can be accessed without need of instantiating class and can be called via "::".

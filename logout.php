<?php
    session_start();
    session_destroy();//重置session

    header("location: ./login.php");
    exit;
?>
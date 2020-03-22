<?php
    session_start();
    session_destroy();

    $res = array('status' => true,
                'msg' => '登出成功，請等待自動導向');
    echo json_encode($res);
?>
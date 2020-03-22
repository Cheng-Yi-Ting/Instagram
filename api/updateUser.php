<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') die();
    session_start();
    $json = json_decode(file_get_contents('php://input'), TRUE);

    if (!isset($_SESSION['userid']) |
        !isset($_SESSION['username'])){
        // die(json_encode(array('status' => false,'msg' => 'Error: up0000')));
        die();
    }

    if (!isset($json['old_password']) |
        !isset($json['new_password'])){
        // die(json_encode(array('status' => false,'msg' => 'Error: up0001')));
        die();
    }


    require_once __DIR__.'/../db.php';
    $dbh = new DBHandle();
    $user_data['userid'] = (int)$_SESSION['userid'];
    $user_data['old_password'] = $json['old_password'];
    $user_data['new_password'] = $json['new_password'];

    //  更新密碼時確認User ID
    $result = $dbh->checkUserByUID($user_data['userid']);
    if ($result['status'] == true &
        $result['username'] == $_SESSION['username']){
        $result = $dbh->updateUser($user_data);
        $res = array('status' => $result['status'],
                'msg' => $result['msg']);
    }else{
        die(json_encode(array('status' => false,'msg' => 'Error: up0002')));
    }

    echo json_encode($res);
?>

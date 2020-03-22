<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') die();
    session_start();
    $json = json_decode(file_get_contents('php://input'), TRUE);

    if (!isset($_SESSION['userid']) |
        !isset($_SESSION['username'])){
        die(json_encode(array('status' => false,'msg' => 'Error: up0000')));
    }

    if (!isset($json['post_id']) |
        !isset($json['content'])){
        die(json_encode(array('status' => false,'msg' => 'Error: up0001')));
    }


    require_once __DIR__.'/../db.php';
    $dbh = new DBHandle();
    $post_data['postid'] = (int)$json['post_id'];
    $post_data['userid'] = (int)$_SESSION['userid'];
    $post_data['content'] = $json['content'];

    $result = $dbh->checkUserByUID($post_data['userid']);
    if ($result['status'] == true &
        $result['username'] == $_SESSION['username']){
        $result = $dbh->updatePost($post_data);
    }else{
        die(json_encode(array('status' => false,'msg' => 'Error: up0003')));
    }

     $res = array('status' => $result['status'],
                'msg' => $result['msg']);

    echo json_encode($res);
?>
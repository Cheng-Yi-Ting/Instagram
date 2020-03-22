<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') die();
    session_start();
    $json = json_decode(file_get_contents('php://input'), TRUE);
    if (!isset($_SESSION['userid']) |
        !isset($_SESSION['username'])){
        die(json_encode(array('status' => false,'msg' => 'Error: gp0000')));
    }

    /*if (!isset($json['img_filename']) |
        !isset($json['content'])){
        die(json_encode(array('status' => false,'msg' => 'Error: gp0001')));
    }*/



    require_once __DIR__.'/../db.php';
    $dbh = new DBHandle();
    $data['userid'] = (int)$_SESSION['userid'];
    $data['username'] = $_SESSION['username'];

    $result = $dbh->checkUserByUID($data['userid']);
    if ($result['status'] == true &
        $result['username'] == $data['username']){
        // $data['offect'] = 0;
        $result = $dbh->getPosts($data);
    }else{
        die(json_encode(array('status' => false,'msg' => 'Error: gp0003')));
    }

    $res = array('status' => $result['status'],
                'post_count' => $result['post_count'],
                'post_data' => $result['post_data'],
                'msg' => $result['msg']);

    echo json_encode($res);
?>

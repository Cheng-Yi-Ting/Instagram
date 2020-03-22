<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') die();
    session_start();
    $json = json_decode(file_get_contents('php://input'), TRUE);

    if (!isset($_SESSION['userid']) |
        !isset($_SESSION['username'])){
        die(json_encode(array('status' => false,'msg' => 'Error: ul0000')));
    }

    if (!isset($json['post_id']) |
        !isset($json['like'])){
        die(json_encode(array('status' => false,'msg' => 'Error: ul0001')));
    }


    require_once __DIR__.'/../db.php';
    $dbh = new DBHandle();
    $data['postid'] = (int)$json['post_id'];
    $data['userid'] = (int)$_SESSION['userid'];
    $data['like'] = ($json['like'] == 1)?1:0;

    $result = $dbh->checkUserByUID($data['userid']);
    if ($result['status'] == true &
        $result['username'] == $_SESSION['username']){
        $result = $dbh->updateLike($data);
    }else{
        die(json_encode(array('status' => false,'msg' => 'Error: ul0003')));
    }

     $res = array('status' => $result['status'],
                'msg' => $result['msg']);

    echo json_encode($res);
?>

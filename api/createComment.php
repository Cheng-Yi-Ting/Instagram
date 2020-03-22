<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') die();
    session_start();
    $json = json_decode(file_get_contents('php://input'), TRUE);

    if (!isset($_SESSION['userid']) |
        !isset($_SESSION['username'])){
        die(json_encode(array('status' => false,'msg' => 'Error: cc0000')));
    }

    if (!isset($json['post_id']) |
        !isset($json['content'])){
        die(json_encode(array('status' => false,'msg' => 'Error: cc0001')));
    }


    require_once __DIR__.'/../db.php';
    $dbh = new DBHandle();
    $comment_data['postid'] = (int)$json['post_id'];
    $comment_data['content'] = $json['content'];
    $comment_data['userid'] = $_SESSION['userid'];
    $comment_data['username'] = $_SESSION['username'];

    $result = $dbh->checkUserByUID($comment_data['userid']);
    if ($result['status'] == true &
        $result['username'] == $comment_data['username']){
        $result = $dbh->insertComment($comment_data);
    }else{
        die(json_encode(array('status' => false,'msg' => 'Error: cc0003')));
    }

     $res = array('status' => $result['status'],
                'name' => $result['name'],
                'content' => $result['content'],
                'date' => $result['date'],
                'msg' => $result['msg']);

    echo json_encode($res);
?>
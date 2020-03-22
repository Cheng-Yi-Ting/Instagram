<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') die();   //$_SERVER['REQUEST_METHOD'] 訪問頁面時的請求方法。例如：「GET」、「HEAD」，「POST」，「PUT」，可以知道表單傳送時是使用GET或是POST!!
    session_start();
    $json = json_decode(file_get_contents('php://input'), TRUE);

    if (!isset($_SESSION['userid']) |
        !isset($_SESSION['username'])){
        die(json_encode(array('status' => false,'msg' => 'Error: cp0000')));
    }

    if (!isset($json['img_filename']) |
        !isset($json['content'])){
        die(json_encode(array('status' => false,'msg' => 'Error: cp0001')));
    }

    
    $img_filename = pathinfo($json['img_filename'], PATHINFO_BASENAME);//PATHINFO_BASENAME:取得檔名(含副檔名)

    require_once __DIR__.'/../db.php';
    $dbh = new DBHandle();
    $post_data['userid'] = $_SESSION['userid'];
    $post_data['username'] = $_SESSION['username'];
    $post_data['img_filename'] = $img_filename;
    $post_data['content'] = $json['content'];

    $result = $dbh->checkUserByUID($post_data['userid']);
    if ($result['status'] == true &
        $result['username'] == $post_data['username']){
        $result = $dbh->insertPost($post_data);
    }else{
        die(json_encode(array('status' => false,'msg' => 'Error: cp0003')));
    }

     $res = array('status' => $result['status'],
                'msg' => $result['msg']);

    echo json_encode($res);
?>

<?php
// $_SERVER['REQUEST_METHOD'] #訪問頁面時的請求方法。例如：「GET」、「HEAD」，「POST」，「PUT」。
// die() 函數輸出一條消息，並退出當前腳本。
// session_start：啟用一個新的或開啟正在使用中的session。
// file_get_contents — Reads entire file into a string
// 使用 file_get_contents 接收 POST 的資料。
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') die();
    session_start();
    // TRUE:use_include_path
    $json = json_decode(file_get_contents('php://input'), TRUE);

    if (!isset($json['username']) |
        !isset($json['password'])){
        // die('Error: r0001');
        die();
    }
    // require_once:引入檔案，可避免重複引入，引不到檔案會出現錯誤息，而且程式會停止執行。
    require_once __DIR__.'/../db.php';
    $dbh = new DBHandle();
    $user_data['username'] = substr($json['username'],0 , 32);
    $user_data['password'] = $json['password'];

    if (strlen($user_data['username']) > 0 &
        strlen($user_data['password']) > 0){
        $result = $dbh->createUser($user_data);
    }else{
        die('Error: r0002');
    }

    if ($result['status']){
        // 設定了一個 userid和 username的變數，接著這兩個變數就存在伺服器上，基本上這樣最簡單的 session 就設定好了。
        $_SESSION['userid'] = $result['userid'];
        $_SESSION['username'] = $result['username'];
    }

     $res = array('status' => $result['status'],
                'msg' => $result['msg']);
    // json_encode — Returns the JSON representation of a value
    echo json_encode($res);
?>

<?php
// 取得src='./img/?n=$filename'/內的$filename
    if (!isset($_GET['n'])){
        die();
    }
    require_once __DIR__.'/../db.php';
    $dbh = new DBHandle();
    
    // 取得n後面的檔案名稱，
    // e.g:http://localhost/zyting/index.php?n=cb00ff552fa6a75cd8f60a4d83f89018.jpg
    // 取得cb00ff552fa6a75cd8f60a4d83f89018.jpg
    $filename = $_GET['n'];
    
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    // [a-z0-9]:含數字或小寫字母之字串，加號 + 所代表的是重複 1 次或以上。.:比對任何一個字元（但換行符號不算）。+:比對前一個字元一次或更多次，等效於 {1,}。[a-z]{3,}:至少3個小寫字母或以上
    // preg_match("/[a-z0-9]+.[a-z]{3,}/", $filename, $matches);//preg_match( 正則表達式 , 要比對的字串 , 比對結果)， 比對是否有符合 string pattern 條件的結果，array matches 是非必要項目，用來把比對的結果或值放入陣列中，如果沒有用到，可以不用寫沒關係
    // $filename = $matches[0];
  
    $l = strlen($filename);
    if (in_array($ext, $allowedExts) &&
        $l >= 36 && $l <= 37){ // 32 + 1 + 4 --> md5.jpeg
        $result = $dbh->getImage($filename);
        if ($result['status'] == true){
            // Content-Type:用於定義網絡文件的類型和網頁的編碼，決定瀏覽器將以什麼形式、什麼編碼讀取這個文件
            // // We'll be outputting a image
            header("Content-Type: image/png");
            echo $result['image'];

        }
    }
?>

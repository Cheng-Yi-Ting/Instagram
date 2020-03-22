<h1 class="upload_title">上傳圖片</h1>
<span class="upload_description">請選擇一張圖片，且檔案大小小於 85KB<br>僅接受檔名為「gif」、 「jpeg」、「jpg」、 「png」</span>
<form action="post.php" method="post" enctype="multipart/form-data">
<input type="file" name="file" id="file"/><br />
<div class="form-group row">
    <div class="col-sm-12">
        <span class="float-right">
            <input class="btn default_btn upload_btn" type="submit" name="submit" value="上傳" />
        </span>
    </div>
</div>
</form>

<!-- 這個網頁表單包含了一個上傳檔案用的 <input>（其 type 設定為 "file"），再加上一個送出的按鈕。網頁表單中如果包含檔案的上傳，就要把 enctype 設定為 "multipart/form-data"。 -->


<!-- PHP 怎麼判斷檔案的各種數據
$_FILES["file"]["name"]：上傳檔案的原始名稱。
$_FILES["file"]["type"]：上傳的檔案類型。
$_FILES["file"]["size"]：上傳的檔案原始大小。
$_FILES["file"]["tmp_name"]：上傳檔案後的暫存資料夾位置。
$_FILES["file"]["error"]：如果檔案上傳有錯誤，可以顯示錯誤代碼。 -->

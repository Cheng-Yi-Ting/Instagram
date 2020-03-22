# DEMO

![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/系統操作影片.gif)

# (a)架構及運作方式

### 檔案架構:

* [api/login.php] - 登入帳號使用

* [api/logout.php] - 登出帳號使用

* [api/register.php] - 註冊帳號使用

* [api/createPost.php] - 建立貼文使用

* [api/getPosts.php] - 取得貼文使用

* [api/updatePost.php] - 更新貼文使用

* [api/deletePost.php] - 刪除貼文使用

* [api/createComment.php] - 建立貼文回覆使用

* [api/updateLike.php] - 更新貼文愛心(點擊及收回愛心)

* [api/updateUser.php] - 更新使用者資料(變更密碼)

* [config.php] - 該專案定義常數，儲存資料庫帳號密碼

* [css/bootstrap.min.css] - 排版使用Bootstrap框架

* [css/font-awesome.min.css] - 使用雲端字型呈現圖示，如愛心

* [css/login_styles.css] - 登入頁面排版樣式

* [css/styles.css] - 專案通用樣式

* [db.php] - 資料庫相關處理函數實作

* [favicon.ico] - 網頁圖示

* [header.php] - 網頁最上方的導覽列

* [img/index.php] - 存取資料庫內圖片使用

* [img/ig.png] - 網頁Logo圖檔

* [index.php] - 網站首頁

* [intro.php] - 網站說明頁面

* [js/app.js] - 網頁通用使用JS

* [js/bootstrap.min.js] - Bootstrap框架使用JS

* [js/config.js] - 網站相關JS設定，如API Server Host

* [js/login_app.js] - 登入頁面使用JS

* [js/post_app.js] - 發布貼文頁面使用JS

* [js/update_user_app.js] - 修改使用者資料頁面使用JS

* [login.php] - 登入頁面

* [logout.php] - 登出處理

* [post.php] - 發布貼文頁面

* [readme/img/*.png] - Readme使用之操作圖片

* [update_post.php] - 更新貼文頁面

* [update_user.php] - 修改使用者資料頁面

* [upload_form.php] - 上傳圖片通用頁面

### 測試環境:

  使用瀏覽器及版本: Chrome ver.78.0.3904.70 (正式版本) beta (64 位元) (cohort: Beta)

### 功能說明:

1. 非同步資料請求: 拆分出後端API進行資料請求，回傳JSON資料後再由前端Javascript進行渲染。
2. 編輯已發布貼文: 已發布的貼文可進行編輯更新內文。
3. 編輯貼文標註: 若貼文的發布時間標記旁顯示最後編輯，則表示該貼文曾被編輯更新過。
4. 刪除貼文: 已發布的貼文進入編輯更新時，可選擇刪除該則貼文；於資料庫不刪除該筆紀錄僅標記不顯示，API請求時不會回傳標記不顯示之貼文。
5. 修改密碼: 使用者可點選修改個人資料頁面進行密碼的變更。

# (b)資料庫設計

## 1. 使用者資料表

使用者註冊的帳號會記錄於此資料表中，登入時也使用此資料表內容進行驗證。

### users - 資料表設計如下

| 欄位       | 說明                                     |
| ---------- | ---------------------------------------- |
| id         | 作為使用者流水號user_id使用，PRIMARY KEY |
| username   | 使用者帳號                               |
| password   | 使用者密碼，使用SHA256 Hash              |
| created_at | 使用者建立時間                           |

## 2. 貼文資料表

使用者所發布貼文將記錄於此資料表。

### posts - 資料表設計如下

| 欄位         | 說明                                     |
| ------------ | ---------------------------------------- |
| id           | 作為貼文流水號post_id使用，PRIMARY KEY   |
| author_id    | 此貼文的作者，對應user_id，外鍵users(id) |
| author_name  | 此貼文的作者名稱                         |
| img_filename | 此貼文使用圖片                           |
| content      | 此貼文的內文                             |
| likes        | 此貼文的愛心數量                         |
| visible      | 此貼文是否可見，用以表示貼文是否刪除     |
| updated_at   | 貼文更新時間                             |
| created_at   | 貼文建立時間                             |

## 3. 回覆資料表

使用者所針對貼文所進行的回覆將記錄於此資料表。

### comments - 資料表設計如下

| 欄位        | 說明                                           |
| ----------- | ---------------------------------------------- |
| id          | 作為回覆流水號，PRIMARY KEY                    |
| post_id     | 此回覆屬於哪篇貼文，對應post_id，外鍵posts(id) |
| author_id   | 此回覆的作者，對應user_id                      |
| author_name | 此回覆的作者名稱                               |
| content     | 此回覆的內文                                   |
| visible     | 此回覆是否可見，用以表示回覆是否刪除           |
| updated_at  | 回覆更新時間                                   |
| created_at  | 回覆建立時間                                   |

## 4. 愛心資料表

使用者所針對貼文所進行的愛心將記錄於此資料表。

### likes - 資料表設計如下

| 欄位       | 說明                                                               |
| ---------- | ------------------------------------------------------------------ |
| id         | 作為愛心紀錄流水號，PRIMARY KEY                                    |
| post_id    | 此愛心屬於哪篇貼文，對應user_id，外鍵posts(id)                     |
| author_id  | 此愛心的發送作者，對應user_id                                      |
| is_like    | 此紀錄作者對該貼文是否有發送愛心，(1表示發送愛心，0表示已收回愛心) |
| updated_at | 回覆更新時間                                                       |
| created_at | 回覆建立時間                                                       |

## 5. 圖片資料表

使用者所上傳圖片將保存於此資料表。

### images - 資料表設計如下

| 欄位       | 說明                        |
| ---------- | --------------------------- |
| id         | 作為圖片流水號，PRIMARY KEY |
| name       | 表示圖片名稱，使用MD5 Hash  |
| data       | 圖片內容                    |
| created_at | 圖片建立時間                |

(c)網站URL
=============

* 首頁
* 登入及註冊頁
* 發布貼文
* 修改個人資料
* 網站說明

(d)進階功能
=============

### 1. 非同步資料請求

拆分出後端API進行資料請求，回傳JSON資料後再由前端進行渲染。

### 2. 編輯已發布貼文

已發布的貼文可進行編輯更新內文。

### 3. 編輯貼文標註

若貼文的發布時間標記旁顯示「最後編輯」，則表示該貼文曾被編輯更新過。

### 4. 刪除貼文

已發布的貼文進入編輯更新時，可選擇刪除該則貼文；於資料庫不刪除該筆紀錄僅標記不顯示，API請求時不會回傳標記不顯示之貼文。

### 5. 修改密碼

使用者可點選修改個人資料頁面進行密碼的變更。

(e)補充 - 操作流程說明及圖片
=============

### 登入頁面

使用帳號密碼進行登入，登入完成後會自動進行重導向。

<!-- ![](img\1.PNG) -->

![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/1.PNG)

#### 登入頁面 - 自動填入使用者帳號

若曾經成功登入過則會將使用者帳號存於Cookie中，於下次進入登入頁時自動填入Username欄位。

-------------------

### 註冊頁面

點擊畫面中「Register」切換標籤頁，即可建立使用者帳號密碼。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/2.PNG)

#### 註冊頁面 - 警告訊息

如下圖若註冊時兩次密碼不相等，會顯示警告訊息。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/3.PNG)

#### 註冊頁面 - 錯誤訊息

如下圖若註冊時使用者已存在，會顯示錯誤訊息。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/5.PNG)

#### 註冊頁面 - 成功訊息

如下圖若註冊成功後會出現成功訊息並自動進行重導向。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/4.PNG)

-------------------

### 首頁

若登入成功後即可訪問首頁，並顯示所有人的貼文資料，畫面右上角有5個圖示，由左而右功能分別為「首頁」、「發布貼文」、「修改個人資料」、「網站說明」、「登出」。

#### 首頁 - 查看其他貼文

將網頁往下捲動即可查看其他貼文，由上而下表示發布貼文的時間由新到舊。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/6.PNG)

#### 首頁 - 最後編輯標註

若下圖紅色框起部分，若發布的時間戳記前面多了「最後編輯」表示該則貼文曾被重新編輯過。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/26.PNG)

#### 首頁 - 發送愛心

於貼文圖片的下方有一個愛心按鈕，點擊即可進行發送及收回愛心。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/7.PNG)

#### 首頁 - 發送回覆

於貼文下方有一留言框，進行文字輸入並點及右方「回覆」即可進行貼文的回覆，由上而下表示發送回覆的時間由舊到新。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/8.PNG)

-------------------

### 發布貼文 - 上傳圖片

發布貼文時第一步需先上傳圖片，於此有特別限制檔案名稱及檔案大小。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/9.PNG)

#### 發布貼文 - 上傳圖片(不允許的檔案類型)

上傳圖片時有特別限制檔案名稱，僅允許副檔名為「gif」、「jpeg」、「jpg」、「png」其一，若不符合則會出現下圖錯誤訊息。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/10.PNG)

#### 發布貼文 - 上傳圖片(檔案大小限制)

上傳圖片時有限制檔案大小，不得超過85KB，若不符合則會出現下圖錯誤訊息。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/11.PNG)

#### 發布貼文 - 上傳圖片(上傳成功)

上傳圖片成功後會導向下一步驟如下圖。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/12.PNG)

#### 發布貼文 - 準備發布

準備發布的步驟如下圖，輸入好欲發布的貼文內容於「編輯內文」下的文字區塊後，點選「發布」按鈕即可發布貼文，發布成功會出現成功訊息並進行自動重新導向。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/13.PNG)

#### 發布貼文 - 查看貼文

貼文發布成功後會自動重新導會首頁，即可查看剛剛發布的貼文如下圖。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/14.PNG)

-------------------

#### 編輯貼文 - 可編輯之貼文

若貼文為目前登入帳號所發布，則在貼文圖片下方的右側有一圖示，如下圖紅色框起處，點擊後即可進行編輯貼文。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/24.PNG)

#### 編輯貼文 - 更新內文

點擊編輯貼文圖示後即會導向如下圖頁面，即可進行內文的更新。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/15.PNG)

#### 編輯貼文 - 更新內文(更新成功)

貼文的新內文輸入完畢後，點即「更新」按鈕即可進行內文更新，成功後會出現成功訊息，並進行自動重新導向。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/16.PNG)

#### 編輯貼文 - 刪除貼文

於編輯貼文的頁面也可以選擇點擊「刪除」按鈕進行刪除貼文，點擊後會出現確認視窗。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/17.PNG)

#### 編輯貼文 - 刪除貼文(刪除成功)

貼文確認刪除後且成功刪除後即會出現成功訊息，並自動重新導向。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/18.PNG)

-------------------

### 修改個人資料

於此頁可以進行個人帳號的密碼修改。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/19.PNG)

#### 修改個人資料 - 修改密碼(警告訊息)

如下圖若修改密碼時時新的密碼兩欄不相等，會顯示警告訊息。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/20.PNG)

#### 修改個人資料 - 修改密碼(錯誤訊息)

如下圖若修改密碼時失敗，如舊密碼錯誤，會顯示錯誤訊息。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/21.PNG)

#### 修改個人資料 - 修改密碼(成功訊息)

如下圖若修改個人資料成功後會出現成功訊息。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/22.PNG)

-------------------

### 說明頁面

此頁為網站說明頁面。
![image](https://github.com/Cheng-Yi-Ting/Instagram/blob/master/readme/img/27.PNG)

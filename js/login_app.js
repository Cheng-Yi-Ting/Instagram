//jquery  $(function ()文檔載入就執行
$(function() {
    // 點擊Login標籤
    $('#login-form-link').click(function(e) {
        // 假如該元素是隱藏的，fadeIn() 方法使用淡入效果來顯示被選元素
        $("#login-form").delay(100).fadeIn(100); //顯示登入表單Username和Password欄位
        $("#register-form").fadeOut(100); //隱藏註冊表表單Username,Password和Confirm Password欄位
        $('#register-form-link').removeClass('active');
        $(this).addClass('active');
        e.preventDefault();
    });
    // 點擊register標籤
    $('#register-form-link').click(function(e) {
        $("#register-form").delay(100).fadeIn(100); //顯示註冊表單Username,Password和Confirm Password欄位
        $("#login-form").fadeOut(100); //隱藏登入表單Username和Password欄位
        $('#login-form-link').removeClass('active');
        $(this).addClass('active');
        e.preventDefault();
    });
    // 幫使用者自動填入上次註冊username
    // $("#login-form #username").val(getUsernameByCookie());
    // login form & register form 輸入enter送出表單
    $("#login-form #password").keypress(function(e) {
        // code = (e.keyCode ? e.keyCode : e.which);
        code = e.which || e.keyCode; // Use either which or keyCode, depending on browser support
        if (code == 13) $("#login-submit").click(); //按下enter=送出
    });
    $("#register-form #confirm-password").keypress(function(e) {
        code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) $("#register-submit").click(); //按下enter=送出
    });
});

function alertMsg(text, type = 'info') {
    var alert = document.querySelector('#alertMsg');
    if (alert) alert.remove() //刪除已經出現在DOM上的提示訊息

    var el = document.querySelector('.container');
    var alertElm = document.createElement("div");
    alertElm.id = 'alertMsg'; //div id
    alertElm.innerHTML = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">\
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">\
								<span aria-hidden="true">&times;</span>\
								<span class="sr-only">Close</span>\
							</button>\
							<span class="msg"></span>\
						  </div>';
    alertElm.querySelector('.msg').innerText = text; //選取msg id 內文為提示訊息
    el.appendChild(alertElm);
}

function login() {
    var from = document.querySelector('#login-form');
    var username = from['username'].value.substring(0, 32);

    if (username.length <= 0 ||
        from['password'].value.length <= 0) {
        alertMsg('請確認帳號或密碼是否遺漏', 'warning');
        return false;
    }
    // fetch()方法是一個位於全域window物件的方法，它會被用來執行送出Request(要求)的工作，fetch() 函式會回傳一個 Promise，並在解析/完成 (resolve) 後，回傳 Response 物件，因此，能直接以 .then(onFulfilled, onRejected) 串接解析 完成 或 拒絕 的回調函式，且能使用 Response 物件 提供的 json() 方法，將回應解析為 JSON 物件
    fetch(API_SERVER_HOST + 'login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json; charset=utf-8'
        },
        // JSON.stringify():從物件建立JSON字串
        body: JSON.stringify({
            'username': username,
            'password': from['password'].value
        }),
        credentials: 'same-origin'
    }).then(data => data.json()).then(data => {
        if (data.status === true) {
            document.cookie = "username=" + username;

            alertMsg(data.msg, 'success');
            window.location.replace(SERVER_HOST);
        } else alertMsg(data.msg, 'danger');
    })

    return false;
}

// 註冊按鈕
function register() {
    var from = document.querySelector('#register-form'); //獲得註冊表單
    var username = from['username'].value.substring(0, 32); //Username前32位字元

    // Username,Password,Confirm-password長度不得<=0
    if (username.length <= 0 ||
        from['password'].value.length <= 0 ||
        from['confirm-password'].value.length <= 0) {
        alertMsg('請確認填寫資料是否遺漏', 'warning');
        return false;
    }

    // Password,Confirm-password需一致
    if (from['password'].value !== from['confirm-password'].value) {
        alertMsg('請確認兩次密碼輸入相等', 'warning');
        return false;
    }

    // contentType is the type of data you're sending, so application/json; charset=utf-8 is a common one
    // The JSON.stringify() method converts a JavaScript value to a JSON string
    // same-origin:同源是指兩份網頁具備相同協定、埠號 (如果有指定) 以及主機位置
    fetch(API_SERVER_HOST + 'register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            'username': username,
            'password': from['password'].value
        }),
        credentials: 'same-origin'
    }).then(data => data.json()).then(data => {
        if (data.status === true) {
            // Create a Cookie with JavaScript，JavaScript can create, read, and delete cookies with the document.cookie property.
            document.cookie = "username=" + username;

            alertMsg(data.msg, 'success');
            window.location.replace(SERVER_HOST);
        } else alertMsg(data.msg, 'danger');
    })
}

// function getCookie(name) {
//     // console.log(name)
//     // value : ; username=sadasd; PHPSESSID=4u4o6pnr70o021aq7d7hqqav87
//     var value = "; " + document.cookie;
//     var parts = value.split("; " + name + "=");
//     var a = parts.pop().split(";").shift();
//     if (parts.length == 2) return parts.pop().split(";").shift(); //把"; username="刪除，留下username
// }

// function getUsernameByCookie() {
//     var username = getCookie('username');
//     console.log(username);
//     if (username !== undefined)
//         return username.substring(0, 32);
//     else
//         return '';
// }
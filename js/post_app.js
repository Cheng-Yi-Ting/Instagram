function alertMsg(text, type = 'info') {
    var alert = document.querySelector('#alertMsg');
    if (alert) alert.remove()

    var el = document.querySelector('.upload_container');
    var alertElm = document.createElement("div");
    alertElm.id = 'alertMsg';
    alertElm.innerHTML = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">\
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">\
								<span aria-hidden="true">&times;</span>\
								<span class="sr-only">Close</span>\
							</button>\
							<span class="msg"></span>\
						  </div>';
    alertElm.querySelector('.msg').innerText = text;
    el.appendChild(alertElm);
}

// PO文
function post() {
    var img_name = document.querySelector('#img_name').value; //圖片檔名
    var post_content = document.querySelector('#post_content').value; //編輯內文
    fetch(API_SERVER_HOST + 'createPost.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json; charset=utf-8'
        },
        body: JSON.stringify({
            'img_filename': img_name,
            'content': post_content
        }),
        credentials: 'same-origin'
    }).then(data => data.json()).then(data => {
        if (data.status === true) {
            alertMsg(data.msg, 'success');
            window.location.replace(SERVER_HOST);
        } else alertMsg(data.msg, 'danger');
    })
}
// 更新貼文
function updatePost(post_id) {
    var post_content = document.querySelector('#post_content').value;
    fetch(API_SERVER_HOST + 'updatePost.php', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json; charset=utf-8'
        },
        body: JSON.stringify({
            'post_id': post_id,
            'content': post_content
        }),
        credentials: 'same-origin'
    }).then(data => data.json()).then(data => {
        if (data.status === true) {
            alertMsg(data.msg, 'success');
            window.location.replace(SERVER_HOST);
        } else alertMsg(data.msg, 'danger');
    })
}

// 刪除貼文
function deletePost(post_id) {
    var post_content = document.querySelector('#post_content').value;
    fetch(API_SERVER_HOST + 'deletePost.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json; charset=utf-8'
        },
        body: JSON.stringify({
            'post_id': post_id
        }),
        credentials: 'same-origin'
    }).then(data => data.json()).then(data => {
        if (data.status === true) {
            alertMsg(data.msg, 'success');
            window.location.replace(SERVER_HOST);
        } else alertMsg(data.msg, 'danger');
    })
}
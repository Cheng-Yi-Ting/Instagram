function alertMsg(text, type='info'){
	var alert = document.querySelector('#alertMsg');
	if (alert) alert.remove()

	var el = document.querySelector('.update_container');
	var alertElm = document.createElement("div");
	alertElm.id = 'alertMsg';
	alertElm.innerHTML = '<div class="alert alert-'+ type +' alert-dismissible fade show" role="alert">\
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">\
								<span aria-hidden="true">&times;</span>\
								<span class="sr-only">Close</span>\
							</button>\
							<span class="msg"></span>\
						  </div>';
	alertElm.querySelector('.msg').innerText = text;
	el.appendChild(alertElm);
}

function updateUser(form){
    var old_password = form['old-password'].value;
    var new_password = form['new-password'].value;
    var confirm_password = form['confirm-password'].value;

	if (old_password.length <= 0 ||
		new_password.length <= 0 ||
		confirm_password.length <= 0){
		alertMsg('請確認填寫資料是否遺漏', 'warning');
		return false;
	}

	if (new_password !== confirm_password){
		alertMsg('請確認兩次新密碼輸入相等', 'warning');
		return false;
	}

	if (old_password === new_password){
		alertMsg('新密碼應與舊密碼不同', 'warning');
		return false;
	}

    fetch(API_SERVER_HOST + 'updateUser.php', {
		method: 'PUT',
		headers: {
			'Content-Type': 'application/json; charset=utf-8'
		},
		body: JSON.stringify({
			'old_password': old_password,
            'new_password': new_password
		}),
		credentials: 'same-origin'
	}).then(data => data.json()).then(data => {
		if (data.status === true) {
			alertMsg(data.msg, 'success');
			form.reset();
		}else alertMsg(data.msg, 'danger');
	})

    return false;
}

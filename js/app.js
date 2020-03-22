// 取得所有貼文和留言
function getPosts() {
    fetch(API_SERVER_HOST + 'getPosts.php', {
        method: 'GET',
        credentials: 'same-origin'
    }).then(data => data.json()).then(data => {
        if (data.status === true) {
            // console.log(data);
            appendPosts(data.post_data);
        }
    })
}
// 送出愛心
function sendLike(el, post_id) {
    var like = 0;
    var likeElm = el.querySelector("i"); //<i class="fa fa-heart-o" aria-hidden="true"></i>
    var likeCountElm = el.nextElementSibling; //<span>0</span>
    // 該篇文章未被該使用者點過愛心，空心
    if (likeElm.classList.contains('fa-heart-o')) { //空心
        like = 1;
        likeElm.classList.add('fa-heart'); //增加紅心
        likeElm.classList.remove('fa-heart-o'); //移除空心
        likeCountElm.innerText = parseInt(likeCountElm.innerText) + 1; //<span>1</span>
        // 該篇文章已被該使用者點過愛心，愛心
    } else {
        likeElm.classList.add('fa-heart-o'); //增加空心
        likeElm.classList.remove('fa-heart'); //移除紅心
        var c = parseInt(likeCountElm.innerText) - 1;
        likeCountElm.innerText = (c < 0) ? 0 : c;
    }

    fetch(API_SERVER_HOST + 'updateLike.php', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json; charset=utf-8'
        },
        body: JSON.stringify({
            'post_id': post_id,
            'like': like
        }),
        credentials: 'same-origin'
    }).then(data => data.json()).then(data => {
        // console.log('123');
        // if (data.status === false) {
        //     if (like === 1) {
        //         likeElm.classList.add('fa-heart-o');
        //         likeElm.classList.remove('fa-heart');
        //         var c = parseInt(likeCountElm.innerText) - 1;
        //         likeCountElm.innerText = (c < 0) ? 0 : c;
        //     } else {
        //         likeElm.classList.add('fa-heart');
        //         likeElm.classList.remove('fa-heart-o');
        //         likeCountElm.innerText = parseInt(likeCountElm.innerText) + 1;
        //     }
        // }
    });
    // console.log('456');
}
// 留言
function sendComment(form) {
    // console.log(form);
    var post_id = form.post_id.value;
    var content = form.comment_text.value;
    form.submit.disabled = true;
    fetch(API_SERVER_HOST + 'createComment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json; charset=utf-8'
        },
        body: JSON.stringify({
            'post_id': post_id,
            'content': content
        }),
        credentials: 'same-origin'
    }).then(data => data.json()).then(data => {
        // console.log(data);
        if (data.status === true) {
            appendComment(form.previousElementSibling, { //取得最後一個class=post_comments的元素，，HTML DOM element.previousElementSibling属性; 返回指定元素的前一个兄弟元素
                name: data.name,
                content: data.content,
                date: data.date
            });
            form.comment_text.value = ""; //把剛才送出的留言清空
        }
        form.submit.disabled = false;
    })

    return false;
}

// 編輯貼文，傳入貼文id，使用form表單submit
function editPost(post_id) {
    var form = document.createElement("form");
    form.setAttribute("method", 'POST');
    form.setAttribute("action", './update_post.php');

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", 'hidden');
    hiddenField.setAttribute("name", 'post_id');
    hiddenField.setAttribute("value", post_id);
    form.appendChild(hiddenField);
    document.body.appendChild(form);
    form.submit(); //透過 post 的方法將  傳遞給 /update_post 這支 PHP 程式。
    document.body.removeChild(form);
    // console.log(form);

}

// 顯示貼文和留言
function appendPosts(posts) {
    var container = document.querySelector("#post-container");
    for (post of posts) {
        var postElm = document.createElement("article");
        postElm.classList.add("post");
        postElm.classList.add("col-lg-8");
        // post.img = /[a-z0-9]+\.[a-z]{3,}/.exec(post.img)[0];
        // post.img = post.img;
        // post.id = parseInt(post.id);
        // console.log(Date.parse(post.last_date).valueOf());
        // console.log(post.last_date)
        // console.log(Date.parse(post.date).valueOf()); //1572075734000
        // console.log(post.date) //2019-10-26 15:42:14
        // PO文有被更新過:last=True,不然last=false
        // console.log(post.last_date);
        // console.log(Date.parse(post.last_date));
        // console.log(Date.parse(post.last_date).valueOf());
        // 把 2020-03-22 00:37:23 =>1584808643000
        // 確認文章是否被編輯過
        var last = Date.parse(post.last_date).valueOf() > Date.parse(post.date).valueOf();
        postElm.innerHTML = '<div>\
                                <img class="post_img col-12" src="./img/?n=' + post.img + '">\
                            </div>\
                            <div class="post_like">\
                                <span class="like_count fa"><a class="btn_like" onclick="sendLike(this, ' + post.id + ')"><i class="fa fa-heart' + ((post.islike == 1) ? '' : '-o') + '" aria-hidden="true"></i></a><span></span> likes</span>\
                                ' + ((post.owner === true) ? ('<span class="post_edit fa"><a class="btn_edit" onclick="editPost(' + post.id + ')"><i class="fa fa-edit fa-2x" aria-hidden="true"></i></a></span>') : '') + '\
                            </div>\
                            <div class="post_comments">\
                                <ul>\
                                    <li class="post_author"><div><span class="u_name"></span><span class="post_content"></span></div>' + ((last) ? '<span class="post_last">最後編輯</span>' : '') + '<i class="fa fa-clock-o" aria-hidden="true"></i><span class="post_date"></span></li>\
                                </ul>\
                                <form class="post_comment_form" onsubmit="return sendComment(this)">\
                                    <input type="hidden" name="post_id" value="' + post.id + '">\
                                    <input type="text" class="post_comment_text" name="comment_text" aria-label="留言⋯⋯" placeholder="留言⋯⋯" value="">\
                                    <input type="submit" class="post_comment_btn btn" name="submit" value="回覆">\
                                </form>\
                            </div>';
        postElm.querySelector('.like_count span').innerText = post.likes; //愛心數量
        postElm.querySelector('.u_name').innerText = post.name;
        postElm.querySelector('.post_date').innerText = (last) ? post.last_date : post.date;
        postElm.querySelector('.post_content').innerText = post.content;

        // 文章留言
        var commentsElm = postElm.querySelector('.post_comments ul');
        // console.log(commentsElm);
        for (comment of post.comments) {
            appendComment(commentsElm, comment);
        }
        // console.log(postElm);
        container.appendChild(postElm);
    }
}

// 顯示留言
function appendComment(commentsElm, comment_data) {
    // console.log(commentsElm)
    // console.log(comment_data)
    var commentElm = document.createElement("li");
    commentElm.classList.add("post_comment");
    commentElm.innerHTML = '<div><span class="u_name"></span><span class="comment_content"></span></div><i class="fa fa-clock-o" aria-hidden="true"></i><span class="comment_date"></span>';
    commentElm.querySelector('.u_name').innerText = comment_data.name;
    commentElm.querySelector('.comment_date').innerText = comment_data.date;
    commentElm.querySelector('.comment_content').innerText = comment_data.content;

    commentsElm.appendChild(commentElm);
}
// 註冊在這裡面的事件都會等到整個視窗裡所有資源都已經全部下載後才會執行
window.onload = getPosts;
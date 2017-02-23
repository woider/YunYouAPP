mui.ready(function () {

    var list = document.getElementsByClassName("yunyou-discuss-list")[0];
    var item = document.getElementById("yunyou-discuss-item-example");

    var text = document.getElementById("yunyou-input-comment");
    var send = document.getElementById("yunyou-btn-comment");

    var p_sum = document.querySelector(".yunyou-discuss > p");

    var btn_ops = document.getElementsByClassName("yunyou-assess-options")[0];
    var btn_zan = document.getElementById("yunyou-btn-zan");
    var btn_cai = document.getElementById("yunyou-btn-cai");

    var image_size = parseInt((screen.width - 60) / 5);
    var review_id = window.location.pathname.replace(/[a-zA-Z\/]/g, "");
    var comm_page = 0;

    ajax_load_comment();

    /* 调整图片大小 */
    mui(".yunyou-image-pack").each(function (index, item) {
        item.style.width = image_size + "px";
        item.style.height = image_size + "px";
        var img = item.getElementsByTagName("img")[0];
        img.onload = function () {
            if (this.width > this.height) {
                this.style.height = "100%";
                this.style.marginlef = -(this.width - image_size) / 2 + "px";
            } else {
                this.style.width = "100%";
                this.style.marginTop = -(this.height - image_size) / 2 + "px";
            }
        }
    });

    /* 滚动到底部 */
    window.onscroll = function () {
        if ((document.documentElement.scrollHeight) ==
            (document.documentElement.scrollTop | document.body.scrollTop) +
            document.documentElement.clientHeight) {
            if (comm_page != null) {
                comm_page++;
                ajax_load_comment();
            }
        }
    };

    /* 评论表单 */
    mui(".yunyou-comment").on("input", "#yunyou-input-comment", function () {
        var count = this.value.replace(/\s+/g, " ").length;
        if (count > 10 && count < 200) {
            send.style.background = "#3598DB";
        } else {
            send.removeAttribute("style");
        }
    }).on("tap", "#yunyou-btn-comment", function () {
        var content = text.value.replace(/\s+/g, " ");
        if (content.length > 10 && content.length < 200) {
            mui.ajax("/discuss/submit", {
                data: {
                    "content": content,
                    "review_id": review_id
                },
                dataType: 'json',
                type: 'post',
                success: function (data) {
                    if (data == null) {
                        mui.openWindow("/login");
                    } else if (data.status) {
                        var item = create_comment_node(data.result);
                        list.insertBefore(item, list.firstChild);
                        var count = p_sum.innerHTML.match(/\d+/)[0];//更新评价总数
                        p_sum.innerHTML = "评论（" + (parseInt(count) + 1) + "）";
                        text.value = null; // 清空评论
                    } else {
                        mui.toast("评论失败");
                    }
                }
            });
        }
    });


    /* 文章评价 */
    mui(".yunyou-assess-options").on("tap", "button", function () {
        var attitude, em_act;
        var btn_node = this;
        var btn_em = btn_node.getElementsByTagName("em")[0];
        var btn_act = btn_ops.getElementsByClassName("yunyou-active")[0];
        if (btn_node === btn_zan) {
            attitude = 1;
        } else {
            attitude = -1;
        }
        if (btn_node === btn_act) {
            attitude = 0;
        }
        if (btn_act != null) {
            em_act = btn_act.getElementsByTagName("em")[0];
        }
        mui.ajax("/discuss/opinion", {
            data: {
                "review_id": review_id,
                "attitude": attitude
            },
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data == null) {
                    mui.toast("亲~请先登录");
                } else if (data.status) {
                    if (em_act != null) {
                        em_act.innerHTML = parseInt(em_act.innerHTML) - 1;
                        btn_act.removeAttribute("class");
                    }
                    if (Math.abs(data.result.attitude)) {
                        btn_em.innerHTML = parseInt(btn_em.innerHTML) + 1;
                        btn_node.setAttribute("class", "yunyou-active");
                    }
                }
            }
        })

    });

    /* 顶部链接 */
    mui(".yunyou-content").on("tap", ".yunyou-content-header>h3", function () {
        var href = this.getAttribute("data-href");
        mui.openWindow(href);
    }).on("tap", ".yunyou-assess-header>img", function () {
        var href = this.getAttribute("data-href");
        mui.openWindow(href);
    });

    /* 列表点击事件 */
    mui(".yunyou-discuss").on("tap", ".yunyou-discuss-item", function (e) {
        var ele = e.target;
        /* 头像链接 */
        if (ele.nodeName == "IMG") {
            var href = ele.getAttribute("data-href");
            mui.openWindow(href);
        }
        /* 点赞按钮 */
        if (ele.nodeName == "BUTTON") {
            var btn = ele;
        }
        if (ele.nodeName == "SPAN" || ele.nodeName == "EM") {
            var btn = ele.parentNode;
        }
        if (btn != undefined && btn.className != "yunyou-active") {
            mui.ajax("/discuss/approve", {
                data: {"comment_id": btn.getAttribute("data-id")},
                dataType: 'json',
                type: 'post',
                success: function (data) {
                    if (data && data.status) {
                        var num = btn.getElementsByTagName("em")[0];
                        num.innerHTML = parseInt(num.innerHTML) + 1;
                        btn.className = "yunyou-active";
                    }
                }
            });
        }
    });


    /* 请求评价数据 */
    function ajax_load_comment() {
        mui.ajax("/discuss/comment", {
            data: {
                "review_id": review_id,
                "comm_page": comm_page
            },
            dataType: 'json',
            type: 'get',
            success: function (data) {
                if (data) {
                    load_comment_list(data);
                }
                if (!data || data.length < 5) {
                    comm_page = null;
                }
            }
        });
    }

    /* 创建评论节点 */
    function create_comment_node(data) {
        var temp = item.cloneNode(true);
        temp.removeAttribute("id");
        temp.removeAttribute("style");
        var profile = temp.getElementsByTagName("img")[0];
        profile.setAttribute("src", data['profile']);
        profile.setAttribute("data-href", "/home/" + data['user_id']);
        var content = temp.getElementsByClassName("yunyou-discuss-content")[0];
        content.getElementsByTagName("h5")[0].innerHTML = data['nickname'];
        var like = content.getElementsByTagName("button")[0];
        like.setAttribute("data-id", data['comment_id']);
        if (approve_init.match(new RegExp(data['comment_id']))) like.className = "yunyou-active";
        content.getElementsByTagName("em")[0].innerHTML = data['approve'];
        var divcom = document.createElement("div");
        divcom.innerHTML = data['content'];
        content.appendChild(divcom);
        return temp;
    }

    /* 填充评论列表 */
    function load_comment_list(data) {
        for (var i = 0; i < data.length; i++) {
            var temp = create_comment_node(data[i]);
            list.appendChild(temp);
        }
    }


    mui.previewImage(); //图片预览插件
});
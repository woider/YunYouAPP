mui.ready(function () {
    var assess_list = document.getElementsByClassName("yunyou-assess-list")[0];
    var assess_item = document.getElementById("yunyou-assess-item-example");
    var discard_list = document.getElementsByClassName("yunyou-discard-list")[0];

    var btn_recommend = document.getElementById("yunyou-btn-recommend");
    var icon_arrow = document.querySelector("#yunyou-tap-fold .mui-icon");
    var btn_more = document.getElementById("yunyou-btn-more");
    var btn_fold = document.getElementById("yunyou-tap-fold");

    var scenery_id = window.location.pathname.replace(/[a-zA-Z\/]/g, "");//景点ID
    var order = "time", page = 0; // 默认加载最新评价

    /* 加载评价列表 */
    ajax_load_review();
    page++;
    /* 加载折叠评价 */
    mui.ajax("/detail/reviews", {
        data: {
            "scenery_id": scenery_id,
            "order": "drop",
            "page": 0
        },
        dataType: 'json',
        type: 'get',
        success: function (data) {
            if (data) {
                load_list(discard_list, data);
                btn_fold.innerHTML = "查看被折叠的评价 （" + data.length + "）";
                btn_fold.innerHTML += "<span class=\"mui-icon mui-icon-arrowright\"></span>";
            }
        }
    });


    /* 展开折叠评价 */
    mui(".yunyou-discard").on("tap", "#yunyou-tap-fold", function () {
        if (discard_list.style.display == "none") {
            discard_list.style.display = "block";
            icon_arrow.className = "mui-icon mui-icon-arrowdown";
        } else {
            discard_list.style.display = "none";
            icon_arrow.className = "mui-icon mui-icon-arrowright";
        }
    });

    /* 推荐评价按钮 */
    mui(".yunyou-scenic-options").on("tap", "#yunyou-btn-recommend", function () {
        mui.ajax("/detail/commend", {
            data: {"scenery_id": scenery_id},
            dataType: 'json',
            type: 'post',
            success: function (data) {
                if (data && data.status) {
                    btn_recommend.innerHTML = "已推荐";
                    btn_recommend.setAttribute("disabled", null);
                } else {
                    mui.toast(data.message);
                }
            }
        });
    }).on("tap", "#yunyou-btn-reedit", function () {
        mui.openWindow("/scenic/" + scenery_id);
    }).on("tap", "#yunyou-btn-assess", function () {
        mui.openWindow("/assess/" + scenery_id);
    });

    /* 跳转链接区域 */
    mui(".yunyou-content").on("tap", ".yunyou-assess-list", list_click)
        .on("tap", ".yunyou-discard-list", list_click);

    /* 列表点击事件 */
    function list_click(e) {
        var node = e.target.nodeName;
        if (node == "IMG" || node == "DIV") {
            var href = e.target.getAttribute("data-href");
            if (href != null) {
                mui.openWindow(href);
            }
        }
    }


    /* 加载更多按钮 */
    mui(".yunyou-assess-footer").on("tap", "#yunyou-btn-more", function () {
        ajax_load_review();
        page++;
    });

    /* 切换列表 */
    mui(".yunyou-assess-header").on("tap", "a", function () {
        if (this.className == "yunyou-active") return false;
        mui(".yunyou-assess-header .yunyou-active")[0].removeAttribute("class");
        this.setAttribute("class", "yunyou-active");
        order = this.getAttribute("data-order"), page = 0;
        remove_list_item();
        ajax_load_review();
        page++;
    });

    function ajax_load_review() {
        mui.ajax("/detail/reviews", {
            data: {
                "scenery_id": scenery_id,
                "order": order,
                "page": page
            },
            dataType: 'json',
            type: 'get',
            success: function (data) {
                if (data) {
                    load_list(assess_list, data);
                }
                if (!data || data.length < 5) {
                    btn_more.style.display = "none";
                }
            }
        });
    }

    /* 加载评论 */
    function load_list(list, data) {
        for (var i = 0; i < data.length; i++) {
            if (list === assess_list) {
                var arr = data[i]['message'].split(" · ");
                if (parseInt(arr[0]) - parseInt(arr[1]) < 0) {
                    continue; // 过滤折叠的评论
                }
            }
            var temp = assess_item.cloneNode(true);
            temp.removeAttribute("id");
            temp.removeAttribute("style");
            var header = temp.getElementsByClassName("yunyou-assess-item-header")[0];
            header.getElementsByTagName("img")[0].setAttribute("data-href", "/home/" + data[i]['user_id']);
            header.getElementsByTagName("img")[0].setAttribute("src", data[i]['profile']);
            header.getElementsByTagName("h4")[0].innerHTML = data[i]['nickname'];
            header.getElementsByTagName("p")[0].innerHTML = data[i]['date'];
            header.appendChild(create_star_node(data[i]['grade']));
            var content = temp.getElementsByClassName("yunyou-assess-item-content")[0];
            content.getElementsByTagName("div")[0].setAttribute("data-href", "/discuss/" + data[i]['review_id']);
            content.getElementsByTagName("div")[0].innerHTML = data[i]['content'];
            content.getElementsByTagName("p")[0].innerHTML = data[i]['message'];
            list.appendChild(temp);
        }
    }

    /* 创建评分节点 */
    function create_star_node(grade) {
        var star_node = document.createElement("div");
        star_node.className = "yunyou-assess-star";
        var star_span = document.createElement("span");
        star_span.className = "mui-icon mui-icon-star-filled";
        for (var i = 0; i < 5; i++) {
            var star = star_span.cloneNode(true);
            if (i < grade) {
                star.className += " yunyou-active";
            }
            star_node.appendChild(star);
        }
        return star_node;
    }

    /* 移除所有列表项 */
    function remove_list_item() {
        var list = assess_list;
        while (list.hasChildNodes()) {
            list.removeChild(list.firstChild);
        }
    }

});
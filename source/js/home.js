mui.ready(function () {
    /* 变量声明 */
    var slider = document.getElementById("yunyou-slider-bar");
    var color = document.getElementsByClassName("yunyou-home-bar")[0].style.backgroundColor;
    var review_item = document.getElementById("yunyou-review-example");
    var scenery_item = document.getElementById("yunyou-scenery-example");
    var review = document.getElementById("yunyou-review");
    var scenery = document.getElementById("yunyou-scenery");
    var review_btn = document.getElementById("yunyou-button-review");
    var scenery_btn = document.getElementById("yunyou-button-scenery");
    var review_bar = mui("#yunyou-button-review")[0];
    var scenery_bar = mui("#yunyou-button-scenery")[0];

    /* 样式初始化 */
    slider.style.backgroundColor = color;
    review_bar.style.color = color;
    scenery.style.display = "none";

    var sum_review_page = parseInt(review_btn.getAttribute("data-sum") / 5);
    var sum_scenery_page = parseInt(scenery_btn.getAttribute("data-sum") / 5);

    var user_id = window.location.pathname.replace(/[a-zA-Z\/]/g, "");
    var scenery_page = 0, review_page = 0;

    /* 加载评价列表 */
    ajax_load_review();
    ajax_load_scenery();

    /* 点击跳转 */
    mui(".yunyou-top-btn").on("tap", "#yunyou-href-home", function () {
        mui.openWindow("/");
    }).on("tap", "#yunyou-href-set", function () {
        mui.openWindow("/setup");
    });

    /* 切换面板 */
    mui(".yunyou-slider").on("tap", "#yunyou-button-review", function () {
        if (slider.style.left == "50%") {
            slider.style.left = "0%";
            scenery_bar.style.color = "#999999";
            review_bar.style.color = color;
            scenery.style.display = "none";
            review.style.display = "block";
            window.scrollTo(0, 0);
        }
    }).on("tap", "#yunyou-button-scenery", function () {
        if (slider.style.left == "0%") {
            slider.style.left = "50%";
            review_bar.style.color = "#999999";
            scenery_bar.style.color = color;
            review.style.display = "none";
            scenery.style.display = "block";
            window.scrollTo(0, 0);
        }
    });

    /* 列表点击 */
    mui("#yunyou-scenery").on("tap", "li", function () {
        var href = this.getAttribute("data-href");
        mui.openWindow(href);
    });
    mui("#yunyou-review").on("tap", "li", function (e) {
        if (e.target.nodeName == "A") {
            href = e.target.getAttribute("data-href");
        } else {
            href = this.getAttribute("data-href");
        }
        mui.openWindow(href);
    });

    /* 下一页按钮 */
    mui(".yunyou-page").on("tap", "#yunyou-button-pre", function () {
        if (review.style.display === "block") {
            if (review_page > 0) {
                review_page--;
                ajax_load_review();
            }
        }
        if (scenery.style.display === "block") {
            if (scenery_page > 0) {
                scenery_page--;
                ajax_load_scenery();
            }
        }
    }).on("tap", "#yunyou-button-next", function () {
        if (review.style.display === "block") {
            console.log(review_page, sum_review_page);
            if (review_page < sum_review_page) {
                review_page++;
                ajax_load_review();
            }
        }
        if (scenery.style.display === "block") {
            if (scenery_page < sum_scenery_page) {
                scenery_page++;
                ajax_load_scenery();
            }
        }

    })

    /* 请求景点数据 */
    function ajax_load_scenery() {
        mui.ajax("/home/scenery", {
            data: {
                "user_id": user_id,
                "page": scenery_page
            },
            dataType: 'json',
            type: 'get',
            success: function (data) {
                if (data) {
                    remove_list_item(scenery);
                    load_scenery_list(data);
                }
            }
        });
    }

    /* 请求评价数据 */
    function ajax_load_review() {
        mui.ajax("/home/review", {
            data: {
                "user_id": user_id,
                "page": review_page
            },
            dataType: 'json',
            type: 'get',
            success: function (data) {
                if (data) {
                    remove_list_item(review);
                    load_review_list(data);
                }
            }
        });
    }

    /* 填充景点列表 */
    function load_scenery_list(data) {
        var list = document.getElementById("yunyou-scenery");
        for (var i = 0; i < data.length; i++) {
            var temp = scenery_item.cloneNode(true);
            temp.removeAttribute("style");
            temp.removeAttribute("id");
            temp.setAttribute("data-href", "/detail/" + data[i]['scenery_id']);
            var view = temp.getElementsByClassName("yunyou-scenery-view")[0];
            if (data[i]['cover']) {
                var img = document.createElement("img");
                img.setAttribute("src", JSON.parse(data[i]['cover'])[0]);
                view.appendChild(img);
            }
            var header = temp.getElementsByClassName("yunyou-scenery-header")[0];
            header.getElementsByTagName("h3")[0].innerHTML = data[i]['name'];
            header.getElementsByTagName("small")[0].innerHTML = data[i]['address'] + " · " + data[i]['belong'];
            var footer = temp.getElementsByClassName("yunyou-scenery-footer")[0];
            var score = (data[i]['sumtimes'] == 0) ? 0
                : Math.round((data[i]['sumscore'] / data[i]['sumtimes']) * 10) / 10;
            footer.appendChild(create_star_node("scenery", score));
            footer.appendChild(document.createElement("em"));
            footer.appendChild(document.createElement("p"));
            footer.getElementsByTagName("em")[0].innerHTML = score.toFixed(1);
            footer.getElementsByTagName("p")[0].innerHTML = data[i]['message'];
            list.appendChild(temp);
        }
    }

    /* 填充评论列表 */
    function load_review_list(data) {
        var list = document.getElementById("yunyou-review");
        for (var i = 0; i < data.length; i++) {
            var temp = review_item.cloneNode(true);
            temp.removeAttribute("style");
            temp.removeAttribute("id");
            temp.setAttribute("data-href", "/discuss/" + data[i]['review_id']);
            var header = temp.getElementsByClassName("yunyou-review-header")[0];
            header.innerHTML = "评价了" + "<a data-href='/detail/"
                + data[i]['scenery_id'] + "'>" + data[i]['name'] + "</a>";
            var article = temp.getElementsByClassName("yunyou-review-article")[0];
            article.innerHTML = data[i]['content'];
            var star_node = create_star_node("review", data[i]['grade']);
            temp.insertBefore(star_node, article);
            var footer = temp.getElementsByClassName("yunyou-review-footer")[0];
            footer.innerHTML = data[i]['message'] + data[i]['date'];
            list.appendChild(temp);
        }
    }

    /* 创建评分图标 */
    function create_star_node(type, score) {
        score = Math.round(score);
        var star_node = document.createElement("div");
        star_node.className = "yunyou-" + type + "-star";
        for (var i = 0; i < 5; i++) {
            var star = document.createElement("span");
            if (i < score) {
                star.className = "mui-icon mui-icon-star-filled yunyou-active";
            } else {
                star.className = "mui-icon mui-icon-star-filled";
            }
            star_node.appendChild(star);
        }
        return star_node;
    }

    /* 移除所有列表项 */
    function remove_list_item(list) {
        while (list.hasChildNodes()) {
            list.removeChild(list.firstChild);
        }
    }

});
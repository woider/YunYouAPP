mui.ready(function () {
    /* 变量声明 */
    var scenic = document.getElementsByClassName("yunyou-scenic")[0];
    var item = document.getElementById("yunyou-scenic-example");

    /* 初始化列表 */
    load_scenic(scenic_init);

    mui("#yunyou-scenic-list").on("tap", ".yunyou-scenic-item", function () {
        var href = this.getAttribute("data-href");
        mui.openWindow(href);
    });

});

mui.init({
    pullRefresh: {
        container: "#yunyou-refresh-container",
        down: {
            height: 50,
            contentdown: "下拉刷新",
            contentover: "释放刷新",
            contentrefresh: "正在刷新...",
            callback: pull_fresh
        }
    }
});
/* 刷新函数 */
function pull_fresh() {
    setTimeout(function () {
        mui.ajax("/main/random", {
            type: 'get',
            dataType: 'json',
            timeout: 3000,
            success: function (data) {
                mui("#yunyou-refresh-container")
                    .pullRefresh().endPulldownToRefresh();
                remove_list_item();
                load_scenic(data);
            },
            error: function () {
                mui("#yunyou-refresh-container")
                    .pullRefresh().endPulldownToRefresh();
            }
        });
    }, 1000);
}

/* 加载景点列表 */
function load_scenic(data) {
    var scenic = document.getElementById("yunyou-scenic-list");
    var item = document.getElementById("yunyou-scenic-example");
    for (var i = 0; i < data.length; i++) {
        var temp = item.cloneNode(true);
        temp.removeAttribute("id");
        temp.removeAttribute("style");
        var img_node = temp.getElementsByClassName("yunyou-scenic-view")[0];
        var info_node = temp.getElementsByClassName("yunyou-scenic-info")[0];
        var h3_node = info_node.getElementsByTagName("h3")[0];
        var p_node = info_node.getElementsByTagName("p")[0];
        var em_node = info_node.getElementsByTagName("em")[0];
        temp.setAttribute("data-href", "/detail/" + data[i].id);
        img_node.setAttribute("src", JSON.parse(data[i].cover)[0]);
        h3_node.innerHTML = data[i].name;
        p_node.innerHTML = data[i].address + " · " + data[i].belong;
        var score = (data[i].sumtimes == 0) ? 0
            : Math.round((data[i].sumscore / data[i].sumtimes) * 10) / 10;
        em_node.innerHTML = score.toFixed(1);
        info_node.appendChild(build_star(score));
        scenic.appendChild(temp);
    }
}

/* 根据得分绘制星星 */
function build_star(score) {
    score = Math.round(score);
    var star_node = document.createElement("div");
    for (var i = 0; i < 5; i++) {
        var star = document.createElement("span");
        if (i < Math.ceil(score)) {
            star.className = "mui-icon mui-icon-star-filled yunyou-active";
        } else {
            star.className = "mui-icon mui-icon-star-filled";
        }
        star_node.appendChild(star);
    }
    star_node.className = "yunyou-scenic-star";
    return star_node;
}

/* 移除所有列表项 */
function remove_list_item() {
    var list = document.getElementById("yunyou-scenic-list");
    while (list.hasChildNodes()) {
        list.removeChild(list.firstChild);
    }
}
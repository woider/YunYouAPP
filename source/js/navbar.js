mui.ready(function () {
    /* 声明变量 */
    var search = document.getElementsByClassName("yunyou-search")[0];
    var content = document.getElementsByClassName("yunyou-content")[0];
    var icon_search = document.getElementById("yunyou-icon-search");
    var input_search = document.getElementById("yunyou-input-search");
    var search_done = document.getElementsByClassName("yunyou-search-done")[0];
    var search_none = document.getElementsByClassName("yunyou-search-none")[0];

    var record = ""; //关键字搜索记录

    /* 监听导航栏事件 */
    mui(".yunyou-navbar").on("tap", "#yunyou-href-logo", function () {
        mui.openWindow("/");
    }).on("tap", "#yunyou-img-head", function () {
        mui.openWindow("/home");
    }).on("focusin", "#yunyou-input-search", function () {
        if (content) content.style.display = "none";
        search.style.display = "block";
        icon_search.setAttribute("class", "mui-icon mui-icon-close");
    }).on("focusout", "#yunyou-input-search", function () {
        if (content) content.style.display = "block";
        search.style.display = "none";
        icon_search.setAttribute("class", "mui-icon mui-icon-search");
    }).on("tap", "#yunyou-icon-search", function () {
        if (content) content.style.display = "block";
        search_done.style.display = "none";
        search_none.style.display = "none";
        search.style.display = "none";
        input_search.value = null;
        input_search.blur();
        remove_list_item();
    }).on("input", "#yunyou-input-search", function () {
        var keyword = input_search.value;
        var temp = keyword.replace(/(\s|\/)/g, "");
        if (temp == record) {
            return false;
        } else {
            record = temp;
        }
        if (temp.length == 0) {
            remove_list_item();
            return false;
        }
        mui.ajax("/navbar/search", {
            data: {"keyword": keyword},
            type: 'get',
            dataType: 'json',
            success: function (data) {
                show_search_result(data);
            }
        });


    });

    /* 列表项点击事件 */
    mui("#yunyou-search-list").on("tap", "li", function () {
        var href = this.getAttribute("data-href");
        mui.openWindow(href);
    });

    /* 跳转至创建景区 */
    mui(".yunyou-search-none").on("tap", "a", function () {
        mui.openWindow("/scenic");
    });

    /* 移除所有列表项 */
    function remove_list_item() {
        var list = document.getElementById("yunyou-search-list");
        while (list.hasChildNodes()) {
            list.removeChild(list.firstChild);
        }
    }

    /**
     * 展示搜索结果
     * @param data
     */
    function show_search_result(data) {
        if (data.length > 0) {
            search_done.style.display = "block";
            search_none.style.display = "none";
            remove_list_item();
            load_search_list(data);
        } else {
            search_done.style.display = "none";
            search_none.style.display = "block";
        }
    }

    /* 加载搜索数据 */
    function load_search_list(data) {
        var item = document.getElementById("yunyou-search-list-item");
        var list = document.getElementById("yunyou-search-list");
        for (var i = 0; i < data.length; i++) {
            var temp = item.cloneNode(true);
            temp.removeAttribute("style");
            temp.removeAttribute("id");
            temp.setAttribute("data-href", "/detail/" + data[i].id);
            temp.getElementsByTagName("h3")[0].innerHTML = data[i].name;
            temp.getElementsByTagName("p")[0].innerHTML = data[i].address + " · " + data[i].belong;
            list.appendChild(temp);
        }
    }
});
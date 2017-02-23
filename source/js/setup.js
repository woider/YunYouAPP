mui.ready(function () {
    /* 变量声明 */
    var image_head = document.getElementById("yunyou-img-head");
    var input_upload = document.getElementById("yunyou-input-upload");
    var input_date = document.getElementById("yunyou-input-date");
    var input_name = document.getElementById("yunyou-input-name");
    var input_work = document.getElementById("yunyou-input-work");

    var icon_boy = document.getElementsByClassName("icon-boy")[0];
    var icon_girl = document.getElementsByClassName("icon-girl")[0];

    /* 保存资料 */
    mui("header").on("tap", "button", function () {
        window.focus();
        mui.ajax("/setup/submit", {
            data: {
                'profile': image_head.getAttribute("src"),
                'nickname': input_name.value,
                'gender': get_sex_value(),
                'birthday': input_date.value,
                'profession': input_work.value
            },
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data && data.status) {
                    fill_user_data(data.result);
                    mui.toast("保存成功");
                }
            }
        });
    });

    /* 退出登录 */
    mui(".yunyou-form").on("tap", "#yunyou-btn-quit", function () {
        mui.ajax("/setup/quit", {
            dataType: 'json',
            type: 'post',
            success: function (data) {
                if (data && data.status) {
                    mui.openWindow(data.result.href);
                }
            }
        });
    });

    /* 监听事件 */
    mui(".yunyou-form").on("tap", ".yunyou-image img", function () {
        input_upload.click();
    }).on("change", "#yunyou-input-upload", function () {
        /* 上传图片 */
        var formData = new FormData();
        formData.append('profile', this.files[0]);
        mui.ajax("/setup/upload", {
            data: formData,
            dataType: 'json',
            type: 'post',
            cache: false,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data && data.status) {
                    image_head.setAttribute("src", data.result.src);
                } else {
                    mui.toast(data.message);
                }
            }
        });
    }).on("tap", ".yunyou-form-sex span", function () {
        /* 切换性别 */
        if (this == icon_boy) {
            icon_boy.setAttribute("style", "color:#3598DB");
            icon_girl.removeAttribute("style");
        } else if (this == icon_girl) {
            icon_girl.setAttribute("style", "color:#FF7F7B");
            icon_boy.removeAttribute("style");
        }
    }).on("change", "#yunyou-input-pick", function () {
        /* 选择日期 */
        if (this.value) {
            input_date.value = this.value;
        }
    });

    /* 获取性别值 */
    function get_sex_value() {
        if (icon_boy.getAttribute("style") !== null) {
            return 1;
        }
        if (icon_girl.getAttribute("style") !== null) {
            return 0;
        }
        return null;
    }

    /* 填充用户数据 */
    function fill_user_data(data) {
        image_head.setAttribute("src", data.profile);
        input_name.value = data.nickname;
        input_date.value = data.birthday;
        input_work.value = data.profession;
        if (data.gender == 1) {
            icon_boy.setAttribute("style", "color:#3598DB");
            icon_girl.removeAttribute("style");
        } else if (data.gender == 0) {
            icon_girl.setAttribute("style", "color:#FF7F7B");
            icon_boy.removeAttribute("style");
        }
    }
});
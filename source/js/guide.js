mui.ready(function () {
    var head = document.getElementById("yunyou-img-head");
    var name = document.getElementById("yunyou-input-name");

    /* 触发上传事件 */
    mui(".yunyou-user-upload").on("tap", "*", function () {
        document.getElementById("yunyou-input-upload").click();
    });
    /* 异步上传图片 */
    mui(".yunyou-user-upload").on("change", "input", function () {
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
                    head.setAttribute("src", data.result.src);
                    head.removeAttribute("style");
                } else {
                    mui.toast("头像上传失败");
                }
            }
        });
    });
    mui(".yunyou-button-group").on("tap", "#yunyou-button-save", function () {
        var src = head.getAttribute("src");
        var str = name.value.replace(/\s/g, "").substr(0, 12);
        if (src == null) {
            mui.toast("亲~上传头像");
        } else if (str.length == 0) {
            mui.toast("亲~填写昵称");
        } else if (!str.match(/^[A-Za-z0-9_\u4e00-\u9fa5]{1,12}$/)) {
            mui.toast("含特殊字符");
        } else {
            mui.ajax("/guide/submit", {
                data: {
                    "profile": src,
                    "nickname": str
                },
                dataType: 'json',
                type: 'post',
                success: function (data) {
                    if (data && data.status) {
                        mui.openWindow(data.result.href);
                    } else {
                        mui.toast(data.message);
                    }
                }
            });
        }

    });
    mui(".yunyou-button-group").on("tap", "#yunyou-button-skip", function () {
        mui.openWindow("/");
    });
});
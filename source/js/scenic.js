mui.ready(function () {
    /* 变量声明 */
    var input_name = document.getElementById("yunyou-input-name");
    var input_address = document.getElementById("yunyou-input-address");
    var input_belong = document.getElementById("yunyou-input-belong");
    var input_introduce = document.getElementById("yunyou-input-introduce");

    var submit = document.getElementsByClassName("mui-btn-link")[0];
    var spinner = document.getElementsByClassName("mui-icon-spinner")[0];
    var form = document.getElementsByClassName("yunyou-form")[0];
    var result = document.getElementsByClassName("yunyou-result")[0];
    var success = document.getElementsByClassName("yunyou-result-success")[0];
    var defeat = document.getElementsByClassName("yunyou-result-defeat")[0];
    var image = document.getElementsByClassName("yunyou-form-image")[0];
    var upload = document.getElementById("yunyou-input-upload");

    /* 初始化样式 */
    var image_width = parseInt((screen.width - 60) / 3);
    var image_height = parseInt(image_width * (600 / 960));
    var image_button = mui(".yunyou-form-image button")[0];
    mui(".yunyou-form-image img").each(function (index, item) {
        item.style.width = image_width + "px";
        item.style.height = image_height + "px";
    });
    image_button.style.width = image_width + "px";
    image_button.style.height = image_height + "px";
    image_button.style.display = "block";


    /* 导航事件 */
    mui("header").on("tap", "button", function () {
        window.focus();
        var param = window.location.pathname.replace(/[a-zA-Z\/]/g, "");
        var scenic = get_scenic_data();//获取数据
        if (!validate_scenic(scenic))return;//验证数据

        this.style.display = "none";
        spinner.style.display = "block";

        mui.ajax("/scenic/submit/" + param, {
            data: scenic,
            dataType: 'json',
            type: 'post',
            success: function (data) {
                if (data && data.status) {
                    var href = "/detail/" + data.result.id;
                    success.setAttribute("data-href", href);
                    submit_response(true);
                } else {
                    submit_response(false);
                }
            }
        });
    });

    /* 反馈事件 */
    mui(".yunyou-result").on("tap", ".yunyou-result-success button", function () {
        var href = success.getAttribute("data-href");
        mui.openWindow(href);
    }).on("tap", ".yunyou-result-defeat button", function () {
        submit.removeAttribute("style");
        form.removeAttribute("style");
        result.style.display = "none";
        success.removeAttribute("style");
        defeat.removeAttribute("style");
    });

    /* 上传图片 */
    mui(".yunyou-form-image").on("tap", "button", function () {
        upload.click();
    }).on("change", "#yunyou-input-upload", function () {
        lrz(this.files[0], {
            width: 960,
            quality: 1,
        }).then(function (rst) {
            rst.formData.append("base64", rst.base64);
            mui.ajax("/scenic/upload", {
                type: 'post',
                dataType: 'json',
                data: rst.formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data && data.status) {
                        append_scenic_image(data.result.src);
                    } else {
                        mui.toast("图片上传失败");
                    }
                }
            });
        });
    });

    /* 获取景点数据 */
    function get_scenic_data() {
        /* 获取相关数据 */
        var name = input_name.value.trim();
        var address = input_address.value.trim();
        var belong = input_belong.value.trim();
        var introduce = input_introduce.value;
        var cover = [];
        mui(".yunyou-form-image img").each(function (index, item) {
            cover.push(item.getAttribute("src"));
        });
        return {
            "name": name,
            "address": address,
            "belong": belong,
            "introduce": introduce,
            "cover": cover
        };
    }

    /* 验证数据有效性 */
    function validate_scenic(scenic) {
        if (scenic.name.length == 0) {
            mui.toast("请填写景点名称");
            return false;
        }
        if (scenic.address.length == 0) {
            mui.toast("请填写所在地区");
            return false;
        }
        if (scenic.belong.length == 0) {
            mui.toast("请填写所属景区");
            return false;
        }
        return true;
    }

    /* 添加景点图片 */
    function append_scenic_image(src) {
        var img = new Image();
        img.src = src;
        img.style.width = image_width + "px";
        img.style.height = image_height + "px";
        img.onload = function () {
            image.insertBefore(img, image_button);
            if (mui(".yunyou-form-image img").length > 5) {
                image.removeChild(mui(".yunyou-form-image img")[0]);
            }
        }
    }

    /*提交反馈页面*/
    function submit_response(status) {
        setTimeout(function () {
            spinner.style.display = "none";
            form.style.display = "none";
            if (status) {
                defeat.style.display = "none";
            } else {
                success.style.display = "none";
            }
            result.style.display = "block";
        }, 1000);
    }
});
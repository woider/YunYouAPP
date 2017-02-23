mui.ready(function () {
    /* 初始化变量 */
    var result = document.getElementsByClassName("yunyou-result")[0];
    var form = document.getElementsByClassName("yunyou-form")[0];
    var success = document.getElementsByClassName("yunyou-result-success")[0];
    var defeat = document.getElementsByClassName("yunyou-result-defeat")[0];
    var star = document.getElementsByClassName("yunyou-assess-star")[0];
    var image_group = document.getElementsByClassName("yunyou-form-upload")[0];

    var upload = document.getElementById("yunyou-btn-upload");
    var input_assess = document.getElementById("yunyou-input-assess");
    var input_upload = document.getElementById("yunyou-input-upload");

    var strtip = mui(".yunyou-assess-content p")[0];
    var spinner = mui("#yunyou-header .mui-icon-spinner")[0];
    var submit = mui("#yunyou-header .mui-btn-link")[0];

    var image_size = parseInt((screen.width - 80) / 5);

    /* 初始化样式 */
    upload.style.width = image_size + "px";
    upload.style.height = image_size + "px";
    upload.style.display = "block";
    image_group.style.height = (image_size + 20) + "px";

    /* 加载图片 */
    if (photo_init !== null) {
        for (index in photo_init) {
            var node = create_image_node(photo_init[index]);
            image_group.insertBefore(node, upload);
        }
    }


    /* 导航栏点击 */
    mui("#yunyou-header").on("tap", "button", function () {
        window.focus();
        var param = window.location.pathname.replace(/[a-zA-Z\/]/g, "");
        var review = get_review_data();//获取数据
        if (!validate_review(review)) return false;

        this.style.display = "none";
        spinner.style.display = "block";

        mui.ajax("/assess/submit/" + param, {
            data: review,
            dataType: 'json',
            type: 'post',
            success: function (data) {
                if (data && data.status) {
                    var href = "/discuss/" + data.result.id;
                    success.setAttribute("data-href", href);
                    submit_response(true);
                } else {
                    submit_response(false);
                }
            }
        });
    });

    /* 评分与评价 */
    mui(".yunyou-form-assess").on("tap", ".yunyou-assess-star", function (e) {
        if (e.target.nodeName != "SPAN") return;
        var position = 10;
        mui(".yunyou-assess-star span").each(function (index, item) {
            if (e.target == item) {
                position = index;
            }
            if (index > position) {
                item.className = "mui-icon mui-icon-star";
            } else {
                item.className = "mui-icon mui-icon-star-filled";
            }
        });
    }).on("input", "#yunyou-input-assess", function () {
        var count = this.value.replace(/(\s|\n)/g, "").length;
        if (count == 0) {
            strtip.innerHTML = "100-1000 个字";
            strtip.removeAttribute("style");
        } else if (count < 100) {
            strtip.innerHTML = "还差 " + (100 - count) + " 个字";
            strtip.setAttribute("style", "color: #DD524D;");
        } else if (count > 1000) {
            strtip.innerHTML = "超出 " + (count - 1000) + " 个字";
            strtip.setAttribute("style", "color: #DD524D;");
        } else {
            strtip.innerHTML = count + " 个字";
            strtip.removeAttribute("style");
        }
    });

    /* 图片上传修改 */
    mui(".yunyou-form-image").on("tap", "#yunyou-btn-upload", function () {
        input_upload.click();
    }).on("tap", ".yunyou-form-upload-image>span", function () {
        var node = this.parentNode;
        if (node.remove) {
            node.remove();
        } else {
            node.parentNode.removeChild(node);
        }
        check_image_count();
    }).on("change", "#yunyou-input-upload", function () {
        lrz(this.files[0], {
            width: 720,
            quality: 1,
        }).then(function (rst) {
            rst.formData.append('base64', rst.base64);
            mui.ajax("/assess/upload", {
                data: rst.formData,
                type: 'post',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data && data.status) {
                        var node = create_image_node(data.result.src);
                        image_group.insertBefore(node, upload);
                        check_image_count();
                    } else {
                        mui.toast("图片上传失败");
                    }
                }
            });
        });
    });

    mui(".yunyou-result").on("tap", ".yunyou-result-success button", function () {
        var href = success.getAttribute("data-href");
        mui.openWindow(href);
    }).on("tap", ".yunyou-result-defeat button", function () {
        success.removeAttribute("style");
        defeat.removeAttribute("style");
        form.removeAttribute("style");
        result.style.display = "none";
        spinner.style.display = "none";
        submit.style.display = "block";
    });

    /* 检查图片数量 */
    function check_image_count() {
        if (mui(".yunyou-form-upload-image").length < 5) {
            upload.style.display = "block";
        } else {
            upload.style.display = "none";
        }
        window.focus(); //清除焦点
    }

    /* 创建图片节点 */
    function create_image_node(source) {

        var div = document.createElement("div");
        var img = document.createElement("img");
        var pack = document.createElement("div");
        var span = document.createElement("span");

        div.className = "yunyou-form-upload-image";
        pack.className = "yunyou-image-pack";
        span.className = "mui-icon mui-icon-close";

        div.style.width = image_size + "px";
        div.style.height = image_size + "px";

        img.src = source;

        pack.appendChild(img);
        div.appendChild(pack);
        div.appendChild(span);

        /* 调整图像位置 */
        img.onload = function () {
            if (this.width > this.height) {
                this.style.height = "100%";
                this.style.marginLeft = -(this.width - image_size) / 2 + "px";
            } else {
                this.style.width = "100%";
                this.style.marginTop = -(this.height - image_size) / 2 + "px";
            }
        };

        return div;
    }

    /* 获取相关数据 */
    function get_review_data() {
        var grade = mui(".mui-icon-star-filled").length;
        var content = input_assess.value;
        var photo = [];
        mui(".yunyou-image-pack img").each(function (index, item) {
            photo[index] = item.getAttribute("src");
        });
        return {
            "grade": grade,
            "content": content,
            "photo": photo
        };
    }

    /* 验证表单有效性 */
    function validate_review(review) {
        if (review.grade == 0) {
            mui.toast("请给一个评分");
            return false;
        }
        var count = review.content.replace(/(\s|\n)/g, "").length;
        if (count < 100) {
            mui.toast("评价至少100字");
            return false;
        }
        return true;
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
mui.ready(function () {
    var phone = document.getElementById("yunyou-input-phone");
    var email = document.getElementById("yunyou-input-email");
    var send = document.getElementById("yunyou-button-send");
    var login = document.getElementById("yunyou-button-login");

    /* 格式化手机号码 */
    mui(".mui-card-content-inner").on("keyup", "#yunyou-input-phone", function (e) {
        var size = this.value.length;
        if (e.keyCode >= 48 && e.keyCode <= 57) {
            var str = this.value.replace(/\s/g, "");
            var temp = "";
            for (var i in str) {
                if (i == 3 || i == 7) {
                    temp += " ";
                }
                temp += str[i];
            }
            this.value = temp;
        } else if (e.keyCode != 8) {
            this.value = this.value.replace(/[a-zA-Z]/g, "");
        }
    });
    /* 发送验证码 */
    mui(".mui-card-content-inner").on("tap", "#yunyou-button-send", function () {
        var number = phone.value.replace(/\s/g, "");
        if (number.length == 0) {
            mui.toast("请填写手机号");
        } else if (number.match(/^(13|14|15|18)[0-9]{9}$/) == null) {
            mui.toast("手机号不正确");
        } else {
            mui.ajax("/login/sms/" + number, {
                    dataType: 'json',
                    type: 'get',
                    success: function (data) {
                        if (data && data.status) {
                            mui.toast("验证码已发送");
                            send.setAttribute("disabled", "disabled");
                            reset_btn(send, data.result.wait);
                        } else {
                            mui.toast(data.message);
                        }
                    }
                }
            );
        }


    });
    /* 登录按钮 */
    document.getElementById("yunyou-button-login").addEventListener("tap", function () {
        if (phone.value.length == 0)return;
        if (email.value.length == 0)return;
        mui.ajax("/login/auth/" + email.value, {
            dataType: 'json',
            type: 'get',
            success: function (data) {
                if (data && data.status) {
                    eval(data.result.script);
                } else {
                    mui.toast(data.message);
                }
            }
        });
    });
});

/* 更新按钮状态 */
function reset_btn(ele, i) {
    if (i > 0) {
        ele.value = "重新获取(" + (i--) + ")";
        setTimeout(function () {
            reset_btn(ele, i);
        }, 1000);
    } else {
        ele.value = "获取验证码";
        ele.removeAttribute("disabled");
    }
}
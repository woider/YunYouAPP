<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>云友点评 - 登录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="/source/dist/css/mui.min.css"/>
    <link rel="stylesheet" type="text/css" href="/source/dist/css/icons-extra.css"/>
    <link rel="stylesheet" type="text/css" href="/source/css/login.css"/>
    <script src="/source/dist/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/source/js/login.js" type="text/javascript" charset="utf-8"></script>
</head>

<body>
<div class="yunyou-logo">
    <img src="/source/img/logo.png" alt="云游LOGO"/>
    <h1>云友点评</h1>
</div>
<div class="yunyou-form mui-card">
    <div class="mui-card-content">
        <div class="mui-card-content-inner">
            <span class="mui-icon-extra mui-icon-extra-phone"></span>
            <input type="tel" placeholder="手机号" id="yunyou-input-phone" maxlength="13"/>
        </div>
        <hr/>
        <div class="mui-card-content-inner">
            <span class="mui-icon mui-icon-email"></span>
            <input type="tel" placeholder="验证码" id="yunyou-input-email" maxlength="6"/>
            <input type="button" id="yunyou-button-send" value="获取验证码"/>
        </div>
    </div>

</div>
<div style="margin: 0px 20px;">
    <button type="button" id="yunyou-button-login">登录</button>
</div>
</body>

</html>
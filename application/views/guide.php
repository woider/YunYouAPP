<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>云友点评 - 登录引导</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="/source/dist/css/mui.min.css"/>
    <link rel="stylesheet" type="text/css" href="/source/css/guide.css"/>
    <script src="/source/dist/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/source/js/guide.js" type="text/javascript" charset="utf-8"></script>
</head>

<body>
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">完善信息</h1>
</header>
<div class="yunyou-user-upload">
    <div class="yunyou-text">上传头像</div>
    <img id="yunyou-img-head" style="visibility: hidden;"/>
    <input type="file" accept="image/jpeg" id="yunyou-input-upload" style="display: none;"/>
</div>
<div class="yunyou-form mui-card">
    <div class="mui-card-content">
        <div class="mui-card-content-inner">
            <span class="mui-icon mui-icon-contact"></span>
            <input type="text" placeholder="昵称（不超过12个字）" id="yunyou-input-name"/>
        </div>
    </div>
</div>
<div style="margin: 0px 20px;" class="yunyou-button-group">
    <button type="submit" id="yunyou-button-save">保存</button>
    <button type="button" id="yunyou-button-skip">跳过</button>
</div>
</body>

</html>
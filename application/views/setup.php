<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>云友点评 - 基本资料</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="/source/dist/css/mui.min.css"/>
    <link rel="stylesheet" type="text/css" href="/source/dist/css/iconfont.css"/>
    <link rel="stylesheet" type="text/css" href="/source/css/setup.css"/>
    <script src="/source/dist/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/source/js/setup.js" type="text/javascript" charset="utf-8"></script>
</head>

<body>
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">基本资料</h1>
    <button class="mui-btn mui-btn-link mui-pull-right">保存</button>
</header>
<div class="yunyou-form">
    <div class="yunyou-form-double">
        <div class="yunyou-image yunyou-card">
            <img src="<?php if (!empty($profile)) echo $profile ?>" id="yunyou-img-head"/>
            <input type="file" accept="image/jpeg" id="yunyou-input-upload" style="display: none;"/>
        </div>
        <div class="yunyou-select-outer">
            <div class="yunyou-select yunyou-card">
                <div class="yunyou-form-sex">
                    <label>性别</label>
                    <?php $sex = (empty($gender) && $gender === null) ? null : $gender; ?>
                    <span class="mui-icon iconfont icon-boy"
                        <?php if ($sex . '' === '1') echo 'style="color: #3598DB;"'; ?>
                    ></span>
                    <span class="mui-icon iconfont icon-girl"
                        <?php if ($sex . '' === '0') echo 'style="color: #FF7F7B;"'; ?>
                    ></span>
                </div>
                <div class="yunyou-form-day">
                    <label>生日</label>
                    <div class="yunyou-input-outer">
                        <input type="date" id="yunyou-input-pick" style="opacity: 0;position: absolute;width: 120px;"/>
                        <input type="text" id="yunyou-input-date" disabled="disabled"
                               value="<?php if (!empty($birthday)) echo $birthday ?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="yunyou-form-single yunyou-card">
        <div class="yunyou-form-name">
            <label>昵称</label>
            <div class="yunyou-input-outer">
                <input type="text" id="yunyou-input-name" value="<?php if (!empty($nickname)) echo $nickname ?>"/>
            </div>
        </div>
        <div class="yunyou-form-work">
            <label>职业</label>
            <div class="yunyou-input-outer">
                <input type="text" id="yunyou-input-work" value="<?php if (!empty($profession)) echo $profession ?>"/>
            </div>
        </div>
    </div>
    <button id="yunyou-btn-quit">退出</button>
</div>
</body>

</html>
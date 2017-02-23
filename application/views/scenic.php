<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>云友点评 - 编辑景点</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="/source/dist/css/mui.min.css"/>
    <link rel="stylesheet" type="text/css" href="/source/css/scenic.css"/>
    <script src="/source/dist/js/mui.js" type="text/javascript" charset="utf-8"></script>
    <script src="/source/dist/js/lrz.bundle.js" type="text/javascript" charset="utf-8"></script>
    <script src="/source/js/scenic.js" type="text/javascript" charset="utf-8"></script>
</head>

<body>
<header id="yunyou-header" class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">编辑景点</h1>
    <button class="mui-btn mui-btn-link mui-pull-right">提交</button>
    <span class="mui-icon mui-icon-spinner" style="display: none;"></span>
</header>
<div class="yunyou-form">
    <div class="yunyou-form-name yunyou-card">
        <label>景点名称</label>
        <input type="text" id="yunyou-input-name" value="<?php if (!empty($name)) echo $name; ?>"/>
    </div>
    <div class="yunyou-form-address yunyou-card">
        <div>
            <label>所在地区</label>
            <input type="text" id="yunyou-input-address" value="<?php if (!empty($address)) echo $address; ?>"/>
        </div>
        <div>
            <label>所属景区</label>
            <input type="text" id="yunyou-input-belong" value="<?php if (!empty($belong)) echo $belong; ?>"/>
        </div>
    </div>
    <div class="yunyou-form-image yunyou-card">
        <?php
        if (!empty($cover) && strtoupper($cover) !== 'NULL') {
            foreach (json_decode($cover) as $src) {
                echo "<img src='$src' />";
            }
        }
        ?>
        <button style="display: none;">
            <span class="mui-icon mui-icon-plusempty"></span>
            <input type="file" accept="image/jpeg" id="yunyou-input-upload" style="display: none;"/>
        </button>
    </div>
    <div class="yunyou-form-describe yunyou-card">
        <textarea id="yunyou-input-introduce" placeholder="景点介绍"
        ><?php if (!empty($introduce)) echo $introduce; ?></textarea>
    </div>
</div>
<div class="yunyou-result" style="display: none;">
    <div class="yunyou-result-success">
        <span class="mui-icon mui-icon-checkmarkempty"></span>
        <h2>提交成功</h2>
        <button>立即查看</button>
    </div>
    <div class="yunyou-result-defeat">
        <span class="mui-icon mui-icon-closeempty"></span>
        <h2>提交失败</h2>
        <button>返回修改</button>
    </div>
</div>
</body>

</html>
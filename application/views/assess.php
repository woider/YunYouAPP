<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>云友点评 - 景点评价</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="/source/dist/css/mui.min.css"/>
    <link rel="stylesheet" type="text/css" href="/source/css/assess.css"/>
    <script src="/source/dist/js/mui.js" type="text/javascript" charset="utf-8"></script>
    <script src="/source/dist/js/lrz.bundle.js" type="text/javascript" charset="utf-8"></script>
    <script src="/source/js/assess.js" type="text/javascript" charset="utf-8"></script>
</head>

<body>
<header id="yunyou-header" class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">我要评价</h1>
    <button class="mui-btn mui-btn-link mui-pull-right">提交</button>
    <span class="mui-icon mui-icon-spinner" style="display: none;"></span>
</header>
<div class="yunyou-form">
    <div class="yunyou-form-title">
        <h3><?php if (!empty($scenery['name'])) echo $scenery['name']; ?></h3>
        <p><?php if (!empty($scenery['address']) && !empty($scenery['belong'])) {
                echo $scenery['address'] . ' · ' . $scenery['belong'];
            } ?></p>
    </div>
    <div class="yunyou-form-assess yunyou-card">
        <div class="yunyou-assess-star">
            <?php
            $empty = empty($review['grade']);
            for ($i = 0; $i < 5; $i++) {
                ?>
                <span class="mui-icon mui-icon-star<?php
                if (!$empty && $i < $review['grade']) {
                    echo '-filled';
                }
                ?>"></span>
                <?php
            }
            ?>
        </div>
        <div class="yunyou-assess-content">
            <textarea id="yunyou-input-assess" placeholder="谈谈你的经历，去帮助更多的人吧！"
            ><?php if (!empty($review['content'])) echo $review['content']; ?></textarea>
            <p>100-1000 个字</p>
        </div>
    </div>
    <div class="yunyou-form-image">
        <p>上传图片（最多5张）</p>
        <script>
            var photo_init = <?php
            if (!empty($review['photo'])) {
                echo 'JSON.parse("' . addslashes($review['photo']) . '");';
            } else {
                echo 'null';
            }
            ?>
        </script>
        <div class="yunyou-form-upload yunyou-card">
            <button id="yunyou-btn-upload" style="display: none;">
                <span class="mui-icon mui-icon-plusempty"></span>
            </button>
            <input type="file" accept="image/jpeg" id="yunyou-input-upload" style="display: none;"/>
        </div>
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
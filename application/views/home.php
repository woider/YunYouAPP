<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>云友点评 - 用户主页</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="/source/dist/css/mui.min.css"/>
    <link rel="stylesheet" type="text/css" href="/source/dist/css/icons-extra.css"/>
    <link rel="stylesheet" type="text/css" href="/source/css/home.css"/>
    <script src="/source/dist/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/source/js/home.js" type="text/javascript" charset="utf-8"></script>
</head>

<body>
<?php
if ($gender === null || $gender === '1') $color = '#3598DB';
if ($gender === '0') $color = '#FF7F7B';
?>
<header class="yunyou-home-bar" style="background-color: <?php echo $color ?>">
    <div class="yunyou-top-btn">
        <button id="yunyou-href-home">
            <span class="mui-icon mui-icon-home-filled"></span>
            <div>首页</div>
        </button>
        <?php
        $user_id = !empty($id) ? $id : null;
        $logged = isset($_SESSION['user_id']);
        if ($logged && $_SESSION['user_id'] == $user_id) { ?>
            <button id="yunyou-href-set">
                <span class="mui-icon mui-icon-gear-filled"></span>
            </button>
            <?php
        } ?>
    </div>
    <div class="yunyou-middle-img">
        <div><img src="<?php if (!empty($profile)) echo $profile ?>"/></div>
        <h1><?php if (!empty($nickname)) echo $nickname ?></h1>
    </div>
    <div class="yunyou-bottom-msg">
        <div id="yunyou-level-like">
            <span class="yunyou-small-font">获得赞同</span><span>&nbsp;</span>
            <span class="mui-icon-extra mui-icon-extra-like"></span><span>&nbsp;</span>
            <span class="yunyou-small-font"><?php echo empty($approve) ? 0 : $approve ?></span>
        </div>
        <div id="yunyou-level-heart">
            <span class="yunyou-small-font">友善度</span><span>&nbsp;</span>
            <?php
            $level = empty($kindness) ? 0 : $kindness;
            for ($i = 0; $i < 5; $i++) {
                if ($i < $level) {
                    echo '<span class="mui-icon-extra mui-icon-extra-heart-filled"></span>';
                } else {
                    echo ' <span class="mui-icon-extra mui-icon-extra-heart"></span>';
                }
            }
            ?>
        </div>
    </div>
</header>
<div class="yunyou-content">
    <div class="yunyou-slider">
        <button id="yunyou-button-review" data-sum="<?php echo $sumreview ?>">评价
            <?php echo $sumreview ?></button>
        <button id="yunyou-button-scenery" data-sum="<?php echo $sumscenery ?>">景点
            <?php echo $sumscenery ?></button>
        <div id="yunyou-slider-bar" style="left: 0%;"></div>
    </div>
    <div class="yunyou-item">
        <li id="yunyou-review-example" style="display: none;">
            <div class="yunyou-review-header"></div>
            <div class="yunyou-review-article"></div>
            <div class="yunyou-review-footer"></div>
        </li>
        <li id="yunyou-scenery-example" style="display: none;">
            <div class="yunyou-scenery-view"></div>
            <div class="yunyou-scenery-header">
                <h3>景点名称</h3>
                <small>所在地区 · 所属景区</small>
            </div>
            <div class="yunyou-scenery-footer"></div>
        </li>
        <ul id="yunyou-review" style="display: block"></ul>
        <ul id="yunyou-scenery" style="display: none"></ul>
    </div>
    <div class="yunyou-page">
        <button id="yunyou-button-pre">
            <span class="mui-icon mui-icon-arrowthinleft"></span>
            <span>上一页</span>
        </button>
        <button id="yunyou-button-next">
            <span>下一页</span>
            <span class="mui-icon mui-icon-arrowthinright"></span>
        </button>
    </div>
</div>
</body>

</html>
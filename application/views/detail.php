<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>景点详情 - <?php echo $scenery['name'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="/source/dist/css/mui.min.css"/>
    <link rel="stylesheet" type="text/css" href="/source/css/detail.css"/>
    <script src="/source/dist/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/source/js/detail.js" type="text/javascript" charset="utf-8"></script>
</head>

<body>
<?php require APPPATH . '/views/navbar.php' ?>
<div class="yunyou-content">
    <div class="yunyou-scenic">
        <div class="yunyou-scenic-header">
            <button onclick="<?php
            echo "mui.openWindow('/home/{$scenery['creator_id']}')";
            ?>">更多
            </button>
            <h3><?php echo $scenery['name'] ?></h3>
            <p><?php echo $scenery['address'] . ' · ' . $scenery['belong'] ?></p>
        </div>
        <div class="mui-slider" style="background: #EEEEEE;">
            <div class="mui-slider-group">
                <?php
                $json = $scenery['cover'];
                $images = empty($json) ? array() : json_decode($json);
                foreach ($images as $i => $src) {
                    ?>
                    <div class="mui-slider-item<?php if ($i == 0) echo ' mui-active'; ?>">
                        <img src="<?php echo $src ?>"/>
                    </div>
                    <?php
                } ?>
            </div>
            <div class="mui-slider-indicator">
                <?php
                for ($i = 0; $i < count($images); $i++) {
                    if ($i == 0) {
                        echo '<div class="mui-indicator mui-active"></div>';
                    } else {
                        echo '<div class="mui-indicator"></div>';
                    }
                }
                ?>
            </div>
        </div>
        <div class="yunyou-scenic-footer">
            <div class="yunyou-scenic-assess">
                <div class="yunyou-scenic-star">
                    <?php
                    if ($scenery['sumtimes'] . '' === '0') {
                        $avg_score = 0.0;
                    } else {
                        $avg_score = $scenery['sumscore'] / $scenery['sumtimes'];
                    }
                    for ($i = 0; $i < 5; $i++) {
                        if ($i < round($avg_score)) {
                            echo '<span class="mui-icon mui-icon-star-filled yunyou-active"></span>';
                        } else {
                            echo '<span class="mui-icon mui-icon-star-filled"></span>';
                        }
                    }
                    ?>
                </div>
                <em><?php printf('%.1f', round($avg_score, 1)) ?></em>
                <p><?php echo $scenery['sumtimes'] . ' 人评价' ?></p>
            </div>
            <div class="yunyou-scenic-introduce">
                <?php
                $text = empty($scenery['introduce']) ? '' : $scenery['introduce'];
                $tip = '...' . '<a style="float: right">（展开）</a>';
                echo build_text($text, 90, $tip);
                ?>
                <script>
                    mui(".yunyou-scenic-introduce").on("tap", "a", function () {
                        var introduce_init = "<?php echo build_text($text, 10000)?>";
                        var p_node = this.parentNode;
                        p_node.innerHTML = introduce_init;
                    });
                </script>
            </div>
            <div class="yunyou-scenic-options">
                <div class="yunyou-pull-left">
                    <?php
                    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
                    if ($user_id == $scenery['creator_id']) {
                        echo '<button id="yunyou-btn-reedit">编辑</button>';
                    } else {
                        $commend = get_cookie('commend');
                        if (preg_match("/{$scenery['id']}/", $commend)) {
                            echo '<button id="yunyou-btn-recommend" disabled>已推荐</button>';
                        } else {
                            echo '<button id="yunyou-btn-recommend">推荐</button>';
                        }
                    }
                    ?>
                </div>
                <div class="yunyou-pull-right">
                    <button id="yunyou-btn-assess">评价</button>
                </div>
            </div>
        </div>
    </div>
    <div class="yunyou-assess">
        <div class="yunyou-assess-header">
            <a data-order="time" class="yunyou-active">最新评价</a>
            &nbsp;/&nbsp;
            <a data-order="like">热门评价</a>
        </div>
        <div class="yunyou-assess-content">
            <li class="yunyou-assess-item" id="yunyou-assess-item-example" style="display: none;">
                <div class="yunyou-assess-item-header">
                    <img src="/source/img/logo.png"/>
                    <h4>用户昵称</h4>
                    <p>2017-01-01</p>
                </div>
                <div class="yunyou-assess-item-content">
                    <div></div>
                    <p>99 赞同 · 9 反对 · 19 评论</p>
                </div>
            </li>
            <ul class="yunyou-assess-list"></ul>
        </div>
        <div class="yunyou-assess-footer">
            <button id="yunyou-btn-more">更多</button>
        </div>
    </div>
    <div class="yunyou-discard yunyou-grayscale">
        <p id="yunyou-tap-fold">查看被折叠的评价 <span class="mui-icon mui-icon-arrowright"></span></p>
        <ul class="yunyou-discard-list" style="display: none;"></ul>
    </div>
</div>
</body>

</html>
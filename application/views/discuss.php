<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>景点评价 - <?php echo $review['scenery']['name'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="/source/dist/css/mui.min.css"/>
    <link rel="stylesheet" type="text/css" href="/source/dist/css/mui.image.css"/>
    <link rel="stylesheet" type="text/css" href="/source/dist/css/icons-extra.css"/>
    <link rel="stylesheet" type="text/css" href="/source/dist/css/iconfont.css"/>
    <link rel="stylesheet" type="text/css" href="/source/css/discuss.css"/>
    <script src="/source/dist/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/source/dist/js/mui.zoom.js" type="text/javascript" charset="utf-8"></script>
    <script src="/source/dist/js/mui.image.js" type="text/javascript" charset="utf-8"></script>
    <script src="/source/js/discuss.js" type="text/javascript" charset="utf-8"></script>
</head>

<body>
<?php require APPPATH . '/views/navbar.php' ?>
<div class="yunyou-content">
    <div class="yunyou-content-header">
        <?php
        $name = $review['scenery']['name'];
        $href = '/detail/' . $review['scenery_id'];
        echo "<h3 data-href='$href'>$name</h3>";
        ?>
        <p><?php echo $review['scenery']['address'] . ' · ' . $review['scenery']['belong'] ?></p>
    </div>
    <div class="yunyou-assess">
        <div class="yunyou-assess-header">
            <?php
            $profile = $review['creator']['profile'];
            $href = '/home/' . $review['creator_id'];
            echo "<img src='$profile' data-href='$href'/>";
            ?>
            <div class="yunyou-assess-info">
                <h4><?php echo $review['creator']['nickname'] ?></h4>
                <div class="yunyou-assess-star">
                    <?php
                    $grade = $review['grade'];
                    for ($i = 0; $i < 5; $i++) {
                        if ($i < $grade) {
                            echo '<span class="mui-icon mui-icon-star-filled yunyou-active"></span>';
                        } else {
                            echo '<span class="mui-icon mui-icon-star-filled"></span>';
                        }
                    }
                    ?>
                </div>
                <p><?php echo date('Y-m-d', $review['update_time']) ?></p>
            </div>
        </div>
        <div class="yunyou-assess-content">
            <?php
            echo build_text($review['content'], 10000);
            ?>
        </div>
        <div class="yunyou-assess-image">
            <?php
            $photos = empty($review['photo']) ? array() : json_decode($review['photo']);
            foreach ($photos as $src) {
                echo '<div class="yunyou-image-pack">';
                echo '<img src="' . $src . '" data-preview-src data-preview-group="1"/>';
                echo '</div>';
            }
            ?>
        </div>
        <?php
        $attitude = empty($opinion['attitude']) ? 0 : $opinion['attitude'];
        ?>
        <div class="yunyou-assess-options">
            <button id="yunyou-btn-zan" class="<?php if ($attitude . '' === '1') echo 'yunyou-active' ?>">
                <span class="mui-icon iconfont icon-zan"></span>
                <em><?php echo $opinion['approve'] ?></em>
            </button>
            <button id="yunyou-btn-cai" class=" <?php if ($attitude . '' === '-1') echo 'yunyou-active' ?>">
                <span class="mui-icon iconfont icon-cai"></span>
                <em><?php echo $opinion['oppose'] ?></em>
            </button>
        </div>
    </div>
    <script>
        var approve_init = "<?php echo get_cookie('approve')?>";
    </script>
    <div class="yunyou-discuss">
        <p>评论（<?php echo empty($sumcom) ? 0 : $sumcom; ?>）</p>
        <li class="yunyou-discuss-item" id="yunyou-discuss-item-example" style="display: none;">
            <img src="/source/img/logo.png"/>
            <div class="yunyou-discuss-content">
                <h5>用户昵称</h5>
                <button>
                    <em>99</em>
                    <span class="mui-icon-extra mui-icon-extra-like"></span>
                </button>
            </div>
        </li>
        <ul class="yunyou-discuss-list"></ul>
    </div>
    <div class="yunyou-comment">
        <div class="yunyou-pull-right">
            <button id="yunyou-btn-comment">发表</button>
            <p>取消</p>
        </div>
        <div class="yunyou-pull-left">
            <textarea placeholder="期待你的评论" id="yunyou-input-comment"></textarea>
        </div>
    </div>
</div>
</body>

</html>
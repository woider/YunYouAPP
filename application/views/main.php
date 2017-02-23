<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>云友点评</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="/source/dist/css/mui.min.css"/>
    <link rel="stylesheet" type="text/css" href="/source/css/main.css"/>
    <script src="/source/dist/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/source/js/main.js" type="text/javascript" charset="utf-8"></script>
</head>

<body>
<?php require APPPATH . '/views/navbar.php' ?>
<div id="yunyou-refresh-container" class="yunyou-content mui-content mui-scroll-wrapper">
    <div class="mui-scroll">
        <li id="yunyou-scenic-example" class="yunyou-scenic-item" style="display: none;">
            <img class="yunyou-scenic-view"/>
            <div class="yunyou-scenic-info">
                <h3>景点名称</h3>
                <p>所在地区 · 所属景区</p>
                <em>9.9</em>
            </div>
        </li>
        <script>
            var scenic_init = <?php
            if (empty($scenery)) {
                echo '[]';
            } else {
                echo 'JSON.parse("' . addslashes(json_encode($scenery)) . '")';
            }
            ?>
        </script>
        <ul class="yunyou-scenic" id="yunyou-scenic-list"></ul>
    </div>
</div>
</body>

</html>
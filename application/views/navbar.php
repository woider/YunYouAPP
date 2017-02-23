<link rel="stylesheet" type="text/css" href="/source/css/navbar.css"/>
<script src="/source/js/navbar.js" type="text/javascript" charset="utf-8"></script>
<header class="yunyou-navbar">
    <div class="yunyou-navbar-logo">
        <h1 id="yunyou-href-logo">云友点评</h1>
    </div>
    <div class="yunyou-navbar-search">
        <input id="yunyou-input-search" type="text" placeholder="搜索内容"/>
        <span id="yunyou-icon-search" class="mui-icon mui-icon-search"></span>
    </div>
    <div class="yunyou-navbar-image">
        <img id="yunyou-img-head" src="<?php
        if (!empty($profile)) {
            echo $profile;
        } else {
            echo '/source/img/logo.png';
        }
        ?>"/>
    </div>
</header>
<div class="yunyou-search" style="display: none;">
    <div class="yunyou-search-done" style="display: none;">
        <li id="yunyou-search-list-item" style="display: none;">
            <h3>景点名称</h3>
            <span class="mui-icon mui-icon-arrowright"></span>
            <p>所在地区 · 所属景区</p>
        </li>
        <ul id="yunyou-search-list"></ul>
    </div>
    <div class="yunyou-search-none" style="display: none;">
        <p>非常抱歉，没有搜索到相关景点。是否要创建该景点？
            <a>创建景区</a>
        </p>
    </div>
</div>
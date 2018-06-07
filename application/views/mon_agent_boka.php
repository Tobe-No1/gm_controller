<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1, maximum-scale=1">
        <link href="<?php echo r_url_new('css/sm.min.css'); ?>" type="text/css" rel="stylesheet">
        <link href="<?php echo r_url_new('css/css.css'); ?>" rel="stylesheet"/>
        <title><?php echo $this->config->item('game_name'); ?>管理后台</title>
    </head>
    <body>

        <div class="page-group page-group-change">
            <div class="page page-current">
                <header class="bar bar-nav">
                    <a class="button button-link button-nav pull-left" href="<?php echo get_url('/index.php/Login/menu'); ?>">
                        <span class="icon icon-left"></span>返回
                    </a>
                    <a class="button button-link button-nav pull-right js-exit" href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
                    <h1 class="title">代理拨卡</h1>
                </header>

                <div class="content native-scroll">
                    <div class="tabs">
                        <!--拨卡-->
                        <div id="tab1" class="tab active">
                            <div class="content-block">
                                <p>我的名称：<?php echo $user_name; ?></p>
                                <p>我的星级：</p>
                                <p>我的房卡：<?= $fangka ?></p>
                                <p>今日拨卡：<?= $baka ?></p>
                                <div class="list-block">
                                    <ul>
                                        <li>
                                            <div class="item-content">
                                                <div class="item-inner">
                                                    <div class="item-title label">代理ID</div>
                                                    <div class="item-input">
                                                        <input placeholder="请输入代理ID" type="text" id="query_user_id">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <p style="margin: 1rem"><a href="#" class="button button-big button-fill" id="queryUserBtnAgent">查找</a></p>
                                <div class="list-block media-list">
                                    <ul id="query_fruit">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--底部版权信息-->
                    <?php include 'common/footer.php'; ?>
                </div>
            </div>
        </div>
        <script src="<?php echo r_url_new('js/zepto.min.js'); ?>"></script>
        <script src="<?php echo r_url_new('js/sm.min.js'); ?>"></script>
        <script src="<?php echo r_url_new('js/share.js'); ?>"></script>
    </body>
</html>
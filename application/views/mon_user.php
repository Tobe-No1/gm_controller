<!DOCTYPE html>
<html lang="en">
    <head>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1, maximum-scale=1">
        <link href="<?php echo r_url_new('css/sm.min.css'); ?>" type="text/css" rel="stylesheet">
        <link href="<?php echo r_url_new('css/css.css'); ?>" rel="stylesheet"/>
        <title><?php echo $this->config->item('game_name'); ?>管理后台</title>
    </head>
</head>
<body>
    <div class="page-group">
        <div class="page page-current">
                <header class="bar bar-nav">
                    <a class="button button-link button-nav pull-left back" href="<?php echo get_url('/index.php/Login/menu'); ?>">
                        <span class="icon icon-left"></span>返回
                    </a>
                    <a class="button button-link button-nav pull-right js-exit" href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
                    <h1 class="title">下线代理</h1>
                </header>
                <div class="content native-scroll">
                    <div class="content-inner">
                        <div class="card">
                            <div class="list-block">
                                <ul>
                                    <?php foreach ($list as $key => $l): ?>
                                        <li>
                                            <div class="item-content">
                                                <div class="item-media"><img class="user-header" src="<?php echo $l['head']; ?>"></div>
                                                <div class="item-inner">
                                                     <div class="item-title">
                                                        <?php echo $l['mg_name']."(".$l['mg_user_id'].")"; ?>
                                                    </div>
                                                    <div class="item-after color-gray mg_name" uid="<?=$l['mg_user_id']?>"  >修改</div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--底部版权信息-->
                    <?php include 'common/footer.php'; ?>
                </div>
        </div>
    </div>
</body>
<script src="<?php echo r_url_new('js/zepto.min.js'); ?>"></script>
<script src="<?php echo r_url_new('js/sm.min.js'); ?>"></script>
<script src="<?php echo r_url_new('js/share.js'); ?>"></script>
</html>

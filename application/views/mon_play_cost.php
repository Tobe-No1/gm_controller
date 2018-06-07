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
        <div class="page-group">
            <div id="page-infinite-scroll-bottom" class="page page-current">
                <header class="bar bar-nav">
                    <a class="button button-link button-nav pull-left back" href="<?php echo get_url('/index.php/Login/menu'); ?>">
                        <span class="icon icon-left"></span>返回
                    </a>
                    <a class="button button-link button-nav pull-right js-exit" href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
                    <h1 class="title">游戏消耗统计</h1>
                </header>
                <div class="content infinite-scroll native-scroll" data-distance="100">
                    <div class="list-block">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>&nbsp;&nbsp;&nbsp;</th>
                                    <th>统计类型</th>
									<th>消耗</th>
                                    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                </tr>
                            </thead>

                            <tbody class="list-container" style="text-align: left;">
                            <?php /*var_dump($list);die();*/?>
                                <?php foreach ($list as $key => $l): ?>
                                <?php if($key=='麻将'){
                                    ?><tr style="font:bold 12px/1.5em 'Microsoft YaHei';">
                                        <td>&nbsp;</td>
                                        <td style="text-align: right;"><?php echo $key ?></td>
                                        <td><?php echo $l ?></a></td>
                                        <td>&nbsp;</td>
                                        </tr>
                                    <?php foreach($majiang as $k => $v): ?>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td style="text-align: right;"><?php echo $k ?></td>
                                        <td><?php echo $v ?></a></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <?php endforeach; ?>
                                        <?php }else{?>
                                    <tr style="font:bold 12px/1.5em 'Microsoft YaHei';">
                                        <td>&nbsp;</td>
                                        <td style="text-align: right;"><?php echo $key ?></td>
                                        <td><?php echo $l ?></a></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                <?php }?><?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- 加载提示符 -->
                    <div class="infinite-scroll-preloader" style="display: none">
                        <div class="preloader">
                        </div>
                    </div>
                </div>
            </div>
        <script src="<?php echo r_url_new('js/zepto.min.js'); ?>"></script>
            <script src="<?php echo r_url_new('js/sm.min.js'); ?>"></script>
            <script src="<?php echo r_url_new('js/share.js'); ?>"></script>
    </body>
</html>

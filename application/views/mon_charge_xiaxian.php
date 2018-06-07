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
        <div class="page-group page-group-change charge-inq">
            <div class="page page-current">
                <header class="bar bar-nav">
                     <a class="button button-link button-nav pull-left back" href="<?php echo get_url('/index.php/Login/menu'); ?>">
                        <span class="icon icon-left"></span>返回
                    </a>
                    <a class="button button-link button-nav pull-right js-exit" href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
                    <h1 class="title">下线充值明细</h1>
                </header>
                <div class="content native-scroll">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>玩家名称</th>
                                <th>玩家UID</th>
                                <th>金币金额</th>
                                <th>房卡金额</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php foreach ($clist as $key => $l): ?>
                                <tr>
                                    <td><?= $l['name'] ?></td>
                                    <td><?= $l['uid'] ?></td>
                                    <td><?= sprintf("%.2f", $l['gold_amount'] / 100) ?></td>
                                    <td><?= sprintf("%.2f", $l['card_amount'] / 100) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!--底部版权信息-->
                    <?php include 'common/footer.php'; ?>
                </div>
            </div>
        </div>
    </body>
</html>
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
    <div id="page-charge-inq" class="page page-current">
        <header class="bar bar-nav">
            <a class="button button-link button-nav pull-left" href="<?php echo get_url('/index.php/Login/menu'); ?>">
                <span class="icon icon-left"></span>返回
            </a>
            <a class="button button-link button-nav pull-right js-exit" href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
            <h1 class="title">七天乐排行</h1>
        </header>

        <div class="content native-scroll">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>排名</th>
                    <th>用户id</th>
                    <th>玩家名</th>
                    <th>玩家别名</th>
                    <th>局数</th>
                    <th>积分</th>
                    <th>日期</th>
                </tr>
                </thead>

                <tbody class="list-container">
                <?php /*var_dump($players);die();*/?>
                <?php foreach ($players as $key => $l): ?>
                    <tr>
                        <td ><?php echo $key+1; ?></td>
                        <td ><?php echo $l['uid'] ?></td>
                        <td ><?php echo $l['name'] ?></td>
                        <td ><?php echo $l['name_alias'] ?></td>
                        <td ><?php echo $l['ju']; ?></td>
                        <td ><?php echo 100-$key ?></td>
                        <td ><?php echo $l['t']; ?></td>
                    </tr>

                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="page-on">
                <?php echo $page_link; ?>
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

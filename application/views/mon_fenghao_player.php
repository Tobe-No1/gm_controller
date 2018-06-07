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
                    <h1 class="title">封号列表</h1>
                </header>

                <div class="content native-scroll">
                    <form method="get" action="<?php echo get_url('/index.php/User/fenghao_player'); ?>">
                    <!--查询条件-->
                    <div class="list-block">
                        <ul>
                            <li class="item-content">
                                <div class="item-inner">
                                    <div class="item-title label">用户ID</div>
                                    <div class="item-input">
                                        <input placeholder="输入用户ID" type="text" name="uid" value="<?php if(isset($show_condition['uid'])) { echo $show_condition['uid']; }?>">
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <p class="buttom-inq"><input type="submit" class="button button-big button-fill" value="搜索"></p>
                    </div>
                    </form>
                    <!--表单-->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>用户id</th>
                                <th>昵称</th>
                                <th>注册时间</th>
                                <th>最后登录时间</th>
                                <th>消耗卡数量</th>
                                <th>房卡数量</th>
                                <th>当前房号</th>
                                <th>群主(0否,1是)</th>
                                <th>封号id</th>
                                <th>封号代理</th>
                                <th>邀请码</th>
                                <th>操作</th>
                            </tr>
                        </thead>

                        <tbody class="list-container">
                                <?php foreach ($players as $key => $l): ?>
                            <tr>
                                <td ><?php echo $l['uid'] ?></td>
                                <td ><?php echo $l['name'] ?></td>
                                <td ><?php echo date('Y-m-d H:i:s',$l['create_time']); ?></td>
                                <td ><?php echo date('Y-m-d H:i:s',$l['last_login_time']); ?></td>
                                <td ><?php echo $l['cost_card']; ?></td>
                                <td ><?php echo $l['card'] ?></td>
                                <td ><?php echo $l['room_id'] ?></td>
                                <td id="qunzhu<?php echo $l['uid'] ?>"><?php echo $l['qunzhu'] ?></td>
                                <td ><?php echo $l['locker_id'] ?></td>
                                <td ><?php echo $l['locker_name'] ?></td>
                                <td  id="id<?php echo $l['uid'] ?>" agent_id="<?php echo $l['invite_id'] ?>" class="show_inviteid"><?php echo $l['invite_id'] ?></td>
                                <td  id="status<?php echo $l['uid'] ?>">
                                    <?php
                                        if($cur_login_id == $l['locker_id'] || $cur_login_id == 1) {
                                            ?>
                                           <span class="fenghao" uid="<?php echo $l['uid'] ?>">解封</span>
                                    <?php
                                        }
                                    ?>
                                </td>
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

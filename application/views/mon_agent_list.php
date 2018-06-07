
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

    <div class="page-group page-group-change charge-inq">
        <div id="page-charge-inq" class="page page-current">
            <header class="bar bar-nav">
                <a class="button button-link button-nav pull-left" href="<?php echo get_url('/index.php/Login/menu'); ?>">
                    <span class="icon icon-left"></span>返回
                </a>
                <a class="button button-link button-nav pull-right js-exit" href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
                <h1 class="title">代理管理</h1>
            </header>

            <div class="content native-scroll">
                <!--查询条件-->
                <form action="<?php echo get_url('/index.php/Gm/agent_list'); ?>" method="post">
                    <div class="list-block">
                        <ul>
                            <li class="item-content">
                                <div class="item-inner">
                                    <div class="item-title label">代理ID</div>
                                    <div class="item-input">
                                        <input placeholder="输入代理ID" type="text" name="user_id" value="<?php if (isset($show_condition['user_id'])) {
    echo $show_condition['user_id'];
} ?>">
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
                             <th>代理id</th>
                            <th>代理名字</th>
                            <th>电话</th>
                            <th>后台卡数</th>
                            <th>创建时间</th>
                            <th>代理等级</th>
                            <th>上线id</th>
                            <th>可设置群主次数</th>
                            <th>已设置群主次数</th>
                            <th>邀请码</th>
                            <th>操作</th>
                        </tr>
                    </thead>

                    <tbody class="list-container">
                        <?php
                        $role_names = $this->config->item('role_names');
                        foreach ($list as $key => $l):
                            ?>
                            <tr>
                                <td><?= $l['mg_user_id'] ?></td>
                                <td><?= $l['mg_name'] ?></td>
                                <td id="phone<?php echo $l['mg_user_id'] ?>"><?php echo $l['phone'] ?></td>
                                <td><?= $l['card'] ?></td>
                                <td><?php echo $l['create_time']; ?></td>
                                <td><?php echo $role_names[$l['level']]; ?></td>
                                <td><?php echo $l['p_mg_user_id']; ?></td>
                                <td id="qunzhu<?php echo $l['mg_user_id'] ?>"><?php echo $l['can_qunzhu']; ?></td>
                                <td><?php echo $l['used_qunzhu']; ?></td>
                                <td id="id<?php echo $l['mg_user_id'] ?>"><?php echo $l['invotecode']; ?></td>
                                <td>
                                    <span class="recommendid" uid="<?php echo $l['mg_user_id'] ?>">修改代理</span>
                                   <!-- <span class="inviteid" uid="<?php /*echo $l['mg_user_id'] */?>">修改邀请码</span>-->&nbsp;&nbsp;
                                    <span class="changepwd" uid="<?php echo $l['mg_user_id'] ?>">修改密码</span>&nbsp;&nbsp;
                                    <span class="change_phone" uid="<?php echo $l['mg_user_id'] ?>">修改手机号码</span>&nbsp;&nbsp;
                                    <!--<span class="qunzhu_num" uid="<?php /*echo $l['mg_user_id'] */?>">修改群主次数</span>&nbsp;&nbsp;-->
                                </td>
                            </tr>
<?php endforeach; ?>
                        <tr style="font-weight: bold;">
                            <td>总代理数</td>
                            <td colspan="7"><?php echo $count; ?></td>
                        </tr>
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
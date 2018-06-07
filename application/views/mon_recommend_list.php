
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
                <h1 class="title">代理推荐查询</h1>
            </header>

            <div class="content native-scroll">
                <!--查询条件-->
                <div class="list-block">
                    <form method="get" action="<?php echo get_url('/index.php/Statistic/recommend_query'); ?>">
                        <ul>
                            <li class="item-content">
                                <div class="item-inner">
                                    <div class="col-50">
                                        <div class="color-gray">开始时间</div>
                                        <input class="input-time" type="text" placeholder="请选择开始时间" id="begin" readonly="true" name="stime" value="<?php echo $show_condition['stime'] ?>">
                                    </div>
                                    <div class="col-50">
                                        <div class="color-gray">结束时间</div>
                                        <input class="input-time" type="text" placeholder="请选择开始时间" id="over" readonly="true" name="etime" value="<?php echo $show_condition['etime'] ?>">
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <p class="buttom-inq"><input type="submit" value="搜索" class="button button-big button-fill"></p>
                    </form>
                </div>

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
                           <th>代理下线消费总额</th>
                        </tr>
                    </thead>

                    <tbody class="list-container">
                        <?php
                        $role_names = $this->config->item('role_names');
                       /* var_dump($list);*/
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
                                <td><?php echo "￥".number_format($l['total_money']/100,2); ?></td>
                            </tr>
<?php endforeach; ?>
                        <tr style="font-weight: bold;">
                            <td>总代理数</td>
                            <td colspan="7"><?php echo $count; ?></td>
                            <td>总计</td>
                            <td colspan="7"><?php echo "￥".number_format($amount/100,2); ?></td>
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
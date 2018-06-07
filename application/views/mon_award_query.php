
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
                <h1 class="title">中奖查询</h1>
            </header>

            <div class="content native-scroll">
                <!--查询条件-->
                <form action="<?php echo get_url('/index.php/User/award_query'); ?>" method="post">
                    <div class="list-block">
                        <ul>
                            <li>
                                <div class="item-content">
                                    <div class="item-inner">
                                        <div class="item-title label">玩家ID</div>
                                        <div class="item-input">
                                            <input type="text" placeholder="输入玩家ID" name="uid">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item-content">
                                    <div class="item-inner">
                                        <div class="item-title label">中奖号码</div>
                                        <div class="item-input">
                                            <input type="text" placeholder="中奖号码" name="award_key">
                                        </div>
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
                    <tr><td colspan="7">等级对应奖励如下    1 : 1张，     2 : 2张，   3 : 5张，   4 : 10张，  5 : 20张，  6 : 50张，  7 : 100张， 8 : 150张， 9 : 200张， 10 : 300张.(单位：房卡)</td></tr>
                        <tr>
                             <th>玩家id</th>
                            <th>中奖时间</th>
                            <th>中奖号码</th>
                            <th>中奖等级</th>
                            <th>是否领取(0:未颁，1:已颁)</th>
                            <th>领取时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody class="list-container">
                        <?php
                        $role_names = $this->config->item('role_names');
//                        var_dump($demo);
                        foreach ($list as $key => $l):
                            ?>
                            <tr>
                                <td><?= $l['uid'] ?></td>
                                <td><?= date('Y-m-d',$l['create_time']) ?></td>
                                <td><?= $l['key'] ?></td>
                                <td><?= $l['award'] ?></td>
                                <td><?= $l['status']==1? "已颁奖":"未颁奖" ?></td>
                                <td><?php echo date('Y-m-d',$l['get_time']); ?></td>
                                <td>
                                    <span class="awardid" uid="<?php echo $l['uid']?>" key="<?php echo $l['key']?>" grade = "<?php echo $l['award']?>" status = "<?php echo $l['status']?>">颁奖</span>&nbsp;
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
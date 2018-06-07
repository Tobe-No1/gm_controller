
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
                <h1 class="title">充值明细</h1>
            </header>

            <div class="content native-scroll">
                <form action="<?php echo get_url('/index.php/Statistic/user_charge'); ?>" method="post">
                <!--查询条件-->
                <div class="list-block">
                    <ul>
                        <li class="item-content">
                            <div class="item-inner">
                                <div class="col-50">
                                    <div class="color-gray">开始时间</div>
                                    <input class="input-time" type="text" placeholder="请选择开始时间" id="begin" readonly="true" name="stime1" value="<?php echo $show_condition['stime'] ?>">
                                </div>
                                <div class="col-50">
                                    <div class="color-gray">结束时间</div>
                                    <input class="input-time" type="text" placeholder="请选择开始时间" id="over" readonly="true" name="etime1" value="<?php echo $show_condition['etime'] ?>">
                                </div>
                            </div>
                        </li>
                        <li class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">状态</div>
                                <div class="item-input">
                                    <select name="status">
                                        <option value="-1">全部</option>
                                        <option value="0" <?php
                                        if ($show_condition['status'] == 0) {
                                            echo 'selected="selected"';
                                        }
                                        ?>>未支付</option>
                                        <option value="1" <?php
                                        if ($show_condition['status'] == 1) {
                                            echo 'selected="selected"';
                                        }
                                        ?>>已支付</option>
                                        <option value="2" <?php
                                                if ($show_condition['status'] == 2) {
                                                    echo 'selected="selected"';
                                                }
                                                ?>>异常1</option>
                                        <option value="3" <?php
                                                if ($show_condition['status'] == 3) {
                                                    echo 'selected="selected"';
                                                }
                                                ?>>异常2</option>
                                    </select>
                                </div>
                            </div>
                        </li>
                        <li class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">玩家ID</div>
                                <div class="item-input">
                                    <input placeholder="输入玩家ID" type="text" name="uid" value="<?php echo $show_condition['uid'] ?>">
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
                            <th>用户名</th>
                            <th>玩家id</th>
                            <th>订单号</th>
                            <th>充值金额</th>
                            <th>时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>

                    <tbody class="list-container">
                        <?php foreach ($list as $key => $l):
                            ?>
                        <tr>
                        <td><?= $l['name'] ?></td>
                        <td><?= $l['uid'] ?></td>
                        <td><?= $l['charge_id'] ?></td>
                        <td><?php echo intval($l['amount'] / 100); ?></td>
                        <td><?php echo date('Y-m-d H:i:s', $l['pay_time']); ?></td>
                        <td id="charge_id<?php echo $l['charge_id']; ?>">
                        <?php
                        echo $status[$l['status']];
                        if ($l['status'] == 2) {
                            echo '<a style="color:blue" href="#" charge_id="' . $l['charge_id'] . '" class="doaction">处理</a>';
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
    <script>
         $(function () {
        $('.doaction').click(function () {
            var charge_id = $(this).attr('charge_id');
            console.log(charge_id)
            $.post("<?php echo get_url('/index.php/Gm/ajax_docharge'); ?>", {'charge_id': charge_id}, function (res) {
                if (res.status == '1') {
                    $('#charge_id' + charge_id).html("已支付");
                } else {
                    alert('处理失败');
                }
            }, 'json');
        });
    });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1, maximum-scale=1">
        <link href="<?php echo r_url_new('css/sm.min.css'); ?>" type="text/css" rel="stylesheet">
        <link href="<?php echo r_url_new('css/css.css'); ?>" rel="stylesheet"/>
        <title><?php echo $this->config->item('game_name'); ?>管理后台</title>
        <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
    <script src="<?php echo r_url('layer/layer.js'); ?>"></script>
    <script>
        function show_zoushi(id){
		layer.open({
		  type: 2,
		  shadeClose: true,
		  shade: 0.8,
		  area: ['100%', '90%'],
		  content: '<?php echo get_url('/index.php/Stat/lately_data'); ?>?user_id='+id //iframe的url
		}); 
            }
    </script>
    </head>
    <body>
        <div class="page-group-change charge-inq">
            <div class="page page-current">
                <header class="bar bar-nav">
                    <a class="button button-link button-nav pull-left back" href="<?php echo get_url('/index.php/Login/menu'); ?>">
                        <span class="icon icon-left"></span>返回
                    </a>
                    <a class="button button-link button-nav pull-right js-exit" href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
                    <h1 class="title">下线代理充值查询</h1>
                </header>

                <div class="content native-scroll">
                    <p class="total"><i class="color-green"><?= $user_info['mg_user_name'] ?></i>下线代理充值列表:总计:<i class="color-orange"><?php echo $xiaxian_total; ?></i></p>
                    <!--表单-->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>代理</th>
                                <th>代理等级</th>
                                <th>下线</th>
                                <th>走势图</th>
                                <th>金额</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($ulistb) && is_array($ulistb)) {
                                foreach ($ulistb as $key => $l): ?>
                                    <tr>
                                        <td><?= $l['mg_user_id'] ?></td>
                                        <td><span style="color: green"><?php echo $this->role_names[$l['level']]; ?></span></td>
                                        <td>
                                            <?php if ($l['total_money'] >= 0) { ?>
                                                <a href="<?php echo get_url('/index.php/Stat/charge_agent'); ?>?s=<?= $s ?>&e=<?= $e ?>&user_id=<?= $l['mg_user_id'] ?>" class="c-ff8a00">展开</a>
                                                <?php
                                            } else {
                                                echo '<a href="#" class="c-333" >展开</a>';
                                            }
                                            ?>
                                        </td>
                                        <td onclick="javascript:show_zoushi(<?= $l['mg_user_id'] ?>)" >走势</td>
                                        <?php
                                        if ($l['total_money'] > 0) {
                                            ?>
                                            <td style="color: #dd514c">￥<?php echo sprintf("%.2f", $l['total_money']); ?></td>
                                            <?php
                                        } else {
                                            echo '<td style="text-align: center;">0</td>';
                                        }
                                        ?>
                                    </tr>
    <?php endforeach;
} ?>
                        </tbody>
                    </table>

                    <p class="total"><i class="color-green"><?= $user_info['mg_user_name'] ?></i>直接玩家充值列表:<i class="color-orange"><?php echo $self_total; ?></i></p>
                    <!--表单-->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>玩家名称</th>
                                <th>玩家UID</th>
                                <th>流水号</th>
                                <th>时间</th>
                                <th>金额</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php foreach ($slist as $key => $l): ?>
                                <tr>
                                    <td><?= $l['name'] ?></td>
                                    <td><?= $l['uid'] ?></td>
                                    <td><?= $l['charge_id'] ?></td>
                                    <td><?php echo date('Y-m-d H:i:s', $l['pay_time']); ?></td>
                                    <td>￥ <?= sprintf("%.2f", $l['amount'] / 100) ?></td>
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
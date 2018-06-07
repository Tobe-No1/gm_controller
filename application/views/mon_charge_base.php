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
                    <h1 class="title">充值查询</h1>
                </header>
		<div class="content native-scroll">
			<!--查询条件-->
			<div class="list-block">
                                <form method="get" action="<?php echo get_url('/index.php/Stat/charge_base'); ?>">
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

			<!--查询结果-->
			<div class="card">
                            <div class="card-content">
                                    <div class="card-content-inner red" style="text-align: center; font-size: 0.8rem;font-weight: 700">
                                            总充值金额：<?php echo sprintf("%.2f",$total_money); ?>元
                                    </div>
                            </div>

                            <div class="list-block" style="font-size: 0.6rem; border-top:1px solid #e7e7e7">
                                <ul>
                                    <a href="<?php echo get_url('/index.php/Stat/charge_xiaxian'); ?>?s=<?php echo $show_condition['stime'] ?>&e=<?php echo $show_condition['etime'] ?>" class="item-content item-link">
                                            <div class="item-inner">
                                                    <div class="item-title">我直接发展的玩家总充值：￥<?=sprintf("%.2f",$xiaxian)?></div>
                                                    <div class="item-after color-gray">详情</div>
                                            </div>
                                    </a>
                                    <a href="<?php echo get_url('/index.php/Stat/charge_agent'); ?>?s=<?php echo $show_condition['stime'] ?>&e=<?php echo $show_condition['etime'] ?>" class="item-content item-link">
                                            <div class="item-inner">
                                                    <div class="item-title">我的下线代理玩家总充值：￥<?php echo  sprintf("%.2f",$daili); ?></div>
                                                    <div class="item-after color-gray">详情</div>
                                            </div>
                                    </a>
                                </ul>
                            </div>
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

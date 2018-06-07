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
<div class="page-group page-group-change">
	<div class="page page-current">
		<header class="bar bar-nav">
                     <a class="button button-link button-nav pull-left" href="<?php echo get_url('/index.php/Login/menu'); ?>">
                        <span class="icon icon-left"></span>返回
                    </a>
                    <a class="button button-link button-nav pull-right js-exit" href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
                    <h1 class="title">活跃编辑</h1>
                </header>
		<div class="content native-scroll">
                    <form action="<?php get_url('/index.php/activity/edit'); ?>" method="post" id="rank-remark-form" enctype="multipart/form-data">
			<div class="list-block">
				<ul>
					<li>
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">QQ</div>
								<div class="item-input">
									<input type="text" placeholder="输入QQ号码" name="qq" value="<?php echo $res['qq'] ?>">
								</div>
							</div>
						</div>
					</li>
					<li>
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">微信</div>
								<div class="item-input">
									<input type="text" placeholder="输入微信号码" name="wx" value="<?php echo $res['wx'] ?>">
								</div>
							</div>
						</div>
					</li>

					<li>
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">delegate</div>
								<div class="item-input">
									<input type="text" placeholder="输入delegate" name="delegate" value="<?php echo $res['delegate'] ?>">
								</div>
							</div>
						</div>
					</li>

					<li>
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">比例</div>
								<div class="item-input">
									<input type="text" placeholder="输入一个数字" name="rat" value="<?php echo $res['rat'] ?>"></p>
								</div>
							</div>
						</div>
					</li>

					<li>
						<div class="item-content-tow">
							<p class="tow-title">支付通道</p>
							<div style="margin-bottom: 0.6rem">
								<span class="color-gray">&nbsp&nbsp&nbsp微信：</span>
								<label class="label"><input type="radio" name="wechat" value="0" checked="checked">无</label>
								<label class="label"><input type="radio" name="wechat" value="6" <?php if($wechat == '6') { echo 'checked="checked"';}?>>苏达</label>
								<label class="label"><input type="radio" name="wechat" value="4" <?php if($wechat == '4') { echo 'checked="checked"';}?>>掌支付</label>
                                                                <label class="label"><input type="radio" name="wechat" value="7" <?php if($wechat == '7') { echo 'checked="checked"';}?>>爱贝</label>
							</div>
							<div>
								<span class="color-gray">支付宝：</span>
								<label class="label"><input type="radio" name="alipay" value="0" checked="checked">无</label>
								<label class="label"><input type="radio" name="alipay" value="5" <?php if($alipay == '5') { echo 'checked="checked"';}?>>苏达</label>
							</div>
						</div>
					</li>
					<li>
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">弹出公告</div>
								<div class="item-input">
									 <select name="is_show_notice">
                                                                            <option value="0">否</option>
                                                                            <option value="1" <?php if($res['is_show_notice'] == 1 ){ echo "selected=\"selected\""; } ?>>是</option>
                                                                        </select>
								</div>
							</div>
						</div>
					</li>

					<li>
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">游戏公告图片</div>
								<div class="item-input">
                                                                        <input type="file" name="hd_img" value=""><br/><br/><img src="<?php echo $res['hd_img']; ?>" alt="" width="100px" >
								</div>
							</div>
						</div>
					</li>

					<li>
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">后台公告图片</div>
								<div class="item-input">
                                                                        <input type="file" name="show_photo" value=""><br/><br/><img src="<?php echo $res['show_photo']; ?>" alt="" width="100px" >
								</div>
							</div>
						</div>
					</li>

					<li class="align-top">
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">轮播公告</div>
								<div class="item-input">
                                                                    <textarea placeholder="请输入轮播公告" name="fix_msg"><?php echo $res['fix_msg'] ?></textarea>
								</div>
							</div>
						</div>
					</li>
                                        
                                        <li>
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">开始时间</div>
								<div class="item-input">
									<input type="text" class="" placeholder="开始时间" name="start_time" value="<?php echo date('Y-m-d H:i:s',$res['start_time']) ?>"></p>
								</div>
							</div>
						</div>
					</li>
                                        
                                        <li>
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">结束时间</div>
								<div class="item-input">
									<input type="text" placeholder="结束时间" name="end_time" value="<?php echo date('Y-m-d H:i:s',$res['end_time']); ?>"></p>
								</div>
							</div>
						</div>
					</li>
                                        
                                        <li>
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">间隔</div>
								<div class="item-input">
									<input type="text" placeholder="间隔" name="jiange" value="<?php echo $res['jiange'] ?>"></p>
								</div>
							</div>
						</div>
					</li>
                                        
                                        <li>
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">轮播次数</div>
								<div class="item-input">
									<input type="text" placeholder="轮播次数" name="send_times" value="<?php echo $res['send_times'] ?>"></p>
								</div>
							</div>
						</div>
					</li>
                                        
                                        <!--<li>
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">类型</div>
								<div class="item-input">
                                                                    <select name="mtype">
                                                                        <option value="1">登录时轮播公告</option>
                                                                        <option value="2" <?php /*if($res['mtype'] == 1 ){ echo "selected=\"selected\""; } */?>>定时轮播公告</option>
                                                                    </select>
								</div>
							</div>
						</div>
					</li>-->
                                        
				</ul>
			</div>
                        <p style="margin: 1rem"><input type="submit" value="发布" class="button button-big button-fill"></p>
			<!--底部版权信息-->
                        <input type="hidden" name="id" value="<?php echo $id ?>">
                        <input type="hidden" name="token" value="<?php echo $token ?>">
                      </form>
			<?php include 'common/footer.php'; ?>
		</div>
	</div>
</div>
<script src="<?php echo r_url_new('js/zepto.min.js'); ?>"></script>
<script src="<?php echo r_url_new('js/sm.min.js'); ?>"></script>
<script src="<?php echo r_url_new('js/share.js'); ?>"></script
</body>
</html>

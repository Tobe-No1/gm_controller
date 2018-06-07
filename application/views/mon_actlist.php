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

<div class="page-group">
	<div class="page page-current">
		<header class="bar bar-nav">
                     <a class="button button-link button-nav pull-left" href="<?php echo get_url('/index.php/Login/menu'); ?>">
                        <span class="icon icon-left"></span>返回
                    </a>
                    <a class="button button-link button-nav pull-right js-exit" href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
                    <h1 class="title">活动配置</h1>
                </header>
		<div class="content native-scroll">
			<div class="content-inner">
				<div class="content">
					<!--表单-->
					<table class="table table-striped">
						<thead>
						<tr>
							<th>操作</th>
							<th>广播类型</th>
							<th>比例</th>
							<th>显示后台公告</th>
							<th>滚动公告</th>
							<th>游戏公告图片</th>
							<th>后台公告图片</th>
						</tr>
						</thead>

						<tbody class="list-container">
                                                    <?php foreach($list as $l):?>
                                                    <tr>
                                                      <td class="bt" >
                                                        <a href="<?php echo get_url('/index.php/activity/edit/'.$l['id']) ?>">编辑</a>
                                                      </td>
                                                        <td><?php if($l['mtype']==1){echo '大厅广播';}else{echo '游戏内广播';}?></td>
                                                      <td><?php echo $l['rat'];?></td>
                                                      <td><?php if($l['is_show_notice']==1){ echo '是';}else{ echo '否'; } ?></td>
                                                      <td><textarea name="" id="" style="border:none;width:100px; height:80px;overflow-y:visible" readonly><?php echo $l['fix_msg']?></textarea></td>
                                                      <td><img src="<?php echo $l['hd_img']; ?>" alt="" width="50px"></td>
                                                      <td><img src="<?php echo $l['show_photo']; ?>" alt="" width="50px"></td>
                                                    </tr>
                                                  <?php endforeach;?>
						</tbody>
					</table>

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
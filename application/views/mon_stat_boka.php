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
			<a class="button button-link button-nav pull-left back" href="<?php echo get_url('/index.php/Login/menu'); ?>">
                        <span class="icon icon-left"></span>返回
                    </a>
                    <a class="button button-link button-nav pull-right js-exit" href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
                    <h1 class="title">拨卡统计</h1>
		</header>

		<div class="content native-scroll">
			<div class="content-block-title">当前位置：拨卡统计</div>
                        <form action="<?php echo get_url('/index.php/Statistic/boka'); ?>" method="post">
			<!--查询条件-->
			<div class="list-block">
				<ul>
					<li class="item-content">
						<div class="item-inner">
							<div class="col-50">
								<div class="color-gray">开始时间</div>
								<input class="input-time" type="text" placeholder="请选择开始时间" id="begin" value="<?php echo $show_condition['stime'] ?>" name="stime1">
							</div>
							<div class="col-50">
								<div class="color-gray">结束时间</div>
								<input class="input-time" type="text" placeholder="请选择开始时间" id="over" value="<?php echo $show_condition['etime'] ?>" name="etime1">
							</div>
						</div>
					</li>
					<li class="item-content">
						<div class="item-inner">
							<div class="item-title label">类别</div>
							<div class="item-input">
								<select name="flag" >
                                                                <option value="0">所有</option>
                                                                <?php foreach ($flag_params as $key => $value) {
                                                                  if($show_condition['flag'] == $value['value'] && $show_condition['flag']){
                                                                    echo '<option value="'.$value['value'].'" selected>'.$value['remark'].'</option>';
                                                                  }else{
                                                                    echo '<option value="'.$value['value'].'">'.$value['remark'].'</option>';
                                                                  }
                                                                } ?>
                                                              </select>
							</div>
						</div>
					</li>

					<li class="item-content">
						<div class="item-inner">
							<div class="item-title label">会员</div>
							<div class="item-input">
								<select name="user_id" >
                                                                <option value="-2">全部</option>
                                                                <?php foreach ($user_list_show as $key => $value) {
                                                                  if($show_condition['user_id'] == $value['mg_user_id'] && $show_condition['user_id']){
                                                                    echo '<option value="'.$value['mg_user_id'].'" selected>'.$value['mg_user_name'].'('.$value['mg_name'].')</option>';
                                                                  }else{
                                                                    echo '<option value="'.$value['mg_user_id'].'">'.$value['mg_user_name'].'('.$value['mg_name'].')</option>';
                                                                  }
                                                                } ?>
                                                              </select>
							</div>
						</div>
					</li>
					<li class="item-content">
						<div class="item-inner">
							<div class="item-title label">接收id</div>
							<div class="item-input">
                                                            <input type="text" placeholder="接收id" name="accept_user_id"  value="<?php echo $show_condition['accept_user_id'] ?>" id="etime1">
							</div>
						</div>
					</li>
				</ul>
                                <p class="buttom-inq"><input type="submit" class="button button-big button-fill" value="搜索"></p>
                                </form>
			</div>

			<!--表单-->
			<table class="table table-striped">
				<thead>
				<tr>
					<th>用户名</th>
                                        <th>代理id</th>
                                        <th>接受方ID</th>
                                        <th>类别</th>
                                        <th>等级</th>
                                        <th>数量</th>
                                        <th>时间</th>
				</tr>
				</thead>

				<tbody class="list-container">
                                <?php
                                $role_names = $this->config->item('role_names');
                                foreach($list as $key => $l):?>
                                  <tr>
                                    <td ><?=$l['mg_name']?></td>
                                    <td ><?=$l['user_id']?></td>
                                    <td ><?=$l['accept_user_id']?></td>
                                    <td ><?=$l['flag']?></td>
                                    <td ><?php echo $role_names[$l['level']];?></td>
                                    <td ><?=$l['count']?></td>
                                    <td ><?=$l['create_time']?></td>
                                  </tr>
                                <?php endforeach;?>
                                
                                <tr>
                                    <td style="font-weight: bold;">总卡数</td>
                                    <td><?php echo $total; ?></td>
                                    <td style="font-weight: bold;">总记录数</td>
                                    <td colspan="4" ><?php echo $count; ?></td>
                                  </tr>
				</tbody>
			</table>
                           <div class="page-on">
<?php echo $page_link; ?>
                </div>     
			<!--底部版权信息-->
			<?php include 'common/footer.php';?>
		</div>
	</div>
</div>
<script src="<?php echo r_url_new('js/zepto.min.js'); ?>"></script>
<script src="<?php echo r_url_new('js/sm.min.js'); ?>"></script>
<script src="<?php echo r_url_new('js/share.js'); ?>"></script>
</body>
</html>
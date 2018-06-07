<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
  <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
  <meta charset="utf-8" />
  <meta content="width=device-width,minimum-scale=1.0,maximum-scale=1.0" name="viewport" />
  <meta content="yes" name="apple-mobile-web-app-capable" />
  <meta content="black" name="apple-mobile-web-app-status-bar-style" />
  <meta content="telephone=no" name="format-detection" />
  <link href="<?php echo r_url('css/global.css'); ?>" rel="stylesheet"/>
  <title><?php echo $this->config->item('game_name');?>管理后台</title>
</head>
<body>
<div class="container b-n">
  <header>
    <nav class="n_t">
      <span class="n_r">
        <img src="<?php echo r_url('imgs/mon/rr.png'); ?>" onClick="javascript :history.back();">
      </span>
      <span>购卡返现</span>
      <span class="n_h">
        
        <a href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
      </span>
    </nav>
  </header>
  <div class="content">
    <div class="login js-slide">
      <div class="detail-tag js-slide">
        <div class="slide-con js-slide-con">
          <div class="list-item pho top p_b">
            <div class="wl_9">
              【<a href="<?php echo get_url('/index.php/Login/menu')?>">首页</a>】欢迎您：<span style="color: red"><?php echo  $this->role_names[$this->__user_info['level']]; ?></span> (<?php echo $this->__user_info['mg_user_name']?>)
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="content wl_9 c-999 clm">
      <div>
          <span style="color:green; font-weight:bold;"> [<?=$user_info['mg_user_name']?>]</span>
          总计:<span class='n_rw'>[ ￥<?php echo $total;?> ]</span>
      </div>
      <hr color="#3D86BD" size="1px">
      <div>
        <table style="width: 100%">
          <tr>
            <th style="width: 10%">代理ID</th>
            <th style="width: 10%">代理等级</th>
            <th style="width: 20%">交易时间</th>
            <th style="width: 20%">房卡数量</th>
            <th style="width: 20%">返现</th>
          </tr>
          <?php foreach($lists as $key => $l):?>
          <tr>
            <td><?=$l['user_id']?></td>
            <td><?php echo $this->role_names[$l['level']]; ?></td>
            <td><?php echo $l['pay_time']; ?></td>
            <td><?php echo $l['card_num']; ?></td>
            <td>￥ <?php echo $l['fan']; ?></td>
          </tr>
          <?php endforeach;?>
        </table>
      </div>
    </div>
      
  </div>
  <footer><?php include 'common/footer.php'; ?></footer>
</div>
    <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
    <script src="<?php echo r_url('layer/layer.js'); ?>"></script>
    <script>
        function show_zoushi(id){
		layer.open({
		  type: 2,
		  shadeClose: true,
		  shade: 0.8,
		  area: ['100%', '90%'],
		  content: '<?php echo get_url('/index.php/Welcome/lately_data'); ?>?user_id='+id //iframe的url
		}); 
            }
    </script>
</body>
</html>

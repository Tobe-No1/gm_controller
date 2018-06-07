<!DOCTYPE html>
<html>
<head>
  <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
  <meta charset="utf-8" />
  <meta content="width=device-width,minimum-scale=1.0,maximum-scale=1.0" name="viewport" />
  <meta content="yes" name="apple-mobile-web-app-capable" />
  <meta content="black" name="apple-mobile-web-app-status-bar-style" />
  <meta content="telephone=no" name="format-detection" />
  <link href="<?php echo r_url('css/global.css'); ?>" rel="stylesheet"/>
  <link href="<?php echo r_url('css/core.css'); ?>" rel="stylesheet"/>
  <title><?php echo $this->config->item('game_name');?>管理后台</title>
</head>
<body>
<div class="container b-n">
  <header>
    <nav class="n_t">
      <span class="n_r">
        <a href="<?php echo get_url('/index.php/Login/menu'); ?>"><img src="<?php echo r_url('imgs/mon/rr.png'); ?>"></a>
      </span>
      <span>代理充值明细</span>
      <span class="n_h">
        
        <a href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
      </span>
    </nav>
  </header>
  <div class="content">
    <div style="padding: 0 10px;margin: 15px 5px">
      <form action="<?php echo get_url('/index.php/Statistic/agent_charge'); ?>" method="post">
        <label for="" class="input-label tt tl">开始时间</label>
        <input type="text" name="stime1" class="input-text-2" value="<?php echo $show_condition['stime'] ?>">
        <label for="" class="input-label tt tl">结束时间</label>
        <input type="text" name="etime1" class="input-text-2" value="<?php echo $show_condition['etime'] ?>">
        <br/>
        <label for="" class="input-label tt tl">代理id</label>
        <input type="text" name="user_id" class="input-text-2" value="<?php if(isset($show_condition['user_id'])){ echo $show_condition['user_id']; } ?>">
        <div class="bt" style="margin-top: 5px">
          <input type="submit" value="查询" class="input-submit">
          </div>
      </form>
      <table class="user-list wl_9" style="width: 100%;margin-top:10px">
        <tr class="ttb" style="border-bottom: 1px solid #2A597C;">
          <th style="width: 15%; text-align: center; padding: 12px 0;">代理名称</th>
          <th style="width: 15%; text-align: center; padding: 12px 0;">代理id</th>
          <th style="width: 15%; text-align: center; padding: 12px 0;">充值金额</th>
          <th style="width: 25%; text-align: center; padding: 12px 0;">时间</th>
        </tr>
        <?php
        $role_names = $this->config->item('role_names');
        foreach($list as $key => $l):?>
          <tr <?php if($key%2 == 0):?>style="background-color: #eee; border-top: 1px solid #ccc;"<?php endif;?>>
            <td style="text-align: center; padding: 12px 0;"><?=$l['mg_name']?></td>
            <td style="text-align: center; padding: 12px 0;"><?=$l['user_id']?></td>
            <td style="text-align: center; padding: 12px 0;"><?php echo $l['rmb'];?></td>
            <td style="text-align: center; padding: 12px 0;"><?php echo $l['pay_time'];?></td>
          </tr>
        <?php endforeach;?>
        <tr style="border: 1px solid #2A597C;font-weight: bold;">
          <td style="text-align: center; padding: 12px 0;">充值总额</td>
          <td style="text-align: center; padding: 12px 0;"><?php echo $total; ?></td>
          <td style="text-align: center; padding: 12px 0; ">充值次数</td>
          <td colspan="4" style="text-align: center; padding: 12px 0;"><?php echo $count; ?></td>
        </tr>
      </table>
      <div class="page_link">
        <?php echo $page_link; ?>
      </div>
    </div>
  </div>

  </div>
<footer><?php include 'common/footer.php'; ?></footer>
</div>

</body>
</html>

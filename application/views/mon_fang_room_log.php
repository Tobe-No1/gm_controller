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
   <link href="<?php echo r_url('css/jquery-ui.min.css'); ?>" rel="stylesheet"/>
  <script type="text/javascript" src="http://cdn.bootcss.com/jquery/1.8.2/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo r_url('js/jquery-ui.min.js'); ?>"></script>
  <script>
       $( function() {
        $( ".datepicker" ).datepicker({
           dateFormat: 'yy-mm-dd'
          });
      } );
  </script>
</head>
<body>
<div class="container b-n">
  <header>
    <nav class="n_t">
      <span class="n_r">
        <a href="<?php echo get_url('/index.php/Login/menu'); ?>"><img src="<?php echo r_url('imgs/mon/rr.png'); ?>"></a>
      </span>
      <span>房主房消费记录</span>
      <span class="n_h">
        
        <a href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
      </span>
    </nav>
  </header>
  <div class="content">
    <div style="padding: 0 10px;margin: 15px 5px">
         <form action="<?php echo get_url('/index.php/User/fang_room_list'); ?>" method="get">
        <label for="" class="input-label tt tl">开始时间</label>
        <input type="text" name="stime" class="input-text-2 datepicker" style="min-width:40px;width: 50px;font-size:9px" value="<?php echo $show_condition['stime'] ?>" id="stime1">
        <label for="" class="input-label tt tl">结束时间</label>
        <input type="text" name="etime" class="input-text-2 datepicker" style="min-width:40px;width: 50px;font-size:9px" value="<?php echo $show_condition['etime'] ?>" id="etime1">
        <br>
        <label for="" class="input-label tt tl">玩家ID</label>
        <input type="text" name="uid" class="input-text-2" style="min-width:25px;width: 50px;font-size:9px" value="<?php echo $show_condition['uid'] ?>" id="etime1">
        <br>
        <div class="bt" style="margin-top: 5px">
          <input type="submit" value="查询" class="input-submit">
          </div>
      </form>
      <table class="user-list wl_9" style="width: 100%;margin-top:10px;">
        <tr class="ttb" style="border-bottom: 1px solid #2A597C;">
            <th style="width: 10%; text-align: center; padding: 12px 0;">用户ID</th>
            <th style="width: 15%; text-align: center; padding: 12px 0;">房号</th>
            <th style="width: 15%; text-align: center; padding: 12px 0;">牌类型</th>
            <th style="width: 15%; text-align: center; padding: 12px 0;">局数</th>
            <th style="width: 15%; text-align: center; padding: 12px 0;">消耗卡数</th>
            <th style="width: 15%; text-align: center; padding: 12px 0;">剩余卡数</th>
            <th style="width: 15%; text-align: center; padding: 12px 0;">时间</th>
        </tr>
         <?php foreach($list as $key => $l):?>
          <tr <?php if($key%2 == 0):?>style="background: -webkit-gradient(linear, 0% 0, 0% 100%, from(#ffffff),	to(#ccc) );"<?php endif;?>>
             <td style="text-align: center; padding: 12px 0;"><?php echo $l['uid']?></td>
            <td style="text-align: center; padding: 12px 0;"><?php echo $l['qipai_type']?></td>
            <td style="text-align: center; padding: 12px 0;"><?php echo $l['room_id']?></td>
            <td style="text-align: center; padding: 12px 0;"><?php echo $l['ju']?></td>
            <td style="text-align: center; padding: 12px 0;"><?php echo $l['cost_card']?></td>
            <td style="text-align: center; padding: 12px 0;"><?php echo $l['left_card']?></td>
            <td style="text-align: center; padding: 12px 0;"><?php echo date('Y-m-d H:i:s',$l['create_time']);?></td>
          </tr>
        <?php endforeach;?>
          <tr><td>开房次数：</td><td><?php echo $count;?></td><td>消耗总量：</td><td colspan="4"><?php echo $cost_card;?></td></tr>
      </table>
      <div class="page_link">
        <?php echo $page_link; ?>
      </div>
    </div>

  </div>
    <footer><?php include 'common/footer.php';?></footer>
</div>

</body>
</html>

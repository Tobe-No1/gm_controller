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
        <a href="<?php echo get_url('/index.php/User/vip_room_list'); ?>?stime=<?php echo $show_condition['stime'] ?>&etime=<?php echo $show_condition['etime'] ?>"><img src="<?php echo r_url('imgs/mon/rr.png'); ?>"></a>
      </span>
      <span>VIP房消费记录</span>
      <span class="n_h">
        <a href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
      </span>
    </nav>
  </header>
  <div class="content">
    <div style="padding: 0 10px;margin: 15px 5px">
      <table class="user-list wl_9" style="width: 100%;margin-top:10px;">
        <tr class="ttb" style="border-bottom: 1px solid #2A597C;">
            <th style="width: 15%; text-align: center; padding: 12px 0;">房号</th>
            <th style="width: 15%; text-align: center; padding: 12px 0;">局数/消耗卡数/</th>
            <th style="width: 15%; text-align: center; padding: 12px 0;">剩余卡数</th>
            <th style="width: 15%; text-align: center; padding: 12px 0;">时间</th>
            <th style="width: 15%; text-align: center; padding: 12px 0;">内容</th>
        </tr>
         <?php foreach($list as $key => $l):?>
          <tr <?php if($key%2 == 0):?>style="background: -webkit-gradient(linear, 0% 0, 0% 100%, from(#ffffff),	to(#ccc) );"<?php endif;?>>
            <td style="text-align: center; padding: 12px 0;"><?php echo $l['room_id']?></td>
            <td style="text-align: center; padding: 12px 0;"><?php echo $l['ju']?>/<?php echo $l['cost_card']?></td>
            <td style="text-align: center; padding: 12px 0;"><?php echo $l['left_card']?></td>
            <td style="text-align: center; padding: 12px 0;"><?php echo date('Y-m-d H:i:s',$l['create_time']);?></td>
            <td style="text-align: left; padding: 12px 0;word-break:break-all "><?php 
            $tmp = json_decode($l['content'],true);
            if(!empty($tmp['players'])){
                foreach($tmp['players'] as $v){
                    echo sprintf("%s(%d):%d&nbsp;&nbsp;&nbsp;&nbsp;",$v['name'],$v['uid'], $v['score']);
                }
            }
            ?>
            </td>
          </tr>
        <?php endforeach;?>
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

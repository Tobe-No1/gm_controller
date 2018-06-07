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
      <span>VIP房消费记录</span>
      <span class="n_h">
        <a href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
      </span>
    </nav>
  </header>
  <div class="content">
    <div style="padding: 0 10px;margin: 15px 5px">
        <form action="<?php echo get_url('/index.php/User/vip_room_list'); ?>" method="get">
        <label for="" class="input-label tt tl">开始时间</label>
        <input type="text" name="stime" class="input-text-2 datepicker" readonly="true" style="min-width:25px;width: 90px;font-size:9px" value="<?php echo $show_condition['stime'] ?>" id="stime1">
        <label for="" class="input-label tt tl">结束时间</label>
        <input type="text" name="etime" class="input-text-2 datepicker" readonly="true" style="min-width:25px;width: 90px;font-size:9px" value="<?php echo $show_condition['etime'] ?>" id="etime1">
        <div class="bt" style="margin-top: 5px">
          <input type="submit" value="查询" class="input-submit">
          </div>
      </form>
      <table class="user-list wl_9" style="width: 100%;margin-top:10px;">
        <tr class="ttb" style="border-bottom: 1px solid #2A597C;">
            <th style="width: 15%; text-align: center; padding: 12px 0;">开房日</th>
            <th style="width: 15%; text-align: center; padding: 12px 0;">房号</th>
            <th style="width: 15%; text-align: center; padding: 12px 0;">状态</th>
            <th style="width: 15%; text-align: center; padding: 12px 0;">已耗卡</th>
            <th style="width: 15%; text-align: center; padding: 12px 0;">查询</th>
            <th style="width: 15%; text-align: center; padding: 12px 0;">操作</th>
        </tr>
         <?php foreach($list as $key => $l):?>
          <tr <?php if($key%2 == 0):?>style="background: -webkit-gradient(linear, 0% 0, 0% 100%, from(#ffffff),	to(#ccc) );"<?php endif;?>>
            <td style="text-align: center; padding: 12px 0;"><?php echo date('Y-m-d H:i:s',$l['create_time']);?></td>
            <td style="text-align: center; padding: 12px 0;"><?php echo $l['room_id']?></td>
            <td style="text-align: center; padding: 12px 0;" id="status<?php echo $l['room_id']?>"><?php  if($l['status']==0){ echo '正常'; }else{ echo '关闭'; } ?></td>
            <td style="text-align: center; padding: 12px 0;"><?php echo $l['cost_card']?></td>
            <td style="text-align: center; padding: 12px 0;color:blue"><a href="<?php echo get_url('/index.php/User/vip_room_list2'); ?>?stime=<?php echo $show_condition['stime'] ?>&etime=<?php echo $show_condition['etime'] ?>&room_id=<?php echo $l['room_id']?>" style="color:blue">展开<a></td>
            <td style="" id="action<?php echo $l['room_id']?>">
                <?php
                    if($l['status']==0) {
                ?>
                <a href="#" room_id="<?php echo $l['room_id']?>" class="close" style="color:blue">关闭</a>
                <?php
                    }else{
                        ?>
                <a href="#" room_id="<?php echo $l['room_id']?>" class="cleandata" style="color:blue">清理数据</a>
                <?php
                    }
                ?>
            </td>
          </tr>
        <?php endforeach;?>
          </table>
    </div>

  </div>
    <footer><?php include 'common/footer.php';?></footer>
</div>

</body>
</html>
<script type="text/javascript">
    $(function () {
        $('.close').click(function () {
            var room_id = $(this).attr('room_id');
            $.post("<?php echo get_url('/index.php/User/ajax_room_close'); ?>", {'room_id':room_id}, function (respond) {
                if(respond.status==0) {
                    $('#status'+room_id).html('关闭');
                    $('#action'+room_id).html('<a href="#" room_id="'+room_id+'" class="cleandata" style="color:blue">清理数据</a>');
                }
            }, 'json');
        });
        
//        $('.cleandata').cl
        
    });
</script>
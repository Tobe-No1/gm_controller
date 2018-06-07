

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
        <a href="<?php echo get_url('/index.php/Login/menu'); ?>"><img src="<?php echo r_url('imgs/mon/rr.png'); ?>"></a>
      </span>
      <span>群主房间查询</span>
      <span class="n_h">
        
        <a href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
      </span>
    </nav>
  </header>
  <div class="content">
    <div class="login js-slide">
      <form method="post" action="<?php echo get_url('/index.php/User/player'); ?>">
      输入房号
      <input type="text" name="room_id" class="input-text-2" value="<?php if(isset($room_id)) { echo $room_id; }?>">
      <input type="submit" value="搜索">
        </form>
    </div>
    <div class="login js-slide">
      <div class="detail-tag js-slide">
        <div class="slide-con js-slide-con wl_9">
            <div style="height: 1px;border-top: 1px solid #BAD3E3;"></div>
            完成时间:<?php if(isset($create_time)){ echo date('Y-m-d H:i:s',$create_time);}  ?>
          <?php if(isset($list)) {
              foreach($list as $key => $l):
                  $color = $l['score'] > 0 ? 'red' : 'black';
                  ?>
          <div class="list-item arrow">
            <div class="ull">
                <img src="<?=$l['head']?>" width="100" height="100" />
              <span><?=$l['name']?></span>
              <span><?=$l['uid']?></span>
              分数:<span style="color:<?php echo $color?>;"><?=$l['score']?></span>
            </div>
          </div>
          <?php endforeach; }?>
          </div>
        </div>
      </div>
    </div>
  </div>
    <footer><?php include 'common/footer.php';?></footer>
</div>

</body>
</html>

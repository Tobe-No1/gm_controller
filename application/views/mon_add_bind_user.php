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
  <script type="text/javascript" src="<?php echo r_url('js/Tost.js'); ?>"></script>
  <title><?php echo $this->config->item('game_name');?>管理后台</title>
</head>
<body>
<div class="container b-n">
  <header>
    <nav class="n_t">
      <span class="n_r">
        <a href="<?php echo get_url('/index.php/User/bind_user_list'); ?>"><img src="<?php echo r_url('imgs/mon/rr.png'); ?>"></a>
      </span>
      <span>添加绑定</span>
      <span class="n_h">
        
        <a href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
      </span>
    </nav>
  </header>
  <div class="content">
    <form action="<?php echo get_url('/index.php/User/add_bind_user') ?>" method="post">
      <div class="login js-slide">
        <div class="detail-tag js-slide">
          <div class="slide-con js-slide-con">
            <div class="list-item h300">
              <div class="pl">
                <div>玩家id</div>
                <div><input type="text" name="uid" value="" required="required"></div>
              </div>
              <div class="bt">
                <input type="submit" value="提交">
                <input type="reset" value="重置">
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
    </div>
    <footer><?php include 'common/footer.php';?></footer>
</div>
</body>
</html>

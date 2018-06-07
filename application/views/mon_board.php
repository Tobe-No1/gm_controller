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
        <a href="#" onClick="javascript :history.back();"><img src="<?php echo r_url('imgs/mon/rr.png'); ?>"></a>
      </span>
      <span>发布弹框公告</span>
      <span class="n_h">
        <a href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
      </span>
    </nav>
  </header>
  <div class="content ed">
    <form action="<?php get_url('/index.php/activity/board'); ?>" method="post" id="rank-remark-form" >
      <p style="margin-bottom:10px"><span class="tt">间隔时间</span><input width="100%" type="text" name="jiange" value="600"></p>
      <p style="margin-bottom:10px"><span class="tt">弹框次数</span><input width="100%" type="text" name="times" value="3"></p>
      <p style="margin-bottom:10px"><span class="tt">公告内容</span><textarea name="content" id="rank-remark-content" style="" rows="5" cols="30"></textarea></p>
      <div class="bt">
      <input type="submit" class="user-message-btn" value="发布">
      </div>
    </form>
  </div>
</div>
<footer><?php include 'common/footer.php'; ?></footer>
</div>

</body>
</html>

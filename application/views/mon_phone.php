

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
        <a href="<?php echo get_url('/index.php/Login/menu'); ?>"><img src="<?php echo r_url('imgs/mon/rr.png'); ?>"></a>
      </span>
      <span>代理信息</span>
      <span class="n_h">
        
        <a href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
      </span>
    </nav>
  </header>
  <div class="content">
    <form action="<?php echo get_url('/index.php/user/doEdit') ?>" method="post">
      <!--TOKEN-->
      <input type="hidden" name="token" value="<?php echo $token ?>">
      <input type="hidden" name="invotecode" value="<?php echo $tpl['invotecode']?>">
      <input type="hidden" name="add_status" value="<?php echo $add_status?>">
      <!--OLD-->
      <input type="hidden" name="mg_user_id" value="<?php echo $tpl['mg_user_id']?>">
      <div class="login js-slide">
        <div class="detail-tag js-slide">
          <div class="slide-con js-slide-con">
            <div class="list-item pho top p_b">
              <div class="">修改手机号码</div>
            </div>
            <div class="list-item h300">
              <div class="pl">
                <div>请输入新号码</div>
                <div><input type="tel" name="phone" value="<?php echo $tpl['phone'] ?>" required="required"></div>
                <!--
                <div>邀请码</div>
                <div>
                  <?php if ($tpl['invotecode']==$tpl['p_mg_user_id']) { ?>
                  <input type="text" name="invotecode" value="<?php echo $tpl['invotecode'] ?>" required="required">
                  <?php } else { echo $tpl['invotecode']; }?>
                </div> -->
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
  <footer><?php include 'common/footer.php'; ?></footer>
</div>

</body>
</html>

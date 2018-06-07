

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
  <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
  <meta charset="utf-8" />
  <meta content="width=device-width,minimum-scale=1.0,maximum-scale=1.0" name="viewport" />
  <meta content="yes" name="apple-mobile-web-app-capable" />
  <meta content="black" name="apple-mobile-web-app-status-bar-style" />
  <meta content="telephone=no" name="format-detection" />
  <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo r_url('js/Tost.js'); ?>"></script>
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
      <span class="n_h">
        
        <a href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
      </span>
    </nav>
  </header>
  <div class="content">
    <div class="login js-slide">
      <div class="detail-tag js-slide">
        <div class="slide-con js-slide-con wl_9">
          <div class="list-item pho top p_b">
              <div class="">基本信息</div>
            </div>
            <div class="list-item h300">
              <div class="pl">
                  <table style="">
                      <tr><td style="width:100px;">手机号码:</td><td><?php echo $phone; ?><a href="javascript:editPhone()" >修改</a></td></tr>
                      <tr><td>邀请码:</td><td><?php echo $invotecode; ?></td></tr>
                      <tr><td>代理等级:</td><td><?php echo $this->role_names[$level]; ?></td></tr>
                      <tr><td>用户名:</td><td><?php echo $mg_user_name; ?></td></tr>
                      <tr><td>创建时间:</td><td><?php echo $create_time; ?></td></tr>
                  </table>
              </div>
            </div>
      </div>
    </div>
  </div>
  <!--<footer><?php include 'common/footer.php'; ?></footer>-->
</div>

</body>
</html>
<script>

 // 玩家管理
    function editPhone(){
      var phone = prompt("请输入号码","");
      $.post('<?php echo get_url('/index.php/User/ajax_edit_phone'); ?>',{phone:phone},
          function(respond){
            if(respond.status === 0){
                location.reload();
            }
          },'json')
    }
 </script>

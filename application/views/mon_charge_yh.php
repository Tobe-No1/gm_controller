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
  <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
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
      <span>购卡</span>
      <span class="n_h">
        <a href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
      </span>
    </nav>
  </header>
  <div class="content">
<span style="margin-top:10px;float: right; position: relative; top: 0px; right: 16px; font-size:13px;" class="c-25">
        余卡：<em style="color: red;"><?=intval($mg_user_account_props['count'])?></em></span>
    <div style="padding: 0 10px;" align="center" >
      <table class="table c-25" style="width: 80%;"  align="center">
        <thead >
        <tr>
          <th  style="text-align:left; padding:12px 0;" >购买方案</th>
        </tr>
        </thead>
        <tbody align="center" valign="middle">
        <?php foreach($list as $l):?>
          <tr rel="<?=$l['pid']?>" align="center" valign="middle" class="ctr button" style="display: block">
            <td class="ctd" >
              <a href="<?=get_url('/index.php/Charge/pay_yh?pid='.$l['pid'])?>">
              <?=$l['desc2']?>
              </a>
            </td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<footer><?php include 'common/footer.php'; ?></footer>
</div>
<script type="text/javascript">

  $(function(){
    $(".button").click(function(){
      var pid = $(this).attr('rel');
      window.location.href = "<?=get_url('/index.php/Charge/pay_ph')?>" + "?pid=" + pid;
    });
  });

</script>
</body>
</html>



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
        <a href="#" onClick="javascript :history.go(-1);"><img src="<?php echo r_url('imgs/mon/rr.png'); ?>"></a>
      </span>
      <span>充值查询</span>
      <span class="n_h">
        <img src="<?php echo r_url('imgs/mon/rr.png'); ?>" onClick="javascript :history.back();">
        <a href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
      </span>
    </nav>
  </header>
  <div class="content">
    <div class="login js-slide">
      <div class="detail-tag js-slide">
        <div class="slide-con js-slide-con">
          <div class="list-item pho top p_b">
            <div class="wl_9">
              【首页】欢迎您：<?=$head?> <?=$mgid?>(<?=$uname?>)，
              <span style="color: red"><?=$role_names?></span>
              我充值：<?=sprintf("%.2f",$mcost)?>元。
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="content wl_9 c-999 clm">
      <div>
        当前位置：统计首页 > [<?=$mgid?>]二级代理 > [<?=$mgidb?>] 三级代理总充值
      </div>
      <hr color="#3D86BD" size="1px">
      <div>
        <table style="width: 100%">
          <tr>
            <td style="width: 20%">代理UID</td>
            <td style="width: 70%">代理等级</td>
            <td style="width: 10%">金额</td>
          </tr>
          <?php if (isset($ulistb) && is_array($ulistb)) {foreach($ulistb as $key => $l):?>
            <tr>
              <td><?=$l['mg_user_id']?></td>
              <td>
                <span style="color: green">三级代理</span>
                <a href="<?php echo get_url('/index.php/Welcome/mon_clist'); ?>?s=<?=$s?>&e=<?=$e?>&cid=<?=$l['mg_user_id']?>" class="c-25">展开</a></td>
              <td style="text-align: right"><?=sprintf("%.2f",$l['i_all'])?></td>
            </tr>
          <?php endforeach;}?>
        </table>
      </div>
    </div>
  </div>
  <footer><?php include 'common/footer.php'; ?></footer>
</div>

</body>
</html>

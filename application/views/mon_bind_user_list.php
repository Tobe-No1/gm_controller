<!DOCTYPE html>
<html>
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
        <link href="<?php echo r_url('css/core.css'); ?>" rel="stylesheet"/>
        <title><?php echo $this->config->item('game_name'); ?>管理后台</title>
    </head>
    <body>
        <div class="container b-n">
            <header>
                <nav class="n_t">
                    <span class="n_r">
                        <a href="<?php echo get_url('/index.php/Login/menu'); ?>"><img src="<?php echo r_url('imgs/mon/rr.png'); ?>"></a>
                    </span>
                    <span>群主绑定列表</span>
                    <span class="n_h">
                        <a href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
                    </span>
                </nav>
            </header>
            <div class="login js-slide" style="padding: 8px;">
                <a href="<?php echo get_url('/index.php/User/add_bind_user'); ?>" style="color:blue">添加绑定</a>
            </div>
            <div class="content">
                <div style="padding: 0 10px;margin: 15px 5px">
                    <table class="user-list wl_9" style="width: 100%;margin-top:10px;">
                        <tr class="ttb" style="border-bottom: 1px solid #2A597C;">
                            <th style="text-align: center; padding: 12px 0;">用户id</th>
                            <th style="text-align: center; padding: 12px 0;">昵称</th>
							<th style="text-align: center; padding: 12px 0;">操作</th>
                        </tr>
<?php foreach ($players as $key => $l): ?>
                            <tr <?php if ($key % 2 == 0): ?>style="background: -webkit-gradient(linear, 0% 0, 0% 100%, from(#ffffff),	to(#ccc) );"<?php endif; ?>>
                                <td style="text-align: center; padding: 12px 0;"><?php echo $l['uid'] ?></td>
                                <td style="text-align: center; padding: 12px 0;"><?php echo $l['name'] ?></td>
								<td><a href="<?php echo get_url('/index.php/User/del_bind_user?uid='.$l['uid']); ?>" style="color:blue">解除绑定</a></td>
                                </td>
                            </tr>
<?php endforeach; ?>
                    </table>
                </div>
            </div>
            <footer><?php include 'common/footer.php'; ?></footer>
        </div>
    </body>
</html>
<script type="text/javascript">
    $(function () {
        $('.inviteid').click(function () {
            var uid = $(this).attr('uid');
            var invite_id = prompt("邀请id",$('#id'+uid).html())
            $.post("<?php echo get_url('/index.php/User/ajax_change_invite'); ?>", {'uid':uid,"invite_id":invite_id}, function (respond) {
                if(respond.status==0) {
                    $('#id'+uid).html(invite_id);
                }
            }, 'json');
        });
        
        $('.fenghao').click(function () {
            var uid = $(this).attr('uid');
            $.post("<?php echo get_url('/index.php/User/ajax_change_status'); ?>", {'uid':uid}, function (respond) {
                if(respond.status==0) {
                    $('#status'+uid).html('正常');
                }else if(respond.status==1) {
                    $('#status'+uid).html('封号');
                }
            }, 'json');
        });
        
    });
</script>

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
                    <span>GM查询工具</span>
                    <span class="n_h">
                        <a href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
                    </span>
                </nav>
            </header>
            <div class="login js-slide">
                    代理位置查询
                    <input type="text" name="agent_id" id="query_agent_id" class="input-text-11" value="">
                    <input type="button" id="agent_query_id" value="搜索">
            </div>
            <div class="content" id="query_agent_content"> </div>
            <div class="login js-slide">
                    邀请码查询
                    <input type="text" name="code" id="code_id" class="input-text-11" value="">
                    <input type="button" id="code_query_id" value="搜索">
            </div>
            <div class="content" id="query_agent_content2"> </div>
            <footer><?php include 'common/footer.php'; ?></footer>
        </div>
    </body>
</html>
<script type="text/javascript">
    $(function () {
         $('#agent_query_id').click(function () {
            var agent_id = $('#query_agent_id').val();
            $.post("<?php echo get_url('/index.php/Gm/ajax_agent_query'); ?>", {'agent_id':agent_id}, function (respond) {
                $('#query_agent_content').html(respond.query_info);
            }, 'json');
        });
        $('#code_query_id').click(function () {
            var code = $('#code_id').val();
            $.post("<?php echo get_url('/index.php/Gm/ajax_invotecode_query'); ?>", {'code':code}, function (respond) {
                $('#query_agent_content2').html(respond.query_info);
            }, 'json');
        });
    });
</script>

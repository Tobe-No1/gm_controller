<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1, maximum-scale=1">
        <link href="<?php echo r_url_new('css/sm.min.css'); ?>" type="text/css" rel="stylesheet">
        <link href="<?php echo r_url_new('css/css.css'); ?>" rel="stylesheet"/>
        <title><?php echo $this->config->item('game_name'); ?>管理后台</title>
    </head>
    <body>
        <div class="register">
            <form id='form1'>
                <div class="content">
                    <div class="register_ico"></div>
                    <div class="list-block">
                        <ul>
                            <li>
                                <div class="item-content">
                                    <div class="item-inner">
                                        <div class="item-input">
                                            <input type="text" placeholder="帐号" name="username">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item-content">
                                    <div class="item-inner">
                                        <div class="item-input">
                                            <input type="password" placeholder="密码" class="" name="password">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item-content">
                                    <div class="item-inner">
                                        <div class="item-input">
                                            <input type="text" placeholder="验证码" class="" name="captcha">
                                        </div>
                                        <img class="captcha" src="<?= get_url('/index.php/Login/get_code') ?>" style="vertical-align: middle;height: 30px;width: 36%;margin: 0">
                                    </div>
                                </div>
                            </li>
                        </ul>

                    </div>
                    <div class="content-block">
                        <div class="row">
                            <div class="col-100"><a href="#" class="button button-big button-fill" id='loginBtn'>登入</a></div>
                        </div>
                    </div>
                    <?php include 'common/footer.php'; ?>
                </div>
            </form>
        </div>
    </body>
</html>
<script src="<?php echo r_url_new('js/zepto.min.js'); ?>"></script>
<script src="<?php echo r_url_new('js/sm.min.js'); ?>"></script>
<script src="<?php echo r_url_new('js/share.js'); ?>"></script>
<script type="text/javascript">
    $(function () {
        $('#loginBtn').click(function () {
            $.post('<?php echo get_url('/index.php/Login/ajax_login'); ?>', $('#form1').serializeArray(), function (respond) {
                if (respond.status)
                {
                    window.location = '<?php echo get_url('/index.php/Login/menu') . "?islogin=1"; ?>' // 刷新
                } else
                {
                    $(".captcha").trigger("click");
                    $.toast(respond.msg);
                }
            }, 'json')
        })

        $(".captcha").click(function () {
            var captchaImg = "<?= get_url('/index.php/Login/get_code') ?>" + "?v=" + new Date().getTime();
            $(this).attr('src', captchaImg);
        });
    })
</script>

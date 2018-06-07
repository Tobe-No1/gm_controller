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
        <div class="page-group page-group-change">
            <div class="page page-current">
                <header class="bar bar-nav">
                    <a class="button button-link button-nav pull-left back" href="<?php echo get_url('/index.php/Login/menu'); ?>">
                        <span class="icon icon-left"></span>返回
                    </a>
                    <a class="button button-link button-nav pull-right js-exit" href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
                    <h1 class="title">修改密码</h1>
                </header>
                <div class="content native-scroll">
                    <form id="form1">
                    <div class="list-block">
                        <ul>
                            <li>
                                <div class="item-content">
                                    <div class="item-inner">
                                        <div class="item-title label">旧密码</div>
                                        <div class="item-input">
                                            <input type="text" placeholder="输入旧密码" name="loginpassword">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item-content">
                                    <div class="item-inner">
                                        <div class="item-title label">新密码</div>
                                        <div class="item-input">
                                            <input type="text" placeholder="输入新密码" name="onepassword">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item-content">
                                    <div class="item-inner">
                                        <div class="item-title label">新密码</div>
                                        <div class="item-input">
                                            <input type="text" placeholder="再次输入新密码" name="towpassword">
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                        
                    <div class="content-block">
                        <div class="row">
                            <div class="col-50"><a href="#" class="button button-big">重置</a></div>
                            <div class="col-50"><a href="#" class="button button-big button-fill" id="upPwdBtn">提交</a></div>
                        </div>
                    </div>
                    </form>
                    <?php include 'common/footer.php'; ?>
                </div>
            </div>
        </div>
    </body>
    <script src="<?php echo r_url_new('js/zepto.min.js'); ?>"></script>
    <script src="<?php echo r_url_new('js/sm.min.js'); ?>"></script>
    <script src="<?php echo r_url_new('js/share.js'); ?>"></script>
</html>


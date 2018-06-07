
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
                    <h1 class="title">激活代理</h1>
                </header>

                <div class="content native-scroll">
                    <form id="form1">
                        <input type="hidden" name="token" value="<?php echo $token ?>">
                        <div class="list-block">
                            <ul>
                                <li>
                                    <div class="item-content">
                                        <div class="item-inner">
                                            <div class="item-title label">登录账号</div>
                                            <div class="item-input">
                                                <input type="text" placeholder="输入玩家ID" name="mg_user_id" id="query_user_id">
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="item-content">
                                        <div class="item-inner">
                                            <div class="item-title label">备注名</div>
                                            <div class="item-input">
                                                <input type="text" placeholder="备注名" name="mg_name">
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <p style="margin: 1rem"><a href="#" class="button button-big button-fill" id="queryUserBtn">查找</a></p>
                        <div class="list-block media-list">
                            <ul id="query_fruit2">
                            </ul>
                        </div>
                        <div class="content-block">

                            <div class="row">
                                <div class="col-50"><a href="#" class="button button-big">重置</a></div>
                                <div class="col-50"><a type="submit" class="button button-big button-fill" id="upBtn">提交</a></div>
                            </div>
                        </div>
                    </form>
                    <!--底部版权信息-->
                    <?php include 'common/footer.php'; ?>
                </div>

            </div>
        </div>
        <script src="<?php echo r_url_new('js/zepto.min.js'); ?>"></script>
        <script src="<?php echo r_url_new('js/sm.min.js'); ?>"></script>
        <script src="<?php echo r_url_new('js/share.js'); ?>"></script>
    </body>
    <script type="text/javascript">
        $(function () {
           
        })
    </script>
</html>

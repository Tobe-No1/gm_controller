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
        <link href="<?php echo r_url('css/jquery-ui.min.css'); ?>" rel="stylesheet"/>
        <script type="text/javascript" src="http://cdn.bootcss.com/jquery/1.8.2/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo r_url('js/jquery-ui.min.js'); ?>"></script>
        <title><?php echo $this->config->item('game_name'); ?>管理后台</title>
        <script>
            $(function () {
                $(".datepicker").datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            });
        </script>
    </head>
    <body>
        <div class="container b-n">
            <header>
                <nav class="n_t">
                    <span class="n_r">
                        <a href="<?php echo get_url('/index.php/Login/menu'); ?>"><img src="<?php echo r_url('imgs/mon/rr.png'); ?>"></a>
                    </span>
                    <span>充值查询</span>
                    <span class="n_h">

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
                                    【<a href="<?php echo get_url('/index.php/Login/menu') ?>">首页</a>】欢迎您：<span style="color: red">
                                        <?php echo $this->role_names[$this->__user_info['level']]; ?></span> <?= $mgid ?>
                                    (<?php echo $this->__user_info['mg_user_name'] ?>)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content wl_9 c-999 clm">
                    <div>当前位置：统计首页</div>
                    <div>
                        <form method="get" action="<?php echo get_url('/index.php/Welcome/mon_fanxian'); ?>">
                            开始时间
                            <input type="datetime" name="stime" class="input-text-2 datepicker" style="min-width:50px;width: 80px;font-size: 7px" readonly="true" value="<?php echo $show_condition['stime'] ?>">
                            结束时间
                            <input type="datetime" name="etime" class="input-text-2 datepicker" style="min-width:50px;width: 80px;font-size: 7px" readonly="true" value="<?php echo $show_condition['etime'] ?>">
                            <div class="bt" style="margin-top: 5px;display: inline;">
                                <input type="submit" value="搜索">
                            </div>
                        </form>
                    </div>
                    <div class="cll c-bl">
                        返现金额：<?php
                        echo sprintf("%.2f", $total);
                        ?>元
                    </div>
                    <div>
                        我的下线代理总购卡返现：<a href="<?php echo get_url('/index.php/Welcome/xiaxian_charge'); ?>?stime=<?php echo $show_condition['stime'] ?>&etime=<?php echo $show_condition['etime'] ?>" class="c-25">【展开】</a>
                        <span style="margin-left: 10px">￥<?php
                            echo sprintf("%.2f", $total);
                            ?></span>
                    </div>
                </div>
            </div>
            <footer><?php include 'common/footer.php'; ?></footer>
        </div>

    </body>
</html>

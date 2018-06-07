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
                    <a class="button button-link button-nav pull-right js-exit" href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
                    <h1 class="title"><?php echo $this->config->item('game_name'); ?>管理后台</h1>
                </header>
                <div class="content native-scroll">
                    <div class="content-inner">
                        <!--头部信息-->
                        <div class="message-div">欢迎您： <?= $uname ?>,<span class="red-number">邀请码[<?php echo $icode; ?>]</span><span class="diamond">钻石: <?php echo $card; ?></span></div>
                        <div class="list-block">
                            <ul>
                                <!--需要传入中央服务器地址eg: 'http://gm.com/'-->
                                <?php if($base_url=='') {
                                    echo '<li><a href="' . get_url('/index.php/Stat/get_total') . '" class="item-link item-content">
                                                        <div class="item-media"><i class="icon icon-f-left icon-charge-inq"></i></div>
                                                        <div class="item-inner">
                                                            <div class="item-title">
                                                                充值总查询
                                                            </div>
                                                        </div>
                                                        </a></li>';
                                }?>
                                <?php {
                                    echo '<li>
                                    <a href="'. get_url('/index.php/Stat/charge_base') .'" class="item-link item-content">
                                        <div class="item-media"><i class="icon icon-f-left icon-charge-inq"></i></div>
                                        <div class="item-inner">
                                            <div class="item-title">充值查询</div>
                                        </div>
                                    </a>
                                </li>';
                                }?>
                                <!--<li>
                                    <a href="<?php /*echo get_url('/index.php/Stat/charge_base'); */?>" class="item-link item-content">
                                        <div class="item-media"><i class="icon icon-f-left icon-charge-inq"></i></div>
                                        <div class="item-inner">
                                            <div class="item-title">充值查询</div>
                                        </div>
                                    </a>
                                </li>-->
                                <!--<li>
                                        <a href="<?php /*echo get_url('/index.php/User/club_pic'); */?>" class="item-link item-content">
                                                <div class="item-media"><i class="icon icon-f-left icon-charge-inq"></i></div>
                                                <div class="item-inner">
                                                        <div class="item-title">俱乐部二维码上传</div>
                                                </div>
                                        </a>
                                </li>-->
                                <li>
                                    <a target="_blank" href="<?php echo get_url('/index.php/User/qrcode'); ?>" class="item-link item-content">
                                        <div class="item-media"><i class="icon icon-f-left icon-charge-inq"></i></div>
                                        <div class="item-inner">
                                            <div class="item-title">游戏推广二维码</div>
                                        </div>
                                    </a>
                                </li>
                              <!--   <li>
                                        <a href="<?php echo get_url('/index.php/Club/members'); ?>" class="item-link item-content">
                                                <div class="item-media"><i class="icon icon-f-left icon-charge-inq"></i></div>
                                                <div class="item-inner">
                                                        <div class="item-title">俱乐部成员管理</div>
                                                </div>
                                        </a>
                                </li>
                                 <li>
                                        <a href="<?php echo get_url('/index.php/Club/point_detail'); ?>" class="item-link item-content">
                                                <div class="item-media"><i class="icon icon-f-left icon-charge-inq"></i></div>
                                                <div class="item-inner">
                                                        <div class="item-title">俱乐部积分明细</div>
                                                </div>
                                        </a>
                                </li>  -->

                                   <li>
                                        <a href="<?php echo get_url('/index.php/User/player_list'); ?>" class="item-link item-content">
                                            <div class="item-media"><i class="icon icon-f-left icon-direct-player"></i></div>
                                            <div class="item-inner">
                                                <div class="item-title">
                                                    总计直接玩家
                                                    <span class="red-number"><?= $mgt ?>人</span>
                                            </div>

                                        </div>
                                        </a>
                                   </li>




                                <li>
                                    <a href="<?php echo get_url('/index.php/User/agent_list'); ?>" class="item-link item-content">
                                        <div class="item-media"><i class="icon icon-f-left icon-direct-agency"></i></div>
                                        <div class="item-inner">
                                            <div class="item-title">
                                                总计授权代理
                                                <span class="red-number"><?= $mgc ?>人</span>
                                            </div>

                                        </div>
                                    </a>
                                </li>

                                <?php
                                {
                                    echo '<li>
                                                <a href="#" class="item-content">
                                                    <div class="item-media"><i class="icon icon-f-left icon-today-statistics"></i></div>
                                                    <div class="item-inner">
                                                        <div class="item-title">
                                                            今日统计充值
                                                            <span class="red-number">'. sprintf("%.2f", $total_money).' 元</span>
                                                        </div>

                                                    </div>
                                                </a>
                                            </li>';
                                }?>


                               <!-- <li>
                                    <a href="#" class="item-content">
                                        <div class="item-media"><i class="icon icon-f-left icon-today-statistics"></i></div>
                                        <div class="item-inner">
                                            <div class="item-title">
                                                今日统计充值
                                                <span class="red-number">￥<?/*= sprintf("%.2f", $total_money) */?>元</span>
                                            </div>

                                        </div>
                                    </a>
                                </li>-->
                                
                                <?php $level = intval($_SESSION['user_info']['level']); ?>
                                <?php
                                $rights = $this->config->item('rights');
                                $system_menus = $this->config->item('menus');
                                $menus = $rights[$level];
//                                var_dump($rights[$level]);
                                foreach ($menus as $menu_id) {
                                    if(isset($system_menus[$menu_id])) {
                                        $menu = $system_menus[$menu_id];
                                        if ($menu['is_show'] == true) {
                                            echo '<li><a href="' . get_url($menu['link']) . '" class="item-link item-content">
                                                        <div class="item-media"><i class="icon icon-f-left ' . $menu['icon'] . '"></i></div>
                                                        <div class="item-inner">
                                                            <div class="item-title">
                                                                ' . $menu['name'] . '
                                                            </div>
                                                        </div>
                                                        </a></li>';
                                        }
                                    }
                                }
                                ?>
                                
                            </ul>
                        </div>
                    </div>
                    <?php include 'common/footer.php'; ?>
                </div>

            </div>
        </div>
        <script src="<?php echo r_url_new('js/zepto.min.js'); ?>"></script>
        <script src="<?php echo r_url_new('js/sm.min.js'); ?>"></script>
        <script src="<?php echo r_url_new('js/share.js'); ?>"></script>
    </body>
</html>
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
        <div class="page-group">
            <div id="page-infinite-scroll-bottom" class="page page-current">
                <header class="bar bar-nav">
                    <a class="button button-link button-nav pull-left back" href="<?php echo get_url('/index.php/Login/menu'); ?>">
                        <span class="icon icon-left"></span>返回
                    </a>
                    <a class="button button-link button-nav pull-right js-exit" href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
                    <h1 class="title">基础统计(<?php echo $current_users_num; ?>)</h1>
                </header>
                <div class="content infinite-scroll native-scroll" data-distance="100">
                    <div class="list-block">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>日期</th>
                                    <th>用户总数</th>
                                    <th>新增用户</th>
                                    <th>活跃用户</th>
									<th>消耗</th>
									<th>金币</th>
                                    <th>充值</th>
                                    <th>玩家充值</th>
                                </tr>
                            </thead>

                            <tbody class="list-container">
                                <?php foreach ($list as $key => $l): ?>
                                    <tr>
                                        <td><?php echo $l['create_time'] ?></td>
                                        <td><?php echo $l['user_total_count'] ?></td>
                                        <td><?php echo $l['user_reg_count'] ?></td>
                                        <td><?php echo $l['online_count'] ?></td>
                                        <td><a href="/index.php/Statistic/cost?time=<?php echo $l['create_time']?>   "><?php echo $l['cost_count'] ?></a></td>
                                        <td><?php echo $l['gold_count'] ?></td>
                                        <td><?php echo $l['charge_count'] ?></td>
                                        <td><?php echo $l['player_gold'] ?></td>
                                    </tr>
                                <?php endforeach; ?>        
                            </tbody>
                        </table>
                    </div>
                    <!-- 加载提示符 -->
                    <div class="infinite-scroll-preloader" style="display: none">
                        <div class="preloader">
                        </div>
                    </div>
                    <div class="page-on">
                        <?php echo $page_link; ?>
                    </div>
                </div>
            </div>
        <script src="<?php echo r_url_new('js/zepto.min.js'); ?>"></script>
            <script src="<?php echo r_url_new('js/sm.min.js'); ?>"></script>
            <script src="<?php echo r_url_new('js/share.js'); ?>"></script>
    </body>
</html>

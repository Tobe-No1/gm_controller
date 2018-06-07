
<!DOCTYPE html>
<html lang="en">
    <head>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1, maximum-scale=1">
        <link href="<?php echo r_url_new('css/sm.min.css'); ?>" type="text/css" rel="stylesheet">
        <link href="<?php echo r_url_new('css/css.css'); ?>" rel="stylesheet"/>
        <title><?php echo $this->config->item('game_name'); ?>管理后台</title>
    </head>
</head>
<body>

    <div class="page-group page-group-change charge-inq">
        <div id="page-charge-inq" class="page page-current">
            <header class="bar bar-nav">
                <a class="button button-link button-nav pull-left" href="<?php echo get_url('/index.php/Login/menu'); ?>">
                    <span class="icon icon-left"></span>返回
                </a>
                <a class="button button-link button-nav pull-right js-exit" href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
                <h1 class="title">俱乐部积分明细</h1>
            </header>
            <div class="content native-scroll">
                <form action="<?php echo get_url('/index.php/Club/point_detail'); ?>" method="post" id="from1">
                <!--查询条件-->
                <div class="list-block">
                    <ul>
                        <li class="item-content">
                            <div class="item-inner">
                                <div class="col-50">
                                    <div class="color-gray">开始时间</div>
                                    <input class="input-time" type="text" placeholder="请选择开始时间" id="begin" readonly="true" name="stime1" value="<?php echo $show_condition['stime'] ?>">
                                </div>
                                <div class="col-50">
                                    <div class="color-gray">结束时间</div>
                                    <input class="input-time" type="text" placeholder="请选择开始时间" id="over" readonly="true" name="etime1" value="<?php echo $show_condition['etime'] ?>">
                                </div>
                            </div>
                        </li>
                        <li class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">俱乐部列表</div>
                                <div class="item-input">
                                    <select name="club_id" onchange="javascipt:document.getElementById('from1').submit();">
                                    <?php
                                        foreach($clubs as $club) {
                                            $option = '';
                                            if($club['id'] == $club_id){
                                                $option = 'selected="selected"';
                                            }
                                            echo '<option value="'.$club['id'].'"  '.$option.'  >'.$club['club_name'].'</option>';
                                        }
                                    ?>
                                        </select>
                                </div>
                            </div>
                        </li>
                        <li class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">玩家ID</div>
                                <div class="item-input">
                                    <input placeholder="输入玩家ID" type="text" name="uid" value="<?php echo $show_condition['uid'] ?>">
                                </div>
                            </div>
                        </li>
                    </ul>
                    <p class="buttom-inq"><input type="submit" class="button button-big button-fill" value="搜索"></p>
                </div>
                </form>
                <!--表单-->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>玩家id</th>
                            <th>昵称</th>
                            <th>时间</th>
                            <th>类型</th>
                            <th>数量</th>
                            <th>剩余数量</th>
                            <th>游戏类型</th>
                            <th>房间号</th>
                        </tr>
                    </thead>

                    <tbody class="list-container">
                        <?php foreach ($list as $key => $l):
                            ?>
                        <tr>
                        <td><?= $l['uid'] ?></td>
                        <td><?= $l['name'] ?></td>
                        <td><?php echo date('Y-m-d H:i:s', $l['create_time']); ?></td>
                        <td><?= $types[$l['type']] ?></td>
                        <td><?= $l['num'] ?></td>
                        <td><?= $l['left_num'] ?></td>
                        <td><?= $games[$l['game_type']] ?></td>
                        <td><?= $l['room_id'] ?></td>
                        </tr>
<?php endforeach; ?>
                    </tbody>
                </table>
                <div class="page-on">
                    <?php echo $page_link; ?>
                </div>
                <!--底部版权信息-->
                <?php include 'common/footer.php'; ?>
            </div>
        </div>
    </div>

    <script src="<?php echo r_url_new('js/zepto.min.js'); ?>"></script>
    <script src="<?php echo r_url_new('js/sm.min.js'); ?>"></script>
    <script src="<?php echo r_url_new('js/share.js'); ?>"></script>
</body>
</html>

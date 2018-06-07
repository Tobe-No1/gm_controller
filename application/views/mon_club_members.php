
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
                <h1 class="title">俱乐部玩家管理</h1>
            </header>
            <div class="content native-scroll">
                <form action="<?php echo get_url('/index.php/Club/members'); ?>" method="post" id="from1">
                    <!--查询条件-->
                    <div class="list-block">
                        <ul>
                            <li class="item-content">
                                <div class="item-inner">
                                    <div class="item-title label">俱乐部列表</div>
                                    <div class="item-input">
                                        <select name="club_id" onchange="javascipt:document.getElementById('from1').submit();">
                                            <?php
                                            foreach ($clubs as $club) {
                                                $option = '';
                                                if ($club['id'] == $club_id) {
                                                    $option = 'selected="selected"';
                                                }
                                                echo '<option value="' . $club['id'] . '"  ' . $option . '  >' . $club['club_name'] . '</option>';
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
                                        <input placeholder="输入玩家ID" type="text" name="uid" value="<?php echo $uid; ?>">
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
                            <th>积分</th>
                            <th>昵称</th>
                            <th>职位</th>
                            <th>总局数</th>
                            <th>操作</th>
                            <th>加入时间</th>
                            <th>最后参与</th>
                        </tr>
                    </thead>

                    <tbody class="list-container">
                        <?php foreach ($members as $key => $l):
                            ?>
                            <tr class="member" data-uid="<?= $l['uid'] ?>" data-name="<?= $l['name'] ?>" data-club_id="<?= $club_id?>">
                                <td><?= $l['uid'] ?></td>
                                <td><?php echo $l['points']; ?></td>    
                                <td><?= $l['name'] ?></td>
                                <td><?= $privilages[$l['privilage']] ?></td>
                                <td><?php echo $l['play_num']; ?></td>
                                <td>
                                    <a href="#">上下分</a>
                                </td>
                                <td><?php echo date('Y-m-d H:i:s', $l['join_time']); ?></td>
                                <td><?php echo date('Y-m-d H:i:s', $l['play_last_time']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!--底部版权信息-->
                <?php include 'common/footer.php'; ?>
            </div>
        </div>
    </div>
    <script src="<?php echo r_url_new('js/zepto.min.js'); ?>"></script>
    <script src="<?php echo r_url_new('js/sm.min.js'); ?>"></script>
    <script src="<?php echo r_url_new('js/share.js'); ?>"></script>
    <script type="text/javascript">
        $(function () {
            // 展开收缩树状
           
        });

    </script>
</body>
</html>

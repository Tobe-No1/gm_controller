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
        <div class="page-group page-group-change charge-inq">
            <div id="page-charge-inq" class="page page-current">
                <header class="bar bar-nav">
                    <a class="button button-link button-nav pull-left" href="<?php echo get_url('/index.php/Login/menu'); ?>">
                        <span class="icon icon-left"></span>返回
                    </a>
                    <a class="button button-link button-nav pull-right js-exit" href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
                    <h1 class="title">交换用户信息</h1>
                </header>

                <div class="content native-scroll">
                    <form method="get" action="<?php echo get_url('/index.php/User/exchange'); ?>">
                    <!--查询条件-->
                    <div class="list-block">
                        <ul>
                            <li class="item-content">
                                <div class="item-inner">
                                    <div class="item-title label">用户一ID</div>
                                    <div class="item-input">
                                        <input placeholder="输入用户ID" type="text" name="uid1" value="<?php if(isset($show_condition['uid'])) { echo $show_condition['uid']; }?>">
                                    </div>
                                </div>
                            </li>
                            <li class="item-content">
                                <div class="item-inner">
                                    <div class="item-title label">用户二ID</div>
                                    <div class="item-input">
                                        <input placeholder="输入用户ID" type="text" name="uid2" value="<?php if(isset($show_condition['uid'])) { echo $show_condition['uid']; }?>">
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <p class="buttom-inq"><input type="submit" class="button button-big button-fill" value="搜索"></p>
                    </div>
                    </form>
                    <form method="get" action="<?php echo get_url('/index.php/User/exchange'); ?>">
                    <!--表单-->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>用户id</th>
                                <th>微信账号</th>
                                <th>昵称</th>
                                <th>性别</th>
                                <th>状态</th>
                            </tr>
                        </thead>

                        <tbody class="list-container">
                                <?php $flag =1;foreach ($players as $key => $l): ?>
                            <tr>
                                <td ><?php echo $l['uid'] ?></td>
                                <td ><?php echo $l['account'] ?></td>
                                <td ><?php echo $l['name'] ?></td>
                                <td ><?php echo $l['sex']==1 ? '男': '女' ?></td>
                                <td  id="status<?php echo $l['uid'] ?>"><?php if ($l['status'] == 0) { echo '正常';  } else {  echo '封号';  } ?></td>
                                <input type="hidden" name="uid<?php echo $flag++;?>" value="<?php echo $l['uid']?>" />
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if(count($players)==2){
                        echo "<input type='hidden' name='state' value='1'><p class='buttom-inq'><input type='submit' class='button button-big button-fill' value='提交'></p>";
                    }

                    ?>
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
</html>

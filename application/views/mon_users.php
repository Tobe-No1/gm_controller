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
        <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo r_url('js/Tost.js'); ?>"></script>
        <link rel="stylesheet" href="<?php echo r_url('css/core.css'); ?>" media="screen" title="no title" charset="utf-8">
        <link rel="stylesheet" href="<?php echo r_url('css/bootstrap.min.css'); ?>" media="screen" title="no title" charset="utf-8">
        <link rel="stylesheet" href="<?php echo r_url('css/style.css'); ?>" media="screen" title="no title" charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no" />
        <title><?php echo $this->config->item('game_name');?>管理后台</title>
    </head>
    <body>
        <div class="container b-n">
            <header>
                <nav class="n_t">
                    <span class="n_r">
                        <a href="<?php echo get_url('/index.php/Login/menu'); ?>"><img src="<?php echo r_url('imgs/mon/rr.png'); ?>"></a>
                    </span>
                    <span>下线代理</span>
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
                                    【首页】欢迎您：<?= $head ?> <?= $mgid ?>(<?= $uname ?>)，
                                    <span style="color: red"><?= $role_names ?></span>
                                    我充值：<?= sprintf("%.2f", $mcost) ?>元。
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="login js-slide">
                    <!--搜索框-->
                    <form method="get" action="<?php echo site_url('user/table') ?>" class="">
                        <input type="text" name="mg_user_id" value="" placeholder="请输入会员ID 或 登录账号" id="mg_user_id" class="">
                        <input type="submit" class="" value="SEARCH">
                    </form>
                    <!--会员列表-->
                    <div class="user-responsive-table">
                        <table class="user-list">
                            <tr>
                                <th width="40%">ID</th>
                                <th width="10%">登录账号</th>
                                <th width="8%">级别</th>
                                <th width="8%">房卡</th>
                                <th width="10%">电话</th>
                                <th width="24%">邀 请 码</th>
                            </tr>
                            <?php if (isset($curr_user) && $curr_user) { ?>
                                <tr>
                                    <td style="text-align: left"><span class="show_up" data-uid="<?php echo $curr_user[0]['mg_user_id'] ?>" data-level="<?php echo $curr_user[0]['level'] ?>"></span><?php echo $curr_user[0]['mg_user_id'] ?></td>
                                    <td><?php echo $curr_user[0]['mg_user_name'] ?></td>
                                    <td><?php echo $role_names[$curr_user[0]['level']] ?></td>
                                    <td><?php echo $curr_user[0]['curr_count'] ?></td>
                                    <td><?php echo $curr_user[0]['phone'] ?></td>
                                    <td><?php echo $curr_user[0]['invotecode'] ?></td>
                                </tr>
<?php } ?>
<?php if ($list) { ?>
                                <?php foreach ($list as $value): ?>
                                    <tr>
                                        <td style="text-align: left"><span class="show_up" data-uid="<?php echo $value['mg_user_id'] ?>" data-level="<?php echo $value['level'] ?>" data-visible="1" style="font-size:25px"><div style="display:inline-block;width:20px;">&nbsp;</div>>+</span><?php echo $value['mg_user_id'] ?></td>
                                        <td><?php echo $value['mg_user_name'] ?></td>
                                        <td><?php echo $role_names[$value['level']] ?></td>
                                        <td><?php echo $value['curr_count'] ?></td>
                                        <td><?php echo $value['phone'] ?></td>
                                        <td style="text-align: center; margin:0 8px;"><?php echo $value['invotecode'] ?></td>
                                    </tr>
    <?php endforeach; ?>
    <?php unset($value) ?>
<?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</body>
</html>
<script type="text/javascript">
    $(function(){
        // 展开收缩树状
        $('.user-list').on('click', '.show_up', function(){
            var uid = $(this).attr('data-uid');
            var level = $(this).attr('data-level');
            var visible = $(this).attr('data-visible');
            var head_comtemt = $(this).html();
            if(visible == 1){ //就展开
                $(this).addClass('show_up_close'+uid);
                 // $(this).removeClass('show_up');
                 
                 $.post("<?php echo get_url('/index.php/user/ajax_get_users1'); ?>",{'uid':uid,'level':level,'head_comtemt':head_comtemt}, function(respond){
                    console.log(respond);
                    var obj_str = ".show_up_close"+respond.uid;
                    
                    if(respond.status == 1){ //有下级
                        console.log();
                        $(obj_str).attr('data-visible', 0);
                        $(obj_str).parent().parent().after(respond.msg);
                        $(obj_str).html(respond.head_comtemt);
                        $(obj_str).removeClass('show_up_close'+respond.uid);
                    }else{//无下级
                        $(obj_str).attr('data-visible', 3);
                        $(obj_str).html(respond.head_comtemt);
                        $(obj_str).removeClass('show_up_close'+respond.uid);
                    }
                    
                 },'json');
            }else if(visible == 0){ //收缩
                // 找到当前用户的所有下级
                $('.parent_is_'+uid).parent().parent().remove();
                // console.log(1);
                $(this).html(head_comtemt+'+');
                $(this).attr('data-visible', 1);
                // $(this).removeClass('show_up_close'+uid);
            }
        });
        
        // 选项面板
        var v_contentWidth = document.body.scrollWidth
        var v_lenght = $('.option-panel .option-panel-title').children().length
        var v_optionPanelTitleW = (v_contentWidth / v_lenght) - (v_lenght - 1)
        $('.option-panel .option-panel-title div').css('width',v_optionPanelTitleW+'px')

        // 选项面板高度
        var v_optionPanelPanelH = $('.option-panel').height() - 40
        $('.option-panel .option-panel-panel').height(v_optionPanelPanelH)

        // 选项面板默认面板显示
        var v_documents = $('.option-panel .option-panel-title').children()
        for (var i = 0; i < v_documents.length; i++) {
            if($(v_documents[i]).hasClass('active')){
                $('.option-panel .option-panel-panel').children().eq(i).css('display','block')
            }
        }

        // 选中事件
        $('.option-panel .option-panel-title div').click(function(){
            $('.option-panel .option-panel-title div').removeClass('active')
            $(this).addClass('active')
            var v_documents = $('.option-panel .option-panel-title').children()
            for (var i = 0; i < v_documents.length; i++) {
                if($(v_documents[i]).hasClass('active')){
                    $('.option-panel .option-panel-panel').children().eq(i).css('display','block')
                }else{
                    $('.option-panel .option-panel-panel').children().eq(i).css('display','none')
                }
            }
        })

        // 查询会员事件
        $('#queryUserBtnAgent').click(function(){
            var v_query_user_id = $('#query_user_id').val()
            $.post('<?php echo get_url('/index.php/Welcome/ajax_queryUserAgent'); ?>',{query_user_id:v_query_user_id},function(respond){
                Tost(respond.msg)
                if(respond.status){
                    var v_query_user_child = '<div class="query-fruit-item" onclick="provideRoomCardAgent('+respond.user_id+')"><img src="<?php echo r_url('imgs/head-sculpture.jpg'); ?>" alt="火男1号" /><div class="text"><p>'+respond.user_name+'</p><p>房卡数量：'+respond.user_room_card+'</p></div></div>'
                    $('#query_fruit').html(v_query_user_child)
                }
            },'json')
        })

        $('.user-del-btn').click(function(){
            return confirm("您确定要删除吗？");
        });
          
    })

    // 拨卡
    function provideRoomCardAgent(v_user_id){
        var v_room_card_number = prompt("请输入拨卡数量","1")
        v_room_card_number = parseInt(v_room_card_number)
        $.post('<?php echo get_url('/index.php/Welcome/ajax_provideRoomCardAgent'); ?>',{user_id:v_user_id,room_card_number:v_room_card_number},
                function(respond){
                    Tost(respond.msg)
                    if(respond.status){
                        setTimeout(function(){
                            location.reload()
                        },1000)
                    }
                },'json')
    }

</script>

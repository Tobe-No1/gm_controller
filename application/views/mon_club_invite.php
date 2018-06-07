<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>亲友圈邀请</title>

    <!-- Bootstrap -->
    <link href="<?php echo r_url_new('css/bootstrap.min.css'); ?>" type="text/css" rel="stylesheet">
    <link href="<?php echo r_url_new('css/site.css'); ?>" rel="stylesheet"/>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }

        a {
            text-decoration: none;
        }

        .weixin-tip img {
            max-width: 100%;
            height: auto;
        }

        .weixin-tip {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            background: rgba(0, 0, 0, 0.8);
            filter: alpha(opacity=80);
            width: 100%;
            height: 100%;
            z-index: 100;
        }

        .weixin-tip p {
            text-align: center;
            margin-top: 10%;
            padding: 0 5%;
            position: relative;
        }

        .weixin-tip .close {
            color: #fff;
            padding: 5px;
            font: bold 20px/24px simsun;
            text-shadow: 0 1px 0 #ddd;
            position: absolute;
            top: 0;
            left: 5%;
        }
        .msg_tip p span{color: #ECDB86}
    </style>
</head>

<body class="">
<img id="bg" src="<?php echo r_url_new('img/bg.png'); ?>" width="100%" height="100%" style="right: 0; bottom: 0;position: absolute; top: 0; z-index=-1" />
<div class="msg_tip">
    <img src="<?php echo isset($invite_head)? $invite_head : r_url_new('img/head.png'); ?>">
    <p>玩家<span><?php echo $invite_name? $invite_name:"";?></span></p><p>邀请你加入亲友圈:</p><p><span><?php echo $club_name? $club_name:"";?></span></p>
</div>
<div class="btnBox">
    <a href="#" class="downloadBtn" uid="<?php echo $uid?>" invite_id="<?php echo $invite_id?>" club_id="<?php echo $club_id?>" account="<?php echo $account?>"><img src="<?php echo r_url_new('img/icon-03.png'); ?>" /></a>
</div>
<div class="weixin-tip">
    <p> <img src="<?php echo r_url_new('img/weixin_tips.png'); ?>" alt="在浏览器打开"><span id="close" title="关闭" class="close" onclick="$(this).closest('div').hide()">×</span> </p>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?php echo r_url_new('js/jquery.min.js'); ?>"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo r_url_new('js/bootstrap.min.js'); ?>"></script>
<script>
    window.alert = function(name){
        var iframe = document.createElement("IFRAME");
        iframe.style.display="none";
        iframe.setAttribute("src", 'data:text/plain,');
        document.documentElement.appendChild(iframe);
        window.frames[0].window.alert(name);
        iframe.parentNode.removeChild(iframe);
    }
    $(window).on("load", function() {
        // var winHeight = $(window).height();

       /* function is_weixin() {
            var ua = navigator.userAgent.toLowerCase();
            if(ua.match(/MicroMessenger/i) == "micromessenger") {
                return true;
            } else {
                return false;
            }
        }*/
        // var isWeixin = is_weixin();
        $(".btnBox a").click(function() {
            var uid = parseInt($(this).attr('uid'));
            var invite_id = parseInt($(this).attr('invite_id'));
            var club_id = parseInt($(this).attr('club_id'));
            var account = $(this).attr('account');
            var data = {'uid':uid,'invite_id':invite_id,'club_id':club_id,'account':account};
            /*if(isWeixin) {
                $(".weixin-tip").css("height", winHeight);
                $(".weixin-tip").show();
                return false;
            }*/
            $.ajax({
                url:'/index.php/Welcome/ajax_club_invite',
                type:'post',
                dataType: 'json',
                data:data,
                error:function (XMLHttpRequest, textStatus, errorThrown) {
                    alert('socket连接失败');
                },
                success:function (respond) {
                    // alert(respond);
                    if (respond.error == 0) {
                        alert('恭喜加入成功');
                    } else{
                        alert(respond.msg);
                    }
                }
            });
        });
    })
</script>
</body>

</html>

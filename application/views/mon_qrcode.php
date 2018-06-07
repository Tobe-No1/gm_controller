<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>推广二维码</title>

    <!-- Bootstrap -->
    <link href="<?php echo r_url_new('css/bootstrap.min.css'); ?>" type="text/css" rel="stylesheet">
    <link href="<?php echo r_url_new('css/site.css'); ?>" rel="stylesheet"/>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }

        .msg_tip p{color: #ECDB86;font-size:0.6em;}
    </style>
</head>

<body class="">
<div class="msg_tip">
    <img src="<?=$url?>">
    <p><?php echo $msg;?></p>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?php echo r_url_new('js/jquery.min.js'); ?>"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo r_url_new('js/bootstrap.min.js'); ?>"></script>
</body>

</html>

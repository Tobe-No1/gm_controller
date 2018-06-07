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
                     <a class="button button-link button-nav pull-left" href="<?php echo get_url('/index.php/Login/menu'); ?>">
                        <span class="icon icon-left"></span>返回
                    </a>
                    <a class="button button-link button-nav pull-right js-exit" href="<?php echo get_url('/index.php/Login/loginout'); ?>">退出</a>
                    <h1 class="title">信息提示</h1>
                </header>

		<div class="content native-scroll">
                    <div class="list-block" style="text-align: center;font-size: 20px;">
				<?php echo $message ?>
			</div>
			<p style="margin: 1rem"><a href="<?php 
        if(isset($url)){
            echo $url;
        } else {
            echo get_url('/index.php/Welcome/menu'); 
        }
    ?>" class="button button-big button-fill">返回</a></p>
			
		</div>

	</div>
</div>
</body>
</html>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['AddExNum'] = 1000000   ;
//增加支付相关参数
$config['notifyUrl'] = 'http://gm.pb.soda666.cn/index.php/charge/CallBack_LYNet';	//回调地址
$config['returnUrl'] = 'http://gm.pb.soda666.cn/index.php/charge/view_charge';		//支付返回页面

//微信参数
$config['club_appid'] = 'wxdb5fd3c108479507';
$config['club_appkey'] = '0e2015ceb8284d5d4744de33abf4554f';
$config['appid'] = 'wxeeaa9edcb333a107';
$config['appkey'] = 'e1b84395230b58f893736beb4a12758d';

//二维码参数
$config['codeUrl'] = 'http://recommend.suda666.com';
$config['codeApp'] = 'gxzpw';//不同的平台不同的参数，相当于$game[pack],参照recommend/common.php

//后台支付参数
$config['pay_appid'] 	= '0000001040';
$config['pay_appkey'] 	= '855a5b0a1258738ba009f75951c593dc';


//下载地址
$config['download_url'] = 'xx';

$config['GMIP'] = '127.0.0.1';
$config['GMPORT'] = 8888;
$config['base_url'] = 'http://gm.com/';
$config['game_name'] = '苏达游戏';
$config['role_names'] = array( 0 => '公司', 1=>'一级代理', 2 =>'二级代理', 3=>'三级代理', 4=>'白金代理', 5=>'黄金代理', 6=>'白银代理',99=>'运营');
//大转盘奖励
$config['da_zhuan_pan'] = array(
     1      => array('id'=>1,  'min'=>1,  'max'=>25,  'txt'=>"1"),
     2      => array('id'=>2,  'min'=>26,  'max'=>55,  'txt'=>"2"),
     3      => array('id'=>3,  'min'=>56,  'max'=>75,  'txt'=>"5"),
     4      => array('id'=>4,  'min'=>76,  'max'=>90,  'txt'=>"10"),
     5      => array('id'=>5,  'min'=>91,  'max'=>100,  'txt'=>"20"),
     6      => array('id'=>6,  'min'=>0,  'max'=>0,  'txt'=>"50"),
     7      => array('id'=>7,  'min'=>0,  'max'=>0,  'txt'=>"100"),
     8      => array('id'=>8,  'min'=>0,  'max'=>0,  'txt'=>"150"),
     9      => array('id'=>9,  'min'=>0,  'max'=>0,  'txt'=>"200"),
     10     => array('id'=>10,  'min'=>0,  'max'=>0,  'txt'=>"300"),
);
//游戏类型
$config['game_type'] = array(1=>'牛牛',2=>'麻将',3=>'十三水',4=>'炸金花',5=>'斗地主',6=>'跑得快',7=>'三公',8=>'28杠',9=>'推筒子',10=>'打弹子',11=>'梭哈',13=>'四色牌',15=>'填大坑');
//麻将子类型
$config['majiang_type'] = array(1=>'转转',2=>'钦州',3=>'南宁',4=>'奈曼',5=>'河北',6=>'北海',7=>'鱼峰',8=>'敖汉',9=>'宁城',10=>'推倒胡',11=>'乌丹',2=>'钦州',3=>'南宁',4=>'奈曼',5=>'河北',6=>'北海',7=>'鱼峰',8=>'敖汉',9=>'宁城',10=>'推倒胡',11=>'乌丹',12=>'大连',13=>'托县',14=>'来宾',15=>'穷胡',16=>'郑州',17=>'一百张',18=>'平庄',19=>'天山',20=>'海拉尔',21=>'大田',22=>'葫芦岛',23=>'曲阳',24=>'昌图',25=>'同城',26=>'通辽',27=>'安康',28=>'防城港',29=>'石泉混爆',30=>'红中',31=>'南昌',32=>'德化',33=>'泉州',34=>'盖牌',35=>'亮喜推倒胡',36=>'内蒙穷胡',37=>'许昌',/*38=>'大板',39=>'大板',40=>'大板',*/41=>'大板');
$config['menus'] = array(
    1   => array('link'=> '/index.php/Statistic/base',          'is_show'=>true,    'name' => '基础统计',       'icon' => 'icon-today-statistics'),
    2   => array('link'=> '/index.php/Activity/index',          'is_show'=>true,    'name' => '活动管理',       'icon' => 'icon-activities-manage'),
    3   => array('link'=> '/index.php/Statistic/boka',  'is_show'=>true,    'name' => '拨卡统计',       'icon' => 'icon-dial-statistics'),
    5   => array('link'=> '/index.php/User/agent_boka',      'is_show'=>true,    'name' => '代理拨卡',       'icon' => 'icon-agency-dial'),
    6   => array('link'=> '/index.php/User/player_boka',       'is_show'=>true,    'name' => '玩家拨卡',       'icon' => 'icon-player-dial'),
    //7   => array('link'=> '/index.php/Charge/view_charge',      'is_show'=>true,    'name' => '购卡',           'icon' => 'icon-buy-block'),
    8   => array('link'=> '/index.php/User/agent_add',                'is_show'=>true,    'name' => '激活代理',       'icon' => 'icon-new-account'),
    11  => array('link'=> '/index.php/Statistic/user_charge',   'is_show'=>true,    'name' => '充值明细',       'icon' => 'icon-charge-detail'),
//    13  => array('link'=> '/index.php/Statistic/agent_charge',  'is_show'=>true,    'name' => '代理购卡明细',   'icon' => 'icon-agency-detail'),
    14  => array('link'=> '/index.php/Login/update_pwd', 'is_show'=>true,    'name' => '修改密码',       'icon' => 'icon-amend-paddword'),
    15  => array('link'=> '/index.php/User/player',             'is_show'=>true,    'name' => '用户信息',       'icon' => 'icon-userinfo'),
    16  => array('link'=> '/index.php/User/fenghao_player',             'is_show'=>true,    'name' => '封号列表',       'icon' => 'icon-userinfo'),
//    16  => array('link'=> '/index.php/Activity/board',          'is_show'=>true,    'name' => '发布弹框公告',   'icon' => 'icon-issue-pop'),
//    17  => array('link'=> '/index.php/Gm/agent_query',          'is_show'=>true,    'name' => '信息查询',       'icon' => 'icon-message-inq'),
    18  => array('link'=> '/index.php/Gm/agent_list',           'is_show'=>true,    'name' => '代理管理',       'icon' => 'icon-account-manage'),
    19  => array('link'=> '/index.php/Statistic/paihang',          'is_show'=>true,    'name' => '七天乐排行',       'icon' => 'icon-today-statistics'),
    20  => array('link'=> '/index.php/Statistic/recommend_query',          'is_show'=>true,    'name' => '推荐代理',       'icon' => 'icon-account-manage'),
//    21  => array('link'=> '/index.php/User/award_query',          'is_show'=>true,    'name' => '中奖查询',       'icon' => 'icon-account-manage'),
    22  => array('link'=> '/index.php/Statistic/cost_rank',          'is_show'=>true,    'name' => '玩家消耗排行',       'icon' => 'icon-today-statistics'),
    23  => array('link'=> '/index.php/User/exchange',          'is_show'=>true,    'name' => '账号修改',       'icon' => 'icon-amend-paddword'),
);

$config['rights'] = array(
    99 => array(1,3,11,15,17,14),
    0 => array(1,22,2,3,8,5,6,7,11,13,15,16,17,18,20,14,21,23),
    1 => array(3,4,8,5,6,7,14,21),
    2 => array(3,4,8,5,6,7,14),
	3 => array(3,4,8,5,6,7,14),
	4 => array(3,4,8,5,6,7,14),
    5 => array(3,6,7,14),
);

$config['index_page'] = 'index.php';
$config['uri_protocol']	= 'REQUEST_URI';
$config['url_suffix'] = '';
$config['language']	= 'chinesesimplified';
$config['charset'] = 'UTF-8';
$config['enable_hooks'] = FALSE;
$config['subclass_prefix'] = 'MY_';
$config['composer_autoload'] = TRUE;
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
$config['allow_get_array'] = TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';
$config['log_threshold'] = 0;
$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| Log File Extension
|--------------------------------------------------------------------------
|G
| The default filename extension for log files. The default 'php' allows for
| protecting the log files via basic scripting, when they are to be stored
| under a publicly accessible directory.
|
| Note: Leaving it blank will default to 'php'.
|
*/
$config['log_file_extension'] = '';

/*
|--------------------------------------------------------------------------
| Log File Permissions
|--------------------------------------------------------------------------
|
| The file system permissions to be applied on newly created log files.
|
| IMPORTANT: This MUST be an integer (no quotes) and you MUST use octal
|            integer notation (i.e. 0700, 0644, etc.)
*/
$config['log_file_permissions'] = 0644;

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Error Views Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/views/errors/ directory.  Use a full server path with trailing slash.
|
*/
$config['error_views_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/cache/ directory.  Use a full server path with trailing slash.
|
*/
$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Include Query String
|--------------------------------------------------------------------------
|
| Whether to take the URL query string into consideration when generating
| output cache files. Valid options are:
|
|	FALSE      = Disabled
|	TRUE       = Enabled, take all query parameters into account.
|	             Please be aware that this may result in numerous cache
|	             files generated for the same page over and over again.
|	array('q') = Enabled, but only take into account the specified list
|	             of query parameters.
|
*/
$config['cache_query_string'] = FALSE;

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class, you must set an encryption key.
| See the user guide for more info.
|
| https://codeigniter.com/user_guide/libraries/encryption.html
|
*/
$config['encryption_key'] = '';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_driver'
|
|	The storage driver to use: files, database, redis, memcached
|
| 'sess_cookie_name'
|
|	The session cookie name, must contain only [0-9a-z_-] characters
|
| 'sess_expiration'
|
|	The number of SECONDS you want the session to last.
|	Setting to 0 (zero) means expire when the browser is closed.
|
| 'sess_save_path'
|
|	The location to save sessions to, driver dependent.
|
|	For the 'files' driver, it's a path to a writable directory.
|	WARNING: Only absolute paths are supported!
|
|	For the 'database' driver, it's a table name.
|	Please read up the manual for the format with other session drivers.
|
|	IMPORTANT: You are REQUIRED to set a valid save path!
|
| 'sess_match_ip'
|
|	Whether to match the user's IP address when reading the session data.
|
|	WARNING: If you're using the database driver, don't forget to update
|	         your session table's PRIMARY KEY when changing this setting.
|
| 'sess_time_to_update'
|
|	How many seconds between CI regenerating the session ID.
|
| 'sess_regenerate_destroy'
|
|	Whether to destroy session data associated with the old session ID
|	when auto-regenerating the session ID. When set to FALSE, the data
|	will be later deleted by the garbage collector.
|
| Other session cookie settings are shared with the rest of the application,
| except for 'cookie_prefix' and 'cookie_httponly', which are ignored here.
|
*/
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = '/tmp/';
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix'   = Set a cookie name prefix if you need to avoid collisions
| 'cookie_domain'   = Set to .your-domain.com for site-wide cookies
| 'cookie_path'     = Typically will be a forward slash
| 'cookie_secure'   = Cookie will only be set if a secure HTTPS connection exists.
| 'cookie_httponly' = Cookie will only be accessible via HTTP(S) (no javascript)
|
| Note: These settings (with the exception of 'cookie_prefix' and
|       'cookie_httponly') will also affect sessions.
|
*/
$config['cookie_prefix']	= '';
$config['cookie_domain']	= '';
$config['cookie_path']		= '/';
$config['cookie_secure']	= FALSE;
$config['cookie_httponly'] 	= FALSE;

/*
|--------------------------------------------------------------------------
| Standardize newlines
|--------------------------------------------------------------------------
|
| Determines whether to standardize newline characters in input data,
| meaning to replace \r\n, \r, \n occurrences with the PHP_EOL value.
|
| This is particularly useful for portability between UNIX-based OSes,
| (usually \n) and Windows (\r\n).
|
*/
$config['standardize_newlines'] = FALSE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
| WARNING: This feature is DEPRECATED and currently available only
|          for backwards compatibility purposes!
|
*/
$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name' = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_expire' = The number in seconds the token should expire.
| 'csrf_regenerate' = Regenerate token on every submission
| 'csrf_exclude_uris' = Array of URIs which ignore CSRF checks
*/
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| Only used if zlib.output_compression is turned off in your php.ini.
| Please do not use it together with httpd-level output compression.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not 'echo' any values with compression enabled.
|
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are 'local' or any PHP supported timezone. This preference tells
| the system whether to use your server's local time as the master 'now'
| reference, or convert it to the configured one timezone. See the 'date
| helper' page of the user guide for information regarding date handling.
|
*/
$config['time_reference'] = 'local';

/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
| Note: You need to have eval() enabled for this to work.
|
*/
$config['rewrite_short_tags'] = FALSE;

/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy
| IP addresses from which CodeIgniter should trust headers such as
| HTTP_X_FORWARDED_FOR and HTTP_CLIENT_IP in order to properly identify
| the visitor's IP address.
|
| You can use both an array or a comma-separated list of proxy addresses,
| as well as specifying whole subnets. Here are a few examples:
|
| Comma-separated:	'10.0.1.200,192.168.5.0/24'
| Array:		array('10.0.1.200', '192.168.5.0/24')
*/
$config['proxy_ips'] = '';

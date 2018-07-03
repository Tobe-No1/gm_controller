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
                                    <a href="<?php echo get_url('/index.php/Club/index'); ?>" class="item-link item-content">
                                            <div class="item-media"><i class="icon icon-f-left icon-charge-inq"></i></div>
                                            <div class="item-inner">
                                                    <div class="item-title">俱乐部</div>
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
        <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
         <script type="text/javascript" src="/resource/layer/layer.js"></script>
        <script>
<?php
if ($agree_privary == 0) {
    ?>

                        //示范一个公告层
                        //    $('.bankinfo').click(function(){
                        layer.open({
                            type: 1
                            , title: false //不显示标题栏
                            , closeBtn: false
                            , area: ['100%', '520px']
                            , shade: 0.8
                            , id: 'LAY_layuipro' //设定一个id，防止重复弹出
                            , resize: false
                            , btn: ['同意', '不同意']
                            , btnAlign: 'c'
                            , moveType: 1 //拖拽模式，0或者1
                            , content: '<div class="dhelp">' +
                                    '<h2 style="text-align:center"> 《苏达游戏》推广员协议</h2><div class="dcont">' +
                                    '<p>欢迎您注册/登录使用《苏达游戏》的推广员系统（下称“本系统”）。请您在注册/登录本系统前仔细阅读《苏达游戏推广员合作协议》（下称“本协议”）的所有内容，特别是有关推广员的义务的条款，以及免除、限制我方责任的条款。本协议是本公司（下称我方）与苏达游戏游戏推广人员（下称“推广员”或“你方”）所约定的规范双方权利、义务关系的电子合同（买卖合同）。<br/>' +
                                    '如您对本协议任何内容有异议，请不要注册及登录/使用本系统。当您点击确认键注册或者登录或使用本系统相关服务，则视为您已仔细阅读本协议所有内容，同意接受本协议的所有规定及我方相关政策的约束，成为推广员，有权从我方批量购买房卡及获得玩家购买《苏达游戏》（下称本游戏）的房卡等产品的利润或服务，并对外转售</p>' +
                                    '您登录本系统的Id和游戏id需要保持一致，此ID的生成是根据微信系统实名认证相一致，任何人使用本ID登录均视为本人。' +
                                    '<h3>一、合作内容</h3>' +
                                    '<p>1、协助我方提升本游戏的房卡销售数量，提升本游戏的玩家用户规模。<br/>' +
                                    '2、为实现前述目的，你方可通过线下场所或线上渠道（Q群、QQ、贴吧、论坛、空间、微信、微博等）、自身人际关系、非官方网络资源等各种合法方式和渠道，推广本游戏，我方将提供必要的支持和协助。<br/>' +
                                    '3、为实现前述目的，双方共同将为本游戏做正面宣传，扩大本游戏的知名度和美誉度。</p>' +
                                    '<h3>二、推广员的权利和义务</h3>' +
                                    '1、推广员成为本游戏的房卡和其他相关服务的批发商，有权从我方以低于市场统一定价的价格、大批量购买本游戏的房卡和其他相关服务，并以市场统一定价对外转售。<br/>' +
                                    '2、推广员有权参与我方组织的市场推广活动，并从我方获得相应的奖励。<br/>' +
                                    '3、推广员不享有本游戏在任何区域的独家合作资格。<br/>' +
                                    '4、推广员不得从我方之外的其他途径购买本游戏的房卡和其他相关服务，不得互相串货，不得私自降价销售，不得扰乱本游戏房卡和相关服务的市场价格体系。<br/>' +
                                    '5、推广员不得对用户歧视性销售，不得捆绑销售、搭便车销售。<br/>' +
                                    '6、在合作推广过程中，推广员的任何行为均不得包含有虚假、诱骗、违反国家法律法规的内容，也不得做出有悖于产品对外公布的相关公告、协议内容的承诺或行为，不得通过组织、参与违法活动（此处指的违法活动不仅仅局限于国家相关法律法规）来追求销售业绩。如有发现，你方独自承担一切法律后果，且我方有权向国家有关机关做举报。<br/>' +
                                    '7、推广员有义务积极维护和宣传我方的品牌、业务和政策，不煽动、不参与任何形式的不利于我方品牌、业务、政策的活动。你方不可假冒和改变我方的商标、标志、商业信息等，不得故意造成与我方产品或服务的混淆，不得以我方公司或员工名义对外从事任何活动。<br/>' +
                                    '8、推广员须保证在进行本游戏推广之前已经获得线上或者线下场所的许可，并遵守相应的线上线下法律规章制度，否则由此产生的纠纷和可能导致的一切责任由推广员本人承担；推广员同意我方对此免责。<br/>' +
                                    '9、推广员理解并认可，推广员在注册和使用本系统过程中所提供的个人资料及相关信息是我方判断你方身份的重要依据，你方应根据要求尽可能提供详尽的信息，并确保信息的完整性、合法性和真实性。如推广员提交的资料有任何变动，必须在本系统中及时更新,必要时以电话或邮件形式告知我方管理人员，以保证其资料准确性。如果推广员不能提供准确完整的个人信息、未及时更新相关信息或者提供相关的资料，将导致我方无法准确判断推广员的身份，从而影响到推广收益发放等操作。因此给推广员造成的损失，我方不承担任何责任。<br/>' +
                                    '10、如因推广员提交的信息资料不真实、不准确、不完整、不合法从而导致我方作出错误判断、遭受直接或间接经济损失的，我方有权立即取消该推广员的资格并追究推广员的法律责任。<br/>' +
                                    '11、推广员仅能依据本协议约定的内容，从事苏达游戏产品的推广活动，不得将本协议约定的权利、义务转让给任何第三方，不得擅自委托第三方从事本游戏的推广行为。<br/>' +
                                    '12、推广员拥有自主的权利，可单方面随时决定终止与我方的合作、并停止使用本系统相关服务。如我方对本协议内容或相关服务等作出任何变更，而推广员不同意有关变更内容的，推广员应立即终止与我方的合作、并停止使用本系统相关服务。<br/>' +
                                    '<h3>三、苏达游戏的权利和义务</h3>' +
                                    '1、苏达游戏会对推广员提交的注册信息和资料进行及时审核，并有权根据公司业务发展需要，做出通过或不通过的决定。<br/>' +
                                    '2、苏达游戏有权了解推广员的推广情况，获得与推广员信息和推广业绩相关的必要的资料、数据和有关证据。<br/>' +
                                    '3、苏达游戏会根据推广员的销售业绩和公司政策，将推广员应得的收益及时支付到位。<br/>' +
                                    '4、苏达游戏将通过本系统的消息通知、电子邮件等方式，将最新的公司政策（包括但不限于优惠政策、对推广员的销售业绩要求等）下发给推广员。推广员有义务遵守、配合公司的各项政策。<br/>' +
                                    '5、苏达游戏有权根据实际需要随时修改本协议，并通过公告、系统消息或邮件等形式公布，修改自公告公布之日、或消息送达推广员邮箱之日起生效。推广员如有异议，可立即停止本协议，并与我方协商结算事宜。<br/>' +
                                    '6、苏达游戏有权单方随时变更、中断或终止本系统及相关服务，且不需对玩家或任何第三方负责。除本协议另有约定外，苏达游戏将以电话、短信、系统消息/页面或者电子邮件方式通知推广员。终止合作通知自发出之时即视为送达。如因推广员资料中登记的联络方式不正确、不完整或者不真实而导致联络失败的，苏达游戏无需对因此导致的任何损失或损害承担法律责任。<br/>' +
                                    '7、苏达游戏有权根据公司业务发展需要，终止本协议涉及的合作。在此情况下，苏达游戏应通过发布公告等形式通知推广员，协议的终止自公告发布之日起生效。<br/>' +
                                    '8、苏达游戏将竭力保证本游戏相关系统和数据的稳定，但因技术问题或第三方原因导致产品宕机、数据丢失等问题，我方不向你方或其他第三方承担赔偿、补偿的法律义务。<br/>' +
                                    '9、苏达游戏有权要求推广员配合我方的相关推广工作，以保证推广本游戏的效果和效率。<br/>' +
                                    '10、尊重个人隐私是苏达游戏的重要政策。在未经授权的情况下，苏达游戏不会公开或向第三方透露推广员在申请时提交的个人资料。但如根据法律规定或政府有权部门的有效命令，或在以下三种情况下，苏达游戏可不经授权而向相关部门或第三方公开或透露推广员的个人信息：<br/>' +
                                    '•	(1)在紧急情况下维护推广员个人、第三人或社会大众的财产或人身安全；<br/>' +
                                    '•	(2)保持、维护苏达游戏的知识产权或其它合法权益；<br/>' +
                                    '•	(3)根据苏达游戏游戏服务的相关规则、制度、条款，应当公开或披露的。' +
                                    '<h3>四、推广收益</h3>' +
                                    '推广员按照苏达游戏相关规定申领推广收益。推广收益的发放形式目前为房卡的发放。具体兑换规则以公司的通知为准。苏达游戏将在后台确认推广业绩并在业绩确认后十个工作日内将推广收益发放至推广员账户。' +
                                    '<h3>五、声明</h3>' +
                                    '苏达游戏与推广员在此声明：双方不会因根据本协议产生的推广合作，构成任何形式的劳动合同关系或劳务关系。推广员不隶属于苏达游戏，不受苏达游戏内部规章制度的约束。苏达游戏除向推广员支付本协议约定的推广收益外，不承担推广员的任何其他费用，包括但不限于因推广员因推广活动所产生的成本、社会保险、福利和医疗保险费用等。<br/>' +
                                    '苏达游戏与推广员共同在此声明：本协议不包含任何可能理解为双方之间设立一种代理关系的内容。推广员无权代表苏达游戏对外缔结合同。推广员不得以苏达游戏的名义开展任何与本协议约定推广活动无关的活动，或者从事违法犯罪活动，否则一切后果由推广员自行承担，苏达游戏并保留追究其法律责任的权利。' +
                                    '<h3>六、违约责任</h3>' +
                                    '如推广员有违反本协议第二条第4、5、6、7、8、9、10、11款之规定的行为，苏达游戏有权根据推广员的违约事实和严重程度，对其采取扣发推广收益、停止发放推广收益、扣减推广员所持房卡、作废推广员所持房卡、暂停推广合作资格、停止推广合作资格等措施，并有权向推广员追讨其通过不当行为所获得的利益。' +
                                    '推广员对此充分理解并认可。' +
                                    '<h3>七、不可抗力</h3>' +
                                    '1、一方因不可抗力不能履行本协议的，根据不可抗力的影响，可以部分或者全部免除违约责任。但一方迟延履行后发生不可抗力的，不能免除责任。' +
                                    '2、一方因不可抗力不能履行本协议的，应当及时通知对方，以减轻可能给对方造成的损失，并在合理期限内提供证明。未及时通知的，对因迟延通知而造成的扩大损失依然承担赔偿责任。' +
                                    '3、一方因不可抗力不能履行合同，但尚未造成对本协议根本违约的，另一方应该在履约时间上给予对方适当的宽限；如果因不可抗力导致不能实现本协议目的的，双方均有权解除本协议。' +
                                    '<h3>八、协议的生效和终止</h3>' +
                                    '1、协议的生效：本协议自您登录推广系统并点击“我已同意《苏达游戏推广员合作协议》”时生效。<br/>' +
                                    '2. 下列情形之一出现时，本协议终止：<br/>' +
                                    '•	（1）本协议约定的合作期限届满；<br/>' +
                                    '•	（2）本游戏终止运营；<br/>' +
                                    '•	（3）在本协议有效期内，推广员被依法追究刑事责任的，苏达游戏有权单方面终止本协议；<br/>' +
                                    '•	（4）其他按照法律规定以及本协议约定的终止情况' +
                                    '<h3>九、其他约定</h3>' +
                                    '本协议未尽事宜，可由双方另行签订补充协议予以明确。补充协议的内容与本协议的内容有冲突的，优先适用补充协议的规定。' +
                                    '推广员与苏达游戏在合作中产生的争议，应当友好协商解决；协商不成的，任何一方均有权向被告所在地有管辖权的法院提起诉讼。' +
                                    '</div>' +
                                    '</div>'
                            ,

                            yes: function (index) {
                                $.post('/Welcome/agree_privary', {}, function (res) {
                                    layer.alert("您已经同意本协议")
                                    layer.close(index);
                                }, 'json');
                            },
                            success: function (layero) {
                                var btn = layero.find('.layui-layer-btn');
                                btn.find('.layui-layer-btn1').attr({
                                    href: '/Login/login'
                                });
                            }
                        });
                        //    });
<?php }
?>
        </script>
    </body>
</html>
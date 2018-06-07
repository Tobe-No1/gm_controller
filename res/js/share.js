
$(function () {

    "use strict";
    //退出按钮
    $(document).on('click', '.js-exit', function () {
        $.confirm('你确定退出吗？');
    });



    /*基本信息页-手机号码修改*/
    $(document).on("pageInit", ".page-group", function (e, id, page) {
        var $content = $(page).find('.content');
        //对话框
        $content.on('click', '.prompt-ok', function () {
            $.prompt('你叫什么问题?', function (value) {
                $.alert('你输入的名字是"' + value + '"');
            });
        });
        $("#begin").calendar({
            value: [$("#begin").attr('value')]
        });

        $("#over").calendar({
            value: [$("#over").attr('value')]
        });

        /*基础统计页-无限滚动*/
        var loading = false;
        // 每次加载添加多少条目
        var itemsPerLoad = 20;
        // 最多可加载的条目
        var maxItems = 100;
        var lastIndex = $('.list-container tr').length;
        function addItems(number, lastIndex) {
        }

        //预先加载20条
        addItems(itemsPerLoad, 0);

        $(page).on('infinite', function () {

            // 如果正在加载，则退出
            if (loading)
                return;
            // 设置flag
            loading = true;
            // 模拟1s的加载过程
            setTimeout(function () {
                // 重置加载flag
                loading = false;
                if (lastIndex >= maxItems) {
                    // 加载完毕，则注销无限加载事件，以防不必要的加载
                    $.detachInfiniteScroll($('.infinite-scroll'));
                    // 删除加载提示符
                    $('.infinite-scroll-preloader').remove();
                    return;
                }
                addItems(itemsPerLoad, lastIndex);

                // 更新最后加载的序号
                lastIndex = $('.list-container tr').length;
                $.refreshScroller();
            }, 1000);
        });


        //玩家拨卡
        $('#queryUserBtn').click(function () {
            var v_query_user_id = $('#query_user_id').val()
            $.post('/index.php/User/ajax_get_user', {query_user_id: v_query_user_id}, function (respond) {
                $.toast(respond.msg)
                if (respond.status) {
                    var v_query_user_child = '<li class="item-content provideRoomCard"  user_id="' + respond.uid + '" ><div class="item-media"><img src="' + respond.head + '" width="44"></div><div class="item-inner">\n\
                                    <div class="item-title-row"><div class="item-title">' + respond.name + '</div></div>\n\
                                    <div class="item-subtitle">房卡数量:' + respond.card + '&nbsp;&nbsp;群主:'+respond.qunzhu+'&nbsp;&nbsp;状态:'+respond.status+'</div>\n\
                                </div></li></div></li><li class="item-content setQunzhu" user_id="' + respond.uid + '"><div><p>设置群主</p></div></div></li>';
                    $('#query_fruit').html(v_query_user_child);

                    var v_query_user_child2 = '<li class="item-content "  user_id="' + respond.uid + '" ><div class="item-media"><img src="' + respond.head + '" width="44"></div><div class="item-inner">\n\
                                    <div class="item-title-row"><div class="item-title">' + respond.name + '</div></div>\n\
                                    <div class="item-subtitle">房卡数量:' + respond.card + '&nbsp;&nbsp;群主:'+respond.qunzhu+'&nbsp;&nbsp;状态:'+respond.status+'</div>\n\
                                </div></li></div></li><li class="item-content" user_id="' + respond.uid + '"></div></li>';
                    $('#query_fruit2').html(v_query_user_child2);
                }
            }, 'json');
        });

        //代理拨卡
        $('#queryUserBtnAgent').click(function () {
            var v_query_user_id = $('#query_user_id').val();
            $.post('/index.php/User/ajax_get_agent', {query_user_id: v_query_user_id}, function (respond) {
                $.toast(respond.msg)
                if (respond.status) {
                    var v_query_user_child = '<li class="item-content provideRoomCardAgent"  user_id="' + respond.user_id + '" ><div class="item-media"><img src="' + respond.head + '" width="44"></div><div class="item-inner">\n\
                                    <div class="item-title-row"><div class="item-title">' + respond.user_name + '</div></div>\n\
                                    <div class="item-subtitle">房卡数量:' + respond.count + '</div>\n\
                                </div></li>';
                    $('#query_fruit').html(v_query_user_child);
                }
            }, 'json')
        });
        
            $('.show_inviteid').click(function () {
                var uid = $(this).attr('agent_id');
                $.post("/index.php/Gm/ajax_agent_query", {'agent_id': uid}, function (respond) {
                    $.toast("上线代理：" + respond.query_info);
                }, 'json');
            });

            $('.fenghao').click(function () {
                var uid = $(this).attr('uid');
                var htm = $('#status'+uid).html();
                var tmp_htm = '';
                if(htm == '正常'){
                    tmp_htm = '封号';
                }else{
                    tmp_htm = '解封';
                }
                $.confirm("您确定把"+uid+tmp_htm,function(){
                     $.post("/index.php/User/ajax_change_status", {'uid': uid}, function (respond) {
                        if (respond.status == 0) {
                            $('#status' + uid).html('正常');
                        } else if (respond.status == 1) {
                            $('#status' + uid).html('封号');
                        }
                    }, 'json');
                });
            });
            $('.qunzhu_num').click(function () {
                var uid = $(this).attr('uid');
                var can_qunzhu = prompt("可设置次数")
                if (can_qunzhu === null) {
                    return;
                }
                $.post("/index.php/User/ajax_qunzhu_num", {'uid': uid, 'can_qunzhu': can_qunzhu}, function (respond) {
                    if (respond.status == 0) {
                        $('#qunzhu' + uid).html(can_qunzhu);
                    }
                }, 'json');
            });
            $('.change_phone').click(function () {
                var uid = $(this).attr('uid');
                var phone = prompt("电话号码")
                if (phone === null) {
                    return;
                }
                $.post("/index.php/User/ajax_change_phone", {'uid': uid, 'phone': phone}, function (respond) {
                    if (respond.status == 0) {
                        $('#phone' + uid).html(phone);
                    }
                }, 'json');
            });
            
             $('.member').on('click', function () {                // 拨卡
                var uid = $(this).attr('data-uid');
                var name = $(this).attr('data-name');
                var club_id = $(this).attr('data-club_id');
                var score = prompt("输入分数(正数上分,负数下分)", "0")
                score = parseInt(score)
                var tag = '上';
                if (score < 0) {
                    tag = '下';
                }
                $.confirm('你确定给"' + name + '"' + tag + score + '分？', function () {
                    $.post('/index.php/Club/add_score', {score: score, club_id: club_id, uid: uid},
                            function (respond) {
                                $.toast(respond.msg);
                            }, 'json');
                }
                );
            });

        $('.recommendid').click(function () {
            var uid = $(this).attr('uid');
            var recommend_id = prompt("请输入代理ID，修改成功后，被邀请码、代理等级自动更新");
            if (recommend_id === null) {
                return;
            }
            $.post("/index.php/User/ajax_change_recommend", {'uid': uid, "recommend_id": recommend_id}, function (respond) {
                switch (respond.status){
                    case 0:$.toast('修改成功');break;
                    case 2:$.toast('代理id不存在');break;
                    default:$.toast('修改失败');
                }
            }, 'json');
        });

        $('.awardid').click(function () {
            var uid = $(this).attr('uid');
            var award_key = $(this).attr('key');
            var award_status = $(this).attr('status');
			var award_id = $(this).attr('grade');
            //var award_id = prompt("颁奖等级",$(this).attr('grade'));
            if (award_id === null) {
                return;
            }
            var data = {'uid':uid,'award_id':award_id,'award_key':award_key,'award_status':award_status};
            $.ajax({
                url:'/index.php/User/ajax_award_boka',
                type:'post',
                dataType: 'json',
                data:data,
                error:function () {
                  alert('error');
                },
                success:function (respond) {
                    if (respond.status == 0) {
                        $.toast('发放成功');
                        setTimeout(function () {
                            location.reload();
                        },500);
                    } else{
                        $.toast('已发放，发放失败');
                    }
                }
            });
        });

        $('.inviteid').click(function () {
            var uid = $(this).attr('uid');
            var invite_id = prompt("邀请码", $('#id' + uid).html())
            if (invite_id === null) {
                return;
            }
            $.post("/index.php/User/ajax_change_invite", {'uid': uid, "invite_id": invite_id}, function (respond) {
                if (respond.status == 0) {
                    $('#id' + uid).html(invite_id);
                } else {
                    $.toast('邀请码已经存');
                }
            }, 'json');
        });
        
        $('.cancelqunzhu').click(function () {
            var uid = $(this).attr('uid');
            $.post("/index.php/User/cancel_qunzhu", {'uid': uid}, function (respond) {
                if (respond.status == 0) {
                    $.toast('取消成功');
                    $('#qunzhu'+uid).html(0);
                } else{
                    $.toast('取消失败');
                }
            }, 'json');
        });
        
         $('.mg_name').click(function () {
            var uid = $(this).attr('uid');
            var mg_name = prompt("备注名", $('#id' + uid).html())
            if (mg_name === null) {
                return;
            }
            $.post("/index.php/User/ajax_update_agent", {'uid': uid, "mg_name": mg_name}, function (respond) {
                if (respond.status == 1) {
                    $.toast('修改成功');
                } else{
                    $.toast('修改失败');
                }
            }, 'json');
        });
        
        $('.changepwd').click(function () {
            var uid = $(this).attr('uid');
            var pwd = prompt("修改密码");
            if (pwd === null) {
                return;
            }
            $.post("/index.php/Gm/charge_pwd", {'uid': uid, "pwd": pwd}, function (respond) {
                if (respond.status == 1) {
                    $.toast('密码不能为空');
                } else {
                    $.toast('修改成功');
                }
            }, 'json');
        });

        $('.shangxian').click(function () {
            var uid = $(this).attr('uid');
            $.post("/index.php/Gm/ajax_agent_query", {'agent_id': uid}, function (respond) {
                $.toast("上线代理：" + respond.query_info);
            }, 'json');
        });


        $('.15day').click(function () {
            var uid = $(this).attr('uid');
            $.post("/index.php/Gm/day15_yeji", {'uid': uid}, function (respond) {
                $.toast("15天业绩：" + respond.total_money);
            }, 'json');
        });
        $('.history').click(function () {
            var uid = $(this).attr('uid');
            $.post("/index.php/Gm/history_yeji", {'uid': uid}, function (respond) {
                $.toast("历史业绩：" + respond.total_money);
            }, 'json');
        });

        $('.clean_agent').click(function () {
            var uid = $(this).attr('uid');
            $.post("/index.php/Gm/clean_agent", {'uid': uid}, function (respond) {
                if (respond.status == 1) {
                    $.toast("代理下面有子代理，不能删除");
                } else if (respond.status == 2) {
                    $.toast("代理有邀请玩家，不能删除");
                } else if (respond.status == 0) {
                    $.toast("删除成功");
                    $('#dd' + uid).remove();
                }

            }, 'json');
        });

        // 添加代理
        $('#upPwdBtn').click(function () {
            // 更新密码
            $.post('/index.php/Login/uppwd', $('#form1').serializeArray(), function (respond) {
                $.toast(respond.msg);
                if (respond.status) {
                    location.reload();
                }
            }, 'json');
        });/*$.post('/index.php/user/doAdd', $('#form1').serializeArray(), function (respond) {
                $.toast(respond.msg);
            }, 'json');*/
        $('#upBtn').click(function () {

            $.ajax({
                url:'/index.php/user/doAdd',
                type:'post',
                data:$('#form1').serializeArray(),
                dataType:'json',
                error: function(){
                    alert('error');
                },
                success:function (respond) {
                $.toast(respond.msg);
                }
            });
        })

    });
    
    var has_click = '0';
    $ (document).on("click", ".provideRoomCard", function () { 
        var v_user_id = $(this).attr('user_id');
        if(has_click == '1') { return; }
            has_click = '1';
         $.prompt('请输入发放数量', function (value) {
                $.post('/index.php/User/ajax_player_boka', {user_id: v_user_id, room_card_number: value},
                        function (respond) {
                            has_click = '0';
                            $.toast(respond.msg)
                            if (respond.status) {
                                setTimeout(function () {
                                    var v_query_user_id = $('#query_user_id').val();
                                    $.post('/index.php/User/ajax_get_user', {query_user_id: v_query_user_id}, function (respond) {
                                         $.toast(respond.msg)
                                        if (respond.status) {
                                            var v_query_user_child = '<li class="item-content provideRoomCard"  user_id="' + respond.uid + '"><div class="item-media"><img src="' + respond.head + '" width="44"></div><div class="item-inner">\n\
                                                    <div class="item-title-row"><div class="item-title">' + respond.name + '</div></div>\n\
                                                    <div class="item-subtitle">房卡数量:' + respond.card + '&nbsp;&nbsp;群主:'+respond.qunzhu+'&nbsp;&nbsp;状态:'+respond.status+'</div>\n\</div>\n\
                                                </div></li><li class="item-content setQunzhu" user_id="' + respond.uid + '"><div><p>设置群主</p></div></div></li>';
                                            $('#query_fruit').html(v_query_user_child);
                                        }
                                    }, 'json');
                                }, 1000)
                            }
                        }, 'json')
            },function(){ has_click = '0';});
    })
    
     $ (document).on("click", ".setQunzhu", function () { 
            var v_user_id = $(this).attr('user_id');
            $.prompt('1为群主', function (value) {
                $.post('/index.php/User/ajax_setQunzhu', {user_id:v_user_id,is_qunzhu:value},
                        function (respond) {
                            has_click = '0';
                            $.toast(respond.msg)
                            if (respond.status) {
                                setTimeout(function () {
                                    var v_query_user_id = $('#query_user_id').val();
                                    $.post('/index.php/User/ajax_get_user', {query_user_id: v_query_user_id}, function (respond) {
                                         $.toast(respond.msg)
                                        if (respond.status) {
                                            var v_query_user_child = '<li class="item-content provideRoomCard"  user_id="' + respond.uid + '"><div class="item-media"><img src="' + respond.head + '" width="44"></div><div class="item-inner">\n\
                                                    <div class="item-title-row"><div class="item-title">' + respond.name + '</div></div>\n\
                                                    <div class="item-subtitle">房卡数量:' + respond.card + '&nbsp;&nbsp;群主:'+respond.qunzhu+'&nbsp;&nbsp;状态:'+respond.status+'</div>\n\</div>\n\
                                                </div></li><li class="item-content setQunzhu" user_id="' + respond.uid + '"><div><p>设置群主</p></div></div></li>';
                                            $('#query_fruit').html(v_query_user_child);
                                        }
                                    }, 'json');
                                }, 1000)
                            }
                        }, 'json')
            },function(){ });
    })
    $(document).on("click", ".setFenghao", function () { 
		var v_user_id = $(this).attr('user_id');
		$.confirm("您确定把'"+v_user_id+"'封号？",function(){
            
            $.post('/index.php/User/ajax_setFenghao', {user_id:v_user_id},
            function (respond) {
                if(respond.status==0){
                    $.toast("封号成功");
                    setTimeout(function () {
                        var v_query_user_id = $('#query_user_id').val();
                        $.post('/index.php/User/ajax_get_user', {query_user_id: v_query_user_id}, function (respond) {
                             $.toast(respond.msg)
                            if (respond.status) {
                                var v_query_user_child = '<li class="item-content provideRoomCard"  user_id="' + respond.uid + '"><div class="item-media"><img src="' + respond.head + '" width="44"></div><div class="item-inner">\n\
                                        <div class="item-title-row"><div class="item-title">' + respond.name + '</div></div>\n\
                                        <div class="item-subtitle">房卡数量:' + respond.card + '&nbsp;&nbsp;群主:'+respond.qunzhu+'&nbsp;&nbsp;状态:'+respond.status+'</div>\n\</div>\n\
                                    </div></li><li class="item-content setQunzhu" user_id="' + respond.uid + '"><div><p>设置群主</p></div></div></li>';
                                $('#query_fruit').html(v_query_user_child);
                            }
                        }, 'json');
                    }, 1000)
                }
            }, 'json');
		});
        },function(){ });
    
    
    
    var has_click_agent = '0';
    $(document).on("click",".provideRoomCardAgent", function () {
            var v_user_id = $(this).attr('user_id');
            if(has_click_agent == '1') { return; }
            has_click_agent = '1';
            $.prompt('请输入发放数量', function (value) {
                $.post('/index.php/User/ajax_boka_agent', {user_id: v_user_id, room_card_number: value},
                        function (respond) {
                             has_click_agent = '0';
                            $.toast(respond.msg);
                            if (respond.status) {
                                setTimeout(function () {
                                    var v_query_user_id = $('#query_user_id').val()
                                    $.post('/index.php/User/ajax_get_agent', {query_user_id: v_query_user_id}, function (respond) {
                                        $.toast(respond.msg);
                                        if (respond.status) {
                                            var v_query_user_child = '<li class="item-content provideRoomCardAgent"  user_id="' + respond.user_id + '" ><div class="item-media"><img src="' + respond.head + '" width="44"></div><div class="item-inner">\n\
                                            <div class="item-title-row"><div class="item-title">' + respond.user_name + '</div></div>\n\
                                            <div class="item-subtitle">房卡数量:' + respond.count + '</div>\n\
                                        </div></li>';
                                            $('#query_fruit').html(v_query_user_child);
                                        }
                                    }, 'json');
                                }, 1000)
                            }
                        }, 'json')
            },function(){ has_click_agent = '0';});
        });
    
    $.init();
})



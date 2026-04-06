<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <link rel="stylesheet" type="text/css" href="{$skin}css/member_basic.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}style/member_center.css" />
    {tpl style}
    <title>会员中心-{$webname}</title>
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header nav_header">
            <a href="javascript:void(0);" class="navbar-brand" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left icon"></span></a>
            <a href="javascript:void(0);" class="btn refresh" onclick="window.location.reload();"><span class="glyphicon glyphicon-refresh"></span></a>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">用户中心</p>
    </div>
</nav>
<div id="user_info">
    {tpl info}
    <div class="member_lists">
        <h4><img src="{$skin}img/user/hytb.png" alt="个人信息" class="img-responsive"/> 会员信息</h4>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                    <a href="{:url('users/info')}">
                        <div class="thumbnail" style="border:none;">
                            <img src="{$skin}img/user/myinfo.png" alt="个人资料" />
                            <div class="caption">
                                <h3>个人资料</h3>
                            </div>
                        </div>
                    </a>
                </div>
            	
            	<?php if(isset($_SESSION['isagent']) && !$_SESSION['isagent'] && !AGENT_MODEL){?>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                    <a href="{:url('users/pay')}">
                        <div class="thumbnail" style="border:none;">
                            <img src="{$skin}img/user/online.png" alt="在线充值" />
                            <div class="caption">
                                <h3>在线充值</h3>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                    <a href="{:url('banking/withdrawals')}">
                        <div class="thumbnail" style="border:none;">
                            <img src="{$skin}img/user/mstx.png" alt="马上提现" />
                            <div class="caption">
                                <h3>马上提现</h3>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                    <a href="{:url('Banking/index')}">
                        <div class="thumbnail" style="border:none;">
                            <img src="{$skin}img/user/skzh.png" alt="收款账号" />
                            <div class="caption">
                                <h3>收款帐号</h3>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                    <a href="{:url('Users/pay_order_list')}">
                        <div class="thumbnail" style="border:none;">
                            <img src="{$skin}img/user/txjl.png" alt="充值记录" />
                            <div class="caption">
                                <h3>充值记录</h3>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                    <a href="{:url('Banking/withdrawals_list')}">
                        <div class="thumbnail" style="border:none;">
                            <img src="{$skin}img/user/txjl.png" alt="提现记录" />
                            <div class="caption">
                                <h3>提现记录</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <?php }?>
                
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                    <a href="{:url('users/mybank')}">
                        <div class="thumbnail" style="border:none;">
                            <img src="{$skin}img/user/mybank.png" alt="我的银行" />
                            <div class="caption">
                                <h3>我的银行</h3>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                    <a href="{:url('users/changePass')}">
                        <div class="thumbnail" style="border:none;">
                            <img src="{$skin}img/user/pass_modify.png" alt="密码修改" />
                            <div class="caption">
                                <h3>密码修改</h3>
                            </div>
                        </div>
                    </a>
                </div>
                
                <?php if(isset($_SESSION['isagent']) && !$_SESSION['isagent'] && AGENT_MODEL){?>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                    <a href="{:url('gift/giftlist')}">
                        <div class="thumbnail" style="border:none;">
                            <img src="{$skin}img/user/gift.png" alt="" />
                            <div class="caption">
                                <h3>兑换中心</h3>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                    <a href="{:url('gift/exchanglist')}">
                        <div class="thumbnail" style="border:none;">
                            <img src="{$skin}img/user/exchanglist.png" alt="" />
                            <div class="caption">
                                <h3>兑换记录</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <?php }?>
                
                
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                    <a href="{:url('activity/myfirends')}">
                        <div class="thumbnail" style="border:none;">
                            <img src="{$skin}img/user/myxx.png" alt="我的下线" />
                            <div class="caption">
                                <h3>我的下线</h3>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                    <a href="{:url('Activity/firends')}">
                        <div class="thumbnail" style="border:none;">
                            <img src="{$skin}img/user/add_friend.png" alt="推荐好友" />
                            <div class="caption">
                                <h3>推荐好友</h3>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                    <a href="{:url('activity/recomrebate')}">
                        <div class="thumbnail" style="border:none;">
                            <img src="{$skin}img/user/mysy.png" alt="推荐奖励" />
                            <div class="caption">
                                <h3>推荐奖励</h3>
                            </div>
                        </div>
                    </a>
                </div>
                
                <?php if(isset($_SESSION['isagent']) && !$_SESSION['isagent']){?>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                    <a href="{:url('activity/redpack')}">
                        <div class="thumbnail" style="border:none;">
                            <img src="{$skin}img/user/hotpage.png" alt="红包领取" />
                            <div class="caption">
                                <h3>红包领取</h3>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                    <a href="{:url('activity/getpresslog')}">
                        <div class="thumbnail" style="border:none;">
                            <img src="{$skin}img/user/jiuji.png" alt="投注记录" />
                            <div class="caption">
                                <h3>投注记录</h3>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                    <a href="{:url('activity/lossrebate')}">
                        <div class="thumbnail" style="border:none;">
                            <img src="{$skin}img/user/fl.png" alt="亏损返利" />
                            <div class="caption">
                                <h3>亏损返利</h3>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                    <a href="{:url('activity/rankrebate')}">
                        <div class="thumbnail" style="border:none;">
                            <img src="{$skin}img/user/fl.png" alt="排行奖励" />
                            <div class="caption">
                                <h3>排行奖励</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <?php }?>
                
                <?php if(isset($_SESSION['isagent']) && !$_SESSION['isagent'] && !AGENT_MODEL){?>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                    <a href="{:url('activity/rechargerebate')}">
                        <div class="thumbnail" style="border:none;">
                            <img src="{$skin}img/user/fl.png" alt="首充返利" />
                            <div class="caption">
                                <h3>首充返利</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <?php }?>

                
            </div>
        </div>
    </div>
</div>
{tpl foot}
<div class="clearfix"></div>

<div class="footer">
	<?php if(!$is_weixin){?>
	<p>客服QQ:78271214 <a href="http://wpa.qq.com/msgrd?v=3&uin=78271214&site=qq&menu=yes">在线客服</a></p>
	<?php }else{?>
	<p>客服QQ:78271214 <a href="mqqwpa://im/chat?chat_type=wpa&uin=78271214&version=1&src_type=web&web_src=oicqzone.com">在线客服</a></p>
	<?php }?>
</div>

</body>
</html>

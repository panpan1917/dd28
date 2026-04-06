<script type="text/javascript">
function TopRefreshPoints(){
	var usersid = "<?php echo $_SESSION['usersid'];?>";
	if(usersid != ""){
        $.post('getuserpoints.php', {}, function(data){
            $("#leftpoints").val(data.points);
            $("#leftbankpoints").val(data.bankpoints);
        }, 'json');
	}
}
</script>


<div id="header">
    <div class="header_top width_1000">
        <div class="header_left pull-left"><a href="./mobile.php"><span class="icon glyphicon glyphicon-phone"></span>手机版</a>     <a href="http://www.luntan28.com/game/dnss.html" target="_blank">防劫持教程</a></div>
		<div class="header_right pull-right">
		
				<?php if (empty($_SESSION['usersid'])){?>
                <div class="input-group text_width" style="margin: -7px 3px;">
                    <input type="text" class="form-control" style="height:28px;width:150px;" maxlength="12" id="username2" autocomplete="off" placeholder="请输入手机号">
                </div>
                <div class="input-group text_width" style="margin: -7px 3px;">
                    <input type="password" class="form-control" style="height:28px;width:150px;" id="pass2" autocomplete="off" placeholder="密码">
                </div>
                <div class="input-group text_width" style="margin: -7px 3px;width:80px;">
                    <input type="text" class="form-control" style="height:28px;width:80px;" id="vcode2" autocomplete="off" placeholder="验证码" maxlength="6">
                </div>
                <div class="input-group text_width" style="margin: -5px 3px;width:82px;">
                    <img src="vcode.php" style="cursor:pointer;height:27px;" onclick="this.src='vcode.php?tm=' + Math.random();" />
                </div>
                <div class="input-group text_width" style="margin: -5px 3px;width:75px;">
                	<a href="javascript:;" id="login2"  class="btn btn-danger" style="outline:medium;color: #fff;line-height:1.139;">登录</a>
                </div>
                
                <input type="hidden" id="referer" value=""/>
                               　<a href="reg.php">免费注册</a>　<a href="forgetpass.php">忘记密码</a>
                <?php }else{?>
                
				<span class="hidden-xs">您好! <?php echo $_SESSION['nickname'] . "({$_SESSION['usersid']})" ?></span>
                <a href="/member.php" class="ti">我的账户</a> 
				<span class="hidden-xs">乐豆: <span id="leftpoints" class="hidden-xs"><?php echo Trans($_SESSION['points'])?></span> | 银行: <span id="leftbankpoints" class="hidden-xs"><?php echo Trans($_SESSION['bankpoints'])?></span></span> | 
				<a href="/slogin.php?act=logout" class="ti">退出</a> 
                
                <?php }?>
		</div>
	</div>
</div>

<div id="alert">
    <div class="alert alert-warning">
        <div class="alert_r width_1000">
            <p> 
            <span style="float:right;margin-right:20px;">客服QQ : 78271214</span>
            </p>
        </div>
    </div>
</div>


<div id="header_nav">
    <div class="navs width_1000">
        <h1><a href="/pcindex.php">滴滴28</a></h1>
        <ul class="nav_li">
            <li><a href="/pcindex.php">首页</a></li>
            <li><a href="/game.php">游戏乐园</a></li>
            <?php if(AGENT_MODEL){?><li><a href="/merchants.php">商务代理</a></li><?php }?>
            <?php if(AGENT_MODEL){?><li><a href="/gift.php">奖品兑换</a></li><?php }?>
            <li><a href="/active.php">活动专区</a></li>
			<li><a href="/rotate.php">幸运轮盘</a></li>
			<li><a href="/rankings.php">排行榜</a></li>
            <li><a href="/download.php">下载</a></li>
            <li><a href="/member.php">会员中心</a></li>
            <li><a href="/friend.php">推荐好友</a></li>
            <?php if(!AGENT_MODEL){?><li><a href="/member.php?controller=user&action=onlinepay">在线充值</a></li><?php }?>
        </ul>
    </div>
</div>




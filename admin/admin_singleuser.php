<?php
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理--单用户查询</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta name="GENERATOR" content="MSHTML 6.00.3790.4275">
	<link rel="stylesheet" type="text/css" href="images/css_body.css">
	<link rel="stylesheet" type="text/css" href="images/window.css">
	<script type="text/javascript" src="images/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="images/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="images/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="images/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
</head>
<body>
<div class="bodytitle">
	<div class="bodytitleleft"></div>
	<div class="bodytitletxt">单用户查询</div>
</div>
<!-- 菜单 -->
<div>
	<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
		<tr bgcolor="#FFFFFF">
			<td width="350">
				<select id="sltUserType">
					<option value="0">用户id</option>
					<option value="1">手机号</option>
					<option value="2">用户名</option>
					<option value="3">用户QQ</option>
					<option value="4">真实姓名</option>
				</select>
				<input id="txtSearhWord" type="text" style="width:130px" />
				<input type="button" value="查询" id="btnSearch" class="btn-1" />
			</td>
			<td>
				<table>
					<tr>
						<td>
							<a class="edi" id='aGameLog' href="user_gamelog.php"><input type="button" value="已开奖记录" id="btnGamelog" class="btn-1" /></a>
							<a class="edi" id='aLoginSuccess' href="user_loginsuccess.php"><input type="button" value="登录成功" id="btnLoginSuccess" class="btn-1" /></a>
							<a class="edi" id='aLoginFail' href="user_loginfail.php"><input type="button" value="登录失败" id="btnLoginFail" class="btn-1" /></a>
							<a class="edi" id='aPayLog' href="user_paylog.php"><input type="button" value="充值记录" id="btnPayLog" class="btn-1" /></a>
							<a class="edi" id='aTransLog' href="user_translog.php"><input type="button" value="转账记录" id="btnTransLog" class="btn-1" /></a>
							<a class="edi" id='aWinLose' href="user_winlose_day.php"><input type="button" value="每日输赢" id="btnWinLose" class="btn-1" /></a>
							<a class="edi" id='aScoreChange' href="user_score_changelog.php"><input type="button" value="游戏分值" id="btnScoreChangeLog" class="btn-1" /></a>
							<!--<a class="edi" id='aInGame' href="user_kg_allgamelog.php"><input type="button" value="近期投注游戏" id="btnInGameLog" class="btn-1" /></a>-->
						</td>
					</tr>
					<tr>
						<td>
							<a class="edi" id='aGameKgLog' href="user_kg_gamelog.php"><input type="button" value="未开奖记录" id="btnGameKglog" class="btn-1" /></a>
							<a class="edi" id='aScoreLog' href="user_scorelog.php"><input type="button" value="分值变化" id="btnScoreLog" class="btn-1" /></a>
							<a class="edi" id='aActionLog' href="user_actionlog.php"><input type="button" value="用户操作" id="btnUserLog" class="btn-1" /></a>
							<a class="edi" id='aChangeDetailLog' href="user_changedetaillog.php"><input type="button" value="修改记录" id="btnChangeLog" class="btn-1" /></a>
							<a class="edi" id='aValidLog' href="user_validlog.php"><input type="button" value="短信/邮箱" id="btnValidLog" class="btn-1" /></a>
							<a class="edi" id='aAllGameKgLog' href="user_kg_allgamelog.php"><input type="button" value="正在投注" id="btnGameAllKglog" class="btn-1" /></a>
							<a class="edi" id='aSendBagLog' href="user_sendbaglog.php"><input type="button" value="红包记录" id="btnSendBagLog" class="btn-1" /></a>
							<a class="edi" id='referrals' href="user_referrals.php?id"><input type="button" value="下线列表" id="btnScoreChangeLog" class="btn-1" /></a>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
<!-- 用户基本信息 -->
<div id="div_UserInfo">
	<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
		<tr bgcolor="#FFFFFF">
			<td style="width:80px">用户ID:<input id="txtUserIDx" type="text" style="display:none" /></td>
			<td style="width:230px"><label id="lblUserIDx"></label></td>
			<td style="width:80px">用户名:</td>
			<td style="width:130px"><span id="sUserName"></span></td>
			<td style="width:80px">游戏分:</td>
			<td style="width:180px"><label id="lblPoints"></label></td>
			<td style="width:80px" rowspan="3">帐号状态:</td>
			<td rowspan="3" colspan="3"><span id="sAccountState"></span> <br></td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td>昵称:</td>
			<td><input id="txtNickName" type="text" style="width:120px" />
				<input type="button" value="更改" id="btnChangeNickName" class="btn-1"/>                    </td>
			<td>当前经验:</td>
			<td ><label id="lblCurExp"></label></td>
			<td >银行分:</td>
			<td ><label id="lblBankPoints"></label></td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td><label id="lblLoginPwdInfo">登录密码</label></td>
			<td><input name="text3" type="text" id="txtLoginPwd" style="width:120px" />
				<input name="button3" type="button" class="btn-1" id="ChangeLoginPwd" value="更改" /></td>
			<td>累积经验:</td>
			<td><span id="sMaxExp"></span></td>
			<td>投注分:</td>
			<td><label id="lblLockPoint"></label></td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td><label id="lblBankPwdInfo">银行密码:</label></td>
			<td><input type="text" id="txtBankPwd" style="width:120px" />
				<input  type="button" class="btn-1" id="ChangeBankPwd" value="更改" /></td>
			<td>登录时间:</td>
			<td><label id="lblLastLoginTime"></label></td>
			<td>总分:</td>
			<td><span id="sTotalPoints" style="color:#FF0000"></span></td>
			<td>个人抽水:</td>
			<td colspan="3"><input id="kf" type="text" style="width:80px" />万分
				<input type="button" value="更改" id="btnChangekf" class="btn-1"/>
				
			</td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td><label id="lblMobileInfo">手机号码:</label></td>
			<td><input name="text2" type="text" id="txtMobile" style="width:120px" />
				<input name="button2" type="button" class="btn-1" id="btnChangeMobile"  value="更改" /></td>
			<td>注册时间:</td>
			<td><label id="lblRegTime"></label></td>
			<td>是否VIP:</td>
			<td><span id="sVIPStatus"></span></td>
			<td>正在投注游戏：</td>
			<td colspan="3"><span id="inGameStr"></span></td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td>验证手机?</td>
			<td><span id="sMobleStatus"></span></td>
			<td>登录IP:</td>
			<td><label id="lblLoginIP"></label></td>
			<td>推荐ID:</td>
			<td><input name="text" type="text" id="txtTjID" style="width:80px" onclick="tjuserdetil(this.value);" />
				<input name="button" type="button" class="btn-1" id="btnChangeTjID" value="更改" /></td>
			<td>返水：</td>
			<td colspan="3"><span id="score_rebate2"></span></td>
			
		</tr>
		<tr bgcolor="#FFFFFF">
			<td><label id="lblEmailInfo">邮箱:</label></td>
			<td><input name="text" type="text" id="txtEmail" style="width:120px" />
				<input name="button" type="button" class="btn-1" id="btnChangeEmail" value="更改" /></td>
			<td>注册IP:</td>
			<td><label id="lblRegIP"></label></td>
			<td>推荐收益:</td>
			<td><label id="lblTjIncome"></label></td>
			<td>红包：</td>
			<td colspan="3"><span id="score_hb"></span></td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td>验证邮箱?</td>
			<td><span id="sEmailStatus"></span></td>
			<td>是否代理:</td>
			<td><label id="lblIsAgent"></label></td>
			<td>推荐统计:</td>
			<td><label id="lblTjStatic"></label></td>
			<td>总流水：</td>
			<td colspan="3"><label id="lblTjTzStatic"></label></td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td>收款人名字:</td>
			<td><input name="text5" type="text" id="txtRecvCashName" style="width:120px" />
				<input name="button5" type="button" class="btn-1" id="btnChangeRecvCashName" value="更改" /></td>
			<td>用户类型:</td>
			<td><label id="lblUserType"></label></td>
			<td>QQ:</td>
			<td><input name="text" type="text" id="txtQQ" style="width:80px" />
				<input  type="button" class="btn-1" id="btnChangeQQ" value="更改" /></td>
			<td>领取亏损返利的流水倍数</td>
			<td><input name="text" type="text" id="txtMultiple" style="width:80px" />
				<input  type="button" class="btn-1" id="btnChangeMultiple" value="更改" /></td>
			<td>备注</td>
			<td><input name="text" type="text" id="txtMemo" style="width:90%" /><br>
				<input  type="button" class="btn-1" id="btnChangeMemo" value="更改" /></td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td>充值帐号:</td>
			<td colspan="9"><input name="text5" type="text" id="txtRechargeAccount" style="width:500px" />
				<input name="button" type="button" class="btn-1" id="btnChangeRechargeAccount" value="更改" /></td>
		</tr>
	</table>
	<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
		<tr bgcolor="#f5fafe">
			<td width="60px" >游戏输赢</td>
			<td align="center"><a class="edi" id='aGameLogFast28' href="user_gamelog.php?gametype=gamefast28">急速28</a></td>
			<td align="center"><a class="edi" id='aGameLogFast16' href="user_gamelog.php?gametype=gamefast16">急速16</a></td>
			<td align="center"><a class="edi" id='aGameLogFast11' href="user_gamelog.php?gametype=gamefast11">急速11</a></td>
			<td align="center"><a class="edi" id='aGameLogFast10' href="user_gamelog.php?gametype=gamefast10">急速10</a></td>
			<td align="center"><a class="edi" id='aGameLog28' href="user_gamelog.php?gametype=game28">蛋蛋28</a></td>
			<td align="center"><a class="edi" id='aGameLog36' href="user_gamelog.php?gametype=game36">蛋蛋36</a></td>
			<td align="center"><a class="edi" id='aGameLogSelf28' href="user_gamelog.php?gametype=gameself28">北京28</a></td>
			<td align="center"><a class="edi" id='aGameLogbj16' href="user_gamelog.php?gametype=gamebj16">北京16</a></td>
			<td align="center"><a class="edi" id='aGameLogbj36' href="user_gamelog.php?gametype=gamebj36">北京36</a></td>
			<td align="center"><a class="edi" id='aGameLogcan28' href="user_gamelog.php?gametype=gamecan28">加拿大28</a></td>
			<td align="center"><a class="edi" id='aGameLogcan16' href="user_gamelog.php?gametype=gamecan16">加拿大16</a></td>
			<td align="center">合计</td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td align="center">分数</td>
			<td align="center"><label id="lblGame_fast28"></label></td>
			<td align="center"><label id="lblGame_fast16"></label></td>
			<td align="center"><label id="lblGame_fast11"></label></td>
			<td align="center"><label id="lblGame_fast10"></label></td>
			<td align="center"><label id="lblGame_28"></label></td>
			<td align="center"><label id="lblGame_36"></label></td>
			<td align="center"><label id="lblGame_self28"></label></td>
			<td align="center"><label id="lblGame_bj16"></label></td>
			<td align="center"><label id="lblGame_bj36"></label></td>
			<td align="center"><label id="lblGame_can28"></label></td>
			<td align="center"><label id="lblGame_can16"></label></td>
			<td rowspan="11" align="center"><label id="lblScore_total" style="color: blue;"></label></td>
		</tr>
		<tr bgcolor="#f5fafe">
			<td width="60px" >游戏输赢</td>
			<td align="center"><a class="edi" id='aGameLogpk10' href="user_gamelog.php?gametype=gamepk10">pk10</a></td>
			<td align="center"><a class="edi" id='aGameLoggj10' href="user_gamelog.php?gametype=gamegj10">pk冠军</a></td>
			<td align="center"><a class="edi" id='aGameLogpk22' href="user_gamelog.php?gametype=gamepk22">pk22</a></td>
			<td align="center"><a class="edi" id='aGameLogpklh' href="user_gamelog.php?gametype=gamepklh">pk龙虎</a></td>
			<td align="center"><a class="edi" id='aGameLogpkgyj' href="user_gamelog.php?gametype=gamepkgyj">pk冠亚军</a></td>
			<td align="center"><a class="edi" id='aGameLoghg28' href="user_gamelog.php?gametype=gamehg28">韩国28</a></td>
			<td align="center"><a class="edi" id='aGameLoghg16' href="user_gamelog.php?gametype=gamehg16">韩国16</a></td>
			<td align="center"><a class="edi" id='aGameLoghg11' href="user_gamelog.php?gametype=gamehg11">韩国11</a></td>
			<td align="center"><a class="edi" id='aGameLoghg36' href="user_gamelog.php?gametype=gamehg36">韩国36</a></td>
			<td align="center"><a class="edi" id='aGameLogcan11' href="user_gamelog.php?gametype=gamecan11">加拿大11</a></td>
			<td align="center"><a class="edi" id='aGameLogcan36' href="user_gamelog.php?gametype=gamecan36">加拿大36</a></td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td align="center">分数</td>
			<td align="center"><label id="lblGame_pk10"></label></td>
			<td align="center"><label id="lblGame_gj10"></label></td>
			<td align="center"><label id="lblGame_pk22"></label></td>
			<td align="center"><label id="lblGame_pklh"></label></td>
			<td align="center"><label id="lblGame_pkgyj"></label></td>
			<td align="center"><label id="lblGame_hg28"></label></td>
			<td align="center"><label id="lblGame_hg16"></label></td>
			<td align="center"><label id="lblGame_hg11"></label></td>
			<td align="center"><label id="lblGame_hg36"></label></td>
			<td align="center"><label id="lblGame_can11"></label></td>
			<td align="center"><label id="lblGame_can36"></label></td>
		</tr>
		
		<tr bgcolor="#f5fafe">
			<td width="60px" >游戏输赢</td>
			<td align="center"><a class="edi" id='aGameLogFast22' href="user_gamelog.php?gametype=gamefast22">急速22</a></td>
			<td align="center"><a class="edi" id='aGameLogFast36' href="user_gamelog.php?gametype=gamefast36">急速36</a></td>
			<td align="center"><a class="edi" id='aGameLogFastgyj' href="user_gamelog.php?gametype=gamefastgyj">急速冠亚军</a></td>
			<td align="center"><a class="edi" id='aGameLogww' href="user_gamelog.php?gametype=gameww">蛋蛋外围</a></td>
			<td align="center"><a class="edi" id='aGameLogdw' href="user_gamelog.php?gametype=gamedw">蛋蛋定位</a></td>
			<td align="center"><a class="edi" id='aGameLogcanww' href="user_gamelog.php?gametype=gamecanww">加拿大外围</a></td>
			<td align="center"><a class="edi" id='aGameLogcandw' href="user_gamelog.php?gametype=gamecandw">加拿大定位</a></td>
			<td align="center"><a class="edi" id='aGameLogpksc' href="user_gamelog.php?gametype=gamepksc">pk赛车</a></td>
			<td align="center"><a class="edi" id='aGameLoghgww' href="user_gamelog.php?gametype=gamehgww">韩国外围</a></td>
			<td align="center"><a class="edi" id='aGameLoghgdw' href="user_gamelog.php?gametype=gamehgdw">韩国定位</a></td>
			<td align="center"><a class="edi" id='aGameLog28gd' href="user_gamelog.php?gametype=game28gd">固定蛋蛋28</a></td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td align="center">分数</td>
			<td align="center"><label id="lblGame_fast22"></label></td>
			<td align="center"><label id="lblGame_fast36"></label></td>
			<td align="center"><label id="lblGame_fastgyj"></label></td>
			<td align="center"><label id="lblGame_ww"></label></td>
			<td align="center"><label id="lblGame_dw"></label></td>
			<td align="center"><label id="lblGame_canww"></label></td>
			<td align="center"><label id="lblGame_candw"></label></td>
			<td align="center"><label id="lblGame_pksc"></label></td>
			<td align="center"><label id="lblGame_hgww"></label></td>
			<td align="center"><label id="lblGame_hgdw"></label></td>
			<td align="center"><label id="lblGame_28gd"></label></td>
		</tr>
		
		
		<tr bgcolor="#f5fafe">
			<td width="60px" >游戏输赢</td>
			<td align="center"><a class="edi" id='aGameLogbj28gd' href="user_gamelog.php?gametype=gamebj28gd">固定北京28</a></td>
			<td align="center"><a class="edi" id='aGameLoghg28gd' href="user_gamelog.php?gametype=gamehg28gd">固定韩国28</a></td>
			<td align="center"><a class="edi" id='aGameLogcan28gd' href="user_gamelog.php?gametype=gamecan28gd">固定加拿大28</a></td>
			<td align="center"><a class="edi" id='aGameLogxync' href="user_gamelog.php?gametype=gamexync">幸运农场</a></td>
			<td align="center"><a class="edi" id='aGameLogcqssc' href="user_gamelog.php?gametype=gamecqssc">重庆时时彩</a></td>
			<td align="center"><a class="edi" id='aGameLogbj11' href="user_gamelog.php?gametype=gamebj11">北京11</a></td>
			<td align="center"><a class="edi" id='aGameLog11' href="user_gamelog.php?gametype=game11">蛋蛋11</a></td>
			<td align="center"><a class="edi" id='aGameLog16' href="user_gamelog.php?gametype=game16">蛋蛋16</a></td>
			<td align="center"><a class="edi" id='aGameLogbjww' href="user_gamelog.php?gametype=gamebjww">北京外围</a></td>
			<td align="center"><a class="edi" id='aGameLogbjdw' href="user_gamelog.php?gametype=gamebjdw">北京定位</a></td>
			<td align="center"></td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td align="center">分数</td>
			<td align="center"><label id="lblGame_bj28gd"></label></td>
			<td align="center"><label id="lblGame_hg28gd"></label></td>
			<td align="center"><label id="lblGame_can28gd"></label></td>
			<td align="center"><label id="lblGame_xync"></label></td>
			<td align="center"><label id="lblGame_cqssc"></label></td>
			<td align="center"><label id="lblGame_bj11"></label></td>
			<td align="center"><label id="lblGame_11"></label></td>
			<td align="center"><label id="lblGame_16"></label></td>
			<td align="center"><label id="lblGame_bjww"></label></td>
			<td align="center"><label id="lblGame_bjdw"></label></td>
			<td align="center"></td>
		</tr>
		
		
		<tr bgcolor="#f5fafe">
			<td width="60px" >游戏输赢</td>
			<td align="center"><a class="edi" id='aGameLogairship10' href="user_gamelog.php?gametype=gameairship10">飞艇10</a></td>
			<td align="center"><a class="edi" id='aGameLogairship22' href="user_gamelog.php?gametype=gameairship22">飞艇22</a></td>
			<td align="center"><a class="edi" id='aGameLogairshipgj10' href="user_gamelog.php?gametype=gameairshipgj10">飞艇冠军</a></td>
			<td align="center"><a class="edi" id='aGameLogairshipgyj' href="user_gamelog.php?gametype=gameairshipgyj">飞艇冠亚军</a></td>
			<td align="center"><a class="edi" id='aGameLogairshiplh' href="user_gamelog.php?gametype=gameairshiplh">飞艇龙虎</a></td>
			<td align="center"></td>
			<td align="center"></td>
			<td align="center"></td>
			<td align="center"></td>
			<td align="center"></td>
			<td align="center"></td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td align="center">分数</td>
			<td align="center"><label id="lblGame_airship10"></label></td>
			<td align="center"><label id="lblGame_airship22"></label></td>
			<td align="center"><label id="lblGame_airshipgj10"></label></td>
			<td align="center"><label id="lblGame_airshipgyj"></label></td>
			<td align="center"><label id="lblGame_airshiplh"></label></td>
			<td align="center"></td>
			<td align="center"></td>
			<td align="center"></td>
			<td align="center"></td>
			<td align="center"></td>
			<td align="center"></td>
		</tr>
		
		
		
		<tr bgcolor="#f5fafe">
			<td align="center">其他来源</td>
			<td align="center"><a class="edi" id='btnAdminTrans' href="user_cz_record.php">系统充值</a></td>
			<td align="center"><a class="edi" id='aScore_PayOnline' href="user_scorelog.php?typeid=5">在线充值</a></td>
			<td align="center"><a class="edi" id='aScore_PayCard' href="admin_withdrawals.php">提现</a></td>
			<td align="center"><a class="edi" id='aScore_Zhuanpan' href="user_scorelog.php?typeid=70">转盘奖励</a></td>
			<td align="center"><a class="edi" id='aScore_Tuijian' href="user_scorelog.php?typeid=21">推荐奖励</a></td>
			<td align="center"><a class="edi" id='aScore_Panihangbang' href="user_scorelog.php?typeid=80">排行奖励</a></td>
			<td align="center"><a class="edi" id='aScore_RwardCard' href="user_scorelog.php?typeid=7">兑奖点卡</a></td>
			<td align="center"><a class="edi" id='aScore_Redbag' href="user_scorelog.php?typeid=40">收发红包</a></td>
			<td align="center"><a class="edi" id='aScore_Transfer' href="user_scorelog.php?typeid=20">亏损返利</a></td>
			<td align="center"><a class="edi" id='aScore_Register' href="#">注册送分</a></td>
			<td align="center">游戏合计</td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td align="center">分数</td>
			<td align="center"><label id="lblOther_100"></label></td>
			<td align="center"><label id="lblOther_101"></label></td>
			<td align="center"><label id="lblOther_103"></label></td>
			<td align="center"><label id="lblOther_102"></label></td>
			<td align="center"><label id="lblOther_104"></label></td>
			<td align="center"><label id="lblOther_105"></label></td>
			<td align="center"><label id="lblOther_106"></label></td>
			<td align="center"><label id="lblOther_107"></label></td>
			<td align="center"><label id="lblOther_108"></label></td>
			<td align="center"><label id="lblOther_120"></label></td>
			<td align="center"><label id="lblTotalGameWinLose"></label></td>
		</tr>
	</table>
	<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
		<tr bgcolor="#FFFFFF">
		<td valign="top">
				<span class="fbold">绑定的账号</span>
				<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" id="tblBdCard">
					<tr bgcolor="#f5fafe">
						<td align="center">绑定时间</td>
						<td align="center">类别</td>
						<td align="center">账号</td>
					</tr>
				</table>
			</td>
			<td valign="top">
				<span class="fbold">最近登录</span>
				<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" id="tblRecentLogin">
					<tr bgcolor="#f5fafe">
						<td align="center">时间</td>
						<td align="center">IP</td>
						<td align="center">分数</td>
						<td align="center">银行</td>
						<td align="center">经验</td>
					</tr>
				</table>
			</td>
			<td valign="top">
				<span class="fbold">最近充值</span>
				<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" id="tblRecentPay">
					<tr bgcolor="#f5fafe">
						<td align="center">时间</td>
						<td align="center">渠道</td>
						<td align="center">分数</td>
					</tr>
				</table>
			</td>
			<td valign="top">
				<span class="fbold">最近提现</span>
				<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" id="tblRecentTrans">
					<tr bgcolor="#f5fafe">
						<td align="center">时间</td>
						<td align="center">金额(RMB)</td>
						<td align="center">分数(积分)</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
</body>
<script type= "text/javascript" language ="javascript">
	$(window.parent.document).attr("title","单用户查询");
	//*************************************************************************************
	//基本信息
	$(document).ready(function() {
		BindClass();
		var userid = request("idx");
		if(userid != "")
		{
			$("#txtSearhWord").val(userid);
			GetBaseInfo();
		}
		//查询
		$("#btnSearch").click(function(){
			GetBaseInfo();
			return true;
		});
		//ID回车事件
		$("#txtSearhWord").keydown(function(event){
			if(event.keyCode==13)
			{
				GetBaseInfo();
				return true;
			}
		});
		$("#btnChangekf").click(function () {
			var UserIDx = $("#txtUserIDx").val();
			var kf=parseInt($("#kf").val());
			var data = "action=changekf&id=" + UserIDx +  "&kf=" + kf;
			SendAjax(data);
		});
		//更改昵称
		$("#btnChangeNickName").click(function(){
			ChangeDetailItem("nickname");
		});
		//更改密码
		$("#ChangeLoginPwd").click(function(){
			ChangeDetailItem("loginpwd");
		});
		//更改邮箱
		$("#btnChangeEmail").click(function(){
			ChangeDetailItem("email");
		});
		//更改推荐ID
		$("#btnChangeTjID").click(function(){
			ChangeDetailItem("tjid");
		});
		
		//更改手机
		$("#btnChangeMobile").click(function(){
			ChangeDetailItem("mobile");
		});
		//支付密码
		$("#ChangeBankPwd").click(function(){
			ChangeDetailItem("bankpwd");
		});
		//更改收款人名字
		$("#btnChangeRecvCashName").click(function(){
			ChangeDetailItem("recvname");
		});
		//更改QQ
		$("#btnChangeQQ").click(function(){
			ChangeDetailItem("qq");
		});
		//更改流水倍数
		$("#btnChangeMultiple").click(function(){
			ChangeDetailItem("multiple_loss");
		});
		//更改备注
		$("#btnChangeMemo").click(function(){
			ChangeDetailItem("memo");
		});
		//更改充值帐号
		$("#btnChangeRechargeAccount").click(function(){
			ChangeDetailItem("charge_account");
		});

		//冻结亏损返利
		$("#chkdj_rebate").change(function() { 
			ChangeDetailItem("dj_rebate");
		}); 

		//冻结排行奖励
		$("#chkdj_rankrebate").change(function() { 
			ChangeDetailItem("dj_rankrebate");
		}); 

		//冻结推荐奖励
		$("#chkdj_extension").change(function() { 
			ChangeDetailItem("dj_extension");
		}); 
	});
	//冻结
	$("#btnForbidden").live("click",function(){
		ChangeDetailItem("forbidden");
	});
	//解封
	$("#btnOpen").live("click",function(){
		ChangeDetailItem("open");
	});
	//绑定手机
	$("#btnBindMobile").live("click",function(){
		ChangeDetailItem("bindmobile");
	});
	//解绑手机
	$("#btnUnBindMobile").live("click",function(){
		ChangeDetailItem("unbindmobile");
	});
	//绑定邮箱
	$("#btnBindEmail").live("click",function(){
		ChangeDetailItem("bindemail");
	});
	//解绑邮箱
	$("#btnUnBindEmail").live("click",function(){
		ChangeDetailItem("unbindemail");
	});
	//查询用户基本数据
	function GetBaseInfo()
	{
		var data = "action=get_baseinfo";
		var SearchWord = $("#txtSearhWord").val()
		if(SearchWord == "")
		{
			alert("请输入关键词!");
			return;
		}
		if($("#sltUserType").val() == "0")
		{
			if(isNaN(SearchWord))
			{
				$("#txtSearhWord").val("");
				alert("请输入数字的ID!");
				return;
			}
		}
		if($("#sltUserType").val() == "1")
		{
			if(isNaN(SearchWord))
			{
				$("#txtSearhWord").val("");
				alert("请输入数字的手机号!");
				return;
			}
		}
		ChangeServiceLink(0);
		data = data + "&usertype=" + $("#sltUserType").val() + "&word=" + SearchWord;
		SendAjax(data);

		if($("#txtUserIDx").val() != "")
		{
			data = "action=get_recentlogin&id=" + $("#txtUserIDx").val();
			SendAjax(data);
			data = "action=get_recentpay&id=" + $("#txtUserIDx").val();
			SendAjax(data);
			data = "action=get_recenttrans&id=" + $("#txtUserIDx").val();
			SendAjax(data);
			data = "action=get_tblBdCard&id=" + $("#txtUserIDx").val();
			SendAjax(data);
		}
		BindClass();
	}

	function tjuserdetil(tjid){
		if(tjid > 0 && tjid !='')
		{
			window.open('index.php?url=admin_singleuser.php?idx='+tjid);
		}
	}

	
	//更改基本信息
	function ChangeDetailItem(ItemType)
	{
		var UserIDx = $("#txtUserIDx").val();
		if(UserIDx.length == 0)
		{
			alert("请先查询出用户才可操作!");
			return false;
		}
		var NewItem = "";
		var chgpwd = "";
		switch(ItemType)
		{
			case "dj_rebate":
				NewItem = 0;
				if($('#chkdj_rebate').is(':checked')) {
					NewItem = 1;
				}
				break;
			case "dj_rankrebate":
				NewItem = 0;
				if($('#chkdj_rankrebate').is(':checked')) {
					NewItem = 1;
				}
				break;
			case "dj_extension":
				NewItem = 0;
				if($('#chkdj_extension').is(':checked')) {
					NewItem = 1;
				}
				break;
			case "nickname":
				NewItem = $.trim($("#txtNickName").val());
				if(NewItem.length == 0 || NewItem.length > 50)
				{
					alert("请输入昵称，长度1-50");
					return false;
				}
				break;
			case "loginpwd":
				NewItem = $.trim($("#txtLoginPwd").val());
				if(NewItem.length < 6 || NewItem.length > 20)
				{
					alert("请输入6-20位密码");
					return false;
				}
				break;
			case "email":
				NewItem = $("#txtEmail").val();
				break;
			case "mobile":
				NewItem = $.trim($("#txtMobile").val());
				break;
			case "tjid":
				chgpwd = prompt("请输入密码:","");
				if (chgpwd != null && chgpwd != ""){
					;
				}else{
					return;
				}
				NewItem = $.trim($("#txtTjID").val());
				break;
			case "bankpwd":
				NewItem = $.trim($("#txtBankPwd").val());
				if(NewItem.length < 6 || NewItem.length > 20)
				{
					alert("请输入6-20位密码");
					return false;
				}
				break;
			case "recvname":
				chgpwd = prompt("请输入密码:","");
				if (chgpwd != null && chgpwd != ""){
					;
				}else{
					return;
				}
				NewItem = $.trim($("#txtRecvCashName").val());
				break;
			case "qq":
				NewItem = $.trim($("#txtQQ").val());
				break;
			case "multiple_loss":
				NewItem = $.trim($("#txtMultiple").val());
				break;
			case "memo":
				NewItem = $.trim($("#txtMemo").val());
				break;
			case "charge_account":
				chgpwd = prompt("请输入密码:","");
				if (chgpwd != null && chgpwd != ""){
					;
				}else{
					return;
				}
				NewItem = $.trim($("#txtRechargeAccount").val());
				break;
			case "forbidden":
				NewItem = $.trim($("#txtReason").val());
				if(NewItem.length == 0)
				{
					alert("请输入原因!");
					return false;
				}
				break;
			case "open":
				NewItem = $.trim($("#txtReason").val());
				if(NewItem.length == 0)
				{
					alert("请输入原因!");
					return false;
				}
				break;
			case "bindmobile":
				NewItem = $.trim($("#txtMobile").val());
				break;
			case "unbindmobile":
				NewItem = $.trim($("#txtMobile").val());
				break;
			case "bindemail":
				NewItem = $.trim($("#txtEmail").val());
				break;
			case "unbindemail":
				NewItem = $.trim($("#txtEmail").val());
				break;
			default:
				break;
		}
		var data = "action=changedetail&id=" + UserIDx + "&type=" + ItemType + "&newitem=" + NewItem + "&pwd=" + chgpwd;
		SendAjax(data);
		return true;
	}

	//*****************************************************************************************************
	//公共函数
	//取得当前url参数
	function request(paras)
	{
		var url = location.href;  //获取当前url地址
		var paraString = url.substring(url.indexOf("?")+1,url.length).split("&");
		var paraObj = {}
		for (i=0; j=paraString[i]; i++){
			paraObj[j.substring(0,j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=")+1,j.length);
		}
		var returnValue = paraObj[paras.toLowerCase()];
		if(typeof(returnValue)=="undefined"){
			return "";
		}else{
			return returnValue;
		}
	}
	//绑定
	function BindClass()
	{
		$(".edi").fancybox({
			type		: 'iframe',
			fitToView	: false,
			width		: '100%',
			height		: '100%',
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none'
		});
	}
	//修改链接
	function ChangeServiceLink(userid)
	{
		var attachText = "";
		if(userid != 0) attachText = "?id=" + userid;
		$("#aLoginSuccess").attr("href","user_loginsuccess.php" + attachText);
		$("#referrals").attr("href","user_referrals.php" + attachText);
		$("#aLoginFail").attr("href","user_loginfail.php" + attachText);
		$("#aPayLog").attr("href","user_paylog.php" + attachText);
		$("#aTransLog").attr("href","user_translog.php" + attachText + "&t=1");
		$("#aScoreLog").attr("href","user_scorelog.php" + attachText + "&t=2");
		$("#aActionLog").attr("href","user_actionlog.php" + attachText);
		$("#aChangeDetailLog").attr("href","user_changedetaillog.php" + attachText);
		$("#aValidLog").attr("href","user_validlog.php" + attachText);
		$("#aGameLog").attr("href","user_gamelog.php" + attachText);
		$("#aGameKgLog").attr("href","user_kg_gamelog.php" + attachText);
		$("#aWinLose").attr("href","user_winlose_day.php" + attachText);
		$("#aAllGameKgLog").attr("href","user_kg_allgamelog.php" + attachText);
		$("#aScoreChange").attr("href","user_score_changelog.php" + attachText);
		$("#aSendBagLog").attr("href","user_sendbaglog.php" + attachText);

		$("#btnAdminTrans").attr("href","user_cz_record.php" + attachText);
		$("#aScore_PayOnline").attr("href","user_scorelog.php" + attachText + "&typeid=5");
		$("#aScore_PayCard").attr("href","admin_withdrawals.php" + "?userid="+userid+"&status=32");
		$("#aScore_Zhuanpan").attr("href","user_scorelog.php" + attachText + "&typeid=70");
		$("#aScore_Tuijian").attr("href","user_scorelog.php" + attachText + "&typeid=21");
		$("#aScore_Panihangbang").attr("href","user_scorelog.php" + attachText + "&typeid=80");
		$("#aScore_RwardCard").attr("href","user_scorelog.php" + attachText + "&typeid=7");
		$("#aScore_Redbag").attr("href","user_scorelog.php" + attachText + "&typeid=40");
		$("#aScore_Transfer").attr("href","user_scorelog.php" + attachText + "&typeid=20");
		//$("#aScore_Register").attr("href","user_scorelog.php" + attachText + "&typeid=120");

		attachText = "&id=" + userid;
		$("#aGameLogFast28").attr("href","user_gamelog.php?gametype=gamefast28" + attachText);
		$("#aGameLogFast16").attr("href","user_gamelog.php?gametype=gamefast16" + attachText);
		$("#aGameLogFast11").attr("href","user_gamelog.php?gametype=gamefast11" + attachText);
		$("#aGameLogFast10").attr("href","user_gamelog.php?gametype=gamefast10" + attachText);
		$("#aGameLog28").attr("href","user_gamelog.php?gametype=game28" + attachText);
		$("#aGameLog36").attr("href","user_gamelog.php?gametype=game36" + attachText);
		$("#aGameLogSelf28").attr("href","user_gamelog.php?gametype=gameself28" + attachText);
		$("#aGameLogbj16").attr("href","user_gamelog.php?gametype=gamebj16" + attachText);
		$("#aGameLogbj36").attr("href","user_gamelog.php?gametype=gamebj36" + attachText);
		$("#aGameLogcan28").attr("href","user_gamelog.php?gametype=gamecan28" + attachText);
		$("#aGameLogcan16").attr("href","user_gamelog.php?gametype=gamecan16" + attachText);

		$("#aGameLogpk10").attr("href","user_gamelog.php?gametype=gamepk10" + attachText);
		$("#aGameLoggj10").attr("href","user_gamelog.php?gametype=gamegj10" + attachText);
		$("#aGameLogpk22").attr("href","user_gamelog.php?gametype=gamepk22" + attachText);
		$("#aGameLogpklh").attr("href","user_gamelog.php?gametype=gamepklh" + attachText);
		$("#aGameLogpkgyj").attr("href","user_gamelog.php?gametype=gamepkgyj" + attachText);
		$("#aGameLoghg28").attr("href","user_gamelog.php?gametype=gamehg28" + attachText);
		$("#aGameLoghg16").attr("href","user_gamelog.php?gametype=gamehg16" + attachText);
		$("#aGameLoghg11").attr("href","user_gamelog.php?gametype=gamehg11" + attachText);
		$("#aGameLoghg36").attr("href","user_gamelog.php?gametype=gamehg36" + attachText);
		$("#aGameLogcan11").attr("href","user_gamelog.php?gametype=gamecan11" + attachText);
		$("#aGameLogcan36").attr("href","user_gamelog.php?gametype=gamecan36" + attachText);

		
		$("#aGameLogFast22").attr("href","user_gamelog.php?gametype=gamefast22" + attachText);
		$("#aGameLogFast36").attr("href","user_gamelog.php?gametype=gamefast36" + attachText);
		$("#aGameLogFastgyj").attr("href","user_gamelog.php?gametype=gamefastgyj" + attachText);


		$("#aGameLogww").attr("href","user_gamelog.php?gametype=gameww" + attachText);
		$("#aGameLogdw").attr("href","user_gamelog.php?gametype=gamedw" + attachText);
		$("#aGameLogcanww").attr("href","user_gamelog.php?gametype=gamecanww" + attachText);
		$("#aGameLogcandw").attr("href","user_gamelog.php?gametype=gamecandw" + attachText);
		$("#aGameLogpksc").attr("href","user_gamelog.php?gametype=gamepksc" + attachText);
		$("#aGameLoghgww").attr("href","user_gamelog.php?gametype=gamehgww" + attachText);
		$("#aGameLoghgdw").attr("href","user_gamelog.php?gametype=gamehgdw" + attachText);


		$("#aGameLog28gd").attr("href","user_gamelog.php?gametype=game28gd" + attachText);
		$("#aGameLogbj28gd").attr("href","user_gamelog.php?gametype=gamebj28gd" + attachText);
		$("#aGameLoghg28gd").attr("href","user_gamelog.php?gametype=gamehg28gd" + attachText);
		$("#aGameLogcan28gd").attr("href","user_gamelog.php?gametype=gamecan28gd" + attachText);
		$("#aGameLogxync").attr("href","user_gamelog.php?gametype=gamexync" + attachText);
		$("#aGameLogcqssc").attr("href","user_gamelog.php?gametype=gamecqssc" + attachText);

		$("#aGameLogbj11").attr("href","user_gamelog.php?gametype=gamebj11" + attachText);
		$("#aGameLog11").attr("href","user_gamelog.php?gametype=game11" + attachText);
		$("#aGameLog16").attr("href","user_gamelog.php?gametype=game16" + attachText);
		$("#aGameLogbjww").attr("href","user_gamelog.php?gametype=gamebjww" + attachText);
		$("#aGameLogbjdw").attr("href","user_gamelog.php?gametype=gamebjdw" + attachText);


		$("#aGameLogairship10").attr("href","user_gamelog.php?gametype=gameairship10" + attachText);
		$("#aGameLogairship22").attr("href","user_gamelog.php?gametype=gameairship22" + attachText);
		$("#aGameLogairshipgj10").attr("href","user_gamelog.php?gametype=gameairshipgj10" + attachText);
		$("#aGameLogairshipgyj").attr("href","user_gamelog.php?gametype=gameairshipgyj" + attachText);
		$("#aGameLogairshiplh").attr("href","user_gamelog.php?gametype=gameairshiplh" + attachText);
		
	}
	//ajax处理
	function SendAjax(SendData)
	{
		var PostURL = "susers.php";
		$.ajax({
			type: "POST",
			async:false,
			dataType: "json",
			url: PostURL,
			data: SendData,
			success: function(data) {DataSuccess(data);}
		});
	}
	//数据成功后
	function DataSuccess(json)
	{
		var tbody = "";
		var pageinfo = "";
		var InfoType = "";
		$.each(json,function(i,item){
			if(i == 0)
			{
				InfoType = item.cmd;
				switch(item.cmd)
				{
					case "get_baseinfo":
						$("#txtNickName").val('');
						$("#txtMobile").val('');
						$("#txtEmail").val('');
						$("#txtTjID").val('');
						$("#txtCaption").val('');
						$("#txtLoginPwd").val('');
						$("#txtBankPwd").val('');
						//$("#tblRecentLogin tr:gt(0)").remove();
						//$("#tblRecentPay tr:gt(0)").remove();
						//$("#tblRecentTrans tr:gt(0)").remove();
						//$("#tblBdCard tr:gt(0)").remove();
						break;
					case "get_recentlogin":
						$("#tblRecentLogin tr:gt(0)").remove();
						break;
					case "get_recentpay":
						$("#tblRecentPay tr:gt(0)").remove();
						break;
					case "get_recenttrans":
						$("#tblRecentTrans tr:gt(0)").remove();
						break;
					case "get_tblBdCard":
						$("#tblBdCard tr:gt(0)").remove();
						break;
					case "err_nologin":
						alert(item.msg);
						window.top.location.href='admin_login.php';
						return;
					default:
						alert(item.msg);
						return;
						break;
				}
			}
			else
			{
				if(InfoType == "get_baseinfo")
				{
					$(window.parent.document).attr("title",item.NickName+"("+item.UserID+")记录");
					//用户信息
					console.info(item.score_rebate);
					$("#kf").val(item.kf);
					$("#txtUserIDx").val(item.UserID);
					$("#lblUserIDx").html(item.UserID);
					$("#txtNickName").val(item.NickName);
					$("#lblLoginPwdInfo").html(item.LoginPwdInfo);
					$("#lblBankPwdInfo").html(item.BankPwdInfo);
					$("#lblMobileInfo").html(item.MobileInfo);
					$("#txtMobile").val(item.Mobile);
					$("#lblEmailInfo").html(item.EmailInfo);
					$("#txtTjID").val(item.TjID);
					$("#txtEmail").val(item.Email);
					$("#sMobleStatus").html(item.MobileStatus);
					$("#sEmailStatus").html(item.EmailStatus);
					$("#txtRecvCashName").val(item.RecvCashName);

					$("#txtQQ").val(item.qq);
					$("#txtMultiple").val(item.multiple_loss);
					$("#txtMemo").val(item.memo);
					$("#txtRechargeAccount").val(item.charge_account);

					$("#sUserName").html(item.UserName);
					$("#lblCurExp").html(item.CurExp);
					$("#sMaxExp").html(item.MaxExp);
					$("#lblLastLoginTime").html(item.LoginTime);
					$("#lblRegTime").html(item.RegTime);
					$("#lblLoginIPInfo").html(item.LoginIPInfo);
					$("#lblLoginIP").html(item.LoginIP);
					$("#lblRegIPInfo").html(item.RegIPInfo);
					$("#lblRegIP").html(item.RegIP);
					$("#lblDJPoints").html(item.DJPoints);
					$("#lblUDAExp").html(item.UDAExp);

					$("#lblPoints").html(item.Points);
					$("#lblBankPoints").html(item.BankPoints);
					$("#lblLockPoint").html(item.LockPoints);
					$("#sTotalPoints").html(item.TotalPoints);
					$("#sVIPStatus").html(item.VIPStatus);
					$("#inGameStr").html(item.InGame);
					$("#score_rebate2").html(item.score_rebate);
					$("#score_hb").html(item.score_hb);
					$("#lblTjTzStatic").html(item.lblTjTzStatic);
					//$("#lblTjID").html(item.TjID);
					
					$("#lblTjIncome").html(item.TjIncome);
					$("#lblTjStatic").html(item.TjStatic);
					$("#lblUDAPoints").html(item.UDAPoints);

					$("#sAccountState").html(item.AccountState);
					$("#lblIsAgent").html(item.isAgent);
					$("#lblUserType").html(item.UserType);

					//分数统计信息
					$("#lblGame_fast28").html(item.Game_fast28);
					$("#lblGame_fast16").html(item.Game_fast16);
					$("#lblGame_fast11").html(item.Game_fast11);
					$("#lblGame_fast10").html(item.Game_fast10);
					$("#lblGame_fast22").html(item.Game_fast22);
					$("#lblGame_fast36").html(item.Game_fast36);
					$("#lblGame_fastgyj").html(item.Game_fastgyj);
					$("#lblGame_28").html(item.Game_28);
					$("#lblGame_36").html(item.Game_36);
					$("#lblGame_self28").html(item.Game_self28);
					$("#lblGame_bj16").html(item.Game_bj16);
					$("#lblGame_bj36").html(item.Game_bj36);
					$("#lblGame_can28").html(item.Game_can28);
					$("#lblGame_can16").html(item.Game_can16);

					$("#lblGame_pk10").html(item.Game_pk10);
					$("#lblGame_gj10").html(item.Game_gj10);
					$("#lblGame_pk22").html(item.Game_pk22);
					$("#lblGame_pklh").html(item.Game_pklh);
					$("#lblGame_pkgyj").html(item.Game_pkgyj);
					$("#lblGame_hg28").html(item.Game_hg28);
					$("#lblGame_hg16").html(item.Game_hg16);
					$("#lblGame_hg11").html(item.Game_hg11);
					$("#lblGame_hg36").html(item.Game_hg36);
					$("#lblGame_can11").html(item.Game_can11);
					$("#lblGame_can36").html(item.Game_can36);

					$("#lblGame_ww").html(item.Game_ww);
					$("#lblGame_dw").html(item.Game_dw);
					$("#lblGame_canww").html(item.Game_canww);
					$("#lblGame_candw").html(item.Game_candw);
					$("#lblGame_pksc").html(item.Game_pksc);
					$("#lblGame_hgww").html(item.Game_hgww);
					$("#lblGame_hgdw").html(item.Game_hgdw);

					$("#lblGame_28gd").html(item.Game_28gd);
					$("#lblGame_bj28gd").html(item.Game_bj28gd);
					$("#lblGame_hg28gd").html(item.Game_hg28gd);
					$("#lblGame_can28gd").html(item.Game_can28gd);
					$("#lblGame_xync").html(item.Game_xync);
					$("#lblGame_cqssc").html(item.Game_cqssc);
					$("#lblGame_bj11").html(item.Game_bj11);
					$("#lblGame_11").html(item.Game_11);
					$("#lblGame_16").html(item.Game_16);
					$("#lblGame_bjww").html(item.Game_bjww);
					$("#lblGame_bjdw").html(item.Game_bjdw);

					$("#lblGame_airship10").html(item.Game_airship10);
					$("#lblGame_airship22").html(item.Game_airship22);
					$("#lblGame_airshipgj10").html(item.Game_airshipgj10);
					$("#lblGame_airshipgyj").html(item.Game_airshipgyj);
					$("#lblGame_airshiplh").html(item.Game_airshiplh);

					$("#lblOther_100").html(item.Other_100);
					$("#lblOther_101").html(item.Other_101);
					$("#lblOther_103").html(item.Other_103);
					$("#lblOther_102").html(item.Other_102);
					$("#lblOther_104").html(item.Other_104);
					$("#lblOther_105").html(item.Other_105);
					$("#lblOther_106").html(item.Other_106);
					$("#lblOther_107").html(item.Other_107);
					$("#lblOther_108").html(item.Other_108);
					$("#lblOther_120").html(item.Other_120);
					$("#lblTotalGameWinLose").html(item.TotalGameWinLose);

					$("#lblScore_total").html(item.Score_total);
					ChangeServiceLink(item.UserID);
				}
				else if(InfoType == "get_recentlogin")
				{
					tbody += "<tr bgcolor='#FFFFFF'>" +
						"<td align='center'>" + item.LoginTime +"</td>" +
						"<td align='center'>" + item.LoginIP + "</td>" +
						"<td align='center'>" + item.Points + "</td>" +
						"<td align='center'>" + item.BankPoints + "</td>" +
						"<td align='center'>" + item.Exp + "</td>" +
						"</tr>";
				}
				else if(InfoType == "get_recentpay")
				{
					tbody += "<tr bgcolor='#FFFFFF'>" +
						"<td align='center'>" + item.PayTime +"</td>" +
						"<td align='center'>" + item.PayType +"</td>" +
						"<td align='center'>" + item.Amount + "</td>" +
						"</tr>";
				}
				else if(InfoType == "get_recenttrans")
				{
					tbody += "<tr bgcolor='#FFFFFF'>" +
						"<td align='center'>" + item.TransTime +"</td>" +
						"<td align='center'>" + item.Rmb + "</td>" +
						"<td align='center'>" + item.Point + "</td>" +
						"</tr>";
				}
				else if(InfoType == "get_tblBdCard")
				{
					tbody += "<tr bgcolor='#FFFFFF'>" +
						"<td align='center'>" + item.Add_time +"</td>" +
						"<td align='center'>" + item.Name + "</td>" +
						"<td align='center'>" + item.Account + "</td>" +
						"</tr>";
				}
			}
		});

		if(tbody != "")
		{
			if(InfoType == "get_recentlogin")
			{
				$("#tblRecentLogin tr:gt(0)").remove();
				$("#tblRecentLogin").append(tbody);
			}
			else if(InfoType == "get_recentpay")
			{
				$("#tblRecentPay tr:gt(0)").remove();
				$("#tblRecentPay").append(tbody);
			}
			else if(InfoType == "get_recenttrans")
			{
				$("#tblRecentTrans tr:gt(0)").remove();
				$("#tblRecentTrans").append(tbody);
			}
			else if(InfoType == "get_tblBdCard")
			{
				$("#tblBdCard tr:gt(0)").remove();
				$("#tblBdCard").append(tbody);
			}
		}

	}


    //setInterval(refreshBaseInfo,15000);
    function refreshBaseInfo() {
		var data = "action=get_baseinfo";
		var SearchWord = $("#txtSearhWord").val()
		if(SearchWord == "")
		{
			alert("请输入关键词!");
			return;
		}
		if($("#sltUserType").val() == "0")
		{
			if(isNaN(SearchWord))
			{
				$("#txtSearhWord").val("");
				alert("请输入数字的ID!");
				return;
			}
		}
		if($("#sltUserType").val() == "1")
		{
			if(isNaN(SearchWord))
			{
				$("#txtSearhWord").val("");
				alert("请输入数字的手机号!");
				return;
			}
		}
		ChangeServiceLink(0);
		data = data + "&usertype=" + $("#sltUserType").val() + "&word=" + SearchWord;
		SendAjax(data);
		BindClass();
    }
</script>
</html>

<?php
/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  :     */
/*  Comment :  */
/*                   */
/*********************/

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd\">\r\n<HTML xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<HEAD>\r\n";
include_once( dirname( __FILE__ )."/inc/config.php" );
echo "<TITLE>左侧导航--后台管理系统</TITLE>\r\n
	<LINK href=\"images/css_menu.css\" type=text/css rel=stylesheet>\r\n
	<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n
	<SCRIPT language=javascript>\r\n
		function getObject(objectId) {\r\n if(document.getElementById && document.getElementById(objectId)) {\r\n // W3C DOM\r\n return document.getElementById(objectId);\r\n }\r\n else if (document.all && document.all(objectId)) {\r\n // MSIE 4 DOM\r\n return document.all(objectId);\r\n }\r\n else if (document.layers && document.layers[objectId]) {\r\n // NN 4 DOM.. note: this won't find nested layers\r\n return document.layers[objectId];\r\n }\r\n else {\r\n return false;\r\n }\r\n}\r\n\r\nfunction showHide(objname){\r\n    var obj = getObject(objname);\r\n    if(obj.style.display == \"none\"){\r\n\t\tobj.style.display = \"block\";\r\n\t}else{\r\n\t\tobj.style.display = \"none\";\r\n\t}\r\n}\r\n
	</SCRIPT>\r\n</HEAD>\r\n
	<BODY>\r\n
		<DIV class=menu>\r\n
			<DL>\r\n  
				<DT><A onclick=\"showHide('items1');\" href=\"#\" target=_self>用户管理</A></DT>\r\n  
				<DD id=items1 style=\"DISPLAY: block\">\r\n  
					<UL>\r\n  \t
						<LI><A href=\"admin_singleuser.php\" target=right>单用户查询</A></LI>\r\n
						<LI><A href=\"admin_patchuser.php\" target=right>批用户查询</A></LI>\r\n 
						<LI><A href=\"admin_pressuser.php\" target=right>正在投注用户</A></LI>\r\n 
						<LI><A href=\"user_userwinrank.php\" target=right>用户输赢排行</A></LI>\r\n
						<LI><A href=\"user_commendedawardrank.php\" target=right>用户领推荐奖排行</A></LI>\r\n
						<LI><A href=\"admin_systemmsg.php\" target=right>系统提醒消息</A></LI>\r\n 
						<LI><A href=\"admin_gamecatchresult.php\" target=right>游戏采集监测</A></LI>\r\n
						<LI><A href=\"admin_ranklist.php\" target=right>排行榜</A></LI>\r\n
						<LI><A href=\"admin_rankprizelog.php\" target=right>排行榜领取记录</A></LI>\r\n
						<LI><A href=\"admin_centerbank.php\" target=right>中央银行</A></LI>\r\n
						<LI><A href=\"admin_gamestats.php\" target=right>游戏开奖</A></LI>\r\n
						<LI><A href=\"admin_createaccount.php\" target=right>帐号生成</A></LI>\r\n\t  
		
						<LI><A href=\"admin_pay.php\" target=right>充值记录</A></LI>\r\n\t  
						<LI><A href=\"admin_withdrawals.php\" target=right>提现申请</A></LI>\r\n\t 
		 
						<LI><A href=\"admin_pack.php\" target=right>红包列表</A></LI>\r\n\t  
						<LI><A href=\"admin_rebate.php\" target=right>返现列表</A></LI>\r\n\t  
						<LI><A href=\"admin_first_rebate.php\" target=right>首充返现列表</A></LI>\r\n\t  
						<LI><A href=\"admin_abnormal.php\" target=right>投注异常勤看</A></LI>\r\n\t  
					</UL>\r\n   
				</DD>\r\n
			</DL>\r\n
		</DIV>\r\n
		<DIV class=menu>\r\n
			<DL>\r\n  
				<DT><A onclick=\"showHide('items2');\" href=\"#\" target=_self>站内短信</A></DT>\r\n  
				<DD id=items2 style=\"DISPLAY: block\">\r\n  
					<UL>\r\n  \t
						<LI><A href=\"admin_sendmsg.php\" target=right>短信发送</A></LI>\r\n\t
						<LI><A href=\"admin_msglog.php\" target=right>发送记录</A></LI>\r\n
					</UL>\r\n   
				</DD>\r\n
			</DL>\r\n
		</DIV>\r\n
	</BODY></HTML>";
?>

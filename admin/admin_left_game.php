<?php
/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  : RM     */
/*  Comment : 071223 */
/*                   */
/*********************/

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd\">\r\n<HTML xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<HEAD>\r\n";
include_once( dirname( __FILE__ )."/inc/config.php" );
echo "<TITLE>左侧导航--后台管理系统</TITLE>\r\n
	<LINK href=\"images/css_menu.css\" type=text/css rel=stylesheet>\r\n
	<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n
	<SCRIPT language=javascript>\r\nfunction getObject(objectId) {\r\n if(document.getElementById && document.getElementById(objectId)) {\r\n // W3C DOM\r\n return document.getElementById(objectId);\r\n }\r\n else if (document.all && document.all(objectId)) {\r\n // MSIE 4 DOM\r\n return document.all(objectId);\r\n }\r\n else if (document.layers && document.layers[objectId]) {\r\n // NN 4 DOM.. note: this won't find nested layers\r\n return document.layers[objectId];\r\n }\r\n else {\r\n return false;\r\n }\r\n}\r\n\r\nfunction showHide(objname){\r\n    var obj = getObject(objname);\r\n    if(obj.style.display == \"none\"){\r\n\t\tobj.style.display = \"block\";\r\n\t}else{\r\n\t\tobj.style.display = \"none\";\r\n\t}\r\n}\r\n
	</SCRIPT>\r\n</HEAD>\r\n
	<BODY>\r\n
	<DIV class=menu>\r\n
		<DL>\r\n  
			<DT><A onclick=\"showHide('items1');\" href=\"#\" target=_self>游戏管理</A></DT>\r\n  
			<DD id=items1 style=\"DISPLAY: block\">\r\n  
				<UL>\r\n  \t
					<LI><A href=\"admin_gameconfig.php\" target=right>游戏设置</A></LI>\r\n\t
					<LI><A href=\"admin_gameinit.php\" target=right>游戏初始化</A></LI>\r\n
					<LI><A href=\"admin_transconfig.php\" target=right>平台参数设置</A></LI>\r\n
					<LI><A href=\"admin_canadatimezone.php\" target=right>加拿大时差设置</A></LI>\r\n
					<LI><A href=\"admin_gamecatchconfig.php\" target=right>游戏采集设置</A></LI>\r\n
					<LI><A href=\"admin_robotpresscfg.php\" target=right>机器人下注设置</A></LI>\r\n
					<LI><A href=\"admin_robotpressmode.php\" target=right>机器人投注模式</A></LI>\r\n
					<LI><A href=\"admin_robotmanange.php\" target=right>机器人管理</A></LI>\r\n 
				</UL>\r\n   
			</DD>\r\n
		</DL>\r\n
	</DIV>\r\n
	</BODY></HTML>";
?>

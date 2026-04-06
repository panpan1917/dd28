<?php
/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  : RM     */
/*  Comment : 071223 */
/*                   */
/*********************/

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd\">\r\n<HTML xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<HEAD>\r\n<TITLE>左侧导航--后台管理系统</TITLE>\r\n<LINK href=\"images/css_menu.css\" type=text/css rel=stylesheet>\r\n<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n<SCRIPT language=javascript>\r\nfunction getObject(objectId) {\r\n if(document.getElementById && document.getElementById(objectId)) {\r\n // W3C DOM\r\n return document.getElementById(objectId);\r\n }\r\n else if (document.all && document.all(objectId)) {\r\n // MSIE 4 DOM\r\n return document.all(objectId);\r\n }\r\n else if (document.layers && document.layers[objectId]) {\r\n // NN 4 DOM.. note: this won't find nested layers\r\n return document.layers[objectId];\r\n }\r\n else {\r\n return false;\r\n }\r\n}\r\n\r\nfunction showHide(objname){\r\n    var obj = getObject(objname);\r\n    if(obj.style.display == \"none\"){\r\n\t\tobj.style.display = \"block\";\r\n\t}else{\r\n\t\tobj.style.display = \"none\";\r\n\t}\r\n}\r\n</SCRIPT>\r\n</HEAD>\r\n<BODY>\r\n<DIV class=menu>\r\n<DL>\r\n  <DT><A onclick=\"showHide('items1');\" href=\"#\" target=_self>安全管理</A></DT>\r\n  <DD id=items1 style=\"DISPLAY: block\">\r\n  <UL>\r\n    <LI><A href=\"admin_admin.php\" target=right>管理员管理</A></LI>\r\n\t<LI><A href=\"admin_log.php\" target=right>操作日志管理</A></LI>\r\n   </UL>\r\n   </DD>\r\n</DL>\r\n</DIV></BODY></HTML>";
?>

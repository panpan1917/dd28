<?php
/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  : RM     */
/*  Comment : 071223 */
/*                   */
/*********************/

include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
login_check( "admingl" );

function del( )
{
		global $db;
		global $web_dbtop;
		$db->query( "delete from ".$web_dbtop."log where STR_TO_DATE(logtime,'%Y-%m-%d')<='".date( "Y-m-" ).( date( "d" ) - 7 )."'" );
}

function main( )
{
		global $db;
		global $web_dbtop;
		$intpage = 20;
		if ( isset( $_GET['page'] ) )
		{
				$rsnum = ( $_GET['page'] - 1 ) * $intpage;
		}
		else
		{
				$rsnum = 0;
		}
		$sql = "Select * from ".$web_dbtop."log";
		if ( $_GET['stopdate'] != "" && $_GET['enddate'] != "" )
		{
				$sql .= " where STR_TO_DATE(logtime,'%Y-%m-%d') between '".$_GET['stopdate']."' and '{$_GET['enddate']}'";
		}
		if ( $_GET['name'] != "" )
		{
				$sql .= " where logname='".$_GET['name']."'";
		}
		$sql .= " Order by id desc";
		$query = $db->query( $sql );
		if ( $db->fetch_array( $query ) )
		{
				$intnum = $db->num_rows( $query );
		}
		echo "<TABLE width=\"99%\" border=0 align=center cellpadding=\"5\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">\r\n  <TBODY>\r\n    <TR>\r\n      <TD vAlign=center bgColor=#f5fafe><table width=\"98%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"1\">\r\n\t  \t<form action=\"admin_log.php\" method=\"get\">\r\n          <tr>\r\n            <td width=\"12%\"><STRONG>按日期查询</STRONG>：</td>\r\n            <td width=\"36%\">从\r\n\t\t\t<input id=stopdate size=10 name=stopdate onfocus=setday(this) readOnly>\r\n            <IMG onclick=stopdate.focus() src=\"images/calendar.gif\" align=absmiddle>\r\n\t\t\t到\r\n\t\t\t<input id=enddate size=10 name=enddate onfocus=setday(this) readOnly>\r\n\t\t\t<IMG onclick=enddate.focus() src=\"images/calendar.gif\" align=absmiddle></td>\r\n            <td width=\"10%\" align=\"center\"><input class=inputbut type=submit value=搜索 name=Submit></td>\r\n            <td><strong>按管理员查询：</strong></td>\r\n            <td><input id=name size=20 name=name></td>\r\n            <td><input class=inputbut type=submit value=搜索 name=Submit></td>\r\n          </tr>\r\n\t\t  </form>\r\n      </table></TD>\r\n    </TR>\r\n  </TBODY>\r\n</TABLE>\r\n<TABLE width=\"99%\" border=0 align=center cellpadding=\"5\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">\r\n  <form action=\"?action=del\" method=\"post\" name=\"form\">\r\n  <TBODY>\r\n    <TR bgColor=\"#f5fafe\">\r\n      <TD width=\"5%\" align=\"center\">ID</TD>\r\n      <TD align=\"center\">操作内容</TD>\r\n      <TD width=\"20%\" align=\"center\">操作时间</TD>\r\n      <TD width=\"20%\" align=\"center\">操作管理员</TD>\r\n      <TD width=\"20%\" align=\"center\">操作管理员IP</TD>\r\n    </TR>\r\n\t";
		$query = $db->query( $sql.( " limit ".$rsnum.",{$intpage}" ) );
		while ( $rs = $db->fetch_array( $query ) )
		{
				echo "    <TR bgcolor=\"#FFFFFF\">\r\n      <TD align=\"center\">";
				echo $rs['id'];
				echo "</TD>\r\n      <TD align=\"center\">";
				echo $rs['logcontent'];
				echo "</TD>\r\n      <TD align=\"center\">";
				echo $rs['logtime'];
				echo "</TD>\r\n      <TD align=\"center\">";
				echo $rs['logname'];
				echo "</TD>\r\n      <TD align=\"center\">";
				echo $rs['logip'];
				echo "</TD>\r\n    </TR>\r\n\t";
		}
		echo "    <TR bgcolor=\"#f8fbfb\">\r\n      <TD colspan=\"5\" align=\"right\"><input type=\"submit\" name=\"del\" class=\"inputbut\" value=\"删除一周前日志\"></TD>\r\n      </TR>\r\n\t<TR align=\"center\" bgcolor=\"#f8fbfb\">\r\n      <TD colspan=\"5\">";
		include_once( dirname( __FILE__ )."/inc/page_class.php" );
		$page = new page( array(
				"total" => $intnum,
				"perpage" => $intpage
		) );
		echo $page->show( 4, "page", "curr" );
		echo "</TD>\r\n    </TR>\r\n  </TBODY>\r\n  </form>\r\n</TABLE>\r\n<script language=\"JavaScript\" src=\"inc/meizzdate.js\"></script>\r\n";
}


echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd\">\r\n<HTML xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<HEAD>\r\n<TITLE>日志管理--后台管理系统</TITLE>\r\n<META http-equiv=Content-Type content=\"text/html; charset=utf8\">\r\n<LINK href=\"images/css_body.css\" type=text/css rel=stylesheet>\r\n<META content=\"MSHTML 6.00.3790.4275\" name=GENERATOR>\r\n</HEAD>\r\n<BODY>\r\n<DIV class=bodytitle>\r\n<DIV class=bodytitleleft></DIV>\r\n<DIV class=bodytitletxt>日志管理</DIV>\r\n</DIV>\r\n";
switch ( $_GET['action'] )
{
case "del" :
		del( );
		addlog( "日志删除成功" );
		showerr( "日志删除成功", "admin_log.php" );
		break;
default :
		main( );
}
echo "</BODY></HTML>\r\n";
?>

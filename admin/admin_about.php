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
//login_check( "users" );


function sadd( )
{
	login_check( "system" );
		global $db;
		global $web_dbtop;
		$db->query( "INSERT INTO ".$web_dbtop."about (title,content,sort) VALUES ('".$_POST['title']."','".$_POST['content']."',".intval( $_POST['sort'] ).")" );
}

function sedit( )
{
	login_check( "system" );
		global $db;
		global $web_dbtop;
		$db->query( "update ".$web_dbtop."about set title='".$_POST['title']."',content='".$_POST['content']."',sort=".intval( $_POST['sort'] ).( " where id=".$_POST['id'] ) );
}

function del( )
{
	login_check( "system" );
		global $db;
		global $web_dbtop;
		$db->query( "delete from ".$web_dbtop."about where id={$_GET['id']}" );
}

function main( )
{
		global $db;
		global $web_dbtop;
		echo "<TABLE width=\"99%\" border=0 align=center cellpadding=\"5\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">\r\n  <TBODY>\r\n    <TR bgColor=\"#f5fafe\">\r\n      <TD align=\"center\">网站信息标题</TD>\r\n      <TD width=\"15%\" align=\"center\">操作</TD>\r\n    </TR>\r\n\t";
		$intpage = 20;
		if ( isset( $_GET['page'] ) )
		{
				$rsnum = ( $_GET['page'] - 1 ) * $intpage;
		}
		else
		{
				$rsnum = 0;
		}
		$query = $db->query( "Select * from ".$web_dbtop."about Order by id desc" );
		if ( $db->fetch_array( $query ) )
		{
				$intnum = $db->num_rows( $query );
		}
		$query = $db->query( "Select * from ".$web_dbtop."about Order by sort asc,id desc limit {$rsnum},{$intpage}" );
		while ( $rs = $db->fetch_array( $query ) )
		{
				echo "    <TR bgcolor=\"#FFFFFF\">\r\n      <TD>";
				echo $rs['title'];
				echo "</TD>\r\n      <TD align=\"center\"><A href=\"admin_about.php?action=edit&id=";
				echo $rs['id'];
				echo "\">修改</a> | <A href=\"admin_about.php?action=del&id=";
				echo $rs['id'];
				echo "\" onClick=\"return confirm('确定要删除吗?');\">删除</a></TD>\r\n    </TR>\r\n\t";
		}
		echo "    <TR align=\"center\" bgcolor=\"#f8fbfb\">\r\n      <TD colspan=\"2\">";
		include_once( dirname( __FILE__ )."/inc/page_class.php" );
		$page = new page( array(
				"total" => $intnum,
				"perpage" => $intpage
		) );
		echo $page->show( 4, "page", "curr" );
		echo "</TD>\r\n    </TR>\r\n  </TBODY>\r\n</TABLE>\r\n";
}

function add( )
{
		echo "<TABLE width=\"99%\" border=0 align=center cellpadding=\"4\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">\r\n<form action=\"?action=sadd\" method=\"post\" onSubmit=\"return Validator.Validate(this,3)\">\r\n  <TBODY>\r\n    <TR>\r\n      <TD vAlign=center width=\"20%\" bgColor=#f5fafe>网站信息标题：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=\"title\" size=50 value=\"\" name=\"title\" dataType=\"Require\" msg=\"请填写信息标题\"></TD>\r\n    </TR>\r\n\t <TR>\r\n      <TD vAlign=center width=\"20%\" bgColor=#f5fafe>排序：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=\"sort\" size=50 value=\"\" name=\"sort\" dataType=\"Integer\" msg=\"请填写数字\"></TD>\r\n    </TR>\r\n    <TR>\r\n      <TD bgColor=#f5fafe>信息内容：</TD>\r\n      <TD bgColor=#ffffff><textarea name=\"content\" style=\"display:none\"></textarea>\r\n  <iframe ID=\"Editor\" name=\"Editor\" src=\"editor/index.html?ID=content\" frameBorder=\"0\" marginHeight=\"0\" marginWidth=\"0\" scrolling=\"No\" style=\"height:320px;width:100%\"></iframe></TD>\r\n    </TR>\r\n    <TR align=\"center\" bgcolor=\"#f8fbfb\">\r\n      <TD colspan=\"2\"><INPUT class=inputbut type=submit value=添加 name=Submit>\r\n      &nbsp;</TD>\r\n    </TR>\r\n  </TBODY>\r\n  </form>\r\n</TABLE>\r\n";
}

function edit( )
{
		global $db;
		global $web_dbtop;
		$query = $db->query( "Select * from ".$web_dbtop."about where id={$_GET['id']}" );
		if ( $rs = $db->fetch_array( $query ) )
		{
				echo "<TABLE width=\"99%\" border=0 align=center cellpadding=\"4\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">\r\n<form action=\"?action=sedit\" method=\"post\" onSubmit=\"return Validator.Validate(this,3)\">\r\n<input name=\"id\" type=\"hidden\" value=\"";
				echo $rs['id'];
				echo "\">\r\n  <TBODY>\r\n    <TR>\r\n      <TD vAlign=center width=\"20%\" bgColor=#f5fafe>网站信息标题：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=\"title\" size=50 value=\"";
				echo $rs['title'];
				echo "\" name=\"title\" dataType=\"Require\" msg=\"请填写信息标题\"></TD>\r\n    </TR>\r\n\t<TR>\r\n      <TD vAlign=center width=\"20%\" bgColor=#f5fafe>排序：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=\"sort\" size=50 value=\"";
				echo $rs['sort'];
				echo "\" name=\"sort\" dataType=\"Integer\" msg=\"请填写数字\"></TD>\r\n    </TR>\r\n    <TR>\r\n      <TD bgColor=#f5fafe>信息内容：</TD>\r\n      <TD bgColor=#ffffff><textarea name=\"content\" style=\"display:none\">";
				echo $rs['content'];
				echo "</textarea>\r\n  <iframe ID=\"Editor\" name=\"Editor\" src=\"editor/index.html?ID=content\" frameBorder=\"0\" marginHeight=\"0\" marginWidth=\"0\" scrolling=\"No\" style=\"height:320px;width:100%\"></iframe></TD>\r\n    </TR>\r\n    <TR align=\"center\" bgcolor=\"#f8fbfb\">\r\n      <TD colspan=\"2\"><INPUT class=inputbut type=submit value=修改 name=Submit>\r\n      &nbsp;</TD>\r\n    </TR>\r\n  </TBODY>\r\n  </form>\r\n</TABLE>\r\n";
		}
}


echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd\">\r\n<HTML xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<HEAD>\r\n<TITLE>网站信息管理--后台管理系统</TITLE>\r\n<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n<LINK href=\"images/css_body.css\" type=text/css rel=stylesheet>\r\n<META content=\"MSHTML 6.00.3790.4275\" name=GENERATOR>\r\n</HEAD>\r\n<BODY>\r\n<DIV class=bodytitle>\r\n<DIV class=bodytitleleft></DIV>\r\n<DIV class=bodytitletxt>网站信息管理</DIV>\r\n<DIV class=bodytitletxt2><a href=\"admin_about.php?action=add\">添加信息</a></DIV>\r\n</DIV>\r\n";
switch ( $_GET['action'] )
{
case "add" :
		add( );
		break;
case "sadd" :
		sadd( );
		addlog( "网站信息添加成功" );
		showerr( "网站信息添加成功", "admin_about.php" );
		break;
case "edit" :
		edit( );
		break;
case "sedit" :
		sedit( );
		addlog( "网站信息修改成功" );
		showerr( "网站信息修改成功", "admin_about.php" );
		break;
case "del" :
		del( );
		addlog( "网站信息删除成功" );
		showerr( "网站信息删除成功", "admin_about.php" );
		break;
default :
		main( );
}
echo "</BODY></HTML>\r\n";
?>

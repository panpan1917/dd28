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
		$db->query( "INSERT INTO ".$web_dbtop."slide (slidename,slidepic,slideurl,sort) VALUES ('".$_POST['slidename']."','".$_POST['slidepic']."','".$_POST['slideurl'].( "',".$_POST['sort'].")" ) );
}

function sedit( )
{
	login_check( "system" );
		global $db;
		global $web_dbtop;
		$db->query( "update ".$web_dbtop."slide set slidename='".$_POST['slidename']."',slidepic='".$_POST['slidepic']."',slideurl='".$_POST['slideurl'].( "',sort=".$_POST['sort']." where id={$_POST['id']}" ) );
}

function slidesort( )
{
	login_check( "system" );
		global $db;
		global $web_dbtop;
		$i = 0;
		for ( ;	$i < count( $_POST['id'] );	++$i	)
		{
				$id = $_POST['id'][$i];
				$db->query( "update ".$web_dbtop."slide set sort=".$_POST["sort_".$id].( " where id=".$id ) );
		}
}

function del( )
{
	login_check( "system" );
		global $db;
		global $web_dbtop;
		$db->query( "delete from ".$web_dbtop."slide where id={$_GET['id']}" );
}

function main( )
{
		global $db;
		global $web_dbtop;
		global $web_dir;
		global $web_slidedir;
		echo "<TABLE width=\"99%\" border=0 align=center cellpadding=\"5\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">\r\n  <TBODY>\r\n    <TR bgColor=\"#f5fafe\">\r\n\t  <TD width=\"4%\" align=\"center\" >&nbsp;</TD>\r\n      <TD align=\"center\">幻灯名称</TD>\r\n\t  <TD width=\"20%\" align=\"center\" bgcolor=\"#f5fafe\">幻灯图片</TD>\r\n      <TD align=\"center\">幻灯地址</TD>\r\n      <TD width=\"8%\" align=\"center\">排序</TD>\r\n      <TD width=\"15%\" align=\"center\">操作</TD>\r\n    </TR>\r\n\t<form action=\"?action=sort\" method=\"post\" name=\"form\">\r\n\t";
		$query = $db->query( "Select * from ".$web_dbtop."slide Order by sort asc,id desc" );
		while ( $rs = $db->fetch_array( $query ) )
		{
				echo "    <TR bgcolor=\"#FFFFFF\">\r\n\t  <TD align=\"center\"><input name=\"id[]\" type=\"checkbox\" id=\"id[]\" value=\"";
				echo $rs['id'];
				echo "\" checked></TD>\r\n      <TD align=\"center\">";
				echo $rs['slidename'];
				echo "</TD>\r\n\t  <TD align=\"center\"><img src=\"";
				echo $web_dir.$web_slidedir.$rs['slidepic'];
				echo "\" width=\"100\" height=\"50\"></TD>\r\n      <TD align=\"center\">";
				echo $rs['slideurl'];
				echo "</TD>\r\n      <TD align=\"center\"><INPUT id=sort_";
				echo $rs['id'];
				echo " size=5 name=sort_";
				echo $rs['id'];
				echo " value=\"";
				echo $rs['sort'];
				echo "\"></TD>\r\n      <TD align=\"center\"><A href=\"admin_slide.php?action=edit&id=";
				echo $rs['id'];
				echo "\">修改</a> | <A href=\"admin_slide.php?action=del&id=";
				echo $rs['id'];
				echo "\" onClick=\"return confirm('确定要删除吗?');\">删除</a></TD>\r\n    </TR>\r\n\t";
		}
		echo "\t<TR bgcolor=\"#FFFFFF\">\r\n      <TD colspan=\"6\" align=\"center\"><INPUT class=inputbut type=submit value=批量修改选中幻灯排序 name=Submit></TD>\r\n    </TR>\r\n\t</form>\r\n  </TBODY>\r\n</TABLE>\r\n";
}

function add( )
{
		global $web_slidedir;
		echo "<TABLE width=\"99%\" border=0 align=center cellpadding=\"4\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">\r\n <form action=\"?action=sadd\" method=\"post\" onSubmit=\"return Validator.Validate(this,3)\">\r\n  <TBODY>\r\n    <TR>\r\n      <TD vAlign=center width=\"20%\" bgColor=#f5fafe>幻灯名称：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=slidename size=50 name=slidename dataType=\"Require\" msg=\"请填写幻灯名称\"></TD>\r\n    </TR>\r\n    <TR>\r\n      <TD bgColor=#f5fafe>幻灯图片：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=slidepic size=50 name=slidepic dataType=\"Require\" msg=\"请填写幻灯图片\"></TD>\r\n    </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>图片上传：</TD>\r\n      <TD bgColor=#ffffff><iframe src=\"inc/upadpic.php?urldir=";
		echo $web_slidedir;
		echo "&picname=slidepic\" width=\"600\" height=\"25\" frameborder=\"0\" scrolling=\"no\"></iframe></TD>\r\n    </TR>\r\n    <TR>\r\n      <TD bgColor=#f5fafe>幻灯地址：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=slideurl size=50 name=slideurl dataType=\"Require\" msg=\"请填写幻灯地址\"></TD>\r\n    </TR>\r\n    <TR>\r\n      <TD bgColor=#f5fafe>排序：</TD>\r\n      <TD bgColor=#ffffff><INPUT name=sort id=sort value=\"0\" size=50 dataType=\"Integer\" msg=\"排序只能为数字\"></TD>\r\n    </TR>\r\n    <TR align=\"center\" bgcolor=\"#f8fbfb\">\r\n      <TD colspan=\"2\"><INPUT class=inputbut type=submit value=添加 name=Submit>\r\n      &nbsp;</TD>\r\n    </TR>\r\n  </TBODY>\r\n  </form>\r\n</TABLE>\r\n";
}

function edit( )
{
		global $db;
		global $web_dbtop;
		global $web_slidedir;
		$query = $db->query( "select * from ".$web_dbtop."slide where id={$_GET['id']}" );
		if ( $rs = $db->fetch_array( $query ) )
		{
				echo "<TABLE width=\"99%\" border=0 align=center cellpadding=\"4\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">\r\n  <form action=\"?action=sedit\" method=\"post\" onSubmit=\"return Validator.Validate(this,3)\">\r\n  <input name=\"id\" type=\"hidden\" value=\"";
				echo $rs['id'];
				echo "\">\r\n    <TBODY>\r\n      <TR>\r\n        <TD vAlign=center width=\"20%\" bgColor=#f5fafe>幻灯名称：</TD>\r\n        <TD bgColor=#ffffff><INPUT id=slidename size=50 name=slidename value=\"";
				echo $rs['slidename'];
				echo "\" dataType=\"Require\" msg=\"请填写幻灯名称\"></TD>\r\n      </TR>\r\n      <TR>\r\n        <TD bgColor=#f5fafe>幻灯图片：</TD>\r\n        <TD bgColor=#ffffff><INPUT id=slidepic size=50 name=slidepic value=\"";
				echo $rs['slidepic'];
				echo "\" dataType=\"Require\" msg=\"请填写幻灯图片\"></TD>\r\n      </TR>\r\n\t  <TR>\r\n     \t<TD bgColor=#f5fafe>图片上传：</TD>\r\n      \t<TD bgColor=#ffffff><iframe src=\"inc/upadpic.php?urldir=";
				echo $web_slidedir;
				echo "&picname=slidepic\" width=\"600\" height=\"25\" frameborder=\"0\" scrolling=\"no\"></iframe></TD>\r\n      </TR>\r\n      <TR>\r\n        <TD bgColor=#f5fafe>幻灯地址：</TD>\r\n        <TD bgColor=#ffffff><INPUT id=slideurl size=50 name=slideurl value=\"";
				echo $rs['slideurl'];
				echo "\" dataType=\"Require\" msg=\"请填写幻灯地址\"></TD>\r\n      </TR>\r\n      <TR>\r\n        <TD bgColor=#f5fafe>排序：</TD>\r\n        <TD bgColor=#ffffff><INPUT name=sort id=sort value=\"";
				echo $rs['sort'];
				echo "\" size=50 dataType=\"Integer\" msg=\"排序只能为数字\"></TD>\r\n      </TR>\r\n      <TR align=\"center\" bgcolor=\"#f8fbfb\">\r\n        <TD colspan=\"2\"><INPUT class=inputbut type=submit value=修改 name=Submit></TD>\r\n      </TR>\r\n    </TBODY>\r\n  </form>\r\n</TABLE>\r\n";
		}
}


echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd\">\r\n<HTML xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<HEAD>\r\n<TITLE>幻灯管理---后台管理系统<</TITLE>\r\n<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n<LINK href=\"images/css_body.css\" type=text/css rel=stylesheet>\r\n<META content=\"MSHTML 6.00.3790.4275\" name=GENERATOR>\r\n</HEAD>\r\n<BODY>\r\n<DIV class=bodytitle>\r\n<DIV class=bodytitleleft></DIV>\r\n<DIV class=bodytitletxt>幻灯管理</DIV>\r\n<DIV class=bodytitletxt2><a href=\"admin_slide.php?action=add\">添加幻灯</a></DIV>\r\n</DIV>\r\n";
switch ( $_GET['action'] )
{
case "add" :
		add( );
		break;
case "sadd" :
		sadd( );
		addlog( "网站幻灯添加成功" );
		showerr( "网站幻灯添加成功", "admin_slide.php" );
		break;
case "edit" :
		edit( );
		break;
case "sedit" :
		sedit( );
		addlog( "网站幻灯修改成功" );
		showerr( "网站幻灯修改成功", "admin_slide.php" );
		break;
case "sort" :
		slidesort( );
		addlog( "网站幻灯修改排序成功" );
		showerr( "网站幻灯修改排序成功", "admin_slide.php" );
		break;
case "del" :
		del( );
		addlog( "网站幻灯删除成功" );
		showerr( "网站幻灯删除成功", "admin_slide.php" );
		break;
default :
		main( );
}
echo "</BODY></HTML>\r\n";
?>

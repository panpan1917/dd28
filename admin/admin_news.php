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
	login_check( "notice" );
		global $db;
		global $web_dbtop;
		$_POST['content'] = nl2br($_POST['content']);
		$sql ="INSERT INTO ".$web_dbtop."news (title,content,time,top,pop) VALUES ('".$_POST['title']."','".$_POST['content']."','".date( "Y-m-d" )."',".intval( $_POST['top'] ).",".intval( $_POST['pop'] ).")";
		echo $sql;
		$db->query($sql);
}

function sedit( )
{
	login_check( "notice" );
		global $db;
		global $web_dbtop;
		$_POST['content'] = nl2br($_POST['content']);
		$sql = "update ".$web_dbtop."news set title='".$_POST['title']."',content='".$_POST['content']."',time='".date( "Y-m-d" )."',top=".intval( $_POST['top'] ) . ",pop=".intval( $_POST['pop'] ) . " where id=".$_POST['id'];
		//WriteLog($sql);
		$db->query( $sql );
		
		if($_POST['pop']){
			$sql = "update news set pop=0 where id!={$_POST['id']}";
			$db->query( $sql );
		}
}

function del( )
{
	login_check( "notice" );
		global $db;
		global $web_dbtop;
		$db->query( "delete from ".$web_dbtop."news where id={$_GET['id']}" );
}

function main( )
{
		global $db;
		global $web_dbtop;
		echo "<TABLE width=\"99%\" border=0 align=center cellpadding=\"5\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">\r\n  <TBODY>\r\n    <TR bgColor=\"#f5fafe\">\r\n      <TD align=\"center\">公告标题</TD>\r\n      <TD width=\"20%\" align=\"center\">首页弹出</TD>\r\n      <TD width=\"20%\" align=\"center\">发布时间</TD>\r\n      <TD width=\"15%\" align=\"center\">操作</TD>\r\n    </TR>\r\n\t";
		$intpage = 20;
		if ( isset( $_GET['page'] ) )
		{
				$rsnum = ( $_GET['page'] - 1 ) * $intpage;
		}
		else
		{
				$rsnum = 0;
		}
		$query = $db->query( "Select * from ".$web_dbtop."news Order by id desc" );
		if ( $db->fetch_array( $query ) )
		{
				$intnum = $db->num_rows( $query );
		}
		$query = $db->query( "Select * from ".$web_dbtop."news Order by top desc,id desc limit {$rsnum},{$intpage}" );
		while ( $rs = $db->fetch_array( $query ) )
		{
				echo "    <TR bgcolor=\"#FFFFFF\">\r\n      <TD>";
				echo $rs['title'];
				echo "</TD>\r\n      <TD align=\"center\">";
				echo $rs['pop'] = $rs['pop']>0?"是":"否";
				echo "</TD>\r\n      <TD align=\"center\">";
				echo $rs['time'];
				echo "</TD>\r\n<TD align=\"center\"><A href=\"admin_news.php?action=edit&id=";
				echo $rs['id'];
				echo "\">修改</a> | <A href=\"admin_news.php?action=del&id=";
				echo $rs['id'];
				echo "\" onClick=\"return confirm('确定要删除吗?');\">删除</a></TD>\r\n    </TR>\r\n\t";
		}
		echo "    <TR align=\"center\" bgcolor=\"#f8fbfb\">\r\n      <TD colspan=\"3\">";
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
		echo "<TABLE width=\"99%\" border=0 align=center cellpadding=\"4\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">\r\n<form action=\"?action=sadd\" method=\"post\" onSubmit=\"return Validator.Validate(this,3)\">\r\n  <TBODY>\r\n    <TR>\r\n      <TD vAlign=center width=\"20%\" bgColor=#f5fafe>公告标题：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=\"title\" size=50 value=\"\" name=\"title\" dataType=\"Require\" msg=\"请填写公告标题\">\r\n        <input name=\"top\" type=\"checkbox\" id=\"top\" value=\"1\">置顶        <input name=\"pop\" type=\"checkbox\" id=\"pop\" value=\"1\">首页弹出</TD>\r\n    </TR>\r\n    <TR>\r\n      <TD bgColor=#f5fafe>公告内容：</TD>\r\n      <TD bgColor=#ffffff><textarea name=\"content\" style=\"display:none\"></textarea>\r\n  <iframe ID=\"Editor\" name=\"Editor\" src=\"editor/index.html?ID=content\" frameBorder=\"0\" marginHeight=\"0\" marginWidth=\"0\" scrolling=\"No\" style=\"height:320px;width:100%\"></iframe></TD>\r\n    </TR>\r\n    <TR align=\"center\" bgcolor=\"#f8fbfb\">\r\n      <TD colspan=\"2\"><INPUT class=inputbut type=submit value=添加 name=Submit>\r\n      &nbsp;</TD>\r\n    </TR>\r\n  </TBODY>\r\n  </form>\r\n</TABLE>\r\n";
}

function edit( )
{
		global $db;
		global $web_dbtop;
		$query = $db->query( "Select * from ".$web_dbtop."news where id={$_GET['id']}" );
		if ( $rs = $db->fetch_array( $query ) )
		{
				echo "<form action=\"?action=sedit\" method=\"post\" onSubmit=\"return Validator.Validate(this,3)\">\r\n
					<input name=\"id\" type=\"hidden\" value=". $rs['id']." />\r\n
					<TABLE width=\"99%\" border=0 align=center cellpadding=\"4\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">\r\n
				<TBODY>\r\n    <TR>\r\n      <TD vAlign=center width=\"20%\" bgColor=#f5fafe>公告标题：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=\"title\" size=50 value=".$rs['title']." name=\"title\" dataType=\"Require\" msg=\"请填写公告标题\">\r\n        
				<input name=\"top\" type=\"checkbox\" id=\"top\" value=\"1\" ";
				if ( $rs['top'] == 1 )
				{
						echo "checked";
				}
				echo ">\r\n置顶				
				<input name=\"pop\" type=\"checkbox\" id=\"pop\" value=\"1\" ";
				if ( $rs['pop'] == 1 )
				{
						echo "checked";
				}
				echo ">\r\n首页弹出</TD>\r\n    </TR>\r\n    <TR>\r\n      <TD bgColor=#f5fafe>公告内容：</TD>\r\n      <TD bgColor=#ffffff><textarea name=\"content\" style=\"display:none\">";
				echo $rs['content'];
				echo "</textarea>\r\n  <iframe ID=\"Editor\" name=\"Editor\" src=\"editor/index.html?ID=content\" frameBorder=\"0\" marginHeight=\"0\" marginWidth=\"0\" scrolling=\"No\" style=\"height:320px;width:100%\"></iframe></TD>\r\n    </TR>\r\n    <TR align=\"center\" bgcolor=\"#f8fbfb\">\r\n      <TD colspan=\"2\"><INPUT class=inputbut type=submit value=修改 name=Submit>\r\n      &nbsp;</TD>\r\n    </TR>\r\n  </TBODY>\r\n</TABLE>\r\n  </form>";
		}
}


echo "<!DOCTYPE HTML>\r\n<HTML>\r\n<HEAD>\r\n<TITLE>公告管理--后台管理系统</TITLE>\r\n<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n<LINK href=\"images/css_body.css\" type=text/css rel=stylesheet>\r\n<META content=\"MSHTML 6.00.3790.4275\" name=GENERATOR>\r\n</HEAD>\r\n<BODY>\r\n<DIV class=bodytitle>\r\n<DIV class=bodytitleleft></DIV>\r\n<DIV class=bodytitletxt>公告管理</DIV>\r\n<DIV class=bodytitletxt2><a href=\"admin_news.php?action=add\">添加公告</a></DIV>\r\n</DIV>\r\n";


switch ( $_GET['action'] )
{
case "add" :
		add( );
		break;
case "sadd" :
		sadd( );
		echo "<script type='text/javascript'>alert('网站公告添加成功');history.back();</script>";
		//addlog( "网站公告添加成功" );
		//showerr( "网站公告添加成功", "admin_news.php" );
		break;
case "edit" :
		edit( );
		break;
case "sedit" :
		sedit( );
		echo "<script type='text/javascript'>alert('网站公告修改成功');history.back();</script>";
		//addlog( "网站公告修改成功" );
		//showerr( "网站公告修改成功", "admin_news.php" );
		break;
case "del" :
		del( );
		echo "<script type='text/javascript'>alert('网站公告删除成功');history.back();</script>";
		//addlog( "网站公告删除成功" );
		//showerr( "网站公告删除成功", "admin_news.php" );
		break;
default :
		main( );
}

echo "</BODY></HTML>\r\n";

?>


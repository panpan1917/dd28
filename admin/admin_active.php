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
	login_check( "hdgl" );
		global $db;
		global $web_dbtop;
		$sql ="INSERT INTO ".$web_dbtop."game_active (tg_title,tg_img,tg_content,tg_start_time,tg_last_time,tg_time,tg_top) VALUES ('".$_POST['title']."','".$_POST['img']."','".$_POST['content']."','".$_POST['start_time']."','".$_POST['last_time']."',NOW(),".intval( $_POST['top'] ).")";
		$db->query($sql);
}

function sedit( )
{
	login_check( "hdgl" );
		global $db;
		global $web_dbtop;
		$sql = "update ".$web_dbtop."game_active set tg_title='".$_POST['title']."',tg_img='".$_POST['img']."',tg_start_time='".$_POST['start_time']."',tg_last_time='".$_POST['last_time']."',tg_active='".$_POST['active']."',tg_content='".$_POST['content']."',tg_time=NOW(),tg_top=".intval( $_POST['top'] ).( " where tg_id=".$_POST['id'] );
		//WriteLog($sql);
		$db->query( $sql );
}

function del( )
{
	login_check( "hdgl" );
		global $db;
		global $web_dbtop;
		$db->query( "delete from ".$web_dbtop."game_active where tg_id={$_GET['id']}" );
}

function main( )
{
		global $db;
		global $web_dbtop;
		echo "<TABLE width=\"99%\" border=0 align=center cellpadding=\"5\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">\r\n  <TBODY>\r\n    <TR bgColor=\"#f5fafe\">\r\n      
			<TD align=\"center\">活动标题</TD>\r\n     
			<TD align=\"center\">活动图片</TD>\r\n  
			<TD align=\"center\">活动时间</TD>\r\n  
			<TD align=\"center\">活动状态</TD>\r\n 
			<TD align=\"center\">发布时间</TD>\r\n      
			<TD align=\"center\">操作</TD>\r\n    
			</TR>\r\n\t";
		$intpage = 20;
		if ( isset( $_GET['page'] ) )
		{
				$rsnum = ( $_GET['page'] - 1 ) * $intpage;
		}
		else
		{
				$rsnum = 0;
		}
		$query = $db->query( "Select * from ".$web_dbtop."game_active Order by tg_id desc" );
		if ( $db->fetch_array( $query ) )
		{
				$intnum = $db->num_rows( $query );
		}
		$query = $db->query( "Select * from ".$web_dbtop."game_active Order by tg_top desc,tg_id desc limit {$rsnum},{$intpage}" );
		while ( $rs = $db->fetch_array( $query ) )
		{
				$_html['active'] = $rs['tg_active'];
				if ($_html['active'] == 1){
					$_html['active_string'] = '活动正在进行中';
				}else{
					$_html['active_string'] = '活动已经结束';
				}
				echo "    <TR align=\"center\" bgcolor=\"#FFFFFF\">\r\n      <TD>".$rs['tg_title'];
				echo "</TD>\r\n <td align='center'>".$rs['tg_img']."</td>  <td align='center'>".$rs['tg_start_time'].'-'.$rs['tg_last_time']."</td> <td>".$_html['active_string']."</td>  <TD align=\"center\">";
				echo $rs['tg_time'];
				echo "</TD>\r\n      <TD align=\"center\"><A href=\"admin_active.php?action=edit&id=";
				echo $rs['tg_id'];
				echo "\">修改</a> | <A href=\"admin_active.php?action=del&id=";
				echo $rs['tg_id'];
				echo "\" onClick=\"return confirm('确定要删除吗?');\">删除</a></TD>\r\n    </TR>\r\n\t";
		}
		echo "    <TR align=\"center\" bgcolor=\"#f8fbfb\">\r\n      <TD colspan=\"6\">";
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
		echo "<TABLE width=\"99%\" border=0 align=center cellpadding=\"4\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">\r\n
		<form action=\"?action=sadd\" method=\"post\" onSubmit=\"return Validator.Validate(this,3)\">\r\n  
			<TBODY>\r\n    
				<TR>\r\n      
					<TD vAlign=center width=\"20%\" bgColor=#f5fafe>活动标题：</TD>\r\n      
					<TD bgColor=#ffffff><INPUT id=\"title\" size=50 value=\"\" name=\"title\" dataType=\"Require\" msg=\"请填写活动标题\">\r\n        <input name=\"top\" type=\"checkbox\" id=\"top\" value=\"1\">\r\n        置顶</TD>
		\r\n    </TR>\r\n    
						<TR>\r\n      
					<TD vAlign=center width=\"20%\" bgColor=#f5fafe>活动图片：</TD>\r\n      
					<TD bgColor=#ffffff><INPUT id=\"title\" size=50 value=\"\" name=\"img\" dataType=\"Require\" msg=\"请填写活动图片\">\r\n  (例如: images/pic.png)</TD>
		\r\n    </TR>\r\n  
						<TR>\r\n      
					<TD vAlign=center width=\"20%\" bgColor=#f5fafe>活动开始时间：</TD>\r\n      
					<TD bgColor=#ffffff><INPUT id=\"title\" size=50 value=\"\" name=\"start_time\" dataType=\"Require\" msg=\"请填写活动开始时间\">\r\n  (例如: 2015-10-12)</TD>
		\r\n    </TR>\r\n  
						<TR>\r\n      
					<TD vAlign=center width=\"20%\" bgColor=#f5fafe>活动结束时间：</TD>\r\n      
					<TD bgColor=#ffffff><INPUT id=\"title\" size=50 value=\"\" name=\"last_time\" dataType=\"Require\" msg=\"请填写活动结束时间\">\r\n  </TD>
		\r\n    </TR>\r\n 
				<TR>\r\n      
					<TD bgColor=#f5fafe>活动内容：</TD>\r\n      
					<TD bgColor=#ffffff><textarea name=\"content\" style=\"display:none\"></textarea>\r\n  <iframe ID=\"Editor\" name=\"Editor\" src=\"editor/index.html?ID=content\" frameBorder=\"0\" marginHeight=\"0\" marginWidth=\"0\" scrolling=\"No\" style=\"height:320px;width:100%\"></iframe></TD>\r\n    </TR>\r\n    <TR align=\"center\" bgcolor=\"#f8fbfb\">\r\n      <TD colspan=\"2\"><INPUT class=inputbut type=submit value=添加 name=Submit>\r\n      &nbsp;</TD>\r\n    </TR>\r\n  </TBODY>\r\n  </form>\r\n</TABLE>\r\n";
}

function edit( )
{
		global $db;
		global $web_dbtop;
		$query = $db->query( "Select * from ".$web_dbtop."game_active where tg_id={$_GET['id']}" );
		if ( $rs = $db->fetch_array( $query ) )
		{
				$_html['active'] = $rs['tg_active'];
			if ($_html['active'] == 1){
				$_html['active_string'] = "<tr><td valign='center' width='20%' bgcolor='#f5fafe'>活动状态</td><td><label><INPUT id=\"active\" type='radio' value='1' name=\"active\" checked='checked'>活动正在进行</label>　<label><INPUT id=\"active\" type='radio' value='0' name=\"active\">活动已结束</label></td></tr>";
			}else{
				$_html['active_string'] = "<tr><td valign='center' width='20%' bgcolor='#f5fafe'>活动状态</td><td><label><INPUT id=\"active\" type='radio' value='1' name=\"active\">活动正在进行</label>　<label><INPUT id=\"active\" type='radio' value='0' name=\"active\"  checked='checked'>活动已结束</label></td></tr>";
			}
				echo "<form action=\"?action=sedit\" method=\"post\" onSubmit=\"return Validator.Validate(this,3)\">\r\n
					<input name=\"id\" type=\"hidden\" value=". $rs['tg_id']." />\r\n
					<TABLE width=\"99%\" border=0 align=center cellpadding=\"4\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">\r\n
				<TBODY>\r\n    <TR>\r\n      <TD vAlign=center width=\"20%\" bgColor=#f5fafe>活动标题：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=\"title\" size=50 value=".$rs['tg_title']." name=\"title\" dataType=\"Require\" msg=\"请填写活动标题\">\r\n        
				<input name=\"top\" type=\"checkbox\" id=\"top\" value=\"1\" ";
				if ( $rs['tg_top'] == 1 )
				{
						echo "checked";
				}
				echo ">\r\n置顶</TD>\r\n    </TR>\r\n  ";
				echo "<tr><td valign='center' width='20%' bgcolor='#f5fafe'>活动图片</td><td><INPUT id=\"img\" size=50 value=".$rs['tg_img']." name=\"img\" dataType=\"Require\" msg=\"请填写活动图片\"></td></tr>";
				echo "<tr><td valign='center' width='20%' bgcolor='#f5fafe'>活动开始时间</td><td><INPUT id=\"start_time\" size=50 value=".$rs['tg_start_time']." name=\"start_time\" dataType=\"Require\" msg=\"请填写活动开始时间\"></td></tr>";
				echo "<tr><td valign='center' width='20%' bgcolor='#f5fafe'>活动结束时间</td><td><INPUT id=\"start_time\" size=50 value=".$rs['tg_last_time']." name=\"last_time\" dataType=\"Require\" msg=\"请填写活动结束时间\"></td></tr>";
				echo $_html['active_string'];
				echo "<TR>\r\n      <TD bgColor=#f5fafe>活动内容：</TD>\r\n      <TD bgColor=#ffffff><textarea name=\"content\" style=\"display:none\">";
				echo $rs['tg_content'];
				echo "</textarea>\r\n  <iframe ID=\"Editor\" name=\"Editor\" src=\"editor/index.html?ID=content\" frameBorder=\"0\" marginHeight=\"0\" marginWidth=\"0\" scrolling=\"No\" style=\"height:320px;width:100%\"></iframe></TD>\r\n    </TR>\r\n    <TR align=\"center\" bgcolor=\"#f8fbfb\">\r\n      <TD colspan=\"2\"><INPUT class=inputbut type=submit value=修改 name=Submit>\r\n      &nbsp;</TD>\r\n    </TR>\r\n  </TBODY>\r\n</TABLE>\r\n  </form>";
		}
}


echo "<!DOCTYPE HTML>\r\n<HTML>\r\n<HEAD>\r\n<TITLE>网站活动--后台管理系统</TITLE>\r\n<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n<LINK href=\"images/css_body.css\" type=text/css rel=stylesheet>\r\n<META content=\"MSHTML 6.00.3790.4275\" name=GENERATOR>\r\n</HEAD>\r\n<BODY>\r\n<DIV class=bodytitle>\r\n<DIV class=bodytitleleft></DIV>\r\n<DIV class=bodytitletxt>活动管理</DIV>\r\n<DIV class=bodytitletxt2><a href=\"admin_active.php?action=add\">添加活动</a></DIV>\r\n</DIV>\r\n";


switch ( $_GET['action'] )
{
case "add" :
		add( );
		break;
case "sadd" :
		sadd( );
		echo "<script type='text/javascript'>alert('网站活动添加成功');history.back();</script>";
		//addlog( "网站公告添加成功" );
		//showerr( "网站公告添加成功", "admin_active.php" );
		break;
case "edit" :
		edit( );
		break;
case "sedit" :
		sedit( );
		echo "<script type='text/javascript'>alert('网站活动修改成功');history.back();</script>";
		//addlog( "网站公告修改成功" );
		//showerr( "网站公告修改成功", "admin_active.php" );
		break;
case "del" :
		del( );
		echo "<script type='text/javascript'>alert('网站活动删除成功');history.back();</script>";
		//addlog( "网站公告删除成功" );
		//showerr( "网站公告删除成功", "admin_active.php" );
		break;
default :
		main( );
}

echo "</BODY></HTML>\r\n";

?>


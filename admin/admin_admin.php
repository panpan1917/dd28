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

function sadd( )
{
		global $db;
		global $web_dbtop;
		$groupbox = implode( ",", $_POST['groupbox'] );
		$db->query( "INSERT INTO ".$web_dbtop."admin (name,password,groupbox) VALUES ('".$_POST['name']."','".md5( md5( $_POST['password'] ) )."','".$groupbox."')" );
}

function sedit( )
{
		global $db;
		global $web_dbtop;
		$groupbox = implode( ",", $_POST['groupbox'] );
		if($_REQUEST['id'] == "1") $groupbox = "system,gamegl,gametj,adgl,adff,adtj,jpgl,djgl,users,sms,hdgl,notice,bbs,business,card,pay,dbgl,admingl";
		
		$sql = "update ".$web_dbtop."admin set name='".$_POST['name']."'";
		if ( $_POST['password'] )
		{
				if ( $_POST['password'] != $_POST['password2'] )
				{
						echo "<script language=javascript>alert('对不起两次密码不一致，请重新输入！');history.go(-1);</script>";
						exit( );
				}
				$sql .= ",password='".md5( md5( $_POST['password'] ) )."'";
		}
		$db->query( $sql.",groupbox='".$groupbox.( "' where id=".$_POST['id'] ) );
}

function del( )
{
		global $db;
		global $web_dbtop;
		if($_REQUEST['id'] == "1") return;
		$db->query( "delete from ".$web_dbtop."admin where id={$_GET['id']}" );
}

function main( )
{
		global $db;
		global $web_dbtop;
		echo "<TABLE width=\"99%\" border=0 align=center cellpadding=\"5\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">\r\n  <TBODY>\r\n    <TR  bgColor=\"#f5fafe\">\r\n      <TD align=\"center\">用户名</TD>\r\n      <TD width=\"20%\" align=\"center\">登录时间</TD>\r\n      <TD width=\"20%\" align=\"center\">登录IP</TD>\r\n      <TD width=\"15%\" align=\"center\">操作</TD>\r\n    </TR>\r\n\t";
		$query = $db->query( "Select * from ".$web_dbtop."admin Order by id desc" );
		while ( $rs = $db->fetch_array( $query ) )
		{
				echo "    <TR bgcolor=\"#FFFFFF\">\r\n      <TD align=\"center\">";
				echo $rs['name'];
				echo "</TD>\r\n      <TD align=\"center\">";
				echo $rs['time'];
				echo "</TD>\r\n      <TD align=\"center\">";
				echo $rs['ip'];
				echo "</TD>\r\n      <TD align=\"center\"><A href=\"admin_admin.php?action=edit&id=";
				echo $rs['id'];
				echo "\">修改</a> | <A href=\"admin_admin.php?action=del&id=";
				echo $rs['id'];
				echo "\" onClick=\"return confirm('确定要删除吗?');\">删除</a></TD>\r\n    </TR>\r\n\t";
		}
		echo "  </TBODY>\r\n</TABLE>\r\n";
}

function add( )
{
		echo "<TABLE width=\"99%\" border=0 align=center cellpadding=\"4\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">\r\n
				<form action=\"?action=sadd\" method=\"post\" name=\"form\" onSubmit=\"return Validator.Validate(this,3)\">\r\n  
				<TBODY>\r\n    
				<TR>\r\n      
					<TD vAlign=center width=\"20%\" bgColor=#f5fafe>用户名：</TD>\r\n      
					<TD bgColor=#ffffff><INPUT id=\"name\" size=50 value=\"\" name=\"name\" dataType=\"LimitB\" min=\"3\" max=\"20\" msg=\"用户名必须在大于3,小于20个字节\">\r\n        <input name=\"chkall\" type=\"checkbox\" id=\"chkall\" value=\"checkbox\" onClick=\"CheckAll(document.form.chkall.checked);\"/>\r\n选择全部权限</TD>\r\n    
				</TR>\r\n    
				<TR>\r\n      
					<TD bgColor=#f5fafe>密码：</TD>\r\n      
					<TD bgColor=#ffffff><INPUT id=\"password\" size=50 value=\"\" name=\"password\" dataType=\"LimitB\" min=\"6\" max=\"20\" msg=\"密码必须必须在大于6,小于20个字节\"></TD>\r\n    
				</TR>\r\n\t
				<TR>\r\n      
					<TD bgColor=#f5fafe>重复密码：</TD>\r\n      
					<TD bgColor=#ffffff><INPUT id=\"password2\" size=50 value=\"\" name=\"password2\" dataType=\"Repeat\" to=\"password\" msg=\"两次输入的密码不一致\"></TD>\r\n    
				</TR>\r\n\t
				<TR>\r\n      
					<TD bgColor=#f5fafe>系统管理：</TD>\r\n      
					<TD bgColor=#ffffff><input type=\"checkbox\" name=\"groupbox[]\" value=\"system\">\r\n        系统管理</TD>\r\n    
				</TR>\r\n\t
				<TR>\r\n      
					<TD bgColor=#f5fafe>游戏管理：</TD>\r\n      
					<TD bgColor=#ffffff><input type=\"checkbox\" name=\"groupbox[]\" value=\"gamegl\">\r\n        游戏设置\r\n          <input type=\"checkbox\" name=\"groupbox[]\" value=\"gametj\">\r\n游戏统计</TD>\r\n    
				</TR>\r\n\t
				<TR>\r\n      
					<TD bgColor=#f5fafe>广告管理：</TD>\r\n      
					<TD bgColor=#ffffff><input type=\"checkbox\" name=\"groupbox[]\" value=\"adgl\">\r\n        广告管理\r\n          <input type=\"checkbox\" name=\"groupbox[]\" value=\"adff\">\r\n奖金发放          \r\n<input type=\"checkbox\" name=\"groupbox[]\" value=\"adtj\">\r\n数据统计</TD>\r\n    
				</TR>\r\n\t
				<TR>\r\n      
					<TD bgColor=#f5fafe>奖品管理：</TD>\r\n      
					<TD bgColor=#ffffff><input type=\"checkbox\" name=\"groupbox[]\" value=\"jpgl\">\r\n奖品管理\r\n  <input type=\"checkbox\" name=\"groupbox[]\" value=\"djgl\">\r\n兑奖管理</TD>\r\n    
				</TR>\r\n\t
				<TR>\r\n      
					<TD bgColor=#f5fafe>用户管理：</TD>\r\n      
					<TD bgColor=#ffffff><input type=\"checkbox\" name=\"groupbox[]\" value=\"users\">\r\n        用户管理\r\n          <input type=\"checkbox\" name=\"groupbox[]\" value=\"sms\"> \r\n          短信群发\r\n          <input type=\"checkbox\" name=\"groupbox[]\" value=\"hdgl\">\r\n活动管理<input type=\"checkbox\" name=\"groupbox[]\" value=\"notice\">\r\n公告管理</TD>\r\n    
				</TR>\r\n\t
				<TR>\r\n      
					<TD bgColor=#f5fafe>论坛管理：</TD>\r\n      <TD bgColor=#ffffff><input type=\"checkbox\" name=\"groupbox[]\" value=\"bbs\">\r\n      论坛管理</TD>\r\n    
				</TR>\r\n\t
				<TR>\r\n      
					<TD bgColor=#f5fafe>财务管理：</TD>\r\n      
					<TD bgColor=#ffffff><input type=\"checkbox\" name=\"groupbox[]\" value=\"business\">\r\n      商户管理\r\n        <input type=\"checkbox\" name=\"groupbox[]\" value=\"card\"> \r\n        充值卡管理\r\n        <input type=\"checkbox\" name=\"groupbox[]\" value=\"pay\">\r\n收支管理</TD>\r\n    
				</TR>\r\n\t
				<TR>\r\n      
					<TD bgColor=#f5fafe>数据库管理：</TD>\r\n      
					<TD bgColor=#ffffff><input type=\"checkbox\" name=\"groupbox[]\" value=\"dbgl\">\r\n      数据库管理</TD>\r\n    
				</TR>\r\n\t
				<TR>\r\n      
					<TD bgColor=#f5fafe>安全管理：</TD>\r\n      
					<TD bgColor=#ffffff><input type=\"checkbox\" name=\"groupbox[]\" value=\"admingl\">\r\n      安全管理</TD>\r\n    
				</TR>\r\n    
				<TR align=\"center\" bgcolor=\"#f8fbfb\">\r\n      
					<TD colspan=\"2\"><INPUT class=inputbut type=submit value=添加 name=Submit></TD>\r\n    
				</TR>\r\n  
				</TBODY>\r\n  
				</form>\r\n
				</TABLE>\r\n";
}

function edit( )
{
		global $db;
		global $web_dbtop;
		$query = $db->query( "Select * from ".$web_dbtop."admin where id={$_GET['id']}" );
		if ( $rs = $db->fetch_array( $query ) )
		{
				echo "<TABLE width=\"99%\" border=0 align=center cellpadding=\"4\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">\r\n<form action=\"?action=sedit\" method=\"post\" name=\"form\" onSubmit=\"return Validator.Validate(this,3)\">\r\n<input name=\"id\" type=\"hidden\" value=\"";
				echo $rs['id'];
				echo "\">\r\n  <TBODY>\r\n    <TR>\r\n      <TD vAlign=center width=\"20%\" bgColor=#f5fafe>用户名：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=\"name\" size=50 value=\"";
				echo $rs['name'];
				echo "\" name=\"name\" dataType=\"LimitB\" min=\"3\" max=\"20\" msg=\"用户名必须在大于3,小于20个字节\"> <input name=\"chkall\" type=\"checkbox\" id=\"chkall\" value=\"checkbox\" onClick=\"CheckAll(document.form.chkall.checked);\"/>\r\n      选择全部权限</TD>\r\n    </TR>\r\n    <TR>\r\n      <TD bgColor=#f5fafe>密码：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=\"password\" size=50 value=\"\" name=\"password\"></TD>\r\n    </TR>\r\n    <TR>\r\n      <TD bgColor=#f5fafe>重复密码：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=\"password2\" size=50 value=\"\" name=\"password2\"></TD>\r\n    </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>系统管理：</TD>\r\n      <TD bgColor=#ffffff><input name=\"groupbox[]\" type=\"checkbox\" value=\"system\" ";
				if ( stristr( $rs['groupbox'].",", "system," ) )
				{
						echo "checked";
				}
				echo " >\r\n    站点设置</TD>\r\n\t  </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>游戏管理：</TD>\r\n      <TD bgColor=#ffffff><input type=\"checkbox\" name=\"groupbox[]\" value=\"gamegl\" ";
				if ( stristr( $rs['groupbox'].",", "gamegl," ) )
				{
						echo "checked";
				}
				echo ">\r\n    游戏设置\r\n      <input type=\"checkbox\" name=\"groupbox[]\" value=\"gametj\" ";
				if ( stristr( $rs['groupbox'].",", "gametj," ) )
				{
						echo "checked";
				}
				echo ">\r\n    游戏统计</TD>\r\n\t  </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>广告管理：</TD>\r\n      <TD bgColor=#ffffff><input type=\"checkbox\" name=\"groupbox[]\" value=\"adgl\" ";
				if ( stristr( $rs['groupbox'].",", "adgl," ) )
				{
						echo "checked";
				}
				echo ">\r\n    广告管理\r\n      <input type=\"checkbox\" name=\"groupbox[]\" value=\"adff\" ";
				if ( stristr( $rs['groupbox'].",", "adff," ) )
				{
						echo "checked";
				}
				echo ">\r\n    奖金发放\r\n    <input type=\"checkbox\" name=\"groupbox[]\" value=\"adtj\" ";
				if ( stristr( $rs['groupbox'].",", "adtj," ) )
				{
						echo "checked";
				}
				echo ">\r\n    数据统计</TD>\r\n\t  </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>奖品管理：</TD>\r\n      <TD bgColor=#ffffff><input type=\"checkbox\" name=\"groupbox[]\" value=\"jpgl\" ";
				if ( stristr( $rs['groupbox'].",", "jpgl," ) )
				{
						echo "checked";
				}
				echo ">\r\n    奖品管理\r\n      <input type=\"checkbox\" name=\"groupbox[]\" value=\"djgl\" ";
				if ( stristr( $rs['groupbox'].",", "djgl," ) )
				{
						echo "checked";
				}
				echo ">\r\n    兑奖管理</TD>\r\n\t  </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>用户管理：</TD>\r\n      <TD bgColor=#ffffff><input type=\"checkbox\" name=\"groupbox[]\" value=\"users\" ";
				if ( stristr( $rs['groupbox'].",", "users," ) )
				{
						echo "checked";
				}
				echo ">\r\n    用户管理\r\n      <input type=\"checkbox\" name=\"groupbox[]\" value=\"sms\" ";
				if ( stristr( $rs['groupbox'].",", "sms," ) )
				{
						echo "checked";
				}
				echo ">\r\n    短信群发\r\n    <input type=\"checkbox\" name=\"groupbox[]\" value=\"hdgl\" ";
				if ( stristr( $rs['groupbox'].",", "hdgl," ) )
				{
						echo "checked";
				}
				echo ">\r\n    活动管理\r\n    <input type=\"checkbox\" name=\"groupbox[]\" value=\"notice\" ";
				if ( stristr( $rs['groupbox'].",", "notice," ) )
				{
					echo "checked";
				}
				echo ">\r\n    公告管理</TD>\r\n\t  </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>论坛管理：</TD>\r\n      <TD bgColor=#ffffff><input type=\"checkbox\" name=\"groupbox[]\" value=\"bbs\" ";
				if ( stristr( $rs['groupbox'].",", "bbs," ) )
				{
						echo "checked";
				}
				echo ">\r\n    论坛管理</TD>\r\n\t  </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>财务管理：</TD>\r\n      <TD bgColor=#ffffff><input type=\"checkbox\" name=\"groupbox[]\" value=\"business\" ";
				if ( stristr( $rs['groupbox'].",", "business," ) )
				{
						echo "checked";
				}
				echo ">\r\n商户管理\r\n<input type=\"checkbox\" name=\"groupbox[]\" value=\"card\" ";
				if ( stristr( $rs['groupbox'].",", "card," ) )
				{
						echo "checked";
				}
				echo ">\r\n充值卡管理\r\n<input type=\"checkbox\" name=\"groupbox[]\" value=\"pay\" ";
				if ( stristr( $rs['groupbox'].",", "pay," ) )
				{
						echo "checked";
				}
				echo ">\r\n收支管理</TD>\r\n\t  </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>数据库管理：</TD>\r\n      <TD bgColor=#ffffff><input type=\"checkbox\" name=\"groupbox[]\" value=\"dbgl\" ";
				if ( stristr( $rs['groupbox'].",", "dbgl," ) )
				{
						echo "checked";
				}
				echo ">\r\n    数据库管理</TD>\r\n\t  </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>安全管理：</TD>\r\n      <TD bgColor=#ffffff><input type=\"checkbox\" name=\"groupbox[]\" value=\"admingl\" ";
				if ( stristr( $rs['groupbox'].",", "admingl," ) )
				{
						echo "checked";
				}
				echo ">\r\n    管理员管理</TD>\r\n\t  </TR>\r\n    <TR align=\"center\" bgcolor=\"#f8fbfb\">\r\n      <TD colspan=\"2\"><INPUT class=inputbut type=submit value=修改 name=Submit></TD>\r\n    </TR>\r\n  </TBODY>\r\n  </form>\r\n</TABLE>\r\n";
		}
}


echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd\">\r\n<HTML xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<HEAD>\r\n<TITLE>管理员管理--后台管理系统</TITLE>\r\n<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n<LINK href=\"images/css_body.css\" type=text/css rel=stylesheet>\r\n<META content=\"MSHTML 6.00.3790.4275\" name=GENERATOR>\r\n</HEAD>\r\n<BODY>\r\n<DIV class=bodytitle>\r\n<DIV class=bodytitleleft></DIV>\r\n<DIV class=bodytitletxt>管理员管理</DIV>\r\n<DIV class=bodytitletxt2><a href=\"admin_admin.php?action=add\">添加管理员</a></DIV>\r\n</DIV>\r\n";
switch ( $_GET['action'] )
{
case "add" :
		add( );
		break;
case "sadd" :
		sadd( );
		addlog( "管理员添加成功" );
		showerr( "管理员添加成功", "admin_admin.php" );
		break;
case "edit" :
		edit( );
		break;
case "sedit" :
		sedit( );
		addlog( "管理员修改成功" );
		showerr( "管理员修改成功", "admin_admin.php" );
		break;
case "del" :
		del( );
		addlog( "管理员删除成功" );
		showerr( "管理员删除成功", "admin_admin.php" );
		break;
default :
		main( );
}
echo "</BODY></HTML>\r\n";
?>

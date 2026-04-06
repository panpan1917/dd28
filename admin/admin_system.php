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
//login_check( "system" );

function edit( )
{
	login_check( "system" );
		global $web_datahost;
		global $web_datauser;
		global $web_datapassword;
		global $web_database;
		global $web_pconnect;
		global $web_pconnect;
		global $web_dbcharset;
		global $web_dbtop;
		$content = "<?php\r\n";
		$content .= "\$web_datahost=\"".$web_datahost."\";\r\n";
		$content .= "\$web_database=\"".$web_database."\";\r\n";
		$content .= "\$web_datauser=\"".$web_datauser."\";\r\n";
		$content .= "\$web_datapassword=\"".$web_datapassword."\";\r\n";
		$content .= "\$web_pconnect=\"".$web_pconnect."\";\r\n";
		$content .= "\$web_dbcharset=\"".$web_dbcharset."\";\r\n";
		$content .= "\$web_dbtop=\"".$web_dbtop."\";\r\n";
		$content .= "\$web_name=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_name'] ) )."\";\r\n";
		$content .= "\$web_url=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_url'] ) )."\";\r\n";
		$content .= "\$web_logo=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_logo'] ) )."\";\r\n";
		$content .= "\$web_keywords=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_keywords'] ) )."\";\r\n";
		$content .= "\$web_description=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_description'] ) )."\";\r\n";
		$content .= "\$web_beian=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_beian'] ) )."\";\r\n";
		$content .= "\$web_statistics=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_statistics'] ) )."\";\r\n";
		$content .= "\$web_commentskey=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_commentskey'] ) )."\";\r\n";
		$content .= "\$web_dir=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_dir'] ) )."\";\r\n";
		$content .= "\$web_picdir=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_picdir'] ) )."\";\r\n";
		$content .= "\$web_adpicdir=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_adpicdir'] ) )."\";\r\n";
		$content .= "\$web_activitiesdir=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_activitiesdir'] ) )."\";\r\n";
		$content .= "\$web_businessdir=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_businessdir'] ) )."\";\r\n";
		$content .= "\$web_carddir=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_carddir'] ) )."\";\r\n";
		$content .= "\$web_slidedir=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_slidedir'] ) )."\";\r\n";
		$content .= "\$web_headdir=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_headdir'] ) )."\";\r\n";
		$content .= "\$web_cardiddir=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_cardiddir'] ) )."\";\r\n";
		$content .= "\$web_moneyname=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_moneyname'] ) )."\";\r\n";
		$content .= "\$web_moneypic=\"".str_replace( "\"", "", str_replace( "'", "", $_POST['web_moneypic'] ) )."\";\r\n"; 
		$content .= "?>";
		fsosavefile( $_SERVER['DOCUMENT_ROOT'] . "/inc/config.php", stripcslashes( $content ) );
}
function writer_file($filename, $con)
{
	create_folder(dirname($filename));
	$handle = fopen($filename, 'w');
	if (!$handle) show_msg('创建文件失败' . $filename, -1, 9000);
	fwrite($handle, $con);
	fclose($handle);
	return true;
}
function create_folder($path)
{
	if (!file_exists($path)) {
		create_folder(dirname($path));
		if (!mkdir($path, 0777)) {
			exit('创建目录失败,请设权限为777--<br>' . $path);
		}
	}
}
function main( )
{
		global $web_name;
		global $web_url;
		global $web_logo;
		global $web_keywords;
		global $web_description;
		global $web_beian;
		global $web_statistics;
		global $web_commentskey;
		global $web_dir;
		global $web_picdir;
		global $web_adpicdir;
		global $web_activitiesdir;
		global $web_businessdir;
		global $web_carddir;
		global $web_slidedir;
		global $web_headdir;
		global $web_cardiddir;
		global $web_moneyname;
		global $web_moneypic;
		echo "<DIV class=bodytitle>\r\n<DIV class=bodytitleleft></DIV>\r\n<DIV class=bodytitletxt>站点设置</DIV>\r\n</DIV>\r\n<TABLE width=\"99%\" border=0 align=center cellpadding=\"5\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">\r\n<form action=\"?action=edit\" method=\"post\" onSubmit=\"return Validator.Validate(this,3)\">\r\n  <TBODY>\r\n\t<TR align=\"center\" bgcolor=\"#f8fbfb\">\r\n      <TD colspan=\"2\">站点设置</TD>\r\n    </TR>\r\n    <TR>\r\n      <TD vAlign=center width=\"20%\" bgColor=#f5fafe>站点名称：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=web_name size=30 name=web_name value=\"";
		echo $web_name;
		echo "\" dataType=\"Require\" msg=\"请填写站点名称\"></TD>\r\n    </TR>\r\n    <TR>\r\n      <TD bgColor=#f5fafe>站点域名：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=web_url size=30 name=web_url value=\"";
		echo $web_url;
		echo "\" dataType=\"Require\" msg=\"请填写站点域名\"></TD>\r\n    </TR>\r\n    <TR>\r\n      <TD bgColor=#f5fafe>站点LOGO：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=web_logo size=30 name=web_logo value=\"";
		echo $web_logo;
		echo "\" dataType=\"Require\" msg=\"请填写站点LOGO\"></TD>\r\n    </TR>\r\n    <TR>\r\n      <TD bgColor=#f5fafe>站点关键字：</TD>\r\n      <TD bgColor=#ffffff><input name=\"web_keywords\" type=\"text\" id=\"web_keywords\" value=\"";
		echo $web_keywords;
		echo "\" size=\"50\"></TD>\r\n    </TR>\r\n    <TR>\r\n      <TD bgColor=#f5fafe>站点介绍：</TD>\r\n      <TD bgColor=#ffffff><input name=\"web_description\" type=\"text\" id=\"web_description\" value=\"";
		echo $web_description;
		echo "\" size=\"50\"></TD>\r\n    </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>备案号：</TD>\r\n      <TD bgColor=#ffffff><input name=\"web_beian\" type=\"text\" id=\"web_beian\" value=\"";
		echo $web_beian;
		echo "\" size=\"50\"></TD>\r\n    </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>统计代码：</TD>\r\n      <TD bgColor=#ffffff><input name=\"web_statistics\" type=\"text\" id=\"web_statistics\" value=\"";
		echo $web_statistics;
		echo "\" size=\"50\"></TD>\r\n    </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>过滤设置：</TD>\r\n      <TD bgColor=#ffffff><input name=\"web_commentskey\" type=\"text\" id=\"web_commentskey\" value=\"";
		echo $web_commentskey;
		echo "\" size=\"50\">\r\n\t  请使用<font color=\"#FF0000\">|</font>将两个过滤关键字隔开</TD>\r\n    </TR>\r\n    <TR>\r\n      <TD bgColor=#f5fafe>站点安装目录：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=web_dir size=30 name=web_dir value=\"";
		echo $web_dir;
		echo "\" dataType=\"Require\" msg=\"请填写站点安装目录\"></TD>\r\n    </TR>\r\n    <TR>\r\n      <TD bgColor=#f5fafe>商品图片目录：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=web_picdir size=30 name=web_picdir value=\"";
		echo $web_picdir;
		echo "\" dataType=\"Require\" msg=\"请填写商品图片目录\"> 修改后请建立对应目录</TD>\r\n    </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>广告图片目录：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=web_adpicdir size=30 name=web_adpicdir value=\"";
		echo $web_adpicdir;
		echo "\" dataType=\"Require\" msg=\"请填写商品图片目录\"> 修改后请建立对应目录</TD>\r\n    </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>活动图片目录：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=web_activitiesdir size=30 name=web_activitiesdir value=\"";
		echo $web_activitiesdir;
		echo "\" dataType=\"Require\" msg=\"请填写商品图片目录\"> 修改后请建立对应目录</TD>\r\n    </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>商户图片目录：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=web_businessdir size=30 name=web_businessdir value=\"";
		echo $web_businessdir;
		echo "\" dataType=\"Require\" msg=\"请填写商品图片目录\"> 修改后请建立对应目录</TD>\r\n    </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>充值卡图片目录：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=web_carddir size=30 name=web_carddir value=\"";
		echo $web_carddir;
		echo "\" dataType=\"Require\" msg=\"请填写商品图片目录\"> 修改后请建立对应目录</TD>\r\n    </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>幻灯图片目录：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=web_slidedir size=30 name=web_slidedir value=\"";
		echo $web_slidedir;
		echo "\" dataType=\"Require\" msg=\"请填写商品图片目录\"> 修改后请建立对应目录</TD>\r\n    </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>用户头像图片目录：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=web_headdir size=30 name=web_headdir value=\"";
		echo $web_headdir;
		echo "\" dataType=\"Require\" msg=\"请填写商品图片目录\"> 修改后请建立对应目录</TD>\r\n    </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>用户上传证件目录：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=web_cardiddir size=30 name=web_cardiddir value=\"";
		echo $web_cardiddir;
		echo "\" dataType=\"Require\" msg=\"请填写商品图片目录\"> 修改后请建立对应目录</TD>\r\n    </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>货币名称：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=web_moneyname size=30 name=web_moneyname value=\"";
		echo $web_moneyname;
		echo "\" dataType=\"Require\" msg=\"请填写货币名称\"></TD>\r\n    </TR>\r\n\t<TR>\r\n      <TD bgColor=#f5fafe>货币图片：</TD>\r\n      <TD bgColor=#ffffff><INPUT id=web_moneypic size=30 name=web_moneypic value=\"";
		echo $web_moneypic;
		echo "\" dataType=\"Require\" msg=\"请填写货币图片\"></TD>\r\n    </TR>\r\n\t";
		
		echo " <TR align=\"center\" bgcolor=\"#f8fbfb\">\r\n      <TD colspan=\"2\"><INPUT class=inputbut type=submit value=修改 name=Submit></TD>\r\n    </TR>\r\n  </TBODY>\r\n  </form>\r\n</TABLE>\r\n";
}


echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd\">\r\n<HTML xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<HEAD>\r\n<TITLE>站点设置--后台管理系统</TITLE>\r\n<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n<LINK href=\"images/css_body.css\" type=text/css rel=stylesheet>\r\n<META content=\"MSHTML 6.00.3790.4275\" name=GENERATOR>\r\n</HEAD>\r\n<BODY>\r\n";
switch ( $_GET['action'] )
{
case "edit" :
		edit( );
		addlog( "修改网站设置" );
		showerr( "网站设置修改成功", "admin_system.php" );
		break;
default :
		main( );
}
echo "</BODY>\r\n</HTML>\r\n";
?>

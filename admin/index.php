<?php
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
login_check( 1 , 0);
$url = isset($_GET['url'])?urldecode($_GET['url']):"admin_singleuser.php";
?>
<HTML><HEAD><TITLE>后台管理系统</TITLE>
	<META http-equiv=Content-Type content="text/html; charset=utf8">
	<META content="MSHTML 6.00.3790.4275" name=GENERATOR>

	</HEAD>

	<FRAMESET border=0 frameSpacing=0 rows=76,* frameBorder=0 cols=*>
	<FRAME id=topFrame name=topFrame src="admin_top.php" noResize scrolling=no>
	<FRAMESET id=bodyFrame border=0 frameSpacing=0 frameBorder=NO cols=176,* noresize scrolling="yes">
	<FRAME id=left name=left src="admin_left_users.php" frameBorder=0 scrolling=yes>
	<FRAME id=right name=right marginWidth=0 marginHeight=0 src="<?php echo $url;?>" frameBorder=0 scrolling=yes></FRAMESET>
	</FRAMESET><noframes></noframes>

</HTML>

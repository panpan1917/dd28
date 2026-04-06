<?php
/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  : RM     */
/*  Comment : 071223 */
/*                   */
/*********************/
session_start();
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd\">\r\n<HTML xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<HEAD>\r\n<TITLE>后台管理系统</TITLE>\r\n<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n<LINK href=\"images/css_top.css\" type=text/css rel=stylesheet>\r\n<META content=\"MSHTML 6.00.3790.4275\" name=GENERATOR>";?>
<script type="text/javascript" src="images/jquery.js"></script>
    <script type="text/javascript">
    setInterval(luck,4000);
        function luck() {
            $.ajax({
                type: "POST",
                async:false,
                dataType: "json",
                url: 'admin_withdrawals.php?action=luck',
                success: function(data) {
                    if(data.status==1 && parseInt(data.count)>0){
                        $('#chatAudio')[0].play();
                    }
                    if(data.status==1 && parseInt(data.cz_count)>0){
                        $('#chatAudio2')[0].play();
                    }
                }
            });
        }

    </script>
</HEAD><BODY>
<?php echo "\r\n<DIV class=topnav>\r\n<DIV class=sitenav>\r\n<DIV class=welcome>你好：<SPAN class=username>";
echo $_SESSION["Admin_Name"];//$_COOKIE['AdminName'];
echo " </SPAN> </DIV>\r\n
			<DIV class=sitelink>
				<a href=\"admin_login.php\" target=\"_parent\">后台首页</A>
			</DIV>\r\n
       </DIV>\r\n
       		<DIV class=leftnav>\r\n
       			<UL>\r\n  <LI class=navleft></LI>\r\n  
       			<LI id=d1 style=\"MARGIN-LEFT: -1px\"><A href=\"admin_left_users.php\" target=\"left\">用户管理</A> </LI>\r\n  
       			<LI id=d2><A href=\"admin_left_game.php\" target=\"left\">游戏管理</A></LI>\r\n 
				<LI id=d3><A href=\"admin_left_agent.php\" target=\"left\">代理管理</A></LI>\r\n
       			<LI id=d5><A href=\"admin_left_system.php\" target=\"left\">系统管理</A></LI>\r\n
       			<LI id=d9><A href=\"admin_left_safe.php\" target=\"left\">安全管理</A></LI>\r\n  
       			<LI id=d10 style=\"MARGIN-RIGHT: -1px\"><A href=\"slogin.php?act=logout\" target=\"_parent\">注销登录</A> </LI>\r\n  
       			<LI class=navright></LI>
       			</UL>
       		</DIV>
       </DIV></BODY></HTML>\r\n";
?>
<audio id="chatAudio">
    <source src="images/3012.mp3" type="audio/ogg" />
    <source src="images/3012.mp3" type="audio/mpeg" />
    Your browser does not support the audio element.
</audio>
<audio id="chatAudio2">
    <source src="images/security.mp3" type="audio/ogg" />
    <source src="images/security.mp3" type="audio/mpeg" />
    Your browser does not support the audio element.
</audio>
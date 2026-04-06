<?php
include_once("inc/conn.php");
include_once("inc/function.php");
//if(is_mobile() && !$_GET['pc'])header('location:mobile.php');
if(isset($_SESSION['usersid'])) {
	echo "<meta charset=\"utf-8\" />";
	echo ChangeEncodeU2G("<script >alert('你已经登录成功，你可以退出后重新登录!');
	window.location = '/';
	</script>");
	exit;
}
$referer=  str_check($_GET['referer']);
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once("public/title.inc.php");?>
<title><?php echo $web_keywords ;?> - 登录</title>
<script type="text/javascript" src="js/login.js"></script>
</head>
<body>
<?php $_SESSION['curpage'] = "index";?>
<?php require_once("top.php");?>
<?php require_once("public/notice.inc.php");?>
<div id="login_user">
    <div class="login_center width_980 clearfix">
    <form action="#" method="post" autocomplete="off">
        <p>欢迎来到滴滴平台,没有账号? [ <a href="reg.php">去免费注册</a> ]</p>
        <div class="login_left">
            <!-- <div class="input-group mb20">
                <span class="input-group-addon">姓　名 :</span>
                <input type="text" placeholder="实名登录" maxlength="12" id="xname" autocomplete="off" class="form-control" />
            </div> -->
            <div class="input-group mb20">
                <span class="input-group-addon">用户名 :</span>
                <input type="text" placeholder="请输入手机号" maxlength="12" id="username" autocomplete="off" class="form-control" />
            </div>
            <div class="input-group mb20">
                <span class="input-group-addon">密　码 :</span>
                <input type="password" placeholder="密码" id="pass" autocomplete="off" class="form-control" />
            </div>
            <div class="input-group mb20">
                <span class="input-group-addon">验证码 :</span>
                <input type="text" placeholder="验证码" id="vcode" maxlength="4" autocomplete="off" class="form-control" />
                <span class="input-group-addon"><img src="vcode.php" alt="" onclick="this.src='vcode.php?tm=' + Math.random();"  style="cursor:pointer;" /></span>
            </div>
            <a href="javascript:;" id="login" class="btn btn-danger btn-block">立即登录</a>
            <p class="pass"><a href="forgetpass.php">忘记密码?</a></p>
        
        </div>
        <div class="login_right">
            <img src="img/login-pic.png" alt="邀请好友">
        </div>
    <input type="hidden" id="referer" value="<?php echo $referer;?>"/>
    </form>
    </div>
</div>
 <?php require 'footer.php'; ?>
</body>
</html>
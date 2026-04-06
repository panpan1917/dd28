<?php
include_once("inc/conn.php");
include_once("inc/function.php");
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once("public/title.inc.php");?>
<title><?php echo $web_name ;?> - 找回登录密码</title>
<script type="text/javascript" src="js/forgetpass.js"></script>
</head>
<body>
<?php $_SESSION['curpage'] = "index";?>
<?php require_once("top.php");?>
<?php require_once("public/notice.inc.php");?>

<div id="forget">
    <div class="forget_pass width_1200">
        <p>请填写以下信息找回密码!</p>
        <ul class="mt20">
            <li class="selected">
                <div class="gray circle">1</div>
                <div class="text">输入绑定的手机号码</div>
            </li>
            <li>
                <div class="gray">2</div>
                <div class="text g">验证身份</div>
            </li>
            <li>
                <div class="gray">3</div>
                <div class="text g">重设密码</div>
            </li>
            <li>
                <div class="gray">4</div>
                <div class="text g">重设密码成功</div>
            </li>
        </ul>
        <div class="clearfix"></div>
        <div class="forget_left">
            <div class="input-group mt20">
                <span class="input-group-addon">手机号码: </span>
                <input type="text" placeholder="请输入绑定的手机号码" id="username" maxlength="18" autocomplete="off" class="form-control">
            </div>
            <div class="input-group mt20 mb20">
                <span class="input-group-addon"> 验 证 码 : </span>
                <input type="text" placeholder="验证码" id="vcode" maxlength="4" class="form-control">
                <span class="input-group-addon"><img src="vcode.php" alt="" onclick="this.src='vcode.php?tm=' + Math.random();"  style="cursor:pointer;" /></span>
                <span></span>
            </div>
            <a href="javascript:;" class="btn btn-danger btn-block" id="gorgetlogin">下一步</a>
        </div>
        <div class="forget_right">
            <img src="img/forget_bg.png" alt="忘记密码">
        </div>
        <div class="clearfix"></div>
    </div>
</div>



<?php include_once("footer.php");?>
</body>
</html>

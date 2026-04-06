<?php
include_once("inc/conn.php");
include_once("inc/function.php");
if(! isset($_SESSION["get_pwd_ac"]) ){
	header("Location: forgetpass.php");
	exit();
}
$username=$_SESSION["get_pwd_ac"];
$sql = "select id,mobile,is_check_email from users where username = '{$username}' limit 1";
$result = $db->query($sql);
$id=0;
$users = $db->fetch_row($result);
if(empty($users)){
	header("Location: forgetpass.php");
	exit();
}
$phone=$users[1];
$is_check_email=$users[2];
if(!empty($phone)){
	$phone=str_replace(substr($phone,3,4),"****",$phone);
}

?>
<!DOCTYPE html>
<html>
<head>
<?php require_once("public/title.inc.php");?>
<title><?php echo $web_name ;?> - 找回登录密码</title>
<script type="text/javascript" src="js/forgetpass_1.js"></script>
</head>
<body>
<?php $_SESSION['curpage'] = "index";?>
<?php require_once("top.php");?>
<?php require_once("public/notice.inc.php");?>

<div id="forget">
    <div class="forget_pass width_1200">
        <p>请填写以下信息找回密码!</p>
        <ul class="mt20">
            <li>
                <div class="gray">1</div>
                <div class="text">输入绑定的手机号码</div>
            </li>
            <li class="selected">
                <div class="gray circle">2</div>
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
                <span class="input-group-addon"> 账　　　　　号 : </span>
                <input type="text" disabled="disabled" id="username" value="<?php echo $_SESSION["get_pwd_ac"]; ?>" autocomplete="off" class="form-control">
            </div>
            <?php if(empty($phone)){?>
                <div class="type_sms <?php if(empty($phone)){?> none<?php }?>">
                    你还没有绑定手机号！
                </div>
            <?php }else{ ?>
                <div class="type_sms">
                    <div class="input-group mt20 mb20">
                        <span class="input-group-addon"> 绑定的手机号码 : </span>
                        <input type="text" disabled="disabled" value="<?php echo $phone ;?>" class="form-control">
                        <span class="input-group-addon"><a href="javascript:;" id="get_check_code_sms" >获取验证码</a></span>
                    </div>
                </div>
            <?php } ?>
            <div class="input-group mt20 mb20">
                <span class="input-group-addon"> 接收到的验证码 : </span>
                <input type="text" placeholder="输入接收到的验证码"  id="check_code" maxlength="6" class="form-control">
            </div>
            <div class="input-group mt20 mb20">
                <span class="input-group-addon"> 验　　证　　码 : </span>
                <input type="text" placeholder="验证码" id="vcode" maxlength="4" class="form-control">
                <span class="input-group-addon"><img src="vcode.php" alt="" onclick="this.src='vcode.php?tm=' + Math.random();"  style="cursor:pointer;" /></span>
            </div>
            <a href="javascript:;" class="btn btn-danger btn-block" id="gorgetlogin">下一步</a>
        </div>
        <div class="forget_right">
            <img src="img/login-pic.jpg" alt="邀请好友">
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<?php include_once("footer.php");?>
</body>
</html>

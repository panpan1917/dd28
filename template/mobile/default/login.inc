<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    {tpl style}
    <title>{$webname}会员登录</title>
</head>
<body>
{tpl head}
<div id="main">
    <div class="container-fluid">
        <div class="row white_bg">
            <div class="login_member"><img src="{$skin}img/login_member.png" class="img-responsive" alt="会员登录"></div>
            <form action="" class="login_form" method="post">
                <div class="input-group input-group-lg mt10 mb10">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></div>
                    <input type="n" name="mobile" id="user" value="{$username}" class="form-control" placeholder="请输入用户名" >
                </div>
                <div class="input-group input-group-lg mb10">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                    <input type="password" class="form-control" name="password" id="pass" placeholder="请输入密码" >
                </div>
                <div class="input-group input-group-lg mb10">
                    <div class="input-group-addon"><span class=" glyphicon glyphicon-equalizer"></span></div>
                    <input type="text" class="form-control" name="captcha" id="captcha" placeholder="验证码" >
                    <span class="input-group-addon"><img src="./vcode.php"  width="90" height="24" onClick="this.src='./vcode.php?'+Math.random()" alt="验证码"></span>
                </div>
                <button class="btn btn-danger btn-block btn-lg " name="click" type="button" onclick="tologin();return false;">我要登录</button>
                <p style="text-align:left;margin-top:20px;"><a href="javascript:void();" onclick="javascript:resetpwd();return false;">找回密码</a></p>
            </form>
        </div>
    </div>
</div>
{tpl foot}
<div class="footer">
	<?php if($is_weixin){?>
	<p>客服QQ:78271214 <a href="http://wpa.qq.com/msgrd?v=3&uin=78271214&site=qq&menu=yes">在线客服</a></p>
	<?php }else{?>
	<p>客服QQ:78271214 <a href="mqqwpa://im/chat?chat_type=wpa&uin=78271214&version=1&src_type=web&web_src=oicqzone.com">在线客服</a></p>
	<?php }?>
</div>
<script language="javascript" type="text/javascript">
    function tologin(){
        var mobile=$("input[name=mobile]").val();
        var re = /^1\d{10}$/
        if (!re.test(mobile)) {
            alerts("请输入正确的手机号码");
            return false;
        }
        var pass=$("input[name=password]").val();
        if(pass.length>20 || pass.length<6){
            alerts('请保持密码长度在6-12位');
            return false;
        }
        var captcha=$("input[name=captcha]").val();
        if(captcha.length!=4){
            alerts("请输入验证码");
            return false;
        }
        $.post('{:url('users/login')}', {mobile: mobile,captcha:captcha,password:pass,cpassword:captcha}, function(data){
            if(data.status>0){
                alerts(data.message);
            }else{
                window.location='{:url('index/index')}';
                return false;
            }
        }, 'json');
        return false;
    }

    function resetpwd(msg) {
    	window.location='{:url('users/resetpwd')}';
    }

    function alerts(msg) {
        alert(msg);
    }
</script>
</body>
</html>

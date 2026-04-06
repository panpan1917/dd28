<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
     {tpl style}
    <title>{$webname}</title>
</head>
<body>
{tpl head}
<div id="main">
    <div class="container-fluid">
        <div class="row white_bg">
            <div class="login_member"><img src="{$skin}img/reg_member.png" class="img-responsive" alt="会员登录"></div>
            <form action="" class="login_form" method="post" >
                <ul class="list-group" id="list_login">
                    <li class="list-group-item">
                        <div class="input-group input-group-lg">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></div>
                            <input type="text" class="form-control" name="nickname" placeholder="请输入昵称" >
                        </div>
                    </li>
                     <li class="list-group-item">
                        <div class="input-group input-group-lg">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></div>
                            <input type="text" class="form-control" name="mobile" placeholder="请输入手机号码" >
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="input-group input-group-lg">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                            <input type="password" class="form-control" name="password" id="pass" placeholder="请输入密码" >
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="input-group input-group-lg">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                            <input type="password" class="form-control" name="cpassword" id="code" placeholder="请再次输入密码" >
                        </div>
                    </li>
                    
                     <li class="list-group-item">
                        <div class="input-group input-group-lg">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></div>
                            <input type="text" class="form-control" name="tjid" placeholder="请输入推荐人ID" value="{$tj}">
                        </div>
                    </li>


                    <li class="list-group-item" style="display: none">
                        <div class="speed">收不到短信可用【<a href="javascript:void(0);" id="speed_reg">快速注册</a>】</div>
                    </li>
                    <li class="list-group-item">
                        <div class="input-group input-group-lg">
                            <div class="input-group-addon"><span class=" glyphicon glyphicon-equalizer"></span></div>
                            <input type="text" class="form-control" name="captcha" placeholder="验证码" >
                            <span class="input-group-addon"><img src="./vcode.php" id="verify" width="90" height="24" alt="验证码" onClick="this.src='./vcode.php?'+Math.random()" ></span>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="input-group input-group-lg">
                            <div class="input-group-addon"><span class=" glyphicon glyphicon-equalizer"></span></div>
                            <input type="text" class="form-control" name="vercode" id="code" placeholder="手机码" >
                            <span class="input-group-addon"><a class="btn btn-default btn-danger btn-xs " href="javascript:;" id="getcode">发送验证码</a></span>
                        </div>
                    </li>
                </ul>
                <input class="btn btn-danger btn-block btn_login btn-lg" type="button" onclick="register()" value="我要注册">
            </form>

        </div>
    </div>
</div>
{tpl foot}
</body>


<script language="javascript" type="text/javascript">
    function register(){
        var mobile=$("input[name=mobile]").val();
        var re = /^1\d{10}$/
        if (!re.test(mobile)) {
            alerts("请输入手机号码");
            return false;
        }
        var nickname=$("input[name=nickname]").val();

        var pass=$("input[name=password]").val();
        var cpass=$("input[name=cpassword]").val();
        if(pass.length>20 || pass.length<6){
            alerts('请保持密码长度在6-12位');
            return false;
        }
        if(pass!=cpass){
            alerts("两次密码不一致");
            return false;
        }
        var captcha=$("input[name=captcha]").val();
        if(captcha.length!=4){
            alerts("请输入验证码");
            return false;
        }
        var vercode=$("input[name=vercode]").val();
        if(vercode.length!=4){
            alerts("请输入短信验证码");
            return false;
        }

        var tjid=$("input[name=tjid]").val();
        
        $.post('{:url('users/reg')}', {mobile: mobile,tjid:tjid,nickname:nickname,code:captcha,password:pass,cpassword:cpass,captcha:captcha,vercode:vercode}, function(data){
            if(data.status>0){
                alerts(data.message);
            }else{
            	window.location='{:url('index/index')}';
            }
        }, 'json');
        return false;
    }

    var times=60,cuttime;
    function getyzm(idn){
        times--;
        if(times>0&&times<60){
            $(idn).text(times+"秒后重新获取");
            $(idn).removeClass("btn-danger");
            cuttime=setTimeout(function(){getyzm(idn)},1000);
        }
        else{
            $(idn).text("获取短信验证码");
            times=60;
            $(idn).addClass("btn-danger");
            clearTimeout(cuttime);
        }
    }

    $(function(){
        $("#dl-tab li").bind({
            click:function(){
                $("#dl-tab li").removeClass("current");
                $(this).addClass("current");
                $("#login-box .login-form").hide();
                $("#login-box .login-form").eq($(this).index()).show();
                $(".yzm img").eq($(this).index()).attr("src","&1337027384");
            }
        });

        $("#getcode").bind("click",function(){
            var captcha=$("input[name=captcha]").val();
            if(captcha.length!=4){
                alerts("请输入验证码");
                return false;
            }

            var mobile=$("input[name=mobile]").val();
            var re = /^1\d{10}$/
            if (!re.test(mobile)) {
                alerts("请输入手机号码");
                return false;
            }
            //alerts("正在发送...");
            if(times==60){
                $.post('{:url('sms/index')}', {mobile: mobile,code:captcha,checkexist:1}, function(data){
                    if(data.status>0){
                        alerts(data.message);
                    }else{
                        alerts("发送成功,请查看!");
                        getyzm("#getcode");
                    }
                    $("#verify").trigger("click");
                }, 'json');
            }
            return false;
        });

    });
    function alerts(msg) {
        alert(msg);
    }
</script>

</html>


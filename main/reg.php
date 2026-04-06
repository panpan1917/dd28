<?php
header('Content-type: text/html;charset=utf-8');
include_once("inc/conn.php");
include_once("inc/function.php");

$tj=intval($_GET['tj']);
if($tj){
	$refererRootArr[] = "pcddzhan.com";
	$refererRootArr[] = "lfx28.com";
	$refererRootArr[] = "28jyz.com";
	$refererRootArr[] = "yide28.com";
	$refererRootArr[] = "bo28.com";
	$refererRootArr[] = "xdi28.com";
	$refererRootArr[] = "28aib.com";
	$refererRootArr[] = "28zhuan.com";
	$refererRootArr[] = "28zaixianqdd28.com";
	$refererRootArr[] = "28bbs.net";
	$refererRootArr[] = "28tp.com";
	$refererRootArr[] = "fyb28.com";
	$refererRootArr[] = "money28.com";
	$refererRootArr[] = "hq28.cn";
	$refererRootArr[] = "tqw28.com";
	$refererRootArr[] = "xy28xxlt.com";
	$refererRootArr[] = "qdd28.com";
	$refererRootArr[] = "wz28.net";
	$refererRootArr[] = "fqb28.com";
	$refererRootArr[] = "also28.com";
	$refererRootArr[] = "28tai.cn";
	$refererRootArr[] = "ssc88k.com";
	$refererRootArr[] = "bbsplay.cn";
	$refererRootArr[] = "daohang28.cn";
	$refererRootArr[] = "tty28.com";
	$refererRootArr[] = "bo28.cn";
	$refererRootArr[] = "xx58w.com";
	$refererRootArr[] = "28ly.net";
	$refererRootArr[] = "doudou28.com";
	$refererRootArr[] = "01002.cc";
	$refererRootArr[] = "pc828.com";
	$refererRootArr[] = "petter28.com";
	$refererRootArr[] = "hei28.com";
	$refererRootArr[] = "tihu28.com";
	$refererRootArr[] = "wenx88.com";
	$refererRootArr[] = "28ren.cn";
	$refererRootArr[] = "aoye28.com";
	$refererRootArr[] = "ll28.cn";
	$refererRootArr[] = "cnadbbs.com";
	$refererRootArr[] = "letu28.cn";
	$refererRootArr[] = "wz288.com";
	$refererRootArr[] = "28zhuan.cn";
	$refererRootArr[] = "jljd28.com";
	$refererRootArr[] = "28rwjd.com";
	$refererRootArr[] = "ssc9120.com";
	$refererRootArr[] = "guguowang.com";
	$refererRootArr[] = "lt28.cn";
	if(!in_array(getRefererRoot(),$refererRootArr))
    	cookie("tj",$tj,time()+3600);
}
$t=intval($_GET['t'])?1:0;
if($t){
    cookie('t',$t,time()+3600);
}elseif($_COOKIE['t']){
    $t=intval($_COOKIE['t']);
}
//if(is_mobile())header('location:mobile.php?c=users&a=login&tj='.$tj);
if(isset($_SESSION['usersid'])) {
	echo ChangeEncodeU2G("<script >alert('你已经登录成功，你可以退出后再注册!');window.location = '/';</script>");
	exit;
}

function cookie($var, $value='', $time=0, $path='', $domain=''){
    $_COOKIE[$var] = $value;
    if(is_array($value)){
        foreach($value as $k=>$v){
            setcookie($var.'['.$k.']', $v, $time, $path, $domain);
        }
    }else{
        setcookie($var, $value, $time, $path, $domain);
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $web_keywords ;?> - 注册</title>
<?php require_once("public/title.inc.php");?>
<script type="text/javascript" src="js/login.js"></script>
<script type="text/javascript" src="js/reg.js"></script>
</head>
<body>
<?php $_SESSION['curpage'] = "index";?>
<?php require_once("top.php");?>
<?php require_once("public/notice.inc.php");?>
<div id="login_user">
    <div class="login_center width_980 clearfix">
    <form action="#" method="post" autocomplete="off">
        <p>欢迎来到滴滴平台,已有账号? [ <a href="login.php">马上登录</a> ]</p>
        <div class="login_left">
            <div class="input-group mb20">
                <span class="input-group-addon">　账　　号 :</span>
                <input type="text" placeholder="请输入手机号" maxlength="12" id="username" autocomplete="off" class="form-control" />
            </div>
            <div class="input-group mb20">
                <span class="input-group-addon">　密　　码 :</span>
                <input type="password" placeholder="请输入密码" id="pass" autocomplete="off" class="form-control" />
            </div>
            <div class="input-group mb20">
                <span class="input-group-addon">　昵　　称 :</span>
                <input type="text" placeholder="昵称" id="nickname" autocomplete="off" class="form-control" />
            </div>
            <div class="input-group mb20">
                <span class="input-group-addon">　推荐人ID :</span>
                <input type="text" placeholder="没有可不填写" id="tjid" autocomplete="off" class="form-control" value="<?php echo $_COOKIE['tj'];?>" <?php if($_COOKIE['tj']) echo 'readonly';?> />
            </div>
            <div class="input-group mb20">
                <span class="input-group-addon">验　证　码 :</span>
                <input type="text" placeholder="验证码" id="svcode" maxlength="4" autocomplete="off" class="form-control" />
                <span class="input-group-addon"><img src="vcode.php" alt="" onclick="this.src='vcode.php?tm=' + Math.random();"  style="cursor:pointer;" /></span>
            </div>
            <div class="input-group mb20">
                <span class="input-group-addon">短信验证码 :</span>
                <input type="text" placeholder="验证码" id="vcode" maxlength="4" autocomplete="off" class="form-control" />
                <span class="input-group-addon"><a href="javascript:;" id="getcode">发送短信</a></span>
            </div>
            <a href="#" id="reg" class="btn btn-danger btn-block">立即注册</a>
            <div class="input-group mt20">
                <a href="/agreement.php" class="btn btn-default">同意《协议》
                <input type="checkbox" id="cbxSee" checked="checked"></a><input type="hidden" name="t" id="t" value="<?php echo $t;?>">
            </div>
        </div>
        <div class="login_right">
            <img src="img/regbg.png" alt="亏损返利">
        </div>
	</form>
    </div>
</div>


<script language="javascript" type="text/javascript">
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
                $(".yzm img").eq($(this).index()).attr("src","captcha.php?1337027384");
            }
        });

        $("#getcode").bind("click",function(){
            //getyzm("#getcode");
            var captcha=$("#svcode").val();
            if(captcha.length!=4){
                alerts("请输入验证码");
                return false;
            }
            var mobile=$("#username").val();
            var re = /^1\d{10}$/
            if (!re.test(mobile)) {
                alerts("请输入手机号码");
                return false;
            }
            //alerts("正在发送...");
            if(times==60){

                $.post('sms.php', {mobile: mobile,code:captcha}, function(data){
                    if(data.code>0){
                        alerts(data.data);
                    }else{
                        //console.log("zs");
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
        //console.log("s")
        alert(msg);
    }
</script>

    
    
<?php include_once("footer.php");?>
</body>
</html>

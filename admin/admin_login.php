<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录 - 后台管理系统</title>  
<link rel="stylesheet" type="text/css" href="images/admin.css" />
<script type="text/javascript" src="images/jquery-1.4.2.min.js"></script>
</head>
<body>

    <div class="login">
    	<form>
            <ul>
                <li class="uese"><input type="text" id="username"  /></li>
                <li class="pass"><input type="password" id="password"  /></li>
                <li class="yzm"><input type="text" id="vcode" maxlength=4 />&nbsp;&nbsp;<img alt="看不清请点击更换" style="width:101px; height:40px;cursor:pointer;" src="vcode.php"  onclick="this.src='vcode.php?t='+ Math.round(Math.random() * 10000)" /></li>
            </ul>
                <p><button type="button" id="login"> 登&nbsp;&nbsp;陆</button></p>
			
       </form>
    </div>	


</body>
</html>
<script type="text/javascript" language="javascript">
    $(document).ready(function() {
        $("#username").focus();
        $("#vcode").keydown(function(e){
            if(e.which == 13)
                $("#login").click(); 
        });
        $("#password").keydown(function(e){
            if(e.which == 13)
                $("#login").click(); 
        });
    });
    $("#login").click(function(){
        var username = $("#username").val();
        var pass = $("#password").val();
        var vcode = $("#vcode").val();
        if(username == "")
        {
            alert("请输入用户名!");
            $("#username").focus();
            return;
        }
        if(pass == "")
        {
            alert("请输入密码!");
            $("#password").focus();
            return;
        }
        if(vcode == "")
        {
            alert("请输入验证码!");
            return;
        }
        
        $.post("slogin.php?act=login",{username:username,pass:pass,vcode:vcode},function(ret){
            switch(ret)
            {
                case "0":
                    top.location.href = "index.php";
                    break;
                case "1":
                    alert("验证码错误!")
                    break;
                case "2":
                    alert("用户名或密码错误!");
                    break;
                default:
                    alert("未知错误!");
                    break;
            }
        });
    });
</script>


$(document).ready(function(){
   
    $("#vcode").keydown(function(e){
    	var e = e || event;
        if(e.keyCode  == 13)
            $("#gorgetlogin").click(); 
    });
    
 
    $("#gorgetlogin").click(function(){
        var vcode = $("#vcode").val();
		var pass = $("#pass").val();
		var pass1 = $("#pass1").val();
         

		if(pass == "")
        {
			$("#pass").focus();
            alert("请输入密码!");
            return;
        }
		if(pass!=pass1){
			$("#pass").focus();
			alert("两次输入的密码不一样!");
            return;
		}
		if(pass.length<6)
        {
			$("#pass").focus();
            alert("你密码过短,密码长度为6到20位长度之间!");
            return;
        }
		if(pass.length>20)
        {
			$("#pass").focus();
            alert("你密码过长,密码长度为6到20位长度之间!");
            return;
        }
		if(vcode == "" || vcode == "验证码")
        {
			$("#vcode").focus();
            alert("请输入验证码!");
            return false;
        }
        $.post("ajax.php?action=get_pwd_step3",{pass:pass, vcode:vcode},function(ret){
            switch(ret)
            {
                case "ok":
					alert("密码修改成功,你可以用新密码登录了。");
                	location.href = "/login.php";
                    break;
                case "vcode":
					$("#vcode").focus();
                    alert("验证码错误！");
                    break;
				case "pass_short":
                    alert("你密码过短,密码必须在6到20位长度之间!");
                    break;
				case "pass_long":
                    alert("你密码过长,密码必须在6到20位长度之间!");
                    break;
                case "code_err":
					$("#check_code").focus();
                    alert("短信验证码错误!");
                    break;
                case "timeout":
                    alert("你操作超时!");
                    location.href = "/forgetpass.php";
                    break;
                default:
                    alert("检验失败,未知错误!");
                    break;
            }
        });
        return false;
    });
});
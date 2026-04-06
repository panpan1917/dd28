var setInterval_time_sms=0;
var timer_sms ;
function checkTime_sms()    
{    
	setInterval_time_sms=setInterval_time_sms+1;
	var s=100-setInterval_time_sms;
	$("#get_check_sms_code").val(" "+s+'秒后可获取验证码 ');
	if(setInterval_time_sms>100){
		clearInterval(timer_sms);
		$("#get_check_sms_code").attr('disabled',false);
		$("#get_check_sms_code").val('获取手机短信验证码');
	}    
}
 
$(document).ready(function(){
   
	$("#pass1").keydown(function(e){
    	var e = e || event;
        if(e.keyCode  == 13)
            $("#safe_pwd_but").click(); 
    });
	$("#pass").keydown(function(e){
    	var e = e || event;
        if(e.keyCode  == 13)
            $("#safe_pwd_but").click(); 
    });
	
    $("#sms_code").keydown(function(e){
    	var e = e || event;
        if(e.keyCode  == 13)
            $("#back_pwd_but").click(); 
    });
	
	
    $("#safe_pwd_but").click(function(){
        var pass = $("#pass").val();
        var pass1 = $("#pass1").val();
 
        if(pass == ""  )
        {
			$("#pass").focus();
            alert("请输入安全密码!");
            return false;
        }
		if(pass1 == ""  )
        {
			$("#pass1").focus();
            alert("请再次确认密码!");
            return false;
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
		if(pass!=pass1)
        {
            alert("你两次输入的密码不一样!");
            return;
        }
        $.post("ajax.php?action=modify_safe_pwd",{pass:pass},function(ret){
            switch(ret)
            { 
                case "ok":
					alert("修改成功!");
                	getContent('smbinfo.php','mydetail');
                    break;
                case "code_err":
					$("#sms_code").focus();
						alert("短信验证码错误!");
                    break;
                case "timeout":
                    alert("你操作超时!");
                    break;
				case "pass_short":
                    alert("你安全密码过短,安全密码必须在6到20位长度之间!");
                    break;
				case "pass_long":
                    alert("你密码过长,安全密码必须在6到20位长度之间!");
                    break;
                default:
                    alert("检验失败,未知错误!");
                    break;
            }
        });
        return false;
    });
	
    
	$("#get_check_sms_code").click(function(){
		setInterval_time_sms=0;
		$.ajax({ 
			type: "post", 
			url: "ajax.php?action=get_back_pwd_sms", 
			dataType: "json", 
			cache:false,
			success: function (data) { 
				if(data.cmd=="OK"){
					$("#get_check_sms_code").attr('disabled',true);
					timer_sms= window.setInterval("checkTime_sms()",1000);
				}
				alert(data.msg);
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		});
	});
	  
	 
    $("#back_pwd_but").click(function(){
        var check_code = $("#sms_code").val();
        var vcode = $("#vcode").val();
		var type="sms";
		var get_type= $('input[name="get_type"]:checked').val();
        if(check_code == ""  )
        {
			$("#sms_code").focus();
            alert("请输入验证码!");
            return false;
        }
        $.post("ajax.php?action=get_safe_pwd_step1",{check_code:check_code},function(ret){
            switch(ret)
            {
                case "OK":
                	getContent('smbinfo.php','get_back_pwd_step2');
                    break;
                case "code_err":
					$("#sms_code").focus();
						alert("短信验证码错误!");
                    break;
                case "timeout":
                    alert("你操作超时!");
                    break;
                default:
                    alert("检验失败,未知错误!");
                    break;
            }
        });
        return false;
    });
});
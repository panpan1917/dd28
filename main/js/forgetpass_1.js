var setInterval_time_sms=0;
var setInterval_time_email=0;
var timer_sms ;var timer_email;
function checkTime_sms()    
{    
	setInterval_time_sms=setInterval_time_sms+1;
	var s=100-setInterval_time_sms;
	$("#get_check_code_sms").val(" "+s+'秒后可获取验证码 ');
	if(setInterval_time_sms>100){
		clearInterval(timer_sms);
		$("#get_check_code_sms").attr('disabled',false);
		$("#get_check_code_sms").val(' 获取验证码 ');
		$("#get_check_code_sms").css('width','136px');
		$("#get_check_code_sms").css('background-color','#F7B722');
	}    
}
function checkTime_email()    
{    
	setInterval_time_email=setInterval_time_email+1;
	var s=100-setInterval_time_email;
	$("#get_check_code_email").val(" "+s+'秒后可获取验证码 ');
	if(setInterval_time_email>100){
		clearInterval(timer_email);
		$("#get_check_code_email").attr('disabled',false);
		$("#get_check_code_email").val(' 获取验证码 ');
		$("#get_check_code_email").css('width','136px');
		$("#get_check_code_email").css('background-color','#F7B722');
	}    
}   

$(document).ready(function(){
   
    $("#vcode").keydown(function(e){
    	var e = e || event;
        if(e.keyCode  == 13)
            $("#gorgetlogin").click(); 
    });
    
	$("#get_check_code_sms").click(function(){
		setInterval_time_sms=0;
		$.ajax({ 
			type: "post", 
			url: "ajax.php?action=get_check_code_sms", 
			dataType: "json", 
			cache:false,
			success: function (data) { 
				if(data.cmd=="OK"){
					$("#get_check_code_sms").attr('disabled',true);
					$("#get_check_code_sms").css('background-color','#989795');
					$("#get_check_code_sms").css('width','160px');
					timer_sms= window.setInterval("checkTime_sms()",1000);
				}
				alert(data.msg);
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		});
	});
	 

	$("#get_check_code_email").click(function(){
		setInterval_time_email=0;
		$.ajax({ 
			type: "post", 
			url: "ajax.php?action=get_check_code_email", 
			dataType: "json", 
			cache:false,
			success: function (data) { 
				alert(data.msg);
				if(data.cmd=="OK"){
					$("#get_check_code_email").attr('disabled',true);
					$("#get_check_code_email").css('background-color','#989795');
					$("#get_check_code_email").css('width','160px');
					timer_email= window.setInterval("checkTime_email()",1000);
				}
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		});
	});
		 
	
    $("#radio_sms").click(function(){

		$(".type_sms").show();
		$(".type_email").hide();
	});
	$("#radio_email").click(function(){
		$(".type_sms").hide();
		$(".type_email").show();
	});
	
    $("#gorgetlogin").click(function(){
        var check_code = $("#check_code").val();
        var vcode = $("#vcode").val();
		var type="sms";
        if(check_code == ""  )
        {
			$("#check_code").focus();
            alert("请输入短信验证码!");
            return false;
        }
       
        if(vcode == "" || vcode == "验证码")
        {
			$("#vcode").focus();
            alert("请输入验证码!");
            return false;
        }
        $.post("ajax.php?action=get_pwd_step2",{check_code:check_code, vcode:vcode},function(ret){

            switch(ret)
            {
                case "OK":
                	location.href = "/forgetpass_2.php";
                    break;
                case "check_email":
                    alert("你的邮箱没有认证，不能用来找回密码，有需要帮助请联系客服.");
                    break;
                case "check_mobile":
                    alert("你还没有绑定手机号，不能用来找回密码，有需要帮助请联系客服.");
                    break;
                case "vcode":
					//$("#vcode").focus();
                    alert("验证码错误！");
                    break;
                case "code_err":
					//$("#check_code").focus();
                    //if(get_type=='sms'){
						alert("短信验证码错误!");
					//}else{
					//	alert("邮件验证码错误!");
					//}
                    break;
                case "verifytimes_err":
                	alert("已经输错5次了，请重发!");
                	break;
                case "timeout":
                    alert("你操作超时!");
                   // location.href = "/forgetpass.php";
                    break;
                default:
                    alert("检验失败,未知错误!");
                    break;
            }
        });
        return false;
    });
});
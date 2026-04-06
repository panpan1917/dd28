
$(document).ready(function(){
   
    $("#vcode").keydown(function(e){
    	var e = e || event;
        if(e.keyCode  == 13)
            $("#gorgetlogin").click(); 
    });
    
    $("#pass").keydown(function(e){
        if(e.keyCode  == 13)
            $("#gorgetlogin").click(); 
    });      

    $("#gorgetlogin").click(function(){
        var username = $("#username").val();
        var pass = $("#pass").val();
        var vcode = $("#vcode").val();
 
        if(username == "" || username == "请输入帐号")
        {
            alert("请输入帐号!");
            return false;
        }
       
        if(vcode == "" || vcode == "验证码")
        {
            alert("请输入验证码!");
            return false;
        }
 
        
        $.post("ajax.php?action=get_pwd_step1",{username:username, vcode:vcode},function(ret){
            switch(ret)
            {
                case "OK":
                	location.href = "/forgetpass_1.php";
                    break;
                case "fault":
                    alert("请输入帐号名!")
                    break;
                case "vcode":
                    alert("验证码错误！");
                    break;
                case "empty":
                    alert("你输入的帐号不存在!");
                    break;
                default:
                    alert("检验失败,未知错误!");
                    break;
            }
        });
        return false;
    });
});
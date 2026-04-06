
$(document).ready(function(){
   
    $("#vcode").keydown(function(e){
    	var e = e || event;
        if(e.keyCode  == 13)
            $("#login").click(); 
    });
    
    $("#pass").keydown(function(e){
        if(e.keyCode  == 13)
            $("#login").click(); 
    });    
    
    $("#reg2").click(function(){
    	window.location = "reg.php";
    }); 
    
    
    $("#login2").click(function(){
        var username = $("#username2").val();
        var pass = $("#pass2").val();
        var vcode = $("#vcode2").val();
        var referer = $("#referer2").val();
        var iskeep = "0";
        if(username == "" || username == "请输入帐号")
        {
            alert("请输入帐号!");
            return false;
        }
        if(pass == "")
        {
            alert("请输入密码!");
            return false;
        }
        if(vcode == "" || vcode == "验证码")
        {
            alert("请输入验证码!");
            return false;
        }
        if($("#cbxAutoLogin").attr("checked"))
            iskeep = "1";
        
        if(referer == "") referer = "game.php";
        
        $.post("b.php",{c:'user',a:'login',username:username,pass:pass,vcode:vcode,iskeep:iskeep},function(data){
            switch(data.status)
            {
                case "ok":
                    if(referer!=""){
                        location.href=referer;
                        return;
                    }
                	location.href = "game.php";
                    break;     
                case "fault":
                    alert("帐号或密码错误!")
                    break;
                case "vcode":
                    alert("验证码错误！");
                    break;
                case "dj_001":
                    alert("帐号被冻结，请联系客服!");
                    break;
                case "use":
                    alert("帐号正在使用，请勿重新登录!");
                    location.href="/";
                    break;
                case 'more':
                    alert("请登录次数过多,请5分钟后再试!");
                    break;
                default:
                    alert("登录失败,未知错误!");
                    break;
            }
        },'json');
        return false;
    });
    

    $("#login").click(function(){
        var username = $("#username").val();
        var pass = $("#pass").val();
        var vcode = $("#vcode").val();
        var referer = $("#referer").val();
        var iskeep = "0";
        if(username == "" || username == "请输入帐号")
        {
            alert("请输入帐号!");
            return false;
        }
        if(pass == "")
        {
            alert("请输入密码!");
            return false;
        }
        if(vcode == "" || vcode == "验证码")
        {
            alert("请输入验证码!");
            return false;
        }
        if($("#cbxAutoLogin").attr("checked"))
            iskeep = "1";
        
        if(referer == "") referer = "game.php";
        
        $.post("b.php",{c:'user',a:'login',username:username,pass:pass,vcode:vcode,iskeep:iskeep},function(data){
            switch(data.status)
            {
                case "ok":
                    if(referer!=""){
                        location.href=referer;
                        return;
                    }
                	location.href = "game.php";
                    break;     
                case "fault":
                    alert("帐号或密码错误!")
                    break;
                case "vcode":
                    alert("验证码错误！");
                    break;
                case "dj_001":
                    alert("帐号被冻结，请联系客服!");
                    break;
                case "use":
                    alert("帐号正在使用，请勿重新登录!");
                    location.href="/";
                    break;
                case 'more':
                    alert("请登录次数过多,请5分钟后再试!");
                    break;
                default:
                    alert("登录失败,未知错误!");
                    break;
            }
        },'json');
        return false;
    });
});
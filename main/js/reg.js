$(document).ready(function(){


    $("#vcode").keydown(function(e){
        if(e.which == 13)
            $("#reg").click();
    });
    $("#reg").click(function(){
        var username = $("#username").val();
        var pass = $("#pass").val();
        var nickname = $("#nickname").val();
        var vcode = $("#vcode").val();
        var tjid = $("#tjid").val();
        var code = $("#code").val();
        var t=$("#t").val();

        var re = /^1\d{10}$/
        if (!re.test(username)) {
            console.info(username)
            alerts("请输入手机号码");
            return false;
        }
        if(pass == "")
        {
            alert("请输入密码!");
            return;
        }
        if(pass.length<6)
        {
            alert("你密码过短,密码长度为6到20位长度之间!");
            return;
        }
        if(pass.length>20)
        {
            alert("你密码过长,密码长度为6到20位长度之间!");
            return;
        }
        if(vcode == "" || vcode == "验证码")
        {
            alert("请输入验证码!");
            return;
        }
        if(nickname == "" || nickname == "输入昵称")
        {
            alert("请输入昵称!");
            return;
        }
        if(!$("#cbxSee").attr("checked")){
            alert("请先阅读服务协议！");
            return;
        }


        if(tjid.length>0){
            if(isNaN(tjid) || tjid<1){
                $("#tjid").focus();
                alert("推荐人ID必须是数字!");
                return;
            }
            var result="";
            jQuery.ajax({
                type: "get",
                async: false,
                url: "/ajax.php?action=check_tjid_exist&tjid="+tjid,
                cache: false,
                success: function (asd) {
                    result=asd;
                }
            });
            if(result!="OK"){
                alert("你输入的推荐人ID不存在");
                return ;
            }
        }

        $.post("b.php?c=user&a=reg",{username:username,t:t,pass:pass,vcode:vcode,nickname:nickname,tjid:tjid,code:code},function(ret){
            switch(ret.status)
            {
                case 0:
                    alert("注册成功!");
                    location.href="/";
                    break;
                default:
                    alert(ret.message);
                    break;
            }
        },"json");
    });
});
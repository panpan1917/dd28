

<div class="com">
    <dl class="text">

        <dt>需要确认登录(安全密码)</dt>
        <dd>
            <div class="input-group">
                <span class="input-group-addon">安全密码</span>
                <input type="password" id="pass" class="form-control" t="安全密码" maxlength="20" placeholder="请输入安全密码" />
                <span class="input-group-addon">*默认跟登录密码一样</span>
            </div>
        </dd>
        <dd>
            <span><a href="javascript:getContent('smbinfo.php','get_back_pwd')">忘记安全密码</a></span>
        </dd>

        <dd><input type="button" id="login" class="btn btn-danger btn-block" value="确 认"></dd>
    </dl>
</div>
<script language="javascript">
    $(document).ready(function(){
        $("#pass").keydown(function(e){
            if(e.keyCode  == 13){
                $("#login").click();
            }
        });
        var setInterval_time=0;
        var timer_sms ;
        function checkTime()
        {
            setInterval_time=setInterval_time+1;
            var s=100-setInterval_time;
            $("#agent_send_sms").val(" "+s+"秒后再次获取验证码 ");
            if(setInterval_time>100){
                clearInterval(timer_sms);
                $("#agent_send_sms").attr("disabled",false);
                $("#agent_send_sms").val(" 获取验证码 ");
            }
        }


        $("#agent_send_sms").click(function(){
            var get_type= $("input[name='get_type']:checked").val();
            setInterval_time=0;
            $.ajax({
                type: "post",
                url: "ajax.php?action=send_agent_login_code",
                dataType: "json",
                cache:false,
                data:{type:get_type},
                success: function (data) {
                    alert(data.msg);
                    if(data.cmd=="ok"){
                        $("#agent_send_sms").attr("disabled",true);
                        $("#agent_send_sms").css("background-color","#989795");
                        $("#agent_send_sms").css("color","#FFF");
                        $("#agent_send_sms").css("width","160px");
                        timer_sms= window.setInterval("checkTime()",1000);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert("检验失败,未知错误!");
                }
            });
        });

        $("#login").click(function(){
            var pass = $("#pass").val();
            if(pass == "")
            {
                alert("请输入"+$("#pass").attr("t")+"!");
                return false;
            }
            $.ajax({
                type: "post",
                url: "ajax.php?action=login_confirm",
                dataType: "json",
                data:{pass:pass},
                cache:false,
                success: function (data) {
                    if(data.cmd=="ok"){
                        //getContent('smbinfo.php','{$act}');
                        getContents('user','onlinepay')
                    }else{
                        alert(data.msg);
                    }

                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert("检验失败,未知错误!");
                }
            });
            return false;
        });
    });
</script>
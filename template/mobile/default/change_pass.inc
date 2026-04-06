
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <title>{$webname}</title>
    <link rel="stylesheet" type="text/css" href="{$skin}css/member_basic.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}style/member_total.css" />
    {tpl style}
    <title>会员中心-{$webname}</title>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header nav_header">
            <a href="javascript:void(0);" class="navbar-brand" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left icon"></span></a>
            <a href="javascript:void(0);" class="btn refresh" onclick="window.location.reload();"><span class="glyphicon glyphicon-refresh"></span></a>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">密码修改</p>
    </div>
</nav>
<div id="main">
    {tpl info}
   
    <div class="container-fluid">
        <div class="row member_ex white_bg member_gift">
            <div class="media">
                <div class="media-left media-middle">
                    <span class="glyphicon glyphicon-calendar"></span>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">密码修改</h4>
                    <p>可修改登录密码和银行密码</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row white_bg member_mycard">
            <div class="member_pwd">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#login_pass" data-toggle="tab">登录密码</a></li>
                    <li><a href="#bank_pass" data-toggle="tab">银行密码</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="login_pass">
                    <div class="thumbnail">
                        <form action="">
                            <div class="form-group">
                                <label for="old">原登录密码:</label>
                                <input type="password" class="form-control" name="oldpass" id="old">
                            </div>
                            <div class="form-group">
                                <label for="new">新登录密码:</label>
                                <input type="password" class="form-control" name="oldpass" id="new">
                            </div>
                            <div class="form-group">
                                <label for="newt">确认新密码:</label>
                                <input type="password" class="form-control" name="oldpass" id="newt">
                            </div>
                            <button class="btn btn-danger btn-block mod" type="button">马上修改</button>
                        </form>
                    </div>
                </div>
                <div class="tab-pane" id="bank_pass">
                    <div class="thumbnail">
                        <form action="">
                            <div class="form-group">
                                <label for="old">原银行密码:</label>
                                <input type="password" class="form-control" name="oldpass" id="old2">
                            </div>
                            <div class="form-group">
                                <label for="new">新银行密码:</label>
                                <input type="password" class="form-control" name="oldpass" id="new2">
                            </div>
                            <div class="form-group">
                                <label for="newt">确认新密码:</label>
                                <input type="password" class="form-control" name="oldpass" id="newt2">
                            </div>
                            <p></p>
                            <button class="btn btn-danger btn-block mod2">马上修改</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{tpl foot}
<script type="text/javascript">
    $(function () {
        $(".mod").bind('click',function () {
           var old=$("#old").val();
            if(old.length<6){
                alert('原密码必须填,或者小于6位');
                return ;
            }
            var news =$("#new").val();
            if(news.length<6 || news.length>20){
                alert('新密码大于6位,小于20位');
                return ;
            }
            var cnew=$("#newt").val();
            if(news!=cnew){
                alert("两次密码不一致");
                return;
            }
            $.ajax({
                type:'post',
                url:'{:url('users/changePass/n/1')}',
                data:{t:1,old:old,news:news,cnew:cnew},
            dataType:'json',
            cache:false,
                success:function (data) {
                    alert(data.message);
                //if(data.status==0)
                },
            error:function(){
                alert("超时");
            }

            });

        });
        $(".mod2").bind('click',function () {
           var old=$("#old2").val();
            if(old.length<6){
                alert('原密码必须填,或者小于6位');
                return ;
            }
            var news =$("#new2").val();
            if(news.length<6 || news.length>20){
                alert('新密码大于6位,小于20位');
                return ;
            }
            var cnew=$("#newt2").val();
            if(news!=cnew){
                alert("两次密码不一致");
                return;
            }
            $.ajax({
                type:'post',
                url:'{:url('users/changePass/n/1')}',
                data:{old:old,news:news,cnew:cnew},
                dataType:'json',
                cache:false,
                success:function (data) {
                    alert(data.message);
                //if(data.status==0)
                },
            error:function(){
                alert("超时");
            }

            });

        });

    });
</script>
</body>
</html>
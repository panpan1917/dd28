
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
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
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">提现帐户</p>
    </div>
</nav>
<div id="main">
    {tpl info}
    <div class="row member_ex white_bg member_gift">
        <div class="media">
            <div class="media-left media-middle">
                <span class="glyphicon glyphicon-folder-open"></span>
            </div>
            <div class="media-body">
                <h4 class="media-heading">提现帐户</h4>
                <!--<p class="red">支付宝,微信提款限额500以下</p>-->
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <table class='table table-hover table-bordered table-striped' style="font-size:12px;">
            <tr>
                <th>收款方式</th>
                <!-- <th>金额限制</th> -->
                <th>账户</th>
                <th>操作</th>
            </tr>

           {loop $list $v}
                    <tr>
                        <td>{$v.type}</td>
                        <!-- <td>{$v.type}</td> -->
                        <td>{$v.account}({$v.name})</td>
                        <td><a href="{:url("banking/bind/id/$v->id")}">修改</a></td>
                    </tr>
               {/loop}
                <tr>
                    <td colspan='5'><a href="{:url('banking/bind')}">添加账号</a> </td>
                </tr>
        </table>
    </div>
</div>
{tpl foot}
<script type="text/javascript">
    $(function () {
        $(".set").bind('click',function () {
            var point = $("input[name=deposit]").val();
            console.info(typeof  point);
            if (parseInt(point) > 0) {

            } else {
                alert('请输入金豆数量');
            }
            $.ajax({
                    type: 'post',
                    url: '{:url('users/mybank')}',
                    data:{t:'save', point:point},
                    dataType:'json',
                    cache:false,
                success:function (data) {
                alert(data.message);
                //if(data.status==0)
            },
            error:function () {
                alert("超时");
            }

        })

        });
            $(".get").bind('click',function () {
                var point = $("input[name=take]").val();
                var pass = $("input[name=pass]").val();
                if (parseInt(point) > 0) {

                } else {
                    alert('请输入金豆数量');
                }
                if (pass.length < 6) {

                    alert('密码必须大于6位');
                }
                $.ajax({
                        type: 'post',
                        url: '{:url('users/mybank/')}',
                    data:{point:point,pwd:pass},
                dataType:'json',
                cache:false,
                    success:function (data) {
                    alert(data.message);
                    if(data.status==0){
                        location.reload();
                    }
                }

                ,
                error:function () {
                    alert("超时");
                }

            });
            });


    });
</script>
</body>
</html>
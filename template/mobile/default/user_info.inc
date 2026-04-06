
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <title>会员中心-{$webname}</title>
    <link rel="stylesheet" type="text/css" href="{$skin}css/member_basic.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}style/member_total.css" />
    {tpl style}
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header nav_header">
            <a href="javascript:void(0);" class="navbar-brand" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left icon"></span></a>
            <a href="javascript:void(0);" class="btn refresh" onclick="window.location.reload();"><span class="glyphicon glyphicon-refresh"></span></a>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">个人资料</p>
    </div>
</nav>
<div id="main">
    <div class="container-fluid">
        <div class='replace'>

            <table class='table table_list table-striped table-hover table-bordered' cellspacing='0px' style='border-collapse:collapse;'>
                <tr>
                    <td>昵　　称 :<font style="color:red;">*</font></td>
                    <td colspan='2'><input id='txtNickName' value='{$row.nickname}' class="form-control" placeholder='6-50字符以内' /></td>
                    </tr>

                <tr>
                    <td>头　　像 :</td><td>
                    <img width='60' height='60' id='imgHead' src='{if $row.head}{$row.head}{else}img/head/1_0.jpg{/if}' />
                    <select id='sltHead'>
                        <?php
                        for($i = 0; $i <= 8; $i++ ){
							if($row->head == "img/head/1_$i.jpg")
								echo  "<option value='img/head/1_$i.jpg' selected>男头像". ($i+1) ."</option>";
							else 
                        		echo  "<option value='img/head/1_$i.jpg'>男头像". ($i+1) ."</option>";
                        }
                        for($i = 0; $i <= 8; $i++ ){
							if($row->head == "img/head/0_$i.jpg")
								echo  "<option value='img/head/0_$i.jpg' selected>女头像". ($i+1) ."</option>";
							else
                        		echo  "<option value='img/head/0_$i.jpg'>女头像". ($i+1) ."</option>";
                        }
                        ?>
                    </select></td>
                    </tr>

                <tr>
                    <td>手　　机 :<font style="color:red;">*</font></td>
                    <td colspan='2'>
                    <?php if($row->is_check_mobile == 0){?>
                    <input id='txtMobile' maxlength='11' value='{$row.mobile}' class='form-control' placeholder='11位手机号码' />
                    <?php }else{?>
                    <input id='txtMobile' type='hidden' value='{$row.mobile}'>{$row.mobile}
                    <?php }?>
                    </td>
                </tr>
                <tr>
                    <td>手机状态 :</td>
                    <td colspan='2'>
                    <?php if($row->is_check_mobile == 0){?>
                     		未绑定&nbsp;&nbsp;
                    <?php }else{?>
                     		已绑定&nbsp;&nbsp;
                    <?php }?>
                    </td>
                </tr>
                
                
                <tr>
                    <td>邮　　箱 :</td>
                    <td colspan='2'>
                    <?php if($row->is_check_email == 0){?>
                    <input id='txtEmail' maxlength='50' value='{$row.email}' class='form-control' placeholder='邮箱地址' />
                    <?php }else{?>
                    <input id='txtEmail' type='hidden' value='{$row.email}'>{$row.email}
                    <?php }?>
                    </td>
                </tr>
                <tr>
                    <td>邮箱状态 :</td>
                    <td colspan='2'>
                    <?php if($row->is_check_email == 0){?>
                     		未绑定&nbsp;&nbsp;
                    <?php }else{?>
                     		已绑定&nbsp;&nbsp;
                    <?php }?>
                    </td>
                </tr>


				<tr>
                    <td>收款人名字:<font style="color:red;">*</font></td>
                    {if $row.recv_cash_name}
                    <td colspan='2'>
                    <input id='recv_cash_name' type='hidden' name='recv_cash_name' value='{$row.recv_cash_name}' />
                    {$row.recv_cash_name_show}
                    </td>
                    {else}
                    <td colspan='2'><input id='recv_cash_name' maxlength='10' name='recv_cash_name' value='' class='form-control' placeholder='填写后不能再修改' /></td>
                    {/if}
                </tr>
                
				<tr>
                    <td>身份证号码:<font style="color:red;">*</font></td>
                    {if $row.card}
                    <td colspan='2'>
                    <input id='card' type='hidden' name='card' value='{$row.card}' />
                    {$row.card}
                    </td>
                    {else}
                    <td colspan='2'><input id='card' maxlength='18' name='card' value='' class='form-control' placeholder='填写后不能再修改' /></td>
                    {/if}
                </tr>
				
                <tr>
                    <td>QQ:</td>
                    {if $row.qq}
                    <td colspan='2'>
                    <input id='txtQQ' type='hidden' name='txtQQ' value='{$row.qq}' />
                    {$row.qq_show}
                    </td>
					{else}
                    <td colspan='2'><input id='txtQQ' maxlength='20' value='{$row.qq}' class='form-control' placeholder='填写后不能再修改' /></td>
                    {/if}
                    </tr>

               

                <tr>
                    <td colspan='3'><input id='btnSubmit' type='button' class='btn btn-danger' style='margin:0 auto;' value='确定提交'></td>
                    </tr>


                </table>
            </div>
        </div>
    </div>
</div>
{tpl foot}
<script type="text/javascript">
    $(function () {


    $("#sltHead").on('change',function(){
        var pic = $(this).children('option:selected').val();
        $("#imgHead").attr("src",pic);
    });
    //修改资料
    $("#btnSubmit").on('click',function(){
        var nickname = $("#txtNickName").val();
        var head = $("#sltHead").val();
        var mobile = $("#txtMobile").val();
        var email = $("#txtEmail").val();
        var qq = $("#txtQQ").val();
        var caption = $("#txtCaption").val();
        var reMobile = /^1\d{10}$/;
        var reEmail = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
        var recv_cash_name=$("#recv_cash_name").val();
        if(nickname.length == 0)
        {
            alert("昵称不能为空!");
            return;
        }
        if(mobile != "")
        {
            if(!reMobile.test(mobile))
            {
                alert("手机号码必须1开头11位长度!");
                return;
            }
        }
        if(recv_cash_name.length == 0)
        {
            alert("收款人姓名不能为空!");
            return;
        }
        
        $.post("{:url('users/info')}",{act:"changedetail",nickname:nickname,head:head,mobile:mobile,email:email,qq:qq,caption:caption,recv_cash_name:recv_cash_name},function(ret){
            switch(ret.status)
            {
                case "0":
                    alert("修改成功!");
                    location.reload();
                    break;
                default:
                    alert(ret.message);
                    break;
            }
        },"json");
    });
    });
</script>
</body>
</html>
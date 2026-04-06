
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
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">账户绑定</p>
    </div>
</nav>
<div id="main">
    {tpl info}
    <div class="container-fluid">
        <div class="row white_bg member_mycard">
            {if $withdrawals.type==3 || !$id}
            <form action="">
                <div class="form-group member_coin">
                    <label for="desposit">选择银行(实时到账):</label>
                    <select class='form-control' id="type">
                        <option value="">选择收款方式</option>
                        <option value="中国工商银行" <?php if($withdrawals->name=='中国工商银行')echo 'selected';?>>中国工商银行</option>
                        <option value="中国农业银行" <?php if($withdrawals->name=='中国农业银行')echo 'selected';?>>中国农业银行</option>
                        <option value="中国建设银行" <?php if($withdrawals->name=='中国建设银行')echo 'selected';?>>中国建设银行</option>
                        <option value="中国银行" <?php if($withdrawals->name=='中国银行')echo 'selected';?>>中国银行</option>
                        <option value="中国民生银行" <?php if($withdrawals->name=='中国民生银行')echo 'selected';?>>中国民生银行</option>
                        <option value="招商银行" <?php if($withdrawals->name=='招商银行')echo 'selected';?>>招商银行</option>
                        <option value="兴业银行" <?php if($withdrawals->name=='兴业银行')echo 'selected';?>>兴业银行</option>
                        <option value="交通银行" <?php if($withdrawals->name=='交通银行')echo 'selected';?>>交通银行</option>
                        <option value="广发银行" <?php if($withdrawals->name=='广发银行')echo 'selected';?>>广发银行</option>
                        <option value="中信银行" <?php if($withdrawals->name=='中信银行')echo 'selected';?>>中信银行</option>
                        <option value="浦东发展银行" <?php if($withdrawals->name=='浦东发展银行')echo 'selected';?>>浦东发展银行</option>
                        <option value="邮政储蓄银行" <?php if($withdrawals->name=='邮政储蓄银行')echo 'selected';?>>邮政储蓄银行</option>
                        <option value="农村信用社" <?php if($array_drw[$id]['name']=='农村信用社')echo 'selected';?>>农村信用社</option>
                        <option value="平安银行" <?php if($array_drw[$id]['name']=='平安银行')echo 'selected';?>>平安银行</option>
                        <option value="其他" <?php if($array_drw[$id]['name']=='其他')echo 'selected';?> style="color: #f00">其他请在卡号后加 银行名称</option>
                    </select>
                </div>
                <div class="form-group member_coin">
                    <input type="text" class="form-control" value="{$withdrawals.account}" id="account" name="alipay" placeholder="收款帐号">
                </div>
                <div class="form-group member_coin">
                    <input type="text" class="form-control" disabled="disabled" value="{$cashname}" id="cashname" name="cashname" placeholder="收款人姓名(个人资料里面绑定)">
                </div>
                <div class="form-group member_coin">
                    <input type="text" class="form-control" value="{$withdrawals.province}" id="province" name="alipay" placeholder="开户省">
                </div>
                <div class="form-group member_coin">
                    <input type="text" class="form-control" value="{$withdrawals.city}" id="city" name="alipay" placeholder="开户市">
                </div>
                <div class="form-group member_coin">
                    <input type="text" class="form-control" value="{$withdrawals.bank_name}" id="bank_name" name="alipay" placeholder="开户网点名称">
                </div>


                <button type="button"  class="btn btn-danger btn-block bank" onclick="add_bank()">提交</button>
            </form>
            {/if}
            
            <!-- 
            {if $withdrawals.type==7 || !$id}
            <form action="">
                <div class="form-group member_coin">
                    <label for="desposit">借贷宝:</label>
                    <input type="text" class="form-control" value="{$withdrawals.account}" name="weichat" id="weichat" placeholder="借贷宝帐号">
                </div>
                <button type="button" class="btn btn-danger btn-block weichat" onclick="add('weichat')">提交</button>
            </form>
            {/if}
            {if $withdrawals.type==1 || !$id}
            <form action="">
                <div class="form-group member_coin">
                    <label for="desposit">支付宝:</label>
                    <input type="text" class="form-control" name="alipay" value="{$withdrawals.account}" id="alipay" placeholder="支付宝账号">
                </div>

                <button type="button" class="btn btn-danger btn-block alipay" onclick="add('alipay');">提交</button>
            </form>
            {/if}
             -->

            <input type="hidden" name="id" id="id" value="{$withdrawals.id}">
        </div>
    </div>
</div>
{tpl foot}
<script type="text/javascript">
    function add(idx){
        var acc=$("#"+idx).val();
        if(acc==''){
            alert("账户不能为空");
            return false;
        }
        var id=$("#id").val();
        $.ajax({
            type: 'post',
            url: '{:url('Banking/add_account')}',
            data: {type: idx, acc: acc,id:id},
            dataType: 'json',
            cache:false,
            success: function (data) {
                if (data.status == 2 || data.status == 2) {
                    alert('你登录超时!');
                    window.location = '{:url('users/login')}';
                    return;
                }
                alert(data.message);
                if (data.status == 0) {
                	window.location='{:url('Banking/index')}';
                } else {

                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('检验失败,未知错误!');
            }
        });
    }
    function add_bank() {
        var type=$("#type").val();
        if(type==0){
            alert("请选择银行");
            return ;
        }
        var id=$("#id").val();
        var acc=$("#account").val();
        if(acc==''){
            alert("账户不能为空");
            return false;
        }
        var province=$("#province").val();
        if(province==''){
            alert("开户行所在省份不能为空");
            return false;
        }
        var city=$("#city").val();
        if(city==''){
            alert("开户行所在市不能为空");
            return false;
        }
        var bank_name=$("#bank_name").val();
        if(bank_name==''){
            alert("开户行不能为空");
            return false;
        }
        $.ajax({
            type: 'post',
            url: '{:url('Banking/add_account')}',
            data: {type: 'bank', acc: acc,province:province,bank_type:type,city:city,bank_name:bank_name,id:id},
            dataType: 'json',
            cache:false,
            success: function (data) {
                if (data.status == 2 || data.status == 2) {
                    alert('你登录超时!');
                    window.location = '{:url('users/login')}';
                    return;
                }
                if (data.status == 0) {
                    alert(data.message);
                    window.location='{:url('Banking/index')}';
                } else {
                    alert(data.message);
                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('检验失败,未知错误!');
            }
        });
    }
</script>
</body>
</html>
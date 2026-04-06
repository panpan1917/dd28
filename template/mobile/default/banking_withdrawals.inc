
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
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">马上提现</p>
    </div>
</nav>
<div id="main">
    {tpl info}
    <div class="container-fluid">
        <div class="row white_bg member_mycard">

            <form action="">
                <div class="form-group member_coin">
                	<label for="desposit" style="color:#f00;">***每天有两次免手续费(需提现金额<?php echo FREE_CASH_FEE_RATE;?>倍流水)提现,超过两次按2%收取手续费***</label>
                	<label for="desposit">今天投注分数：{$totalscore}</label>
                    <label for="desposit">提款金额(请先存人银行,单笔最小提款额：{$min_cash} RMB):</label>
                    <input type="number" readonly class="form-control" value="" id="money" name="money" placeholder="200,300,400" style="margin-bottom: 10px;">
                    <button type="button"  class="btn btn-danger" onclick="add(100)">100</button>
                    <button type="button"  class="btn btn-danger" onclick="add(500)">500</button>
                    <button type="button"  class="btn btn-danger" onclick="add(1000)">1000</button>
                    <button type="button"  class="btn btn-danger" onclick="add(5000)">5000</button>
                    <button type="button"  class="btn btn-danger" onclick="removes()">清除</button>
                </div>
                <div class="form-group member_coin">

                    <label for="desposit">付款方式:</label>
                    <select class='form-control' id="type" name="select">
                        <option value="0">选择收款方式</option>
                        {loop $list $v}
                        <option value="{$v.id}" rel="{$v.type}">{$v.name}({$v.account})</option>
                        {/loop}
                    </select>
                </div>

                <div class="form-group member_coin">
                    <label for="desposit">安全密码:</label>
                    <input type="password" class="form-control" value="" id="password" name="alipay" placeholder="">
                </div>



                <button type="button"  class="btn btn-danger btn-block bank" onclick="withdrawals()">提交</button>
            </form>
            <input type="hidden" name="id" id="id" value="{$withdrawals.id}">
        </div>
    </div>
</div>
{tpl foot}
<script type="text/javascript">
    var rm={$info.bankpoints};
    var cashtimes = {$cashtimes};
    
    function withdrawals() {
    	
        if(rm<{$min_cash}){
            alert("您现在拥有¥"+rm+" ,小于最低提现额度!");
            return ;
        }
        var pass=$("#password").val();
        var money=$("#money").val();
        var reg = new RegExp("^[0-9]*$");
        if(money=='' || !reg.test(money)){
            alert("请输入纯数字!");
            return ;
        }
        var select=$('#type option:selected').val();
        var txtype=$('#type option:selected').attr("rel");
        /* if((txtype==0 || txtype==5) && parseInt(money)>500){
            alert("微信,支付宝只能提现500以下!");
            return false;
        } */
        console.info(select);
        console.info(txtype);
        if((select)<1){
            alert("请选择提现的账户");
            return ;
        }
        if(pass==''){
            alert("请输入密码");
            return;
        }

      	//TODO检查当天已经提现的次数
      	if(cashtimes >= 2){
			if(!confirm("您今天已经提现超过两次,将收取2%的手续费,要继续吗?")) return;
        }
        
        $.ajax({
            type: "post",
            url: "{:url('banking/withdrawals')}",
            dataType: "json",
            data:{pass:pass,money:money,select:select},
            cache:false,
            success: function (data) {
                if(data.status==0){
                    alert(data.message);
                    window.location='{:url('users/index')}';
                }else{
                    alert(data.message);
                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert(textStatus);
                alert("网络不畅，无法提交!");
				
            }
        });
    }
    function add(i) {
        var money=parseInt($("#money").val());
        if(isNaN(money))money=0;
        $("#money").val(money+parseInt(i));
    }
    function removes() {
        $("#money").val("0")
    }
</script>
</body>
</html>
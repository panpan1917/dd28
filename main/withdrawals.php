<?php
include_once("inc/conn.php");
include_once("inc/function.php");
session_check();

/* echo "<meta charset=\"utf-8\" />";
echo "<script>alert('系统临时维护');window.location='index.php';</script>";
exit; */

global $db;
$sql='select * from users where id='.$_SESSION['usersid'];
$info=$db->fetch_first($sql);

if($info['dj'] == 1){
	echo "<meta charset=\"utf-8\" />";
	echo "<script>alert('您的账号已经被冻结！');window.location='index.php';</script>";
	exit;
}


$sql = "SELECT count(*) as cnt FROM `pay_online` WHERE uid={$_SESSION['usersid']} and state=32 and to_days(now())-to_days(pay_time)=0";
$cashrow=$db->fetch_first($sql);
$cashtimes = (int)$cashrow['cnt'];


$sql = "SELECT count(*) as cnt FROM `pay_online` WHERE uid={$_SESSION['usersid']} and state=1 and to_days(now())-to_days(pay_time)=0";
$chargerow=$db->fetch_first($sql);
$chargetimes = (int)$chargerow['cnt'];


$list=get_account();




$sql = "SELECT sum(totalscore) as totalscore FROM `presslog` WHERE uid={$_SESSION['usersid']} and to_days(now())-to_days(presstime)=0";
$result = $db->query($sql);
$rs=$db->fetch_array($result);
$totalscore = (int)$rs['totalscore'];//当天总投注分数
if($chargetimes > 0){//当天有充值
	$allowcash = floor($totalscore/1000/FREE_CASH_FEE_RATE/100)*100;//当天前两次可免费提现金额
	$allowcash = number_format($allowcash);
}else{
	$allowcash = "不限";
}

?>
<ul class='nav nav-tabs' style='margin:-1px 0 0 -1px;'>
<li class="active"><a href="javascript:getContent('smbinfo.php','Withdrawals')">我要提现</a></li>
<li><a href="javascript:getContent('smbinfo.php','binding')" style="color:#f00;font-weight:bold;font-size:14px;">绑定收款帐号</a></li>
</ul>

<div class='tab-content' style='margin:10px 0 0 0;'>
<div class='tab-pane active' id='tx'>
<div class='panel panel-default'>
<div class='panel-heading'>
	提现<div style="text-align: center;">
		<span style="color:#f00;font-weight:bold;font-size:14px;">***每天有两次免手续费(需提现金额<?php echo FREE_CASH_FEE_RATE;?>倍流水)提现,超过两次按2%收取手续费***<br>今天投注分数：<?php echo number_format($totalscore);?></span>
		
		</div>
</div>
<div class='panel-body'>
<table class='table table-striped table-hover table-bordered' style="font-size:12px;">
<tr>
<td>银行余额：</td>
<td colspan='2'>￥<?php if($dj==1){echo '0';}else{echo floor($info['back']/1000);}?>,可提现:￥<?php if($dj==1 or floor($info['back']/1000)<100){echo '0';}else{echo floor($info['back']/1000);}?> (请先存入银行) </td>
<td></td>
</tr>
<tr>
<td>提款金额：</td>
<td colspan='2'>
	<input type='text' id="money" class='form-control' value='0' disabled style='margin:0 0 5px 0;' />
	<input type="button" class='btn btn-danger ff'  value='100元' /> 
	<input type="button" class='btn btn-danger ff'  value='500元' /> 
	<input type="button" class='btn btn-danger ff'  value='1000元' /> 
	<input type="button"  value='5000元' class='btn btn-danger ff' /> 
	<input type="button"  value='10000元' class='btn btn-danger ff' /> 
	<input type="button"  value='30000元' class='btn btn-danger ff' />  
	<input type="button"  value='清除' class='btn btn-danger ff' />
</td>
<td><span style="color:#f00;">单笔最小提款额：<?php echo MIN_CASH;?> RMB</span></td>
</tr>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".ff").click(function () {
                var money=parseFloat($("#money").val());
                if(isNaN(money))money=0;
                var val=$(this).val();
                if(val=="清除")return $("#money").val('');
                $("#money").val(parseFloat(val.replace('元',''))+money);
            });
        });
    </script>

<tr>
<td>收款方式：</td>
<td colspan='3'>
                        <table class='table table-hover table-striped table-bordered'>
                            <tr>
                                <th>选择</th>
                                <th>收款方式</th>
                                <th>收款人姓名</th>
                                <th>收款帐号</th>
                            </tr>
                            <?php
                            if (count($list)>0){
                                $alipay=0;
                                $bank=0;
                                foreach ($list as $k=>$v){
									if($v['type']==2 or $v['type']==7)continue;
                                    if($v['type']==1)$alipay=1;
                                    if($v['type']==3)$bank=1;
                                    ?>
                                    <tr>
                                        <td><input rel="<?php echo $v['type']?>" type="radio" value="<?php echo $v['id'];?>" name="select" /></td>
                                        <td><?php echo cz_type($v['type']);?></td>
                                        <td><?php echo $info['recv_cash_name'];?></td>
                                        <td><?php echo $v['account'];?>(<?php echo $v['name'];?>)</td>
                                    </tr>
                                <?php }}else{?>
                                <tr>
                                    <td colspan='4'>暂无数据</td>
                                </tr>
                            <?php }?>
                        </table>
						<span style="font-size:12px;">温馨提示</span><br>
    <span style="color:#f00;font-size:20px;"> *** 只支持银行卡提现，银行卡 实时到账 *** </span>
                    </td>
</tr>
    <tr>
        <td>安全密码：</td>
        <td colspan='3'><input type='password' id="password" class='form-control' /></td>
    </tr>
<tr>
<td colspan='4'><button type="button" onclick="withdrawals();" class='btn btn-danger'>确定提款</button></td>
</tr>
</table>
</div>
</div>

<div class='pay_recored' style="font-size:12px;">
<p>提现记录</p>

<table class='table table-striped table-hover table-bordered' style="font-size:12px;">
<tr>
<th>金额</th>
<th>提现时间</th>
<th>打款时间</th>
<th>状态</th>
<th>收款方式</th>
<th>收款帐号</th>
<th style="display: none">收款人姓名</th>
<!--<th>操作</th>-->
</tr>
    <?php
    $sql='select p.*,w.type,w.name as bankname from pay_online p left join withdrawals w on w.uid=p.uid and p.cz_type=w.id where p.state in(30,31,32) and p.uid='.$_SESSION['usersid'].' order by p.id desc limit 20';
    $res=$db->query($sql);
	$result=[];
    while ($row=$db->fetch_array($res)){
		$result[]=['id'=>$row['id'],'state'=>$row['state']];
    ?>
        <tr>
            <td><?php echo $row['rmb'];?></td>
            <td><?php echo $row['add_time'];?></td>
            <td><?php echo $row['pay_time'];?></td>
            <td><?php if($row['state']==30){ echo '审核中';}elseif($row['state']==31){echo '审核未通过,请联系客服';}elseif($row['state']==32){echo '审核通过';}?></td>
            <td><?php echo cz_type($row['type']) ;?></td>
            <td><?php echo $row['account'];?>(<?php echo $row['bankname'];?>)</td>
            <td style="display: none"><?php echo $row['name'];?></td>
        </tr>
    <?php

    }
    ?>
<tr>

</tr>
</table>
</div>
</div>
</div>
<script type="text/javascript">


    var rm=<?php echo floor($info['back']/1000);?>;
    var cashtimes = <?php echo $cashtimes;?>;
    function withdrawals() {
        
        if(rm<<?php echo MIN_CASH ;?>){
            alert("您现在拥有¥"+rm+" ,小于最低提现额度!");
            return ;
        }
        var pass=$("#password").val();
        var money=$("#money").val();
        var reg = new RegExp("^[0-9]*$");
        if(!reg.test(money)){
            alert("请输入数字!");
            return ;
        }
        var select=$('input[name="select"]:checked').val();
        var txtype=$('input[name="select"]:checked').attr("rel");
        /* if((txtype==1) && parseInt(money)>500){
            //alert("支付宝只能提现500以下!");
            //return false;
        } */
            console.info(txtype);
        if(typeof(select)=='undefined'){
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
            url: "b.php?c=banking&a=withdrawals",
            dataType: "json",
            data:{pass:pass,money:money,select:select},
            cache:false,
            success: function (data) {
                if(data.status==0){
                    alert(data.message);
                    getContent('smbinfo.php','Withdrawals');
                }else{
                    alert(data.message);
                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("检验失败,未知错误!");
            }
        });
    }
</script>
<?php
$order_id=str_check($_REQUEST['orderid']);

if(is_numeric($order_id)) {
    $sql = 'select p.*,rt.is_pic,rt.name as cz_name,rt.remarks,rt.marks,rt.acc_name,rt.id as paytypeid from pay_online p left join recharge_type rt on rt.id=p.cz_type where p.uid=\'' . $_SESSION['usersid'] . '\' and p.order_id=\'' . $order_id . '\'';
    $info=$db->fetch_first($sql);
    if($info['is_pic']==0){
        $user=explode('#',$info['remarks']);
    }else{
        $url='/pic/'.$info['cz_type'].'.png';
    }
}
?>
<script type="text/javascript" src="js/clipboard.min.js"></script>
<script type="text/javascript" src="js/jquery.qrcode.min.js"></script>
<script type='text/javascript'  >
$(document).ready(function(){
		var paytypeid = "<?php echo $info['paytypeid'];?>";
		<?php if(in_array($info['paytypeid'],[5003,5004,9999,5012,6040,6041,9502,7000,7001])){?>
			$('#qrcode').qrcode("<?php echo $info['qrcode'];?>");//二维码或快捷
		<?php }else if(in_array($info['paytypeid'],[5000,5001,5002])){?>
			//个人二维码;
		<?php }else{?>
			alert("收款卡号随时变动,请仔细核对!");
		<?php }?>

		var clipboard1 = new Clipboard('#btnCopyBankCard');
		var clipboard2 = new Clipboard('#btnCopyAccName');
    });
</script>


<div class='panel panel-default'>
    <div class='panel-heading'>客服直充</div>
    <div class='panel-body'>
        <p style='font-size:18px;'><strong>充值流程:</strong>　<strong>1.创建订单　>></strong>　<strong style='color:#f00;'>2.付款</strong></p>
        <div class='pay_money'>
            <table class='table table-striped table-hover table-bordered'>
                <tr>
                    <td style="width:200px;">付款方式：</td>
                    <td><div style="float:left;margin-left:8px;font-size:18px;"><?php echo ($info['cz_name']);?></div></td>

                    <?php if($info['is_pic']){?>
                    <td rowspan='6' style="color:#f00;font-size:16px;">
                    	<div id="qrcode">
                        	<?php if(in_array($info['paytypeid'],[5003,5004,9999,5012,6040,6041,9502,7000,7001])){?>
                        		<!-- 二维码或快捷 -->
                        	<?php }else{
                        		if(in_array($info['paytypeid'],[9500,9501])) $url=$info['qrcode'];
                        	?>
                        		<img src='<?php echo $url;?>' style='width:300px; height:300px; padding:0; margin:0;'/>
                        	<?php }?>
                    	</div> 
                    </td>
                    <?php }?>
                </tr>
                
                
                <?php if(!$info['is_pic']){?>
                <tr>
                    <td>收款帐号：</td>
                    <td><div style="float:left;margin-left:8px;font-size:18px;" id="accountid"><?php echo $user[1];?></div><div style="width:60px;float:right;margin-right:2px;"><input type='button' id='btnCopyBankCard' class='btn btn-danger' data-clipboard-action="copy" data-clipboard-target="#accountid" value='复制' /></div></td>
                </tr>
                <tr>
                    <td>收款人：</td>
                    <td><div style="float:left;margin-left:8px;font-size:18px;" id="accountname"><?php echo $user[0];?></div><div style="width:60px;float:right;margin-right:2px;"><input type='button' id='btnCopyAccName' class='btn btn-danger' data-clipboard-action="copy" data-clipboard-target="#accountname" value='复制' /></div></td>
                </tr>
                <?php }?>
                
                
                <tr>
                    <td>支付备注：</td>
                    <td><div style="float:left;margin-left:8px;"><?php echo $info['remarks'];?></div></td>
                </tr>
                <tr>
                    <td>充值金额：</td>
                    <td><div style="float:left;margin-left:8px;"><?php echo $info['rmb'];?></div></td>
                </tr>
                <tr>
                    <td>充值订单号：</td>
                    <td><div style="float:left;margin-left:8px;"><?php echo $info['order_id'];?></div></td>
                </tr>
                <tr>
                    <td>订单状态：</td>
                    <td><div style="float:left;margin-left:8px;"><?php if($info['state']==0){echo '未完成';}elseif($info['state']==1){echo '已支付';}else{echo '支付失败';}?></div></td>
                </tr>
                <tr>
                    <td>付款人姓名:</td>
                    <td><div style="float:left;margin-left:8px;"><?php echo $info['name'];?></div></td>
                </tr>
                <tr>
                    <td colspan='<?php if($info['is_pic']) echo "3";else echo "2";?>'>
                    <a href="javascript:;" onClick="getContent('smbinfo.php?orderid=<?php echo $order_id?>','recharge_order_pay');"  class='btn btn-danger btn-block;' >刷新订单状态</a>
                    <!-- 网关/快捷支付确认 -->
					<?php if(in_array($info['paytypeid'],[9999,5012])){?>
						<?php if(substr(trim($info['qrcode']),0,1)=="<"){
									echo "<a onclick=\"javascript:document.forms[0].submit();\"  class=\"btn btn-danger btn-block;\" target=\"_blank\">确认支付</a>";
									preg_match_all("#<form (.*?)</form>#",str_replace("\t","",str_replace("\r","",str_replace("\n","",trim($info['qrcode'])))),$data);
									echo $data[0][0];
							  }else{
						?>
									<a href="<?php echo $info['qrcode'];?>"  class='btn btn-danger btn-block;' target="_blank">确认支付</a>
						<?php }?> 
					<?php }?>
					<!-- 网关/快捷支付确认 -->
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
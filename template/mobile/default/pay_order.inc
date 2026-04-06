
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="applicable-device" content="mobile">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <link rel="stylesheet" type="text/css" href="{$skin}css/member_basic.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}style/member_total.css" />
    {tpl style}
    <script type="text/javascript" src="js/clipboard.min.js"></script>
    <script type="text/javascript" src="js/jquery.qrcode.min.js"></script> 
    <title>会员中心-{$webname}</title>
    <style>
	*{-webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; }
	</style>
</head>
<script type='text/javascript'  >
$(document).ready(function(){
		var paytypeid = "<?php echo $order_info['paytypeid'];?>";
		<?php if(in_array($order_info['paytypeid'],[5003,5004,9999,5012,6040,6041,9502,7000,7001])){?>
			$('#qrcode').qrcode("<?php echo $order_info['qrcode'];?>");//二维码或快捷
		<?php }else if(in_array($order_info['paytypeid'],[5000,5001,5002])){?>
			//个人二维码;
		<?php }else{?>
			alert("收款卡号随时变动,请仔细核对!");
		<?php }?>

		//var clipboard1 = new Clipboard('#btnCopyBankCard');
		//var clipboard2 = new Clipboard('#btnCopyAccName');

		var clipboard1 = new Clipboard('#btnCopyBankCard', {
	        text: function () {
	            return $("#accountid").html();
	        }
	    });

		var clipboard2 = new Clipboard('#btnCopyAccName', {
	        text: function () {
	            return $("#accountname").html();
	        }
	    });
    });
</script>

<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header nav_header">
            <a href="{:url('users/index')}" class="navbar-brand" ><span class="glyphicon glyphicon-chevron-left icon"></span></a>
            <a href="javascript:void(0);" class="btn refresh" onclick="window.location.reload();"><span class="glyphicon glyphicon-refresh"></span></a>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">在线充值</p>
    </div>
</nav>
<div id="main">
    {tpl info}
    <div class='panel panel-default'>
    <div class='panel-heading'>客服直充</div>
    <div class='panel-body'>
        <p style='font-size:18px;'><strong>充值流程:</strong>　<strong>1.创建订单　>></strong>　<strong style='color:#f00;'>2.付款</strong></p>
        <div class='pay_money'>
            <table class='table table-striped table-hover table-bordered'>
                <tr>
                    <td>收款银行(方式)：</td>
                    <td><?php echo $order_info['cz_name'];?></td>
                    <?php if($order_info['is_pic']){?>
                    <td rowspan='5' style="color:#f00;font-size:16px;">
		        	<div id="qrcode">
		                <?php if(in_array($order_info['paytypeid'],[5003,5004,9999,5012,6040,6041,9502,7000,7001])){?>
		                	<!-- 二维码或快捷 -->
		                <?php }else{
		                	if(in_array($order_info['paytypeid'],[9500,9501])) $url=$order_info['qrcode'];
		                ?>
		                	<img src='<?php echo $url;?>' style='width:300px; height:300px; padding:0; margin:0;'/>
		                <?php }?>
		            </div>
                    </td>
                    <?php }?>
                </tr>
                
            	<?php if(!$order_info['is_pic']){?>
                <tr>
                    <td>收款帐号：</td>
                    <td><div id="accountid" style="cursor:pointer;"><?php echo $order_info['user'][1];?></div><div style="width:60px;float:right;margin-right:2px;"><input type='button' id='btnCopyBankCard' class='btn btn-danger' data-clipboard-action="copy" data-clipboard-target="#accountid" value='复制' /></div></td>
                </tr>
                <tr>
                    <td>收款人：</td>
                    <td><div id="accountname" style="cursor:pointer;"><?php echo $order_info['user'][0];?></div><div style="width:60px;float:right;margin-right:2px;"><input type='button' id='btnCopyAccName' class='btn btn-danger' data-clipboard-action="copy" data-clipboard-target="#accountname" value='复制' /></div></td>
                </tr>
            	<?php }?>
                
                
                <tr>
                    <td>订单金额：</td>
                    <td><?php echo $order_info['rmb'];?></td>
                </tr>
                <tr>
                    <td>订单编号：</td>
                    <td><?php echo $order_info['order_id'];?></td>
                </tr>
                <tr>
                    <td>订单状态：</td>
                    <td><?php if($order_info['state']==0){echo '未完成';}elseif($order_info['state']==1){echo '已支付';}else{echo '支付失败';}?></td>
                </tr>
                <tr>
                    <td>付款人姓名:</td>
                    <td><?php echo $order_info['name'];?></td>
                </tr>
                <tr>
                    <td colspan='<?php if($order_info['is_pic']) echo "3";else echo "2";?>'>
                    <a href="javascript:;" onClick="location.reload();"  class='btn btn-danger btn-block;' >刷新订单状态</a>
                    <!-- 网关/快捷/H5支付确认 -->
                    <?php if(in_array($order_info['paytypeid'],[9999,5012,9502,7001])){?>
						<?php if(substr(trim($order_info['qrcode']),0,1)=="<"){
									echo "<a onclick=\"javascript:document.forms[0].submit();\"  class=\"btn btn-danger btn-block;\" target=\"_blank\">确认支付</a>";
									preg_match_all("#<form (.*?)</form>#",str_replace("\t","",str_replace("\r","",str_replace("\n","",trim($order_info['qrcode'])))),$data);
									echo $data[0][0];
							  }else{
						?>
									<a href="<?php echo $order_info['qrcode'];?>"  class='btn btn-danger btn-block;' target="_blank">确认支付</a>
						<?php }?>
					<?php }?>
					<!-- 网关/快捷/H5支付确认 -->
                    </td>
                </tr>
            </table>
        </div>
        
    </div>
</div>
    </div>
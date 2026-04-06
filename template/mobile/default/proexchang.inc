
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <title>兑换中心-{$webname}</title>
    <link rel="stylesheet" type="text/css" href="{$skin}css/member_basic.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}style/member_total.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}style/product.css" />
    {tpl style}
</head>

<script type="text/javascript">
var cart_id='{$card_id}';
var setInterval_time=0;
var timer , timer_sms;
function checkTime()    
{    
	setInterval_time=setInterval_time+1;
	var s=100-setInterval_time;
	$("#send_but").val('请等'+s+'秒');
	if(setInterval_time>100){
		clearInterval(timer_sms);
		$("#send_but").attr('disabled',false);
		$("#send_but").val('获取验证码');
		$("#send_but").css('width','136px');
		$("#send_but").css('background-color','#F7B722');
	}    
}

$(document).ready(function(){
	
	/* $(".send_type").find("input").change( function() {
		var type= $('input[name="get_type"]:checked').val();
		if(type=='sms'){
			 $(".channel_sms").show();
		}else{
			$(".channel_sms").hide();
		}
	}); */

	
	$("#send_but").click(function(){
		$("#send_but").attr('disabled',true);
		//var channel= $("input[name='channel_radio']:checked").val();
		var get_type= 'sms';//$('input[name="get_type"]:checked').val();
		setInterval_time_email=0;
		$.ajax({ 
			type: "post", 
			url: "/ajax.php?action=send_card_code", 
			dataType: "json", 
			data:{type:get_type},//,channel:channel
			cache:false,
			success: function (data) { 
				alert(data.msg);
				$("#send_but").attr('disabled',false);
				if(data.cmd=="ok"){
					$("#send_but").css('background-color','#989795');
					$("#send_but").css('color','#FFF');
					$("#send_but").css('width','160px');
					timer_sms= window.setInterval("checkTime()",1000);
				}
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
		$("#send_but").attr('disabled',false);
			} 
		});
	});

	
	$('#user_exchange_num').keyup(function(){
		var inpVal = $(this).val();
		if(isNaN(inpVal) || inpVal<1){ 
			$(this).val('');
		}
	});
	
 $("#user_Exchange").click(function(){
		var num = $("#user_exchange_num").val();
		var pass= $("#sage_pass").val();
		var vcode= $("#vcode").val();
		if(num == ""){
			$("#user_exchange_num").focus();
			alert("请输入兑换数量!");
            return;
		}
		if(isNaN(num) || num<1){ 
			$("#user_exchange_num").focus();
			$("#user_exchange_num").val('');
			alert("请输入兑换数量!");
            return;
		}
		if(pass == ""){
			$("#sage_pass").focus();
			alert("请输入你的安全密码!");
            return;
		}
		if(vcode == ""){
			$("#vcode").focus();
			alert("请输入验证码!");
            return;
		}
		$(this).attr('disabled',true);
		$.ajax({ 
			type: "post", 
			url: "/ajax.php?action=users_exchange", 
			dataType: "json", 
			data:{num:num,cart_id:cart_id,pass:pass,vcode:vcode},
			cache:false,
			success: function (data) {
				$("#user_Exchange").attr('disabled',false);
				if(data.cmd=="notlogin"){
					alert("你登录超时!");
					window.location = "/mobile.php?c=users&a=login";
					return;
				}
				if(data.cmd=="pass"){
					alert("安全密码错误!");
					$("#sage_pass").focus();
					return;
				}
				if(data.cmd=="vcode"){
					alert("请输入验证码!");
					$("#vcode").focus();
					return;
				}
				if(data.cmd=="vcode_not"){
					alert("请输入请先获取验证码!");
					$("#send_but").focus();
					return;
				}
				if(data.cmd=="vcode_time"){
					alert("验证码超时，5分钟内有效!");
					$("#send_but").focus();
					return;
				}
				if(data.cmd=="vcode_err"){
					alert("验证码错误!");
					$("#vcode").focus();
					return;
				}
				if(data.cmd=="ok"){
					$("#sage_pass").val('');
					$("#vcode").val('');
					alert(data.msg);
					location.reload();
				}else{
					alert(data.msg);
					window.location = "/mobile.php?c=gift&a=giftlist";
					return;
				}
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
					$("#user_Exchange").attr('disabled',false);
			} 
		});
	});
});
</script>

<body>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header nav_header">
            <a href="javascript:void(0);" class="navbar-brand" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left icon"></span></a>
            <a href="javascript:void(0);" class="btn refresh" onclick="window.location.reload();"><span class="glyphicon glyphicon-refresh"></span></a>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">兑换中心</p>
    </div>
</nav>

<div id="main">

    <div class="container-fluid" style="padding:0px;">
		
		<div id="Content">
		  <div class="exch-border exch-itemrow">
		    <div style="margin:5px 5px;">
		      <div class="dbfootxh2">
		        <table width="100%">
		          <tbody>
		            <tr>
		              <td valign="top"><img id="product_img" src="/images/gift/product{$card_id}.png" style="width:100px;height:100px;"></td>
		              <td valign="top" style=" color:#333; font-size:14px;text-align:left;"><h3 class="proshowtitle" style="font-size: 20px;"><?php echo $cart_list["cart_".$card_id]["name"];?></h3>
		                <table class="tblevel">
		                  <tbody>
		                    <tr>
		                      <td style="text-align:right;width:40%;" height="40px">兑换价格：</td>
		                      <td style="text-align:left;"><span class="bdspan"><?php echo $my_price;?></span></td>
		                    </tr>
		                    
		                    <tr>
		                      <td style="text-align:right;" height="40px">兑换数量：</td>
		                      <td style="text-align:left;"><input name="user_exchange_num" type="text" value="1" id="user_exchange_num" class="form-control" style="width:100px; height:35px;"></td>
		                    </tr>
		                    
		                    <tr>
		                      <td style="text-align:right;" height="40px">安全密码：</td>
		                      <td style="text-align:left;"><input name="sage_pass" type="password" value="" id="sage_pass" class="form-control" maxlength="20" style="width:100px; height:35px;"></td>
		                    </tr>
		                    
		                    <tr>
		                      <td style="text-align:right;" height="40px">验证码：</td>
		                      <td style="text-align:left;"><input name="vcode" type="text" value="" id="vcode" class="form-control" maxlength="6" style="width:100px; height:35px;"></td>
		                    </tr>
		                    
		                    <tr>
		                      <td style="text-align:right;" height="40px"></td>
		                      <td style="text-align:left;"><input name="send_but" type="button" value="获取验证码" id="send_but" class="inputpadding btn" style="background-color:#B73C3C;color:#fff"></td>
		                    </tr>
		                    
		                  </tbody>
		              </table></td>
		            </tr>
		            <tr>
		              <td height="40px"></td>
		              <td></td>
		            </tr>
		            <tr>
		              <td></td>
		              <td align="left"><a href="javascript:void(0)" class="pro" id="user_Exchange">立即兑换</a></td>
		            </tr>
		          </tbody>
		        </table>
		      </div>
		    </div>
		  </div>
		</div>
    
		
		
    </div>
    
</div>


{tpl foot}
</body>
</html>
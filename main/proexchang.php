<?php
	include_once("inc/conn.php");
	include_once("inc/function.php"); 
	session_check();
	$usersid=$_SESSION['usersid'];
	/* $sql = "SELECT reward_discount FROM usergroups
 		WHERE (SELECT experience FROM  users WHERE id={$usersid})
		 BETWEEN creditslower AND creditshigher LIMIT 1";
	$result = $db->query($sql);
	$users = $db->fetch_row($result);
	if(empty($users)){
		header("Location: login.php");
		exit();
	}
	$reward_discount=$users[0]; */
	$reward_discount=$_SESSION['reward_discount']; 
	
	$sql = "select is_check_mobile,is_check_email,email from users where id = '{$usersid}' limit 1";
	$result = $db->query($sql);
	$users = $db->fetch_row($result);
	if(empty($users)){
		header("Location: login.php");
		exit();
	}
	if($users[0]==0){// || $users[1]==0 || $users[2]==""
		echo '<meta charset="utf-8"></meta>';
		echo ChangeEncodeU2G('<script language="javascript">
				alert("需要先绑定手机才能兑换!");//"为了让体验卡能发到你邮箱，需要先绑定手机和验证邮箱才能兑换!"
				window.location = "/member.php";
			 </script>');
		exit();
	}
	
	
 	$card_id=1;
	if(isset($_GET["id"])){
		$card_id=intval($_GET["id"]);
	}
	if($card_id<1){
		$card_id=1;
	}
	if($card_id>9){
		$card_id=9;
	}
	
	
	//未达到两倍流水要收取2%手续费
	//$sql='select ifnull(sum(tzpoints),0) as tzpoints from game_day_static where to_days(now())=to_days(time) and uid='.$usersid;
	$sql='select ifnull(sum(tzpoints),0) as tzpoints from game_day_static where uid='.$usersid;
	$point=$db->fetch_first($sql);
	if($reward_discount <= 1.00 && $point['tzpoints'] < $cart_list["cart_".$card_id]["price"][3] * 2){
		$reward_discount = 1.02;
	}
	
	$my_price=	 ($cart_list["cart_".$card_id]["price"][3]*$reward_discount);
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $web_name ;?> - 兑换中心</title>
<?php require_once("public/title.inc.php");?>
<link rel="stylesheet" type="text/css" href="css/1/proexchang.css" />
<script type="text/javascript"  >
var cart_id='<?php echo $card_id;?>';

var setInterval_time=0;
var timer ,timer_sms;
function checkTime()    
{    
	setInterval_time=setInterval_time+1;
	var s=100-setInterval_time;
	$("#send_but").val('请等'+s+'秒');
	if(setInterval_time>100){
		clearInterval(timer_sms);
		$("#send_but").attr('disabled',false);
		$("#send_but").val(' 获取验证码 ');
		$("#send_but").css('width','136px');
		$("#send_but").css('background-color','#F7B722');
	}    
}

$(document).ready(function(){
	$("#send_but").click(function(){
		var get_type= $('input[name="get_type"]:checked').val();
		var op = "exchange";
		setInterval_time_email=0;
		$.ajax({ 
			type: "post", 
			url: "ajax.php?action=send_card_code", 
			dataType: "json", 
			cache:false,
			data:{type:get_type,op:op},
			success: function (data) { 
				alert(data.msg);
				if(data.cmd=="ok"){
					$("#send_but").attr('disabled',true);
					$("#send_but").css('background-color','#989795');
					$("#send_but").css('color','#FFF');
					$("#send_but").css('width','160px');
					timer= window.setInterval("checkTime()",1000);
				}
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
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
		$.ajax({ 
			type: "post", 
			url: "ajax.php?action=users_exchange", 
			dataType: "json", 
			cache:false,
			data:{num:num,cart_id:cart_id,pass:pass,vcode:vcode},
			success: function (data) { 
				if(data.cmd=="notlogin"){
					alert("你登录超时!");
					location.href = "/login.php";
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
				if(data.cmd=="vcode_err"){
					alert("验证码错误!");
					$("#vcode").focus();
					return;
				}
				if(data.cmd=="ok"){
					$("#sage_pass").val('');
					$("#vcode").val('');
				}
				alert(data.msg);
				window.location = "/gift.php";
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		});
	});
});
</script>
</head>
<?php $_SESSION['curpage'] = "gift";?>
<?php require_once("top.php");?>
<?php require_once("public/notice.inc.php");?>


<div id="active">
    <div class="active_list width_980">
            <div class="panel-body">
                <div class="tab-content tab_active">
                    <div class="tab-pane active" id="ing">
                            <div class="panel panel-default">
                                <div class="panel-heading">兑换奖品</div>
                                <div class="panel-body">
									<div id="Content">
									<div class="dbfootxh2">
									<table style="wdth:100%;">
									<tbody><tr><td width="255px"><img width="250px" height="236px" src="images/gift/product<?php echo $card_id;?>.png"></td>
									<td valign="top" style=" color:#333; font-size:14px;">
									<h3 class="proshowtitle"><?php echo $cart_list["cart_".$card_id]["name"];?></h3>
									<table style="wdth:480px;">
									
									<tbody><tr><td width="105" height="30">你的兑换价格：</td><td width="203"><span class="bdspan"><?php echo $my_price;?></span></td>
									<td width="156" ></td>
									</tr>
									<tr><td height="30">你要兑换数量：</td>
									<td><input name="user_exchange_num" type="text" value="1" id="user_exchange_num" class="inputpadding" style=" width:100px; height:25px;"></td>
									<td ></td>
									</tr>
									
									
									<tr><td height="30">你的安全密码：</td>
									<td><input name="sage_pass" type="password" value="" id="sage_pass" class="inputpadding" maxlength="20" style=" width:100px;height:25px;"></td>
									<td ></td>
									</tr>
									
									<tr> <td height="40" colspan="3">
										<label for="radio_sms"><input name="get_type" type="radio" class="none_float" id="radio_sms" value="sms" checked="checked"  	  />短信验证</label>
									  	<input name="send_but" type="button" value="&nbsp;发送验证码&nbsp;" id="send_but" class="inputpadding"  >
									</td>
									    </tr>
									
									<tr>
									<td height="30">验证码：</td>
									<td><input name="vcode" type="text" value="" id="vcode" class="inputpadding" maxlength="6" style=" width:100px;height:25px;"></td>
									<td ></td>
									</tr>
									
									 
									<tr>
									<td height="50" colspan="2" ><div align="left">
									  <a href=" javascript:void(0)" class="pro" id="user_Exchange">立即兑换</a></div></td>
									<td ></td>
									</tr>
									 
									</tbody></table>
									</td>
									</tr>
									<tr>
									<td colspan="2">
									 
									 
									</td>
									</tr>
									</tbody></table>   
									</div>
					
									</div>

                                </div>
                            </div>
                    </div>
            </div>
        </div>
   </div>
</div>

<?php include_once("footer.php"); ?>
</body>
</html>

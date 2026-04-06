<?php
	include_once("inc/conn.php");
	include_once("inc/function.php"); 
	session_check();
	$usersid=$_SESSION['usersid'];
	
	$reward_discount=$_SESSION['reward_discount'];
	
 	$card_id=2;
	if(isset($_GET["a"])){
		$card_id=(int)$_GET["a"];
	}
	if($card_id<1){
		$card_id=2;
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
 
	$my_price =	 ($cart_list["cart_".$card_id]["price"][3]*$reward_discount);
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $web_name ;?> - 兑换中心</title>
<?php require_once("public/title.inc.php");?>
<link rel="stylesheet" type="text/css" href="css/1/product.css" />
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
                                <div class="panel-heading">兑换详情</div>
                                <div class="panel-body">
									<div id="Content">
									<div class="dbfootxh2">
									<table style="wdth:100%;">
									<tbody><tr><td width="255px"><img width="250px" height="236px" src="images/gift/product<?php echo $card_id;?>.png" /></td><td valign="top" style=" color:#333; font-size:14px;">
									<h3 class="proshowtitle"><?php echo $cart_list["cart_".$card_id]["name"];?></h3>
									<table>
									
									
									    
									     <tbody><tr><td align="right" height="30px"><a class="lvspan0">&nbsp;</a>-<a class="lvspan1">&nbsp;</a>兑换价格：</td><td><span class="bdspan"><?php echo $cart_list["cart_".$card_id]["price"][0];?></span></td><td></td></tr>
									
									
									     <tr><td align="right" height="30px"><a class="lvspan2">&nbsp;</a>-<a class="lvspan5">&nbsp;</a>兑换价格：</td><td><span class="bdspan"><?php echo $cart_list["cart_".$card_id]["price"][1];?></span></td><td></td></tr>
									
									
									     <tr><td align="right" height="30px"><a class="lvspan6">&nbsp;</a>兑换价格：</td><td><span class="bdspan"><?php echo $cart_list["cart_".$card_id]["price"][2];?></span></td><td></td></tr>
									
										 
									
									     <tr><td align="right" height="30px"><a class="lvspan7">&nbsp;</a>兑换价格：</td><td><span class="bdspan"><?php echo $cart_list["cart_".$card_id]["price"][3];?></span></td><td></td></tr>
									
									     <tr><td align="right" height="30px" colspan="3" style="color:red">未达到两倍流水要收取2%手续费</td></tr>
									
									<tr><td height="30px" style="text-align:right">我的兑换价格：</td><td><span class="bdspan" style="color:red"><?php echo $my_price;?></span></td><td></td></tr>
									</tbody></table>
									</td>
									</tr>
									<tr><td></td><td align="left"><a href="proexchang.php?id=<?php echo $card_id;?>" class="pro">立即兑换</a></td></tr>
									<tr>
									<td colspan="2">
									<div class="detail-tabcont" style="display: block;">
									                        <strong style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74); font-size: 14px">奖品寄送方式：<br/>
									</strong><span style="line-height: 24px; background-color: rgb(255,254,246); font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">卡密只能给代理回收，不能自行使用，如果发现不能兑换可以联系首页客服QQ<br>
									</br>
									</span><strong style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74); font-size: 14px">奖品兑换流程：<br/>
									</strong><span style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)"><br/>
									1. 奖品价格</span><span class="re1" style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(225,0,0)">已经包含邮寄费用在内</span><span style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">，您无须另行支付。兑奖前请确认您的帐户中有足够数量的巴豆！</span><br style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)" />
									<span style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">2. 在您要兑奖的奖品页面点击“立即兑换”按钮，提交您的兑奖申请！</span><br style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)" />
									<span style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">3. 确认您的奖品邮寄地址、联系电话正确无误后提交兑奖申请 ！</span><br style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)" />
									<span style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">4. 实物奖品将在您的兑奖确认后的2-5工作日内发出(奖品状态您可通过“</span><a target="_blank" href="http://www.19dou.com/Member/Exchange"><span style="color: rgb(255,0,0)"><strong>账户中心-兑奖记录</strong></span></a><span style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">”查询)！</span><br style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)" />
									<span style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">5. 兑奖中心所有奖品颜色均为随机发送, 敬请谅解！</span><br style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)" />
									<span style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">6. 奖品受供货商库存影响，会有缺货情况，如有缺货，客服会取消兑奖，退还乐豆。</span>
									                    </div>
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

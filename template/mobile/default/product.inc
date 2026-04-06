
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
		                      <td style="text-align:right;width:40%;" height="30px">LV0&nbsp;-LV1&nbsp;兑换价格：</td>
		                      <td style="text-align:left;"><span class="bdspan"><?php echo $cart_list["cart_".$card_id]["price"][0];?></span></td>
		                    </tr>
		                    <tr>
		                      <td style="text-align:right;" height="30px">LV2&nbsp;-LV5&nbsp;兑换价格：</td>
		                      <td style="text-align:left;"><span class="bdspan"><?php echo $cart_list["cart_".$card_id]["price"][1];?></span></td>
		                    </tr>
		                    <tr>
		                      <td style="text-align:right;" height="30px">LV6&nbsp;兑换价格：</td>
		                      <td style="text-align:left;"><span class="bdspan"><?php echo $cart_list["cart_".$card_id]["price"][2];?></span></td>
		                    </tr>
		                    <tr>
		                      <td style="text-align:right;" height="30px">LV7&nbsp;兑换价格：</td>
		                      <td style="text-align:left;"><span class="bdspan"><?php echo $cart_list["cart_".$card_id]["price"][3];?></span></td>
		                    </tr>
		                    
		                    <tr><td style="text-align:right;" height="30px" colspan="2">未达到两倍流水要收取2%手续费</td></tr>
		                    
		                    <tr>
		                      <td style="text-align:right;" height="30px">我的兑换价格：</td>
		                      <td style="text-align:left;"><span class="bdspan" style="color:red"><?php echo $my_price;?></span></td>
		                    </tr>
		                  </tbody>
		              </table></td>
		            </tr>
		            <tr>
		              <td></td>
		              <td align="left"><a href="{:url('gift/proexchang')}&card_id={$card_id}" class="pro">立即兑换</a></td>
		            </tr>
		            <tr>
		              <td colspan="2">
		              	<div class="detail-tabcont" style="display: block;text-align:left;"> 
		              	  <strong style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74); font-size: 14px">奖品寄送方式：<br></strong>
		                  <span style="line-height: 24px; background-color: rgb(255,254,246); font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">卡密只能给代理回收，不能自行使用，如果发现不能兑换可以联系首页客服QQ 
		                  <br>
		                  <br>
		                  </span>
		                  <strong style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74); font-size: 14px">奖品兑换流程：<br></strong>
		                  <span style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">1. 奖品价格</span>
		                  <span class="re1" style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(225,0,0)">已经包含邮寄费用在内</span>
		                  <span style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">，您无须另行支付。兑奖前请确认您的帐户中有足够数量的余额！</span><br style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">
		                  <span style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">2. 在您要兑奖的奖品页面点击“立即兑换”按钮，提交您的兑奖申请！</span><br style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">
		                  <span style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">3. 确认您的奖品邮寄地址、联系电话正确无误后提交兑奖申请 ！</span><br style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">
		                  <span style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">4. 实物奖品将在您的兑奖确认后的2-5工作日内发出(奖品状态您可通过“</span><a target="_blank" href="/member.php"><span style="color: rgb(255,0,0)"><strong>账户中心-兑奖记录</strong></span></a><span style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">”查询)！</span><br style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">
		                  <span style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">5. 兑奖中心所有奖品颜色均为随机发送, 敬请谅解！</span><br style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">
		                  <span style="line-height: 24px; font-family: Arial, Helvetica, sans-serif, 宋体; color: rgb(74,74,74)">6. 奖品受供货商库存影响，会有缺货情况，如有缺货，客服会取消兑奖，退还金额。</span>
		                 </div>
		               </td>
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
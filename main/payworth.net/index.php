<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>网关测试</title>
<style type="text/css">
<!--
.STYLE1 {
	font-family: "微软雅黑";
	font-size: x-large;
}
-->
</style>
</head>

<body marginheight="0" marginwidth="0">
    <form action="Action/PayAction.php" method="post">
<table width="40%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="93" colspan="2" align="center"><span class="STYLE1">网关测试DEMO</span></td>
  </tr>
  <tr>
    <td width="50%" height="34" align="right">银行：</td>
    <td width="50%">
	<select name="defaultbank" id="defaultbank" >
				<option value="ALIPAY" selected="selected">支付宝</option>	
				<option value="WXPAY">微信</option>
				<option value="CMB">招商银行</option>
				<option value="ICBC">中国工商银行</option>
				<option value="CCB">中国建设银行</option>
				<option value="BOC">中国银行</option>
				<option value="SPDB">浦发银行</option>
				<option value="ABC">中国农业银行</option>
				<option value="CMBC">民生银行</option>
				<option value="CIB">兴业银行</option>
				<option value="BOCM">交通银行</option>
				<option value="CEB">光大银行</option>
				<option value="BCCB">北京银行</option>		
				<option value="PAYH">平安银行</option>
				<option value="CGB">广发银行</option>
				<option value="CITIC">中信银行</option>
				<option value="SHBANK">上海银行</option>
				<option value="HXB">华夏银行</option>
				<option value="PSBC">中国邮政储蓄银行</option>
      </select>
	</td>
  </tr>
  <tr>
    <td height="36" align="right">支付金额：</td>
    <td><input name="total_fee" type="text" id="total_fee" /></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><label>
      <input type="submit" name="Submit" value="提交" />
    </label></td>
  </tr>
</table>
    </form>
</body>
</html>

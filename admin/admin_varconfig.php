<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>系统管理--变量设置</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta name="GENERATOR" content="MSHTML 6.00.3790.4275">
	<link rel="stylesheet" type="text/css" href="images/css_body.css">
	<link rel="stylesheet" type="text/css" href="images/window.css">
	<link rel="Stylesheet" type="text/css" href="images/jquery_ui.css" />
	<script type="text/javascript" src="images/jquery.js"></script> 
	<script type="text/javascript" src="images/jquery_ui.js"></script>
</head>
<body>
	<div class="bodytitle">
		<div class="bodytitleleft"></div>
		<div class="bodytitletxt">变量设置</div>
	</div>
	<!-- 变量设置 -->
    <div class="categorylist" id="div_GameConfig">
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >	
				<tr bgcolor="#FFFFFF">
					<td width="200">兑奖身份验证</td>
				  	<td width="200">
				  		<input type="checkbox" id="cbxCheckCard" />
				  	</td>
					<td></td>
				</tr>
                <tr bgcolor="#FFFFFF">
					<td >兑奖分下限</td>
				  	<td><input type="text" id="txtPrizePoints" style="width:100px" />
				  	</td>
					<td></td>
				</tr>
                <tr bgcolor="#FFFFFF">
					<td>注册奖励分</td>
				  	<td><input type="text" id="txtRegPoints" style="width:100px" />
				  	</td>
					<td></td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>登录奖励经验</td>
				  	<td><input type="text" id="txtLoginExp" style="width:100px" />
				  	</td>
					<td></td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>身份验证奖励</td>
				  	<td><input type="text" id="txtAuthen" style="width:100px" />
				  	</td>
					<td></td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>推广限制人数量</td>
				  	<td><input type="text" id="txtLinkNum" style="width:100px" />
				  	</td>
					<td></td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>推广奖励分</td>
				  	<td><input type="text" id="txtLinkPoints" style="width:100px" />
				  	</td>
					<td></td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>每日兑换次数</td>
				  	<td><input type="text" id="txtExchangeNum" style="width:100px" />
				  	</td>
					<td></td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>vip每日兑换次数</td>
				  	<td><input type="text" id="txtExchangeNumVip" style="width:100px" />
				  	</td>
					<td></td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td><font color="red">机器人平衡分</font></td>
				  	<td><input type="text" id="txtRobotBalance" style="width:100px" />
				  	</td>
					<td>当机器人分数小于此数时自动加分到此数，半小时平衡一次</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td><font color="red">兑奖需达到经验</font></td>
				  	<td><input type="text" id="txtExchangeMinExp" style="width:100px" />
				  	</td>
					<td>兑奖点卡时需要达到最小经验值</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td colspan="3" align="center">SMTP邮局设置</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>SMTP服务器</td>
				  	<td><input type="text" id="txtSMTPServer" style="width:180px" />
				  	</td>
					<td>如:vip.163.com</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>SMTP服务器端口</td>
				  	<td><input type="text" id="txtSMTPPort" style="width:180px" />
				  	</td>
					<td>一般为25</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>SMTP用户邮箱</td>
				  	<td><input type="text" id="txtSMTPUserMail" style="width:180px" />
				  	</td>
					<td>如abc@vip.163.com</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>SMTP发送者昵称</td>
				  	<td><input type="text" id="txtSMTPNickName" style="width:180px" />
				  	</td>
					<td>如时时彩票</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>SMTP用户帐号</td>
				  	<td><input type="text" id="txtSMTPUser" style="width:180px" />
				  	</td>
					<td>如abc</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>SMTP用户密码</td>
				  	<td><input type="text" id="txtSMTPPass" style="width:180px" />
				  	</td>
					<td></td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>SMTP邮件类型</td>
				  	<td><input type="text" id="txtMialType" style="width:180px" />
				  	</td>
					<td>HTML/TXT</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td></td>
				  	<td colspan="2"><input type="button" value="保存更改" id="btnSaveConfig" class="btn-1" /></td>
				</tr>                
			</table>
	</div>
</form>    
</body>
<script type= "text/javascript" language ="javascript">
    $(window.parent.document).attr("title","变量设置");
    $(document).ready(function() {
        GetVarConfig();
        //***************************************************************
		//保存设置
		$("#btnSaveConfig").click(function(){
			SaveVarConfig();
		}); 
	});
    //***************************************************************************************************
    function GetVarConfig()
    {
		var data = "action=get_varconfig";
		SendAjax(data);
    }
    //保存设置
    function SaveVarConfig()
    {
        var data = "action=save_varconfig";
        var PrizePoints = $.trim($("#txtPrizePoints").val());
        var RegPoints = $.trim($("#txtRegPoints").val());
        var LoginExp = $.trim($("#txtLoginExp").val());
        var Authen = $.trim($("#txtAuthen").val());
        var LinkNum = $.trim($("#txtLinkNum").val());
        var LinkPoints = $.trim($("#txtLinkPoints").val());
        var ExchangeNum = $.trim($("#txtExchangeNum").val());
        var ExchangeNumVip = $.trim($("#txtExchangeNumVip").val());
        var RobotBalance = $.trim($("#txtRobotBalance").val());
        var ExchangeMinExp = $.trim($("#txtExchangeMinExp").val());
		var SMTPServer = $.trim($("#txtSMTPServer").val());
		var SMTPPort = $.trim($("#txtSMTPPort").val());
		var SMTPUserMail = $.trim($("#txtSMTPUserMail").val());
		var SMTPNickName = $.trim($("#txtSMTPNickName").val());
		var SMTPUser = $.trim($("#txtSMTPUser").val());
		var SMTPPass = $.trim($("#txtSMTPPass").val());
		var MialType = $.trim($("#txtMialType").val());
        var CheckCard = "0";
        
        if($("#cbxCheckCard").is(":checked"))
        	 CheckCard = "1";
        if(PrizePoints == "" || isNaN(PrizePoints) || PrizePoints < 0) 
        {
			alert("兑奖分下限必须为数字!");
			return false;
        }
        if(RegPoints == "" || isNaN(RegPoints) || RegPoints < 0)
        {
			alert("注册奖励分必须为数字!");
			return false;
        }
        if(LoginExp == "" || isNaN(LoginExp) || LoginExp < 0)
        {
			alert("登录奖励经验必须为数字!");
			return false;
        }
        if(Authen == "" || isNaN(Authen) || Authen < 0)
        {
			alert("身份验证奖励必须为数字!");
			return false;
        }
        if(LinkNum == "" || isNaN(LinkNum) || LinkNum < 0)
        {
			alert("推广限制人数量必须为数字!");
			return false;
        }
        if(LinkPoints == "" || isNaN(LinkPoints) || LinkPoints < 0)
        {
			alert("推广奖励分必须为数字!");
			return false;
        }
        if(ExchangeNum == "" || isNaN(ExchangeNum) || ExchangeNum < 0)
        {
			alert("每日兑换次数必须为数字!");
			return false;
        }  
        if(ExchangeNumVip == "" || isNaN(ExchangeNumVip) || ExchangeNumVip < 0)
        {
			alert("vip每日兑换次数必须为数字!");
			return false;
        } 
        if(RobotBalance == "" || isNaN(RobotBalance) || RobotBalance < 0)
        {
			alert("机器人平衡分必须为数字!");
			return false;
        } 
        if(ExchangeMinExp == "" || isNaN(ExchangeMinExp) || ExchangeMinExp < 0)
        {
			alert("兑奖需达到经验必须为数字!");
			return false;
        } 
        data += "&CheckCard=" + parseInt(CheckCard) 
        		+ "&PrizePoints=" + parseInt(PrizePoints) 
        		+ "&RegPoints=" + parseInt(RegPoints)
        		+ "&LoginExp=" + parseInt(LoginExp)
        		+ "&Authen=" + parseInt(Authen)
        		+ "&LinkNum=" + parseInt(LinkNum)
        		+ "&LinkPoints=" + parseInt(LinkPoints)
        		+ "&ExchangeNum=" + parseInt(ExchangeNum)
        		+ "&ExchangeNumVip=" + parseInt(ExchangeNumVip)  
        		+ "&RobotBalance=" + parseInt(RobotBalance)
        		+ "&ExchangeMinExp=" + parseInt(ExchangeMinExp)
        		+ "&SMTPServer=" + SMTPServer
        		+ "&SMTPPort=" + SMTPPort
        		+ "&SMTPUserMail=" + SMTPUserMail
        		+ "&SMTPNickName=" + SMTPNickName
        		+ "&SMTPUser=" + SMTPUser
        		+ "&SMTPPass=" + SMTPPass
        		+ "&MialType=" + MialType;
        SendAjax(data);
    }
    //***************************************************************************************************
	//ajax处理
	function SendAjax(SendData)
	{
		var PostURL = "ssystem.php";
		$.ajax({
		       type: "POST",
		       async:false,
		       dataType: "json",
		       url: PostURL,
		       data: SendData,
		       success: function(data) {DataSuccess(data);}
		});
	}
	//数据成功后
	function DataSuccess(json)
	{ 
		var tbody = "";
		var pageinfo = "";
        var InfoType = "";
		$.each(json,function(i,item){
			if(i == 0)
			{
                InfoType = item.cmd;
				switch(item.cmd)
				{
					case "get_varconfig":
                        break;
					default:
						alert(item.msg);
						return;
						break;
				}
			}
			else
			{
				if(InfoType == "get_varconfig")
                {
                	if(item.CheckCard == "0")
                		$("#cbxCheckCard").attr("checked",false);
                	else
                		$("#cbxCheckCard").attr("checked",true);
                    $("#txtPrizePoints").val(item.PrizePoints);
                    $("#txtRegPoints").val(item.RegPoints);
                    $("#txtLoginExp").val(item.LoginExp);
                    $("#txtAuthen").val(item.Authen); 
                    $("#txtLinkNum").val(item.LinkNum); 
                    $("#txtLinkPoints").val(item.LinkPoints); 
                    $("#txtExchangeNum").val(item.ExchangeNum); 
                    $("#txtExchangeNumVip").val(item.ExchangeNumVip); 
                    $("#txtRobotBalance").val(item.RobotBalance);
                    $("#txtExchangeMinExp").val(item.ExchangeMinExp);
                    $("#txtSMTPServer").val(item.SMTPServer);
                    $("#txtSMTPPort").val(item.SMTPPort);
                    $("#txtSMTPUserMail").val(item.SMTPUserMail);
                    $("#txtSMTPNickName").val(item.SMTPNickName);
                    $("#txtSMTPUser").val(item.SMTPUser);
                    $("#txtSMTPPass").val(item.SMTPPass);
                    $("#txtMialType").val(item.MialType);
                 }
			}

		});
	}
    
</script>

</html>

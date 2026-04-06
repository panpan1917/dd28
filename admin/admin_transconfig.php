<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理--转账参数设置</title>
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
		<div class="bodytitletxt">平台参数设置</div>
	</div>
	<!-- 转账参数设置 -->
    <div class="categorylist" id="div_GameConfig">
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >	
				<tr bgcolor="#FFFFFF">
					<td width="200">转账手续费</td>
				  	<td width="200"><input id="txtTransOdds" type="text" style="width:100px" />%</td>
					<td>0-100</td>
				</tr>
                <tr bgcolor="#FFFFFF">
					<td >转账最小值</td>
				  	<td><input type="text" id="txtTransPointMin" style="width:100px" />分
				  	</td>
					<td>至少转多少分</td>
				</tr>
                <tr bgcolor="#FFFFFF">
					<td>手续费免除帐号</td>
				  	<td><input type="text" id="txtTransExcuseID" style="width:100px" />
				  	</td>
					<td>只能填写一个ID</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td><font color="#FF0000">所有游戏停止开关</font></td>
				  	<td><input id="cbxGameShutDown" type="checkbox" >停止所有游戏
				  	</td>
					<td>请谨慎使用,勾选保存后将立即生效</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td><font color="#FF0000">游戏停止原因</font></td> 
				  	<td><input type="text" id="txtShutdownReason" style="width:200px" />
				  	</td>
					<td>只能填写一个ID</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>手动下注间隔</td> 
				  	<td><input type="text" id="txtPressInterval" style="width:50px" />秒
				  	</td>
					<td>两次下注间隔,0为无限制</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>单次发红包金额上限</td> 
				  	<td><input type="text" id="txtRedBagSigleMax" style="width:50px" />元
				  	</td>
					<td></td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>单次发红包个数上限</td> 
				  	<td><input type="text" id="txtRedBagCntMax" style="width:50px" />个
				  	</td>
					<td></td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>单日发红包金额上限</td> 
				  	<td><input type="text" id="txtRedBagDayMax" style="width:50px" />元
				  	</td>
					<td></td>
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
    $(window.parent.document).attr("title","平台参数设置");
    $(document).ready(function() {
        GetTransConfig();
        //***************************************************************
		//保存设置
		$("#btnSaveConfig").click(function(){
			SaveTransConfig();
		}); 
	});
    //***************************************************************************************************
    function GetTransConfig()
    {
		var data = "action=get_transconfig";
		SendAjax(data);
    }
    //保存设置
    function SaveTransConfig()
    {
        var data = "action=save_transconfig";
        var Odds = $.trim($("#txtTransOdds").val());
        var PointMin = $.trim($("#txtTransPointMin").val());
        var ExcuseID = $.trim($("#txtTransExcuseID").val());
        var ShutdownReason =  $.trim($("#txtShutdownReason").val()); 
        var PressInterval =  $.trim($("#txtPressInterval").val());
        var RBSingleMax =  $.trim($("#txtRedBagSigleMax").val());
        var RBCntMax =  $.trim($("#txtRedBagCntMax").val());
        var RBDayMax =  $.trim($("#txtRedBagDayMax").val());
        var GameOpenFlag = "0"; 
        if(Odds == "" || isNaN(Odds) || Odds < 0 || Odds > 100) 
        {
			alert("转账手续费必须0-100!");
			return false;
        }
        if(PointMin == "" || isNaN(PointMin))
        {
			alert("转账最小值必须为数字!");
			return false;
        }
        if($("#cbxGameShutDown").is(":checked")) 
        	GameOpenFlag = "1";
        if(parseInt(PressInterval) < 0)
        {
			alert("手动下注间隔不能为负数");
			return false;
        }
        
        data += "&odds=" + parseInt(Odds) + "&pointmin=" + parseInt(PointMin) + "&excuseid=" + parseInt(ExcuseID)
        		+ "&pressinterval=" + parseInt(PressInterval) + "&rbsinglemax=" + parseInt(RBSingleMax) + "&rbcntmax=" + parseInt(RBCntMax)
        		+ "&rbdaymax=" + RBDayMax
        		+ "&gameopenflag=" + GameOpenFlag + "&shutdownreason=" + ShutdownReason;
        SendAjax(data);
    }
    //***************************************************************************************************
	//ajax处理
	function SendAjax(SendData)
	{
		var PostURL = "sgamemanage.php";
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
					case "get_transconfig":
                        break;
					default:
						alert(item.msg);
						return;
						break;
				}
			}
			else
			{
				if(InfoType == "get_transconfig")
                {
                    $("#txtTransOdds").val(item.bank_trans_odds);
                    $("#txtTransPointMin").val(item.bank_trans_min);
                    $("#txtTransExcuseID").val(item.bank_trans_excuse_id); 
                    $("#txtShutdownReason").val(item.game_shutdown_reason);
                    $("#txtPressInterval").val(item.game_press_interval); 
                    $("#txtRedBagSigleMax").val(item.redbag_single_max); 
                    $("#txtRedBagCntMax").val(item.redbag_cnt_max); 
                    $("#txtRedBagDayMax").val(item.redbag_day_max); 
                    
                    if(item.game_open_flag == "0")
                    	$("#cbxGameShutDown").attr("checked",false);
                    else
                        $("#cbxGameShutDown").attr("checked",true);
                 }
			}

		});
	}
    
</script>

</html>

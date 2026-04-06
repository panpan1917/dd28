<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理--游戏设置</title>
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
		<div class="bodytitletxt">游戏设置</div>
	</div>
	<!-- 游戏设置 -->
    <div class="categorylist" id="div_GameConfig">
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >				
				<tr bgcolor="#FFFFFF">
					<td colspan="8">
						选择游戏
						<select id = "sltGCGameList">
                        </select>
					</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td width="125">记录号</td>
				  	<td width="150"><label id="lblGCRecID"></label></td>
					<td width="110">游戏类型</td>
				  	<td width="150"><label id="lblGCGameType"></label></td>
					<td width="110">游戏前缀</td>
			  	  	<td><label id="lblGCTablePrefix"></label></td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>游戏名称</td>
				  	<td><input type="text" id="txtGCGameName" style="width:100px" /></td>
					<td>用户奖励经验</td>
				  	<td><input id="txtGCJLExp" type="text" style="width:100px" /></td>
					<td>奖励经验上限</td>
			  	  <td><input id="txtJLMaxExp" type="text" style="width:80px" /></td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>抽取投注万分比</td>
				  	<td><input id="txtGCGoSamples" type="text" style="width:100px" /></td>
					<td>vip奖励经验</td>
				  	<td><input id="txtGCJLExpVIP" type="text" style="width:100px" /></td>
					<td>vip奖励经验上限</td>
			  	  <td><input id="txtJLMaxExpVIP" type="text" style="width:80px" /></td>
				</tr>
                <tr bgcolor="#FFFFFF">
					<td>开奖延迟</td>
				  	<td><input type="text" id="txtGCKjDelay" style="width:100px" />
				  	  秒</td>
					<td>系统赢概率</td>
				  	<td><input type="text" id="txtGCSysWinOdds" style="width:100px" />
			  	    0-100</td>
					<td>系统每天赢上下限</td>
		  	    <td><input id="txtGCSysWinMin" type="text" style="width:80px" />
至
  <input id="txtGCSysWinMax" type="text" style="width:80px" /></td>
				</tr>
                <tr bgcolor="#FFFFFF">
					<td>投注截至</td>
				  	<td><input type="text" id="txtGCTzClose" style="width:100px" />
				  	  秒</td>
					<td>奖励经验投注下限</td>
				  	<td><input type="text" id="txtGCTzExp" style="width:100px" />
				  	  分</td>
					<td>投注上下限</td>
                    <td>
                        <input id="txtGCPressMin" type="text" style="width:80px" />
                        至
                        <input id="txtGCPressMax" type="text" style="width:80px" />                    </td>
				</tr> 
				<tr bgcolor="#FFFFFF">
					<td>排除号码数</td>
				  	<td><input type="text" id="txtGCNoOpenNum" style="width:100px" /></td>
					<td>排除最大下注的号码数量</td>
				  	<td></td>
					<td>下盘开最小下注?</td>
                    <td>
                        <label id="lblNextOpenFlag"></label>
                        <input type="button" value="设置" id="btnGCOpenPressMin" class="btn-1" />
                        <input type="button" value="刷新" id="btnGCRefresh" class="btn-1" />
                    </td>
				</tr> 
				<tr bgcolor="#FFFFFF">
					<td><font color="#FF0000">单个游戏停止开关</font></td>
				  	<td colspan="5"><input id="cbxGameShutDown" type="checkbox" >停止该游戏(请谨慎使用,勾选保存后将立即生效)</td>
				</tr> 
				<tr bgcolor="#FFFFFF">
					<td><font color="#FF0000">游戏停止原因</font></td>
				  	<td colspan="5"><input type="text" id="txtShutdownReason" style="width:200px" /></td>
				</tr> 
				<tr bgcolor="#FFFFFF">
					<td>标准赔率</td>
				  	<td colspan="5"><input type="text" id="txtGCStdOdds" style="width:600px" />以|分隔</td>
				</tr> 
				<tr bgcolor="#FFFFFF">
					<td>标准投注额</td>
				  	<td colspan="5"><input type="text" id="txtGCSTdPress" style="width:600px" />以,分隔</td>
				</tr> 
				<tr bgcolor="#FFFFFF">
					<td>扣分模式(只针对急速)</td>
				  	<td colspan="5"><input type="text" id="txtGCGameModel" style="width:600px" />分数,百分比,例:100,20|1000,15</td>
				</tr> 
				<tr bgcolor="#FFFFFF">
					<td></td>
				  	<td colspan="5"><input type="button" value="保存更改" id="btnGCSaveConfig" class="btn-1" /></td>
				</tr>                
			</table>
	</div>
</form>    
</body>
<script type= "text/javascript" language ="javascript">
    $(window.parent.document).attr("title","游戏设置");
    $(document).ready(function() {
        GetGameListOption();
        GetGameConfig();
        //***************************************************************
		//保存设置
		$("#btnGCSaveConfig").click(function(){
			SaveGameConfig();
		});
		//游戏列表变更
		$("#sltGCGameList").change(function(){
            GetGameConfig();
        }); 
        //设置
		$("#btnGCOpenPressMin").click(function(){
            var data = "action=set_nextopenflag&gametype=" + $("#sltGCGameList").val();
            SendAjax(data);
            GetGameConfig();
        }); 
        //刷新
        $("#btnGCRefresh").click(function(){
            GetGameConfig();
        }); 
	});
    //***************************************************************************************************
    //取游戏列表
    function GetGameListOption()
    {
		var data = "action=get_gamelist_option";
		SendAjax(data);
    }
    //取游戏配置
    function GetGameConfig()
    {
		var gameType = $("#sltGCGameList").val();
		if(gameType == "") return;
		var data = "action=get_gameconfig&gametype=" + gameType;
		SendAjax(data);
    }
    //保存设置
    function SaveGameConfig()
    {
        var data = "action=save_gameconfig";
        var GameType = $.trim($("#lblGCGameType").html());
        var GameName = $.trim($("#txtGCGameName").val());
        var JLExp = $.trim($("#txtGCJLExp").val());
        var MaxExp = $.trim($("#txtJLMaxExp").val());
        var GoSamples = $.trim($("#txtGCGoSamples").val());
        var JLExpVIP = $.trim($("#txtGCJLExpVIP").val());
        var MaxExpVIP = $.trim($("#txtJLMaxExpVIP").val());
        var KjDelay = $.trim($("#txtGCKjDelay").val());
        var SysWinOdds = $.trim($("#txtGCSysWinOdds").val());
        var SysWinMin = $.trim($("#txtGCSysWinMin").val());
        var SysWinMax = $.trim($("#txtGCSysWinMax").val());
        var NoOpenNum = $.trim($("#txtGCNoOpenNum").val());
		var TzClose = $.trim($("#txtGCTzClose").val());
		var GCTzExp = $.trim($("#txtGCTzExp").val());
        var PressMin = $.trim($("#txtGCPressMin").val());
        var PressMax = $.trim($("#txtGCPressMax").val());
        var StdOdds = $.trim($("#txtGCStdOdds").val());
        var STdPress = $.trim($("#txtGCSTdPress").val());
        var GameModel = $.trim($("#txtGCGameModel").val()); 
        var GameOpenFlag = "0";
        var ShutdownReason =  $.trim($("#txtShutdownReason").val());
        
        if(GameType == "") 
        {
			alert("参数错误!");
			return false;
        }
        if(GameName == "")
        {
			alert("请输入游戏名称!");
			return false;
        }
        if(JLExp == "" || isNaN(JLExp))
        {
			alert("用户奖励经验必须为数字!");
			return false;
        }
        if(MaxExp == "" || isNaN(MaxExp))
        {
			alert("奖励经验上限必须为数字!");
			return false;
        } 
        if(GoSamples == "" || isNaN(GoSamples))
        {
			alert("抽取投注万分比必须为数字!");
			return false;
        }
        if(JLExpVIP == "" || isNaN(JLExpVIP))
        {
			alert("vip奖励经验必须为数字!");
			return false;
        }
        if(MaxExpVIP == "" || isNaN(MaxExpVIP))
        {
			alert("vip奖励经验上限必须为数字!");
			return false;
        }
        if(KjDelay == "" || isNaN(KjDelay))
        {
			alert("开奖延迟必须为数字!");
			return false;
        }
        if(SysWinOdds == "" || isNaN(SysWinOdds))
        {
			alert("系统赢概率必须为数字!");
			return false;
        }
        if(SysWinMin == "" || isNaN(SysWinMin))
        {
			alert("系统每天赢下限必须为数字!");
			return false;
        }
        if(SysWinMax == "" || isNaN(SysWinMax))
        {
			alert("系统每天赢上限必须为数字!");
			return false;
        }   
        if(parseInt(SysWinMax) < parseInt(SysWinMin)) 
        {
			alert("系统每天赢上限必须大于等于下限!");
			return false;
        }
        if(TzClose == "" || isNaN(TzClose))
        {
			alert("投注截至秒数必须为数字!");
			return false;
        }
        if(GCTzExp == "" || isNaN(GCTzExp))
        {
			alert("奖励经验投注下限必须为数字!");
			return false;
        }
        if(PressMin == "" || isNaN(PressMin))
        {
			alert("投注下限必须为数字!");
			return false;
        }
        if(PressMax == "" || isNaN(PressMax))
        {
			alert("投注上限必须为数字!");
			return false;
        }
        if(parseInt(PressMax) < parseInt(PressMin))
        {
			alert("投注上限必须大于等于下限!");
			return false;
        }
        
        if(NoOpenNum == "" || isNaN(NoOpenNum))
        {
			alert("排除号码数必须为数字!");
			return false;
        }
        if($("#cbxGameShutDown").is(":checked")) 
        	GameOpenFlag = "1";
        
        data += "&gametype=" + parseInt(GameType) + "&GameName=" + GameName + "&JLExp=" + parseInt(JLExp)
        		+ "&MaxExp=" + parseInt(MaxExp)
        		+ "&GoSamples=" + parseInt(GoSamples)
        		+ "&JLExpVIP=" + parseInt(JLExpVIP)
        		+ "&MaxExpVIP=" + parseInt(MaxExpVIP)
        		+ "&KjDelay=" + parseInt(KjDelay)
        		+ "&SysWinOdds=" + parseInt(SysWinOdds)
        		+ "&SysWinMin=" + parseInt(SysWinMin)
        		+ "&SysWinMax=" + parseInt(SysWinMax)
        		+ "&NoOpenNum=" + parseInt(NoOpenNum)
        		+ "&TzClose=" + parseInt(TzClose)
        		+ "&GCTzExp=" + parseInt(GCTzExp)
        		+ "&PressMin=" + parseInt(PressMin)
        		+ "&PressMax=" + parseInt(PressMax)
        		+ "&StdOdds=" + StdOdds
        		+ "&STdPress=" + STdPress
        		+ "&GameModel=" + GameModel
        		+ "&gameopenflag=" + GameOpenFlag 
        		+ "&shutdownreason=" + ShutdownReason;
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
					case "get_gameconfig":
                        break;
					case "get_gamelist_option":
						$("#sltGCGameList").empty();
                        $(item.msg).appendTo("#sltGCGameList");
						return;
					default:
						alert(item.msg);
						return;
						break;
				}
			}
			else
			{
				if(InfoType == "get_gameconfig")
                {
                    $("#lblGCRecID").html(item.RecID);
                    $("#lblGCGameType").html(item.GameType);
                    $("#lblGCTablePrefix").html(item.TablePrefix);
                    
                    $("#txtGCGameName").val(item.GameName);
                    $("#txtGCJLExp").val(item.JLExp);
                    $("#txtJLMaxExp").val(item.MaxExp);
                    
                    $("#txtGCGoSamples").val(item.GoSamples);
                    $("#txtGCJLExpVIP").val(item.JLExpVIP);
                    $("#txtJLMaxExpVIP").val(item.MaxExpVIP);
                    
                    $("#txtGCKjDelay").val(item.KjDelay);
                    $("#txtGCSysWinOdds").val(item.SysWinOdds);
                    $("#txtGCSysWinMin").val(item.SysWinMin);
                    $("#txtGCSysWinMax").val(item.SysWinMax);
                    
                    $("#txtGCNoOpenNum").val(item.NoOpenNum); 
                    $("#lblNextOpenFlag").html(item.NextOpenFlag); 
                    
					$("#txtGCTzClose").val(item.TzClose);
					$("#txtGCTzExp").val(item.TzExp);
                    $("#txtGCPressMin").val(item.PressMin);
                    $("#txtGCPressMax").val(item.PressMax);
                    
                    $("#txtGCStdOdds").val(item.StdOdds);
                    $("#txtGCSTdPress").val(item.STdPress);
                    $("#txtGCGameModel").val(item.GameModel);
                    
                    $("#txtShutdownReason").val(item.game_shutdown_reason);
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

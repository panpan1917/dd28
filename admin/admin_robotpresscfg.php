<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>游戏管理--机器人下注设置</title>
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
		<div class="bodytitletxt">机器人下注设置</div>
	</div>
	<!-- 机器人下注设置 -->
    <div class="categorylist" id="div_GameConfig">
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >				
			<tr bgcolor="#FFFFFF">
				<td colspan="8">
					选择游戏
					<select id = "sltGameList">
                    </select>
                    <input type="button" value="刷新" id="btnRefresh" class="btn-1" />
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td width="150">游戏标识</td>
				<td><label id="lblGameType"></label></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>游戏名称</td>
				<td><label id="lblGameName"></label></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>每盘最少用户数</td>
				<td><input type="text" id="txtUserCountMin" style="width:150px" /></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>每盘最多用户数</td>
				<td><input id="txtUserCountMax" type="text" style="width:150px" /></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>最小投注额</td>
				<td><input type="text" id="txtPressPointMin" style="width:150px" /></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>最大投注额</td>
				<td><input id="txtPressPointMax" type="text" style="width:150px" /></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td></td>
				<td><input type="button" value="保存设置" id="btnSaveRobotPress" class="btn-1" /></td>
			</tr>                
		</table>
		<table id='tbResult' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			<tr bgcolor="#f5fafe">
				<td align="center">游戏</td>
				<td align="center">最少用户数</td>
				<td align="center">最多用户数</td> 
				<td align="center">最小投注额</td>
				<td align="center">最大投注额</td>
			</tr>
		</table>
	</div>
</form>    
</body>
<script type= "text/javascript" language ="javascript">
    $(window.parent.document).attr("title","机器人下注设置");
    $(document).ready(function() {
        GetGameListOption();
        GetRobotConfig();
        GetRobotConfigList();
        //***************************************************************
		//保存设置
		$("#btnSaveRobotPress").click(function(){
			SaveRobotPress();
		});
		//刷新
		$("#btnRefresh").click(function(){
			GetRobotConfig();
			GetRobotConfigList();
		});
		//游戏列表变更
		$("#sltGameList").change(function(){
            GetRobotConfig();
        });
         
	});
    //***************************************************************************************************
    //取游戏列表
    function GetGameListOption()
    {
		var data = "action=get_gamelist_option";
		SendAjax(data);
    }
    //取机器人配置
    function GetRobotConfig()
    {
    	$("#lblGameType").html($("#sltGameList").val());
    	$("#lblGameName").html($("#sltGameList").find("option:selected").text());
		var data = "action=game_robotpress_config&gametype=" + $("#sltGameList").val();
		SendAjax(data);
    }
    //取记录
    function GetRobotConfigList()
    { 
        var data = GetData();
        SendAjax(data);
    }
    //取参数
    function GetData()
    {   
    	$("#tbResult tr:gt(0)").remove(); 
        var data = "action=get_robotconfig_list";
        return data;
    }
    //保存设置
    function SaveRobotPress()
    {
        var data = "action=save_robotpress";
        var GameType = $.trim($("#lblGameType").html());
        var UserMin = $.trim($("#txtUserCountMin").val());
        var UserMax = $.trim($("#txtUserCountMax").val());
        var PressMin = $.trim($("#txtPressPointMin").val());
        var PressMax = $.trim($("#txtPressPointMax").val());
        
        if(GameType == "") 
        {
			alert("游戏类型错误，请刷新!");
			return false;
        }
        if(UserMin == "" || isNaN(UserMin))
        {
			alert("每盘最少用户数必须为数字!");
			return false;
        }
        if(UserMax == "" || isNaN(UserMax))
        {
			alert("每盘最多用户数必须为数字!");
			return false;
        }
        if(parseInt(UserMax) < parseInt(UserMin))
        {
			alert("每盘最多用户数必须大于等于最少用户数!");
			return false;
        }
        if(PressMin == "" || isNaN(PressMin))
        {
			alert("最小投注额数必须为数字!");
			return false;
        }
        if(PressMax == "" || isNaN(PressMax))
        {
			alert("最大投注额必须为数字!");
			return false;
        }
        if(parseInt(PressMax) < parseInt(PressMin))
        {
			alert("最大投注额必须大于等于最小投注额!");
			return false;
        }
        
        data += "&gametype=" + GameType + "&usermin=" + parseInt(UserMin) + "&usermax=" + UserMax + "&pressmin=" + PressMin + "&pressmax=" + PressMax;
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
					case "game_robotpress_config":
						break;
					case "get_robotconfig_list":
                        break;
					case "get_gamelist_option":
						$("#sltGameList").empty();
                        $(item.msg).appendTo("#sltGameList");
						return;
					case "save_robotpress":
						alert(item.msg);
						GetRobotConfigList();
						break;
					default:
						alert(item.msg);
						return;
						break;
				}
			}
			else
			{
				if(InfoType == "get_robotconfig_list")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td align='center'>" + item.GameName +"</td>" +
                            "<td align='center'>" + item.UserCountMin + "</td>" + 
                            "<td align='center'>" + item.UserCountMax + "</td>" + 
                            "<td align='center'>" + item.PressMin + "</td>" +
                            "<td align='center'>" + item.PressMax + "</td>" +
                            "</tr>";
                 }
                 else if(InfoType == "game_robotpress_config")
                {
                    $("#txtUserCountMin").val(item.UserCountMin);
                    $("#txtUserCountMax").val(item.UserCountMax);
                    $("#txtPressPointMin").val(item.PressPointMin);
                    $("#txtPressPointMax").val(item.PressPointMax);
                 }
			}

		});
		if(tbody != "")
		{
			if(InfoType == "get_robotconfig_list")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
            }
		}
	}
    
</script>

</html>

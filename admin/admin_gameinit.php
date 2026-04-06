<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理--游戏初始化</title>
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
		<div class="bodytitletxt">游戏初始化</div>
	</div>
	<!-- 游戏初始化 -->
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
				<td colspan="2">说明:由于系统自动生成期号要参考最近一次的期号，所以请正确填写</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td width="110">期号</td>
				<td><input type="text" id="txtGameNo" style="width:150px" />请输入最新一期未开奖期号</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>开奖时间</td>
				<td><input type="text" id="txtKGTime" style="width:150px" value="<?php echo date('Y-m-d H:i:00'); ?>" />精确到秒    <input type="button" value="创建期号" id="btnSaveGameInit" class="btn-1" /></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>开奖时间调整</td>
				<td><input type="text" id="txtKjTimeDiff" value="0" style="width:150px" />秒    <input type="button" value="调整" id="btnSaveGameKjTimeDiff" class="btn-1" /></td>
			</tr>                
		</table>
		<table id='tbResult' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			<tr bgcolor="#f5fafe">
				<td align="center">期号</td>
				<td align="center">开奖时间</td>
				<td align="center">开奖结果</td> 
				<td align="center">中奖人数</td>
				<td align="center">投注总数</td>
				<td align="center">游戏总抽税</td>
				<td align="center">用户投注</td>
				<td align="center">用户输赢</td>
			</tr>
		</table>
		<div class="fenyebar" id="PageInfo"></div>
	</div>
</form>    
</body>
<script type= "text/javascript" language ="javascript">
    $(window.parent.document).attr("title","游戏初始化");
    $(document).ready(function() {
        GetGameListOption();
        GetGameList();
        //***************************************************************
		//保存设置
		$("#btnSaveGameInit").click(function(){
			SaveGameInit();
		});

		$("#btnSaveGameKjTimeDiff").click(function(){
			var data = "action=adjust_gamekjtime";
			var GameType = $.trim($("#sltGameList").val());
	        var KjTimeDiff = $.trim($("#txtKjTimeDiff").val());
	        
	        if(GameType == "") 
	        {
				alert("游戏类型错误，请重新打开本页!");
				return false;
	        }

	        data += "&gametype=" + GameType + "&kjtimediff=" + KjTimeDiff;
	        SendAjax(data);
	        
		});
		
		//刷新
		$("#btnRefresh").click(function(){
			GetGameList();
		});
		//游戏列表变更
		$("#sltGameList").change(function(){
            GetGameList();
        });
         
	});
    //***************************************************************************************************
    //取游戏列表
    function GetGameListOption()
    {
		var data = "action=get_gametype_option";
		SendAjax(data);
    }
    //取记录
    function GetGameList()
    { 
        var data = GetData();
        SendAjax(data);
    }
    //取参数
    function GetData()
    {   
    	$("#tbResult tr:gt(0)").remove();
    	$("#PageInfo").html('');     
        var data = "action=get_gamestats_log";
        var GameType = $("#sltGameList").val();
        
        data += "&gametype=" + GameType + "&PageSize=20";
        data += "&order=kgtime"+ "&ordertype=desc";
        return data;
    }
    //分页
    function ajax_page(page)
    {
        var data = GetData();
        data += "&Page=" + page;
        SendAjax(data);
    }
    //保存设置
    function SaveGameInit()
    {
        var data = "action=save_gameinit";
        var GameType = $.trim($("#sltGameList").val());
        var GameNo = $.trim($("#txtGameNo").val());
        var KgTime = $.trim($("#txtKGTime").val());
        
        if(GameType == "") 
        {
			alert("游戏类型错误，请重新打开本页!");
			return false;
        }
        if(GameNo == "" || isNaN(GameNo))
        {
			alert("期号必须为数字!");
			return false;
        }
        if(KgTime == "")
        {
			alert("请输入正确的开奖时间!");
			return false;
        }
        
        data += "&gametype=" + GameType + "&gameno=" + parseInt(GameNo) + "&kgtime=" + KgTime;
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
					case "get_gamestats_log":
						pageinfo = item.msg;
                        break;
					case "get_gametype_option":
						$("#sltGameList").empty();
                        $(item.msg).appendTo("#sltGameList");
						return;
					case "save_gameinit":
						alert(item.msg);
						GetGameList();
						break;
					default:
						alert(item.msg);
						return;
						break;
				}
			}
			else
			{
				if(InfoType == "get_gamestats_log")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td align='center'>" + item.NO +"</td>" +
                            "<td align='center'>" + item.kgtime + "</td>" + 
                            "<td align='center'>" + item.kgjg + "</td>" + 
                            "<td align='center'>" + item.zjrnum + "</td>" +
                            "<td align='center'>" + item.tzpoints + "</td>" + 
                            "<td align='center'>" + item.game_tax + "</td>" + 
                            "<td align='center'>" + item.user_tzpoints + "</td>" + 
                            "<td align='center'>" + item.user_winpoints + "</td>" + 
                            "</tr>";
                 }
			}

		});
		if(tbody != "")
		{
			if(InfoType == "get_gamestats_log")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
                $("#PageInfo").html(pageinfo);
            }
		}
	}
    
</script>

</html>

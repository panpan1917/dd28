<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理--游戏采集设置</title>
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
		<div class="bodytitletxt">游戏采集设置</div>
	</div>
    <div class="categorylist" id="div_GameConfig">
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >				
			<tr bgcolor="#FFFFFF">
				<td colspan="2">说明:设置每类游戏的开始时间、结束时间、间隔(加拿大游戏时间间隔变化时自动停止游戏，需要在这里修改)</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td width="110">游戏种类:</td>
				<td><input type="text" id="txtGameKind" style="width:100px" />
				<input type="button" value="取配置" id="btnGetConfig" class="btn-1" />
				输入原来的种类则为修改
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td width="110">备注:</td>
				<td><input type="text" id="txtRemark" style="width:100px" /></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>游戏开始时间</td>
				<td><input type="text" id="txtBeginTime" style="width:150px" value="<?php echo date('h:i:00'); ?>" /></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>游戏截止时间:</td>
				<td><input type="text" id="txtEndTime" style="width:150px" value="<?php echo date('h:i:00'); ?>" /></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>两期间隔:</td>
				<td><input type="text" id="txtInterval" style="width:100px" value="300" />秒</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>内部采集地址:</td>
				<td><input type="text" id="txtCatchURL" style="width:600px" value="http://" />不带&no=xxx</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>内部开奖地址:</td>
				<td><input type="text" id="txtOpenURL" style="width:600px" value="http://" />不带&no=xxx</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td></td>
				<td><input type="button" value="保存" id="btnSave" class="btn-1" /></td>
			</tr>                
		</table>
		<table id='tbResult' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			<tr bgcolor="#f5fafe">
				<td align="center">游戏种类</td>
				<td align="center">备注</td> 
				<td align="center">开始时间</td>
				<td align="center">结束时间</td> 
				<td align="center">两期间隔(秒)</td>  
				<td align="center">内部采集地址</td> 
				<td align="center">内部开奖地址</td> 
				<td align="center">操作</td>
			</tr>
		</table>
		<div class="fenyebar" id="PageInfo"></div>
	</div>
</form>    
</body>
<script type= "text/javascript" language ="javascript">
    $(window.parent.document).attr("title","游戏采集设置");
    $(document).ready(function() {
        GetGameCatchConfig();
        //***************************************************************
		//保存设置
		$("#btnSave").click(function(){
			SaveGameCatchConfig();
			GetGameCatchConfig();
		});
        //取配置  
        $("#btnGetConfig").click(function(){
        	var GameKind = $.trim($("#txtGameKind").val());
        	if(GameKind == "") 
        	{
				alert("请输入游戏种类!");
				return;
        	}
			var data = "action=get_gamecatchconfig_single&gamekind=" + GameKind;
			SendAjax(data);
		}); 
	});
    //*************************************************************************************************** 
    //取记录
    function GetGameCatchConfig()
    { 
        var data = GetData();
        SendAjax(data);
    }
    //取参数
    function GetData()
    {   
    	$("#tbResult tr:gt(0)").remove();
    	$("#PageInfo").html('');     
        var data = "action=get_gamecatchconfig";
        
        data += "&PageSize=50";
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
    function SaveGameCatchConfig()
    {
        var data = "action=save_gamecatchconfig";
        var GameKind = $.trim($("#txtGameKind").val());
        var Remark = $.trim($("#txtRemark").val()); 
        var BeginTime = $.trim($("#txtBeginTime").val());
        var EndTime = $.trim($("#txtEndTime").val());
        var IntervalSec = $.trim($("#txtInterval").val());
        var CatchURL = $.trim($("#txtCatchURL").val());
        var OpenURL = $.trim($("#txtOpenURL").val());
        
        if(GameKind == "" || BeginTime == "" || EndTime == "" || IntervalSec == "" || isNaN(IntervalSec) ) 
        {
			alert("参数填写错误");
			return false;
        }
        data += "&gamekind=" + GameKind + "&begintime=" + BeginTime + "&endtime=" + EndTime 
        		+ "&interval=" + parseInt(IntervalSec) + "&remark=" + Remark 
        		+ "&catchurl=" + encodeURIComponent(CatchURL)
        		+ "&openurl=" + encodeURIComponent(OpenURL);
        SendAjax(data);
    }
    //删除
    function RemoveRec(gamekind)
    {
		var data = "action=remove_gamecatchconfig&gamekind=" + gamekind;
		SendAjax(data);
		GetGameCatchConfig();
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
					case "get_gamecatchconfig":
						pageinfo = item.msg;
                        break;
                    case "get_gamecatchconfig_single":
                    	break;
					default:
						alert(item.msg);
						return;
						break;
				}
			}
			else
			{
				if(InfoType == "get_gamecatchconfig_single")
				{
					$("#txtRemark").val(item.Remark);
					$("#txtBeginTime").val(item.BeginTime);
					$("#txtEndTime").val(item.EndTime);
					$("#txtInterval").val(item.Interval);
					$("#txtCatchURL").val(item.CatchURL);
					$("#txtOpenURL").val(item.OpenURL);
				}
				else if(InfoType == "get_gamecatchconfig")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td align='center'>" + item.GameKind +"</td>" +
                            "<td align='center'>" + item.Remark +"</td>" +
                            "<td align='center'>" + item.BeginTime + "</td>" + 
                            "<td align='center'>" + item.EndTime + "</td>" + 
                            "<td align='center'>" + item.Interval + "</td>" + 
                            "<td align='center'>" + item.CatchURL + "</td>" + 
                            "<td align='center'>" + item.OpenURL + "</td>" + 
                            "<td align='center'>" + item.Opr + "</td>" +
                            "</tr>";
                 }
			}

		});
		if(tbody != "")
		{
			if(InfoType == "get_gamecatchconfig")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
                $("#PageInfo").html(pageinfo);
            }
		}
	}
    
</script>

</html>

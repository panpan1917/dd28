<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>游戏管理--机器人管理</title>
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
		<div class="bodytitletxt">机器人管理</div>
	</div>
	<!-- 机器人管理 -->
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
				<td>可用分最小值</td>
				<td><input type="text" id="txtMaxG" value="10000" style="width:150px" />可用分大于此值才会下注
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>使用模式</td>
				<td>
					 <select id = "sltModelType">
                     </select>
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>加入机器人数量</td>
				<td>
					<input type="text" id="txtRobotNum" style="width:150px" value="50" />
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td></td>
				<td><input type="button" value="添加" id="btnAddRobot" class="btn-1" /></td>
			</tr>                
		</table>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			<tr bgcolor="#FFFFFF">
				<td>
					机器人列表
				</td>
			</tr>  
			<tr bgcolor="#FFFFFF">
				<td>
					用户ID:
					<input id="txtUserIdx" type="text" style="width:80px" />
				    模式
				    <select id = "sltQueryModelType">
                    </select>
                    页大小
				   	<input id="txtPageSize" type="text" value="50" style="width:50px" />
				    <input type="button" value="查询" id="btnSearchRobot" class="btn-1" />
				</td>
			</tr>  
		  	<tr bgcolor="#FFFFFF">
				<td>
					<input id="cbxSelectAll" type="checkbox"/>全选
					<input type="button" value="禁用" id="btnForbidden" class="btn-1" />
					<input type="button" value="恢复" id="btnRestore" class="btn-1" />
					<input type="button" value="删除" id="btnRemove" class="btn-1" />
				    
				</td>
			</tr> 
		</table>
		<table id='tbResult' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			<tr bgcolor="#f5fafe">
				<td align="center">选择</td>
				<td align="center">用户ID</td>
				<td align="center">昵称</td>
				<td align="center">使用模式</td> 
				<td align="center">当前分</td>
				<td align="center">银行分</td>
				<td align="center">投注分</td>
				<td align="center">状态</td>
				<td align="center">操作</td>
			</tr>
		</table>
		<div class="fenyebar" id="PageInfo"></div>
	</div>
</form>    
</body>
<script type= "text/javascript" language ="javascript">
    $(window.parent.document).attr("title","机器人管理");
    $(document).ready(function() {
        GetGameListOption();
        GetModelOption();
        GetRobotUserList();
        //***************************************************************
		//添加
		$("#btnAddRobot").click(function(){
			AddRobot();
		});
		//刷新
		$("#btnRefresh").click(function(){
			GetModelOption();
        	GetRobotUserList();
		});
		//游戏列表变更
		$("#sltGameList").change(function(){
            GetModelOption();
        	GetRobotUserList();
        });
        // 禁用
        $("#btnForbidden").click(function(){
        	ChangeRobotStatus(0);
		});  
		// 恢复
        $("#btnRestore").click(function(){
        	ChangeRobotStatus(1);
		});    
		// 删除
        $("#btnRemove").click(function(){
        	RemoveRobotPatch();
		});  
		
		// 查询
        $("#btnSearchRobot").click(function(){
        	GetRobotUserList();
		});
         
	});
    //***************************************************************************************************
    //取游戏列表
    function GetGameListOption()
    {
		var data = "action=get_gamelist_option";
		SendAjax(data);
    }
    //取模式列表
    function GetModelOption()
    {
		var data = "action=get_model_option&gametype=" + $("#sltGameList").val();
		SendAjax(data);
    }
    //修改状态
    function ChangeRobotStatus(t)
    {
		var IDs = GetCheckID();
		if(IDs.length == 0)
		{
			alert("必须勾选一个!");
			return false;
		}
		if(confirm("您确定要操作吗?"))
		{
			var data = "action=robot_changestate&gametype=" + $("#sltGameList").val() + "&t=" + t + "&id=" + IDs;
			SendAjax(data);
			GetRobotUserList();
		}
    }
    
    //添加
    function AddRobot()
    {
        var data = "action=add_robot";
        var GameType = $("#sltGameList").val();
        var ModelID = $.trim($("#sltModelType").val());
        var MaxG = $.trim($("#txtMaxG").val());
        var RobotNum = $.trim($("#txtRobotNum").val());
        
        if(GameType == "") 
        {
			alert("游戏类型错误!");
			return false;
        }
        if(ModelID == "" || isNaN(ModelID))
        {
			alert("请选择一个模式!");
			return false;
        }
        if(MaxG == "" || isNaN(MaxG) )
        {
			alert("可用分最小值必须为数字!");
			return false;
        }
        if(RobotNum == "" || isNaN(RobotNum))
        {
			alert("加入机器人数量必须为数字!");
			return false;
        }
        
        data += "&gametype=" + GameType + "&modelid=" + ModelID + "&maxg=" + MaxG + "&robotnum=" + parseInt(RobotNum);
        SendAjax(data);
    }
    
    //批量删除
    function RemoveRobotPatch()
    {
		var IDs = GetCheckID();
		if(IDs.length == 0)
		{
			alert("必须勾选一个!");
			return false;
		}
		if(confirm("您确定要操作吗?"))
		{
			var data = "action=remove_robot_patch&gametype=" + $("#sltGameList").val() + "&id=" + IDs;
			SendAjax(data);
			GetRobotUserList();
		}
    }
    //取记录
    function GetRobotUserList()
    { 
        var data = GetData();
        SendAjax(data);
    }
    //取参数
    function GetData()
    {   
    	$("#tbResult tr:gt(0)").remove(); 
    	$("#PageInfo").html(''); 
    	var data = "action=get_robotuser_list&gametype=" + $("#sltGameList").val();
    	var UserID = $.trim($("#txtUserIdx").val());
    	var ModelType = $("#sltQueryModelType").val();
    	var PageSize =  $("#txtPageSize").val();
    	
    	if(PageSize == "" || isNaN(PageSize))
        {
            PageSize = "20";
            $("#txtPageSize").val("20");
        }
        data += "&modeltype=" + ModelType +  "&PageSize=" + PageSize;
        if(UserID != "")
        {
            if(isNaN(UserID))
            {
                $("#txtUserIdx").val("");
            }
            else
            {
                data += "&userid=" + UserID;
            }            
        }
        
        return data;
    }
    //分页
    function ajax_page(page)
    {
        var data = GetData();
        data += "&Page=" + page;
        SendAjax(data);
    }
    //全选和反选
	$("#cbxSelectAll").click(function(){
		if(this.checked)
		{
			$("input[name='cbxID']").each(function(){this.checked=true;});
		}
		else
		{
			$("input[name='cbxID']").each(function(){this.checked=false;});
		}
	});
	//取得勾选ID
	function GetCheckID()
	{
		var IDs = "";
		$("input[name='cbxID']:checked").each(function(){
			IDs += $(this).val() + ",";
		});
		if(IDs.length > 0)
		{
			IDs = IDs.substr(0,IDs.length-1);
		}
		return IDs;
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
					case "get_robotuser_list":
						pageinfo = item.msg;
						break;
					case "get_gamelist_option":
						$("#sltGameList").empty();
                        $(item.msg).appendTo("#sltGameList");
						return;
					case "get_model_option":
						$("#sltModelType").empty();     
                        $(item.msg).appendTo("#sltModelType");
                        $("#sltChangeModelType").empty();
                        $(item.msg).appendTo("#sltChangeModelType");
                        $("#sltQueryModelType").empty();
                        $(item.msg).appendTo("#sltQueryModelType");
						break;
					case "add_robot":
					    alert(item.msg);
					    GetRobotUserList();
					    break;
					default:
						alert(item.msg);
						return;
						break;
				}
			}
			else
			{
				if(InfoType == "get_robotuser_list")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td align='center'>" + item.strCheckBox +"</td>" +
                            "<td align='center'>" + item.UserID + "</td>" + 
                            "<td align='center'>" + item.NickName + "</td>" + 
                            "<td align='center'>" + item.ModelName + "</td>" +
                            "<td align='center'>" + item.Points + "</td>" +
                            "<td align='center'>" + item.Back + "</td>" + 
                            "<td align='center'>" + item.LockPoints + "</td>" + 
                            "<td align='center'>" + item.Status + "</td>" + 
                            "<td align='center'>" + item.Opr + "</td>" + 
                            "</tr>";
                 }
			}

		});
		if(tbody != "")
		{
			if(InfoType == "get_robotuser_list")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
                $("#PageInfo").html(pageinfo);
            }
		}
	}
    
</script>

</html>

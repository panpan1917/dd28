<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "user" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>内部帐号管理-系统管理</title>
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
		<div class="bodytitletxt">内部帐号管理</div>
	</div>
    <div class="categorylist" id="div_GameConfig">
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >				
			<tr bgcolor="#FFFFFF">
				<td width="110">添加id</td>
				<td>
					<input type="text" id="txtUserID" style="width:100px" />
					<input type="button" value="检测" id="btnCheck" class="btn-1" />
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td width="110">备注</td>
				<td>
					<input type="text" id="txtRemark" style="width:100px" />
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td></td>
				<td><input type="button" value="添加" id="btnSave" class="btn-1" /></td>
			</tr>                
		</table>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >				
			<tr bgcolor="#FFFFFF">
				<td>
					搜索用户ID
					<input type="text" id="txtSUserID" style="width:80px" />
					<input type="button" value="查询" id="btnSearch" class="btn-1" />
				</td>
			</tr>            
		</table>
		<table id='tbResult' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			<tr bgcolor="#f5fafe">
				<td align="center">用户ID</td>
				<td align="center">帐号</td>
				<td align="center">昵称</td>
				<td align="center">当前分</td> 
				<td align="center">银行分</td>
				<td align="center">投注分</td>
				<td align="center">添加时间</td>
				<td align="center">备注</td>
				<td align="center">操作</td> 
			</tr>
		</table>
		<div class="fenyebar" id="PageInfo"></div>
	</div>
</form>    
</body>
<script type= "text/javascript" language ="javascript">
    $(window.parent.document).attr("title","内部帐号管理");
    $(document).ready(function() {
        GetResult();
        //***************************************************************
		//保存设置
		$("#btnSave").click(function(){
			AddInnerUser();
			GetResult();
		});
		//检测
		$("#btnCheck").click(function(){
			 var data = "action=check_user&userid=" + parseInt($.trim($("#txtUserID").val()));
			 SendAjax(data);
		});
		//搜索  
		$("#btnSearch").click(function(){
			GetResult();
		});
         
	});
    //*************************************************************************************************** 
    //取记录
    function GetResult()
    { 
        var data = GetData();
        SendAjax(data);
    }
    //取参数
    function GetData()
    {   
    	$("#tbResult tr:gt(0)").remove();
    	$("#PageInfo").html(''); 
    	var UserID = $.trim($("#txtSUserID").val());
    	
    	if(UserID == "" || isNaN(UserID))
    	{
			$("#txtSUserID").val("");
			UserID = "";
    	}    
        var data = "action=get_inneruser_list&userid=" + UserID;
        
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
    function AddInnerUser()
    {
        var data = "action=add_inneruser"; 
        var UserID = $.trim($("#txtUserID").val());
        var Remark = $.trim($("#txtRemark").val());
        
        if(UserID == "" || isNaN(UserID)  ) 
        {
			alert("请正确输入数字ID");
			return false;
        } 
        
        data += "&userid=" + UserID + "&remark=" + Remark;
        SendAjax(data);
    }
    //删除
    function RemoveUser(userid)
    {
		var data = "action=remove_inneruser&userid=" + userid;
		SendAjax(data);
		GetResult();
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
					case "get_inneruser_list":
						pageinfo = item.msg;
                        break;
					default:
						alert(item.msg);
						return;
						break;
				}
			}
			else
			{
				if(InfoType == "get_inneruser_list")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td align='center'>" + item.UserID +"</td>" +
                            "<td align='center'>" + item.UserName + "</td>" + 
                            "<td align='center'>" + item.NickName + "</td>" + 
                            "<td align='center'>" + item.Points + "</td>" +
                            "<td align='center'>" + item.Back + "</td>" +
                            "<td align='center'>" + item.LockPoints + "</td>" +
                            "<td align='center'>" + item.AddTime + "</td>" +
                            "<td align='center'>" + item.Remark + "</td>" +
                            "<td align='center'>" + item.Opr + "</td>" +
                            "</tr>";
                 }
			}

		});
		if(tbody != "")
		{
			if(InfoType == "get_inneruser_list")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
                $("#PageInfo").html(pageinfo);
            }
		}
	}
    
</script>

</html>

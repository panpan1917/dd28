<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理--正在投注用户</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta name="GENERATOR" content="MSHTML 6.00.3790.4275">
	<link rel="stylesheet" type="text/css" href="images/css_body.css">
	<link rel="stylesheet" type="text/css" href="images/window.css">
	<script type="text/javascript" src="images/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="images/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="images/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="images/fancybox/jquery.fancybox-1.3.4.css" media="screen" /> 
</head>
<body>
	<div class="bodytitle">
		<div class="bodytitleleft"></div>
		<div class="bodytitletxt">正在投注用户</div>
	</div>
	<div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
			<tr bgcolor="#FFFFFF">
				<td>
					选择游戏
					<select id = "sltGameList">
                    </select>
                    用户ID
                    <input id="txtUserIdx" type="text" style="width:80px" />
                    <input id="cbxExceptInner" type="checkbox" checked="checked">排除内部号
                    <input type="button" value="查询" id="btnSearch" class="btn-1"/>
					页大小
					<input id="txtPageSize" type="text" value="50" style="width:50px" />
					                 
			  	</td>
			  	<td width="180">
				  <select id = "sltOrder">
						<option value="points">游戏分</option>
						<option value="back">银行分</option>
						<option value="logintime">登录时间</option>
						<option value="time">注册时间</option>
						<option value="experience">经验</option>
					</select>
					&nbsp;&nbsp;
					<select id = "sltOrderType">
						<option value="desc">降序</option>
						<option value="">升序</option>
					</select>
			  </td>
			</tr>
		</table>
		<table id='tbResult' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			<tr bgcolor="#f5fafe">
				<td align="center">用户ID</td>
				<td align="center">昵称</td>
				<td align="center">手机</td>
				<td align="center">游戏分</td>
				<td align="center">银行分</td>
				<td align="center">投注分</td>
				<td align="center">经验</td>
				<td align="center">登录时间</td>
				<td align="center">登录IP</td>
				<td align="center">所在游戏</td>
				<td align="center">清在线</td>
			</tr>
		</table>
		<div class="fenyebar" id="PageInfo"></div>
	</div>
</body>
<script type= "text/javascript" language ="javascript">
    $(window.parent.document).attr("title","正在投注用户");
    //*************************************************************************************
    //基本信息
	$(document).ready(function() {
    	BindClass();
		GetGameTypeOption();
        GetPressUser();
        //查询
		$("#btnSearch").click(function(){
			GetPressUser();
		});

		//var clock = setInterval(function() {
		//	GetPressUser();
		//}, 8000);
	});
	//取游戏列表
	function GetGameTypeOption()
	{
		var data = "action=get_gametypeid_option";
		SendAjax(data);
	}
	//取记录
    function GetPressUser()
    {
        var data = GetData();
        SendAjax(data);
        BindClass();
    }
    //取参数
    function GetData()
    {   
    	$("#tbResult tr:gt(0)").remove();
    	$("#PageInfo").html('');     
        var data = "action=get_pressuser_list";
        var GameType = $("#sltGameList").val();
        var UserID = $("#txtUserIdx").val();
        var PageSize = $.trim($("#txtPageSize").val()); 
        
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
        if(PageSize == "" || isNaN(PageSize))
        {
            PageSize = "50";
            $("#txtPageSize").val("50");
        }
        
        var isExceptInner = 0;
        if($("#cbxExceptInner").is(":checked"))
        	isExceptInner = 1;
        data += "&isexceptinner=" + isExceptInner;
        	
        data += "&gametype=" + GameType + "&PageSize=" + PageSize;
        
        data += "&order=" + $("#sltOrder").val() + "&ordertype=" + $("#sltOrderType").val();
        return data;
    }
    //分页
    function ajax_page(page)
    {
        var data = GetData();
        data += "&Page=" + page;
        SendAjax(data);
    }
    //清在线
    function ClearInGame(uid)
    {
    	var gametype = $("#sltIngame_" + uid).val();
		var data = "action=clear_useringame&uid=" + uid + "&gametype=" + gametype;
		SendAjax(data);
		GetPressUser();
	};
    //*****************************************************************************************************
    //公共函数
    //验证日期正确是否，如2012-06-22
	function ValidDate(str)
    { 
		 var r = str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
		 if(r==null)return false; 
		 var d= new Date(r[1], r[3]-1, r[4]);
		 return (d.getFullYear()==r[1]&&(d.getMonth()+1)==r[3]&&d.getDate()==r[4]); 
    }
    //绑定
	function BindClass()
	{ 
		$(".edi").fancybox({
				type		: 'iframe',
				fitToView	: false,
				width		: '100%',
				height		: '100%',
				autoSize	: false,
				closeClick	: false,
				openEffect	: 'none',
				closeEffect	: 'none'
			}); 
	}
	//ajax处理
	function SendAjax(SendData)
	{
		var PostURL = "susers.php";
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
                	case "get_gametypeid_option":
						$("#sltGameList").empty();
                        $(item.msg).appendTo("#sltGameList");
						return;
                    case "get_pressuser_list":
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
                if(InfoType == "get_pressuser_list")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td align='center'>" + item.UserID +"</td>" +
                            "<td align='center'>" + item.NickName + "</td>" + 
                            "<td align='center'>" + item.Mobile + "</td>" + 
                            "<td align='center'>" + item.Points + "</td>" +
                            "<td align='center'>" + item.BankPoints + "</td>" + 
                            "<td align='center'>" + item.LockPoints + "</td>" + 
                            "<td align='center'>" + item.Exp + "</td>" +
                            "<td align='center'>" + item.LoginTime + "</td>" + 
                            "<td align='center'>" + item.LoginIP + "</td>" + 
                            "<td align='center'>" + item.InGame + "</td>" + 
                            "<td align='center'>" + item.Opr + "</td>" + 
                            "</tr>";
                }
			}						
		});
        
        if(tbody != "")
		{
			if(InfoType == "get_pressuser_list")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
                $("#PageInfo").html(pageinfo);
            }
		}
		
	}
</script>
</html>

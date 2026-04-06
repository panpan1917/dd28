<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>排行榜-用户管理</title>
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
		<div class="bodytitletxt">排行榜</div>
	</div>
	<div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
			<tr bgcolor="#FFFFFF">
				<td>
					类型
					<select id = "sltRankType">
						<option value="1">昨日排行</option>
						<option value="2">七日排行</option>
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
						<option value="rank_num">排行</option>
						<option value="state">状态</option>
					</select>
					&nbsp;&nbsp;
					<select id = "sltOrderType">
						<option value="">升序</option>
						<option value="desc">降序</option>
					</select>
			  </td>
			</tr>
		</table>
		<table id='tbResult' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			<tr bgcolor="#f5fafe">
				<td align="center">排行</td>
				<td align="center">用户ID</td>
				<td align="center">昵称</td>
				<td align="center">排行分</td>
				<td align="center">奖励分</td>
				<td align="center">状态</td>
			</tr>
		</table>
		<div class="fenyebar" id="PageInfo"></div>
	</div>
</body>
<script type= "text/javascript" language ="javascript">
    $(window.parent.document).attr("title","排行榜");
    //*************************************************************************************
    //基本信息
	$(document).ready(function() {
        GetRankUser();
        //查询
		$("#btnSearch").click(function(){
			GetRankUser();
		});
	});
	//取记录
    function GetRankUser()
    {
        var data = GetData();
        SendAjax(data);
    }
    //取参数
    function GetData()
    {   
    	$("#tbResult tr:gt(0)").remove();
    	$("#PageInfo").html('');     
        var data = "action=get_ranklist";
        var RankType = $("#sltRankType").val();
        var UserID = $("#txtUserIdx").val();
        var PageSize = $.trim($("#txtPageSize").val()); 
        
        var isExceptInner = 0;
        if($("#cbxExceptInner").is(":checked"))
        	isExceptInner = 1;
        data += "&isexceptinner=" + isExceptInner;
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
        	
        data += "&ranktype=" + RankType + "&PageSize=" + PageSize;
        
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
                    case "get_ranklist":
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
                if(InfoType == "get_ranklist")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td align='center'>" + item.RankNum +"</td>" +
                            "<td align='center'>" + item.UserID + "</td>" + 
                            "<td align='center'>" + item.NickName + "</td>" + 
                            "<td align='center'>" + item.RankPoints + "</td>" +
                            "<td align='center'>" + item.PrizePoints + "</td>" + 
                            "<td align='center'>" + item.State + "</td>" + 
                            "</tr>";
                }
			}						
		});
        
        if(tbody != "")
		{
			if(InfoType == "get_ranklist")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
                $("#PageInfo").html(pageinfo);
            }
		}
		
	}
</script>
</html>

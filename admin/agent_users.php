<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>代理管理--代理列表</title>
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
		<div class="bodytitletxt">代理列表</div>
		<div class="bodytitletxt2">
			<a class='edi' href="agent_edituser.php?act=add">添加代理</a>
		</div>
	</div>
	<div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
			<tr bgcolor="#FFFFFF">
				<td>
				   用户ID
				   <input id="txtUserID" type="text" style="width:100px" />页大小
				   <input id="txtPageSize" type="text" value="50" style="width:50px" />
				   &nbsp;&nbsp;
				  <input type="button" value="查询" id="btnSearch" class="btn-1"/>                 
			  </td>    
			  <td width="180">
				  <select id = "sltOrder">
						<option value="add_time">添加时间</option>
						<option value="last_logintime">登录时间</option>
						<option value="state">状态</option>
						<option value="is_recommend">推荐</option>
						<option value="totalpoints">总分</option>
						<option value="distribute_money">铺货</option>
					</select>
					&nbsp;
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
				<td align="center">名称</td>
				<td align="center">添加时间</td> 
				<td align="center">总分</td>
				<td align="center">铺货</td>
				<td align="center">待结金额</td>
				<td align="center">最后登录</td>
				<td align="center">登录IP</td>
				<td align="center">状态</td>
				<td align="center">推荐</td>
				<td align="center">操作</td>
			</tr>
		</table>
		<div class="fenyebar" id="PageInfo"></div>
	</div>
</body>
</html>
<script type= "text/javascript" language ="javascript">
	$(window.parent.document).attr("title","代理列表");
    $(document).ready(function() {
    	GetResult();
	});
    //**************************************************************************
    //查询
    $("#btnSearch").click(function(){
    	GetResult();
    });
    
    //取记录
    function GetResult()
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
        var data = "action=get_agentlist";
        var UserID = $("#txtUserID").val();
        var PageSize = $.trim($("#txtPageSize").val());
        
        if(PageSize == "" || isNaN(PageSize))
        {
            PageSize = "50";
            $("#txtPageSize").val("50");
        }
        data += "&userid=" + UserID + "&PageSize=" + PageSize;
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
    
    function ChangeAgentState(recid,flag)
    {
		var data = "action=agent_change_state&id="+ recid + "&flag=" + flag;
		SendAjax(data);
		GetResult();
    }
    function ChangeRecommend(recid,flag)
    {
		var data = "action=agent_change_recommend&id="+ recid + "&flag=" + flag;
		SendAjax(data);
		GetResult();
    }
    //****************************************************************************************
    //公共函数
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
    //初始化日期控件
    function InitDatePicker(o)
    {
		$("#" + o).datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,  //可以选择月份  
            changeYear: true,   //可以选择年份 
            dayNamesMin : ["日", "一", "二", "三", "四", "五", "六"], 
            firstDay : 1, 
            monthNamesShort: ["1", "2", "3", "4", "5", "6","7", "8", "9", "10", "11", "12"],
            yearRange: 'c-60:c+20'
        });  
    }
    //取得当前url参数
    function request(paras)
    {
        var url = location.href;  //获取当前url地址
        var paraString = url.substring(url.indexOf("?")+1,url.length).split("&");
        var paraObj = {}
        for (i=0; j=paraString[i]; i++){
            paraObj[j.substring(0,j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=")+1,j.length);
        }
        var returnValue = paraObj[paras.toLowerCase()];
        if(typeof(returnValue)=="undefined"){
            return "";
        }else{
            return returnValue;
        }
    }
    //验证日期正确是否，如2012-06-22
	function ValidDate(str)
    { 
		 var r = str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
		 if(r==null)return false; 
		 var d= new Date(r[1], r[3]-1, r[4]);
		 return (d.getFullYear()==r[1]&&(d.getMonth()+1)==r[3]&&d.getDate()==r[4]); 
    }
    //ajax处理
	function SendAjax(SendData)
	{
		var PostURL = "sagent.php";
		$.ajax({
		       type: "POST",
		       async:false,
		       dataType: "json",
		       url: PostURL,
		       data: SendData,
		       success: function(data) {DataSuccess(data);},
               error:function(XMLHttpRequest, textStatus, errorThrown){alert(textStatus);}
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
					case "get_agentlist":
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
				if(InfoType == "get_agentlist")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td>" + item.UserID +"</td>" +
                            "<td>" + item.AgentName +"</td>" + 
                            "<td>" + item.AddTime +"</td>" + 
                            "<td>" + item.TotalPoints +"</td>" +
                            "<td>" + item.DistributeMoney +"</td>" + 
                            "<td>" + item.BalanceMoney +"</td>" +
                            "<td>" + item.LastLoginTime +"</td>" +
                            "<td>" + item.LastLoginIP +"</td>" +
                            "<td>" + item.State +"</td>" +
                            "<td>" + item.Recommend +"</td>" +
                            "<td>" + item.Opr +"</td>" +
                            "</tr>";
                 }
			}

		});
		if(tbody != "")
		{
			if(InfoType == "get_agentlist")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
                $("#PageInfo").html(pageinfo);
            }
		}
	}
</script>

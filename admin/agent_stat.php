<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>代理统计信息-代理管理</title>
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
		<div class="bodytitletxt">代理统计信息</div>
		<div class="bodytitletxt2">
			<a href="javascript:RemoveLog(6);">删除6个月前日志</a>
		</div>
	</div>
	<div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
			<tr bgcolor="#FFFFFF">
				<td>
				   月度统计<input type="button" value="刷新" id="btnRefresh" class="btn-1"/>
			  	</td>
		</table>
		<table id='tbMonthResult' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			<tr bgcolor="#f5fafe">
				<td align="center">月份</td>
				<td align="center">销售总额(￥)</td>
				<td align="center">销售利润(￥)</td>
				<td align="center">回收总额(￥)</td> 
				<td align="center">回收利润(￥)</td>
				<td align="center">总利润(￥)</td>
			</tr>
		</table>
		<div class="fenyebar" id="PageInfo_Month"></div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
			<tr bgcolor="#FFFFFF">
				<td>
				   代理
				   <select id="sltAgentList">
				   </select>
				   	<input id="cbxTime" type="checkbox" checked="checked"/>日期
				   	<input id="txtTimeBegin" type="text" style="width:90px" value="<?php echo date('Y-m-d',strtotime('-1 week')); ?>" />-
				   	<input id="txtTimeEnd" type="text" style="width:90px" value="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" />
				   <input type="button" value="查询" id="btnSearch" class="btn-1"/>
				   页大小
				   <input id="txtPageSize" type="text" value="20" style="width:50px" />
				   
			  </td>    
			  <td width="180">
				  <select id = "sltOrder">
						<option value="thedate">日期</option>
						<option value="agentid">代理</option>
						<option value="out_points">销售额</option>
						<option value="in_points">回收额</option>
					</select>
					&nbsp;
					<select id = "sltOrderType">
						<option value="desc">降序</option>
						<option value="">升序</option>
					</select>
			  </td>
			</tr>
		</table>
		<table id='tbDayResult' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			<tr bgcolor="#f5fafe">
				<td align="center">代理名称</td>
				<td align="center">日期</td>
				<td align="center">销售额(￥)</td>
				<td align="center">折扣</td> 
				<td align="center">销售利润(￥)</td>
				<td align="center">回收额(￥)</td>
				<td align="center">折扣</td>
				<td align="center">回收利润(￥)</td>
				<td align="center">总利润(￥)</td>
			</tr>
		</table>
		<div class="fenyebar" id="PageInfo_Day"></div>
	</div>
</body>
</html>
<script type= "text/javascript" language ="javascript">
	$(window.parent.document).attr("title","代理统计信息");
    $(document).ready(function() {
    	GetAgentListOption();
    	GetAgentMonthStat();
    	GetAgentDayStat();
	});
    //**************************************************************************
    //取得代理option
    function GetAgentListOption()
    {
		var data = "action=get_agentlist_option";
		SendAjax(data);
    }
    //取得代理操作option
    function GetAgentOprType()
    {
		var data = "action=get_oprlog_type";
		SendAjax(data);
    }
    //刷新
    $("#btnRefresh").click(function(){
    	GetAgentMonthStat();
    });
    //查询
    $("#btnSearch").click(function(){
    	GetAgentDayStat();
    });
    
    //取记录
    function GetAgentMonthStat()
    { 
        var data = GetMData();
        SendAjax(data);
    }
    //取参数
    function GetMData()
    {   
    	$("#tbMonthResult tr:gt(0)").remove();
    	$("#PageInfo_Month").html('');     
        var data = "action=get_agent_monthstat";
        return data;
    }
    //分页
    function ajax_page_month(page)
    {
        var data = GetMData();
        data += "&Page=" + page;
        SendAjax(data);
    }
    
    //取记录
    function GetAgentDayStat()
    { 
        var data = GetDData();
        SendAjax(data);
    }
    //取参数
    function GetDData()
    {   
    	$("#tbDayResult tr:gt(0)").remove();
    	$("#PageInfo_Day").html('');     
        var data = "action=get_agent_daystat";
        var AgentID = $("#sltAgentList").val();
        var PageSize = $.trim($("#txtPageSize").val());
        var DateBegin = $("#txtTimeBegin").val();
        var DateEnd = $("#txtTimeEnd").val();
        
        if(PageSize == "" || isNaN(PageSize))
        {
            PageSize = "20";
            $("#txtPageSize").val("20");
        }
        if($("#cbxTime").is(":checked"))
        {   
            if(DateBegin != "")
            {
                if(!ValidDate(DateBegin))
                {
                    $("#txtTimeBegin").val("");
                }
                else
                {
                    data += "&timebegin=" + DateBegin;
                }
            }
            if(DateEnd != "")
            {
                if(!ValidDate(DateEnd))
                {
                    $("#txtTimeEnd").val("");
                }
                else
                {
                    data += "&timeend=" + DateEnd;
                }                
            }
        }
        data += "&agentid=" + AgentID + "&order=" + $("#sltOrder").val() + "&ordertype=" + $("#sltOrderType").val();
        return data;
    }
    //分页
    function ajax_page_day(page)
    {
        var data = GetDData();
        data += "&Page=" + page;
        SendAjax(data);
    }
    
    function RemoveLog(m)
    {
		var data = "action=agent_remove_agentdaystatic&m="+ m;
		SendAjax(data);
		GetResult();
    }
    //****************************************************************************************
    //公共函数
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
					case "get_agentlist_option":
						$("#sltAgentList").empty();
                        $(item.msg).appendTo("#sltAgentList");
						return;
					case "get_agent_monthstat":
                        pageinfo = item.msg;
                        break;
                    case "get_agent_daystat":
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
				if(InfoType == "get_agent_monthstat")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td>" + item.theMonth +"</td>" +
                            "<td>" + item.SellRMB +"</td>" + 
                            "<td>" + item.SellProfit +"</td>" + 
                            "<td>" + item.RecRMB +"</td>" +
                            "<td>" + item.RecProfit +"</td>" + 
                            "<td>" + item.TotalProfit +"</td>" +
                            "</tr>";
                 }
                else if(InfoType == "get_agent_daystat")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td>" + item.AgentName +"</td>" +
                            "<td>" + item.TheDay +"</td>" +
                            "<td>" + item.SellRMB +"</td>" + 
                            "<td>" + item.SellRate +"</td>" + 
                            "<td>" + item.SellProfit +"</td>" + 
                            "<td>" + item.RecRMB +"</td>" +
                            "<td>" + item.RecRate +"</td>" + 
                            "<td>" + item.RecProfit +"</td>" + 
                            "<td>" + item.TotalProfit +"</td>" +
                            "</tr>";
                 }
			}

		});
		if(tbody != "")
		{
			if(InfoType == "get_agent_monthstat")
            {
                $("#tbMonthResult tr:gt(0)").remove();
                $("#tbMonthResult").append(tbody);
                $("#PageInfo_Month").html(pageinfo);
            }
            else if(InfoType == "get_agent_daystat")
            {
                $("#tbDayResult tr:gt(0)").remove();
                $("#tbDayResult").append(tbody);
                $("#PageInfo_Day").html(pageinfo);
            }
		}
	}
</script>

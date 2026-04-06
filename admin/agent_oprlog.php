<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>代理管理--操作日志</title>
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
		<div class="bodytitletxt">操作日志</div>
		<div class="bodytitletxt2">
			<a href="javascript:RemoveOprLog(3);">删除3个月前日志</a>
		</div>
	</div>
	<div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
			<tr bgcolor="#FFFFFF">
				<td>
				   代理
				   <select id="sltAgentList">
				   </select>
				   类型
				   <select id="sltOprType">
				   </select>
				   内容
				   <input id="txtContent" type="text" style="width:100px" />
				   支持模糊查询
				   <input type="button" value="查询" id="btnSearch" class="btn-1"/>
				   页大小
				   <input id="txtPageSize" type="text" value="50" style="width:50px" />
				   <br>
				   	<input id="cbxTime" type="checkbox"/>时间
				   	<input id="txtTimeBegin" type="text" style="width:90px" value="<?php echo date('Y-m-d',strtotime('-1 week')); ?>" />-
				   	<input id="txtTimeEnd" type="text" style="width:90px" value="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" />
			  </td>    
			  <td width="180">
				  <select id = "sltOrder">
						<option value="opr_time">操作时间</option>
						<option value="opr_type">类型</option>
						<option value="opr_ip">IP</option>
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
				<td align="center">代理名称</td>
				<td align="center">操作类型</td>
				<td align="center">操作内容</td> 
				<td align="center">影响分</td>
				<td align="center">操作后分</td>
				<td align="center">操作时间</td>
				<td align="center">操作IP</td>
			</tr>
		</table>
		<div class="fenyebar" id="PageInfo"></div>
	</div>
</body>
</html>
<script type= "text/javascript" language ="javascript">
	$(window.parent.document).attr("title","代理操作日志");
    $(document).ready(function() {
    	GetAgentListOption();
    	GetAgentOprType();
    	GetResult();
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
    //查询
    $("#btnSearch").click(function(){
    	GetResult();
    });
    
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
        var data = "action=get_agent_oprlog";
        var Content = $("#txtContent").val();
        var AgentID = $("#sltAgentList").val();
        var OprType = $("#sltOprType").val();
        var PageSize = $.trim($("#txtPageSize").val());
        var DateBegin = $("#txtTimeBegin").val();
        var DateEnd = $("#txtTimeEnd").val();
        
        if(PageSize == "" || isNaN(PageSize))
        {
            PageSize = "50";
            $("#txtPageSize").val("50");
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
        data += "&agentid=" + AgentID + "&oprtype=" + OprType + "&PageSize=" + PageSize
        		+ "&order=" + $("#sltOrder").val() + "&ordertype=" + $("#sltOrderType").val()
        		+ "&content=" + Content;
        return data;
    }
    //分页
    function ajax_page(page)
    {
        var data = GetData();
        data += "&Page=" + page;
        SendAjax(data);
    }
    
    function RemoveOprLog(m)
    {
		var data = "action=agent_removelog&m="+ m;
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
					case "get_oprlog_type":
						$("#sltOprType").empty();
                        $(item.msg).appendTo("#sltOprType");
						return;
					case "get_agent_oprlog":
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
				if(InfoType == "get_agent_oprlog")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td>" + item.UserID +"</td>" +
                            "<td>" + item.AgentName +"</td>" + 
                            "<td>" + item.OprType +"</td>" + 
                            "<td>" + item.Content +"</td>" +
                            "<td>" + item.OprPoints +"</td>" + 
                            "<td>" + item.TotalPoints +"</td>" +
                            "<td>" + item.OprTime +"</td>" +
                            "<td>" + item.OprIP +"</td>" +
                            "</tr>";
                 }
			}

		});
		if(tbody != "")
		{
			if(InfoType == "get_agent_oprlog")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
                $("#PageInfo").html(pageinfo);
            }
		}
	}
</script>

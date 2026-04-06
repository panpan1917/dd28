<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>代理管理--提现审核</title>
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
		<div class="bodytitletxt">提现审核</div>
		<div class="bodytitletxt2">
		</div>
	</div>
	<div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
			<tr bgcolor="#FFFFFF">
				<td>
				   代理
				   <select id="sltAgentList">
				   </select>
				   状态
				   <select id="sltState">
				   		<option value="-1">所有</option>
				   		<option value="0">未处理</option>
				   		<option value="1">已处理</option>
				   		<option value="2">用户撤销</option>
				   		<option value="3">管理员撤销</option>
				   </select>
				   <input type="button" value="查询" id="btnSearch" class="btn-1"/>
				   页大小
				   <input id="txtPageSize" type="text" value="50" style="width:50px" />
				   <br>
				   	<input id="cbxTime" type="checkbox" checked="checked"/>时间
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
				<td align="center">订单号</td>
				<td align="center">用户ID</td>
				<td align="center">代理名称</td>
				<td align="center">申请时间</td>
				<td align="center">提现额</td>
				<td align="center">代理余额</td> 
				<td align="center">铺货金额</td>
				<td align="center">状态</td>
				<td align="center">处理时间</td>
				<td align="center">处理人</td>
				<td align="center">操作</td>
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
    	GetResult();
	});
    //**************************************************************************
    //取得代理option
    function GetAgentListOption()
    {
		var data = "action=get_agentlist_option";
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
        var data = "action=get_agent_withdraw";
        var AgentID = $("#sltAgentList").val();
        var State = $("#sltState").val();
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
        data += "&agentid=" + AgentID + "&state=" + State + "&PageSize=" + PageSize
        		+ "&order=" + $("#sltOrder").val() + "&ordertype=" + $("#sltOrderType").val();
        return data;
    }
    //分页
    function ajax_page(page)
    {
        var data = GetData();
        data += "&Page=" + page;
        SendAjax(data);
    }
    //处理
    function ToDual(id,state)
    {
		var remark = $("#remark_" + id).val();
		if(remark == "")
		{
			alert("请输入审核结果");
			return;
		}
		var data = "action=dual_withdraw&id=" + id + "&state=" + state + "&remark=" + remark;
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
					case "get_agent_withdraw":
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
				if(InfoType == "get_agent_withdraw")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                    		"<td>" + item.ID +"</td>" +
                            "<td>" + item.UserID +"</td>" +
                            "<td>" + item.AgentName +"</td>" + 
                            "<td>" + item.AddTime +"</td>" + 
                            "<td>" + item.Points +"</td>" +
                            "<td>" + item.TotalPoints +"</td>" + 
                            "<td>" + item.DBPoints +"</td>" +
                            "<td>" + item.State +"</td>" +
                            "<td>" + item.OprTime +"</td>" +
                            "<td>" + item.OprUser +"</td>" +
                            "<td>" + item.Opr +"</td>" +
                            "</tr>";
                 }
			}

		});
		if(tbody != "")
		{
			if(InfoType == "get_agent_withdraw")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
                $("#PageInfo").html(pageinfo);
            }
		}
	}
</script>

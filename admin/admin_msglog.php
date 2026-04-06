<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理--发送记录</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta name="GENERATOR" content="MSHTML 6.00.3790.4275">
	<link rel="stylesheet" type="text/css" href="images/css_body.css">
	<link rel="stylesheet" type="text/css" href="images/window.css">
	<link rel="Stylesheet" type="text/css" href="images/jquery_ui.css" />
	<script type="text/javascript" src="images/jquery.js"></script> 
	<script type="text/javascript" src="images/jquery_ui.js"></script>
</head>
<body>
	<!-- 发送记录 -->
	<div class="bodytitle">
		<div class="bodytitleleft"></div>
		<div class="bodytitletxt">发送记录</div>
		<div class="bodytitletxt2">
			<a href="javascript:RemoveMsg('time',1);">删除一月前已看过短信</a>
		</div>
	</div>
	<div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
			<tr bgcolor="#FFFFFF">
				<td>
					<select id='sltMsgType'>
						<option value="-1">所有</option>
						<option value="0">系统短信</option>
						<option value="1">用户短信</option>
					</select>
					用户ID
					<input id="txtUserIdx" type="text" style="width:80px" />
				   <input id="cbxTime" type="checkbox"/>时间
				   <input id="txtTimeBegin" type="text" style="width:90px" value="<?php echo date('Y-m-d',strtotime('-1 week')); ?>" />&nbsp;
				   <input id="txtTimeEnd" type="text" style="width:90px" value="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" />
				   页大小
				   <input id="txtPageSize" type="text" value="20" style="width:50px" />
				   &nbsp;&nbsp;
				  <input type="button" value="查询" id="btnSearch" class="btn-1"/>                 
			  </td>    
			  <td width="180">
				  <select id = "sltOrder">
						<option value="time">操作时间</option>
						<option value="msgtype">类型</option>
						<option value="usersid">发送者</option> 
						<option value="mid">接收者</option>
						<option value="title">主题</option>
						<option value="look">状态</option>
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
				<td align="center" width="30px"></td>
				<td align="center">发送者</td>
				<td align="center">主题</td>
				<td align="center">内容</td> 
				<td align="center">接收者</td>
				<td align="center">时间</td>
				<td align="center">状态</td>
				<td align="center">操作</td>
			</tr>
		</table>
		<div class="fenyebar" id="PageInfo"></div>
		<div>
	        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
	            <tr bgcolor="#FFFFFF">
	                <td width="60" align="left">
	                    <input type="checkbox" id="cbxSelectAll" />
	                    全选
	                </td>
	                <td align="left">
	                    <input type="button" value="批量删除"  id="btnRemove" class="btn-1" />  
	                </td>
	            </tr>
	        </table>     
	    </div>
	</div>
</body>
</html>
<script type= "text/javascript" language ="javascript">
    $(document).ready(function() {
    	InitDatePicker("txtTimeBegin");
        InitDatePicker("txtTimeEnd");
    	GetMsgLog();
	});
    //**************************************************************************
    //查询
    $("#btnSearch").click(function(){
    	GetMsgLog();
    });
    //删除
    $("#btnRemove").click(function(){
    	RemoveMsgLog();
    });
    
    //取记录
    function GetMsgLog()
    { 
        var data = GetData();
        SendAjax(data);
    }
    //取参数
    function GetData()
    {   
    	$("#tbResult tr:gt(0)").remove();
    	$("#PageInfo").html('');     
        var data = "action=get_msglog";
        var useridx = $("#txtUserIdx").val();
        var MsgType = $("#sltMsgType").val();
        var DateBegin = $("#txtTimeBegin").val();
        var DateEnd = $("#txtTimeEnd").val();
        var PageSize = $.trim($("#txtPageSize").val());
        
        if(PageSize == "" || isNaN(PageSize))
        {
            PageSize = "20";
            $("#txtPageSize").val("20");
        }
        data += "&msgtype=" + MsgType + "&PageSize=" + PageSize;
        if(useridx != "")
        {
            if(isNaN(useridx))
            {
                $("#txtUserIdx").val("");
            }
            else
            {
                data += "&userid=" + useridx;
            }            
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
    
    function RemoveMsg(t,m)
    {
		var data = "action=remove_msg&type="+ t + "&m=" + m;
		SendAjax(data);
		GetMsgLog();
    }
    function RemoveMsgLog()
    {
		var IDs = GetCheckID(); 
		if(IDs.length == 0)
		{
			alert("必须勾选一个!");
			return false;
		}
		if(confirm("您确定要删除吗?"))
		{
			RemoveMsg("id",IDs);
		}
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
		var PostURL = "susers.php";
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
					case "get_msglog":
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
				if(InfoType == "get_msglog")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                    		"<td>" + item.CheckBox +"</td>" +
                            "<td>" + item.Sender +"</td>" +
                            "<td>" + item.Title +"</td>" +
                            "<td>" + item.Content +"</td>" +
                            "<td>" + item.Receiver +"</td>" +
                            "<td>" + item.SendTime + "</td>" +
                            "<td>" + item.Status + "</td>" +
							"<td>" + item.Opr + "</td>" +
                            "</tr>";
                 }
			}

		});
		if(tbody != "")
		{
			if(InfoType == "get_msglog")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
                $("#PageInfo").html(pageinfo);
            }
		}
	}
</script>

<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理--单用户查询</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta name="GENERATOR" content="MSHTML 6.00.3790.4275">
	<link rel="stylesheet" type="text/css" href="images/css_body.css">
	<link rel="stylesheet" type="text/css" href="images/window.css">
	<link rel="Stylesheet" type="text/css" href="images/jquery_ui.css" />
	<script type="text/javascript" src="images/jquery.js"></script> 
	<script type="text/javascript" src="images/jquery_ui.js"></script>
</head>
<body>
	<!-- 分值变化记录 -->
	<div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >				
			<tr bgcolor="#FFFFFF">
				<td>
					<label id="lblUserIdx"></label>用户分值变化记录
				</td>
				<td width="200">
				    <a style="cursor:pointer" onClick="javascript:RemoveLogDayAgo(3);">删除3天前数据</a>
				</td>
			</tr>				
		</table>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
			<tr bgcolor="#FFFFFF">
				<td>
					用户ID:
					<input id="txtUserIdx" type="text" style="width:80px" />
					发生类型
					<select id="sltGameType">
					</select>
					期号
					<input id="txtNo" type="text" style="width:80px" />
					页大小
				   	<input id="txtPageSize" type="text" value="50" style="width:50px" />
				   	<input type="button" value="查询" id="btnSearch" class="btn-1"/>
				   	全压ID
				   	<input id="txtPressAllID" type="text" value="483579" style="width:80px" />
				   	<input type="button" value="查无中奖" id="btnSearchNoReward" class="btn-1"/>
				   	<br>
				   	<input id="cbxTime" type="checkbox" checked="checked"/>日期
				   	<input id="txtTimeBegin" type="text" style="width:80px" value="<?php echo date('Y-m-d',strtotime('0 day')); ?>" />-
				   	<input id="txtTimeEnd" type="text" style="width:80px" value="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" />
				   	<input id="cbxChangeScore" type="checkbox"/>变化额
				   	<input id="txtScoreMin" type="text" style="width:80px" value="1000000" />-
				   	<input id="txtScoreMax" type="text" style="width:80px" value="5000000" />
				   	<input id="cbxExceptInner" type="checkbox" checked="checked">排除内部号
			  </td>    
			  <td width="180">
				  <select id = "sltOrder">
				  		<option value="id">时间</option>
						<option value="uid">用户ID</option>
						<option value="change_points">变化额</option>
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
				<td>序号</td>
				<td>用户ID</td>
				<td>昵称</td>
				<td>游戏</td>
				<td>期号</td>
				<td>备注</td>
				<td>时间</td>
				<td>变化后分</td>
				<td>变化额</td>
				<td>银行</td>
				<td>投注分</td>
				<td>经验</td>
			</tr>
		</table>
		<div class="fenyebar" id="PageInfo"></div>
	</div>
</body>
</html>
<script type= "text/javascript" language ="javascript">
    $(document).ready(function() { 
    	InitDatePicker("txtTimeBegin");
    	InitDatePicker("txtTimeEnd");
    	var useridx = request("id");
    	$("#txtUserIdx").val(useridx);
    	GetGameTypeOption();
    	GetGameLogData();
	});
    //**************************************************************************
    //取类型
    function GetGameTypeOption()
    {
		var data = "action=get_gametypeid_score_option";
		SendAjax(data);
    } 
    //查询
    $("#btnSearch").click(function(){
    	GetGameLogData();
    });
    //查询无中奖
    $("#btnSearchNoReward").click(function(){
    	GetNoRewardRecord();
    });
    
    //取记录
    function GetGameLogData()
    { 
        var data = GetData();
        SendAjax(data);
    }
    //取参数
    function GetData()
    {   
    	$("#tbResult tr:gt(0)").remove();
    	$("#PageInfo").html('');     
        var data = "action=get_gamescore_changelog";
        var useridx = $("#txtUserIdx").val();
        var gametype = $("#sltGameType").val();
        var gameno = $("#txtNo").val();
        var DateBegin = $("#txtTimeBegin").val();
        var DateEnd = $("#txtTimeEnd").val();
        var ScoreMin = $("#txtScoreMin").val();
        var ScoreMax = $("#txtScoreMax").val();
        var PageSize = $.trim($("#txtPageSize").val());
        
        var isExceptInner = 0;
        if($("#cbxExceptInner").is(":checked"))
        	isExceptInner = 1;
        data += "&isexceptinner=" + isExceptInner;
        
        if(PageSize == "" || isNaN(PageSize))
        {
            PageSize = "50";
            $("#txtPageSize").val("50");
        }
        data += "&gametype=" + gametype + "&gameno="+ gameno +  "&PageSize=" + PageSize;
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
        if($("#cbxChangeScore").is(":checked"))
        {   
            if(ScoreMin != "")
            {
                if(isNaN(ScoreMin))
                {
                    $("#txtScoreMin").val("");
                }
                else
                {
                    data += "&scoremin=" + ScoreMin;
                }
            }
            if(ScoreMax != "")
            {
                if(isNaN(ScoreMax))
                {
                    $("#txtScoreMax").val("");
                }
                else
                {
                    data += "&scoremax=" + ScoreMax;
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
    //取无中奖
    function GetNoRewardRecord()
    { 
        var data = GetNRData();
        SendAjax(data);
    }
    //取参数
    function GetNRData()
    {   
    	$("#tbResult tr:gt(0)").remove();
    	$("#PageInfo").html('');     
        var data = "action=get_noreward_changelog";
        var useridx = $("#txtPressAllID").val();
        var gametype = $("#sltGameType").val();
        var gameno = $("#txtNo").val();
        var DateBegin = $("#txtTimeBegin").val();
        var DateEnd = $("#txtTimeEnd").val();
        var ScoreMin = $("#txtScoreMin").val();
        var ScoreMax = $("#txtScoreMax").val();
        var PageSize = $.trim($("#txtPageSize").val());
        
        if(PageSize == "" || isNaN(PageSize))
        {
            PageSize = "50";
            $("#txtPageSize").val("50");
        }
        data += "&gametype=" + gametype + "&gameno="+ gameno +  "&PageSize=" + PageSize;
        if(useridx == "")
        {
            alert("必须输入全压ID");
            return;    
        }
        data += "&userid=" + useridx;
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
        if($("#cbxChangeScore").is(":checked"))
        {   
            if(ScoreMin != "")
            {
                if(isNaN(ScoreMin))
                {
                    $("#txtScoreMin").val("");
                }
                else
                {
                    data += "&scoremin=" + ScoreMin;
                }
            }
            if(ScoreMax != "")
            {
                if(isNaN(ScoreMax))
                {
                    $("#txtScoreMax").val("");
                }
                else
                {
                    data += "&scoremax=" + ScoreMax;
                }                
            }
        }
        data += "&order=" + $("#sltOrder").val() + "&ordertype=" + $("#sltOrderType").val();
        return data;
    }
    //分页
    function ajax_page_NR(page)
    {
        var data = GetNRData();
        data += "&Page=" + page;
        SendAjax(data);
    }
    function RemoveLogDayAgo(m)
    {
		var data = "action=remove_scorechange_log&m=" + m;
		SendAjax(data);
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
					case "get_gametypeid_score_option":
						$("#sltGameType").empty();
                        $(item.msg).appendTo("#sltGameType");
						return; 
					case "get_gamescore_changelog":
                        pageinfo = item.msg;
                        break;
                    case "get_noreward_changelog":
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
				if(InfoType == "get_gamescore_changelog" || InfoType == "get_noreward_changelog")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                    		"<td>" + item.ID +"</td>" +
                            "<td>" + item.UserID +"</td>" +
                            "<td>" + item.NickName +"</td>" +
                            "<td>" + item.GameName +"</td>" +
                            "<td>" + item.GameNo +"</td>" +
                            "<td>" + item.Remark +"</td>" +
                            "<td>" + item.TheTime + "</td>" +
                            "<td>" + item.PointAfter + "</td>" +
                            "<td>" + item.ChangeScore + "</td>" +
                            "<td>" + item.Bank + "</td>" +
                            "<td>" + item.LockPoints + "</td>" +
                            "<td>" + item.Exp + "</td>" +
                            "</tr>";
                 }
			}

		});
		if(tbody != "")
		{
			if(InfoType == "get_gamescore_changelog" || InfoType == "get_noreward_changelog")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
                $("#PageInfo").html(pageinfo);
            }
		}
	}
</script>

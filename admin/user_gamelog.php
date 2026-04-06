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
	<!-- 游戏记录 -->
	<div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >				
			<tr bgcolor="#FFFFFF">
				<td><label id="lblUserIdx"></label>已开奖游戏记录
					<input id="txtDataType" type="text" value="recent" style="display: none;" />
				</td>
			</tr>				
		</table>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
			<tr bgcolor="#FFFFFF">
				<td>
					用户ID:
					<input id="txtUserIdx" type="text" style="width:80px" />
					游戏
					<select id="sltGameType">
					</select>
					类型
					<select id = "sltUserType">
						<option value="0">用户</option>
						<option value="1">机器</option>
						<option value="-1">所有</option>
					</select>
					期号:
					<input id="txtNO" type="text" style="width:60px" />
					<input type="button" value="查询" id="btnSearch" class="btn-1"/> 
					页大小
				   	<input id="txtPageSize" type="text" value="20" style="width:50px" />
				   	<br>
				   	<input id="cbxTime" type="checkbox" checked="checked"/>时间
				   	<input id="txtTimeBegin" type="text" style="width:90px" value="<?php echo date('Y-m-d',strtotime('0 day')); ?>" />-
				   	<input id="txtTimeEnd" type="text" style="width:90px" value="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" />
				   	<input id="cbxExceptInner" type="checkbox" checked="checked">排除内部号
			  </td>    
			  <td width="180">
				  <select id = "sltOrder">
				  		<option value="time">时间</option>
				  		<option value="points">投注</option>
						<option value="uid">用户ID</option>
						<option value="NO">期号</option>
						<option value="hdpoints">输赢</option>
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
				<td align="center">期号</td> 
				<td align="center">校验</td>
				<td align="center">时间</td>
				<td>抽水赔率</td>
				<td>赔率</td>
				<td align="center">投注</td>
				<td align="center">得分</td>
				<td align="center">输赢分</td>
				<td>开奖结果</td>
				
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
    	var gametype = request("gametype");
    	var NO = request("no");
    	$("#lblUserIdx").html(useridx);
    	$("#txtUserIdx").val(useridx);
    	$("#txtNO").val(NO); 
    	GetGameTypeOption();
    	if(gametype != "")
    	{
    		$("#sltGameType").get(0).value = gametype;
    	}
    	else
    	{
			$("#sltGameType").get(0).selectedindex = 0;
    	}
    	GetGameLogData();
	});
    //**************************************************************************
    //取类型
    function GetGameTypeOption()
    {
		var data = "action=get_gametypeoption";
		SendAjax(data);
    }
    //查询
    $("#btnSearch").click(function(){
    	GetGameLogData();
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
        var data = "action=get_gamelog";   
        var useridx = $("#txtUserIdx").val();
        var usertype = $("#sltUserType").val();
        var gametype = $("#sltGameType").val();
        var NO = $("#txtNO").val();
        var DateBegin = $("#txtTimeBegin").val();
        var DateEnd = $("#txtTimeEnd").val();
        var PageSize = $.trim($("#txtPageSize").val());
        
        var isExceptInner = 0;
        if($("#cbxExceptInner").is(":checked"))
        	isExceptInner = 1;
        data += "&isexceptinner=" + isExceptInner;
        
        if(PageSize == "" || isNaN(PageSize))
        {
            PageSize = "20";
            $("#txtPageSize").val("20");
        }
        data += "&gametype=" + gametype + "&usertype=" + usertype +  "&PageSize=" + PageSize;
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
        if(NO != "")
        {
            if(isNaN(NO))
            {
                $("#txtNO").val("");
            }
            else
            {
                data += "&no=" + NO;
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
    //登录成功分页
    function ajax_page(page)
    {
        var data = GetData();
        data += "&Page=" + page;
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
					case "get_gametypeoption":
						$("#sltGameType").empty();
                        $(item.msg).appendTo("#sltGameType");
						return;
					case "get_gamelog":
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
				if(InfoType == "get_gamelog")
                {
					if(typeof item.yzjpl=='undefined'){
						item.yzjpl='0';
					}
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td>" + item.UserID +"</td>" +
                            "<td>" + item.NickName +"</td>" +
                            "<td>" + item.No +"</td>" +
                            "<td align='center'>" + item.verifyResult + "</td>" +
                            "<td>" + item.GameTime +"</td>" +
                            "<td>" + item.zjpl +"</td>" +
                            "<td>" + item.yzjpl +"</td>" +
                            "<td align='center'>" + item.Points + "</td>" +
                            "<td align='center'>" + item.HdPoints + "</td>" +
                            "<td align='center'>" + item.spoint + "</td>" +
							"<td>" + item.TZNo + "</td>" +
							
                            "</tr>";
                 }
			}

		});
		if(tbody != "")
		{
			if(InfoType == "get_gamelog")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
                $("#PageInfo").html(pageinfo);
            }
		}
	}
</script>

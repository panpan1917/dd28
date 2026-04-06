<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理--用户输赢排行</title>
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
	<!-- 输赢排行 -->
	<div class="bodytitle">
		<div class="bodytitleleft"></div>
		<div class="bodytitletxt">用户输赢排行</div> 
	</div>
	<div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
			<tr bgcolor="#FFFFFF">
				<td>
					用户ID:
					<input id="txtUserIdx" type="text" style="width:80px" />
					游戏
					<select id="sltGameType">
					</select>
					日期
					<select id="sltDay">
						<option value="0">今天</option>	
						<option value="1">昨天</option>
						<option value="2">前天</option>
						<option value="3">最近一周</option>
						<option value="4">最近一个月</option>
						<option value="5">最近三个月</option>
						<option value="6">最近半年</option>
					</select>
					页大小
				   	<input id="txtPageSize" type="text" value="50" style="width:50px" />
				   	<input type="button" value="查询" id="btnSearch" class="btn-1"/>
				   	<br>
				   	<input id="cbxWinLose" type="checkbox"/>输赢范围
				   	<input id="txtWinLoseMin" type="text" style="width:80px" value="1000000" />-
				   	<input id="txtWinLoseMax" type="text" style="width:80px" value="50000000" />
				   	<input id="cbxExceptInner" type="checkbox" checked="checked">排除内部号
			  </td>    
			  <td width="180">
				  <select id = "sltOrder">
				  		<option value="totalpoints">输赢</option>
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
				<td>用户ID</td>
				<td>昵称</td>
				<td><font color="red">输赢</font></td>
				<td>当前分</td>
				<td>当前银行</td>
				<td>当前投注</td>
				<td>最后登录</td>
				<td>最后IP</td>
				<td>操作</td>
			</tr>
		</table>
		<div class="fenyebar" id="PageInfo"></div>
	</div>
</body>
</html>
<script type= "text/javascript" language ="javascript">
	$(window.parent.document).attr("title","用户输赢排行");
    $(document).ready(function() {
    	GetGameTypeOption();
    	GetGameLogData();
	});
    //**************************************************************************
    //取类型
    function GetGameTypeOption()
    {
		var data = "action=get_gametypeid_option";
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
        BindClass();
    }
    //取参数
    function GetData()
    {   
    	$("#tbResult tr:gt(0)").remove();
    	$("#PageInfo").html('');     
        var data = "action=get_userwinlose_rank";
        var useridx = $("#txtUserIdx").val();
        var gametype = $("#sltGameType").val();
        var TheDay = $("#sltDay").val();
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
        data += "&gametype=" + gametype + "&day=" + TheDay +  "&PageSize=" + PageSize;
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
        if($("#cbxWinLose").is(":checked"))
        {   
            if(WinMin != "")
            {
                if(isNaN(WinMin))
                {
                    $("#txtWinLoseMin").val("");
                }
                else
                {
                    data += "&winmin=" + WinMin;
                }
            }
            if(WinMax != "")
            {
                if(isNaN(WinMax))
                {
                    $("#txtWinLoseMax").val("");
                }
                else
                {
                    data += "&winmax=" + WinMax;
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
					case "get_gametypeid_option":
						$("#sltGameType").empty();
                        $(item.msg).appendTo("#sltGameType");
						return;
					case "get_userwinlose_rank":
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
				if(InfoType == "get_userwinlose_rank")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td>" + item.UserID +"</td>" +
                            "<td>" + item.NickName +"</td>" +
                            "<td>" + item.WinLose +"</td>" +
                            "<td>" + item.Points +"</td>" +
                            "<td>" + item.Back +"</td>" +
                            "<td>" + item.LockPoints +"</td>" +
                            "<td>" + item.LastLoginTime +"</td>" +
                            "<td>" + item.LastLoginIP +"</td>" +
                            "<td>" + item.Opr + "</td>" +
                            "</tr>";
                 }
			}

		});
		if(tbody != "")
		{
			if(InfoType == "get_userwinlose_rank")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
                $("#PageInfo").html(pageinfo);
            }
		}
	}
</script>

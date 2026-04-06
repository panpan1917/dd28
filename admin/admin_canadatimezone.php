<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理--加拿大时差设置</title>
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
		<div class="bodytitletxt">加拿大游戏时差设置</div>
	</div>
    <div class="categorylist" id="div_GameConfig">
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >				
			<tr bgcolor="#FFFFFF">
				<td colspan="2">说明:夏令时:当地时间3月第2个周日2:00开始,冬令时:当地时间11月第1个周日2:00开始</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td width="110">年份:</td>
				<td><input type="text" id="txtYear" style="width:60px" />如2016</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>夏令时开始时间:</td>
				<td><input type="text" id="txtBeginTime" style="width:150px" value="<?php echo date('Y-03-10 02:00:00'); ?>" /></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>冬令时开始时间:</td>
				<td><input type="text" id="txtEndTime" style="width:150px" value="<?php echo date('Y-11-03 02:00:00'); ?>" /></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td></td>
				<td><input type="button" value="添加" id="btnSave" class="btn-1" /></td>
			</tr>                
		</table>
		<table id='tbResult' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			<tr bgcolor="#f5fafe">
				<td align="center">年份</td>
				<td align="center">夏令时开始时间</td>
				<td align="center">冬令时开始时间</td> 
				<td align="center">操作</td>
			</tr>
		</table>
		<div class="fenyebar" id="PageInfo"></div>
	</div>
</form>    
</body>
<script type= "text/javascript" language ="javascript">
    $(window.parent.document).attr("title","加拿大游戏时差设置");
    $(document).ready(function() {
        GetCanadaTimeZone();
        //***************************************************************
		//保存设置
		$("#btnSave").click(function(){
			SaveTimeZone();
			GetCanadaTimeZone();
		});
         
	});
    //*************************************************************************************************** 
    //取记录
    function GetCanadaTimeZone()
    { 
        var data = GetData();
        SendAjax(data);
    }
    //取参数
    function GetData()
    {   
    	$("#tbResult tr:gt(0)").remove();
    	$("#PageInfo").html('');     
        var data = "action=get_canadatimezone";
        
        data += "&PageSize=50";
        return data;
    }
    //分页
    function ajax_page(page)
    {
        var data = GetData();
        data += "&Page=" + page;
        SendAjax(data);
    }
    //保存设置
    function SaveTimeZone()
    {
        var data = "action=save_canadatimezone";
        var theYear = $.trim($("#txtYear").val());
        var beginTime = $.trim($("#txtBeginTime").val());
        var endTime = $.trim($("#txtEndTime").val());
        
        if(theYear == "" || isNaN(theYear)  ) 
        {
			alert("请正确填写年份");
			return false;
        }
        if(beginTime == "" )
        {
			alert("请填写夏令时开始时间");
			return false;
        }
        if(endTime == "")
        {
			alert("请填写冬令时开始时间");
			return false;
        }
        
        data += "&year=" + theYear + "&begintime=" +beginTime + "&endtime=" + endTime;
        SendAjax(data);
    }
    //删除
    function RemoveYear(year)
    {
		var data = "action=remove_canadatimezone&year=" + year;
		SendAjax(data);
		GetCanadaTimeZone();
    }
    //***************************************************************************************************
	//ajax处理
	function SendAjax(SendData)
	{
		var PostURL = "sgamemanage.php";
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
					case "get_canadatimezone":
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
				if(InfoType == "get_canadatimezone")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td align='center'>" + item.Year +"</td>" +
                            "<td align='center'>" + item.BeginTime + "</td>" + 
                            "<td align='center'>" + item.EndTime + "</td>" + 
                            "<td align='center'>" + item.Opr + "</td>" +
                            "</tr>";
                 }
			}

		});
		if(tbody != "")
		{
			if(InfoType == "get_canadatimezone")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
                $("#PageInfo").html(pageinfo);
            }
		}
	}
    
</script>

</html>

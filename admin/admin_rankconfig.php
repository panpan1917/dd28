<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>排行奖励设置-系统管理</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta name="GENERATOR" content="MSHTML 6.00.3790.4275">
	<link rel="stylesheet" type="text/css" href="images/css_body.css">
	<link rel="stylesheet" type="text/css" href="images/window.css">
	<script type="text/javascript" src="images/jquery.js"></script>
</head>
<body>
	<div class="bodytitle">
		<div class="bodytitleleft"></div>
		<div class="bodytitletxt">排行奖励设置</div>
	</div>
	<!-- 排行奖励设置 -->
	<div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >				
			<tr bgcolor="#FFFFFF">
				<td>
					排行奖励设置
                    <input type="button" value="刷新" id="btnRefresh" class="btn-1" />
				</td>
			</tr>      
		</table>
		<table id='tbResult' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			<tr bgcolor="#f5fafe">
				<td align="center">排名</td>
				<td align="center">奖励</td>
				<td align="center">操作</td>
			</tr>
		</table> 	
	</div>
</body>
</html>
<script type= "text/javascript" language ="javascript">
	$(window.parent.document).attr("title","排行奖励设置");
    $(document).ready(function() {
    	GetResult();
    	//刷新	
		$("#btnRefresh").click(function(){
            GetResult();
		});
	});
	function GetResult()
	{
		var data = "action=get_ranklevel_info";
		SendAjax(data);
	}
	function save(id)
	{
		var RankPoint = $.trim($("#rankpoint_" + id).val());
		var data = "action=save_rankpoint_config&id=" + id 
					+ "&rankpoint=" + parseInt(RankPoint);
		SendAjax(data);
	}
    //****************************************************************************************
    //公共函数 
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
					case "get_ranklevel_info":
						break;
					default:
						alert(item.msg);
						return;
						break;
				}
			}
			else
			{
				if(InfoType == "get_ranklevel_info")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td align='center'>" + item.RankNum +"</td>" +
                            "<td align='center'>" + item.RankPoint + "</td>" +
                            "<td align='center'>" + item.Opr + "</td>" + 
                            "</tr>";
                 }
			}
		});
		if(tbody != "")
		{
			if(InfoType == "get_ranklevel_info")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
            }
		}
	}
</script>

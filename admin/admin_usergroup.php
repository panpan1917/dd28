<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户等级管理-系统管理</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta name="GENERATOR" content="MSHTML 6.00.3790.4275">
	<link rel="stylesheet" type="text/css" href="images/css_body.css">
	<link rel="stylesheet" type="text/css" href="images/window.css">
	<script type="text/javascript" src="images/jquery.js"></script>
</head>
<body>
	<div class="bodytitle">
		<div class="bodytitleleft"></div>
		<div class="bodytitletxt">用户等级管理</div>
	</div>
	<!-- 用户等级管理 -->
	<div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >				
			<tr bgcolor="#FFFFFF">
				<td>
					用户等级设置
                    <input type="button" value="刷新" id="btnRefresh" class="btn-1" />
				</td>
			</tr>      
		</table>
		<table id='tbResult' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			<tr bgcolor="#f5fafe">
				<td align="center">ID</td>
				<td align="center">等级</td>
				<td align="center">经验范围</td> 
				<td align="center">每日救济</td>
				<td align="center">兑奖折扣</td>
				<td align="center">上线1层提成</td>
				<td align="center">上线2层提成</td>
				<td align="center">上线3层提成</td>
				<td align="center">操作</td>
			</tr>
		</table> 	
	</div>
</body>
</html>
<script type= "text/javascript" language ="javascript">
	$(window.parent.document).attr("title","用户等级管理");
    $(document).ready(function() {
    	GetResult();
    	//刷新	
		$("#btnRefresh").click(function(){
            GetResult();
		});
	});
	function GetResult()
	{
		var data = "action=get_userlevel_info";
		SendAjax(data);
	}
	function save(id)
	{
		var ExpMin = $.trim($("#exp_min_" + id).val());
		var ExpMax = $.trim($("#exp_max_" + id).val());
		var Jiuji = $.trim($("#jiuji_" + id).val());
		var RewardRate = $.trim($("#rewardrate_" + id).val());
		var Up1Rate = $.trim($("#up1_" + id).val());
		var Up2Rate = $.trim($("#up2_" + id).val());
		var Up3Rate = $.trim($("#up3_" + id).val());
		var data = "action=save_usergroup_config&id=" + id 
					+ "&expmin=" + parseInt(ExpMin)
					+ "&expmax=" + parseInt(ExpMax)
					+ "&jiuji=" + parseInt(Jiuji)
					+ "&rewardrate=" + parseFloat(RewardRate)
					+ "&up1rate=" + parseFloat(Up1Rate)
					+ "&up2rate=" + parseFloat(Up2Rate)
					+ "&up3rate=" + parseFloat(Up3Rate);
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
					case "get_userlevel_info":
						break;
					default:
						alert(item.msg);
						return;
						break;
				}
			}
			else
			{
				if(InfoType == "get_userlevel_info")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td align='center'>" + item.ID +"</td>" +
                            "<td align='center'>" + item.Level + "</td>" + 
                            "<td align='center'>" + item.Exp + "</td>" + 
                            "<td align='center'>" + item.Jiuji + "</td>" +
                            "<td align='center'>" + item.RewardRate + "</td>" + 
                            "<td align='center'>" + item.Up1Rate + "</td>" + 
                            "<td align='center'>" + item.Up2Rate + "</td>" + 
                            "<td align='center'>" + item.Up3Rate + "</td>" + 
                            "<td align='center'>" + item.Opr + "</td>" + 
                            "</tr>";
                 }
			}
		});
		if(tbody != "")
		{
			if(InfoType == "get_userlevel_info")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
            }
		}
	}
</script>

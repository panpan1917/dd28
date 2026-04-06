<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
login_check( "gamegl" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理--手动干预</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta name="GENERATOR" content="MSHTML 6.00.3790.4275">
	<link rel="stylesheet" type="text/css" href="images/css_body.css">
	<link rel="stylesheet" type="text/css" href="images/window.css">
	<script type="text/javascript" src="images/jquery.js"></script>
</head>
<body>
	<!-- 手动干预 -->
	<div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >				
			<tr bgcolor="#FFFFFF">
				<td width="100">期号</td>
				<td><label id="lblNo"></label>
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>游戏标识</td>
				<td><label id="lblGameType"></label>
				</td>
			</tr>
			
			
			<tr bgcolor="#FFFFFF" id="trResult">
				<td>结果号码</td>
				<td><input id="txtResult" type="text" style="width:80px" />
				<input type="button" value="取开奖号" id="btnGetRewardNum" class="btn-1"/>多次点击可随机更换
				</td>
			</tr>	
			<tr bgcolor="#FFFFFF" id="trNum1">
				<td>开奖号码1</td>
				<td><input id="txtNum1" type="text" style="width:80px" readonly="true" />
				</td>
			</tr>
			<tr bgcolor="#FFFFFF" id="trNum2">
				<td>开奖号码2</td>
				<td><input id="txtNum2" type="text" style="width:80px" readonly="true" />
				</td>
			</tr>
			<tr bgcolor="#FFFFFF" id="trNum3" style="display: none;">
				<td>开奖号码3</td>
				<td><input id="txtNum3" type="text" style="width:80px" readonly="true" />
				</td>
			</tr>
			
			
			<tr bgcolor="#FFFFFF" id="trKgNo" style="display: none;">
				<td>官方开奖号码</td>
				<td><input id="txtKgNo" type="text" style="width:700px" />用,分隔,必须半角逗号
				</td>
			</tr>	
			<tr bgcolor="#FFFFFF">
				<td></td>
				<td>
					<input type="button" value="马上开奖" id="btnOpen" class="btn-1"/>
					
				</td>
			</tr>										
		</table>
	</div>
</body>
</html>
<script type= "text/javascript" language ="javascript">
	$(window.parent.document).attr("title","游戏开奖");
    $(document).ready(function() {
    	var gametype = request("gametype");
    	var no = request("no");
    	$("#lblNo").html(no);
    	$("#lblGameType").html(gametype);
    	DisplayTr(gametype);
    	//开奖	
		$("#btnOpen").click(function(){
            OpenGame();
		}); 
		//取开奖号
		$("#btnGetRewardNum").click(function(){
            GetRewardNum();
		});
	});
	function DisplayTr(t)
	{
		if(t == "gamefast10" || t == "gamefast11" || t=="gamefast16" || t=="gamefast22" || t=="gamefast28" || t=="gamefast36" || t=="gamefastgyj"){
			$("#trNum1").show();
			$("#trNum2").show();
			$("#trResult").show();
		}else{
			$("#trNum1").hide();
			$("#trNum2").hide();
			$("#trResult").hide();
		}

		
		if(t=="gamefast10" || t=="gamefast22" || t=="gamefast16" || t == "gamefast28" || t=="gamefast36"){
			$("#trNum3").show();
		}else{
			$("#trNum3").hide();
		}


		if(t == "gamefast11" || t=="gamefast16" || t=="gamefast28" || t=="gamefast36"){
			$("#trKgNo").hide();
		}else{
			$("#trKgNo").show();
			if(t=="gamefast10" || t=="gamefast22" || t=="gamefastgyj"){
				$("#trKgNo").attr("readonly","readonly");
			}else{
				$("#trKgNo").attr("readonly","");
			}
		}

		
	}
	function OpenGame()
	{
		var data = "action=open_game";
		var NO = $.trim($("#lblNo").html());
		var GameType = $.trim($("#lblGameType").html());
		var Result = $.trim($("#txtResult").val());
		var Num1 = $.trim($("#txtNum1").val());
		var Num2 = $.trim($("#txtNum2").val()); 
		var Num3 = $.trim($("#txtNum3").val()); 
		var kgno = $.trim($("#txtKgNo").val());
		
		data += "&no=" + NO + "&gametype=" + GameType + "&result=" + Result +"&num1=" + Num1 + "&num2=" + Num2 + "&num3=" + Num3 + "&kgno=" + kgno;
		SendAjax(data);
	}
	//取开奖号
	function GetRewardNum()
	{
		var ResultNo = $.trim($("#txtResult").val());
		var GameType = $.trim($("#lblGameType").html());
		var data = "action=get_openno_result";
		if(ResultNo == "" || isNaN(ResultNo))
		{
			alert("结果号码错误!");
			return;
		}
		if(GameType == "gamefast28" || GameType == "gamefast16" || GameType == "gamefast11" || GameType == "gamefast10" || GameType == "gamefast22" || GameType == "gamefast36" || GameType == "gamefastgyj")
		{
			data += "&gametype=" + GameType + "&resultno=" + ResultNo;
			SendAjax(data);
		}
	}
	//检测开奖号
	function CheckKgNo(kgno,len)
	{
		var arrKgNo = kgno.split(',');
		if(arrKgNo.length != len)
		{
			alert("官方开奖号错误，必须要"+ len +"个开奖号，以逗号分隔!");
			return false;
		}
		for(var i=0;i < arrKgNo.length;i++)
		{
			if(arrKgNo[i] == "" || isNaN(arrKgNo[i]))
			{
				alert("官方开奖号错误，开奖号必须为数字!");
				return false;
			}
		}
		return true;
	}
	//取随机数
	function GetRandomNum(Min,Max)
	{   
		var Range = Max - Min;   
		var Rand = Math.random();   
		return(Min + Math.round(Rand * Range));   
	} 
    //****************************************************************************************
    //公共函数
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
					case "get_openno_result":
						break;
					case "open_game":
						alert("开奖成功，请关闭此页面并在原页面重新查询!");
						break;
					default:
						alert(item.msg);
						return;
						break;
				}
			}
			if(InfoType == "get_openno_result")
			{
				$("#txtResult").val(item.ResultNo);
				$("#txtNum1").val(item.Num1);
				$("#txtNum2").val(item.Num2);
				$("#txtNum3").val(item.Num3);
				$("#txtKgNo").val(item.kgNo);
			}

		});
	}
</script>

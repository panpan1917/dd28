<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>兑换卡类型管理</title>
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
		<div class="bodytitletxt">兑换卡类型管理</div>
	</div>
    <div class="categorylist" id="div_GameConfig">
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >	
			<tr bgcolor="#FFFFFF">
				<td width="110">卡类型:</td>
				<td><input type="text" id="txtCardType" style="width:60px" />数字，不可重复</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>面额(￥):</td>
				<td><input type="text" id="txtCardRMB" style="width:60px"  />元</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>卡名称:</td>
				<td><input type="text" id="txtCardName" style="width:150px"  /></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>备注:</td>
				<td><input type="text" id="txtRemark" style="width:150px"  /></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td></td>
				<td><input type="button" value="添加" id="btnSave" class="btn-1" /></td>
			</tr>                
		</table>
		<table id='tbResult' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			<tr bgcolor="#f5fafe">
				<td align="center">卡类型</td>
				<td align="center">面额</td>
				<td align="center">卡名称</td> 
				<td align="center">备注</td>
				<td align="center">操作</td>
			</tr>
		</table>
		<div class="fenyebar" id="PageInfo"></div>
	</div>
</form>    
</body>
<script type= "text/javascript" language ="javascript">
    $(window.parent.document).attr("title","兑换卡类型管理");
    $(document).ready(function() {
        GetExchangeCard();
        //***************************************************************
		//保存设置
		$("#btnSave").click(function(){
			SaveCardType();
			GetExchangeCard();
		});
         
	});
    //*************************************************************************************************** 
    //取记录
    function GetExchangeCard()
    { 
        var data = GetData();
        SendAjax(data);
    }
    //取参数
    function GetData()
    {   
    	$("#tbResult tr:gt(0)").remove();
    	$("#PageInfo").html('');     
        var data = "action=get_exchangecardtype";
        
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
    function SaveCardType()
    {
        var data = "action=save_exhcangecardtype";
        var cardtype = $.trim($("#txtCardType").val());
        var cardrmb = $.trim($("#txtCardRMB").val());
        var cardname = $.trim($("#txtCardName").val());
        var remark = $.trim($("#txtRemark").val());
        
        if(cardtype == "" || isNaN(cardtype)  ) 
        {
			alert("请正确填写卡类型，必须数字");
			return false;
        }
        if(cardrmb == "" || isNaN(cardrmb) )
        {
			alert("请填写面额，必须整数");
			return false;
        }
        if(cardname == "")
        {
			alert("请填写卡名称");
			return false;
        }
        
        data += "&cardtype=" + cardtype + "&cardrmb=" +cardrmb + "&cardname=" + cardname + "&remark=" + remark;
        SendAjax(data);
    }
    //删除
    function RemoveCardType(cardtype)
    {
		var data = "action=remove_cardtype&cardtype=" + cardtype;
		SendAjax(data);
		GetExchangeCard();
    }
    //***************************************************************************************************
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
					case "get_exchangecardtype":
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
				if(InfoType == "get_exchangecardtype")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td align='center'>" + item.CardType +"</td>" +
                            "<td align='center'>" + item.CardRMB + "</td>" + 
                            "<td align='center'>" + item.CardName + "</td>" + 
                            "<td align='center'>" + item.Remark + "</td>" +
                            "<td align='center'>" + item.Opr + "</td>" +
                            "</tr>";
                 }
			}

		});
		if(tbody != "")
		{
			if(InfoType == "get_exchangecardtype")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
                $("#PageInfo").html(pageinfo);
            }
		}
	}
    
</script>

</html>

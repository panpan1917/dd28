<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理--编辑代理</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta name="GENERATOR" content="MSHTML 6.00.3790.4275">
	<link rel="stylesheet" type="text/css" href="images/css_body.css">
	<link rel="stylesheet" type="text/css" href="images/window.css">
	<script type="text/javascript" src="images/jquery.js"></script>
</head>
<body>
	<!-- 添加代理 -->
	<div class="categorylist" id="div_addagent" style="display: none;">
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >				
			<tr bgcolor="#FFFFFF">
				<td width="120">代理名称</td>
				<td width="200">
					<input id="txtAgentName" type="text" style="width:180px" />	
				</td>
				<td></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>绑定用户ID</td>
				<td> 
					<input id="txtUserID" type="text" style="width:110px" />
					<input type="button" value="检测" id="btnCheckUserID" class="btn-1"/>
				</td>
				<td>绑定用户ID</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>进卡折扣</td>
				<td><input id="txtBuyCardRate" type="text" value="1.00" style="width:100px" />
				</td>
				<td>如98折则输入0.98,1表示不打折扣</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>收卡折扣</td>
				<td><input id="txtRecCardRate" type="text" value="1.00" style="width:100px" />
				</td>
				<td>如98折则输入0.98,1表示不打折扣</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>收卡利润</td>
				<td><input id="txtRecCardProfitRate" type="text" value="0.00" style="width:100px" />
				</td>
				<td>0表示没利润，利润是1%请输入0.01</td>
			</tr>	
			
			
			<tr bgcolor="#FFFFFF">
				<td>铺货分</td>
				<td><input id="txtDistributeMoney" type="text" value="10000000" style="width:100px" />
				</td>
				<td>代理商提现，余额只有在这基础之上才可以</td>
			</tr>	
			<tr bgcolor="#FFFFFF">
				<td>是否可收卡</td>
				<td><input id="cbxCanRecCard" type="checkbox" checked="checked">可收卡
				</td>
				<td></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>是否推荐</td>
				<td><input id="cbxIsRecommend" type="checkbox" checked="checked">推荐
				</td>
				<td></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>是否可用</td>
				<td><input id="cbxState" type="checkbox" checked="checked">可用
				</td>
				<td></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td></td>
				<td colspan="2"><input type="button" value="确认提交" id="btnCreate" class="btn-1"/>
				</td>
			</tr>										
		</table>
	</div>
	<!-- 编辑代理 -->
	<div class="categorylist" id="div_editagent" style="display: none;">
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >				
			<tr bgcolor="#FFFFFF">
				<td width="120">记录ID</td>
				<td width="200"> 
					<label id="lblEID"></label>	
				</td>
				<td></td>
			</tr>    
			<tr bgcolor="#FFFFFF">
				<td>绑定用户ID</td>
				<td> 
					<label id="lblEUserID"></label>	
				</td>
				<td></td>
			</tr>	
			<tr bgcolor="#FFFFFF">
				<td>绑定时间</td>
				<td> 
					<label id="lblEAddTime"></label>	
				</td>
				<td></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>当前总分</td>
				<td> 
					<label id="lblETotalPoints"></label>	
				</td>
				<td></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>代理名称</td>
				<td>
					<input id="txtEAgentName" type="text" style="width:180px" />	
				</td>
				<td></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>进卡折扣</td>
				<td><input id="txtEBuyCardRate" type="text" value="1.00" style="width:100px" />
				</td>
				<td>如98折则输入0.98,1表示不打折扣</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>收卡折扣</td>
				<td><input id="txtERecCardRate" type="text" value="1.00" style="width:100px" />
				</td>
				<td>如98折则输入0.98,1表示不打折扣</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>收卡利润</td>
				<td><input id="txtERecCardProfitRate" type="text" value="0.00" style="width:100px" />
				</td>
				<td>0表示没利润，利润是1%请输入0.01</td>
			</tr>	
			
			
			
			<tr bgcolor="#FFFFFF">
				<td>铺货分</td>
				<td><input id="txtEDistributeMoney" type="text" value="0.01" style="width:100px" />
				</td>
				<td>代理商提现，余额只有在这基础之上才可以</td>
			</tr>	
			<tr bgcolor="#FFFFFF">
				<td>是否可收卡</td>
				<td><input id="cbxECanRecCard" type="checkbox" checked="checked">可收卡
				</td>
				<td></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>是否推荐</td>
				<td><input id="cbxEIsRecommend" type="checkbox" checked="checked">推荐
				</td>
				<td></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>是否可用</td>
				<td><input id="cbxEState" type="checkbox" checked="checked">可用
				</td>
				<td></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td></td>
				<td colspan="2"><input type="button" value="确认提交" id="btnEdit" class="btn-1"/>
				</td>
			</tr>										
		</table>
	</div>
</body>
</html>
<script type= "text/javascript" language ="javascript">
    $(document).ready(function() {
    	var action = request("act");
    	var id = request("id");
    	if(action == "add")
    	{
    		$("#div_addagent").show();
		}
    	else
    	{
    		$("#div_editagent").show();
    		GetDetail(id);
		}    
    	//代理生成	
		$("#btnCreate").click(function(){
            CreateAccount();
		});
		//代理编辑	
		$("#btnEdit").click(function(){
            EditAccount();
		});
		//检测ID
		$("#btnCheckUserID").click(function(){
			var userid = $("#txtUserID").val();
			if(userid == "" || isNaN(userid))
			{
				alert("请输入要绑定的用户数字ID");
				return;
			}
            var data = "action=check_agentuserid&userid=" + userid;
            SendAjax(data);
		});
	});
	function CreateAccount()
	{
		var data = "action=addnew_agent";
		var UserID = $.trim($("#txtUserID").val()); 
		var AgentName = $.trim($("#txtAgentName").val());
		var bcRate = $.trim($("#txtBuyCardRate").val()); 
		var rcRate = $.trim($("#txtRecCardRate").val());
		var rcpfRate = $.trim($("#txtRecCardProfitRate").val());
		
		var dbMoney = $.trim($("#txtDistributeMoney").val());
		var canRecCard = "0";
		var isRecommend = "0";
		var State = "0";
		
		if($("#cbxCanRecCard").is(":checked")) 
        	canRecCard = "1";
        if($("#cbxIsRecommend").is(":checked")) 
        	isRecommend = "1";
        if($("#cbxState").is(":checked")) 
        	State = "1";
        if(AgentName.indexOf("&") > 0)
        {
			alert("代理名字不能包含&符号");
			return;
        }
        if(bcRate == "" || parseFloat(bcRate) > 1 || parseFloat(bcRate) < 0 )
        {
			alert("进卡折扣错误，要介于0-1之间");
        }
        if(rcRate == "" || parseFloat(rcRate) > 1 || parseFloat(rcRate) < 0 )
        {
			alert("收卡折扣错误，要介于0-1之间");
        }
        if(rcpfRate == "" || parseFloat(rcpfRate) > 1 || parseFloat(rcpfRate) < 0 )
        {
			alert("进卡利润错误，要介于0-1之间");
        }
		
		data += "&userid=" + UserID + "&bcrate=" + bcRate + "&rcrate=" + rcRate + "&rcpfrate=" + rcpfRate + "&bcmoney=" + dbMoney
				 + "&canreccard=" + canRecCard + "&isrecommend=" + isRecommend + "&state="+ State +"&agentname=" + AgentName;
		SendAjax(data);
	}
	function EditAccount()
	{
		var data = "action=edit_agent";
		var RecID = $.trim($("#lblEID").html()); 
		var AgentName = $.trim($("#txtEAgentName").val());
		var bcRate = $.trim($("#txtEBuyCardRate").val()); 
		var rcRate = $.trim($("#txtERecCardRate").val());
		var rcpfRate = $.trim($("#txtERecCardProfitRate").val());

		var dbMoney = $.trim($("#txtEDistributeMoney").val());
		var canRecCard = "0";
		var isRecommend = "0";
		var State = "0";
		
		if($("#cbxECanRecCard").is(":checked")) 
        	canRecCard = "1";
        if($("#cbxEIsRecommend").is(":checked")) 
        	isRecommend = "1";
        if($("#cbxEState").is(":checked")) 
        	State = "1";
        if(AgentName.indexOf("&") > 0)
        {
			alert("代理名字不能包含&符号");
			return;
        }
        if(bcRate == "" || parseFloat(bcRate) > 1 || parseFloat(bcRate) < 0 )
        {
			alert("进卡折扣错误，要介于0-1之间");
        }
        if(rcRate == "" || parseFloat(rcRate) > 1 || parseFloat(rcRate) < 0 )
        {
			alert("收卡折扣错误，要介于0-1之间");
        }
        if(rcpfRate == "" || parseFloat(rcpfRate) > 1 || parseFloat(rcpfRate) < 0 )
        {
			alert("进卡利润错误，要介于0-1之间");
        }

		
		data += "&recid=" + RecID + "&bcrate=" + bcRate + "&rcrate=" + rcRate + "&rcpfrate=" + rcpfRate + "&bcmoney=" + dbMoney 
				 + "&canreccard=" + canRecCard + "&isrecommend=" + isRecommend + "&state="+ State +"&agentname=" + AgentName;
		SendAjax(data);
	}
	function GetDetail(id)
	{
		var data = "action=get_agent_detail&id=" + id;
		SendAjax(data);
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
					case "get_agent_detail":
						break;
					default:
						alert(item.msg);
						return;
						break;
				}
			}
			if(InfoType == "get_agent_detail")
			{
				$("#lblEID").html(item.RecID); 
				$("#lblEUserID").html(item.UserID);
				$("#lblETotalPoints").html(item.TotalPoints);
				$("#lblEAddTime").html(item.AddTime);
				$("#txtEAgentName").val(item.AgentName);
				$("#txtEBuyCardRate").val(item.BuyCardRate);
				$("#txtERecCardRate").val(item.RecCardRate);
				$("#txtERecCardProfitRate").val(item.RecCardProfitRate);

				$("#txtEDistributeMoney").val(item.DistributeMoney);
				
				if(item.CanRecCard == "0")
                    $("#cbxECanRecCard").attr("checked",false);
                else
                    $("#cbxECanRecCard").attr("checked",true);
                
                if(item.IsRecommend == "0")
                    $("#cbxEIsRecommend").attr("checked",false);
                else
                    $("#cbxEIsRecommend").attr("checked",true);
                    
                if(item.State == "0")
                    $("#cbxEState").attr("checked",false);
                else
                    $("#cbxEState").attr("checked",true);
			}

		});
	}
</script>

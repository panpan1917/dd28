<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理--发送短信</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta name="GENERATOR" content="MSHTML 6.00.3790.4275">
	<link rel="stylesheet" type="text/css" href="images/css_body.css">
	<link rel="stylesheet" type="text/css" href="images/window.css">
	<script type="text/javascript" src="images/jquery.js"></script>
</head>
<body>
	<!-- 发送短信 -->
	<div class="bodytitle">
		<div class="bodytitleleft"></div>
		<div class="bodytitletxt">短信发送</div>
	</div>
	<div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >				
			<tr bgcolor="#FFFFFF">
				<td width="20%">发送方式</td>
				<td>
					<label>
                        <input name="rgpType" id="rgp_sys" type="radio" value="0" checked >
                        系统消息
					</label>
                    &nbsp;&nbsp;
                    <label>
                        <input name="rgpType" id="rgp_user" type="radio" value="1">
                        指定用户
                    </label>
                    <label>
                        <input name="rgpType" id="rgp_vip" type="radio" value="2">
                        VIP用户
                    </label>
				</td>
			</tr>
			<tr bgcolor="#FFFFFF" id="trUser_ID" style="display: none;">
				<td>收信人ID</td>
				<td> <input id="txtUserID" type="text" style="width:100px" />
				</td>
			</tr>	
			<tr bgcolor="#FFFFFF">
				<td>主题</td>
				<td><input id="txtSubject" type="text" style="width:350px" />
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>正文</td>
				<td><textarea msg="请填写短信正文" atatype="Require" rows="7" cols="50" id="txtContent"></textarea>
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td></td>
				<td><input type="button" value="发送" id="btnSend" class="btn-1"/>
				</td>
			</tr>										
		</table>
	</div>
</body>
</html>
<script type= "text/javascript" language ="javascript">
    $(document).ready(function() {
    	$("input[name=rgpType]").click(function(){
    		if($(":radio[name='rgpType']:checked").val() == "0" || $(":radio[name='rgpType']:checked").val() == "2")
    		 {
    		 	 $("tr[id*='trUser_']").hide();
    		 }
    		 else
    		 {
				 $("tr[id*='trUser_']").show();
    		 }
		});
    		
		$("#btnSend").click(function(){
            SendMsg();
		});
	});
	function SendMsg()
	{
		var data = "action=send_msg";
		var MsgType = $(":radio[name='rgpType']:checked").val();
		var UserID = $.trim($("#txtUserID").val()); 
		var Subject = $.trim($("#txtSubject").val());
		var Content = $.trim($("#txtContent").val());
		
		data += "&type=" + MsgType +  "&userid=" + UserID + "&subject=" + Subject + "&content=" + Content;
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
					case "send_msg":
						alert(item.msg);
						break;
					default:
						alert(item.msg);
						return;
						break;
				}
			}

		});
	}
</script>

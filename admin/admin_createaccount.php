<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理--帐号生成</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta name="GENERATOR" content="MSHTML 6.00.3790.4275">
	<link rel="stylesheet" type="text/css" href="images/css_body.css">
	<link rel="stylesheet" type="text/css" href="images/window.css">
	<script type="text/javascript" src="images/jquery.js"></script>
</head>
<body>
	<div class="bodytitle">
		<div class="bodytitleleft"></div>
		<div class="bodytitletxt">帐号生成</div>
	</div>
	<!-- 帐号生成 -->
	<div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >				
			<tr bgcolor="#FFFFFF">
				<td width="100">类型</td>
				<td width="150">
					<label>
                        <input name="rgpUserType" id="rgp_user" type="radio" value="0" checked >
                        用户
					</label>
                    &nbsp;&nbsp;
                    <label>
                        <input name="rgpUserType" id="rgp_robot" type="radio" value="1">
                        机器人
                    </label>
				</td>
				<td></td>
			</tr>
			<tr bgcolor="#FFFFFF" id="trUser_ID">
				<td>用户ID</td>
				<td> <input id="txtUserID" type="text" style="width:100px" />
				</td>
				<td>数字ID</td>
			</tr>	
			<tr bgcolor="#FFFFFF" id="trUser_Name">
				<td>用户名</td>
				<td><input id="txtUserName" type="text" style="width:100px" />
				</td>
				<td></td>
			</tr>
			<tr bgcolor="#FFFFFF" id="trUser_NickName">
				<td>昵称</td>
				<td><input id="txtNickName" type="text" style="width:100px" />
				</td>
				<td></td>
			</tr>
			<tr bgcolor="#FFFFFF" id="trUser_pwd">
				<td>密码</td>
				<td><input id="txtPassword" type="text" style="width:100px" />
				</td>
				<td></td>
			</tr>
			<tr bgcolor="#FFFFFF" id="trRobot_Count" style="display: none;">
				<td>生成数量</td>
				<td><input id="txtCount" type="text" style="width:100px" />
				</td>
				<td>200以内一次,默认密码是Hello_Jacky_56789</td>
			</tr>	
			<tr bgcolor="#FFFFFF">
				<td></td>
				<td colspan="2"><input type="button" value="马上生成" id="btnCreate" class="btn-1"/>
				</td>
			</tr>										
		</table><br>
		
		<table id="createlog" class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
			<tr bgcolor="#FFFFFF">
				<td>用户ID</td>
				<td>用户昵称</td>
				<td>用户手机号</td>
				<td>创建时间</td>
				<td>管理员ID</td>
				<td>管理员用户名</td>
			</tr>
		</table>
		
		
	</div>
</body>
</html>
<script type= "text/javascript" language ="javascript">
    $(document).ready(function() {
    	$("input[name=rgpUserType]").click(function(){
    		if($(":radio[name='rgpUserType']:checked").val() == "0")
    		 {
    		 	 $("tr[id*='trUser_']").show();
				 $("tr[id*='trRobot_']").hide();
				 $("#createlog").show();
    		 }
    		 else
    		 {
				 $("tr[id*='trUser_']").hide();
				 $("tr[id*='trRobot_']").show();
				 $("#createlog").hide();
    		 }
		});
    	//帐号生成	
		$("#btnCreate").click(function(){
            CreateAccount();
		});

		GetCreateLog();
	});

	function GetCreateLog(){
		var data = "action=get_adduser_log";
		var pagenum = 50;
		$("#createlog tr:gt(0)").remove();
		data += "&pagenum=" + pagenum;
		SendAjax(data);
	}
	
	function CreateAccount()
	{
		var data = "action=addnew_account";
		var AccountType = $(":radio[name='rgpUserType']:checked").val();
		var UserID = $.trim($("#txtUserID").val()); 
		var UserName = $.trim($("#txtUserName").val());
		var NickName = $.trim($("#txtNickName").val()); 
		var Pwd = $.trim($("#txtPassword").val()); 
		var UserCount = $.trim($("#txtCount").val());
		
		data += "&type=" + AccountType +  "&userid=" + UserID + "&username=" + UserName + "&nickname=" + NickName + "&pwd=" + Pwd + "&cnt=" + UserCount;
		SendAjax(data);
		if(AccountType == 0){
			GetCreateLog();
		}
	}
    //****************************************************************************************
    //公共函数 
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
					case "addnew_account":
						alert(item.msg);
						break;
					case "get_adduser_log":
						return;
						break;
					default:
						alert(item.msg);
						return;
						break;
				}
			}
			else
			{
						tbody += "<tr bgcolor='#FFFFFF'>" +
                                "<td>" + item.uid + "</td>" +
                                "<td>" + item.nickname + "</td>" +
                                "<td>" + item.mobile + "</td>" +
                                "<td>" + item.createtime + "</td>" +
                                "<td>" + item.adminid + "</td>" +
                                "<td>" + item.adminname + "</td>" +
                            "</tr>";   
			}
		});

		$("#createlog").append(tbody);
	}
</script>

<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/xhtml1/dtD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理--批用户查询</title>
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
		<div class="bodytitletxt">批用户查询</div>
	</div>
	<div>
		<div>
			<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >				
				<tr bgcolor="#FFFFFF">
					<td colspan="8">
						用户ID:
						<input id="txtUserIdx" type="text" style="width:100px" />
						<input type="button" name="btnSearch" value="查询" id="btnSearch" class="btn-1" />					
						&nbsp;
						状态
						<select id = "sltStatus">
                            <option value="-1">所有</option>
                            <option value="0">正常</option>
                            <option value="1">冻结</option>
                        </select>
						用户类型
						<select id = "sltUserType">
							<option value="0">用户</option>
                            <option value="-1">所有</option>
                            <option value="1">机器</option>
                        </select>
						<input id="cbxExceptInner" type="checkbox" >排除内部号
						&nbsp;&nbsp;每页
                        <input type="text" id="txtPageSize" style="width:30px" value="20" />
条 </td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td width="80">昵称</td>
				  	<td width="180"><input id="txtNickName" type="text" style="width:110px" /></td>
					<td width="80">登录IP</td>
				  	<td width="150"><input id="txtLoginIP" type="text" style="width:120px" /></td>
					<td width="110">当前分</td>
			  	  <td><input id="txtPointMin" type="text" style="width:80px" />
至
  <input id="txtPointMax" type="text" style="width:80px" /></td>
				  <td>注册时间</td>
			  	  <td><input id="txtRegTimeMin" type="text" style="width:80px" />
至
  <input id="txtRegTimeMax" type="text" style="width:80px" /></td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td>用户名</td>
				  	<td><input id="txtUserName" type="text" style="width:110px" /></td>
					<td>注册IP</td>
				  	<td><input id="txtRegIP" type="text" style="width:120px" /></td>
					<td>银行分</td>
			  	  <td><input id="txtBankPointMin" type="text" style="width:80px" />
至
  <input id="txtBankPointMax" type="text" style="width:80px" /></td>
					<td width="80">登录时间</td>
			  	  <td><input  id="txtLoginMin" type="text" style="width:80px" />
至
  <input id="txtLoginMax" type="text" style="width:80px" /></td>
				</tr>
                <tr bgcolor="#FFFFFF">
					<td>邮箱</td>
				  	<td><input type="text" id="txtEmail" style="width:110px" /><input id="cbxCheckEmail" type="checkbox"/>绑定</td>
					<td>登录密码</td>
				  	<td><input type="text" id="txtLoginPwd" style="width:120px" /></td>
					<td>总分</td>
		  	    <td><input id="txtTotalPointMin" type="text" style="width:80px" />
至
  <input id="txtTotalPointMax" type="text" style="width:80px" /></td>
				<td width="80">经验</td>
			  	  <td><input id="txtExpMin" type="text" style="width:80px" />
至
  <input id="txtExpMax" type="text" style="width:80px" /></td>
				</tr>
                <tr bgcolor="#FFFFFF">
					<td>手机</td>
				  	<td><input type="text" id="txtMobile" style="width:110px" /><input id="cbxCheckMobile" type="checkbox"/>绑定</td>
					<td>支付密码</td>
				  	<td><input type="text" id="txtBankPwd" style="width:120px" /></td>
					<td>累积经验</td>
                    <td>
                        <input id="txtTotalExpMin" type="text" style="width:80px" />
                        至
                        <input id="txtTotalExpMax" type="text" style="width:80px" />                    </td>
                    <td>充值分</td>
                    <td>
                        <input id="txtTotalChargeMin" type="text" style="width:80px" />
                        至
                        <input id="txtTotalChargeMax" type="text" style="width:80px" />
                    </td>
				</tr>       
				<tr bgcolor="#FFFFFF">
                    <td>排序</td>
                    <td colspan="7"><select id = "sltOrder">
                      <option value="time">注册时间</option>
					  <option value="logintime">登录时间</option>
                      <option value="points">当前分</option>
                      <option value="back">银行分</option>
                      <option value="totalpoint">总分</option>
                      <option value="experience">经验</option>
                      <option value="regip">注册IP</option>
                      <option value="loginip">登录IP</option>
                      <option value="dj">状态</option>
                      <option value="vip">vip</option>
                      <option value="mobile">手机</option>
                      <option value="email">邮箱</option>
                      <option value="is_check_mobile">绑定手机</option>
                      <option value="is_check_email">绑定邮箱</option>
					  <option value="qq">qq</option>
					  <option value="recv_cash_name">收款人</option>
                      <option value="nickname">昵称</option>
                      <option value="id">用户ID</option>
                      <option value="username">用户名</option>
                    </select>
                      <select id = "sltOrderType">
                        <option value="desc">降序</option>
                        <option value="">升序</option>
                      </select></td>
				</tr>      
			</table>
			<table id='tblList' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			  	<tr bgcolor="#f5fafe">
					<td width="20"></td>
					<td align="center">用户ID</td>
					<td align="center">用户名</td>
					<td align="center">昵称</td>
					<td align="center">手机</td>
					<td align="center">邮箱</td>
					<td align="center">QQ</td>
					<td align="center">收款人</td>
		            <td align="center">注册IP</td>
					<td align="center">注册时间</td>
		            <td align="center">登录时间</td>
		            <td align="center">登录IP</td>
		            <td align="center">当前分</td>
		            <td align="center">银行分</td>
		            <td align="center">投注分</td> 
		            <td align="center">总分</td>
					<td align="center">经验</td>
		            <td align="center">状态</td>
					<td align="center">原因</td>
					<td align="center">冻结返利</td>
				</tr>			    
			</table>
			<div class="fenyebar" id="pageinfo"></div>				
		</div>		 
	    <div>
	        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
	            <tr bgcolor="#FFFFFF">
	                <td height="30" width="60" align="left">
	                    <input type="checkbox" id="cbxSelectAll" />
	                    全选
	                </td>
	                <td height="30" align="left">
	                    原因:<input id="txtReason" type="text" style="width:120px" />
	                    <input type="button" value="冻结帐号"  id="btnForbidden" class="btn-1" />	
						<input type="button" value="解封帐号"  id="btnOpen" class="btn-1" />    
						&nbsp;
						<input type="button" value="冻结返利"  id="btnCloseRebate" class="btn-1" />	
						<input type="button" value="解封返利"  id="btnOpenRebate" class="btn-1" />        
	                </td>
	                
	                <td height="30" align="left">
	                    &nbsp;红包码:<input id="txtPackCode" type="text" style="width:120px" />	
						&nbsp;<input type="button" value="发送红包"  id="btnSendPack" class="btn-1" />        
	                </td>
	            </tr>
	        </table>     
	    </div>
    </div>
</body>

<script type= "text/javascript" language ="javascript">
    $(window.parent.document).attr("title","批用户查询");
	$(document).ready(function() {
        InitDatePicker("txtRegTimeMin");
        InitDatePicker("txtRegTimeMax");
        InitDatePicker("txtLoginMin");
        InitDatePicker("txtLoginMax");
        var type = request("type");
        var word = request("word");
        if(word != "")
        {
            switch(type)
            {
                case "email":
                    $("#txtEmail").val(word);
                    break;
                case "mobile":
                    $("#txtMobile").val(word);
                    break;
                case "loginip":
                    $("#txtLoginIP").val(word);
                    break;
                case "regip":
                    $("#txtRegIP").val(word);
                    break;
                case "loginpwd":
                    $("#txtLoginPwd").val(word);
                    break;
                case "bankpwd":
                    $("#txtBankPwd").val(word);
                    break;
                default:
                    break;
            } 
        }
        SearchData();
		//全选和反选
		$("#cbxSelectAll").click(function(){
			if(this.checked)
			{
				$("input[name='cbxID']").each(function(){this.checked=true;});
			}
			else
			{
				$("input[name='cbxID']").each(function(){this.checked=false;});
			}
			});
		//查询
		$("#btnSearch").click(function(){
			SearchData();
		});
		//ID回车事件
		$("#txtUserIdx").keydown(function(event){
			if(event.keyCode==13)
			{
				SearchData();
			}
		});
		//冻结
		$("#btnForbidden").click(function(){
			var IDs = GetCheckID();
			if(IDs.length == 0)
			{
				alert("必须勾选一个!");
				return false;
			}
            if($("#txtReason").val() == "")
            {
                alert("请输入冻结原因!");
                return false;
            }
			if(confirm("您确定要冻结吗?"))
			{
				var data = "action=forbidden&id=" + IDs + "&reason=" + $("#txtReason").val();
                //alert(data);
                //return false;
				SendAjax(data);
				SearchData();
			}
			return true;
		});
		//解封
		$("#btnOpen").click(function(){
			var IDs = GetCheckID();
			if(IDs.length == 0)
			{
				alert("必须勾选一个!");
				return false;
			}
            if($("#txtReason").val() == "")
            {
                alert("请输入解封原因!");
                return false;
            }
			if(confirm("您确定要解封用户吗?"))
			{
				var data = "action=open&id=" + IDs + "&reason=" + $("#txtReason").val();

				SendAjax(data);
				SearchData();
			}
			return true;
		});


		//发送红包短信
		$("#btnSendPack").click(function(){
			var IDs = GetCheckID();
			if(IDs.length == 0)
			{
				alert("必须勾选一个!");
				return false;
			}
            if($("#txtPackCode").val() == "")
            {
                alert("请输入红包码!");
                return false;
            }
			if(confirm("您确定要给这些用户发红包吗?"))
			{
				var data = "action=sendPack&id=" + IDs + "&packcode=" + $("#txtPackCode").val();

				SendAjax(data);
			}
			return true;
		});
		

		//冻结返利
		$("#btnCloseRebate").click(function(){
			var IDs = GetCheckID();
			if(IDs.length == 0)
			{
				alert("必须勾选一个!");
				return false;
			}
			
			if(confirm("您确定要冻结吗?"))
			{
				var data = "action=closeRebate&id=" + IDs;
                //alert(data);
                //return false;
				SendAjax(data);
				SearchData();
			}
			return true;
		});
		//解封返利
		$("#btnOpenRebate").click(function(){
			var IDs = GetCheckID();
			if(IDs.length == 0)
			{
				alert("必须勾选一个!");
				return false;
			}
			
			if(confirm("您确定要解封用户吗?"))
			{
				var data = "action=openRebate&id=" + IDs ;

				SendAjax(data);
				SearchData();
			}
			return true;
		});

	});

	//取得勾选ID
	function GetCheckID()
	{
		var IDs = "";
		$("input[name='cbxID']:checked").each(function(){
			IDs += $(this).val() + ",";
		});
		if(IDs.length > 0)
		{
			IDs = IDs.substr(0,IDs.length-1);
		}
		return IDs;
	}

	//查询数据
	function SearchData()
	{
		var data = GetData();
		SendAjax(data);

	}
    //分页
    function ajax_page(page)
    {
        var data = GetData();
        data += "&Page=" + page;
        SendAjax(data);
    }
    //取得查询条件
    function GetData()
    {
        $("#tblList tr:gt(0)").remove(); 
        $("#pageinfo").html('');
        var data = "action=get_patchuserinfo";
		var order1='<?php echo trim($_GET['order']);?>';
		if(order1!=""){
		   data += "&order=" + order1;
		   $("#sltOrder").val(order1);
		}
        var userid = $("#txtUserIdx").val()
        if( userid != "" )
        {
            if(isNaN(userid))
            {
                $("#txtUserIdx").val("");
            }
            else
            {
                data += "&userid=" + userid;
            }
        }   
        data += "&status=" + $("#sltStatus").val() + "&usertype=" + $("#sltUserType").val();
        if($("#txtNickName").val() != "")
            data += "&nickname=" + $("#txtNickName").val();
        if($("#txtEmail").val() != "")
            data += "&email=" + $("#txtEmail").val();
        if($("#txtMobile").val() != "")
            data += "&mobile=" + $("#txtMobile").val();
        if($("#txtLoginIP").val() != "")
            data += "&loginip=" + $("#txtLoginIP").val();
        if($("#txtRegIP").val() != "")
            data += "&regip=" + $("#txtRegIP").val();
        if($("#cbxCheckEmail").is(":checked"))
        	data += "&checkemail=1";
        if($("#cbxCheckMobile").is(":checked"))
        	data += "&checkmobile=1";
        if($("#txtLoginPwd").val() != "")
            data += "&loginpwd=" + $("#txtLoginPwd").val();
        if($("#txtBankPwd").val() != "")
            data += "&bankpwd=" + $("#txtBankPwd").val();
        if($("#txtUserName").val() != "")
            data += "&username=" + $("#txtUserName").val();
        	
        
        if($("#txtPointMin").val() != "")
        {
            if(isNaN($("#txtPointMin").val()))
            {
                $("#txtPointMin").val("");
            }
            else
            {
                data += "&pointmin=" + $("#txtPointMin").val();
            }
        }
        if($("#txtPointMax").val() != "")
        {
            if(isNaN($("#txtPointMax").val()))
            {
                $("#txtPointMax").val("");
            }
            else
            {
                data += "&pointmax=" + $("#txtPointMax").val();
            }
        }
        if($("#txtBankPointMin").val() != "")
        {
            if(isNaN($("#txtBankPointMin").val()))
            {
                $("#txtBankPointMin").val("");
            }
            else
            {
                data += "&bankpointmin=" + $("#txtBankPointMin").val();
            }
        }
        if($("#txtBankPointMax").val() != "")
        {
            if(isNaN($("#txtBankPointMax").val()))
            {
                $("#txtBankPointMax").val("");
            }
            else
            {
                data += "&bankpointmax=" + $("#txtBankPointMax").val();
            }
        }
        if($("#txtTotalPointMin").val() != "")
        {
            if(isNaN($("#txtTotalPointMin").val()))
            {
                $("#txtTotalPointMin").val("");
            }
            else
            {
                data += "&totalpointmin=" + $("#txtTotalPointMin").val();
            }
        }
        if($("#txtTotalPointMax").val() != "")
        {
            if(isNaN($("#txtTotalPointMax").val()))
            {
                $("#txtTotalPointMax").val("");
            }
            else
            {
                data += "&totalpointmax=" + $("#txtTotalPointMax").val();
            }
        }
		if($("#txtTotalExpMin").val() != "")
        {
            if(isNaN($("#txtTotalExpMin").val()))
            {
                $("#txtTotalExpMin").val("");
            }
            else
            {
                data += "&totalexpmin=" + $("#txtTotalExpMin").val();
            }
        }
        if($("#txtTotalExpMax").val() != "")
        {
            if(isNaN($("#txtTotalExpMax").val()))
            {
                $("#txtTotalExpMax").val("");
            }
            else
            {
                data += "&totalexpmax=" + $("#txtTotalExpMax").val();
            }
        }

        if($("#txtTotalChargeMax").val() != "")
        {
            if(isNaN($("#txtTotalChargeMax").val()))
            {
                $("#txtTotalChargeMax").val("");
            }
            else
            {
                data += "&totalchargemax=" + $("#txtTotalChargeMax").val();
            }
        }
        if($("#txtTotalChargeMin").val() != "")
        {
            if(isNaN($("#txtTotalChargeMin").val()))
            {
                $("#txtTotalChargeMin").val("");
            }
            else
            {
                data += "&totalchargemin=" + $("#txtTotalChargeMin").val();
            }
        }


        
        if($("#txtRegTimeMin").val() != "")
        {
            if(!ValidDate($("#txtRegTimeMin").val()))
            {
                $("#txtRegTimeMin").val("");
            }
            else
            {
                data += "&regtimemin=" + $("#txtRegTimeMin").val();
            }
        }
        if($("#txtRegTimeMax").val() != "")
        {
            if(!ValidDate($("#txtRegTimeMax").val()))
            {
                $("#txtRegTimeMax").val("");
            }
            else
            {
                data += "&regtimemax=" + $("#txtRegTimeMax").val();
            }
        }
        if($("#txtLoginMin").val() != "")
        {
            if(!ValidDate($("#txtLoginMin").val()))
            {
                $("#txtLoginMin").val("");
            }
            else
            {
                data += "&logintimemin=" + $("#txtLoginMin").val();
            }
        }
        if($("#txtLoginMax").val() != "")
        {
            if(!ValidDate($("#txtLoginMax").val()))
            {
                $("#txtLoginMax").val("");
            }
            else
            {
                data += "&logintimemax=" + $("#txtLoginMax").val();
            }
        }
        if($("#txtExpMin").val() != "")
        {
            if(isNaN($("#txtExpMin").val()))
            {
                $("#txtExpMin").val("");
            }
            else
            {
                data += "&expmin=" + $("#txtExpMin").val();
            }
        }
        if($("#txtExpMax").val() != "")
        {
            if(isNaN($("#txtExpMax").val()))
            {
                $("#txtExpMax").val("");
            }
            else
            {
                data += "&expmax=" + $("#txtExpMax").val();
            }
        } 
        var PageSize = "20";
        if($("#txtPageSize").val() == "" || isNaN($("#txtPageSize").val()) )
        {
            $("#txtPageSize").val("20");
        }
        else
        {
            PageSize = $("#txtPageSize").val();
        }
        var isExceptInner = 0;
        if($("#cbxExceptInner").is(":checked"))
        	isExceptInner = 1;
        data += "&isexceptinner=" + isExceptInner;
        	
        data += "&PageSize=" + PageSize;
        data += "&order=" + $("#sltOrder").val();
        data += "&ordertype=" + $("#sltOrderType").val();
        
        return data;
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
		       success: function(data) {DataSuccess(data);}
		});
	}
	//数据成功后
	function DataSuccess(json)
	{
		var tbody = "";
		var pageinfo = "";
		$.each(json,function(i,item){
			if(i == 0)
			{
				switch(item.cmd)
				{
					case "get_patchuserinfo":
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
				tbody += "<tr bgcolor='#FFFFFF'>" +
							"<td align='center'>" + item.strCheckBox +"</td>" +
                            "<td align='center'>" + item.UserID + "</td>" +
                            "<td align='center'>" + item.UserName + "</td>" +
							"<td align='center'>" + item.NickName + "</td>" +
							"<td align='center'>" + item.Mobile + "</td>" +
							"<td align='center'>" + item.Email + "</td>" +
							"<td align='center'>" + item.QQ + "</td>" +
							"<td align='center'>" + item.CashName + "</td>" +
							"<td align='center'>" + item.RegIP + "</td>" +
							"<td align='center'>" + item.RegTime + "</td>" +
							"<td align='center'>" + item.LoginTime + "</td>" +
                            "<td align='center'>" + item.LoginIP + "</td>" +
                            "<td align='center'>" + item.Points + "</td>" +  
                            "<td align='center'>" + item.BankPoints + "</td>" +
                            "<td align='center'>" + item.LockPoints + "</td>" +
                            "<td align='center'>" + item.TotalPoints + "</td>" +
                            "<td align='center'>" + item.Exp + "</td>" +
                            "<td align='center'>" + item.State + "</td>" +
                            "<td align='center'>" + item.Reason + "</td>" +
                            "<td align='center'>" + item.DjRebate + "</td>" +
						"</tr>";
			}
						
		});
		if(tbody != "")
		{
			$("#tblList tr:gt(0)").remove();
			$("#tblList").append(tbody);
			$("#pageinfo").html(pageinfo);
		}

	}
</script>

</html>

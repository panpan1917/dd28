var timerid;
var stopSec = 180;
clearInterval(timerid);
$(document).ready(function(){
	if(controller == "" || action == "")
		getContent("smbinfo.php", "baseinfo");
	$(".nav-tabs li").live("click",function () {
		console.info('a')
		$(".nav-tabs li").removeClass('active');
		$(this).addClass('active');
		$(".tab-content .tab-pane").hide();
		$(".tab-content .tab-pane").eq($(this).index()).show();
	})
});
function getContent(url, o)
{
	cssChange(o);
	$.get(url,{act:o},function(ret){
		$("#divContent").empty();
		$("#divContent").html(ret);
	});
	//$("#divContent").html("<iframe style='height: 100%' src=\""+url+"?act="+o+"\" border='0' width='100%' height='676'></iframe>");
}
function cssChange(o)
{
	$("a[id*='menu_']").removeClass("pick");
	$("#menu_" + o).addClass("pick");
}
$.setupJMPopups({
	screenLockerBackground: '#cccccc',
	screenLockerOpacity: '0.7'
});
function openrecord(name,w_width,w_height,url)
{
	$.openPopupLayer({
		name: name,
		width: w_width,
		height: w_height,
		url: url
	});
}
function closerecord(name)
{
	clearInterval(timerid);
	$.closePopupLayer(name);
}
//更换头像
$("#sltHead").live('change',function(){
	var pic = $(this).children('option:selected').val();
	$("#imgHead").attr("src","img/head/"+pic);
});
//修改资料
$("#btnSubmit").live('click',function(){
	var nickname = $("#txtNickName").val();
	var head = $("#sltHead").val();
	var mobile = $("#txtMobile").val();
	var email = $("#txtEmail").val();
	var qq = $("#txtQQ").val();
	var caption = $("#txtCaption").val();
	var reMobile = /^1\d{10}$/;
	var reEmail = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
	var recv_cash_name=$("#recv_cash_name").val();
	var card=$("#card").val();
	if(nickname.length == 0)
	{
		alert("昵称不能为空!");
		return;
	}
	if(mobile != "")
	{
		if(!reMobile.test(mobile))
		{
			alert("手机号码必须1开头11位长度!");
			return;
		}
	}


	$.post("smbinfo.php",{act:"changedetail",nickname:nickname,head:head,mobile:mobile,email:email,qq:qq,caption:caption,recv_cash_name:recv_cash_name,card:card},function(ret){
		switch(ret.cmd)
		{
			case "ok":
				alert("修改成功!");
				break;
			default:
				alert(ret.msg);
				break;
		}
	},"json");
});
//存乐豆
$("#btnSaveScore").live('click',function(){
	var score = $("#txtSaveScore").val();
	if(score == "" || isNaN(score))
	{
		alert("乐豆数量必须为数字!");
		return;
	}
	score = parseInt(score);
	$.post("smbinfo.php",{act:"savescore",score:score},function(ret){
		switch(ret.cmd)
		{
			case "ok":
				alert("乐豆存银行成功!");
				getContent("smbinfo.php", "mybank");
				break;
			default:
				alert(ret.msg);
				break;
		}
	},"json");
});
//取乐豆
$("#btnGetScore").live('click',function(){
	var score = $("#txtGetScore").val();
	var pwd = $("#txtBankPwd").val();
	if(score == "" || isNaN(score))
	{
		alert("乐豆数量必须为数字!");
		return;
	}
	score = parseInt(score);
	if(pwd.length == 0)
	{
		alert("请输入银行密码!");
		return;
	}
	$.post("smbinfo.php",{act:"getscore",score:score,pwd:pwd},function(ret){
		switch(ret.cmd)
		{
			case "ok":
				alert("乐豆取出成功!");
				getContent("smbinfo.php", "mybank");
				break;
			default:
				alert(ret.msg);
				break;
		}
	},"json");
});



//取全部乐豆 
$("#btnGetAll").live('click',function(){
	$.post("smbinfo.php",{act:"getallscore"},function(ret){
		switch(ret.cmd)
		{
			case "ok":
				$("#txtGetScore").val(ret.data.back);
				break;
			default:
				alert(ret.msg);
				break;
		}
	},"json");
});
//存全部乐豆 
$("#btnSaveAll").live('click',function(){
	$.post("smbinfo.php",{act:"getallscore"},function(ret){
		switch(ret.cmd)
		{
			case "ok":
				$("#txtSaveScore").val(ret.data.points);
				break;
			default:
				alert(ret.msg);
				break;
		}
	},"json");
});



//检测ID
$("#btnTransCheckID").live('click',function(){
	var targetid = $.trim($("#txtTragetID").val());
	if(targetid == "" || isNaN(targetid))
	{
		alert("请输入对方数字ID!");
		return;
	}
	$.post("smbinfo.php",{act:"checktargetid",targetid:targetid},function(ret){
		alert(ret.msg);
	},"json");
});
//转账
$("#btnTransScore").live('click',function(){
	var score = $("#txtTransScore").val();
	var targetid = $("#txtTragetID").val();
	var pwd = $("#txtTransBankPwd").val();
	if(score == "" || isNaN(score))
	{
		alert("转账乐豆数量必须为数字!");
		return;
	}
	score = parseInt(score);
	if(targetid == "" || isNaN(targetid))
	{
		alert("对方ID必须为数字!");
		return;
	}
	targetid = parseInt(targetid);
	if(pwd.length == 0)
	{
		alert("请输入银行密码!");
		return;
	}
	$.post("smbinfo.php",{act:"transscore",score:score,targetid:targetid,pwd:pwd},function(ret){
		switch(ret.cmd)
		{
			case "ok":
				alert("转账成功!");
				getContent("smbinfo.php", "mybank");
				break;
			default:
				alert(ret.msg);
				break;
		}
	},"json");
});
//取乐豆明细分页
function ajax_page_sl(page)
{
	getContent("smbinfo.php" + "?page=" + page, "scoredetail");
}
//取操作日志分页
function ajax_page_al(page)
{
	getContent("smbinfo.php" + "?page=" + page, "actionlog");
}
//取系统消息分页
function ajax_page_sm(page)
{
	getContent("smbinfo.php" + "?pages=" + page, "messagelist");
}
//取投注消息分页
function ajax_page_press(page)
{
	getContent("smbinfo.php" + "?pages=" + page, "get_press_log");
}
//取用户消息分页
function ajax_page_um(page)
{
	getContent("smbinfo.php" + "?pagem=" + page + "&t=" + $("#msgtype").html(), "messagelist");
}
//充值体验卡
$("#btnPayCards").live('click',function(){
	var cardids = $.trim($("#txtCardIDs").val());
	var vcode = $("#txtValidCode").val();
	if(cardids == "")
	{
		alert("请输入卡密!");
		return;
	}
	if(vcode == "" || vcode.length != 4)
	{
		alert("请输入4位长度验证码!");
		return;
	}
	$.post("smbinfo.php",{act:"paycard",cardids:cardids,vcode:vcode},function(ret){
		alert(ret.msg);
	},"json");
});
//修改登录密码
$("#btnChangeLoginPwd").live('click',function(){
	var oldpwd = $.trim($("#txtOldLoginPwd").val());
	var newpwd = $.trim($("#txtNewLoginPwd").val());
	var rpwd = $.trim($("#txtRNewLoginPwd").val());

	if(oldpwd == "")
	{
		alert("请输入原登录密码");
		return;
	}
	if(newpwd == "")
	{
		alert("请输入新登录密码");
		return;
	}
	if(newpwd != rpwd)
	{
		alert("确认密码与新密码不相符");
		return;
	}
	$.post("smbinfo.php",{act:"changeloginpwd",oldpwd:oldpwd,newpwd:newpwd},function(ret){
		alert(ret.msg);
	},"json");
});
//修改银行密码
$("#btnChangeBankPwd").live('click',function(){
	var oldpwd = $.trim($("#txtOldBankPwd").val());
	var newpwd = $.trim($("#txtNewBankPwd").val());
	var rpwd = $.trim($("#txtRNewBankPwd").val());

	if(oldpwd == "")
	{
		alert("请输入原银行密码");
		return;
	}
	if(newpwd == "")
	{
		alert("请输入新银行密码");
		return;
	}
	if(newpwd != rpwd)
	{
		alert("确认密码与新密码不相符");
		return;
	}
	$.post("smbinfo.php",{act:"changebankpwd",oldpwd:oldpwd,newpwd:newpwd},function(ret){
		alert(ret.msg);
	},"json");
});
//发送消息
$("#btnSendTargetMsg").live('click',function(){
	var targetuser = $.trim($("#txtTargetUser").val());
	var targettitle = $.trim($("#txtTargetTitle").val());
	var targetmsg = $.trim($("#txtTargetMsg").val());

	if(targetuser == "" || isNaN(targetuser))
	{
		alert("请输入对方数字ID");
		return;
	}
	if(targettitle == "")
	{
		alert("请输入标题");
		return;
	}
	if(targetmsg == "")
	{
		alert("请输入内容");
		return;
	}
	$.post("smbinfo.php",{act:"sendmsg",user:targetuser,title:targettitle,msg:targetmsg},function(ret){
		if(ret.cmd == 'ok')
		{
			alert(ret.msg);
			closerecord(0);
		}
		else
		{
			alert(ret.msg);
		}
	},"json");
});
//全选反选
function selectAll(n,t)
{
	if(t == 1)
	{
		$("input[name='"+ n +"']").each(function(){this.checked=true;});
	}
	else
	{
		$("input[name='"+ n +"']").each(function(){this.checked=!this.checked;});
	}
}
//取得勾选ID
function GetCheckID(n)
{
	var IDs = "";
	$("input[name='"+ n +"']:checked").each(function(){
		IDs += $(this).val() + ",";
	});
	if(IDs.length > 0)
	{
		IDs = IDs.substr(0,IDs.length-1);
	}
	return IDs;
}
//删除消息
function removeMsg(t)
{
	var IDs = GetCheckID('cbxUMID');
	if(IDs.length == 0)
	{
		alert("必须勾选一个!");
		return false;
	}
	if(confirm("您确定要删除所选消息吗?"))
	{
		$.post("smbinfo.php",{act:"removemsg",t:t,id:IDs},function(ret){
			alert(ret.msg);
		},"json");
		getContent("smbinfo.php?t=" + t, "messagelist");
	}
}
//发送手机验证码
$("#btnGetMobileValid").live('click',function(){
	var mobile = $("#txtBindMobile").val();
	var reMobile = /^1\d{10}$/;
	if(!reMobile.test(mobile))
	{
		alert("请填写您常用的手机号码");
		return;
	}
	$.post("smbinfo.php",{act:"sendmobilevalid",mobile:mobile},function(ret){
		if(ret.cmd == 'ok')
		{
			$('#txtBindMobile').attr('disabled',true);
			alert("短信已发出，请检查手机");
			stopSec = 180;
			timerid = window.setInterval(function(){
				refreshTime("mobile");
			},1000);
		}
		else
		{
			alert(ret.msg);
		}
	},"json");
});
//刷新函数
function refreshTime(vtype)
{
	var objAccount = "#txtBindMobile";
	var objBtn = "#btnGetMobileValid";
	var objLbl = "#lblBindtype";
	if(vtype == "email")
	{
		objAccount = "#txtBindEmail";
		objBtn = "#btnGetEmailValid";
	}

	if(stopSec < 0)
	{
		$(objBtn).val('重新发送');
		$(objBtn).attr('disabled',false);
		clearInterval(timerid);
	}
	else
	{
		$(objBtn).attr('disabled',true);
		$(objBtn).val('还剩' + stopSec + '秒');
		stopSec--;
	}
}
//绑定手机
$("#btnBindMobile").live('click',function(){
	var mobile = $("#txtBindMobile").val();
	var validcode = $("#txtMobileValidCode").val();
	var reMobile = /^1\d{10}$/;
	if(!reMobile.test(mobile))
	{
		alert("请填写您常用的手机号码");
		return;
	}
	if(validcode == "")
	{
		alert("请输入验证码");
		return;
	}
	$.post("smbinfo.php",{act:"bindmobile",mobile:mobile,code:validcode},function(ret){
		if(ret.cmd == 'ok')
		{
			alert("绑定成功");
			closerecord(-1);
			getContent("smbinfo.php", "mydetail");
		}
		else
		{
			alert(ret.msg);
		}
	},"json");
});
//解绑手机
$("#btnUnBindMobile").live('click',function(){
	var validcode = $("#txtMobileValidCode").val();
	if(validcode == "")
	{
		alert("请输入验证码");
		return;
	}
	$.post("smbinfo.php",{act:"unbindmobile",code:validcode},function(ret){
		if(ret.cmd == 'ok')
		{
			alert("解绑成功");
			closerecord(-1);
			getContent("smbinfo.php", "mydetail");
		}
		else
		{
			alert(ret.msg);
		}
	},"json");
});
//绑定邮箱
$("#btnToBindEmail").live('click',function(){
	var email = $("#txtEmail").val();
	re = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/
	if(!re.test(email)){
		alert("请输入常用的邮箱地址");
		return;
	}
	$.post("smbinfo.php",{act:"bindemail",email:email},function(ret){
		if(ret.cmd == 'ok')
		{
			alert("验证邮件已经发送到邮箱，请打开邮箱点击链接通过验证");
			closerecord(-2);
		}
		else
		{
			alert(ret.msg);
		}
	},"json");
});
//解绑邮箱
$("#btnToUnBindEmail").live('click',function(){
	var email = $("#txtEmail").val();
	re = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/
	if(!re.test(email)){
		alert("请输入常用的邮箱地址");
		return;
	}
	$.post("smbinfo.php",{act:"unbindemail",email:email},function(ret){
		if(ret.cmd == 'ok')
		{
			alert("验证已发到原邮箱，请打开邮箱点击链接完成解绑");
			closerecord(-2);
		}
		else
		{
			alert(ret.msg);
		}
	},"json");
});
//取得救济
function GetJiuji()
{
	$.post("smbinfo.php",{act:"getjiuji"},function(ret){
		alert(ret.msg);
	},"json");
}
//兑奖点卡
function ExchangeCard(cardtype)
{
	var amount = $("#txtcardtype_" + cardtype).val();
	var re = /^\d+$/;
	if(!re.test(amount))
	{
		alert("请正确输入兑奖点卡数量");
		return;
	}

	if(confirm("您确定要兑奖吗?"))
	{
		$.post("smbinfo.php",{act:"exchangecard",cardtype:cardtype,amount:amount},function(ret){
			alert(ret.msg);
		},"json");
	}
}
//充值选择数额
$("#sltPayRMB").live('change',function(){
	var paypoint = $(this).children('option:selected').val();
	$("#txtPayPoint").val(paypoint*1000);
});
//去充值
$("#btnGoPay").live('click',function(){
	var payrmb = $("#sltPayRMB").val();
	if(payrmb == "" || isNaN(payrmb)){
		alert("请正确选择充值金额");
		return;
	}
	window.open('jbpay/jbtopay.php?rmb=' + payrmb);
});
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
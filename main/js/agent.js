var recommend_tjid=0;
var parent_tjid=0;
var parent_parent_tjid = new Array();
var recommend_level=0;
function ajax_page_recommend_list(page){
	$.ajax({ 
		type: "post",
		url: "smbinfo.php?act=agent_recommend_tjid", 
		data:{page:page,userid:recommend_tjid},
		success: function (data) { 
			if(data=="timeout"){
				alert("你登录超时!");
				location.href = "/login.php";
				return;
			}else{
				$(".recommend_list tr:gt(0)").remove();
				$(".recommend_list table").append(data);
			}
		}, 
		error: function (XMLHttpRequest, textStatus, errorThrown) { 
				alert("检验失败,未知错误!");
		} 
	}); 
}

function get_recommend_list_level(tjid){
	if(tjid==0){
			$('.recommend_list').find(".m_val").find(".link").remove();
	}
	if(tjid==0){
		recommend_level=0;
	}else{
		recommend_level=recommend_level-1;
	}
	recommend_tjid=tjid;
	show_recommend_link();
	ajax_page_recommend_list(1);
	
}
function get_Previous_recommend_list(){
	get_recommend_list_level(parent_parent_tjid[recommend_tjid]);
}
function show_recommend_link(){
		if(recommend_level>1){
			var html="<span class='link'><a href=\"javascript:get_recommend_list_level(0);\">返回第一层</a>|<a href=\"javascript:get_Previous_recommend_list();\">返回上一层</a></span>";
		}else{
			var html="<span class='link'><a href=\"javascript:get_recommend_list_level(0);\">返回第一层</a></span>";
		}
		$('.recommend_list').find(".m_val").find(".link").remove();
		if(recommend_level>0){
			$('.recommend_list').find(".m_val").append(html);
		}
}
//推荐用户
function get_recommend_list(tjid){
	recommend_tjid=tjid;
	if ( ! parent_parent_tjid.hasOwnProperty('tjid')) {
		parent_parent_tjid[tjid]=parent_tjid;
	}
	parent_tjid=tjid;
	ajax_page_recommend_list(1);
	recommend_level=recommend_level+1;
	show_recommend_link();
	
}

function ajax_page_statistics(page){
	var Time_Begin="";
	var Time_end="";
	if($('#cbxTime').is(':checked')) {
		  var Time_Begin = $("#txtTimeBegin").val();
		  if(Time_Begin==""){
			   $("#txtTimeBegin").focus();
			   alert("请输入查询开始时间!");
			   return;
		 }
		  var Time_end = $("#txtTimeEnd").val();
		  if(Time_end==""){
			  $("#txtTimeEnd").focus();
			   alert("请输入查询结束时间!");
			   return;
		 }
	}
	ajax_page_statistics_get(page,Time_Begin,Time_end);
}
function ajax_page_statistics_get(page,begin,end){
	$.ajax({ 
			type: "post",
			url: "smbinfo.php?act=agent_statistics_page", 
			data:{page:page,begin:begin,end:end},
			success: function (data) { 
				if(data=="timeout"){
					alert("你登录超时!");
					location.href = "/login.php";
					return;
				}else{
					$(".statistics_list tr:gt(1)").remove();
				  	$(".statistics_list table").append(data);
				}
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		}); 
}



function ajax_page_rexperience(page){
		var userid = $("#search_userid").val();
		if(userid=="输入用户名或ID"){
			userid="";
		}
	 	var day= $('input[name="search_day"]:checked').val();
		ajax_page_experience_log(page,userid,day);
}
 
function ajax_page_agent_log(page){
	 	var userid = $("#search_userid").val();
		if(userid=="输入用户名或ID"){
			userid="";
		}
	 	var day= $('input[name="search_day"]:checked').val();
		ajax_page_agent_log_page(page,userid,day);
}

function ajax_page_agent_log_page(page,userid,day){
	$.ajax({ 
			type: "post",
			url: "smbinfo.php?act=agent_get_agent_log", 
			data:{userid:userid,day:day,page:page},
			success: function (data) { 
				if(data=="timeout"){
					alert("你登录超时!");
					location.href = "/login.php";
					return;
				}else{
					$(".agent_log_list tr:gt(1)").remove();
				  	$(".agent_log_list table").append(data);
				}
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		}); 
}
//我发的红包
function ajax_page_my_send_red_bag(page){
	$.ajax({ 
			type: "post",
			url: "smbinfo.php?act=get_my_red_bag", 
			data:{page:page},
			success: function (data) { 
				if(data=="timeout"){
					alert("你登录超时!");
					location.href = "/login.php";
					return;
				}else{
					$(".my_red_bag_list tr:gt(0)").remove();
				  	$(".my_red_bag_list table").append(data);
				}
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		}); 
}

function ajax_page_user_exchange(page){
	 	var day= $('input[name="search_day"]:checked').val();
		ajax_page_user_exchange_log(page,day);
}
function ajax_page_user_exchange_log(page,day){
	$.ajax({ 
			type: "post",
			url: "smbinfo.php?act=user_exchange_log", 
			data:{day:day,page:page},
			success: function (data) { 
				if(data=="timeout"){
					alert("你登录超时!");
					location.href = "/login.php";
					return;
				}else{
					$(".user_rexperience_log_list tr:gt(1)").remove();
				  	$(".user_rexperience_log_list table").append(data);
				}
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		}); 
}

function ajax_page_experience_log(page,userid,day){
	$.ajax({ 
			type: "post",
			url: "smbinfo.php?act=agent_get_rexperience_log", 
			data:{userid:userid,day:day,page:page},
			success: function (data) { 
				if(data=="timeout"){
					alert("你登录超时!");
					location.href = "/login.php";
					return;
				}else{
					$(".rexperience_log_list tr:gt(1)").remove();
				  	$(".rexperience_log_list table").append(data);
				}
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		}); 
}


function ajax_page_recharge(page){
		var userid = $("#search_userid").val();
		if(userid=="输入用户名或ID"){
			userid="";
		}
	 	var day= $('input[name="search_day"]:checked').val();
		ajax_page_recharge_log(page,userid,day);
}
function ajax_page_recharge_log(page,userid,day){
	$.ajax({ 
			type: "post",
			url: "smbinfo.php?act=agent_get_recharge_log", 
			data:{userid:userid,day:day,page:page},
			success: function (data) { 
				if(data=="timeout"){
					alert("你登录超时!");
					location.href = "/login.php";
					return;
				}else{
					$(".recharge_log_list tr:gt(1)").remove();
				  	$(".recharge_log_list table").append(data);
				}
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		}); 
}
function ajax_page_withdraw_log(page){
	$.ajax({ 
			type: "post",
			url: "smbinfo.php?act=withdraw_log", 
			data:{page:page},
			success: function (data) { 
				if(data=="timeout"){
					alert("你登录超时!");
					location.href = "/login.php";
					return;
				}else{
					$(".withdraw_log_list tr:gt(0)").remove();
				  	$(".withdraw_log_list table").append(data);
				}
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		}); 
}

function withdraw_Revocation (id){
	$.ajax({ 
			type: "post",
			url: "ajax.php?action=agent_withdraw_revocation", 
			data:{id:id},
			dataType: "json", 
			success: function (data) { 
				if(data.cmd=="timeout"){
					alert("你登录超时!");
					location.href = "/login.php";
					return;
				}
				if(data.cmd=="ok"){
					ajax_page_withdraw_log(1);
				}
				alert(data.msg);
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		}); 
}

$(document).ready(function(){
	
	
	 $('.radio_li').bind('click', function() {
   		  var type= $('input[name="type"]:checked').val();
		  if(type==0){
		 	 $("#red_money_name").html('每个红包金额');
		  }else{
		 	 $("#red_money_name").html('红包总金额');
		  }
	});	 
	
	
	$("#statistics_btnSearch").click(function(){
		ajax_page_statistics(1);
	});
	
	$("#agent_log_btnATSearch").click(function(){
		ajax_page_agent_log(1);
	});
	
	$("#exchange_btnATSearch").click(function(){
		ajax_page_user_exchange(1);
	});
	//点卡回收
	$("#submit_card_but").click(function(){
		var card_list = $("#card_list").val();
		if(card_list == ""){
			$("#card_list").focus();
			alert("请输入卡号与卡密!");
            return;
		}
		$.ajax({ 
			type: "post", 
			url: "ajax.php?action=agent_rcycle_card", 
			dataType: "json", 
			data:{card_list:card_list},
			success: function (data) { 
				if(data.cmd=="timeout" || data.cmd=="notlogin"){
					alert("你登录超时!");
					location.href = "/login.php";
					return;
				}
				if(data.cmd=="ok"){
					  alert(data.msg);
					  getContent('smbinfo.php','agent_experience_card');
				}else{
					alert(data.msg);
				}
				
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		});
	});
	
	
	//点卡检测
	$("#cetection_card_but").click(function(){
		var card_list = $("#card_list").val();
		if(card_list == ""){
			$("#card_list").focus();
			alert("请输入卡号与卡密!");
            return;
		}
		$.ajax({ 
			type: "post", 
			url: "ajax.php?action=agent_check_card", 
			dataType: "json", 
			data:{card_list:card_list},
			success: function (data) { 
				if(data.cmd=="timeout" || data.cmd=="notlogin"){
					alert("你登录超时!");
					location.href = "/login.php";
					return;
				}
				if(data.cmd=="ok"){
					 $(".cart_check_submit_tr").show();
					 $(".cart_check_ret").html("");
					 $(".cart_check_ret").html("<td colspan='2'  >"+data.table+"</td>");
				}else{
					alert(data.msg);
				}
				
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		});
	});
	
	
	
	//额度转换
	$('#change_money').keyup(function(){
		var inpVal = $(this).val();
		if(isNaN(inpVal) || inpVal<1){ 
			$(this).val('');
		}
	});
	
	//额度转换
	$("#agent_change_but").click(function(){
		var money = $("#change_money").val();
		var agent_id = $("#agent_select").val();
		if(money == ""){
			$("#change_money").focus();
			alert("请输入转换额度!");
            return;
		}
		if(isNaN(money) || money<1){ 
			$("#change_money").focus();
			alert("转换金额必须是数字!");
            return;
		}
		if(agent_id == ""){
			$("#agent_select").focus();
			alert("选择转换代理!");
            return;
		}
		$.ajax({ 
			type: "post", 
			url: "ajax.php?action=agent_change", 
			dataType: "json", 
			data:{agent_id:agent_id,money:money},
			success: function (data) { 
				if(data.cmd=="timeout" || data.cmd=="notlogin"){
					alert("你登录超时!");
					location.href = "/login.php";
					return;
				}
				if(data.cmd=="ok"){
					getContent('smbinfo.php','agent_change');
				}
				alert(data.msg);
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		});
	});
	
	//排行榜奖励
	$("#reward_rank_but").click(function(){
		$.ajax({ 
			type: "post", 
			url: "ajax.php?action=get_reward_rank", 
			dataType: "json", 
			success: function (data) { 
				if(data.cmd=="timeout" || data.cmd=="notlogin"){
					alert("你登录超时!");
					location.href = "/login.php";
					return;
				}
				if(data.cmd=="ok"){
					getContent('smbinfo.php','rewardrank');
				}
				alert(data.msg);
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		});
	});
	//提现申请begin
	$('#withdraw_money').keyup(function(){
		var inpVal = $(this).val();
		if(isNaN(inpVal) || inpVal<1){ 
			$(this).val('');
		}
	});
	
	
	
    $("#send_bag_but").click(function(){
		var type= $('input[name="type"]:checked').val();
		var money = $("#red_bag_money").val();
		var title=$("#red_bag_title").val();
		if(money == ""){
			$("#red_bag_money").focus();
			alert("请输入红包金额!");
            return;
		}
		if(isNaN(money)  ){ 
			$("#red_bag_money").focus();
			alert("红包金额必须是数字!");
            return;
		}
		if(  money<0.01){ 
			$("#red_bag_money").focus();
			alert("红包金额不能小于0.01!");
            return;
		}
		
		var num = $("#red_bag_num").val();
		if(num == ""){
			$("#red_bag_num").focus();
			alert("请输入红包数量!");
            return;
		}
		if(isNaN(num) || num<1){ 
			$("#red_bag_num").focus();
			alert("红包数量必须是数字!");
            return;
		}
		if(title == ""){
			$("#red_bag_title").focus();
			alert("请输入红包标题!");
            return;
		}
		if(type==1){
			if((money*1000<num)){
				$("#red_bag_num").focus();
				alert("你设置的红包金额不够发"+num+"个红包!");
				return;
			}
		}
		
		var ms="你确定生成红包吗?";
		var is_submit = window.confirm(ms);
		if(is_submit){
			$.ajax({ 
				type: "post", 
				url: "ajax.php?action=send_red_bag", 
				dataType: "json",
				data:{money:money,num:num,type:type,title:title},
				success: function (data) { 
					if(data.cmd=="timeout" || data.cmd=="notlogin"){
						alert("你登录超时!");
						location.href = "/login.php";
						return;
					}
					if(data.cmd=="ok"){
						var html="红包生成成功，复制代码发给您的好友!<textarea rows='3' style='width:500px;padding:10px;'  readonly='readonly' id='cptxt'>"+data.title+"!\n领取红包链接:http://"+web_url+"/bag.php?bid="+data.msg+"</textarea><div style='height:30px; padding-top:10px;padding-left:150px;'><img src='../image/fuzhi.gif' id='copyToClip' class=' hover'></div>";
						 $(".copytoclip_div").html(html);
						 $(".copytoclip_div").show();
						 $("#copyToClip").zclip({
								path: "/js/ZeroClipboard.swf",
								copy: function(){
									return $("#cptxt").text();
								},
								afterCopy:function(){/* 复制成功后的操作 */
									 alert("复制成功，你可以用ctrl+v粘贴了");
								}
						});

					}else{
						alert(data.msg);
					}
				}, 
				error: function (XMLHttpRequest, textStatus, errorThrown) { 
						alert("检验失败,未知错误!");
				} 
			});
		}
	});
	
	
	
    $("#withdraw_but").click(function(){
		var money = $("#withdraw_money").val();
		if(money == ""){
			$("#withdraw_money").focus();
			alert("请输入提现金额!");
            return;
		}
		if(isNaN(money) || money<1){ 
			$("#withdraw_money").focus();
			alert("提现金额必须是数字!");
            return;
		}
		
		if(money<100){
			$("#withdraw_money").focus();
			alert("提现金额过小,必须是100的倍数!");
            return;
		}
		if(money%100!=0){
			$("#withdraw_money").focus();
			alert("提现金额必须是100的倍数!");
            return;
		}
		 
	 
		$.ajax({ 
			type: "post", 
			url: "ajax.php?action=agent_withdraw", 
			dataType: "json", 
			data:{money:money},
			success: function (data) { 
				if(data.cmd=="timeout" || data.cmd=="notlogin"){
					alert("你登录超时!");
					location.href = "/login.php";
					return;
				}
				if(data.cmd=="ok"){
					ajax_page_withdraw_log(1);
				}
				alert(data.msg);
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		});
 
		 
	});
	
	//提现申请end /////////////////////////////////////////////////////////
	
	
	
	//回收记录
	$("#rexperience_btnATSearch").click(function(){
		ajax_page_rexperience(1);
	});
	
	//充值记录
	$("#recharge_btnATSearch").click(function(){
		ajax_page_recharge(1);
	});
	 $("#search_userid").click(function(){
		if($(this).val()=="输入用户名或ID"){
			$(this).val('');
		}
	 });
	$('#recharge_money').keyup(function(){
		var inpVal = $(this).val();
		if(isNaN(inpVal) || inpVal<1){ 
			$(this).val('');
			$("#recharge_money_ms").html('');
		}else{
			$("#recharge_money_ms").html('你将被扣除'+(inpVal*1000*Number(buycard_rate)));
		}
	});
	
	$('#red_bag_num').keyup(function(){
		var type= $('input[name="type"]:checked').val();
		var num = $(this).val();
		if(isNaN(num ) || num <1){ 
			$(this).val('');
			$("#red_bag_num").html('只能输入数字');
		}else{
			 var money = $("#red_bag_money").val();
			 if(isNaN(money) ){ 
			 		$("#red_bag_money_ms").html('只能输入数字');
			 }else{
				 	if(  money<0.01){ 
						$("#red_bag_money").focus();
						$("#red_bag_money_ms").html("红包金额不能小于0.01!");
					}else{
				 		$("#red_bag_num_ms").html('');
						if(type==1){
							if((money*1000<num)){
								$("#red_bag_num").focus();
								$("#red_bag_num_ms").html("你设置的红包金额不够发"+num+"个红包!");
							}
						}	
					}
			 }
		}
	});
	
	$('#red_bag_money').keyup(function(){
		var type= $('input[name="type"]:checked').val();
		var inpVal = $(this).val();
		if(isNaN(inpVal) ){ 
			$(this).val('');
			$("#red_bag_money_ms").html('只能输入数字');
		}else{
			if(  inpVal<0.01){
				$("#red_bag_money_ms").html('红包金额不能小于0.01!');
			}else{
				$("#red_bag_money_ms").html('');
			}
		}
	});
	
    $("#recharge_check_but").click(function(){
		var userid = $("#recharge_userid").val();
		if(userid == ""){
			$("#recharge_userid").focus();
			alert("请输入用户ID或用户名!");
            return;
		}
		$.ajax({ 
			type: "post", 
			url: "ajax.php?action=agent_check_users", 
			dataType: "json", 
			data:{userid:userid},
			success: function (data) { 
				if(data.cmd=="timeout"){
					alert("你登录超时!");
					location.href = "/login.php";
					return;
				}
				if(data.cmd=="user_empty"){
					$("#recharge_userid").focus();
					alert("请输入帐号!");
					return;
				}
				if(data.cmd=="empty"){
					$("#recharge_userid").focus();
					alert("帐号不存在!");
					return;
				}
				if(data.cmd=="OK"){
					$("#users_ms").remove();
					$("#check_acount").append("<div id='users_ms'>ID:"+data.data.id+",昵称:"+data.data.username+",收款人姓名："+data.data.recv_cash_name+"</div>");
				}
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		});
		 
	});
	
	
    $("#recharge_recharge_but").click(function(){
		var userid = $("#recharge_userid").val();
		var money = $("#recharge_money").val();
		if(userid == ""){
			$("#recharge_userid").focus();
			alert("请输入用户ID或用户名!");
            return;
		}
		if(money == ""){
			$("#recharge_money").focus();
			alert("请输入充值金额!");
            return;
		}
		var nickname="";
		var recv_cash_name="";
		$.ajax({ 
			type: "post", 
			async: false,
			url: "ajax.php?action=agent_check_users", 
			dataType: "json", 
			data:{userid:userid},
			success: function (data) { 
				if(data.cmd=="timeout"){
					alert("你登录超时!");
					location.href = "/login.php";
					return;
				}
				if(data.cmd=="user_empty"){
					$("#recharge_userid").focus();
					alert("请输入帐号!");
					return;
				}
				if(data.cmd=="empty"){
					$("#recharge_userid").focus();
					alert("帐号不存在!");
					return;
				}
				if(data.cmd=="OK"){
					$("#users_ms").remove();
					nickname=data.data.username;
					recv_cash_name=data.data.recv_cash_name;
					$("#check_acount").append("<div id='users_ms'>ID:"+data.data.id+",昵称:"+data.data.username+",收款人姓名："+data.data.recv_cash_name+"</div>");
				}
			}, 
			error: function (XMLHttpRequest, textStatus, errorThrown) { 
					alert("检验失败,未知错误!");
			} 
		});
		var ms="你确定向"+nickname;
		if(recv_cash_name){
			ms=ms+'('+recv_cash_name+')';
		}
		ms=ms+'充值￥'+money+"元吗?";
		if (confirm(ms))  {  
			$.ajax({ 
				type: "post", 
				url: "ajax.php?action=agent_recharge", 
				dataType: "json", 
				data:{userid:userid,money:money},
				success: function (data) { 
					if(data.cmd=="timeout"){
						alert("你登录超时!");
						location.href = "/login.php";
						return;
					}
					if(data.cmd=="notlogin"){
						alert("你登录超时!");
						location.href = "/login.php";
						return;
					}
					
					if(data.cmd=="user_empty"){
						$("#recharge_userid").focus();
						alert("请输入帐号!");
						return;
					}
					if(data.cmd=="not_money"){
						$("#recharge_money").focus();
						alert("请输入充值金额!");
						return;
					}
					if(data.cmd=="err_money"){
						$("#recharge_money").focus();
						alert("充值金额错误!");
						return;
					}
 			 
					if(data.cmd=="self"){
						alert("不能给自己帐号充值!");
						return;
					}	
 
					if(data.cmd=="freeze" ||data.cmd=="err" || data.cmd=="insufficient" ||data.cmd=="freeze" ){
						alert(data.msg);
						return;
					}	
 
					if(data.cmd=="ok"){
						alert("充值成功!");
						//$("#users_ms").remove();
						//$("#recharge_userid").val("");
						$("#recharge_money").val("");
						$("#recharge_money_ms").html("");
						ajax_page_recharge(1);
						return;
					}else{
						alert(data.msg);
					}
				}, 
				error: function (XMLHttpRequest, textStatus, errorThrown) { 
						alert("检验失败,未知错误!");
				} 
			});
		}
		 
	});
	
	
	//////////////////////////////////////////////////////
	
	
    $("#vcode").keydown(function(e){
    	var e = e || event;
        if(e.keyCode  == 13)
            $("#login").click(); 
    });
    
    $("#pass").keydown(function(e){
        if(e.keyCode  == 13)
            $("#login").click(); 
    });      

    $("#login").click(function(){
        var username = $("#username").val();
        var pass = $("#pass").val();
        var vcode = $("#vcode").val();
 
        if(username == "" || username == "请输入帐号")
        {
            alert("请输入帐号!");
            return false;
        }
       
        if(vcode == "" || vcode == "验证码")
        {
            alert("请输入验证码!");
            return false;
        }
 
        
        $.post("ajax.php?action=get_pwd_step1",{username:username, vcode:vcode},function(ret){
            switch(ret)
            {
                case "OK":
                	location.href = "/forgetpass_1.php";
                    break;
                case "fault":
                    alert("请输入帐号名!")
                    break;
                case "vcode":
                    alert("验证码错误！");
                    break;
                case "empty":
                    alert("你输入的帐号不存在!");
                    break;
                default:
                    alert("检验失败,未知错误!");
                    break;
            }
        });
        return false;
    });
});
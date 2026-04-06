<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理--游戏开奖</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta name="GENERATOR" content="MSHTML 6.00.3790.4275">
	<link rel="stylesheet" type="text/css" href="images/css_body.css">
	<link rel="stylesheet" type="text/css" href="images/window.css">
	<script type="text/javascript" src="images/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="images/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="images/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="images/fancybox/jquery.fancybox-1.3.4.css" media="screen" /> 
</head>
<body>
	<div class="bodytitle">
		<div class="bodytitleleft"></div>
		<div class="bodytitletxt">游戏开奖</div>
		<div class="bodytitletxt2">
			<a href="http://www.bwlc.net/bulletin/keno.html" target="_blank">28官方开奖</a>
			&nbsp;
			<a href="http://www.bwlc.net/bulletin/trax.html" target="_blank">pk官方开奖</a>
			&nbsp;
			<a href="http://www.jlotto.kr/keno.aspx?method=kenoWinNoList" target="_blank">韩国官方开奖</a>
			&nbsp;
			<a href="http://www.168kai.com/Lottery/10041" target="_blank">加拿大168开奖</a>
		</div>
	</div>
	<div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
			<tr bgcolor="#FFFFFF">
				<td>
					<label id="lblGameLogType" style="display: none;">0</label>
					选择游戏
					<select id = "sltGameList">
                    </select>
                    &nbsp;
                    期号
                    <input id="txtNo" type="text" style="width:60px" />
					<input type="button" value="查询" id="btnSearch" class="btn-1"/> 
                    &nbsp;
                    状态
                    <select id = "sltkjStatus">
                    	<option value="-1">所有</option>
                    	<option value="0">未开奖</option>
                    	<option value="1">已开奖</option>
                    </select>
                    <input id="cbxTime" type="checkbox"/>时间
				   	<input id="txtTimeBegin" type="text" style="width:90px" value="<?php echo date('Y-m-d',strtotime('-1 week')); ?>" />&nbsp;
				   	<input id="txtTimeEnd" type="text" style="width:90px" value="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" />
					页大小
					<input id="txtPageSize" type="text" value="20" style="width:50px" />
					                 
			  </td>
			  <td width="75">
			  		<input type="button" value="最近记录" id="btnRecentGameLog" class="btn-1"/> 
			  </td>
			</tr>
		</table>
		<table id='tbResult' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			<tr bgcolor="#f5fafe">
				<td align="center">期号</td>
				<td align="center">开奖时间</td>
				<td align="center">开奖结果</td> 
				<td align="center">中奖人数</td>
				<td align="center">投注总数</td>
				<td align="center">游戏总抽税</td>
				<td align="center">用户投注</td>
				<td align="center">用户输赢</td>
				<td align="center">自动人数</td>
				<td align="center">自动投分</td>
				<td align="center">手动/分</td>
				<td align="center">中奖赔率</td>
				<td align="center">查看</td>
				<td align="center">操作</td>
			</tr>
		</table>
		<div class="fenyebar" id="PageInfo"></div>
	</div>
</body>
<script type= "text/javascript" language ="javascript">
    $(window.parent.document).attr("title","游戏开奖");
    //*************************************************************************************
    //基本信息
	$(document).ready(function() {
    	BindClass();
		GetGameTypeOption();
        GetGameStats(0);
        //查询
		$("#btnSearch").click(function(){
			GetGameStats(0);
		});
		//取最近记录
		$("#btnRecentGameLog").click(function(){
			GetGameStats(1);
		});
	});
	//取游戏列表
	function GetGameTypeOption()
	{
		var data = "action=get_gametype_option";
		SendAjax(data);
	}
	//取记录
    function GetGameStats(t)
    {
    	$("#lblGameLogType").html(t); 
        var data = GetData();
        SendAjax(data);
    }
    //取参数
    function GetData()
    {   
    	$("#tbResult tr:gt(0)").remove();
    	$("#PageInfo").html('');     
        var data = "action=get_gamestats_log" + "&logtype=" + $("#lblGameLogType").html();
        var GameType = $("#sltGameList").val();
		/*if(GameType==null){
		   GameType = "gamehg28";
		}*/
        var theNo = $.trim($("#txtNo").val());  
        var kjStatus = $("#sltkjStatus").val();
        var DateBegin = $("#txtTimeBegin").val();
        var DateEnd = $("#txtTimeEnd").val();
        var PageSize = $.trim($("#txtPageSize").val()); 
        
        if(PageSize == "" || isNaN(PageSize))
        {
            PageSize = "20";
            $("#txtPageSize").val("20");
        }
        if(theNo == "" || isNaN(theNo))
        {
        	$("#txtNo").val("");
        	theNo = "";
		}
        data += "&no=" + theNo;
        	
        data += "&gametype=" + GameType + "&status=" + kjStatus + "&PageSize=" + PageSize;
        if($("#cbxTime").is(":checked"))
        {   
            if(DateBegin != "")
            {
                if(!ValidDate(DateBegin))
                {
                    $("#txtTimeBegin").val("");
                }
                else
                {
                    data += "&timebegin=" + DateBegin;
                }
            }
            if(DateEnd != "")
            {
                if(!ValidDate(DateEnd))
                {
                    $("#txtTimeEnd").val("");
                }
                else
                {
                    data += "&timeend=" + DateEnd;
                }                
            }
        }
        data += "&order=kgtime"+ "&ordertype=desc";
        return data;
    }
    //分页
    function ajax_page(page)
    {
        var data = GetData();
        data += "&Page=" + page;
        SendAjax(data);
    }
    //撤销投注
    function CancelGamePress(no,gametype)
    {
		if(confirm("你确定要撤销期号为"+no+"的投注吗?"))
    	{
			var data = "action=cancel_gamelog&no=" + no + "&gametype=" + gametype;
			SendAjax(data);
			GetGameStats($("#lblGameLogType").html());
		}
    }
    // 手动开奖
    function ManualOpenGame(no,gametype)
    {
    	if(confirm("你确定要开奖期号为"+no+"的记录吗?")) 
    	{
			var data = "action=open_gamelog&no=" + no + "&gametype=" + gametype;
			SendAjax(data);
			GetGameStats($("#lblGameLogType").html());
		}
    }
    // 手动采集
    function ManualCatchResult(no,gametype)
    {
    	if(confirm("你确定要采集期号为"+no+"的记录吗?"))
    	{
			var data = "action=catch_gamelog&no=" + no + "&gametype=" + gametype;
			SendAjax(data);
			GetGameStats($("#lblGameLogType").html());
		}
    }
    
    //删除记录
    function RemoveGameLog(no,gametype)
    {
    	if(confirm("你确定要删除期号为"+no+"的记录吗(已投注和已开奖不能删除)?"))
    	{
			var data = "action=remove_gamelog&no=" + no + "&gametype=" + gametype;
			SendAjax(data);
			GetGameStats($("#lblGameLogType").html());
		}
    }
    //*****************************************************************************************************
    //公共函数
    //验证日期正确是否，如2012-06-22
	function ValidDate(str)
    { 
		 var r = str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
		 if(r==null)return false; 
		 var d= new Date(r[1], r[3]-1, r[4]);
		 return (d.getFullYear()==r[1]&&(d.getMonth()+1)==r[3]&&d.getDate()==r[4]); 
    }
    //绑定
	function BindClass()
	{ 
		$(".edi").fancybox({
				type		: 'iframe',
				fitToView	: false,
				width		: '100%',
				height		: '100%',
				autoSize	: false,
				closeClick	: false,
				openEffect	: 'none',
				closeEffect	: 'none'
			}); 
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
                	case "get_gametype_option":
						$("#sltGameList").empty();
                        $(item.msg).appendTo("#sltGameList");
						return;
                    case "get_gamestats_log":
                    	pageinfo = item.msg;
                        break;
                    case "err_nologin":
                    	alert(item.msg);
                    	window.top.location.href='admin_login.php';
                    	return;
                    case "remove_gamelog":
                    	alert("删除成功！");
                    	GetGameStats($("#lblGameLogType").html());
                    	return;
					default:
						alert(item.msg);
						return;
						break;
				}
			}
			else
			{  
                if(InfoType == "get_gamestats_log")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td align='center'>" + item.NO +"</td>" +
                            "<td align='center'>" + item.kgtime + "</td>" + 
                            "<td align='center'>" + item.kgjg + "</td>" + 
                            "<td align='center'>" + item.zjrnum + "</td>" +
                            "<td align='center'>" + item.tzpoints + "</td>" + 
                            "<td align='center'>" + item.game_tax + "</td>" + 
                            "<td align='center'>" + item.user_tzpoints + "</td>" + 
                            "<td align='center'>" + item.user_winpoints + "</td>" + 
                            "<td align='center'>" + item.zd_count + "</td>" + 
                            "<td align='center'>" + item.zd_point + "</td>" + 
                            "<td align='center'>" + item.sd_cnt_point + "</td>" + 
                            "<td align='center'>" + item.take_time_remark + "</td>" +
                            "<td align='center'>" + item.viewresult + "</td>" + 
                            "<td align='center'>" + item.opr + "</td>" + 
                            "</tr>";
                }
			}						
		});
        
        if(tbody != "")
		{
			if(InfoType == "get_gamestats_log")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
                $("#PageInfo").html(pageinfo);
                BindClass();
            }
		}
		
	}
</script>
</html>

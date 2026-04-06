<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
	
if($_POST['action']=='referrals'){
		$DataType = isset($_POST['datatype'])?FilterStr($_POST['datatype']):"recent";
		$UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
		$Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
		$OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
		$TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
		$TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
		$IsExceptInner = intval($_POST['isexceptinner']);

		$sqlCount = "select Count(*) ";
		$sqlCol = "select id,username,nickname,logintime,loginip
					";
		$sqlFrom = " from users
					 where 1=1 ";
		$sqlWhere = "";
		$sqlOrder = "";
		$sql = "";
		//页大小
		$PageSize = isset($_POST['PageSize'])?$_POST['PageSize']:20;
		$PageSize = intval($PageSize);
		//页码
		$page = isset($_POST['Page'])?$_POST['Page']:1;
		$page =intval($page);

		$arrReturn = array(array());
		//取得查询条件
		if($UserID != "")
			$sqlWhere .= " and tjid = " . $UserID;
		//时间
		$TimeField = "time";
		//$sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
		//取得排序
		$sqlOrder = (($Order == "") ? "" : " order by {$Order} {$OrderType}");
		//取得总记录数
		$TotalCount = $db->GetRecordCount($sqlCount.$sqlFrom.$sqlWhere);
		//取记录
		$sql = $sqlCol . $sqlFrom . $sqlWhere . $sqlOrder . GetLimit($page,$PageSize);

		//WriteLog($sql);
		//return;

		$RowCount = 0;
		$arrRows = array(array());
		$result = $db->query($sql);
		//取得返回记录数
		$RowCount = $db->num_rows($result);
		if($RowCount == 0)
		{
			$arrReturn[0]["cmd"] = "norecord";
			$arrReturn[0]["msg"] = "没有记录!";
			ArrayChangeEncode($arrReturn);
			echo json_encode($arrReturn);
			return;
		}
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{
			$sql='select ifnull(sum(tzpoints),0) as tzpoints from game_day_static where to_days(now())=to_days(time) and uid='.$row['id'];
			$point=$db->fetch_first($sql);
			$arrRows[$i]['tzpoint']=$point['tzpoints'];
			//对返回数据进行包装
			$arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["id"]}")."' target='_blank'>{$row["id"]}</a>";
			$arrRows[$i]["username"] = $row["username"];
			$arrRows[$i]["NickName"] = $row["nickname"];
			$arrRows[$i]["logintime"] = $row["logintime"];
			$IPInfo = "<a href='http://www.ip138.com/ips.asp?ip={$row["loginip"]}' target='_blank'>". $row["loginip"] ."</a>";
			$IPInfo .= "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["loginip"]}")."' target='_blank'>批</a>";
			$arrRows[$i]["IP"] = $IPInfo;
			$arrRows[$i]["ErrMsg"] = $row["err_msg"];
		}
		//返回分页
		require_once('inc/fenye.php');
		$ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
		$pageInfo = $ajaxpage->show();

		$arrRows[0]["cmd"] = 'get_loginfail';
		$arrRows[0]["msg"] = $pageInfo;
		ArrayChangeEncode($arrRows);
		echo json_encode($arrRows);
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理--单用户查询</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta name="GENERATOR" content="MSHTML 6.00.3790.4275">
	<link rel="stylesheet" type="text/css" href="images/css_body.css">
	<link rel="stylesheet" type="text/css" href="images/window.css">
	<link rel="Stylesheet" type="text/css" href="images/jquery_ui.css" />
	<script type="text/javascript" src="images/jquery.js"></script> 
	<script type="text/javascript" src="images/jquery_ui.js"></script>
</head>
<body>
	<!-- 登录失败记录 -->
	<div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >				
			<tr bgcolor="#FFFFFF">
				<td><label id="lblUserIdx"></label>下线列表
					<input id="txtDataType" type="text" value="recent" style="display: none;" />
				</td>
			</tr>				
		</table>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
			<tr bgcolor="#FFFFFF">
				<td>
					用户ID:
					<input id="txtUserIdx" type="text" style="width:80px" />
					  &nbsp;&nbsp;
				   <input id="cbxTime" type="checkbox" checked="checked"/>时间
				   <input id="txtTimeBegin" type="text" style="width:90px" value="<?php echo date('Y-m-d',strtotime('-1 week')); ?>" />&nbsp;
				   <input id="txtTimeEnd" type="text" style="width:90px" value="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" />
				   <input id="cbxExceptInner" type="checkbox" checked="checked">排除内部号
				   页大小
				   <input id="txtPageSize" type="text" value="20" style="width:50px" />
				   &nbsp;&nbsp;
				  <input type="button" value="查询" id="btnSearch" class="btn-1"/>                 
			  </td>    
			  <td width="180">
				  <select id = "sltOrder">
						<option value="time">登录时间</option>
						<option value="uid">用户ID</option>
						<option value="loginip">登录IP</option>
					</select>
					&nbsp;&nbsp;
					<select id = "sltOrderType">
						<option value="desc">降序</option>
						<option value="">升序</option>
					</select>
			  </td>
			</tr>
		</table>
		<table id='tbResult' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			<tr bgcolor="#f5fafe">
				<td>用户ID</td>
				<td>用户</td>
				<td>昵称</td>
				<td>流水</td>
				<td>登录时间</td>
				<td>登录IP</td>
			</tr>
		</table>
		<div class="fenyebar" id="PageInfo"></div>
	</div>
</body>
</html>
<script type= "text/javascript" language ="javascript">
	$(window.parent.document).attr("title","登录失败记录");
    $(document).ready(function() { 
    	InitDatePicker("txtTimeBegin");
    	InitDatePicker("txtTimeEnd");
    	var useridx = request("id");
    	$("#lblUserIdx").html(useridx);
    	$("#txtUserIdx").val(useridx);
    	GetLoginFailData();
	});
    //**************************************************************************
    //查询
    $("#btnSearch").click(function(){
    	GetLoginFailData();
    });
    
    //取记录
    function GetLoginFailData()
    { 
        var data = GetData();
        SendAjax(data);
    }
    //取参数
    function GetData()
    {   
    	$("#tbResult tr:gt(0)").remove();
    	$("#PageInfo").html('');     
        var data = "action=referrals";
        var useridx = $("#txtUserIdx").val();
        var DateBegin = $("#txtTimeBegin").val();
        var DateEnd = $("#txtTimeEnd").val();
        var PageSize = $.trim($("#txtPageSize").val());
        
        var isExceptInner = 0;
        if($("#cbxExceptInner").is(":checked"))
        	isExceptInner = 1;
        data += "&isexceptinner=" + isExceptInner;
        
        if(PageSize == "" || isNaN(PageSize))
        {
            PageSize = "20";
            $("#txtPageSize").val("20");
        }
        data += "&PageSize=" + PageSize;
        if(useridx != "")
        {
            if(isNaN(useridx))
            {
                $("#txtUserIdx").val("");
            }
            else
            {
                data += "&userid=" + useridx;
            }            
        }
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
        data += "&order=" + $("#sltOrder").val() + "&ordertype=" + $("#sltOrderType").val();
        return data;
    }
    //登录成功分页
    function ajax_page(page)
    {
        var data = GetData();
        data += "&Page=" + page;
        SendAjax(data);
    }
    //****************************************************************************************
    //公共函数
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
		var PostURL = "";
		$.ajax({
		       type: "POST",
		       async:false,
		       dataType: "json",
		       url: PostURL,
		       data: SendData,
		       success: function(data) {DataSuccess(data);},
               error:function(XMLHttpRequest, textStatus, errorThrown){alert('error:'+textStatus);}
		});
	}
	//数据成功后
	function DataSuccess(json)
	{
		var tbody = "";
		var pageinfo = "";
        var InfoType = "";
		console.info(json);
		$.each(json,function(i,item){
			if(i == 0)
			{
				InfoType = item.cmd;
				switch(item.cmd)
				{
					case "get_loginfail":
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
				if(InfoType == "get_loginfail")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td>" + item.UserID +"</td>" +
                            
                            "<td>" + item.username +"</td>" +
                            
                            "<td>" + item.NickName +"</td>" +
                            "<td>" + item.tzpoint +"</td>" +
                            "<td>" + item.logintime + "</td>" +
                            "<td>" + item.IP + "</td>" +
                            "</tr>";
                 }
			}

		});
		if(tbody != "")
		{
			if(InfoType == "get_loginfail")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
                $("#PageInfo").html(pageinfo);
            }
		}
	}
</script>

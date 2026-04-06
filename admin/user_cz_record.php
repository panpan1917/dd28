<?php
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );

$action=$_REQUEST['action'];

if($action=='get_list'){
//var_dump(222222);
    $TimeBegin = isset($_POST['txtTimeBegin'])?FilterStr($_POST['txtTimeBegin']):"";
    $TimeEnd = isset($_POST['txtTimeEnd'])?FilterStr($_POST['txtTimeEnd']):"";
    $username=isset($_POST['txtUserName'])?FilterStr($_POST['txtUserName']):'';

    $userid=isset($_POST['userid'])?FilterStr($_POST['userid']):'';
    $status=isset($_POST['status'])?intval($_POST['status']):-1;
    $sqlCount = "select Count(*) ";
        $sqlCol = "SELECT a.opr_time,a.opr_type,a.amount,a.uid,b.nickname,a.opr_user,c.name,a.remark_type,a.reason
                  ";
        $sqlFrom = "FROM admin_translog a
					LEFT OUTER JOIN users b
					ON a.uid = b.id
					LEFT OUTER JOIN admin c
					ON a.opr_user = c.id
					WHERE 1=1 ";
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
    //时间
    $TimeField = "a.opr_time";
    $sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
	//var_dump($TimeBegin);
	//var_dump($TimeEnd);
    $username=trim($username);
    $userid=trim($userid);
    if($username!=''){
        $sqlWhere.=' and b.username like \'%'.$username.'%\'';
    }
    if($userid!=''){
        $sqlWhere.=' and a.uid = \''.$userid.'\'';
    }
 
    //取得排序
    $sqlOrder = " order by a.id desc";
    //取得总记录数
    $TotalCount = $db->GetRecordCount($sqlCount.$sqlFrom.$sqlWhere);
    //取记录
   $sql = $sqlCol . $sqlFrom . $sqlWhere . $sqlOrder . GetLimit($page,$PageSize);
   // var_dump($sql);
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
        //对返回数据进行包装
        $row['log_time'] = $row["log_time"];
        $arrRows[$i] = $row;
    }
    //返回分页
    require_once('inc/fenye.php');
    $ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
    $pageInfo = $ajaxpage->show();
    $arrRows[0]["cmd"] = $act;
    $arrRows[0]["msg"] = $pageInfo;
    die(json_encode($arrRows));
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理-系统充值记录</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta name="GENERATOR" content="MSHTML 6.00.3790.4275">
	<link rel="stylesheet" type="text/css" href="images/css_body.css">
	<link rel="stylesheet" type="text/css" href="images/window.css">
	<link rel="Stylesheet" type="text/css" href="images/jquery_ui.css" />
	<script type="text/javascript" src="images/jquery.js"></script> 
	<script type="text/javascript" src="images/jquery_ui.js"></script>
</head>
<body>
<div>
<div>
	 <!-- 查询 -->
        <table width="99%" border="0" align="center" cellpadding="0" cellspacing="1" class="tbtitle" style="BACKGROUND: #cad9ea;">
            <tr bgcolor="#FFFFFF">
                <td  colspan="10" style="text-align:left">查询历史记录</td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td colspan="3">
                   用户ID:
                        <input id="txtUserIdx" type="text" style="width:100px" />
                        用户名:
                        <input id="txtUserName" type="text" style="width:100px" />
                   时间
                   <input id="txtTimeBegin" type="text" style="width:80px" value="<?php echo date('Y-m-d',strtotime('-1 week')); ?>" />&nbsp;
                   <input id="txtTimeEnd" type="text" style="width:80px" value="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" />
                   页大小
                   <input id="txtATPageSize" type="text" value="20" style="width:30px" />                        
                    <!--充值类型
                    <select id="sltATOprType">
						<option value="-1">所有</option>
						<option value="0">充值可用分</option>
                        <option value="1">充值银行分</option>
                        <option value="2">充值经验</option>
                        <option value="3">投注分</option> 
					</select>-->
                    &nbsp;&nbsp;
                    <input type="button" value="查询" id="btnSearch" class="btn-1"/>
                </td>
                <td width="180">
                  <select id = "sltATOrder">
						<option value="opr_time">操作时间</option>
						<option value="opr_type">类型</option>
                        <option value="amount">数量</option>
                        <option value="uid">会员ID</option>
                        <option value="opr_user">操作用户</option>
                        <option value="remark_type">备注类型</option>
						<option value="reason">备注</option>
				  </select>
					&nbsp;&nbsp;
					<select id = "sltATOrderType">
						<option value="desc">降序</option>
						<option value="">升序</option>
					</select>
                </td>
            </tr>
      </table>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" id="tblList">
		  	<tr bgcolor="#f5fafe">
				<td  >操作人</td>
				<td  >操作时间</td>
				<td  >会员ID</td>
                <td  >昵称</td>
                <td  >充值类型</td>
                <td  >数量</td>
                <td  >备注类型</td>
                <td  >备注</td>
			</tr>			    
		</table>
		<div class="fenyebar" id="pageinfo"></div>
	</div>
	</div>
</body>

<script type= "text/javascript" language ="javascript">
    $(document).ready(function() { 
    	InitDatePicker("txtTimeBegin");
    	InitDatePicker("txtTimeEnd");
    	var useridx = request("id");
    	//var typeid = request("typeid");
    	//$("#lblUserIdx").html(useridx);
    	$("#txtUserIdx").val(useridx);
    	
    	SearchData();
	});

        //SearchData();
       
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
        var data = "action=get_list";
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
		
        //data += "&status=" + $("#sltStatus").val();
		data += "&txtTimeBegin=" + $("#txtTimeBegin").val();;
		data += "&txtTimeEnd=" + $("#txtTimeEnd").val();
        if($("#txtUserName").val() != "")
            data += "&username=" + $("#txtUserName").val();
        //if($("#txtOrderId").val() != "")
        //    data += "&orderid=" + $("#txtOrderId").val();


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
        //data += "&order=" + $("#sltOrder").val();
        //data += "&ordertype=" + $("#sltOrderType").val();

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
        var PostURL = "";

        $.ajax({
            type: "POST",
            async:false,
            dataType: "json",
            url: PostURL,
            data: SendData,
            success: function(data) {
                
                DataSuccess(data);
            }
        });

    }
    //数据成功后
    function DataSuccess(json)
    {
        console.info(json)
        var tbody = "";
        var pageinfo = "";
        $.each(json,function(i,item){
            if(i == 0)
            {
                switch(item.cmd)
                {
                    case "get_list":
                        pageinfo = item.msg;
                        break;
                    default:
                        //alert(item.msg);
                        pageinfo = item.msg;
                        return;
                        break;
                }
            }
            else
            {
			console.info(item);

                 tbody += "<tr bgcolor='#FFFFFF'>" +
                    			"<td>" + item.name + "</td>" +
                                "<td>" + item.opr_time + "</td>" +
                                "<td>" + item.uid + "</td>" +
                                "<td>" + item.nickname + "</td>" +
                                "<td>" + item.remark_type + "</td>" +
                                "<td>" + item.amount + "</td>" +
                                "<td>" + item.remark_type + "</td>" + 
                                "<td>" + item.reason + "</td>" + 
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

    
    function confirms(order_id) {
        if(confirm("您确定要拒绝吗？"))

        {
            $.getJSON('?action=confirm&id='+order_id,function (data) {
                if(data.status==0){
                    //alert("充值成功");
                    location.reload();
                }else{
                    alert(data.message);
                }
            })
        }
    }
</script>
</html>
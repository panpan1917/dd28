<?php
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );


$action=$_REQUEST['action'];
if($action=='get_list'){
    $TimeBegin = isset($_POST['txtTimeBegin'])?FilterStr($_POST['txtTimeBegin']):"";
    $TimeEnd = isset($_POST['txtTimeEnd'])?FilterStr($_POST['txtTimeEnd']):"";
    $username=isset($_POST['txtUserName'])?FilterStr($_POST['txtUserName']):'';

    $userid=isset($_POST['userid'])?FilterStr($_POST['userid']):'';
    $status=isset($_POST['status'])?intval($_POST['status']):-1;
    $sqlCount = "select Count(*) ";
    $sqlCol = "SELECT s.*,u.username,g.game_name ";
    $sqlFrom = "FROM game_day_static s left join users u on u.id=s.uid
	             left outer join game_config g on g.game_type=s.kindid
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
    $TimeField = "s.time";
    $sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
	//var_dump($TimeBegin);
	//var_dump($TimeEnd);
    $username=trim($username);
    $userid=trim($userid);
    if($username!=''){
        $sqlWhere.=' and u.username like \'%'.$username.'%\'';
    }
    if($userid!=''){
        $sqlWhere.=' and s.uid = \''.$userid.'\'';
    }
 
    //取得排序
    $sqlOrder = " order by s.id desc";
    //取得总记录数
    $TotalCount = $db->GetRecordCount($sqlCount.$sqlFrom.$sqlWhere);
    //取记录
   $sql = $sqlCol . $sqlFrom . $sqlWhere . $sqlOrder . GetLimit($page,$PageSize);
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
    
    $total_points = 0;
    $total_tzpoints = 0;
    for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
    {
        //对返回数据进行包装
        $row['time'] = $row["time"];
        $arrRows[$i] = $row;
        $total_points += $row["points"];
		$total_tzpoints += $row["tzpoints"];
    }
	
	if($RowCount > 0)
        {
             /*$sql = "SELECT SUM(tzpoints) as tzpoints,COUNT(*) AS cnt
                    FROM game_day_static
                    WHERE uid = {$userid}";
             $result = $db->query($sql); 
             $row = $db->fetch_array($result);
             
             $sTotal = "总:" . $row['tzpoints'] . "," . $row['cnt'] . "笔";*/
             $index = $RowCount + 1;
             
             $arrRows[$index]["uid"] = "";
             $arrRows[$index]["username"] = "";
			 $arrRows[$index]["game_name"] = "";
             $arrRows[$index]["points"] = "页小计:".$total_points;
			 $arrRows[$index]["tzpoints"] = $total_tzpoints;
			 $arrRows[$index]["time"] = "";
                 
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
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>每日流水记录查询</title>
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
    <div class="bodytitletxt">用户每日流水查询</div>
</div>

    <div>
        <div>
            <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
                <tr bgcolor="#FFFFFF">
                    <td colspan="8">
                        用户ID:
                        <input id="txtUserIdx" type="text" style="width:100px" />
                        用户名:
                        <input id="txtUserName" type="text" style="width:100px" />
						时间:
                        <input id="txtTimeBegin" type="text" style="width:90px" value="<?php echo date('Y-m-d',strtotime('0 day')); ?>" />&nbsp;
				   <input id="txtTimeEnd" type="text" style="width:90px" value="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" />
                        <!--订单ID:
                        <input id="txtOrderId" type="text" style="width:100px" />-->
                        <input type="button" name="btnSearch" value="查询" id="btnSearch" class="btn-1" />
                        &nbsp;


                        &nbsp;&nbsp;每页
                        <input type="text" id="txtPageSize" style="width:30px" value="20" />
                        条 </td>
                </tr>
            </table>
            <table id='tblList' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
                <tr bgcolor="#f5fafe">
                    <td>用户ID</td>
                    <td>昵称</td>
                    <td>所在游戏</td>
                    <td>输赢</td>
                    <td>投注额</td>
					<td>时间</td>
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
		$("#lblUserIdx").html(useridx);
    	$("#txtUserIdx").val(useridx);
    	SearchData();
    	//GetData();
	});

        SearchData();
        
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
    {console.info("xxx")
        var PostURL = "";

        $.ajax({
            type: "POST",
            async:false,
            dataType: "json",
            url: PostURL,
            data: SendData,
            success: function(data) {
                console.info("xxx")
                DataSuccess(data);
                console.info("xxx")
            }
        });

    }
    //数据成功后
    function DataSuccess(json)
    {
        console.info("xxx")
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

                tbody += "<tr bgcolor='#FFFFFF'>" +

                    "<td><a href='index.php?url=admin_singleuser.php%3Fidx%3D"+item.uid+"' target='_blank'> " + item.uid + "</a></td>" +
                    "<td>" + item.username + "</td>" +
                    "<td>" + item.game_name + "</td>" +
                    "<td>" + item.points + "</td>" +
                    "<td>" + item.tzpoints + "</td>" +
					"<td>" + item.time + "</td>" +
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

<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );

$action=$_REQUEST['action'];
if($action=='get_list'){
    $date = isset($_POST['date'])?FilterStr($_POST['date']):"";
	$tjid=isset($_POST['tjid'])?FilterStr($_POST['tjid']):''; //推广员ID
	//var_dump($tjid);
    $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
    $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
    $username=isset($_POST['username'])?FilterStr($_POST['username']):'';
 	
    $userid=isset($_POST['userid'])?FilterStr($_POST['userid']):'';
    $status=isset($_POST['status'])?intval($_POST['status']):-1;
    $sqlCount = "select Count(*) ";
    $sqlCol = "SELECT * ";
    $sqlFrom = "FROM users u
                    WHERE 1=1 ";
    if($date){
        $sqlFrom.=' and to_days(time)=TO_DAYS(\''.date('Y-').$date.'\')';
    }
	if($tjid!=0){
	   $sqlFrom.=' and u.tjid=\''.$tjid.'\'';
	}
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
    $TimeField = "u.time";
    $sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
    $username=trim($username);
    $userid=trim($userid);
	
    if($username!=''){
        $sqlWhere.=' and u.username like \'%'.$username.'%\'';
    }
	

    //取得排序
    $sqlOrder = " order by time desc";
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
    for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++, $row=$db->fetch_array($result))
    {
        //对返回数据进行包装
        $arrRows[$i]["regtime"] = $row["time"];
        $arrRows[$i]["id"] = $row["id"];
        $arrRows[$i]["username"] = $row["username"];
        $arrRows[$i]["nickname"] = ($row["nickname"]);
        $arrRows[$i]["points"] = ($row["points"]);
        $arrRows[$i]["back"] = ($row["back"]);
        $arrRows[$i]["regip"] = ($row["regip"]);
        $arrRows[$i]["logintime"] = ($row["logintime"]);
        $arrRows[$i]["loginip"] = ($row["loginip"]);
        $arrRows[$i]["source"] = ($row["source"]);

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
    <title>提现记录查询</title>
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
    <div class="bodytitletxt">注册记录查询</div>
</div>
<?php if($action=='pay'){
?>
<?php }else{?>
<div>
    <div>
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;display: none" >
            <tr bgcolor="#FFFFFF">
                <td colspan="8">
                    用户ID:
                    <input id="txtUserIdx" type="text" style="width:100px" />
                    用户名:
                    <input id="txtUserName" type="text" style="width:100px" />
                    <!--订单ID:
                    <input id="txtOrderId" type="text" style="width:100px" />-->
                    <input type="button" name="btnSearch" value="查询" id="btnSearch" class="btn-1" />
                    &nbsp;
                    状态
                    <select id = "sltStatus">
                        <option value="-1">所有</option>
                        <option value="0">未支付</option>
                        <option value="1">支付成功</option>
                        <option value="2">支付失败</option>
                        <option value="3">已取消</option>
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
        </table>
        <table id='tblList' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
            <tr bgcolor="#f5fafe">

                <td>用户ID</td>
                <td>用户名称</td>
                <td>昵称</td>
                <td>注册时间</td>
                <td>积分</td>
                <td>银行</td>
                <td>注册ip</td>
                <td>登录IP</td>
                <td>登录时间</td>
                <td>来源</td>
            </tr>
        </table>
        <div class="fenyebar" id="pageinfo"></div>
    </div>

</div>
<?php }?>
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
                console.info('您确定要冻结吗')
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

    });


    //查询数据
    function SearchData()
    {
        var data = GetData();
        console.info('查询数据')
        SendAjax(data);

    }
    //分页
    function ajax_page(page)
    {
        var data = GetData();
        data += "&Page=" + page;
        console.info('分页')
        SendAjax(data);
    }
    //取得查询条件
    function GetData()
    {
        $("#tblList tr:gt(0)").remove();
        $("#pageinfo").html('');
        var data = "action=get_list";
        var date='<?php echo trim($_GET['date']);?>';
		var tjid='<?php echo trim($_GET['tjid']);?>';
        var userid = $("#txtUserIdx").val()
        if(date){
            data+='&date='+date;
        }
		if(tjid!=""){
		    data+='&tjid='+tjid;
		}
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
        data += "&status=" + $("#sltStatus").val();

        if($("#txtUserName").val() != "")
            data += "&username=" + $("#txtUserName").val();
        if($("#txtOrderId").val() != "")
            data += "&orderid=" + $("#txtOrderId").val();


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

                    "<td><a href='index.php?url=admin_singleuser.php%3Fidx%3D"+item.id+"' target='_blank'> " + item.id + "</a></td>" +
                    "<td>" + item.username + "</td>" +
                    "<td>" + item.nickname + "</td>" +
                    "<td>" + item.regtime + "</td>" +
                    "<td>" + item.points + "</td>" +
                    "<td>" + item.back + "</td>" +
                    "<td>" + item.regip + "</td>" +
                    "<td>" + item.loginip + "</td>" +
                    "<td>" + item.logintime + "</td>" +
                    "<td>" + item.source + "</td>" +
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
    
    setInterval(luck,60000);
    function luck() {
        $.ajax({
            type: "POST",
            async:false,
            dataType: "json",
            url: '?action=luck',
            success: function(data) {

                if(data.status==1){
                    //alert("有新的充值,请刷新");
                }
            }
        });
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

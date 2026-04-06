<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
	
	
$action=$_REQUEST['action'];
$userid=intval($_GET['userid'])?:'';
$status=intval($_GET['status'])?:-1;
$TimeBegin = isset($_GET['txtTimeBegin'])?FilterStr($_GET['txtTimeBegin']):"";
if($action=='get_list'){
    $TimeBegin = isset($_POST['txtTimeBegin'])?FilterStr($_POST['txtTimeBegin']):"";
    $TimeEnd = isset($_POST['txtTimeEnd'])?FilterStr($_POST['txtTimeEnd']):"";
    $username=isset($_POST['username'])?FilterStr($_POST['username']):'';

    $userid=isset($_POST['userid'])?FilterStr($_POST['userid']):'';
    $status=isset($_POST['status'])?intval($_POST['status']):-1;
    $sqlCount = "select Count(*) ";
    $sqlCol = "SELECT p.* ,c.game_name ";
    $sqlFrom = "FROM update_tz  p  left join game_config c on c.game_type=p.tid  WHERE 1=1  ";
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
    $TimeField = "p.up_time";
    $sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
    $username=trim($username);
    $userid=trim($userid);
    if($username!=''){
        $sqlWhere.=' and u.username like \'%'.$username.'%\'';
    }
    if($userid!=''){
        $sqlWhere.=' and p.uid = \''.$userid.'\'';
    }

    //取得排序
    $sqlOrder = " order by p.id desc";
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
    for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
    {
        //对返回数据进行包装
        $arrRows[$i]["id"] = $row["id"];
        $arrRows[$i]["game_name"] = $row["game_name"];
        $arrRows[$i]["uid"] = $row["uid"];
        $arrRows[$i]["no"] = ($row["no"]);
        $arrRows[$i]["tzpoints"] = ($row["tzpoints"]);
		$arrRows[$i]["tznum"] = ($row["tznum"]);
		$arrRows[$i]["otzpoints"] = ($row["otzpoints"]);
        $arrRows[$i]["otznum"] = ($row["otznum"]);
        $arrRows[$i]["tz_time"] = ($row["tz_time"]);
        $arrRows[$i]["up_time"] = $row["up_time"];

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
    <title>记录查询</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta name="GENERATOR" content="MSHTML 6.00.3790.4275">
    <link rel="stylesheet" type="text/css" href="images/css_body.css">
    <link rel="stylesheet" type="text/css" href="images/window.css">
    <link rel="Stylesheet" type="text/css" href="images/jquery_ui.css" />
    <script type="text/javascript" src="images/jquery.js"></script>
    <script type="text/javascript" src="images/jquery_ui.js"></script>
</head>

<body>

<?php if($action=='pay')
{
?>
<div class="bodytitle">
    <div class="bodytitleleft"></div>
    <div class="bodytitletxt">提现记录查询</div>
</div>
<div>
    <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
        <?php
        $sql='select * from withdrawals where uid='.$pay_result['uid'];
        $res=$db->query($sql);
        while ($row=$db->fetch_array($res)){


        ?>
        <tr bgcolor="#FFFFFF">
            <td>类型:</td><td><?php if($row['type']==1){echo '支付宝'; }else{echo '银行卡';}?></td>
        </tr>
            <tr bgcolor="#FFFFFF">
            <td>账号:</td><td><?php echo $row['account'];?></td>
            </tr>
            <tr bgcolor="#FFFFFF">
            <td>名称:</td><td><?php echo $row['name'];?></td>
            </tr>
            <tr bgcolor="#FFFFFF">
            <td>地址:</td><td><?php echo $row['address'];?></td>
        </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
        <?php }?>
        <tr>
            <td colspan="2" bgcolor="#fff">
                <button type="button" id="alipay">已打款</button>
                <button type="button" id="bank" style="display: none">已通过银行卡打款</button>
                <button type="button" id="weichat" style="display: none">已通过微信打款</button>
                <button type="button" onclick="javascript:history.go(-1);">回到上一页</button>

            </td>
        </tr>
    </table>
</div>
    <script type="text/javascript">
        $(document).ready(function () {
            var id=<?php echo $id;?>;
            $("#alipay").click(function () {
                $.getJSON('?action=to_pay','t=1&id='+id,function (data) {
                    console.info(data);
                    if(data.status==0){
                        location.href="admin_withdrawals.php";
                    }else{
                        alert(data.message);
                    }
                });
            });
            $("#weichat").click(function () {
                $.getJSON('?action=to_pay','t=5&id='+id,function (data) {
                    console.info(data);
                    if(data.status==0){
                        location.href="admin_withdrawals.php";
                    }else{
                        alert(data.message);
                    }
                });
            });
            $("#bank").click(function () {
                $.getJSON('?action=to_pay',"t=3&id="+id,function (data) {
                    console.info(data);
                    if(data.status==0){
                        location.href="admin_withdrawals.php";
                    }else{
                        alert(data.message);
                    }
                });
            });
        });
    </script>
<?php }else{?>

<div class="bodytitle">
    <div class="bodytitleleft"></div>
    <div class="bodytitletxt">投注异常查询</div>
</div>
<div>
    <div>
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
            <tr bgcolor="#FFFFFF">
                <td colspan="8">
                    用户ID:
                    <input id="txtUserIdx" type="text" style="width:100px" value="<?php echo $userid;?>" />
                    用户名:
                    <input id="txtUserName" type="text" style="width:100px" />
                    <!--订单ID:
                    <input id="txtOrderId" type="text" style="width:100px" />-->
                    <input type="button" name="btnSearch" value="查询" id="btnSearch" class="btn-1" />
                    &nbsp;
                    状态
                    <select id = "sltStatus">
                        <option value="-1" <?php if($status==-1){ echo 'selected';}?>>所有</option>
                        <option value="30" <?php if($status==30){ echo 'selected';}?>>未审核</option>
                        <option value="32" <?php if($status==32){ echo 'selected';}?>>已支付</option>
                        <option value="31" <?php if($status==31){ echo 'selected';}?>>未通过</option>
                    </select>
                    用户类型
                    <select id = "sltUserType">
                        <option value="0">用户</option>
                        <option value="-1">所有</option>
                        <option value="1">机器</option>
                    </select>
					时间<input id="txtTimeBegin" type="text" style="width:90px" value="<?php if($TimeBegin){echo $TimeBegin;}else{ echo date('Y-m-d',strtotime('+0 day'));} ?>" />&nbsp;
				   <input id="txtTimeEnd" type="text" style="width:90px" value="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" />
                    <input id="cbxExceptInner" type="checkbox" >排除内部号
                    &nbsp;&nbsp;每页
                    <input type="text" id="txtPageSize" style="width:30px" value="20" />
                    条 </td>
            </tr>
        </table>
        <table id='tblList' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;font-size:12px;">
            <tr bgcolor="#f5fafe">

                <td>用户ID</td>
                <td>游戏</td>
                <td>期号</td>
                <td>变化前号码</td>
                <td>变化前积分</td>
                <td>号码</td>
                <td>积分</td>
                <td>投注积分</td>
                <td>变化时间</td>
            </tr>
        </table>
        <div class="fenyebar" id="pageinfo"></div>
    </div>

</div>
<?php }?>
</body>

<script type= "text/javascript" language ="javascript">
    $(document).ready(function() {
    	InitDatePicker("txtTimeBegin");
        InitDatePicker("txtTimeEnd");
    	//GetData();
	});


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
         data += "&status=" + $("#sltStatus").val();
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
                    "<td>" + item.game_name + "</td>" +
                    "<td>" + item.no + "</td>" +
                    "<td>" + item.otznum + "</td>" +
                    "<td>" + item.otzpoints+ "</td>" +
                    "<td>" + item.tznum + "</td>" +
                    "<td>" + item.tzpoints + "</td>" +
                    "<td>" + item.tz_time + "</td>" +
                    "<td>" + item.up_time + "</td>" +
                    "<td>" ;

                tbody+=
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

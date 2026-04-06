<?php
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );

$action=$_REQUEST['action'];
if($action=='get_list'){
    $TimeBegin = isset($_POST['txtTimeBegin'])?FilterStr($_POST['txtTimeBegin']):"";
    $TimeEnd = isset($_POST['txtTimeEnd'])?FilterStr($_POST['txtTimeEnd']):"";
    $username=isset($_POST['username'])?FilterStr($_POST['username']):'';
    $orderid=isset($_POST['orderid'])?FilterStr($_POST['orderid']):'';
    $userid=isset($_POST['userid'])?FilterStr($_POST['userid']):'';
    $status=isset($_POST['status'])?intval($_POST['status']):-1;
    $sqlCount = "select Count(*) ";
    $sqlCol = "SELECT * ";
    $sqlFrom = "FROM recharge_type p 
                    WHERE 1=1 ";
    $sqlWhere = "";
    $sqlOrder = "";
    $sql = "";
    //页大小
    $PageSize = isset($_POST['PageSize'])?$_POST['PageSize']:30;
    $PageSize = intval($PageSize);
    //页码
    $page = isset($_POST['Page'])?$_POST['Page']:1;
    $page =intval($page);

    $arrReturn = array(array());

    //取得排序
    $sqlOrder = " order by id desc";
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
        $arrRows[$i]["name"] = $row["name"];
        $arrRows[$i]["remarks"] = $row["remarks"];
        $arrRows[$i]["marks"] = $row["marks"];
        $arrRows[$i]["site"] = $row["site"];
        $arrRows[$i]["source"] = $row["source"];
        $state='';
        $cz='';
        if($row['is_show']==0){
            $state='隐';
        }elseif($row['is_show']==1){
            $state='显示中';
        }
        $arrRows[$i]['show']=$state;
        $arrRows[$i]['is_show']=$row['is_show'];
    }
    //返回分页
    require_once('inc/fenye.php');
    $ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
    $pageInfo = $ajaxpage->show();
    $arrRows[0]["cmd"] = $act;
    $arrRows[0]["msg"] = $pageInfo;
    die(json_encode($arrRows));
}
elseif($action=='mod'){
	login_check( "system" );
    $id=intval($_GET['id']);
    $v=$_GET['v']?0:1;
    if($id){
        $sql='update recharge_type set is_show='.$v.' where id='.$id;
        $db->query($sql);
    }
    echo json_encode('ok');
    exit;
}
elseif($action=='rmove'){
	login_check( "system" );
    $id=intval($_GET['id']);
    if($id){
        $sql='delete from recharge_type where id < 1000 and id='.$id;
        $db->query($sql);
    }
    echo json_encode('ok');
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>充值记录查询</title>
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
    <div class="bodytitletxt">充值种类</div>
</div>
<div>
    <div>
        <table id='tblList' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="font-size:16px;BACKGROUND: #cad9ea;">
            <tr bgcolor="#f5fafe">
                <td>ID</td>
                <td>名称</td>
                <td>渠道来源</td>
                <td>姓名</td>
                <td>备注</td>
                <td>使用站点</td>
                <td>状态</td>
                <td>操作</td>
            </tr>
        </table>
        <div class="fenyebar" id="pageinfo"></div>
    </div>

</div>
</body>

<script type= "text/javascript" language ="javascript">
    $(window.parent.document).attr("title","充值种类");
    $(document).ready(function() {
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
                    "<td>" + item.id + "</td>" +
                    "<td>" + item.name + "</td>" +
                    "<td>" + item.source + "</td>" +
                    "<td>" + item.remarks + "</td>" +
                    "<td>" + item.marks + "</td>" +
                    "<td>" + item.site + "</td>" +
                    "<td><a href='javascript:mod(" + item.id + "," + item.is_show + ")'>" + item.show + "</a></td>" +
                    "<td><a href='javascript:rmovetype(" + item.id + ")'>彻底删除</a></td>" +
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
    function mod(id,val) {
        if(confirm("确定开启或关闭充值？"))
        {
            $.getJSON('?action=mod&v='+val+'&id='+id,function (data) {
                    location.reload();
            })
        }
    }

    function rmovetype(id) {
        if(confirm("确定要彻底删除此充值方式？"))
        {
            $.getJSON('?action=rmove&id='+id,function (data) {
                    location.reload();
            })
        }
    }
</script>

</html>

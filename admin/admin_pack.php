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
    $sqlCol = "SELECT p.* ";
    $sqlFrom = "FROM pack p 
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
    //$TimeField = "p.endtime";
    //$sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
    if($TimeBegin && $TimeEnd){
        $sqlWhere.=' and p.endtime>='.strtotime($TimeBegin).' and p.endtime<='.strtotime($TimeEnd);
    }
    //取得排序
    $sqlOrder = " order by p.id desc";
    //取得总记录数

    $TotalCount = $db->GetRecordCount($sqlCount.$sqlFrom.$sqlWhere);
    //取记录
    $sql = $sqlCol . $sqlFrom . $sqlWhere . $sqlOrder . GetLimit($page,$PageSize);
    $RowCount = 0;
    //echo $sql;
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
    
    $arrRows[0]["cmd"] = $action;
    for($i=0,$row=$db->fetch_array($result);$i < $RowCount;$i++,$row = $row=$db->fetch_array($result))
    {
        //对返回数据进行包装
        $row['endtime']=date('Y-m-d H:i:s',$row['endtime']);
        $arrRows[1][$i] = $row;
    }
    //返回分页
    require_once('inc/fenye.php');
    $ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
    $pageInfo = $ajaxpage->show();
    $arrRows[0]["msg"] = $pageInfo;
    die(json_encode($arrRows));
}
elseif($action=='addnew'){
	login_check( "system" );
    $count=intval($_GET['count'])?:0;
    $min=intval($_GET['min'])?:0;
    $max=intval($_GET['max'])?:0;
    $desc=str_check($_GET['desc']);
    $pack_num=intval($_GET['pack_num'])?:1;
    if($count==0 || $max==0){
        die(json_encode(array('status'=>1,'message'=>'不能为0')));
    }
    if($min>$max){
        die(json_encode(array('status'=>1,'message'=>'最小不能大于最大')));
    }
    
    //print_r($_REQUEST);exit;
    
    $len=0;
    for($i=0;$i<$pack_num;$i++) {
        $num=get_id();
        $time = time();
        $sql = "insert into pack(type,num,count,endcount,min,max,endtime,`pack_desc`)values(1,'{$num}',{$count},0,{$min},{$max},{$time},'{$desc}')";//"(typeid,uid,ledou,time) values(1,)";
        //echo "<br>";
        //continue;
        $db->query($sql);
        $row = $db->affected_rows();
        $len+=$row;
    }
    die(json_encode(array('status'=>0,'message'=>'影响行数'.$len)));
}elseif($action=='mod'){
	login_check( "system" );
    $id=intval($_POST['id']);
    $status=intval($_POST['status'])?1:0;
    if($id){
        $sql="update pack set status={$status} where id={$id}";
        $db->query($sql);
    }
    
    die(json_encode(array('status'=>0,'message'=>'更新完成')));
}
function get_id(){
	$strSrc = "01234567890abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ";
	$strLen = strlen($strSrc);
	$num = "";
	for($i=0;$i<10;$i++){
		$idx = rand(0, $strLen);
		$num = $num . $strSrc[$idx];
	}
	
	global  $db;
	$sql = "select num from pack where num='{$num}'";
	$pack = $db->fetch_first($sql);
	if(empty($pack)){
		return $num;
	}else{
		return get_id();
	}
	
	
	
    /* global  $db;
    $sql = 'select FLOOR(100000 + RAND()*900000) AS id from pack where num!=id limit 1';
    $pack = $db->fetch_first($sql);
    if(empty($pack)) return FLOOR(100000 + RAND()*900000);
    $num = $pack['id'];
    if($num)return $num;
    return get_id(); */
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
    <script type="text/javascript" src="js/jquery.zclip.js"></script>
    <script src="js/clipboard.min.js"></script>
    <script type="text/javascript">
        $(function () { new Clipboard('.copy');    })
    </script>
</head>

<body>
<div class="bodytitle">
    <div class="bodytitleleft"></div>
    <div class="bodytitletxt">红包记录查询</div>
</div>
<div>
    <div>
        <table class="tbtitle" width="99%" data-clipboard-text="dd" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
            <tr bgcolor="#FFFFFF">
                <td colspan="8">
                    红包数量:
                    <input id="count" type="number" style="width:100px" />
                    乐豆最小:
                    <input id="min" type="number" style="width:100px" />
                    乐豆最大:
                    <input id="max" type="number" style="width:100px" />
                    红包个数:
                    <input id="pack_num" type="pack_num" value="1" style="width:50px" />
                    说明:
                    <input id="desc" type="desc" value="" style="width:100px" />
                    <input type="button" name="btnSearch" value="生成" id="btnCreate" class="btn-1" />
                    时间:<input id="txtTimeBegin" type="text" style="width:90px" value="<?php echo date('Y-m-d',strtotime('+0 day')); ?>" />&nbsp;
                    <input id="txtTimeEnd" type="text" style="width:90px" value="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" />
                    <input type="button" name="btnSearch" value="查询" id="btnSearch" class="btn-1" />
                </td>
            </tr>
        </table>
        <table id='tblList' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
            <tr bgcolor="#f5fafe">
                <td>ID</td>
                <td>类ID</td>
                <td>号码</td>
                <td>应发总数</td>
                <td>实发总数</td>
                <td>最小</td>
                <td>最大</td>
                <td>说明</td>
                <td>时间</td>
                <td>操作</td>
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
        //GetData();
    });
    $(window.parent.document).attr("title","批用户查询");
    $(document).ready(function() {
        $("#btnCreate").bind('click',function () {
            var count=parseInt($("#count").val());
            var min=parseInt($("#min").val());
            var max=parseInt($("#max").val());
            var pack_num=parseInt($("#pack_num").val());
            var desc=($("#desc").val());
            console.info( count)
            if(isNaN(count) ){
                console.info(111)
                alert("数量错了");
                return ;
            }
            if(isNaN(min) ){
                alert("最小必须大于0");
                return;
            }
            if(isNaN(max)){
                alert("最大必须是整数");
                return;
            }
            $.getJSON('admin_pack.php?action=addnew&count='+count+'&min='+min+'&max='+max+'&pack_num='+pack_num+'&desc='+desc,function (data) {
				if(data.status==0 || data.status==1){
                	alert(data.message);
                	if(data.status==0)location.reload();
				}else{
					$.each(data,function(i,item){
						if(i == 0){
							alert(item.msg);
							return;
						}
					});
				}
            })
        });


        /*---------------*/


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
        /*var timestamp = Date.parse(new Date($("#txtTimeBegin").val()));
        timestamp = timestamp / 1000;
        //alert(timestamp);
        var timestamp2 = Date.parse(new Date($("#txtTimeEnd").val()));
        timestamp2 = timestamp2 / 1000;*/
        data += "&txtTimeBegin=" + $("#txtTimeBegin").val();
        data += "&txtTimeEnd=" + $("#txtTimeEnd").val();
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
        var PostURL = "admin_pack.php";

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


	function updateStatus(id,status){
        var PostURL = "admin_pack.php";
        $.ajax({
            type: "POST",
            async:false,
            dataType: "json",
            url: PostURL,
            data: {action:"mod",id:id,status:status},
            success: function(data) {
            	//alert(data.message);
            	//SearchData();

				if(data.status==0){
					alert(data.message);
					SearchData();
				}else{
					$.each(data,function(i,item){
						if(i == 0){
							alert(item.msg);
							return;
						}
					});
				}
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
            	$.each(item,function(j,item2){
                tbody += "<tr bgcolor='#FFFFFF'>" +
                    "<td>" + item2.id + "</td>" +
                    "<td>" + item2.type + "</td>" +
                    "<td class='copy' data-clipboard-text="+item2.num+">" + item2.num + "</td>" +
                    "<td>" + item2.count + "</td>" +
                    "<td>" + item2.endcount + "</td>" +
                    "<td>" + item2.min + "</td>" +
                    "<td>" + item2.max + "</td>" +
                    "<td>" + item2.pack_desc + "</td>" +
                    "<td>" + item2.endtime + "</td><td>" ;
                    if (item2.status == 0) {
                        tbody += "<a href='javascript:updateStatus("+ item2.id +",1);'>已禁止</a>";
                    } else {
                        tbody += "<a href='javascript:updateStatus("+ item2.id +",0);'>已生效</a>";
                    }
            
            	tbody+=" <a href='admin_pack_list.php?id="+item2.id+"'>查看领取记录</a>";
            	tbody+= "</td></tr>";
            	});
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

<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );


$action=$_REQUEST['action'];
$userid=isset($_GET['userid'])?FilterStr($_GET['userid']):'';
$TimeBegin = isset($_GET['txtTimeBegin'])?FilterStr($_GET['txtTimeBegin']):"";

if($action=='check_charge'){
	login_check( "pay" );
	$OrderNo=isset($_REQUEST['OrderNo'])?FilterStr($_REQUEST['OrderNo']):'';
	$paynum=isset($_REQUEST['paynum'])?FilterStr($_REQUEST['paynum']):'';
	
	
	$OrderNoArr = explode("_", $OrderNo);
	$sql = "select * from pay_online where id={$OrderNoArr[1]} and order_id='{$OrderNoArr[0]}' and state=0";
	$result = $db->query($sql);
	$row=$db->fetch_array($result);
	if((int)$row['id'] > 0){
		$pay_web = (int)$row['cz_type'];
		
		
		if(in_array($pay_web , [5003,5004,5012])){
			include_once( dirname( __FILE__ )."/inc/payment2.php" );
			$payment = new payment();
			$payment->setAccount($pay_web);
		}
		
		if(in_array($pay_web , [5005,5006])){
			include_once( dirname( __FILE__ )."/inc/payment3.php" );
			$payment = new payment();
			$payment->setAccount($pay_web);
		}
		
		if(in_array($pay_web , [9999,9500,9501,9502])){
			include_once( dirname( __FILE__ )."/inc/payment4.php" );
			$payment = new payment();
			$payment->setAccount($pay_web);
		}
		
		if(in_array($pay_web , [7000,7001])){
			include_once( dirname( __FILE__ )."/inc/payment6.php" );
			$payment = new payment();
			$payment->setAccount($pay_web);
		}
		
		if(in_array($pay_web , [6040,6041])){
			include_once( dirname( __FILE__ )."/inc/payment1.php" );
			$payment = new payment();
			$payment->setAccount($pay_web);
		}
		
		if(in_array($pay_web , [8301,8302,8304])){
			include_once( dirname( __FILE__ )."/inc/payment5.php" );
			$payment = new payment();
			$payment->setAccount($pay_web);
		}
		
		$ret = $payment->checkChargeRequest($OrderNo);
		
		
		if($ret['rcode'] == "1" || $ret['pay_result'] == "20" || $ret['respCode'] == "S0001" || ($ret['returnCode'] === "0" && $ret['resultCode'] === "0" && $ret['status'] === "02") || ($ret['TXNSTATUS'] === "S" && $ret['RSPCODE'] === "000000")){
			$tagNo = empty($ret['pay_seq'])?"000000":$ret['pay_seq'];
			
			$sql = "call web_payonline_topay('{$OrderNoArr[0]}',$tagNo)";
			$arr = $db->Mysqli_Multi_Query($sql);
			$ret = $arr[0][0]["result"];
			switch($ret)
			{
				case '-1':
					$msg = "系统错误，请联系客服!";
					$status=-1;
					break;
				case '0':
					$msg = "ok";
					$status=0;
					break;
				case '1':
					$msg = "订单不存在!请联系客服!";
					$status=1;
					break;
				case '2':
					$msg = "ok";
					$status=2;
					break;
				default:
					$msg = "未知错误，请联系客服!";
					$status=1;
					break;
			}
			if($status == 0 || $status == 2)
				die(json_encode(array('rcode'=>1,'rmsg'=>'支付成功')));
			else 
				die(json_encode(array('rcode'=>-1,'rmsg'=>'支付失败')));
		}else{
			die(json_encode(array('rcode'=>-1,'rmsg'=>'检查订单失败')));
		}
	}else{
		die(json_encode(array('rcode'=>1,'rmsg'=>'订单不存在或已经支付成功')));
	}
	
	exit;
}


if($action=='get_list'){
    $TimeBegin = isset($_POST['txtTimeBegin'])?FilterStr($_POST['txtTimeBegin']):"";
    $TimeEnd = isset($_POST['txtTimeEnd'])?FilterStr($_POST['txtTimeEnd']):"";
    $username=isset($_POST['username'])?FilterStr($_POST['username']):'';
    $orderid=isset($_POST['orderid'])?FilterStr($_POST['orderid']):'';
    $userid=isset($_POST['userid'])?FilterStr($_POST['userid']):'';
    $status=isset($_POST['status'])?intval($_POST['status']):-1;
    $cz_type=(int)$_POST['cz_type'];
    $_SESSION['curr_cz_type'] = $cz_type;
    $sqlCount = "select Count(*) ";
    $sqlCol = "SELECT p.*,u.username,rt.name as cz_type,rt.remarks,rt.id as paytypeid,rt.autocharge ";
    $sqlFrom = "FROM pay_online p left join users u on u.id=p.uid left join recharge_type rt on rt.id=p.cz_type
                    WHERE 1=1 and state<10 ";
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
    $TimeField = "p.add_time";
    $sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
    $username=trim($username);
    $userid=trim($userid);
    $orderid=trim($orderid);
    if($username!=''){
        $sqlWhere.=' and u.username like \'%'.$username.'%\'';
    }
    if($userid!=''){
        $sqlWhere.=' and p.uid = \''.$userid.'\'';
    }
    if($orderid){
        $sqlWhere.=' and p.order_id =\''.$orderid.'\'';
    }
    if($status>-1){
        $sqlWhere.=' and p.state =\''.$status.'\'';
    }else{
        $sqlWhere.=' and p.state<3';
    }
    
    if($cz_type > 0) $sqlWhere.=' and p.cz_type = ' . $cz_type;
    if($cz_type < 0) $sqlWhere.=' and p.cz_type > 5002  ';
    
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
    
    $curPageAmount = 0.00;
    for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
    {
        //对返回数据进行包装
        $arrRows[$i]["add_time"] = $row["add_time"];
        $arrRows[$i]["order_id"] = $row["order_id"];
        $arrRows[$i]["uid"] = $row["uid"];
        $arrRows[$i]["rmb"] = number_format($row["rmb"]);
        $curPageAmount = $curPageAmount + $row["rmb"];
        $arrRows[$i]["point"] = number_format($row["point"]);
        $arrRows[$i]["point_befor"] = number_format($row["point_befor"]);
        $arrRows[$i]["pay_time"] = ($row["pay_time"]);
        $arrRows[$i]["ip"] = ($row["ip"]);
        $arrRows[$i]["username"] =' <a href="?userid='.$row['uid'].'&txtTimeBegin='.date('Y-m-d',strtotime('-1 year')).'">'.$row["username"].'</a>';
        $state='';
        $cz='';
        if($row['state']==0){
            $state='未支付';
            $cz='<a href="javascript:confirms('.$row['order_id'].');">确认</a>';
        }elseif($row['state']==1){
            $state='支付成功';
        }elseif($row['state']==2){
            $state='支付失败';
        }elseif($row['state']==3){
            $state='已撤销';
        }
        
        if($row['autocharge']){//自动上分
        	$cz = '';
        	//TODO 对账请求
        	if($row['paytypeid'] > 5002 && $row['state']==0){//
        		$cz = '<a href="javascript:chechCharge(\''.$row['order_id'].'\' , \''.$row['id'].'\' , \''.$row['order_target_id'].'\');">刷新</a>';
        	}
        }
        
        $arrRows[$i]["state"] = $state;
        /*$cz_type='';
        if($row['cz_type']=='1' or $row['cz_type']==4){
            $cz_type='支付宝';
        }elseif($row['cz_type']=='2'){
            $cz_type='微信手动';
        }elseif($row['cz_type']=='5'){
            $cz_type='微信直充';
        }elseif($row['cz_type']=='7'){
            $cz_type='借贷宝';
        }else{
            $cz_type='网银';
        }*/
        $marks=explode('#',$row['remarks']);
        $arrRows[$i]['result']=$cz;
        $arrRows[$i]["cz_type"] = $row['cz_type'].'_'.$marks[0];


        $arrRows[$i]["account"] = ($row["account"]);
        $arrRows[$i]["name"] = $row["name"];
        $arrRows[$i]["give_point"] = $row["give_point"];

    }
    //返回分页
    require_once('inc/fenye.php');
    $ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
    $pageInfo = $ajaxpage->show();
    $arrRows[0]["cmd"] = $act;
    $arrRows[0]["msg"] = $pageInfo . " 本页金额：￥".$curPageAmount; 
    die(json_encode($arrRows));
}
elseif($action=='luck'){
    $sql = "select count(*) from pay_online WHERE state=0 and notice=0";
    $count=$db->GetRecordCount($sql);
    if($count>0){
        die(json_encode(array('status'=>1,'count'=>$count)));
    }
    die(json_encode(array('status'=>0)));
}
elseif($action=='confirm'){
	login_check( "pay" );
    $order_id=trim(FilterStr($_GET['oid']));
    if(!$order_id)die(json_encode(array('status'=>1,'message'=>'错误')));
    $sql='select * from pay_online where order_id=\''.$order_id.'\'';
    $res=$db->fetch_first($sql);
    if(!$res['id']){
        die(json_encode(array('status'=>1,'message'=>'没有此订单错误')));
    }
    if($res['state']!=0){
        die(json_encode(array('status'=>1,'message'=>'此非未支付订单,已支付或已撤销')));
    }

    $payid=$order_id;
    $orderNo='000000';
    $sql = "call web_payonline_topay('{$payid}','{$orderNo}')";
    //WriteLog($sql);
    $arr = $db->Mysqli_Multi_Query($sql);
    $ret = $arr[0][0]["result"];
    switch($ret)
    {
        case '-1':
            $msg = "系统错误，请联系客服!";
            $status=-1;
            break;
        case '0':
            $msg = "ok";
            $status=0;
            break;
        case '1':
            $msg = "订单不存在!请联系客服!";
            $status=1;
            break;
        case '2':
            $msg = "ok";
            $status=2;
            break;
        default:
            $msg = "未知错误，请联系客服!";
            $status=1;
            break;
    }
    die(json_encode(array('status'=>$status,'message'=>$msg)));
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
    <div class="bodytitletxt">充值记录查询</div>
</div>
<div>
    <div>
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
            <tr bgcolor="#FFFFFF">
                <td colspan="8">
                    用户ID:
                    <input value="<?php echo $userid;?>" id="txtUserIdx" type="text" style="width:100px" />
                    用户名:
                    <input id="txtUserName" type="text" style="width:100px" />订单ID:
                    <input id="txtOrderId" type="text" style="width:100px" />
                    <input type="button" name="btnSearch" value="查询" id="btnSearch" class="btn-1" />
                    &nbsp;
                    状态
                    <select id = "sltStatus">
                        <option value="-1">所有</option>
                        <option value="0">未支付</option>
                        <option value="1">支付成功</option>
                        <option value="2">支付失败</option>
                        <option value="3">已取消</option>
                    </select>&nbsp;
                    
                   来源
                    <select id = "sltCzType">
                    <option value="">所有</option>
                    <?php 
                    $sql = "select id,name,acc_name from recharge_type 
							where id <=5002 
							order by id";
                    $result = $db->query($sql);
                    while($row=$db->fetch_array($result)){
						if($_SESSION['curr_cz_type'] == $row['id']){$selected = "selected";}else{$selected = "";}
                    	echo "<option value=\"{$row['id']}\" {$selected}>{$row['name']}({$row['acc_name']})</option>";
                    	
                    ?>
                    <?php
					}
					
					if($_SESSION['curr_cz_type'] == -1){$selected = "selected";}else{$selected = "";}
					echo "<option value=\"-1\" {$selected}>第三方支付</option>";
                    ?>
                    </select>&nbsp;
                    
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
        <table id='tblList' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="font-size:14px;BACKGROUND: #cad9ea;">
            <tr bgcolor="#f5fafe">
                <td >订单ID</td>
                <td >用户ID</td>
                <td >用户名称</td>
                <td >提交时间</td>
                <td >充值时间</td>
                <!-- <td  >IP</td> -->
                <td>充值前积分</td>
                <td  >充值积分</td>
                <!-- <td  >赠送积分</td> -->
                <td  >充值来源</td>
                <!-- <td  >账户</td> -->
                <td  >名称</td>
                <td >充值金额</td>
                
                <td  >状态</td>
                <td  >操作</td>
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
        data += "&cz_type=" + $("#sltCzType").val();
        
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
    {console.info("xxx")
        var PostURL = "admin_pay.php";

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
                    "<td>" + item.order_id + "</td>" +
                    "<td><a href='index.php?url=admin_singleuser.php%3Fidx%3D"+item.uid+"' target='_blank'> " + item.uid + "</a></td>" +
                    "<td>" + item.username + "</td>" +
                    "<td>" + item.add_time + "</td>" +
                    "<td>" + item.pay_time + "</td>" +
                    //"<td>" + item.ip + "</td>" +
                    "<td>" + item.point_befor + "</td>" +
                    "<td>" + item.point + "</td>" +
                    //"<td>" + item.give_point + "</td>" +
                    "<td>" + item.cz_type + "</td>" +
                    //"<td>" + item.account + "</td>" +
                    "<td>" + item.name + "</td>" +
                    "<td>" + item.rmb + "</td>" +
                    
                    "<td>" + item.state + "</td>" +
                    "<td>" + item.result + "</td>" +

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

	function chechCharge(order_id , id, paynum){
		var orderNo = order_id + '_' + id;
        $.getJSON('?action=check_charge&OrderNo='+orderNo+'&paynum='+paynum,function (data) {
            if(data.rcode=="1"){
            	alert(data.rmsg);
                location.reload();
            }else{
            	//alert(data.rmsg);
                if(confirm(data.rmsg+",确定已经收到汇款了吗？")){
	                $.getJSON('?action=confirm&oid='+order_id,function (ret) {
	                    console.info(ret);
	                    if(ret.status==0){
	                        alert("充值成功");
	                        location.reload();
	                    }else{
	                        alert(ret.message);
	                    }
	                });
                }
            }
        })
	}
    
    function confirms(order_id) {
        if(confirm("确定已经收到汇款了吗？"))

        {
        $.getJSON('?action=confirm&oid='+order_id,function (data) {
            console.info(data);
            if(data.status==0){
                alert("充值成功");
                location.reload();
            }else{
                alert(data.message);
            }
        })
        }
    }
</script>

</html>


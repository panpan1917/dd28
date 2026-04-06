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
    $sqlCol = "SELECT p.*,u.username,u.recv_cash_name,w.name,w.account,w.address,w.type,u.lock_points,u.points as upoints ";
    $sqlFrom = "FROM pay_online p left join users u on u.id=p.uid
    left join withdrawals w on w.uid=p.uid and w.id=p.cz_type
                    WHERE 1=1  ";
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
    $TimeField = "p.pay_time";
    $sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
    $username=trim($username);
    $userid=trim($userid);
    if($username!=''){
        $sqlWhere.=' and u.username like \'%'.$username.'%\'';
    }
    if($userid!=''){
        $sqlWhere.=' and p.uid = \''.$userid.'\'';
    }

    if($status==-1) {
        $sqlWhere .= ' and p.state>=30 and p.state<=32';
    }else{
        $sqlWhere.=' and p.state =\''.$status.'\'';
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
        $sql='select points from game_static where typeid=101 and uid='.$row['uid'];
        $cz=$db->fetch_first($sql);
        $sql='select points from game_static where typeid=140 and uid='.$row['uid'];
        $tx=$db->fetch_first($sql);
 
        if(abs($cz['points'])>=(abs($tx['points'])+$row['point'])){
            $arrRows[$i]['color']='block';
        }else{
            $arrRows[$i]['color']='red';
        }
        //对返回数据进行包装
        $arrRows[$i]["upoints"] = number_format($row["upoints"]);
        $arrRows[$i]["add_time"] = date('m-d H:i:s',strtotime($row["add_time"]));
        $arrRows[$i]["order_id"] = $row["order_id"];
        $arrRows[$i]["uid"] = $row["uid"];
        $arrRows[$i]["rmb"] = abs($row["rmb"]);//abs(intval($row["rmb"]));
        $arrRows[$i]["point"] = number_format($row["point"]);
        $arrRows[$i]["lock_points"] = number_format($row["lock_points"]);
        $arrRows[$i]["point_befor"] = number_format($row["point_befor"]);
        $arrRows[$i]["point_after"] = number_format($row["point_after"]);
        $arrRows[$i]["pay_time"] = date('m-d H:i:s',strtotime($row["pay_time"]));
        $arrRows[$i]["ip"] = ($row["ip"]);
        $arrRows[$i]["username"] = ' <a href="?userid='.$row['uid'].'&txtTimeBegin='.date('Y-m-d',strtotime('-1 year')).'">'.$row["username"].'</a>';
        $state='';
        $cz='';
        if($row['state']==30){
            $state='提现申请';
            $cz='<a href="?action=pay&id='.$row['id'].'">去付款</a>&nbsp;&nbsp;';
            $cz.='<a href="javascript:confirms('.$row['id'].');">拒绝</a>';
        }elseif($row['state']==31){
            $state='未通过';
        }elseif($row['state']==32){
            $state='已支付';
        }
        $arrRows[$i]["state"] = $state;
        $arrRows[$i]["stateflag"] = $row['state'];
        
        $cz_type='';
        if($row['type']=='1' ){
            $cz_type='支付宝';
        }elseif($row['type']=='2'){
            $cz_type='微信';
        }elseif($row['type']=='7'){
            $cz_type='借贷宝';
        }else{
            $cz_type='网银';
        }
        $arrRows[$i]['recv_cash_name']=$row['recv_cash_name'];
        $arrRows[$i]['result']=$cz;
        $arrRows[$i]["cz_type"] = $cz_type;
        $arrRows[$i]["account"] = str_replace('/','',$row["account"]);
        $arrRows[$i]["name"] = $row["name"];
        $arrRows[$i]["address"] = $row["address"];
        $arrRows[$i]["source"] = $row["source"];

    }
    //返回分页
    require_once('inc/fenye.php');
    $ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
    $pageInfo = $ajaxpage->show();
    $arrRows[0]["cmd"] = $act;
    $arrRows[0]["msg"] = $pageInfo;
    die(json_encode($arrRows));
}
elseif($action=='luck'){
    $sql = "select count(*) from pay_online WHERE state=30 and notice=0";
    $count=$db->GetRecordCount($sql);
    $sql = "select count(*) from pay_online WHERE state=0 and notice=0 and date_sub(now(),interval 2 minute) <add_time and cz_type in(select id from recharge_type where autocharge=0)";
    $cz_count=$db->GetRecordCount($sql);

    if($count>0 || $cz_count>0){
        die(json_encode(array('status'=>1,'count'=>$count,'cz_count'=>$cz_count)));
    }
    die(json_encode(array('status'=>0,'count'=>0,'cz_count'=>$cz_count)));
}
elseif($action=='confirm'){
	login_check( "pay" );
    $id=trim(FilterStr($_GET['id']));
    if(!$id)die(json_encode(array('status'=>1,'message'=>'错误')));

    $sql='select * from pay_online where id='.$id;
    $res=$db->fetch_first($sql);
    if($res['state']==31){
        return result(1,'已经拒绝了的' );
    }
    if($res['state']==32){
        return result(1,'已付款了的' );
    }
    $point=abs($res['point']);
    
    $sql='select dj from users where id='.$res['uid'];
    $ures=$db->fetch_first($sql);
    if($ures['dj']==1){
    	return result(1,'账号已冻结' );
    }
    
    //$db->query('set autocommit=0');
    //$db->query('begin');
    $sql='update users set `back`=`back` +'.$point.' ,lock_points=lock_points-'.$point.' where id='.$res['uid'];
    if(!$db->query($sql)){
        //$db->query('rollback');
        return result(1, '失败了,请联系管理员');
    }
    /*$sql="select row_count() as total";
    $num=$db->fetch_first($sql);
    if($num['total']<1){
        return result(1,'没有足够的分可供扣除!');
    }*/
    $sql='update pay_online set state=31 ,pay_time=now() where id='.$id.' and uid='.$res['uid'];
    if(!$db->query($sql)){
       // $db->query('rollback');
        return result(1, '失败了,请联系管理员');
    }
    //$sql="UPDATE centerbank SET score = score $res[point] WHERE bankIdx = 4";
    //$db->query($sql);
    //$db->query('commit');
   // $db->query('set autocommit=1');

    withdrawals_log(12,$res['point'],0,'退回申请提现的积分!',$res['uid']);
    return result(0, '已拒绝了!');
}
elseif($action=='sendMsg'){
	login_check( "pay" );
	$uid=(int)$_GET['uid'];
	if($uid){
		$sql = "update users set alertmsg='帐号有误，请联系客服！' where id = '{$uid}'";
		$result = $db->query($sql);
		return result(0,'已经发送');
	}
}
elseif($action=='pay'){
	login_check( "pay" );
    $id=trim(FilterStr($_GET['id']));
    if(!$id){$result="未知的ID";
    }else{
        $sql='select * from pay_online where id='.$id;
        $pay_result=$db->fetch_first($sql);
        if($pay_result['state']!=30) {
            $result = '非是提现申请的单号';

        }
    }
}elseif($action=='to_pay'){
	login_check( "pay" );
    $id=intval($_GET['id']);
    $t=intval($_GET['t'])?:1;
    if(!$id)die(json_encode(array('status'=>1,'message'=>'错误')));
    $sql='select uid,point,state,fee from pay_online where id='.$id;
    $res=$db->fetch_first($sql);
    if($res['state']==31){
        return result(1,'已经拒绝了的' );
    }
    if($res['state']==32){
        return result(1,'已付款了的' );
    }
    $sql='update users set lock_points=lock_points-'.abs($res['point']).' where id='.$res['uid'];
    if(!$db->query($sql)){
        //$db->query('rollback');
        return result(1, '失败了,请联系管理员');
    }
    /*$sql="select row_count() as total";
    $num=$db->fetch_first($sql);
    if($num['total']<1){
        return result(1,'没有足够的分可供扣除!');
    }*/
    $sql='update pay_online set  state=32,pay_time=now() where state=30 and id='.$id.' and uid='.$res['uid'];

    if(!$db->query($sql)){
        return result(1, '失败了,请联系管理员');
    }
    $sql="select row_count() as total";
    $num=$db->fetch_first($sql);
    if($num['total']<1){
        return result(1,'更新订单状态失败!');
    }
    $db->query('set autocommit=0');
    $db->query('begin');
    $sql="UPDATE centerbank SET score = score+ ".abs($res[point])." WHERE bankIdx = 4";
    $db->query($sql);
    $sql="INSERT game_static (uid,typeid,points) values ($res[uid],140, $res[point]) ON DUPLICATE KEY UPDATE points=points+'$res[point]'";
    $db->query($sql);
    //$sql="INSERT INTO webtj(`time`,exchangepoints) VALUES(now(),".($res[point]*-1).") on duplicate key update exchangepoints=exchangepoints+'".($res[point]*-1)."';";
    $sql="INSERT INTO webtj(`time`,exchangepoints,cashfee) VALUES(now(),".($res[point]*-1).",'{$res['fee']}') on duplicate key update cashfee=cashfee+'{$res['fee']}',exchangepoints=exchangepoints+'".($res[point]*-1)."';";
    $db->query($sql);
    $db->query('commit');
    $db->query('set autocommit=1');
    withdrawals_log(10,$res['point'],0,'已付款!');
    $sql='select points,back from users where id='.$res['uid'];
    $us=$db->fetch_first($sql);
    admin_logs(10,$res['point'],$us['points'],$us['back'],'提现',$res['uid']);
    return result(0,'');
}
function admin_logs($opr,$amount,$points,$bank,$remark,$uid=0){
    /*
uidbigint(20) NOT NULL用户id
opr_typeint(11) NOT NULL类型，0：存，1：取，2：充值体验卡，3：转账入，4：转账出,5:在线充值,6:领取救济,7:兑奖点卡,8:推荐收益,55:系统会员充值,12:退回提现,10:提现通过,11:提现申请
amountbigint(20) NOT NULL数量
log_timedatetime NOT NULL时间
ipvarchar(15) NOT NULLip
pointsbigint(20) NOT NULL操作后豆
bankpointsbigint(20) NOT NULL操作后银行豆
remarkvarchar(254) NOT NULL备注
     */
    global $db;
    $uid=$uid?:$_SESSION['users'];
    $ip=usersip();
    $sql="INSERT INTO score_log(uid,opr_type,amount,log_time,ip,points,bankpoints,remark) values ('$uid','$opr','$amount',now(),'$ip','$points','$bank','$remark')";
    $db->query($sql);
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
    <script type="text/javascript" src="js/clipboard.min.js"></script>
    <style type="text/css">
        .green{color:#0FB50E;}
        .red{color:#f00;}
    </style>
</head>

<body>
<div class="bodytitle">
    <div class="bodytitleleft"></div>
    <div class="bodytitletxt">提现记录查询</div>
</div>
<?php if($action=='pay'){
?>
<div>
    <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >
        <?php
        $sql="select * from withdrawals where id = {$pay_result['cz_type']} and uid={$pay_result['uid']}";
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
        <table id='tblList' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;font-size:14px;">
            <tr bgcolor="#f5fafe">

                <td>用户ID</td>
                <td>用户名称</td>
                <td>申请时间</td>
                <td>审核时间</td>
                
                <td>账户积分</td>
                <td>目前冻结积分</td>
                <td>提现积分</td>
                <td>提现前积分</td>
                <td>提现后积分</td>
                <td>付款方式</td>
                <td>发消息</td>
                <td colspan="2">账户</td>
                <td>收款名称</td>
                <td>提现金额</td>
                <!-- <td>IP</td> -->
                <td>状态</td>
                <td>来源</td>
                <td>操作</td>
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
    {console.info("xxx")
        var PostURL = "admin_withdrawals.php";

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

    var clipboard = new Clipboard('.btncp');
    
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

                tbody += "<tr bgcolor='#FFFFFF' class='"+item.color+"'>" +

                    "<td><a href='index.php?url=admin_singleuser.php%3Fidx%3D"+item.uid+"' target='_blank'> " + item.uid + "</a></td>" +
                    "<td>" + item.username + "</td>" +
                    "<td>" + item.add_time + "</td>" +
                    "<td>" + item.pay_time + "</td>" +
                    
                    "<td>" + item.upoints + "</td>" +
                    "<td>" + item.lock_points + "</td>" +
                    "<td>" + item.point + "</td>" +

                    "<td>" + item.point_befor + "</td>" +
                    "<td>" + item.point_after + "</td>" +

                    "<td align='center'><a href='#' onclick='sendMsg(" + item.uid + ");'>发消息</a></td>" +
                    
                    "<td>" + item.cz_type + "</td>";

                 if(item.stateflag == 30){   
                	 tbody += "<td><span id='account_"+i+"'>" + item.account + "</span> <input type='button' class=\"btncp\" data-clipboard-action='copy' data-clipboard-target='#account_"+i+"' value='复制'></td>" +
                    "<td>" + item.name + "(" + item.address + ")</td>" +
                    "<td><span id='cashname_"+i+"'>" + item.recv_cash_name + "</span><input type='button' class=\"btncp\" data-clipboard-action='copy' data-clipboard-target='#cashname_"+i+"' value='复制'></td>" +
                    "<td><span id='rmb_"+i+"'>" + item.rmb + "</span><input type='button' class=\"btncp\" data-clipboard-action='copy' data-clipboard-target='#rmb_"+i+"' value='复制'></td>";
                 }else{
                	 tbody += "<td>" + item.account + "</td>" +
                     "<td>" + item.name + "(" + item.address + ")</td>" +
                     "<td>" + item.recv_cash_name + "</td>" +
                     "<td>" + item.rmb + "</td>";
                 }
                 //tbody += "<td>" + item.ip + "</td>" +
                 tbody += "<td>" + item.state + "</td>" +
                    "<td>" ;
                if(item.source==0){
                    tbody+='pc';
                }else{
                    tbody+='m';
                }
                tbody+= "</td>" +
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


    function sendMsg(userid){
        if(confirm("您确定要拒绝吗？")){
	        $.getJSON('?action=sendMsg&uid='+userid,function (data) {
	            
	        });
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

<?php
include_once("inc/conn.php");
include_once("inc/function.php");
$action="";
if(isset($_GET["action"])){
    $action=$_GET["action"];
}
if($action=="checklogin"){//检测是否登录
    if(!isset($_SESSION['usersid'])) {
        $arrRet['cmd'] = "notlogin";
        echo  $arrRet['cmd'];
    }else{
        echo  "ok";
    }
    exit();
}
switch ($action){
    case 'add_recharge_order':
        $payer=str_check($_REQUEST['payer']);
        $pay_web=intval($_REQUEST['pay_web']);
        $pay_account=str_check($_REQUEST['pay_account']);
        $money=sprintf('%.2f',$_REQUEST['money']);
        
        if($pay_web==0){
            die(msg('err','充值类型错误！'));
        }
        if(intval($_SESSION['usersid'])<1){
            die(msg('err','请刷新网站！'));
        }
        if($pay_account=='')die(msg('err','付款帐号不能为空！'));
        if($payer=='')die(msg('err','付款人姓名不能为空！'));
        
        $sql = "select * from recharge_type where id = {$pay_web}";
        $res=$db->query($sql);
        $row=$db->fetch_array($res);
        $maxamount = $row['maxamount'];
        $minamount = $row['minamount'];
        $fee_rate = $row['fee_rate'];//手续费率
        if((int)$money > $maxamount){
        	die(msg("err","充值金额过大！"));
        }
        if((int)$money < $minamount){
        	die(msg("err","充值金额{$minamount}起！"));
        }
        
        $cardNo = "";
        $mobileNo = "";
        $cashName = "";
        if(in_array($pay_web , [9999,8301,8302,8304])){//快捷支付，检查身份证号码和手机号，姓名
	        $sql = "select * from users where id={$_SESSION[usersid]}";
	        $res=$db->query($sql);
	        $row=$db->fetch_array($res);
	        $cardNo = $row['card'];
	        $mobileNo = $row['mobile'];
	        $cashName = $row['recv_cash_name'];
	        
	        if(in_array($pay_web , [9999])){
		        if(empty($cardNo)){
		        	die(msg("err","请补充身份证号码信息！"));
		        }
		        if(empty($mobileNo)){
		        	die(msg("err","请补充手机号码信息！"));
		        }
	        }
	        
	        if(in_array($pay_web , [8301,8302,8304])){
	        	if(empty($cashName)){
	        		die(msg("err","请补充真实姓名信息！"));
	        	}
	        }
        }
        
        if(in_array($pay_web , [6040,6041])){
        	if(!in_array($money,[50,100,150,200,300,500,600,800,1000,1500,2000,3000,5000])){
        		die(msg("err","只能充这些金额[50,100,150,200,300,500,600,800,1000,1500,2000,3000,5000]！"));
        	}
        }
        
        $point = $money * 1000;
        $point = $point * (1 - $fee_rate);
        $point = (int)$point;
        
        //if(in_array($pay_web , [5003,5004])) $money = $money - rand(2,6) * 0.01;
        //$money = 100.01; //TODO debug
        
        $sql = "select count(*) as num from pay_online where uid={$_SESSION[usersid]} and state=0 and add_time > DATE_ADD(NOW(),INTERVAL -300 SECOND)";
        $res=$db->query($sql);
        $row=$db->fetch_array($res);
        if($row['num'] >= 1){
        	die(msg("err","提交订单太频繁,请先撤销未充值订单！"));
        }
        
        $order_id=time().rand(1000,9999);
        $ip = usersip();
        $sql='select CzScoreLimit,CzZsBl from czzsbl order by CzScoreLimit desc';
        $res=$db->query($sql);
        $give_point=0;
        while ($row=$db->fetch_array($res)){
            if($money>=$row['CzScoreLimit']){
                $give_point=$row['CzZsBl'];
                break;
            }
        }
        RefreshPoints();
        $point_befor=$_SESSION['points'];
        
        
        $sql="insert into pay_online (`order_id`, `uid`,  `rmb` ,  `point`,`point_befor` , `order_target_id` ,  `add_time`,  `pay_time` ,  `ip` ,  `error_msg`,  `state`,  `cz_type`,`account`,`name`,`give_point`) values ('$order_id','$_SESSION[usersid]','$money','$point','$point_befor','',now(),'','$ip','','0','$pay_web','$pay_account','$payer','$give_point')";
        $db->query($sql);
        $lastid=$db->insert_id();
        
        
        //调用百顺支付接口
        if(in_array($pay_web , [5003,5004,5012])){
        	require_once '../core/payment/payment2.php';
        	$payment = new payment();
        	$payment->setAccount($pay_web);
        	
        	if(in_array($pay_web , [5003,5004]))
        		$payResult = $payment->payRequest($order_id . "_" . $lastid , $money);
        	else 
        		$payResult = $payment->quickPayRequest($order_id . "_" . $lastid , $money);
        		
        	if($payResult['status_code'] == 0){
        		$sql="update pay_online set qrcode = '{$payResult['pay_url']}',order_target_id='{$payResult['pay_seq']}' where id={$lastid}";
        		$db->query($sql);
        	}else{
        		$sql="delete from pay_online where id={$lastid}";
        		$db->query($sql);
        		die(json_encode(array('cmd'=>'err','orderid'=>$order_id , 'msg'=>$payResult['status_msg'])));
        		break;
        	}
        }
        
        
        //调用真享gmall支付接口
        /* if(in_array($pay_web , [5005,5006])){
        	require_once '../core/payment/payment3.php';
        	$payment = new payment();
        	$payment->setAccount($pay_web);
        
        	$payResult = $payment->payRequest($order_id . "_" . $lastid , $money);
        	if($payResult['respCode'] == "S0001"){
        		$sql="update pay_online set qrcode = '{$payResult['codeUrl']}' where id={$lastid}";
        		$db->query($sql);
        	}else{
        		$sql="delete from pay_online where id={$lastid}";
        		$db->query($sql);
        		die(json_encode(array('cmd'=>'err','orderid'=>$order_id , 'msg'=>$payResult['respMessage'])));
        		break;
        	}
        } */
        
        
        //万荣支付
        if(in_array($pay_web , [6040,6041])){
        	require_once '../core/payment/payment1.php';
        	$payment = new payment();
        	$payment->setAccount($pay_web);
        		
        	$payResult = $payment->payRequest($order_id . "_" . $lastid , $money , $_SESSION[usersid]);

        	if($payResult['code'] === 0 && ($payResult['success'] === "true" || $payResult['success'] === true)){
        		$sql="update pay_online set qrcode = '{$payResult['data']['pay_url']}' where id={$lastid}";
        		$db->query($sql);
        	}else{
        		$sql="delete from pay_online where id={$lastid}";
        		$db->query($sql);
        		die(json_encode(array('cmd'=>'err','orderid'=>$order_id , 'msg'=>$payResult['message'])));
        		break;
        	}
        }
        
        //百捷
        if(in_array($pay_web , [7000,7001])){
        	require_once '../core/payment/payment6.php';
        	$payment = new payment();
        	$payment->setAccount($pay_web);
        
        	$payResult = $payment->payRequestQrCode($order_id . "_" . $lastid , $money , $ip);
        
        	if($payResult['TXNSTATUS'] === "S" || $payResult['RSPCODE'] === "000000"){
        		$sql="update pay_online set qrcode = '{$payResult['RSPDATA']}' where id={$lastid}";
        		$db->query($sql);
        	}else{
        		$sql="delete from pay_online where id={$lastid}";
        		$db->query($sql);
        		die(json_encode(array('cmd'=>'err','orderid'=>$order_id , 'msg'=>$payResult['RSPMSG'])));
        		break;
        	}
        }
        
        
        //调用捷通支付接口
        if(in_array($pay_web , [9999,9500,9501,9502])){
        	require_once '../core/payment/payment4.php';
        	$payment = new payment();
        	$payment->setAccount($pay_web);
        	
        	if(in_array($pay_web , [9500,9501,9502])){
        		$payResult = $payment->payRequestQrCode($order_id . "_" . $lastid , $money);
        	}elseif(in_array($pay_web , [9999])){
        		$payCardInfo = array('bankCardNo'=>$pay_account,'customerName'=>$payer,'phoneNo'=>$mobileNo,'cerType'=>'01','cerNo'=>$cardNo);
        		$payCardInfo = json_encode($payCardInfo);
        		$payResult = $payment->quickPayRequest($order_id . "_" . $lastid , $money , $payCardInfo);
        	}
        	
        	if($payResult['returnCode'] === "0" && $payResult['resultCode'] === "0"){
        		$payResult['payCode'] = str_replace('method="post"','method="post" target="_blank"',$payResult['payCode']);
        		$sql="update pay_online set qrcode = '{$payResult['payCode']}' where id={$lastid}";
        		$db->query($sql);
        	}else{
        		$sql="delete from pay_online where id={$lastid}";
        		$db->query($sql);
        		die(json_encode(array('cmd'=>'err','orderid'=>$order_id , 'msg'=>$payResult['errCodeDes'])));
        		break;
        	}
        }
        
        
        /* if(in_array($pay_web , [8301,8302,8304])){
        	require_once '../core/payment/payment5.php';
        	$payment = new payment();
        	$payment->setAccount($pay_web);
        
        	$payResult = $payment->quickPayRequest($order_id . "_" . $lastid , $money , $_SESSION[usersid] , $cashName);
        	if($payResult['rtnCode'] === "20000"){//rtnCode 20000
        		$sql="update pay_online set qrcode = '{$payResult['qrCodeURL']}' where id={$lastid}";
        		$db->query($sql);
        	}else{
        		$sql="delete from pay_online where id={$lastid}";
        		$db->query($sql);
        		die(json_encode(array('cmd'=>'err','orderid'=>$order_id , 'msg'=>$payResult['returnMsg'])));
        		break;
        	}
        } */
        
        
        
        die(json_encode(array('cmd'=>'ok','orderid'=>$order_id)));
        break;
    case 'cancel_recharge_order':
        $id=str_check($_REQUEST['id']);
        if(trim($id)){
            $sql='update  pay_online set state=3 where uid='.$_SESSION['usersid'].' and order_id=\''.$id.'\'  and state=0';
            $db->query($sql);
        }
        die(json_encode(array('cmd'=>'ok','msg'=>'')));
        exit();
        break;
    case 'get_recharge_order_log':
        $day=intval($_REQUEST['day'])>7?intval($_REQUEST['day']):7;
        $page=intval($_REQUEST['page'])?intval($_REQUEST['page']):1;
        //$sql='select * from pay_online where uid='.$_SESSION['usersid'];
        $sql='select a.*,b.name as pay_type from pay_online a left join recharge_type b on a.cz_type=b.id where a.uid='.$_SESSION['usersid'].' and a.state <3 ';
        if($day){
            $sql.=' and  TO_DAYS(NOW())-TO_DAYS(a.add_time)<'.$day;
        }
        $sql.=' order by a.id desc limit 30';
        $res=$db->query($sql);
        $str='';
        while ($row=$db->fetch_array($res)) {
            $str.= '<tr>
					<td>'.$row['order_id'].'</td>
					<td>'.$row['rmb'].'</td> 
					<td>';
            if($row['state']==0){
                $str.= '未支付';
            }elseif($row['state']==1){
                $str.= '支付成功';
            }elseif($row['state']==2){
                $str.= '支付失败';
            }elseif($row['state']==3){
                $str.= '已撤销';
            }
            $str.='</td>
					<td  >'.$row['add_time'].'</td>
					<td  >';
            /* if($row['cz_type']==1){
                $str.='支付宝';
            }elseif($row['cz_type']==2){
                $str.= '微信';
            }elseif($row['cz_type']==3){
                $str.= '网银';
            } */
            $str.= $row['pay_type'];
            $str.='</td>
					<td  >'.$row['account'].'</td>
					<td  >'.$row['name'].'</td>
					<td  >';
            if($row['state']==0) {
                $str .= '<a style="cursor:pointer" onclick="Cancel_recharge_order(\'' . $row['order_id'] . '\')">撤消</a>';
            }
            $str.='</td></tr>';
        }
        echo $str;
        break;
    case 'add_account':
        add_account();
        break;
    case 'getlastaccount':
        	$uid = (int)$_SESSION['usersid'];
        	$sql = "select name,account from pay_online where uid={$uid} and state=1 order by id desc limit 1";
        	$res=$db->query($sql);
        	$row=$db->fetch_array($res);
        	if(!empty($row)){
        		die(json_encode(array('cmd'=>'ok','msg'=>'',data=>array('name'=>$row['name'],'account'=>$row['account']))));
        		exit();
        	}else{
        		die(json_encode(array('cmd'=>'error','msg'=>'刷新失败')));
        		exit();
        	}
        	break;
    case 'getrecvname':
        	$uid = (int)$_SESSION['usersid'];
        	$sql = "select recv_cash_name from users where id={$uid} limit 1";
        	$res=$db->query($sql);
        	$row=$db->fetch_array($res);
        	if(!empty($row)){
        		die(json_encode(array('cmd'=>'ok','msg'=>'',data=>array('recvname'=>$row['recv_cash_name']))));
        		exit();
        	}else{
        		die(json_encode(array('cmd'=>'error','msg'=>'刷新失败')));
        		exit();
        	}
        	break;
}
function add_account(){
    global $db;
    $type=str_check($_REQUEST['type']);
    $id=intval($_POST['id'])?:0;
    if($type=='alipay' || $type=='weichat'){
        $acc=str_check($_REQUEST['acc']);
        if($acc==''){
            echo result(1,'账户不能为空');
            return;
        }
        if($type=='alipay'){
            $s_type=1;
        }elseif($type=='weichat'){
            $s_type=7;
        }
        $sql="select recv_cash_name from users where id=".$_SESSION['usersid'];
        $rename=$db->result_first($sql);
        if($rename==''){
            return result(1,'请先去绑定收款人姓名' );
        }
        $sql='select id from withdrawals where uid='.$_SESSION['usersid'].' and type='.$s_type;
        $res=$db->result_first($sql);
        if($id>0){
            if($res == $id){
                $sql="update withdrawals set account='$acc',add_time=now() where id=".$id;
                $db->query($sql);
                return result(0,'修改成功');
            }
        }else{

            if($res)return result(1,'您已经添加过了' );
            $sql="insert into withdrawals (uid,type,account,add_time) values(".$_SESSION['usersid'].",'$s_type','$acc',now())";
            $db->query($sql);
            return result(0,'添加成功' );
        }
    }elseif ($type=='bank'){
        $acc=str_check($_REQUEST['acc']);
        if($acc==''){
            echo result(1,'账户不能为空');
            return;
        }
        $bank_type=str_check($_REQUEST['bank_type']);
        if($type==''){
            echo result(1,'请选择银行');
            return;
        }
        $province=str_check($_REQUEST['province']);
        if($province==''){
            return result(1,'开户行所在省份不能为空' );
        }
        $city=str_check($_REQUEST['city']);
        if($city==''){
            return result(1,'开户行所在市不能为空' );
        }
        $bank_name=str_check($_REQUEST['bank_name']);
        if($bank_name==''){
            return result(1,'开户行不能为空' );
        }
        $sql="select recv_cash_name from users where id=".$_SESSION['usersid'];
        $rename=$db->result_first($sql);
        if($rename==''){
            return result(1,'请先去绑定收款人姓名' );
        }
        $sql='select id from withdrawals where uid='.$_SESSION['usersid'].' and type=3';
        $res=$db->result_first($sql);
        if($id>0){
            if($res == $id){
                $sql="update withdrawals set name='$bank_type',account='$acc',address='".$province .'|'. $city.'|' . $bank_name."',add_time=now() where id=$id;";
                $db->query($sql);
                return result(0,'修改成功');
            }
        }else {
            if ($res) {
                return result(1, '您已经添加过了');

            }
            $sql = "insert into withdrawals (uid,type,account,name,address,add_time) values(" . $_SESSION['usersid'] . ",3,'$acc','$bank_type','" . $province .'|'. $city.'|' . $bank_name . "',now())";
            $db->query($sql);
            return result(0, '添加成功');
        }
    }
}
function msg($cmd,$msg){
    return json_encode(array('cmd'=>$cmd,'msg'=>$msg));
}




if($action=="agent_check_users"){//检查帐号
	$user_id = str_check($_POST['userid']);
	$arrRet=array();
	$arrRet['cmd'] ="err";
	if(! isset($_SESSION['isagent'])){
		$arrRet['cmd'] = "timeout";
		echo json_encode($arrRet);
		exit();
	}
	if(empty($user_id)){
		$arrRet['cmd'] = "user_empty";
		echo json_encode($arrRet);
		exit();
	}

	$sql = "select id,username,recv_cash_name from users where id = '{$user_id}' or username = '{$user_id}' limit 1";
	$result = $db->query($sql);
	$rs = $db->fetch_array($result);
	if(empty($rs)){
		$arrRet['cmd'] = "empty";
	}else{
		$arrRet['cmd'] = "OK";
		$rs["username"]=ChangeEncodeG2U($rs["username"]);
		$rs["recv_cash_name"]=ChangeEncodeG2U($rs["recv_cash_name"]);
		$arrRet['data'] =$rs;
	}
	echo json_encode($arrRet);
	exit();
}

if($action=="check_tjid_exist"){//判断推荐人ID是否存在
	$tjid = str_check($_GET["tjid"]);
	$sql = "select id from users where id = '{$tjid}' limit 1";
	$result = $db->query($sql);
	$id=0;
	if($rs = $db->fetch_array($result))
	{
		$id = $rs['id'];
	}
	if(empty($id)){
		echo "empty";
	}else{
		echo "OK";
	}
	exit();
}

if($action=="get_pwd_step1"){//检证用户名是否存在
	$arrRet = array('cmd'=>'');
	$username = str_check($_POST["username"]);
	$vcode = $_POST['vcode'];
	if($vcode != $_SESSION["CheckNum"]) //验证码错误
	{
		$arrRet['cmd'] = "vcode";
		echo $arrRet['cmd'];
		exit;
	}
	if(strlen($username) < 1)
	{
		echo 'fault';
		exit;
	}
	$sql = "select id from users where username = '{$username}' limit 1";
	$result = $db->query($sql);
	$id=0;
	if($rs = $db->fetch_array($result))
	{
		$id = $rs['id'];
		$_SESSION["get_pwd_ac"] = $username;
	}
	if(empty($id)){
		$arrRet['cmd'] = "empty";
		echo $arrRet['cmd'];
	}else{
		$arrRet['cmd'] = "OK";
		echo $arrRet['cmd'];
	}
	exit();
}

if($action=="get_check_code_sms"){//发送短信验证码
	$arrRet = array('cmd'=>'');
	if(! isset($_SESSION["get_pwd_ac"]) ){
		$arrRet['cmd'] = "timeout";
		echo json_encode($arrRet);
		exit;
	}
	$username = $_SESSION["get_pwd_ac"];
	$sql = "select id,mobile from users where username = '{$username}' limit 1";
	$result = $db->query($sql);
	$rs = $db->fetch_row($result);
	$mobile=$user_id=0;
	if(!empty($rs)){
		$user_id= $rs[0];
		$mobile=$rs[1];
	}
	if($user_id==0){
		$arrRet['cmd'] = "timeout";
		echo json_encode($arrRet);
		exit;
	}
	$sql = "select count(*) from validcodelog where code_type = 0 and state = 0 and account = '{$mobile}' and to_days(add_time) = to_days(now())";
	$TodayMsgCount = $db->GetRecordCount($sql);
	if($TodayMsgCount >= 5)
	{
		$arrRet['cmd'] = "err";
		$arrRet['msg'] = "您今天发送的验证码次数已用完，请明天再试,有疑问请联系客服!";
		echo json_encode($arrRet);
		exit;
	}
	
	include_once("class/Sms.php");
	$Sms = new Sms();
	
	if ($validcode = $Sms->send($mobile)) 
	{
		$arrRet['cmd'] = "ok";
		$arrRet['msg'] = "发送成功";
		$_SESSION['get_pwd_check_code_sms'] = $validcode;
	}
	else
	{
		$arrRet['cmd'] = "err";
		$arrRet['msg'] = "短信未能发出，请稍后再试!";
	}
	echo json_encode($arrRet);
	exit();
}

if($action=="agent_recharge"){//检查帐号
	$user_id = str_check($_POST['userid']);
	$money= intval($_POST['money']);
	$arrRet=array();
	$arrRet['cmd'] ="err";
	if(!isset($_SESSION['usersid'])) {
		$arrRet['cmd'] = "notlogin";
		echo json_encode($arrRet);
		exit();
	}
	if(! isset($_SESSION['Agent_Id'])){
		$arrRet['cmd'] = "timeout";
		echo json_encode($arrRet);
		exit();
	}
	if(empty($user_id)){
		$arrRet['cmd'] = "user_empty";
		echo json_encode($arrRet);
		exit();
	}
	if($user_id==$_SESSION['usersid']){
		$arrRet['cmd'] = "self";
		echo json_encode($arrRet);
		exit();
	}

	if(empty($money)){
		$arrRet['cmd'] = "not_money";
		echo json_encode($arrRet);
		exit();
	}
	if($money<1){
		$arrRet['cmd'] = "err_money";
		echo json_encode($arrRet);
		exit();
	}
	
	
	//if(ereg("^[0-9]*[1-9][0-9]*$",$money)!=1)//当不为整数时
	if(preg_match('/^[0-9]*[1-9][0-9]*$/i',$money,$matches)!=1)
	{
		$arrRet['cmd'] = "err_money";
		echo json_encode($arrRet);
		exit();
	}
	$sql = "select id,username,recv_cash_name from users where id = '{$user_id}' or username = '{$user_id}' limit 1";
	$result = $db->query($sql);
	$rs = $db->fetch_array($result);
	if(empty($rs)){
		$arrRet['cmd'] = "user_empty";
	}else{
		$agentid=$_SESSION['Agent_Id'];
		$touserid=$rs['id'];
		$ip = usersip();
		/*
		 -- ************************************************************************************
		-- 名称:代理充值
		-- p_agentid     代理id
		-- p_touserid     用户id
		-- p_rmb         rmb
		-- p_ip         操作ip
		-- 返回:result: 0-操作成功，1-分范围错误，2-玩家id不存在，3-代理已被冻结,4-不能自己转给自己,5-代理余额不足，99-数据库错误
		-- ************************************************************************************
		*/
		$sql="call web_agent_paytouser({$agentid},{$touserid},{$money},'{$ip}')";
		$arr = $db->Mysqli_Multi_Query($sql);
		switch($arr[1][0]["result"])
		{
			case '0': //成功
				$arrRet['cmd'] = "ok";
				$arrRet['msg'] = "充值成功!";
				break;
			case '1':
				$arrRet['cmd'] = "err_money";
				break;
			case '2':
				$arrRet['cmd'] = "user_empty";
				break;
			case '3':
				$arrRet['msg'] = "你帐号被冻结，不能充值，有需要帮助请联系客服!";
				$arrRet['cmd'] = "freeze";
				break;
			case '4':
				$arrRet['cmd'] = "self";
				break;
			case '5':
				$arrRet['msg'] = "你帐号余额不足!";
				$arrRet['cmd'] = "insufficient";
				break;
			case '6':
				$arrRet['msg'] = "对方是代理,不能充值!";
				$arrRet['cmd'] = "agent";
				break;
			default:
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = "系统错误，执行失败!";
				break;
		}

		echo json_encode($arrRet);
		exit;

	}
	echo json_encode($arrRet);
	exit();
}



if($action=="send_card_code"){//兑换发送验证码
	$arrRet = array('cmd'=>'');
	$type = $_POST['type'];
	$op = $_REQUEST['op'];
	if(!isset($_SESSION['usersid'])) {
		$arrRet['cmd'] = "notlogin";
		echo json_encode($arrRet);
		exit();
	}
	$usersid=$_SESSION['usersid'];
	$username="";
	$sql = "select id,mobile,username from users where id = '{$usersid}' limit 1";
	$result = $db->query($sql);
	$rs = $db->fetch_row($result);
	$mobile=$user_id=0;
	if(!empty($rs)){
		$mobile=$rs[1];
		$username=$rs[2];
	}
	if($type=="sms"){
		if($op != "exchange"){
			$sql = "select count(*) from validcodelog where code_type = 0 and state = 0 and account = '{$mobile}' and to_days(add_time) = to_days(now())";
			$TodayMsgCount = $db->GetRecordCount($sql);
			if($TodayMsgCount >= 5)
			{
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = "您今天发送的验证码次数已用完，请明天再试,有疑问请联系客服!";
				echo json_encode($arrRet);
				exit;
			}
		}
		
		include_once("class/Sms.php");
		$Sms = new Sms();
		
		if($validcode = $Sms->send($mobile))
		{
			$arrRet['cmd'] = "ok";
			$arrRet['msg'] = "发送成功";
			$_SESSION['exchange_card_vcode'] = $validcode;
		}else{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "短信未能发出，请稍后再试!";
		}
		echo json_encode($arrRet);
		exit();
	}
	
	if($type=="email"){
		$arrRet=SendEmailValidCode($username,"exchange_card_vcode","兑换体验卡验证码");
	}
	
	echo json_encode($arrRet);
	exit();
}




if($action=="agent_rcycle_card"){//卡回收
	$arrRet=array();
	$arrRet['cmd'] ="err";
	$card_list = isset($_POST['card_list'])?$_POST['card_list']:'';
	if($card_list==""){
		$arrRet['cmd'] = "num_err";
		$arrRet['msg'] = "卡号与卡密!";
		echo json_encode($arrRet);
		exit();
	}
	if(!isset($_SESSION['usersid'])) {
		$arrRet['cmd'] = "timeout";
		return $arrRet['cmd'];
	}
	if(! isset($_SESSION['Agent_Id'])){
		$arrRet['cmd'] = "err";
		$arrRet['msg'] = "代理登录超时!";
		echo json_encode($arrRet);
	}
	$Agent_Id=$_SESSION['Agent_Id'];
	$card_list_id=$card_list_data=array();
	$list=explode("\n", $card_list);
	foreach($list as $c){
		$c_list=explode(" ", trim($c));
		if(!empty($c_list)){
			if(count($c_list)==2){
				if(! (empty($c_list[0]) &&  empty($c_list[1])) ){
					$card_list_data[]=array(trim($c_list[0]),trim($c_list[1]));
				}
			}
		}
	}
	$card_obj=array();
	foreach($card_list_data as $cart_data){
		$cardid= $cart_data[0];
		$cardpaw= $cart_data[1];
		$sql = "SELECT    card_no,card_pwd
		FROM exchange_cards
		WHERE   card_no='{$cardid}' and card_pwd='{$cardpaw}' and state=0
		limit 1";
		$query = $db->query($sql);
		$rs=$db->fetch_array($query);
		if(!empty($rs)){
		$card_obj[]=$rs;
	}
	}
	$ip = usersip();
		$cardpoint=$ok_num=0;
		foreach($card_obj as $obj ){
		$CardID=$obj["card_no"];
		$CardPwd=$obj["card_pwd"];
		/*
		-- ************************************************************************************
				-- 名称:代理回收点卡
		-- p_agentid         代理id
		-- p_CardID            卡ID
		-- p_CardPwd        卡密码
		-- p_IP                IP
		-- 返回，result：0,成功,1,卡号错误或卡密错误，2-卡已使用过，3-卡已被冻结，4-代理已被冻结，4-代理没有回收权限，99-系统错误
		-- ************************************************************************************
		*/
		$sql="call web_agent_cards_recycle({$Agent_Id},'{$CardID}','{$CardPwd}','{$ip}')";
		$arr=$db->Mysqli_Multi_Query($sql);
		if(!empty($arr[0][0])){
		if($arr[0][0]["result"]=='0'){
		$ok_num=$ok_num+1;
		$cardpoint=$cardpoint+$arr[0][0]["cardpoint"];
		}
		}
		}
		if($ok_num>0){
		$arrRet['cmd'] = "ok";
		$arrRet['msg'] = "成功回收点卡".$ok_num."张卡,额度共".$cardpoint.", 已成功保存到你银行！";
		}else{
		$arrRet['cmd'] = "err";
			$arrRet['msg'] = "回收失败,无效的卡号和卡密！";
			}
				echo json_encode($arrRet);
				exit();
			}



	if($action=="agent_check_card"){//检测卡号
			$arrRet=array();
			$arrRet['cmd'] ="err";
			$card_list = isset($_POST['card_list'])?$_POST['card_list']:'';
			if($card_list==""){
			$arrRet['cmd'] = "num_err";
					$arrRet['msg'] = "卡号与卡密!";
					echo json_encode($arrRet);
					exit();
			}
			$card_list_id=$card_list_data=array();
			$list=explode("\n", $card_list);
			foreach($list as $c){
			$c_list=explode(" ", trim($c));
			if(!empty($c_list)){
			if(count($c_list)==2){
			if(! (empty($c_list[0]) &&  empty($c_list[1])) ){
			$card_list_data[]=array(trim($c_list[0]),trim($c_list[1]));
			}
			}
			}
			}
			if(!isset($_SESSION['usersid'])) {
			$arrRet['cmd'] = "timeout";
			return $arrRet['cmd'];
			}
			if(! isset($_SESSION['Agent_Id'])){
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "代理登录超时!";
			echo json_encode($arrRet);
			}
			$card_obj=array();
			foreach($card_list_data as $cart_data){
			$cardid= $cart_data[0];
				$cardpaw= $cart_data[1];
				$sql = "SELECT   c.id,card_no,card_points,c.state,u.nickname,u.qq,u.recv_cash_name ,card_name,u.id as uid
				FROM exchange_cards c
				LEFT JOIN  users u  on(u.id=c.uid)
				LEFT JOIN    exchange_cardtype t on(t.card_type=c.card_type)
				WHERE   card_no='{$cardid}' and card_pwd='{$cardpaw}'
				limit 1
				";
			$query = $db->query($sql);
			$rs=$db->fetch_array($query);
			if(empty($rs)){
				$card_obj[]=array(
					"cardid"=>$cardid,"cardtype"=>"","state"=>"不存在或密码错误","username"=>'',"qq"=>"","recv_cash_name">""
				);
			}else{
				if($rs["state"]==0){
				$rs["state"]="可用";
				}else if($rs["state"]==1){
				$rs["state"]="已使用";
				}else if($rs["state"]==2){
				$rs["state"]="已冻结";
				}
				$card_obj[]=$rs;
			}
			}
			$table_str="
			<table class='table_list ' cellspacing='0px' style='border-collapse:collapse;'>
			<tr height='30'>
			<th     align='left'>回收</th>
			<th  style='width:100px;!important;'  align='left'>卡号</th>
			<th width=70  align='left'>卡名</th>
			<th    width=60  align='left'>点数</th>
					<th    width=80  align='left'>卡状态</th>
							<th    width=60  align='left'>所属用户</th>
							<th    width=60  align='left'>qq</th>
								<th    width=60  align='left'>收款人</th>
										</tr>
											";
			foreach($card_obj as $data){
			$data["card_name"]=ChangeEncodeG2U($data["card_name"]);
			$checked="";
			if($data["state"]=="可用"){
                $checked="checked";
            }
            $table_str .="<tr>
                <td style='width:20px;!important;' ><input name='checkbox' type='checkbox' id='checkbox' {$checked} disabled></td>
                <td style='width:80px;!important;' >".$data["card_no"]."</td>
                <td>".$data["card_name"]."</td>
                <td>".number_format($data["card_points"])."</td>
                <td>".$data["state"]."</td>
                <td>".ChangeEncodeG2U($data["nickname"])."(".$data["uid"].")</td>
                <td>".$data["qq"]."</td>
                <td>".ChangeEncodeG2U($data["recv_cash_name"])."</td>
                </tr>
                		";
		}

		$table_str .="
		</table>
			";
		if(empty($card_obj)){
			$arrRet['cmd'] = "empty";
			$arrRet['msg'] = "卡不可用!";
		}else{
			$arrRet['cmd'] = "ok";
			$arrRet['msg'] = "卡不可用!";
			$arrRet['table']=$table_str;
		}
        echo json_encode($arrRet);
        exit();
    }


if($action=="agent_change"){//额度转换
	$arrRet=array();
	$arrRet['cmd'] ="err";
	$toagentid = isset($_POST['agent_id'])?intval($_POST['agent_id']):0;
	$money = isset($_POST['money'])?intval($_POST['money']):0;
	if($money<1){
		$arrRet['cmd'] = "num_err";
		$arrRet['msg'] = "请输入转换额度!";
		echo json_encode($arrRet);
		exit();
	}
	if($toagentid<1){
		$arrRet['cmd'] = "num_err";
		$arrRet['msg'] = "选择转换代理!";
		echo json_encode($arrRet);
		exit();
	}

	if(!isset($_SESSION['usersid'])) {
		$arrRet['cmd'] = "timeout";
		return $arrRet['cmd'];
	}
	if(! isset($_SESSION['Agent_Id'])){
		$arrRet['cmd'] = "err";
		$arrRet['msg'] = "代理登录超时!";
		echo json_encode($arrRet);
	}
	$usersid=$_SESSION['usersid'];
	$Agent_Id=$_SESSION['Agent_Id'];
	$ip = usersip();
	/*
	 -- ************************************************************************************
	-- 名称:代理转让额度
	-- p_agentid     代理id
	-- p_toagentid     对方id
	-- p_point         额度
	-- p_ip         操作ip
	-- 返回:result: 0-操作成功，1-分范围错误，2-代理已被冻结,3-对方代理id已被冻结，4-不能自己转给自己,5-额度不足，6-银行余额不足，99-数据库错误
	-- ************************************************************************************
	*/
	$sql="call web_agent_point2agent({$Agent_Id},{$toagentid},{$money},'{$ip}')";
	$arr = $db->Mysqli_Multi_Query($sql);
	if(empty($arr)){
		$arrRet['cmd'] = "err";
		$arrRet['msg'] = "系统错误，执行失败!";
		echo json_encode($arrRet);
		exit();
	}
	switch($arr[1][0]["result"])
	{
		case '0': //成功
			$arrRet['cmd'] = "ok";
			$arrRet['msg'] = "转换成功!";
			break;
		case '1':
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "转换额度范围错误!";
			break;
		case '2':
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "代理已被冻结!";
			break;
		case '3':
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "对方代理id已被冻结!";
			break;
		case '4':
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "不能自己转给自己!";
			break;
		case '5':
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "额度不足!";
			break;
		case '6':
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "银行余额不足!";
			break;
		default:
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "系统错误，执行失败!";
			break;
	}
	echo json_encode($arrRet);
	exit();
}




if($action=="agent_withdraw_revocation"){//代理取消提现
	$id = intval($_POST['id']);
	$arrRet=array();
	$arrRet['cmd'] ="err";
	if($id<0){
		$arrRet['cmd'] = "num_err";
		$arrRet['msg'] = "参数错误!";
		echo json_encode($arrRet);
		exit();
	}
	if(!isset($_SESSION['usersid'])) {
		$arrRet['cmd'] = "timeout";
		return $arrRet['cmd'];
	}
	if(! isset($_SESSION['Agent_Id'])){
		$arrRet['cmd'] = "timeout";
		return $arrRet['cmd'];
	}
	$agentid=$_SESSION['Agent_Id'];
	$ip = usersip();
	/*
	 -- ************************************************************************************
	-- 名称:代理撤销提现
	-- p_agentid         代理id
	-- p_recid            申请表记录id
	-- p_ip                操作ip
	-- 返回，result：0-成功,1-代理已被冻结，2-订单信息错误，3-订单已被撤销过
	-- ************************************************************************************
	*/
	$sql="call web_agent_cancel_withdraw({$agentid},{$id},'{$ip}')";
	$arr = $db->Mysqli_Multi_Query($sql);
	if(empty($arr)){
		$arrRet['cmd'] = "err";
		$arrRet['msg'] = "系统错误，执行失败!";
		echo json_encode($arrRet);
		exit();
	}
	switch($arr[0][0]["result"])
	{
		case '0': //成功
			$arrRet['cmd'] = "ok";
			$arrRet['msg'] = "撤销成功,金额已打回到你的银行!";
			break;
		case '1':
			$arrRet['cmd'] = "user";
			$arrRet['msg'] = "你帐号已被冻结，有需要帮助请联系客服!";
			break;
		case '2':
			$arrRet['cmd'] = "data_err";
			$arrRet['msg'] = "订单信息错误!";
			break;
		case '3':
			$arrRet['msg'] = "订单已被撤销过!";
			$arrRet['cmd'] = "data_err";
			break;

		default:
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "系统错误，执行失败!";
			break;
	}
	echo json_encode($arrRet);
	exit();
}


if($action=="agent_withdraw"){//代理申请兑现
	$money = intval($_POST['money']);
	$arrRet=array();
	$arrRet['cmd'] ="err";
	if($money<0){
		$arrRet['cmd'] = "num_err";
		$arrRet['msg'] = "提现金额不对!";
		echo json_encode($arrRet);
		exit();
	}
	if($money<100){
		$arrRet['cmd'] = "num_err";
		$arrRet['msg'] = "提现金额过小,必须是100的倍数!!";
		echo json_encode($arrRet);
		exit();
	}
	if($money%100!=0){
		$arrRet['cmd'] = "num_err";
		$arrRet['msg'] = "提现金额必须是100的倍数!";
		echo json_encode($arrRet);
		exit();
	}
	if(! isset($_SESSION['Agent_Id'])){
		$arrRet['cmd'] = "timeout";
		$arrRet['msg'] = "登录超时!";
		echo json_encode($arrRet);
		exit();
	}
	if(!isset($_SESSION['usersid'])) {
		$arrRet['cmd'] = "notlogin";
		$arrRet['msg'] = "登录超时!";
		exit();
	}
	$money=$money*1000;
	$agentid=$_SESSION['Agent_Id'];
	$ip = usersip();
	/*
	 -- ************************************************************************************
	-- 名称:代理申请兑现
	-- p_agentid         代理id
	-- p_apply_points    申请提现分
	-- p_IP                IP
	-- 返回，result：0-成功,1-代理已被冻结，2-银行余额不足，3-可提现余额不足,99-数据库错误
	-- ************************************************************************************
	*/
	$sql="call web_agent_apply_withdraw({$agentid},{$money},'{$ip}')";
	$arr = $db->Mysqli_Multi_Query($sql);
	if(empty($arr)){
		$arrRet['cmd'] = "err";
		$arrRet['msg'] = "系统错误，执行失败!";
		echo json_encode($arrRet);
		exit();
	}
	switch($arr[0][0]["result"])
	{
		case '0': //成功
			$arrRet['cmd'] = "ok";
			$arrRet['msg'] = "申请成功!";
			break;
		case '1':
			$arrRet['cmd'] = "user";
			$arrRet['msg'] = "你帐号已被冻结，有需要帮助请联系客服!";
			break;
		case '2':
			$arrRet['cmd'] = "not_money";
			$arrRet['msg'] = "银行余额不足!";
			break;
		case '3':
			$arrRet['msg'] = "可提现余额不足!";
			$arrRet['cmd'] = "not_money";
			break;

		default:
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "系统错误，执行失败!";
			break;
	}
	echo json_encode($arrRet);
	exit();
}


if($action=="users_exchange"){//用户兑换

	$num = intval($_POST['num']);
	$cart_id= intval($_POST['cart_id']);
	$pass=$_POST['pass'];
	$vcode=$_POST['vcode'];
	 
	$arrRet=array();
	$arrRet['cmd'] ="err";
	if($num<1){
		$arrRet['cmd'] = "num_err";
		$arrRet['msg'] = "兑换数量不对!";
		echo json_encode($arrRet);
		exit();
	}
	if($cart_id<1){
		$arrRet['cmd'] = "cart_id_err";
		$arrRet['msg'] = "体验卡不存在!";
		echo json_encode($arrRet);
		exit();
	}
	if($cart_id>9){
		$arrRet['cmd'] = "cart_id_err";
		$arrRet['msg'] = "体验卡不存在!";
		echo json_encode($arrRet);
		exit();
	}
	
	if(empty($vcode)){
		$arrRet['cmd'] = "vcode";
		echo json_encode($arrRet);
		exit();
	}
	if(!isset($_SESSION['exchange_card_vcode'] )){
		$arrRet['cmd'] = "vcode_not";
		echo json_encode($arrRet);
		exit();
	}
	if($_SESSION['exchange_card_vcode'] !=$vcode){
		$arrRet['cmd'] = "vcode_err";
		echo json_encode($arrRet);
		exit();
	}

	if(empty($pass)){
		$arrRet['cmd'] = "pass";
		echo json_encode($arrRet);
		exit();
	}
	 
	if(!isset($_SESSION['usersid'])) {
		$arrRet['cmd'] = "notlogin";
		echo json_encode($arrRet);
		exit();
	}
	$usersid=$_SESSION['usersid'];
	$sql = "select is_check_mobile,is_check_email,email from users where id = '{$usersid}' limit 1";
	$result = $db->query($sql);
	$users = $db->fetch_row($result);
	$email="";
	if(!empty($users)){
		if($users[0]==0){// || $users[1]==0 || $users[2]==""
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "需要先绑定手机才能兑换!";
			echo json_encode($arrRet);
			exit();
		}
		$email=$users[2];

	}
	$sql = "select uid from users_inner where uid = '{$usersid}' limit 1";
	$result = $db->query($sql);
	$users = $db->fetch_row($result);
	if(!empty($users)){
		$arrRet['cmd'] = "cart_id_err";
		$arrRet['msg'] = "你是内部帐号不允许兑换!";
		echo json_encode($arrRet);
		exit();
	}
	$pwd = md5($web_pwd_encrypt_prefix . $pass);
	$ip = usersip();
	/*
	 -- ************************************************************************************
	-- 名称:用户兑换点卡
	-- p_userid     用户id
	-- p_cardtype    卡类型
	-- p_num         卡数量
	-- p_bankpwd     安全密码，密文
	-- p_ip         操作ip
	-- 返回:result: 0-操作成功，1-密码错误，2-参数错误，3-用户已被冻结,4-取折扣错误,5-体验卡不存在，6-余额不足，99-数据库错误
	-- ************************************************************************************
	*/
	$sql="call web_user_exchange_point2card({$usersid},{$cart_id},{$num},'{$pwd}','{$ip}')";
	$arr = $db->Mysqli_Multi_Query($sql);
	if(empty($arr)){
		$arrRet['cmd'] = "err";
		$arrRet['msg'] = "系统错误，执行失败!";
		echo json_encode($arrRet);
		exit();
	}
	switch($arr[0][0]["result"])
	{
		case '0': //成功
			$arrRet['cmd'] = "ok";
			/* $arrRet['msg'] = "兑换成功,体验卡密码已经发送到你邮箱!";
			$mailTitle="恭喜你成功兑换体验卡";
			$mailContent="你兑换".$cart_list["cart_".$cart_id]["name"]."的卡号和卡密码为：".$arr[0][0]["cardlist"];
			SendMailToUser($email,$mailTitle,$mailContent); */
			$_SESSION['exchange_card_vcode'] = "";
			unset($_SESSION['exchange_card_vcode']);
			$arrRet['msg'] = "兑换成功,请查看您的会员中心的兑换记录!";
			break;
		case '1':
			$arrRet['cmd'] = "pass";
			$arrRet['msg'] = "你的安全密码错误!";
			break;
		case '2':
			$arrRet['cmd'] = "Parameters";
			$arrRet['msg'] = "参数错误!";
			break;
		case '3':
			$arrRet['cmd'] = "user";
			$arrRet['msg'] = "你帐号已被冻结，有需要帮助请联系客服!";
			break;
		case '4':
			$arrRet['cmd'] = "Parameters";
			$arrRet['msg'] = "系统错误，有需要帮助请联系客服!";
			break;
		case '5':
			$arrRet['msg'] = "体验卡不存在!";
			$arrRet['cmd'] = "insufficient";
			break;
		case '6':
			$arrRet['msg'] = "你的余额不足!";
			$arrRet['cmd'] = "insufficient";
			break;
		default:
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "系统错误，执行失败!";
			break;
	}
	echo json_encode($arrRet);
	exit();
}


//检查验证码
if($action=="get_pwd_step2"){
    $arrRet = array('cmd'=>'');
    $get_type = $_POST['get_type'];
    $vcode = $_POST['vcode'];

    $check_code=false;
    /* if(  isset($_SESSION["get_pwd_check_code_email"])    ){
        $check_code=true;
    } */
    if(  isset($_SESSION["get_pwd_check_code_sms"])  && !empty($_SESSION["get_pwd_check_code_sms"])  ){
        $check_code=true;
    }
    
    if($check_code==true){

        $username = $_SESSION["get_pwd_ac"];
        $sql = "select is_check_email,is_check_mobile,mobile from users where username = '{$username}' limit 1";
        $result = $db->query($sql);
        $rs = $db->fetch_row($result);
        $user_id=0;
        if(!empty($rs)){
        	$mobile = $rs[2];
            /* if($get_type=="email"){
                if($rs[0]==0){
                    $arrRet['cmd'] = "check_email";
                    echo $arrRet['cmd'];
                    exit;
                }
            }
            if($get_type=="sms"){
                if($rs[1]==0){
                    $arrRet['cmd'] = "check_mobile";
                    echo $arrRet['cmd'];
                    exit;
                }
            } */
            
            if($rs[1]==0){
            	$arrRet['cmd'] = "check_mobile";
            	echo $arrRet['cmd'];
            	exit;
            }
        }

    }
    if($check_code==false){
        $arrRet['cmd'] = "timeout";
        echo $arrRet['cmd'];
        exit;
    }
    if(! isset($_SESSION["get_pwd_ac"]) ){
        $arrRet['cmd'] = "timeout";
        echo $arrRet['cmd'];
        exit;
    }
    if($vcode != $_SESSION["CheckNum"]) //验证码错误
    {
        $arrRet['cmd'] = "vcode";
        echo $arrRet['cmd'];
        exit;
    }

    $check_code = trim($_POST['check_code']);
    if(empty($check_code)){
    	$arrRet['cmd'] = "code_err";
    	echo $arrRet['cmd'];
    	exit;
    }
    /* if($get_type=="email"){
        if($check_code != $_SESSION["get_pwd_check_code_email"] ){
            $arrRet['cmd'] = "code_err";
            echo $arrRet['cmd'];
            exit;
        }
    }
    if($get_type=="sms"){
        if($check_code != $_SESSION["get_pwd_check_code_sms"] ){
            $arrRet['cmd'] = "code_err";
            echo $arrRet['cmd'];
            exit;
        }
    } */
    
    
    //检查验证次数
    $sql = "select id,code,verifytimes from validcodelog where code_type = 0 and state = 0 and account = '{$mobile}' and to_days(add_time) = to_days(now()) order by id desc limit 1";
    $row=$db->fetch_first($sql);
    if((int)$row['verifytimes'] > 5){
    	$arrRet['cmd'] = "verifytimes_err";
    	echo $arrRet['cmd'];
    	exit;
    }
    
    if($check_code != $_SESSION["get_pwd_check_code_sms"] ){
    	$id = $row['id'];
    	$sql = "update validcodelog set verifytimes=verifytimes+1 where id={$id}";
    	$db->exec($sql);
    	
    	$arrRet['cmd'] = "code_err";
    	echo $arrRet['cmd'];
    	exit;
    }else{
    	$id = $row['id'];
    	$sql = "update validcodelog set verifytimes=verifytimes+5 where id={$id}";
    	$db->exec($sql);
    }
    
    
    if($get_type=="email"){
        $_SESSION["pwd_check_code_email"]="OK";
    }
    if($get_type=="sms"){
        $_SESSION["pwd_check_code_sms"]="OK";
    }
    
    $_SESSION["pass_user_account"] = $username;
    
    $arrRet['cmd'] = "OK";
    echo $arrRet['cmd'];
}

//检查修改密码
if($action=="get_pwd_step3"){
    $arrRet = array('cmd'=>'');
    $pass = $_POST['pass'];
    $vcode = $_POST['vcode'];
 
    if(! isset($_SESSION["pass_user_account"]) ){
        $arrRet['cmd'] = "timeout";
        echo $arrRet['cmd'];
        exit;
    }
    if($vcode != $_SESSION["CheckNum"]) //验证码错误
    {
        $arrRet['cmd'] = "vcode";
        echo $arrRet['cmd'];
        exit;
    }
    if(empty($pass)) //密码空
    {
        $arrRet['cmd'] = "pass";
        echo $arrRet['cmd'];
        exit;
    }
    if(strlen($pass)>20) //密码空
    {
        $arrRet['cmd'] = "pass_long";
        echo $arrRet['cmd'];
        exit;
    }
    if(strlen($pass)<6) //密码空
    {
        $arrRet['cmd'] = "pass_short";
        echo $arrRet['cmd'];
        exit;
    }
    $username = $_SESSION["pass_user_account"];
    $sql = "select id from users where username = '{$username}' limit 1";
    $result = $db->query($sql);
    $UserID=0;
    $ip = usersip();
    $rs = $db->fetch_row($result);
    if(!empty($rs)){
        $UserID=$rs[0];
    }
    if($UserID==0){
        $arrRet['cmd'] = "timeout";
        echo $arrRet['cmd'];
        exit;
    }
    /*
    -- 名称: 修改密码
    -- p_UserID         用户ID
    -- p_PwdType        0:登录密码，1：银行密码
    -- p_OprUser        back-后台修改，sms-短信修改，userid-用户修改
    -- p_OldPwd          老密码
    -- p_NewPwd            新密码
    -- p_IP                IP
    -- 返回: 0:成功，1：原密码错误，99：系统错误
    */
    $pass = md5($web_pwd_encrypt_prefix . $pass);
    $sql="call web_user_changepwd({$UserID},0,'sms','','{$pass}','{$ip}')";
    $arr = $db->Mysqli_Multi_Query($sql);
    if($arr[0][0]["result"]=='0'){
        $arrRet['cmd'] = "ok";
        if(  isset($_SESSION["pwd_check_code_email"])    ){
            unset($_SESSION['pwd_check_code_email']);
        }
        if(  isset($_SESSION["pwd_check_code_sms"])    ){
            unset($_SESSION['pwd_check_code_sms']);
        }
        unset($_SESSION['get_pwd_ac']);
        echo $arrRet['cmd'];
        exit;
    }else{
        $arrRet['cmd'] = "db_err";
        echo $arrRet['cmd'];
        exit;
    }
}

if($action=="login_confirm"){
    $arrRet = array('cmd'=>'');
    $pass = $_REQUEST['pass'];
    if(empty($pass)) //密码空
    {
        $arrRet['cmd'] = "pass";
        $arrRet['msg']="请输入安全密码！";
        echo json_encode($arrRet);
        exit;
    }
    $pass = md5($web_pwd_encrypt_prefix . $pass);
    $isagent=$usersid=0;
    $sql = "select id,isagent from users where id = {$_SESSION['usersid']} and bankpwd='{$pass}' ";
    $result =  $db->query($sql);
    if($row = $db->fetch_array($result))
    {
        $usersid = $row["id"];
        $isagent = $row["isagent"];
        if($isagent==1){//是否代理
            $ip = usersip();
            $sql="update agent set last_logintime=now(),last_loginip='{$ip}' where uid={$usersid}";
            $db->query($sql); //更新代理登录时间
        }
    }
    if($usersid ==0){
        $arrRet['msg']="密码错误！";
        $arrRet['cmd'] = "err";
    }else{
        $arrRet['cmd'] = "ok";
        $arrRet['msg']="登录成功";
        $_SESSION['Login_Confirm']=$_SESSION['usersid'];
    }
    echo json_encode($arrRet);
    exit;
}


if($action=="get_back_pwd_sms"){//发送短信验证码
	$arrRet = array('cmd'=>'');
	if(! isset($_SESSION["usersid"]) ){
		$arrRet['cmd'] = "timeout";
		echo json_encode($arrRet);
		exit;
	}

	$usersid = $_SESSION["usersid"];
	$sql = "select id,mobile from users where id = '{$usersid}' limit 1";
	$result = $db->query($sql);
	$rs = $db->fetch_row($result);
	$mobile=$user_id=0;
	if(!empty($rs)){
		$user_id= $rs[0];
		$mobile=$rs[1];
	}
	if($user_id==0){
		$arrRet['cmd'] = "timeout";
		echo json_encode($arrRet);
		exit;
	}
	$sql = "select count(*) from validcodelog where code_type = 0 and state = 0 and account = '{$mobile}' and to_days(add_time) = to_days(now())";
	$TodayMsgCount = $db->GetRecordCount($sql);
	if($TodayMsgCount >= 5)
	{
		$arrRet['cmd'] = "err";
		$arrRet['msg'] = "您今天发送的验证码次数已用完，请明天再试,有疑问请联系客服!";
		echo json_encode($arrRet);
		exit;
	}
	
	
	include_once("class/Sms.php");
	$Sms = new Sms();
	
	if($validcode = $Sms->send($mobile))
	{
		$arrRet['cmd'] = "ok";
		$arrRet['msg'] = "发送成功";
		$_SESSION['get_safe_pwd_check_code_sms'] = $validcode;
	}
	else
	{
		$arrRet['cmd'] = "err";
        $arrRet['msg'] = "短信未能发出，请稍后再试!";
	}
	echo json_encode($arrRet);
	exit();
}


//找回安全密码
if($action=="get_safe_pwd_step1"){
    $arrRet = array('cmd'=>'');
    $sms_code = trim($_POST['check_code']);
    if( ! isset($_SESSION["get_safe_pwd_check_code_sms"])  || empty($sms_code)  ){
        $arrRet['cmd'] = "timeout";
        echo $arrRet['cmd'];
        exit;
    }
    if($sms_code != $_SESSION["get_safe_pwd_check_code_sms"] ){
        $arrRet['cmd'] = "code_err";
        echo $arrRet['cmd'];
        exit;
    }
    $_SESSION["safe_pwd_check_code"]="OK";
    $arrRet['cmd'] = "OK";
    echo $arrRet['cmd'];
}


//检查修改密码
if($action=="modify_safe_pwd"){
    $arrRet = array('cmd'=>'');
    $pass = $_POST['pass'];

    if(empty($pass)) //密码空
    {
        $arrRet['cmd'] = "pass";
        echo $arrRet['cmd'];
        exit;
    }
    if(strlen($pass)>20) //密码空
    {
        $arrRet['cmd'] = "pass_long";
        echo $arrRet['cmd'];
        exit;
    }
    if(strlen($pass)<6) //密码空
    {
        $arrRet['cmd'] = "pass_short";
        echo $arrRet['cmd'];
        exit;
    }
    if(! isset($_SESSION["safe_pwd_check_code"]) || empty($_SESSION["safe_pwd_check_code"]) ){
        $arrRet['cmd'] = "timeout";
        echo $arrRet['cmd'];
        exit;
    }

    if(! isset($_SESSION["usersid"]) ){
        $arrRet['cmd'] = "timeout";
        echo json_encode($arrRet);
        exit;
    }
    $UserID=$_SESSION["usersid"];
    $ip = usersip();
    /*
    -- 名称: 修改密码
    -- p_UserID         用户ID
    -- p_PwdType        0:登录密码，1：银行密码
    -- p_OprUser        back-后台修改，sms-短信修改，userid-用户修改
    -- p_OldPwd          老密码
    -- p_NewPwd            新密码
    -- p_IP                IP
    -- 返回: 0:成功，1：原密码错误，99：系统错误
    */
    $pass = md5($web_pwd_encrypt_prefix . $pass);
    $sql="call web_user_changepwd({$UserID},1,'sms','','{$pass}','{$ip}')";
    $arr = $db->Mysqli_Multi_Query($sql);
    if($arr[0][0]["result"]=='0'){
        $arrRet['cmd'] = "ok";
        $_SESSION['Login_Confirm']=$_SESSION['usersid'];
        if(  isset($_SESSION["safe_pwd_check_code"])    ){
            unset($_SESSION['safe_pwd_check_code']);
        }
        echo $arrRet['cmd'];
        exit;
    }else{
        $arrRet['cmd'] = "db_err";
        echo $arrRet['cmd'];
        exit;
    }
}


//检查修改密码
if($action=="get_jiuji"){
	$ip = usersip();
	$arrRet = array('cmd'=>'','msg'=>'');
	$sql = "call web_reward_jiuji({$_SESSION['usersid']},'{$ip}')";
	//WriteLog($sql);
	$arr = $db->Mysqli_Multi_Query($sql);
	$arrRet['cmd'] = "ok";
	switch($arr[0][0]["result"])
	{
		case '0': //成功
			$arrRet['msg'] = "成功获得救济" . $arr[0][0]["points"];
			RefreshPoints();
			break;
		case '1':
			$arrRet['msg'] = "今日已领取过了!";
			break;
		case '2':
			$arrRet['msg'] = "该等级无救济分!";
			break;
		default:
			$arrRet['msg'] = "系统错误，执行失败!";
			break;
	}
	
	echo json_encode($arrRet);
	exit;
}

?>
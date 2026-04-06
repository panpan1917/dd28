<?php 
header ( 'Content-type:text/html;charset=utf-8' );
include_once '../core/gwpay/func/secureUtil.php';

// 初始化日志
$log = new PhpLog ( SDK_LOG_FILE_PATH, "PRC", SDK_LOG_LEVEL );
$log->LogInfo ( "===========处理支付后台通知开始============" );

$paramStr = createLinkString($_POST);
$log->LogInfo ( "===========处理支付后台通知:" . $paramStr );

// 验签
$flag = verify($_POST);

if($flag){
	$log->LogInfo ( "处理支付后台通知验签成功，可继续后续业务");
	
	$rtnCode = $_POST['rtnCode'];
	$tranSerialNum = $_POST['tranSerialNum'];
	$paySerialNo = $_POST['paySerialNo'];
	if($rtnCode == "000"){
		include_once("inc/conn.php");
		
		$sql="select * from pay_online where order_id='{$tranSerialNum}'";
		$result = $db->query($sql);
		$res=$db->fetch_array($result);
		if(!$res['id']){
			$log->LogInfo ( "订单({$tranSerialNum})不存在！");
			exit;
		}
		
		if($res['state']==1){
			$log->LogInfo ( "订单({$tranSerialNum})已经上分！");
			echo 'YYYYYY';
			exit;
		}
		
		if($res['state']!=0){
			$log->LogInfo ( "订单({$tranSerialNum})已撤销！");
			exit;
		}
		
		//用户上分
		//$sql = "call web_payonline_topay('{$tranSerialNum}','{$paySerialNo}')";
		$arr = $db->Mysqli_Multi_Query($sql);
		switch($arr[0][0]["result"])
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
		
		if($status == 0 || $status == 2){
			$log->LogInfo ( "订单({$tranSerialNum})上分成功！");
			echo 'YYYYYY';
			exit;
		}else{
			$log->LogInfo ( "订单({$tranSerialNum})上分失败！");
			exit;
		}
	}
}else{
	$log->LogInfo ( "处理支付后台通知验签失败");
}

$log->LogInfo ( "===========处理支付后台通知结束============" );

//echo 'YYYYYY';


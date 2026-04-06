<?php
include_once("inc/conn.php");
include_once("inc/function.php");


if(empty($_POST)){
	$ret['resultCode'] = "-1";
	$ret['msg'] = "回调参数错误";
	echo json_encode($ret);
	exit;
}


if($_POST['pay_result']==20){
	$attachArr = explode("_", $_POST['order_id']);
	$id = (int)$attachArr[1];
	$order_id = $attachArr[0];


	$sql="select * from pay_online where id={$id} and order_id='{$order_id}'";
	$result = $db->query($sql);
	$res=$db->fetch_array($result);
	if(!$res['id']){
		$ret['resultCode'] = "-3";
		$ret['msg'] = "订单不存在";
		
		$file = "/tmp/pay.log";
		@file_put_contents($file, "{$order_id}#订单不存在#",FILE_APPEND);
		
		echo json_encode($ret);
		exit;
	}
	
	require_once '../core/payment/payment2.php';
	$payment = new payment();
	$payment->setAccount($res['cz_type']);
	
	$params['app_id'] = (int)$_POST['app_id'];
	$params['order_id'] = $_POST['order_id'];
	$params['pay_seq'] = $_POST['pay_seq'];
	$params['pay_amt'] = $_POST['pay_amt'];
	$params['pay_result'] = $_POST['pay_result'];
	$params['key'] = md5($payment->Appkey);
	
	
	$sign = $_POST['sign'];

	$curSign = $payment->verSign($params);
	
	
	if($sign != $curSign || empty($curSign)){
		$ret['resultCode'] = "-5";
		$ret['msg'] = "验签失败";
		
		$file = "/tmp/pay.log";
		@file_put_contents($file, "{$sign}#验签失败#{$curSign}",FILE_APPEND);
		
		echo json_encode($ret);
		exit;
	}
	
	
	if($res['state']==1){
		$ret['resultCode'] = "2";//成功
		//echo json_encode($ret);
		echo "ok";
		exit;
	}
	
	/* if($res['state']!=0){
		$ret['resultCode'] = "-4";
		$ret['msg'] = "订单已撤销";
		
		$file = "/tmp/pay.log";
		@file_put_contents($file, "订单已撤销",FILE_APPEND);
		
		echo json_encode($ret);
		exit;
	} */

	//用户上分
	$sql = "call web_payonline_topay('{$order_id}','{$_POST['pay_seq']}')";
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
		$ret['resultCode'] = "2";//成功
		//echo json_encode($ret);
		echo $msg;
		exit;
	}else{
		$ret['resultCode'] = "-2";
		$ret['msg'] = "上分失败";
		
		$file = "/tmp/pay.log";
		@file_put_contents($file, "上分失败",FILE_APPEND);
		
		echo json_encode($ret);
		exit;
	}
}else{
	$ret['resultCode'] = "-1";
	$ret['msg'] = "回调参数错误";
	
	$file = "/tmp/pay.log";
	@file_put_contents($file, "回调参数错误",FILE_APPEND);
	
	$file = "/tmp/pay.log";
	@file_put_contents($file, json_encode($_POST),FILE_APPEND);
	
	echo json_encode($ret);
	exit;
}






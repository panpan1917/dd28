<?php
include_once("inc/conn.php");
include_once("inc/function.php");


$postDataArr = $_GET;
unset($postDataArr['t']);

/* $file = "/tmp/pay1.log";
@file_put_contents($file, json_encode($postDataArr),FILE_APPEND); 
 */

if(empty($postDataArr)){
	$ret['resultCode'] = "-1";
	$ret['msg'] = "回调参数错误";
	echo json_encode($ret);
	exit;
}


$sign = $postDataArr['sign'];
unset($postDataArr['sign']);
$params = $postDataArr;


if($params['status']=="1"){
	$attachArr = explode("_", $postDataArr['mch_order_no']);
	$id = (int)$attachArr[1];
	$order_id = $attachArr[0];


	$sql="select * from pay_online where id={$id} and order_id='{$order_id}'";
	$result = $db->query($sql);
	$res=$db->fetch_array($result);
	if(!$res['id']){
		$ret['resultCode'] = "-3";
		$ret['msg'] = "订单不存在";
		
		$file = "/tmp/pay1.log";
		@file_put_contents($file, "{$order_id}#订单不存在#",FILE_APPEND);
		
		echo json_encode($ret);
		exit;
	}
	
	require_once '../core/payment/payment1.php';
	$payment = new payment();
	$payment->setAccount($res['cz_type']);
	
	$curSign = $payment->verSign($params);
	if($sign != $curSign || empty($curSign)){
		$ret['resultCode'] = "-5";
		$ret['msg'] = "验签失败";
		
		$file = "/tmp/pay1.log";
		@file_put_contents($file, "{$sign}#验签失败#{$curSign}",FILE_APPEND);
		
		echo json_encode($ret);
		exit;
	}
	
	
	
	if($res['state']==1){
		$ret['resultCode'] = "2";//成功
		//echo json_encode($ret);
		echo 'Success';
		exit;
	}

	//用户上分
	$sql = "call web_payonline_topay('{$order_id}','{$postDataArr['paynum']}')";
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
		echo 'Success';
		exit;
	}else{
		$ret['resultCode'] = "-2";
		$ret['msg'] = "上分失败";
		
		$file = "/tmp/pay1.log";
		@file_put_contents($file, "上分失败",FILE_APPEND);
		
		echo json_encode($ret);
		exit;
	}
}else{
	$ret['resultCode'] = "-1";
	$ret['msg'] = "回调参数错误";
	
	$file = "/tmp/pay1.log";
	@file_put_contents($file, "回调参数错误",FILE_APPEND);
	
	echo json_encode($ret);
	exit;
}






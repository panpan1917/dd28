<?php
include_once("inc/conn.php");
include_once("inc/function.php");


$postData = file_get_contents("php://input");
$postDataArr = json_decode($postData , true);
if(empty($postDataArr)){
	$ret['resultCode'] = "-1";
	$ret['msg'] = "回调参数错误";
	echo json_encode($ret);
	exit;
}

$file = "/tmp/pay.log";
@file_put_contents($file, $postData,FILE_APPEND);
 
$params['code'] = $postDataArr['code'];
$params['attach'] = $postDataArr['attach'];
$params['paynum'] = $postDataArr['paynum'];
$params['paytype'] = $postDataArr['paytype'];
$params['money'] = $postDataArr['money'];
$params['paytime'] = $postDataArr['paytime'];
$params['resultcode'] = $postDataArr['resultcode'];
$params['resultmsg'] = $postDataArr['resultmsg'];
$sign = $postDataArr['sign'];



if($params['resultcode']==0){
	$attachArr = explode("_", $postDataArr['attach']);
	$id = (int)$attachArr[2];
	$order_id = $attachArr[1];


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
	
	require_once '../core/payment/payment.php';
	$payment = new payment();
	
	if(in_array($res['cz_type'] , [104,105,106])) $payment->setAccount(0);
	if(in_array($res['cz_type'] , [1004,1002,1001,1003,1014,1008,1011,1013,1006,1007,1009,1012,1017,1016,1025,1010,1005,1103])) $payment->setAccount(3);

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
		echo json_encode($ret);
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
		echo json_encode($ret);
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
	
	echo json_encode($ret);
	exit;
}






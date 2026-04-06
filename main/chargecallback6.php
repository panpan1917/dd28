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

$curtime = date("Y-m-d H:i:s");

$sign = $postDataArr['md5value'];
$params = $postDataArr;

if($postDataArr['txnStatus']==="S"){
	$attachArr = explode("_", $postDataArr['mercOrderId']);
	$id = (int)$attachArr[1];
	$order_id = $attachArr[0];


	$sql="select * from pay_online where id={$id} and order_id='{$order_id}'";
	$result = $db->query($sql);
	$res=$db->fetch_array($result);
	if(!$res['id']){
		$ret['resultCode'] = "-3";
		$ret['msg'] = "订单不存在";
		
		$file = "/tmp/pay6.log";
		@file_put_contents($file, "{$curtime}:{$order_id}#订单不存在\n",FILE_APPEND);
		
		echo json_encode($ret);
		exit;
	}
	
	
	require_once '../core/payment/payment6.php';
	$payment = new payment();
	$payment->setAccount($res['cz_type']);
	
	

	$curSign = strtoupper(md5($params['mercId'] . $params['mercOrderId'] . $params['txnDate'] . $params['txnStatus'] . $payment->apiKey));
	
	
	if($sign != $curSign || empty($curSign)){
		$ret['resultCode'] = "-5";
		$ret['msg'] = "验签失败";
		
		$file = "/tmp/pay6.log";
		@file_put_contents($file, "{$curtime}:{$sign}#验签失败#{$curSign}\n",FILE_APPEND);
		
		echo json_encode($ret);
		exit;
	}
	
	
	if($res['state']==1){
		$ret['resultCode'] = "2";//成功
		//echo json_encode($ret);
		echo "success";
		exit;
	}
	
	/* if($res['state']!=0){
		$ret['resultCode'] = "-4";
		$ret['msg'] = "订单已撤销";
		
		$file = "/tmp/pay6.log";
		@file_put_contents($file, "订单已撤销",FILE_APPEND);
		
		echo json_encode($ret);
		exit;
	} */

	//用户上分
	$sql = "call web_payonline_topay('{$order_id}','{$postDataArr['outChannelNo']}')";
	$arr = $db->Mysqli_Multi_Query($sql);
   	switch($arr[0][0]["result"])
	{
		case '-1':
			$msg = "系统错误，请联系客服!";
			$status=-1;
			break;
		case '0':
			$msg = "success";
			$status=0;
			break;
		case '1':
			$msg = "订单不存在!请联系客服!";
			$status=1;
			break;
		case '2':
			$msg = "success";
			$status=2;
			break;
		default:
			$msg = "未知错误，请联系客服!";
			$status=1;
			break;
	}

	if($status == 0 || $status == 2){
		$ret['resultCode'] = "2";//成功
		echo $msg;
		
		$file = "/tmp/pay6.log";
		@file_put_contents($file, "{$curtime}:{$order_id}#{$msg}\n",FILE_APPEND);
		
		exit;
	}else{
		$ret['resultCode'] = "-2";
		$ret['msg'] = "上分失败";
		
		$file = "/tmp/pay6.log";
		@file_put_contents($file, "{$curtime}:{$order_id}上分失败\n",FILE_APPEND);
		
		echo json_encode($ret);
		exit;
	}
}else{
	$ret['resultCode'] = "-1";
	$ret['msg'] = "回调参数错误";
	
	$file = "/tmp/pay6.log";
	@file_put_contents($file, "{$curtime}:回调参数错误:".json_encode($postDataArr)."\n",FILE_APPEND);
	
	echo json_encode($ret);
	exit;
}






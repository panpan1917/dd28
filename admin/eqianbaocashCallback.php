<?php
/*********************/
/*                   */
/*  Version : 1.0  */
/*  Author  : XMB     */
/*  Comment : 07-04-08 19:33 */
/*                   */
/*********************/

include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
include_once( dirname( __FILE__ )."/inc/payment.php" );


$postData = file_get_contents("php://input");
$postDataArr = json_decode($postData , true);
    	
$params['orderNum'] = $postDataArr['orderNum'];
$params['drawAmount'] = $postDataArr['drawAmount'];
$params['drawFee'] = $postDataArr['drawFee'];
$params['tradeFee'] = $postDataArr['tradeFee'];
$params['drawTime'] = $postDataArr['drawTime'];
$params['respType'] = $postDataArr['respType'];
$params['resultCode'] = $postDataArr['resultCode'];
$params['resultMsg'] = $postDataArr['resultMsg'];
$sign = $postDataArr['sign'];

$payment = new payment();
$curSign = $payment->verSign($params);
if($params['resultCode']==0 && $sign == $curSign && !empty($curSign)){
	$attachArr = explode("_", $postDataArr['orderNum']);
	$orderid = $attachArr[1];
	
	$sql = "update eqianbao_cashlog set 
				amount='{$params['drawAmount']}',
				fee='{$params['drawFee']}',
				tradefee='{$params['tradeFee']}',
				cashtime='{$params['drawTime']}',
				status='2'
				where ordernum='{$orderid}'";
	$res = $db->query($sql);
	if($res){
		$ret['resultCode'] = "2";//成功
		echo json_encode($ret);
		exit;
	}else{
		$ret['resultCode'] = "-2";//失败
		echo json_encode($ret);
		exit;
	}
}else{
    		$ret['resultCode'] = "-1";
    		$ret['msg'] = "回调参数错误";
    		echo json_encode($ret);
    		exit;
}
?>


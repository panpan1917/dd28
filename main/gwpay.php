<?php 
session_start();
header ( 'Content-type:text/html;charset=utf-8' );
define('KKINC', str_replace("\\", '/', dirname(__FILE__)));
include_once '../core/gwpay/func/secureUtil.php';

// 初始化日志
$log = new PhpLog ( SDK_LOG_FILE_PATH, "PRC", SDK_LOG_LEVEL );
$log->LogInfo ( "===========处理支付请求开始============" );

//加密敏感数据
if (!empty($_REQUEST['payer'])) {
	$buyerName = $_REQUEST ['payer']; // 买家姓名
	$buyerName = encryptData($buyerName);
	$postdata['buyerName'] = $buyerName;
	
	if(!empty($_REQUEST['contact'])){
		$contact = $_REQUEST['contact']; //买家联系方式
		$contact = encryptData($contact);
		$postdata['contact'] = $contact;
	}else{
		$postdata['contact'] = encryptData($_SESSION["username"]);
	}
} else {
	echo '买家姓名为空';
	return;
}

$postdata['merchantNo'] = MERCHANTNO;
$postdata['version'] = VERSION;
$postdata['channelNo'] = CHANNELNO;
$postdata['tranTime'] = date("YmdHis");
$postdata['currency'] = "CNY";
$postdata['tranSerialNum'] = $_REQUEST['orderid'];
/*
03010000  交通银行
01030000  农业银行
03030000  光大银行
04012902  上海银行
01040000  中国银行
03100000  浦发银行
03050001  民生银行
05105840  平安银行
03080000  招商银行
01059999  建设银行
01020000  工商银行
63020000  中信银行
03060000  广发银行
04031000  北京银行
03090000  兴业银行
63040000  华夏银行
04243010  南京银行
*/
//$postdata['bankId'] = "03080000";//招商银行
$postdata['amount'] = $_REQUEST['money'] * 100;
$postdata['bizType'] = "14900";//其他费用
$postdata['goodsName'] = "滴滴点卡";
$postdata['goodsInfo'] = "滴滴点卡";
$postdata['goodsNum'] = $_REQUEST['money'] * 1000;
$postdata['notifyUrl'] = "http://" . $_SERVER['HTTP_HOST'] . "/gwnotify.php";
$postdata['returnUrl'] = "http://" . $_SERVER['HTTP_HOST'] . "/gwreturn.php";
$postdata['buyerId'] = $_POST['uid'];
//$postdata['cardType'] = "04";//02贷记卡  01借记卡 04混合
$postdata['ip'] = get_ip();
$postdata['valid'] = 30;
$postdata['remark'] = "";
$postdata['YUL1'] = "";
$postdata['referer'] = "http://" . $_SERVER['HTTP_HOST'] . "/gwpay.php";

//echo "<pre>";
//print_r($postdata);exit;

//签名 
sign($postdata);

$html = create_html($postdata, IELPM_PAY_URL);

echo $html;


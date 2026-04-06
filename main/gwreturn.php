<?php 
header ( 'Content-type:text/html;charset=utf-8' );
include_once '../core/gwpay/func/secureUtil.php';

// 初始化日志
$log = new PhpLog ( SDK_LOG_FILE_PATH, "PRC", SDK_LOG_LEVEL );
$log->LogInfo ( "===========处理支付前台通知开始============" );

$paramStr = createLinkString($_POST);
$log->LogInfo ( "===========处理支付前台通知:" . $paramStr );

// 验签
$flag = verify($_POST);

if($flag){
	$log->LogInfo ( "处理支付前台通知验签成功，可继续后续业务");
	$rtnCode = $_POST['rtnCode'];
	$tranSerialNum = $_POST['tranSerialNum'];
	
	//echo $paramStr;
	echo "<meta http-equiv=\"Content-Type\"	content=\"text/html; charset=utf-8\" />";
	echo "<script type='text/javascript'>";
	if($rtnCode == "000"){
		echo "alert('支付成功');window.location = '/member.php';";
	}else{
		echo "alert('支付失败');window.location = '/member.php';";
	}
	echo "</script>";
}else{
	$log->LogInfo ( "处理支付前台通知验签失败");
}

$log->LogInfo ( "===========处理支付前台通知结束============" );



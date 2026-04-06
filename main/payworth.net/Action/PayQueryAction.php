<?php
require_once '../Config/init.php';

$order_no = isset($_POST["order_no"])? trim($_POST["order_no"]):"";//订单号
$trade_no = isset($_POST["trade_no"])? trim($_POST["trade_no"]):"";//系统交易号，order_target_id

$DataContentParms =ARRAY();
$DataContentParms["merchant_ID"] = $GLOBALS["merchant_ID"];//合作伙伴在华势的用户ID
$DataContentParms["charset"] = $GLOBALS["charset"];//编码字符集
$DataContentParms["return_type"] = "xml";//返回类型(XML/JSON)
$DataContentParms["order_no"] = "YK28ORD" . $order_no;//商户订单号
$DataContentParms["trade_no"] = $trade_no;//系统交易号

$Md5str = Util::GetMd5str($DataContentParms,$GLOBALS["key"]);
Log::LogWirte("MD5验签值：".$Md5str);
$DataContentParms["sign_type"] = $GLOBALS["sign_type"];//签名方式  
$DataContentParms["sign"] = $Md5str;//签名

$HtmlStr = HttpClient::Post($DataContentParms, $GLOBALS["Query_url"]);
Log::LogWirte("查询返回结果：".$HtmlStr);
echo $HtmlStr;

<?php
require_once '../Config/init.php';

$defaultbank = isset($_POST["defaultbank"])? trim($_POST["defaultbank"]):"";//银行编码.当支付方式为bankPay时，该值为空;支付方式为directPay时必输

$total_fee = (int)$_POST["total_fee"];//订单金额
$order_no = get_orderno();
//$order_no = isset($_POST["order_no"])? trim($_POST["order_no"]):"";//订单号
//TODO 根据订单号查出金额等信息


$DataContentParms =ARRAY();
$DataContentParms["service"] = $GLOBALS["service"];
$DataContentParms["merchant_ID"] = $GLOBALS["merchant_ID"];//合作伙伴在华势的用户ID
$DataContentParms["notify_url"] = $GLOBALS["notify_url"];//针对该交易的交易状态同步通知接收URL。（URL里丌要带参数）
$DataContentParms["return_url"] = $GLOBALS["return_url"];//结果返回URL，仅适用亍立即返回处理结果的接口。华势处理完请求后，立即将处理结果返回给这个URL。（URL里丌要带参数）

$DataContentParms["charset"] = $GLOBALS["charset"];//编码字符集
$DataContentParms["title"] = "滴滴游戏点卡";//商品的名称
$DataContentParms["body"] = "滴滴游戏点卡:{$total_fee}元";//商品的具体描述
$DataContentParms["order_no"] = "YK28ORD" . $order_no;//商户订单号（确保在合作伙伴系统中唯一）
$DataContentParms["total_fee"] = $total_fee;//订单金额

$DataContentParms["payment_type"] = "1";//当前默认值为1；
$DataContentParms["paymethod"] = "directPay";//固定值directPay，直连模式
$DataContentParms["defaultbank"] = $defaultbank;

$DataContentParms["seller_email"] = "game211@126.com";//卖家在华势的注册Email.
$DataContentParms["isApp"] = "";//固定值： 值为"app",表示app接入； 值为空，表示web接入
$DataContentParms["buyer_email"] = "";//买家在华势的注册Email       

$Md5str = Util::GetMd5str($DataContentParms,$GLOBALS["key"]);
Log::LogWirte("MD5验签值：".$Md5str);

$DataContentParms["sign_type"] = $GLOBALS["sign_type"];//签名方式  
$DataContentParms["sign"] = $Md5str;//签名

$HtmlStr = HttpClient::Html($GLOBALS["Pay_url"], $DataContentParms);
//$HtmlStr = HttpClient::Post($DataContentParms,$GLOBALS["Pay_url"]);
Log::LogWirte("FROM表单跳转：".$HtmlStr);
echo $HtmlStr;











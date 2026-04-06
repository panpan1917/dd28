<?php
require_once '../Config/init.php';
require_once '../Function/AgentPayDecryption.php';

$order_no = isset($_POST["order_no"])? trim($_POST["order_no"]):"";//订单号
$submit_date = date("Ymd" , strtotime(isset($_POST["add_time"])? trim($_POST["add_time"]):""));//订单提交日期

$order_no = "2017062220480725338";
$submit_date = "20170622";


$DataContentParms =ARRAY();
$DataContentParms["batchBizid"] = $GLOBALS["merchant_ID"];//合作伙伴在华势的用户ID
$DataContentParms["_input_charset"] = $GLOBALS["_input_charset"];//编码字符集
$DataContentParms["batchVersion"] = "00";//版本号
$DataContentParms["batchCurrnum"] = "YK28ORD" . $order_no;//批次号,订单号代替
$DataContentParms["batchDate"] = $submit_date;
$DataContentParms["sign_type"] = $GLOBALS["sign_type"];//签名方式

$Md5str = Util::GetMd5str($DataContentParms,$GLOBALS["key"]);
Log::LogWirte("MD5验签值：".$Md5str);
$DataContentParms["sign"] = $Md5str;//签名

//echo "<pre>";
//print_r($DataContentParms);

$HtmlStr = HttpClient::Post($DataContentParms, $GLOBALS["AgentPayQuery_url"]);
Log::LogWirte("查询返回结果：".$HtmlStr);
//echo $HtmlStr;
//echo "<br>";
$decryption = new AgentPayDecryption();
$HtmlStr = $decryption->decrypt($HtmlStr);
echo $HtmlStr;

/* <Resp>
<status>fail</status>
<reason>商户号不能为空</reason>
</Resp> */
//TODO 根据返回结果判断是否代付成功，处理进一步的逻辑



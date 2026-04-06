<?php
require_once '../Config/init.php';


$total_fee = 10.00;//订单金额
$order_no = get_orderno();


//$order_no = isset($_POST["order_no"])? trim($_POST["order_no"]):"";//订单号
//TODO 根据订单号查出金额等信息


$DataContentParms =ARRAY();
$DataContentParms["_input_charset"] = $GLOBALS["_input_charset"];//输入编码
$DataContentParms["batchBizid"] = $GLOBALS["merchant_ID"];//合作伙伴在华势的用户ID
$DataContentParms["batchBiztype"] = "00000";//提交批次类型
$DataContentParms["batchDate"] = date("Ymd");//提交日期
$DataContentParms["batchVersion"] = "00";//版本号
$DataContentParms["batchCurrnum"] = "YK28ORD" . $order_no;//批次号,订单号代替
$DataContentParms["batchCount"] = "1";//总笔数
$DataContentParms["batchAmount"] = $total_fee;//总金额
$DataContentParms["batchContent"] = "1,6225880187459876,小明,中国工商银行,北京分行,朝阳支行,私,10.00,CNY,北京,北京,13654789876,身份证,123456789012345678,201706001,".$DataContentParms["batchCurrnum"].",工资";
$pu_key = file_get_contents("../key/tomcat.cer");
$DataContentParms["batchContent"] = RsaUtils::pubkeyEncrypt($DataContentParms["batchContent"] , $pu_key);//rsa加密内容

$Md5str = Util::GetMd5str($DataContentParms,$GLOBALS["key"]);
Log::LogWirte("MD5验签值：".$Md5str);
$DataContentParms["sign_type"] = $GLOBALS["sign_type"];//签名方式  
$DataContentParms["sign"] = $Md5str;//签名

//echo "<pre>";
//print_r($DataContentParms);

$HtmlStr = HttpClient::Post($DataContentParms,$GLOBALS["AgentPay_url"]);
Log::LogWirte("Response：".$HtmlStr);

echo $HtmlStr;//"<Resp><status>succ</status></Resp>"

//TODO 根据返回结果判断是否代付成功，处理进一步的逻辑























<?php
\header("Content-type: text/html; charset=utf-8"); 
$path = $_SERVER['DOCUMENT_ROOT']."/payworth.net";
//====================配置商户的宝付接口授权参数============================
require_once($path."/Function/Log.php");
require_once($path."/Function/HttpClient.php");
require_once($path."/Function/Util.php");
require_once($path."/Function/RsaUtil.php");

Log::LogWirte("=================网关支付=====================");
//====================配置商户的宝付接口授权参数==============

$service = "online_pay";//online_pay，表示网上支付；
$merchant_ID = "100000000001986";	//合作伙伴在华势的用户ID
$key = "95ff8e3b2ff06eb4f894e46fb028ccedc8d2294e068632e810c10bg6adgegg05";	//终端号
$charset = "UTF-8";//字符集
$_input_charset = "utf8";//输入参数字符集
$sign_type = "MD5";//签名方式
$notify_url = "http://develop.didi8888.com/payworth.net/Action/NotifyAction.php";//页面跳转地址
$return_url = "http://develop.didi8888.com/payworth.net/Action/ReturnAction.php";//服务器跳转地址
$Pay_url = "https://ebank.payworth.net/portal";
$Query_url="https://mapi.payworth.net/query/payment";
$AgentPay_url = "https://client.payworth.net/agentpay/pay";
$AgentPayQuery_url = "https://client.payworth.net/agentpay/payquery";

function get_orderno(){//生成时间戳
	return return_time().rand5();	
}
function rand5(){//生成5位随机数
	return rand(10000,99999);
}
function return_time(){//生成时间
	return date('YmdHis',time());
}
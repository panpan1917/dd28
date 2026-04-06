<?php
include_once("inc/conn.php");
include_once("inc/function.php");
include_once("class/Sms.php");
$Sms = new Sms();
$t = isset($_POST['t']) ? str_check($_POST['t']) : "reg";
$mobile = isset($_POST['mobile']) ? str_check($_POST['mobile']) : "";
switch ($t) {
    default:
    	
    	$ip = $Sms->get_ip();
    	$sql = "select ip from forbid_ip";
    	$result = $db->query($sql);
		while ($row = $db->fetch_array($result)) {
    		if(stristr($ip,$row['ip']) !== FALSE){
    			die($Sms->result('您涉嫌恶意注册！',1));
    		}
    	}
    	
        $code = $_POST["code"];
        if(strlen($code) != 4 || $_SESSION['CheckNum'] == '' || $code != $_SESSION["CheckNum"])
        {
            die($Sms->result('验证码错误!',1));
        }
        if ($mobile == "" || !is_numeric($mobile)) {
            die($Sms->result('请输入常用的手机号码', 1));
        }
        $TodayMsgCount = $Sms->count($mobile);
        if ($TodayMsgCount >= 5) {
            die($Sms->result('您今天发送的验证码次数已用完，请明天再试,有疑问请联系客服!', 1));
        }
        if($Sms->is_has_usr($mobile)){
            die($Sms->result('已经注册过了,如果忘记了,请找回',1));
        }
        if ($Sms->send($mobile)) {
        	$_SESSION['mobilesmscode'] = $validcode;
            die($Sms->result('发送成功', 0));
        } else {
            die($Sms->result('短信未能发出，请稍后再试!', 1));
        }
        break;
}
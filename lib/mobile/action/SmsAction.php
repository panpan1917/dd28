<?php

class SmsAction extends BaseAction
{

    function __construct()
    {
        $this->no_login=['index','login'];
        parent::__construct();
    }
    function index(){
        $code = Req::post('code');
        $mobile = Req::post('mobile');
        $checkexist = Req::post('checkexist');
        $checkexist = (int)$checkexist;
        
        $Sms = new sms();
        
        $ip = get_ip();
		$sql = "select ip from forbid_ip";
		$rows = db::get_all($sql,'assoc');
		for($i=0;$i<count($rows);$i++) {
			if(stristr($ip,$rows[$i]['ip']) !== FALSE){
        		return $this->result(1,'您涉嫌恶意注册！');
        	}
        }
        
        if (strlen($code) != 4 || $_SESSION['CheckNum'] == '' || $_SESSION['CheckNum'] != $code) {
            return $this->result(1,'验证码不对');
        }
        if ($mobile == '' || !is_numeric($mobile)) {
            return $this->result( 2,'手机号码不对');
        }
        if ($mobile == "" || !is_numeric($mobile)) {
            return($this->result(1,'请输入常用的手机号码'));
        }
        $TodayMsgCount = $Sms->count($mobile);
        if ($TodayMsgCount >= 5) {
            die($this->result(1,'您今天发送的验证码次数已用完，请明天再试,有疑问请联系客服!'));
        }
        
        if($Sms->is_has_usr($mobile) && $checkexist){
            return $this->result(1,'已经注册过了,如果忘记了,请找回');
        }
        
        if ($ret = $Sms->send($mobile)) {
            die($this->result(0,'发送成功'));
        } else {
            die($this->result(1,$ret));
        }

    }
    
    
    /* 发送手机验证码
   *
   */
    public function ValidCode($mobile)
    {
        $table = 'validcodelog';
        $result = array('status' => '', 'msg' => '');
        $TodayMsgCount = Db::table('validcodelog')->where('code_type = 0 and state = 0 and account = \'' . $mobile . '\' and to_days(add_time) = to_days(now())')->count();
        if ($TodayMsgCount >= 5) {
            $result['status'] = "err";
            $result['msg'] = "您今天发送的验证码次数已用完，请明天再试,有疑问请联系客服!";
            return $result;
        }
        $validcode = rand(1000, 9999);
        $SMS = "您的验证码是：{$validcode}。请不要把验证码泄露给其他人。如非本人操作，可不用理会！";
        $data = [
            'userid' => 0,
            'code_type' => 0,
            'account' => $mobile,
            'content' => $SMS,
            'add_time' => date('Y-m-d H:i:s'),
            'state' => 0
        ];
        Db::table($table)->insert($data);
        $insertID = Db::getLastInsID();
        //发送短信
        $smsReturn = $this->PostSMS($mobile,$SMS);
        $tplid = 135113;
        $smsReturn = $this->SendSMS($mobile, array($validcode , 10), $tplid);
        
        //$smsReturn = 'ok';
        if ($smsReturn == "ok") {
            $result['status'] = "ok";
            $result['msg'] = "发送成功";
            $_SESSION['mobilesmscode'] = $validcode;
        } else {
            $result['status'] = "err";
            $result['msg'] = "短信未能发出，请稍后再试!";
            Db::table($table)->update(['state' => 1, 'err_msg' => $result['msg'], 'id' => $insertID]);
        }

        return $result;
    }

    /* 取得绑定/解绑手机信息
    *
    */
    function GetBindMobileInfo()
    {
        global $db;
        $BindType = str_check($_GET['t']);

        $sql = "select id,mobile from users where id = '{$_SESSION['usersid']}'";
        $result = $db->query($sql);
        if ($rs = $db->fetch_array($result)) {
            $Mobile = $rs['mobile'];
        }
        if (isset($_SESSION['mobilecodecount']))
            unset($_SESSION['mobilecodecount']);
        $RetContent = "<div class='popup'>\r\n";
        //header
        $RetContent .= "\t<div class='popup-header'>\r\n";
        $RetContent .= "\t\t\t<h2>绑定/解绑手机</h2>\r\n";
        $RetContent .= "\t\t\t<a href='javascript:;' onclick='closerecord(-1)' title='关闭' class='close-link'>[关闭]</a>\r\n";
        $RetContent .= "\t\t\t<br clear='both' /> \r\n";
        $RetContent .= "\t</div>";
        //body
        $RetContent .= "\t<div class='popup-body'>\r\n";
        $RetContent .= "\t\t<div class='table'>\r\n";
        $RetContent .= "\t\t\t<table class='table_list' cellspacing='0px' style='border-collapse:collapse;border:1px;width:500;height:400;'>\r\n";
        if ($BindType == "bind")
            $RetContent .= "\t\t\t\t<tr><td width=100>手机</td><td width=350 style='text-align:left;'><input id='txtBindMobile' value='{$Mobile}' maxlength=11 >11位手机号码</td>";
        else
            $RetContent .= "\t\t\t\t<tr><td width=100>手机</td><td width=350 style='text-align:left;'><input id='txtBindMobile' value='{$Mobile}' maxlength=11 disabled='disabled'></td>";
        $RetContent .= "\t\t\t\t<tr><td>验证码</td><td style='text-align:left;'><input type='text' style='width:50px' id='txtMobileValidCode' maxlength=4><input type='button' id='btnGetMobileValid' style='width:200' value='获取验证码' /><label id='lblBindtype' style='display:none'>{$BindType}</label></td></tr>\r\n";
        if ($BindType == "bind")
            $RetContent .= "\t\t\t\t<tr><td></td><td><input type='button' id='btnBindMobile' style='width:100' value='马上绑定' /></td></tr>\r\n";
        else
            $RetContent .= "\t\t\t\t<tr><td></td><td><input type='button' id='btnUnBindMobile' style='width:100' value='解绑' /></td></tr>\r\n";

        $RetContent .= "\t\t\t</table>\r\n";

        $RetContent .= "\t\t</div>\r\n";
        $RetContent .= "\t</div>";

        echo $RetContent;
        exit;

    }
    
    
    private function curl_post($url, $data, $header, $post = 1) {
    	//初始化curl
    	$ch = curl_init();
    	//参数设置
    	$res = curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	curl_setopt($ch, CURLOPT_POST, $post);
    	if ($post)
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    	$result = curl_exec($ch);
    	//连接失败
    	if ($result == FALSE) {
    		$result = "{\"statusCode\":\"172001\",\"statusMsg\":\"internet error\"}";
    	}
    
    	curl_close($ch);
    	return $result;
    }
    
    private function SendSMS($to, $datas=array(), $tempId = 135113) {
    	$accountSid = '8a216da858867fd701588a0b4529016b';
    	$accountToken = '45e837bc23d6420897e7e8a40fd23e56';
    	$appId = '8a216da858867fd701588a0b47330172';
    	$serverIP = 'app.cloopen.com'; //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com 生产环境（用户应用上线使用）：app.cloopen.com
    	$serverPort = '8883';
    	$softVersion = '2013-12-26';
    	$Batch = date("YmdHis");
    	$BodyType = 'json';
    
    	$data = "";
    	for ($i = 0; $i < count($datas); $i++) {
    		$data = $data . "'" . $datas[$i] . "',";
    	}
    	$body = "{'to':'$to','templateId':'$tempId','appId':'$appId','datas':[" . $data . "]}";
    	$sig = strtoupper(md5($accountSid . $accountToken . $Batch));
    	$url = "https://" . $serverIP . ":" . $serverPort . "/" . $softVersion . "/Accounts/" . $accountSid . "/SMS/TemplateSMS?sig=" . $sig;
    	$authen = base64_encode($accountSid . ":" . $Batch);
    	$header = array("Accept:application/$BodyType", "Content-Type:application/$BodyType;charset=utf-8", "Authorization:$authen");
    	$result = curl_post($url, $body, $header);
    	if ($BodyType == "json") {//JSON格式
    		$datas = json_decode($result);
    	} else { //xml格式
    		$datas = simplexml_load_string(trim($result, " \t\n\r"));
    	}
    
    	if ($datas->statusCode == '000000') {
    		return 'ok';
    	}
    	return false;
    }

    private function PostSMS($mobile, $content)
    {
    	$url="http://utf8.sms.webchinese.cn/?Uid=vichiba&Key=cbbfe5a1da58cdf1ff2e&smsMob=$mobile&smsText=$content";
    	$str=file_get_contents($url);
    	$num=intval($str);
    	if($num>0){
    		return 'ok';
    	}elseif($num==-4||$num==-41){
    		return '手机号格式不正确';
    	}elseif($num==-42){
    		return '短信内容为空';
    	}else{
    		return "错误$num";
    	}
    }


}
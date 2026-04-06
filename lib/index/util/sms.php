<?php
if (!defined('KKINC'))	 exit('Request Error!') ;
class sms
{
	//创蓝发送短信接口URL, 如无必要，该参数可不用修改
	const API_SEND_URL='http://sms.253.com/msg/send';
	
	//创蓝短信余额查询接口URL, 如无必要，该参数可不用修改
	const API_BALANCE_QUERY_URL='http://sms.253.com/msg/balance';
	
	const API_ACCOUNT='N9879459';//创蓝账号 替换成你自己的账号
	
	const API_PASSWORD='Thgj17081708';//创蓝密码 替换成你自己的密码
	
	/**
	 * 发送短信
	 *
	 * @param string $mobile 		手机号码
	 * @param string $msg 			短信内容
	 * @param string $needstatus 	是否需要状态报告
	 */
	public function sendSMS( $mobile, $msg, $needstatus = 1) {
	
		//创蓝接口参数
		$postArr = array (
				'un' => self::API_ACCOUNT,
				'pw' => self::API_PASSWORD,
				'msg' => $msg,
				'phone' => $mobile,
				'rd' => $needstatus
		);
	
		$result = $this->curlPost( self::API_SEND_URL , $postArr);
		return $result;
	}
	
	/**
	 * 查询额度
	 *
	 *  查询地址
	 */
	public function queryBalance() {
	
		//查询参数
		$postArr = array (
				'un' => self::API_ACCOUNT,
				'pw' => self::API_PASSWORD,
		);
		$result = $this->curlPost(self::API_BALANCE_QUERY_URL, $postArr);
		return $result;
	}
	
	/**
	 * 处理返回值
	 *
	 */
	public function execResult($result){
		$result=preg_split("/[,\r\n]/",$result);
		return $result;
	}
	
	/**
	 * 通过CURL发送HTTP请求
	 * @param string $url  //请求URL
	 * @param array $postFields //请求参数
	 * @return mixed
	 */
	private function curlPost($url,$postFields){
		$postFields = http_build_query($postFields);
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postFields );
		$result = curl_exec ( $ch );
		curl_close ( $ch );
		return $result;
	}
	
	//魔术获取
	public function __get($name){
		return $this->$name;
	}
	
	//魔术设置
	public function __set($name,$value){
		$this->$name=$value;
	}
	   

    function count($mobile){
        $sql = "select count(*) from validcodelog where code_type = 0 and state = 0 and account = '{$mobile}' and to_days(add_time) = to_days(now())";
        return $total=db::get_total($sql);
    }
    function get_code($mobile){
        $sql = "select id,code,verifytimes from validcodelog where code_type = 0 and state = 0 and account = '{$mobile}' and to_days(add_time) = to_days(now()) order by id desc limit 1";
        $row=db::get_one($sql,'assoc');
        return $row;//$row['code'];
    }
    function cumulate_verifytimes($id , $times=1){
    	$sql = "update validcodelog set verifytimes=verifytimes+{$times} where id={$id}";
    	return db::_query($sql);
    }
    function is_has_usr($username){
        $sql="select id from users where username='$username'";
        $row=db::get_one($sql,'assoc');
        return $row['id'];
    }
    function send($mobile){
    	$validcode = rand(1000,9999);
    	//$SMS =  "【滴滴网】您的验证码是：{$validcode}，请不要把验证码泄露给其他人。如非本人操作，可不用理会！";
    	$SMS =  "您的验证码是：{$validcode}。请不要把验证码泄露给其他人。如非本人操作，可不用理会！";
    	$sM = ($mobile);
    	$sC = ($SMS);
    	$sql = "insert into validcodelog(userid,code_type,account,content,add_time,state,code)
    	values(0,0,'{$sM}','{$sC}',now(),0,{$validcode})";
    	$result =  db::_query($sql);
    	$insertID = db::last_id();
    	 
    	//$smsReturn = $this->PostSMS($mobile,$SMS);
    	$smsReturn = $this->sendSMS($mobile,$SMS);
    	$smsReturn = $this->execResult($smsReturn);
    	if($smsReturn[1] == "0")
    	{
    		$_SESSION['mobilesmscode'] = $validcode;
    		return $validcode;
    	}
    	else
    	{
    		$sR = ($this->statusStr[$smsReturn[1]]);
    		$sql = "update validcodelog set state = 1,err_msg='{$sR}' where id = {$insertID}";
    		$result =  db::_query($sql);
    		return false;
    	}
    	return false;
    	
    }
    
    function curl_post($url, $data, $header, $post = 1) {
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
    
    function SendSMS2($to, $datas=array(), $tempId = 135113) {
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
    	$result = $this->curl_post($url, $body, $header);
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
    
    
function PostSMS($mob,$msg){
        $url="http://utf8.sms.webchinese.cn/?Uid=vichiba&Key=cbbfe5a1da58cdf1ff2e&smsMob=$mob&smsText=$msg";
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
/*
-1  没有该用户账户
-2  接口密钥不正确 [查看密钥]
不是账户登录密码
-21 MD5接口密钥加密不正确
-3  短信数量不足
-11 该用户被禁用
-14 短信内容出现非法字符
-4  手机号格式不正确
-41 手机号码为空
-42 短信内容为空
-51 短信签名格式不正确
接口签名格式为：【签名内容】
-6  IP限制
大于0 短信发送数量
*/
    }

    function Post($curlPost,$url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }

}
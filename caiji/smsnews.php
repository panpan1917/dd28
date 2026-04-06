<?php
set_time_limit(0);
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL ^ E_NOTICE);

include_once(dirname( __FILE__ ) ."/Mysql.class.php");


class Sms
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
}


$Sms = new Sms();

$db = new db();
$sql = "SELECT mobile FROM users WHERE usertype=0 AND dj=0 AND logintime <= '2017-06-01'";
//$sql = "SELECT mobile FROM users WHERE usertype=0 AND dj=0";
$users = $db->getAll($sql);
foreach($users as $user){
	echo $mobile = trim($user['mobile']);
	echo "\n";
	
	if(!empty($mobile)){
		$Sms->sendSMS($mobile , "中秋佳节，圆月皎洁，短信不缺，祝福跳跃，快乐如雪，纷飞不歇，忧愁全解，烦恼逃曳，好运真切，幸福的确，滴滴祝：中秋快乐!");
		//$Sms->sendSMS($mobile , "工作的繁忙，不代表遗忘;夏日的到来，愿你心情凉爽;小小短信，穿街走巷，带着我最深的祝福，直达你心上：端午节快乐!幸福永安康!");
	}
}










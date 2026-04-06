<?php 
$key="LV68PMJ7GZF3JSCUCYDOO9425L9U18GV";
/**
 * * MD5签名
 * 请求所有参数 正序ASC排列，拼接，加上商户key字段 MD5加密
 * @param $data
 * @param $key
 * @return string
 */
function sign($data, $mch_key)
{
	//解码
	foreach ($data as $key => &$value) {
		$value = urldecode($value);
	}
	unset($value);
	if (isset($data['sign'])) {
		unset($data['sign']);
	}

	//数组正序排列
	ksort($data);
	//拼接
	$params_str = urldecode(http_build_query($data));
	//拼接商户密钥在最后面
	$params_str = $params_str.'&key='.$mch_key;
	//返回MD5结果
	return md5($params_str);
}

/**
 * curl请求
 * @param $url
 * @param array $data
 * @return mixed
 */
function curl_post($url, $data = [])
{
	//初始化
	$curl = curl_init();
	//设置抓取的url
	curl_setopt($curl, CURLOPT_URL, $url);
	//设置头文件的信息作为数据流输出
	curl_setopt($curl, CURLOPT_HEADER, 0);
	//设置获取的信息以文件流的形式返回，而不是直接输出
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	//设置post方式提交
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	//执行命令
	$result = curl_exec($curl);
	//关闭url请求
	curl_close($curl);
	return $result;
}

$content = array(
	"goods" => "用户充值",
	"total_fee" => "1500",
	"order_sn" => "21312".time(),
	"pay_type" => "1",
	"user_id" => "8088258",
	"return_url" => "http://wanrong.online/pay/returnURL.do",
	"notify_url" => "http://wanrong.online/pay/notifyURL.do"
);
//echo json_encode($content);
$data = array(
	'mch_id' => "18408",
	'method' => "shop.payment.transferPay",
	'version' => '1.0',
	'timestamp' => time().'000',
	'content' => json_encode($content)
);
	
$signs = sign($data, $key);

$data["sign"] = $signs;

echo '<meta charset="utf-8" />';
echo curl_post("http://wanrong.online/pay/transferPayH5.do", $data);

?>
<?php
class payment
{
	//万融支付
	private $merchantId = "18408";
	public $apiKey = "LV68PMJ7GZF3JSCUCYDOO9425L9U18GV";
	public $pay_type;
	public $bankCode;
	
	private $paymentUrl = "http://wanrong.online/pay/transferPayH5.do";//充值支付
	private $PayCallBackUrl = "";
	private $PayReturnUrl = "http://www.didi5188.com";
	
	private $checkChargeUrl = "http://wanrong.online/pay/transferPayH5.do";
	
    public function __construct() {
        $this->PayCallBackUrl = "http://www.didi5188.com/chargecallback1.php";
        
        $this->account[6040]['pay_type'] = "1";//支付宝
        $this->account[6041]['pay_type'] = "2";//微信
        
        
        /* $this->account[0]['pay_type'] = "0";//快捷
        $bankCode[2000] = "01020000";//	工商银行
        $bankCode[2001] = "01050000";//	建设银行
        $bankCode[2002] = "01030000";//	农业银行
        $bankCode[2003] = "03080000";//	招商银行
        $bankCode[2004] = "03010000";//	交通银行
        $bankCode[2005] = "01040000";//	中国银行
        $bankCode[2006] = "03030000";//	光大银行
        $bankCode[2007] = "03050000";//	民生银行
        $bankCode[2008] = "03090000";//	兴业银行
        $bankCode[2009] = "03020000";//	中信银行
        $bankCode[2010] = "03060000";//	广发银行
        $bankCode[2011] = "03100000";//	浦发银行
        $bankCode[2012] = "03070000";//	平安银行
        $bankCode[2013] = "03040000";//	华夏银行
        $bankCode[2014] = "04083320";//	宁波银行
        $bankCode[2015] = "03200000";//	东亚银行
        $bankCode[2016] = "04012900";//	上海银行
        $bankCode[2017] = "01000000";//	中国邮储银行
        $bankCode[2018] = "04243010";//	南京银行
        $bankCode[2019] = "65012900";//	上海农商行
        $bankCode[2020] = "03170000";//	渤海银行
        $bankCode[2021] = "64296510";//	成都银行
        $bankCode[2022] = "04031000";//	北京银行
        $bankCode[2023] = "64296511";//	徽商银行
        $bankCode[2024] = "04341101";//	天津银行
        
        $this->bankCode = $bankCode; */
    }
    
    public function setAccount($idx){
    	$this->pay_type = $this->account[$idx]['pay_type'];
    }
	
	public function setPayCallBackUrl($url){
		$this->PayCallBackUrl = $url;
	} 
	
	public function setPayReturnUrl($url){
		$this->PayReturnUrl = $url;
	}
	
	
	public function checkChargeRequest($OrderNo , $paynum=''){
		date_default_timezone_set('Asia/Shanghai');
		
		$params['merchantNo'] = $this->merchantId;
		$params['orgNo'] = $this->merchantId;
		$params['version'] = "v1";
		$params['channelNo'] = "M002";
		$params['tranCode'] = "YS2002";
		$params['tranSerialNumY'] = $this->merchantId.$OrderNo;
		
		$sign = $this->verSign($params);
		
		$params['sign'] = $sign;
		//$params['apiKey'] = $this->apiKey;
		
		/* $file = "/tmp/pay1.log";
		$params_json = json_encode($params , JSON_UNESCAPED_UNICODE);
		@file_put_contents($file, $params_json,FILE_APPEND); */
		
		$result = $this->curlPost($this->checkChargeUrl, $params);
		
		/* $file = "/tmp/pay1.log";
		file_put_contents($file, $result,FILE_APPEND); */
		
		$return_data = json_decode($result, true);
		return $return_data;
		//rtnCode ==0000 && status ==1 && rtnCodeY == 0000  成功
	}
	
	public function payRequest($OrderNo , $Amount , $buyerId){
		date_default_timezone_set('Asia/Shanghai');
		
		$content = array(
				"goods" => "didi_score",
				"total_fee" => $Amount,
				"order_sn" => $OrderNo,
				"pay_type" => $this->pay_type,
				"user_id" => $buyerId,
				"return_url" => $this->PayReturnUrl,
				"notify_url" => $this->PayCallBackUrl
		);
		
		$data = array(
				'mch_id' => $this->merchantId,
				'method' => "shop.payment.transferPay",
				'version' => '1.0',
				'timestamp' => time().'000',
				'qrCode' => '1',
				'content' => json_encode($content)
		);
		
		$sign = $this->verSign($data);
		$data["sign"] = $sign;
		
		//将所有参数urlencode编码，防止中文乱码
		/* foreach ($data as &$item) {
			$item = urlencode($item);
		}
		unset($item); */
		
		//print_r($data);exit;
		/* $file = "/tmp/pay1.log";
		$data_json = json_encode($data , JSON_UNESCAPED_UNICODE);
		@file_put_contents($file, $data_json,FILE_APPEND); */
		
		$result = $this->curlPost($this->paymentUrl, $data);
		
		/* $file = "/tmp/pay1.log";
		$data_json = json_encode($data , JSON_UNESCAPED_UNICODE);
		@file_put_contents($file, $result,FILE_APPEND); */
		
		$result = json_decode($result, true);
		
		
		
		return $result;
	}
	
	
	
	public function quickPayRequest($OrderNo , $Amount , $buyerId,$bankCode,$ip='127.0.0.1'){
		date_default_timezone_set('Asia/Shanghai');
		$params['goods'] = "didi_score";
		$params['total_fee'] = $Amount;
		$params['order_sn'] = $OrderNo;
		$params['client'] = "wap";
		$params['user_id'] = $buyerId;
		$params['bank_code'] = $bankCode;
		$params['client_ip'] = $ip;
		$params['return_url'] = $this->PayReturnUrl;
		$params['notify_url'] = $this->PayCallBackUrl;
		
		
		$data = [
		'mch_id'    => $this->merchantId,
		'method'    => "shop.payment.unQuickpay",
		'version'   => '1.0',
		'timestamp' => time().'000',
		'content'   => json_encode($params)
		];
		
		
		
		$sign = $this->verSign($data);
		$data['sign'] = $sign;
		
		//将所有参数urlencode编码，防止中文乱码
		/* foreach ($data as &$item) {
			$item = urlencode($item);
		}
		unset($item); */
		
		
		//print_r($data);exit;
		/* $file = "/tmp/pay1.log";
		$data_json = json_encode($data , JSON_UNESCAPED_UNICODE);
		@file_put_contents($file, $data_json,FILE_APPEND); */
		
		$result = $this->curlPost($this->paymentUrl, $data);
		$result = json_decode($result, true);
		
		return $result;
	}
	
	
	
	public function verSign($data){
	    //解码
	    foreach ($data as $key => &$value) {
	    	$value = urldecode($value);
	    }
	    unset($value);
	    if (isset($data['sign'])) {
	    	unset($data['sign']);
	    }
	    if (isset($data['qrCode'])) {
	    	unset($data['qrCode']);
	    }
	    
	    
	    //数组正序排列
	    ksort($data);
	    //拼接
	    $params_str = urldecode(http_build_query($data));
	    //拼接商户密钥在最后面
	    $params_str = $params_str.'&key='.$this->apiKey;
	    
	    //$file = "/tmp/pay1.log";
	    //@file_put_contents($file, $params_str."###".md5($params_str)."###",FILE_APPEND);
	    
	    //返回MD5结果
	    return md5($params_str);
	}
	
	
	private function curlPost($url,$data=[]){
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
	
	
}





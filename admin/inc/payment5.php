<?php
class payment
{
	private $merchantId = "000003";//000004
	public $apiKey = "AE96C6F2D2914F8ABC0B88D99CFB863D";//02E4965194E9432289E198160BE97FC8
	public $pay_type;
	
	private $paymentUrl = "http://tranpay.wangliany.com/tranpay/tran/qrFastPay";//充值支付
	private $PayCallBackUrl = "";
	private $PayReturnUrl = "http://www.didi5188.com";
	
	private $checkChargeUrl = "http://tranpay.wangliany.com/tranpay/tran/qrFastPayQuery";
	
    public function __construct() {
        $this->PayCallBackUrl = "http://www.didi5188.com/chargecallback5.php";//"http://" . $_SERVER['HTTP_HOST'] . "/chargecallback.php";
        
        $this->account[8301]['pay_type'] = "0301";//快捷（收银台）8301
        $this->account[8302]['pay_type'] = "0302";//快捷（API直连）8302
        $this->account[8304]['pay_type'] = "0304";//快捷WAP（直连）8304
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
		
		/* $file = "/tmp/pay5.log";
		$params_json = json_encode($params , JSON_UNESCAPED_UNICODE);
		@file_put_contents($file, $params_json,FILE_APPEND); */
		
		$result = $this->curlPost($this->checkChargeUrl, $params);
		
		/* $file = "/tmp/pay5.log";
		file_put_contents($file, $result,FILE_APPEND); */
		
		$return_data = json_decode($result, true);
		return $return_data;
		//rtnCode ==0000 && status ==1 && rtnCodeY == 0000  成功
	}
	
	public function payRequest($OrderNo , $Amount , $buyerId=0,$buyerName=''){
		date_default_timezone_set('Asia/Shanghai');
		
		$params['merchantNo'] = $this->merchantId;
		$params['orgNo'] = $this->merchantId;
		$params['version'] = "v1";
		$params['tranFlow'] = $this->merchantId.$OrderNo;
		$params['tranDate'] = Date("Ymd");
		$params['tranTime'] = Date("His");
		$params['amount'] = $Amount*100;
		$params['payType'] = $this->pay_type;
		$params['channelNo'] = "M002";
		$params['notifyUrl'] = $this->PayCallBackUrl;
		$params['goodsName'] = "didi_score";
		$params['buyerName'] = $buyerName;
		$params['buyerId'] = $buyerId;
		$params['remark'] = "didi_score_{$Amount}";
		
		$sign = $this->verSign($params);
		$params['sign'] = $sign;
		
		//print_r($params);
		/* $file = "/tmp/pay5.log";
		$params_json = json_encode($params , JSON_UNESCAPED_UNICODE);
		@file_put_contents($file, $params_json,FILE_APPEND); */
		//print_r($params);
		//result = $this->send_post($this->paymentUrl, $params);
		$output = $this->curlPost($this->paymentUrl, $params);
		parse_str($output, $result);
		//print_r($result);
		
		$result['qrCodeURL'] = @str_replace("|","&",$result['qrCodeURL']);
		
		//print_r($result);
		
		$file = "/tmp/pay5.log";
		file_put_contents($file, json_encode($result)."\n",FILE_APPEND);
		
		return $result;
		//$return_data = json_decode($result, true);
		//return $return_data;
	}
	
	
	
	public function quickPayRequest($OrderNo , $Amount , $buyerId=0,$buyerName=''){
		date_default_timezone_set('Asia/Shanghai');
		
		$params['merchantNo'] = $this->merchantId;
		$params['orgNo'] = $this->merchantId;
		$params['version'] = "v1";
		$params['tranFlow'] = $this->merchantId.$OrderNo;
		$params['tranDate'] = Date("Ymd");
		$params['tranTime'] = Date("His");
		$params['amount'] = $Amount*100;
		$params['payType'] = $this->pay_type;
		$params['channelNo'] = "M002";
		$params['notifyUrl'] = $this->PayCallBackUrl;
		$params['goodsName'] = "didi_score";
		$params['buyerName'] = $buyerName;
		$params['buyerId'] = $buyerId;
		$params['remark'] = "didi_score_{$Amount}";
		
		$sign = $this->verSign($params);
		$params['sign'] = $sign;
		
		//print_r($params);
		$file = "/tmp/pay5.log";
		$params_json = json_encode($params , JSON_UNESCAPED_UNICODE);
		@file_put_contents($file, $params_json,FILE_APPEND);
		//print_r($params);
		//result = $this->send_post($this->paymentUrl, $params);
		$output = $this->curlPost($this->paymentUrl, $params);
		parse_str($output, $result);
		//print_r($result);
		
		//$file = "/tmp/pay5.log";
		//file_put_contents($file, $output."\n",FILE_APPEND);
		
		$result['qrCodeURL'] = @str_replace("|","&",$result['qrCodeURL']);
		
		//print_r($result);
		
		$file = "/tmp/pay5.log";
		file_put_contents($file, json_encode($result)."\n",FILE_APPEND);
		
		return $result;
		//$return_data = json_decode($result, true);
		//return $return_data;
	}
	
	
	
	public function verSign($params){
	    ksort($params);
	    $string = "";
	    foreach ($params as $name => $value) {
	      $string .= $name . '=' . $value . '&';
	    }
	    //$string = substr($string, 0, strlen($string) -1 );
	    $string .= 'key=' . $this->apiKey;
	    return strtoupper(md5($string));
	}
	
	private function send_post($url,$param){
		$postdata = json_encode($param);
		$options = array(
				'http' => array(
						'method' => 'POST',
						'header' => 'Content-type:application/json; charset=utf-8',
						'content' => $postdata,
						'timeout' => 15 * 60 // 超时时间（单位:s）
				)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		return $result;
	}
	
	
	private function curlPost($url,$data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($ch, CURLOPT_TIMEOUT, 25);
		curl_setopt($ch, CURLOPT_HEADER, 0); //不返回header部分
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //返回字符串，而非直接输出
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		
		//curl_setopt($ch, CURLOPT_USERAGENT, '(compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; 360SE)');
		$contents = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if($http_code != 200 || empty($contents)){
			$contents = "";
		}
		
		return $contents;
	}
	
	
}





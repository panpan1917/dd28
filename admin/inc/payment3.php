<?php
class payment
{
	private $merchantId = "";
	public $apiKey = "";
	public $pay_type;
	
	private $paymentUrl = "https://ebank.zhenxiangmall.com";//充值支付
	private $PayCallBackUrl = "";
	private $PayReturnUrl = "http://www.didi5188.com";
	
	private $checkChargeUrl = "https://ebank.zhenxiangmall.com";
	
    public function __construct() {
        $this->PayCallBackUrl = "http://www.didi5188.com/chargecallback3.php";//"http://" . $_SERVER['HTTP_HOST'] . "/chargecallback.php";
        
        $this->account[5005]['pay_type'] = "QQPAY";//QQ钱包
        $this->account[5006]['pay_type'] = "JDPAY";//京东钱包
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
		
		$params['merchantId'] = $this->merchantId;
		$params['orderNo'] = $OrderNo;
		$params['charset'] = "UTF-8";
		
		$sign = $this->verSign($params);
		$params['signType'] = "SHA";
		$params['sign'] = $sign;
		//$params['apiKey'] = $this->apiKey;
		
		/* $file = "/tmp/pay3.log";
		$params_json = json_encode($params , JSON_UNESCAPED_UNICODE);
		@file_put_contents($file, $params_json,FILE_APPEND); */
		
		$result = $this->send_post($this->checkChargeUrl. '/payment/v1/order/' . $params['merchantId'] . '-' . $params['orderNo'], $params);
		
		/* $file = "/tmp/pay3.log";
		file_put_contents($file, $result,FILE_APPEND); */
		
		$return_data = json_decode($result, true);
		return $return_data;
	}
	
	public function payRequest($OrderNo , $Amount){
		date_default_timezone_set('Asia/Shanghai');
		
		$params['service'] = "online_pay";
		$params['paymentType'] = 1;
		$params['merchantId'] = $this->merchantId;
		$params['returnUrl'] = $this->PayReturnUrl;
		$params['notifyUrl'] = $this->PayCallBackUrl;
		$params['orderNo'] = $OrderNo;
		$params['title'] = "didi_score";
		$params['body'] = "didi_score";
		$params['totalFee'] = $Amount;
		$params['paymethod'] = "directPay";
		$params['defaultbank'] = $this->pay_type;
		$params['isApp'] = "app";
		$params['charset'] = "UTF-8";
		
		$sign = $this->verSign($params);
		$params['signType'] = "SHA";
		$params['sign'] = $sign;
		//$params['apiKey'] = $this->apiKey;
		
		
		//$params['notify_url'] = urlencode($params['notify_url']);
		//$params['return_url'] = urlencode($params['return_url']);
		
		
		$file = "/tmp/pay3.log";
		$params_json = json_encode($params , JSON_UNESCAPED_UNICODE);
		@file_put_contents($file, $params_json,FILE_APPEND);
		
		$result = $this->send_post($this->paymentUrl. '/payment/v1/order/' . $params['merchantId'] . '-' . $params['orderNo'], $params);
		
		$file = "/tmp/pay3.log";
		file_put_contents($file, $result,FILE_APPEND);
		
		$return_data = json_decode($result, true);
		return $return_data;
	}
	
	
	
	public function verSign($params){
	    ksort($params);
	    $string = "";
	    foreach ($params as $name => $value) {
	      $string .= $name . '=' . $value . '&';
	    }
	    $string = substr($string, 0, strlen($string) -1 );
	    $string .= $this->apiKey;
	    return strtoupper(sha1($string));
	}
	
	private function send_post($url,$param){
		$postdata = http_build_query($param);
		$options = array(
				'http' => array(
						'method' => 'POST',
						'header' => 'Content-type:application/x-www-form-urlencoded',
						'content' => $postdata,
						'timeout' => 15 * 60 // 超时时间（单位:s）
				)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		return $result;
	}
	
	
}





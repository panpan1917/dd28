<?php
class payment
{
	private $merchantId = "000000230000000001";
	public $apiKey = "5b979d3e950e4da48387df44d488be3f";
	public $pay_type;
	
	private $paymentUrl = "http://mer.jtongpay.com:43251/acquire/acquirePlatform/api/transfer.html";//充值支付
	private $PayCallBackUrl = "";
	private $PayReturnUrl = "http://www.didi5188.com";
	
	private $checkChargeUrl = "http://mer.jtongpay.com:43251/acquire/acquirePlatform/api/transfer.html";
	
    public function __construct() {
        $this->PayCallBackUrl = "http://www.didi5188.com/chargecallback4.php";//"http://" . $_SERVER['HTTP_HOST'] . "/chargecallback.php";
        
        $this->account[9500]['pay_type'] = "alipayQR";//支付宝扫码支付
        $this->account[9501]['pay_type'] = "wxPubQR";//微信扫码支付
        $this->account[9502]['pay_type'] = "alipayH5";//支付宝H5支付
        $this->account[9999]['pay_type'] = "qpay";//快捷支付
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
		
		$params['tradeType'] = "trade.query";
		$params['version'] = "1.7";
		$params['mchId'] = $this->merchantId;
		$params['outTradeNo'] = $OrderNo;
		$params['queryType'] = 1;
		
		$sign = $this->verSign($params);
		
		$params['sign'] = $sign;
		//$params['apiKey'] = $this->apiKey;
		
		/* $file = "/tmp/pay4.log";
		$params_json = json_encode($params , JSON_UNESCAPED_UNICODE);
		@file_put_contents($file, $params_json,FILE_APPEND); */
		
		$result = $this->send_post($this->checkChargeUrl, $params);
		
		/* $file = "/tmp/pay4.log";
		file_put_contents($file, $result,FILE_APPEND); */
		
		$return_data = json_decode($result, true);
		return $return_data;
	}
	
	public function payRequestGateWay($OrderNo , $Amount , $BankId=0){
		date_default_timezone_set('Asia/Shanghai');
		
		$params['tradeType'] = "pay.submit";
		$params['version'] = "1.7";
		$params['channel'] = $this->pay_type;
		$params['mchId'] = $this->merchantId;
		$params['body'] = "didi_score";
		$params['outTradeNo'] = $OrderNo;
		$params['amount'] = $Amount;
		$params['settleCycle'] = 0;
		$params['bankCode'] = $BankId;
		$params['cardType'] = "D";
		$params['accessType'] = "1";
		$params['notifyUrl'] = $this->PayCallBackUrl;
		$params['callbackUrl'] = $this->PayReturnUrl;
		
		$sign = $this->verSign($params);
		$params['sign'] = $sign;
		
		
		/* $file = "/tmp/pay4.log";
		$params_json = json_encode($params , JSON_UNESCAPED_UNICODE);
		@file_put_contents($file, $params_json,FILE_APPEND); */
		
		$result = $this->send_post($this->paymentUrl, $params);
		
		/* $file = "/tmp/pay4.log";
		file_put_contents($file, $result."\n",FILE_APPEND); */
		
		$return_data = json_decode($result, true);
		return $return_data;
	}
	
	public function payRequestQrCode($OrderNo , $Amount){
		date_default_timezone_set('Asia/Shanghai');
	
		$params['tradeType'] = "pay.submit";
		$params['version'] = "1.7";
		$params['channel'] = $this->pay_type;
		$params['mchId'] = $this->merchantId;
		$params['body'] = "didi_score";
		$params['outTradeNo'] = $OrderNo;
		$params['amount'] = $Amount;
		$params['settleCycle'] = 0;
		$params['bankCode'] = 0;
		$params['cardType'] = "D";
		$params['accessType'] = "1";
		$params['notifyUrl'] = $this->PayCallBackUrl;
		$params['callbackUrl'] = $this->PayReturnUrl;
	
		$sign = $this->verSign($params);
		$params['sign'] = $sign;
	
	
		/* $file = "/tmp/pay4.log";
		$params_json = json_encode($params , JSON_UNESCAPED_UNICODE);
		@file_put_contents($file, $params_json,FILE_APPEND); */
	
		$result = $this->send_post($this->paymentUrl, $params);
	
		/* $file = "/tmp/pay4.log";
		file_put_contents($file, $result."\n",FILE_APPEND); */
	
		$return_data = json_decode($result, true);
		return $return_data;
	}
	
	public function quickPayRequest($OrderNo , $Amount , $payCardInfo="{}"){
		date_default_timezone_set('Asia/Shanghai');
	
		$params['tradeType'] = "pay.submit";
		$params['version'] = "1.7";
		$params['channel'] = $this->pay_type;
		$params['mchId'] = $this->merchantId;
		$params['body'] = "didi_score";
		$params['outTradeNo'] = $OrderNo;
		$params['amount'] = $Amount;
		$params['settleCycle'] = 0;
		$params['cardType'] = "D";
		$params['payCardInfo'] = $payCardInfo;//{"bankCardNo":"bankCardNo","customerName":"customerName","phoneNo":"phoneNo","cerType":"cerType","cerNo":"cerNo"}
		$params['notifyUrl'] = $this->PayCallBackUrl;
		$params['callbackUrl'] = $this->PayReturnUrl;
	
		$sign = $this->verSign($params);
		$params['sign'] = $sign;
	
	
		/* $file = "/tmp/pay4.log";
		$params_json = json_encode($params , JSON_UNESCAPED_UNICODE);
		@file_put_contents($file, $params_json,FILE_APPEND); */
	
		$result = $this->send_post($this->paymentUrl, $params);
	
		$file = "/tmp/pay4.log";
		file_put_contents($file, $result."\n",FILE_APPEND);
	
		$return_data = json_decode($result, true);
		return $return_data;
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
	
	
}





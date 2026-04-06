<?php
class payment
{
	private $instId = "800068";//TODO 机构号
	private $merchantId = "OLP800068000001";//TODO qrcode
	public $apiKey = "PQIQepltJj2DHU5KGPBQCaFZc";//TODO qrcode
	public $pay_type;
	
	private $paymentUrl = "http://103.47.137.51:8092/posm/qrpayreq.tran?olpdat=";//充值支付
	private $PayCallBackUrl = "";
	private $PayReturnUrl = "http://www.didi5188.com";
	
	private $checkChargeUrl = "http://103.47.137.51:8092/posm/qrpayqry.tran?olpdat=";
	
    public function __construct() {
        $this->PayCallBackUrl = "http://www.didi5188.com/chargecallback6.php";//"http://" . $_SERVER['HTTP_HOST'] . "/chargecallback.php";
        
        $this->account[7000]['pay_type'] = "Z";//支付宝扫码支付
        $this->account[7001]['pay_type'] = "N";//支付宝H5支付
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
	
	
	public function checkChargeRequest($OrderNo , $payDate='00000000'){
		date_default_timezone_set('Asia/Shanghai');
		
		$params['mercId'] = $this->merchantId;
		$params['mercOrderId'] = $OrderNo;
		$params['txnDate'] = $payDate;
		
		$params['md5value'] = strtoupper(md5($params['mercId'] . $params['mercOrderId'] . $params['txnDate'] . $this->apiKey));
		
		
		/* $file = "/tmp/pay6.log";
		$params_json = json_encode($params , JSON_UNESCAPED_UNICODE);
		@file_put_contents($file, $params_json,FILE_APPEND); */
		
		$result = $this->send_post($this->checkChargeUrl, $params);
		
		/* $file = "/tmp/pay6.log";
		file_put_contents($file, $result,FILE_APPEND); */
		
		$return_data = json_decode($result, true);
		$return_data['msg'] = $return_data['RSPMSG'];
		return $return_data;
	}
	
	
	public function payRequestQrCode($OrderNo , $Amount , $ip=""){
		date_default_timezone_set('Asia/Shanghai');
	
		$params['instId'] = $this->instId;
		$params['mercId'] = $this->merchantId;
		$params['mercOrderId'] = $OrderNo;
		$params['txnType'] = $this->pay_type;
		$params['txnDate'] = date("Ymd");
		$params['txnTime'] = date("His");
		$params['ccy'] = "CNY";
		$params['txnAmt'] = $Amount * 100;//分
		$params['notifyUrl'] = $this->PayCallBackUrl;
		$params['frontUrl'] = $this->PayReturnUrl;
		
		$params['productName'] = "didi_score";
		$params['productDesc'] = "didi_score";
		
		$params['clientIp'] = $ip;
	
		$params['md5value'] = strtoupper(md5($params['instId'] . $params['mercId'] . $params['mercOrderId'] . $params['txnType'] . $params['txnDate'] . $params['txnTime'] . $params['ccy'] . $params['txnAmt'] . $this->apiKey));
	
	
		/* $file = "/tmp/pay6.log";
		$params_json = json_encode($params , JSON_UNESCAPED_UNICODE);
		@file_put_contents($file, $params_json,FILE_APPEND); */
	
		$result = $this->send_post($this->paymentUrl, $params);
	
		/* $file = "/tmp/pay6.log";
		file_put_contents($file, $result."\n",FILE_APPEND); */
	
		$return_data = json_decode($result, true);
		$return_data['msg'] = $return_data['RSPMSG'];
		return $return_data;
	}
	
	
	
	private function send_post($url,$param){
		$postdata = json_encode($param);
		$url = $url.$postdata;
		$options = array(
				'http' => array(
						'method' => 'POST',
						'header' => 'Content-type:application/json; charset=utf-8',
						'content' => '',
						'timeout' => 15 * 60 // 超时时间（单位:s）
				)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		return $result;
	}
	
	
}





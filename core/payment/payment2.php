<?php
class payment
{
	private $account = array();
	
	private $app_id = "1201";
	public $Appkey = "e34d7cd11a1c4dafbb7c72ea6542f2cf";
	private $pay_type;
	private $paymentUrl;
	
	private $paymentUrlTencent = "http://101.132.126.81/Pay/GateWayTencent.aspx";//充值支付 微信，QQ钱包，京东钱包
	private $paymentUrlAliPay = "http://101.132.126.81/Pay/GateWayAliPay.aspx";//充值支付 支付宝
	private $paymentUrlGateWayUnion = "http://101.132.126.81/Pay/GateWayUnionPay.aspx";//充值支付 网关 快捷
	private $PayCallBackUrl = "";
	private $PayReturnUrl = "http://www.didi5188.com";
	
	private $checkChargeUrl = "http://101.132.126.81/Pay/ThridPayQuery.aspx";
	
    public function __construct() {
        $this->PayCallBackUrl = "http://www.didi5188.com/chargecallback2.php";
        
        $this->account[5003]['pay_type'] = "2";//微信扫码
        $this->account[5003]['paymentUrl'] = $this->paymentUrlTencent;
        
        $this->account[5004]['pay_type'] = "2";//支付宝扫码
        $this->account[5004]['paymentUrl'] = $this->paymentUrlAliPay;
        
        $this->account[5012]['pay_type'] = "QUICK";//快捷
        $this->account[5012]['paymentUrl'] = $this->paymentUrlGateWayUnion;
    }
    
    public function setAccount($idx){
    	$this->pay_type = $this->account[$idx]['pay_type'];
    	$this->paymentUrl = $this->account[$idx]['paymentUrl'];
    }
	
	public function setPayCallBackUrl($url){
		$this->PayCallBackUrl = $url;
	} 
	
	public function setPayReturnUrl($url){
		$this->PayReturnUrl = $url;
	}
	
	
	public function checkChargeRequest($OrderNo , $paynum=''){
		$params['app_id'] = $this->app_id;
		$params['order_id'] = $OrderNo;
		$params['time_stamp'] = date("YmdHis", time());
		$params['sign'] = $this->verSign($params);
		//print_r($params);
		//$params_json = json_encode($params , JSON_UNESCAPED_UNICODE);
		$params_str = http_build_query($params);
		$result = $this->http_request($this->checkChargeUrl, $params_str, "POST");
		//print_r($result);
		$return_data = json_decode($result, true);
		return $return_data;
	}
	
	public function payRequest($OrderNo , $Amount){
		date_default_timezone_set('Asia/Shanghai');
		$time_stamp = date("YmdHis", time());
		
		$params['app_id'] = $this->app_id;
		$params['pay_type'] = $this->pay_type;
		$params['order_id'] = $OrderNo;
		$params['order_amt'] = $Amount;
		$params['notify_url'] = $this->PayCallBackUrl;
		$params['return_url'] = $this->PayReturnUrl;
		$params['time_stamp'] = $time_stamp;
		$params['key'] = md5($this->Appkey);
		
		$params['sign'] = $this->verSign($params);
		
		
		$params['goods_name'] = "滴滴点卡";
		$params['notify_url'] = urlencode($params['notify_url']);
		$params['return_url'] = urlencode($params['return_url']);
		$params['goods_name'] = urlencode($params['goods_name']);
		unset($params['key']);
		
		/* $params_json = json_encode($params , JSON_UNESCAPED_UNICODE);
		$file = "/tmp/pay2.log";
		@file_put_contents($file, $params_json,FILE_APPEND); */
		
		$result = $this->send_post($this->paymentUrl, $params);
		
		/* $file = "/tmp/pay2.log";
		file_put_contents($file, $result,FILE_APPEND); */
		
		$return_data = json_decode($result, true);
		return $return_data;
	}
	
	public function quickPayRequest($OrderNo , $Amount){
		date_default_timezone_set('Asia/Shanghai');
		$time_stamp = date("YmdHis", time());
	
		$params['app_id'] = $this->app_id;
		
		$params['bank_code'] = $this->pay_type;
		$params['order_id'] = $OrderNo;
		$params['order_amt'] = $Amount;
		$params['notify_url'] = $this->PayCallBackUrl;
		$params['return_url'] = $this->PayReturnUrl;
		$params['time_stamp'] = $time_stamp;
		$params['key'] = md5($this->Appkey);
	
		$params['sign'] = $this->verSign($params);
		$params['card_type'] = 1;
		$params['goods_name'] = "滴滴点卡";
	
	
		$params['notify_url'] = urlencode($params['notify_url']);
		$params['return_url'] = urlencode($params['return_url']);
		$params['goods_name'] = urlencode($params['goods_name']);
		unset($params['key']);
	
		/* $params_json = json_encode($params , JSON_UNESCAPED_UNICODE);
		$file = "/tmp/pay2.log";
		@file_put_contents($file, $params_json,FILE_APPEND); */
	
		$result = $this->send_post($this->paymentUrl, $params);
	
		/* $file = "/tmp/pay2.log";
		file_put_contents($file, $result,FILE_APPEND); */
	
		$return_data = json_decode($result, true);
		return $return_data;
	}
	
	public function verSign($params){
		if(empty($params)) return "";
		$paramsStr = "";
		foreach($params as $key=>$val){
				$paramsStr .= $key . "=" . $val . "&";
		}
		
		$paramsStr = mb_substr($paramsStr , 0 , -1, 'UTF-8');
		
		/* $file = "/tmp/pay2.log";
		@file_put_contents($file, "verSign : " . $paramsStr . "#",FILE_APPEND); */
		
		
		return md5($paramsStr);
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
	
	
	private function http_request($url, $param = "", $requst_type = "GET", $timeout = null, $header = array(), $cookie = '', $options = array()) {
		$http_request = new CurlRequest($url, $param, $requst_type, $timeout, $header, $cookie, $options);
		$result = $http_request->send();
		return $result;
	}
	
}



class CurlRequest {
	private $url = '';
	private $param = '';
	private $requst_type = '';
	private $header = array();
	private $cookies = '';
	private $timeout = 8; //单位：秒
	private $options = array();

	public function __construct($url = '', $param = array(), $requst_type = "GET", $timeout = null, $header = array(), $cookie = '', $options = array()) {
		$this->setUrl($url);
		$this->setParam($param);
		$this->setRequstType($requst_type);
		$this->setHeader($header);
		$this->setCookie($cookie);
		$t = $timeout;
		$this->setTimeout($t);
		$this->setOptions($options);
	}

	private function setUrl($_url) {
		if ($_url != '') {
			if (strncasecmp(substr($_url, 0, 7), "http://", 7) != 0)
				$_url = "http://" . $_url;
			$this->url = $_url;
		}
	}

	private function setParam($_param) {
		if (empty($_param))
			return;
		if (is_array($_param))
			$this->param = $this->makeQueryArr2Str($_param); //自带urlencode 空格转为+
		elseif (is_string($_param))
		$this->param = $_param;
	}

	private function setRequstType($_requst_type) {
		if ($_requst_type == "POST" || $_requst_type == "GET") {
			$this->requst_type = $_requst_type;
		}
	}

	private function setHeader($_header) {
		if (empty($_header))
			return;
		if (is_array($_header)) {
			foreach ($_header as $k => $v) {
				$this->header[] = is_numeric($k) ? trim($v) : (trim($k) . ": " . trim($v));
			}
		} elseif (is_string($_header)) {
			$this->header[] = $_header;
		}
	}

	private function setCookie($_cookie) {
		if (empty($_cookie)) {
			return;
		}
		if (is_array($_cookie)) {
			$this->cookies = $this->makeQueryArr2Str($_cookie, ';');
		} elseif (is_string($_cookie)) {
			$this->cookies = $_cookie;
		}
	}

	private function setTimeout($_timeout) {
		if (is_numeric($_timeout)) {
			$this->timeout = $_timeout;
		}
	}

	private function setOptions($_options) {
		if (empty($_options)) {
			return;
		}
		if (is_array($_options)) {
			$this->options = $_options;
		}
	}


	private function makeQueryArr2Str($array, $sep = '&') {
		$param = '';
		foreach ($array as $k => $v) {
			$param .= ($param ? $sep : "");
			$param.=($k . "=" . $v);
		}
		return $param;
	}

	public function send() {
		$result = "";
		try {
			$curl = curl_init();
			$url = $this->url;
			if ($this->requst_type == "GET" && !empty($this->param)) {
				$parse = parse_url($url);
				$sep = isset($parse['query']) ? '&' : '?';
				$url.=($sep . $this->param);
			}

			if ($this->requst_type == "POST") {
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $this->param);
			}
			curl_setopt($curl, CURLOPT_URL, $url);

			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

			if (empty($this->header)) {
				$this->setHeader(array(
						'User-Agent: Mozilla/5.0 (X11; U; Linux i686; zh-CN; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2',
						//'Accept-Language: zh-cn',
						//'Cache-Control: no-cache',
				)
				);
			}
			curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
			//curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; U; Linux i686; zh-CN; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2');
			if (!empty($this->cookies)) {
				curl_setopt($curl, CURLOPT_COOKIE, $this->cookies);
			}
			if (!empty($this->options)) {
				curl_setopt_array($curl, $this->options);
			}

			$result = curl_exec($curl);
			$err = curl_error($curl);
			if ($err) {
				throw new Exception(__CLASS__ . " curl error: " . $err);
			}
			curl_close($curl);
		} catch (Exception $e) {
			if ($curl)curl_close($curl);
		}
		return $result;
	}

}




<?php
class payment
{
	private $account = array();
	
	private $MerchantNo;
	private $Key;
	
	private $paymentUrl = "http://pay.eqianbao.cc/pay.htm";//充值支付//http://yk.dianxiao2.cc/pay.htm
	private $PayCallBackUrl = "";
	
	private $drawUrl = "http://pay.eqianbao.cc/anotherpay.htm";//提现//http://yk.dianxiao2.cc/anotherpay.htm
	private $DrawCallBackUrl = "";
	
	private $checkChargeUrl = "http://pay.eqianbao.cc/osearch.htm";//http://yk.dianxiao2.cc/osearch.htm
	
    public function __construct() {
        $this->PayCallBackUrl = "http://www.didi5188.com/chargecallback.php";//"http://" . $_SERVER['HTTP_HOST'] . "/chargecallback.php";
        $this->DrawCallBackUrl = "http://www.didi5188.com/drawcallback.php";
        
        $this->account[0]['MerchantNo'] = "20106";//20092杭州拓达科技（新通道）
        $this->account[0]['Key'] = "588f4de7-965b-471c-926b-5e57a8986bf6";//52d79645-fc59-421d-9494-4342b3049413
        
        $this->account[1]['MerchantNo'] = "";//杭州科达文体 20023
        $this->account[1]['Key'] = "";//5394c38a-58f7-4435-9ed6-0bea9f222072
        
        $this->account[2]['MerchantNo'] = "";//"20052";//杭州鸿达娱乐
        $this->account[2]['Key'] = "";//"ca99d411-efb7-442c-9e08-8da9d3306cea";
        
        $this->account[3]['MerchantNo'] = "20083";//网关支付
        $this->account[3]['Key'] = "df134cd6-a6a8-434a-ac78-3f3079bd943c";
         
    }
    
    public function setAccount($idx){
    	$this->MerchantNo = $this->account[$idx]['MerchantNo'];
    	$this->Key = $this->account[$idx]['Key'];
    }
	
	public function setPayCallBackUrl($url){
		$this->PayCallBackUrl = $url;
	} 
	
	public function setDrawCallBackUrl($url){
		$this->DrawCallBackUrl = $url;
	}
	
	public function checkChargeRequest($OrderNo , $paynum=''){
		$params['code'] = $this->MerchantNo;
		$params['attach'] = $this->MerchantNo . "_" . $OrderNo;
		$params['paynum'] = !empty($paynum)?$paynum:$this->Key;
		$params['sign'] = $this->verSign($params);
		//print_r($params);
		$params_json = json_encode($params , JSON_UNESCAPED_UNICODE);
		$result = $this->http_request($this->checkChargeUrl, $params_json, "POST");
		//print_r($result);
		$return_data = json_decode($result, true);
		return $return_data;
	}
	
	public function payRequest($PayType , $OrderNo , $Amount , $BankId=0 , $Ip=null){
		$params['code'] = $this->MerchantNo;
		$params['attach'] = $this->MerchantNo . "_" . $OrderNo;
		$params['money'] = $Amount;
		$params['callbackurl'] = $this->PayCallBackUrl;
		$params['paytype'] = $PayType;
		$params['title'] = "赢客点卡";
		$params['sign'] = $this->verSign($params);
		if(!empty($BankId))
			$params['extend'] = base64_encode("{\"bank_segment\":\"" . $BankId . "\"}");
		
		if(!empty($Ip))
			$params['extend'] = base64_encode("{\"spbill_create_ip\":\"" . $Ip . "\"}");
		//print_r($params);
		$params_json = json_encode($params , JSON_UNESCAPED_UNICODE);
		//echo $params_json;
		
		/* $file = "/tmp/pay.log";
		@file_put_contents($file, $params_json,FILE_APPEND); */
		
		$result = $this->http_request($this->paymentUrl, $params_json, "POST");
		
		/* $file = "/tmp/pay.log";
		file_put_contents($file, $result,FILE_APPEND); */
		
		$return_data = json_decode($result, true);
		return $return_data;
	}
	
	
	public function drawRequest($OrderNo , $Amount , $extend = array()){
		//$extend=array('accName'=>'张三','accTel'=>'13800138000','bankName'=>'浦发银行','cardNo'=>'62170983437275252');
		$params['code'] = $this->MerchantNo;
		$params['attach'] = $this->MerchantNo . "_" . $OrderNo;
		$params['money'] = $Amount;
		$params['callbackurl'] = $this->PayCallBackUrl;
		$params['sign'] = $this->verSign($params);
		$params['extend'] = base64_encode(json_encode($extend , JSON_UNESCAPED_UNICODE));
		
		//print_r($params);
		$params_json = json_encode($params , JSON_UNESCAPED_UNICODE);
		$result = $this->http_request($this->drawUrl, $params_json, "POST");
		//print_r($result);
		$return_data = json_decode($result, true);
		return $return_data;
	}
	
	
	public function verSign($params){
		if(empty($params)) return "";
		ksort($params);
		$paramsStr = "";
		foreach($params as $key=>$val){
			if($val!=="" && $val!==null)
				$paramsStr .= $key . "=" . $val . "&";
		}
		$paramsStr = mb_substr($paramsStr , 0 , -1, 'UTF-8') . $this->Key;
		
		/* $file = "/tmp/pay.log";
		@file_put_contents($file, "verSign : " . $paramsStr . "#",FILE_APPEND); */
		
		return md5($paramsStr);
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




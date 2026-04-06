<?php
function httpGet($url , $referurl = '' , $param = null){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);

	if(!empty($referurl)){
		curl_setopt($ch, CURLOPT_REFERER , $referurl);
	}

	curl_setopt($ch, CURLOPT_AUTOREFERER , true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
	curl_setopt($ch, CURLOPT_TIMEOUT, 25);
	curl_setopt($ch, CURLOPT_HEADER, 0); //不返回header部分
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //返回字符串，而非直接输出
	if(!empty($param)){
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	}

	//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
	//curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie_file); //存储cookies
	//curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0');
	curl_setopt($ch, CURLOPT_USERAGENT, '(compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; 360SE)');
	$contents = curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);


	if($http_code != 200 || empty($contents)){
		$contents = "";//file_get_contents($url);
	}

	return $contents;
}





echo httpGet("http://lotto.bclc.com/services2/keno/draw/latest/20/0" , "http://lotto.bclc.com");
















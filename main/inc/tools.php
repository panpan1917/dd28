<?php
define('EARTH_RADIUS', 6378.137); //地球半径
define('PI', 3.1415926535898); //圆周率

class tools {
	static function trans_left_time($endtime){
		$now =strtotime("now"); //当前时间
		$endtime= strtotime($endtime); //设定截止时间，转成时间戳
	
		$second = $endtime-$now; //获取截止时间到现在时间的时间戳（秒数）
		if($second <= 0)return array('y'=>0 , 'mon'=>0 , 'd'=>0 , 'h'=>0 , 'm'=>0 , 's'=>0);
		$year = floor($second/3600/24/365); //从这个时间戳中换算出年头数
	
		$temp =$second-$year*365*24*3600; //从这个时间戳中去掉整年的秒数，就剩下月份的秒数
		$month=floor($temp/3600/24/30); //从这个时间戳中共换算出月数
	
		$temp=$temp-$month*30*3600*24; //从时间戳中去掉整月的秒数，就剩下天的描述
		$day = floor($temp/24/3600); //从这个时间戳中换算出剩余的天数
	
		$temp=$temp-$day*3600*24; //从这个时间戳中去掉整天的秒数，就剩下小时的秒数
		$hour = floor($temp/3600); //从这个时间戳中换算出剩余的小时数
	
		$temp=$temp- $hour*3600; //从时间戳中去掉小时的秒数，就剩下分的秒数
		$minute=floor($temp/60); //从这个时间戳中换算出剩余的分数
	
		$second=$temp-$minute*60; //最后只有剩余的秒数了
	
		//echo "距离截止时间还有($year)年($month)月($day)天($hour)小时($minute)分($second)秒。";
	
		return array('y'=>$year , 'mon'=>$month , 'd'=>$day , 'h'=>$hour , 'm'=>$minute , 's'=>$second);
	
	}
	
	
	static function get_mimetype($filename) {
		$mime_types = array(
				'txt' => 'text/plain',
				'htm' => 'text/html',
				'html' => 'text/html',
				'php' => 'text/html',
				'css' => 'text/css',
				'js' => 'application/javascript',
				'json' => 'application/json',
				'xml' => 'application/xml',
				'swf' => 'application/x-shockwave-flash',
				'flv' => 'video/x-flv',
	
				// images
				'png' => 'image/png',
				'jpe' => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'jpg' => 'image/jpeg',
				'gif' => 'image/gif',
				'bmp' => 'image/bmp',
				'ico' => 'image/vnd.microsoft.icon',
				'tiff' => 'image/tiff',
				'tif' => 'image/tiff',
				'svg' => 'image/svg+xml',
				'svgz' => 'image/svg+xml',
	
				// archives
				'zip' => 'application/zip',
				'rar' => 'application/x-rar-compressed',
				'exe' => 'application/x-msdownload',
				'msi' => 'application/x-msdownload',
				'cab' => 'application/vnd.ms-cab-compressed',
	
				// audio/video
				'mp3' => 'audio/mpeg',
				'qt' => 'video/quicktime',
				'mov' => 'video/quicktime',
	
				// adobe
				'pdf' => 'application/pdf',
				'psd' => 'image/vnd.adobe.photoshop',
				'ai' => 'application/postscript',
				'eps' => 'application/postscript',
				'ps' => 'application/postscript',
	
				// ms office
				'doc' => 'application/msword',
				'rtf' => 'application/rtf',
				'xls' => 'application/vnd.ms-excel',
				'ppt' => 'application/vnd.ms-powerpoint',
	
				// open office
				'odt' => 'application/vnd.oasis.opendocument.text',
				'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		);
	
		$ext = strtolower(array_pop(explode('.',$filename)));
		if (array_key_exists($ext, $mime_types)) {
			return $mime_types[$ext];
		}
		elseif (function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME);
			$mimetype = finfo_file($finfo, $filename);
			finfo_close($finfo);
			return $mimetype;
		}
		else {
			return 'application/octet-stream';
		}
	}
	
	
	static function paseFilterMap($jsonStr){
		$tmpArr = json_decode($jsonStr , true);
		$ret = array();
		if(!empty($tmpArr)){
			foreach($tmpArr as $item){
				$ret[$item['id']] = $item['text'];
			}
		}
	
		return $ret;
	}
	
	static function getClientIp()
	{
		if ( $_SERVER['HTTP_CLIENT_IP'] && $_SERVER['HTTP_CLIENT_IP'] != "unknown" )
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}else if ( $_SERVER['HTTP_X_FORWARDED_FOR'] && $_SERVER['HTTP_X_FORWARDED_FOR'] != "unknown" )
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		if(strlen($ip) > 15) $ip = "";
		
		return $ip;
	}
	
	
	static function php_json_decode($str){
		$stripStr = stripslashes($str);
		$json = json_decode($stripStr,true);
		if(!empty($json)){
			foreach($json as $k=>$v){
				$json[$v] = strip_tags(trim($v));
			}
		}
		return $json;
	}
	
	
	static function getRequestHeaders(){
		$headers['appversion'] = "";
		$headers['custid'] = "";
		$headers['token'] = "";
		foreach ($_SERVER as $name => $value){
			if (substr($name, 0, 5) == 'HTTP_'){
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		
		$headers = array_change_key_case($headers, CASE_LOWER);
		
		return $headers;
	}
	
	
	
	/**
	 * 计算两组经纬度坐标 之间的距离
	 * params ：lat1 纬度1； lng1 经度1； lat2 纬度2； lng2 经度2； len_type （1:m or 2:km);
	 * return m or km
	 */
	static function getDistance($lat1, $lng1, $lat2, $lng2, $len_type = 1, $decimal = 2) {
		$radLat1 = $lat1 * PI / 180.0;
		$radLat2 = $lat2 * PI / 180.0;
		$a = $radLat1 - $radLat2;
		$b = ($lng1 * PI / 180.0) - ($lng2 * PI / 180.0);
		$s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
		$s = $s * EARTH_RADIUS;
		$s = round($s * 1000);
		if ($len_type > 1) {
			$s /= 1000;
		}
		return round($s, $decimal);
	}
	
	static function getDistanceFormate($distance, $last_time) {
		$diff_time = time() - $last_time;
		$time_txt = '';
		if ($diff_time < 3600) {
			$time = ceil($diff_time / 60);
			if ($time > 0) {
				$time_txt = $time . '分前';
			}
		} elseif ($diff_time < 24 * 3600) {
			$time = ceil($diff_time / 3600);
			if ($time <= 0) {
				$time = 1;
			}
			$time_txt = $time . '小时前';
		} else {
			$time = ceil($diff_time / 3600 / 24);
			if ($time <= 0) {
				$time = 1;
			}
			$time_txt = $time . '天前';
		}
	
		$distance = ceil($distance / 1000) * 1000;
		if ($distance <= 1000) {
			return $time_txt . '1公里内';
		} elseif($distance > 1000 * 1000) {
			return $time_txt . '1000公里外';
		}
		return $time_txt . ceil($distance / 1000) . '公里内';
	}
	
	//计算时间
	static function getTimeFormate($last_time) {
		$diff_time = time() - $last_time;
		$time_txt = '';
		if ($diff_time < 3600) {
			$time = ceil($diff_time / 60);
			if ($time > 0) {
				$time_txt = $time . '分前';
			}
		} elseif ($diff_time < 24 * 3600) {
			$time = ceil($diff_time / 3600);
			if ($time <= 0) {
				$time = 1;
			}
			$time_txt = $time . '小时前';
		} else {
			$time = ceil($diff_time / 3600 / 24);
			if ($time <= 0) {
				$time = 1;
			}
			$time_txt = $time . '天前';
		}
		 
		return 	$time_txt;
	}
	
	static function getSampleArea($area){
		$areaArr = explode("-", $area);
		if(count($areaArr)==1) return $areaArr[0];
		else return $areaArr[0]."-".$areaArr[1];
	}
	
	static function delAllFile($dir){
		$dh=opendir($dir);
		while($file=readdir($dh)) {
			if($file!="." && $file!="..") {
				$fullpath=$dir."/".$file;
 				@unlink($fullpath);
			}
		}
	}
	
	
	static function makeUniqueNum(){
		return substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
	}
	
	
	static function makeFilePath($path , $filename){
		$tmpStr = md5(microtime(true));
		$targetpath = $path . substr($tmpStr , -4 , 2)  . "/";
		if(!is_dir($targetpath)){
			@mkdir($targetpath , 0777);
		}
	
		$targetpath = $targetpath . substr($tmpStr , -2)  . "/";
		if(!is_dir($targetpath)){
			@mkdir($targetpath , 0777);
		}
	
		$filetarget =  "{$targetpath}$filename";
	
		return array('targetpath' => $targetpath , 'filetarget' => $filetarget);
	}
	
	
	
	static function makeQrcode($data , $qrcodefile , $logofile = ""){
		require_once(dirname(__DIR__)."/phpqrcode/phpqrcode.php");
		
		$errorCorrectionLevel = 'L';//容错级别
		$matrixPointSize = 6;//生成图片大小
		//生成二维码图片
		QRcode::png($data, $qrcodefile, $errorCorrectionLevel, $matrixPointSize, 2);
		//$logofile = 'http://img.aiti.com/uploaded/avatar/20140305/5176438df39012880af6da07c725d91f_1394001874.jpeg' 
		if(empty($logofile)) 
			$logocontent = FALSE;
		else 
			$logocontent = file_get_contents($logofile);
		if($logocontent !== FALSE){
			$QR = imagecreatefromstring(file_get_contents($qrcodefile));
			$logo = imagecreatefromstring($logocontent);
			$QR_width = imagesx($QR);//二维码图片宽度
			$QR_height = imagesy($QR);//二维码图片高度
			$logo_width = imagesx($logo);//logo图片宽度
			$logo_height = imagesy($logo);//logo图片高度
			$logo_qr_width = $QR_width / 5;
			$scale = $logo_width/$logo_qr_width;
			$logo_qr_height = $logo_height/$scale;
			$from_width = ($QR_width - $logo_qr_width) / 2;
			//重新组合图片并调整大小
			imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
			$logo_qr_height, $logo_width, $logo_height);
			//输出图片
			imagepng($QR, $qrcodefile);
		}
		 
		header('Content-type: image/png');
		echo readfile($qrcodefile);
	}
	
	static function getAge($birthday){
		if($birthday == "0000-00-00")return "0";
		return (string)ceil((time() - strtotime($birthday))/86400/365);
	}
	
	static function encodePwd($password){
		return password_hash($password, PASSWORD_DEFAULT);
	}
	
	static function verifyPwd($password , $hash){
		return password_verify($password, $hash);
	}
	
	static function getSign($str){
		return md5(md5($str));
	}
	
	static function curl_post($url, $data, $header, $post = 1) {
		//初始化curl
		$ch = curl_init();
		//参数设置
		$res = curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, $post);
		if ($post)
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$result = curl_exec($ch);
		//连接失败
		if ($result == FALSE) {
			$result = "{\"statusCode\":\"172001\",\"statusMsg\":\"internet error\"}";
		}
	
		curl_close($ch);
		return $result;
	}
	
	static function http_request($url, $param = "", $requst_type = "GET", $timeout = null, $header = array(), $cookie = '', $options = array()) {
		$http_request = new CurlRequest($url, $param, $requst_type, $timeout, $header, $cookie, $options);
		$result = $http_request->send();
		return $result;
	}
	
	
	
	//获取指定日期后的第n个月份
	static function getOneMonth($date="2015-01-01" , $sign = 0){
		$tmp_date=date("Ym" , strtotime($date));
		//切割出年份
		$tmp_year=substr($tmp_date,0,4);
		//切割出月份
		$tmp_mon =substr($tmp_date,4,2);
		$tmp_nextmonth=mktime(0,0,0,$tmp_mon+$sign,1,$tmp_year);
		return date('Y-m',$tmp_nextmonth);
	}
	
	//获取指定日期所在自然周的第一天的日期
	static function getOneWeekFirst($date){
		$timestamp = strtotime($date);
		$sdate = date('Y-m-d',$timestamp-(date('N',$timestamp)-1)*86400);
		return $sdate;
	}
	
	
	//获取指定日期所在自然周的最后一天的日期
	static function getWeekEnd($date){
		$timestamp = strtotime($date);
		$edate = date('Y-m-d',$timestamp + (7-date('N',$timestamp))*86400);
		return $edate;
	}
	
	
	
	/**
	 * 系统邮件发送函数
	 * @param string $to    接收邮件者邮箱
	 * @param string $name  接收邮件者名称
	 * @param string $subject 邮件主题
	 * @param string $body    邮件内容
	 * @param string $attachment 附件列表
	 * @return boolean
	 */
	static function send_mail($to, $name, $subject = '', $body = '', $attachment = null)
	{
		$config = array(
			'SMTP_HOST'   => 'smtp.mxhichina.com', //SMTP服务器
			'SMTP_PORT'   => '25', //SMTP服务器端口
			'SMTP_USER'   => 'post@qiyunxin.cn', //SMTP服务器用户名
			'SMTP_PASS'   => 'Qiyunxin4321', //SMTP服务器密码  xcvd34dsrtzxw5
			'FROM_EMAIL'  => 'post@qiyunxin.cn', //发件人EMAIL
			'FROM_NAME'   => '企云信', //发件人名称
			'REPLY_EMAIL' => '', //回复EMAIL（留空则为发件人EMAIL）
			'REPLY_NAME'  => '', //回复名称（留空则为发件人名称）
			);
		//vendor('phpMailer.PHPMailerAutoload'); //从PHPMailer目录导class.phpmailer.php类文件
		//Create a new PHPMailer instance
		require_once(dirname(__DIR__)."/phpMailer/PHPMailerAutoload.php");
		$mail = new PHPMailer();
		$mail->IsSMTP(); // 设定使用SMTP服务
		//$mail->SMTPDebug  = 2;                     // 启用SMTP调试功能
		$mail->Host = $config['SMTP_HOST'];//HOST 地址
		$mail->Port = $config['SMTP_PORT'];//端口
		$mail->Username = $config['SMTP_USER'];//用户名
		$mail->Password = $config['SMTP_PASS'];//密码
		$mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
		//$mail->SMTPSecure = "ssl";		// 打开SSL加密，这里是为了解决QQ企业邮箱的加密认证问题的~~
		$mail->CharSet = "UTF-8";
		
		// Set PHPMailer to use the sendmail transport
		//$mail->isSendmail();
		//Set who the message is to be sent from
		$mail->setFrom($config['FROM_EMAIL'], $config['FROM_NAME']);
		//Set an alternative reply-to address
		$replyEmail       = $config['REPLY_EMAIL']?$config['REPLY_EMAIL']:$config['FROM_EMAIL'];
		$replyName        = $config['REPLY_NAME']?$config['REPLY_NAME']:$config['FROM_NAME'];
		$mail->addReplyTo($replyEmail, $replyName);
		//Set who the message is to be sent to
		$mail->addAddress($to, $name);
		//Set the subject line
		$mail->Subject = $subject ;
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
	
		$mail->msgHTML($body, dirname(__FILE__));
		//Replace the plain text body with one created manually
		$mail->AltBody = 'This is a plain-text message body';
		//Attach an image file
		if($attachment) {
			$mail->addAttachment($attachment);
		}
		//send the message, check for errors
		if (!$mail->send()) {
			return $mail->ErrorInfo;
		} else {
			return true;
		}
	}
	
	
	
	
	
	
	
	

	static function google2baidu($x, $y) {
		$data = @file_get_contents("http://api.map.baidu.com/ag/coord/convert?from=2&to=4&x=" . $x . "&y=" . $y);
		$array = json_decode($data, true);
		$arr = array();
		$arr['x'] = base64_decode($array['x']);
		$arr['y'] = base64_decode($array['y']);
		return $arr;
	}
	
	static function baidu2google($lat, $lng) {
		$x_pi = 3.14159265358979324 * 3000.0 / 180.0;
		
		$x = $lng - 0.0065;
		$y = $lat - 0.006;
		$z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
		
		$theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
		$lng = $z * cos($theta);
		$lat = $z * sin($theta);
		$lat_lng[0] = $lat;
		$lat_lng[1] = $lng;
		return $lat_lng;
	}

	static public function br2nl($text) {
		return preg_replace('/<br\\s*?\/??>/i', "\n", $text);
	}

	static public function isMobileRequest() {
		$_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
		$mobile_browser = '0';
		if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
			$mobile_browser++;
		if ((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') !== false))
			$mobile_browser++;
		if (isset($_SERVER['HTTP_X_WAP_PROFILE']))
			$mobile_browser++;
		if (isset($_SERVER['HTTP_PROFILE']))
			$mobile_browser++;
		$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
		$mobile_agents = array(
		    'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
		    'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
		    'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
		    'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
		    'newt', 'noki', 'oper', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
		    'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
		    'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
		    'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
		    'wapr', 'webc', 'winw', 'winw', 'xda', 'xda-'
		);
		if (in_array($mobile_ua, $mobile_agents))
			$mobile_browser++;
		if (strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
			$mobile_browser++;
		// Pre-final check to reset everything if the user is on Windows   
		if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
			$mobile_browser = 0;
		// But WP7 is also Windows, with a slightly different characteristic   
		if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)
			$mobile_browser++;
		if ($mobile_browser > 0)
			return true;
		else
			return false;
	}

	/**
	 *  计算.星座  
	 *  
	 * @param int $month 月份  
	 * @param int $day 日期  
	 * @return str  
	 */
	static public function getConstellation($month, $day) {
//1	0	白羊座	白羊座	Aries	constellation_type	0	0
//2	0	金牛座	金牛座	Taurus	constellation_type	0	0
//3	0	双子座	双子座	Gemini	constellation_type	0	0
//4	0	巨蟹座	巨蟹座	Cancer	constellation_type	0	0
//5	0	狮子座	狮子座	Leo	constellation_type	0	0
//6	0	处女座	处女座	Virgo	constellation_type	0	0
//7	0	天秤座	天秤座	Libra	constellation_type	0	0
//8	0	天蝎座	天蝎座	Scorpio	constellation_type	0	0
//9	0	射手座	射手座	Sagittarius	constellation_type	0	0
//10	0	摩羯座	摩羯座	Capricorn	constellation_type	0	0
//11	0	水瓶座	水瓶座	Aquarius	constellation_type	0	0
//12	0	双鱼座	双鱼座	Pisces	constellation_type	0	0

		$res = 0;
		switch ($month) {
			case 1:
				if ($day < 20) {
					$res = 10;
				} else {
					$res = 11;
				} break;
			case 2:
				if ($day < 19) {
					$res = 11;
				} else {
					$res = 12;
				} break;
			case 3:
				if ($day < 21) {
					$res = 12;
				} else {
					$res = 1;
				} break;
			case 4:
				if ($day < 20) {
					$res = 1;
				} else {
					$res = 2;
				} break;
			case 5:
				if ($day < 21) {
					$res = 2;
				} else {
					$res = 3;
				} break;
			case 6:
				if ($day < 22) {
					$res = 3;
				} else {
					$res = 4;
				} break;
			case 7:
				if ($day < 23) {
					$res = 4;
				} else {
					$res = 5;
				} break;
			case 8:
				if ($day < 23) {
					$res = 5;
				} else {
					$res = 6;
				} break;
			case 9:
				if ($day < 23) {
					$res = 6;
				} else {
					$res = 7;
				} break;
			case 10:
				if ($day < 24) {
					$res = 7;
				} else {
					$res = 8;
				} break;
			case 11:
				if ($day < 23) {
					$res = 8;
				} else {
					$res = 9;
				} break;
			case 12:
				if ($day < 22) {
					$res = 9;
				} else {
					$res = 10;
				} break;
		}
		return $res;
	}

	/**
	 * 操作系统是否是linux
	 * @return boolean
	 */
	static public function isLinux() {
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			return false;
		} else {
			return true;
		}
	}

	static public function redirectLink($url) {
		header('Location: ' . $url);
		exit;
	}

	static public function isSubmit($submit) {
		return (
			isset($_POST[$submit]) OR isset($_POST[$submit . '_x']) OR isset($_POST[$submit . '_y']) OR isset($_GET[$submit]) OR isset($_GET[$submit . '_x']) OR isset($_GET[$submit . '_y'])
			);
	}

	static public function get_user_ip() {
		$ip = (isset($_SERVER['HTTP_VIA'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
		if(strlen($ip) > 15) $ip = "";
		return $ip;
	}

	static public function getCurrentPageUrl() {

		$pageURL = "http";
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
			$pageURL .= "s";
		}
		$pageURL .= "://";
		$pageURL .= isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
		$pageURL .= $_SERVER['REQUEST_URI'];

		return $pageURL;
	}

	static public function truncate($instr, $len = 22, $po = 1) {
		$newstr = "";
		$pot = '';
		if ($po) {
			$pot = "...";
		}
		if (strlen($instr) > $len) {
			$newstr = self::msubstr($instr, 0, $len);
			$newstr = $newstr . $pot;
			return $newstr;
		} else {
			return $instr;
		}
	}

	private static function msubstr($str, $start, $len) {
		return mb_substr($str,$start,$len, 'utf-8');
	}

	/**
	 * 检查是否为图片
	 * @param <string> $filename 文件名
	 * @param <bool> $show_type 是否返回真实文件格式
	 * @return <bool> or <string> 返回结果(是否图片或者图片格式)
	 */
	static function check_image_format($filename, $show_type = false) {
		$img_array = array('jpg' => 255216, 'gif' => 7173, 'bmp' => 6677, 'png' => 13780);
		$file = fopen($filename, "rb");
		$bin = fread($file, 2); //只读2字节
		fclose($file);
		$strInfo = @unpack("C2chars", $bin);
		$typeCode = intval($strInfo['chars1'] . $strInfo['chars2']);
		if (in_array($typeCode, $img_array)) {
			if ($show_type)
				return array_search($typeCode, $img_array);
			return true;
		}
		return false;
	}

	/**
	 *  去除文本连续换行
	 *
	 * @param     string  $str  待处理的字符串
	 * @return    string
	 */
	static function stripLineBreaks($str) {
		$str = preg_replace("/[\r\n]+$/", "", $str);
		$str = preg_replace("/[\r\n]{2,}/", "\r\n", $str);
		return $str;
	}

	/**
	 * 对文本内的URL进行Auto Link的处理
	 * 
	 * @param string $text 待处理的文本
	 * @param int  $length url最多显示的长度
	 * @return string  
	 */
	static function urlAutoLink($text, $length) {
		preg_match_all("/(https?|ftps?):\/\/(\w+)\.([^\.\/]+)\.(com|net|org|cn)(\.)?(tw|net|us|cn)?(\/[\w-\.\/\?\%\&\=]*)?/i", $text, $links);
		foreach ($links[0] as $link_url) {
			//计算URL的长度。如果超过$max_size的设置，则缩短。 
			$len = strlen($link_url);
			if ($len > $length) {
				$link_text = substr($link_url, 0, $length) . "...";
			} else {
				$link_text = $link_url;
			}
			//生成HTML文字 
			$text = str_replace($link_url, '<a href="' . $link_url . '" target="_blank" title="' . $link_url . '">' . $link_text . '</a>', $text);
		}
		return $text;
	}

	public static function genRandomString($len = 6) {
		$str = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$str_len = strlen($str);
		$code = '';
		for ($i = 0; $i < $len; $i++) {
			$str1 = $str[rand(0, $str_len - 1)];
			$code .= $str1;
		}
		return $code;
	}

	public static function genRandomNum($len = 6) {
		$str = '123456789';
		$str_len = strlen($str);
		$code = '';
		for ($i = 0; $i < $len; $i++) {
			$str1 = $str[rand(0, $str_len - 1)];
			$code .= $str1;
		}
		return $code;
	}

	public static function emailToLoginUrl($email) {
		if (empty($email)) {
			return '#';
		}
		$email_url = array(
		    'gmail.com' => 'https://mail.google.com/', '163.com' => 'http://mail.163.com/',
		    '126.com' => 'http://mail.126.com/', 'qq.com' => 'http://mail.qq.com/',
		    'sina.com' => 'http://mail.sina.com/', 'sohu.com' => 'http://mail.sohu.com/',
		    '21.cn' => 'http://mail.21cn.com/', '139.com' => 'http://mail.139.com/',
		    'hotmail.com' => 'http://mail.live.com', 'sina.cn' => 'http://mail.sina.cn',
		    '189.cn' => 'http://webmail23.189.cn/webmail/', 'wo.com.cn' => 'http://mail.wo.com.cn/mail/login.action');
		$email_array = explode("@", $email);
		if (!$email_url[$email_array[1]]) {
			return 'http://mail.' . $email_array[1];
		}
		return $email_url[$email_array[1]];
	}

	public static function getHttpSource() {
		$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
		// iphone  
		$is_iphone = strripos($useragent, 'iphone');
		if ($is_iphone) {
			return 'iphone';
		}
		// android  
		$is_android = strripos($useragent, 'android');
		if ($is_android) {
			return 'android';
		}
		// 微信  
		$is_weixin = strripos($useragent, 'micromessenger');
		if ($is_weixin) {
			return 'weixin';
		}
		// ipad  
		$is_ipad = strripos($useragent, 'ipad');
		if ($is_ipad) {
			return 'iphone';
		}
		// ipod  
		$is_ipod = strripos($useragent, 'ipod');
		if ($is_ipod) {
			return 'iphone';
		}
		// pc电脑  
		$is_pc = strripos($useragent, 'windows nt');
		if ($is_pc) {
			return 'pc';
		}
		return 'other';
	}

}




class CurlRequest {
	private $url = '';
	private $param = '';
	private $requst_type = '';
	private $header = array();
	private $cookies = '';
	private $timeout = 0; //单位：秒
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



function sortGEO($a, $b) {
	if ($a['geodate'] == $b['geodate'] && $a['distance'] == $b['distance']) {
		return 0;
	}

	if ($a['geodate'] == $b['geodate']) {
		if ($a['distance'] > $b['distance']) {
			return 1;
		} else {
			return -1;
		}
	}

	if ($a['geodate'] > $b['geodate']) {
		return -1;
	} else {
		return 1;
	}
}




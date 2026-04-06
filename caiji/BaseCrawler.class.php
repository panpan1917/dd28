<?php
include_once(dirname( __FILE__ ) ."/Mysql.class.php");

abstract class BaseCrawler
{
	public $db;
	public $sleepSec;
	protected $stopBegin;
	protected $stopEnd;
	protected $proxylist = array();
	protected $crawlerUrls = array();
	public $gameType;
	protected $gameTypes = array(); 
	
	abstract protected function getUnOpenGameNos();
	abstract public function crawler();
	abstract public function open($No=0);
	
	public function __construct(){
		$this->db = new db();
	}
	
	public function stop(){
		if(time() >= strtotime(date("Y-m-d") . " " . $this->stopBegin) && time() <= (strtotime(date("Y-m-d") . " " . $this->stopEnd) - $this->sleepSec)){
			$this->Logger("Stop time. : " . date("Y-m-d H:i:s"));
			sleep($this->sleepSec);
			return true;
		}
		
		return false;
	}
	
	public function close(){
		$this->db->close();
		exit;
	}
	
	
	protected function switchGame($isstop){
		$gameTypes = implode($this->gameTypes, ",");
		$sql = "update game_config set isstop={$isstop} where game_type in({$gameTypes})";
		$this->db->execute($sql);
	}
	
	
	protected function getProxy(){
		$fp = fopen(dirname( __FILE__ ) ."/proxy.txt","r");
		while($line = fgets($fp)){
			$line = trim($line);
			if(!empty($line)){
				$this->proxylist[] = $line;
			}
		}
	
		@fclose($fp);
	}
	
	public function saveCrawlerData($gameNo , $resultStr){
		if(!empty($gameNo) && !empty($resultStr)){
			$resultStr = str_replace(",","|",$resultStr);
			$sql = "insert ignore into game_result(gametype,gameno,gameresult,`addtime`) values('{$this->gameType}',$gameNo,'{$resultStr}',now())";
			//$this->Logger($sql);
			$result = $this->db->execute($sql);
			if($result === FALSE){
				$this->Logger("Insert Error! : {$sql} {$this->db->error}");
			}
		}
	}
	
	protected function GetGameResult($gameNo){
		$sql = "select gameresult from game_result where gametype='{$this->gameType}' and gameno = '{$gameNo}'";// and isopen = 0
		//$this->Logger($sql);
		$result = $this->db->getRow($sql);
		return trim($result['gameresult']);
	}
	
	protected function GetOddByResult($gameId , $resultIds = array()){
		$result = [];
		$sql = "select game_std_odds as odds from game_config where game_type={$gameId}";
		$row = $this->db->getRow($sql);
		$odds = explode("|", $row['odds']);
		foreach($odds as $k=>$v){
			if(in_array($k , $resultIds)){
				$result[] = $v;
			}
		}
		
		return $result;
	}
	
	protected function Logger($content){
		$file = dirname( __FILE__ ) ."/".date("Ymd")."_".$this->gameType.".log";
		$content = date("Y-m-d H:i:s") . ":" . $content . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
		@chmod($file, 777);
	}
	

	protected function httpGet($url , $useproxy = 0 , $referurl = '' , $param = null){
		$cookie_file = dirname( __FILE__ ) ."/cookie.txt";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		
		if($useproxy){
			$idx = rand(0,count($this->proxylist)-1);
			if(!empty($this->proxylist[$idx]))
				curl_setopt($ch, CURLOPT_PROXY , $this->proxylist[$idx]);
		}
		
		if(!empty($referurl)){
			curl_setopt($ch, CURLOPT_REFERER , $referurl);
		}
		
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION , true);
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
		
		if(empty($contents)) $this->Logger("Http Get Error: {$url}");
		
		return $contents;
	}
	
	
	protected function getGame36Result($a,$b,$c){//36开奖结果
		$arrNum = array($a,$b,$c);
		sort($arrNum);
		if($arrNum[0] == $arrNum[2]) //豹子
			return 1;
		if($arrNum[0] == $arrNum[1] || $arrNum[1] == $arrNum[2]) //对子
			return 2;
		if($arrNum[0] == 0 && ($arrNum[1] == 1 || $arrNum[1]==8) && $arrNum[2] == 9)//顺子特例
			return 3;
		if($arrNum[1] - $arrNum[0] == 1 && $arrNum[2] - $arrNum[1] == 1) //顺子
			return 3;
		if($arrNum[0] == 0  && $arrNum[2] == 9)//半顺特例
			return 4;
		if($arrNum[1] - $arrNum[0] == 1 || $arrNum[2] - $arrNum[1] == 1) //半顺
			return 4;
		
		return 5; //杂
	}
	
}





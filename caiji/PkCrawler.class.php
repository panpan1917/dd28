<?php
include_once(dirname( __FILE__ ) ."/BaseCrawler.class.php");

class PkCrawler extends BaseCrawler
{
	public function __construct(){
		parent::__construct();
		$this->sleepSec = 3;
		$this->stopBegin = "00:10:00";
		$this->stopEnd = "09:00:00";
		$this->gameType = "gamepk";
		$this->gameTypes = [6,7,14,16,17,29];
		$this->crawlerUrls = array(
								0=>array('url'=>'http://ho.apiplus.net/newly.do?token=t06f7eak&code=bjpk10&format=json','method'=>'_api','useproxy'=>0,'referurl'=>''),
								//2=>array('url'=>'http://www.bwlc.net/bulletin/prevtrax.html?num=','method'=>'_parse_bwlc','useproxy'=>0,'referurl'=>'','useno'=>1),
								//0=>array('url'=>'http://www.bwlc.net/bulletin/prevtrax.html','method'=>'_parse_bwlc','useproxy'=>0,'referurl'=>''),
								//3=>array('url'=>'http://116.62.128.99/http.php?url=http://www.bwlc.net/bulletin/prevtrax.html?num=','method'=>'_parse_bwlc','useproxy'=>0,'referurl'=>'','useno'=>1),
								//3=>array('url'=>'http://116.62.128.99/http.php?url=http://www.bwlc.net/bulletin/prevtrax.html','method'=>'_parse_bwlc','useproxy'=>0,'referurl'=>''),
								//3=>array('url'=>'http://baidu.lecai.com/lottery/draw/view/557','method'=>'_pase_baidu','useproxy'=>0,'referurl'=>''),
								1=>array('url'=>'http://api.api68.com/pks/getPksHistoryList.do?date=&lotCode=10001','method'=>'_pase_1680210','useproxy'=>0,'referurl'=>'http://www.1680100.com/html/PK10/pk10kai_history.html')
								);
	}
	
	private function _api($contents){
		$str = json_decode($contents);
		$data = $str->data;
		$result = array();
	
		foreach ($data as $item) {
			$no = $item->expect;
			$result[$no]['no'] = $no;
			$result[$no]['time'] = $item->opentime;
			$result[$no]['data'] = str_replace(',', '|', $item->opencode);
		}
	
		if(empty($result)) $this->Logger("Parse Error.");
	
		return $result;
	}
	
	
	private function _pase_pk8810($contents){
		$arrRet = json_decode($contents,true);
		$arrRet = $arrRet['list'];
		$result = [];
		
		for($i = 0; $i < count($arrRet); $i++)
		{
			$no = $arrRet[$i]['c_t'];
			$NumArr = explode(",", $arrRet[$i]['c_r']);
			if(is_numeric($no) && count($NumArr) == 10){
				$result[$no]['no'] = $arrRet[$i]['c_t'];
				$result[$no]['time'] = date("Y-m-d H:i:s" , strtotime($arrRet[$i]['c_d']));
				$result[$no]['data'] = implode('|',$NumArr);
			}
		}
		
		if(empty($result)) $this->Logger("Parse Error.");
		
		return $result;
	}
	
	
	private function _pase_1680210($contents){
		$arrRet = json_decode($contents,true);
		$arrRet = $arrRet['result']['data'];
		$result = [];
		
		for($i = 0; $i < count($arrRet); $i++)
		{
			$no = $arrRet[$i]['preDrawIssue'];
			$NumArr = explode(",", $arrRet[$i]['preDrawCode']);
			if(is_numeric($no) && count($NumArr) == 10){
				$result[$no]['no'] = $arrRet[$i]['preDrawIssue'];
				$result[$no]['time'] = date("Y-m-d H:i:s" , strtotime($arrRet[$i]['preDrawTime']));
				$result[$no]['data'] = implode('|',$NumArr);
			}
		}
		
		if(empty($result)) $this->Logger("Parse Error.");
		
		return $result;
	}
	
	
	private function _pase_baidu($contents){
		preg_match('#<title>(\D+)(\d+)(\D+)(\d+)(\D+)</title>#s',$contents,$tabledata);
		$result = [];
		$data = [];
		$no = $tabledata[2];
		if(is_numeric($no) && strlen($tabledata[4])==20){
			for($i=0;$i<20;$i=$i+2){
				$num = substr($tabledata[4],$i,2);
				$num = (int)$num;
				if($num > 0){
					$data[] = $num;
				}
			}
			
			if(count($data) == 10){
				$result[$no]['no'] = $no;
				$result[$no]['time'] = date("Y-m-d H:i:s");
				$result[$no]['data'] = implode('|',$data);
			}
		}
		
		if(empty($result)) $this->Logger("Parse Error.");
		
		return $result;
	}
	
	
	private function _pase_pk10($contents){
		$arrRet = json_decode($contents,true);
		$result = [];
		
		for($i = 0; $i < count($arrRet); $i++)
		{
			$no = $arrRet[$i]['c_t'];
			$NumArr = explode(",", $arrRet[$i]['c_r']);
			if(is_numeric($no) && count($NumArr) == 10){
				$result[$no]['no'] = $arrRet[$i]['preDrawIssue'];
				$result[$no]['time'] = date("Y-m-d H:i:s");
				$result[$no]['data'] = implode('|',$NumArr);
			}
		}
		
		if(empty($result)) $this->Logger("Parse Error.");
		
		return $result;
	}
	
	private function _parse_bwlc($contents){
		//preg_match('#<table class="tb"(.*?)</table>#s',$contents,$tabledata);
		//preg_match_all('#<tr class=".*">\s+<td>(\d+)</td>\s+<td>([0-9,]+)</td>\s+<td>([0-9-: ]+)</td>\s+</tr>#',$tabledata[1],$data );
		/* $result = [];
		foreach ($data[1] as $k=>$v){
			$arrTmp=explode(',',$data[2][$k]);
			if(is_numeric($v) && count($arrTmp) == 10)
				$result[$v] = ['no'=>$v,'time'=>$data[3][$k],'data'=>implode('|',$arrTmp)];
		} */
		preg_match("#<table class='tb' width='100%'>(.*?)</table>#s",$contents,$tabledata);
		preg_match_all("#<tr class='(.*?)'><td>([0-9]+)</td><td>([0-9,]+)</td><td>([0-9-: ]+)</td></tr>#",$tabledata[1],$data );
		$result = [];
		foreach ($data[2] as $k=>$v){
			$arrTmp=explode(',',$data[3][$k]);
			if(is_numeric($v) && count($arrTmp) == 10)
				$result[$v] = ['no'=>$v,'time'=>$data[4][$k],'data'=>implode('|',$arrTmp)];
		}
		
		if(empty($result)) $this->Logger("Parse Error.");
		
		return $result;
	}
	
	
	protected function getUnOpenGameNos(){
		$ret = array();
		$sql = "SELECT id FROM gamepk10 
					WHERE kj = 0 AND kgtime < NOW() 
					AND kgtime > DATE_ADD(NOW(),INTERVAL -30 MINUTE)
					AND id NOT IN(select gameno FROM game_result WHERE gametype = '{$this->gameType}') 
					ORDER BY id DESC";
		$result = $this->db->getAll($sql);
		if(!empty($result)){
			foreach($result as $res){
				$ret[$res['id']] = $res['id'];
			}
		}
		
		if(count($result) >= 4){
			$this->switchGame(1);
		}else{
			;//$this->switchGame(0);
		}
		
		return $ret;
	}
	
	public function crawler(){
		$count = 0;		
		$result = array();
		$rets = $this->getUnOpenGameNos();
		if(count($rets) > 0){
			foreach($this->crawlerUrls as $idx=>$source){
				if(!empty($source['useno']) && $source['useno'] > 0){
					foreach($rets as $k=>$v){
						$source['url'] = $source['url'] . $k;
						break;
					}
				}
				
				$contents = $this->httpGet($source['url'] , $source['useproxy'] , $source['referurl']);
				$result = $this->$source['method']($contents);
				if(count($result) > 0){
					$hasnewdata = 0;
					foreach($rets as $k=>$v){
						if(!empty($result[$k])){
							$hasnewdata = 1;
						}
						break;
					}
						
					if($hasnewdata)
					break;
				}
			}
			
			//print_r($result);exit;
			
			foreach($rets as $k=>$v){
				if(!empty($result[$k])){
					$this->saveCrawlerData($k , $result[$k]['data']);
					$count++;
				}
			}
		}
		
		if(empty($count) && count($this->crawlerUrls) == 1){
			sleep($this->sleepSec);
		}
		
		return $count;
	}
	
	public function open($No=0){
		$isToAuto = true;
		
		if($No == 0){
			$sql = "select gameno from game_result where gametype = '{$this->gameType}' and isopen = 0 order by gameno desc limit 1";
			$result = $this->db->getRow($sql);
			if(!empty($result)){
				$No = $result["gameno"];
			}else{
				//保证采集不到但下盘时间快到时自动下注
				$sql = "SELECT id,kgtime,now() as nowtime FROM gamepk10 WHERE kj = 0 AND zdtz_r = 0 AND kgtime > NOW() ORDER BY kgtime LIMIT 1";
				$result = $this->db->getRow($sql);
				if(!empty($result)){
					$NextNo = $result['id'];
					if(strtotime($result['kgtime']) - strtotime($result['nowtime']) < 120){
						//自动投注
						$this->autoPress($NextNo-1,$NextNo);
					}
				}
				return;
			}
		}else{
			$isToAuto = false;
		
			$sql = "SELECT id,kgtime,now() as nowtime FROM gamepk10 WHERE id = '{$No}' ";
			//$this->Logger($sql);
			$result = $this->db->getRow($sql);
			if(empty($result)){
				return;
			}
			
			if(strtotime($result['kgtime']) >= strtotime($result['nowtime'])){
				return;
			}
		}
		
		$strkjNum = $this->getGameResult($No);//取开奖号码串
		$kjnum_array = explode( "|", $strkjNum );
		
		if(count($kjnum_array) == 10){ //取到了
			//更新开奖状态
			$sql = "update game_result set isopen = 1,opentime = now() where gametype = '{$this->gameType}' and gameno = '{$No}'";
			$result = $this->db->execute($sql);
			if($result === FALSE){
				$this->Logger("DB Error! : {$sql} {$this->db->error}");
			}
		
			$this->Logger("{$No} result string:" . $strkjNum);
		
			//开奖pk10
			$zj_a =  substr($No,-1); //期号尾数;
			$index = $zj_a - 1;
			if($zj_a == 0) $index = 9;
			$zj_b =  $kjnum_array[$index];//第n个数字
			$zj_c =  -1;
			$zj_result = $zj_b;
			$sql = "call web_kj_gamepk10({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			//$this->Logger($sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("pk10 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
		
			//开奖pk冠军
			$zj_a = $kjnum_array[0];
			$zj_b = $kjnum_array[1];
			$zj_c = -1;
			$zj_result = $zj_a;
			$sql = "call web_kj_gamegj10({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			//$this->Logger($sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("pkgj {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
		
			//开奖pk22
			$zj_a = $kjnum_array[0];
			$zj_b = $kjnum_array[1];
			$zj_c = $kjnum_array[2];
			$zj_result = $zj_a + $zj_b + $zj_c;
			$sql = "call web_kj_gamepk22({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			//$this->Logger($sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("pk22 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
		
			//开奖pk龙虎
			$zj_a = $kjnum_array[0];
			$zj_b = $kjnum_array[9];
			$zj_c = -1;
			$zj_result = (($kjnum_array[0] > $kjnum_array[9])?1:2);
			$sql = "call web_kj_gamepklh({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			//$this->Logger($sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("pklh {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			
			//开奖pk冠亚军
			$zj_a = $kjnum_array[0];
			$zj_b = $kjnum_array[1];
			$zj_c = -1;
			$zj_result = $zj_a + $zj_b;
			$sql = "call web_kj_gamepkgyj({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			//$this->Logger($sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("pkgyj {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			
			$sql = "SET GLOBAL group_concat_max_len = 4096";
			$this->db->execute($sql);
			$sql = "SET SESSION group_concat_max_len = 4096";
			$this->db->execute($sql);
			
			$resultIds = $this->getGameSCResult($kjnum_array);
			$resultIdsCnt = count($resultIds);
			$odds = $this->GetOddByResult(29 , $resultIds);
			$oddsCnt = count($odds);
			if($resultIdsCnt == $oddsCnt && $resultIdsCnt > 0){
				$resultIdStr = implode(",", $resultIds);
				$oddStr = implode(",", $odds);
				$sql = "call web_kj_gamepksc({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}',{$oddsCnt},'{$resultIdStr}','{$oddStr}')";
				//$this->Logger($sql);
				$result = $this->db->MultiQuery($sql);
				$this->Logger("pksc {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			}
			
		}
		
		
		
		if($isToAuto){
			//给下一盘自动投注
			$NextNo = $No+1;
			$sql = "select id from gamepk10 where id={$NextNo} and kj=0 and zdtz_r=0 limit 1";
			$result = $this->db->getRow($sql);
			if(!empty($result)){
				//自动投注
				$this->autoPress($No,$NextNo);
			}
		}
			
			
	}
	
	
	private function autoPress($No,$NextNo){
		//pk10自动投注
		$sql = "call web_tz_gamepk10_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("pk10 {$NextNo} auto press:{$result[0][0]['result']}");
	
		//pk冠军自动投注
		$sql = "call web_tz_gamegj10_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("pkgj {$NextNo} auto press:{$result[0][0]['result']}");
	
		//pk22自动投注
		$sql = "call web_tz_gamepk22_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("pk22 {$NextNo} auto press:{$result[0][0]['result']}");
	
		//pk龙虎自动投注
		$sql = "call web_tz_gamepklh_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("pklh {$NextNo} auto press:{$result[0][0]['result']}");
	
		//pk冠亚军自动投注
		$sql = "call web_tz_gamepkgyj_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("pkgyj {$NextNo} auto press:{$result[0][0]['result']}");
	}
	
	
	private function getGameSCResult($kjNoArr){//赛车开奖结果
		$a = $kjNoArr[0];
		$b = $kjNoArr[1];
		$c = -1;
		$total = $a + $b;
	
		$result[] = $total - 3;
	
		if($total >= 12){//大
			$result[] = 17;
		}else{//小
			$result[] = 18;
		}
	
		if($total % 2 == 0){//双
			$result[] = 20;
		}else{//单
			$result[] = 19;
		}
	
		//10个车道
		for($n=0;$n<10;$n++){
			if($kjNoArr[$n] >= 6){//大
				$result[] = 20 + $n * 14 + 1;
			}else{//小
				$result[] = 20 + $n * 14 + 2;
			}
				
			if($kjNoArr[$n] % 2 == 0){//双
				$result[] = 20 + $n * 14 + 4;
			}else{//单
				$result[] = 20 + $n * 14 + 3;
			}
				
			for($i=1;$i<=10;$i++){
				if($kjNoArr[$n] == $i){
					$result[] = 20 + $n * 14 + 4 + $i;
					break;
				}
			}
				
		}
	
		//龙虎
		for($n=0;$n<5;$n++){
			if($kjNoArr[$n] > $kjNoArr[9-$n]){
				$result[] = 160 + $n * 2 + 1;
			}else{
				$result[] = 160 + $n * 2 + 2;
			}
		}
	
		sort($result);
		return $result;
	}
}



<?php
include_once(dirname( __FILE__ ) ."/BaseCrawler.class.php");

class BeijingCrawler extends BaseCrawler
{
	public function __construct(){
		parent::__construct();
		$this->sleepSec = 3;
		$this->stopBegin = "00:05:00";
		$this->stopEnd = "09:00:00";
		$this->gameType = "gamebj";
		$this->gameTypes = [3,4,5,11,12,25,26,32,33,38,39,40,41,42];
		$this->crawlerUrls = array(
								0=>array('url'=>'http://ho.apiplus.net/newly.do?token=t06e9bf7eak&code=bjkl8&format=json','method'=>'_api','useproxy'=>0,'referurl'=>''),
								//0=>array('url'=>'http://chart.cp.360.cn/kaijiang/kl8/','method'=>'_parse_360','useproxy'=>0,'referurl'=>''),
								//1=>array('url'=>'http://www.bwlc.net/bulletin/prevkeno.html?num=','method'=>'_parse_bwlc','useproxy'=>0,'referurl'=>''),
								//3=>array('url'=>'http://www.bwlc.net/bulletin/prevkeno.html','method'=>'_parse_bwlc','useproxy'=>0,'referurl'=>''),
								//4=>array('url'=>'http://116.62.128.99/http.php?url=http://www.bwlc.net/bulletin/prevkeno.html?num=','method'=>'_parse_bwlc','useproxy'=>0,'referurl'=>'','useno'=>1),
								//4=>array('url'=>'http://116.62.128.99/http.php?url=http://www.bwlc.net/bulletin/prevkeno.html','method'=>'_parse_bwlc','useproxy'=>0,'referurl'=>''),
								1=>array('url'=>'http://api.api68.com/LuckTwenty/getBaseLuckTwentyList.do?date=&lotCode=10014','method'=>'_pase_1680210','useproxy'=>0,'referurl'=>'http://www.1680100.com/html/beijinkl8/bjkl8_index.html'),
								2=>array('url'=>'https://api.1399klc.com/LuZhu/Select?callback=&sc={%22LotteryCode%22:%22bjkl8%22,%22StatDate%22:%22'.date("Y-m-d" , time()).'%22}&quantity=15','method'=>'_1399klc','useproxy'=>0,'referurl'=>'')
								);
	}
	


	private function _1399klc($contents){
		$contents = str_replace(['(',')'],['',''],$contents);
		$str = json_decode($contents);
		$data = $str->Result->Data;
		$result = array();
	
		foreach ($data as $item) {
			$no = $item->Data->issueNo;
			$result[$no]['no'] = $no;
			$result[$no]['time'] = $item->Data->awardTime;
			$result[$no]['data'] = str_replace(',', '|', $item->Data->result);
		}
	
	
		if(empty($result)) $this->Logger("Parse Error.");
	
		return $result;
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
	
	
	private function _pase_1680210($contents){
		$arrRet = json_decode($contents,true);
		$arrRet = $arrRet['result']['data'];
		$result = [];
		
		for($i = 0; $i < count($arrRet); $i++)
		{
			$no = $arrRet[$i]['preDrawIssue'];
			$NumArr = explode(",", $arrRet[$i]['preDrawCode']);
			array_pop($NumArr);//去掉最后一个
			sort($NumArr);
			if(is_numeric($no) && count($NumArr) == 20){
				$result[$no]['no'] = $arrRet[$i]['preDrawIssue'];
				$result[$no]['time'] = date("Y-m-d H:i:s" , strtotime($arrRet[$i]['preDrawTime']));
				$result[$no]['data'] = implode('|',$NumArr);
			}
		}
		
		if(empty($result)) $this->Logger("Parse Error.");
		
		return $result;
	}
	
	
	private function _pase_baidu($contents){
		preg_match('#<title>(\D+)(\d+)(\D+)(\d+)(\D+)(\d+)(\D+)(\d+)(\D+)(\d+)(\D+)</title>#s',$contents,$tabledata);
		$result = [];
		$data = [];
		$no = $tabledata[4];
		if(is_numeric($no) && strlen($tabledata[6])==42){
			for($i=0;$i<40;$i=$i+2){
				$num = substr($tabledata[6],$i,2);
				$num = (int)$num;
				if($num > 0){
					$data[] = $num;
				}
			}
			
			if(count($data) == 20){
				$result[$no]['no'] = $no;
				$result[$no]['time'] = date("Y-m-d H:i:s");
				$result[$no]['data'] = implode('|',$data);
			}
		}
	
		if(empty($result)) $this->Logger("Parse Error.");
	
		return $result;
	}
	
	
	private function _parse_bwlc($contents){
		/* preg_match('#<table class="tb" width="100%">(.*?)</table>#s',$contents,$tabledata);
		preg_match_all('#<tr class=".*">\s+<td>(\d+)</td>\s+<td>([0-9,]+)</td>\s+<td>(\d+)</td>\s+<td>([0-9-: ]+)</td>\s+</tr>#',$tabledata[1],$data );
		$result = [];
		foreach ($data[1] as $k=>$v){
			$arrTmp=explode(',',$data[2][$k] );
			sort($arrTmp);
			if(is_numeric($v) && count($arrTmp) == 20)
				$result[$v] = ['no'=>$v,'time'=>$data[4][$k],'data'=>implode('|',$arrTmp)];
		} */
		preg_match("#<table class='tb' width='100%'>(.*?)</table>#s",$contents,$tabledata);
		preg_match_all("#<tr class='(.*?)'><td>([0-9]+)</td><td>([0-9,]+)</td><td>([0-9]+)</td><td>([0-9-: ]+)</td></tr>#",$tabledata[1],$data );
		$result = [];
		foreach ($data[2] as $k=>$v){
			$arrTmp=explode(',',$data[3][$k] );
			sort($arrTmp);
			if(is_numeric($v) && count($arrTmp) == 20)
				$result[$v] = ['no'=>$v,'time'=>$data[5][$k],'data'=>implode('|',$arrTmp)];
		}
		
		if(empty($result)) $this->Logger("Parse Error.");
	
		return $result;
	}
	
	private function _parse_360($contents){
		preg_match('#<tbody id=\'data-tab\'>(.*?)</tbody>#is',$contents,$html);
		$html=str_replace(['&nbsp;','<span class=\'kl8_ball\'>','</span>'],[',','',''],$html[1]);
		preg_match_all('#<tr><td>\d+</td><td>(\d+)</td><td>(.*?)</td><td>.*?</td><td>.*?</td><td>(.*?)</td></tr>#',$html,$list);
		$result=[];
		unset($list[0]);
		
		foreach($list[1] as $k=>$v){
			$no = $list[1][$k];
			$data = str_replace(",","|",substr($list[2][$k],0,-1));
			if(is_numeric($no))
				$result[$no]=['no'=>$no,'time'=>$list[3][$k],'data'=>$data];
		}
		
		if(empty($result)) $this->Logger("Parse Error.");
		
		return $result;
	}
	
	
	protected function getUnOpenGameNos(){
		$ret = array();
		$sql = "SELECT id FROM game28 
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
				$sql = "SELECT id,kgtime,now() as nowtime FROM game28 WHERE kj = 0 AND zdtz_r = 0 AND kgtime > NOW() ORDER BY kgtime LIMIT 1";
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
			
			$sql = "SELECT id,kgtime,now() as nowtime FROM game28 WHERE id = '{$No}' ";
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
		
		if(count($kjnum_array) == 20){ //取到了
			//排序
			sort($kjnum_array,SORT_NUMERIC);
			//更新开奖状态
			$sql = "update game_result set isopen = 1,opentime = now() where gametype = '{$this->gameType}' and gameno = '{$No}'";
			$result = $this->db->execute($sql);
			if($result === FALSE){
				$this->Logger("DB Error! : {$sql} {$this->db->error}");
			}
			
			$this->Logger("{$No} result string:" . $strkjNum);
			
			//开奖北京28
			$zjh_a = $kjnum_array[1] + $kjnum_array[4] + $kjnum_array[7] + $kjnum_array[10] + $kjnum_array[13] + $kjnum_array[16];
			$zjh_b = $kjnum_array[2] + $kjnum_array[5] + $kjnum_array[8] + $kjnum_array[11] + $kjnum_array[14] + $kjnum_array[17];
			$zjh_c = $kjnum_array[3] + $kjnum_array[6] + $kjnum_array[9] + $kjnum_array[12] + $kjnum_array[15] + $kjnum_array[18];
			$zj_a = substr( $zjh_a, -1 );
			$zj_b = substr( $zjh_b, -1 );
			$zj_c = substr( $zjh_c, -1 );
			$zj_result = $zj_a + $zj_b + $zj_c;
			$sql = "call web_kj_gameself28({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			$result = $this->db->MultiQuery($sql);
			$this->Logger("beijing28 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			
			//开奖北京28固定
			$sql = "call web_kj_gamebj28gd({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			$this->Logger("web_kj_gamebj28gd : " . $sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("beijing28gd {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			
			//TODO
			$sql = "SET GLOBAL group_concat_max_len = 4096";
			$this->db->execute($sql);
			$sql = "SET SESSION group_concat_max_len = 4096";
			$this->db->execute($sql);
			
			$resultIds = $this->getGameBJWWResult($kjnum_array);
			$resultIdsCnt = count($resultIds);
			$odds = $this->GetOddByResult(41 , $resultIds);
			$oddsCnt = count($odds);
			if($resultIdsCnt == $oddsCnt && $resultIdsCnt > 0){
				$resultIdStr = implode(",", $resultIds);
				$oddStr = implode(",", $odds);
				$sql = "call web_kj_gamebjww({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}',{$oddsCnt},'{$resultIdStr}','{$oddStr}')";
				//$this->Logger($sql);
				$result = $this->db->MultiQuery($sql);
				$this->Logger("bj ww {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			}
			
			$resultIds = $this->getGameBJDWResult($kjnum_array);
			$resultIdsCnt = count($resultIds);
			$odds = $this->GetOddByResult(42 , $resultIds);
			$oddsCnt = count($odds);
			if($resultIdsCnt == $oddsCnt && $resultIdsCnt > 0){
				$resultIdStr = implode(",", $resultIds);
				$oddStr = implode(",", $odds);
				$sql = "call web_kj_gamebjdw({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}',{$oddsCnt},'{$resultIdStr}','{$oddStr}')";
				//$this->Logger($sql);
				$result = $this->db->MultiQuery($sql);
				$this->Logger("bj dw {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			}
			
			
			
			
			
			//开奖北京36
			$zjh_a = $kjnum_array[1] + $kjnum_array[4] + $kjnum_array[7] + $kjnum_array[10] + $kjnum_array[13] + $kjnum_array[16];
			$zjh_b = $kjnum_array[2] + $kjnum_array[5] + $kjnum_array[8] + $kjnum_array[11] + $kjnum_array[14] + $kjnum_array[17];
			$zjh_c = $kjnum_array[3] + $kjnum_array[6] + $kjnum_array[9] + $kjnum_array[12] + $kjnum_array[15] + $kjnum_array[18];
			$zj_a = substr( $zjh_a, -1 );
			$zj_b = substr( $zjh_b, -1 );
			$zj_c = substr( $zjh_c, -1 );
			$zj_result = $this->getGame36Result($zj_a,$zj_b,$zj_c);
			$sql = "call web_kj_gamebj36({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			//$this->Logger($sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("beijing36 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			
			//开奖北京16
			$zjh_a = $kjnum_array[0] + $kjnum_array[3] + $kjnum_array[6] + $kjnum_array[9] + $kjnum_array[12] + $kjnum_array[15];
			$zj_a = ($zjh_a % 6) + 1;
			$zjh_b = $kjnum_array[1] + $kjnum_array[4] + $kjnum_array[7] + $kjnum_array[10] + $kjnum_array[13] + $kjnum_array[16];
			$zj_b = ($zjh_b % 6) + 1;
			$zjh_c = $kjnum_array[2] + $kjnum_array[5] + $kjnum_array[8] + $kjnum_array[11] + $kjnum_array[14] + $kjnum_array[17];
			$zj_c = ($zjh_c % 6) + 1;
			$zj_result = $zj_a + $zj_b + $zj_c;
			$sql = "call web_kj_gamebj16({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			//$this->Logger($sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("beijing16 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			
			
			//开奖北京11
			$zjh_a = $kjnum_array[0] + $kjnum_array[3] + $kjnum_array[6] + $kjnum_array[9] + $kjnum_array[12] + $kjnum_array[15];
			$zjh_b = $kjnum_array[2] + $kjnum_array[5] + $kjnum_array[8] + $kjnum_array[11] + $kjnum_array[14] + $kjnum_array[17];
			$zj_a = ($zjh_a % 6) + 1;
			$zj_b = ($zjh_b % 6) + 1;
			$zj_c = -1;
			$zj_result = $zj_a + $zj_b;
			$sql = "call web_kj_gamebj11({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			$this->Logger($sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("beijing11 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			
				
			//开奖蛋蛋16
			$zjh_a = $kjnum_array[0] + $kjnum_array[1] + $kjnum_array[2] + $kjnum_array[3] + $kjnum_array[4] + $kjnum_array[5];
			$zj_a = ($zjh_a % 6) + 1;
			$zjh_b = $kjnum_array[6] + $kjnum_array[7] + $kjnum_array[8] + $kjnum_array[9] + $kjnum_array[10] + $kjnum_array[11];
			$zj_b = ($zjh_b % 6) + 1;
			$zjh_c = $kjnum_array[12] + $kjnum_array[13] + $kjnum_array[14] + $kjnum_array[15] + $kjnum_array[16] + $kjnum_array[17];
			$zj_c = ($zjh_c % 6) + 1;
			$zj_result = $zj_a + $zj_b + $zj_c;
			$sql = "call web_kj_game16({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			$this->Logger($sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("dandan16 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
				
			//开奖蛋蛋11
			$zjh_a = $kjnum_array[0] + $kjnum_array[1] + $kjnum_array[2] + $kjnum_array[3] + $kjnum_array[4] + $kjnum_array[5];
			$zjh_b = $kjnum_array[12] + $kjnum_array[13] + $kjnum_array[14] + $kjnum_array[15] + $kjnum_array[16] + $kjnum_array[17];
			$zj_a = ($zjh_a % 6) + 1;
			$zj_b = ($zjh_b % 6) + 1;
			$zj_c = -1;
			$zj_result = $zj_a + $zj_b;
			$sql = "call web_kj_game11({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			$this->Logger($sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("dandan11 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			
			
			//开奖蛋蛋36
			$zjh_a = $kjnum_array[0] + $kjnum_array[1] + $kjnum_array[2] + $kjnum_array[3] + $kjnum_array[4] + $kjnum_array[5];
			$zjh_b = $kjnum_array[6] + $kjnum_array[7] + $kjnum_array[8] + $kjnum_array[9] + $kjnum_array[10] + $kjnum_array[11];
			$zjh_c = $kjnum_array[12] + $kjnum_array[13] + $kjnum_array[14] + $kjnum_array[15] + $kjnum_array[16] + $kjnum_array[17];
			$zj_a = substr( $zjh_a, -1 );
			$zj_b = substr( $zjh_b, -1 );
			$zj_c = substr( $zjh_c, -1 );
			$zj_result = $this->getGame36Result($zj_a,$zj_b,$zj_c);
			$sql = "call web_kj_game36({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			//$this->Logger($sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("dandan36 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			
			//开奖蛋蛋28
			$zjh_a = $kjnum_array[0] + $kjnum_array[1] + $kjnum_array[2] + $kjnum_array[3] + $kjnum_array[4] + $kjnum_array[5];
			$zjh_b = $kjnum_array[6] + $kjnum_array[7] + $kjnum_array[8] + $kjnum_array[9] + $kjnum_array[10] + $kjnum_array[11];
			$zjh_c = $kjnum_array[12] + $kjnum_array[13] + $kjnum_array[14] + $kjnum_array[15] + $kjnum_array[16] + $kjnum_array[17];
			$zj_a = substr( $zjh_a, -1 );
			$zj_b = substr( $zjh_b, -1 );
			$zj_c = substr( $zjh_c, -1 );
			$zj_result = $zj_a + $zj_b + $zj_c;
			$sql = "call web_kj_game28({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			$result = $this->db->MultiQuery($sql);
			$this->Logger("dandan28 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			
			
			//开奖蛋蛋28固定
			$sql = "call web_kj_game28gd({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			$this->Logger("web_kj_game28gd : " . $sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("dandan28gd {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			
			$sql = "SET GLOBAL group_concat_max_len = 4096";
			$this->db->execute($sql);
			$sql = "SET SESSION group_concat_max_len = 4096";
			$this->db->execute($sql);
			
			$resultIds = $this->getGameWWResult($kjnum_array);
			$resultIdsCnt = count($resultIds);
			$odds = $this->GetOddByResult(25 , $resultIds);
			$oddsCnt = count($odds);
			if($resultIdsCnt == $oddsCnt && $resultIdsCnt > 0){
				$resultIdStr = implode(",", $resultIds);
				//$this->Logger("resultIds : " . $resultIdStr);
				$oddStr = implode(",", $odds);
				//$this->Logger("odds : " . $oddStr);
				$sql = "call web_kj_gameww({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}',{$oddsCnt},'{$resultIdStr}','{$oddStr}')";
				//$this->Logger($sql);
				$result = $this->db->MultiQuery($sql);
				$this->Logger("dandanww {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			}
				
				
			$resultIds = $this->getGameDWResult($kjnum_array);
			$resultIdsCnt = count($resultIds);
			$odds = $this->GetOddByResult(26 , $resultIds);
			$oddsCnt = count($odds);
			if($resultIdsCnt == $oddsCnt && $resultIdsCnt > 0){
				$resultIdStr = implode(",", $resultIds);
				$oddStr = implode(",", $odds);
				$sql = "call web_kj_gamedw({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}',{$oddsCnt},'{$resultIdStr}','{$oddStr}')";
				//$this->Logger($sql);
				$result = $this->db->MultiQuery($sql);
				$this->Logger("dandandw {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			}
		}
		
		
		
		if($isToAuto){
			//给下一盘自动投注
			$NextNo = $No+1;
			$sql = "select id from game28 where id={$NextNo} and kj=0 and zdtz_r=0 limit 1";
			$result = $this->db->getRow($sql);
			if(!empty($result)){
				//自动投注
				$this->autoPress($No,$NextNo);
			}
		}
			
			
	}
	
	private function autoPress($No,$NextNo){
		//蛋蛋28自动投注
		$sql = "call web_tz_game28_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("dandan28 {$NextNo} auto press:{$result[0][0]['result']}");
		
		//蛋蛋36自动投注
		$sql = "call web_tz_game36_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("dandan36 {$NextNo} auto press:{$result[0][0]['result']}");
		
		//蛋蛋11自动投注
		$sql = "call web_tz_game11_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("dandan11 {$NextNo} auto press:{$result[0][0]['result']}");
		
		//蛋蛋16自动投注
		$sql = "call web_tz_game16_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("dandan16 {$NextNo} auto press:{$result[0][0]['result']}");
		
		
		//北京28自动投注
		$sql = "call web_tz_gameself28_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("beijing28 {$NextNo} auto press:{$result[0][0]['result']}");
		
		//北京36自动投注
		$sql = "call web_tz_gamebj36_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("beijing36 {$NextNo} auto press:{$result[0][0]['result']}");
		
		//北京16自动投注
		$sql = "call web_tz_gamebj16_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("beijing16 {$NextNo} auto press:{$result[0][0]['result']}");
		
		//北京11自动投注
		$sql = "call web_tz_gamebj11_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("beijing11 {$NextNo} auto press:{$result[0][0]['result']}");
	}
	
	
	private function getGameWWResult($kjNoArr){//蛋蛋外围开奖结果
		$zjhA = $kjNoArr[0] + $kjNoArr[1] + $kjNoArr[2] + $kjNoArr[3] + $kjNoArr[4] + $kjNoArr[5];
		$zjhB = $kjNoArr[6] + $kjNoArr[7] + $kjNoArr[8] + $kjNoArr[9] + $kjNoArr[10] + $kjNoArr[11];
		$zjhC = $kjNoArr[12] + $kjNoArr[13] + $kjNoArr[14] + $kjNoArr[15] + $kjNoArr[16] + $kjNoArr[17];
		$a = substr( $zjhA, -1 );
		$b = substr( $zjhB, -1 );
		$c = substr( $zjhC, -1 );
	
		$total = $a + $b + $c;
		$result = [];
	
		$is_max = 0;
		if($total >= 14){//大
			$is_max = 1;
			$result[] = 1;
			if($total >= 22){//极大
				$result[] = 9;
			}
		}else{//小
			$result[] = 6;
			if($total <= 5){
				$result[] = 4;//极小
			}
		}
	
		if($total % 2 == 0){//双
			$result[] = 5;
			if($is_max){
				$result[] = 8;//大双
			}else{
				$result[] = 7;//小双
			}
		}else{//单
			$result[] = 0;
			if($is_max){
				$result[] = 3;//大单
			}else{
				$result[] = 2;//小单
			}
		}
	
		if(in_array($total , [0,3,6,9,12,15,18,21,24,27])) $result[] = 10;
		if(in_array($total , [1,4,7,10,13,16,19,22,25])) $result[] = 11;
		if(in_array($total , [2,5,8,11,14,17,20,23,26])) $result[] = 12;
	
		sort($result);
		return $result;
	}
	
	
	private function getGameDWResult($kjNoArr){//蛋蛋定位开奖结果
		$zjhA = $kjNoArr[0] + $kjNoArr[1] + $kjNoArr[2] + $kjNoArr[3] + $kjNoArr[4] + $kjNoArr[5];
		$zjhB = $kjNoArr[6] + $kjNoArr[7] + $kjNoArr[8] + $kjNoArr[9] + $kjNoArr[10] + $kjNoArr[11];
		$zjhC = $kjNoArr[12] + $kjNoArr[13] + $kjNoArr[14] + $kjNoArr[15] + $kjNoArr[16] + $kjNoArr[17];
		$a = substr( $zjhA, -1 );
		$b = substr( $zjhB, -1 );
		$c = substr( $zjhC, -1 );
	
		$total = $a + $b + $c;
		$result = [];
	
		$is_max = 0;
		if($total >= 14){//大
			$is_max = 1;
			$result[] = 1;
			if($total >= 22){//极大
				$result[] = 9;
			}
		}else{//小
			$result[] = 6;
			if($total <= 5){
				$result[] = 4;//极小
			}
		}
	
		if($total % 2 == 0){//双
			$result[] = 5;
			if($is_max){
				$result[] = 8;//大双
			}else{
				$result[] = 7;//小双
			}
		}else{//单
			$result[] = 0;
			if($is_max){
				$result[] = 3;//大单
			}else{
				$result[] = 2;//小单
			}
		}
	
		if($a > $c) $result[] = 10;
		if($a < $c) $result[] = 11;
		if($a == $c) $result[] = 12;
	
		if($a >= 5){//大
			$result[] = 13;
		}else{//小
			$result[] = 14;
		}
	
		if($a % 2 == 0){//双
			$result[] = 16;
		}else{//单
			$result[] = 15;
		}
	
		$result[] = $a + 17;
	
	
		if($b >= 5){//大
			$result[] = 27;
		}else{//小
			$result[] = 28;
		}
	
		if($b % 2 == 0){//双
			$result[] = 30;
		}else{//单
			$result[] = 29;
		}
	
		$result[] = $b + 31;
	
	
		if($c >= 5){//大
			$result[] = 41;
		}else{//小
			$result[] = 42;
		}
	
		if($c % 2 == 0){//双
			$result[] = 44;
		}else{//单
			$result[] = 43;
		}
	
		$result[] = $c + 45;
	
		sort($result);
		return $result;
	}
	
	
	
	
	
	
	private function getGameBJWWResult($kjNoArr){//北京外围开奖结果
		$zjhA = $kjNoArr[1] + $kjNoArr[4] + $kjNoArr[7] + $kjNoArr[10] + $kjNoArr[13] + $kjNoArr[16];
		$zjhB = $kjNoArr[2] + $kjNoArr[5] + $kjNoArr[8] + $kjNoArr[11] + $kjNoArr[14] + $kjNoArr[17];
		$zjhC = $kjNoArr[3] + $kjNoArr[6] + $kjNoArr[9] + $kjNoArr[12] + $kjNoArr[15] + $kjNoArr[18];
		$a = substr( $zjhA, -1 );
		$b = substr( $zjhB, -1 );
		$c = substr( $zjhC, -1 );
	
		$total = $a + $b + $c;
		$result = [];
	
		$is_max = 0;
		if($total >= 14){//大
			$is_max = 1;
			$result[] = 1;
			if($total >= 22){//极大
				$result[] = 9;
			}
		}else{//小
			$result[] = 6;
			if($total <= 5){
				$result[] = 4;//极小
			}
		}
	
		if($total % 2 == 0){//双
			$result[] = 5;
			if($is_max){
				$result[] = 8;//大双
			}else{
				$result[] = 7;//小双
			}
		}else{//单
			$result[] = 0;
			if($is_max){
				$result[] = 3;//大单
			}else{
				$result[] = 2;//小单
			}
		}
	
		if(in_array($total , [0,3,6,9,12,15,18,21,24,27])) $result[] = 10;
		if(in_array($total , [1,4,7,10,13,16,19,22,25])) $result[] = 11;
		if(in_array($total , [2,5,8,11,14,17,20,23,26])) $result[] = 12;
	
		sort($result);
		return $result;
	}
	
	
	private function getGameBJDWResult($kjNoArr){//北京定位开奖结果
		$zjhA = $kjNoArr[1] + $kjNoArr[4] + $kjNoArr[7] + $kjNoArr[10] + $kjNoArr[13] + $kjNoArr[16];
		$zjhB = $kjNoArr[2] + $kjNoArr[5] + $kjNoArr[8] + $kjNoArr[11] + $kjNoArr[14] + $kjNoArr[17];
		$zjhC = $kjNoArr[3] + $kjNoArr[6] + $kjNoArr[9] + $kjNoArr[12] + $kjNoArr[15] + $kjNoArr[18];
		$a = substr( $zjhA, -1 );
		$b = substr( $zjhB, -1 );
		$c = substr( $zjhC, -1 );
	
		$total = $a + $b + $c;
		$result = [];
	
		$is_max = 0;
		if($total >= 14){//大
			$is_max = 1;
			$result[] = 1;
			if($total >= 22){//极大
				$result[] = 9;
			}
		}else{//小
			$result[] = 6;
			if($total <= 5){
				$result[] = 4;//极小
			}
		}
	
		if($total % 2 == 0){//双
			$result[] = 5;
			if($is_max){
				$result[] = 8;//大双
			}else{
				$result[] = 7;//小双
			}
		}else{//单
			$result[] = 0;
			if($is_max){
				$result[] = 3;//大单
			}else{
				$result[] = 2;//小单
			}
		}
	
		if($a > $c) $result[] = 10;
		if($a < $c) $result[] = 11;
		if($a == $c) $result[] = 12;
	
		if($a >= 5){//大
			$result[] = 13;
		}else{//小
			$result[] = 14;
		}
	
		if($a % 2 == 0){//双
			$result[] = 16;
		}else{//单
			$result[] = 15;
		}
	
		$result[] = $a + 17;
	
	
		if($b >= 5){//大
			$result[] = 27;
		}else{//小
			$result[] = 28;
		}
	
		if($b % 2 == 0){//双
			$result[] = 30;
		}else{//单
			$result[] = 29;
		}
	
		$result[] = $b + 31;
	
	
		if($c >= 5){//大
			$result[] = 41;
		}else{//小
			$result[] = 42;
		}
	
		if($c % 2 == 0){//双
			$result[] = 44;
		}else{//单
			$result[] = 43;
		}
	
		$result[] = $c + 45;
	
		sort($result);
		return $result;
	}
	
	
	
}



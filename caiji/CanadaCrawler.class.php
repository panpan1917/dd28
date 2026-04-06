<?php
include_once(dirname( __FILE__ ) ."/BaseCrawler.class.php");

class CanadaCrawler extends BaseCrawler
{
	public function __construct(){
		parent::__construct();
		$this->sleepSec = 1;
		$this->stopBegin = "19:05:00";//20:05:00    19:05:00
		$this->stopEnd = "19:55:00";//20:55:00    19:55:00
		$this->gameType = "gamecan";
		$this->gameTypes = [8,9,10,13,27,28,35];
		$this->crawlerUrls = array(
								//0=>array('url'=>'http://47.74.129.187/jnd/caiji_jnd.php?key=get_cache','method'=>'_parse_jnd2','useproxy'=>0,'referurl'=>''),
								//0=>array('url'=>'http://47.90.52.104:180/jnd.php?key=get_cache','method'=>'_parse_jnd','useproxy'=>0,'referurl'=>''),
								//1=>array('url'=>'http://47.90.47.199:9999/jnd.php','method'=>'_parse_jnd','useproxy'=>0,'referurl'=>''),
								0=>array('url'=>'http://ho.apiplus.net/newly.do?token=t06129bf7eak&code=cakeno&format=json','method'=>'_api','useproxy'=>0,'referurl'=>''),
								//0=>array('url'=>'http://e.apiplus.net/newly.do?token=td7a4f3a2ak&code=cakeno&format=json','method'=>'_api','useproxy'=>0,'referurl'=>''),
								1=>array('url'=>'http://47.91.250.227:9898/fetchcan1.php','method'=>'_parse_jnd','useproxy'=>0,'referurl'=>''),
								2=>array('url'=>'http://47.91.250.227:9898/fetchcan2.php','method'=>'_parse_jnd','useproxy'=>0,'referurl'=>''),
								);
		
	}
	
	
	private function _api($contents){
		$str = json_decode($contents);
		$data = $str->data;
		$result = array();
		
		$sql = "select no_begin_time,no_end_time from game_catch_config where gamekind = '{$this->gameType}'";
		$row = $this->db->getRow($sql);
		if(!empty($row)){
			$no_begin_time = $row['no_begin_time'];
			$no_end_time = $row['no_end_time'];
			
			if(date("H",time()) >= date("H",strtotime($no_end_time)) && date("H",time()) <= date("H",strtotime($no_begin_time)+10000))
					return $result;
		}
			
		foreach ($data as $item) {
			$no = $item->expect;
			$result[$no]['no'] = $no;
			$result[$no]['time'] = $item->opentime;
			$result[$no]['data'] = str_replace(',', '|', $item->opencode);
		}
	
		if(empty($result)) $this->Logger("Parse Error.");
	
		return $result;
	}
	
	
	private function _getDiffHourCanadaToChina($theDate)
	{
		$diffHour = 16;
		$Year = date("Y",strtotime($theDate));
		$beginTime = "";
		$endTime = "";
		$sql = "select c_begintime,c_endtime from canada_timezone where c_year = {$Year}";
		$result = $this->db->getRow($sql);
		if(!empty($result))
		{
			$beginTime = $result['c_begintime'];
			$endTime = $result['c_endtime'];
			if(strtotime($theDate) >= strtotime($beginTime) && strtotime($theDate) < strtotime($endTime))
				$diffHour = 15;
		}
		return $diffHour;
	}
	
	private function _parse_1680180($contents){
		$arrRet = json_decode($contents,true);
		$result = [];
		
		$no = $arrRet['l_t'];
		$NumArr = explode(",", $arrRet['l_r']);
		sort($NumArr);
		if(is_numeric($no) && count($NumArr) == 20){
			$result[$no]['no'] = $no;
			$sql = "SELECT kgtime as time FROM gamecan28 WHERE id={$no} limit 1";
			$ret = $this->db->getRow($sql);
			if(!empty($ret)){
				$time = $ret['time'];
			}else{
				return [];
			}
			$result[$no]['time'] = $time;
			$result[$no]['data'] = implode('|',$NumArr);
		}
		
		if(empty($result)) $this->Logger("Parse Error.");
		
		$rets = $this->_checkDrawTime($result);
		if(!$rets){
			$this->Logger("Error canada open time");
			return [];
		}
		
		return $result;
	}
	
	private function _parse_168879($contents){
		$arrRet = json_decode($contents,true);
		$result = [];
		
		$no = $arrRet['l_t'];
		$NumArr = explode(",", $arrRet['l_r']);
		sort($NumArr);
		if(is_numeric($no) && count($NumArr) == 20){
			$result[$no]['no'] = $no;
			$sql = "SELECT kgtime as time FROM gamecan28 WHERE id={$no} limit 1";
			$ret = $this->db->getRow($sql);
			if(!empty($ret)){
				$time = $ret['time'];
			}else{
				return [];
			}
			$result[$no]['time'] = $time;
			$result[$no]['data'] = implode('|',$NumArr);
		}
		
		if(empty($result)) $this->Logger("Parse Error.");
		
		$rets = $this->_checkDrawTime($result);
		if(!$rets){
			$this->Logger("Error canada open time");
			return [];
		}
		
		return $result;
	}
	
	
	private function _parse_jnd2($contents){
		$arrRet = json_decode($contents,true);
		$result = [];
		for($i = 0; $i < count($arrRet); $i++)
		{
			$no = $arrRet[$i]['drawNbr'];
			if(is_numeric($no)){
				$result[$no]['no'] = $no;
				$result[$no]['time'] = date("Y-m-d H:i:s",strtotime($arrRet[$i]['date']));
				$result[$no]['data'] = implode("|",$arrRet[$i]['drawNbrs']);
			}
		}
	
		if(empty($result)) $this->Logger("Parse Error.");
	
		$rets = $this->_checkDrawTime($result);
			if(!$rets){
		$this->Logger("Error canada open time");
			return [];
		}
	
		return $result;
	}
	
	
	private function _parse_jnd($contents){
		$arrRet = json_decode($contents,true);
		$diffHour = 15;
		$result = [];
		for($i = 0; $i < count($arrRet); $i++)
		{
			$no = $arrRet[$i]['drawNbr'];
			if(is_numeric($no)){
				$result[$no]['no'] = $no;
				$day = date("Y-m-d",strtotime($arrRet[$i]['drawDate']));
				$tim = date("H:i:s",strtotime($arrRet[$i]['drawTime'] ));
				$openTime = $day." ". $tim;
				if($i == 0) //更新时差
					$diffHour = $this->_getDiffHourCanadaToChina($openTime);
				$result[$no]['time'] = date("Y-m-d H:i:s",strtotime($day." ". $tim . " +{$diffHour} hour"));
				$result[$no]['data'] = implode("|",$arrRet[$i]['drawNbrs']);
			}
		}
		
		if(empty($result)) $this->Logger("Parse Error.");
		
		$rets = $this->_checkDrawTime($result);
		if(!$rets){
			$this->Logger("Error canada open time");
			return [];
		}
		
		return $result;
	}
	
	private function _checkDrawTime($data){
		if(count($data) >= 2){
			$intervalTime = 210;
			$sql = "select no_interval_second from game_catch_config where gamekind = '{$this->gameType}'";
			$result = $this->db->getRow($sql);
			if(!empty($result)){
				$intervalTime = $result['no_interval_second'];
			}
			
			$idx=0;
			$Time0=0;
			$Time1=0;
			$No0=0;
			$No1=0;
			foreach($data as $k=>$v){
				if($idx == 0){
					$Time0 = strtotime($v['time']);
					$No0 = strtotime($v['no']);
				}
				if($idx == 1){
					$Time1 = strtotime($v['time']);
					$No1 = strtotime($v['no']);
				}
				
				if($idx >= 1) break;
				
				$idx++;
			}
				
			$newIntervalTime = $Time0 - $Time1;
			if($No0 - $No1 == 1 && $newIntervalTime <> $intervalTime && $newIntervalTime < 300) //时间间隔对不上，停止开奖
			{
				//echo "Time0:{$Time0} Time1:{$Time1} No0:{$No0} No1:{$No1} intervalTime:{$intervalTime}\n";
				$sql = "insert into system_msg(msg_type,msg_content,msg_time) values('加拿大采集问题','系统检测到两盘时间间隔{$newIntervalTime}与设定值{$intervalTime},不一致，暂停了采集结果!')";
				$this->db->execute($sql);
				return false;
			}
		}
	
		return true;
	}
	
	
	protected function getUnOpenGameNos(){
		$ret = array();
		$sql = "SELECT id FROM gamecan28 
					WHERE kj = 0 AND kgtime < NOW()
					AND kgtime > DATE_ADD(NOW(),INTERVAL -180 MINUTE)
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
	
	
	private function _createNewNo($No,$time){
		//if(time() >= strtotime(date("Y-m-d") . " " . $this->stopBegin) && time() <= strtotime(date("Y-m-d") . " " . $this->stopEnd)){
		//	return;
		//}else{
			$sql = "call web_gameno_addcan({$No},'{$time}',10)";
			$result = $this->db->MultiQuery($sql);
			$this->Logger("create new game result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
		//}
	}
	
	public function crawler(){
		$count = 0;	
		$result = array();
		$rets = $this->getUnOpenGameNos();
		
		foreach($this->crawlerUrls as $idx=>$source){
			$contents = $this->httpGet($source['url'] , $source['useproxy'] , $source['referurl']);
			$result = $this->$source['method']($contents);
			if(count($result) > 0){
				$hasnewdata = 0;
				if(count($rets) > 0){
					foreach($rets as $k=>$v){
						if(!empty($result[$k])){
							$hasnewdata = 1;
						}
						break;
					}
				}
					
				if($hasnewdata)
				break;
			}
		}
		
		//print_r($result);exit;
			
		if(count($rets) > 0){
			foreach($rets as $k=>$v){
				if(!empty($result[$k])){
					$this->saveCrawlerData($k , $result[$k]['data']);
					$count++;
				}
			}
			
			/* if($count > 0){
				foreach($result as $k=>$v){
					$this->_createNewNo($result[$k]['no'],$result[$k]['time']);
					break;
				}
			} */
		}
		
		/* if($count == 0 && count($result) > 0){
			$sql = "select gameno FROM game_result WHERE gametype = '{$this->gameType}' ORDER BY id DESC limit 1";
			$ret = $this->db->getRow($sql);
			$gameno = $ret['gameno'];
			
			foreach($result as $k=>$v){
				if($result[$k]['no'] > $gameno){
					$this->_createNewNo($result[$k]['no'],$result[$k]['time']);
				}
				break;
			}
		} */
		
		
		if(count($result) > 0){
			foreach($result as $k=>$v){
				$this->_createNewNo($result[$k]['no'],$result[$k]['time']);
				break;
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
				$sql = "SELECT id,kgtime,now() as nowtime FROM gamecan28 WHERE kj = 0 AND zdtz_r = 0 AND kgtime > NOW() ORDER BY kgtime LIMIT 1";
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
		
			$sql = "SELECT id,kgtime,now() as nowtime FROM gamecan28 WHERE id = '{$No}' ";
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
		
			//开奖加拿大36
			$zjh_a = $kjnum_array[1] + $kjnum_array[4] + $kjnum_array[7] + $kjnum_array[10] + $kjnum_array[13] + $kjnum_array[16];
			$zjh_b = $kjnum_array[2] + $kjnum_array[5] + $kjnum_array[8] + $kjnum_array[11] + $kjnum_array[14] + $kjnum_array[17];
			$zjh_c = $kjnum_array[3] + $kjnum_array[6] + $kjnum_array[9] + $kjnum_array[12] + $kjnum_array[15] + $kjnum_array[18];
			$zj_a = substr( $zjh_a, -1 );
			$zj_b = substr( $zjh_b, -1 );
			$zj_c = substr( $zjh_c, -1 );
			$zj_result = $this->getGame36Result($zj_a,$zj_b,$zj_c);
			$sql = "call web_kj_gamecan36({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			$result = $this->db->MultiQuery($sql);
			$this->Logger("canada16 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
		
			//开奖加拿大16
			$zjh_a = $kjnum_array[0] + $kjnum_array[3] + $kjnum_array[6] + $kjnum_array[9] + $kjnum_array[12] + $kjnum_array[15];
			$zjh_b = $kjnum_array[1] + $kjnum_array[4] + $kjnum_array[7] + $kjnum_array[10] + $kjnum_array[13] + $kjnum_array[16];
			$zjh_c = $kjnum_array[2] + $kjnum_array[5] + $kjnum_array[8] + $kjnum_array[11] + $kjnum_array[14] + $kjnum_array[17];
			$zj_a = ($zjh_a % 6) + 1;
			$zj_b = ($zjh_b % 6) + 1;
			$zj_c = ($zjh_c % 6) + 1;
			$zj_result = $zj_a + $zj_b + $zj_c;
			$sql = "call web_kj_gamecan16({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			$result = $this->db->MultiQuery($sql);
			$this->Logger("canada11 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
		
			//开奖加拿大11
			$zjh_a = $kjnum_array[0] + $kjnum_array[3] + $kjnum_array[6] + $kjnum_array[9] + $kjnum_array[12] + $kjnum_array[15];
			$zjh_b = $kjnum_array[2] + $kjnum_array[5] + $kjnum_array[8] + $kjnum_array[11] + $kjnum_array[14] + $kjnum_array[17];
			$zj_a = ($zjh_a % 6) + 1;
			$zj_b = ($zjh_b % 6) + 1;
			$zj_c = -1;
			$zj_result = $zj_a + $zj_b;
			$sql = "call web_kj_gamecan11({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			$result = $this->db->MultiQuery($sql);
			$this->Logger("canada36 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			
			//开奖加拿大28
			$zjh_a = $kjnum_array[1] + $kjnum_array[4] + $kjnum_array[7] + $kjnum_array[10] + $kjnum_array[13] + $kjnum_array[16];
			$zjh_b = $kjnum_array[2] + $kjnum_array[5] + $kjnum_array[8] + $kjnum_array[11] + $kjnum_array[14] + $kjnum_array[17];
			$zjh_c = $kjnum_array[3] + $kjnum_array[6] + $kjnum_array[9] + $kjnum_array[12] + $kjnum_array[15] + $kjnum_array[18];
			$zj_a = substr( $zjh_a, -1 );
			$zj_b = substr( $zjh_b, -1 );
			$zj_c = substr( $zjh_c, -1 );
			$zj_result = $zj_a + $zj_b + $zj_c;
			$sql = "call web_kj_gamecan28({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			$result = $this->db->MultiQuery($sql);
			$this->Logger("canada28 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
				
			$sql = "call web_kj_gamecan28gd({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			$this->Logger("web_kj_gamecan28gd : " . $sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("canada28gd {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			
			$sql = "SET GLOBAL group_concat_max_len = 4096";
			$this->db->execute($sql);
			$sql = "SET SESSION group_concat_max_len = 4096";
			$this->db->execute($sql);
			
			$resultIds = $this->getGameWWResult($kjnum_array);
			$resultIdsCnt = count($resultIds);
			$odds = $this->GetOddByResult(27 , $resultIds);
			$oddsCnt = count($odds);
			if($resultIdsCnt == $oddsCnt && $resultIdsCnt > 0){
				$resultIdStr = implode(",", $resultIds);
				$oddStr = implode(",", $odds);
				$sql = "call web_kj_gamecanww({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}',{$oddsCnt},'{$resultIdStr}','{$oddStr}')";
				$result = $this->db->MultiQuery($sql);
				$this->Logger("canadaww {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			}
				
			$resultIds = $this->getGameDWResult($kjnum_array);
			$resultIdsCnt = count($resultIds);
			$odds = $this->GetOddByResult(28 , $resultIds);
			$oddsCnt = count($odds);
			if($resultIdsCnt == $oddsCnt && $resultIdsCnt > 0){
				$resultIdStr = implode(",", $resultIds);
				$oddStr = implode(",", $odds);
				$sql = "call web_kj_gamecandw({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}',{$oddsCnt},'{$resultIdStr}','{$oddStr}')";
				$result = $this->db->MultiQuery($sql);
				$this->Logger("canadadw {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			}
		}
		
		
		
		if($isToAuto){
			//给下一盘自动投注
			$NextNo = $No+1;
			$sql = "select id from gamecan28 where id={$NextNo} and kj=0 and zdtz_r=0 limit 1";
			$result = $this->db->getRow($sql);
			if(!empty($result)){
				//自动投注
				$this->autoPress($No,$NextNo);
			}
		}
			
			
	}
	
	
	private function autoPress($No,$NextNo){
		//加拿大28自动投注
		$sql = "call web_tz_gamecan28_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("canada28 {$NextNo} auto press:{$result[0][0]['result']}");
	
		//加拿大36自动投注
		$sql = "call web_tz_gamecan36_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("canada36 {$NextNo} auto press:{$result[0][0]['result']}");
	
		//加拿大16自动投注
		$sql = "call web_tz_gamecan16_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("canada28 {$NextNo} auto press:{$result[0][0]['result']}");
	
		//加拿大11自动投注
		$sql = "call web_tz_gamecan11_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("canada36 {$NextNo} auto press:{$result[0][0]['result']}");
	}
	
	
	private function getGameWWResult($kjNoArr){//外围开奖结果
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
	
	
	private function getGameDWResult($kjNoArr){//定位开奖结果
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



<?php
include_once(dirname( __FILE__ ) ."/BaseCrawler.class.php");

class AirshipCrawler extends BaseCrawler
{
	public function __construct(){
		parent::__construct();
		$this->sleepSec = 3;
		$this->stopBegin = "04:20:00";
		$this->stopEnd = "13:00:00";
		$this->gameType = "gameairship";
		$this->gameTypes = [43,44,45,46,47];
		$this->crawlerUrls = array(
								//1=>array('url'=>'http://www.luckyairship.com/history.html','method'=>'_parse_airship','useproxy'=>0,'referurl'=>'','useno'=>1),
								0=>array('url'=>'http://ho.apiplus.net/newly.do?token=t0612bf7eak&code=mlaft&format=json','method'=>'_api','useproxy'=>0,'referurl'=>'')
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
	
	
	private function _parse_airship($contents){
		preg_match('#<table width="100%" border="0" cellspacing="0" cellpadding="0">(.*?)</table>#s',$contents,$tabledata);
		preg_match_all('#<tr class=".*">\s+<td>([0-9-]+)</td>\s+<td>([0-9]+)</td>\s+<td><span class="ball1">([0-9]+)</span><span class="ball1">([0-9]+)</span><span class="ball1">([0-9]+)</span><span class="ball1">([0-9]+)</span><span class="ball1">([0-9]+)</span><span class="ball1">([0-9]+)</span><span class="ball1">([0-9]+)</span><span class="ball1">([0-9]+)</span><span class="ball1">([0-9]+)</span><span class="ball1">([0-9]+)</span></td>\s+</tr>#',$tabledata[1],$data );
		$result = [];
		if(count($data[2]) == count($data[3]) && count($data[2]) == count($data[4]) && count($data[2]) == count($data[12])){
			foreach ($data[2] as $k=>$v){
				unset($arrTmp);
				$arrTmp[] = $data[3][$k];
				$arrTmp[] = $data[4][$k];
				$arrTmp[] = $data[5][$k];
				$arrTmp[] = $data[6][$k];
				$arrTmp[] = $data[7][$k];
				$arrTmp[] = $data[8][$k];
				$arrTmp[] = $data[9][$k];
				$arrTmp[] = $data[10][$k];
				$arrTmp[] = $data[11][$k];
				$arrTmp[] = $data[12][$k];
				if(is_numeric($v))
					$result[$v] = ['no'=>$v,'time'=>'','data'=>implode('|',$arrTmp)];
			}
		}
		
		if(empty($result)) $this->Logger("Parse Error.");
		
		return $result;
	}
	
	
	private function _createNewNo(){
		$sql = "select count(*) as cnt from gameairship10 where kgtime > now() order by id desc limit 1";
		$cnt = $this->db->getOne($sql);
		if($cnt == 0){
			$sql = "DELETE FROM gameairship10_users_tz WHERE time < DATE_ADD(NOW(),INTERVAL -15 DAY)";
			$this->db->execute($sql);
			$sql = "DELETE FROM gameairship10 WHERE kgtime < DATE_ADD(NOW(),INTERVAL -15 DAY)";
			$this->db->execute($sql);
				
			$sql = "DELETE FROM gameairship22_users_tz WHERE time < DATE_ADD(NOW(),INTERVAL -15 DAY)";
			$this->db->execute($sql);
			$sql = "DELETE FROM gameairship22 WHERE kgtime < DATE_ADD(NOW(),INTERVAL -15 DAY)";
			$this->db->execute($sql);
				
			$sql = "DELETE FROM gameairshipgj10_users_tz WHERE time < DATE_ADD(NOW(),INTERVAL -15 DAY)";
			$this->db->execute($sql);
			$sql = "DELETE FROM gameairshipgj10 WHERE kgtime < DATE_ADD(NOW(),INTERVAL -15 DAY)";
			$this->db->execute($sql);
				
			$sql = "DELETE FROM gameairshipgyj_users_tz WHERE time < DATE_ADD(NOW(),INTERVAL -15 DAY)";
			$this->db->execute($sql);
			$sql = "DELETE FROM gameairshipgyj WHERE kgtime < DATE_ADD(NOW(),INTERVAL -15 DAY)";
			$this->db->execute($sql);
				
			$sql = "DELETE FROM gameairshiplh_users_tz WHERE time < DATE_ADD(NOW(),INTERVAL -15 DAY)";
			$this->db->execute($sql);
			$sql = "DELETE FROM gameairshiplh WHERE kgtime < DATE_ADD(NOW(),INTERVAL -15 DAY)";
			$this->db->execute($sql);
				
				
			$sql = "select now() as nowtime";
			$nowtime = $this->db->getOne($sql);
			$currday = date("Ymd" , strtotime($nowtime));
			$currday2 = date("Y-m-d" , strtotime($nowtime));
				
			$i = 1;
			$starttime = $currday2 . " 13:09:00";
			$timestep = 0;
			while($i<=180){
				$no = $currday . substr("00".$i , -3);
				$time = date("Y-m-d H:i:s" , strtotime($starttime) + $timestep);
				$starttime = $time;
				$timestep = 300;
				$i++;
	
				$sql = "INSERT IGNORE INTO gameairship10(id,kgtime,gfid,zjpl) SELECT {$no},'{$time}',{$no},game_std_odds FROM game_config WHERE game_type = 43";
				$this->db->execute($sql);
	
				$sql = "INSERT IGNORE INTO gameairship22(id,kgtime,gfid,zjpl) SELECT {$no},'{$time}',{$no},game_std_odds FROM game_config WHERE game_type = 44";
				$this->db->execute($sql);
	
				$sql = "INSERT IGNORE INTO gameairshipgj10(id,kgtime,gfid,zjpl) SELECT {$no},'{$time}',{$no},game_std_odds FROM game_config WHERE game_type = 46";
				$this->db->execute($sql);
	
				$sql = "INSERT IGNORE INTO gameairshipgyj(id,kgtime,gfid,zjpl) SELECT {$no},'{$time}',{$no},game_std_odds FROM game_config WHERE game_type = 45";
				$this->db->execute($sql);
	
				$sql = "INSERT IGNORE INTO gameairshiplh(id,kgtime,gfid,zjpl) SELECT {$no},'{$time}',{$no},game_std_odds FROM game_config WHERE game_type = 47";
				$this->db->execute($sql);
			}
				
		}
	
	}
	
	
	protected function getUnOpenGameNos(){
		$ret = array();
		$sql = "SELECT id FROM gameairship10 
					WHERE kj = 0 AND kgtime < NOW() 
					AND kgtime > DATE_ADD(NOW(),INTERVAL -60 MINUTE)
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
		$this->_createNewNo();
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
				$sql = "SELECT id,kgtime,now() as nowtime FROM gameairship10 WHERE kj = 0 AND zdtz_r = 0 AND kgtime > NOW() ORDER BY kgtime LIMIT 1";
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
		
			$sql = "SELECT id,kgtime,now() as nowtime FROM gameairship10 WHERE id = '{$No}' ";
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
		
			//开奖飞艇10
			$zj_a =  substr($No,-1); //期号尾数;
			$index = $zj_a - 1;
			if($zj_a == 0) $index = 9;
			$zj_b =  $kjnum_array[$index];//第n个数字
			$zj_c =  -1;
			$zj_result = $zj_b;
			$sql = "call web_kj_gameairship10({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			//$this->Logger($sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("airship10 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
		
			//开奖飞艇冠军
			$zj_a = $kjnum_array[0];
			$zj_b = $kjnum_array[1];
			$zj_c = -1;
			$zj_result = $zj_a;
			$sql = "call web_kj_gameairshipgj10({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			//$this->Logger($sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("airshipgj {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
		
			//开奖飞艇22
			$zj_a = $kjnum_array[0];
			$zj_b = $kjnum_array[1];
			$zj_c = $kjnum_array[2];
			$zj_result = $zj_a + $zj_b + $zj_c;
			$sql = "call web_kj_gameairship22({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			//$this->Logger($sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("airship22 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
		
			//开奖飞艇龙虎
			$zj_a = $kjnum_array[0];
			$zj_b = $kjnum_array[9];
			$zj_c = -1;
			$zj_result = (($kjnum_array[0] > $kjnum_array[9])?1:2);
			$sql = "call web_kj_gameairshiplh({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			//$this->Logger($sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("airshiplh {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			
			//开奖飞艇冠亚军
			$zj_a = $kjnum_array[0];
			$zj_b = $kjnum_array[1];
			$zj_c = -1;
			$zj_result = $zj_a + $zj_b;
			$sql = "call web_kj_gameairshipgyj({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			//$this->Logger($sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("airshipgyj {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			
		}
		
		
		
		if($isToAuto){
			//给下一盘自动投注
			$NextNo = $No+1;
			$sql = "select id from gameairship10 where id={$NextNo} and kj=0 and zdtz_r=0 limit 1";
			$result = $this->db->getRow($sql);
			if(!empty($result)){
				//自动投注
				$this->autoPress($No,$NextNo);
			}
		}
			
			
	}
	
	
	private function autoPress($No,$NextNo){
		//飞艇10自动投注
		$sql = "call web_tz_gameairship10_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("airship10 {$NextNo} auto press:{$result[0][0]['result']}");
	
		//飞艇冠军自动投注
		$sql = "call web_tz_gameairshipgj10_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("airshipgj {$NextNo} auto press:{$result[0][0]['result']}");
	
		//飞艇22自动投注
		$sql = "call web_tz_gameairship22_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("airship22 {$NextNo} auto press:{$result[0][0]['result']}");
	
		//飞艇龙虎自动投注
		$sql = "call web_tz_gameairshiplh_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("airshiplh {$NextNo} auto press:{$result[0][0]['result']}");
	
		//飞艇冠亚军自动投注
		$sql = "call web_tz_gameairshipgyj_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("airshipgyj {$NextNo} auto press:{$result[0][0]['result']}");
	}
	
}



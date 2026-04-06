<?php
include_once(dirname( __FILE__ ) ."/BaseCrawler.class.php");

class LuckFarmCrawler extends BaseCrawler
{
	public function __construct(){
		parent::__construct();
		$this->sleepSec = 10;
		$this->stopBegin = "02:12:20";
		$this->stopEnd = "10:02:20";
		$this->gameType = "gamexync";
		$this->gameTypes = [36];
		$this->crawlerUrls = array(
								//0=>array('url'=>'http://www.cqcp.net/','method'=>'_parse_xync_home','useproxy'=>0,'referurl'=>''),
								1=>array('url'=>'http://www.cqcp.net/game/xync/yu.aspx?name=','method'=>'_parse_xync','useproxy'=>0,'referurl'=>'http://www.cqcp.net/Trend/Xync/Xync.aspx?sType=ZH&type=QP'),
								0=>array('url'=>'http://www.cqcp.net/game/xync/opencode.aspx?time=','method'=>'_parse_xync1','useproxy'=>0,'referurl'=>'http://www.cqcp.net/game/xync/'),
								2=>array('url'=>'http://buy.cqcp.net/trend/lucky/xyncnumzh_chart.aspx','method'=>'_parse_xync2','useproxy'=>0,'referurl'=>'http://www.cqcp.net')
								);
	}
	
	
	private function _parse_xync_home($contents){
		$result = [];
	
		$contents = str_replace("\r","",str_replace("\n","",$contents));
		preg_match_all("#<ul class='(.*?)' id='ulkj_4' onmouseover='(.*?)'><li class='(.*?)'><img src='(.*?)'(.*?)/></li><li class='(.*?)'>([0-9]+)期(.*?)</li><li class='kjggxync'><img src='images/default/01/sg([0-9]+).jpg'></li><li class='kjggxync'><img src='images/default/01/sg([0-9]+).jpg'></li><li class='kjggxync'><img src='images/default/01/sg([0-9]+).jpg'></li><li class='kjggxync'><img src='images/default/01/sg([0-9]+).jpg'></li><li class='kjggxync'><img src='images/default/01/sg([0-9]+).jpg'></li><li class='kjggxync'><img src='images/default/01/sg([0-9]+).jpg'></li><li class='kjggxync'><img src='images/default/01/sg([0-9]+).jpg'></li><li class='kjggxync'><img src='images/default/01/sg([0-9]+).jpg'></li></ul>#",$contents,$data);
	
		if(is_numeric($data[7][0]) && is_numeric($data[9][0]) && is_numeric($data[10][0]) && is_numeric($data[11][0]) && is_numeric($data[12][0]) && is_numeric($data[13][0]) && is_numeric($data[14][0]) && is_numeric($data[15][0]) && is_numeric($data[16][0])){
			$no = $data[7][0];
			$result[$no] = ['no'=>$no,'time'=>date("Y-m-d H:i:s"),'data'=>$data[9][0].'|'.$data[10][0].'|'.$data[11][0].'|'.$data[12][0].'|'.$data[13][0].'|'.$data[14][0].'|'.$data[15][0].'|'.$data[16][0]];
		}
	
		if(empty($result)) $this->Logger("Parse Error.");
	
		return $result;
	}
	
	
	private function _parse_xync($contents){
		$result = [];
		
		$contentsArr = explode("|", $contents);
		if(count($contentsArr) == 2){
			$no = $contentsArr[1];
			$data = str_replace("-","|",$contentsArr[0]);
			
			$result[$no] = ['no'=>$no,'time'=>'','data'=>$data];
		}
		
		if(empty($result)) $this->Logger("Parse Error.");
		
		return $result;
	}
	
	private function _parse_xync1($contents){
		$contents = str_replace("\r","" , $contents);
		$contents = str_replace("\n","" , $contents);
		
		preg_match("#<table (.*?)>(.*?)</table>#s",$contents,$tabledata);
		preg_match_all("#<tr><td (.*?)>(\d+)期</td><td (.*?)><img src='/images/xync/tu/(\d+).jpg' width='20' height='20' /></td><td (.*?)><img src='/images/xync/tu/(\d+).jpg' width='20' height='20' /></td><td (.*?)><img src='/images/xync/tu/(\d+).jpg' width='20' height='20' /></td><td (.*?)><img src='/images/xync/tu/(\d+).jpg' width='20' height='20' /></td><td (.*?)><img src='/images/xync/tu/(\d+).jpg' width='20' height='20' /></td><td (.*?)><img src='/images/xync/tu/(\d+).jpg' width='20' height='20' /></td><td (.*?)><img src='/images/xync/tu/(\d+).jpg' width='20' height='20' /></td><td (.*?)><img src='/images/xync/tu/(\d+).jpg' width='20' height='20' /></td></tr>#",$tabledata[2],$data );
		$result = [];
		foreach ($data[2] as $k=>$no){
			if(is_numeric($no)){
				$result[$no] = ['no'=>$no,'time'=>'','data'=>$data[4][$k] . '|' .$data[6][$k] . '|' .$data[8][$k] . '|' .$data[10][$k] . '|' .$data[12][$k] . '|' .$data[14][$k] . '|' .$data[16][$k] . '|' .$data[18][$k]];
			}
		}
		
		if(empty($result)) $this->Logger("Parse Error.");
		
		return $result;
	}
	
	
	private function _parse_xync2($contents){
		$contents = str_replace("\r","" , $contents);
		$contents = str_replace("\n","" , $contents);
		$result = [];
		
		preg_match("#(.*?)var Con_BonusCode = (.*?)\"(.*?)\";(.*?)#s",$contents,$data);
		$listarr = explode(";", $data[3]);
		foreach ($listarr as $row){
			$rowarr = explode("=", $row);
			if(is_numeric($rowarr[0])){
				$no = $rowarr[0];
				$result[$no] = ['no'=>$no,'time'=>'','data'=>str_replace(",","|",$rowarr[1])];
			}
		}
		
	
		if(empty($result)) $this->Logger("Parse Error.");
	
		return $result;
	}
	
	private function _createNewNo(){
		$sql = "select count(*) as cnt from gamexync where kgtime > now() order by id desc limit 1";
		$cnt = $this->db->getOne($sql);
		if($cnt == 0){
			$sql = "DELETE FROM gamexync_users_tz WHERE time < DATE_ADD(NOW(),INTERVAL -15 DAY)";
			$this->db->execute($sql);
				
			$sql = "DELETE FROM gamexync WHERE kgtime < DATE_ADD(NOW(),INTERVAL -15 DAY)";
			$this->db->execute($sql);
			
			
			$sql = "select now() as nowtime";
			$nowtime = $this->db->getOne($sql);
			$currday = date("ymd" , strtotime($nowtime));
			$currday2 = date("Y-m-d" , strtotime($nowtime));
			
			$i = 1;
			$starttime = $currday2 . " 00:02:20";
			$timestep = 0;
			while($i<=13){
				$no = $currday . substr("00".$i , -3);
				$time = date("Y-m-d H:i:s" , strtotime($starttime) + $timestep);
				$starttime = $time;
				$timestep = 600;
				$i++;
				
				$sql = "INSERT IGNORE INTO gamexync(id,kgtime,gfid,zjpl) SELECT {$no},'{$time}',{$no},game_std_odds FROM game_config WHERE game_type = 36";
				$this->db->execute($sql);
			}
			
			$starttime = $currday2 . " 10:02:20";
			$timestep = 0;
			while($i<=97){
				$no = $currday . substr("00".$i , -3);
				$time = date("Y-m-d H:i:s" , strtotime($starttime) + $timestep);
				$starttime = $time;
				$timestep = 600;
				$i++;
				
				$sql = "INSERT IGNORE INTO gamexync(id,kgtime,gfid,zjpl) SELECT {$no},'{$time}',{$no},game_std_odds FROM game_config WHERE game_type = 36";
				$this->db->execute($sql);
			}
			
		}
		
	}
	
	
	protected function getUnOpenGameNos(){
		$ret = array();
		$sql = "SELECT id FROM gamexync 
					WHERE kj = 0 AND kgtime < NOW() 
					AND kgtime > DATE_ADD(NOW(),INTERVAL -120 MINUTE)
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
			$this->switchGame(0);
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
				$contents = $this->httpGet($source['url'] , $source['useproxy'] , $source['referurl']);//$source['url'] . microtime(true)
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
		
		if(empty($count)){// && count($this->crawlerUrls) == 1
			sleep($this->sleepSec);
		}
		
		return $count;
	}
	
	public function open($No=0){
		if($No == 0){
			$sql = "select gameno from game_result where gametype = '{$this->gameType}' and isopen = 0 order by gameno desc limit 1";
			$result = $this->db->getRow($sql);
			if(!empty($result)){
				$No = $result["gameno"];
			}else{
				return;
			}
		}else{
			$sql = "SELECT id,kgtime,now() as nowtime FROM gamexync WHERE id = '{$No}' ";
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
		
		if(count($kjnum_array) == 8){ //取到了
			//更新开奖状态
			$sql = "update game_result set isopen = 1,opentime = now() where gametype = '{$this->gameType}' and gameno = '{$No}'";
			$result = $this->db->execute($sql);
			if($result === FALSE){
				$this->Logger("DB Error! : {$sql} {$this->db->error}");
			}
		
			$this->Logger("{$No} result string:" . $strkjNum);
			
			$sql = "SET GLOBAL group_concat_max_len = 4096";
			$this->db->execute($sql);
			$sql = "SET SESSION group_concat_max_len = 4096";
			$this->db->execute($sql);
			
			$resultIds = $this->getGameXYNCResult($kjnum_array);
			$resultIdsCnt = count($resultIds);
			$odds = $this->GetOddByResult(36 , $resultIds);
			$oddsCnt = count($odds);
			//print_r($resultIds);
			//print_r($odds);
			if($resultIdsCnt == $oddsCnt && $resultIdsCnt > 0){
				$zj_result = $kjnum_array[0] + $kjnum_array[1] + $kjnum_array[2] + $kjnum_array[3] + $kjnum_array[4] + $kjnum_array[5] + $kjnum_array[6] + $kjnum_array[7];
				
				$resultIdStr = implode(",", $resultIds);
				$oddStr = implode(",", $odds);
				$sql = "call web_kj_gamexync({$No},{$zj_result},'{$strkjNum}',{$oddsCnt},'{$resultIdStr}','{$oddStr}')";
				$this->Logger($sql);
				$result = $this->db->MultiQuery($sql);
				$this->Logger("xync {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			}
			
		}
			
			
	}
	
	
	
	private function getGameXYNCResult($kjNoArr){//幸运农场开奖结果
		$total = (int)$kjNoArr[0] + (int)$kjNoArr[1] + (int)$kjNoArr[2] + (int)$kjNoArr[3] + (int)$kjNoArr[4] + (int)$kjNoArr[5] + (int)$kjNoArr[6] + (int)$kjNoArr[7];
		
		if($total >= 85 && $total <= 132) $result[] = 0;//大
		if($total >= 36 && $total <= 83) $result[] = 1;//小
		
		if($total % 2 != 0) $result[] = 2;//单
		if($total % 2 == 0) $result[] = 3;//双
		
		if($total % 10 >= 5) $result[] = 4;//尾大
		if($total % 10 < 5) $result[] = 5;//尾小
	
		//8个车道
		for($n=0;$n<8;$n++){
			$kjNoArr[$n] = (int)$kjNoArr[$n];
			for($i=1;$i<=20;$i++){
				if($kjNoArr[$n] == $i){
					$result[] = 5 + $n * 32 + $i;
					break;
				}
			}
			
			if($kjNoArr[$n] >= 11) $result[] = 5 + $n * 32 + 21;//大
			if($kjNoArr[$n] % 2 == 0) $result[] = 5 + $n * 32 + 22;//双
			if($kjNoArr[$n] % 10 >= 5) $result[] = 5 + $n * 32 + 23;//尾大
			if((floor($kjNoArr[$n]/10) + $kjNoArr[$n] % 10) % 2 == 0) $result[] = 5 + $n * 32 + 24;//合双
			
			if($kjNoArr[$n] < 11) $result[] = 5 + $n * 32 + 25;//小
			if($kjNoArr[$n] % 2 != 0) $result[] = 5 + $n * 32 + 26;//单
			if($kjNoArr[$n] % 10 < 5) $result[] = 5 + $n * 32 + 27;//尾小
			if((floor($kjNoArr[$n]/10) + $kjNoArr[$n] % 10) % 2 != 0) $result[] = 5 + $n * 32 + 28;//合单
			
			if(in_array($kjNoArr[$n] , [1,5,9,13,17])) $result[] = 5 + $n * 32 + 29;//东
			if(in_array($kjNoArr[$n] , [2,6,10,14,18])) $result[] = 5 + $n * 32 + 30;//南
			if(in_array($kjNoArr[$n] , [3,7,11,15,19])) $result[] = 5 + $n * 32 + 31;//西
			if(in_array($kjNoArr[$n] , [4,8,12,16,20])) $result[] = 5 + $n * 32 + 32;//北
		}
	
		//龙虎
		for($n=0;$n<4;$n++){
			if($kjNoArr[$n] > $kjNoArr[7-$n]){
				$result[] = 261 + $n * 2 + 1;//龙
			}else{
				$result[] = 261 + $n * 2 + 2;//虎
			}
		}
	
		sort($result);
		return $result;
	}
}



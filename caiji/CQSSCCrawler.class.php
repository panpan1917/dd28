<?php
include_once(dirname( __FILE__ ) ."/BaseCrawler.class.php");

class CQSSCCrawler extends BaseCrawler
{
	public function __construct(){
		parent::__construct();
		$this->sleepSec = 5;
		$this->stopBegin = "02:00:00";
		$this->stopEnd = "10:00:00";
		$this->gameType = "gamecqssc";
		$this->gameTypes = [37];
		$this->crawlerUrls = array(
								//0=>array('url'=>'http://www.cqcp.net/','method'=>'_parse_cqssc_home','useproxy'=>0,'referurl'=>''),
								0=>array('url'=>'http://buy.cqcp.net/Game/GetNum.aspx?iType=3&time=','method'=>'_parse_cqssc','useproxy'=>0,'referurl'=>'http://buy.cqcp.net/game/cqssc/'),
								1=>array('url'=>'http://www.cqcp.net/game/ssc/','method'=>'_parse_cqssc_home2','useproxy'=>0,'referurl'=>''),
								3=>array('url'=>'https://fx.cp2y.com/cqssckj/','method'=>'_parse_cp2y_com','useproxy'=>0,'referurl'=>'https://www.cp2y.com'),
								2=>array('url'=>'http://caipiao.163.com/award/cqssc/','method'=>'_parse_163_com','useproxy'=>0,'referurl'=>'http://caipiao.163.com'),
								);
	}
	
	private function _parse_163_com($contents){
		$result = [];
	
		$contents = str_replace("\t","",str_replace("\r","",str_replace("\n","",$contents)));
		preg_match_all("#<td class=\"start\" data-win-number='(.*?)' data-period=\"([0-9:]+)\">([0-9:]+)</td>#",$contents,$data);
		
		for($i=0;$i<count($data[1]);$i++){
			$strnum = str_replace(" ","",$data[1][$i]);
			if(is_numeric($strnum) && is_numeric($data[2][$i])){
				$no = $data[2][$i];
				$result[$no] = ['no'=>$no,'time'=>date("Y-m-d H:i:s"),'data'=>str_replace(" ","|",$data[1][$i])];
			}
		}
	
		if(empty($result)) $this->Logger("Parse Error.");
	
		return $result;
	}
	
	private function _parse_cp2y_com($contents){
		$result = [];
	
		$contents = str_replace("\t","",str_replace("\r","",str_replace("\n","",$contents)));
		preg_match_all("#<tr><td><span>(.*?)<b class=\"(.*?)\">([0-9:]+)</b></span></td><td><i class=\"(.*?)\">([0-9])</i><i class=\"(.*?)\">([0-9])</i><i class=\"(.*?)\">([0-9])</i><i class=\"(.*?)\">([0-9])</i><i class=\"(.*?)\">([0-9])</i></td><td><i class=\"(.*?)\">([0-9])</i><i class=\"(.*?)\">([0-9])</i><i class=\"(.*?)\">([0-9])</i></td><td>(.*?)</td><td>(.*?)</td><td>(.*?)</td><td><i class=\"(.*?)\">([0-9])</i><i class=\"(.*?)\">([0-9])</i></td><td>(.*?)</td><td><i class=\"(.*?)\">([0-9])</i></td>(.*?)</tr>#",$contents,$data);
		
		
		for($i=0;$i<count($data[1]);$i++){
			$data[1][$i] = trim($data[1][$i]);
			if(is_numeric($data[1][$i]) && is_numeric($data[5][$i]) && is_numeric($data[7][$i]) && is_numeric($data[9][$i]) && is_numeric($data[11][$i]) && is_numeric($data[13][$i])){
				$no = $data[1][$i];
				$result[$no] = ['no'=>$no,'time'=>date("Y-m-d H:i:s"),'data'=>$data[5][$i].'|'.$data[7][$i].'|'.$data[9][$i].'|'.$data[11][$i].'|'.$data[13][$i]];
			}
		}
	
		if(empty($result)) $this->Logger("Parse Error.");
	
		return $result;
	}
	
	private function _parse_cqssc_home($contents){
		$result = [];
	
		$contents = str_replace("\r","",str_replace("\n","",$contents));
		preg_match_all("#<ul class='(.*?)' id='ulkj_2' onmouseover='(.*?)'><li class='(.*?)'><img src='(.*?)'(.*?)/></li><li class='(.*?)'>([0-9]+)期(.*?)</li><li class='kjggred'>([0-9])</li><li class='kjggred'>([0-9])</li><li class='kjggred'>([0-9])</li><li class='kjggred'>([0-9])</li><li class='kjggred'>([0-9])</li></ul>#",$contents,$data);
	
		if(is_numeric($data[7][0]) && is_numeric($data[9][0]) && is_numeric($data[10][0]) && is_numeric($data[11][0]) && is_numeric($data[12][0]) && is_numeric($data[13][0])){
			$no = $data[7][0];
			$result[$no] = ['no'=>$no,'time'=>date("Y-m-d H:i:s"),'data'=>$data[9][0].'|'.$data[10][0].'|'.$data[11][0].'|'.$data[12][0].'|'.$data[13][0]];
		}
	
		if(empty($result)) $this->Logger("Parse Error.");
	
		return $result;
	}
	
	private function _parse_cqssc_home2($contents){
		$result = [];
	
		$contents = str_replace("\r","",str_replace("\n","",$contents));
		preg_match_all("#<ul><li style='width:65px;'>([0-9]+)</li><li style='width:80px;'>([-0-9]+)</li><li style='width:50px;'>([0-9]+)</li><li style='width:40px;'>(.*?)</li><li style='width:40px;'>(.*?)</li><li style='width:40px;'>(.*?)</li><li style='width:40px;'>([0-9]+)</li><li style='width:40px;'>([0-9]+)</li><li style='width:80px; line-height:18px;border-right:0px;'>(.*?)</li></ul>#",$contents,$data);
	
		foreach ($data[1] as $k=>$v){
			$arrTmp=explode('-',$data[2][$k] );
			if(is_numeric($v) && count($arrTmp) == 5)
				$result[$v] = ['no'=>$v,'time'=>date("Y-m-d H:i:s"),'data'=>implode('|',$arrTmp)];
		}
	
		if(empty($result)) $this->Logger("Parse Error.");
		
		return $result;
	}
	
	private function _parse_cqssc($contents){
		$result = [];
		
		preg_match_all('#<ul class=\'(.*?)\'><li class=\'(.*?)\'>(\d+)</li><li class=\'(.*?)\'>([0-9,]+)</li><li class=\'(.*?)\'>([0-9-: ]+)</li></ul>#',$contents,$data);
		
		foreach ($data[3] as $k=>$v){
			$arrTmp=explode(',',$data[5][$k] );
			if(is_numeric($v) && count($arrTmp) == 5)
				$result[$v] = ['no'=>$v,'time'=>date("Y-m-d H:i:s" , strtotime(date("Y-") . $data[7][$k])),'data'=>implode('|',$arrTmp)];
		}
		//print_r($result);exit;
		
		if(empty($result)) $this->Logger("Parse Error.");
		
		return $result;
	}
	
	private function _pase_1680210($contents){
		$arrRet = json_decode($contents,true);
		$arrRet = $arrRet['result']['data'];
		$result = [];
		
		for($i = 0; $i < count($arrRet); $i++)
		{
			$no = substr($arrRet[$i]['preDrawIssue'],2);
			$NumArr = explode(",", $arrRet[$i]['preDrawCode']);
			if(is_numeric($no) && count($NumArr) == 5){
				$result[$no]['no'] = $no;
				$result[$no]['time'] = date("Y-m-d H:i:s" , strtotime($arrRet[$i]['preDrawTime']));
				$result[$no]['data'] = implode('|',$NumArr);
			}
		}
		
		//print_r($result);exit;
		
		if(empty($result)) $this->Logger("Parse Error.");
		
		return $result;
	}
	
	
	private function _createNewNo(){
		$sql = "select count(*) as cnt from gamecqssc where kgtime > now() order by id desc limit 1";
		$cnt = $this->db->getOne($sql);
		if($cnt < 2){
			$sql = "DELETE FROM gamecqssc_users_tz WHERE time < DATE_ADD(NOW(),INTERVAL -15 DAY)";
			$this->db->execute($sql);
			
			$sql = "DELETE FROM gamecqssc WHERE kgtime < DATE_ADD(NOW(),INTERVAL -15 DAY)";
			$this->db->execute($sql);
			
			$sql = "select now() as nowtime";
			$nowtime = $this->db->getOne($sql);
			$currday = date("ymd" , strtotime($nowtime));
			$currday2 = date("Y-m-d" , strtotime($nowtime));
			
			$i = 1;
			$starttime = $currday2 . " 00:05:00";
			$timestep = 0;
			while($i<=23){
				$no = $currday . substr("00".$i , -3);
				$time = date("Y-m-d H:i:s" , strtotime($starttime) + $timestep);
				$starttime = $time;
				$timestep = 300;
				$i++;
				
				if($time > $nowtime){
					$sql = "INSERT IGNORE INTO gamecqssc(id,kgtime,gfid,zjpl) SELECT {$no},'{$time}',{$no},game_std_odds FROM game_config WHERE game_type = 37";
					$this->db->execute($sql);
				}
			}
			
			$starttime = $currday2 . " 10:00:00";
			$timestep = 0;
			while($i<=96){
				$no = $currday . substr("00".$i , -3);
				$time = date("Y-m-d H:i:s" , strtotime($starttime) + $timestep);
				$starttime = $time;
				$timestep = 600;
				$i++;
				
				if($time > $nowtime){
					$sql = "INSERT IGNORE INTO gamecqssc(id,kgtime,gfid,zjpl) SELECT {$no},'{$time}',{$no},game_std_odds FROM game_config WHERE game_type = 37";
					$this->db->execute($sql);
				}
			}
			
			
			$starttime = $currday2 . " 22:05:00";
			$timestep = 0;
			while($i<=120){
				$no = $currday . substr("00".$i , -3);
				$time = date("Y-m-d H:i:s" , strtotime($starttime) + $timestep);
				$starttime = $time;
				$timestep = 300;
				$i++;
			
				if($time > $nowtime){
					$sql = "INSERT IGNORE INTO gamecqssc(id,kgtime,gfid,zjpl) SELECT {$no},'{$time}',{$no},game_std_odds FROM game_config WHERE game_type = 37";
					$this->db->execute($sql);
				}
			}
			
		}
		
	}
	
	
	protected function getUnOpenGameNos(){
		$ret = array();
		$sql = "SELECT id FROM gamecqssc 
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
			$sql = "SELECT id,kgtime,now() as nowtime FROM gamecqssc WHERE id = '{$No}' ";
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
		
		if(count($kjnum_array) == 5){ //取到了
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
			
			$resultIds = $this->getGameCqsscResult($kjnum_array);
			$resultIdsCnt = count($resultIds);
			$odds = $this->GetOddByResult(37 , $resultIds);
			$oddsCnt = count($odds);
			//print_r($resultIds);
			//print_r($odds);
			if($resultIdsCnt == $oddsCnt && $resultIdsCnt > 0){
				$zj_result = $kjnum_array[0] + $kjnum_array[1] + $kjnum_array[2] + $kjnum_array[3] + $kjnum_array[4] + $kjnum_array[5] + $kjnum_array[6] + $kjnum_array[7];
				
				$resultIdStr = implode(",", $resultIds);
				$oddStr = implode(",", $odds);
				$sql = "call web_kj_gamecqssc({$No},{$zj_result},'{$strkjNum}',{$oddsCnt},'{$resultIdStr}','{$oddStr}')";
				$this->Logger($sql);
				$result = $this->db->MultiQuery($sql);
				$this->Logger("cqssc {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			}
			
		}
			
			
	}
	
	
	
	private function getGameCqsscResult($kjNoArr){//时时彩开奖结果
		$total = (int)$kjNoArr[0] + (int)$kjNoArr[1] + (int)$kjNoArr[2] + (int)$kjNoArr[3] + (int)$kjNoArr[4];
		
		if($total >= 23 && $total <= 45) $result[] = 0;//大
		if($total >= 0 && $total <= 22) $result[] = 1;//小
		
		if($total % 2 != 0) $result[] = 2;//单
		if($total % 2 == 0) $result[] = 3;//双
		
		if($kjNoArr[0] > $kjNoArr[4]) $result[] = 4;//龙
		if($kjNoArr[0] < $kjNoArr[4]) $result[] = 5;//虎
		if($kjNoArr[0] == $kjNoArr[4]) $result[] = 6;//和
		
		//5个车道
		for($n=0;$n<5;$n++){
			$kjNoArr[$n] = (int)$kjNoArr[$n];
			
			if($kjNoArr[$n] >= 5 && $kjNoArr[$n] <= 9) $result[] = 7 + $n * 14;//大
			if($kjNoArr[$n] >= 0 && $kjNoArr[$n] <= 4) $result[] = 8 + $n * 14;//小
			
			if($kjNoArr[$n] % 2 != 0) $result[] = 9 + $n * 14;//单
			if($kjNoArr[$n] % 2 == 0) $result[] = 10 + $n * 14;//双
			
			for($i=0;$i<=9;$i++){
				if($kjNoArr[$n] == $i){
					$result[] = 11 + $n * 14 + $i;
					break;
				}
			}
		}
		
		$a = $this->getGame36Result($kjNoArr[0],$kjNoArr[1],$kjNoArr[2]);
		$result[] = 76 + $a;
		$b = $this->getGame36Result($kjNoArr[1],$kjNoArr[2],$kjNoArr[3]);
		$result[] = 81 + $b;
		$c = $this->getGame36Result($kjNoArr[2],$kjNoArr[3],$kjNoArr[4]);
		$result[] = 86 + $c;
	
		sort($result);
		return $result;
	}
}


//$ss = new CQSSCCrawler();
//$ss->crawler();



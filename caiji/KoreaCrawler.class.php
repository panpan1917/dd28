<?php
include_once(dirname( __FILE__ ) ."/BaseCrawler.class.php");

class KoreaCrawler extends BaseCrawler
{
	private $merid = "2004";//2003
	private $merkey = "0f2aa67b41af0f8677d2a853d5af8e2d";//278d3ed9f7855cde4eb8f2fa718c035e
	private $mertoken = "ee649a9fe96c387e";//fe6047ff8e6c4cbe
	private $merPostUrl = "http://39.108.193.213/index.php/api/GameValidate";//http://test.yidian28.com:49800/api/GameValidate
	private $merOpenUrl = "http://39.108.193.213/index.php/api/getGameData";//http://test.yidian28.com:49800/api/getGameData
	private $submitflag = array();
	
	public function __construct(){
		parent::__construct();
		$this->sleepSec = 2;
		$this->stopBegin = "05:05:00";
		$this->stopEnd = "07:00:00";
		$this->gameType = "gamehg";
		$this->gameTypes = [18,19,20,21,30,31,34];
		$this->crawlerUrls = array(
								//0=>array('url'=>$this->merOpenUrl.'?type=1&token='.$this->mertoken,'method'=>'_parse_apiunion','useproxy'=>0,'referurl'=>'')
								//0=>array('url'=>'http://47.90.52.104:180/hg/new.php','method'=>'_parse_hg','useproxy'=>0,'referurl'=>''),
								0=>array('url'=>'http://120.27.236.149:9921/open.php','method'=>'_parse_hg2','useproxy'=>0,'referurl'=>''),
								////1=>array('url'=>'http://47.74.129.187/hg/','method'=>'_parse_hg','useproxy'=>0,'referurl'=>''),
								//1=>array('url'=>'http://47.90.52.104:180/hg/caiji_hg.php','method'=>'_parse_hg','useproxy'=>0,'referurl'=>''),
								//2=>array('url'=>'http://47.90.52.104:180/hg/api.php','method'=>'_parse_hg','useproxy'=>0,'referurl'=>''),
								////0=>array('url'=>'http://c.apiplus.net/newly.do?token=8dcc1443d6644fb0&code=krkeno&format=json','method'=>'_parse_apiplus','useproxy'=>0,'referurl'=>'')
								);
	}
	
	private function _parse_apiunion($contents){
		$arrRet = json_decode($contents,true);
		$arrRet = $arrRet['data'];
		$result = [];
		for($i = 0; $i < count($arrRet); $i++)
		{
			$no = $arrRet[$i]["periods"];
			if(is_numeric($no)){
				$result[$no]['no'] = $no;
				$result[$no]['time'] = $arrRet[$i]["opentime"];
				$result[$no]['data'] = str_replace(',','|' ,$arrRet[$i]['number'] );
			}
		}
	
		if(empty($result)) $this->Logger("Parse Error.");
	
		return $result;
	}
	
	private function _parse_apiplus($contents){
		$arrRet = json_decode($contents,true);
		$arrRet = $arrRet['data'];
		$result = [];
		for($i = 0; $i < count($arrRet); $i++)
		{
			$no = $arrRet[$i]["expect"];
			if(is_numeric($no)){
				$result[$no]['no'] = $no;
				$result[$no]['time'] = $arrRet[$i]["opentime"];
				$result[$no]['data'] = str_replace(',','|' ,$arrRet[$i]['opencode'] );
			}
		}
		
		if(empty($result)) $this->Logger("Parse Error.");
		
		return $result;
	}
	
	private function _parse_hg($contents){
		$arrRet = json_decode($contents,true);
		$result = [];
		for($i = 0; $i < count($arrRet); $i++)
		{
			$no = $arrRet[$i]["l_t"];
			if(is_numeric($no)){
				$result[$no]['no'] = $no;
				$result[$no]['time'] = $arrRet[$i]["l_d"];
				$result[$no]['data'] = str_replace(',','|' ,$arrRet[$i]['c_r'] );
			}
		}
		
		if(empty($result)) $this->Logger("Parse Error.");
		
		return $result;
	}
	
	private function _parse_hg2($contents){
		$arrRet = json_decode($contents,true);
		$result = [];
		for($i = 0; $i < count($arrRet); $i++)
		{
			$no = $arrRet[$i]["gfid"];
			if(is_numeric($no)){
				$result[$no]['no'] = $no;
				$result[$no]['time'] = $arrRet[$i]["opentime"];
				$result[$no]['data'] = str_replace(',','|' ,$arrRet[$i]['kgsort'] );
			}
		}
	
		if(empty($result)) $this->Logger("Parse Error.");
	
		return $result;
	}
	
	protected function getUnOpenGameNos(){
		$ret = array();
		$sql = "SELECT id FROM gamehg28 
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
		$this->sendPressData();
		
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
		
		if(empty($count)){
			sleep($this->sleepSec);
		}
		
		return $count;
	}
	
	
	private function sendPressData(){
		
		$sql = "SELECT id as periods,kgtime,now() as currtime FROM gamehg28 WHERE kj = 0 AND kgtime < DATE_ADD(NOW(),INTERVAL +90 SECOND)
					AND kgtime > DATE_ADD(NOW(),INTERVAL -5 SECOND)
					AND id NOT IN(select gameno FROM game_result WHERE gametype = '{$this->gameType}') 
					ORDER BY id limit 1";
		$gamerow = $this->db->getRow($sql);
		$periods = (int)$gamerow['periods'];
		if(empty($periods)){
			$this->Logger("no new game");
			return false;
		}
		
		if($this->submitflag[$periods]) return true;
		
		$sql = "select game_kj_delay,game_tz_close from game_config where game_type = 18";
		$conf = $this->db->getRow($sql);
		if(strtotime($gamerow['currtime']) <= (strtotime($gamerow['kgtime'])-$conf['game_tz_close']) || strtotime($gamerow['currtime']) > strtotime($gamerow['kgtime'])){
			//$this->Logger("can't post game data");
			return false;
		}
		
		
		
		$param['id'] = $this->merid;   //商户id
		$param['type'] = "1";   //游戏类型(韩国：1)
		$param['periods'] = $periods;   //期号
		
		
		$sql = "SELECT gamehg11+gamehg16+gamehg28+gamehg36+gamehg28gd+gamehgww+gamehgdw as sys_win FROM `webtj` WHERE `time`=DATE(NOW())";
		$sys_win = $this->db->getOne($sql);
		$sys_win = (int)$sys_win;
		
		$data['sys_win'] = -$sys_win;//平台该彩种今日盈亏
		$data['game_data'] = array();//游戏数据
		
		//韩国11
		$sql = "SELECT SUM(tzpoints) AS tzpoints,tznum FROM gamehg11_kg_users_tz WHERE `NO`={$periods} AND usertype=0 group by tznum";
		$rows = $this->db->getAll($sql);
		if(count($rows) > 0){
			$sql = "select zjpl from gamehg11 where id={$periods}";
			$zjpl = $this->db->getOne($sql);
			$zjplArr = explode("|", $zjpl);
			if(count($zjplArr) < 11) return false;
			
			foreach($rows as $idx=>$row){
				$data['game_data']['101'][$idx]['numid'] = (int)$row['tznum'];
				$data['game_data']['101'][$idx]['amount'] = (int)$row['tzpoints'];
				foreach($zjplArr as $idxodd=>$odds){
					if($idxodd+2 == $row['tznum']){
						$data['game_data']['101'][$idx]['odds'] = (float)$odds;
						break;
					}
				}
			}
		}
		
		//韩国16
		$sql = "SELECT SUM(tzpoints) AS tzpoints,tznum FROM gamehg16_kg_users_tz WHERE `NO`={$periods} AND usertype=0 group by tznum";
		$rows = $this->db->getAll($sql);
		if(count($rows) > 0){
			$sql = "select zjpl from gamehg16 where id={$periods}";
			$zjpl = $this->db->getOne($sql);
			$zjplArr = explode("|", $zjpl);
			if(count($zjplArr) < 16) return false;
				
			foreach($rows as $idx=>$row){
				$data['game_data']['102'][$idx]['numid'] = (int)$row['tznum'];
				$data['game_data']['102'][$idx]['amount'] = (int)$row['tzpoints'];
				foreach($zjplArr as $idxodd=>$odds){
					if($idxodd+3 == $row['tznum']){
						$data['game_data']['102'][$idx]['odds'] = (float)$odds;
						break;
					}
				}
			}
		}
		
		//韩国28
		$sql = "SELECT SUM(tzpoints) AS tzpoints,tznum FROM gamehg28_kg_users_tz WHERE `NO`={$periods} AND usertype=0 group by tznum";
		$rows = $this->db->getAll($sql);
		if(count($rows) > 0){
			$sql = "select zjpl from gamehg28 where id={$periods}";
			$zjpl = $this->db->getOne($sql);
			$zjplArr = explode("|", $zjpl);
			if(count($zjplArr) < 28) return false;
		
			foreach($rows as $idx=>$row){
				$data['game_data']['103'][$idx]['numid'] = (int)$row['tznum'];
				$data['game_data']['103'][$idx]['amount'] = (int)$row['tzpoints'];
				foreach($zjplArr as $idxodd=>$odds){
					if($idxodd+0 == $row['tznum']){
						$data['game_data']['103'][$idx]['odds'] = (float)$odds;
						break;
					}
				}
			}
		}
		
		//韩国36
		$sql = "SELECT SUM(tzpoints) AS tzpoints,tznum FROM gamehg36_kg_users_tz WHERE `NO`={$periods} AND usertype=0 group by tznum";
		$rows = $this->db->getAll($sql);
		if(count($rows) > 0){
			$sql = "select zjpl from gamehg36 where id={$periods}";
			$zjpl = $this->db->getOne($sql);
			$zjplArr = explode("|", $zjpl);
			if(count($zjplArr) < 5) return false;
		
			foreach($rows as $idx=>$row){
				$data['game_data']['104'][$idx]['numid'] = (int)$row['tznum']+101;
				$data['game_data']['104'][$idx]['amount'] = (int)$row['tzpoints'];
				foreach($zjplArr as $idxodd=>$odds){
					if($idxodd+1 == $row['tznum']){
						$data['game_data']['104'][$idx]['odds'] = (float)$odds;
						break;
					}
				}
			}
		}
		
		//韩国外围
		$sql = "SELECT SUM(tzpoints) AS tzpoints,tznum FROM gamehgww_kg_users_tz WHERE `NO`={$periods} AND usertype=0 group by tznum";
		$rows = $this->db->getAll($sql);
		if(count($rows) > 0){
			$sql = "select zjpl from gamehgww where id={$periods}";
			$zjpl = $this->db->getOne($sql);
			$zjplArr = explode("|", $zjpl);
			if(count($zjplArr) < 13) return false;
		
			foreach($rows as $idx=>$row){
				if($row['tznum']==0) $data['game_data']['105'][$idx]['numid'] = 107;
				if($row['tznum']==1) $data['game_data']['105'][$idx]['numid'] = 109;
				if($row['tznum']==2) $data['game_data']['105'][$idx]['numid'] = 112;
				if($row['tznum']==3) $data['game_data']['105'][$idx]['numid'] = 111;
				if($row['tznum']==4) $data['game_data']['105'][$idx]['numid'] = 116;
				if($row['tznum']==5) $data['game_data']['105'][$idx]['numid'] = 108;
				if($row['tznum']==6) $data['game_data']['105'][$idx]['numid'] = 110;
				if($row['tznum']==7) $data['game_data']['105'][$idx]['numid'] = 114;
				if($row['tznum']==8) $data['game_data']['105'][$idx]['numid'] = 113;
				if($row['tznum']==9) $data['game_data']['105'][$idx]['numid'] = 115;
				if($row['tznum']==10) $data['game_data']['105'][$idx]['numid'] = 117;
				if($row['tznum']==11) $data['game_data']['105'][$idx]['numid'] = 118;
				if($row['tznum']==12) $data['game_data']['105'][$idx]['numid'] = 119;
				
				$data['game_data']['105'][$idx]['amount'] = (int)$row['tzpoints'];
				foreach($zjplArr as $idxodd=>$odds){
					if($idxodd+0 == $row['tznum']){
						$data['game_data']['105'][$idx]['odds'] = (float)$odds;
						break;
					}
				}
			}
		}
		
		//韩国定位
		$sql = "SELECT SUM(tzpoints) AS tzpoints,tznum FROM gamehgdw_kg_users_tz WHERE `NO`={$periods} AND usertype=0 group by tznum";
		$rows = $this->db->getAll($sql);
		if(count($rows) > 0){
			$sql = "select zjpl from gamehgdw where id={$periods}";
			$zjpl = $this->db->getOne($sql);
			$zjplArr = explode("|", $zjpl);
			if(count($zjplArr) < 55) return false;
		
			foreach($rows as $idx=>$row){
				if($row['tznum']==0) $data['game_data']['106'][$idx]['numid'] = 120;
				if($row['tznum']==1) $data['game_data']['106'][$idx]['numid'] = 122;
				if($row['tznum']==2) $data['game_data']['106'][$idx]['numid'] = 125;
				if($row['tznum']==3) $data['game_data']['106'][$idx]['numid'] = 124;
				if($row['tznum']==4) $data['game_data']['106'][$idx]['numid'] = 129;
				if($row['tznum']==5) $data['game_data']['106'][$idx]['numid'] = 121;
				if($row['tznum']==6) $data['game_data']['106'][$idx]['numid'] = 123;
				if($row['tznum']==7) $data['game_data']['106'][$idx]['numid'] = 127;
				if($row['tznum']==8) $data['game_data']['106'][$idx]['numid'] = 126;
				if($row['tznum']==9) $data['game_data']['106'][$idx]['numid'] = 128;
				if($row['tznum']==10) $data['game_data']['106'][$idx]['numid'] = 130;
				if($row['tznum']==11) $data['game_data']['106'][$idx]['numid'] = 131;
				if($row['tznum']==12) $data['game_data']['106'][$idx]['numid'] = 132;
				if($row['tznum']>=13 && $row['tznum']<=54) $data['game_data']['106'][$idx]['numid'] = 120+$row['tznum'];
		
				$data['game_data']['106'][$idx]['amount'] = (int)$row['tzpoints'];
				foreach($zjplArr as $idxodd=>$odds){
					if($idxodd+0 == $row['tznum']){
						$data['game_data']['106'][$idx]['odds'] = (float)$odds;
						break;
					}
				}
			}
		}
		
		//韩国28固定
		$sql = "SELECT SUM(tzpoints) AS tzpoints,tznum FROM gamehg28gd_kg_users_tz WHERE `NO`={$periods} AND usertype=0 group by tznum";
		$rows = $this->db->getAll($sql);
		if(count($rows) > 0){
			$sql = "select zjpl from gamehg28gd where id={$periods}";
			$zjpl = $this->db->getOne($sql);
			$zjplArr = explode("|", $zjpl);
			if(count($zjplArr) < 28) return false;
		
			foreach($rows as $idx=>$row){
				$data['game_data']['108'][$idx]['numid'] = (int)$row['tznum'];
				$data['game_data']['108'][$idx]['amount'] = (int)$row['tzpoints'];
				foreach($zjplArr as $idxodd=>$odds){
					if($idxodd+0 == $row['tznum']){
						$data['game_data']['108'][$idx]['odds'] = (float)$odds;
						break;
					}
				}
			}
		}
		
		if(empty($data['game_data'])) return false;
		
		$param['data'] = $this->Game_Encode(json_encode($data), $this->merkey);
		
		
		$ret = $this->httpGet($this->merPostUrl , 0 , '' , $param);
		$ret = $this->Game_Decode($ret,$this->merkey);
		$ret = json_decode($ret,true);
		if($ret['code'] === 0){
			$this->submitflag[$periods] = true;
			$this->Logger("send press data success!");
		}else{
			$this->Logger("submit fail {$ret['code']}");
		}
	}
	
	private function Game_Encode($data,$key){
		$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_ECB);
		$len = strlen($data);
		$pad = $blockSize - ($len % $blockSize);
		$data .= str_repeat(chr($pad), $pad);
		$encData = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB);
		return base64_encode($encData);
	}
	
	private function Game_Decode($base64_data,$key){
		$data = base64_decode($base64_data);
		$plain_data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB);
		$plain_data = trim($plain_data, "\x00..\x1F");
		return $plain_data;
	}
	
	public function open($No=0){
		$isToAuto = true;
		
		if($No == 0){
			$sql = "select gameno from game_result where gametype = '{$this->gameType}' and isopen = 0 order by gameno desc limit 1";//AND ADDTIME>DATE_SUB(NOW(),INTERVAL 10 MINUTE) 
			$result = $this->db->getRow($sql);
			if(!empty($result)){
				$No = $result["gameno"];
			}else{
				//保证采集不到但下盘时间快到时自动下注
				$sql = "SELECT id,kgtime,now() as nowtime FROM gamehg28 WHERE kj = 0 AND zdtz_r = 0 AND kgtime > NOW() ORDER BY kgtime LIMIT 1";
				$result = $this->db->getRow($sql);
				if(!empty($result)){
					$NextNo = $result['id'];
					if(strtotime($result['kgtime']) - strtotime($result['nowtime']) < 30){
						//自动投注
						$this->autoPress($NextNo-1,$NextNo);
					}
				}
				return;
			}
		}else{
			$isToAuto = false;
				
			$sql = "SELECT id,kgtime,now() as nowtime FROM gamehg28 WHERE id = '{$No}' ";
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
				
			//开奖韩国16
			$zjh_a = $kjnum_array[0] + $kjnum_array[3] + $kjnum_array[6] + $kjnum_array[9] + $kjnum_array[12] + $kjnum_array[15];
			$zj_a = ($zjh_a % 6) + 1;
			$zjh_b = $kjnum_array[1] + $kjnum_array[4] + $kjnum_array[7] + $kjnum_array[10] + $kjnum_array[13] + $kjnum_array[16];
			$zj_b = ($zjh_b % 6) + 1;
			$zjh_c = $kjnum_array[2] + $kjnum_array[5] + $kjnum_array[8] + $kjnum_array[11] + $kjnum_array[14] + $kjnum_array[17];
			$zj_c = ($zjh_c % 6) + 1;
			$zj_result = $zj_a + $zj_b + $zj_c;
			$sql = "call web_kj_gamehg16({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			$result = $this->db->MultiQuery($sql);
			$this->Logger("korea16 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
				
			//开奖韩国11
			$zjh_a = $kjnum_array[0] + $kjnum_array[3] + $kjnum_array[6] + $kjnum_array[9] + $kjnum_array[12] + $kjnum_array[15];
			$zjh_b = $kjnum_array[2] + $kjnum_array[5] + $kjnum_array[8] + $kjnum_array[11] + $kjnum_array[14] + $kjnum_array[17];
			$zj_a = ($zjh_a % 6) + 1;
			$zj_b = ($zjh_b % 6) + 1;
			$zj_c = -1;
			$zj_result = $zj_a + $zj_b;
			$sql = "call web_kj_gamehg11({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			$result = $this->db->MultiQuery($sql);
			$this->Logger("korea11 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
				
			//开奖韩国36
			$zjh_a = $kjnum_array[1] + $kjnum_array[4] + $kjnum_array[7] + $kjnum_array[10] + $kjnum_array[13] + $kjnum_array[16];
			$zjh_b = $kjnum_array[2] + $kjnum_array[5] + $kjnum_array[8] + $kjnum_array[11] + $kjnum_array[14] + $kjnum_array[17];
			$zjh_c = $kjnum_array[3] + $kjnum_array[6] + $kjnum_array[9] + $kjnum_array[12] + $kjnum_array[15] + $kjnum_array[18];
			$zj_a = substr( $zjh_a, -1 );
			$zj_b = substr( $zjh_b, -1 );
			$zj_c = substr( $zjh_c, -1 );
			$zj_result = $this->getGame36Result($zj_a,$zj_b,$zj_c);
			$sql = "call web_kj_gamehg36({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			$result = $this->db->MultiQuery($sql);
			$this->Logger("korea36 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			
			//开奖韩国28
			$zjh_a = $kjnum_array[1] + $kjnum_array[4] + $kjnum_array[7] + $kjnum_array[10] + $kjnum_array[13] + $kjnum_array[16];
			$zjh_b = $kjnum_array[2] + $kjnum_array[5] + $kjnum_array[8] + $kjnum_array[11] + $kjnum_array[14] + $kjnum_array[17];
			$zjh_c = $kjnum_array[3] + $kjnum_array[6] + $kjnum_array[9] + $kjnum_array[12] + $kjnum_array[15] + $kjnum_array[18];
			$zj_a = substr( $zjh_a, -1 );
			$zj_b = substr( $zjh_b, -1 );
			$zj_c = substr( $zjh_c, -1 );
			$zj_result = $zj_a + $zj_b + $zj_c;
			$sql = "call web_kj_gamehg28({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			$result = $this->db->MultiQuery($sql);
			$this->Logger("korea28 {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			
			$sql = "call web_kj_gamehg28gd({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}')";
			$this->Logger("web_kj_gamehg28gd : " . $sql);
			$result = $this->db->MultiQuery($sql);
			$this->Logger("korea28gd {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			
			$sql = "SET GLOBAL group_concat_max_len = 4096";
			$this->db->execute($sql);
			$sql = "SET SESSION group_concat_max_len = 4096";
			$this->db->execute($sql);
				
			$resultIds = $this->getGameWWResult($kjnum_array);
			$resultIdsCnt = count($resultIds);
			$odds = $this->GetOddByResult(30 , $resultIds);
			$oddsCnt = count($odds);
			if($resultIdsCnt == $oddsCnt && $resultIdsCnt > 0){
				$resultIdStr = implode(",", $resultIds);
				//$this->Logger("resultIds : " . $resultIdStr);
				$oddStr = implode(",", $odds);
				//$this->Logger("odds : " . $oddStr);
				$sql = "call web_kj_gamehgww({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}',{$oddsCnt},'{$resultIdStr}','{$oddStr}')";
				//$this->Logger($sql);
				$result = $this->db->MultiQuery($sql);
				$this->Logger("koreaww {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			}
				
			$resultIds = $this->getGameDWResult($kjnum_array);
			$resultIdsCnt = count($resultIds);
			$odds = $this->GetOddByResult(31 , $resultIds);
			$oddsCnt = count($odds);
			if($resultIdsCnt == $oddsCnt && $resultIdsCnt > 0){
				$resultIdStr = implode(",", $resultIds);
				$oddStr = implode(",", $odds);
				$sql = "call web_kj_gamehgdw({$No},{$zj_a},{$zj_b},{$zj_c},{$zj_result},'{$strkjNum}',{$oddsCnt},'{$resultIdStr}','{$oddStr}')";
				//$this->Logger($sql);
				$result = $this->db->MultiQuery($sql);
				$this->Logger("koreadw {$No} open result is:{$result[0][0]['msg']}({$result[0][0]['result']})");
			}
		}
		
		
		
		if($isToAuto){
			//给下一盘自动投注
			$NextNo = $No+1;
			$sql = "select id from gamehg28 where id={$NextNo} and kj=0 and zdtz_r=0 limit 1";
			$result = $this->db->getRow($sql);
			if(!empty($result)){
				//自动投注
				$this->autoPress($No,$NextNo);
			}
		}
			
			
	}
	
	
	private function autoPress($No,$NextNo){
		//韩国28自动投注
		$sql = "call web_tz_gamehg28_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("korea28 {$NextNo} auto press:{$result[0][0]['result']}");
	
		//韩国16自动投注
		$sql = "call web_tz_gamehg16_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("korea16 {$NextNo} auto press:{$result[0][0]['result']}");
	
		//韩国11自动投注
		$sql = "call web_tz_gamehg11_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("korea11 {$NextNo} auto press:{$result[0][0]['result']}");
	
		//韩国36自动投注
		$sql = "call web_tz_gamehg36_auto_new({$No},{$NextNo})";
		$result = $this->db->MultiQuery($sql);
		if($result[0][0]['result'] == 99){
			sleep(1);
			$result = $this->db->MultiQuery($sql);
		}
		$this->Logger("korea36 {$NextNo} auto press:{$result[0][0]['result']}");
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



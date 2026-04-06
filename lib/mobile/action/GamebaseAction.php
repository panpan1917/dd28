<?php

class GamebaseAction extends BaseAction
{
    function __construct()
    {
        parent::__construct();
        $this->RefreshPoints();
    }
    
    function parameterCheck($str) {
    	return preg_match('/group_concat|table|create|call|drop|database|alter|select|truncate|call|insert|update|delete|name_const|where|from| and | or |truncate|script|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i',$str,$matches);
    }

    protected function GetGameTableName($act, $t)
    {
        $tablegame = "";
        $tablegame_auto = "";
        $tablegame_auto_tz = "";
        $tablegame_kg_users_tz = "";
        $tablegame_users_tz = "";
        $tableret = "";
        switch ($act) {
            case "0"://gamefast28
                $tablegame = "gamefast28";
                $tablegame_auto = "gamefast28_auto";
                $tablegame_auto_tz = "gamefast28_auto_tz";
                $tablegame_kg_users_tz = "gamefast28_kg_users_tz";
                $tablegame_users_tz = "gamefast28_users_tz";
                break;
            case "1"://gamefast16
                $tablegame = "gamefast16";
                $tablegame_auto = "gamefast16_auto";
                $tablegame_auto_tz = "gamefast16_auto_tz";
                $tablegame_kg_users_tz = "gamefast16_kg_users_tz";
                $tablegame_users_tz = "gamefast16_users_tz";
                break;
            case "2"://gamefast11
                $tablegame = "gamefast11";
                $tablegame_auto = "gamefast11_auto";
                $tablegame_auto_tz = "gamefast11_auto_tz";
                $tablegame_kg_users_tz = "gamefast11_kg_users_tz";
                $tablegame_users_tz = "gamefast11_users_tz";
                break;
            case "3"://game28
                $tablegame = "game28";
                $tablegame_auto = "game28_auto";
                $tablegame_auto_tz = "game28_auto_tz";
                $tablegame_kg_users_tz = "game28_kg_users_tz";
                $tablegame_users_tz = "game28_users_tz";
                break;
            case "4":
                $tablegame = "gameself28";
                $tablegame_auto = "gameself28_auto";
                $tablegame_auto_tz = "gameself28_auto_tz";
                $tablegame_kg_users_tz = "gameself28_kg_users_tz";
                $tablegame_users_tz = "gameself28_users_tz";
                break;
            case "5":
                $tablegame = "gamebj16";
                $tablegame_auto = "gamebj16_auto";
                $tablegame_auto_tz = "gamebj16_auto_tz";
                $tablegame_kg_users_tz = "gamebj16_kg_users_tz";
                $tablegame_users_tz = "gamebj16_users_tz";
                break;
            case "6":
                $tablegame = "gamepk10";
                $tablegame_auto = "gamepk10_auto";
                $tablegame_auto_tz = "gamepk10_auto_tz";
                $tablegame_kg_users_tz = "gamepk10_kg_users_tz";
                $tablegame_users_tz = "gamepk10_users_tz";
                break;
            case "7":
                $tablegame = "gamegj10";
                $tablegame_auto = "gamegj10_auto";
                $tablegame_auto_tz = "gamegj10_auto_tz";
                $tablegame_kg_users_tz = "gamegj10_kg_users_tz";
                $tablegame_users_tz = "gamegj10_users_tz";
                break;
            case "8":
                $tablegame = "gamecan28";
                $tablegame_auto = "gamecan28_auto";
                $tablegame_auto_tz = "gamecan28_auto_tz";
                $tablegame_kg_users_tz = "gamecan28_kg_users_tz";
                $tablegame_users_tz = "gamecan28_users_tz";
                break;
            case "9":
                $tablegame = "gamecan16";
                $tablegame_auto = "gamecan16_auto";
                $tablegame_auto_tz = "gamecan16_auto_tz";
                $tablegame_kg_users_tz = "gamecan16_kg_users_tz";
                $tablegame_users_tz = "gamecan16_users_tz";
                break;
            case "10":
                $tablegame = "gamecan11";
                $tablegame_auto = "gamecan11_auto";
                $tablegame_auto_tz = "gamecan11_auto_tz";
                $tablegame_kg_users_tz = "gamecan11_kg_users_tz";
                $tablegame_users_tz = "gamecan11_users_tz";
                break;
            case "11":
                $tablegame = "game36";
                $tablegame_auto = "game36_auto";
                $tablegame_auto_tz = "game36_auto_tz";
                $tablegame_kg_users_tz = "game36_kg_users_tz";
                $tablegame_users_tz = "game36_users_tz";
                break;
            case "12":
                $tablegame = "gamebj36";
                $tablegame_auto = "gamebj36_auto";
                $tablegame_auto_tz = "gamebj36_auto_tz";
                $tablegame_kg_users_tz = "gamebj36_kg_users_tz";
                $tablegame_users_tz = "gamebj36_users_tz";
                break;
            case "13":
                $tablegame = "gamecan36";
                $tablegame_auto = "gamecan36_auto";
                $tablegame_auto_tz = "gamecan36_auto_tz";
                $tablegame_kg_users_tz = "gamecan36_kg_users_tz";
                $tablegame_users_tz = "gamecan36_users_tz";
                break;
            case "14":
                $tablegame = "gamepk22";
                $tablegame_auto = "gamepk22_auto";
                $tablegame_auto_tz = "gamepk22_auto_tz";
                $tablegame_kg_users_tz = "gamepk22_kg_users_tz";
                $tablegame_users_tz = "gamepk22_users_tz";
                break;
            case "15":
                $tablegame = "gamefast10";
                $tablegame_auto = "gamefast10_auto";
                $tablegame_auto_tz = "gamefast10_auto_tz";
                $tablegame_kg_users_tz = "gamefast10_kg_users_tz";
                $tablegame_users_tz = "gamefast10_users_tz";
                break;
            case "16":
                $tablegame = "gamepklh";
                $tablegame_auto = "gamepklh_auto";
                $tablegame_auto_tz = "gamepklh_auto_tz";
                $tablegame_kg_users_tz = "gamepklh_kg_users_tz";
                $tablegame_users_tz = "gamepklh_users_tz";
                break;
            case "17":
                $tablegame = "gamepkgyj";
                $tablegame_auto = "gamepkgyj_auto";
                $tablegame_auto_tz = "gamepkgyj_auto_tz";
                $tablegame_kg_users_tz = "gamepkgyj_kg_users_tz";
                $tablegame_users_tz = "gamepkgyj_users_tz";
                break;
            case "18":
                $tablegame = "gamehg28";
                $tablegame_auto = "gamehg28_auto";
                $tablegame_auto_tz = "gamehg28_auto_tz";
                $tablegame_kg_users_tz = "gamehg28_kg_users_tz";
                $tablegame_users_tz = "gamehg28_users_tz";
                break;
            case "19":
                $tablegame = "gamehg16";
                $tablegame_auto = "gamehg16_auto";
                $tablegame_auto_tz = "gamehg16_auto_tz";
                $tablegame_kg_users_tz = "gamehg16_kg_users_tz";
                $tablegame_users_tz = "gamehg16_users_tz";
                break;
            case "20":
                $tablegame = "gamehg11";
                $tablegame_auto = "gamehg11_auto";
                $tablegame_auto_tz = "gamehg11_auto_tz";
                $tablegame_kg_users_tz = "gamehg11_kg_users_tz";
                $tablegame_users_tz = "gamehg11_users_tz";
                break;
            case "21":
                $tablegame = "gamehg36";
                $tablegame_auto = "gamehg36_auto";
                $tablegame_auto_tz = "gamehg36_auto_tz";
                $tablegame_kg_users_tz = "gamehg36_kg_users_tz";
                $tablegame_users_tz = "gamehg36_users_tz";
                break;
			case "22":
				$tablegame = "gamefast22";
				$tablegame_auto = "gamefast22_auto";
				$tablegame_auto_tz = "gamefast22_auto_tz";
				$tablegame_kg_users_tz = "gamefast22_kg_users_tz";
				$tablegame_users_tz = "gamefast22_users_tz";
				break;
			case "23":
				$tablegame = "gamefast36";
				$tablegame_auto = "gamefast36_auto";
				$tablegame_auto_tz = "gamefast36_auto_tz";
				$tablegame_kg_users_tz = "gamefast36_kg_users_tz";
				$tablegame_users_tz = "gamefast36_users_tz";
				break;	
			case "24":
				$tablegame = "gamefastgyj";
				$tablegame_auto = "gamefastgyj_auto";
				$tablegame_auto_tz = "gamefastgyj_auto_tz";
				$tablegame_kg_users_tz = "gamefastgyj_kg_users_tz";
				$tablegame_users_tz = "gamefastgyj_users_tz";
				break;
			case "25":
				$tablegame = "gameww";
				$tablegame_auto = "gameww_auto";
				$tablegame_auto_tz = "gameww_auto_tz";
				$tablegame_kg_users_tz = "gameww_kg_users_tz";
				$tablegame_users_tz = "gameww_users_tz";
				break;	
			case "26":
				$tablegame = "gamedw";
				$tablegame_auto = "gamedw_auto";
				$tablegame_auto_tz = "gamedw_auto_tz";
				$tablegame_kg_users_tz = "gamedw_kg_users_tz";
				$tablegame_users_tz = "gamedw_users_tz";
				break;
			case "27":
				$tablegame = "gamecanww";
				$tablegame_auto = "gamecanww_auto";
				$tablegame_auto_tz = "gamecanww_auto_tz";
				$tablegame_kg_users_tz = "gamecanww_kg_users_tz";
				$tablegame_users_tz = "gamecanww_users_tz";
				break;
			case "28":
				$tablegame = "gamecandw";
				$tablegame_auto = "gamecandw_auto";
				$tablegame_auto_tz = "gamecandw_auto_tz";
				$tablegame_kg_users_tz = "gamecandw_kg_users_tz";
				$tablegame_users_tz = "gamecandw_users_tz";
				break;
			case "29":
				$tablegame = "gamepksc";
				$tablegame_auto = "gamepksc_auto";
				$tablegame_auto_tz = "gamepksc_auto_tz";
				$tablegame_kg_users_tz = "gamepksc_kg_users_tz";
				$tablegame_users_tz = "gamepksc_users_tz";
				break;
			case "30":
				$tablegame = "gamehgww";
				$tablegame_auto = "gamehgww_auto";
				$tablegame_auto_tz = "gamehgww_auto_tz";
				$tablegame_kg_users_tz = "gamehgww_kg_users_tz";
				$tablegame_users_tz = "gamehgww_users_tz";
				break;
			case "31":
				$tablegame = "gamehgdw";
				$tablegame_auto = "gamehgdw_auto";
				$tablegame_auto_tz = "gamehgdw_auto_tz";
				$tablegame_kg_users_tz = "gamehgdw_kg_users_tz";
				$tablegame_users_tz = "gamehgdw_users_tz";
				break;
			case "32":
				$tablegame = "game28gd";
				$tablegame_auto = "game28gd_auto";
				$tablegame_auto_tz = "game28gd_auto_tz";
				$tablegame_kg_users_tz = "game28gd_kg_users_tz";
				$tablegame_users_tz = "game28gd_users_tz";
				break;
			case "33":
				$tablegame = "gamebj28gd";
				$tablegame_auto = "gamebj28gd_auto";
				$tablegame_auto_tz = "gamebj28gd_auto_tz";
				$tablegame_kg_users_tz = "gamebj28gd_kg_users_tz";
				$tablegame_users_tz = "gamebj28gd_users_tz";
				break;
			case "34":
				$tablegame = "gamehg28gd";
				$tablegame_auto = "gamehg28gd_auto";
				$tablegame_auto_tz = "gamehg28gd_auto_tz";
				$tablegame_kg_users_tz = "gamehg28gd_kg_users_tz";
				$tablegame_users_tz = "gamehg28gd_users_tz";
				break;
			case "35":
				$tablegame = "gamecan28gd";
				$tablegame_auto = "gamecan28gd_auto";
				$tablegame_auto_tz = "gamecan28gd_auto_tz";
				$tablegame_kg_users_tz = "gamecan28gd_kg_users_tz";
				$tablegame_users_tz = "gamecan28gd_users_tz";
				break;
			case "36":
				$tablegame = "gamexync";
				$tablegame_auto = "gamexync_auto";
				$tablegame_auto_tz = "gamexync_auto_tz";
				$tablegame_kg_users_tz = "gamexync_kg_users_tz";
				$tablegame_users_tz = "gamexync_users_tz";
				break;
			case "37":
				$tablegame = "gamecqssc";
				$tablegame_auto = "gamecqssc_auto";
				$tablegame_auto_tz = "gamecqssc_auto_tz";
				$tablegame_kg_users_tz = "gamecqssc_kg_users_tz";
				$tablegame_users_tz = "gamecqssc_users_tz";
				break;
			case "38":
				$tablegame = "gamebj11";
				$tablegame_auto = "gamebj11_auto";
				$tablegame_auto_tz = "gamebj11_auto_tz";
				$tablegame_kg_users_tz = "gamebj11_kg_users_tz";
				$tablegame_users_tz = "gamebj11_users_tz";
				break;
			case "39":
				$tablegame = "game11";
				$tablegame_auto = "game11_auto";
				$tablegame_auto_tz = "game11_auto_tz";
				$tablegame_kg_users_tz = "game11_kg_users_tz";
				$tablegame_users_tz = "game11_users_tz";
				break;
			case "40":
				$tablegame = "game16";
				$tablegame_auto = "game16_auto";
				$tablegame_auto_tz = "game16_auto_tz";
				$tablegame_kg_users_tz = "game16_kg_users_tz";
				$tablegame_users_tz = "game16_users_tz";
				break;
			case "41":
				$tablegame = "gamebjww";
				$tablegame_auto = "gamebjww_auto";
				$tablegame_auto_tz = "gamebjww_auto_tz";
				$tablegame_kg_users_tz = "gamebjww_kg_users_tz";
				$tablegame_users_tz = "gamebjww_users_tz";
				break;
			case "42":
				$tablegame = "gamebjdw";
				$tablegame_auto = "gamebjdw_auto";
				$tablegame_auto_tz = "gamebjdw_auto_tz";
				$tablegame_kg_users_tz = "gamebjdw_kg_users_tz";
				$tablegame_users_tz = "gamebjdw_users_tz";
				break;
			case "43":
				$tablegame = "gameairship10";
				$tablegame_auto = "gameairship10_auto";
				$tablegame_auto_tz = "gameairship10_auto_tz";
				$tablegame_kg_users_tz = "gameairship10_kg_users_tz";
				$tablegame_users_tz = "gameairship10_users_tz";
				break;
			case "44":
				$tablegame = "gameairship22";
				$tablegame_auto = "gameairship22_auto";
				$tablegame_auto_tz = "gameairship22_auto_tz";
				$tablegame_kg_users_tz = "gameairship22_kg_users_tz";
				$tablegame_users_tz = "gameairship22_users_tz";
				break;
			case "45":
				$tablegame = "gameairshipgyj";
				$tablegame_auto = "gameairshipgyj_auto";
				$tablegame_auto_tz = "gameairshipgyj_auto_tz";
				$tablegame_kg_users_tz = "gameairshipgyj_kg_users_tz";
				$tablegame_users_tz = "gameairshipgyj_users_tz";
				break;
			case "46":
				$tablegame = "gameairshipgj10";
				$tablegame_auto = "gameairshipgj10_auto";
				$tablegame_auto_tz = "gameairshipgj10_auto_tz";
				$tablegame_kg_users_tz = "gameairshipgj10_kg_users_tz";
				$tablegame_users_tz = "gameairshipgj10_users_tz";
				break;
			case "47":
				$tablegame = "gameairshiplh";
				$tablegame_auto = "gameairshiplh_auto";
				$tablegame_auto_tz = "gameairshiplh_auto_tz";
				$tablegame_kg_users_tz = "gameairshiplh_kg_users_tz";
				$tablegame_users_tz = "gameairshiplh_users_tz";
				break;
					
				
            default:
                break;

        }
        switch ($t) {
            case "game":
                $tableret = $tablegame;
                break;
            case "auto":
                $tableret = $tablegame_auto;
                break;
            case "auto_tz":
                $tableret = $tablegame_auto_tz;
                break;
            case "kg_users_tz":
                $tableret = $tablegame_kg_users_tz;
                break;
            case "users_tz":
                $tableret = $tablegame_users_tz;
                break;
            default:
                break;
        }
        return $tableret;
    }
    function GetGameConfig($act){
        $json=ApplicationRegistry::getCache('game_config');
        if($json){
            $result= json_decode($json,true);
            foreach ($result as $k=>$v){
                if($v['game_type']==$act)return $v;
            }
            return $result[$act];
        }else {
            $sql = 'select game_type,game_name,game_table_prefix from game_config';
            $list = db::get_all($sql,'assoc');
            $result=array();
            foreach ($list as $k=>$v){
                if($v['game_type']==$act)$result=$v;
            }
            $json = json_encode($list, JSON_UNESCAPED_UNICODE);
            //ApplicationRegistry::setConf(
            ApplicationRegistry::setCache('game_config', $json);
            
            return $result;
        }
        return array();
    }

    function GetHeadContent($act, $sid, &$aret)
    {
        $tablegame = $this->GetGameTableName($act, "game");
        $tablegametz = $this->GetGameTableName($act, "users_tz");
        $SecondSub = -90;
        if (in_array($act,[0,1,2,15,22,23,24])) $SecondSub = -10;//急速  [0,1,2,15,22,23,24]
        //取当前待开奖
        $sql = "SELECT id,kgtime,now() as nowtime FROM {$tablegame} WHERE kj = 0 AND kgtime > DATE_ADD(NOW(),interval {$SecondSub} second) ORDER BY id LIMIT 1";
        $result = db::get_one($sql, 'assoc');
        $aret['preno'] = $result["id"];
        $aret['prekgtime'] = $this->DateDiff($result["kgtime"], $result["nowtime"], "s");
        //取游戏配置
        $sql = "select game_kj_delay,game_tz_close from game_config where game_type='{$act}'";
        $result = db::get_one($sql, 'assoc');
        $aret['game_kj_delay'] = $result['game_kj_delay'];
        $aret['game_tz_close'] = $result['game_tz_close'];
        return true;
    }
    function GetNewNo($act){
        $SecondSub=$this->delay_time($act);
        $tablegame = $this->GetGameTableName($act, "game");
        $sql = "SELECT id,kgtime,now() as nowtime FROM {$tablegame} WHERE kj = 0 AND kgtime > DATE_ADD(NOW(),interval {$SecondSub} second) ORDER BY id LIMIT 1";
        $result = db::get_one($sql, 'assoc');
        return $result['id'];
    }
    function delay_time($act){
        $SecondSub = -90;
        if (in_array($act,[0,1,2,15,22,23,24])) $SecondSub = -10;//急速  [0,1,2,15,22,23,24]
        return $SecondSub;
    }
    function GetKjTzTime($act){
        $sql = "select game_kj_delay,game_tz_close from game_config where game_type='{$act}'";
        $result = db::get_one($sql, 'assoc');
        $result=array('game_kj_delay'=>$result['game_kj_delay'],'game_tz_close'=>$result['game_tz_close']);
        return $result;
    }
    function GetCurrNo($act,$no){
        $tablegame = $this->GetGameTableName($act, "game");
        $aret=array();
        //取当前待开奖
        $sql = "SELECT id,kj,kgtime,now() as nowtime FROM {$tablegame} WHERE id=$no ORDER BY id LIMIT 1";
        $result = db::get_one($sql, 'assoc');
        $aret['preno'] = $result["id"];
        $aret['prekgtime'] = $this->DateDiff($result["kgtime"], $result["nowtime"], "s");
        $aret['kj']=$result['kj'];
        //取游戏配置
        $sql = "select game_kj_delay,game_tz_close from game_config where game_type='{$act}'";
        $result = db::get_one($sql, 'assoc');
        $aret['kjSec'] = $aret['prekgtime'] + $result['game_kj_delay'];
        $aret['StopSec'] = $aret['prekgtime'] - $result['game_tz_close'];
        return $aret;
    }
    function DateDiff($date1, $date2, $unit = ""){
        switch ($unit) {
            case 's':
                $dividend = 1;
                break;
            case 'i':
                $dividend = 60;
                break;
            case 'h':
                $dividend = 3600;
                break;
            case 'd':
                $dividend = 86400;
                break;
            default:
                $dividend = 86400;
        }
        $time1 = strtotime($date1);
        $time2 = strtotime($date2);
        if ($time1 && $time2)
            return (float)($time1 - $time2) / $dividend;
        return false;
    }

    //取离第一个开奖数字步长
    function GetFromBeginNumStep($GameType)
    {
		$step = 0;
		if($GameType == "14" || $GameType == "22" || $GameType == "44")//22游戏
			$step = 6;
		else if($GameType == "1" || $GameType == "5" || $GameType == "9" || $GameType == "17" || $GameType == "19" || $GameType == "24" || $GameType == "40" || $GameType == "45")
			$step = 3;
		else if($GameType == "2" || $GameType == "10" || $GameType == "20" || $GameType == "38" || $GameType == "39")
			$step = 2;
		else if($GameType == "6" || $GameType == "7" || $GameType == "11" || $GameType == "12" || $GameType == "13" || $GameType == "15" || $GameType == "16" || $GameType == "21" || $GameType == "23" || $GameType == "43" || $GameType == "46" || $GameType == "47")
			$step = 1;
	
		return $step;
    }
    //取赔率类型
    function GetGameOddsType($act)
    {
		$reward_num_type = "game28";
		if($act == "1" || $act == "5" || $act == "9" || $act == "19" || $act == "40")//16游戏
			$reward_num_type = "game16";
		else if($act == "2" || $act == "10" || $act == "20" || $act == "38" || $act == "39")//11游戏
			$reward_num_type = "game11";
		else if($act == "6" || $act == "7" || $act == "15" || $act == "43" || $act == "46") //PK10 飞艇10 急速10 冠军10
			$reward_num_type = "game10";
		else if($act == "11" || $act == "12" || $act == "13" || $act == "21" || $act == "23")//36游戏
			$reward_num_type = "game36";
		else if($act == "14" || $act == "22" || $act == "44")//22游戏
			$reward_num_type = "game22";
		else if($act == "16" || $act == "47")//龙虎游戏
			$reward_num_type = "gamelh";
		else if($act == "17" || $act == "24" || $act == "45")//冠亚军游戏
			$reward_num_type = "gamegyj";
		else if($act == "25" || $act == "27" || $act == "30" || $act == "41")
			$reward_num_type = "gameww";
		else if($act == "26" || $act == "28" || $act == "31" || $act == "42")
			$reward_num_type = "gamedw";
		else if($act == "29")
			$reward_num_type = "gamesc";
	
		return  $reward_num_type;
    }

    //获取游戏的赔率
    function get_config($id)
    {
        $sql='select game_press_min as press_min,game_press_max as press_max,game_std_press as pressNum from game_config where game_type='.$id;
        $row=db::get_one($sql);
        $row->gameType = count(explode(',', $row->pressNum));
        return $row;
    }
 
}
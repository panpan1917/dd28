<?php
function session_check(){
	global $db;
	if (empty($_SESSION["usersid"]) || empty($_SESSION["password"]) || empty($_SESSION["username"])){
		
		setcookie("usersid");
		setcookie("username");
		setcookie("password");
		
		session_destroy();
		echo "<meta charset=\"utf-8\" />";
		echo "<script>alert('您还没登录或者链接超时，请登录！');window.location='/login.php';</script>";
		exit;
	}else{
		//如果帐号被冻结，立即注销退出
		if(isset($_SESSION['freeze']) && $_SESSION['freeze'] == 1)
		{
			//退出
			
			setcookie("usersid");
			setcookie("username");
			setcookie("password");
			
			session_destroy();
			echo "<meta charset=\"utf-8\" />";
			echo "<script>alert('您的账号已经被冻结！');window.location='index.php';</script>";
			exit;
		}
		
		if(isset($_SESSION['logintime']) && !empty($_SESSION['logintime'])){
			$sql = "select logintime from users where id = '{$_SESSION['usersid']}'";
			$result = $db->query($sql);
			if($rs = $db->fetch_array($result))
			{
				if($rs['logintime'] != $_SESSION['logintime']){
					
					setcookie("usersid");
					setcookie("username");
					setcookie("password");
					
					session_destroy();
					echo "<meta charset=\"utf-8\" />";
					echo "<script>alert('您的账号在异地或其他客户端登录！');window.location='/login.php';</script>";
					exit;
				}
			}
		}
	}
}


function login_check(){
	global $db;
	$ret = ['status'=>0,'msg'=>''];
	if(isset($_SESSION['logintime']) && !empty($_SESSION['logintime'])){
		$sql = "select logintime,alertmsg from users where id = '{$_SESSION['usersid']}'";
		$result = $db->query($sql);
		if($rs = $db->fetch_array($result))
		{
			if($rs['logintime'] != $_SESSION['logintime']){
				session_destroy();
				$ret['status'] = 1;
				$ret['msg'] = "您的账号在异地或其他客户端登录！";
			}
			
			if(!empty($rs['alertmsg'])){
				$ret['status'] = 2;
				$ret['msg'] = $rs['alertmsg'];
			}
		}
	}
	
	echo json_encode($ret);
}

/* 
*取得用户组名
*/
function GetUserGroupName()
{
	global $db;
	$ret = "";
	$sql = "select experience from users where id = '{$_SESSION['usersid']}'";
	$result = $db->query($sql);
	if($rs = $db->fetch_array($result))
	{
		$exp = $rs['experience'];
		$sql = "select name from usergroups where {$exp} BETWEEN creditslower AND creditshigher ";
		$r = $db->query($sql);
		if($rg = $db->fetch_array($r))
		{
			$ret = $rg["name"];
		}
	}
	return $ret;
}
/* 更新分数
 *
*/
function RefreshPoints()
{
	if (!empty($_SESSION["usersid"]) && !empty($_SESSION["logintime"]) && !empty($_SESSION["username"])){
		global $db;
		$sql = "select points,back,dj from users where id = '{$_SESSION['usersid']}'";
		$result = $db->query($sql);
		if($rs = $db->fetch_array($result))
		{
			$_SESSION['points'] = $rs['points'];
			$_SESSION['bankpoints'] = $rs['back'];
			$_SESSION['freeze'] = $rs['dj'];
		}
		
		$sql = "SELECT id,reward_discount FROM usergroups
		WHERE (SELECT experience FROM  users WHERE id={$_SESSION['usersid']})
		BETWEEN creditslower AND creditshigher LIMIT 1";
		$result = $db->query($sql);
		if($rs = $db->fetch_array($result)){
			if($rs['id'] > 10) $rs['id'] = 10;
			$_SESSION['level'] = $rs['id'];
			$_SESSION['reward_discount'] = $rs['reward_discount'];
		}
		
		
		//如果帐号被冻结，立即注销退出
		if(isset($_SESSION['freeze']) && $_SESSION['freeze'] == 1)
		{
			//退出
			session_destroy();
			echo "<script language=javascript>window.location='index.php';</script>";
			exit;
		}
	}
}

/*取得sql语句limit
 * $curPage:当前页
 * $PageSize:页大小
 */
function GetLimit($curPage,$PageSize=20)
{
	if($curPage < 1) $curPage = 1;
	$BeginNum = $PageSize * ($curPage - 1);
	return " limit " . $BeginNum . "," . $PageSize;
}

/*过滤字符串，防止sql注入
 * $str
 */
function FilterStr($str)
{
	if (inject_check($str)) { exit('error parameter!'); }
    if (!get_magic_quotes_gpc()){
        $str=addslashes($str);
    }
    $str=str_replace("%","/%",$str);
    $str=htmldecode($str);
    return $str;
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
//取开奖号码个数
function GetGameRewardNumCount($act)
{
	$reward_num_cnt = 28;
	if($act == "1" || $act == "5" || $act == "9" || $act == "19" || $act == "40")//16游戏
		$reward_num_cnt = 16;
	else if($act == "2" || $act == "10" || $act == "20" || $act == "38" || $act == "39")//11游戏
		$reward_num_cnt = 11;
	else if($act == "6" || $act == "7" || $act == "15" || $act == "43" || $act == "46") //PK10 飞艇10 急速10 冠军10
		$reward_num_cnt = 10;
	else if($act == "11" || $act == "12" || $act == "13" || $act == "21" || $act == "23")//36游戏
		$reward_num_cnt = 5;
	else if($act == "14" || $act == "22" || $act == "44")//22游戏
		$reward_num_cnt = 22;
	else if($act == "16" || $act == "47")//龙虎游戏
		$reward_num_cnt = 2;
	else if($act == "17" || $act == "24" || $act == "45")//冠亚军游戏
		$reward_num_cnt = 17;

	return  $reward_num_cnt;
}


function getGame36Result($a,$b,$c){//36开奖结果
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


function GetGameTableName($act,$t)
{
	$tablegame = "";
	$tablegame_auto = "";
	$tablegame_auto_tz = "";
	$tablegame_kg_users_tz = "";
	$tablegame_users_tz = "";
	$tableret = "";
	switch($act)
	{
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
	switch($t)
	{
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

function GetSubMenu($act,$sid)
{
	global $db;
	$subMenuID = intval($sid);
	$class = " class='pick' ";
	$RetDiv = "<div class='titles' style='padding-top:5px;padding-bottom:35px;'>\r\n";
	$RetDiv .= "\t<ul class='list'>\r\n";
	$RetDiv .= "\t\t<li><div style='float:left'>\r\n";
	if($subMenuID == "") $subMenuID = 1;

	$GameName = "首页";
	$GameType = intval($act);
	$sql = "select game_name from game_config where game_type= '{$GameType}'" ;
	$result = $db->query($sql);
	if($rs = $db->fetch_array($result))
	{
		$GameName = ChangeEncodeG2U($rs['game_name']) . $GameName;
	}
	for($i = 1; $i <= 8; $i++)
	{
		if(in_array($act,[25,26,27,28,29,30,31,36,37,41,42]) && in_array($i,[4,5])) continue;
		if(in_array($act,[32,33,34,35]) && in_array($i,[5])) continue;
		switch($i)
		{
			case 1:
				$RetDiv .= "\t\t\t<a " . (($i == $subMenuID)?$class:"") . " href=\"javascript:getContent('sgame.php?act={$act}&sid={$i}')\">{$GameName}</a>\r\n | \r\n";
				break;

			case 2:
				$RetDiv .= "\t\t\t<a " . (($i == $subMenuID)?$class:"") . " href=\"javascript:getContent('sgamerule.php?act={$act}&sid={$i}')\"" . ">游戏规则</a>\r\n | \r\n";
				break;
			case 3:
				$RetDiv .= "\t\t\t<a " . (($i == $subMenuID)?$class:"") . " href=\"javascript:getContent('sgamerecord.php?act={$act}&sid={$i}')\"" . ">投注记录</a>\r\n | \r\n";
				break;
			case 4:
				$RetDiv .= "\t\t\t<a " . (($i == $subMenuID)?$class:"") . " href=\"javascript:getContent('smodel.php?act={$act}&sid={$i}')\"" . ">模式编辑</a>\r\n | \r\n";
				break;
			case 5:
				$RetDiv .= "\t\t\t<a " . (($i == $subMenuID)?$class:"") . " href=\"javascript:getContent('sautopress.php?act={$act}&sid={$i}')\"" . ">自动投注</a>\r\n | \r\n";
				break;
			case 7:
				$RetDiv .= "\t\t\t<a " . (($i == $subMenuID)?$class:"") . " href=\"javascript:getContent('strend.php?act={$act}&sid={$i}')\"" . ">走势图</a>\r\n | \r\n";
				break;
			case 8:
				$RetDiv .= "\t\t\t<a " . (($i == $subMenuID)?$class:"") . " href=\"javascript:getContent('swinstat.php?act={$act}&sid={$i}')\"" . ">盈利统计</a>\r\n";
				break;
			default:
				break;
		}
	}
	
	$RetDiv .= "\t\t<span style='color:red;padding-left:100px;'>每款游戏单期最大赔付500000000分！</span>\r\n";
	
	$RetDiv .= "\t\t</div>\r\n";
	
	if(!in_array($act,[25,26,27,28,29,30,31,32,33,34,35,36,37]))
		$RetDiv .= "\t\t\t<div style='float:right;padding-right:9px;'><a href=\"javascript:getContent('sautopress.php?act={$act}&sid=5');\" class=\"btn btn-danger\" style='color:#fff;text-decoration:none;'>自动投注>></a></div>\r\n";
	
	$RetDiv .= "\t\t</li>\r\n";
	$RetDiv .= "\t</ul>";
	$RetDiv .= "</div><script>var is_open=0;</script>";
	return $RetDiv;
}
function GetHeadContent($act,$sid,&$aret)
{
	global $db;
	$tablegame = GetGameTableName($act,"game");
	$tablegametz = GetGameTableName($act,"users_tz");
	$SecondSub = -80;//北京快乐8系列
	if(in_array($act,[18,19,20,21,30,31,34])) {//韩国系列
		$SecondSub = -90;
	}elseif (in_array($act,[6,7,14,16,17,29])){//PK系列
		$SecondSub=-120;
	}elseif (in_array($act,[43,44,45,46,47])){//飞艇系列
		$SecondSub=-120;
	}elseif(in_array($act,[0,1,2,15,22,23,24])){ //急速系列
		$SecondSub = -10;
	}elseif(in_array($act,[36])){ //幸运农场
		$SecondSub = -600;
	}
	//取当前待开奖
	$sql = "SELECT id,kgtime,now() as nowtime FROM {$tablegame} WHERE kj = 0 AND kgtime > DATE_ADD(NOW(),interval {$SecondSub} second) ORDER BY id LIMIT 1";
	$result = $db->query($sql);
	if($rs = $db->fetch_array($result))
	{
		$preNo = $rs["id"];
		$prekgTime = DateDiff($rs["kgtime"],$rs["nowtime"],"s");
		$aret['preno'] = $preNo;
		$aret['prekgtime'] = $prekgTime;
		$aret['kgtime']=$rs['kgtime'];
	}
	//取游戏配置
	$sql = "select game_kj_delay,game_tz_close from game_config where game_type='{$act}'";
	$result = $db->query($sql);
	if($rs = $db->fetch_array($result))
	{
		$aret['game_kj_delay'] = $rs['game_kj_delay'];
		$aret['game_tz_close'] = $rs['game_tz_close'];
	}

	//取最新一次开奖
	$sql = "SELECT id,kgjg,kgNo FROM {$tablegame} WHERE kj = 1 ORDER BY id DESC LIMIT 1";
	$result = $db->query($sql);
	if($rs = $db->fetch_array($result))
	{
		$kgNo = $rs["id"];
		$kgResult = $rs["kgNo"];
		$arrkg = explode("|",$rs["kgjg"]);
		/*
		if($act == "3" || $act == "4" || $act == "5" || $act == "11" || $act == "12" ||
			$act == "18" || $act == "19" || $act == "20" || $act == "21" ||
			$act == "8" || $act == "9" || $act == "10" || $act == "13")
		{
			$arrTemp = explode("|",$kgResult);
			sort($arrTemp,SORT_NUMERIC);
			$kgResult = implode("|",$arrTemp);
		}
		*/
	}
	//写头部
	$divPeriod = "<div class='period'>\r\n";
	$divPeriod .= "\t<div>\r\n";
	$divPeriod .= "\t\t<h3>第" . $kgNo . "期</h3>\r\n";
	$divPeriod .= "\t\t<span>开奖结果</span>\r\n";
	$divPeriod .= "\t</div>\r\n";
	$divPeriod .= "\t<ul>\r\n";
	
	if(in_array($act,[3,4,5,8,9,11,12,13,18,19,21,25,26,27,28,30,31,32,33,34,35,39,40,41,42])){//北京韩国加拿大快乐8源的16,28,36游戏
		$divnums = explode("|",$kgResult);
		if (in_array($act,[3,11,25,26,32,39,40])){   // 蛋蛋28 蛋蛋36 蛋蛋外围 蛋蛋定位;$act == "3" || $act == "11"
			$str .= "<span>";
			foreach($divnums as $k => $v){
				if (in_array($k+1,array(1,2,3,4,5,6))) $str.= "<span class='red'>$v</span> ";
				if (in_array($k+1,array(7,8,9,10,11,12))) $str.= "<span class='blue'>$v</span> ";
				if (in_array($k+1,array(13,14,15,16,17,18))) $str.= "<span class='brown'>$v</span> ";
				if (in_array($k+1,array(19,20))) $str.="<span class='grey'>$v</span> ";
			}
			$str .= "</span>";
			$divPeriod .= "\t\t<li>$str</li>\r\n";
		}

		if (in_array($act,[4,8,12,13,18,21,27,28,30,31,33,34,35,41,42])){  //北京韩国加拿大快乐8源的28,36游戏,加拿大外围，加拿大定位
			$str='';
			foreach ($divnums as $k=>$v){
				if(in_array($k+1,array(1,20) ))$str.='<span class="grey">'.$v.' </span>';
				if(in_array($k+1,array(2,5,8,11,14,17)))$str.='<span class="red">'.$v.' </span>';
				if(in_array($k+1,array(3,6,9,12,15,18)))$str.='<span class="blue">'.$v.' </span>';
				if(in_array($k+1,array(4,7,10,13,16,19)))$str.='<span class="brown">'.$v.' </span>';
			}
			$divPeriod.="<li>".$str."</li>";
		}
		if (in_array($act,[5,9,19])){  //北京16,韩国16,加拿大16
			$str='';
			foreach ($divnums as $k=>$v){
				if(in_array($k+1,array(1,4,7,10,13,16) ))$str.='<span class="red">'.$v.' </span>';
				if(in_array($k+1,array(2,5,8,11,14,17)))$str.='<span class="blue">'.$v.' </span>';
				if(in_array($k+1,array(3,6,9,12,15,18)))$str.='<span class="brown">'.$v.' </span>';
				if(in_array($k+1,array(19,20)))$str.='<span class="grey">'.$v.' </span>';
			}
			$divPeriod.="<li>".$str."</li>";
		}

		//$divPeriod .= "\t\t<li class='kaic'><a target='_blank' href='http://www.bwlc.net/bulletin/prevkeno.html'>第三方开奖查询</a></li>\r\n";
	}
	else if(in_array($act,[36]))//幸运农场
	{
		$divn = explode("|",$kgResult);
		$str .= "<span>";
		foreach($divn as $k => $v){
			//$str .= "<span class='grey'>$v</span> ";
			$kjNum = substr("0".$v , -2);
			$str .= "<em class=\"num{$kjNum} number kjnhidden\"></em>";
		}
		$str .= "</span>";
		$divPeriod .= "\t\t<li>$str</li>\r\n";
	}
	else if(in_array($act,[37]))//重庆时时彩
	{
		$divn = explode("|",$kgResult);
		$str .= "<span>";
		foreach($divn as $k => $v){
			$str .= "<span class='grey'>$v</span> ";
			//$str .= show_num($v,1);
		}
		$str .= "</span>";
		$divPeriod .= "\t\t<li>$str</li>\r\n";
	}
	else if(in_array($act,[6,7,14,16,17,29,43,44,45,46,47])) //北京pk 马耳他飞艇
	{
		switch ($act){
			case '6' :
				$_html['kgNo_last'] = substr($kgNo,-1);
				$divn = explode("|",$kgResult);
				$str ='<span>';
				foreach($divn as $k => $v){
					if ($divn[$k] == $arrkg[1]){
						$str .= '<span class="red"> '.$v.'</span>';
					}else{
						$str .= '<span class="grey"> '.$v.'</span>';
					}
				}
				$str .='</span>';
				$divPeriod .= "\t\t<li><span style=\"color:red;\">".$str."<span></li>\r\n";
				break;
			case '7' :
				$divn = explode("|",$kgResult);
				$str .= "<span>";
				foreach($divn as $k => $v){
					if (in_array($k+1,array(1))) $str.= "<span class='red'>$v</span> ";
					if (in_array($k+1,array(2,3,4,5,6,7,8,9,10))) $str .= "<span class='grey'>$v</span> ";
				}
				$str .= "</span>";
				$divPeriod .= "\t\t<li>$str</li>\r\n";
				break;
			case '14' :
				$divn = explode("|",$kgResult);
				$str .= "<span>";
				foreach($divn as $k => $v){
					if (in_array($k+1,array(1,2,3))) $str.= "<span class='red'>$v</span> ";
					if (in_array($k+1,array(4,5,6,7,8,9,10))) $str .= "<span class='grey'>$v</span> ";
				}
				$str .= "</span>";
				$divPeriod .= "\t\t<li>$str</li>\r\n";
				break;
			case '16' :
				$divn = explode("|",$kgResult);
				$str .= "<span>";
				foreach($divn as $k => $v){
					if (in_array($k+1,array(1))) $str.= "<span class='red'>$v</span> ";
					if (in_array($k+1,array(2,3,4,5,6,7,8,9))) $str .= "<span class='grey'>$v</span> ";
					if (in_array($k+1,array(10))) $str .= "<span class='blue'>$v</span> ";
				}
				$str .= "</span>";
				$divPeriod .= "\t\t<li>$str</li>\r\n";
				break;
			case '17' :
				$divn = explode("|",$kgResult);
				$str .= "<span>";
				foreach($divn as $k => $v){
					if (in_array($k+1,array(1,2))) $str.= "<span class='red'>$v</span> ";
					if (in_array($k+1,array(3,4,5,6,7,8,9,10))) $str .= "<span class='grey'>$v</span> ";
				}
				$str .= "</span>";
				$divPeriod .= "\t\t<li>$str</li>\r\n";
				break;
			case '29' :
				$divn = explode("|",$kgResult);
				$str .= "<span>";
				foreach($divn as $k => $v){
					$str .= "<span class='grey'>$v</span> ";
				}
				$str .= "</span>";
				$divPeriod .= "\t\t<li>$str</li>\r\n";
				break;
				
			case '43' :
				$_html['kgNo_last'] = substr($kgNo,-1);
				$divn = explode("|",$kgResult);
				$str ='<span>';
				foreach($divn as $k => $v){
					if ($divn[$k] == $arrkg[1]){
						$str .= '<span class="red"> '.$v.'</span>';
					}else{
						$str .= '<span class="grey"> '.$v.'</span>';
					}
				}
				$str .='</span>';
				$divPeriod .= "\t\t<li><span style=\"color:red;\">".$str."<span></li>\r\n";
				break;
			case '46' :
				$divn = explode("|",$kgResult);
				$str .= "<span>";
				foreach($divn as $k => $v){
					if (in_array($k+1,array(1))) $str.= "<span class='red'>$v</span> ";
					if (in_array($k+1,array(2,3,4,5,6,7,8,9,10))) $str .= "<span class='grey'>$v</span> ";
				}
				$str .= "</span>";
				$divPeriod .= "\t\t<li>$str</li>\r\n";
				break;
			case '44' :
				$divn = explode("|",$kgResult);
				$str .= "<span>";
				foreach($divn as $k => $v){
					if (in_array($k+1,array(1,2,3))) $str.= "<span class='red'>$v</span> ";
					if (in_array($k+1,array(4,5,6,7,8,9,10))) $str .= "<span class='grey'>$v</span> ";
				}
				$str .= "</span>";
				$divPeriod .= "\t\t<li>$str</li>\r\n";
				break;
			case '47' :
				$divn = explode("|",$kgResult);
				$str .= "<span>";
				foreach($divn as $k => $v){
					if (in_array($k+1,array(1))) $str.= "<span class='red'>$v</span> ";
					if (in_array($k+1,array(2,3,4,5,6,7,8,9))) $str .= "<span class='grey'>$v</span> ";
					if (in_array($k+1,array(10))) $str .= "<span class='blue'>$v</span> ";
				}
				$str .= "</span>";
				$divPeriod .= "\t\t<li>$str</li>\r\n";
				break;
			case '45' :
				$divn = explode("|",$kgResult);
				$str .= "<span>";
				foreach($divn as $k => $v){
					if (in_array($k+1,array(1,2))) $str.= "<span class='red'>$v</span> ";
					if (in_array($k+1,array(3,4,5,6,7,8,9,10))) $str .= "<span class='grey'>$v</span> ";
				}
				$str .= "</span>";
				$divPeriod .= "\t\t<li>$str</li>\r\n";
				break;
		}
		//$divPeriod .= "\t\t<li class='kaic'><a target='_blank' href='http://www.bwlc.net/bulletin/prevtrax.html'>第三方开奖查询</a></li>\r\n";
	}
	else if(in_array($act,[10,20,38])) //加拿大11,韩国源11,北京11
	{
		$divn = explode("|",$kgResult);
		$str .="<span>";
		foreach($divn as $k => $v){
			if (in_array($k+1,array(1,4,7,10,13,16))) $str.= "<span class='red'>$v</span> ";
			if (in_array($k+1,array(2,5,8,11,14,17,19,20))) $str.= "<span class='grey'>$v</span> ";
			if (in_array($k+1,array(3,6,9,12,15,18))) $str.= "<span class='blue'>$v</span> ";
		}
		$str .= "</span>";
		$divPeriod .= "\t\t<li>".$str."</li>\r\n";

		//$divPeriod .= "\t\t<li class='kaic'><a target='_blank' href='http://www.kenolotto.kr/kenoWinNoList.php'>第三方开奖查询</a></li>\r\n";
	}else{
		if(in_array($act,[15,22,24])) {//急速10,急速22,急速冠亚军
			$divn = explode("|", $kgResult);
			$str = '<span>';
			foreach ($divn as $k => $v) {
				if ($k == 0) {
					$str .= '<span class="red">' . $v . ' </span>';
				} else {
					if($k == 1 && ($act==22 || $act==24))
						$str .= '<span class="red">' . $v . ' </span>';
					else{
						if($k == 2 && $act==22)
							$str .= '<span class="red">' . $v . ' </span>';
						else
							$str .= '<span class="grey"> '.$v . '</span> ';
					}
				}
			}
			$str .= '</span>';
			$divPeriod .= '<li>' . $str . '</li>';
		}else {
			$divPeriod .= "\t\t<li> 系统开奖</li>\r\n";
		}
	}




	$divPeriod .= "\t\t<li class='pers'>\r\n";
	$divPeriod .= "\t\t<dl>\r\n";
	if(!in_array($act,[6,7,36,37,43,46])) {
		$divPeriod .= "\t\t\t<dd><i style='position:relative; top:-5px;'>一区号码:</i>" . " <span class=\"kj kj_{$arrkg[0]}\"></span></dd>\r\n";
		$divPeriod .= "\t\t\t<dd><i style='position:relative; top:-5px;'>二区号码:</i>" . " <span class=\"kj kj_{$arrkg[1]}\"></span></dd>\r\n";
	}
	
	if($act == 36)
	{
		$divPeriod .= "\t\t\t<dd><span class=\"he\">{$arrkg[8]}</span></dd>\r\n";
	}
	elseif($act == 37)
	{
		$divPeriod .= "\t\t\t<dd><span class=\"he\">{$arrkg[5]}</span></dd>\r\n";
	}
	elseif($arrkg[2] == "-1") //只有两区
	{
		$LastKGResult = " <span class=\"mh m{$arrkg[3]}\"></span>";
		if(in_array($act,[16,47])) //pk龙虎 飞艇龙虎单独处理
		{
			if($arrkg[3] == 1) //龙
			{
				$LastKGResult = "<em class=\"lh n1\"></em>";
				//$LastKGResult = "<em class='lh0'></em>";
			}
			else //虎
			{
				$LastKGResult = "<em class=\"lh n2\"></em>";
				//$LastKGResult = "<em class='lh1'></em>";
			}
		}
		$divPeriod .= "\t\t\t<dd><i style='position:relative; top:-12px;'>结果:</i>" . $LastKGResult ."</dd>\r\n";
	}
	else //有三区
	{
		$kjFinal = $arrkg[3];
		if(in_array($act,[11,12,13,21,23])) //36游戏
		{
			switch($arrkg[3])
			{
				case 1:
					$kjFinal = "豹";
					break;
				case 2:
					$kjFinal = "对";
					break;
				case 3:
					$kjFinal = "顺";
					break;
				case 4:
					$kjFinal = "半";
					break;
				case 5:
					$kjFinal = "杂";
					break;
				default:
					$kjFinal = "";
					break;
			}
		}
		$divPeriod .= "\t\t\t<dd><i style='position:relative; top:-5px;'>三区号码:</i>" . " <span class=\"kj kj_{$arrkg[2]}\"></span></dd>\r\n";
		if (is_numeric($kjFinal)){
			$divPeriod .= "\t\t\t<dd><i style='position:relative; top:-7px;'>结果:</i>" . " <span class=\"mh m{$kjFinal}\"></span></dd>\r\n";
		}else{
			$divPeriod .= "\t\t\t<dd><i style='position:relative; top:-12px;'>结果:</i>" . " <em class=\"zh z{$arrkg[3]}\"></em></dd>\r\n";
		}

	}
	if (in_array($act,[3,4,5,11,12,25,26,32,33,38,39,40,41,42])){//北京,蛋蛋类 
		$divPeriod .= "\t\t<dd class='kaic'><a target='_blank' href='http://www.bwlc.net/bulletin/prevkeno.html'>第三方开奖查询</a></dd>\r\n";
	}elseif (in_array($act,[18,19,20,21,30,31,34])){//韩国类
		$divPeriod .= "\t\t<dd class='kaic'><a target='_blank' href='http://www.kenolotto.kr/kenoWinNoList.php'>第三方开奖查询</a></dd>\r\n";
	}elseif (in_array($act,[6,7,14,16,17,29])){//pk类
		$divPeriod .= "\t\t<dd class='kaic'><a target='_blank' href='http://www.bwlc.net/bulletin/prevtrax.html'>第三方开奖查询</a></dd>\r\n";
	}elseif (in_array($act,[8,9,10,13,27,28,35])){//加拿大类
		$divPeriod .= "\t\t<dd class='kaic'><a target='_blank' href='http://lotto.bclc.com/winning-numbers/keno-and-keno-bonus.html'>第三方开奖查询</a></dd>\r\n";
	}elseif (in_array($act,[36])){//幸运农场
		$divPeriod .= "\t\t<dd class='kaic'><a target='_blank' href='http://www.cqcp.net/game/xync/'>第三方开奖查询</a></dd>\r\n";
	}elseif (in_array($act,[37])){//重庆时时彩
		$divPeriod .= "\t\t<dd class='kaic'><a target='_blank' href='http://www.cqcp.net/game/ssc/'>第三方开奖查询</a></dd>\r\n";
	}elseif (in_array($act,[43,44,45,46,47])){//马耳他飞艇
		$divPeriod .= "\t\t<dd class='kaic'><a target='_blank' href='http://www.luckyairship.com/history.html'>第三方开奖查询</a></dd>\r\n";
	}
	
	$divPeriod .= "\t\t</dl>\r\n";
	$divPeriod .= "\t\t</li>\r\n";
	$divPeriod .= "\t</ul>\r\n";
	$divPeriod .= "</div>\r\n";

	//写中间
	$sql = "SELECT 	COUNT(id) totalcount,IFNULL(SUM(hdpoints - points),0) winpoints,COUNT(IF(hdpoints - points > 0,TRUE,NULL)) AS wincount,
					COUNT(IF(hdpoints - points < 0,TRUE,NULL)) AS losscount
	        FROM {$tablegametz} WHERE uid = '{$_SESSION['usersid']}' AND `time` > CURDATE()";
	$result = $db->query($sql);
	if($rs = $db->fetch_array($result))
	{
		$totalCount = $rs["totalcount"];
		$sumWinPoint = $rs["winpoints"];
		$winCount = $rs["wincount"];
		$lossCount = $rs["losscount"];
		if($winCount+$lossCount != 0)
			$winOdds = round($winCount/($winCount+$lossCount) * 100);
		else 
			$winOdds = 0;
	}
	$divTies = "<div class='ties'>\r\n";
	$divTies .= "\t<ul>\r\n";
	$divTies .= "\t\t<li id='liTimer' style='width:335px;'><i></i><em></em></li>\r\n";
	
	
	$divTies .= "\t\t<li style='width:430px;'><a id=\"xlb_gongshowdiv\" target=\"_blank\" style=\"color:#000\"></a></li>\r\n";
	
	
	$divTies .= "\t\t<li style='width:410px;'>今日盈亏:<em>".Trans($sumWinPoint)."</em> 参与:<i>{$totalCount}</i>期 胜率:<i>{$winOdds}%</i></li></ul>\r\n";	
	$divTies .= "<ul class=\"sond\" style=\"padding:5px 0px;\"><img id=\"sond_offon\" onclick=\"sondclick(this)\" src=\"/images/S_Close.gif\"></ul>";
	$divTies .= "\t</ul>\r\n";
	//$divTies .= "\t<span class=\"autotz\"><a href=\"javascript:getContent('sautopress.php?act={$act}&sid=5');\" class=\"btn btn-danger btn-block\">自动投注>></a></span>\r\n";
	$divTies .= "</div>\r\n";

	return $divPeriod . $divTies;
}

function GetHeadContent_beifen($act,$sid,&$aret)
{
	global $db;
	$tablegame = GetGameTableName($act,"game");
	$tablegametz = GetGameTableName($act,"users_tz");
	$SecondSub = -90;
	if(in_array($act,[0,1,2,15,22,23,24])) //急速
		$SecondSub = 10;
	//取当前待开奖
	$sql = "SELECT id,kgtime,now() as nowtime FROM {$tablegame} WHERE kj = 0 AND kgtime > DATE_ADD(NOW(),interval {$SecondSub} second) ORDER BY id LIMIT 1";
	$result = $db->query($sql);
	if($rs = $db->fetch_array($result))
	{
		$preNo = $rs["id"];
		$prekgTime = DateDiff($rs["kgtime"],$rs["nowtime"],"s");
		$aret['preno'] = $preNo;
		$aret['prekgtime'] = $prekgTime;
	}
	//取游戏配置
	$sql = "select game_kj_delay,game_tz_close from game_config where game_type='{$act}'";
	$result = $db->query($sql);
	if($rs = $db->fetch_array($result))
	{
		$aret['game_kj_delay'] = $rs['game_kj_delay'];
		$aret['game_tz_close'] = $rs['game_tz_close'];
	}

	//取最新一次开奖
	$sql = "SELECT id,kgjg,kgNo FROM {$tablegame} WHERE kj = 1 ORDER BY id DESC LIMIT 1";
	$result = $db->query($sql);
	if($rs = $db->fetch_array($result))
	{
		$kgNo = $rs["id"];
		$kgResult = $rs["kgNo"];
		$arrkg = explode("|",$rs["kgjg"]);
		/*
		if($act == "3" || $act == "4" || $act == "5" || $act == "11" || $act == "12" ||
			$act == "18" || $act == "19" || $act == "20" || $act == "21" ||
			$act == "8" || $act == "9" || $act == "10" || $act == "13")
		{
			$arrTemp = explode("|",$kgResult);
			sort($arrTemp,SORT_NUMERIC);
			$kgResult = implode("|",$arrTemp);
		}
		*/
	}
	//写头部
	$divPeriod = "<div class='period'>\r\n";
	$divPeriod .= "\t<div>\r\n";
	$divPeriod .= "\t\t<h3>第" . $kgNo . "期</h3>\r\n";
	$divPeriod .= "\t\t<span>开奖结果</span>\r\n";
	$divPeriod .= "\t</div>\r\n";
	$divPeriod .= "\t<ul>\r\n";




	if (in_array($act,[3,4,5,11,12,25,26,32,33,38,39,40,41,42])){//北京类 蛋蛋类
		$divPeriod .= "\t\t<li class='kaic'><a target='_blank' href='http://www.bwlc.net/bulletin/prevkeno.html'>第三方开奖查询</a></li>\r\n";
	}elseif (in_array($act,[18,19,20,21,30,31,34])){//	韩国类
		$divPeriod .= "\t\t<li class='kaic'><a target='_blank' href='http://www.kenolotto.kr/kenoWinNoList.php'>第三方开奖查询</a></li>\r\n";
	}elseif (in_array($act,[6,7,14,16,17,29])){		// pk类
		$divPeriod .= "\t\t<li class='kaic'><a target='_blank' href='http://www.bwlc.net/bulletin/prevtrax.html'>第三方开奖查询</a></li>\r\n";
	}elseif (in_array($act,[8,9,10,13,27,28,35])){//加拿大类
		$divPeriod .= "\t\t<li class='kaic'><a target='_blank' href='http://lotto.bclc.com/winning-numbers/keno-and-keno-bonus.html'>第三方开奖查询</a></li>\r\n";
	}elseif (in_array($act,[36])){//幸运农场
		$divPeriod .= "\t\t<li class='kaic'><a target='_blank' href='http://www.cqcp.net/game/xync/'>第三方开奖查询</a></li>\r\n";
	}elseif (in_array($act,[37])){//重庆时时彩
		$divPeriod .= "\t\t<li class='kaic'><a target='_blank' href='http://www.cqcp.net/game/ssc/'>第三方开奖查询</a></li>\r\n";
	}elseif (in_array($act,[43,44,45,46,47])){//马耳他飞艇
		$divPeriod .= "\t\t<li class='kaic'><a target='_blank' href='http://www.luckyairship.com/history.html'>第三方开奖查询</a></li>\r\n";
	}

	
	
	

	$divPeriod .= "\t\t<li class='pers'>\r\n";
	if(!in_array($act,[6,7,36,37,43,46])) {
		$divPeriod .= "\t\t\t一区号码:" . " <i class=\"kj kj_{$arrkg[0]}\"></i>\r\n";
		$divPeriod .= "\t\t\t二区号码:" . " <i class=\"kj kj_{$arrkg[1]}\"></i>\r\n";
	}
	if($arrkg[2] == "-1") //只有两区
	{
		$LastKGResult = " <em class=\"mh m{$arrkg[3]}\"></em>";
		if(in_array($act,[16,47])) //pk龙虎 飞艇龙虎单独处理
		{
			if($arrkg[3] == 1) //龙
			{
				$LastKGResult = "<i class=\"lh n1\"></i>";
				//$LastKGResult = "<em class='lh0'></em>";
			}
			else //虎
			{
				$LastKGResult = "<i class=\"lh n2\"></i>";
				//$LastKGResult = "<em class='lh1'></em>";
			}
		}
		$divPeriod .= "\t\t\t结果:" . $LastKGResult . "\r\n";
	}
	else //有三区
	{
		$kjFinal = $arrkg[3];
		if(in_array($act,[11,12,13,21,23])) //36游戏
		{
			switch($arrkg[3])
			{
				case 1:
					$kjFinal = "豹";
					break;
				case 2:
					$kjFinal = "对";
					break;
				case 3:
					$kjFinal = "顺";
					break;
				case 4:
					$kjFinal = "半";
					break;
				case 5:
					$kjFinal = "杂";
					break;
				default:
					$kjFinal = "";
					break;
			}
		}
		$divPeriod .= "\t\t\t三区号码:" . " <i class=\"kj kj_{$arrkg[2]}\"></i>\r\n";
		if (is_numeric($kjFinal)){
			$divPeriod .= "\t\t\t结果:" . " <em class=\"mh m{$kjFinal}\"></em>\r\n";
		}else{
			$divPeriod .= "\t\t\t结果:" . " <em class=\"zh z{$arrkg[3]}\"></em>\r\n";
		}

	}
	$divPeriod .= "\t\t</li>\r\n";
	$divPeriod .= "\t</ul>\r\n";
	$divPeriod .= "</div>\r\n";

	//写中间
	$sql = "SELECT 	COUNT(id) totalcount,IFNULL(SUM(hdpoints - points),0) winpoints,COUNT(IF(hdpoints - points > 0,TRUE,NULL)) AS wincount,
					COUNT(IF(hdpoints - points < 0,TRUE,NULL)) AS losscount
	        FROM {$tablegametz} WHERE uid = '{$_SESSION['usersid']}' AND `time` > CURDATE()";
	$result = $db->query($sql);
	if($rs = $db->fetch_array($result))
	{
		$totalCount = $rs["totalcount"];
		$sumWinPoint = $rs["winpoints"];
		$winCount = $rs["wincount"];
		$lossCount = $rs["losscount"];
		$winOdds = round($winCount/($winCount+$lossCount) * 100);
	}
	$divTies = "<div class='ties'>\r\n";
	$divTies .= "\t<ul>\r\n";
	$divTies .= "\t\t<li id='liTimer'><i></i><em></em></li>\r\n";
	$divTies .= "\t\t<li>今日盈亏:<em>".Trans($sumWinPoint)."</em> 参与:<i>{$totalCount}</i>期 胜率:<i>{$winOdds}%</i>\r\n";
	$divTies .= "\t</ul>\r\n";
	//$divTies .= "\t<p><a href=\"javascript:getContent('sautopress.php?act={$act}&sid=5');\" class=\"btn btn-danger btn-block\">自动投注>></a></p>\r\n";
	$divTies .= "</div>\r\n";

	return $divPeriod . $divTies;
}

function GetRewardJS($act,$arrR,$viewtype)
{
	$arrR['prekgtime']=DateDiff($arrR['kgtime'],date('Y-m-d H:i:s',time()),'s');
	$kjSec = $arrR['prekgtime'] + $arrR['game_kj_delay'];
	$StopSec = $arrR['prekgtime'] - $arrR['game_tz_close'];
	$No = $arrR['preno'];
	$jsFun = "";
	$RefreshSecond = -3;
	$ReSecond = 3;
	if(in_array($act,[0,1,2,15,22,23,24]))//急速
	{
		$RefreshSecond = -3;
		$ReSecond = 3;
	}
	if($viewtype == "game") //在开奖列表里时
	{
		$jsFun = "
		function refreshContent()
		{
			if(kjSec <= 0)
			{
				if(kjSec == {$RefreshSecond})
				{
					$('#liTimer').html('Loading......');
					getContent('sgame.php?act={$act}&t=' + Math.random());
				}
				else if(kjSec < {$RefreshSecond} && Math.abs(kjSec) % {$ReSecond} == 0){
					$('#liTimer').html('Loading......');
					getContent('sgame.php?act={$act}&t=' + Math.random());
					//get_dou();
					//window.parent.location.reload()
				} else {
					$('#liTimer').html('第<i>' + curNo + '</i>期 正在开奖，请稍后!');
					
				}
				kjSec--;
			}
			else
			{
				if(stopSec == 0){
					$('#scur_{$No}').html('开奖中...');
					$('#scur_{$No}').attr('class','btn btn-success btn-block');
				}
				if(stopSec > 0){
				 	$('#liTimer').html('第<i>' +curNo+'</i>期 还有<em>' + stopSec + '</em>秒停止下注!');
				 	//alert(curNo);
				} else {
				 	$('#liTimer').html('第<i>'+curNo+'</i>期 停止下注，还有<em> ' + kjSec + ' </em>秒开奖!');
				}
				kjSec--;
				stopSec--;
			}
		}
		";
	}
	else
	{
		$jsFun = "
		function refreshContent()
			{
				if(kjSec <= 0)
				{
					if(kjSec <= -10){
						$('#liTimer').html('第<i>' + curNo + '</i>期 已开奖，请刷新!');
					} else {
					 	$('#liTimer').html('第<i>' + curNo + '</i>期 正在开奖，请稍后!');
					}
					kjSec--;
				}
				else
				{
				 	if(stopSec > 0){
				 	 	$('#liTimer').html('第<i>' +curNo+'</i>期 还有<em>' + stopSec + '</em>秒停止下注!');
				 	} else {
				 	 	$('#liTimer').html('第<i>'+curNo+'</i>期 停止下注，还有<em> ' + kjSec + ' </em>秒开奖!');
				 	}
				 	kjSec--;
				 	stopSec--;
				}
			}
		";
	}
	$js = "<script type=\"text/javascript\">";
	/*if(in_array($act,[0,1,2,15])){//0,1,2,15,22,23,24
		$t=50;
	}elseif (in_array($act,[3,4,5,11,12])){
		$t=210;
	}elseif (in_array($act,[8,9,10,13])){
		$t=140;
	}elseif(in_array($act,[6,7,14,16,17])){
		$t=210;
	}elseif (in_array($act,[18,19,20,21])){
		$t=42;
	}
	if($StopSec>$t){
		$js.="console.log($StopSec); is_open=1;";
	}else{
		$js.=" console.info($StopSec)";
	}*/
	if($kjSec<0)$kjSec=1;
	$js .= "
		var curNo = '{$No}';
		var stopSec = '{$StopSec}';
		var kjSec = '{$kjSec}';
		clearInterval(timerid);
		timerid = setInterval('refreshContent()',1000);
		$('#dou').html('". Trans($_SESSION['points']) ."');
		$('#iBankPoints').html('". Trans($_SESSION['bankpoints']) ."');
		$('#leftpoints').html('". Trans($_SESSION['points']) ."');
		$('#leftbankpoints').html('". Trans($_SESSION['bankpoints']) ."');
		";
	$js .= $jsFun;
	$js .= "refreshContent();\r\n";
	$js .= "refreshContent();\r\n";
	$js .= "</script>\r\n";
	return $js;
}

function ChangeEncodeU2G($s)
{
	return $s;
	return iconv("UTF-8", "GB2312//IGNORE", $s);
}
if(!function_exists('result')){
	function result($code,$msg){
		echo json_encode(array('status'=>$code,'message'=>$msg));
	}
}
function get_account($id=0){
	global $db;
	$sql="select * from withdrawals where uid=".($id?$id:$_SESSION['usersid']);
	$result =  $db->query($sql);
	$list=array();
	while($rs=$db->fetch_array($result))
	{
		$list[]=$rs;
	}
	return $list;
}

function get_info($id=0){
	global $db;
	$sql='select * from users where id='.($id?$id:$_SESSION['usersid']);
	$info=$db->fetch_first($sql);
	return $info;
}
function has_account($id=0){
	//判断是否已有收款账号
	global $db;
	$sql='select count(id) as total from withdrawals where uid='.($id?$id:$_SESSION['usersid']);
	$res=$db->fetch_first($sql);
	return $res['total'];
}
function ChangeEncodeG2U($s)
{
	return $s;
	return iconv("GB2312", "UTF-8", $s);
}

function ArrayChangeEncode(& $arrfrom)
{
	if(is_array($arrfrom)){
		foreach($arrfrom as $k => & $arr)
		{
			if(is_array($arr)){
				foreach($arr as $t => & $v)
				{
					$v = ChangeEncodeG2U($v);
				}
			}
		}
	}
}

function Trans($num)
{
	return number_format($num);
}

function curl_post($url, $data, $header, $post = 1) {
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


function SendSMS($to, $datas=array(), $tempId = 135113) {
	$accountSid = '8a216da858867fd701588a0b4529016b';
	$accountToken = '45e837bc23d6420897e7e8a40fd23e56';
	$appId = '8a216da858867fd701588a0b47330172';
	$serverIP = 'app.cloopen.com'; //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com 生产环境（用户应用上线使用）：app.cloopen.com
	$serverPort = '8883';
	$softVersion = '2013-12-26';
	$Batch = date("YmdHis");
	$BodyType = 'json';

	$data = "";
	for ($i = 0; $i < count($datas); $i++) {
		$data = $data . "'" . $datas[$i] . "',";
	}
	$body = "{'to':'$to','templateId':'$tempId','appId':'$appId','datas':[" . $data . "]}";
	$sig = strtoupper(md5($accountSid . $accountToken . $Batch));
	$url = "https://" . $serverIP . ":" . $serverPort . "/" . $softVersion . "/Accounts/" . $accountSid . "/SMS/TemplateSMS?sig=" . $sig;
	$authen = base64_encode($accountSid . ":" . $Batch);
	$header = array("Accept:application/$BodyType", "Content-Type:application/$BodyType;charset=utf-8", "Authorization:$authen");
	$result = curl_post($url, $body, $header);
	if ($BodyType == "json") {//JSON格式
		$datas = json_decode($result);
	} else { //xml格式
		$datas = simplexml_load_string(trim($result, " \t\n\r"));
	}

	if ($datas->statusCode == '000000') {
		return 'ok';
	}
	return $datas->statusCode;
}

function PostSMS($mobile,$content)
{
	$url="http://utf8.sms.webchinese.cn/?Uid=23rwer&Key=uetrwewewerw453453trrtewt&smsMob={$mobile}&smsText={$content}";
	$str=file_get_contents($url);
	$num=intval($str);
	if($num>0){
		return 'ok';
	}elseif($num==-4||$num==-41){
		return '手机号格式不正确';
	}elseif($num==-42){
		return '短信内容为空';
	}else{
		return "错误$num";
	}
		/*
		-1  没有该用户账户
		-2  接口密钥不正确 [查看密钥]
		 不是账户登录密码
		-21 MD5接口密钥加密不正确
		-3  短信数量不足
		-11 该用户被禁用
		-14 短信内容出现非法字符
		-4  手机号格式不正确
		-41 手机号码为空
		-42 短信内容为空
		-51 短信签名格式不正确
		接口签名格式为：【签名内容】
		-6  IP限制
		大于0 短信发送数量
		*/
	
	
	
	
	
	$target = "http://sms.106jiekou.com/utf8/sms.aspx";
	//替换成自己的测试账号,参数顺序和wenservice对应
	$post_data = "account=robinson&password=qq52013145678&mobile={$mobile}&content=".rawurlencode($content);

	$smsResult = Post($post_data, $target);
	//$xml = simplexml_load_string($smsResult);
	//$result = (string) $xml->result;
	$ret = "ok";
	switch($smsResult)
	{
		case "100":
			$ret = "ok";
			break;
		case "101":
			$ret = "验证失败";
			break;
		case "102":
			$ret = "手机号码格式不正确";
			break;
		case "103":
			$ret = "会员级别不够";
			break;
		case "104":
			$ret = "内容未审核";
			break;
		case "105":
			$ret = "内容过多";
			break;
		case "106":
			$ret = "账户余额不足";
			break;
		case "107":
			$ret = "Ip受限";
			break;
		case "108":
			$ret = "手机号码发送太频繁，请换号或隔天再发";
			break;
		case "109":
			$ret = "帐号被锁定";
			break;
		case "110":
			$ret = "发送通道不正确";
			break;
		case "111":
			$ret = "当前时间段禁止短信发送";
			break;
		case "120":
			$ret = "系统升级";
			break;
		default:
			$ret = "未知错误:" . $smsResult;
			break;
	}
	return $ret;
}

function Post($curlPost,$url){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_NOBODY, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
	$return_str = curl_exec($curl);
	curl_close($curl);
	return $return_str;
}


function WriteLog($Msg,$flag=FILE_APPEND)
{
	$LogFile = dirname(dirname(__FILE__)) . '/log/logtext.txt';
	$Msg = date("Y-m-d H:i:m ") . $Msg ."\r\n";
	file_put_contents($LogFile, $Msg,$flag);
}
function LogFormat($Msg)
{
	return date("Y-m-d H:i:m ") . $Msg ."\r\n";
}

function business_check(){
	global $db,$web_dbtop,$web_dir;
	$query=$db->query("Select id From {$web_dbtop}business where uid=" .intval($_COOKIE["usersid"]));
	if(!$rs=$db->fetch_array($query)){
		echo "<script>alert('对不起，您没有该权限！');window.location='".$web_dir."index.php';</script>";
		exit;
	}
}

function usersip() {
	if (getenv('HTTP_CLIENT_IP')) {
		$ip = getenv('HTTP_CLIENT_IP');
	}elseif (getenv('HTTP_X_FORWARDED_FOR')) {
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	}elseif (getenv('HTTP_X_FORWARDED')) {
		$ip = getenv('HTTP_X_FORWARDED');
	}elseif (getenv('HTTP_FORWARDED_FOR')) {
		$ip = getenv('HTTP_FORWARDED_FOR');
	}elseif (getenv('HTTP_FORWARDED')) {
		$ip = getenv('HTTP_FORWARDED');
	}else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	if(strpos($ip,",")){
        $ip_data=explode(",",$ip);
        $ip = $ip_data[1];
    }
    
    if(strlen($ip) > 15)
    {
        $ip = "";
    }
    
    return str_check($ip);
}

function cnsubstr($str_cut,$length)
{
	if (strlen($str_cut) > $length)
	{
		for($i=0; $i < $length; $i++)
			if (ord($str_cut[$i]) > 128)    $i++;
		$str_cut = substr($str_cut,0,$i);
	}
	return $str_cut;
}


function random($length) {
	$hash = '';
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';

	$max = strlen($chars) - 1;

	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;

}

function showid($tables,$tablesname,$id){
	global $db,$web_dbtop;
	$query=$db->query("Select $tablesname from $web_dbtop$tables where email='".$id."'");
	if($rs=$db->fetch_array($query)){
		return $rs[$tablesname];
	}
}

function msg_num($type){
	global $db,$web_dbtop;
	$sql="select count(id) from {$web_dbtop}msg";
	if($type==1){
		$sql.=" where usersid=".intval($_COOKIE["usersid"])." and del=0 and look=0";
	}else{
		$sql.=" where mid=".intval($_COOKIE["usersid"])." and del=0 and look=0";
	}
	return $db->result_first($sql);
}

function ck_secans($secans){
	global $db,$web_dbtop;
	$query=$db->query("Select secans From {$web_dbtop}users where id=" .intval($_COOKIE["usersid"]). " And password='" .str_check($_COOKIE["password"]). "'");
	if($rs=$db->fetch_array($query)){
		if($rs["secans"]!=$secans){
			echo "<script language=javascript>alert('对不起，您输入的安全答案错误，请核对和后再试！');history.go(-1);</script>";
			exit;
		}
	}
}

function backlog($mun,$type){
	global $db,$web_dbtop;
	if($type==1){
		$db->query("INSERT INTO {$web_dbtop}backlog (time,log,points,back,usersid) VALUES ('".date("Y-m-d H:i:s")."','存',".-$mun.",".$mun.",".intval($_COOKIE["usersid"]).")");
	}else{
		$db->query("INSERT INTO {$web_dbtop}backlog (time,log,points,back,usersid) VALUES ('".date("Y-m-d H:i:s")."','取',".$mun.",".-$mun.",".intval($_COOKIE["usersid"]).")");
	}
}

function userslog($logtype,$log,$points,$experience,$usersid=''){
	global $db,$web_dbtop;
	if(!$usersid)
		$usersid=intval($_COOKIE["usersid"]);
	$db->query("INSERT INTO {$web_dbtop}userslog (time,logtype,log,points,experience,usersid) VALUES ('".date("Y-m-d H:i:s")."',".intval($logtype).",'".str_check($log)."',".intval($points).",".intval($experience).",".$usersid.")");
}

function showstars($num) {
	global $web_dir;
	$starthreshold=3;
	$num=$num-1;
	if($num<0){
		$num=0;
	}
	$alt = 'title="等级: '.$num.'级"';
	$ret = "";
	$ret .= '<a class="lvspan'.$num.'"  '.$alt.'>&nbsp;</a>';

	/*
	if(empty($starthreshold)) {
		for($i = 0; $i < $num; $i++) {
			$ret .= '<img src="'.$web_dir.'img/score/1.gif" '.$alt.' />';
		}
	} else {
		for($i = 6; $i > 0; $i--) {
			$numlevel = intval($num / pow($starthreshold, ($i - 1)));
			$num = ($num % pow($starthreshold, ($i - 1)));
			for($j = 0; $j < $numlevel; $j++) {
                if($j==$num){
				    $ret .= '<img src="'.$web_dir.'img/score/'.$i.'.gif" '.$alt.' />';
                }
			}
		}
	}
	*/
	return $ret;
}

function userslive($experience){
	global $db,$web_dbtop;
	$query=$db->query("Select stars from {$web_dbtop}usergroups where $experience BETWEEN creditslower AND creditshigher Order by id desc");
	if($rs=$db->fetch_array($query)){
		return $rs["stars"];
	}
}

function showselect($id){
	global $db,$web_dbtop;
	echo "<OPTION value=\"\" ".(!$id?"selected":"").">全部礼品</OPTION>";
	$query=$db->query("Select * from {$web_dbtop}ctype where typeid=0 Order by sort asc,id desc");
	while($rs=$db->fetch_array($query)){
		echo "<OPTION value=".$rs["id"]." ".($id==$rs["id"]?"selected":"").">".$rs["name"]."</OPTION>";
		$query_f=$db->query("Select * from {$web_dbtop}ctype where typeid=".$rs["id"]." Order by sort asc,id desc");
		while($rs_f=$db->fetch_array($query_f)){
			echo "<OPTION value=".$rs_f["id"]." ".($id==$rs_f["id"]?"selected":"").">├--".$rs_f["name"]."</OPTION>";
		}
	}
}

function showcontent($tables,$tablesname,$id){
	global $db,$web_dbtop;
	$query=$db->query("Select $tablesname from $web_dbtop$tables where id=$id");
	if($rs=$db->fetch_array($query)){
		return $rs[$tablesname];
	}
}

function typesid($id){
	global $db,$web_dbtop;
	$query=$db->query("Select * from {$web_dbtop}ctype where typeid=$id Order by sort asc,id desc");
	while($rs=$db->fetch_array($query)){
		$content.=$rs["id"].",";
	}
	return rtrim($content,",");
}

function delkey($content){
	global $web_commentskey;
	$commentskey=explode("|",$web_commentskey);
	for($i=0; $i<count($commentskey); $i++){
		$content=str_replace($commentskey[$i],"*",$content);
	}
	return htmlspecialchars($content);
}

function checkEmail($inAddress){
	return (ereg("^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+",$inAddress));
}

function cardtypeselect(){
	global $db,$web_dbtop;
	echo"<select name=\"cardtype\">";
	echo"<option value=\"\">请选择充值卡类型</option>";
	$query=$db->query("Select * FROM {$web_dbtop}cardtype Order by id desc");
	while($rs=$db->fetch_array($query)){
		echo"<option value=".$rs["id"].">".$rs["cardname"]."</option>";
	}
	echo "</select>";
}

function showbusinessid($uid){
	global $db,$web_dbtop;
	$query=$db->query("Select id from {$web_dbtop}business where uid=$uid");
	if($rs=$db->fetch_array($query)){
		return $rs["id"];
	}
}

function showcardtype($id){
	global $db,$web_dbtop;
	$query=$db->query("Select cardname from {$web_dbtop}cardtype where id=$id");
	if($rs=$db->fetch_array($query)){
		return $rs["cardname"];
	}
}

function createrandstring($length,$type) {
	$hash = '';
	$chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	switch($type){
		case 0:
			$max=9;
			break;
		case 1:
			$max=35;
			break;
		case 2:
			$max = strlen($chars) - 1;
			break;
		default:
			$max=9;
			break;
	}
	for($i = 0; $i < $length; $i++){
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
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

function zint($val){
	if($val>0){
		return $val;
	}else{
		return 0;
	}
}

function game11pl($num){
	$game11_array = array(36,18,12,9,7.2,6,7.2,9,12,18,36);
	return $game11_array[$num-2];
	unset($game11_array);
}

function game16pl($num){
	$game16_array = array(216,72,36,21.6,14.4,10.29,8.64,8,8,8.64,10.29,14.4,21.6,36,72,216);
	return $game16_array[$num-3];
	unset($game16_array);
}

function game28pl($num){
	$game28_array = array(1000,333.33,166.67,100,66.66,47.61,35.71,27.77,22.22,18.18,15.87,14.49,13.69,13.33,13.33,13.69,14.49,15.87,18.18,22.22,27.77,35.71,47.61,66.66,100,166.66,333.33,1000);
	return $game28_array[$num];
	unset($game28_array);
}

function fsockurl($httpurl){
	$url=explode("/",$httpurl);
	$urls=$url[2];
	if(stristr($urls,":")){
		$w_url=explode(":",$urls);
		$urls=$w_url[0];
		$port=$w_url[1];
	}else{
		$port=80;
	}
	for($i=3;$i<count($url);$i++){
		$pstr .= "/".$url[$i];
	}
	$fp = @fsockopen($urls,$port);
	if($fp){
		$out = "GET $pstr HTTP/1.1\r\n";
		$out .= "Host: $urls\r\n";
		$out .= "Connection: Close\r\n\r\n";
		fwrite($fp, $out);
		while (!feof($fp)){
			$httpcontent.=fgets($fp, 1024);
		}
		fclose($fp);
	}
	$httpcontent=explode("\r\n\r\n",$httpcontent,2);
	return $httpcontent[1];
}

function GetBodyc($string,$start,$end){
	$start=stripcslashes($start);
	$end=stripcslashes($end);
	$message = @explode($start,$string);
	if(count($message) < 1) return "";
	$message = @explode($end,$message[1]);
	if(count($message)>1){
		return $message[0];
	} else{
		return "";
	}
}

function pro_rand($pro, &$res, $num=1){
	$pro_sum = array_sum($pro);
	for($i = 0; $i < $num; $i++){
		$rand_num = mt_rand(1, $pro_sum);
		reset($pro);
		foreach($pro as $key => $value){
			if($rand_num <= $value){
				break;
			}else{
				$rand_num -= $value;
			}
		}
		$res[$i] = $key;
	}
}

function dodgejg($pk,$ww){
	if($pk - $ww == -1 || $pk - $ww == 2){
		return 1;
	}elseif($pk - $ww == 1 || $pk - $ww == -2){
		return 2;
	}else{
		return 3;
	}
}

function today_posts_num($id){
	global $db,$web_dbtop;
	$sql="select count(id) from {$web_dbtop}bbs_posts where STR_TO_DATE(time,'%Y-%m-%d')='".date("Y-m-d")."'";
	$sql.=" and section=".intval($id);
	return $db->result_first($sql);
}

function posts_num($id){
	global $db,$web_dbtop;
	$sql="select count(id) from {$web_dbtop}bbs_posts where";
	$sql.=" section=".intval($id);
	return $db->result_first($sql);
}

function reply_num($id){
	global $db,$web_dbtop;
	$sql="select count({$web_dbtop}bbs_reply.id) from {$web_dbtop}bbs_reply,{$web_dbtop}bbs_posts where";
	$sql.=" {$web_dbtop}bbs_reply.pid={$web_dbtop}bbs_posts.id and {$web_dbtop}bbs_posts.section=".intval($id);
	return $db->result_first($sql);
}

function f_reply_num($id){
	global $db,$web_dbtop;
	$sql="select count(id) from {$web_dbtop}bbs_reply where";
	$sql.=" pid=".intval($id);
	return $db->result_first($sql);
}

function f_final_id($id){
	global $db,$web_dbtop;
	$query=$db->query("Select uid from {$web_dbtop}bbs_reply where pid=".intval($id)." Order by id desc");
	if($rs=$db->fetch_array($query)){
		return showcontent("users","name",$rs["uid"]);
	}
	return "&nbsp;";
}

function flashslide($Slidewidth,$Slideheight){
	global $db,$web_dbtop,$web_dir,$web_slidedir;
	$flashslide="<script type=text/javascript>\n";
	$flashslide.="var swf_width=".$Slidewidth.";\n";
	$flashslide.="var swf_height=".$Slideheight.";\n";
	$query=$db->query("select slidepic,slideurl from {$web_dbtop}slide Order by sort asc,id desc");
	while($rs=$db->fetch_array($query)){
		$i++;
		if(stristr($rs["slidepic"],"http://")){
			$images=$rs["slidepic"];
		}else{
			$images=$web_dir.$web_slidedir.$rs["slidepic"];
		}
		$pic.=$images."|";
		$links.=$rs["slideurl"]."|";
	}
	$flashslide.="var files='".rtrim($pic,"|")."';\n";
	$flashslide.="var links='".rtrim($links,"|")."';\n";
	$flashslide.="var texts='';\n";
	$flashslide.="document.write('<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"'+ swf_width +'\" height=\"'+ swf_height +'\">');\n";
	$flashslide.="document.write('<param name=\"movie\" value=\"".$web_dir."inc/flash.swf\"><param name=\"quality\" value=\"high\">');\n";
	$flashslide.="document.write('<param name=\"menu\" value=\"false\"><param name=\"wmode\" value=\"opaque\">');\n";
	$flashslide.="document.write('<param name=\"FlashVars\" value=\"bcastr_file='+files+'&bcastr_link='+links+'&bcastr_title='+texts+'\">');\n";
	$flashslide.="document.write('<embed src=\"".$web_dir."inc/flash.swf\" wmode=\"opaque\" FlashVars=\"bcastr_file='+files+'&bcastr_link='+links+'&bcastr_title='+texts+'& menu=\"false\" quality=\"high\" width=\"'+ swf_width +'\" height=\"'+ swf_height +'\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />'); document.write('</object>');\n";
	$flashslide.="</script>";
	return $flashslide;
}

/* 发送邮箱
*
*/
function SendMailToUser($email,$mailTitle,$mailContent)
{
	global $db;
	$smtpserver = "";
	$smtpserverport = "";
	$smtpusername = "";
	$smtpusermail = "";
	$smtpuser = "";
	$smtppass = "";
	$mailtype = "";

	$sql = "select smtp_server,smtp_serverport,smtp_username,smtp_usermail,smtp_user,smtp_pass,smtp_mailtype
				from web_config where id = 1";
	$result =  $db->query($sql);
	if($row = $db->fetch_array($result))
	{
		$smtpserver = $row["smtp_server"];
		$smtpserverport = $row["smtp_serverport"];
		$smtpusername = $row["smtp_username"];
		$smtpusermail = $row["smtp_usermail"];
		$smtpuser = $row["smtp_user"];
		$smtppass = $row["smtp_pass"];
		$mailtype = $row["smtp_mailtype"];
	}

	require_once("inc/smtp.php");
	$sM = ChangeEncodeU2G($email);
	$sC = ChangeEncodeU2G($mailContent);
	$sql = "insert into validcodelog(userid,code_type,account,content,add_time,state)
				values({$_SESSION['usersid']},2,'{$sM}','{$sC}',now(),0)";
	$result =  $db->query($sql);
	$insertID = $db->insert_id();
	$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
	//$smtp->debug = TRUE;//是否显示发送的调试信息
	$smtpusername = ChangeEncodeG2U($smtpusername);
	if($smtp->sendmail($email, $smtpusername, $smtpusermail, $mailTitle, $mailContent, $mailtype))
	{
		return true;
	}
	else
	{
		$sR = ChangeEncodeU2G("邮件未能正常发出");
		$sql = "update validcodelog set state = 1,err_msg='{$sR}' where id = {$insertID}";
		$result =  $db->query($sql);
		return false;
	}
}


/*取得sql条件范围
*  $fieldName  列名
*  $from       开始
*  $to         结束
*  $isNum	   是否为数字
*/
function GetSqlBetween($fieldName,$from,$to,$isNum)
{
	$str = "";
	$retstr = "";
	if(!$isNum)
		$str = "'";
	if($from != "" && $to == "")
	{
		$retstr .= " and ({$fieldName} < {$str}{$from}{$str})" ;
	}
	elseif($from == "" && $to != "")
	{
		$retstr .= " and ({$fieldName} > {$str}{$to}{$str})";
	}
	elseif($from != "" && $to != "")
	{
		$retstr .= " and ({$fieldName} between  {$str}{$from}{$str} and {$str}{$to}{$str})";
	}

	return $retstr;
}
function admin_log($opr,$amount,$points,$bank,$remark,$uid=0){
	/*
uidbigint(20) NOT NULL用户id
opr_typeint(11) NOT NULL类型，0：存，1：取，2：充值体验卡，3：转账入，4：转账出,5:在线充值,6:领取救济,7:兑奖点卡,8:推荐收益,55:系统会员充值,12:退回提现,10:提现通过,11:提现申请
amountbigint(20) NOT NULL数量
log_timedatetime NOT NULL时间
ipvarchar(15) NOT NULLip
pointsbigint(20) NOT NULL操作后豆
bankpointsbigint(20) NOT NULL操作后银行豆
remarkvarchar(254) NOT NULL备注
	 */
	global $db;
	$uid=$uid?$uid:$_SESSION['users'];
	$ip=usersip();
	$sql="INSERT INTO score_log(uid,opr_type,amount,log_time,ip,points,bankpoints,remark) values ('$uid','$opr','$amount',now(),'$ip','$points','$bank','$remark')";
	$db->query($sql);
}
function withdrawals_log($opr,$points,$bank,$remark){
	admin_log($opr,30, $points, $bank, $remark,$_SESSION['usersid']);

}
function get_info_point($uid=0){
	global $db;
	$sql='select points,back from users where id='.($uid?$uid:$_SESSION['usersid']);
	return $db->fetch_first($sql);
}
function ip2address($ip){
	$address=GetIpLookup($ip);
	return $address['province'].'&nbsp;'.$address['city'];
	$url='http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;
	$html=json_decode(file_get_contents($url));
	var_dump($html);
}
function GetIpLookup($ip = ''){
	if(empty($ip)){
		return array('province','city');
	}
	$res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
	if(empty($res)){ return false; }
	$jsonMatches = array();
	preg_match('#\{.+?\}#', $res, $jsonMatches);
	if(!isset($jsonMatches[0])){ return false; }
	$json = json_decode($jsonMatches[0], true);
	if(isset($json['ret']) && $json['ret'] == 1){
		$json['ip'] = $ip;
		unset($json['ret']);
	}else{
		return false;
	}
	return $json;
}

/* 取得排行榜
	*
	*/

function GetRankList()
{
	global $db;
	$sql='select r.uid,r.rank_num,r.rank_points as points,u.nickname from rank_list r left join users u on r.uid=u.id order by r.id asc limit 10;';
	$result=$db->fetch_all($sql,-1);
	return $result;
}


function GetGameNames(){
	global $db;
	$ret = [];
	$sql="select game_type,game_name from game_config";
	$result=$db->fetch_all($sql,-1);
	foreach($result as $row){
		$game_type = $row['game_type'];
		$ret[$game_type] = $row['game_name'];
	}
	return $ret;
}


function cz_type($id){
	$arr=[1=>'支付宝',2=>'微信',3=>'银行卡',4=>'支付宝',5=>'微信',7=>'借贷宝'];
	return $arr[$id];
}
// 手机判断period
function is_mobile() {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$mobile_agents = array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi",
		"android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio",
		"au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu",
		"cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ",
		"fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","honor",
		"htc","huawei","hutchison","inno","ipad","ipaq","iphone","ipod","jbrowser","kddi",
		"kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo",
		"mercator","meridian","mi ","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-",
		"moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia",
		"nook","novarra","obigo","oppo","palm","panasonic","pantech","philips","phone","pg-",
		"playstation","pocket","pt-","qc-","qtek","redmi","rover","sagem","sama","samu","sanyo",
		"samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank",
		"sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit",
		"tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vivo",
		"vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce",
		"wireless","xda","xde","zte");
	$is_mobile = false;
	foreach ($mobile_agents as $device) {
		if (stristr($user_agent, $device)) {
			$is_mobile = true;
			break;
		}
	}
	return $is_mobile;
}

?>
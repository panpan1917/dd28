<?php
  	include_once("inc/conn.php");
    include_once("inc/function.php");
    
    $arrRet = array('cmd'=>'','msg'=>'');
    if(!isset($_SESSION['usersid'])) {
    	$arrRet['cmd'] = "notlogin";
    	$arrRet['msg'] = "您还没登录或者链接超时,请先登录!";
		echo json_encode($arrRet);
		exit;
	}
	
	
	switch($_POST['act'])
	{
		case "saveautomodel": //保存自动投注模式
			SaveAutoModel();
			break;
		case "removeautomodel": //取消自动投注模式
			RemoveAutoModel();
			break;
		case "changautomodel"://修改自动投注模式
			ChangeAutoModel();
			break;
		case "savemodel"://保存模式
			SaveModel();
			break;
		case "removemodel": //删除模式
			RemoveUserModel();
			break;
		case "getmodeloption": //取得投注模式列表option
			GetModelOptionStr();
			break;
		case "changmodelname": //修改模式名称
			ChangeModelName();
			break;
		case "getmodelpress"://根据id或者期号获得投注信息
			GetModelPressInfo();
			break;
		case "savepress": //游戏投注
			SaveGamePress();
			break;
		case "checkpress": //检测去投注
			CheckPress();
			break;
		case "getodds": //取得赔率
			getOdds();
			break;
		case "getlastpress"://取得上盘押注
			getLastPress();
			break;
		default:
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "无效的命令!";
			echo json_encode($arrRet);
			exit;
	}
	
	/* 保存自动投注模式
	*
	*/
	function SaveAutoModel()
	{
		global $db;
		$GameType = intval($_POST["gtype"]);
		$CurNo = intval($_POST['no']);
		$BeginNo = intval($_POST["bno"]);
		$tzCount = intval($_POST["cnt"]);
		$tzMaxG = intval($_POST["maxg"]);
		$tzMinG = intval($_POST["ming"]);
		$BeginRecID = intval($_POST["cid"]);
		
		$arrRet = array('cmd'=>'ok','msg'=>'');
		
		if($BeginNo < $CurNo)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "开始期号至少要大于当前的期号!";
			echo json_encode($arrRet);
			exit;
		}
		if($tzCount < 10)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "投注期数至少不少于10期!";
			echo json_encode($arrRet);
			exit;
		}
		if($tzMinG  < 100)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "乐豆下限至少不少于100";
			echo json_encode($arrRet);
			exit;
		}
		if($tzMinG >= $tzMaxG)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "乐豆下限必须要小于上限！";
			echo json_encode($arrRet);
			exit;
		}
		
		$tableautotz = GetGameTableName($GameType,"auto_tz");
		$sql = "select count(id) cnt from {$tableautotz} where id = {$BeginRecID} and uid = '{$_SESSION['usersid']}'";
		$result = $db->query($sql);
		$rs = $db->fetch_array($result);
		if($rs['cnt'] < 1)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "您所要选择开始的投注模式已不存在，请刷新页面!";
			echo json_encode($arrRet);
			exit;
		}
		if($tzMaxG < $_SESSION['points'])
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "您当前的乐豆已经大于自动投注设置的上限了，您可以加大上限或者把一部分乐豆转回银行!";
			echo json_encode($arrRet);
			exit;
		}
		
		$table_kg_users_tz = GetGameTableName($GameType,"kg_users_tz");
		$sql = "select max(NO) as maxno from {$table_kg_users_tz} where uid = '{$_SESSION['usersid']}'";
		$result = $db->query($sql);
		if($rs = $db->fetch_array($result))
		{
			$BeginNo = ($BeginNo > $rs['maxno']) ? $BeginNo : ($rs['maxno']+1);
		}
		$endNo = $BeginNo + $tzCount;
		$tableauto = GetGameTableName($GameType,"auto"); 
		$sql = "insert into {$tableauto}(startNO,endNO,minG,maxG,autoid,usertype,status,uid,start_auto_id)
				values({$BeginNo},{$endNo},{$tzMinG},{$tzMaxG},{$BeginRecID},0,1,{$_SESSION['usersid']},'{$BeginRecID}')";
		$result = $db->query($sql);
		if($db->affected_rows() > 0)
		{
			$arrRet['cmd'] = "ok";
			$arrRet['msg'] = "保存成功，系统将从第{$BeginNo}期开始为您自动投注(注意:您不在线也会自动投注)"; 
		}
		else
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "系统繁忙，保存失败!";
		}
		echo json_encode($arrRet);
		exit;
	}
	
	/* 取消自动投注
	*
	*/
	function RemoveAutoModel()
	{
		global $db;
		$GameType = intval($_POST["gtype"]);
		$tableauto = GetGameTableName($GameType,"auto");
		$arrRet = array('cmd'=>'ok','msg'=>'');
		
		$sql = "delete from {$tableauto} where uid = '{$_SESSION['usersid']}'";
		$result = $db->query($sql);
		if($db->affected_rows() > 0)
		{
			$arrRet['cmd'] = "ok";
			$arrRet['msg'] = "ok";
		}
		else
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "err";
		}
		echo json_encode($arrRet);
		exit;
	}
	
	/* 修改自动投注模式
	*
	*/
	function ChangeAutoModel()
	{
		global $db;
		$GameType = intval($_POST["gtype"]);
		$WinOrLossID = intval($_POST['v']);
		$RecID = intval($_POST['cid']);
		$WinOrLossType = str_check($_POST['ct']);
		$tableautotz = GetGameTableName($GameType,"auto_tz");
		$arrRet = array('cmd'=>'ok','msg'=>'');  
		
		$sql = "update {$tableautotz} set " . (($WinOrLossType == "win") ? "winid" : "lossid") . " = {$WinOrLossID} where id = {$RecID} and uid = '{$_SESSION['usersid']}'";
		//WriteLog($sql);
		$result = $db->query($sql);
		$arrRet['cmd'] = "ok";
		$arrRet['msg'] = "修改成功";
		echo json_encode($arrRet);
		exit;
	}
	
	/* 保存模式
	*
	*/
	function SaveModel()
	{     
		global $db;
		$GameType = intval($_POST["gtype"]);
		$ID = intval($_POST["thev"]);
		$newName = ChangeEncodeU2G(str_check($_POST["thename"]));
		$newName = substr($newName,0,20);
		$press = str_check($_POST["press"]);
		$totalScore = intval($_POST["total"]);
		$tableautotz = GetGameTableName($GameType,"auto_tz");
		$tableauto = GetGameTableName($GameType,"auto");
		$arrRet = array('cmd'=>'ok','msg'=>'');
		
		$sql = "select count(*) cnt from {$tableautotz} where uid = '{$_SESSION['usersid']}'";
		$result = $db->query($sql);
		$rs = $db->fetch_array($result);
		if($rs["cnt"] >= 5)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "单个游戏最多只能设置5个模式!";
			echo json_encode($arrRet);
			exit;
		}
		
		//判断投注串合法性
		if(!CheckPressStrValid($GameType,$press))
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "投注验证失败!";
			echo json_encode($arrRet);
			exit;
		}
		//检查是否正在自动投注
		if(CheckAutoPress($GameType))
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "您当前已设置了自动投注，不允许编辑模式，请先取消自动投注!";
			echo json_encode($arrRet);
			exit;	
		}
		//名称是否重名
		$sql = "select count(*) cnt from {$tableautotz} where id <> {$ID} and tzname = '{$newName}' and uid = '{$_SESSION['usersid']}'";
		$result = $db->query($sql);
		$rs = $db->fetch_array($result);
		if($rs["cnt"] > 0)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "模式名称重名了，请先修改!";
			echo json_encode($arrRet);
			exit;	
		}
    	$step = GetFromBeginNumStep($GameType);
    		
    	$arrPress = explode(",",$press);
    	$PressStr = "";
    	$sumScore = 0;
		for($i = 0 ; $i < count($arrPress); $i++)
		{
			if($arrPress[$i] != "" && intval($arrPress[$i]) > 0)
			{
				$PressStr .= ($i+$step) . "," . $arrPress[$i] . "|";
				$sumScore += intval($arrPress[$i]);
			}
		}
		if($PressStr == "")
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "您还没有选择投注!";
			echo json_encode($arrRet);
			exit;
		}
		$PressStr = substr($PressStr,0,-1);
		if($sumScore != $totalScore)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "您的投注信息验证错误!";
			echo json_encode($arrRet);
			exit;
		}
		$sql = "select game_press_min,game_press_max from game_config where game_type = '{$GameType}'";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
			$press_min = $rs['game_press_min'];
			$press_max = $rs['game_press_max'];
			if($sumScore < $press_min)
			{
				 $arrRet['cmd'] = "err";
				 $arrRet['msg'] = "投注额至少大于下限" . $press_min;
				 echo json_encode($arrRet);
				 exit;
			}
			if($sumScore > $press_max)
			{
				 $arrRet['cmd'] = "err";
				 $arrRet['msg'] = "投注额不能超过上限" . $press_max;
				 echo json_encode($arrRet);
				 exit;
			}
    	}
		if($ID == "0")
			$sql = "insert into {$tableautotz}(uid,tzname,tzpoints,tznum,tzid,winid,lossid)
						values({$_SESSION['usersid']},'{$newName}',{$totalScore},'{$PressStr}',0,0,0)";
		else
			$sql = "update {$tableautotz} set tzname = '{$newName}',tzpoints={$totalScore},tznum='{$PressStr}'
					where id = '{$ID}' and uid = '{$_SESSION['usersid']}'";
		$result = $db->query($sql);
		$autoID = 0;
		if($ID == "0")
		{
			$insertID = $db->insert_id();
			$autoID = $insertID;
			if($insertID > 0){
				$sql = "update {$tableautotz} set winid={$insertID},lossid = {$insertID} where id = {$insertID} and uid='{$_SESSION['usersid']}'";
				$result = $db->query($sql);
			}
		}
		
		if($ID != '0') $autoID = $ID;
		//更新模式
		$sql = "select EditPressModel({$GameType},{$autoID},{$_SESSION['usersid']},'{$PressStr}')"; 
		$result = $db->query($sql);
		
		$arrRet['cmd'] = "ok";
		$arrRet['msg'] = "";
		echo json_encode($arrRet);
		exit;
	}
	
	/*删除模式
	*
	*/
	function RemoveUserModel()
	{
		global $db;
		$GameType = intval($_POST["gtype"]);
		$ID = intval($_POST["id"]);
		$tableautotz = GetGameTableName($GameType,"auto_tz");
		$arrRet = array('cmd'=>'ok','msg'=>'');
		//检查是否正在自动投注
		if(CheckAutoPress($GameType))
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "您当前已设置了自动投注，不允许编辑模式，请先取消自动投注!";
			echo json_encode($arrRet);
			exit;	
		}
		$sql = "delete from {$tableautotz} where id = {$ID} and uid = '{$_SESSION['usersid']}'";
		$result = $db->query($sql);
		//删除模式
		$sql = "delete from gameall_auto_tz where autoid = {$ID} and uid = '{$_SESSION['usersid']}' and gametype='{$GameType}'";
		$result = $db->query($sql);
		
		//更新引用此模式的记录
		$sql = "update {$tableautotz} set winid=id where winid = {$ID} and uid = '{$_SESSION['usersid']}'";
		$result = $db->query($sql);
		
		//更新引用此模式的记录
		$sql = "update {$tableautotz} set lossid=id where lossid = {$ID} and uid = '{$_SESSION['usersid']}'";
		$result = $db->query($sql);
		
		$arrRet['cmd'] = "ok";
		$arrRet['msg'] = "删除成功!";
		echo json_encode($arrRet);
		exit;
	}
	
	/*取得投注模式option列表
	*
	*/
	function GetModelOptionStr()
	{
		global $db;
		$GameType = intval($_POST["gtype"]);
		$tableautotz = GetGameTableName($GameType,"auto_tz");
		$arrRet = array('cmd'=>'ok','msg'=>'');
		
		$sql = "SELECT id,tzname FROM {$tableautotz} WHERE uid = '{$_SESSION['usersid']}'";
		$option_text = "\t\t\t\t\t<option value='0' selected='selected'>--新建模式--</option>\r\n";
		$result = $db->query($sql);
		while($rs = $db->fetch_array($result))
		{
			$option_text .= "<option value='{$rs['id']}'>". ChangeEncodeG2U($rs['tzname']) ."</option>\r\n";
		}
		$arrRet['cmd'] = "ok";
		$arrRet['msg'] = $option_text;
		echo json_encode($arrRet);
		exit;
	}
	
	/*修改模式名称
	*
	*/
	function ChangeModelName()
	{
		global $db;
		$GameType = intval($_POST["gtype"]);
		$ID = intval($_POST["id"]);
		$newName = ChangeEncodeU2G(str_check($_POST["newname"]));
		$tableautotz = GetGameTableName($GameType,"auto_tz");
		$arrRet = array('cmd'=>'ok','msg'=>'');
		
		if(strlen($newName) == 0 || strlen($newName) > 30)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "新名称长度只允许30个字符以内!";
			echo json_encode($arrRet);
			exit;
		}
		$sql = "select count(*) as cnt from {$tableautotz} where tzname = '{$newName}' and uid = '{$_SESSION['usersid']}'";
		$result = $db->query($sql);
		$rs = $db->fetch_array($result);
		if($rs["cnt"] > 0)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "模式名称有重名，修改失败!";
			echo json_encode($arrRet);
			exit;
		}
		$sql = "update {$tableautotz} set tzname = '{$newName}' where id = '{$ID}' and uid = '{$_SESSION['usersid']}'";
		$result = $db->query($sql);
		$arrRet['cmd'] = "ok";
		$arrRet['msg'] = "修改成功!";
		echo json_encode($arrRet);
		exit;
	}
	
	/*根据ID或者期号获得投注信息
	*
	*/
	function GetModelPressInfo()
	{
		global $db;
		$gtype = intval($_POST['gtype']);
		$IDorNo = intval($_POST['id']);
		$IDType = $_POST['ftype'];//id or no
		$tableautotz = GetGameTableName($gtype,"auto_tz");
		$tableuserstz = GetGameTableName($gtype,'users_tz');
		$arrRet = array('cmd'=>'','msg'=>'');
		$isOK = true;
		
		$RewardNumCount = GetGameRewardNumCount($gtype);
		$step = GetFromBeginNumStep($gtype);
		
		$arrPress = array();
		for($i = 0; $i < $RewardNumCount; $i++)
		{
			$arrPress[] = 0;
		} 
		if($IDType == 'id')
		{
			$sql = "SELECT tznum FROM {$tableautotz} WHERE id = {$IDorNo} AND uid = '{$_SESSION['usersid']}'";
			$result = $db->query($sql);
			if($rs = $db->fetch_array($result))
			{
				$arrtznum = explode("|",$rs["tznum"]);
				foreach($arrtznum as $v)
				{
					
					$arrnum = explode(",",$v);
					$index = $arrnum[0] - $step;
					$arrPress[$index] = $arrnum[1];
				}
			}
			else
			{
				$isOK = false;
			}
		}
		else
		{
			$sql = "SELECT tznum,tzpoints FROM {$tableuserstz} WHERE id = {$IDorNo} AND uid = '{$_SESSION['usersid']}'";
			$result = $db->query($sql);
			if($rs = $db->fetch_array($result))
			{
				$arrtznum = explode("|",$rs["tznum"]);
				$arrtzpoints = explode("|",$rs["tzpoints"]);
				for($i = 0; $i < count($arrtznum); $i++)
				{
					$index = $arrtznum[$i]-$step;
					$arrPress[$index] = $arrtzpoints[$i];
				}
			}
			else
			{
				$isOK = false;
			}
		}
		if($isOK)
		{
			$arrRet['cmd'] = "ok";
			$arrRet['msg'] = implode(",",$arrPress);
		}
		else
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "无法取得投注信息!";
		}
		
		echo json_encode($arrRet);
		exit;	
	}
	/* 游戏投注
	*
	*/
	function SaveGamePress()
	{ 
		global $db;
		$GameType = intval($_POST["gtype"]);
		$No = intval($_POST["no"]);
		$Press = str_check($_POST["press"]);
		$TotalScore = intval($_POST["total"]);
		$arrRet = array('cmd'=>'ok','msg'=>'');
		$procedue = "";
		
		//判断投注串合法性
		if(!CheckPressStrValid($GameType,$Press))
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "投注验证失败!";
			echo json_encode($arrRet);
			exit;
		}
		//判断下注间隔时间
		if(isset($_SESSION["pressinterval"]))
		{
			if(isset($_SESSION["lastpresstime"]))
			{
			 	if( strtotime(date('Y-m-d H:i:s',time())) - $_SESSION["lastpresstime"] <= $_SESSION["pressinterval"] )
			 	{
					$arrRet['cmd'] = "err";
				    $arrRet['msg'] = "下注太频繁了!";
				    echo json_encode($arrRet);
					exit;		
			 	}
			}
		}
		else
		{
			$sql = "select fldValue from sys_config where fldVar in('game_press_interval')";
			$result = $db->query($sql);
			if($rs = $db->fetch_array($result))
			{
				$_SESSION["pressinterval"] = $rs["fldValue"];
			}	
		}
		
		//判断游戏是否允许下注
		$sql = "select fldVar,fldValue from sys_config where fldVar in('game_open_flag','game_shutdown_reason') order by fldIdx";
		$result = $db->query($sql);
		if($rs = $db->fetch_array($result))
		{
			if($rs["fldValue"] == "1")
			{
				$rs = $db->fetch_array($result);
				$arrRet['cmd'] = "err";
			    $arrRet['msg'] = "游戏已停止下注,原因:" . ChangeEncodeG2U($rs["fldValue"]);
			    echo json_encode($arrRet);
				exit;
			}
			   
		}
		
		//判断单个游戏是否允许下注
		$sql = "select isstop,stop_msg from game_config where game_type = '{$GameType}'";
		$result = $db->query($sql);
		if($rs = $db->fetch_array($result))
		{
			if($rs["isstop"] == 1)
			{
				$rs = $db->fetch_array($result);
				$arrRet['cmd'] = "showdown";
			    $arrRet['msg'] = "游戏已停止下注,原因:" . ChangeEncodeG2U($rs["stop_msg"]);
			    echo json_encode($arrRet);
				exit;
			}
		}
		
		//判断用户
		$sql = "select dj,isagent from users where id = '{$_SESSION['usersid']}'";
		$result = $db->query($sql);
		$rs = $db->fetch_array($result);
		if($rs["isagent"] == 1)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "为了安全，代理绑定帐号禁止玩游戏";
			echo json_encode($arrRet);
			exit;
		}
		if($rs["dj"] == 1)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "帐号已被冻结";
			echo json_encode($arrRet);
			exit;
		}
		
		if(CheckAutoPress($GameType))
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "您已经设置了自动投注！请先取消";
			echo json_encode($arrRet);
			exit;
		}
		$step = GetFromBeginNumStep($GameType);
		$procedue = "web_tz_" . GetGameTableName($GameType,"game");
		
		if($procedue == "web_tz_")
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "游戏类型错误!";
			echo json_encode($arrRet);
			exit;
		}
		$sumScore = 0;
		$arrPress = explode(",",$Press);
		
		$PressStr = "";
		for($i = 0 ; $i < count($arrPress); $i++)
		{
			if($arrPress[$i] != "" && intval($arrPress[$i]) > 0)
			{
				$PressStr .= ($i+$step) . "," . $arrPress[$i] . "|";
				$sumScore += intval($arrPress[$i]);
			}
		}
		if($PressStr == "")
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "您还没有投注!";
			echo json_encode($arrRet);
			exit;
		}
		if($sumScore != $TotalScore)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "您的投注信息验证错误!";
			echo json_encode($arrRet);
			exit;
		}
		$PressStr = substr($PressStr,0,-1);
		
		if(CheckGameTimeout($GameType,$No))
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "本期投注时间已过，请选其它期!";
			echo json_encode($arrRet);
			exit;
		}
		
		
		
		
		
		
		/*
		//禁止翻倍加码投注
		$secnum = 360;
		if(in_array($GameType,[3,4,5,11,12,25,26,32,33])) $secnum = 1800;//北京，蛋蛋
		if(in_array($GameType,[6,7,14,16,17,29])) $secnum = 1800;//PK
		if(in_array($GameType,[8,9,10,13,27,28,35])) $secnum = 1260;//加拿大
		if(in_array($GameType,[18,19,20,21,30,31,34])) $secnum = 540;//韩国
		if(in_array($GameType,[36])) $secnum = 3600;//农场
		
		$table_users_tz = GetGameTableName($GameType,"users_tz");
		$sql = "select * from {$table_users_tz} where uid={$_SESSION['usersid']} 
				AND UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(time) <= {$secnum}
				order by id desc limit 5";
		$result = $db->query($sql);
		$list = array();
		while($rs = $db->fetch_array($result)){
			$list[] = $rs;
		}
		
		$countNum = 0;
		$lastNO = 0;
		$lasttznum = "";
		$lastpoints = 0;
		$break = false;
		
		if(count($list) > 0){
			foreach ($list as $key => $row) {
				$qihao[$key] = $row['NO'];
			}
			array_multisort($qihao, SORT_ASC, $list);
			
			$tmptznum = array();
			foreach ($list as $key => $row) {
				if(count($tmptznum) > 0){
					$_exist = false;
					foreach($tmptznum as $_tmptznum){
						if(stristr($row['tznum'] , $_tmptznum) !== FALSE){
							$_exist = true;
						}
					}
				}
				
				if($countNum > 0 && abs($row['NO'] - $lastNO) == 1 && $_exist && $row['points'] <= $lastpoints){
					break;
				}
				
				$lastNO = $row['NO'];
				$lasttznum = $row['tznum'];
				$lastpoints = $row['points'];
				
				$tmptznum[] = $row['tznum'];
					
				$countNum++;
			}
		}
		
		if($countNum >= 5){
			$break = true;
		}
		
		if($break){
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "系统检测到非法投注，请重新投注!";//重复加码投注次数太多
			echo json_encode($arrRet);
			exit;
		}
		//禁止翻倍加码投注
		*/
		
		
		
		
		
		
		
		//$sql = "insert into presslog(uid,no,gametype,pressStr,totalscore) values({$_SESSION['usersid']},{$No},{$GameType},'{$PressStr}',{$TotalScore})";
		//$db->query($sql);
		
		//保存
		$sql = "call {$procedue}({$_SESSION['usersid']},{$No},{$sumScore},0,'{$PressStr}')";
		$arr = $db->Mysqli_Multi_Query($sql);
    	switch($arr[0][0]["result"])
    	{
			case '0': //成功
				$_SESSION["points"] = $arr[0][0]["points"];
				$_SESSION["bankpoints"] = $arr[0][0]["back"];
				$_SESSION["lastpresstime"] = strtotime(date('Y-m-d H:i:s',time()));	
				$arrRet['cmd'] = "ok";
				$arrRet['msg'] = Trans($arr[0][0]["points"]) . "|" . Trans($arr[0][0]["back"]);
				break;
			case '1': //投注额过小
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = "您的投注额小于最小限制" . $web_presspoint_game28_min;
				break;
			case '2': //余额不足
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = "您的余额不足!";
				break;
			case '3': //核对投注额失败
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = "您的实际投注额核对失败!";
				break;
			case '4': //已开奖过了
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = "本期已开奖过了!";
				break;
			case '5': //投注额低于最小限制
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = $arr[0][0]["msg"];
				break;
			case '6': //投注额大于限制
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = $arr[0][0]["msg"];
				break;
			case '99': //数据库错误
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = "系统错误，投注失败，请稍后再试!";
				break;
			default:
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = "未知错误!";
				break;
    	}
    	ArrayChangeEncode($arrRet);
		echo json_encode($arrRet);
		exit;
	}
	
	/*
	*
	*/
	function CheckPressStrValid($gametype,&$press)
	{
		global $db;
		$ret = false;
		$sql = "select reward_num from game_config where game_type = '{$gametype}'";
		$result = $db->query($sql);
		if($rs = $db->fetch_array($result))
		{
			 $arrPress = explode(",",$press);
			 $pressCount = count($arrPress) - 1;
			 if($pressCount == $rs['reward_num'] && $pressCount > 0){
			 	for($i=0;$i<$pressCount;$i++){
			 		/* if(!is_numeric($arrPress[$i])){
			 			return $ret;
			 		} */
			 		$arrPress[$i] = (int)$arrPress[$i];
			 	}
			 	
			 	$press = implode(",", $arrPress) . ",";
			 	$ret = true;
			 }
		}
		return $ret;
	}
	/* 检测是否设置了自动投注
	*
	*/
	function CheckAutoPress($t)
	{
		global $db;
		$tableName = GetGameTableName($t,"auto");
		$sql = "select 1 from " . $tableName . " where uid = '{$_SESSION['usersid']}'";
		$result = $db->query($sql);
		if($rs = $db->fetch_array($result))
			return true;
		else
			return false;
	}
	
	/* 检测本期投注时间是否已过
	*
	*/
	function CheckGameTimeout($t,$no)
	{
		global $db,$web_kj_delaysecond,$web_tz_delaysecond;
		$tableName = GetGameTableName($t,"game");
		$retState = true;
		$sql = "select game_kj_delay,game_tz_close from game_config where game_type = '{$t}'";
		$result = $db->query($sql);
		if($rs = $db->fetch_array($result))
		{
			$game_kj_delay = $rs["game_kj_delay"];
			$game_tz_close = $rs["game_tz_close"];
		} 
		$no = (int)$no;
		$sql = "select kj,kgtime,now() as servertime from " . $tableName . " where id = '{$no}' and kj=0";
		$result = $db->query($sql);
		if($rs = $db->fetch_array($result))
		{
			/* $timediff = DateDiff($rs["kgtime"],$rs["servertime"],"s");
			if($timediff <= 0) return true;
			
			if($rs["kj"] == 0 && $timediff - $game_tz_close > 0)
				$retState = false; */
			
			if(strtotime($rs["servertime"]) > (strtotime($rs["kgtime"])-$game_tz_close)){
				return true;
			}else{
				return false;
			}
		}
		return $retState;
	}
	
	
	/* 检测是否可以去投注
	*
	*/
	function CheckPress()
	{
		global $db;
		$GameType = intval($_POST["gtype"]);
		$No = intval($_POST["no"]);
		$tableName = GetGameTableName($GameType,"auto");
		$arrRet = array('cmd'=>'ok','msg'=>'');
		if($tableName == "")
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "游戏类型错误!";
			echo json_encode($arrRet);
			exit;
		}
		//判断总游戏是否允许下注
		$sql = "select fldVar,fldValue from sys_config where fldVar in('game_open_flag','game_shutdown_reason') order by fldIdx";
		$result = $db->query($sql);
		if($rs = $db->fetch_array($result))
		{
			if($rs["fldValue"] == "1")
			{
				$rs = $db->fetch_array($result);
				$arrRet['cmd'] = "showdown";
			    $arrRet['msg'] = "游戏已停止下注,原因:" . ChangeEncodeG2U($rs["fldValue"]);
			    echo json_encode($arrRet);
				exit;
			}
			   
		}
		
		//判断单个游戏是否允许下注
		$sql = "select isstop,stop_msg from game_config where game_type = '{$GameType}'";
		$result = $db->query($sql);
		if($rs = $db->fetch_array($result))
		{
			if($rs["isstop"] == 1)
			{ 
				$arrRet['cmd'] = "showdown";
			    $arrRet['msg'] = "游戏已停止下注,原因:" . ChangeEncodeG2U($rs["stop_msg"]);
			    echo json_encode($arrRet);
				exit;
			}
		}
		$sql = "select 1 from " . $tableName . " where uid = '{$_SESSION['usersid']}'";
		$result = $db->query($sql);
		if($rs = $db->fetch_array($result))
		{
			   $arrRet['cmd'] = "auto";
			   $arrRet['msg'] = "您已设置了自动投注，请先停止!";
		}
		//WriteLog(json_encode($arrRet));
		echo json_encode($arrRet);
		exit;
	}
	
	/* 取得赔率
	*
	*/
	function getOdds()
	{
		global $db;
		$GameType = intval($_POST["gtype"]);
		$No = intval($_POST["no"]);
		$TableName = GetGameTableName($GameType,"game");
		$arrRet = array('cmd'=>'','msg'=>'');
		
		if($TableName == "")
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "游戏类型错误!";
			echo json_encode($arrRet);
			exit;
		}
		$sql = "select ifnull(zjpl,'') as zjpl from {$TableName} where id = '{$No}'";
		$result = $db->query($sql);
		if($rs = $db->fetch_array($result))
		{
			$arrRet['cmd'] = "ok";
			$arrRet['msg'] = $rs['zjpl'];
		}
		else
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "无法取到上期赔率数据,请稍后再试!";
		}
		echo json_encode($arrRet);
		exit;
	}
	
	/* 取上盘押注情况
	*
	*/
	function getLastPress()
	{
		global $db;
		$GameType = intval($_POST["gtype"]);
		$No = intval($_POST["no"]);
		$tabletz = GetGameTableName($GameType,"users_tz");
		$tablekg = GetGameTableName($GameType,"kg_users_tz");
		$arrRet = array('cmd'=>'','msg'=>'');
		
		if($tabletz == "")
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "游戏类型错误!";
			echo json_encode($arrRet);
			exit;
		}
		$pos = GetFromBeginNumStep($GameType);
		$pos = -$pos;
		$retMsg = "";
		$sql = "select tznum,tzpoints from {$tabletz} where  uid = '{$_SESSION['usersid']}' order by id desc";
		$result = $db->query($sql);
		if($rs = $db->fetch_array($result))
		{
			$arrNum = explode("|",$rs['tznum']);
			$arrPoints = explode("|",$rs['tzpoints']);
			
			for($i = 0; $i < count($arrNum); $i++)
			{
				$retMsg .= ($arrNum[$i] + $pos) . "," . $arrPoints[$i] . "|";
			}
			if($retMsg != "")
			{
				$retMsg = substr($retMsg,0,-1);
			}
		}
		else
		{
			$sql = "select tznum,tzpoints from {$tablekg} where uid = '{$_SESSION['usersid']}' order by id desc";
			$result = $db->query($sql);
			while($rs = $db->fetch_array($result))
			{
				$retMsg .= ($rs['tznum'] + $pos) . "," . $rs['tzpoints'] . "|";
			}
			if($retMsg != "")
			{
				$retMsg = substr($retMsg,0,-1);
			}
		}
		if($retMsg == "")
		{
			$arrRet['cmd'] = "norecord";
			$arrRet['msg'] = "您上期没有投注!";
		}
		else
		{
			$arrRet['cmd'] = "ok";
			$arrRet['msg'] = $retMsg;
		}
		echo json_encode($arrRet);
		exit;	
	}

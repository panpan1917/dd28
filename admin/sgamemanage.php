<?php  
    include_once( "inc/conn.php" );
    include_once( "inc/function.php" );
    
    /*判断是否登录*/
	if(!isset($_SESSION["Admin_UserID"]))
	{
		$arrNoLogin = array(array());
		$arrNoLogin[0]["cmd"] = "err_nologin";
		$arrNoLogin[0]["msg"] = "页面超时或您还没登录，请重新登录!";
		ArrayChangeEncode($arrNoLogin);
		echo json_encode($arrNoLogin);
		exit;
	}
	if(isset($_POST["action"]) && $_POST["action"] != "")
	{ 
        $act =  $_POST["action"];
	    switch($act)
	    {
	    	case "add_inneruser": // 添加内部号
	    		login_check( "users" );
	    		AddInnerUser($act);
	    		break;
	    	case "remove_inneruser": //移除内部用户
	    		login_check( "users" );
	    		RemoveInnerUser($act);
	    		break;
	    	case "get_inneruser_list": //取得内部用户列表
	    	 	GetInnerUserList($act);
	    	 	break;
	    	case "check_user": // 检测帐号
	    		CheckUser($act);
	    		break;
	    	case "get_openno_result": //取急速开奖号码
	    		GetOpenNoResult($act);
	    		break;
	    	case "cancel_gamelog": //撤销投注记录
	    		login_check( "gamegl" );
	    		CancelGameLog($act);
	    		break;
	    	case "get_gamecatchconfig_single": //取得单个游戏采集配置
	    		GetSingleCatchConfig($act);
	    		break;
	    	case "catch_gamelog": // 手动采集
	    		login_check( "gamegl" );
	    		CatchGameResult($act);
	    		break;
	    	case "open_gamelog":// 手动开奖
	    		login_check( "gamegl" );
	    		OpenGameResultManual($act);
	    		break;
	    	case "remove_gamecatchconfig": //删除游戏采集设置
	    		login_check( "gamegl" );
	    		RemoveGameCatchConfig($act);
	    		break;
	    	case "save_gamecatchconfig": //游戏采集保存
	    		login_check( "gamegl" );
	    		SaveGameCatchConfig($act);
	    		break;
	    	case "get_gamecatchconfig": //取游戏采集设置
	    		GetGameCatchConfig($act);
	    		break;
	    	case "save_canadatimezone": //添加拿大时区
	    		login_check( "gamegl" );
	    		AddCanadaTimeZone($act);
	    		break;
	    	case "remove_canadatimezone": //删除加拿大时区
	    		login_check( "gamegl" );
	    		RemoveCanadaTimeZone($act);
	    		break;
	    	case "get_canadatimezone": //取得加拿大时区
	    		GetCanadaTimeZone($act);
	    		break;
	    	case "game_specialmodel_config": //取得指定id模式
	    		GetSpecialModelConfig($act);
	    		break;
	    	case "get_specialid_model": //取特定ID特定游戏模式
	    		GetSpecialIDModelList($act);
	    		break;
	    	case "set_nextopenflag": //设置下盘开最小
	    		login_check( "gamegl" );
	    	 	SetNextOpenFlag($act);
	    	 	break;
	    	case "remove_robot_patch": //批量删除机器人
	    		login_check( "gamegl" );
	    		RemoveRobotPatch($act);
	    		break;
	    	case "add_robot": //添加机器人
	    		login_check( "gamegl" );
	    		AddRobot($act);
	    		break;
	    	case "robot_changestate": //修改机器人状态
	    		login_check( "gamegl" );
	    		ChangeRobotStatus($act);
	    		break;
	    	case "robot_changemodel": // 更改机器人模式
	    		login_check( "gamegl" );
	    		ChangeRobotModel($act);
	    		break;
	    	case "get_robotuser_list": //取得机器人列表
	    		GetGameRobotList($act);
	    		break;
	    	case "remove_model": //删除模式
	    		login_check( "gamegl" );
	    		RemoveModel($act);
	    		break;
	    	case "save_modeldetail": //保存投注模式 
	    		login_check( "gamegl" );
	    		SaveModelDetail($act);
	    		break;
	    	case "get_model_list": //取得模式列表
	    		GetModelList($act);
	    		break;
	    	case "game_model_config": //取得模式配置
	    		GetModelConfig($act);
	    		break;
	    	case "get_model_option": //取得模式下拉列表
	    		GetModelOption($act);
	    		break;
	    	case "save_robotpress": //保存机器人下注配置
	    		login_check( "gamegl" );
	    		SaveRobotPressConfig($act);
	    		break;
	    	case "get_robotconfig_list": //取得机器人下注配置表
	    		GetRobotConfigList($act);
	    		break;
	    	case "game_robotpress_config": //取得机器人下注配置
	    		GetRobotPressConfig($act);
	    		break;
	    	case "save_transconfig": //保存转账配置
	    		login_check( "gamegl" );
	    		SaveTransConfig($act);
	    		break;
	    	case "get_transconfig": //取得转账手续费配置
	    		GetTransConfig($act);
	    		break;
	    	case "save_gameinit": //游戏初始化
	    		login_check( "gamegl" );
	    		SaveGameInit($act);
	    		break;
    		case "adjust_gamekjtime": //调整游戏开奖时间
    			login_check( "gamegl" );
    			AdjustGameKjTime($act);
    			break;
	    	case "addnew_account": //创建帐号
	    		login_check( "users" );
	    		CreateAccount();
	    		break;
    		case "get_adduser_log": //创建帐号日志
    			GetCreateAccountLog($act);
    			break;
	    	case "open_game": // 开奖游戏
	    		login_check( "gamegl" );
	    		OpenGameResult($act);
	    		break;
	    	case "remove_gamelog": //删除游戏记录
	    		login_check( "gamegl" );
	    		RemoveGameLog($act);
	    		break;
	    	case "get_gamelogdetail": //取游戏开奖号码和赔率
	    		GetGameLogDetail($act);
	    		break;
	    	case "get_gamestats_log": // 游戏开奖记录
	    		GetGameStatsLog($act);
	    		break;
	    	case "get_gametype_option": //取得游戏列表
	    		GetGameTypeOption($act);
	    		break;
	    	case "save_gameconfig": //保存游戏设置
	    		login_check( "gamegl" );
	    		SaveGameConfig($act);
	    		break;
	    	case "get_gameconfig": //取游戏设置
	    		GetGameConfig($act);
	    		break;
	    	case "get_gamelist_option": //取游戏列表option
	    		GetGameListOption($act);
	    		break;
	        default:
	            exit;
	    }
	}
	/* 添加内部号
	*
	*/
	function AddInnerUser($act)
	 {
		global $db;
		$UserID = intval($_POST['userid']);
		$Remark = isset($_POST['remark'])?FilterStr($_POST['remark']):""; 
		$sql = "select 1 from users_inner where uid = {$UserID}";
		$result = $db->query($sql);
		if($row=$db->fetch_array($result))
		{
			$arrReturn[0]["cmd"] = "err";
			$arrReturn[0]["msg"] = "不能重复添加";
			ArrayChangeEncode($arrReturn);
			echo json_encode($arrReturn);
			return;
		}
		$sql = "select 1 from users where id = {$UserID}";
		$result = $db->query($sql);
		$RowCount = $db->num_rows($result); 
		if($RowCount == 0)
		{
			$arrReturn[0]["cmd"] = "err";
			$arrReturn[0]["msg"] = "帐号不存在";
			ArrayChangeEncode($arrReturn);
			echo json_encode($arrReturn);
			return;
		}
		
		$sql = "insert into users_inner(uid,add_time,remark)
			values({$UserID},now(),'{$Remark}')";
		$result = $db->query($sql);
		$affectCount = $db->affected_rows();
		
		$arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = "操作成功,影响记录数" . $affectCount ."条"; 
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	function RemoveInnerUser($act)
	 {
		global $db;
		$UserID = intval($_POST['userid']);
		
		$sql = "delete from users_inner where uid = '{$UserID}'";
		$result = $db->query($sql);
		$affectCount = $db->affected_rows();
		
		$arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = "操作成功,影响记录数" . $affectCount ."条"; 
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 取得内部用户列表
	*
	*/
	function GetInnerUserList($act)
	{
		global $db;
		$UserID = intval($_POST['userid']);
        $sqlCount = "select Count(*) ";
        $sqlCol = "select a.uid,a.add_time,a.remark,b.username,b.nickname,b.points,b.back,b.lock_points
					";
        $sqlFrom = " from users_inner a
        			 left outer join users b
        			 on a.uid = b.id
        			 where 1=1 ";
        $sqlWhere = "";
        if($UserID != 0 )
        	$sqlWhere .= " and a.uid = {$UserID}";
        $sqlOrder = " order by add_time desc";
        //页大小
        $PageSize = isset($_POST['PageSize'])?$_POST['PageSize']:50;
        $PageSize = intval($PageSize);
        //页码
        $page = isset($_POST['Page'])?$_POST['Page']:1;
        $page =intval($page);
        
        //取得总记录数
        $TotalCount = $db->GetRecordCount($sqlCount.$sqlFrom.$sqlWhere);
        //取记录
        $sql = $sqlCol . $sqlFrom . $sqlWhere . $sqlOrder . GetLimit($page,$PageSize);

        $RowCount = 0;
        $arrRows = array(array());
        $result = $db->query($sql);
        $RowCount = $db->num_rows($result); 
        $total_points = 0;
        $total_back = 0;
        $total_lockpoints = 0;
        for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
        {    
            //对返回数据进行包装
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["UserName"] = $row["username"];
            $arrRows[$i]["NickName"] = $row["nickname"];
            $arrRows[$i]["Points"] = Trans($row["points"]);
            $arrRows[$i]["Back"] = Trans($row["back"]);
            $arrRows[$i]["LockPoints"] = Trans($row["lock_points"]);
            $arrRows[$i]["AddTime"] = $row["add_time"];
            $arrRows[$i]["Remark"] = $row["remark"];
            $arrRows[$i]["Opr"] = "<a style='cursor:pointer' title='移除' onclick=\"RemoveUser({$row['uid']})\">移除</a>";
            $total_points += $row["points"];
	        $total_back += $row["back"];
	        $total_lockpoints += $row["lock_points"];
        }
        if($RowCount > 1)
        {
        	$i = $RowCount + 1;
			$arrRows[$i]["UserID"] = "";
            $arrRows[$i]["UserName"] = "";
            $arrRows[$i]["NickName"] = "";
            $arrRows[$i]["Points"] = Trans($total_points);
            $arrRows[$i]["Back"] = Trans($total_back);
            $arrRows[$i]["LockPoints"] = Trans($total_lockpoints);
            $arrRows[$i]["AddTime"] = "";
            $arrRows[$i]["Remark"] = "";
            $arrRows[$i]["Opr"] = "";
        }
        
        //返回分页
        require_once('inc/fenye.php');
        $ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
        $pageInfo = $ajaxpage->show();
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
    }
	/* 检测帐号
	*
	*/
	function CheckUser($act)
	{
		global $db;
		$UserID = intval($_POST['userid']);
		$sql = "select username,nickname,points,back,lock_points from users where id = {$UserID}";
		$result = $db->query($sql);
		$msg = "";
		if($row=$db->fetch_array($result))
		{
			$msg = "ID:" . $UserID . ",帐号:" . $row["username"] . ",昵称:" . $row["nickname"] . ",游戏分:" . Trans($row["points"]) .
					",银行分:" . Trans($row["back"]) . ",投注分:" . Trans($row["lock_points"]);
		}
		else
		{
			$msg = "帐号不存在!";
		}
		$arrReturn[0]["cmd"] = "ok";
	    $arrReturn[0]["msg"] = $msg;
	    ArrayChangeEncode($arrReturn);
	    echo json_encode($arrReturn);
	}
	/* 取开奖号码
	*
	*/
	function GetOpenNoResult($act)
	{
		global $db;
		$ResultNo = isset($_POST['resultno'])?FilterStr($_POST['resultno']):"";
		$GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
		if($ResultNo == "" || $GameType == "" )
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		$ResultNo = intval($ResultNo);
		if($GameType == "gamefast28"){
			$num_type = "game28";
		}else if($GameType == "gamefast16"){
			$num_type = "game16";
		}else if($GameType == "gamefast11"){
			$num_type = "game11";
		}else if($GameType == "gamefast36"){
			$num_type = "game36";
		}else if($GameType == "gamefast10"){
			$arrRows[0]["cmd"] = $act;
			$arrRows[0]["msg"] = "ok";
				
			if(intval($ResultNo) >= 1 && intval($ResultNo) <= 10){
				$arrRows[1]["ResultNo"] = intval($ResultNo);
				$sql = "select kgjg,kgNo from gamefast10 where kgjg like '%|$ResultNo' order by rand() limit 1";
				$result = $db->query($sql);
				if($row=$db->fetch_array($result)){
					$i = 1;
					$kgjgArr = explode("|", $row['kgjg']);
					$arrRows[$i]["Num1"] = $kgjgArr[0];
					$arrRows[$i]["Num2"] = $kgjgArr[1];
					$arrRows[$i]["Num3"] = $kgjgArr[2];
					$arrRows[$i]["ResultNo"] = $kgjgArr[3];
					$arrRows[$i]["kgNo"] = str_replace("|",",",$row['kgNo']);
					ArrayChangeEncode($arrRows);
					echo json_encode($arrRows);
					return;
				}
			}
				
				
			$arrRows[1]["Num1"] = "0";
			$arrRows[1]["Num2"] = "0";
			$arrRows[1]["Num3"] = "0";
			$arrRows[1]["ResultNo"] = "";
			$arrRows[1]["kgNo"] = "";
			ArrayChangeEncode($arrRows);
			echo json_encode($arrRows);
			return;
		}else if($GameType == "gamefast22"){
			$arrRows[0]["cmd"] = $act;
			$arrRows[0]["msg"] = "ok";
			
			if(intval($ResultNo) >= 6 && intval($ResultNo) <= 27){
				$arrRows[1]["ResultNo"] = intval($ResultNo);
				$sql = "select kgjg,kgNo from gamefast22 where kgjg like '%|$ResultNo' order by rand() limit 1";
				$result = $db->query($sql);
				if($row=$db->fetch_array($result)){
					$i = 1;
					$kgjgArr = explode("|", $row['kgjg']);
					$arrRows[$i]["Num1"] = $kgjgArr[0];
					$arrRows[$i]["Num2"] = $kgjgArr[1];
					$arrRows[$i]["Num3"] = $kgjgArr[2];
					$arrRows[$i]["ResultNo"] = $kgjgArr[3];
					$arrRows[$i]["kgNo"] = str_replace("|",",",$row['kgNo']);
					ArrayChangeEncode($arrRows);
					echo json_encode($arrRows);
					return;
				}
			}
			
			
			$arrRows[1]["Num1"] = "0";
			$arrRows[1]["Num2"] = "0";
			$arrRows[1]["Num3"] = "0";
			$arrRows[1]["ResultNo"] = "";
			$arrRows[1]["kgNo"] = "";
			ArrayChangeEncode($arrRows);
			echo json_encode($arrRows);
			return;
		}else if($GameType == "gamefastgyj"){
			$arrRows[0]["cmd"] = $act;
			$arrRows[0]["msg"] = "ok";
				
			if(intval($ResultNo) >= 3 && intval($ResultNo) <= 19){
				$arrRows[1]["ResultNo"] = intval($ResultNo);
				$sql = "select kgjg,kgNo from gamefastgyj where kgjg like '%|$ResultNo' order by rand() limit 1";
				$result = $db->query($sql);
				if($row=$db->fetch_array($result)){
					$i = 1;
					$kgjgArr = explode("|", $row['kgjg']);
					$arrRows[$i]["Num1"] = $kgjgArr[0];
					$arrRows[$i]["Num2"] = $kgjgArr[1];
					$arrRows[$i]["Num3"] = $kgjgArr[2];
					$arrRows[$i]["ResultNo"] = $kgjgArr[3];
					$arrRows[$i]["kgNo"] = str_replace("|",",",$row['kgNo']);
					ArrayChangeEncode($arrRows);
					echo json_encode($arrRows);
					return;
				}
			}
				
				
			$arrRows[1]["Num1"] = "0";
			$arrRows[1]["Num2"] = "0";
			$arrRows[1]["Num3"] = "0";
			$arrRows[1]["ResultNo"] = "";
			$arrRows[1]["kgNo"] = "";
			ArrayChangeEncode($arrRows);
			echo json_encode($arrRows);
			return;
		}
		
		
		$sql = "select num_a,num_b,num_c,num_sum from numsumlist where num_type='{$num_type}' and num_sum = '{$ResultNo}' order by rand() limit 1";
		$result = $db->query($sql);
		if($row=$db->fetch_array($result))
		{
			$i = 1;
			$arrRows[$i]["ResultNo"] = $row["num_sum"];
            $arrRows[$i]["Num1"] = $row["num_a"]; 
            $arrRows[$i]["Num2"] = $row["num_b"];
            $arrRows[$i]["Num3"] = $row["num_c"];
            $arrRows[$i]["kgNo"] = "";
		}
		else
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "结果号码错误,请检查是否超出范围";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		$arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = "ok"; 
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
	}
	/* 撤销投注记录
	*
	*/
	function CancelGameLog($act)
	{
		global $db;
		$No = isset($_POST['no'])?FilterStr($_POST['no']):"";
		$GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
		$No = intval($No);
		if($No == 0 || $GameType == "" )
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		$sql = "call web_cancel_gamepress('{$GameType}',{$No})";
		$arrT = $db->Mysqli_Multi_Query($sql);
		switch($arrT[0][0]["result"])
		{
			case 0:
				$msg = "已成功撤销!";
				break;
			case 1:
				$msg = "没有投注记录!";
			case 99:
				$msg = "系统错误，撤销失败!";
				break;
			default:
				$msg = "未知错误，撤销失败!";
				break;
		}
		$arrReturn[0]["cmd"] = "ok";
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	/* 取得单个游戏采集配置
	*
	*/
	function GetSingleCatchConfig($act)
	 {
		global $db;
		$GameKind = isset($_POST['gamekind'])?FilterStr($_POST['gamekind']):"";
		if($GameKind == "")
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		
		$sql = "select gamekind,remark,no_interval_second,no_begin_time,no_end_time,catch_url,open_url
				from game_catch_config where gamekind = '{$GameKind}'";
		$result = $db->query($sql);
		if($row=$db->fetch_array($result))
		{
			$i = 1;
			$arrRows[$i]["GameKind"] = $row["gamekind"];
            $arrRows[$i]["Remark"] = $row["remark"]; 
            $arrRows[$i]["BeginTime"] = $row["no_begin_time"];
            $arrRows[$i]["EndTime"] = $row["no_end_time"];
            $arrRows[$i]["Interval"] = $row["no_interval_second"];
            $arrRows[$i]["CatchURL"] = $row["catch_url"];
            $arrRows[$i]["OpenURL"] = $row["open_url"];
		}
		else
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "无法找到配置";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		$arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = "ok"; 
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
	}
	/* 手动采集
	*
	*/
	function CatchGameResult($act)
	{
		global $db;
		$No = isset($_POST['no'])?FilterStr($_POST['no']):"";
		$GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
		$No = intval($No);
		if($No == 0 || $GameType == "" )
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		$GameKind = GetGameKindFromGameType($GameType); 
		if($GameKind == "") $msg = "游戏类型参数错误，无法采集";
		$sql = "select catch_url from game_catch_config where gamekind = '{$GameKind}'";
		//WriteLog($sql);
		$result = $db->query($sql);
		if($row=$db->fetch_array($result))
		{
			$url = $row['catch_url'] . "&no=" . $No; 
			//WriteLog($url);
			$kjcontent = fsockurl($url);
			$msg = "已成功执行采集，是否已采集到请关注监测结果";
		}
		else
		{
			$msg = "无法取得采集地址";
		}
		$arrReturn[0]["cmd"] = "ok";
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	/* 根据gametype取得GameKind
	*
	*/
	function GetGameKindFromGameType($GameType)
	{
		$GameKind = "";
		if($GameType == "game11" || $GameType == "game16" || $GameType == "game28" || $GameType == "gameself28" || $GameType == "gamebj11" || $GameType == "gamebj16" || $GameType == "game36" || $GameType == "gamebj36" || $GameType == "gameww" || $GameType == "gamedw")
			$GameKind = "gamebj";
		else if($GameType == "gamepk10" || $GameType == "gamegj10" || $GameType == "gamepk22" || $GameType == "gamepklh" || $GameType == "gamepkgyj" || $GameType == "gamepksc")
			$GameKind = "gamepk";
		else if($GameType == "gamecan28" || $GameType == "gamecan16" || $GameType == "gamecan11" || $GameType == "gamecan36" || $GameType == "gamecanww" || $GameType == "gamecandw")
			$GameKind = "gamecan";
		else if($GameType == "gamehg28" || $GameType == "gamehg16" || $GameType == "gamehg11" || $GameType == "gamehg36" || $GameType == "gamehgww" || $GameType == "gamehgdw")
			$GameKind = "gamehg";
		else if($GameType == "gamexync" || $GameType == "gamecqssc")
			$GameKind = $GameType;
		return $GameKind;
	}
	/* 手动开奖
	*
	*/
	function OpenGameResultManual($act)
	{
		global $db;
		$No = isset($_POST['no'])?FilterStr($_POST['no']):"";
		$kgno = isset($_POST['kgno'])?FilterStr($_POST['kgno']):"";
		$GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
		$No = intval($No);
		if($No == 0 || $GameType == "" )
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		if($GameType == "gamefast10" || $GameType == "gamefast11" || $GameType == "gamefast16" || $GameType == "gamefast28" || $GameType == "gamefast22" || $GameType == "gamefast36" || $GameType == "gamefastgyj")
		{
			//急速类
			$sql = " call sys_kj_" . $GameType . "({$No})";
			$arrT = $db->Mysqli_Multi_Query($sql);
			if($arrT[0][0]["result"] == 0){
				WriteLog( $_SESSION["Admin_UserID"] . ":" . usersip() . " : " . $sql);
				$msg = "开奖成功!";
			}else{
				$msg = "系统错误，开奖失败!";
			}
			
			$arrReturn[0]["cmd"] = "ok";
	        $arrReturn[0]["msg"] = $msg;
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		else
		{
			//采集类
			$GameKind = GetGameKindFromGameType($GameType); 
			if($GameKind == "") $msg = "游戏类型参数错误，无法开奖";
			
			$sql = "select open_url from game_catch_config where gamekind = '{$GameKind}'";
			$result = $db->query($sql);
			if($row=$db->fetch_array($result))
			{
				$cmd = "cd /alidata/www/kdy28/caiji && {$row['open_url']} No={$No} resultStr={$kgno}";
				@system($cmd);
				$msg = "已成功执行开奖，是否已开奖请关注监测结果";
			}
			else
			{
				$msg = "无法取得开奖地址";
			}
			
			
			$arrReturn[0]["cmd"] = "ok";
			$arrReturn[0]["msg"] = $msg;
			ArrayChangeEncode($arrReturn);
			echo json_encode($arrReturn);
		}
	}
	/* 删除游戏采集设置
	*
	*/
	function RemoveGameCatchConfig($act)
	{
		global $db;
		$GameKind = isset($_POST['gamekind'])?FilterStr($_POST['gamekind']):"";
		if($GameKind == "")
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		
		$sql = "delete from game_catch_config where gamekind = '{$GameKind}'";
		$result = $db->query($sql);
		$affectCount = $db->affected_rows();
		
		$arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = "操作成功,影响记录数" . $affectCount ."条"; 
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 游戏采集保存
	*
	*/
	function SaveGameCatchConfig($act)
	{
		global $db;
		$GameKind = isset($_POST['gamekind'])?FilterStr($_POST['gamekind']):"";   
		$Remark = isset($_POST['remark'])?FilterStr($_POST['remark']):"";
		$Interval = intval($_POST['interval']);
		$BeginTime = isset($_POST['begintime'])?FilterStr($_POST['begintime']):"";
		$EndTime = isset($_POST['endtime'])?FilterStr($_POST['endtime']):"";
		$CatchURL = isset($_POST['catchurl'])?FilterStr($_POST['catchurl']):"";
		$OpenURL = isset($_POST['openurl'])?FilterStr($_POST['openurl']):"";
		
		//$CatchURL = urldecode($CatchURL);
		//$OpenURL = urldecode($OpenURL);
		echo $CatchURL;
		$Remark = ChangeEncodeU2G($Remark);
		if($GameKind == "" || $Remark == "" || $Interval == 0 || $BeginTime == "" || $EndTime == "" )
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "数据错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		$sql = "";
		$sqlTmp = "select 1 from game_catch_config where gamekind = '{$GameKind}'";
		$result = $db->query($sqlTmp);
		if($row=$db->fetch_array($result))
		{
			$sql = "update game_catch_config set no_interval_second = {$Interval},no_begin_time = '{$BeginTime}',no_end_time='{$EndTime}',remark='{$Remark}',
					catch_url='{$CatchURL}',open_url='{$OpenURL}'
					where gamekind = '{$GameKind}'";
		}
		else
		{
			$sql = "insert into game_catch_config(gamekind,no_interval_second,no_begin_time,no_end_time,remark,catch_url,open_url)
				values('{$GameKind}',{$Interval},'{$BeginTime}','{$EndTime}','{$Remark}','{$CatchURL}','{$OpenURL}')";
		}
		$result = $db->query($sql);
		$affectCount = $db->affected_rows();
		
		$arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = "操作成功,影响记录数" . $affectCount ."条"; 
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 取游戏采集设置
	*
	*/
	function GetGameCatchConfig($act)
	{
		global $db;
        $sqlCount = "select Count(*) ";
        $sqlCol = "select gamekind,remark,no_interval_second,no_begin_time,no_end_time,catch_url,open_url
					";
        $sqlFrom = " from game_catch_config ";
        $sqlWhere = "";
        $sqlOrder = " ";
        //页大小
        $PageSize = isset($_POST['PageSize'])?$_POST['PageSize']:50;
        $PageSize = intval($PageSize);
        //页码
        $page = isset($_POST['Page'])?$_POST['Page']:1;
        $page =intval($page);
        
        //取得总记录数
        $TotalCount = $db->GetRecordCount($sqlCount.$sqlFrom.$sqlWhere);
        //取记录
        $sql = $sqlCol . $sqlFrom . $sqlWhere . $sqlOrder . GetLimit($page,$PageSize);

        $RowCount = 0;
        $arrRows = array(array());
        $result = $db->query($sql);
        //取得返回记录数
        $RowCount = $db->num_rows($result);
        if($RowCount == 0)
        {
            $arrReturn[0]["cmd"] = "norecord";
            $arrReturn[0]["msg"] = "没有记录!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
        }
        for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
        {    
            //对返回数据进行包装
            $arrRows[$i]["GameKind"] = $row["gamekind"];
            $arrRows[$i]["Remark"] = $row["remark"]; 
            $arrRows[$i]["BeginTime"] = $row["no_begin_time"];
            $arrRows[$i]["EndTime"] = $row["no_end_time"];
            $arrRows[$i]["Interval"] = $row["no_interval_second"];
            $arrRows[$i]["CatchURL"] = $row["catch_url"];
            $arrRows[$i]["OpenURL"] = $row["open_url"];
            $arrRows[$i]["Opr"] = "<a style='cursor:pointer' title='删除' onclick=\"RemoveRec('{$row['gamekind']}')\">删除</a>";
        }
        
        //返回分页
        require_once('inc/fenye.php');
        $ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
        $pageInfo = $ajaxpage->show();
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
    }
	/* 添加加拿大时区
	*
	*/
	function AddCanadaTimeZone($act)
	{
		global $db;
		$Year = intval($_POST['year']);
		$BeginTime = isset($_POST['begintime'])?FilterStr($_POST['begintime']):"";
		$EndTime = isset($_POST['endtime'])?FilterStr($_POST['endtime']):"";
		if($Year == 0 || $Year < 2015 || $Year > 2100 || $BeginTime == "" || $EndTime == "" || strtotime($EndTime) <= strtotime($BeginTime))
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "数据错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		$sql = "select 1 from canada_timezone where c_year = {$Year}";
		$result = $db->query($sql);
		if($row=$db->fetch_array($result))
		{
			$arrReturn[0]["cmd"] = "err";
			$arrReturn[0]["msg"] = "不能重复添加";
			ArrayChangeEncode($arrReturn);
			echo json_encode($arrReturn);
			return;
		}
		
		$sql = "insert into canada_timezone(c_year,c_begintime,c_endtime)
			values({$Year},'{$BeginTime}','{$EndTime}')";
		$result = $db->query($sql);
		$affectCount = $db->affected_rows();
		
		$arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = "操作成功,影响记录数" . $affectCount ."条"; 
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 删除加拿大时区
	*
	*/
	function RemoveCanadaTimeZone($act)
	 {
		global $db;
		$Year = intval($_POST['year']);
		if($Year == 0 || $Year < 2015 || $Year > 2100)
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "年份错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		
		$sql = "delete from canada_timezone where c_year = '{$Year}'";
		$result = $db->query($sql);
		$affectCount = $db->affected_rows();
		
		$arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = "操作成功,影响记录数" . $affectCount ."条"; 
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 取得加拿大时区
	*
	*/
	function GetCanadaTimeZone($act)
	{
		global $db;
        $sqlCount = "select Count(*) ";
        $sqlCol = "select c_year,c_begintime,c_endtime
					";
        $sqlFrom = " from canada_timezone ";
        $sqlWhere = "";
        $sqlOrder = " order by c_year";
        //页大小
        $PageSize = isset($_POST['PageSize'])?$_POST['PageSize']:50;
        $PageSize = intval($PageSize);
        //页码
        $page = isset($_POST['Page'])?$_POST['Page']:1;
        $page =intval($page);
        
        //取得总记录数
        $TotalCount = $db->GetRecordCount($sqlCount.$sqlFrom.$sqlWhere);
        //取记录
        $sql = $sqlCol . $sqlFrom . $sqlWhere . $sqlOrder . GetLimit($page,$PageSize);

        $RowCount = 0;
        $arrRows = array(array());
        $result = $db->query($sql);
        //取得返回记录数
        $RowCount = $db->num_rows($result);
        if($RowCount == 0)
        {
            $arrReturn[0]["cmd"] = "norecord";
            $arrReturn[0]["msg"] = "没有记录!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
        }
        for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
        {    
            //对返回数据进行包装
            $arrRows[$i]["Year"] = $row["c_year"];
            $arrRows[$i]["BeginTime"] = $row["c_begintime"];
            $arrRows[$i]["EndTime"] = $row["c_endtime"];
            $arrRows[$i]["Opr"] = "<a style='cursor:pointer' title='删除' onclick=\"RemoveYear({$row['c_year']})\">删除</a>";
        }
        
        //返回分页
        require_once('inc/fenye.php');
        $ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
        $pageInfo = $ajaxpage->show();
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
    }
	/* 取得指定id模式
	*
	*/
	function GetSpecialModelConfig($act)
	{
		global $db;
		$ID = intval($_POST['id']); 
		$GameType = intval($_POST['gametype']);
		$tableName = GetGameTableName($GameType) . "_auto_tz";
		
		$sql = "select id,tzname,tzpoints,tznum from {$tableName} where id = {$ID}";
		$arrRows = array(array());
        $result = $db->query($sql);
        if($row=$db->fetch_array($result))
        {
			$arrRows[1]["ModelName"] = $row["tzname"];
			$arrRows[1]["PressDetail"] = $row["tznum"];
			$arrRows[1]["PressPoint"] = $row["tzpoints"];
        }
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = "ok";
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
	}
	/* 取指定用户ID指定游戏模式
	*
	*/
	function GetSpecialIDModelList($act)
	{
		global $db;
		$GameType = intval($_POST['gametype']); 
		$UserID = intval($_POST['userid']);
		$arrReturn = array(array());
		
		$tableName = GetGameTableName($GameType) . "_auto_tz";
		
		$sql = "select id,tzname from {$tableName} where uid = {$UserID}";
		$result = $db->query($sql);
		$option = "";
		while($row=$db->fetch_array($result))
		{
			$option .= "<option value='{$row['id']}'>{$row['tzname']}</option>";
		}
		$arrReturn = array(array());
		$arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = $option;
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 根据游戏类型ID取得表名
	*
	*/
	function GetGameTableName($gametype)
	{
		global $db;
		$tableName = "";
		$sql = "select game_table_prefix from game_config where game_type = '{$gametype}'";
		$result = $db->query($sql);
		if($row=$db->fetch_array($result))
		{
			$tableName = $row["game_table_prefix"];
		}
		return $tableName;
	}
	/*
	*
	*/
	function GetGameTypeFromName($name)
	{
		global $db;
		$GameType  = -1;
		$sql = "select game_type from game_config where game_table_prefix = '{$name}'";
		$result = $db->query($sql);
		if($row=$db->fetch_array($result))
		{
			$GameType = $row["game_type"];
		}
		return $GameType;
	}
	/* 设置下盘开最小
	*
	*/
	function SetNextOpenFlag($act)
	{
		global $db;
		$GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
		
		$arrReturn = array(array());
		$sql = "update game_config set
						game_open_flag = 1
		        where game_type = {$GameType}
				";
		$result = $db->query($sql);
		$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		$arrReturn[0]["cmd"] = $act;
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	/* 批量删除机器人
	*
	*/
	function RemoveRobotPatch($act)
	{
		global $db;
		$GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
		$ID = isset($_POST['id'])?FilterStr($_POST['id']):"";
		$arrReturn = array(array());
		if($GameType == "")
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		$sql = "select game_table_prefix from game_config where game_type = '{$GameType}'";
		$result = $db->query($sql);
		$tableName = "";
		if($row=$db->fetch_array($result))
		{
			$tableName = $row["game_table_prefix"] . "_auto";
		}
		if($tableName == "")
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		
		//删除详细model
		$sql = "delete from gameall_auto_tz where gametype='{$GameType}' 
				and uid in(select uid from {$tableName} where id in({$ID}) and usertype = 1) ";
		$result = $db->query($sql);
		
		$sql = "delete from {$tableName} where id in({$ID}) and usertype = 1";
		$result = $db->query($sql);
		$affectCount = $db->affected_rows();
		
		$arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = "操作成功,影响记录数" . $affectCount ."条"; 
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	
	/* 添加机器人
	*
	*/
	function AddRobot($act)
	{
		global $db;
		$GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
		$ModelID = isset($_POST['modelid'])?FilterStr($_POST['modelid']):"";
		$RobotNum = isset($_POST['robotnum'])?FilterStr($_POST['robotnum']):"";
		$MaxG = isset($_POST['maxg'])?FilterStr($_POST['maxg']):""; 
		$arrReturn = array(array());
		
		if(!is_numeric($GameType) || !is_numeric($ModelID) || !is_numeric($MaxG) || !is_numeric($RobotNum) )
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		$sql = "call web_back_addrobot({$GameType},{$MaxG},{$ModelID},{$RobotNum})";
		$result = $db->query($sql);
		
		
		$arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = "操作成功"; 
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 修改机器人状态
	*
	*/
	function ChangeRobotStatus($act)
	{
		global $db;
		$GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
		$StatusType = isset($_POST['t'])?FilterStr($_POST['t']):"";
		$IDs = isset($_POST['id'])?FilterStr($_POST['id']):"";
		$arrReturn = array(array());
		
		if(!is_numeric($GameType) || !is_numeric($StatusType) || $IDs == "")
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		$sql = "select game_table_prefix from game_config where game_type = {$GameType}";
		$result = $db->query($sql);
		$tableName = "";
		if($row=$db->fetch_array($result))
		{
			$tableName = $row["game_table_prefix"] . "_auto";
		}
		if($tableName == "")
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		
		$sql = "update {$tableName} set status = {$StatusType}
				where usertype = 1 and id in({$IDs})";
		//WriteLog($sql);
		$result = $db->query($sql);
		
		$arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = "操作成功!";
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 更改机器人模式
	*
	*/
	function ChangeRobotModel($act)
	{
		global $db;
		$GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
		$ModelID = isset($_POST['modelid'])?FilterStr($_POST['modelid']):"";
		$IDs = isset($_POST['id'])?FilterStr($_POST['id']):"";
		$arrReturn = array(array());
		
		if(!is_numeric($GameType) || !is_numeric($ModelID) || $IDs == "")
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		$sql = "select game_table_prefix from game_config where game_type = {$GameType}";
		$result = $db->query($sql);
		$tableName = "";
		if($row=$db->fetch_array($result))
		{
			$tableName = $row["game_table_prefix"] . "_auto";
		}
		if($tableName == "")
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		
		$sql = "update {$tableName} set autoid = {$ModelID}
				where usertype = 1 and id in({$IDs})";
		//WriteLog($sql);
		$result = $db->query($sql);
		
		$arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = "操作成功!";
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 取得游戏机器人列表
	*
	*/
	function GetGameRobotList($act)
	{
		global $db;
    	$GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
    	$ModelType = isset($_POST['modeltype'])?FilterStr($_POST['modeltype']):"";
    	$UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $arrReturn = array(array());
        
        if(!is_numeric($GameType))
        {
			$arrReturn[0]["cmd"] = "norecord";
            $arrReturn[0]["msg"] = "请选择游戏!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
        }
        $sql = "select game_table_prefix from game_config where game_type = {$GameType}";
		$result = $db->query($sql);
		$tableName = "";
		if($row=$db->fetch_array($result))
		{
			$tableName = $row["game_table_prefix"] . "_auto";
		}
		if($tableName == "")
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
        
        $sqlCount = "select Count(*) ";
        $sqlCol = "SELECT a.id,a.uid,b.nickname,b.points,b.back,b.lock_points,a.maxG,a.autoid,a.status,c.tzname
					";
        $sqlFrom = " FROM {$tableName} a
					LEFT OUTER JOIN users b
					ON a.uid = b.id
					LEFT OUTER JOIN game_robot_model c
					ON a.autoid = c.id
					WHERE a.usertype = 1
					";
        $sqlWhere = "";
        $sqlOrder = " order by autoid desc,uid";
        if($UserID != "")
        	$sqlWhere .= " and a.uid = {$UserID}";
        if($ModelType != "")
        	$sqlWhere .= " and a.autoid = {$ModelType}";
        $sql = "";
        //页大小
        $PageSize = isset($_POST['PageSize'])?$_POST['PageSize']:50;
        $PageSize = intval($PageSize);
        //页码
        $page = isset($_POST['Page'])?$_POST['Page']:1;
        $page =intval($page);
        
        //取得总记录数
        $TotalCount = $db->GetRecordCount($sqlCount.$sqlFrom.$sqlWhere);
        //取记录
        $sql = $sqlCol . $sqlFrom . $sqlWhere . $sqlOrder . GetLimit($page,$PageSize);

        //WriteLog($sql);
        //return;

        $RowCount = 0;
        $arrRows = array(array());
        $result = $db->query($sql);
        //取得返回记录数
        $RowCount = $db->num_rows($result);
        if($RowCount == 0)
        {
            $arrReturn[0]["cmd"] = "norecord";
            $arrReturn[0]["msg"] = "没有记录!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
        }
        for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
        {    
            //对返回数据进行包装
            $arrRows[$i]["strCheckBox"] = "<input name='cbxID' id='cbxID' type='checkbox' value='" . $row["id"] ."'>"; 
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            $arrRows[$i]["ModelName"] = $row["tzname"];
            $arrRows[$i]["Points"] = Trans($row["points"]);
            $arrRows[$i]["Back"] = Trans($row["back"]);
            $arrRows[$i]["LockPoints"] = Trans($row["lock_points"]);
            $arrRows[$i]["Status"] = ($row["status"] == 0) ? "<font color='red'>禁用</font>" : "<font color='blue'>正常</font>";
            
            $arrRows[$i]["Opr"] = $opr;
        }
        
        //返回分页
        require_once('inc/fenye.php');
        $ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
        $pageInfo = $ajaxpage->show();
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
    }
	/* 删除模式
	*
	*/
	function RemoveModel($act)
	{
		global $db;
		$ID = isset($_POST['id'])?FilterStr($_POST['id']):"";
		$GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
		$arrReturn = array(array());
		
		if(!is_numeric($GameType) || !is_numeric($ID))
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		$sql = "select game_table_prefix from game_config where game_type = {$GameType}";
		$result = $db->query($sql);
		$tableName = "";
		if($row=$db->fetch_array($result))
		{
			$tableName = $row["game_table_prefix"] . "_auto";
		}
		if($tableName == "")
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		
		
		$sql = "select count(*) as cnt from {$tableName} where usertype = 1 and autoid = {$ID}";
		$result = $db->query($sql);
		$row=$db->fetch_array($result);
		if($row["cnt"] > 0)
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "还有机器人在使用该模式，不能删除!";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		
		$sql = "delete from game_robot_model where id = {$ID}";
		$result = $db->query($sql);
		
		$arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = "删除成功!";
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 保存投注模式
	*
	*/
	function SaveModelDetail($act)
	{
		global $db;   
		$OprType = isset($_POST['oprtype'])?FilterStr($_POST['oprtype']):"";
		$ID = isset($_POST['modelid'])?FilterStr($_POST['modelid']):"";
		$GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
		$ModelName = isset($_POST['modelname'])?FilterStr($_POST['modelname']):"";
		$PressDetail = isset($_POST['pressdetail'])?FilterStr($_POST['pressdetail']):"";
		
		$arrReturn = array(array());
		
		if(!is_numeric($GameType) || $OprType == "" || $ModelName == "" || $PressDetail == "")
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		$GameType = intval($GameType);
		$sumPoint = 0;
		$PressDetail = str_replace("，",",",$PressDetail);
		$arrPress = explode("|",$PressDetail);
		foreach($arrPress as $k => $v )
		{
			$arrTmp = explode(",",$v);
			if(!is_numeric($arrTmp[1]))
			{
				$arrReturn[0]["cmd"] = "err";
		        $arrReturn[0]["msg"] = "下注明细中有错误:" . $v;
		        ArrayChangeEncode($arrReturn);
		        echo json_encode($arrReturn);
		        return;
			}
			$sumPoint += $arrTmp[1];
		} 
		$sql = "";
		$ModelName = ChangeEncodeU2G($ModelName); 
		if($OprType == "new")
		{
			$sql = "insert into game_robot_model(game_type,tzname,tzpoints,tznum)
						values({$GameType},'{$ModelName}',{$sumPoint},'{$PressDetail}')";
			
		}
		else
		{
			$sql = "update game_robot_model set tzname = '{$ModelName}',
						tzpoints = {$sumPoint},
						tznum = '{$PressDetail}'
			        where id = {$ID}";
		}
        $result = $db->query($sql);
		$msg = "操作成功";
		$arrReturn[0]["cmd"] = $act;
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	/* 取得游戏模式列表
	*
	*/
	function GetModelList($act)
	{
		global $db;
		$GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):""; 
		$sql = "select a.id,a.game_type,b.game_name,a.tzname,a.tzpoints,a.tznum 
				from game_robot_model a
				left outer join game_config b
				on a.game_type = b.game_type 
				where a.game_type = {$GameType}";
		$arrRows = array(array());
        $result = $db->query($sql);
        $i = 1;
        while($row=$db->fetch_array($result))
        {
			$arrRows[$i]["ModelID"] = $row["id"];
			$arrRows[$i]["GameName"] = $row["game_name"];
			$arrRows[$i]["ModelName"] = $row["tzname"];
			$arrRows[$i]["PressPoint"] = $row["tzpoints"]; 
			$arrRows[$i]["PressDetail"] = $row["tznum"]; 
			$i++;
        }
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = "ok";
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
	}
	/* 取得模式配置
	*
	*/
	function GetModelConfig($act)
	{
		global $db;
		$ID = isset($_POST['id'])?FilterStr($_POST['id']):""; 
		$sql = "select id,tzname,tzpoints,tznum from game_robot_model where id = {$ID}";
		$arrRows = array(array());
        $result = $db->query($sql);
        if($row=$db->fetch_array($result))
        {
			$arrRows[1]["ModelName"] = $row["tzname"];
			$arrRows[1]["PressDetail"] = $row["tznum"];
			$arrRows[1]["PressPoint"] = $row["tzpoints"];
        }
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = "ok";
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
	}
	/* 取得模式下拉列表
	*
	*/
	function GetModelOption($act)
	{
		global $db;
		$GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):""; 
		$arrReturn = array(array());
		
		$sql = "select id,tzname from game_robot_model where game_type = {$GameType}";
		$result = $db->query($sql);
		$option = "";
		while($row=$db->fetch_array($result))
		{
			$option .= "<option value='{$row['id']}'>{$row['tzname']}</option>";
		}
		$arrReturn = array(array());
		$arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = $option;
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 保存机器人下注配置
	*
	*/
	function SaveRobotPressConfig($act)
	{
		global $db;
		$GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
		$UserMin = isset($_POST['usermin'])?FilterStr($_POST['usermin']):"";
		$UserMax = isset($_POST['usermax'])?FilterStr($_POST['usermax']):"";
		$PressMin = isset($_POST['pressmin'])?FilterStr($_POST['pressmin']):"";
		$PressMax = isset($_POST['pressmax'])?FilterStr($_POST['pressmax']):"";
		
		$arrReturn = array(array());
		
		if(!is_numeric($GameType) || !is_numeric($UserMin) || !is_numeric($UserMax) || !is_numeric($PressMin) || !is_numeric($PressMax))
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		$GameType = intval($GameType);
		$UserMin = intval($UserMin);
		$UserMax = intval($UserMax);
		$PressMin = $PressMin;  
		$PressMax = $PressMax;  
		
		$sql = "update game_robot_config set min_num = {$UserMin},
					max_num = {$UserMax},
					min_points = {$PressMin},
					max_points = {$PressMax}
		        where game_type = {$GameType}";
        $result = $db->query($sql);
        
		$msg = "操作成功";
		$arrReturn[0]["cmd"] = $act;
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	/* 取得机器人下注配置表
	*
	*/
	function GetRobotConfigList($act)
	{
		global $db;
		$sql = "select a.game_type,a.min_num,a.max_num,a.min_points,a.max_points,b.game_name 
				from game_robot_config a
				left outer join game_config b
				on a.game_type = b.game_type ";
		$arrRows = array(array());
        $result = $db->query($sql);
        $i = 1;
        while($row=$db->fetch_array($result))
        {
        	$arrRows[$i]["GameName"] = $row["game_name"];
			$arrRows[$i]["UserCountMin"] = $row["min_num"];
			$arrRows[$i]["UserCountMax"] = $row["max_num"];
			$arrRows[$i]["PressMin"] = $row["min_points"];
			$arrRows[$i]["PressMax"] = $row["max_points"];
			$i++;
        }
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = "ok";
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
	}
	/* 取得机器人下注配置
	*
	*/
	function GetRobotPressConfig($act)
	{
		global $db;
		$GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
		$sql = "select game_type,min_num,max_num,min_points,max_points 
				from game_robot_config
				where game_type = {$GameType} limit 1";
		$arrRows = array(array());
        $result = $db->query($sql);
        if($row=$db->fetch_array($result))
        {
			$arrRows[1]["UserCountMin"] = $row["min_num"];
			$arrRows[1]["UserCountMax"] = $row["max_num"];
			$arrRows[1]["PressPointMin"] = $row["min_points"];
			$arrRows[1]["PressPointMax"] = $row["max_points"];
        }
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = "ok";
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
	}
	/* 保存配置
	*
	*/
	function SaveTransConfig($act)
	{
		global $db;
		$Odds = isset($_POST['odds'])?FilterStr($_POST['odds']):"";
		$PointMin = isset($_POST['pointmin'])?FilterStr($_POST['pointmin']):"";
		$ExcuseID = isset($_POST['excuseid'])?FilterStr($_POST['excuseid']):"";
		$GameOpenFlag =intval($_POST['gameopenflag']);
		$PressInterval =intval($_POST['pressinterval']);
		$RBSingleMax =intval($_POST['rbsinglemax']);
		$RBCntMax =intval($_POST['rbcntmax']);
		$RBDayMax =intval($_POST['rbdaymax']);
		$GameShutdownReason = isset($_POST['shutdownreason'])?FilterStr($_POST['shutdownreason']):"";
		$GameShutdownReason = ChangeEncodeU2G($GameShutdownReason);
		$arrReturn = array(array());
		
		if($Odds == "" || $PointMin == "" || $ExcuseID == "")
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "参数错误";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		$Odds = intval($Odds);
		$PointMin = intval($PointMin);
		$ExcuseID = intval($ExcuseID);
		
		$sql = "update sys_config set fldValue = '{$Odds}' where fldVar = 'bank_trans_odds' and fldType = 0";
        $result = $db->query($sql);
        $sql = "update sys_config set fldValue = '{$PointMin}' where fldVar = 'bank_trans_min' and fldType = 0";
        $result = $db->query($sql);
        $sql = "update sys_config set fldValue = '{$ExcuseID}' where fldVar = 'bank_trans_excuse_id' and fldType = 0";
        $result = $db->query($sql);
        $sql = "update sys_config set fldValue = '{$GameOpenFlag}' where fldVar = 'game_open_flag' and fldType = 0";
        $result = $db->query($sql);
        $sql = "update sys_config set fldValue = '{$GameShutdownReason}' where fldVar = 'game_shutdown_reason' and fldType = 0";
        $result = $db->query($sql);
        $sql = "update sys_config set fldValue = '{$PressInterval}' where fldVar = 'game_press_interval' and fldType = 0";
        $result = $db->query($sql);
        $sql = "update sys_config set fldValue = '{$RBSingleMax}' where fldVar = 'redbag_single_max' and fldType = 0";
        $result = $db->query($sql);
        $sql = "update sys_config set fldValue = '{$RBCntMax}' where fldVar = 'redbag_cnt_max' and fldType = 0";
        $result = $db->query($sql);
        $sql = "update sys_config set fldValue = '{$RBDayMax}' where fldVar = 'redbag_day_max' and fldType = 0";
        $result = $db->query($sql);
        
		$msg = "操作成功";
		$arrReturn[0]["cmd"] = $act;
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	/*取得配置
	*
	*/
	function GetTransConfig($act)
	{
		global $db;
		$sql = "select fldVar,fldValue from sys_config where fldType = 0";
		$arrRows = array(array());
        $result = $db->query($sql);
        while($row=$db->fetch_array($result))
        {
			$arrRows[1][$row["fldVar"]] = $row["fldValue"];
        }
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = "ok";
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
	}
	/* 游戏初始化
	*
	*/
	function SaveGameInit($act)
	{
		global $db;
		$GameTable = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
		$GameNo = isset($_POST['gameno'])?FilterStr($_POST['gameno']):"";
		$KgTime = isset($_POST['kgtime'])?FilterStr($_POST['kgtime']):"";
		
		$arrReturn = array(array());
		
		if($GameTable == "" )
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "获取游戏类型错误，请重新打开本页";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		if(!is_numeric($GameNo) )
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "期号必须为数字";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		$KgTime = date("Y-m-d H:i:s",strtotime($KgTime));
		$sql = "insert into {$GameTable}(id,kgtime,gfid,zjpl)
					select {$GameNo},'{$KgTime}',{$GameNo},game_std_odds
					from game_config
					where game_table_prefix = '{$GameTable}' limit 1
				";
		$result = $db->query($sql);
		$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		$arrReturn[0]["cmd"] = $act;
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	
	
	/*
	 * 调整游戏开奖时间
	 * */
	function AdjustGameKjTime($act){
		global $db;
		$GameTable = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
		$KjTimeDiff = isset($_POST['kjtimediff'])?FilterStr($_POST['kjtimediff']):"";
		$KjTimeDiff = (int)$KjTimeDiff;
		
		$arrReturn = array(array());
		
		if($GameTable == "" )
		{
			$arrReturn[0]["cmd"] = "err";
			$arrReturn[0]["msg"] = "获取游戏类型错误，请重新打开本页";
			ArrayChangeEncode($arrReturn);
			echo json_encode($arrReturn);
			return;
		}
		if($KjTimeDiff == 0){
			$arrReturn[0]["cmd"] = "err";
			$arrReturn[0]["msg"] = "时间描述应为不等于0的整数";
			ArrayChangeEncode($arrReturn);
			echo json_encode($arrReturn);
			return;
		}
		
		$sql = "UPDATE {$GameTable} SET kgtime=FROM_UNIXTIME(UNIX_TIMESTAMP(kgtime)+{$KjTimeDiff}) 
					WHERE kj=0 AND kgtime > DATE_ADD(NOW(),INTERVAL -120 MINUTE) ";
		$result = $db->query($sql);
		$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		$arrReturn[0]["cmd"] = $act;
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	
	
	/* 创建帐号
	*
	*/
	function CreateAccount($act)
	{
		global $db;
    	$Type = isset($_POST['type'])?FilterStr($_POST['type']):"";
    	$UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):""; 
    	$UserName = isset($_POST['username'])?FilterStr($_POST['username']):"";
    	$NickName = isset($_POST['nickname'])?FilterStr($_POST['nickname']):"";
    	$Pwd = isset($_POST['pwd'])?FilterStr($_POST['pwd']):"";
    	$Cnt = isset($_POST['cnt'])?FilterStr($_POST['cnt']):"";
    	$arrReturn = array(array());
    	
    	$ProcSql = "";
    	$ip = usersip();
    	if($Type == "0")
    	{
    		if($UserID == "" || !is_numeric($UserID))
    		{
				$arrReturn[0]["cmd"] = "err";
	            $arrReturn[0]["msg"] = "请输入数字的用户ID!";
	            ArrayChangeEncode($arrReturn);
	            echo json_encode($arrReturn);
	            return;
    		}
    		if($UserName == "")
    		{
				$arrReturn[0]["cmd"] = "err";
	            $arrReturn[0]["msg"] = "请输入用户名!";
	            ArrayChangeEncode($arrReturn);
	            echo json_encode($arrReturn);
	            return;
    		}
    		//$UserName = ChangeEncodeU2G($UserName); 
    		if($NickName == "")
    		{
				$arrReturn[0]["cmd"] = "err";
	            $arrReturn[0]["msg"] = "请输入昵称!";
	            ArrayChangeEncode($arrReturn);
	            echo json_encode($arrReturn);
	            return;
    		}
    		//$NickName = ChangeEncodeU2G($NickName);  
    		if($Pwd == "")
    		{
				$arrReturn[0]["cmd"] = "err";
	            $arrReturn[0]["msg"] = "请输入密码!";
	            ArrayChangeEncode($arrReturn);
	            echo json_encode($arrReturn);
	            return;
    		}
    		//$Pwd = md5($Pwd);
    		$Pwd = setPassword($Pwd);
    		$ProcSql = "call web_user_reg(0,{$UserID},'{$UserName}','{$NickName}','{$Pwd}','{$ip}',0)";
		}
		else
		{
    		if(!is_numeric($Cnt) || $Cnt < 0)
    		{
				$arrReturn[0]["cmd"] = "err";
	            $arrReturn[0]["msg"] = "请输入生成数量!";
	            ArrayChangeEncode($arrReturn);
	            echo json_encode($arrReturn);
	            return;
    		}
    		$Cnt = intval($Cnt);
    		$ProcSql = "call web_robot_reg({$Cnt})";
		}
		
		$arr = $db->Mysqli_Multi_Query($ProcSql);
		$arrReturn[0]["cmd"] = "err";
		$ret = $arr[0][0]["result"];
		if($Type == "0")
		{
	        switch($arr[0][0]["result"])
	        {
        		case 0:
        			$arrReturn[0]["cmd"] = $act;  
					$arrReturn[0]["msg"] = "生成成功!";
					if($Type == "0"){
						$sql = "insert createuserlog(uid,adminid) values({$UserID},{$_SESSION['Admin_UserID']})";
						$db->exec($sql);
					}
					break; 
				case 1:
					$arrReturn[0]["msg"] = "用户名重名";
					break;
				case 2: 
					$arrReturn[0]["msg"] = "昵称非法";
					break;
				case 3: 
					$arrReturn[0]["msg"] = "用户ID已存在";
					break;
				case 4: 
					$arrReturn[0]["msg"] = "昵称重名";
					break;
				case 99:  
					$arrReturn[0]["msg"] = "数据库执行错误，生成失败";
					break;
				default:
					$arrReturn[0]["msg"] = "其他错误，生成失败";
					break;
	        }
		}
		else
		{
			$arrReturn[0]["cmd"] = $act;  
			$arrReturn[0]["msg"] = "成功生成帐号数:" . $ret . "个";
		}
    	ArrayChangeEncode($arrReturn);
	    echo json_encode($arrReturn);
	}
	
	
	
	function GetCreateAccountLog($act){
		global $db;
		$sql = "select a.id as uid,a.nickname,a.username ,a.mobile , b.adminid , b.createtime , c.name as adminname 
				from users a,createuserlog b,admin c 
				where a.id=b.uid and b.adminid=c.id order by b.id desc limit 50";
		$result = $db->query($sql);
		//$RowCount = $db->num_rows($result);
		
		$i = 0;
		$arrRows[$i]["cmd"] = $act;
		$arrRows[$i]["msg"] = "";
		$i++;
		//$arrRows = [];
		while($row=$db->fetch_array($result))
		{
			//对返回数据进行包装
			$arrRows[$i]["uid"] = $row["uid"];
			$arrRows[$i]["nickname"] = $row["nickname"];
			$arrRows[$i]["mobile"] = !empty($row["mobile"])?$row["mobile"]:$row["username"];
			$arrRows[$i]["createtime"] = $row["createtime"];
			$arrRows[$i]["adminid"] = $row["adminid"];
			$arrRows[$i]["adminname"] = $row["adminname"];
			$i++;
		}
		
		ArrayChangeEncode($arrRows);
		echo json_encode($arrRows);
	}
	
	
	
	
	/* 手动干预开奖
	*
	*/
	function OpenGameResult($act)
	{
		global $db;
    	$GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
    	$No = isset($_POST['no'])?FilterStr($_POST['no']):""; 
    	$Result = isset($_POST['result'])?FilterStr($_POST['result']):"";
    	$Num1 = isset($_POST['num1'])?FilterStr($_POST['num1']):"";
    	$Num2 = isset($_POST['num2'])?FilterStr($_POST['num2']):"";
    	$Num3 = isset($_POST['num3'])?FilterStr($_POST['num3']):"";
    	$KgNo = isset($_POST['kgno'])?FilterStr($_POST['kgno']):"";
    	$arrReturn = array(array());
    	if($KgNo != "")
    		$KgNo = str_replace(",","|",$KgNo);
    	
    	if(!is_numeric($No) || $GameType == "")
    	{
			$arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "参数错误!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
    	}
    	
    	if($GameType == "gamefast10" || $GameType == "gamefast11" || $GameType == "gamefast16" || $GameType == "gamefast28" || $GameType == "gamefast22" || $GameType == "gamefast36" || $GameType == "gamefastgyj")
    	{
    	
		    	if(!is_numeric($Num1) || $Num1 < 0)
		    	{
					$arrReturn[0]["cmd"] = "err";
		            $arrReturn[0]["msg"] = "开奖号码1错误!";
		            ArrayChangeEncode($arrReturn);
		            echo json_encode($arrReturn);
		            return;
		    	}
		    	
		    	if(!is_numeric($Num2) || $Num2 < 0)
		    	{
					$arrReturn[0]["cmd"] = "err";
		            $arrReturn[0]["msg"] = "开奖号码2错误!";
		            ArrayChangeEncode($arrReturn);
		            echo json_encode($arrReturn);
		            return;
		    	}
		    	
				if((!is_numeric($Num3) || $Num3 < 0) && !in_array($GameType,['gamefast11','gamefastgyj']))
	    		{
					$arrReturn[0]["cmd"] = "err";
		            $arrReturn[0]["msg"] = "开奖号码3错误!";
		            ArrayChangeEncode($arrReturn);
		            echo json_encode($arrReturn);
		            return;
	    		}
		    	
		    	if(!is_numeric($Result) || $Result < 0)
		    	{
					$arrReturn[0]["cmd"] = "err";
		            $arrReturn[0]["msg"] = "开奖结果号码错误!";
		            ArrayChangeEncode($arrReturn);
		            echo json_encode($arrReturn);
		            return;
		    	}
    	
		    	
		    	$PreOpenStr = "";
		    	$prcName = "web_kj_" .  $GameType;
		    	$ProcSql = "";
		    	if($GameType=="gamefast28" || $GameType == "gamefast16" || $GameType == "gamefast36")
		    	{
		    		$ProcSql = "call {$prcName}({$No},{$Num1},{$Num2},{$Num3},{$Result})";
		    		$PreOpenStr = "{$Num1};{$Num2};{$Num3};{$Result}";
		    	}
		    	else if($GameType == "gamefast11")
		    	{
		    		$ProcSql = "call {$prcName}({$No},{$Num1},{$Num2},{$Result})";
		    		$PreOpenStr = "{$Num1};{$Num2};{$Result}";
		    	}
		    	else if($GameType == "gamefast10")
		    	{
		    		//$arrResult = GetGameFast10Result($Result);
		    		//$kgStr = implode("|",$arrResult);
		    		$ProcSql = "call {$prcName}({$No},{$Num1},{$Num2},{$Num3},'{$KgNo}')";
		    		$PreOpenStr = "{$Num1};{$Num2};{$Num3};{$KgNo}";
		    	}
		    	else if($GameType == "gamefast22")
		    	{
		    		$ProcSql = "call {$prcName}({$No},{$Num1},{$Num2},{$Num3},'{$KgNo}')";
		    		$PreOpenStr = "{$Num1};{$Num2};{$Num3};{$KgNo}";
		    	}
		    	else if($GameType == "gamefastgyj")
		    	{
		    		$ProcSql = "call {$prcName}({$No},{$Num1},{$Num2},'{$KgNo}')";
		    		$PreOpenStr = "{$Num1};{$Num2};{$KgNo}";
		    	}
		    	
		    	
		    	$sql = "select 1 from {$GameType} where id = '{$No}' and kgtime > now()";
		    	$result = $db->query($sql);
		    	if($row=$db->fetch_array($result))
		    	{ //大于当前时间，只记录结果
			    	$sql = "update {$GameType} set pre_kgjg = '{$PreOpenStr}' where id = '{$No}'";
			    	$result = $db->query($sql);
			    	$msg = "开奖时间大于当前时间，已记录预开奖结果，影响记录数" . $db->affected_rows() ."条";
			    	$arrReturn[0]["cmd"] = "ok";
			    	$arrReturn[0]["msg"] = $msg;
			    	ArrayChangeEncode($arrReturn);
			    	echo json_encode($arrReturn);
			    	return;
		    	}
		    	
		    	$arr = $db->Mysqli_Multi_Query($ProcSql);
		    	$arrReturn[0]["cmd"] = "ok";
		    	$msg = "开奖结果:";
		    	switch($arr[0][0]["result"])
		    	{
		    		case 0:
		    			WriteLog( $_SESSION["Admin_UserID"] . ":" . usersip() . " : " . $ProcSql);
		    			$msg .= "成功!";
		    			break;
		    		case 1:
		    			$msg .= "该期已开奖过了，不能重复开奖!";
		    			break;
		    		case 2:
		    			$msg .= "由于取中奖赔率错误，开奖失败!";
		    			break;
		    		case 99:
		    			$msg .= "数据库执行错误，开奖失败!";
		    			break;
		    		default:
		    			$msg .= "其他错误，开奖失败!";
		    			break;
		    	}
		    	
		    	$arrReturn[0]["msg"] = $msg;
		    	ArrayChangeEncode($arrReturn);
		    	echo json_encode($arrReturn);
		    	
    	}else{
    		
    		//采集类
    		$GameKind = GetGameKindFromGameType($GameType);
    		if($GameKind == "") $msg = "游戏类型参数错误，无法开奖";
    			
    		$sql = "select open_url from game_catch_config where gamekind = '{$GameKind}'";
    		$result = $db->query($sql);
    		if($row=$db->fetch_array($result))
    		{
    			$KgNo = str_replace("|",",",$KgNo);
    			$cmd = "cd /alidata/www/kdy28/caiji && {$row['open_url']} No={$No} resultStr={$KgNo}";
    			@system($cmd);
    			$msg = "已成功执行开奖，是否已开奖请关注监测结果";
    		}
    		else
    		{
    			$msg = "无法取得开奖地址";
    		}
    			
    			
    		$arrReturn[0]["cmd"] = "ok";
    		$arrReturn[0]["msg"] = $msg;
    		ArrayChangeEncode($arrReturn);
    		echo json_encode($arrReturn);
    	}
    	
    	
    	/*
    	$checkGameOpen = CheckGameOpen($GameType,$No,$Num1,$Num2,$Num3,$KgNo);
    	if($checkGameOpen != "ok")
    	{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = $checkGameOpen;
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
    	}
    	*/
		
		

	}
	/* 取得fast10开奖号码序列
	*
	*/
	function GetGameFast10Result($resultno)
	{
		$arrR = array();
		$arrR[0] = $resultno;
		$arr10 = array(1,2,3,4,5,6,7,8,9,10);
		array_splice($arr10,$resultno-1,1);
		
		for($i = 1; $i <= 9 ; $i++)
		{
			$index = rand(0,count($arr10)-1);
			$arrR[$i] = $arr10[$index];
			array_splice($arr10,$index,1);
		}
		
		return $arrR;
	}
	/*
	*
	*/
	function CheckGameOpen($GameType,$No,$Num1,$Num2,$Num3,$KgNo)
	{
		$ret = "ok";
		
		//检测官方开奖号是否正确
		if($GameType == "game28" || $GameType == "gameself28" || $GameType == "gamebj16" || $GameType == "gamecan28" ||
			$GameType == "gamecan16" || $GameType == "gamecan11")
		{
			$arrKg = explode("|",$KgNo);
			if(count($arrKg) != 20)
			{
				$ret = "官方开奖号码错误，必须为20个开奖号,现在是" . count($arrKg) . "个!";
				return $ret;
			}
		}
		elseif($GameType == "gamepk10" || $GameType == "gamegj10")
		{
			$arrKg = explode("|",$KgNo);
			if(count($arrKg) != 10)
			{
				$ret = "官方开奖号码错误，必须为10个开奖号,现在是" . count($arrKg) . "个!";
				return $ret;
			}
		}
		
		if($GameType == "game28" || $GameType == "gameself28" || $GameType == "gamefast28" || $GameType == "gamecan28")
		{
			if($Num1 > 9)
			{
				$ret = "开奖号码1超出范围0-9!";
				return $ret;
			}
			if($Num2 > 9)
			{
				$ret = "开奖号码2超出范围0-9!";
				return $ret;
			}
			if($Num3 > 9)
			{
				$ret = "开奖号码3超出范围0-9!";
				return $ret;
			}
			if($Num1 + $Num2 + $Num3 > 27)
			{
				$ret = "开奖号码总和不能超过27!";
				return $ret;
			}
		}
		elseif($GameType == "gamebj16" ||  $GameType == "gamefast16" || $GameType == "gamecan16")
		{
			if($Num1 > 6 || $Num1 < 1)
			{
				$ret = "开奖号码1超出范围1-6!";
				return $ret;
			}
			if($Num2 > 6 || $Num2 < 1)
			{
				$ret = "开奖号码2超出范围1-6!";
				return $ret;
			}
			if($Num3 > 6 || $Num3 < 1)
			{
				$ret = "开奖号码3超出范围1-6!";
				return $ret;
			}
			if($Num1 + $Num2 + $Num3 > 18 || $Num1 + $Num2 + $Num3 < 3)
			{
				$ret = "开奖号码总和超出范围3-18!";
				return $ret;
			}
		}
		elseif($GameType == "gamefast11" || $GameType == "gamecan11")
		{
			if($Num1 > 6 || $Num1 < 1)
			{
				$ret = "开奖号码1超出范围1-6!";
				return $ret;
			}
			if($Num2 > 6 || $Num2 < 1)
			{
				$ret = "开奖号码2超出范围1-6!";
				return $ret;
			}
			if($Num1 + $Num2 < 2 || $Num1 + $Num2 > 12)
			{
				$ret = "开奖号码总和超出范围2-12!";
				return $ret;
			}
		}
		elseif($GameType == "gamepk10")
		{
			if($Num1 > 9)
			{
				$ret = "开奖号码1超出范围0-9!必须为期号的尾数!";
				return $ret;
			}
			if(substr($No,-1,1) !=  $Num1)
			{
				$ret = "开奖号码1必须为期号的尾数!";
				return $ret;
			}
			$arrT = explode("|",$KgNo);
			$LastNum = $arrT[count($arrT)-1];
			if($Num2 != $LastNum || $Num2 > 10 || $Num2 < 1)
			{
				$ret = "开奖号码2为开奖号的最后一个号码，范围1-10!";
				return $ret;
			}
		}
		elseif($GameType == "gamegj10")
		{
			if($Num1 > 10)
			{
				$ret = "开奖号码1超出范围1-10!必须为开奖号的首个号码!";
				return $ret;
			}
			$arrT = explode("|",$KgNo);
			if($Num1 != $arrT[0])
			{
				$ret = "开奖号码1必须为第一个官方开奖号!";
				return $ret;
			}
			if($Num2 != $arrT[1])
			{
				$ret = "开奖号码2必须为第二个官方开奖号!";
				return $ret;
			}
			if($Num3 != $arrT[2])
			{
				$ret = "开奖号码3必须为第三个官方开奖号!";
				return $ret;
			}
		}
		return $ret;
	}
	/* 删除游戏记录
	*
	*/
	function RemoveGameLog($act)
	{
		global $db;
    	$tableName = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
    	$No = isset($_POST['no'])?FilterStr($_POST['no']):""; 
    	$arrReturn = array(array());
    	
    	if($tableName == "" || $No == "")
    	{
			$arrReturn[0]["cmd"] = "norecord";
            $arrReturn[0]["msg"] = "参数错误!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
    	}
    	$sql = "select kj,tzpoints from {$tableName} where id = {$No}";
    	$result = $db->query($sql);
        //取得返回记录数
        $RowCount = $db->num_rows($result);
        if($RowCount == 0)
        {
            $arrReturn[0]["cmd"] = "norecord";
            $arrReturn[0]["msg"] = "没有记录!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
        }
        $row=$db->fetch_array($result);
        if($row["tzpoints"] > 0)
        {
			$arrReturn[0]["cmd"] = "norecord";
            $arrReturn[0]["msg"] = "该期已有投注，不能删除!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
        }
        $sql = "delete from {$tableName} where id = {$No}";
        $result = $db->query($sql); 
    	$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		$arrReturn[0]["cmd"] = $act;
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	/* 取开奖赔率和开奖号码
	*
	*/
	function GetGameLogDetail($act)
	{
		global $db;
    	$tableName = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
    	$No = isset($_POST['no'])?FilterStr($_POST['no']):""; 
    	$arrReturn = array(array());
    	
    	if($tableName == "" || $No == "")
    	{
			$arrReturn[0]["cmd"] = "norecord";
            $arrReturn[0]["msg"] = "参数错误!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
    	}
    	$sql = "select kgjg,zjpl from {$tableName} where id = {$No}";
    	$arrRows = array(array());
        $result = $db->query($sql);
        //取得返回记录数
        $RowCount = $db->num_rows($result);
        if($RowCount == 0)
        {
            $arrReturn[0]["cmd"] = "norecord";
            $arrReturn[0]["msg"] = "没有记录!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
        }
        $row=$db->fetch_array($result);
        $arrRows[1]["kgNo"] = $row["kgjg"];
        $arrRows[1]["kjpl"] = $row["zjpl"];
        //取标准赔率
        $sql = "select game_std_odds from game_config where game_table_prefix = '{$tableName}'";
        $result = $db->query($sql);
        if($row=$db->fetch_array($result))
        {
			$arrRows[1]["stdpl"] = $row["game_std_odds"];
        }
        
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = "ok";
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
	}
	/* 取游戏开奖结果记录
	*
	*/
	function GetGameStatsLog($act)
	{
		global $db;
    	$tableName = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
    	$LogType = isset($_POST['logtype'])?FilterStr($_POST['logtype']):"0";
    	$No = isset($_POST['no'])?FilterStr($_POST['no']):""; 
    	$Status = isset($_POST['status'])?FilterStr($_POST['status']):"-1";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $arrReturn = array(array());
        
        if($tableName == "")
        {
			$arrReturn[0]["cmd"] = "norecord";
            $arrReturn[0]["msg"] = "请选择游戏!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
        }
        
        $sqlCount = "select Count(*) ";
        $sqlCol = "SELECT id,kgtime,kgjg,kj,tznum,tzpoints,zjrnum,zdtz,zdtz_r,zdtz_points,zdtz_rpoints,sdtz,sdtz_points,game_tax,user_tzpoints,user_winpoints,take_time_remark
					";
        $sqlFrom = " FROM {$tableName}
					WHERE 1=1
					";
        $sqlWhere = "";
        $sqlOrder = "";
        $sql = "";
        //页大小
        $PageSize = isset($_POST['PageSize'])?$_POST['PageSize']:20;
        $PageSize = intval($PageSize);
        //页码
        $page = isset($_POST['Page'])?$_POST['Page']:1;
        $page =intval($page);
        
        if($No != "")
        	$sqlWhere .= " and id={$No}";
        
        if($Status != "-1")
        	$sqlWhere .= " and kj = " . $Status;
        
        //时间
        $TimeField = "kgtime";
        $sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
        
        if($LogType == "1") //取最近
        	$sqlWhere = " and kgtime < DATE_ADD(NOW(),INTERVAL 15 MINUTE)";
        //取得排序
        $sqlOrder = (($Order == "") ? "" : " order by {$Order} {$OrderType}");
        //取得总记录数
        $TotalCount = $db->GetRecordCount($sqlCount.$sqlFrom.$sqlWhere);
        //取记录
        $sql = $sqlCol . $sqlFrom . $sqlWhere . $sqlOrder . GetLimit($page,$PageSize);

        //WriteLog($sql);
        //return;

        $RowCount = 0;
        $arrRows = array(array());
        $result = $db->query($sql);
        //取得返回记录数
        $RowCount = $db->num_rows($result);
        if($RowCount == 0)
        {
            $arrReturn[0]["cmd"] = "norecord";
            $arrReturn[0]["msg"] = "没有记录!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
        }
        $Total_zjrnum = 0;
        $Total_tzpoints = 0;
        $Total_game_tax = 0;
        $Total_user_tzpoints = 0;
        $Total_user_winpoints = 0;
        $theGameType = GetGameTypeFromName($tableName);
        for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
        {    	
            //对返回数据进行包装
            $arrRows[$i]["NO"] = $row["id"];
            $arrRows[$i]["kgtime"] = date("m-d H:i:s",strtotime($row["kgtime"]));
            $arrRows[$i]["kgjg"] = $row["kgjg"];
            $arrRows[$i]["zjrnum"] = $row["zjrnum"] . "/" . ($row["zdtz"] + $row["zdtz_r"] + $row["sdtz"]);
            $arrRows[$i]["tzpoints"] = Trans($row["tzpoints"]);
            $arrRows[$i]["game_tax"] = Trans($row["game_tax"]);
            $arrRows[$i]["user_tzpoints"] = Trans($row["user_tzpoints"]);
            $arrRows[$i]["user_winpoints"] = Trans($row["user_winpoints"]);
            $arrRows[$i]["zd_count"] = $row["zdtz"] . "/" . $row["zdtz_r"]; 
            $arrRows[$i]["zd_point"] = Trans($row["zdtz_points"]) . "/" . Trans($row["zdtz_rpoints"]);
            $arrRows[$i]["take_time_remark"] = $row["take_time_remark"];
            $arrRows[$i]["sd_cnt_point"] = $row["sdtz"] . "/" . Trans($row["sdtz_points"]);
            
            $viewResult = "<a class='edi' href='game_detail.php?gametype={$tableName}&no={$row['id']}'>开奖赔率</a>";
            if($row['kj'] == 1)
            	$viewResult .= "&nbsp;|&nbsp;" . "<a class='edi' href=\"user_gamelog.php?gametype={$tableName}&no={$row['id']}\">开奖记录</a>";
            else     
            	//$viewResult .= "&nbsp;|&nbsp;" . "<a class='edi' href=\"user_kg_allgamelog.php?gametype={$theGameType}&no={$row['id']}\">投注记录</a>";
				$viewResult .= "&nbsp;|&nbsp;" . "<a class='edi' href=\"user_kg_allgamelog.php?gametype={$tableName}&no={$row['id']}\">投注记录</a>";
				
				
            $arrRows[$i]["viewresult"] = $viewResult;
            
            $opr = "<a style='cursor:pointer' title='删除' onclick=\"RemoveGameLog({$row['id']},'{$tableName}')\">删除</a>";
            if($row['kj'] == 0)
            {
            	$opr .= "&nbsp;|&nbsp;" . "<a style='cursor:pointer' title='撤销下注' onclick=\"CancelGamePress({$row['id']},'{$tableName}')\">撤销下注</a>";
            	/* if($tableName == "gamefast28" || $tableName == "gamefast16" || $tableName == "gamefast11" || $tableName == "gamefast10" || $tableName == "gamefast22" || $tableName == "gamefast36" || $tableName == "gamefastgyj")
            	{
            		$opr .= "&nbsp;|&nbsp;" . "<a class='edi' title='手动开奖' href=\"game_open.php?no={$row['id']}&gametype={$tableName}\">手动开奖</a>";
				}
				else
				{
					$opr .= "&nbsp;|&nbsp;" . "<a style='cursor:pointer' title='手动采集' onclick=\"ManualCatchResult({$row['id']},'{$tableName}')\">手动采集</a>";
					$opr .= "&nbsp;|&nbsp;" . "<a style='cursor:pointer' title='手动开奖' onclick=\"ManualOpenGame({$row['id']},'{$tableName}')\">手动开奖</a>";
				} */
				
				$opr .= "&nbsp;|&nbsp;" . "<a class='edi' title='手动开奖' href=\"game_open.php?no={$row['id']}&gametype={$tableName}\">手动开奖</a>";
			}
            $arrRows[$i]["opr"] = $opr;
            
            $Total_zjrnum += $row["zjrnum"]; 
	        $Total_tzpoints += $row["tzpoints"]; 
	        $Total_game_tax += $row["game_tax"]; 
	        $Total_user_tzpoints += $row["user_tzpoints"]; 
	        $Total_user_winpoints += $row["user_winpoints"]; 
        }
        if($RowCount > 1)
        {
        	$i = $RowCount + 1;
			$arrRows[$i]["NO"] = "";
            $arrRows[$i]["kgtime"] = ""; 
            $arrRows[$i]["kgjg"] = "页小计"; 
            $arrRows[$i]["zjrnum"] = Trans($Total_zjrnum);
            $arrRows[$i]["tzpoints"] = Trans($Total_tzpoints);   
            $arrRows[$i]["game_tax"] = Trans($Total_game_tax);   
            $arrRows[$i]["user_tzpoints"] = Trans($Total_user_tzpoints);   
            $arrRows[$i]["user_winpoints"] = Trans($Total_user_winpoints);   
            $arrRows[$i]["zd_count"] = "";
            $arrRows[$i]["zd_point"] = "";
            $arrRows[$i]["sd_cnt_point"] = "";
            $arrRows[$i]["take_time_remark"] = "";
            $arrRows[$i]["viewresult"] = ""; 
            $arrRows[$i]["opr"] = "";
        }
        
        //返回分页
        require_once('inc/fenye.php');
        $ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
        $pageInfo = $ajaxpage->show();
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
    }
	/* 取游戏列表类型option
	*
	*/
	function GetGameTypeOption($act)
	{
		global $db;
		$sql = "select game_table_prefix,game_name from game_config order by game_name";
		$result = $db->query($sql);
		$option = "";
		while($row=$db->fetch_array($result))
		{
			$option .= "<option value='{$row['game_table_prefix']}'>{$row['game_name']}</option>";
		}
		$arrReturn = array(array());
		$arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = $option;
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 保存游戏设置
	*
	*/
	function SaveGameConfig($act)
	{
		global $db;
		$GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"-1";
		$GameName = isset($_POST['GameName'])?FilterStr($_POST['GameName']):"";
		$JLExp = intval($_POST['JLExp']);//isset($_POST['JLExp'])?FilterStr($_POST['JLExp']):"";
		$MaxExp = intval($_POST['MaxExp']);//isset($_POST['MaxExp'])?FilterStr($_POST['MaxExp']):"";
		$GoSamples = intval($_POST['GoSamples']);//isset($_POST['GoSamples'])?FilterStr($_POST['GoSamples']):"";
		$JLExpVIP = intval($_POST['JLExpVIP']);//isset($_POST['JLExpVIP'])?FilterStr($_POST['JLExpVIP']):"";
		$MaxExpVIP = intval($_POST['MaxExpVIP']);//isset($_POST['MaxExpVIP'])?FilterStr($_POST['MaxExpVIP']):"";
		$KjDelay = intval($_POST['KjDelay']);//isset($_POST['KjDelay'])?FilterStr($_POST['KjDelay']):"";
		$SysWinOdds = intval($_POST['SysWinOdds']);//isset($_POST['SysWinOdds'])?FilterStr($_POST['SysWinOdds']):"";
		$SysWinMin = intval($_POST['SysWinMin']);//isset($_POST['SysWinMin'])?FilterStr($_POST['SysWinMin']):"";
		$SysWinMax = intval($_POST['SysWinMax']);//isset($_POST['SysWinMax'])?FilterStr($_POST['SysWinMax']):"";
		$NoOpenNum = intval($_POST['NoOpenNum']);//isset($_POST['NoOpenNum'])?FilterStr($_POST['NoOpenNum']):"";
		$TzClose = intval($_POST['TzClose']);//isset($_POST['TzClose'])?FilterStr($_POST['TzClose']):"";
		$GCTzExp = intval($_POST['GCTzExp']);//isset($_POST['GCTzExp'])?FilterStr($_POST['GCTzExp']):"";
		$PressMin = intval($_POST['PressMin']);//isset($_POST['PressMin'])?FilterStr($_POST['PressMin']):"";
		$PressMax = intval($_POST['PressMax']);//isset($_POST['PressMax'])?FilterStr($_POST['PressMax']):"";
		$StdOdds = isset($_POST['StdOdds'])?FilterStr($_POST['StdOdds']):"";
		$STdPress = isset($_POST['STdPress'])?FilterStr($_POST['STdPress']):"";
		$GameModel = isset($_POST['GameModel'])?FilterStr($_POST['GameModel']):"";
		$GameOpenFlag = intval($_POST['gameopenflag']);
		$GameShutdownReason = isset($_POST['shutdownreason'])?FilterStr($_POST['shutdownreason']):"";
		
		$arrReturn = array(array());
		
		$STdPress = str_replace("，",",",$STdPress);
		
		if(count(explode("|",$StdOdds)) != count(explode(",",$STdPress)) )
		{
			$arrReturn[0]["cmd"] = "err";
	        $arrReturn[0]["msg"] = "标准赔率和标准投注额的个数必须一致";
	        ArrayChangeEncode($arrReturn);
	        echo json_encode($arrReturn);
	        return;
		}
		$GameName = ChangeEncodeU2G($GameName);
		$GameShutdownReason = ChangeEncodeU2G($GameShutdownReason);
		$sql = "update game_config set game_name = '{$GameName}'," . "
						game_go_samples = {$GoSamples},
						game_tz_exp = {$GCTzExp},
						game_jl_exp = {$JLExp},
						game_jl_maxexp = {$MaxExp},
						game_jl_exp_vip = {$JLExpVIP},
						game_jl_maxexp_vip = {$MaxExpVIP},
						game_press_min = {$PressMin},
						game_press_max = {$PressMax},
						game_std_odds = '{$StdOdds}',
						game_std_press = '{$STdPress}',
						game_kj_delay = {$KjDelay},
						game_tz_close = {$TzClose},
						game_sys_win_min = {$SysWinMin},
						game_sys_win_max = {$SysWinMax},
						game_sys_win_odds = {$SysWinOdds},
						game_noopen_num = {$NoOpenNum},
						game_model = '{$GameModel}',
						isstop = {$GameOpenFlag},
						stop_msg = '{$GameShutdownReason}'
		        where game_type = {$GameType}
				";
		//WriteLog($sql);
		$result = $db->query($sql);
		$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		$arrReturn[0]["cmd"] = $act;
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	/* 取游戏设置
	*
	*/
	function GetGameConfig($act)
	{
		global $db;
		$GameType = intval($_POST['gametype']);
		$arrReturn = array(array());
		
		$sql = "select * from game_config where game_type = '{$GameType}'";
		$result = $db->query($sql);
		if($row=$db->fetch_array($result))
		{
			$arrReturn[1]["RecID"] = $row["id"];
			$arrReturn[1]["GameType"] = $row["game_type"];
			$arrReturn[1]["TablePrefix"] = $row["game_table_prefix"];
			$arrReturn[1]["GameName"] = $row["game_name"];
			$arrReturn[1]["JLExp"] = $row["game_jl_exp"];
			$arrReturn[1]["MaxExp"] = $row["game_jl_maxexp"];
			$arrReturn[1]["GoSamples"] = $row["game_go_samples"];
			$arrReturn[1]["JLExpVIP"] = $row["game_jl_exp_vip"];
			$arrReturn[1]["MaxExpVIP"] = $row["game_jl_maxexp_vip"];
			$arrReturn[1]["KjDelay"] = $row["game_kj_delay"];
			$arrReturn[1]["SysWinOdds"] = $row["game_sys_win_odds"];
			$arrReturn[1]["SysWinMin"] = $row["game_sys_win_min"];
			$arrReturn[1]["SysWinMax"] = $row["game_sys_win_max"];
			$arrReturn[1]["TzClose"] = $row["game_tz_close"];
			$arrReturn[1]["TzExp"] = $row["game_tz_exp"];
			$arrReturn[1]["PressMin"] = $row["game_press_min"];
			$arrReturn[1]["PressMax"] = $row["game_press_max"];
			$arrReturn[1]["StdOdds"] = $row["game_std_odds"];
			$arrReturn[1]["STdPress"] = $row["game_std_press"];
			$arrReturn[1]["GameModel"] = $row["game_model"]; 
			$arrReturn[1]["NoOpenNum"] = $row["game_noopen_num"];
			$arrReturn[1]["NextOpenFlag"] = ($row["game_open_flag"] == 0) ? "未设置":"已设置"; 
			$arrReturn[1]["game_open_flag"] = $row["isstop"];
			$arrReturn[1]["game_shutdown_reason"] = $row["stop_msg"];
		}
		
		$arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = "ok";
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 取游戏列表option
	*
	*/
	function GetGameListOption($act)
	{
		global $db;
		$sql = "select game_type,game_name from game_config order by game_name";
		$result = $db->query($sql);
		$option = "";
		while($row=$db->fetch_array($result))
		{
			$option .= "<option value='{$row['game_type']}'>{$row['game_name']}</option>";
		}
		$arrReturn = array(array());
		$arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = $option;
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
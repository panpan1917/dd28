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
	    	case "get_stats_daylog": //按天统计
	    		GetStatsDayLog($act);
	    		break;
	    	case "doubtuser_remark": // 备注可疑用户
	    		login_check( "users" );
				RemarkDoubtUser();
				break;
			case "remove_doubtuser": // 移出可疑用户
				login_check( "users" );
				RemoveDoubtUser();
				break;
			case "get_negative_user": // 取负分用户
				GetNegativeUser($act);
				break;
			case "get_doubt_user": // 取可疑用户
				GetDoubtUser($act);
				break;
	    	case "get_admintranslog": //取管理员充值记录
                GetAdminTransLog($act);
                break;
			case "admin_trans": // 管理员充值
				login_check( "system" );
                AdminTransToUser();
                break;
	    	case "check_user_at": // 检测用户状态
                CheckUserInfo($act);
                break;
	    	case "get_changelog": // 取中央银行调度记录
                GetCenterItemChangeLog($act);
                break;
	    	case "change_center_item": // 加减中央银行子帐号
	    		login_check( "system" );
                ChangeCenterBankItem();
                break;
	    	case "get_cc_info"://  中央银行实时信息
                GetCenterbankInfo($act);
                break;
	    	case "get_sh_info"://  中央银行历史信息
                GetCenterbankHistoryInfo($act);
                break;
            case "get_sc_info"://  中央银行实时信息
                GetCenterbankInfo($act);
                break;
	        default:
	            exit;
	    }
	}
	
	/* 按天统计
	*
	*/ 
	function GetStatsDayLog($act)
	{
		global $db;
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        
        $sqlCount = "select Count(*) ";
        $sqlCol = "SELECT *
					";
        $sqlFrom = " FROM webtj
					WHERE 1=1
					";
        $sqlWhere = "";
        $sqlOrder = "";
        $sql = "";
        //页大小
        $PageSize = isset($_POST['PageSize'])?$_POST['PageSize']:30;
        $PageSize = intval($PageSize);
        //页码
        $page = isset($_POST['Page'])?$_POST['Page']:1;
        $page =intval($page);

        $arrReturn = array(array());
        //取得查询条件 
        //时间
        $TimeField = "time";
        $sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
        
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
        $l_RegNum = 0;
		$l_ForumNum = 0;
		$l_OnlineNum = 0;
        $l_RegPoints = 0;
        $l_JJPoints = 0;
        $l_Rebate = 0;
        $l_ExchangePoints = 0;
        $l_CashFee = 0;
        $l_Card = 0;
        $l_PayOnline = 0;
		$l_Give_cz_point = 0;
        $l_TransTax = 0;
        $l_GameTax = 0;
        $l_game11 = 0;
        $l_game16 = 0;
        $l_game28 = 0;
        $l_game36 = 0;
        $l_gameww = 0;
        $l_gamedw = 0;
        
        $l_gamebj11 = 0;
        $l_gamebj16 = 0;
        $l_gameself28 = 0;
        $l_gamebj36 = 0;
        $l_gamebjdw = 0;
        $l_gamebjww = 0;
        
        $l_gamecan28 = 0;
        $l_gamecan16 = 0;
        $l_gamecan11 = 0;
        $l_gamecan36 = 0;
        $l_gamecanww = 0;
        $l_gamecandw = 0;
        
        
        $l_gamefast28 = 0;
        $l_gamefast16 = 0;
        $l_gamefast10 = 0;
        $l_gamefast11 = 0;
        $l_gamefast22 = 0;
        $l_gamefast36 = 0;
        $l_gamefastgyj = 0;
        
        $l_gamepk10 = 0;
        $l_gamepk22 = 0;
        $l_gamepklh = 0;
        $l_gamegj10 = 0;
        $l_gamepkgyj = 0;
        $l_gamepksc = 0;
        
        $l_gamehg28 = 0;
        $l_gamehg16 = 0;
        $l_gamehg11 = 0;
        $l_gamehg36 = 0;
        $l_gamehgww = 0;
        $l_gamehgdw = 0;
        
        $l_game28gd = 0;
        $l_gamebj28gd = 0;
        $l_gamehg28gd = 0;
        $l_gamecan28gd = 0;
        $l_gamexync = 0;
        $l_gamecqssc = 0;
        
        $l_gameairship10 = 0;
        $l_gameairship22 = 0;
        $l_gameairshiplh = 0;
        $l_gameairshipgj10 = 0;
        $l_gameairshipgyj = 0;
        
        $day_gamewinlose = 0;
        $l_gamewinlost = 0;
        $l_xxtg=0;
        for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
        {  
            //对返回数据进行包装
            $arrRows[$i]["LogDate"] = date("m-d",strtotime($row["time"]));
            $arrRows[$i]["RegNum"] = $row["regnum"];
			$arrRows[$i]["ForumNum"] = $row["forumnum"];
            $sql='select count(id) as c from users where source=1 and DATE_FORMAT(TIME,\'%Y-%m-%d\')=\''.$row['time'].'\'';
            $arrRows[$i]['xxtg']=$db->GetRecordCount($sql);
			$arrRows[$i]["OnlineNum"] = $row["onlinenum"];
            $arrRows[$i]["RegPoints"] = Trans($row["regpoints"]); //注册送分
            $arrRows[$i]["Rebate"] = Trans($row["rebate"]); //返水分
            $arrRows[$i]["ExchangePoints"] = Trans($row["exchangepoints"]);//提现分
            $arrRows[$i]["CashFee"] = Trans($row["cashfee"]);//提现手续费
            $arrRows[$i]["Card"] = Trans($row["pack"]); //红包统计分
            $arrRows[$i]["PayOnline"] = Trans($row["payonline"]);//充值统计分
			$arrRows[$i]["Give_cz_point"] = Trans($row["give_cz_point"]);//充值送分
            $arrRows[$i]["TransTax"] = Trans($row["tgpoints"]); //推广收益分
            $arrRows[$i]["GameTax"] = Trans($row["gametax"]);
            
            $arrRows[$i]["game11"] = Trans($row["game11"]);
            $arrRows[$i]["game16"] = Trans($row["game16"]);
            $arrRows[$i]["game28"] = Trans($row["game28"]);
            $arrRows[$i]["game36"] = Trans($row["game36"]);
            $arrRows[$i]["gameww"] = Trans($row["gameww"]);
            $arrRows[$i]["gamedw"] = Trans($row["gamedw"]);
            
            $arrRows[$i]["gamebj11"] = Trans($row["gamebj11"]);
            $arrRows[$i]["gamebj16"] = Trans($row["gamebj16"]);
            $arrRows[$i]["gameself28"] = Trans($row["gameself28"]);
            $arrRows[$i]["gamebj36"] = Trans($row["gamebj36"]);
            $arrRows[$i]["gamebjww"] = Trans($row["gamebjww"]);
            $arrRows[$i]["gamebjdw"] = Trans($row["gamebjdw"]);
            
            $arrRows[$i]["gamecan28"] = Trans($row["gamecan28"]);
            $arrRows[$i]["gamecan16"] = Trans($row["gamecan16"]);
            $arrRows[$i]["gamecan11"] = Trans($row["gamecan11"]);
            $arrRows[$i]["gamecan36"] = Trans($row["gamecan36"]);
            $arrRows[$i]["gamecanww"] = Trans($row["gamecanww"]);
            $arrRows[$i]["gamecandw"] = Trans($row["gamecandw"]);
            
            $arrRows[$i]["gamefast28"] = Trans($row["gamefast28"]);
            $arrRows[$i]["gamefast16"] = Trans($row["gamefast16"]);
            $arrRows[$i]["gamefast11"] = Trans($row["gamefast11"]);
            $arrRows[$i]["gamefast10"] = Trans($row["gamefast10"]);
            $arrRows[$i]["gamefast22"] = Trans($row["gamefast22"]);
            $arrRows[$i]["gamefast36"] = Trans($row["gamefast36"]);
            $arrRows[$i]["gamefastgyj"] = Trans($row["gamefastgyj"]);
            
            $arrRows[$i]["gamepk22"] = Trans($row["gamepk22"]);
            $arrRows[$i]["gamepklh"] = Trans($row["gamepklh"]);
            $arrRows[$i]["gamepk10"] = Trans($row["gamepk10"]);
            $arrRows[$i]["gamegj10"] = Trans($row["gamegj10"]);
            $arrRows[$i]["gamepkgyj"] = Trans($row["gamepkgyj"]);
            $arrRows[$i]["gamepksc"] = Trans($row["gamepksc"]);
            
            $arrRows[$i]["gamehg28"] = Trans($row["gamehg28"]);
            $arrRows[$i]["gamehg16"] = Trans($row["gamehg16"]);
            $arrRows[$i]["gamehg11"] = Trans($row["gamehg11"]);
            $arrRows[$i]["gamehg36"] = Trans($row["gamehg36"]);
            $arrRows[$i]["gamehgww"] = Trans($row["gamehgww"]);
            $arrRows[$i]["gamehgdw"] = Trans($row["gamehgdw"]);

            $arrRows[$i]["game28gd"] = Trans($row["game28gd"]);
            $arrRows[$i]["gamebj28gd"] = Trans($row["gamebj28gd"]);
            $arrRows[$i]["gamehg28gd"] = Trans($row["gamehg28gd"]);
            $arrRows[$i]["gamecan28gd"] = Trans($row["gamecan28gd"]);
            $arrRows[$i]["gamexync"] = Trans($row["gamexync"]);
            $arrRows[$i]["gamecqssc"] = Trans($row["gamecqssc"]);
            
            
            $arrRows[$i]["gameairship22"] = Trans($row["gameairship22"]);
            $arrRows[$i]["gameairshiplh"] = Trans($row["gameairshiplh"]);
            $arrRows[$i]["gameairship10"] = Trans($row["gameairship10"]);
            $arrRows[$i]["gameairshipgj10"] = Trans($row["gameairshipgj10"]);
            $arrRows[$i]["gameairshipgyj"] = Trans($row["gameairshipgyj"]);
            
            
            $day_gamewinlose =  $row["gamebj11"] + $row["gamebj16"] + $row["gameself28"] + $row["gamebj28gd"]+ $row["gamebj36"]+ $row["gamebjww"]+ $row["gamebjdw"]
            				+ $row["game11"] + $row["game16"]+ $row["game28"]+ $row["game28gd"]+ $row["game36"]+ $row["gameww"]+ $row["gamedw"]
            				+ $row["gamepkgyj"]+ $row["gamepklh"]+ $row["gamepk10"]+ $row["gamepk22"]+ $row["gamegj10"]+ $row["gamepksc"]
            				+ $row["gamecan28"]+ $row["gamecan28gd"]+ $row["gamecan16"]+ $row["gamecan11"]+ $row["gamecan36"]+ $row["gamecanww"]+ $row["gamecandw"]
            				+ $row["gamefastgyj"]+ $row["gamefast36"]+ $row["gamefast28"]+ $row["gamefast22"]+ $row["gamefast16"]+ $row["gamefast11"]+ $row["gamefast10"]
            				+ $row["gamehg28"]+ $row["gamehg28gd"]+ $row["gamehg16"]+ $row["gamehg11"]+ $row["gamehg36"]+ $row["gamehgww"]+ $row["gamehgdw"]
            				+ $row["gamexync"]+ $row["gamecqssc"]
            				+ $row["gameairshipgyj"]+ $row["gameairshiplh"]+ $row["gameairship10"]+ $row["gameairship22"]+ $row["gameairshipgj10"]
            				;
            $arrRows[$i]["gamewinlose"] = Trans($day_gamewinlose);
            
            $l_RegNum += $row["regnum"];
			$l_ForumNum += $row["forumnum"];
			$l_OnlineNum += $row["onlinenum"];
            $l_RegPoints += $row["regpoints"];
            $l_Rebate += $row["rebate"];//$row["jjpoints"];
            $l_ExchangePoints += $row["exchangepoints"];
            $l_CashFee += $row["cashfee"];
            $l_Card += $row["pack"];
            $l_PayOnline += $row["payonline"];
			$l_Give_cz_point += $row["give_cz_point"];
            $l_TransTax += $row["tgpoints"];
            $l_GameTax += $row["gametax"];
            $l_game11 += $row["game11"];
            $l_game16 += $row["game16"];
            $l_game28 += $row["game28"];
            $l_gameww += $row["gameww"];
            $l_gamedw += $row["gamedw"];
            $l_gamebj11 += $row["gamebj11"];
            $l_gamebj16 += $row["gamebj16"];
            $l_gamepk10 += $row["gamepk10"];
            $l_gamegj10 += $row["gamegj10"];
            $l_gamecan28 += $row["gamecan28"];
            $l_gamecan16 += $row["gamecan16"];
            $l_gamecan11 += $row["gamecan11"];
            $l_gamecanww += $row["gamecanww"];
            $l_gamecandw += $row["gamecandw"];
            $l_gameself28 += $row["gameself28"];
            $l_gamefast28 += $row["gamefast28"];
            $l_gamefast16 += $row["gamefast16"];
            $l_gamefast11 += $row["gamefast11"];
            $l_gamefast22 += $row["gamefast22"];
            $l_gamefast36 += $row["gamefast36"];
            $l_gamefastgyj += $row["gamefastgyj"];
            $l_game36 += $row["game36"];
	        $l_gamebj36 += $row["gamebj36"];
	        $l_gamebjdw += $row["gamebjdw"];
	        $l_gamebjww += $row["gamebjww"];
	        $l_gamecan36 += $row["gamecan36"];
	        $l_gamepk22 += $row["gamepk22"];
	        $l_gamefast10 += $row["gamefast10"];
	        $l_gamepklh += $row["gamepklh"];
	        $l_gamepkgyj += $row["gamepkgyj"];
	        $l_gamepksc += $row["gamepksc"];
	        $l_gamehg28 += $row["gamehg28"];
	        $l_gamehg16 += $row["gamehg16"];
	        $l_gamehg11 += $row["gamehg11"];
	        $l_gamehg36 += $row["gamehg36"];
	        $l_gamehgww += $row["gamehgww"];
	        $l_gamehgdw += $row["gamehgdw"];
	        $l_game28gd += $row["game28gd"];
	        $l_gamebj28gd += $row["gamebj28gd"];
	        $l_gamehg28gd += $row["gamehg28gd"];
	        $l_gamecan28gd += $row["gamecan28gd"];
	        $l_gamexync += $row["gamexync"];
	        $l_gamecqssc += $row["gamecqssc"];
	        
	        $l_gameairship10 += $row["gameairship10"];
	        $l_gameairship22 += $row["gameairship22"];
	        $l_gameairshipgj10 += $row["gameairshipgj10"];
	        $l_gameairshiplh += $row["gameairshiplh"];
	        $l_gameairshipgyj += $row["gameairshipgyj"];
	        
	        $l_gamewinlost += $day_gamewinlose;
            $l_xxtg+=$arrRows[$i]['xxtg'];
        }
        if($RowCount > 1)
        {
        	$i = $RowCount + 1;
			$arrRows[$i]["LogDate"] = "页小计";
            $arrRows[$i]["RegNum"] = $l_RegNum;
			$arrRows[$i]["ForumNum"] = $l_ForumNum;
			$arrRows[$i]["OnlineNum"] = $l_OnlineNum;
            $arrRows[$i]["RegPoints"] = Trans($l_RegPoints);
            $arrRows[$i]["Rebate"] = Trans($l_Rebate);
            $arrRows[$i]["ExchangePoints"] = Trans($l_ExchangePoints);
            $arrRows[$i]["CashFee"] = Trans($l_CashFee);
            $arrRows[$i]["Card"] = Trans($l_Card);
            $arrRows[$i]["PayOnline"] = Trans($l_PayOnline);
			$arrRows[$i]["Give_cz_point"] = Trans($l_Give_cz_point);
            $arrRows[$i]["TransTax"] = Trans($l_TransTax);
            $arrRows[$i]["GameTax"] = Trans($l_GameTax);
            $arrRows[$i]["game11"] = Trans($l_game11);
            $arrRows[$i]["game16"] = Trans($l_game16);
            $arrRows[$i]["game28"] = Trans($l_game28);
            $arrRows[$i]["gameww"] = Trans($l_gameww);
            $arrRows[$i]["gamedw"] = Trans($l_gamedw);
            $arrRows[$i]["gamebj11"] = Trans($l_gamebj11);
            $arrRows[$i]["gamebj16"] = Trans($l_gamebj16);
            $arrRows[$i]["gamepk10"] = Trans($l_gamepk10);
            $arrRows[$i]["gamegj10"] = Trans($l_gamegj10);
            $arrRows[$i]["gamecan28"] = Trans($l_gamecan28);
            $arrRows[$i]["gamecan16"] = Trans($l_gamecan16);
            $arrRows[$i]["gamecan11"] = Trans($l_gamecan11);
            $arrRows[$i]["gamecanww"] = Trans($l_gamecanww);
            $arrRows[$i]["gamecandw"] = Trans($l_gamecandw);
            $arrRows[$i]["gameself28"] = Trans($l_gameself28);
            $arrRows[$i]["gamefast28"] = Trans($l_gamefast28);
            $arrRows[$i]["gamefast16"] = Trans($l_gamefast16);
            $arrRows[$i]["gamefast11"] = Trans($l_gamefast11);
            $arrRows[$i]["gamefast22"] = Trans($l_gamefast22);
            $arrRows[$i]["gamefast36"] = Trans($l_gamefast36);
            $arrRows[$i]["gamefastgyj"] = Trans($l_gamefastgyj);
            $arrRows[$i]["game36"] = Trans($l_game36);
            $arrRows[$i]["gamebj36"] = Trans($l_gamebj36);
            $arrRows[$i]["gamebjdw"] = Trans($l_gamebjdw);
            $arrRows[$i]["gamebjww"] = Trans($l_gamebjww);
            $arrRows[$i]["gamecan36"] = Trans($l_gamecan36);
            $arrRows[$i]["gamepk22"] = Trans($l_gamepk22);
            $arrRows[$i]["gamefast10"] = Trans($l_gamefast10);
            $arrRows[$i]["gamepklh"] = Trans($l_gamepklh);
            $arrRows[$i]["gamepkgyj"] = Trans($l_gamepkgyj);
            $arrRows[$i]["gamepksc"] = Trans($l_gamepksc);
            $arrRows[$i]["gamehg28"] = Trans($l_gamehg28);
            $arrRows[$i]["gamehg16"] = Trans($l_gamehg16);
            $arrRows[$i]["gamehg11"] = Trans($l_gamehg11);
            $arrRows[$i]["gamehg36"] = Trans($l_gamehg36);
            $arrRows[$i]["gamehgww"] = Trans($l_gamehgww);
            $arrRows[$i]["gamehgdw"] = Trans($l_gamehgdw);
            
            $arrRows[$i]["game28gd"] = Trans($l_game28gd);
            $arrRows[$i]["gamebj28gd"] = Trans($l_gamebj28gd);
            $arrRows[$i]["gamehg28gd"] = Trans($l_gamehg28gd);
            $arrRows[$i]["gamecan28gd"] = Trans($l_gamecan28gd);
            $arrRows[$i]["gamexync"] = Trans($l_gamexync);
            $arrRows[$i]["gamecqssc"] = Trans($l_gamecqssc);
            
            $arrRows[$i]["gameairship10"] = Trans($l_gameairship10);
            $arrRows[$i]["gameairship22"] = Trans($l_gameairship22);
            $arrRows[$i]["gameairshipgj10"] = Trans($l_gameairshipgj10);
            $arrRows[$i]["gameairshipgyj"] = Trans($l_gameairshipgyj);
            $arrRows[$i]["gameairshiplh"] = Trans($l_gameairshiplh);
            
            $arrRows[$i]["gamewinlose"] = Trans($l_gamewinlost);
            $arrRows[$i]["xxtg"] = Trans($l_xxtg);
        }
        
        //返回分页
        require_once('inc/fenye.php');
        $ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page_SD','nowindex' => $page));
        $pageInfo = $ajaxpage->show();
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
    }
	/* 备注可疑用户
	*
	*/
	function RemarkDoubtUser()
	{
		global $db;
		$ID = isset($_POST['id'])?FilterStr($_POST['id']):"0";
		$Remark = isset($_POST['remark'])?FilterStr($_POST['remark']):"";
		$AdminIdx = $_SESSION["Admin_UserID"];
		$sql = "update  doubt_user set remark='{$Remark}',opr_user={$AdminIdx},status=1 where id = {$ID}";
		//WriteLog($sql);
		$result = $db->query($sql);
        $msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
        $arrReturn[0]["cmd"] = "remarkdoubtuser";
        $arrReturn[0]["msg"] = $msg;
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 移除可疑用户
	*
	*/
	function RemoveDoubtUser()
	{
		global $db;
		$ID = isset($_POST['id'])?FilterStr($_POST['id']):"0";
		$sql = "delete from doubt_user where id = {$ID}";
		//WriteLog($sql);
		$result = $db->query($sql);
        $msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
        $arrReturn[0]["cmd"] = "removedoubtuser";
        $arrReturn[0]["msg"] = $msg;
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
		
	}
	/* 取负分用户
	*
	*/
	function GetNegativeUser($act)
	{
		global $db;
    	$MemberIdx = isset($_POST['memberidx'])?FilterStr($_POST['memberidx']):"";
    	$Kind = isset($_POST['kind'])?FilterStr($_POST['kind']):"0";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        
        $sqlCount = "select Count(*) ";
        $sqlCol = "SELECT * 
					";
        $sqlFrom = " FROM 
        			(
						SELECT id,nickname,points,back,lock_points,logintime,loginip,
							CASE WHEN points < 0 THEN 1
								 WHEN back < 0 THEN 2
								 WHEN lock_points < 0 THEN 3
								 ELSE -1
							END AS negativetype
						FROM users
						WHERE points < 0 OR back < 0 OR lock_points < 0
						) AS a
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

        $arrReturn = array(array());
        //取得查询条件
        if($MemberIdx != "")
        	$sqlWhere .= " and id = {$MemberIdx}";
        if($Kind != "0")
        	$sqlWhere .= " and negativetype = {$Kind}";
        
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
        for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
        {  
            //对返回数据进行包装
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?memberidx={$row["id"]}")."' target='_blank'>{$row["id"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            
            $l_Kind = "";
            switch($row["negativetype"])
            {
				case 1:
					$l_Kind = "可用分";
					break;
				case 2:
					$l_Kind = "银行分";
					break;
				case 3:
					$l_Kind = "投注分";
					break;
				default:
					$l_Kind = "未知";
					break;
            }
            $arrRows[$i]["NegativeType"] = $l_Kind;
            $arrRows[$i]["Points"] = Trans($row["points"]);
            $arrRows[$i]["Back"] = Trans($row["back"]);
            $arrRows[$i]["LockPoints"] = Trans($row["lock_points"]);
            $arrRows[$i]["LastLogin"] = $row["logintime"];
            $arrRows[$i]["LoginIP"] = "<a href='http://www.ip138.com/ips.asp?ip={$row["loginip"]}' target='_blank'>". $row["loginip"] ."</a>" .
                                         "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["loginip"]}")."' target='_blank'>批</>";
            
            
        }
        
        //返回分页
        require_once('inc/fenye.php');
        $ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page_NU','nowindex' => $page));
        $pageInfo = $ajaxpage->show();
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
    }
	/* 取可疑用户
	*
	*/
	function GetDoubtUser($act)
	{
		global $db;
    	$MemberIdx = isset($_POST['memberidx'])?FilterStr($_POST['memberidx']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
        
        $sqlCount = "select Count(*) ";
        $sqlCol = "SELECT a.*,b.nickname,c.name
					";
        $sqlFrom = " FROM doubt_user a
					LEFT OUTER JOIN users b
					ON a.uid = b.id
					left outer join admin c
					on a.opr_user = c.id
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

        $arrReturn = array(array());
        //取得查询条件
        if($MemberIdx != "")
        	$sqlWhere .= " and a.uid = {$MemberIdx}";
        if($IsExceptInner == 1)
        	$sqlWhere .= " and a.uid not in(select uid from users_inner)"; 
        //时间
        $TimeField = "a.check_time";
        $sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
        
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
        $Total_Points = 0;
        $Total_Back = 0;
        $Total_LockPoints = 0;
        $Total_SumPoints = 0;
        $Total_AllPoints = 0;
        $Total_DiffPoints = 0;
        for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
        {  
            //对返回数据进行包装
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            $arrRows[$i]["Points"] = Trans($row["points"]);
            $arrRows[$i]["Back"] = Trans($row["back"]);
            $arrRows[$i]["LockPoints"] = Trans($row["lock_points"]);
            $arrRows[$i]["SumPoints"] = Trans($row["points"] + $row["back"]);
            $arrRows[$i]["TotalPoints"] = Trans($row["total_points"]);
            $arrRows[$i]["DiffPoints"] = Trans($row["diff_points"]);
            $arrRows[$i]["CheckTime"] = $row["check_time"];
            $arrRows[$i]["Status"] = ($row["status"] == 0)?"未备注":"已备注";
            $arrRows[$i]["Remark"] = $row["remark"];
            $arrRows[$i]["OprUser"] = $row["name"];
            $opr = "<a style='cursor:pointer' title='删除' onclick='RemoveDoubtUser({$row['id']})'>删除</a>";
            if($row["status"] == 0)
            {
				$opr .= "|" . "<a style='cursor:pointer' title='备注' onclick='ToRemark({$row['id']})'>备注</a>";
				$opr .= "<br>" . "<input type='text' id='{$row['id']}_remark' style='width:130px;border:1px solid #999;background-color:#FFFFCC;' />";
            }
            $arrRows[$i]["Opr"] = $opr;
            
            $Total_Points += $row["points"];
	        $Total_Back += $row["back"];
	        $Total_LockPoints += $row["lock_points"];
	        $Total_SumPoints += $row["points"] + $row["back"];
	        $Total_AllPoints += $row["total_points"];
	        $Total_DiffPoints += $row["diff_points"];
        }
        if($RowCount > 1)
        {
        	$i = $RowCount + 1;
			$arrRows[$i]["UserID"] = "";
            $arrRows[$i]["NickName"] = "页小计:";
            $arrRows[$i]["Points"] = Trans($Total_Points);
            $arrRows[$i]["Back"] = Trans($Total_Back);
            $arrRows[$i]["LockPoints"] = Trans($Total_LockPoints);
            $arrRows[$i]["SumPoints"] = Trans($Total_SumPoints);
            $arrRows[$i]["TotalPoints"] = Trans($Total_AllPoints);
            $arrRows[$i]["DiffPoints"] = Trans($Total_DiffPoints);
            $arrRows[$i]["CheckTime"] = "";
            $arrRows[$i]["Status"] = "";
            $arrRows[$i]["Remark"] = "";
            $arrRows[$i]["OprUser"] = "";
            $arrRows[$i]["Opr"] = "";
        }
        
        //返回分页
        require_once('inc/fenye.php');
        $ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page_DU','nowindex' => $page));
        $pageInfo = $ajaxpage->show();
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
    }
	/* 取管理员充值记录
    *
    */
    function GetAdminTransLog($act)
    {
    	global $db;
    	$SearchType = isset($_POST['type'])?FilterStr($_POST['type']):"1";
    	$SearchWord = isset($_POST['word'])?FilterStr($_POST['word']):"";
    	$OprType = isset($_POST['oprtype'])?FilterStr($_POST['oprtype']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        
        $sqlCount = "select Count(*) ";
        $sqlCol = "SELECT a.opr_time,a.opr_type,a.amount,a.uid,b.nickname,a.opr_user,c.name,a.remark_type,a.reason
                  ";
        $sqlFrom = "FROM admin_translog a
					LEFT OUTER JOIN users b
					ON a.uid = b.id
					LEFT OUTER JOIN admin c
					ON a.opr_user = c.id
					WHERE 1=1 ";
        $sqlWhere = "";
        $sqlOrder = "";
        $sql = "";
        //页大小
        $PageSize = isset($_POST['PageSize'])?$_POST['PageSize']:20;
        $PageSize = intval($PageSize);
        //页码
        $page = isset($_POST['Page'])?$_POST['Page']:1;
        $page =intval($page);

        $arrReturn = array(array());
        //取得查询条件
        if($SearchWord != "")
        	$sqlWhere .= " and a.uid = {$SearchWord}";
        if($OprType != "-1")
        	$sqlWhere .= " and a.opr_type = {$OprType}";
        //时间
        $TimeField = "a.opr_time";
        $sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
        
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
        $Total_Amount = 0;
        for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
        {  
            //对返回数据进行包装
            $arrRows[$i]["OprUser"] = $row["name"];
            $arrRows[$i]["OprTime"] = $row["opr_time"];
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            
            $l_TransType = "";
            switch($row["opr_type"])
            {
				case 0:
					$l_TransType = "充值可用分";
					break;
				case 1:
					$l_TransType = "充值银行分";
					break;
				case 2:
					$l_TransType = "充值经验";
					break;
				case 3:
					$l_TransType = "充值投注分";
					break;
				default:
					$l_TransType = "未知";
					break;
            }
            $arrRows[$i]["TransType"] = $l_TransType;
            $arrRows[$i]["Amount"] = Trans($row["amount"]);
            $arrRows[$i]["RemarkType"] = ($row["remark_type"] == 0) ? "会员充值":"错误填平";
            $arrRows[$i]["Remark"] = $row["reason"];
            $Total_Amount += $row["amount"];
        }
        if($RowCount > 1 && $OprType != "-1")
        {
        	$index = $RowCount + 1;
			$arrRows[$index]["OprUser"] = "";
            $arrRows[$index]["OprTime"] = "";
            $arrRows[$index]["MemberIdx"] = "";
            $arrRows[$index]["NickName"] = "";
            $arrRows[$index]["TransType"] = "页小计:";
            $arrRows[$index]["Amount"] = Trans($Total_Amount);
            $arrRows[$index]["RemarkType"] = "";
            $arrRows[$index]["Remark"] = "";
        }
        
        //返回分页
        require_once('inc/fenye.php');
        $ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page_AT','nowindex' => $page));
        $pageInfo = $ajaxpage->show();
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows);  
        echo json_encode($arrRows);
    }
    /* 充值给会员
    *
    */
    function AdminTransToUser()
    {  
    	global $db;   
        $MemberIdx = isset($_POST['memberidx'])?FilterStr($_POST['memberidx']):"";
        $TransType = isset($_POST['transtype'])?FilterStr($_POST['transtype']):"";
        $RemarkType = isset($_POST['remarktype'])?FilterStr($_POST['remarktype']):"";
        $Amount = isset($_POST['amount'])?FilterStr($_POST['amount']):"";
        $Pwd = isset($_POST['pwd'])?FilterStr($_POST['pwd']):"";
        $Remark = isset($_POST['remark'])?FilterStr($_POST['remark']):"";
        
        $arrReturn = array(array());
        
        if(!is_numeric($MemberIdx))
        {
            $arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "提交的会员ID必须是数字!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
        }
        
        if(!is_numeric($TransType))
        {
            $arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "提交的充值类型错误!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
        }
        if(!is_numeric($Amount))
        {
            $arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "提交的数量必须为数字!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
        }
        
        $sql = "call web_AdminTrans('{$Pwd}',{$TransType},{$Amount},{$MemberIdx},{$_SESSION["Admin_UserID"]},{$RemarkType},'{$Remark}')";
        //WriteLog($sql);
        //return;
        $arr = $db->Mysqli_Multi_Query($sql);
        $Return = $arr[0][0]["result"];
        $cmd = "cmdadmintrans";
        $msg = "";
        if($Return == 0)
        { 
            $msg = "操作成功!";
        }
        elseif($Return == 1)
        {
			$msg = "密码错误!";
        }
        else
        {
            $msg = "由于数据库执行错误，执行失败!";
        }
        $arrReturn[0]["cmd"] = $cmd;
        $arrReturn[0]["msg"] = $msg;
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
        return;
    }
	/* 检测用户信息
    *
    */
    function CheckUserInfo($act)
    {
    	global $db; 
		$MemberIdx = isset($_POST['memberidx'])?FilterStr($_POST['memberidx']):"0";
		$sql = "select nickname,dj,points,back,lock_points,experience from users where id = '{$MemberIdx}'";
		$arrReturn = array(array());
		
        $result = $db->query($sql);
        //取得返回记录数
        $RowCount = $db->num_rows($result);
        if($RowCount == 0)
        {
            $arrReturn[0]["cmd"] = $act;
            $arrReturn[0]["msg"] = "用户不存在!";
            ArrayChangeEncode($arrReturn); 
            echo json_encode($arrReturn);
            return;
        }
        $row=$db->fetch_array($result);
        $msg = "会员ID:" . $MemberIdx . ",昵称:" . $row["nickname"] . ",状态:" . (($row["dj"] == 0)?"正常":"冻结") . 
        		",当前分:" . $row["points"] . ",银行分:" . $row["back"] . ",投注分:" . $row["lock_points"] . ",经验:" . $row["experience"];
        $arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = $msg;
        ArrayChangeEncode($arrReturn);  
        echo json_encode($arrReturn);
    }
	/* 取中央银行子帐号修改记录
    *
    */
    function GetCenterItemChangeLog($act)
    {
    	global $db;
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        
        $sqlCount = "select Count(*) ";
        $sqlCol = "SELECT a.bankIdx,b.bankRemark,a.old_value,a.new_value,(a.old_value+a.new_value) AS after_value,a.opr_time,c.name
                  ";
        $sqlFrom = "FROM centerbank_changlog a
					LEFT OUTER JOIN centerbank b
					ON a.bankIdx = b.bankIdx
					LEFT OUTER JOIN admin c
					ON a.opr_user = c.id
					WHERE 1=1 ";
        $sqlWhere = "";
        $sqlOrder = "";
        $sql = "";
        //页大小
        $PageSize = isset($_POST['PageSize'])?$_POST['PageSize']:20;
        $PageSize = intval($PageSize);
        //页码
        $page = isset($_POST['Page'])?$_POST['Page']:1;
        $page =intval($page);

        $arrReturn = array(array());
        //取得查询条件
        //时间
        $TimeField = "a.opr_time";
        $sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
        
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
        for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
        {  
            //对返回数据进行包装
            $arrRows[$i]["OprTime"] = $row["opr_time"];
            $arrRows[$i]["OprItem"] = $row["bankRemark"];
            $arrRows[$i]["OldValue"] = $row["old_value"];
            $arrRows[$i]["AddValue"] = $row["new_value"];
            $arrRows[$i]["NewValue"] = $row["after_value"];
            $arrRows[$i]["OprUser"] = $row["name"];
        }
        
        //返回分页
        require_once('inc/fenye.php');
        $ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page_CS','nowindex' => $page));
        $pageInfo = $ajaxpage->show();
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
    }
	/* 加减中央银行子帐号
    *
    */
    function ChangeCenterBankItem()
    {
    	global $db;
        $ItemField = isset($_POST['field'])?FilterStr($_POST['field']):"";
        $Increment = isset($_POST['inc'])?FilterStr($_POST['inc']):"";
        $BankIdx = 0;
        $arrReturn = array(array());
        
        if(!is_numeric($Increment))
        {
            $arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "提交的加减值必须是数字!";
            echo json_encode($arrReturn);
            return;
        }
        switch($ItemField)
        {
            case "SystemScore":
                $BankIdx = 1;
                break;
            case "RobotScore":
                $BankIdx = 2;
                break;
            case "AdminScore":
                $BankIdx = 3;
                break;
            case "OnlinePayScore":
                $BankIdx = 4;
                break;
            case "CardPayScore":
                $BankIdx = 5;
                break;
            case "ReliefScore":
                $BankIdx = 6;
                break;
            case "UnderManScore":
                $BankIdx = 7;
                break;
            case "RewardScore":
                $BankIdx = 8;
                break;
            case "ActivityScore":
                $BankIdx = 9;
                break;
            case "PropScore":
                $BankIdx = 10;
                break;
            case "ErrorScore":
                $BankIdx = 11;
                break;
            case "OtherScore":
                $BankIdx = 12;
                break;
            default:
                $BankIdx = 0;
                break;
        }
        if($BankIdx == 0)
        {
            $arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "提交的银行子帐号错误!";
            ArrayChangeEncode($arrReturn); 
            echo json_encode($arrReturn);
            return;
        }
        $sql = "call web_ChangeChenterBankItem({$BankIdx},{$Increment},{$_SESSION["Admin_UserID"]})";
        //WriteLog($sql);
        //return;
        $arr = $db->Mysqli_Multi_Query($sql);
        $Return = $arr[0][0]["result"];
        $cmd = "changecenteritem";
        $msg = "";
        if($Return == "0")
        { 
            $msg = "操作成功!";
        }
        else
        {
            $msg = "由于数据库执行错误，执行失败!";
        }
        $arrReturn[0]["cmd"] = $cmd;
        $arrReturn[0]["msg"] = $msg;
        ArrayChangeEncode($arrReturn); 
        echo json_encode($arrReturn);
        
        return;
    }
	/*  中央银行历史信息
    *
    */
    function GetCenterbankHistoryInfo($act)
    {
    	global $db;
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";

        $sqlCount = "select Count(*) ";
        $sqlCol = "SELECT *
                  ";
        $sqlFrom = "FROM centerbank_snap
                    WHERE 1=1";
        $sqlWhere = "";
        $sqlOrder = "";
        $sql = "";
        //页大小
        $PageSize = isset($_POST['PageSize'])?$_POST['PageSize']:20;
        $PageSize = intval($PageSize);
        //页码
        $page = isset($_POST['Page'])?$_POST['Page']:1;
        $page =intval($page);

        $arrReturn = array(array());
        //取得查询条件
        //时间
        $TimeField = "logtime";
        $sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
        
        //取得排序
        $sqlOrder = " order by logtime desc";
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
            $arrRows[$i]["LogTime"] = $row["logtime"];
            $arrRows[$i]["TotalExp"] = Trans($row["total_exp"]);
	        $arrRows[$i]["AdminScore"] = Trans($row["admin_score"]);
	        $arrRows[$i]["OnlinePayScore"] = Trans($row["online_pay_score"]);
	        $arrRows[$i]["CardPayScore"] = Trans($row["card_pay_score"]);
	        $arrRows[$i]["ReliefScore"] = Trans($row["relief_score"]);
	        $arrRows[$i]["UnderManScore"] = Trans($row["underman_score"]);
	        $arrRows[$i]["RewardScore"] = Trans($row["reward_score"]);
	        $arrRows[$i]["ActivityScore"] = Trans($row["activity_score"]);
	        $arrRows[$i]["PropScore"] = Trans($row["prop_score"]);
	        $arrRows[$i]["ErrorScore"] = Trans($row["error_score"]);
	        $arrRows[$i]["OtherScore"] = Trans($row["other_score"]);
	        $arrRows[$i]["TransTaxScore"] = Trans($row["trans_tax_score"]);
	        $arrRows[$i]["GameTaxScore"] = Trans($row["game_tax_score"]);
	        $arrRows[$i]["BlockScore"] = Trans($row["block_score"]);
	        $arrRows[$i]["UserScore"] = Trans($row["user_score"]);
	        $arrRows[$i]["RobotScore"] = Trans($row["cir_score"] - $row["user_score"]); 
	        $arrRows[$i]["CirScore"] = Trans($row["cir_score"]);
	        $arrRows[$i]["CenterBank"] = Trans($row["centerbank"]);
	        $arrRows[$i]["UserWinLoseScore"] = Trans($row["user_winlose_score"]);
        }
        
        //返回分页
        require_once('inc/fenye.php');
        $ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page_SI','nowindex' => $page));
        $pageInfo = $ajaxpage->show();
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows);    
        echo json_encode($arrRows);
    }
	/* 取中央银行实时信息
    *
    */
    function GetCenterbankInfo($act)
    { 
        global $db;
        $sql = "call web_get_centerbank_snap('realtime')";
        $arr = $db->Mysqli_Multi_Query($sql);
        $arrRows = array(array());
        $arrRows[1]["SystemScore"] = Trans($arr[0][0]["v_system_score"]);
        $arrRows[1]["RobotScore"] = Trans($arr[0][0]["v_robot_score"]);
        $arrRows[1]["AdminScore"] = Trans($arr[0][0]["v_admin_score"]);
        $arrRows[1]["OnlinePayScore"] = Trans($arr[0][0]["v_online_pay_score"]);
        $arrRows[1]["CardPayScore"] = Trans($arr[0][0]["v_card_pay_score"]);
        $arrRows[1]["ReliefScore"] = Trans($arr[0][0]["v_relief_score"]);
        $arrRows[1]["UnderManScore"] = Trans($arr[0][0]["v_underman_score"]);
        $arrRows[1]["RewardScore"] = Trans($arr[0][0]["v_reward_score"]);
        $arrRows[1]["ActivityScore"] = Trans($arr[0][0]["v_activity_score"]);
        $arrRows[1]["PropScore"] = Trans($arr[0][0]["v_prop_score"]);
        $arrRows[1]["ErrorScore"] = Trans($arr[0][0]["v_error_score"]);
        $arrRows[1]["OtherScore"] = Trans($arr[0][0]["v_other_score"]);
        $arrRows[1]["TransTaxScore"] = Trans($arr[0][0]["v_trans_tax_score"]);
        $arrRows[1]["GameTaxScore"] = Trans($arr[0][0]["v_game_tax_score"]);
        $arrRows[1]["TotalExp"] = Trans($arr[0][0]["v_total_exp"]);
        $arrRows[1]["BlockScore"] = Trans($arr[0][0]["v_block_score"]);
        $arrRows[1]["CirScore"] = Trans($arr[0][0]["v_cir_score"]);
        $arrRows[1]["RobotTotalScore"] = Trans($arr[0][0]["v_cir_score"] - $arr[0][0]["v_user_score"]);  
        $arrRows[1]["UserScore"] = Trans($arr[0][0]["v_user_score"]);                     
        $arrRows[1]["CenterBank"] = Trans($arr[0][0]["v_centerbank"]);
        $arrRows[1]["UserWinLoseScore"] = Trans($arr[0][0]["v_user_winlose_score"]);
        
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = "ok";
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
        return;
    } 

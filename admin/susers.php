<?php  
    include_once( "inc/conn.php" );
    include_once( "inc/function.php" );
    //login_check( "users" );
    
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
			case 'changekf':
				login_check( "system" );
				changekf();
				break;
	    	case "get_redbag_recvlog": //红包收取记录
	    		RecvRedbagLog($act);
	    		break;
	    	case "get_redbag_sendlog": //发送红包记录
	    		SendRedbagLog($act);
	    		break;
	    	case "remove_rankprizelog": //删除排行领取日志
	    		login_check( "system" );
	    		RemoveRankPrizeLog($act);
	    		break;
	    	case "get_rankprizelog": //取得排行领取日志
	    		GetRankPrizeLog($act);
	    		break;
	    	case "get_ranklist": //取得排行榜
	    		GetRankList($act);
	    		break;
	    	case "save_rankpoint_config": //设置排行奖励
	    		login_check( "system" );
	    		SaveRankPointConfig($act);
	    		break;
	    	case "get_ranklevel_info": //取得排行奖励设置
	    		GetRankLevelInfo($act);
	    		break;
	    	case "save_usergroup_config": //保存等级信息
	    		login_check( "system" );
	    		SaveUserGroupConig($act);
	    		break;
	    	case "get_userlevel_info": //取得用户等级信息
	    		GetUserLevelInfo($act);
	    		break;
	    	case "get_userwinlose_rank": //取输赢排行
	    		GetUserWinLoseRank($act);
	    		break;
    		case "get_commendedaward_rank": //取推荐奖排行
    			GetUserCommendedRank($act);
    			break;
	    	case "get_noreward_changelog": // 取全压无开奖记录
	    		GetNoRewardLog($act);
	    		break;
	    	case "remove_scorechange_log": //删除分值变化记录
	    		login_check( "system" );
	    		RemoveScoreChangeLog($act);
	    		break;
	    	case "get_gamescore_changelog": //取得用户分值变化
	    		GetGameScoreChangeLog($act);
	    		break;
	    	case "get_gametypeid_score_option": //取得游戏列表
	    		GetGameTypeIDScoreOption($act);
	    		break;
	    	case "get_gameallkglog": //取得预投注历史记录
	    		GetAllGameKgHistory($act);
	    		break;
	    	case "remove_winlose_log": //删除过期输赢记录
	    		login_check( "system" );
	    		RemoveWinLoseLog($act);
	    		break;
	    	case "get_gamewinlose":// 游戏每日输赢
	    		GetGameWinLoseLog($act);
	    		break;
	    	case "clear_useringame": // 清除在线
	    		login_check( "users" );
	    		ClearUserInGameStatus($act);
	    		break;
	    	case "catchresult_remove": //删除开奖结果
	    		login_check( "gamegl" );
	    		CatchResultRemove($act);
	    		break;
	    	case "catchresult_reopen": //重开奖
	    		login_check( "gamegl" );
	    		CatchResultReopen($act);
	    		break;
	    	case "get_catchresult": //取得开奖结果
	    		GetCatchResult($act);
	    		break;
	    	case "remove_sysmsg": //删除一个月系统消息
	    		login_check( "system" );
	    		RemoveSysMsgLog($act);
	    		break;
	    	case "get_sysmsglog": //取得系统消息
	    		GetSystemMsgLog($act);
	    		break;
	    	case "get_pressuser_list": //取得正在下注用户
	    		GetPressUserList($act);
	    		break;
	    	case "get_gametypeid_option": //取得游戏列表按id
	    		GetGameTypeIDOption($act);
	    		break;
	    	case "get_gamekglog": //取得未开奖记录
	    		GetGameKgLog($act);
	    		break;
	    	case "remove_msg": // 删除消息
	    		login_check( "system" );
	    		RemoveMsg($act);
	    		break;
	    	case "get_msglog": // 信息记录
	    		GetMsgLog($act);
	    		break;
	    	case "send_msg": //发送站内信息
	    		login_check( "system" );
	    		SendMsg($act);
	    		break;
	    	case "open": //解封用户
	    		login_check( "users" );
				ChangeUserStatus("open");
				break;
			case "forbidden": //冻结用户
				login_check( "users" );
				ChangeUserStatus("forbidden");
				break;   
				
			case "openRebate": //解封用户返利
				login_check( "users" );
				ChangeUserRebate("open");
				break;
			case "closeRebate": //冻结用户返利
				login_check( "users" );
				ChangeUserRebate("close");
				break;
			case "sendPack"://发红包短信
				login_check( "sms" );
				SendPack($act);
				break;
				
	    	case "get_patchuserinfo": //取得批用户
	    		GetPatchUserInfo($act);
                break;
	    	case "get_gamelog": //取得游戏投注
	    		GetGameLog($act);
	    		break;
	    	case "get_gametypeoption": //取得游戏列表
	    		GetGameTypeOption($act);
	    		break;
	    	case "get_validlog": //取得验证记录
                GetValidLog($act);
                break;
	    	case "get_changedetaillog": //取得操作记录
                GetChangeDetailLog($act);
                break;
	    	case "get_actionlog": //取得用户操作记录
                GetActionLog($act);
                break;
	    	case "get_scorelog": //取得分值变化记录
                GetScoreLog($act);
                break;
	    	case "get_translog": //取得转账记录
                GetTransLog($act);
                break;
	    	case "get_paylog": //取得充值记录
                GetPayLog($act);
                break;
	    	case "get_loginfail": //取得登录失败记录
                GetLoginFailLog($act);
                break;
	    	case "get_loginsuccess": //取得登录成功记录
                GetLoginSuccessLog($act);
                break;
	    	case "changedetail": //修改资料
	    		login_check( "users" );
                ChangeDetailItem();
                break;
	        case "get_baseinfo": //取用户基本信息
	        	GetUserBaseInfo($act);
        		break;
        	case "get_recenttrans": //最近提现
               GetWithDrawLog($act);
                break;
            case "get_recentpay": //最近充值
                GetRecentPayLog($act);
                break;
			case "get_tblBdCard": //绑定卡号
                GetBdAccounts($act);
                break;
            case "get_recentlogin": //取最近登录
                GetRecentLoginLog($act);
                break;
	        default:
	            exit;
	    }
	}
	function changekf(){
		global $db;
		$kf = isset($_POST['kf'])?intval($_POST['kf']):0;
		$UserIDx = isset($_POST['id'])?FilterStr($_POST['id']):"";

		$arrReturn = array(array("cmd"=>"","msg"=>""));
		if($UserIDx == "")
		{
			$arrReturn[0]["cmd"] = "changedetail_error";
			$arrReturn[0]["msg"] = "数据非法!";
			echo json_encode($arrReturn);
			return;
		}
		$sql="update users set kf={$kf} where id=".$UserIDx;
		$db->query($sql);
		$sql='SELECT kf FROM users WHERE id = '.$UserIDx;
		$info=$db->fetch_first($sql);
		$sql="INSERT INTO changedetaillog(detail_type,uid,item_old,item_new,change_time,opr_user)
				VALUES('扣分',{$UserIDx},{$info['kf']},{$kf},NOW(),".$_SESSION["Admin_UserID"].");";
		$db->query($sql);
		$arrReturn[0]["cmd"] = 0;
		$arrReturn[0]["msg"] = '修改成功!';
		echo json_encode($arrReturn);
		return;
	}
	/* 接收红包记录
	*
	*/
	function RecvRedbagLog($act)
	{
		global $db;
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $BagNo = isset($_POST['bagno'])?FilterStr($_POST['bagno']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "select b.nickname,a.bagno,a.recv_uid,a.recv_time,a.recv_msg,a.recv_ip,a.points
					";
		$sqlFrom = " from redbag_recv_log a
					 left outer join users b
					 on a.recv_uid = b.id
					 where state = 1 ";
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
        if($UserID != "")
            $sqlWhere .= " and a.recv_uid = " . $UserID;
        if($BagNo != "")
            $sqlWhere .= " and a.bagno = '{$BagNo}'";
        if($IsExceptInner == 1)
        	$sqlWhere .= " and a.recv_uid not in(select uid from users_inner)";
        //时间
        $TimeField = "a.recv_time";
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
		$total_points = 0;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
            $arrRows[$i]["RecvUserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["recv_uid"]}")."' target='_blank'>{$row["recv_uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            $arrRows[$i]["RecvTime"] = $row["recv_time"];
			$arrRows[$i]["RecvPoints"] = Trans($row["points"]) . "(￥" . $row['points']/1000 .")";
			$arrRows[$i]["Msg"] = $row["recv_msg"];
			$IPInfo = "<a href='http://www.ip138.com/ips.asp?ip={$row["recv_ip"]}' target='_blank'>". $row["recv_ip"] ."</a>";
			$IPInfo .= "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["recv_ip"]}")."' target='_blank'>批</a>";
			$arrRows[$i]["RecvIP"] = $IPInfo;
			$total_points += $row["points"];
		}
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
            $arrRows[$i]["RecvUserID"] = "";
            $arrRows[$i]["NickName"] = "";
            $arrRows[$i]["RecvTime"] = "页小计:";
			$arrRows[$i]["RecvPoints"] = Trans($total_points) . "(￥" . $total_points/1000 .")";
			$arrRows[$i]["Msg"] = "";
			$arrRows[$i]["RecvIP"] = "";
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
	/* 发送红包记录
	*
	*/
	function SendRedbagLog($act)
	{
		global $db;
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $BagType = isset($_POST['bagtype'])?FilterStr($_POST['bagtype']):"-1";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "select b.nickname,a.bagno,a.bagtype,a.send_uid,a.send_time,a.send_title,a.send_ip,
						a.points,a.send_cnt,a.had_recv_cnt,a.had_recv_points,a.end_time,a.state,now() as nowtime
					";
		$sqlFrom = " from redbag_send_log a
					 left outer join users b
					 on a.send_uid = b.id
					 where 1=1 ";
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
        if($UserID != "")
            $sqlWhere .= " and a.send_uid = " . $UserID;
        if($BagType != "-1")
            $sqlWhere .= " and a.bagtype = " . $BagType;
        if($IsExceptInner == 1)
        	$sqlWhere .= " and a.send_uid not in(select uid from users_inner)";
        //时间
        $TimeField = "a.send_time";
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
		$total_sendpoints = 0;
		$total_recvpoints = 0;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
            $arrRows[$i]["SendUserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["send_uid"]}")."' target='_blank'>{$row["send_uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            $arrRows[$i]["SendTime"] = $row["send_time"];
            $arrRows[$i]["BagNo"] = $row["bagno"];
            $arrRows[$i]["BagType"] = ($row["bagtype"] == 0) ? "普通" : "拼手气";
            $arrRows[$i]["SendCount"] = $row["send_cnt"];
			$arrRows[$i]["SendPoints"] = Trans($row["points"]) . "(￥" . $row['points']/1000 .")";
			$arrRows[$i]["HadRecvCnt"] = $row["had_recv_cnt"];
			$arrRows[$i]["HadRecvPoints"] = Trans($row["had_recv_points"]) . "(￥" . $row['had_recv_points']/1000 .")";
			$arrRows[$i]["Deadline"] = $row["end_time"]; 
			$arrRows[$i]["IsCompleted"] = ($row["state"] == 0) ? "<font color='red'>未领完</font>" : "已领完"; 
			$arrRows[$i]["TheState"] = (strtotime($row["nowtime"]) > strtotime($row["end_time"])) ? "<font color='red'>已过期</font>" : "可用";
			$IPInfo = "<a href='http://www.ip138.com/ips.asp?ip={$row["send_ip"]}' target='_blank'>". $row["send_ip"] ."</a>";
			$IPInfo .= "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["send_ip"]}")."' target='_blank'>批</a>";
			$arrRows[$i]["SendIP"] = $IPInfo;
			$arrRows[$i]["Opr"] = "<a class='edi' title='查询领取记录' href=\"user_recvbaglog.php?bagno={$row['bagno']}\">查看</a>";
			$total_sendpoints += $row["points"];
			$total_recvpoints += $row["had_recv_points"];
		}
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
            $arrRows[$i]["SendUserID"] = "";
            $arrRows[$i]["NickName"] = "";
            $arrRows[$i]["SendTime"] = "";
            $arrRows[$i]["BagNo"] = "";
            $arrRows[$i]["BagType"] = "";
            $arrRows[$i]["SendCount"] = "页小计:";
			$arrRows[$i]["SendPoints"] = Trans($total_sendpoints) . "(￥" . $total_sendpoints/1000 .")";
			$arrRows[$i]["HadRecvCnt"] = "";
			$arrRows[$i]["HadRecvPoints"] = Trans($total_recvpoints) . "(￥" . $total_recvpoints/1000 .")";
			$arrRows[$i]["Deadline"] = ""; 
			$arrRows[$i]["IsCompleted"] = ""; 
			$arrRows[$i]["TheState"] = "";
			$arrRows[$i]["SendIP"] = "";
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
	/* 删除排行日志
	*
	*/
	function RemoveRankPrizeLog($act)
	{
		global $db;
		$month = intval($_POST['m']);
		$arrReturn = array(array("cmd"=>"","msg"=>""));
		$sql = "delete from rank_prizelog where prize_time < DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -{$month} MONTH),'%Y-%m-01')";
		$result = $db->query($sql);
		
		$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		//添加日志
		addlog("删除排行领取记录:".$msg);
		
		$arrReturn[0]["cmd"] = "ok";
        $arrReturn[0]["msg"] = $msg;  
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 取得排行领取日志
	*
	*/
	function GetRankPrizeLog($act)
	{
		global $db;
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "select a.uid,a.rank_num,b.nickname,a.rank_points,a.prize_points,a.prize_time,a.prize_ip
					";
		$sqlFrom = " from rank_prizelog a
					 left outer join users b
					 on a.uid = b.id
					 where 1=1 ";
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
        if($UserID != "")
            $sqlWhere .= " and a.uid = " . $UserID;
        if($IsExceptInner == 1)
        	$sqlWhere .= " and a.uid not in(select uid from users_inner)";
        //时间
        $TimeField = "a.prize_time";
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
		$total_rankpoints = 0;
		$total_prizepoints = 0;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            $arrRows[$i]["PrizeTime"] = $row["prize_time"];
            $arrRows[$i]["PrizePoint"] = Trans($row["prize_points"]);
            $arrRows[$i]["RankNum"] = "第" . $row["rank_num"] . "名";
			$arrRows[$i]["RankPoint"] = Trans($row["rank_points"]);
			$IPInfo = "<a href='http://www.ip138.com/ips.asp?ip={$row["prize_ip"]}' target='_blank'>". $row["prize_ip"] ."</a>";
			$IPInfo .= "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["prize_ip"]}")."' target='_blank'>批</a>";
			$arrRows[$i]["IP"] = $IPInfo;
			$total_rankpoints += $row["rank_points"];
			$total_prizepoints += $row["prize_points"];
		}
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
            $arrRows[$i]["UserID"] = "";
            $arrRows[$i]["NickName"] = "";
            $arrRows[$i]["PrizeTime"] = "页小计:";
            $arrRows[$i]["PrizePoint"] = Trans($total_prizepoints);
            $arrRows[$i]["RankNum"] = "";
			$arrRows[$i]["RankPoint"] = Trans($total_rankpoints);
			$arrRows[$i]["IP"] = "";
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
	/* 取得排行榜
	*
	*/
	function GetRankList($act)
	{
		global $db;
        $RankType = isset($_POST['ranktype'])?FilterStr($_POST['ranktype']):"1";
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "select a.uid,a.rank_num,b.nickname,a.rank_points,a.prize_points,a.state
					";
		$sqlFrom = " from rank_list a
					 left outer join users b
					 on a.uid = b.id
					 where rank_type = {$RankType} ";
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
        if($UserID != "")
            $sqlWhere .= " and a.uid = " . $UserID;
        if($IsExceptInner == 1)
        	$sqlWhere .= " and a.uid not in(select uid from users_inner)";
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
		$total_rankpoints = 0;
		$total_prizepoints = 0;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
			$arrRows[$i]["RankNum"] = "第" . $row["rank_num"] . "名";
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
			$arrRows[$i]["RankPoints"] = Trans($row["rank_points"]);
			$arrRows[$i]["PrizePoints"] = Trans($row["prize_points"]);
			$arrRows[$i]["State"] = ($row["state"] == 0) ? "<font color='red'>未领取</font>" : "<font color='blue'>已领取</font>";
			$total_rankpoints += $row["rank_points"];
			$total_prizepoints += $row["prize_points"];
		}
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
			$arrRows[$i]["RankNum"] = "";
            $arrRows[$i]["UserID"] = "";
            $arrRows[$i]["NickName"] = "页小计";
			$arrRows[$i]["RankPoints"] = Trans($total_rankpoints);
			$arrRows[$i]["PrizePoints"] = Trans($total_prizepoints);
			$arrRows[$i]["State"] = "";
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
	/* 设置排行奖励
	*
	*/
	function SaveRankPointConfig($act)
	{
		global $db;
		$RecID = intval($_POST['id']); 
		$RankPoint = intval($_POST['rankpoint']);
		$arrReturn = array(array());
		if($RecID == 0 || $RankPoint < 0)
		{
			$arrReturn[0]["cmd"] = "err";
			$arrReturn[0]["msg"] = "数据错误!";
			ArrayChangeEncode($arrReturn);
			echo json_encode($arrReturn);
			return;
		}
		$sql = "update rank_prize set prize_points = {$RankPoint}
					where id = {$RecID}";
		$result = $db->query($sql);
		
		$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		//添加日志
		addlog("修改排行榜记录号{$RecID}结果:".$msg);
		
		$arrReturn[0]["cmd"] = "ok";
        $arrReturn[0]["msg"] = $msg;  
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 取得排行奖励设置
	*
	*/
	function GetRankLevelInfo($act)
	{
		global $db;
		$sql = "select id,rank_num,prize_points
		         from rank_prize
		         order by rank_num";
		$RowCount = 0;
		$arrRows = array(array());
		$result = $db->query($sql);
		$RowCount = $db->num_rows($result);
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
            $arrRows[$i]["RankNum"] = "第" . $row["rank_num"] . "名";
            $arrRows[$i]["RankPoint"] = "<input type='text' id='rankpoint_{$row['id']}' value='{$row["prize_points"]}' style='width:120px;border:1px solid #999;background-color:#FFFFCC;' />";
			$arrRows[$i]["Opr"] = "<a style='cursor:pointer' onclick='save({$row['id']})'>保存设置</a>";
		}
		$arrRows[0]["cmd"] = $act;
		$arrRows[0]["msg"] = "";
        ArrayChangeEncode($arrRows); 
        //WriteLog(json_encode($arrRows));
		echo json_encode($arrRows);
	}
	/* 保存用户等级信息
	*
	*/
	function SaveUserGroupConig($act)
	{
		global $db;
		$RecID = intval($_POST['id']); 
		$ExpMin = intval($_POST['expmin']);
		$ExpMax = intval($_POST['expmax']);
		$Jiuji = intval($_POST['jiuji']);
		$RewardRate = isset($_POST['rewardrate'])?FilterStr($_POST['rewardrate']):"";
		$Up1rate = isset($_POST['up1rate'])?FilterStr($_POST['up1rate']):"";
		$Up2rate = isset($_POST['up2rate'])?FilterStr($_POST['up2rate']):"";
		$Up3rate = isset($_POST['up3rate'])?FilterStr($_POST['up3rate']):"";
		$arrReturn = array(array());
		if($RecID == 0 || $ExpMin < 0 || $ExpMin >= $ExpMax)
		{
			$arrReturn[0]["cmd"] = "err";
			$arrReturn[0]["msg"] = "数据错误!";
			ArrayChangeEncode($arrReturn);
			echo json_encode($arrReturn);
			return;
		}
		$sql = "update usergroups set creditslower = {$ExpMin},creditshigher = {$ExpMax},
					day_jiuji_point= {$Jiuji},reward_discount = {$RewardRate},
					tj_1_level_discount = {$Up1rate},tj_2_level_discount = {$Up2rate},tj_3_level_discount = {$Up3rate}
		        where id = {$RecID}";
		$result = $db->query($sql);
		
		$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		//添加日志
		addlog("修改用户等级记录号{$RecID}结果:".$msg);
		
		$arrReturn[0]["cmd"] = "ok";
        $arrReturn[0]["msg"] = $msg;  
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 取得用户等级信息
	*
	*/
	function GetUserLevelInfo($act)
	{
		global $db;
		$sql = "select id,creditshigher,creditslower,day_jiuji_point,reward_discount,
					tj_1_level_discount,tj_2_level_discount,tj_3_level_discount
		         from usergroups
		         order by id";
		$RowCount = 0;
		$arrRows = array(array());
		$result = $db->query($sql);
		$RowCount = $db->num_rows($result);
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
            $arrRows[$i]["ID"] = $row["id"];
            $arrRows[$i]["Level"] = "<img src='images/lvimg/lv" . ($row["id"] - 1) . ".gif' />";
            $arrRows[$i]["Exp"] = "<input type='text' id='exp_min_{$row['id']}' value='{$row["creditslower"]}' style='width:80px;border:1px solid #999;background-color:#FFFFCC;' /> - " .
            						"<input type='text' id='exp_max_{$row['id']}' value='{$row["creditshigher"]}' style='width:80px;border:1px solid #999;background-color:#FFFFCC;' />";
            $arrRows[$i]["Jiuji"] = "<input type='text' id='jiuji_{$row['id']}' value='{$row["day_jiuji_point"]}' style='width:80px;border:1px solid #999;background-color:#FFFFCC;' />";
			$arrRows[$i]["RewardRate"] = "<input type='text' id='rewardrate_{$row['id']}' value='{$row["reward_discount"]}' style='width:60px;border:1px solid #999;background-color:#FFFFCC;' />1为无折扣";
			$arrRows[$i]["Up1Rate"] = "<input type='text' id='up1_{$row['id']}' value='{$row["tj_1_level_discount"]}' style='width:60px;border:1px solid #999;background-color:#FFFFCC;' />";
			$arrRows[$i]["Up2Rate"] = "<input type='text' id='up2_{$row['id']}' value='{$row["tj_2_level_discount"]}' style='width:60px;border:1px solid #999;background-color:#FFFFCC;' />";
			$arrRows[$i]["Up3Rate"] = "<input type='text' id='up3_{$row['id']}' value='{$row["tj_3_level_discount"]}' style='width:60px;border:1px solid #999;background-color:#FFFFCC;' />";
			$arrRows[$i]["Opr"] = "<a style='cursor:pointer' onclick='save({$row['id']})'>保存设置</a>";
		}
		$arrRows[0]["cmd"] = $act;
		$arrRows[0]["msg"] = "";
        ArrayChangeEncode($arrRows); 
        //WriteLog(json_encode($arrRows));
		echo json_encode($arrRows);
	}
	/* 取得输赢排行
	*
	*/
	function GetUserWinLoseRank($act)
	{
		global $db;
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
        $dayType = isset($_POST['day'])?FilterStr($_POST['day']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $WinMin = isset($_POST['winmin'])?FilterStr($_POST['winmin']):"";
        $WinMax = isset($_POST['winmax'])?FilterStr($_POST['winmax']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT b.uid,b.totalpoints,a.nickname,a.points,a.back,a.lock_points,a.logintime,a.loginip
					";
		$whereIn = "";
		$whereOut = "";
		if($UserID != "")
			$whereIn .= " and uid = '{$UserID}'";
		if($IsExceptInner == 1)
        	$sqlWhere .= " and uid not in(select uid from users_inner)";
		if($GameType != "-1")
			$whereIn .= " and kindid = '{$GameType}'";
		switch($dayType)
		{
			case "0":
				$whereIn .= " and `time` = CURDATE()";
				break;
			case "1":
				$whereIn .= " and `time` = DATE_ADD(CURDATE(),INTERVAL -1 DAY)";
				break;
			case "2":
				$whereIn .= " and `time` = DATE_ADD(CURDATE(),INTERVAL -2 DAY)";
				break;
			case "3":
				$whereIn .= " and `time` >= DATE_ADD(CURDATE(),INTERVAL -7 DAY)";
				break;
			case "4":
				$whereIn .= " and `time` >= DATE_ADD(CURDATE(),INTERVAL -1 month)";
				break;
			case "5":
				$whereIn .= " and `time` >= DATE_ADD(CURDATE(),INTERVAL -3 month)";
				break;
			case "6":
				$whereIn .= " and `time` >= DATE_ADD(CURDATE(),INTERVAL -6 month)";
				break;
			default:
				break;
		}
		//输赢
        $Field = "b.totalpoints";
        $whereOut .= GetSqlBetween($Field,$WinMin,$WinMax,true);
        
		$sqlFrom = " FROM
					(
						SELECT uid,SUM(points) AS totalpoints
						FROM game_day_static
						where 1 = 1 {$whereIn}
						GROUP BY uid 
					) b 
					LEFT OUTER JOIN users a
					ON b.uid = a.id
					WHERE 1=1 {$whereOut}";
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
		$total_winlose = 0;
		$total_points = 0;
		$total_bankpoints = 0; 
		$total_lockpoints = 0; 
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            $arrRows[$i]["WinLose"] = "<font color='red'>" . Trans($row["totalpoints"]) . "</font>";
            $arrRows[$i]["Points"] = Trans($row["points"]);
			$arrRows[$i]["Back"] = Trans($row["back"]);
			$arrRows[$i]["LockPoints"] = Trans($row["lock_points"]);
			$arrRows[$i]["LastLoginTime"] = $row["logintime"];
			
			$IPInfo = "<a href='http://www.ip138.com/ips.asp?ip={$row["loginip"]}' target='_blank'>". $row["loginip"] ."</a>";
			$IPInfo .= "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["loginip"]}")."' target='_blank'>批</a>";
			$arrRows[$i]["LastLoginIP"] = $IPInfo;
			$arrRows[$i]["Opr"] = "<a class='edi' href=\"user_winlose_day.php?id={$row['uid']}&gametype={$GameType}\">查看每日输赢</a>";
			$total_winlose += $row["totalpoints"];
			$total_points += $row["points"];
			$total_bankpoints += $row["back"];
			$total_lockpoints += $row["lock_points"];
		}
		
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
			$arrRows[$i]["UserID"] = "";
			$arrRows[$i]["NickName"] = "页小结:"; 
			$arrRows[$i]["WinLose"] = "<font color='red'>" . Trans($total_winlose) . "</font>";
            $arrRows[$i]["Points"] = Trans($total_points);
			$arrRows[$i]["Back"] = Trans($total_bankpoints);
			$arrRows[$i]["LockPoints"] = Trans($total_lockpoints);
			$arrRows[$i]["LastLoginTime"] = "";
			$arrRows[$i]["LastLoginIP"] = "";
			$arrRows[$i]["Opr"] = "";
		}
		
		
		//返回分页
		require_once('inc/fenye.php');
		$ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
		$pageInfo = $ajaxpage->show();
		
		$arrRows[0]["cmd"] = $act;
		$arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows); 
        //WriteLog(json_encode($arrRows));
		echo json_encode($arrRows);
		exit;
    }
    /* 取得推荐奖排行
     *
    */
    function GetUserCommendedRank($act)
    {
    	global $db;
    	$UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
    	$dayType = isset($_POST['day'])?FilterStr($_POST['day']):"";
    	$Order = isset($_POST['order'])?FilterStr($_POST['order']):"totalpoints";
    	$OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
    	$WinMin = isset($_POST['winmin'])?FilterStr($_POST['winmin']):"";
    	$WinMax = isset($_POST['winmax'])?FilterStr($_POST['winmax']):"";
    
    	$sqlCount = "select Count(*) ";
    	$sqlCol = "SELECT b.uid,b.totalpoints,a.nickname,a.points,a.back,a.lock_points,a.logintime,a.loginip
					";
    	$whereIn = "";
    	$whereOut = "";
    	if($UserID != "")
    		$whereIn .= " and uid = '{$UserID}'";
    
    	switch($dayType)
    	{
    		case "0":
    			$whereIn .= " and DATE_FORMAT(`log_time`, '%Y-%m-%d') = CURDATE()";
    			break;
    		case "1":
    			$whereIn .= " and DATE_FORMAT(`log_time`, '%Y-%m-%d') = DATE_ADD(CURDATE(),INTERVAL -1 DAY)";
    			break;
    		case "2":
    			$whereIn .= " and DATE_FORMAT(`log_time`, '%Y-%m-%d') = DATE_ADD(CURDATE(),INTERVAL -2 DAY)";
    			break;
    		case "3":
    			$whereIn .= " and DATE_FORMAT(`log_time`, '%Y-%m-%d') >= DATE_ADD(CURDATE(),INTERVAL -7 DAY)";
    			break;
    		case "4":
    			$whereIn .= " and DATE_FORMAT(`log_time`, '%Y-%m-%d') >= DATE_ADD(CURDATE(),INTERVAL -1 month)";
    			break;
    		case "5":
    			$whereIn .= " and DATE_FORMAT(`log_time`, '%Y-%m-%d') >= DATE_ADD(CURDATE(),INTERVAL -3 month)";
    			break;
    		case "6":
    			$whereIn .= " and DATE_FORMAT(`log_time`, '%Y-%m-%d') >= DATE_ADD(CURDATE(),INTERVAL -6 month)";
    			break;
    		default:
    			break;
    	}
    	//输赢
    	$Field = "b.totalpoints";
    	$whereOut .= GetSqlBetween($Field,$WinMin,$WinMax,true);
    
    	$sqlFrom = " FROM
    	(
    	SELECT uid,SUM(amount) AS totalpoints
    	FROM score_log
    	where 1 = 1 and opr_type=21 {$whereIn}
    	GROUP BY uid
    	) b
    	LEFT OUTER JOIN users a
    	ON b.uid = a.id
    	WHERE 1=1 {$whereOut}";
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
    			//取得排序
    			$sqlOrder = (($Order == "") ? "" : " order by totalpoints {$OrderType}");
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
    			$total_winlose = 0;
    			$total_points = 0;
    			$total_bankpoints = 0;
    			$total_lockpoints = 0;
    			for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
    			{
    			//对返回数据进行包装
    			$arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
    				$arrRows[$i]["NickName"] = $row["nickname"];
    						$arrRows[$i]["totalpoints"] = "<font color='red'>" . Trans($row["totalpoints"]) . "</font>";
    			$arrRows[$i]["Points"] = Trans($row["points"]);
    			$arrRows[$i]["Back"] = Trans($row["back"]);
    					$arrRows[$i]["LockPoints"] = Trans($row["lock_points"]);
    			$arrRows[$i]["LastLoginTime"] = $row["logintime"];
    			 
    			$IPInfo = "<a href='http://www.ip138.com/ips.asp?ip={$row["loginip"]}' target='_blank'>". $row["loginip"] ."</a>";
    			$IPInfo .= "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["loginip"]}")."' target='_blank'>批</a>";
    			$arrRows[$i]["LastLoginIP"] = $IPInfo;
    			$arrRows[$i]["Opr"] = "<a class='edi' href=\"user_winlose_day.php?id={$row['uid']}&gametype={$GameType}\">查看每日输赢</a>";
        		$total_winlose += $row["totalpoints"];
    			$total_points += $row["points"];
        			$total_bankpoints += $row["back"];
        			$total_lockpoints += $row["lock_points"];
    			}
    
    			if($RowCount > 0)
    			{
    			$i = $RowCount + 1;
    					$arrRows[$i]["UserID"] = "";
    					$arrRows[$i]["NickName"] = "页小结:";
    					$arrRows[$i]["totalpoints"] = "<font color='red'>" . Trans($total_winlose) . "</font>";
    			$arrRows[$i]["Points"] = Trans($total_points);
    				$arrRows[$i]["Back"] = Trans($total_bankpoints);
    						$arrRows[$i]["LockPoints"] = Trans($total_lockpoints);
    				$arrRows[$i]["LastLoginTime"] = "";
        				$arrRows[$i]["LastLoginIP"] = "";
            						$arrRows[$i]["Opr"] = "";
    			}
    
    
    			//返回分页
    			require_once('inc/fenye.php');
    					$ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
    					$pageInfo = $ajaxpage->show();
    
    			$arrRows[0]["cmd"] = $act;
    			$arrRows[0]["msg"] = $pageInfo;
    			ArrayChangeEncode($arrRows);
    			//WriteLog(json_encode($arrRows));
    			echo json_encode($arrRows);
    			exit;
    }
    
    
	/* 取全压无开奖记录
	*
	*/
	function GetNoRewardLog($act)
	{
		global $db;
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
        $GameNo = intval($_POST['gameno']);
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $ScoreMin = isset($_POST['scoremin'])?FilterStr($_POST['scoremin']):"";
        $ScoreMax = isset($_POST['scoremax'])?FilterStr($_POST['scoremax']):"";
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT a.id,a.uid,b.nickname,a.points,a.back,a.lock_points,a.experience,a.change_points,
					a.thetime,a.remark,a.gameno,ifnull(c.game_name,'') as gamename
					";
		$sqlFrom = " FROM user_score_changelog a
					LEFT OUTER JOIN users b
					ON a.uid = b.id
					left outer join game_config c
					on a.gametype = c.game_type
					WHERE a.gametype > 0 and change_points = 0 and remark = '开奖后' ";
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
        if($UserID != "")
            $sqlWhere .= " and a.uid = " . $UserID;
        if($GameType != "-1")
            $sqlWhere .= " and a.gametype = " . $GameType;
        if($GameNo != "")
            $sqlWhere .= " and a.gameno = " . $GameNo;
        //时间
        $TimeField = "a.thetime";
        $sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
        //输赢
        $Field = "a.change_points";
        $sqlWhere .= GetSqlBetween($Field,$ScoreMin,$ScoreMax,true);
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
		$total_points = 0;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
			$arrRows[$i]["ID"] = $row["id"];
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            $arrRows[$i]["GameName"] = $row["gamename"];
			$arrRows[$i]["GameNo"] = ($row["gameno"] == 0) ? "-" : $row["gameno"];
			$arrRows[$i]["Remark"] = $row["remark"];
			$arrRows[$i]["TheTime"] = $row["thetime"];
			$arrRows[$i]["PointAfter"] = Trans($row["points"]);
			$arrRows[$i]["ChangeScore"] = Trans($row["change_points"]);
			$arrRows[$i]["Bank"] = Trans($row["back"]);
			$arrRows[$i]["LockPoints"] = Trans($row["lock_points"]);
			$arrRows[$i]["Exp"] = $row["experience"];
			$total_points += $row["points"];
		}
		
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
			$arrRows[$i]["ID"] = "";
            $arrRows[$i]["UserID"] = "";
            $arrRows[$i]["NickName"] = "";
            $arrRows[$i]["GameName"] = "";
			$arrRows[$i]["GameNo"] = "";
			$arrRows[$i]["Remark"] = "";
			$arrRows[$i]["TheTime"] = "";
			$arrRows[$i]["PointAfter"] = "页小计:";
			$arrRows[$i]["ChangeScore"] = Trans($total_points);
			$arrRows[$i]["Bank"] = "";
			$arrRows[$i]["LockPoints"] = "";
			$arrRows[$i]["Exp"] = "";
		}
		
		
		//返回分页
		require_once('inc/fenye.php');
		$ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page_NR','nowindex' => $page));
		$pageInfo = $ajaxpage->show();
		
		$arrRows[0]["cmd"] = $act;
		$arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows); 
        //WriteLog(json_encode($arrRows));
		echo json_encode($arrRows);
		exit;
    }
	/* 删除分值变化记录
	*
	*/
	function RemoveScoreChangeLog($act)
	{
		global $db;
		$m = intval($_POST['m']);
		$sql = "delete from user_score_changelog where thetime < date_add(curdate(),INTERVAL -{$m} day)";
		
        $result = $db->query($sql); 
    	$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		$arrReturn[0]["cmd"] = $act;
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	/* 取得用户分值变化记录
	*
	*/
	function GetGameScoreChangeLog($act)
	{
		global $db;
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
        $GameNo = intval($_POST['gameno']);
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $ScoreMin = isset($_POST['scoremin'])?FilterStr($_POST['scoremin']):"";
        $ScoreMax = isset($_POST['scoremax'])?FilterStr($_POST['scoremax']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT a.id,a.uid,b.nickname,a.points,a.back,a.lock_points,a.experience,a.change_points,
					a.thetime,a.remark,a.gameno,ifnull(c.game_name,'') as gamename
					";
		$sqlFrom = " FROM user_score_changelog a
					LEFT OUTER JOIN users b
					ON a.uid = b.id
					left outer join game_config c
					on a.gametype = c.game_type
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
        if($UserID != "")
            $sqlWhere .= " and a.uid = " . $UserID;
        if($IsExceptInner == 1)
        	$sqlWhere .= " and a.uid not in(select uid from users_inner)";
        if($GameType != "-1")
            $sqlWhere .= " and a.gametype = " . $GameType;
        if($GameNo != "")
            $sqlWhere .= " and a.gameno = " . $GameNo;
        //时间
        $TimeField = "a.thetime";
        $sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
        //输赢
        $Field = "a.change_points";
        $sqlWhere .= GetSqlBetween($Field,$ScoreMin,$ScoreMax,true);
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
		$total_points = 0;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
			$arrRows[$i]["ID"] = $row["id"];
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            $arrRows[$i]["GameName"] = $row["gamename"];
			$arrRows[$i]["GameNo"] = ($row["gameno"] == 0) ? "-" : $row["gameno"];
			$arrRows[$i]["Remark"] = $row["remark"];
			$arrRows[$i]["TheTime"] = $row["thetime"];
			$arrRows[$i]["PointAfter"] = Trans($row["points"]);
			$arrRows[$i]["ChangeScore"] = Trans($row["change_points"]);
			$arrRows[$i]["Bank"] = Trans($row["back"]);
			$arrRows[$i]["LockPoints"] = Trans($row["lock_points"]);
			$arrRows[$i]["Exp"] = $row["experience"];
			$total_points += $row["points"];
		}
		
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
			$arrRows[$i]["ID"] = "";
            $arrRows[$i]["UserID"] = "";
            $arrRows[$i]["NickName"] = "";
            $arrRows[$i]["GameName"] = "";
			$arrRows[$i]["GameNo"] = "";
			$arrRows[$i]["Remark"] = "";
			$arrRows[$i]["TheTime"] = "";
			$arrRows[$i]["PointAfter"] = "页小计:";
			$arrRows[$i]["ChangeScore"] = Trans($total_points);
			$arrRows[$i]["Bank"] = "";
			$arrRows[$i]["LockPoints"] = "";
			$arrRows[$i]["Exp"] = "";
		}
		
		
		//返回分页
		require_once('inc/fenye.php');
		$ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
		$pageInfo = $ajaxpage->show();
		
		$arrRows[0]["cmd"] = $act;
		$arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows); 
        //WriteLog(json_encode($arrRows));
		echo json_encode($arrRows);
		exit;
    }
	/* 取得游戏列表
	*
	*/
	function GetGameTypeIDScoreOption($act)
	{
		global $db;
		$sql = "SELECT game_type,game_name from game_config order by ordernum";
        $result = $db->query($sql);
		//取得返回记录数
		$RowCount = $db->num_rows($result);
        $Option = "<option value='-1'>其他类别</option>";
        if($RowCount > 0)
        {
            for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
            {
                $Option .= "<option value='{$row['game_type']}'>{$row['game_name']}</option>";
            }
        }
        //返回结果
        $arrRows = array(array());
          
        $arrRows[0]["cmd"] = $act;
		$arrRows[0]["msg"] = $Option;
        ArrayChangeEncode($arrRows);
		echo json_encode($arrRows);
	}
	/* 取得预投注历史记录
	*
	*/
	function GetAllGameKgHistory($act)
	{
		global $db;
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
        $NO = isset($_POST['no'])?FilterStr($_POST['no']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
		
		$TableName = "game28_users_tz";
        if($GameType != "") $TableName = $GameType . "_kg_users_tz";
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT a.uid,b.nickname,a.NO as NO,GROUP_CONCAT(a.tznum) as tznum,group_concat(a.tzpoints) as tzpoints,SUM(tzpoints) AS totalpoints,a.time";
		$sqlFrom = " FROM {$TableName} a
					LEFT OUTER JOIN users b
					ON a.uid = b.id
					WHERE 1=1 ";
		
	
       /* $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT a.uid,b.nickname,a.NO as NO,GROUP_CONCAT(a.tznum) as tznum,group_concat(a.tzpoints) as tzpoints,a.time,c.game_name
					";
		$sqlFrom = " FROM gameall_kg_users_tz_history a
					LEFT OUTER JOIN users b
					ON a.uid = b.id
					left outer join game_config c
					on a.gametype = c.game_type
					WHERE 1=1 ";*/
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
        if($UserID != "")
            $sqlWhere .= " and a.uid = " . $UserID;
        if($IsExceptInner == 1)
        	$sqlWhere .= " and b.usertype=0 and a.uid not in(select uid from users_inner)";
            
        if($NO != "") 
        	$sqlWhere .= " and a.NO = '" . $NO . "'";
        	
        /*if($GameType != "-1")
        	$sqlWhere .= " and a.gametype = '{$GameType}'";*/
        //时间
        $TimeField = "a.time";
        $sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
		//取得排序
		$sqlOrder = (($Order == "") ? "" : " order by uid,No desc, {$Order} {$OrderType}");
		//$sqlGroup=' group by a.NO,a.gametype ';
		$sqlGroup=' group by a.NO,a.uid';
		//取得总记录数
		$TotalCount = $db->GetRecordCount($sqlCount.$sqlFrom.$sqlWhere.$sqlGroup);
		//取记录
		$sql = $sqlCol . $sqlFrom . $sqlWhere .$sqlGroup. $sqlOrder . GetLimit($page,$PageSize);

        //WriteLog($sql);
        //return;
//echo $sql;
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
		$total_points = 0;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            $arrRows[$i]["GameName"] = $row["game_name"];
            $arrRows[$i]["No"] = $row["NO"];
			$arrRows[$i]["Time"] = $row["time"]; 
			$arrRows[$i]["Tznum"] = $row["tznum"];
			$arrRows[$i]["Tzpoints"] = ($row["tzpoints"]);
			$arrRows[$i]["Totalpoints"] = ($row["totalpoints"]);
			$total_points += $row["totalpoints"];
		}
		
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
			$arrRows[$i]["UserID"] = "";
			$arrRows[$i]["NickName"] = ""; 
			$arrRows[$i]["GameName"] = ""; 
            $arrRows[$i]["No"] = ""; 
            $arrRows[$i]["Time"] = "";
			$arrRows[$i]["Tznum"] = "";
			$arrRows[$i]["Tzpoints"] = "页小结:";
			$arrRows[$i]["Totalpoints"] = Trans($total_points);
		}
		
		
		//返回分页
		require_once('inc/fenye.php');
		$ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
		$pageInfo = $ajaxpage->show();
		
		$arrRows[0]["cmd"] = $act;
		$arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows); 
        //WriteLog(json_encode($arrRows));
		echo json_encode($arrRows);
		exit;
    }
	/* 删除过期输赢记录
	*
	*/
	function RemoveWinLoseLog($act)
	{
		global $db;
		$m = intval($_POST['m']);
		$sql = "delete from game_day_static where time < date_add(curdate(),INTERVAL -{$m} month)";
		
        $result = $db->query($sql); 
    	$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		$arrReturn[0]["cmd"] = $act;
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	/* 取得游戏每日输赢
	*
	*/
	function GetGameWinLoseLog($act)
	{
		global $db;
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $WinMin = isset($_POST['winmin'])?FilterStr($_POST['winmin']):"";
        $WinMax = isset($_POST['winmax'])?FilterStr($_POST['winmax']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT a.uid,b.nickname,a.time,a.points,c.game_name
					";
		$sqlFrom = " FROM game_day_static a
					LEFT OUTER JOIN users b
					ON a.uid = b.id
					left outer join game_config c
					on a.kindid = c.game_type
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
        if($UserID != "")
            $sqlWhere .= " and a.uid = " . $UserID;
        if($IsExceptInner == 1)
        	$sqlWhere .= " and a.uid not in(select uid from users_inner)";
        if($GameType != "-1")
            $sqlWhere .= " and a.kindid = " . $GameType;
        //时间
        $TimeField = "a.time";
        $sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
        //输赢
        $Field = "a.points";
        $sqlWhere .= GetSqlBetween($Field,$WinMin,$WinMax,true);
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
		$total_points = 0;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            $arrRows[$i]["theDate"] = $row["time"];
			$arrRows[$i]["GameName"] = $row["game_name"];
			$arrRows[$i]["WinLose"] = Trans($row["points"]);
			$total_points += $row["points"];
		}
		
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
			$arrRows[$i]["UserID"] = "";
			$arrRows[$i]["NickName"] = ""; 
            $arrRows[$i]["theDate"] = "";
			$arrRows[$i]["GameName"] = "页小结:";
			$arrRows[$i]["WinLose"] = Trans($total_points);
		}
		
		
		//返回分页
		require_once('inc/fenye.php');
		$ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
		$pageInfo = $ajaxpage->show();
		
		$arrRows[0]["cmd"] = $act;
		$arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows); 
        //WriteLog(json_encode($arrRows));
		echo json_encode($arrRows);
		exit;
    } 
	/* 清除在线
	*
	*/
	function ClearUserInGameStatus()
	{
		global $db;
        $GameType = intval($_POST['gametype']);
        $uid = intval($_POST['uid']);
        
        $sql = "update users set ingame=ingame ^ pow(2,{$GameType}) where id = '{$uid}'";
        $result = $db->query($sql); 
    	$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
        $sql='select game_table_prefix from game_config where game_type='.$GameType;
        $res=$db->fetch_first($sql);
        $sql='delete from '.$res['game_table_prefix'].'_auto where uid='.$uid;
        $db->query($sql);
		$arrReturn[0]["cmd"] = $act;
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	/* 删除开奖结果
	*
	*/
	function CatchResultRemove($act)
	{
		global $db;
		$day = intval($_POST['day']);
		if($day == 0) $day = 1; 
		$sql = "delete from game_result where `addtime` < date_add(curdate(),INTERVAL -{$day} day)";
		
        $result = $db->query($sql); 
    	$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		$arrReturn[0]["cmd"] = $act;
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	/* 重开奖
	*
	*/
	function CatchResultReopen($act)
	{
		global $db;
		$ID = intval($_POST['id']); 
		$sql = "update game_result set isopen = 0 where id = '{$ID}'";
		
        $result = $db->query($sql); 
    	$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		$arrReturn[0]["cmd"] = $act;
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	/* 取得开奖结果
	*
	*/
	function GetCatchResult($act)
	 {
		global $db;
        $GameKind = isset($_POST['gamekind'])?FilterStr($_POST['gamekind']):"";
        $No = isset($_POST['no'])?FilterStr($_POST['no']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT id,gametype,gameno,gameresult,addtime,isopen,opentime
					";
		$sqlFrom = " FROM game_result
					WHERE 1=1 ";
		$sqlWhere = "";
		$sqlOrder = "";
		$sql = "";
		//页大小
		$PageSize = isset($_POST['PageSize'])?$_POST['PageSize']:50;
		$PageSize = intval($PageSize);
		//页码
		$page = isset($_POST['Page'])?$_POST['Page']:1;
		$page =intval($page);

		$arrReturn = array(array()); 
		//取得查询条件
        if($GameKind != "")
            $sqlWhere .= " and gametype='{$GameKind}'";
        if($No != "")
        	$sqlWhere .= " and gameno='{$No}'";
        //时间
        $TimeField = "addtime";
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
            $arrRows[$i]["GameKind"] = $row["gametype"];
            $arrRows[$i]["No"] = $row["gameno"];
            $arrRows[$i]["Result"] = $row["gameresult"];
            $arrRows[$i]["CatchTime"] = $row["addtime"];
            $arrRows[$i]["IsOpen"] = ($row["isopen"] == 0) ? "<font color='#FF0000'>未开奖</font>":"<font color='#0000FF'>已开奖</font>"; 
            $arrRows[$i]["OpenTime"] = $row["opentime"]; 
            $arrRows[$i]["Opr"] = ($row["isopen"] == 0) ? "" : "<a style='cursor:pointer' title='重开奖' onclick=\"ReOpen({$row['id']})\">重开奖</a>"; 
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
	/* 删除一个月前系统消息
	*
	*/
	function RemoveSysMsgLog($act)
	 {
		global $db;
		$sql = "delete from system_msg where msg_time < date_add(curdate(),INTERVAL -1 month)";
		
        $result = $db->query($sql); 
    	$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		$arrReturn[0]["cmd"] = $act;
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	/* 取得系统提醒消息
	*
	*/
	function GetSystemMsgLog($act)
	 {
		global $db;
        $Content = isset($_POST['content'])?FilterStr($_POST['content']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT id,msg_type,msg_content,msg_time
					";
		$sqlFrom = " FROM system_msg
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
        if($Content != "")
            $sqlWhere .= " and msg_content like '%" . ChangeEncodeU2G($Content) ."%'";
        //时间
        $TimeField = "msg_time";
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
            $arrRows[$i]["ID"] = $row["id"];
            $arrRows[$i]["MsgType"] = $row["msg_type"];
            $arrRows[$i]["MsgTime"] = $row["msg_time"];
            $arrRows[$i]["Content"] = $row["msg_content"];
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
	/*取得正在下注用户
	*
	*/
	function GetPressUserList($act)
	 {
		global $db;
        $GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"-1";
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):""; 
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "select id,nickname,points,back,lock_points,mobile,experience,loginip,logintime,ingame
					";
		$sqlFrom = " from users
					 where usertype = 0 and ingame > 0 ";
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
        if($UserID != "")
            $sqlWhere .= " and id = " . $UserID;
        if($IsExceptInner == 1)
        	$sqlWhere .= " and id not in(select uid from users_inner)";
        if($GameType != "-1")
        {
			$IngameNum = pow(2,$GameType);
			$sqlWhere .= " and ingame & {$IngameNum} = {$IngameNum}";
        }
        
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
		$total_points = 0;
		$total_bankpoints = 0;
		$total_lockpoints = 0;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["id"]}")."' target='_blank'>{$row["id"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
			$arrRows[$i]["Mobile"] = $row["mobile"]; 
			$arrRows[$i]["Points"] = Trans($row["points"]);
			$arrRows[$i]["BankPoints"] = Trans($row["back"]);
			$arrRows[$i]["LockPoints"] = Trans($row["lock_points"]);
			$arrRows[$i]["Exp"] = Trans($row["experience"]);
			$arrRows[$i]["LoginTime"] = $row["logintime"];
			
			$IPInfo = "<a href='http://www.ip138.com/ips.asp?ip={$row["loginip"]}' target='_blank'>". $row["loginip"] ."</a>";
			$IPInfo .= "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["loginip"]}")."' target='_blank'>批</a>";
			$arrRows[$i]["LoginIP"] = $IPInfo;
			$arrTemp = GetInGameStr($row["id"],$row["ingame"]);
			$arrRows[$i]["InGame"] = $arrTemp["ingame"];
			$arrRows[$i]["Opr"] = $arrTemp["opr"];
			$total_points += $row["points"];
			$total_bankpoints += $row["back"];
			$total_lockpoints += $row["lock_points"];
		}
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
			$arrRows[$i]["UserID"] = "";
			$arrRows[$i]["NickName"] = ""; 
            $arrRows[$i]["Mobile"] = "页小结:"; 
            $arrRows[$i]["Points"] = Trans($total_points);
			$arrRows[$i]["BankPoints"] = Trans($total_bankpoints);
			$arrRows[$i]["LockPoints"] = Trans($total_lockpoints);
			$arrRows[$i]["Exp"] = "累计分:" . Trans($total_lockpoints + $total_points + $total_lockpoints);
			$arrRows[$i]["LoginTime"] = "";
			$arrRows[$i]["LoginIP"] = "";
			$arrRows[$i]["InGame"] = "";
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
    /* 取得所在游戏列表
    *
    */
    function GetInGameStr($userid,$ingamenum)
    {
		global $db;
		$arrRet = array("ingame"=>"","opr"=>"");
		$ingame = "";
		$Option = "";
		if($ingamenum == 0) return $ret;
		$sql = "select game_type,game_table_prefix,game_name from game_config order by ordernum";
		$result = $db->query($sql);
		while($rs = $db->fetch_array($result))
		{
			if(intval($ingamenum & pow(2,$rs["game_type"])) == intval(pow(2,$rs["game_type"])))
			{
				$ingame .= "<a class='edi' href='user_kg_gamelog.php?gametype={$rs['game_table_prefix']}&id={$userid}'><font color='#FF0000'>{$rs['game_name']}</font></a> &nbsp;";
			    $Option .= "<option value='{$rs['game_type']}'>{$rs['game_name']}</option>";
			}
		}
		if($ingame != "")
		{  
			$arrRet["ingame"] = $ingame;
			$arrRet["opr"] = "<select id = 'sltIngame_{$userid}'>".$Option . "</select>" .
							"<br><a style='cursor:pointer' title='清除在线' onclick=\"ClearInGame({$userid})\" > 清除 </a>";
		}
		return $arrRet;
    }
	/* 取得游戏列表按id
	*
	*/
	function GetGameTypeIDOption($act)
	{
		global $db;
		$sql = "SELECT game_type,game_name from game_config order by ordernum";
        $result = $db->query($sql);
		//取得返回记录数
		$RowCount = $db->num_rows($result);
        $Option = "<option value='-1'>所有游戏</option>";
        if($RowCount > 0)
        {
            for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
            {
                $Option .= "<option value='{$row['game_type']}'>{$row['game_name']}</option>";
            }
        }
        //返回结果
        $arrRows = array(array());
          
        $arrRows[0]["cmd"] = $act;
		$arrRows[0]["msg"] = $Option;
        ArrayChangeEncode($arrRows);
		echo json_encode($arrRows);
	}
	/* 取得未开奖记录
	*
	*/
	function GetGameKgLog($act)
	{
		global $db;
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
        $NO = isset($_POST['no'])?FilterStr($_POST['no']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
        $usertype = intval($_POST['usertype']);
        $TableName = "game28_users_tz";
        if($GameType != "") $TableName = $GameType . "_kg_users_tz";
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT a.uid,b.nickname,a.NO,a.tznum,a.tzpoints,a.time
					";
		$sqlFrom = " FROM {$TableName} a
					LEFT OUTER JOIN users b
					ON a.uid = b.id
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
        if($UserID != "")
            $sqlWhere .= " and a.uid = " . $UserID;
        if($IsExceptInner == 1)
        	$sqlWhere .= " and a.usertype=0 and a.uid not in(select uid from users_inner)";
            
        if($NO != "") 
        	$sqlWhere .= " and a.NO = '" . $NO . "'";
        //时间
        $TimeField = "a.time";
        $sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
		//取得排序
		$sqlOrder = (($Order == "") ? "" : " order by uid,No desc, {$Order} {$OrderType}");
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
		$total_points = 0;
		
		$sql="select game_type,game_model from game_config where game_table_prefix='$GameType'";
		$gt=$db->fetch_first($sql);
		$gt['game_model'] = explode(",",$gt['game_model']);
		
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            $arrRows[$i]["No"] = $row["NO"];
			$arrRows[$i]["Time"] = $row["time"]; 
			if(in_array($gt['game_type'], [25,26,27,28,29,30,31,36,37,41,42])){//外围，定位，赛车，农场，时时彩
					$prefix = "";
				
					if(in_array($gt['game_type'],[26,28,31,42]) && $row["tznum"]>=13 && $row["tznum"]<=26) $prefix = "(1球)";
					if(in_array($gt['game_type'],[26,28,31,42]) && $row["tznum"]>=27 && $row["tznum"]<=40) $prefix = "(2球)";
					if(in_array($gt['game_type'],[26,28,31,42]) && $row["tznum"]>=41 && $row["tznum"]<=54) $prefix = "(3球)";
					
					if(in_array($gt['game_type'],[29]) && $row["tznum"]>=21 && $row["tznum"]<=34) $prefix = "(1球)";
					if(in_array($gt['game_type'],[29]) && $row["tznum"]>=35 && $row["tznum"]<=48) $prefix = "(2球)";
					if(in_array($gt['game_type'],[29]) && $row["tznum"]>=49 && $row["tznum"]<=62) $prefix = "(3球)";
					if(in_array($gt['game_type'],[29]) && $row["tznum"]>=63 && $row["tznum"]<=76) $prefix = "(4球)";
					if(in_array($gt['game_type'],[29]) && $row["tznum"]>=77 && $row["tznum"]<=90) $prefix = "(5球)";
					if(in_array($gt['game_type'],[29]) && $row["tznum"]>=91 && $row["tznum"]<=104) $prefix = "(6球)";
					if(in_array($gt['game_type'],[29]) && $row["tznum"]>=105 && $row["tznum"]<=118) $prefix = "(7球)";
					if(in_array($gt['game_type'],[29]) && $row["tznum"]>=119 && $row["tznum"]<=132) $prefix = "(8球)";
					if(in_array($gt['game_type'],[29]) && $row["tznum"]>=133 && $row["tznum"]<=146) $prefix = "(9球)";
					if(in_array($gt['game_type'],[29]) && $row["tznum"]>=147 && $row["tznum"]<=160) $prefix = "(10球)";
					if(in_array($gt['game_type'],[29]) && $row["tznum"]>=161 && $row["tznum"]<=162) $prefix = "(1v10)";
					if(in_array($gt['game_type'],[29]) && $row["tznum"]>=163 && $row["tznum"]<=164) $prefix = "(2v9)";
					if(in_array($gt['game_type'],[29]) && $row["tznum"]>=165 && $row["tznum"]<=166) $prefix = "(3v8)";
					if(in_array($gt['game_type'],[29]) && $row["tznum"]>=167 && $row["tznum"]<=168) $prefix = "(4v7)";
					if(in_array($gt['game_type'],[29]) && $row["tznum"]>=169 && $row["tznum"]<=170) $prefix = "(5v6)";
					
					if(in_array($gt['game_type'],[36]) && $row["tznum"]>=6 && $row["tznum"]<=37) $prefix = "(1球)";
					if(in_array($gt['game_type'],[36]) && $row["tznum"]>=38 && $row["tznum"]<=69) $prefix = "(2球)";
					if(in_array($gt['game_type'],[36]) && $row["tznum"]>=70 && $row["tznum"]<=101) $prefix = "(3球)";
					if(in_array($gt['game_type'],[36]) && $row["tznum"]>=102 && $row["tznum"]<=133) $prefix = "(4球)";
					if(in_array($gt['game_type'],[36]) && $row["tznum"]>=134 && $row["tznum"]<=165) $prefix = "(5球)";
					if(in_array($gt['game_type'],[36]) && $row["tznum"]>=166 && $row["tznum"]<=197) $prefix = "(6球)";
					if(in_array($gt['game_type'],[36]) && $row["tznum"]>=198 && $row["tznum"]<=229) $prefix = "(7球)";
					if(in_array($gt['game_type'],[36]) && $row["tznum"]>=230 && $row["tznum"]<=261) $prefix = "(8球)";
					if(in_array($gt['game_type'],[36]) && $row["tznum"]>=262 && $row["tznum"]<=263) $prefix = "(1v8)";
					if(in_array($gt['game_type'],[36]) && $row["tznum"]>=264 && $row["tznum"]<=265) $prefix = "(2v7)";
					if(in_array($gt['game_type'],[36]) && $row["tznum"]>=266 && $row["tznum"]<=267) $prefix = "(3v6)";
					if(in_array($gt['game_type'],[36]) && $row["tznum"]>=268 && $row["tznum"]<=269) $prefix = "(4v5)";
					
					
					if(in_array($gt['game_type'],[37]) && $row["tznum"]>=7 && $row["tznum"]<=20) $prefix = "(1球)";
					if(in_array($gt['game_type'],[37]) && $row["tznum"]>=21 && $row["tznum"]<=34) $prefix = "(2球)";
					if(in_array($gt['game_type'],[37]) && $row["tznum"]>=35 && $row["tznum"]<=48) $prefix = "(3球)";
					if(in_array($gt['game_type'],[37]) && $row["tznum"]>=49 && $row["tznum"]<=62) $prefix = "(4球)";
					if(in_array($gt['game_type'],[37]) && $row["tznum"]>=63 && $row["tznum"]<=76) $prefix = "(5球)";
					if(in_array($gt['game_type'],[37]) && $row["tznum"]>=77 && $row["tznum"]<=81) $prefix = "(前3)";
					if(in_array($gt['game_type'],[37]) && $row["tznum"]>=82 && $row["tznum"]<=86) $prefix = "(中3)";
					if(in_array($gt['game_type'],[37]) && $row["tznum"]>=87 && $row["tznum"]<=91) $prefix = "(后3)";
					
				
				$arrRows[$i]["Tznum"] = $prefix . $gt['game_model'][$row["tznum"]];
			}else{
				$arrRows[$i]["Tznum"] = $row["tznum"];
			}
			$arrRows[$i]["Tzpoints"] = Trans($row["tzpoints"]);
			$total_points += $row["tzpoints"];
		}
		
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
			$arrRows[$i]["UserID"] = "";
			$arrRows[$i]["NickName"] = ""; 
            $arrRows[$i]["No"] = ""; 
            $arrRows[$i]["Time"] = "";
			$arrRows[$i]["Tznum"] = "页小结:";
			$arrRows[$i]["Tzpoints"] = Trans($total_points);
		}
		
		
		//返回分页
		require_once('inc/fenye.php');
		$ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
		$pageInfo = $ajaxpage->show();
		
		$arrRows[0]["cmd"] = $act;
		$arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows); 
        //WriteLog(json_encode($arrRows));
		echo json_encode($arrRows);
		exit;
    }
	/* 删除信息
	*
	*/
	function RemoveMsg($act)
	{
		global $db;
        $type = isset($_POST['type'])?FilterStr($_POST['type']):"";
        $m = isset($_POST['m'])?FilterStr($_POST['m']):"";
        $arrReturn = array(array("cmd"=>"","msg"=>""));
		if($type == "" || $m == "")
		{
			$arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "参数错误!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
		}
		$sql = "";
		if($type == "time")
		{
			$sql = "delete from msg where `time` < date_add(curdate(),INTERVAL -1 month) and look=1";
		}
		else
		{
			$sql = "delete from msg where id in({$m})";
		}
        $result = $db->query($sql); 
    	$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		$arrReturn[0]["cmd"] = $act;
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	/* 信息记录
	*
	*/
	function GetMsgLog($act)
	{
		global $db;
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $MsgType = isset($_POST['msgtype'])?FilterStr($_POST['msgtype']):"-1";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT a.id,CASE WHEN a.usersid = 0 THEN 0 ELSE 1 END AS msgtype, a.usersid,IFNULL(b.nickname,'') AS send_nickname,a.title,a.mag,a.mid,IFNULL(c.nickname,'') AS rec_nickname,a.time,a.look
					";
		$sqlFrom = " FROM msg a
					LEFT OUTER JOIN users b
					ON a.usersid = b.id
					LEFT OUTER JOIN users c
					ON a.mid = c.id
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
        if($UserID != "")
            $sqlWhere .= " and a.usersid = " . $UserID . " or a.mid = {$UserID}";
        if($MsgType == "0")
            $sqlWhere .= " and a.usersid = 0";
        elseif($MsgType == "1")
        	$sqlWhere .= " and a.usersid > 0";
        //时间
        $TimeField = "a.time";
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
            $arrRows[$i]["CheckBox"] = "<input name='cbxID' id='cbxID' type='checkbox' value='" . $row["id"] ."'>";
            if($row["msgtype"] == 1)
            	$arrRows[$i]["Sender"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["usersid"]}")."' target='_blank'>{$row["usersid"]}({$row["send_nickname"]})</a>";
            else
            	$arrRows[$i]["Sender"] = "系统";
            
            $arrRows[$i]["Title"] = $row["title"];
            $arrRows[$i]["Content"] = $row["mag"];
            $arrRows[$i]["Receiver"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["mid"]}")."' target='_blank'>{$row["mid"]}({$row["rec_nickname"]})</a>"; 
            $arrRows[$i]["SendTime"] = $row["time"];
            if($row["msgtype"] == 1)
				$arrRows[$i]["Status"] = ($row["look"] == 0) ? "未读":"已读";
			else
				$arrRows[$i]["Status"] = "--";
			$arrRows[$i]["Opr"] = "<a style='cursor:pointer' title='删除' onclick=\"RemoveMsg('t',{$row['id']})\">删除</a>"; 
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
	/* 发送站内短信
	*
	*/
	function SendMsg($act)
	{
		global $db;
		$type = isset($_POST['type'])?FilterStr($_POST['type']):"";
		$userid = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
		$subject = isset($_POST['subject'])?FilterStr($_POST['subject']):"";
		$content = isset($_POST['content'])?FilterStr($_POST['content']):"";
		$arrReturn = array(array("cmd"=>"","msg"=>""));
		if($type == "")
		{
			$arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "参数错误!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
		}
		if($subject == "")
		{
			$arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "请输入主题!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
		}
		if($content == "")
		{
			$arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "参数正文!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
		}  
		if($type == "1" && !is_numeric($userid))
		{
			$arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "请输入数字的用户ID!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
		}
		$subject = ChangeEncodeU2G($subject);
		$content = ChangeEncodeU2G($content);
		$sql = "";
		if($type == "0")
		{
			$sql = "insert into msg(usersid,title,mag,mid,`time`)
						values(0,'{$subject}','{$content}',0,now())";
		}
		elseif($type == "1")
		{
			$sql = "insert into msg(usersid,title,mag,mid,`time`)
						values(0,'{$subject}','{$content}',{$userid},now())";
		}
		else
		{
			$sql = "insert into msg(usersid,title,mag,mid,`time`)
						select 0,'{$subject}','{$content}',id,now()
						from users 
						where vip = 1";
		}
		$result = $db->query($sql); 
    	$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		$arrReturn[0]["cmd"] = $act;
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	/* 更改用户状态，冻结/解封
	*
	*/
	function ChangeUserStatus($t)
	{
		global $db;
		$userids = isset($_POST['id'])?FilterStr($_POST['id']):"";
		$Reason = isset($_POST['reason'])?FilterStr($_POST['reason']):"";
		$BlockType = 0;
		if($t == "forbidden")
		{
			$BlockType = 1;
			$Reason = $_SESSION["Admin_Name"] . date("Y-m-d H:i:s") . ":"  . $Reason;
		}
		else
		{
			$BlockType = 0;
			$Reason = '';
		}
		$arrReturn = array(array("cmd"=>"","msg"=>""));
        if($userids == "")
        {
            $arrReturn[0]["cmd"] = "errcmd";
            $arrReturn[0]["msg"] = "数据非法!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
        }
        
        $sql = "call web_blockusers('{$userids}',{$BlockType},'{$Reason}')";
        //WriteLog($sql);
        //return;
        $arr = $db->Mysqli_Multi_Query($sql);
        $Return = $arr[0][0]["result"];
        $cmd = "forbidden";
        $msg = "";
        if($Return == "0")
        { 
        	WriteLog( $_SESSION["Admin_UserID"] . ":" . usersip() . " : " . $sql);
            $msg = "操作成功!";
        }
        else
        {
            $msg = "由于数据库执行错误，执行失败!";
        }
        $arrReturn[0]["cmd"] = $cmd;
        $arrReturn[0]["msg"] = $msg;
        echo json_encode($arrReturn);
        return;
		
	}
	
	
	function ChangeUserRebate($t){
		global $db;
		$userids = isset($_POST['id'])?FilterStr($_POST['id']):"";
		$BlockType = 0;
		if($t == "close")
		{
			$BlockType = 1;
		}
		else
		{
			$BlockType = 0;
		}
		$arrReturn = array(array("cmd"=>"","msg"=>""));
		if($userids == "")
		{
			$arrReturn[0]["cmd"] = "errcmd";
			$arrReturn[0]["msg"] = "数据非法!";
			ArrayChangeEncode($arrReturn);
			echo json_encode($arrReturn);
			return;
		}
		
		$sql = "update users set dj_rebate={$BlockType} where id in({$userids})";
		$Return = $db->exec($sql);
		
		$cmd = "close";
		$msg = "";
		if($Return === false)
		{
			$msg = "由于数据库执行错误，执行失败!";
		}
		else
		{
			$msg = "操作成功!";
		}
		$arrReturn[0]["cmd"] = $cmd;
		$arrReturn[0]["msg"] = $msg;
		echo json_encode($arrReturn);
		return;
	}
	
	
	
	function SendSMS($to, $datas=array(), $tempId = 135079) {
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
			return true;
		}
		return false;
	}
	
	
	function PostSMS($mob,$msg){
		$msg = urlencode($msg);
		$url="http://utf8.sms.webchinese.cn/?Uid=vichiba&Key=cbbfe5a1da58cdf1ff2e&smsMob=$mob&smsText=$msg";
		$str=file_get_contents($url);
        $num=intval($str);
		if($num>0){
			return true;
		}
		
		return false;
	}
	
	
	/* 发短信红包
	 *
	*/
	function SendPack($act)
	{
		global $db;
		$userids = isset($_POST['id'])?FilterStr($_POST['id']):"";
		$packcode = $_POST['packcode'];
		
		$sql = "select mobile from users where id in({$userids})";
		$result = $db->query($sql);
		while($row=$db->fetch_array($result)){
			$mobile = $row['mobile'];
			$msg = "【赢客网】回馈玩家红包大放送，红包码：{$packcode}";
			$Return = PostSMS($mobile,$msg);
		}

		
		//$Return = true;
		
		$cmd = "sendpack";
		$msg = "";
		if($Return === false)
		{
			$msg = "发送失败!";
		}
		else
		{
			$msg = "发送成功!";
		}
		$arrReturn[0]["cmd"] = $cmd;
		$arrReturn[0]["msg"] = $msg;
		echo json_encode($arrReturn);
		return;
	}
	
	/* 取批用户信息
    *
    */
    function GetPatchUserInfo($act)
    {
    	global $db,$web_pwd_encrypt_prefix;
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $Status = isset($_POST['status'])?FilterStr($_POST['status']):"-1";
        $UserType = isset($_POST['usertype'])?FilterStr($_POST['usertype']):"-1";
        $NickName = isset($_POST['nickname'])?FilterStr($_POST['nickname']):"";
        $Email = isset($_POST['email'])?FilterStr($_POST['email']):"";
        $Mobile = isset($_POST['mobile'])?FilterStr($_POST['mobile']):"";
        $LoginIP = isset($_POST['loginip'])?FilterStr($_POST['loginip']):"";
        $RegIP = isset($_POST['regip'])?FilterStr($_POST['regip']):"";
        $CheckEmail = isset($_POST['checkemail'])?FilterStr($_POST['checkemail']):""; 
        $CheckMobile = isset($_POST['checkmobile'])?FilterStr($_POST['checkmobile']):"";
        $LoginPwd = isset($_POST['loginpwd'])?FilterStr($_POST['loginpwd']):"";
        $BankPwd = isset($_POST['bankpwd'])?FilterStr($_POST['bankpwd']):""; 
        $UserName = isset($_POST['username'])?FilterStr($_POST['username']):""; 
        $PointMin = isset($_POST['pointmin'])?FilterStr($_POST['pointmin']):"";
        $PointMax = isset($_POST['pointmax'])?FilterStr($_POST['pointmax']):"";
        $BankPointMin = isset($_POST['bankpointmin'])?FilterStr($_POST['bankpointmin']):"";
        $BankPointMax = isset($_POST['bankpointmax'])?FilterStr($_POST['bankpointmax']):"";
        $TotalPointMin = isset($_POST['totalpointmin'])?FilterStr($_POST['totalpointmin']):"";
        $TotalPointMax = isset($_POST['totalpointmax'])?FilterStr($_POST['totalpointmax']):"";
        $TotalExpMin = isset($_POST['totalexpmin'])?FilterStr($_POST['totalexpmin']):"";
        $TotalExpMax = isset($_POST['totalexpmax'])?FilterStr($_POST['totalexpmax']):"";
        $TotalChargeMin = isset($_POST['totalchargemin'])?FilterStr($_POST['totalchargemin']):"";
        $TotalChargeMax = isset($_POST['totalchargemax'])?FilterStr($_POST['totalchargemax']):"";
        $RegTimeMin = isset($_POST['regtimemin'])?FilterStr($_POST['regtimemin']):"";
        $RegTimeMax = isset($_POST['regtimemax'])?FilterStr($_POST['regtimemax']):"";
        $LoginTimeMin = isset($_POST['logintimemin'])?FilterStr($_POST['logintimemin']):"";
        $LoginTimeMax = isset($_POST['logintimemax'])?FilterStr($_POST['logintimemax']):"";
        $ExpMin = isset($_POST['expmin'])?FilterStr($_POST['expmin']):"";
        $ExpMax = isset($_POST['expmax'])?FilterStr($_POST['expmax']):"";
        $IsExceptInner = isset($_POST['isexceptinner'])?FilterStr($_POST['isexceptinner']):"0"; 
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";

        $sqlCount = "select Count(*) ";
        $sqlCol = "SELECT * ";
        $sqlFrom = " FROM (
        			select t.*,(points+back+lock_points) AS totalpoint
        			from users t
        			) a
                    where 1=1 ";
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
        if($IsExceptInner == 1)
        	$sqlWhere .= " and id NOT IN(SELECT uid FROM users_inner)";
        
        if($TotalChargeMin != "" || $TotalChargeMax != ""){
        	$sqlWhere .= " and id in(select uid from game_static where typeid=101 ";
        	if($TotalChargeMin != "") $sqlWhere .= " and points >={$TotalChargeMin} ";
        	if($TotalChargeMin != "") $sqlWhere .= " and points <={$TotalChargeMax} ";
        	$sqlWhere .= "  )";
        }
        
        
        if($UserID != "")
            $sqlWhere .= " and id like '%{$UserID}%'";
        if($Status != "-1")  
            $sqlWhere .= " and dj = " . $Status;
        if($UserType != "-1")  
            $sqlWhere .= " and usertype = " . $UserType;
        if($NickName != "")           
            $sqlWhere .= " and nickname like '%". ChangeEncodeU2G($NickName) ."%'";
        if($Email != "")        
            $sqlWhere .= " and email like '%". ChangeEncodeU2G($Email) ."%'";
        if($Mobile != "")
            $sqlWhere .= " and mobile like '%". ChangeEncodeU2G($Mobile) ."%'";
        if($LoginIP != "")
            $sqlWhere .= " and loginip like '%{$LoginIP}%'";
        if($RegIP != "")
            $sqlWhere .= " and regip like '%". ChangeEncodeU2G($RegIP) ."%'";
        if($CheckEmail != "")
            $sqlWhere .= " and is_check_email = 1";
        if($CheckMobile != "")
            $sqlWhere .= " and is_check_mobile = 1";
        if($LoginPwd != "")
        {
        	if(strlen($LoginPwd) == 32)
            	$sqlWhere .= " and password = '{$LoginPwd}'";
            else
            	$sqlWhere .= " and password = '" . md5($web_pwd_encrypt_prefix.$LoginPwd) ."'";
		}
		if($BankPwd != "")
        {
        	if(strlen($BankPwd) == 32)
            	$sqlWhere .= " and bankpwd = '{$BankPwd}'";
            else
            	$sqlWhere .= " and bankpwd = '" . md5($web_pwd_encrypt_prefix.$BankPwd) ."'";
		}
        
        $theField = "points";
        $sqlWhere .= GetSqlBetween($theField,$PointMin,$PointMax,true);
        $theField = "back";
        $sqlWhere .= GetSqlBetween($theField,$BankPointMin,$BankPointMax,true);
        $theField = "totalpoint";
        $sqlWhere .= GetSqlBetween($theField,$TotalPointMin,$TotalPointMax,true);
        $theField = "maxexperience";
        $sqlWhere .= GetSqlBetween($theField,$TotalExpMin,$TotalExpMax,true);
        $theField = "experience";
        $sqlWhere .= GetSqlBetween($theField,$ExpMin,$ExpMax,true);
        
        $theField = "time";
        $sqlWhere .= GetSqlBetween($theField,$RegTimeMin,$RegTimeMax,false);
        $theField = "logintime";
        $sqlWhere .= GetSqlBetween($theField,$LoginTimeMin,$LoginTimeMax,false);
        
        
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
        //WriteLog($sql); 
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
        $l_pageTotalPoints = 0;
        $l_pageTotalBankPoints = 0;
        $l_pageTotalLockPoints = 0;
        $l_pageTotalExp = 0;
        $l_pageTotalMaxExp = 0;
        $l_pageAllPoint = 0;
        for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
        {    
            //对返回数据进行包装
            $arrRows[$i]["strCheckBox"] = "<input name='cbxID' id='cbxID' type='checkbox' value='" . $row["id"] ."'>"; 
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["id"]}")."' target='_blank'>{$row["id"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            $arrRows[$i]["Mobile"] = $row["mobile"];
            $arrRows[$i]["UserName"] = $row["username"];
            $arrRows[$i]["QQ"] = $row["qq"];
            $arrRows[$i]["CashName"] = $row["recv_cash_name"];
            $arrRows[$i]["Email"] = $row["email"];
            
            $IPInfo = "";
            if($row["regip"] != "")
            {
                $IPInfo = "<a href='http://www.ip138.com/ips.asp?ip={$row["regip"]}' target='_blank'>". $row["regip"] ."</a>";
                $IPInfo .= "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=regip&word={$row["regip"]}")."' target='_blank'>批</a>";
            }
            $arrRows[$i]["RegIP"] = $IPInfo; 
            $arrRows[$i]["RegTime"] = $row["time"];
            $arrRows[$i]["LoginTime"] = $row["logintime"];
            
            $IPInfo = "";
            if($row["loginip"] != "")
            {
                $IPInfo = "<a href='http://www.ip138.com/ips.asp?ip={$row["loginip"]}' target='_blank'>". $row["loginip"] ."</a>";
                $IPInfo .= "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["loginip"]}")."' target='_blank'>批</a>";
            }
            $arrRows[$i]["LoginIP"] = $IPInfo;
            
            $arrRows[$i]["Points"] = Trans($row["points"]);
            $arrRows[$i]["BankPoints"] = Trans($row["back"]);
            $arrRows[$i]["LockPoints"] = Trans($row["lock_points"]);
            $arrRows[$i]["TotalPoints"] = Trans($row["totalpoint"]);
            $arrRows[$i]["TotalExp"] = Trans($row["maxexperience"]);
			$arrRows[$i]["Exp"] = Trans($row["experience"]);
            $arrRows[$i]["State"] = (($row["dj"] == 0) ? "正常":"<font color='red'>冻结</font>");
            $arrRows[$i]["Reason"] = $row["djly"];
            $arrRows[$i]["DjRebate"] = (($row["dj_rebate"] == 0) ? "正常":"<font color='red'>冻结</font>");
            
            
            $l_pageTotalPoints += $row["points"];
	        $l_pageTotalBankPoints += $row["back"];
	        $l_pageTotalLockPoints += $row["lock_points"];
	        $l_pageTotalExp += $row["experience"];
	        $l_pageTotalMaxExp += $row["maxexperience"];
	        $l_pageAllPoint += $row["totalpoint"];
        }
        if($RowCount > 1)
        {
        	$i = $RowCount + 1;
            $arrRows[$i]["strCheckBox"] = ""; 
            $arrRows[$i]["UserID"] = ""; 
            $arrRows[$i]["NickName"] = "";
            $arrRows[$i]["UserName"] = "";
            $arrRows[$i]["QQ"] = "";
            $arrRows[$i]["CashName"] = ""; 
            $arrRows[$i]["Mobile"] = ""; 
            $arrRows[$i]["Email"] = "";
            $arrRows[$i]["RegIP"] = ""; 
            $arrRows[$i]["RegTime"] = ""; 
            $arrRows[$i]["LoginTime"] = ""; 
            $arrRows[$i]["LoginIP"] = "页小计:";
            $arrRows[$i]["Points"] = Trans($l_pageTotalPoints);
            $arrRows[$i]["BankPoints"] = Trans($l_pageTotalBankPoints);
            $arrRows[$i]["LockPoints"] = Trans($l_pageTotalLockPoints);
            $arrRows[$i]["TotalPoints"] = Trans($l_pageAllPoint);
            $arrRows[$i]["Exp"] = Trans($l_pageTotalExp);
            $arrRows[$i]["TotalExp"] = Trans($l_pageTotalMaxExp);
            $arrRows[$i]["State"] = ""; 
            $arrRows[$i]["Reason"] = ""; 
            $arrRows[$i]["DjRebate"] = ""; 
        }
        
        //返回分页
        require_once('inc/fenye.php');
        $ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
        $pageInfo = $ajaxpage->show();
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows);
        //WriteLog(var_export($arrRows,true));
        echo json_encode($arrRows);
        exit;
    }
	/* 取得游戏投注
	*
	*/
	function GetGameLog($act)
	{
		global $db;
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $UserType = isset($_POST['usertype'])?FilterStr($_POST['usertype']):"-1";
        $GameType = isset($_POST['gametype'])?FilterStr($_POST['gametype']):"";
        $NO = isset($_POST['no'])?FilterStr($_POST['no']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);

		$sql="select game_type,game_model,game_table_prefix from game_config where game_table_prefix='$GameType'";
		$gt=$db->fetch_first($sql);
		$gt['game_model'] = explode(",",$gt['game_model']);
		$game_table_prefix = $gt['game_table_prefix'];
		
		//取标准赔率
		$reward_num_type = GetGameOddsType($gt['game_type']);
		$sql = "SELECT GROUP_CONCAT(num SEPARATOR '|') AS strnum,GROUP_CONCAT(odds SEPARATOR '|') AS strodds FROM gameodds WHERE game_type = '{$reward_num_type}' ORDER BY num";
		$result = $db->query($sql);
		if($rs = $db->fetch_array($result))
		{
			$arrStdNums = explode("|",$rs['strnum']);
			$arrStdOdds = explode("|",$rs['strodds']);
		}
        
        $TableName = "game28_users_tz";
        if($GameType != "") $TableName = $GameType . "_users_tz";

        $sqlCount = "select Count(*) ";
        
        if(in_array($gt['game_type'] , [24,23,0,22,1,2,15]))
			$sqlCol = "SELECT t.kgtime,g.zjpl as yzjpl,a.uid,b.nickname,a.NO,a.tznum,a.tzpoints,a.zjpoints,a.time,a.points,a.hdpoints,a.zjpl,g.pre_kgjg";
        else	
			$sqlCol = "SELECT t.kgtime,g.zjpl as yzjpl,a.uid,b.nickname,a.NO,a.tznum,a.tzpoints,a.zjpoints,a.time,a.points,a.hdpoints,a.zjpl";
		
		$sqlFrom = " FROM {$TableName} a,users b,".$GameType." g,{$game_table_prefix} t
					where a.NO=t.id and a.uid = b.id and g.gfid=a.NO ";
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
        if($UserID != "")
            $sqlWhere .= " and a.uid = " . $UserID;
        if($IsExceptInner == 1)
        	$sqlWhere .= " and a.uid not in(select uid from users_inner)";
            
        if($NO != "") 
        	$sqlWhere .= " and a.NO = '" . $NO . "'";
        if($UserType != "-1")
        	$sqlWhere .= " and b.usertype = '{$UserType}'";
        //时间
        $TimeField = "a.time";
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
		$total_points = 0;
		$total_hdpoints = 0;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{
			//对返回数据进行包装
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            $arrRows[$i]["No"] = $row["NO"];
			$arrRows[$i]["GameTime"] = $row["time"]; 
			$arrRows[$i]["Points"] = Trans($row["points"]);
			$arrRows[$i]["HdPoints"] = Trans($row["hdpoints"]);
			$arrRows[$i]['spoint']=$row['hdpoints']-$row['points'];

			$e_zj=explode('|',$row['zjpoints'] );
			$e_tz=explode('|',$row['tzpoints'] );
			$e_tznum=explode('|',$row['tznum'] );
			$e_tznum_alias=$e_tznum;
			if(in_array($gt['game_type'], [25,26,27,28,29,30,31,36,37,41,42])){//外围，定位，赛车，农场，时时彩
				foreach($e_tznum_alias as $idx=>&$e_tznum_item){
					$prefix = "";
					if(in_array($gt['game_type'],[26,28,31,42]) && $e_tznum_item>=13 && $e_tznum_item<=26) $prefix = "(1球)";
					if(in_array($gt['game_type'],[26,28,31,42]) && $e_tznum_item>=27 && $e_tznum_item<=40) $prefix = "(2球)";
					if(in_array($gt['game_type'],[26,28,31,42]) && $e_tznum_item>=41 && $e_tznum_item<=54) $prefix = "(3球)";
					
					if(in_array($gt['game_type'],[29]) && $e_tznum_item>=21 && $e_tznum_item<=34) $prefix = "(1球)";
					if(in_array($gt['game_type'],[29]) && $e_tznum_item>=35 && $e_tznum_item<=48) $prefix = "(2球)";
					if(in_array($gt['game_type'],[29]) && $e_tznum_item>=49 && $e_tznum_item<=62) $prefix = "(3球)";
					if(in_array($gt['game_type'],[29]) && $e_tznum_item>=63 && $e_tznum_item<=76) $prefix = "(4球)";
					if(in_array($gt['game_type'],[29]) && $e_tznum_item>=77 && $e_tznum_item<=90) $prefix = "(5球)";
					if(in_array($gt['game_type'],[29]) && $e_tznum_item>=91 && $e_tznum_item<=104) $prefix = "(6球)";
					if(in_array($gt['game_type'],[29]) && $e_tznum_item>=105 && $e_tznum_item<=118) $prefix = "(7球)";
					if(in_array($gt['game_type'],[29]) && $e_tznum_item>=119 && $e_tznum_item<=132) $prefix = "(8球)";
					if(in_array($gt['game_type'],[29]) && $e_tznum_item>=133 && $e_tznum_item<=146) $prefix = "(9球)";
					if(in_array($gt['game_type'],[29]) && $e_tznum_item>=147 && $e_tznum_item<=160) $prefix = "(10球)";
					if(in_array($gt['game_type'],[29]) && $e_tznum_item>=161 && $e_tznum_item<=162) $prefix = "(1v10)";
					if(in_array($gt['game_type'],[29]) && $e_tznum_item>=163 && $e_tznum_item<=164) $prefix = "(2v9)";
					if(in_array($gt['game_type'],[29]) && $e_tznum_item>=165 && $e_tznum_item<=166) $prefix = "(3v8)";
					if(in_array($gt['game_type'],[29]) && $e_tznum_item>=167 && $e_tznum_item<=168) $prefix = "(4v7)";
					if(in_array($gt['game_type'],[29]) && $e_tznum_item>=169 && $e_tznum_item<=170) $prefix = "(5v6)";
					
					if(in_array($gt['game_type'],[36]) && $e_tznum_item>=6 && $e_tznum_item<=37) $prefix = "(1球)";
					if(in_array($gt['game_type'],[36]) && $e_tznum_item>=38 && $e_tznum_item<=69) $prefix = "(2球)";
					if(in_array($gt['game_type'],[36]) && $e_tznum_item>=70 && $e_tznum_item<=101) $prefix = "(3球)";
					if(in_array($gt['game_type'],[36]) && $e_tznum_item>=102 && $e_tznum_item<=133) $prefix = "(4球)";
					if(in_array($gt['game_type'],[36]) && $e_tznum_item>=134 && $e_tznum_item<=165) $prefix = "(5球)";
					if(in_array($gt['game_type'],[36]) && $e_tznum_item>=166 && $e_tznum_item<=197) $prefix = "(6球)";
					if(in_array($gt['game_type'],[36]) && $e_tznum_item>=198 && $e_tznum_item<=229) $prefix = "(7球)";
					if(in_array($gt['game_type'],[36]) && $e_tznum_item>=230 && $e_tznum_item<=261) $prefix = "(8球)";
					if(in_array($gt['game_type'],[36]) && $e_tznum_item>=262 && $e_tznum_item<=263) $prefix = "(1v8)";
					if(in_array($gt['game_type'],[36]) && $e_tznum_item>=264 && $e_tznum_item<=265) $prefix = "(2v7)";
					if(in_array($gt['game_type'],[36]) && $e_tznum_item>=266 && $e_tznum_item<=267) $prefix = "(3v6)";
					if(in_array($gt['game_type'],[36]) && $e_tznum_item>=268 && $e_tznum_item<=269) $prefix = "(4v5)";
					
					
					if(in_array($gt['game_type'],[37]) && $e_tznum_item>=7 && $e_tznum_item<=20) $prefix = "(1球)";
					if(in_array($gt['game_type'],[37]) && $e_tznum_item>=21 && $e_tznum_item<=34) $prefix = "(2球)";
					if(in_array($gt['game_type'],[37]) && $e_tznum_item>=35 && $e_tznum_item<=48) $prefix = "(3球)";
					if(in_array($gt['game_type'],[37]) && $e_tznum_item>=49 && $e_tznum_item<=62) $prefix = "(4球)";
					if(in_array($gt['game_type'],[37]) && $e_tznum_item>=63 && $e_tznum_item<=76) $prefix = "(5球)";
					if(in_array($gt['game_type'],[37]) && $e_tznum_item>=77 && $e_tznum_item<=81) $prefix = "(前3)";
					if(in_array($gt['game_type'],[37]) && $e_tznum_item>=82 && $e_tznum_item<=86) $prefix = "(中3)";
					if(in_array($gt['game_type'],[37]) && $e_tznum_item>=87 && $e_tznum_item<=91) $prefix = "(后3)";
					
					
					
					$e_tznum_item = $prefix . $gt['game_model'][$e_tznum_item];
				}
			}
			$e_yzjpl=explode('|',$row['yzjpl'] );
			$tznum=99;
			foreach ($e_zj as $k=>$v){
				if($v>0){
					$tznum=$e_tznum[$k];
					$e_zj[$k]='<span class="red bold">'.$v.'</span>';
					$e_tz[$k]='<span class="red bold">'.$e_tz[$k].'</span>';
					//$e_tznum[$k]='<span class="red bold">'.$e_tznum[$k].'</span>';
					if(in_array($gt['game_type'], [25,26,27,28,29,30,31,36,37,41,42])){//外围，定位，赛车，农场，时时彩
						$e_tznum_alias[$k]='<span class="red bold">'.$e_tznum_alias[$k].'</span>';
					}else{
						$e_tznum_alias[$k]='<span class="red bold">'.$e_tznum_alias[$k].'</span>';
					}
					$arrRows[$i]['yzjpl']=0;//$e_yzjpl[$e_tznum[$k]]?:0;

				}

			}
			foreach ($arrStdNums as $k=>$v){
				if($v==$tznum){
					$arrRows[$i]['yzjpl']=$e_yzjpl[$k]?:0;

				}

			}
			//$arrRows[$i]['yzjpl'].="<br>".$row['yzjpl'];
			//$arrRows[$i]['zjpl']=doubleval($row['zjpl'])?:$arrRows[$i]['yzjpl'];
			
			$zjpl_arr=explode('|',$row['zjpl'] );
			$zjpl_tmp = array();
			for($k=0;$k<count($zjpl_arr);$k++){
				if($zjpl_arr[$k] != '0.0000') $zjpl_tmp[] = $zjpl_arr[$k];
			}
			$arrRows[$i]['zjpl']=implode("|", $zjpl_tmp);
			if(in_array($gt['game_type'] , [25,26,27,28,29,30,31,36,37,41,42]))
				$arrRows[$i]['yzjpl']=$arrRows[$i]['zjpl'];

			$arrRows[$i]["TZNo"] = implode('|', $e_tznum_alias) . "<br>" . implode('|',$e_tz) . "<br>" . implode('|',$e_zj);
			
			
			//TODO
			$arrRows[$i]["verifyResult"] = "通过";
			if(!empty($UserID) && !empty($GameType)){
				$presslog_arr = array();
				$e_tznum_arr = explode('|', $row['tznum']);
				$e_tz_arr = explode('|', $row['tzpoints']);
				for($k=0;$k<count($e_tznum_arr);$k++){
					$_tznum = $e_tznum_arr[$k];
					$presslog_arr[$_tznum] = $e_tz_arr[$k];
				}
				
				
				$presslogArr = array();
				$sql = "select * from presslog where uid='{$UserID}' and no='{$row["NO"]}' and gametype = '{$gt['game_type']}' ";
				$res = $db->query($sql);
				while($presslogrows=$db->fetch_array($res))
				{
					if($presslogrows['presstime'] != $presslogrows['updatetime'] || $presslogrows['updatetime'] > $row["kgtime"]){
						$arrRows[$i]["verifyResult"] = "<span class=\"red bold\">不通过</span>";
					}
					
					/* if(in_array($gt['game_type'],[3,4,8,18,32,33,34,35])){
						if(($presslogrows['presstime'] > "2018-08-07 17:50:00") && ($presslogrows['updatetime'] != $presslogrows['presstime2'])){
							$arrRows[$i]["verifyResult"] = "<span class=\"red bold\">不通过</span>";
						}
					} */
					
					$pressStr = $presslogrows['pressStr'];
					$pressStr_Arr = explode('|', $pressStr);
					for($k=0;$k<count($pressStr_Arr);$k++){
						$tmptz = $pressStr_Arr[$k];
						$spressArr = explode(',', $tmptz);
						$_tznum = $spressArr[0];
						if(!empty($presslogArr[$_tznum])){
							$presslogArr[$_tznum] += $spressArr[1];
						}else{
							$presslogArr[$_tznum] = $spressArr[1];
						}
					}
				}
				
				if(empty($presslogArr) || empty($presslog_arr) || count($presslogArr)!=count($presslog_arr)) $arrRows[$i]["verifyResult"] = "<span class=\"red bold\">不通过</span>";
				
				//print_r($presslogArr);
				//print_r($presslog_arr);
				foreach($presslogArr as $key=>$val){
					if($presslogArr[$key] != $presslog_arr[$key]){
						$arrRows[$i]["verifyResult"] = "<span class=\"red bold\">不通过</span>";
						break;
					}
				}
			}
			
			if(in_array($gt['game_type'] , [24,23,0,22,1,2,15]) && !empty($row["pre_kgjg"])){
				$arrRows[$i]["verifyResult"] = $arrRows[$i]["verifyResult"] . "(M)";
			}
			
			
			$total_points += $row["points"];
			$total_hdpoints += $row["hdpoints"];

		}
		
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
			$arrRows[$i]["UserID"] = "";
			$arrRows[$i]["NickName"] = ""; 
            $arrRows[$i]["No"] = ""; 
			$arrRows[$i]["GameTime"] = "页小计:";  
			$arrRows[$i]['zjpl']= "";
			$arrRows[$i]["Points"] = Trans($total_points);
			$arrRows[$i]["HdPoints"] = Trans($total_hdpoints);
			$arrRows[$i]['spoint']=$total_hdpoints-$total_points;
			$arrRows[$i]["TZNo"] = "";
			$arrRows[$i]["verifyResult"] = "";
		}
		
		
		//返回分页
		require_once('inc/fenye.php');
		$ajaxpage=new page(array('total'=>$TotalCount,'perpage'=>$PageSize,'ajax'=>'ajax_page','nowindex' => $page));
		$pageInfo = $ajaxpage->show();
		
		$arrRows[0]["cmd"] = $act;
		$arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows); 
        //WriteLog(json_encode($arrRows));
		echo json_encode($arrRows);
		exit;
    }  
	/* 取得游戏列表
	*
	*/
	function GetGameTypeOption($act)
	{
		global $db;
		$sql = "SELECT game_table_prefix,game_name from game_config order by ordernum";
        $result = $db->query($sql);
		//取得返回记录数
		$RowCount = $db->num_rows($result);
        $Option = "";
        if($RowCount > 0)
        {
            for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
            {
                $Option .= "<option value='{$row['game_table_prefix']}'>{$row['game_name']}</option>";
            }
        }
        //返回结果
        $arrRows = array(array());
          
        $arrRows[0]["cmd"] = $act;
		$arrRows[0]["msg"] = $Option;
        ArrayChangeEncode($arrRows);
		echo json_encode($arrRows);
		//echo json_encode($arrReturn,JSON_UNESCAPED_UNICODE);
	}
	/* 取得操作记录
	*
	*/
	function GetValidLog($act)
	{
		global $db;
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $theType = isset($_POST['type'])?FilterStr($_POST['type']):"-1";
        $theState = isset($_POST['state'])?FilterStr($_POST['state']):"-1";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT a.userid,a.code_type,a.account,a.content,a.add_time,a.state,a.err_msg,b.nickname
					";
		$sqlFrom = " FROM validcodelog a
					LEFT OUTER JOIN users b
					ON a.userid = b.id
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
        if($UserID != "")
            $sqlWhere .= " and a.userid = " . $UserID;
        if($IsExceptInner == 1)
        	$sqlWhere .= " and a.userid not in(select uid from users_inner)";
        if($theType != "-1")
            $sqlWhere .= " and a.code_type = " . $theType;
        if($theState != "-1")
            $sqlWhere .= " and a.state = " . $theState;
        //时间
        $TimeField = "a.add_time";
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
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["userid"]}")."' target='_blank'>{$row["userid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            $arrRows[$i]["OprTime"] = $row["add_time"];
            $arrRows[$i]["ValidType"] = ($row["code_type"] == 0) ? "发短信":"发邮件";
            $arrRows[$i]["Account"] = $row["account"];
			$arrRows[$i]["State"] = ($row["state"] == 0) ? "成功":"失败";
			$arrRows[$i]["Content"] = $row["content"];
			$arrRows[$i]["ErrMsg"] = $row["err_msg"];
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
	/* 取得操作记录
	*
	*/
	function GetChangeDetailLog($act)
	{
		global $db;
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT a.uid,b.nickname,a.detail_type,a.item_old,a.item_new,a.change_time,a.opr_user
					";
		$sqlFrom = " FROM changedetaillog a
					LEFT OUTER JOIN users b
					ON a.uid = b.id
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
        if($UserID != "")
            $sqlWhere .= " and a.uid = " . $UserID;
        if($IsExceptInner == 1)
        	$sqlWhere .= " and a.uid not in(select uid from users_inner)";
        //时间
        $TimeField = "a.change_time";
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
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            $arrRows[$i]["OprTime"] = $row["change_time"];
            $arrRows[$i]["DetailType"] = $row["detail_type"];
            $arrRows[$i]["ItemOld"] = $row["item_old"];
			$arrRows[$i]["ItemNew"] = $row["item_new"];
			$arrRows[$i]["OprUser"] = $row["opr_user"];
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
	/* 取得用户操作记录
	*
	*/
	function GetActionLog($act)
	{
		global $db;
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $OprType = isset($_POST['oprtype'])?FilterStr($_POST['oprtype']):"-1";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT a.usersid,a.time,a.logtype,a.log,a.points,a.bankpoints,a.experience,a.ip,b.nickname
					";
		$sqlFrom = " FROM userslog a
					LEFT OUTER JOIN users b
					ON a.usersid = b.id
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
        if($UserID != "")
            $sqlWhere .= " and a.usersid = " . $UserID;
        if($IsExceptInner == 1)
        	$sqlWhere .= " and a.usersid not in(select uid from users_inner)";
        if($OprType != "-1")
        	$sqlWhere .= " and a.logtype = {$OprType}";
        //时间
        $TimeField = "a.time";
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
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["usersid"]}")."' target='_blank'>{$row["usersid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            $arrRows[$i]["OprTime"] = $row["time"];
            $OprType = "";
            switch($row["logtype"])
            {
				case 1:
					$OprType = "广告奖励";
					break;
				case 2:
					$OprType = "在线充值";
					break;
				case 3:
					$OprType = "奖罚记录";
					break;
				case 4:
					$OprType = "登录";
					break;
				case 10:
					$OprType = "改密码";
					break;
				case 11:
					$OprType = "银行存取";
					break;
				case 12:
					$OprType = "充值卡";
					break;
				case 13:
					$OprType = "转账";
					break;
				case 14:
					$OprType = "领取救济";
					break;
				case 15:
					$OprType = "兑奖点卡";
					break;
				default:
					$OprType = "其他";
					break;
            }
            $arrRows[$i]["OprType"] = $OprType;
            $arrRows[$i]["Log"] = $row["log"];
			$arrRows[$i]["Point"] = Trans($row["points"]);
			$arrRows[$i]["BankPoint"] = Trans($row["bankpoints"]);
			$IPInfo = "<a href='http://www.ip138.com/ips.asp?ip={$row["ip"]}' target='_blank'>". $row["ip"] ."</a>";
			$IPInfo .= "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["ip"]}")."' target='_blank'>批</a>";
			$arrRows[$i]["IP"] = $IPInfo;
			$arrRows[$i]["Exp"] = Trans($row["experience"]);
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
	/* 取得充值记录
	*
	*/
	function GetScoreLog($act)
	{
		global $db;
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $OprType = isset($_POST['oprtype'])?FilterStr($_POST['oprtype']):"-1";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "select a.uid,b.nickname,a.opr_type,a.amount,a.log_time,a.points,a.bankpoints,a.remark,a.ip
					";
		$sqlFrom = " from score_log a
					 left outer join users b
					 on a.uid = b.id
					 where 1=1 ";
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
        if($UserID != "")
            $sqlWhere .= " and a.uid = " . $UserID;
        if($IsExceptInner == 1)
        	$sqlWhere .= " and a.uid not in(select uid from users_inner)";
        if($OprType != "-1"){
        	if($OprType == "20")
        		$sqlWhere .= " and a.opr_type in(20,30)";
        	else
        		$sqlWhere .= " and a.opr_type = {$OprType}";
        }
        //时间
        $TimeField = "log_time";
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
		$total_amount = 0;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            $OprType = 0;
            switch($row["opr_type"])
            {
				case 0:
					$OprType = "存分";
					break;
				case 1:
					$OprType = "取分";
					break;
				case 2:
					$OprType = "卡充值";
					break;
				case 3:
					$OprType = "转账入";
					break;
				case 4:
					$OprType = "转账出";
					break;
				case 5:
					$OprType = "在线充值";
					break;
				case 6:
					$OprType = "领取救济";
					break;
				case 7:
					$OprType = "兑奖点卡";
					break;
				case 21:
					$OprType = "推荐收益";
					break;
				case 210:
					$OprType = "首充返利";
					break;
				case 9:
					$OprType = "未开奖返还";
					break;
				case 10:
					$OprType = "在线提现";
					break;
				case 11:
				case 12:
					$OprType="返回提现积分";
					break;
				case 20:
					$OprType="日亏损返利";
					break;
				case 30:
					$OprType="周亏损返利";
					break;
				case 40:
					$OprType="收发红包";
					break;
				case 55:
					$OprType="系统充值";
					break;
				default:
					$OprType = "其他";
					break;
            }
            $arrRows[$i]["OprType"] = $OprType;
            $arrRows[$i]["Amount"] = Trans($row["amount"]);
			$arrRows[$i]["LogTime"] = $row["log_time"]; 
			$arrRows[$i]["Point"] = Trans($row["points"]);
			$arrRows[$i]["BankPoint"] = Trans($row["bankpoints"]);
			$IPInfo = "<a href='http://www.ip138.com/ips.asp?ip={$row["ip"]}' target='_blank'>". $row["ip"] ."</a>";
			$IPInfo .= "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["ip"]}")."' target='_blank'>批</a>";
			$arrRows[$i]["IP"] = $IPInfo;
			$arrRows[$i]["Remark"] = $row["remark"];
			$total_amount += $row["amount"];
		}
		
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
			$arrRows[$i]["UserID"] = "";
            $arrRows[$i]["NickName"] = "";
            $arrRows[$i]["OprType"] = "页小计:";
            $arrRows[$i]["Amount"] = Trans($total_amount);
			$arrRows[$i]["LogTime"] = ""; 
			$arrRows[$i]["Point"] = "";
			$arrRows[$i]["BankPoint"] = "";
			$arrRows[$i]["IP"] = "";    
			$arrRows[$i]["Remark"] = "";
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
	/* 取得转账记录
	*
	*/
	function GetTransLog($act)
	{
		global $db;
        $UserType = isset($_POST['usertype'])?FilterStr($_POST['usertype']):"";
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT a.from_id,b.nickname AS from_name,a.to_id,c.nickname AS to_name,a.amount,FLOOR(a.amount * a.odds * 0.01) AS tax,a.logtime
					";
		$sqlFrom = " FROM trans_score_log a
					LEFT OUTER JOIN users b
					ON a.from_id = b.id
					LEFT OUTER JOIN users c
					ON a.to_id = c.id
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
        if($UserID != "")
        {
        	if($UserType == '0')
        	{
            	$sqlWhere .= " and (a.from_id = {$UserID} or a.to_id = {$UserID}) ";
			}
            elseif($UserType == '1')
            	$sqlWhere .= " and a.from_id = {$UserID} ";
            elseif($UserType == '2')
            	$sqlWhere .= " and a.to_id = {$UserID} ";
		}
		if($IsExceptInner == 1)
        {
        	$sqlWhere .= " and a.from_id not in(select uid from users_inner)
        					and a.to_id not in(select uid from users_inner) ";
        	
		}
		
        //时间
        $TimeField = "a.logtime";
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
		$total_amount = 0;
		$total_tax = 0;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
            $arrRows[$i]["FromUser"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["from_id"]}")."' target='_blank'>{$row["from_id"]}({$row['from_name']})</a>";
            $arrRows[$i]["TransTime"] = $row["logtime"]; 
            $arrRows[$i]["Amount"] = Trans($row["amount"]);
			$arrRows[$i]["Tax"] = Trans($row["tax"]);
			$arrRows[$i]["ToUser"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["to_id"]}")."' target='_blank'>{$row["to_id"]}({$row['to_name']})</a>"; 
			$total_amount += $row["amount"];
			$total_tax += $row["tax"];
		}
		
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
			$arrRows[$i]["FromUser"] = "";
            $arrRows[$i]["TransTime"] = "页小计:";
            $arrRows[$i]["Amount"] = Trans($total_amount);
			$arrRows[$i]["Tax"] = Trans($total_tax);
			$arrRows[$i]["ToUser"] = "";
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
	/* 取得充值记录
	*
	*/
	function GetPayLog($act)
	{
		global $db;
        $DataType = isset($_POST['datatype'])?FilterStr($_POST['datatype']):"recent";
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "select a.uid,b.nickname,a.opr_type,a.amount,a.log_time,a.points,a.bankpoints,a.remark
					";
		$sqlFrom = " from score_log a
					 left outer join users b
					 on a.uid = b.id
					 where a.opr_type in(2,5,55) ";
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
        if($UserID != "")
            $sqlWhere .= " and uid = " . $UserID;
        if($IsExceptInner == 1)
        	$sqlWhere .= " and uid not in(select uid from users_inner)";
        //时间
        $TimeField = "log_time";
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
		$total_amount = 0;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            
            //$arrRows[$i]["PayType"] = ($row["opr_type"] == 2) ? "<font color='red'>卡充值</font>" : "<font color='blue'>在线充值</font>"; 
			if($row["opr_type"] == 2) $arrRows[$i]["PayType"] = "<font color='red'>卡充值</font>";
			if($row["opr_type"] == 5) $arrRows[$i]["PayType"] = "<font color='blue'>在线充值</font>";
			if($row["opr_type"] == 55) $arrRows[$i]["PayType"] = "<font color='green'>系统充值</font>";
            
            $arrRows[$i]["Amount"] = Trans($row["amount"]);
			$arrRows[$i]["PayTime"] = $row["log_time"]; 
			$arrRows[$i]["Point"] = Trans($row["points"]);
			$arrRows[$i]["BankPoint"] = Trans($row["bankpoints"]);
			$arrRows[$i]["Remark"] = $row["remark"];
			$total_amount += $row["amount"];
		}
		
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
			$arrRows[$i]["UserID"] = "";
            $arrRows[$i]["NickName"] = "";
            $arrRows[$i]["PayType"] = "页小计:";
			$arrRows[$i]["Amount"] = Trans($total_amount);
			$arrRows[$i]["PayTime"] = "";
			$arrRows[$i]["Point"] = "";
			$arrRows[$i]["BankPoint"] = "";
			$arrRows[$i]["Remark"] = "";
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
	/* 取得登录成功记录
	*
	*/
	function GetLoginFailLog($act)
	{
		global $db;
        $DataType = isset($_POST['datatype'])?FilterStr($_POST['datatype']):"recent";
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "select uid,nickname,pwd,login_ip,login_time,err_msg
					";
		$sqlFrom = " from login_fail
					 where 1=1 ";
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
        if($UserID != "")
            $sqlWhere .= " and uid = " . $UserID;
        if($IsExceptInner == 1)
        	$sqlWhere .= " and uid not in(select uid from users_inner)";
        //时间
        $TimeField = "login_time";
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
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
			$arrRows[$i]["LoginDate"] = $row["login_time"]; 
			$arrRows[$i]["Pwd"] = $row["pwd"];
			$IPInfo = "<a href='http://www.ip138.com/ips.asp?ip={$row["login_ip"]}' target='_blank'>". $row["login_ip"] ."</a>";
			$IPInfo .= "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["login_ip"]}")."' target='_blank'>批</a>";
			$arrRows[$i]["IP"] = $IPInfo;
			$arrRows[$i]["ErrMsg"] = $row["err_msg"];
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
	/* 取得登录成功记录
	*
	*/
	function GetLoginSuccessLog($act)
	{
		global $db;
        $DataType = isset($_POST['datatype'])?FilterStr($_POST['datatype']):"recent";
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $IsExceptInner = intval($_POST['isexceptinner']);
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "select uid,nickname,point,bankpoint,lockpoint,exp,loginip,login_time
					";
		$sqlFrom = " from login_success
					 where 1=1 ";
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
        if($UserID != "")
            $sqlWhere .= " and uid = " . $UserID;
        if($IsExceptInner == 1)
        	$sqlWhere .= " and uid not in(select uid from users_inner)";
        //时间
        $TimeField = "login_time";
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
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
			$arrRows[$i]["LoginDate"] = $row["login_time"]; 
			$arrRows[$i]["Points"] = Trans($row["point"]);
			$arrRows[$i]["BankPoints"] = Trans($row["bankpoint"]);
			$arrRows[$i]["LockPoints"] = Trans($row["lockpoint"]);
			$arrRows[$i]["Exp"] = Trans($row["exp"]);
			$IPInfo = "<a href='http://www.ip138.com/ips.asp?ip={$row["loginip"]}' target='_blank'>". $row["loginip"] ."</a>";
			$IPInfo .= "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["loginip"]}")."' target='_blank'>批</a>";
			
			
			$arrRows[$i]["IP"] = $IPInfo;
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
	/* 修改密码
    *
    */
    function ChangePwd($UserIDx,$t,$newpwd)
    {
    	global $db,$web_pwd_encrypt_prefix;
        $IP = $_SERVER['REMOTE_ADDR'];
        $arrReturn = array(array("cmd"=>"","msg"=>""));
        if($newpwd == "" || $UserIDx == "")
        {
            $arrReturn[0]["cmd"] = "changepwd_error";
            $arrReturn[0]["msg"] = "密码不能为空!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
        }
        $newpwd = md5($web_pwd_encrypt_prefix . $newpwd);
        $ChangeType = (($t == "loginpwd") ? 0 : 1);
        $sql = "call web_user_changepwd({$UserIDx},{$ChangeType},'back','','{$newpwd}','{$IP}')";
        //WriteLog($sql);
        $arr = $db->Mysqli_Multi_Query($sql);
        $Return = $arr[0][0]["result"];
        if($Return == "0")
        {
            $arrReturn[0]["cmd"] = "changepwd_ok";
            $arrReturn[0]["msg"] = "操作成功!";
        }
        else if($Return == "1")
        {
            $arrReturn[0]["cmd"] = "changepwd_ok";
            $arrReturn[0]["msg"] = "原密码错误，修改失败!";
        }
        else
        {
            $arrReturn[0]["cmd"] = "changedetail_ok";
            $arrReturn[0]["msg"] = "由于数据库执行错误，修改失败!";
        }
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
        exit;
    } 
	/* 修改资料
    *
    */ 
    function ChangeDetailItem()
    {
    	global $db;
        $ChangeType = isset($_POST['type'])?FilterStr($_POST['type']):"";
        $NewItem = isset($_POST['newitem'])?FilterStr($_POST['newitem']):"";
        $UserIDx = isset($_POST['id'])?FilterStr($_POST['id']):"";
        $Pwd = isset($_POST['pwd'])?FilterStr($_POST['pwd']):"";
        
        $arrReturn = array(array("cmd"=>"","msg"=>""));
        if($ChangeType == "" || $UserIDx == "")
        {
            $arrReturn[0]["cmd"] = "changedetail_error";
            $arrReturn[0]["msg"] = "数据非法!";
            ArrayChangeEncode($arrReturn);  
            echo json_encode($arrReturn);
            return;
        }
        //修改密码，单独处理
        if($ChangeType == "loginpwd" || $ChangeType == "bankpwd")
        {
            ChangePwd($UserIDx,$ChangeType,$NewItem);
            return;
        }
        $sql = "call web_back_changedetail({$UserIDx},'{$ChangeType}','{$NewItem}',{$_SESSION["Admin_UserID"]},'{$Pwd}')";
        WriteLog( $_SESSION["Admin_UserID"] . ":" . usersip() . " : " . $sql);
        //return;
        $arr = $db->Mysqli_Multi_Query($sql);
        $Return = $arr[0][0]["result"];
        $cmd = "changedetail";
        $msg = "";
        if($Return == "0")
        { 
            $msg = "操作成功!";
        }
        else if($Return == "1")
        {
            $msg = "由于重名，修改失败!";
        }
        else
        {
            $msg = "由于数据库执行错误，修改失败!";
        }
        $arrReturn[0]["cmd"] = $cmd;
        $arrReturn[0]["msg"] = $msg;
        ArrayChangeEncode($arrReturn);  
        echo json_encode($arrReturn);
        return;
    } 
	/* 最近提现记录
    *
    */
	   function GetWithDrawLog($act)
    {
    	global $db;
        $ID = isset($_POST['id'])?FilterStr($_POST['id']):"";
        
        $sql = "SELECT a.id,a.uid,a.pay_time,a.rmb,a.point
				FROM pay_online a
				WHERE uid = {$ID} and state=32
				order by pay_time desc
				limit 10";
        //$sql = "select id,uid,card_points as point,card_points/1000 as rmb,add_time as pay_time,used_time from exchange_cards where uid = '{$ID}' and state=1 order by id desc limit 6";
        $arrRows = array(array());
        $result = $db->query($sql);
          //取得返回记录数
        $RowCount = $db->num_rows($result);
        if($RowCount > 0)
        {
            for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
            {
                $arrRows[$i]["TransTime"] = $row["pay_time"]; 
                $arrRows[$i]["Rmb"] = "<a href='index.php?url=".urlencode("admin_withdrawals.php?userid={$row["uid"]}&status=32")."' target='_blank'>{$row["rmb"]}</a>"; 
                //$arrRows[$i]["NickName"] = $row["nickname"];
                $arrRows[$i]["Point"] = Trans($row["point"]);
            }
        }
        if($RowCount > 0)
        {
             $sql = "SELECT SUM(point) as totalAmount,COUNT(*) AS cnt
                    FROM pay_online
                    WHERE uid = {$ID} and state=32";
             //$sql = "select SUM(card_points) as totalAmount,COUNT(*) AS cnt from exchange_cards where uid = '{$ID}' and state=1";
             $result = $db->query($sql); 
             $row = $db->fetch_array($result);
             
             $sTotal = "总:" . Trans($row['totalAmount']) . "," . $row['cnt'] . "笔";
             $index = $RowCount + 1;
             $arrRows[$index]["TransTime"] = $sTotal;
             $arrRows[$index]["Rmb"] = "";
             $arrRows[$index]["Point"] = "";     
        }
        
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = "ok";
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
    }
	/* 最近转账记录
    *
    */
    function GetRecentTransLog($act)
    {
    	global $db;
        $ID = isset($_POST['id'])?FilterStr($_POST['id']):"";
        
        $sql = "SELECT a.to_id,b.nickname,a.amount,a.logtime
				FROM trans_score_log a
				LEFT OUTER JOIN users b
				ON a.to_id = b.id
				WHERE from_id = {$ID}
				order by logtime desc
				limit 6";
        $arrRows = array(array());
        $result = $db->query($sql);
          //取得返回记录数
        $RowCount = $db->num_rows($result);
        if($RowCount > 0)
        {
            for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
            {
                $arrRows[$i]["TransTime"] = $row["logtime"]; 
                $arrRows[$i]["TargetID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["to_id"]}")."' target='_blank'>{$row["to_id"]}</a>"; 
                $arrRows[$i]["NickName"] = $row["nickname"];
                $arrRows[$i]["Amount"] = $row["amount"];
            }
        }
        if($RowCount > 0)
        {
             $sql = "SELECT SUM(amount) as totalAmount,COUNT(*) AS cnt
                    FROM trans_score_log
                    WHERE from_id = {$ID}";
             $result = $db->query($sql); 
             $row = $db->fetch_array($result);
             
             $sTotal = "总:" . $row['totalAmount'] . "," . $row['cnt'] . "笔";
             $index = $RowCount + 1;
             $arrRows[$index]["TransTime"] = $sTotal;
             $arrRows[$index]["TargetID"] = "";
             $arrRows[$index]["NickName"] = "";
             $arrRows[$index]["Amount"] = "";      
        }
        
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = "ok";
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
    }
    /* 最近充值记录
    *
    */
    function GetRecentPayLog($act)
    {
    	global $db;
        $ID = isset($_POST['id'])?FilterStr($_POST['id']):"";
        
        $sql = "SELECT opr_type,amount,log_time FROM score_log WHERE uid = {$ID} and opr_type in(2,5,55) ORDER BY log_time DESC LIMIT 10";
        $arrRows = array(array());
        $result = $db->query($sql);
        //取得返回记录数
        $RowCount = $db->num_rows($result);
        if($RowCount > 0)
        {
            for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
            {
                $arrRows[$i]["PayTime"] = $row["log_time"];
                switch($row["opr_type"])
                {
					case 2:
						$OprType = "充值卡";
						break;
					case 5:
						$OprType = "在线充值";
						break;
					case 55:
						$OprType = "系统充值";
						break;
					default:
						$OprType = "其他充值";
						break;
                }
                $arrRows[$i]["PayType"] = $OprType;
                $arrRows[$i]["Amount"] = Trans($row["amount"]);
            }
        }
        if($RowCount > 0)
        {
             $sql = "SELECT SUM(amount) as totalAmount,COUNT(*) AS cnt
                    FROM score_log
                    WHERE uid = {$ID} and opr_type in(2,5,55)";
             $result = $db->query($sql); 
             $row = $db->fetch_array($result);
             
             $sTotal = "总:" .  Trans($row['totalAmount']). "," . $row['cnt'] . "笔";
             $index = $RowCount + 1;
             $arrRows[$index]["PayTime"] = $sTotal;
             $arrRows[$index]["PayType"] = "";
             $arrRows[$index]["Amount"] = "";
                 
        }
        
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = "ok";
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
    }
	
	    /* 绑定卡号
    *
    */
    function GetBdAccounts($act)
    {
    	global $db;
        $ID = isset($_POST['id'])?FilterStr($_POST['id']):"";
        
        $sql = "SELECT add_time,name,account,type FROM withdrawals WHERE uid = {$ID}";
        $arrRows = array(array());
        $result = $db->query($sql);
        //取得返回记录数
        $RowCount = $db->num_rows($result);
        if($RowCount > 0)
        {
            for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
            {
                $arrRows[$i]["Add_time"] = $row["add_time"];
                switch($row["type"])
                {
					case 1:
						$Name = "支付宝";
						break;
					case 2:
						$Name = "微信";
						break;
					default:
						$Name = $row["name"];
						break;
                }
				$arrRows[$i]["Name"] = $Name;
                $arrRows[$i]["Account"] = $row["account"];
            }
        }
       
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = "ok";
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
    }
    
    /* 取最近登录记录
    *
    */
    function GetRecentLoginLog($act)
     {
     	global $db;
        $ID = isset($_POST['id'])?FilterStr($_POST['id']):"";
        
        $sql = "SELECT point,bankpoint,exp,loginip,login_time
                FROM login_success
                WHERE uid = '{$ID}'
                order by login_time desc
                limit 7";
        $arrRows = array(array());
        $result = $db->query($sql);
        //取得返回记录数
        $RowCount = $db->num_rows($result);
        if($RowCount > 0)
        {
            for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
            {
                $arrRows[$i]["LoginTime"] = $row["login_time"];
                //$arrRows[$i]["LoginIP"] = "<a href='http://www.ip138.com/ips.asp?ip={$row["loginip"]}' target='_blank'>". $row["loginip"] ."</a>" .
                //                         "&nbsp;<a href='index.php?url=".urlencode("patchuser.php?type=loginip&word={$row["loginip"]}")."' target='_blank'>批</>";
                
                
                $IPInfo = "";
                if($row["loginip"] != "")
                {
                	$IPInfo = "<a href='http://www.ip138.com/ips.asp?ip={$row["loginip"]}' target='_blank'>". $row["loginip"] ."</a>";
                	$IPInfo .= "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["loginip"]}")."' target='_blank'>批</a>";
                }
                $arrRows[$i]["LoginIP"] = $IPInfo;
                
                
                $arrRows[$i]["Points"] = Trans($row["point"]);
                $arrRows[$i]["BankPoints"] = Trans($row["bankpoint"]);
                $arrRows[$i]["Exp"] = Trans($row["exp"]);
            }
        }
        
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = "ok";
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
    }
	/* 取得用户基本信息
     * 
     */
    function GetUserBaseInfo($act)
    {  
    	global $db;  
        $SearchType = intval($_POST['usertype']);
        $SearchWord = isset($_POST['word'])?FilterStr($_POST['word']):"";
        $sql = "";
          
        $sqlCol = "select * ";
        $sqlFrom = " from users
                    where  1=1 ";
        $sqlWhere = "";
        if($SearchType == 0)
            $sqlWhere = " and id = " . $SearchWord;
        else if($SearchType == 1)
            $sqlWhere = " and mobile = '{$SearchWord}'";
        else if($SearchType == 2)
        	$sqlWhere = " and username = '{$SearchWord}'";
        else if($SearchType == 3)
        	$sqlWhere = " and qq = '{$SearchWord}'";
        else if($SearchType == 4)
        	$sqlWhere = " and recv_cash_name = '{$SearchWord}'";
        
        //按条件去记录
        $sql = $sqlCol . $sqlFrom . $sqlWhere . " limit 1"; 
        //WriteLog($sql);
        $RowCount = 0;
        $arrRows = array(array());
        $arrReturn = array(array());
        $result = $db->query($sql);        
        //取得返回记录数
        $RowCount = $db->num_rows($result);
        
        if($RowCount == 0)
        {
            $arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "没有记录!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            exit;
        }
        //填充数据                
        $row=$db->fetch_array($result);
        $User_IDx = $row["id"];
        $arrRows[1]["UserID"] = $User_IDx; 
        
        $arrRows[1]["NickName"] = $row["nickname"];
        $arrRows[1]["LoginPwdInfo"] = ($row["password"] == "")?"登录密码:":"<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginpwd&word={$row["password"]}")."' target='_blank'>登录密码:</a>";
        $arrRows[1]["BankPwdInfo"] = ($row["bankpwd"] == "")?"支付密码:":"<a href='index.php?url=".urlencode("admin_patchuser.php?type=bankpwd&word={$row["bankpwd"]}")."' target='_blank'>支付密码:</a>";
        $arrRows[1]["EmailInfo"] = ($row["email"] == "")?"邮箱:":"<a href='index.php?url=".urlencode("admin_patchuser.php?type=email&word={$row["email"]}")."' target='_blank'>邮箱:</a>";
        $arrRows[1]["Email"] = $row["email"];
        $arrRows[1]["MobileInfo"] = ($row["mobile"] == "")?"手机:":"<a href='index.php?url=".urlencode("admin_patchuser.php?type=mobile&word={$row["mobile"]}")."' target='_blank'>手机:</a>";
        $arrRows[1]["Mobile"] = $row["mobile"];
        $arrRows[1]["MobileStatus"] = ($row['is_check_mobile'] == 0) ? "否<input type='button' id='btnBindMobile' value='绑定' class='btn-1'>" : "已绑定<input type='button' id='btnUnBindMobile' value='解绑' class='btn-1'>";
        $arrRows[1]["EmailStatus"] = ($row['is_check_email'] == 0) ? "否<input type='button' id='btnBindEmail' value='绑定' class='btn-1'>" : "已绑定<input type='button' id='btnUnBindEmail' value='解绑' class='btn-1'>";
        $arrRows[1]["RecvCashName"] = $row["recv_cash_name"];
         
        $arrRows[1]["UserName"] = $row["username"];
        $arrRows[1]["CurExp"] = Trans($row["experience"]);
        $arrRows[1]["MaxExp"] = "总:" . Trans($row["maxexperience"]) . ";今日:" . Trans($row["dailygameexp"]); 
        $arrRows[1]["LoginTime"] = $row["logintime"];
        $arrRows[1]["RegTime"] = $row["time"];
        $arrRows[1]["LoginIPInfo"] = ($row["loginip"] == "")?"登录IP:":"<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["loginip"]}")."' target='_blank'>登录IP:</a>";
        $arrRows[1]["LoginIP"] = "<a href='http://www.ip138.com/ips.asp?ip={$row["loginip"]}' target='_blank'>". $row["loginip"] ."</a>";
        $arrRows[1]["RegIPInfo"] = ($row["regip"] == "")?"注册IP:":"<a href='index.php?url=".urlencode("admin_patchuser.php?type=regip&word={$row["regip"]}")."' target='_blank'>注册IP:</a>";
        $arrRows[1]["RegIP"] = "<a href='http://www.ip138.com/ips.asp?ip={$row["regip"]}' target='_blank'>". $row["regip"] ."</a>";
        $arrRows[1]["DJPoints"] = $row['djpoints'] . "(次数:{$row['djcs']})";
        $arrRows[1]["UDAExp"] = $row["udaexperience"];
        
        $arrRows[1]["Points"] = Trans($row["points"]);
        $arrRows[1]["BankPoints"] = Trans($row["back"]);
        $arrRows[1]["LockPoints"] = Trans($row["lock_points"]);
        $arrRows[1]["TotalPoints"] = Trans($row["points"] + $row['back'] + $row["lock_points"]);
        $arrRows[1]["VIPStatus"] = ($row["vip"] == 0) ? "否" : ("是,到期时间" . $row["vipdate"]);
        $arrRows[1]["TjID"] = $row["tjid"];//"<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["tjid"]}")."' target='_blank'>{$row["tjid"]}</a>";
        $arrRows[1]["TjIncome"] = GetTjIncome($User_IDx);
        $arrRows[1]["TjStatic"] = "一层:" . $row["tj_level1_count"] . "，二层:" . $row["tj_level2_count"] . "，三层:" . $row["tj_level3_count"];
        $arrRows[1]["UDAPoints"] = $row["udapoints"]; 
        $arrRows[1]["isAgent"] = ($row["isagent"] == 0)?"否":"<font color='red'>代理</font>";
        $arrRows[1]["UserType"] = ($row["usertype"] == 1) ? "机器" : GetUserType($User_IDx);
		$arrRows[1]['kf']=$row['kf'];
		$arrRows[1]['qq']=$row['qq'];
		$arrRows[1]['multiple_loss']=$row['multiple_loss'];
		$arrRows[1]['memo']=$row['memo'];
		$arrRows[1]['charge_account']=$row['charge_account'];
		//新增正在投注的游戏
        $arrTemp = GetInGameStr($row["id"],$row["ingame"]);
		$arrRows[1]["InGame"] = $arrTemp["ingame"];
        
		$arrRows[1]["score_rebate"] = "<a class='edi' id='aScore_RwardCards' href='user_scorelog.php?id=$User_IDx&typeid=20'>".GetTjScore_log($User_IDx,20)."</a>";
		//$arrRows[1]["score_rebate"] = GetTjScore_log($User_IDx,20);
		$arrRows[1]["score_hb"] = "<a class='edi' id='aScore_RwardCardss' href='user_scorelog.php?id=$User_IDx&typeid=40'>".GetTjScore_log($User_IDx,40)."</a>";
		$arrRows[1]["lblTjTzStatic"] = "<a class='edi' id='aScore_lblTjTzStatic' href='user_day_gamestatic.php?id=$User_IDx'>".GetTjTzScore($User_IDx)."</a>";
        $AccountState = "";
        if($row["dj"] == 0)
        {
        	$AccountState = "正常<br><input type='button' value='冻结' id='btnForbidden' class='btn-1' />";
		}
        else
        {
        	$AccountState = "冻结( ". $row["djly"] .")";
			$AccountState .= "<br><input type='button' value='解封' id='btnOpen' class='btn-1'/>";
		}
		$AccountState .= "&nbsp;原因<input id='txtReason' type='text' style='width:150px' />";

		$dj_rebate = $row["dj_rebate"]==1?"checked":"";
		$dj_rankrebate = $row["dj_rankrebate"]==1?"checked":"";
		$dj_extension = $row["dj_extension"]==1?"checked":"";
		$AccountState .= "<br><input id='chkdj_rebate' type='checkbox' onclick='ChangeDetailItem(\"dj_rebate\")' {$dj_rebate} />冻结亏损返利&nbsp;<input id='chkdj_rankrebate' type='checkbox' onclick='ChangeDetailItem(\"dj_rankrebate\")' {$dj_rankrebate} />冻结排行奖励&nbsp;<input id='chkdj_extension' type='checkbox' onclick='ChangeDetailItem(\"dj_extension\")' {$dj_extension} />冻结推荐奖励&nbsp;";
		
		$arrRows[1]["AccountState"] = $AccountState;
		$isInGame = $row["ingame"];
		
		
		//取分来源
		$sql = "SELECT typeid,points FROM game_static WHERE uid = '{$User_IDx}'";
		$result = $db->query($sql);  
        $score_total = 0; 
        $game_total = 0;
        $arrRows[1]["Game_fast28"] = 0; 
        $arrRows[1]["Game_fast16"] = 0;
        $arrRows[1]["Game_fast11"] = 0;
        $arrRows[1]["Game_fast10"] = 0;
        $arrRows[1]["Game_fast22"] = 0;
        $arrRows[1]["Game_fast36"] = 0;
        $arrRows[1]["Game_fastgyj"] = 0;
        $arrRows[1]["Game_28"] = 0;
        $arrRows[1]["Game_36"] = 0;
        $arrRows[1]["Game_11"] = 0;
        $arrRows[1]["Game_16"] = 0;
        $arrRows[1]["Game_self28"] = 0;
        $arrRows[1]["Game_bj11"] = 0;
        $arrRows[1]["Game_bj16"] = 0;
        $arrRows[1]["Game_bj36"] = 0;
        $arrRows[1]["Game_bjww"] = 0;
        $arrRows[1]["Game_bjdw"] = 0;
        $arrRows[1]["Game_pk10"] = 0;
        $arrRows[1]["Game_gj10"] = 0;
        $arrRows[1]["Game_pk22"] = 0;
        $arrRows[1]["Game_pklh"] = 0;
        $arrRows[1]["Game_pkgyj"] = 0;
        $arrRows[1]["Game_hg28"] = 0;
        $arrRows[1]["Game_hg16"] = 0;
        $arrRows[1]["Game_hg11"] = 0;
        $arrRows[1]["Game_hg36"] = 0;
        $arrRows[1]["Game_can28"] = 0;
        $arrRows[1]["Game_can16"] = 0;
        $arrRows[1]["Game_can11"] = 0;
        $arrRows[1]["Game_can36"] = 0;
        
        $arrRows[1]["Game_ww"] = 0;
        $arrRows[1]["Game_dw"] = 0;
        $arrRows[1]["Game_canww"] = 0;
        $arrRows[1]["Game_candw"] = 0;
        $arrRows[1]["Game_hgww"] = 0;
        $arrRows[1]["Game_hgdw"] = 0;
        $arrRows[1]["Game_pksc"] = 0;
        
        $arrRows[1]["Game_28gd"] = 0;
        $arrRows[1]["Game_bj28gd"] = 0;
        $arrRows[1]["Game_hg28gd"] = 0;
        $arrRows[1]["Game_can28gd"] = 0;
        $arrRows[1]["Game_xync"] = 0;
        $arrRows[1]["Game_cqssc"] = 0;
        
        $arrRows[1]["Game_airship10"] = 0;
        $arrRows[1]["Game_airship22"] = 0;
        $arrRows[1]["Game_airshipgj10"] = 0;
        $arrRows[1]["Game_airshipgyj"] = 0;
        $arrRows[1]["Game_airshiplh"] = 0;
        
        
        $arrRows[1]["Other_100"] = 0;
        $arrRows[1]["Other_101"] = 0;
        $arrRows[1]["Other_103"] = 0;
        $arrRows[1]["Other_102"] = 0;
        $arrRows[1]["Other_104"] = 0;
        $arrRows[1]["Other_105"] = 0;
        $arrRows[1]["Other_106"] = 0;
        $arrRows[1]["Other_107"] = 0;
        $arrRows[1]["Other_108"] = 0;
        $arrRows[1]["Other_120"] = 0;  
        while($row=$db->fetch_array($result))
        { 
        	switch($row['typeid'])
        	{    
				case 0:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_fast28"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamefast28&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";         
					else
						$arrRows[1]["Game_fast28"] = Trans($row['points']);
						
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 1:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_fast16"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamefast16&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>"; 
					else
						$arrRows[1]["Game_fast16"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 2:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_fast11"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamefast11&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_fast11"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 3:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_28"] = "<a class='edi' href='user_kg_gamelog.php?gametype=game28&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_28"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 4:        
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_self28"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gameself28&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_self28"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 5:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_bj16"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamebj16&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_bj16"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 6:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_pk10"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamepk10&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_pk10"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 7:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_gj10"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamegj10&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_gj10"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 8:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_can28"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamecan28&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_can28"] = Trans($row['points']);
					$arrRows[1]["Game_can28"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 9:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_can16"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamecan16&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_can16"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 10:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_can11"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamecan11&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_can11"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 11:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_36"] = "<a class='edi' href='user_kg_gamelog.php?gametype=game36&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_36"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 12:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_bj36"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamebj36&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_bj36"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 13:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_can36"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamecan36&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_can36"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 14:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_pk22"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamepk22&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_pk22"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 15:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_fast10"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamefast10&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_fast10"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 16:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_pklh"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamepklh&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_pklh"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 17:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_pkgyj"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamepkgyj&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_pkgyj"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 18:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_hg28"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamehg28&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_hg28"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 19:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_hg16"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamehg16&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_hg16"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 20:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_hg11"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamehg11&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_hg11"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 21:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_hg36"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamehg36&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";   
					else
						$arrRows[1]["Game_hg36"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 22:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_fast22"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamefast22&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
					else
						$arrRows[1]["Game_fast22"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 23:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_fast36"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamefast36&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
					else
						$arrRows[1]["Game_fast36"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 24:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_fastgyj"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamefastgyj&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
					else
						$arrRows[1]["Game_fastgyj"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
					
				case 25:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_ww"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gameww&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
					else
						$arrRows[1]["Game_ww"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 26:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_dw"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamedw&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
					else
						$arrRows[1]["Game_dw"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 27:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_canww"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamecanww&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
					else
						$arrRows[1]["Game_canww"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 28:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_candw"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamecandw&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
					else
						$arrRows[1]["Game_candw"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 29:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_pksc"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamepksc&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
					else
						$arrRows[1]["Game_pksc"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 30:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_hgww"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamehgww&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
					else
						$arrRows[1]["Game_hgww"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 31:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_hgdw"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamehgdw&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
					else
						$arrRows[1]["Game_hgdw"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 32:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_28gd"] = "<a class='edi' href='user_kg_gamelog.php?gametype=game28gd&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
					else
						$arrRows[1]["Game_28gd"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 33:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_bj28gd"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamebj28gd&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
					else
						$arrRows[1]["Game_bj28gd"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 34:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_hg28gd"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamehg28gd&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
					else
						$arrRows[1]["Game_hg28gd"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 35:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_can28gd"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamecan28gd&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
					else
						$arrRows[1]["Game_can28gd"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;	
				case 36:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_xync"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamexync&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
					else
						$arrRows[1]["Game_xync"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;
				case 37:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_cqssc"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamecqssc&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
					else
						$arrRows[1]["Game_cqssc"] = Trans($row['points']);
					$score_total += $row['points'];
					$game_total += $row['points'];
					break;	
						
				case 38:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_bj11"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamebj11&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
						else
							$arrRows[1]["Game_bj11"] = Trans($row['points']);
						$score_total += $row['points'];
						$game_total += $row['points'];
						break;		
				case 39:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_11"] = "<a class='edi' href='user_kg_gamelog.php?gametype=game11&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
						else
							$arrRows[1]["Game_11"] = Trans($row['points']);
						$score_total += $row['points'];
						$game_total += $row['points'];
						break;
				case 40:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_16"] = "<a class='edi' href='user_kg_gamelog.php?gametype=game16&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
						else
							$arrRows[1]["Game_16"] = Trans($row['points']);
						$score_total += $row['points'];
						$game_total += $row['points'];
						break;
				case 41:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_bjww"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gameww&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
						else
							$arrRows[1]["Game_bjww"] = Trans($row['points']);
						$score_total += $row['points'];
						$game_total += $row['points'];
						break;
				case 42:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_bjdw"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gamedw&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
						else
							$arrRows[1]["Game_bjdw"] = Trans($row['points']);
						$score_total += $row['points'];
						$game_total += $row['points'];
						break;	
						
						
				case 43:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_airship10"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gameairship10&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
						else
							$arrRows[1]["Game_airship10"] = Trans($row['points']);
						$score_total += $row['points'];
						$game_total += $row['points'];
						break;	
				case 44:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_airship22"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gameairship22&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
						else
							$arrRows[1]["Game_airship22"] = Trans($row['points']);
						$score_total += $row['points'];
						$game_total += $row['points'];
						break;
				case 45:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_airshipgyj"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gameairshipgyj&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
						else
							$arrRows[1]["Game_airshipgyj"] = Trans($row['points']);
						$score_total += $row['points'];
						$game_total += $row['points'];
						break;	
				case 46:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_airshipgj10"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gameairshipgj10&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
						else
							$arrRows[1]["Game_airshipgj10"] = Trans($row['points']);
						$score_total += $row['points'];
						$game_total += $row['points'];
						break;
				case 47:
					if( intval($isInGame & pow(2,$row['typeid'])) == intval(pow(2,$row['typeid'])) )
						$arrRows[1]["Game_airshiplh"] = "<a class='edi' href='user_kg_gamelog.php?gametype=gameairshiplh&id={$User_IDx}'><font color='#FF0000'>" . Trans($row['points']) . "</font></a>";
						else
							$arrRows[1]["Game_airshiplh"] = Trans($row['points']);
						$score_total += $row['points'];
						$game_total += $row['points'];
						break;		
						
					
				case 100:
					$arrRows[1]["Other_100"] = Trans($row['points']);
					$score_total += $row['points'];
					break;
				case 101:
					$arrRows[1]["Other_101"] = Trans($row['points']);
					$score_total += $row['points'];
					break;
				case 102:
					$arrRows[1]["Other_102"] = Trans($row['points']);
					$score_total += $row['points'];
					break;
				case 140:
					$arrRows[1]["Other_103"] = Trans($row['points']);
					$score_total += $row['points'];
					break;
				case 104:
					$arrRows[1]["Other_104"] = Trans($row['points']);
					$score_total += $row['points'];
					break;
				case 105:
					$arrRows[1]["Other_105"] = Trans($row['points']);
					$score_total += $row['points'];
					break;
				case 106:
					$arrRows[1]["Other_106"] = Trans($row['points']);
					$score_total += $row['points'];
					break;
				case 141:
					$arrRows[1]["Other_107"] = Trans($row['points']);
					$score_total += $row['points'];
					break;
				case 142:
					$arrRows[1]["Other_108"] = Trans($row['points']);
					$score_total += $row['points'];
					break;
				case 120:
					$arrRows[1]["Other_120"] = Trans($row['points']);
					$score_total += $row['points'];
					break;
				default:
					break;
        	}
		}    
		$arrRows[1]["Score_total"] = Trans($score_total + $arrRows[1]["LockPoints"]);
		$arrRows[1]["TotalGameWinLose"] = "<font color='red'>" . Trans($game_total) . "</font>";
		//返回结果
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = "ok"; 
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
    }
    /* 取推荐奖励
    *
    */
    function GetTjIncome($uid)
    {
		global $db;
		$msg = "无推荐收益";
		$sql = "select tj_next_leve1_points,tj_next_leve2_points,tj_next_leve3_points 
				from user_tj_reward where uid = {$uid}";
		$result = $db->query($sql);
		if($row=$db->fetch_array($result))
		{
			$msg = "一层:" . Trans($row["tj_next_leve1_points"]) .
					",二层:" . Trans($row["tj_next_leve2_points"]) .
					",二层:" . Trans($row["tj_next_leve3_points"]) .
					",总:" . Trans($row["tj_next_leve1_points"] + $row["tj_next_leve2_points"] + $row["tj_next_leve3_points"]);
		}
		return $msg;
    }
    /* 取用户类型
    *
    */
    function GetUserType($uid)
    {
		global $db;
		$msg = "玩家";
		$sql = "select remark from users_inner where uid = {$uid}";
		$result = $db->query($sql);
		if($row=$db->fetch_array($result))
		{
			$msg = "内部号-" . $row["remark"];
		}
		return $msg;
    }
	
	 /* 取返水红包总计
    *
    */
   function GetTjScore_log($uid,$type)
    {
		global $db;
		$sql = "select sum(amount) as amount from score_log where uid={$uid} and opr_type={$type}";
		$info=$db->fetch_first($sql);
		//var_dump($info);
		return Trans($info['amount']);
    }
	
	/* 取个人流水投注总计
    *
    */
   function GetTjTzScore($uid)
    {
		global $db;
		$sql = "select sum(tzPoints) as tzPoints from game_day_static where uid={$uid}";
		$info=$db->fetch_first($sql);
		//var_dump($info);
		return Trans($info['tzPoints']);
    }
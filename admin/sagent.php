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
	    	case "agent_remove_agentdaystatic": //删除6个月前数据
	    		login_check( "users" );
	    		RemoveAgentDayStatic($act);
	    		break;
	    	case "get_agent_daystat": //取得代理日统计
	    		GetAgentDayStat($act);
	    		break;
	    	case "get_agent_monthstat": //取得代理月统计
	    		GetAgentMonthStat($act);
	    		break;
	    	case "agent_changecardstate": //更改兑换卡状态
	    		login_check( "users" );
	    		ChangeExchangeState($act);
	    		break;
	    	case "get_user_exchangecard": //取得用户兑换卡列表
	    		GetUserExchangeCardList($act);
	    		break;
	    	case "remove_cardtype": //删除卡类型
	    		login_check( "jpgl" );
	    		RemoveExchangeCardType($act);
	    		break;
	    	case "save_exhcangecardtype": //保存卡类型
	    		login_check( "jpgl" );
	    		SaveExchangeCardType($act);
	    		break;
	    	case "get_exchangecardtype": // 取得卡类型
	    		GetExchangeCardType($act);
	    		break;
	    	case "dual_withdraw": // 处理提现
	    		login_check( "users" );
	    		DualWithdraw($act);
	    		break;
	    	case "get_agent_withdraw": // 取提现申请
	    		GetAgentWithDraw($act);
	    		break; 
	    	case "agent_removelog": //删除代理操作日志
	    		login_check( "users" );
	    		RemoveAgentOprLog($act);
	    		break;
	    	case "get_agent_oprlog": //取得代理操作日志
	    		GetAgentOprLog($act);
	    		break;
	    	case "get_oprlog_type": //取得代理操作option
	    		GetOprOption($act);
	    		break;
	    	case "get_agentlist_option": //取得代理option
	    		GetAgentListOption($act);
	    		break;
	    	case "agent_change_recommend": // 修改推荐
	    		login_check( "users" );
	    		ChangeAgentRecommend($act);
	    		break;
	    	case "agent_change_state":// 修改代理状态
	    		login_check( "users" );
	    		ChangeAgentState($act);
	    		break;
	    	case "get_agent_detail": // 取得代理明细
	    		GetAgentDetail($act);
	    		break;
	    	case "edit_agent": //编辑代理
	    		login_check( "users" );
	    		EditAgent($act);
	    		break;
	    	case "addnew_agent": //生成代理
	    		login_check( "users" );
	    		CreateAgent($act);
	    		break;
	    	case "check_agentuserid": // 检测用户id
	    		CheckAgentUserID($act);
	    		break;
	    	case "get_agentlist": // 取代理列表
	    		GetAgentList($act);
	    		break;
	        default:
	            exit;
	    }
	}
	/* 删除6个月前数据
	*
	*/
	function RemoveAgentDayStatic($act)
	 {
		global $db;
		$month = intval($_POST['m']);
		$arrReturn = array(array("cmd"=>"","msg"=>""));
		$sql = "delete from agent_day_static where thedate < DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -{$month} MONTH),'%Y-%m-01')";
		$result = $db->query($sql);
		
		$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		//添加日志
		addlog("删除代理日统计日志:".$msg);
		
		$arrReturn[0]["cmd"] = "ok";
        $arrReturn[0]["msg"] = $msg;  
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 取得代理日统计
	*
	*/
	function GetAgentDayStat($act)
	{
		global $db;
        $AgentID = isset($_POST['agentid'])?FilterStr($_POST['agentid']):"0";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT a.thedate,b.agent_name,b.buycard_rate,b.reccard_rate,a.out_points,a.out_points * (1-b.buycard_rate) AS sellprofit,
						a.in_points,a.in_points * (1-b.reccard_rate) AS recprofit";
		$sqlFrom = " FROM agent_day_static a
					LEFT OUTER JOIN agent b
					ON a.agentid = b.id
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
        if($AgentID != "0")        
            $sqlWhere .= " and a.agentid = " . $AgentID;
        
        //时间
        $TimeField = "a.thedate";
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
		$total_sellrmb = 0;
		$total_sellprofit = 0;
		$total_recrmb = 0;
		$total_recprofit = 0;
		$total_totalprofit = 0;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
            $arrRows[$i]["AgentName"] = $row["agent_name"];
            $arrRows[$i]["TheDay"] = date("Y-m-d",strtotime($row["thedate"]));
            $arrRows[$i]["SellRMB"] = Trans($row["out_points"]/1000);
            $arrRows[$i]["SellRate"] = $row["buycard_rate"];
            $arrRows[$i]["SellProfit"] = Trans($row["sellprofit"]/1000);
            $arrRows[$i]["RecRMB"] = Trans($row["in_points"]/1000);
			$arrRows[$i]["RecRate"] = $row["reccard_rate"];
			$arrRows[$i]["RecProfit"] = Trans($row["recprofit"]/1000);
			$arrRows[$i]["TotalProfit"] = Trans($row["sellprofit"]/1000 + $row["recprofit"]/1000);
			
			$total_sellrmb += $row["out_points"]/1000;
			$total_sellprofit += $row["sellprofit"]/1000;
			$total_recrmb += $row["in_points"]/1000;
			$total_recprofit += $row["recprofit"]/1000; 
			$total_totalprofit += $row["out_points"]/1000 + $row["recprofit"]/1000; 
		}
		
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
			$arrRows[$i]["AgentName"] = "";
			$arrRows[$i]["TheDay"] = "页小计:"; 
            $arrRows[$i]["SellRMB"] = Trans($total_sellrmb);
            $arrRows[$i]["SellRate"] = ""; 
			$arrRows[$i]["SellProfit"] = Trans($total_sellprofit);
			$arrRows[$i]["RecRMB"] = Trans($total_recrmb);
			$arrRows[$i]["RecRate"] = ""; 
			$arrRows[$i]["RecProfit"] = Trans($total_recprofit);
			$arrRows[$i]["TotalProfit"] = Trans($total_totalprofit);
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
    }
	/* 取得代理月统计
	*
	*/
	function GetAgentMonthStat($act)
	{
		global $db;
		$sql = "
		SELECT DATE_FORMAT(a.thedate,'%Y-%m') AS themonth,SUM(a.out_points) AS sum_out_points,
		SUM(a.out_points * (1-b.buycard_rate)) AS sum_sellprofit,
		SUM(a.in_points) AS sum_in_points,
		SUM(a.in_points * (1-b.reccard_rate)) AS sum_recprofit
		FROM agent_day_static a
		LEFT OUTER JOIN agent b
		ON a.agentid = b.id
		GROUP BY themonth
		ORDER BY themonth DESC
		limit 6;
		";
		$arrRows = array(array());
		$result = $db->query($sql);
		//取得返回记录数
		$RowCount = $db->num_rows($result);
		$total_sellrmb = 0;
		$total_sellprofit = 0;
		$total_recrmb = 0;
		$total_recprofit = 0;
		$total_totalprofit = 0;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
			$arrRows[$i]["theMonth"] = $row["themonth"]; 
            $arrRows[$i]["SellRMB"] = Trans($row["sum_out_points"]/1000);
			$arrRows[$i]["SellProfit"] = Trans($row["sum_sellprofit"]/1000);
			$arrRows[$i]["RecRMB"] = Trans($row["sum_in_points"]/1000);
			$arrRows[$i]["RecProfit"] = Trans($row["sum_recprofit"]/1000);
			$arrRows[$i]["TotalProfit"] = Trans($row["sum_out_points"]/1000 + $row["sum_recprofit"]/1000);
			
			$total_sellrmb += $row["sum_out_points"]/1000;
			$total_sellprofit += $row["sum_sellprofit"]/1000;
			$total_recrmb += $row["sum_in_points"]/1000;
			$total_recprofit += $row["sum_recprofit"]/1000; 
			$total_totalprofit += $row["sum_out_points"]/1000 + $row["sum_recprofit"]/1000;
		}
		if($RowCount > 1)
		{
			$i = $RowCount + 1; 
			$arrRows[$i]["theMonth"] = "页小计:"; 
            $arrRows[$i]["SellRMB"] = Trans($total_sellrmb);
			$arrRows[$i]["SellProfit"] = Trans($total_sellprofit);
			$arrRows[$i]["RecRMB"] = Trans($total_recrmb);
			$arrRows[$i]["RecProfit"] = Trans($total_recprofit);
			$arrRows[$i]["TotalProfit"] = Trans($total_totalprofit);
		}
		$arrRows[0]["cmd"] = $act;
		$arrRows[0]["msg"] = $pageInfo;
        ArrayChangeEncode($arrRows); 
        //WriteLog(json_encode($arrRows));
		echo json_encode($arrRows);
	}
	/* 更改兑换卡状态
	*
	*/
	function ChangeExchangeState($act)
	{
		global $db;
		$RecID = intval($_POST['id']);
		$State = isset($_POST['state'])?FilterStr($_POST['state']):"";
		$arrReturn = array(array("cmd"=>"","msg"=>""));
		if($RecID == 0 || $State == "")
		{
			$arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "参数错误!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
		}
		if($State == "1") //冻结
		{
			$CardState = 2;
			$msg = "冻结卡，记录id:" . $RecID;
		}
		else
		{
			$CardState = 0;
			$msg = "取消冻结兑换卡,记录id:" . $RecID;
		}
		$sql = "update exchange_cards set state = {$CardState},used_time=now() where id = '{$RecID}'";
		$result = $db->query($sql);
		
		$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		//添加日志
		addlog("操作用户兑奖卡:,".$msg);
		
		$arrReturn[0]["cmd"] = "ok";
        $arrReturn[0]["msg"] = $msg;  
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 取得用户兑奖卡列表
	*
	*/
	function GetUserExchangeCardList($act)
	{
		global $db;
		$UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $AgentID = isset($_POST['agentid'])?FilterStr($_POST['agentid']):"0";
        $CardState = isset($_POST['cardstate'])?FilterStr($_POST['cardstate']):"-1";
        $CardNo = isset($_POST['cardno'])?FilterStr($_POST['cardno']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT a.id,a.uid,a.nickname,a.card_type,b.card_name,a.card_no,a.card_pwd,a.card_points,a.add_time,a.add_ip,
						a.agentid,IFNULL(c.agent_name,'') AS agent_name,a.used_time,a.used_ip,a.remark,a.state";
		$sqlFrom = " FROM exchange_cards a
					LEFT OUTER JOIN exchange_cardtype b
					ON a.card_type = b.card_type
					LEFT OUTER JOIN agent c
					ON a.agentid = c.id
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
        if($AgentID != "0")        
            $sqlWhere .= " and a.agentid = " . $AgentID;
        if($CardState != "-1")
            $sqlWhere .= " and a.state = " . $CardState;
        if($CardNo != "")
            $sqlWhere .= " and a.card_no like '%{$CardNo}%'";
            
        if($CardState != "-1")
            $sqlWhere .= " and a.state = " . $CardState;
        
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
		$total_points = 0;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{  //对返回数据进行包装
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["NickName"] = $row["nickname"];
            $arrRows[$i]["CardName"] = $row["card_name"];
            $arrRows[$i]["CardNo"] = $row["card_no"];
            $arrRows[$i]["CardPwd"] = $row["card_pwd"];
            $arrRows[$i]["CardPoints"] = Trans($row["card_points"]);
            $arrRows[$i]["AddTime"] = $row["add_time"];
            $IPInfo = "<a href='http://www.ip138.com/ips.asp?ip={$row["add_ip"]}' target='_blank'>". $row["add_ip"] ."</a>";
			$IPInfo .= "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["add_ip"]}")."' target='_blank'>批</a>";
			$arrRows[$i]["AddIP"] = $IPInfo;
			$arrRows[$i]["AgentName"] = $row["agent_name"];
			$arrRows[$i]["UsedTime"] = $row["used_time"];
			$IPInfo = "<a href='http://www.ip138.com/ips.asp?ip={$row["used_ip"]}' target='_blank'>". $row["used_ip"] ."</a>";
			$IPInfo .= "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["used_ip"]}")."' target='_blank'>批</a>";
			$arrRows[$i]["UsedIP"] = $IPInfo;
			$State = "";
			$Opr = "";
			switch($row["state"])
			{
				case "0":
					$State = "<font color='#0000FF'>已生成</font>";
					$Opr = "<a style='cursor:pointer' onclick='ChangeState({$row['id']},1)'>冻结</a>";
					break;
				case "1":
					$State = "<font color='#9966FF'>已处理</font>";
					break;
				case "2":
					$State = "<font color='red'>已冻结</font>";
					$Opr = "<a style='cursor:pointer' onclick='ChangeState({$row['id']},0)'>取消冻结</a>";
					break;
				default:
					$State = "未知状态";
					break;
			} 
			
			$arrRows[$i]["State"] = $State;
			$arrRows[$i]["Opr"] = $Opr;
			
			
			$total_points += $row["card_points"]; 
		}
		
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
			$arrRows[$i]["UserID"] = "";
            $arrRows[$i]["NickName"] = ""; 
            $arrRows[$i]["CardName"] = ""; 
            $arrRows[$i]["CardNo"] = ""; 
            $arrRows[$i]["CardPwd"] = "页小计:"; 
            $arrRows[$i]["CardPoints"] = Trans($total_points);
            $arrRows[$i]["AddTime"] = "";
            $arrRows[$i]["AddIP"] = "";
			$arrRows[$i]["AgentName"] = "";
			$arrRows[$i]["UsedTime"] = "";
			$arrRows[$i]["UsedIP"] = "";
			$arrRows[$i]["State"] = "";
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
    }
	/* 删除卡类型
	*
	*/
	function RemoveExchangeCardType($act)
	{
		global $db;
		$cardtype = intval($_POST['cardtype']);
		$arrReturn = array(array("cmd"=>"","msg"=>""));
		$sql = "delete from exchange_cardtype where card_type = {$cardtype}";
		$result = $db->query($sql);
		
		$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		//添加日志
		addlog("删除卡类型:".$msg);
		
		$arrReturn[0]["cmd"] = "ok";
        $arrReturn[0]["msg"] = $msg;  
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 保存卡类型
	*
	*/
	function SaveExchangeCardType($act)
	{
		global $db;
		$CardType = intval($_POST['cardtype']);
		$CardRMB = intval($_POST['cardrmb']);
		$CardName = isset($_POST['cardname'])?FilterStr($_POST['cardname']):"";
		$Remark = isset($_POST['remark'])?FilterStr($_POST['remark']):"";
		
		$CardName = ChangeEncodeU2G($CardName);
		$Remark = ChangeEncodeU2G($Remark);
		$arrReturn = array(array("cmd"=>"","msg"=>""));
		if($CardType == 0 || $CardRMB == 0 || $CardName == "")
		{
			$arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "参数错误!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
		}
		$sql = "select 1 from exchange_cardtype where card_type = '{$CardType}'";
		$result = $db->query($sql);
		//取得返回记录数
		$RowCount = $db->num_rows($result);
		if($RowCount > 0)
		{
			$arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "卡类型已存在!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
		}
		$sql = "insert into exchange_cardtype(card_type,card_name,card_rmb,remark)
					values({$CardType},'{$CardName}',{$CardRMB},'{$Remark}')";
		$result = $db->query($sql);
		$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		//添加日志
		addlog("生成兑换卡类型:".$msg);
		
		$arrReturn[0]["cmd"] = "ok";
        $arrReturn[0]["msg"] = $msg;  
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
		
	}
	/* 取得卡类型
	*
	*/
	function GetExchangeCardType($act)
	{
		global $db;
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT  card_type,card_name,card_rmb,remark
					";
		$sqlFrom = " FROM exchange_cardtype
					where 1=1";
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
		$sqlOrder = (($Order == "") ? "" : " order by card_type");
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
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
			$arrRows[$i]["CardType"] = $row["card_type"];
            $arrRows[$i]["CardRMB"] = $row["card_rmb"];
			$arrRows[$i]["CardName"] = $row["card_name"];
			$arrRows[$i]["Remark"] = $row["remark"];
			$Opr = "<a style='cursor:pointer' onclick='RemoveCardType({$row['card_type']})'>删除</a>";
			$arrRows[$i]["Opr"] = $Opr;
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
    }
	/* 处理审核
	*
	*/
	function DualWithdraw($act)
	{
		global $db;
		
		$RecID = intval($_POST['id']);
		$State = intval($_POST['state']);
		$Remark = isset($_POST['remark'])?FilterStr($_POST['remark']):"";
		
		$arrReturn = array(array("cmd"=>"","msg"=>""));
		if($RecID == 0 || $State == 0)
		{
			$arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "参数错误!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
		}
		$sql = "call web_agent_dual_withdraw({$RecID},{$State},'{$Remark}','{$_SESSION['Admin_Name']}')";
		//WriteLog($sql);
		$arrT = $db->Mysqli_Multi_Query($sql);
		$msg = "";
		switch($arrT[0][0]["result"])
		{
			case 0:
				if($State == 1)
					$msg = "操作成功！";
				else
					$msg = "操作成功!代理申请额".$arrT[0][0]['points']."已退回用户银行!";
				addlog("审核代理订单". $RecID ."提现申请,".$msg);
				break;
			case 1:
				$msg = "不能对用户已撤销、管理员撤销或者已处理的订单进行处理";
				break;
			case 99:
				$msg = "系统错误";
				break;
			default:
				$msg = "未知错误";
				break;
		}
		$arrReturn[0]["cmd"] = "ok";
        $arrReturn[0]["msg"] = $msg;
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 取提现申请
	*
	*/
	function GetAgentWithDraw($act)
	{
		global $db;
        $AgentID = isset($_POST['agentid'])?FilterStr($_POST['agentid']):"";
        $State = isset($_POST['state'])?FilterStr($_POST['state']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT a.id,b.uid,a.agentid,b.agent_name,a.points,a.add_time,a.state,a.opr_time,a.opr_user,
					b.distribute_money,(c.points + c.back + c.lock_points) AS totalpoints,a.msg
					";
		$sqlFrom = " FROM agent_withdraw a
					LEFT OUTER JOIN agent b
					ON a.agentid = b.id
					LEFT OUTER JOIN users c
					ON b.uid = c.id
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
        if($AgentID != "0")
            $sqlWhere .= " and a.agentid = " . $AgentID;
        if($State != "-1")
            $sqlWhere .= " and a.state = " . $State;
        
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
		$total_points = 0;
		$total_curpoints = 0;
		$total_DBMoney = 0;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
			$arrRows[$i]["ID"] = $row["id"];
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["AgentName"] = $row["agent_name"];
            $arrRows[$i]["AddTime"] = $row["add_time"];
			$arrRows[$i]["Points"] = Trans($row["points"]) . "(￥". Trans($row['points']/1000) .")";
			$arrRows[$i]["TotalPoints"] = Trans($row["totalpoints"]) . "(￥". Trans($row['totalpoints']/1000) .")";
			$arrRows[$i]["DBPoints"] = Trans($row["distribute_money"]) . "(￥". Trans($row['distribute_money']/1000) .")";
			$arrRows[$i]["OprTime"] = $row["opr_time"];
			$arrRows[$i]["OprUser"] = $row["opr_user"];
			
			$State = "";
			$Opr = "";
			switch($row['state'])
			{
				case 0: //未处理
					$State = "<font color='#0000FF'>未处理</font>";
					$Opr = "<a style='cursor:pointer' onclick='ToDual({$row['id']},1)'>通过</a>&nbsp;|&nbsp;<a style='cursor:pointer' onclick='ToDual({$row['id']},3)'>撤销</a>";
					$Opr .= "<br>" . "<input type='text' id='remark_{$row['id']}' value='已打款' style='width:150px;border:1px solid #999;background-color:#FFFFCC;' />";
					break;
				case 1: //已处理
					$State = "<font color='#9966FF'>已处理</font>";
					$Opr = $row["msg"];
					break;
				case 2: //用户撤销
					$State = "<font color='red'>用户撤销</font>";
					$Opr = $row["msg"];
					break;
				case 3: //管理员撤销
					$State = "<font color='red'>管理员撤销</font>";
					$Opr = $row["msg"];
					break;
				default:
					$State = "未知状态";
					$Opr = $row["msg"];
					break;
			}
			
			$arrRows[$i]["State"] = $State;
			$arrRows[$i]["Opr"] = $Opr;
			
			
			$total_points += $row["points"];
			$total_curpoints += $row["totalpoints"];
			$total_DBMoney += $row["distribute_money"]; 
		}
		
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
			$arrRows[$i]["ID"] = "";
			$arrRows[$i]["UserID"] = "";
            $arrRows[$i]["AgentName"] = "";
            $arrRows[$i]["AddTime"] ="页小结:";
			$arrRows[$i]["Points"] = Trans($total_points) . "(￥". Trans($total_points/1000) .")";
			$arrRows[$i]["TotalPoints"] = Trans($total_curpoints) . "(￥". Trans($total_curpoints/1000) .")";
			$arrRows[$i]["DBPoints"] = Trans($total_DBMoney) . "(￥". Trans($total_DBMoney/1000) .")";
			$arrRows[$i]["OprTime"] = "";
			$arrRows[$i]["OprUser"] = "";
			$arrRows[$i]["State"] = "";
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
    }
	/* 删除代理操作日志
	*
	*/
	function RemoveAgentOprLog($act)
	{
		global $db;
		$month = intval($_POST['m']);
		$arrReturn = array(array("cmd"=>"","msg"=>""));
		$sql = "delete from agent_oprlog where opr_time < date_add(now(),interval -{$month} month)";
		$result = $db->query($sql);
		
		$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		//添加日志
		addlog("删除代理操作日志,".$msg);
		
		$arrReturn[0]["cmd"] = "ok";
        $arrReturn[0]["msg"] = $msg;  
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 取得代理操作日志
	*
	*/
	function GetAgentOprLog($act)
	{
		global $db;
        $AgentID = isset($_POST['agentid'])?FilterStr($_POST['agentid']):"";
        $OprType = isset($_POST['oprtype'])?FilterStr($_POST['oprtype']):"";
        $Content = isset($_POST['content'])?FilterStr($_POST['content']):"";
        $TimeBegin = isset($_POST['timebegin'])?FilterStr($_POST['timebegin']):"";
        $TimeEnd = isset($_POST['timeend'])?FilterStr($_POST['timeend']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT a.agentid,b.uid,b.agent_name,a.opr_type,c.opr_name,a.opr_time,a.opr_ip,
					a.opr_points,a.cur_totalpoints,a.content
					";
		$sqlFrom = " FROM agent_oprlog a
					LEFT OUTER JOIN agent b
					ON a.agentid = b.id
					left outer join agent_oprtype c
					on a.opr_type = c.opr_type
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
        if($AgentID != "0")
            $sqlWhere .= " and a.agentid = " . $AgentID;
        if($OprType != "0")
            $sqlWhere .= " and a.opr_type = " . $OprType;
        if($Content != "")
            $sqlWhere .= " and a.content like '%". $Content ."%'";
        
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
		$total_points = 0;
		$total_curpoints = 0;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["AgentName"] = $row["agent_name"];
            $arrRows[$i]["OprType"] = $row["opr_name"];
            $arrRows[$i]["Content"] = $row["content"];
			$arrRows[$i]["OprPoints"] = Trans($row["opr_points"]);
			$arrRows[$i]["TotalPoints"] = Trans($row["cur_totalpoints"]);
			$arrRows[$i]["OprTime"] = $row["opr_time"];
			$IPInfo = "<a href='http://www.ip138.com/ips.asp?ip={$row["opr_ip"]}' target='_blank'>". $row["opr_ip"] ."</a>";
			$IPInfo .= "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["opr_ip"]}")."' target='_blank'>批</a>";
			
			$arrRows[$i]["OprIP"] = $IPInfo;
			$total_points += $row["opr_points"];
			$total_curpoints += $row["cur_totalpoints"];
		}
		
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
			$arrRows[$i]["UserID"] = "";
            $arrRows[$i]["AgentName"] = "";
            $arrRows[$i]["OprType"] = "";
            $arrRows[$i]["Content"] = "页小计";
			$arrRows[$i]["OprPoints"] = Trans($total_points);
			$arrRows[$i]["TotalPoints"] = Trans($total_curpoints);
			$arrRows[$i]["OprTime"] = "";
			$arrRows[$i]["OprIP"] = "";
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
	/* 取得代理操作option
	*
	*/
	function GetOprOption($act)
	 {
		global $db;
		$sql = "select opr_type,opr_name from agent_oprtype";
		$result = $db->query($sql);
		$option = "<option value='0'>所有操作</option>";
		while($row=$db->fetch_array($result))
		{
			$option .= "<option value='{$row['opr_type']}'>{$row['opr_name']}</option>";
		}
		$arrReturn = array(array());
		$arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = $option;
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 取得代理option
	*
	*/
	function GetAgentListOption($act)
	 {
		global $db;
		$sql = "select id,agent_name from agent";
		$result = $db->query($sql);
		$option = "<option value='0'>所有代理</option>";
		while($row=$db->fetch_array($result))
		{
			$option .= "<option value='{$row['id']}'>{$row['agent_name']}</option>";
		}
		$arrReturn = array(array());
		$arrReturn[0]["cmd"] = $act;
        $arrReturn[0]["msg"] = $option;
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 修改推荐
	*
	*/
	function ChangeAgentRecommend($act)
	{
		global $db;
        $recID = intval($_POST['id']);
        $flag = intval($_POST['flag']);
        
        $sql = "update agent set is_recommend = {$flag} where id = {$recID}";
        $result = $db->query($sql);
		
		$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		
		//添加日志
		addlog("更改代理{$recID}推荐为{$flag},".$msg);
		
		$arrReturn[0]["cmd"] = "ok";
        $arrReturn[0]["msg"] = $msg;
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 修改代理状态
	*
	*/
	function ChangeAgentState($act)
	{
		global $db;
        $recID = intval($_POST['id']);
        $flag = intval($_POST['flag']);
        
        $sql = "update agent set state = {$flag} where id = {$recID}";
        $result = $db->query($sql);
        $rownum = $db->affected_rows();
		$msg = "操作成功，影响记录数" . $rownum ."条";
		//添加日志
		addlog("更改代理{$recID}状态为{$flag},".$msg);
		
		if($rownum > 0){
			$sql = "select uid from agent where id = {$recID}";
			$result = $db->fetch_first($sql);
			$uid = $result['uid'];
			$sql = "update users set isagent = {$flag} where id = {$uid}";
			$db->query($sql);
		}
		
		
		$arrReturn[0]["cmd"] = "ok";
        $arrReturn[0]["msg"] = $msg;
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 取得代理明细
	*
	*/
	function GetAgentDetail($act)
	{
		global $db;
        $recID = intval($_POST['id']);
		$sql = "SELECT a.*,(b.points+b.back+b.lock_points) as totalpoints 
				FROM agent a
				LEFT OUTER JOIN users b
				ON a.uid = b.id
				WHERE a.id = '{$recID}' ";
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
		$i = 1;
		$arrRows[$i]["RecID"] = $row["id"];
        $arrRows[$i]["UserID"] = $row["uid"];
        $arrRows[$i]["AddTime"] = $row["add_time"];
        $arrRows[$i]["TotalPoints"] = Trans($row["totalpoints"]);
		$arrRows[$i]["AgentName"] = $row["agent_name"]; 
		$arrRows[$i]["BuyCardRate"] = $row["buycard_rate"];
		$arrRows[$i]["RecCardRate"] = $row["reccard_rate"];
		$arrRows[$i]["RecCardProfitRate"] = $row["reccard_profit_rate"];
		$arrRows[$i]["DistributeMoney"] = $row["distribute_money"];
		$arrRows[$i]["CanRecCard"] = $row["can_reccard"];
		$arrRows[$i]["IsRecommend"] = $row["is_recommend"];
		$arrRows[$i]["State"] = $row["state"];
		
		$arrRows[0]["cmd"] = $act;
		$arrRows[0]["msg"] = "ok";
        ArrayChangeEncode($arrRows); 
        //WriteLog(json_encode($arrRows));
		echo json_encode($arrRows);
		exit;
    }
	/* 编辑代理
	*
	*/
	function EditAgent($act)
	{
		global $db;
		$RecID = intval($_POST['recid']);
		$bcMoney = intval($_POST["bcmoney"]);
		
		$bcRate = isset($_POST['bcrate'])?FilterStr($_POST['bcrate']):"";
		$rcRate = isset($_POST['rcrate'])?FilterStr($_POST['rcrate']):"";
		$rcpfRate = isset($_POST['rcpfrate'])?FilterStr($_POST['rcpfrate']):""; 
		$AgentName = isset($_POST['agentname'])?FilterStr($_POST['agentname']):"";
		$CanRecCard = isset($_POST['canreccard'])?FilterStr($_POST['canreccard']):"";
		$IsRecommend = isset($_POST['isrecommend'])?FilterStr($_POST['isrecommend']):"";
		$State = isset($_POST['state'])?FilterStr($_POST['state']):"";
		
		$AgentName = ChangeEncodeU2G($AgentName);
		
		$arrReturn = array(array("cmd"=>"","msg"=>""));
		if(intval($RecID) == 0 || $bcRate > 1 || $rcRate > 1 || $rcpfRate > 1 || $AgentName == ""
			|| $bcRate < 0 || $rcRate < 0 || $rcpfRate < 0 )
		{
			$arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "参数错误!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
		}
		$sql = "update agent set agent_name = '{$AgentName}',buycard_rate={$bcRate},reccard_rate={$rcRate},
					reccard_profit_rate={$rcpfRate},distribute_money={$bcMoney},can_reccard={$CanRecCard},
					is_recommend={$IsRecommend},state={$State}
		        where id = '{$RecID}'";
		$result = $db->query($sql);
		
		$msg = "操作成功，影响记录数" . $db->affected_rows() ."条";
		//添加日志
		addlog("修改代理{$RecID}资料,".$msg);
		
		$arrReturn[0]["cmd"] = "ok";
        $arrReturn[0]["msg"] = $msg;  
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 生成代理
	*
	*/
	function CreateAgent($act)
	{
		global $db;
		
		$UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
		$bcMoney = intval($_POST["bcmoney"]);
		
		$bcRate = isset($_POST['bcrate'])?FilterStr($_POST['bcrate']):"";
		$rcRate = isset($_POST['rcrate'])?FilterStr($_POST['rcrate']):"";
		$rcpfRate = isset($_POST['rcpfrate'])?FilterStr($_POST['rcpfrate']):""; 
		$AgentName = isset($_POST['agentname'])?FilterStr($_POST['agentname']):"";
		$CanRecCard = isset($_POST['canreccard'])?FilterStr($_POST['canreccard']):"";
		$IsRecommend = isset($_POST['isrecommend'])?FilterStr($_POST['isrecommend']):"";
		$State = isset($_POST['state'])?FilterStr($_POST['state']):"";
		
		//$AgentName = ChangeEncodeU2G($AgentName);
		
		$arrReturn = array(array("cmd"=>"","msg"=>""));
		if(intval($UserID) == 0 || $bcRate > 1 || $rcRate > 1 || $rcpfRate > 1 || $AgentName == ""
			|| $bcRate < 0 || $rcRate < 0 || $rcpfRate < 0 )
		{
			$arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "参数错误!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
		}
		$sql = "call web_agent_add({$UserID},'{$AgentName}',{$bcMoney},{$bcRate},{$rcRate},{$rcpfRate},{$CanRecCard},{$IsRecommend},{$State})";
		$arrT = $db->Mysqli_Multi_Query($sql);
		$msg = "";
		switch($arrT[0][0]["result"])
		{
			case 0:
				$msg = "添加成功!代理所绑定的帐号已被禁止游戏和兑奖";
				break;
			case 1:
				$msg = "帐号已被其他代理绑定了";
				break;
			case 2:
				$msg = "帐号不存在";
				break;
			case 99:
				$msg = "系统错误";
				break;
			default:
				$msg = "未知错误";
				break;
		}
		$arrReturn[0]["cmd"] = "ok";
        $arrReturn[0]["msg"] = $msg;
        ArrayChangeEncode($arrReturn);
        echo json_encode($arrReturn);
	}
	/* 检测代理id
	*
	*/
	function CheckAgentUserID($act)
	{
		global $db;
		$UserID = intval($_POST['userid']);
		$arrReturn = array(array("cmd"=>"","msg"=>""));
		if($UserID == 0)
		{
			$arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "参数错误!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
		}
		$sql = "select 1 from agent where uid = '{$UserID}'";
		$result = $db->query($sql);
		if($rs = $db->fetch_array($result))
		{
			$arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "该用户已经被其他代理绑定了!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
		}
		$sql = "select id,nickname,mobile,`time` from users where id = '{$UserID}'";
		$result = $db->query($sql);
		if($rs = $db->fetch_array($result))
		{
			$arrReturn[0]["cmd"] = "ok";
            $arrReturn[0]["msg"] = "用户ID:" . $UserID . ",昵称:" . $rs["nickname"] . ",手机:" . $rs["mobile"] . ",注册时间:" . $rs["time"];
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
		}
		else
		{
			$arrReturn[0]["cmd"] = "err";
            $arrReturn[0]["msg"] = "用户不存在!";
            ArrayChangeEncode($arrReturn);
            echo json_encode($arrReturn);
            return;
		}
	}
	/* 取代理列表
	*
	*/
	function GetAgentList($act)
	{
		global $db;
        $UserID = isset($_POST['userid'])?FilterStr($_POST['userid']):"";
        $Order = isset($_POST['order'])?FilterStr($_POST['order']):"";
        $OrderType = isset($_POST['ordertype'])?FilterStr($_POST['ordertype']):"";
        
        $sqlCount = "select Count(*) ";
		$sqlCol = "SELECT a.*,b.nickname,(b.points+b.back+b.lock_points) as totalpoints
					";
		$sqlFrom = " FROM agent a
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
		$total_cash = 0.00;
		$total_dbmoney = 0.00;
		for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
		{   
			//对返回数据进行包装
            $arrRows[$i]["UserID"] = "<a href='index.php?url=".urlencode("admin_singleuser.php?idx={$row["uid"]}")."' target='_blank'>{$row["uid"]}</a>";
            $arrRows[$i]["AgentName"] = $row["agent_name"];
            $arrRows[$i]["AddTime"] = $row["add_time"];
            $arrRows[$i]["TotalPoints"] = Trans($row["totalpoints"]);
			$arrRows[$i]["DistributeMoney"] = Trans($row["distribute_money"]);
			$arrRows[$i]["BalanceMoney"] = Trans($row["cash"] - $row["distribute_money"]);
			$arrRows[$i]["LastLoginTime"] = $row["last_logintime"];
			$IPInfo = "<a href='http://www.ip138.com/ips.asp?ip={$row["last_loginip"]}' target='_blank'>". $row["last_loginip"] ."</a>";
			$IPInfo .= "&nbsp;<a href='index.php?url=".urlencode("admin_patchuser.php?type=loginip&word={$row["last_loginip"]}")."' target='_blank'>批</a>";
			
			$arrRows[$i]["LastLoginIP"] = $IPInfo;
			$arrRows[$i]["State"] = ($row["state"] == 0) ? "<font color='red'>已停用</font>&nbsp;<a onclick='ChangeAgentState({$row['id']},1)' title='开启使用' style='cursor:pointer'><font color='blue'>启用</a>" :
															"可用&nbsp;<a onclick='ChangeAgentState({$row['id']},0)' title='停止使用' style='cursor:pointer'><font color='blue'>停用</a>";
			$arrRows[$i]["Recommend"] = ($row["is_recommend"] == 0) ? "<font color='red'>未推荐</font>&nbsp;<a onclick='ChangeRecommend({$row['id']},1)' title='推荐' style='cursor:pointer'><font color='blue'>推荐</a>" :
															"已推荐&nbsp;<a onclick='ChangeRecommend({$row['id']},0)' title='撤销推荐' style='cursor:pointer'><font color='blue'>撤销</a>";
			$arrRows[$i]["Opr"] = "<a class='edi' href=\"agent_edituser.php?act=edit&id={$row['id']}\" >编辑</a> ";   //<a class='edi' href=\"agent_recharge.php?act=edit&id={$row['id']}\" >充值</a>
			$total_points += $row["points"];
			$total_cash += $row["cash"];
			$total_dbmoney += $row["distribute_money"];
		}
		
		if($RowCount > 0)
		{
			$i = $RowCount + 1;
			$arrRows[$i]["UserID"] = "";
            $arrRows[$i]["AgentName"] = "";
            $arrRows[$i]["AddTime"] = "页小计:";
            $arrRows[$i]["TotalPoints"] = Trans($total_points);
			$arrRows[$i]["DistributeMoney"] = Trans($total_dbmoney); 
			$arrRows[$i]["BalanceMoney"] = "";
			$arrRows[$i]["LastLoginTime"] = "";
			$arrRows[$i]["LastLoginIP"] = "";
			$arrRows[$i]["State"] = 
			$arrRows[$i]["Recommend"] = "";
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
	
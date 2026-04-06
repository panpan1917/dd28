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
	    	case "save_varconfig": //保存变量
	    		login_check( "system" );
	    		SaveVarConfig($act);
	    		break;
	    	case "get_varconfig": // 取变量
	    		GetVarConfig($act);
	    		break;
	        default:
	            exit;
	    }
	}
	
	/* 保存变量
	*
	*/
	function SaveVarConfig($act)
	{
		global $db;
		$CheckCard = isset($_POST['CheckCard'])?FilterStr($_POST['CheckCard']):"";
		$PrizePoints = isset($_POST['PrizePoints'])?FilterStr($_POST['PrizePoints']):"";
		$RegPoints = isset($_POST['RegPoints'])?FilterStr($_POST['RegPoints']):"";
		$LoginExp = isset($_POST['LoginExp'])?FilterStr($_POST['LoginExp']):"";
		$Authen = isset($_POST['Authen'])?FilterStr($_POST['Authen']):"";
		$LinkNum = isset($_POST['LinkNum'])?FilterStr($_POST['LinkNum']):""; 
		$LinkPoints = isset($_POST['LinkPoints'])?FilterStr($_POST['LinkPoints']):""; 
		$ExchangeNum = isset($_POST['ExchangeNum'])?FilterStr($_POST['ExchangeNum']):"";   
		$ExchangeNumVip = isset($_POST['ExchangeNumVip'])?FilterStr($_POST['ExchangeNumVip']):""; 
		$RobotBalance = isset($_POST['RobotBalance'])?FilterStr($_POST['RobotBalance']):"";
		$ExchangeMinExp = isset($_POST['ExchangeMinExp'])?FilterStr($_POST['ExchangeMinExp']):"";
		$SMTPServer = isset($_POST['SMTPServer'])?FilterStr($_POST['SMTPServer']):""; 
		$SMTPPort = isset($_POST['SMTPPort'])?FilterStr($_POST['SMTPPort']):""; 
		$SMTPUserMail = isset($_POST['SMTPUserMail'])?FilterStr($_POST['SMTPUserMail']):""; 
		$SMTPNickName = isset($_POST['SMTPNickName'])?FilterStr($_POST['SMTPNickName']):""; 
		$SMTPUser = isset($_POST['SMTPUser'])?FilterStr($_POST['SMTPUser']):""; 
		$SMTPPass = isset($_POST['SMTPPass'])?FilterStr($_POST['SMTPPass']):""; 
		$MialType = isset($_POST['MialType'])?FilterStr($_POST['MialType']):""; 
		
		$arrReturn = array(array());
		
		$SMTPNickName = ChangeEncodeU2G($SMTPNickName);
		
		$sql = "update web_config set web_ck_card = {$CheckCard},
					prizes_points = {$PrizePoints},
					reg_points = {$RegPoints},
					web_authentication = {$Authen},
					web_linknum = {$LinkNum},
					web_linkpoints = {$LinkPoints},
					web_loginperience = {$LoginExp},
					web_exchanged_num = {$ExchangeNum},
					web_exchanged_num_vip = {$ExchangeNumVip}, 
					robot_balance = {$RobotBalance},
					exchange_min_exp = {$ExchangeMinExp},
					smtp_server = '{$SMTPServer}',
					smtp_serverport = '{$SMTPPort}',
					smtp_username = '{$SMTPNickName}',
					smtp_usermail = '{$SMTPUserMail}',
					smtp_user = '{$SMTPUser}',
					smtp_pass = '{$SMTPPass}',
					smtp_mailtype = '{$MialType}'
		        where id = 1";
        $result = $db->query($sql);
        
		$msg = "操作成功";
		$arrReturn[0]["cmd"] = $act;
		$arrReturn[0]["msg"] = $msg;
		ArrayChangeEncode($arrReturn);
		echo json_encode($arrReturn);
	}
	/* 取变量
	*
	*/
	function GetVarConfig($act)
	{
		global $db;
		$ID = isset($_POST['id'])?FilterStr($_POST['id']):""; 
		$sql = "SELECT web_ck_card,prizes_points,reg_points,web_authentication,web_linknum,web_linkpoints,
						web_loginperience,web_exchanged_num,web_exchanged_num_vip,robot_balance,exchange_min_exp,
						smtp_server,smtp_serverport,smtp_username,smtp_usermail,smtp_user,smtp_pass,smtp_mailtype
				FROM web_config
				WHERE id = 1";
		$arrRows = array(array());
        $result = $db->query($sql);
        if($row=$db->fetch_array($result))
        {
			$arrRows[1]["CheckCard"] = $row["web_ck_card"];
			$arrRows[1]["PrizePoints"] = $row["prizes_points"];
			$arrRows[1]["RegPoints"] = $row["reg_points"];
			$arrRows[1]["LoginExp"] = $row["web_loginperience"];
			$arrRows[1]["Authen"] = $row["web_authentication"];
			$arrRows[1]["LinkNum"] = $row["web_linknum"];
			$arrRows[1]["LinkPoints"] = $row["web_linkpoints"];
			$arrRows[1]["ExchangeNum"] = $row["web_exchanged_num"];
			$arrRows[1]["ExchangeNumVip"] = $row["web_exchanged_num_vip"];
			$arrRows[1]["RobotBalance"] = $row["robot_balance"]; 
			$arrRows[1]["ExchangeMinExp"] = $row["exchange_min_exp"];
			$arrRows[1]["SMTPServer"] = $row["smtp_server"];
			$arrRows[1]["SMTPPort"] = $row["smtp_serverport"];
			$arrRows[1]["SMTPUserMail"] = $row["smtp_usermail"];
			$arrRows[1]["SMTPNickName"] = $row["smtp_username"];
			$arrRows[1]["SMTPUser"] = $row["smtp_user"];  
			$arrRows[1]["SMTPPass"] = $row["smtp_pass"];  
			$arrRows[1]["MialType"] = $row["smtp_mailtype"];  
        }
        $arrRows[0]["cmd"] = $act;
        $arrRows[0]["msg"] = "ok";
        ArrayChangeEncode($arrRows);
        echo json_encode($arrRows);
	} 
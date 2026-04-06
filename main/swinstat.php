<?php
	include_once("inc/conn.php");
    include_once("inc/function.php");
    
    if(!isset($_SESSION['usersid'])) {
		echo "您还没登录或者链接超时，请先去<a href='/login.php'>登录</a>!";
		exit;
	}
	
	
	$act = intval($_GET['act']);
	//返回游戏记录
	GetGameRecord($act);
	
    /* 返回游戏记录
    * 
    */
    function GetGameRecord($act)
    {
		$sid = intval($_GET['sid']);

		
		$RetContent = "<div class='Pattern'>\r\n";
		$RetContent .= "\t<div class='Content'>\r\n";
		//取得子菜单
		$RetContent .= GetSubMenu($act,$sid);
		
		//取号码表格
		$RetContent .= GetTableContent();
		
		$RetContent .= "\t</div>\r\n"; //content结束
		$RetContent .= "</div>\r\n";
		
		echo $RetContent;
		exit;
    }
    
    /* 取号码表格
    *
    */
    function GetTableContent()
    {
    	global $db;
    	
    	//$sql = "SELECT GROUP_CONCAT(game_type SEPARATOR ',') AS gametype,GROUP_CONCAT(game_name SEPARATOR ',') AS gamename
		//		FROM game_config where state=1
		//		ORDER BY game_name"; //TODO and id<=37
    	//$result = $db->query($sql);
    	//if($rs = $db->fetch_array($result)){
    	//$arrGameType = explode(",",$rs["gametype"]);
    	//$arrGameName = explode(",",ChangeEncodeG2U($rs["gamename"]));
    	//}
    	
    	$sql = "SELECT game_type,game_name FROM game_config where state=1 ORDER BY game_name"; //TODO and id<=43
    	$result = $db->query($sql);
		while($rs = $db->fetch_array($result))
		{
			$arrGameType[] = $rs["game_type"];
			$arrGameName[] = $rs["game_name"];
		}
		$arr7dayResult = array(array());
		$arr7dayWinOdds = array(array());
    	for($i = 0; $i < count($arrGameType); $i++)
    	{     
    		$arr7dayResult[$i]['name'] = $arrGameName[$i];
    		$arr7dayWinOdds[$i]['name'] = $arrGameName[$i]; 
    		for($j = 0; $j < 7; $j++)
    		{
				$datestr = date('Y-m-d',strtotime("-{$j} day"));
				$arr7dayResult[$i]["{$datestr}"] = 0;
				$arr7dayWinOdds[$i]["{$datestr}"] = "0";		
    		}
    		$arr7dayResult[$i]['total'] = 0;
    		$arr7dayWinOdds[$i]['totalcount'] = 0;
    		$arr7dayWinOdds[$i]['wincount'] = 0;
    		$tabletz = GetGameTableName($arrGameType[$i],"users_tz");
    		$sql = "SELECT DATE_FORMAT(`time`,'%Y-%m-%d') AS recDate,SUM((hdpoints - points)) AS winpoint,
    					COUNT(*) totalcnt,COUNT(IF(hdpoints-points >0,TRUE,NULL)) wincnt 
					FROM {$tabletz} 
					WHERE uid = '{$_SESSION['usersid']}' AND `time` > DATE_ADD(CURDATE(),INTERVAL -6 DAY)
					GROUP BY recDate";
			$result = $db->query($sql);
			while($rs = $db->fetch_array($result))
			{
				
				$arr7dayResult[$i]["{$rs['recDate']}"] = $rs['winpoint'];
				$arr7dayResult[$i]['total'] += $rs['winpoint'];
			
				$arr7dayWinOdds[$i]["{$rs['recDate']}"] = $rs['totalcnt'] . "期," . floor($rs['wincnt']/$rs['totalcnt'] * 100) . "%";
				$arr7dayWinOdds[$i]['totalcount'] += $rs['totalcnt'];
				$arr7dayWinOdds[$i]['wincount'] += $rs['wincnt']; 
				
			}
		}
		//WriteLog(print_r($arr7dayResult,true));
		$divTable = "<div class='table'>\r\n";
		$divTable .= "\t<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;color:#000000;'>\r\n";
		$divTable .= "\t<tbody>\r\n";
		$divTable .= "\t\t<tr>\r\n";
		$divTable .= "\t\t\t<th width='110'>游戏名称</th>\r\n";
		$divTable .= "\t\t\t<th width='110'>今天</th>\r\n";
		$divTable .= "\t\t\t<th width='110'>昨天</th>\r\n";
		$divTable .= "\t\t\t<th width='110'>前天</th>\r\n";
		$divTable .= "\t\t\t<th width='110'>". date('m-d',strtotime("-3 day")) ."</th>\r\n";
		$divTable .= "\t\t\t<th width='110'>". date('m-d',strtotime("-4 day")) ."</th>\r\n";
		$divTable .= "\t\t\t<th width='110'>". date('m-d',strtotime("-5 day")) ."</th>\r\n";
		$divTable .= "\t\t\t<th width='110'>". date('m-d',strtotime("-6 day")) ."</th>\r\n";
		$divTable .= "\t\t\t<th width='120'>总数</th>\r\n";
		$divTable .= "\t\t</tr>\r\n";
		//竖列统计
		$arrDaySum = array(0=>'总数',1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0);
		//数据
		for($i = 0; $i < count($arr7dayResult); $i++)
		{
			$divTable .= "\t\t<tr>\r\n";
			$j = 0;
			foreach($arr7dayResult[$i] as $v)
			{
				if($j == 0)
				    $divTable .= "\t\t\t<td ". ($v > 0 ? "style='background-color: #FFD8D9;'" : "") .">". $v ."</td>\r\n";
				else
					$divTable .= "\t\t\t<td ". ($v > 0 ? "style='background-color: #FFD8D9;'" : "") .">". Trans($v) ."</td>\r\n"; 
				if($j > 0)
					$arrDaySum[$j] += $v; 
				$j++;
			}
			$divTable .= "\t\t</tr>\r\n";
		}
		
		//写最后一行
		$divTable .= "\t\t<tr>\r\n";
		$j = 0;
		foreach($arrDaySum as $v)
		{
			if($j == 0)
				$divTable .= "\t\t\t<td ". ($v > 0 ? "style='background-color: #FFD8D9;'" : "") .">". $v ."</td>\r\n";
			else
				$divTable .= "\t\t\t<td ". ($v > 0 ? "style='background-color: #FFD8D9;'" : "") .">". Trans($v) ."</td>\r\n"; 
			$j++;  
		}
		$divTable .= "\t\t</tr>\r\n";
		
		$divTable .= "\t</tbody>\r\n";
		$divTable .= "\t</table>\r\n";
		
		//第二个表格
		$divTable .= "\t<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;color:#000000;'>\r\n";
		$divTable .= "\t<tbody>\r\n";
		$divTable .= "\t\t<tr>\r\n";
		$divTable .= "\t\t\t<th width='110'>游戏名称</th>\r\n";
		$divTable .= "\t\t\t<th width='110'>今天</th>\r\n";
		$divTable .= "\t\t\t<th width='110'>昨天</th>\r\n";
		$divTable .= "\t\t\t<th width='110'>前天</th>\r\n";
		$divTable .= "\t\t\t<th width='110'>". date('m-d',strtotime("-3 day")) ."</th>\r\n";
		$divTable .= "\t\t\t<th width='110'>". date('m-d',strtotime("-4 day")) ."</th>\r\n";
		$divTable .= "\t\t\t<th width='110'>". date('m-d',strtotime("-5 day")) ."</th>\r\n";
		$divTable .= "\t\t\t<th width='110'>". date('m-d',strtotime("-6 day")) ."</th>\r\n";
		$divTable .= "\t\t\t<th width='120'>汇总</th>\r\n"; 
		$divTable .= "\t\t</tr>\r\n";
		//数据
		for($i = 0; $i < count($arr7dayWinOdds); $i++)
		{
			$divTable .= "\t\t<tr>\r\n"; 
			foreach($arr7dayWinOdds[$i] as $k=>$v)
			{
				if($k == 'totalcount' || $k == 'wincount')
					continue;
				$divTable .= "\t\t\t<td>". $v ."</td>\r\n";
			}
			//汇总
			if($arr7dayWinOdds[$i]['totalcount'] > 0)
				$divTable .= "\t\t\t<td>共{$arr7dayWinOdds[$i]['totalcount']}期,". floor($arr7dayWinOdds[$i]['wincount']/$arr7dayWinOdds[$i]['totalcount'] * 100) . "%</td>\r\n";
			else 
				$divTable .= "\t\t\t<td>共{$arr7dayWinOdds[$i]['totalcount']}期,0%</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
		}
		
		$divTable .= "\t</tbody>\r\n";
		$divTable .= "\t</table>\r\n";
		
		$divTable .= "</div>\r\n";  
		return $divTable;
    }
    
    

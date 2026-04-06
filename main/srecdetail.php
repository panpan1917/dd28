<?php
	include_once("inc/conn.php");
    include_once("inc/function.php");
    
    if(!isset($_SESSION['usersid'])) {
		echo "您还没登录或者链接超时，请先去<a href='/login.php'>登录</a>!";
		exit;
	}
	
	
	$act = intval($_GET['act']);
	//返回游戏记录
	GetRecordDetail($act);
	
    /* 返回游戏记录
    * 
    */
    function GetRecordDetail($act)
    {
		$no = intval($_GET['no']);

		
		$RetContent = "<div class='popup'>\r\n";
		//header
		$RetContent .= "\t<div class='popup-header'>\r\n";
		$RetContent .= "\t\t\t<h2>第{$no}期中奖名单</h2>\r\n";
		$RetContent .= "\t\t\t<a href='javascript:;' onclick='closerecord({$no})' title='关闭' class='close-link'>[关闭]</a>\r\n";
		$RetContent .= "\t\t\t<br clear='both' /> \r\n";
		$RetContent .= "\t</div>";
		//body
		$RetContent .= "\t<div class='popup-body'>\r\n";
		
		if(in_array($act , [25,27,30,41])){
			$RetContent .= GetTableContent2($act,$no);
		}elseif(in_array($act , [26,28,31,42])){
			$RetContent .= GetTableContent3($act,$no);
		}elseif(in_array($act , [29])){
			$RetContent .= GetTableContentPKSC($act,$no);
		}elseif(in_array($act , [36])){
			$RetContent .= GetTableContentXYNC($act,$no);
		}elseif(in_array($act , [37])){
			$RetContent .= GetTableContentSSC($act,$no);
		}elseif(in_array($act , [32,33,34,35])){
			$RetContent .= GetTableContentGD28($act,$no);
		}else{
			$RetContent .= GetTableContent($act,$no);
		}
		$RetContent .= "\t</div>";
		
		echo $RetContent;
		exit;
    }
    
    /* 取号码表格
    *
    */
    function GetTableContent($act,$No)
    {
    	global $db;
    	$tabletz = GetGameTableName($act,'users_tz');
    	$tablegame = GetGameTableName($act,"game");
    	if($tabletz == "")
    		return "提交参数错误！";
    	//取押注情况
    	$sql = "SELECT tznum,tzpoints,points,hdpoints,zjpoints,zjpl FROM {$tabletz} WHERE uid = '{$_SESSION['usersid']}' AND NO = '{$No}'";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
			$arrtznum = explode("|",$rs['tznum']);
			$arrtzpoints = explode("|",$rs["tzpoints"]);
			$arrzjpoints = explode("|",$rs['zjpoints']);
			$hdpoints = $rs['hdpoints'];
			$points = $rs['points'];
			$zjpl=$rs['zjpl'];
			
    	}
    	else
    	{
			return "<p>很抱歉,无投注记录！</p>";
    	}
    	//取标准赔率
    	$reward_num_type = GetGameOddsType($act);
    		 
    	$sql = "SELECT GROUP_CONCAT(num SEPARATOR '|') AS strnum,GROUP_CONCAT(odds SEPARATOR '|') AS strodds FROM gameodds WHERE game_type = '{$reward_num_type}' ORDER BY num";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
    		$arrStdNums = explode("|",$rs['strnum']);
			$arrStdOdds = explode("|",$rs['strodds']);
    	}
    	else
    	{
			return "无法取得标准赔率";
    	}
    	//重新格式化押注情况
    	$arrNewtz = array();
    	$arrNewhdPoints = array();
    	foreach($arrStdNums as $num)
    	{
			$arrNewtz[$num] = 0;
			$arrNewhdPoints[$num] = 0;
    	}
    	for($i = 0; $i < count($arrtznum); $i++)
    	{
			$arrNewtz[$arrtznum[$i]] = $arrtzpoints[$i];
			$arrNewhdPoints[$arrtznum[$i]] = $arrzjpoints[$i];
    	}
    	//取开奖赔率
    	$sql = "SELECT zjpl,kgtime FROM {$tablegame} WHERE id = {$No}";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
			$arrRwardOdds = explode("|",$rs['zjpl']);
			$kgtime = $rs['kgtime'];
    	}
    	else
    	{
			return "无法取得开奖赔率";
    	}
    	//填表
		$divTable ="<div class='table'>";
		$divTable .= "\t<table class='table_list table table-hover table-striped table-bordered' >\r\n";
		$divTable .= "\t<tbody>\r\n";
		$divTable .= "\t\t<tr><td colspan='5'>第{$No}期投注详细结果</td></tr>\r\n";
		$divTable .= "\t\t<tr><td colspan='5' class='please'>开奖时间:{$kgtime} 投注:<span>". Trans($points) ."</span> 获得:<span>". Trans($hdpoints) ."</span></td></tr>\r\n";
		
		$divTable .= "\t\t<tr>\r\n";
		$divTable .= "\t\t\t<th width='80'>号码</th>\r\n";
		$divTable .= "\t\t\t<th width='100'>当前赔率</th>\r\n";
		$divTable .= "\t\t\t<th width='100'>开奖赔率</th>\r\n";
		$divTable .= "\t\t\t<th width='160'>投注数量</th>\r\n";
		$divTable .= "\t\t\t<th width='160'>获得数量</th>\r\n";
		$divTable .= "\t\t</tr>\r\n";
		for($i = 0; $i < count($arrStdNums);$i++)
    	{
			$divTable .= "\t\t<tr>\r\n";
			if(in_array($act,array(16,47))) //pk龙虎 飞艇龙虎
			{
				if($arrStdNums[$i] == 1)
					$divTable .= "\t\t\t<td><li class='finalbig'>龙</li></td>\r\n";
				else
					$divTable .= "\t\t\t<td><li class='finalbig'>虎</li></td>\r\n";
			}
			else if(in_array($act,array(11,12,13,21,23)))
			{
				$NumberNameStr = "";
				switch($arrStdNums[$i])
				{
					case 1:
						$NumberNameStr = "豹";
						break;
					case 2:
						$NumberNameStr = "对";
						break;
					case 3:
						$NumberNameStr = "顺";
						break;
					case 4:
						$NumberNameStr = "半";
						break;
					case 5:
						$NumberNameStr = "杂";
						break;
					default:
						break;
				}
				$divTable .= "\t\t\t<td><li class='finalbig'>{$NumberNameStr}</li></td>\r\n";
			}
			else
			{
				$divTable .= "\t\t\t<td><li class='finalbig'>{$arrStdNums[$i]}</li></td>\r\n";
			}
			
			
			if($arrNewhdPoints[$arrStdNums[$i]]>0 && doubleval($zjpl)>0) {
				$divTable .= "\t\t\t<td>".sprintf('%.4f',$zjpl+$zjpl*.005)."</td>\r\n";
				$divTable .= "\t\t\t<td>".sprintf('%.4f',$zjpl)."</td>\r\n";
			}else{
				$divTable .= "\t\t\t<td>".sprintf('%.4f',$arrRwardOdds[$i]+$arrRwardOdds[$i]*.005)."</td>\r\n";
				$divTable .= "\t\t\t<td>{$arrRwardOdds[$i]}</td>\r\n";
				
			}
			$divTable .= "\t\t\t<td class='please'>". Trans($arrNewtz[$arrStdNums[$i]]) ."</td>\r\n";
			$divTable .= "\t\t\t<td class='please'>". Trans($arrNewhdPoints[$arrStdNums[$i]]) ."</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
    	}
		$divTable .= "\t</tbody>\r\n";
		$divTable .= "\t</table>\r\n";
		$divTable .= "</div>";
		return $divTable;
    }
    
    
    /* 取号码表格
     *
    */
    function GetTableContentGD28($act,$No)
    {
    	global $db;
    	$tabletz = GetGameTableName($act,'users_tz');
    	$tablegame = GetGameTableName($act,"game");
    	if($tabletz == "")
    		return "提交参数错误！";
    	//取押注情况
    	$sql = "SELECT tznum,tzpoints,points,hdpoints,zjpoints,zjpl FROM {$tabletz} WHERE uid = '{$_SESSION['usersid']}' AND NO = '{$No}'";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
    		$arrtznum = explode("|",$rs['tznum']);
    		$arrtzpoints = explode("|",$rs["tzpoints"]);
    		$arrzjpoints = explode("|",$rs['zjpoints']);
    		$hdpoints = $rs['hdpoints'];
    		$points = $rs['points'];
    		$zjpl=$rs['zjpl'];
    			
    	}
    	else
    	{
    		return "<p>很抱歉,无投注记录！</p>";
    	}
    	//取标准赔率
    	$reward_num_type = GetGameOddsType($act);
    	 
    	$sql = "SELECT GROUP_CONCAT(num SEPARATOR '|') AS strnum,GROUP_CONCAT(odds SEPARATOR '|') AS strodds FROM gameodds WHERE game_type = '{$reward_num_type}' ORDER BY num";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
    		$arrStdNums = explode("|",$rs['strnum']);
    		$arrStdOdds = explode("|",$rs['strodds']);
    	}
    	else
    	{
    		return "无法取得标准赔率";
    	}
    	//重新格式化押注情况
    	$arrNewtz = array();
    	$arrNewhdPoints = array();
    	foreach($arrStdNums as $num)
    	{
    		$arrNewtz[$num] = 0;
    		$arrNewhdPoints[$num] = 0;
    	}
    	for($i = 0; $i < count($arrtznum); $i++)
    	{
    		$arrNewtz[$arrtznum[$i]] = $arrtzpoints[$i];
    		$arrNewhdPoints[$arrtznum[$i]] = $arrzjpoints[$i];
    	}
    	//取开奖赔率
    	$sql = "SELECT zjpl,kgtime FROM {$tablegame} WHERE id = {$No}";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
    		$arrRwardOdds = explode("|",$rs['zjpl']);
    		$kgtime = $rs['kgtime'];
    	}
    	else
    	{
    		return "无法取得开奖赔率";
    	}
    	//填表
    	$divTable ="<div class='table'>";
    	$divTable .= "\t<table class='table_list table table-hover table-striped table-bordered' >\r\n";
		$divTable .= "\t<tbody>\r\n";
		$divTable .= "\t\t<tr><td colspan='5'>第{$No}期投注详细结果</td></tr>\r\n";
    	$divTable .= "\t\t<tr><td colspan='5' class='please'>开奖时间:{$kgtime} 投注:<span>". Trans($points) ."</span> 获得:<span>". Trans($hdpoints) ."</span></td></tr>\r\n";
    
		$divTable .= "\t\t<tr>\r\n";
		$divTable .= "\t\t\t<th width='80'>号码</th>\r\n";
    	$divTable .= "\t\t\t<th width='100'>赔率</th>\r\n";
    	$divTable .= "\t\t\t<th width='160'>投注数量</th>\r\n";
    	$divTable .= "\t\t\t<th width='160'>获得数量</th>\r\n";
    	$divTable .= "\t\t</tr>\r\n";
		for($i = 0; $i < count($arrStdNums);$i++)
    	{
    		$divTable .= "\t\t<tr>\r\n";
    		$divTable .= "\t\t\t<td><li class='finalbig'>{$arrStdNums[$i]}</li></td>\r\n";
    		$divTable .= "\t\t\t<td>".sprintf('%.4f',$arrRwardOdds[$i])."</td>\r\n";   		
    		$divTable .= "\t\t\t<td class='please'>". Trans($arrNewtz[$arrStdNums[$i]]) ."</td>\r\n";
    		$divTable .= "\t\t\t<td class='please'>". Trans($arrNewhdPoints[$arrStdNums[$i]]) ."</td>\r\n";
    		$divTable .= "\t\n</tr>\r\n";
    	}
	    $divTable .= "\t</tbody>\r\n";
	    $divTable .= "\t</table>\r\n";
	    $divTable .= "</div>";
	    return $divTable;
    }
    
    
    function GetTableContent2($act,$No)
    {
    	global $db;
    	$tabletz = GetGameTableName($act,'users_tz');
    	$tablegame = GetGameTableName($act,"game");
    	if($tabletz == "")
    		return "提交参数错误！";
    	//取押注情况
    	$sql = "SELECT tznum,tzpoints,points,hdpoints,zjpoints,zjpl FROM {$tabletz} WHERE uid = '{$_SESSION['usersid']}' AND NO = '{$No}'";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
    		$arrtznum = explode("|",$rs['tznum']);
    		$arrtzpoints = explode("|",$rs["tzpoints"]);
    		$arrzjpoints = explode("|",$rs['zjpoints']);
    		$hdpoints = $rs['hdpoints'];
    		$points = $rs['points'];
    		$zjpl=$rs['zjpl'];
    			
    	}
    	else
    	{
    		return "<p>很抱歉,无投注记录！</p>";
    	}
    	
    	
    	//取开奖时间,赔率
    	$sql = "SELECT zjpl,kgtime FROM {$tablegame} WHERE id = {$No}";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
    		$arrStdOdds = explode("|",$rs['zjpl']);
    		$kgtime = $rs['kgtime'];
    	}
    	else
    	{
    		return "无法取得开奖时间";
    	}
    	
	    	//填表
	    	$divTable ="<div class='table'>";
	    	$divTable .= "\t<table class='table_list table table-hover table-striped table-bordered' >\r\n";
			$divTable .= "\t<tbody>\r\n";
			$divTable .= "\t\t<tr><td colspan='5'>第{$No}期投注详细结果</td></tr>\r\n";
	    	$divTable .= "\t\t<tr><td colspan='5' class='please'>开奖时间:{$kgtime} 投注:<span>". Trans($points) ."</span> 获得:<span>". Trans($hdpoints) ."</span></td></tr>\r\n";
	    
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<th width='80'>号码</th>\r\n";
    		$divTable .= "\t\t\t<th width='100'>赔率</th>\r\n";
    		$divTable .= "\t\t\t<th width='160'>投注数量</th>\r\n";
    		$divTable .= "\t\t\t<th width='160'>获得数量</th>\r\n";
    		$divTable .= "\t\t</tr>\r\n";
			for($i = 0; $i < count($arrzjpoints);$i++)
	    	{
    				$divTable .= "\t\t<tr>\r\n";
    				$divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_{$arrtznum[$i]}\"></span></td>\r\n";
    				$divTable .= "\t\t\t<td>".sprintf('%.4f',$arrStdOdds[$arrtznum[$i]])."</td>\r\n";
    				$divTable .= "\t\t\t<td class='please'>". Trans($arrtzpoints[$i]) ."</td>\r\n";
    				$divTable .= "\t\t\t<td class='please'>". Trans($arrzjpoints[$i]) ."</td>\r\n";
    				$divTable .= "\t\n</tr>\r\n";
	    	}
		    $divTable .= "\t</tbody>\r\n";
		    $divTable .= "\t</table>\r\n";
		    $divTable .= "</div>";
		    return $divTable;
    }
    
    
    function GetTableContent3($act,$No)
    {
    	global $db;
    	$tabletz = GetGameTableName($act,'users_tz');
    	$tablegame = GetGameTableName($act,"game");
    	if($tabletz == "")
    		return "提交参数错误！";
    	//取押注情况
    	$sql = "SELECT tznum,tzpoints,points,hdpoints,zjpoints,zjpl FROM {$tabletz} WHERE uid = '{$_SESSION['usersid']}' AND NO = '{$No}'";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
    		$arrtznum = explode("|",$rs['tznum']);
    		$arrtzpoints = explode("|",$rs["tzpoints"]);
    		$arrzjpoints = explode("|",$rs['zjpoints']);
    		$hdpoints = $rs['hdpoints'];
    		$points = $rs['points'];
    		$zjpl=$rs['zjpl'];
    		 
    	}
    	else
    	{
    		return "<p>很抱歉,无投注记录！</p>";
    	}

    	//取开奖时间,赔率
    	$sql = "SELECT zjpl,kgtime FROM {$tablegame} WHERE id = {$No}";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
    		$arrStdOdds = explode("|",$rs['zjpl']);
    		$kgtime = $rs['kgtime'];
    	}
    	else
    	{
    		return "无法取得开奖时间";
    	}
    	 
    	//填表
    	$divTable ="<div class='table'>";
    	$divTable .= "\t<table class='table_list table table-hover table-striped table-bordered' >\r\n";
    	$divTable .= "\t<tbody>\r\n";
    	$divTable .= "\t\t<tr><td colspan='5'>第{$No}期投注详细结果</td></tr>\r\n";
    	$divTable .= "\t\t<tr><td colspan='5' class='please'>开奖时间:{$kgtime} 投注:<span>". Trans($points) ."</span> 获得:<span>". Trans($hdpoints) ."</span></td></tr>\r\n";
    	 
    	$divTable .= "\t\t<tr>\r\n";
    	$divTable .= "\t\t\t<th width='80'>号码</th>\r\n";
    	$divTable .= "\t\t\t<th width='100'>赔率</th>\r\n";
    	$divTable .= "\t\t\t<th width='160'>投注数量</th>\r\n";
    	$divTable .= "\t\t\t<th width='160'>获得数量</th>\r\n";
    	$divTable .= "\t\t</tr>\r\n";
    	for($i = 0; $i < count($arrzjpoints);$i++)
    	{
    		$divTable .= "\t\t<tr>\r\n";
    		if($arrtznum[$i] <= 11){
		    	$divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_{$arrtznum[$i]}\"></span></td>\r\n";
    		}
    		if($arrtznum[$i] == 12){
    			$divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_13\"></span></td>\r\n";
    		}
    		
    		if($arrtznum[$i] >= 13 && $arrtznum[$i] <= 26){
    			$j = $arrtznum[$i] -17;
    			if($arrtznum[$i] == 13)
    				$divTable .= "\t\t\t<td class=\"ds\">一号<span class=\"ds_1\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 14)
    				$divTable .= "\t\t\t<td class=\"ds\">一号<span class=\"ds_6\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 15)
    				$divTable .= "\t\t\t<td class=\"ds\">一号<span class=\"ds_0\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 16)
    				$divTable .= "\t\t\t<td class=\"ds\">一号<span class=\"ds_5\"></span></td>\r\n";
    			else 
    				$divTable .= "\t\t\t<td class=\"ds\">一号<i class=\"mh m{$j}\"></i></td>\r\n";
    		}
    		
    		
    		if($arrtznum[$i] >= 27 && $arrtznum[$i] <= 40){
    			$j = $arrtznum[$i] -31;
    			if($arrtznum[$i] == 27)
    				$divTable .= "\t\t\t<td class=\"ds\">二号<span class=\"ds_1\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 28)
    				$divTable .= "\t\t\t<td class=\"ds\">二号<span class=\"ds_6\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 29)
    				$divTable .= "\t\t\t<td class=\"ds\">二号<span class=\"ds_0\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 30)
    				$divTable .= "\t\t\t<td class=\"ds\">二号<span class=\"ds_5\"></span></td>\r\n";
    			else
    				$divTable .= "\t\t\t<td class=\"ds\">二号<i class=\"mh m{$j}\"></i></td>\r\n";
    		}
    		
    		if($arrtznum[$i] >= 41 && $arrtznum[$i] <= 54){
    			$j = $arrtznum[$i] -45;
    			if($arrtznum[$i] == 41)
    				$divTable .= "\t\t\t<td class=\"ds\">三号<span class=\"ds_1\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 42)
    				$divTable .= "\t\t\t<td class=\"ds\">三号<span class=\"ds_6\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 43)
    				$divTable .= "\t\t\t<td class=\"ds\">三号<span class=\"ds_0\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 44)
    				$divTable .= "\t\t\t<td class=\"ds\">三号<span class=\"ds_5\"></span></td>\r\n";
    			else
    				$divTable .= "\t\t\t<td class=\"ds\">三号<i class=\"mh m{$j}\"></i></td>\r\n";
    		}
    		
    		$divTable .= "\t\t\t<td>".sprintf('%.4f',$arrStdOdds[$arrtznum[$i]])."</td>\r\n";
    		$divTable .= "\t\t\t<td class='please'>". Trans($arrtzpoints[$i]) ."</td>\r\n";
    		$divTable .= "\t\t\t<td class='please'>". Trans($arrzjpoints[$i]) ."</td>\r\n";
    		$divTable .= "\t\n</tr>\r\n";
    	}
		$divTable .= "\t</tbody>\r\n";
		$divTable .= "\t</table>\r\n";
    	$divTable .= "</div>";
    	return $divTable;
    }  
    
    
    function GetTableContentPKSC($act,$No)
    {
    	global $db;
    	$tabletz = GetGameTableName($act,'users_tz');
    	$tablegame = GetGameTableName($act,"game");
    	if($tabletz == "")
    		return "提交参数错误！";
    	//取押注情况
    	$sql = "SELECT tznum,tzpoints,points,hdpoints,zjpoints,zjpl FROM {$tabletz} WHERE uid = '{$_SESSION['usersid']}' AND NO = '{$No}'";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
    		$arrtznum = explode("|",$rs['tznum']);
    		$arrtzpoints = explode("|",$rs["tzpoints"]);
    		$arrzjpoints = explode("|",$rs['zjpoints']);
    		$hdpoints = $rs['hdpoints'];
    		$points = $rs['points'];
    		$zjpl=$rs['zjpl'];
    		 
    	}
    	else
    	{
    		return "<p>很抱歉,无投注记录！</p>";
    	}

    	//取开奖时间,赔率
    	$sql = "SELECT zjpl,kgtime FROM {$tablegame} WHERE id = {$No}";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
    		$arrStdOdds = explode("|",$rs['zjpl']);
    		$kgtime = $rs['kgtime'];
    	}
    	else
    	{
    		return "无法取得开奖时间";
    	}
    	
    	
    	//填表
    	$divTable ="<div class='table'>";
    	$divTable .= "\t<table class='table_list table table-hover table-striped table-bordered' >\r\n";
    	$divTable .= "\t<tbody>\r\n";
    	$divTable .= "\t\t<tr><td colspan='5'>第{$No}期投注详细结果</td></tr>\r\n";
    	$divTable .= "\t\t<tr><td colspan='5' class='please'>开奖时间:{$kgtime} 投注:<span>". Trans($points) ."</span> 获得:<span>". Trans($hdpoints) ."</span></td></tr>\r\n";
    	 
    	$divTable .= "\t\t<tr>\r\n";
    	$divTable .= "\t\t\t<th width='80'>号码</th>\r\n";
    	$divTable .= "\t\t\t<th width='100'>赔率</th>\r\n";
    	$divTable .= "\t\t\t<th width='160'>投注数量</th>\r\n";
    	$divTable .= "\t\t\t<th width='160'>获得数量</th>\r\n";
    	$divTable .= "\t\t</tr>\r\n";
    	for($i = 0; $i < count($arrzjpoints);$i++)
    	{
    		$divTable .= "\t\t<tr>\r\n";
    	    if($arrtznum[$i] >= 0 && $arrtznum[$i] <= 16){
    			$j = $arrtznum[$i] + 3;
    			$divTable .= "\t\t\t<td class=\"ds\"><i class=\"mh m{$j}\"></i></td>\r\n";
    		}
    		
    		if($arrtznum[$i] >= 17 && $arrtznum[$i] <= 20){
    			if($arrtznum[$i] == 17)
    				$divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_1\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 18)
    				$divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_6\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 19)
    				$divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_0\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 20)
    				$divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_5\"></span></td>\r\n";
    		}
    		
    		if($arrtznum[$i] >= 21 && $arrtznum[$i] <= 24){
    			if($arrtznum[$i] == 21)
    				$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_1\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 22)
    				$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_6\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 23)
    				$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_0\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 24)
    				$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_5\"></span></td>\r\n";
    		}
    		if($arrtznum[$i] >= 25 && $arrtznum[$i] <= 34){
    			$j = $arrtznum[$i] - 24;
    			$divTable .= "\t\t\t<td class=\"ds\">1号<i class=\"mh m{$j}\"></i></td>\r\n";
    		}
    		
    		if($arrtznum[$i] >= 35 && $arrtznum[$i] <= 38){
    			if($arrtznum[$i] == 35)
    				$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_1\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 36)
    				$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_6\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 37)
    				$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_0\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 38)
    				$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_5\"></span></td>\r\n";
    		}
    		if($arrtznum[$i] >= 39 && $arrtznum[$i] <= 48){
    			$j = $arrtznum[$i] - 38;
    			$divTable .= "\t\t\t<td class=\"ds\">2号<i class=\"mh m{$j}\"></i></td>\r\n";
    		}
    		
    		if($arrtznum[$i] >= 49 && $arrtznum[$i] <= 52){
    			if($arrtznum[$i] == 49)
    				$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_1\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 50)
    				$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_6\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 51)
    				$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_0\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 52)
    				$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_5\"></span></td>\r\n";
    		}
    		if($arrtznum[$i] >= 53 && $arrtznum[$i] <= 62){
    			$j = $arrtznum[$i] - 52;
    			$divTable .= "\t\t\t<td class=\"ds\">3号<i class=\"mh m{$j}\"></i></td>\r\n";
    		}
    		
    		if($arrtznum[$i] >= 63 && $arrtznum[$i] <= 66){
    			if($arrtznum[$i] == 63)
    				$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_1\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 64)
    				$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_6\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 65)
    				$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_0\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 66)
    				$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_5\"></span></td>\r\n";
    		}
    		if($arrtznum[$i] >= 67 && $arrtznum[$i] <= 76){
    			$j = $arrtznum[$i] - 66;
    			$divTable .= "\t\t\t<td class=\"ds\">4号<i class=\"mh m{$j}\"></i></td>\r\n";
    		}
    		
    		if($arrtznum[$i] >= 77 && $arrtznum[$i] <= 80){
    			if($arrtznum[$i] == 77)
    				$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_1\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 78)
    				$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_6\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 79)
    				$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_0\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 80)
    				$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_5\"></span></td>\r\n";
    		}
    		if($arrtznum[$i] >= 81 && $arrtznum[$i] <= 90){
    			$j = $arrtznum[$i] - 80;
    			$divTable .= "\t\t\t<td class=\"ds\">5号<i class=\"mh m{$j}\"></i></td>\r\n";
    		}

    		if($arrtznum[$i] >= 91 && $arrtznum[$i] <= 94){
    			if($arrtznum[$i] == 91)
    				$divTable .= "\t\t\t<td class=\"ds\">6号<span class=\"ds_1\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 92)
    				$divTable .= "\t\t\t<td class=\"ds\">6号<span class=\"ds_6\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 93)
    				$divTable .= "\t\t\t<td class=\"ds\">6号<span class=\"ds_0\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 94)
    				$divTable .= "\t\t\t<td class=\"ds\">6号<span class=\"ds_5\"></span></td>\r\n";
    		}
    		if($arrtznum[$i] >= 95 && $arrtznum[$i] <= 104){
    			$j = $arrtznum[$i] - 94;
    			$divTable .= "\t\t\t<td class=\"ds\">6号<i class=\"mh m{$j}\"></i></td>\r\n";
    		}

    		if($arrtznum[$i] >= 105 && $arrtznum[$i] <= 108){
    			if($arrtznum[$i] == 105)
    				$divTable .= "\t\t\t<td class=\"ds\">7号<span class=\"ds_1\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 106)
    				$divTable .= "\t\t\t<td class=\"ds\">7号<span class=\"ds_6\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 107)
    				$divTable .= "\t\t\t<td class=\"ds\">7号<span class=\"ds_0\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 108)
    				$divTable .= "\t\t\t<td class=\"ds\">7号<span class=\"ds_5\"></span></td>\r\n";
    		}
    		if($arrtznum[$i] >= 109 && $arrtznum[$i] <= 118){
    			$j = $arrtznum[$i] - 108;
    			$divTable .= "\t\t\t<td class=\"ds\">7号<i class=\"mh m{$j}\"></i></td>\r\n";
    		}    		
    		
    		if($arrtznum[$i] >= 119 && $arrtznum[$i] <= 122){
    			if($arrtznum[$i] == 119)
    				$divTable .= "\t\t\t<td class=\"ds\">8号<span class=\"ds_1\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 120)
    				$divTable .= "\t\t\t<td class=\"ds\">8号<span class=\"ds_6\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 121)
    				$divTable .= "\t\t\t<td class=\"ds\">8号<span class=\"ds_0\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 122)
    				$divTable .= "\t\t\t<td class=\"ds\">8号<span class=\"ds_5\"></span></td>\r\n";
    		}
    		if($arrtznum[$i] >= 123 && $arrtznum[$i] <= 132){
    			$j = $arrtznum[$i] - 122;
    			$divTable .= "\t\t\t<td class=\"ds\">8号<i class=\"mh m{$j}\"></i></td>\r\n";
    		}	
    		
    		if($arrtznum[$i] >= 133 && $arrtznum[$i] <= 136){
    			if($arrtznum[$i] == 133)
    				$divTable .= "\t\t\t<td class=\"ds\">9号<span class=\"ds_1\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 134)
    				$divTable .= "\t\t\t<td class=\"ds\">9号<span class=\"ds_6\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 135)
    				$divTable .= "\t\t\t<td class=\"ds\">9号<span class=\"ds_0\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 136)
    				$divTable .= "\t\t\t<td class=\"ds\">9号<span class=\"ds_5\"></span></td>\r\n";
    		}
    		if($arrtznum[$i] >= 137 && $arrtznum[$i] <= 146){
    			$j = $arrtznum[$i] - 136;
    			$divTable .= "\t\t\t<td class=\"ds\">9号<i class=\"mh m{$j}\"></i></td>\r\n";
    		}
    		
    		if($arrtznum[$i] >= 147 && $arrtznum[$i] <= 150){
    			if($arrtznum[$i] == 147)
    				$divTable .= "\t\t\t<td class=\"ds\">10号<span class=\"ds_1\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 148)
    				$divTable .= "\t\t\t<td class=\"ds\">10号<span class=\"ds_6\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 149)
    				$divTable .= "\t\t\t<td class=\"ds\">10号<span class=\"ds_0\"></span></td>\r\n";
    			elseif($arrtznum[$i] == 150)
    				$divTable .= "\t\t\t<td class=\"ds\">10号<span class=\"ds_5\"></span></td>\r\n";
    		}
    		if($arrtznum[$i] >= 151 && $arrtznum[$i] <= 160){
    			$j = $arrtznum[$i] - 150;
    			$divTable .= "\t\t\t<td class=\"ds\">10号<i class=\"mh m{$j}\"></i></td>\r\n";
    		}
    		
    		
    		if($arrtznum[$i] == 161){
    			$divTable .= "\t\t\t<td class=\"sc\">1V10<span class=\"sc lh_0\">0</span></td>\r\n";
    		}
    		if($arrtznum[$i] == 162){
    			$divTable .= "\t\t\t<td class=\"sc\">1V10<span class=\"sc lh_1\">1</span></td>\r\n";
    		}
    		
    		if($arrtznum[$i] == 163){
    			$divTable .= "\t\t\t<td class=\"sc\">2V9<span class=\"sc lh_0\">0</span></td>\r\n";
    		}
    		if($arrtznum[$i] == 164){
    			$divTable .= "\t\t\t<td class=\"sc\">2V9<span class=\"sc lh_1\">1</span></td>\r\n";
    		}
    		
    		if($arrtznum[$i] == 165){
    			$divTable .= "\t\t\t<td class=\"sc\">3V8<span class=\"sc lh_0\">0</span></td>\r\n";
    		}
    		if($arrtznum[$i] == 166){
    			$divTable .= "\t\t\t<td class=\"sc\">3V8<span class=\"sc lh_1\">1</span></td>\r\n";
    		}
    		
    		if($arrtznum[$i] == 167){
    			$divTable .= "\t\t\t<td class=\"sc\">4V7<span class=\"sc lh_0\">0</span></td>\r\n";
    		}
    		if($arrtznum[$i] == 168){
    			$divTable .= "\t\t\t<td class=\"sc\">4V7<span class=\"sc lh_1\">1</span></td>\r\n";
    		}
    		
    		if($arrtznum[$i] == 169){
    			$divTable .= "\t\t\t<td class=\"sc\">5V6<span class=\"sc lh_0\">0</span></td>\r\n";
    		}
    		if($arrtznum[$i] == 170){
    			$divTable .= "\t\t\t<td class=\"sc\">5V6<span class=\"sc lh_1\">1</span></td>\r\n";
    		}
    		
    		
    		$divTable .= "\t\t\t<td>".sprintf('%.4f',$arrStdOdds[$arrtznum[$i]])."</td>\r\n";
    		$divTable .= "\t\t\t<td class='please'>". Trans($arrtzpoints[$i]) ."</td>\r\n";
    		$divTable .= "\t\t\t<td class='please'>". Trans($arrzjpoints[$i]) ."</td>\r\n";
    		$divTable .= "\t\n</tr>\r\n";
    	}
		$divTable .= "\t</tbody>\r\n";
		$divTable .= "\t</table>\r\n";
    	$divTable .= "</div>";
    	return $divTable;
    }
    
    
    
    function GetTableContentXYNC($act,$No)
    {
    	global $db;
    	$tabletz = GetGameTableName($act,'users_tz');
    	$tablegame = GetGameTableName($act,"game");
    	if($tabletz == "")
    		return "提交参数错误！";
    	//取押注情况
    	$sql = "SELECT tznum,tzpoints,points,hdpoints,zjpoints,zjpl FROM {$tabletz} WHERE uid = '{$_SESSION['usersid']}' AND NO = '{$No}'";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
    		$arrtznum = explode("|",$rs['tznum']);
    		$arrtzpoints = explode("|",$rs["tzpoints"]);
    		$arrzjpoints = explode("|",$rs['zjpoints']);
    		$hdpoints = $rs['hdpoints'];
    		$points = $rs['points'];
    		$zjpl=$rs['zjpl'];
    		 
    	}
    	else
    	{
    		return "<p>很抱歉,无投注记录！</p>";
    	}
    
    	//取开奖时间,赔率
    	$sql = "SELECT zjpl,kgtime FROM {$tablegame} WHERE id = {$No}";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
    		$arrStdOdds = explode("|",$rs['zjpl']);
    		$kgtime = $rs['kgtime'];
    	}
    	else
    	{
    		return "无法取得开奖时间";
    	}
    	 
    	 
    	//填表
    	$divTable ="<div class='table'>";
    	$divTable .= "\t<table class='table_list table table-hover table-striped table-bordered' >\r\n";
    	$divTable .= "\t<tbody>\r\n";
    	$divTable .= "\t\t<tr><td colspan='5'>第{$No}期投注详细结果</td></tr>\r\n";
    	$divTable .= "\t\t<tr><td colspan='5' class='please'>开奖时间:{$kgtime} 投注:<span>". Trans($points) ."</span> 获得:<span>". Trans($hdpoints) ."</span></td></tr>\r\n";
    
    	$divTable .= "\t\t<tr>\r\n";
    	$divTable .= "\t\t\t<th width='80'>号码</th>\r\n";
    	$divTable .= "\t\t\t<th width='100'>赔率</th>\r\n";
    	$divTable .= "\t\t\t<th width='160'>投注数量</th>\r\n";
    	$divTable .= "\t\t\t<th width='160'>获得数量</th>\r\n";
    	$divTable .= "\t\t</tr>\r\n";
    	for($i = 0; $i < count($arrzjpoints);$i++)
    	{
    		$divTable .= "\t\t<tr>\r\n";
    		if($arrtznum[$i] >= -1 && $arrtznum[$i] <= 5){
    			if($arrtznum[$i] == 0) $divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_1\"></span></td>\r\n";
    			if($arrtznum[$i] == 1) $divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_6\"></span></td>\r\n";
    			if($arrtznum[$i] == 2) $divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_0\"></span></td>\r\n";
    			if($arrtznum[$i] == 3) $divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_5\"></span></td>\r\n";
    			if($arrtznum[$i] == 4) $divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_14\"></span></td>\r\n";
    			if($arrtznum[$i] == 5) $divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_15\"></span></td>\r\n";
    		}
    		
    		
    		if($arrtznum[$i] >= 6 && $arrtznum[$i] <= 25){
	    		$j = $arrtznum[$i] - 5;
	    		$divTable .= "\t\t\t<td class=\"ds\">1号<i class=\"mh m{$j}\"></i></td>\r\n";
	    	}
	    
	    	if($arrtznum[$i] >= 26 && $arrtznum[$i] <= 37){
	    		if($arrtznum[$i] == 26)
	    			$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_1\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 27)
	    			$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_5\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 28)
	    			$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_14\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 29)
	        		$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_17\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 30)
	    			$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_6\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 31)
	    			$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_0\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 32)
	    			$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_15\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 33)
	    			$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_16\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 34)
	    			$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_18\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 35)
	    			$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_19\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 36)
	    			$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_20\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 37)
	    			$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_21\"></span></td>\r\n";
	    	}
	    	
	    	
	    	
	    	
    	    if($arrtznum[$i] >= 38 && $arrtznum[$i] <= 57){
	    		$j = $arrtznum[$i] - 37;
	    		$divTable .= "\t\t\t<td class=\"ds\">2号<i class=\"mh m{$j}\"></i></td>\r\n";
	    	}
	    
	    	if($arrtznum[$i] >= 58 && $arrtznum[$i] <= 69){
	    		if($arrtznum[$i] == 58)
	    			$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_1\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 59)
	    			$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_5\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 60)
	    			$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_14\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 61)
	        		$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_17\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 62)
	    			$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_6\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 63)
	    			$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_0\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 64)
	    			$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_15\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 65)
	    			$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_16\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 66)
	    			$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_18\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 67)
	    			$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_19\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 68)
	    			$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_20\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 69)
	    			$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_21\"></span></td>\r\n";
	    	}
	    	
	    	
	    	if($arrtznum[$i] >= 70 && $arrtznum[$i] <= 89){
	    		$j = $arrtznum[$i] - 69;
	    		$divTable .= "\t\t\t<td class=\"ds\">3号<i class=\"mh m{$j}\"></i></td>\r\n";
	    	}
	    	 
	    	if($arrtznum[$i] >= 90 && $arrtznum[$i] <= 101){
	    		if($arrtznum[$i] == 90)
	    			$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_1\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 91)
	    			$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_5\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 92)
	    			$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_14\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 93)
	    			$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_17\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 94)
	    			$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_6\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 95)
	    			$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_0\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 96)
	    			$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_15\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 97)
	    			$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_16\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 98)
	    			$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_18\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 99)
	    			$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_19\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 100)
	    			$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_20\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 101)
	    			$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_21\"></span></td>\r\n";
	    	}    	
	    	
	    	
	    	
	    	if($arrtznum[$i] >= 102 && $arrtznum[$i] <= 121){
	    		$j = $arrtznum[$i] - 101;
	    		$divTable .= "\t\t\t<td class=\"ds\">4号<i class=\"mh m{$j}\"></i></td>\r\n";
	    	}
	    	 
	    	if($arrtznum[$i] >= 122 && $arrtznum[$i] <= 133){
	    		if($arrtznum[$i] == 122)
	    			$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_1\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 123)
	    			$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_5\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 124)
	    			$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_14\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 125)
	    			$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_17\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 126)
	    			$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_6\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 127)
	    			$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_0\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 128)
	    			$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_15\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 129)
	    			$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_16\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 130)
	    			$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_18\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 131)
	    			$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_19\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 132)
	    			$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_20\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 133)
	    			$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_21\"></span></td>\r\n";
	    	}
	    	
	    	
	    	if($arrtznum[$i] >= 134 && $arrtznum[$i] <= 153){
	    		$j = $arrtznum[$i] - 133;
	    		$divTable .= "\t\t\t<td class=\"ds\">5号<i class=\"mh m{$j}\"></i></td>\r\n";
	    	}
	    	 
	    	if($arrtznum[$i] >= 154 && $arrtznum[$i] <= 165){
	    		if($arrtznum[$i] == 154)
	    			$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_1\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 155)
	    			$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_5\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 156)
	    			$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_14\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 157)
	    			$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_17\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 158)
	    			$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_6\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 159)
	    			$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_0\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 160)
	    			$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_15\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 161)
	    			$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_16\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 162)
	    			$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_18\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 163)
	    			$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_19\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 164)
	    			$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_20\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 165)
	    			$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_21\"></span></td>\r\n";
	    	}
	    	
	    	
	    	if($arrtznum[$i] >= 166 && $arrtznum[$i] <= 185){
	    		$j = $arrtznum[$i] - 165;
	    		$divTable .= "\t\t\t<td class=\"ds\">6号<i class=\"mh m{$j}\"></i></td>\r\n";
	    	}
	    	 
	    	if($arrtznum[$i] >= 186 && $arrtznum[$i] <= 197){
	    		if($arrtznum[$i] == 186)
	    			$divTable .= "\t\t\t<td class=\"ds\">6号<span class=\"ds_1\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 187)
	    			$divTable .= "\t\t\t<td class=\"ds\">6号<span class=\"ds_5\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 188)
	    			$divTable .= "\t\t\t<td class=\"ds\">6号<span class=\"ds_14\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 189)
	    			$divTable .= "\t\t\t<td class=\"ds\">6号<span class=\"ds_17\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 190)
	    			$divTable .= "\t\t\t<td class=\"ds\">6号<span class=\"ds_6\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 191)
	    			$divTable .= "\t\t\t<td class=\"ds\">6号<span class=\"ds_0\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 192)
	    			$divTable .= "\t\t\t<td class=\"ds\">6号<span class=\"ds_15\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 193)
	    			$divTable .= "\t\t\t<td class=\"ds\">6号<span class=\"ds_16\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 194)
	    			$divTable .= "\t\t\t<td class=\"ds\">6号<span class=\"ds_18\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 195)
	    			$divTable .= "\t\t\t<td class=\"ds\">6号<span class=\"ds_19\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 196)
	    			$divTable .= "\t\t\t<td class=\"ds\">6号<span class=\"ds_20\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 197)
	    			$divTable .= "\t\t\t<td class=\"ds\">6号<span class=\"ds_21\"></span></td>\r\n";
	    	}	    	
	    	

	    	
	    	if($arrtznum[$i] >= 198 && $arrtznum[$i] <= 217){
	    		$j = $arrtznum[$i] - 197;
	    		$divTable .= "\t\t\t<td class=\"ds\">7号<i class=\"mh m{$j}\"></i></td>\r\n";
	    	}
	    	 
	    	if($arrtznum[$i] >= 218 && $arrtznum[$i] <= 229){
	    		if($arrtznum[$i] == 218)
	    			$divTable .= "\t\t\t<td class=\"ds\">7号<span class=\"ds_1\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 219)
	    			$divTable .= "\t\t\t<td class=\"ds\">7号<span class=\"ds_5\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 220)
	    			$divTable .= "\t\t\t<td class=\"ds\">7号<span class=\"ds_14\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 221)
	    			$divTable .= "\t\t\t<td class=\"ds\">7号<span class=\"ds_17\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 222)
	    			$divTable .= "\t\t\t<td class=\"ds\">7号<span class=\"ds_6\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 223)
	    			$divTable .= "\t\t\t<td class=\"ds\">7号<span class=\"ds_0\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 224)
	    			$divTable .= "\t\t\t<td class=\"ds\">7号<span class=\"ds_15\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 225)
	    			$divTable .= "\t\t\t<td class=\"ds\">7号<span class=\"ds_16\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 226)
	    			$divTable .= "\t\t\t<td class=\"ds\">7号<span class=\"ds_18\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 227)
	    			$divTable .= "\t\t\t<td class=\"ds\">7号<span class=\"ds_19\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 228)
	    			$divTable .= "\t\t\t<td class=\"ds\">7号<span class=\"ds_20\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 229)
	    			$divTable .= "\t\t\t<td class=\"ds\">7号<span class=\"ds_21\"></span></td>\r\n";
	    	}	    	
	    	
	    	
	    	
	    	if($arrtznum[$i] >= 230 && $arrtznum[$i] <= 249){
	    		$j = $arrtznum[$i] - 229;
	    		$divTable .= "\t\t\t<td class=\"ds\">8号<i class=\"mh m{$j}\"></i></td>\r\n";
	    	}
	    	 
	    	if($arrtznum[$i] >= 250 && $arrtznum[$i] <= 261){
	    		if($arrtznum[$i] == 250)
	    			$divTable .= "\t\t\t<td class=\"ds\">8号<span class=\"ds_1\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 251)
	    			$divTable .= "\t\t\t<td class=\"ds\">8号<span class=\"ds_5\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 252)
	    			$divTable .= "\t\t\t<td class=\"ds\">8号<span class=\"ds_14\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 253)
	    			$divTable .= "\t\t\t<td class=\"ds\">8号<span class=\"ds_17\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 254)
	    			$divTable .= "\t\t\t<td class=\"ds\">8号<span class=\"ds_6\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 255)
	    			$divTable .= "\t\t\t<td class=\"ds\">8号<span class=\"ds_0\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 256)
	    			$divTable .= "\t\t\t<td class=\"ds\">8号<span class=\"ds_15\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 257)
	    			$divTable .= "\t\t\t<td class=\"ds\">8号<span class=\"ds_16\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 258)
	    			$divTable .= "\t\t\t<td class=\"ds\">8号<span class=\"ds_18\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 259)
	    			$divTable .= "\t\t\t<td class=\"ds\">8号<span class=\"ds_19\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 260)
	    			$divTable .= "\t\t\t<td class=\"ds\">8号<span class=\"ds_20\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 261)
	    			$divTable .= "\t\t\t<td class=\"ds\">8号<span class=\"ds_21\"></span></td>\r\n";
	    	}
	    	
    
    
    		if($arrtznum[$i] == 262){
        		$divTable .= "\t\t\t<td class=\"sc\">1V8<span class=\"sc lh_0\">0</span></td>\r\n";
        	}
        	if($arrtznum[$i] == 263){
        		$divTable .= "\t\t\t<td class=\"sc\">1V8<span class=\"sc lh_1\">1</span></td>\r\n";
        	}
    
    		if($arrtznum[$i] == 264){
    			$divTable .= "\t\t\t<td class=\"sc\">2V7<span class=\"sc lh_0\">0</span></td>\r\n";
        	}
    		if($arrtznum[$i] == 265){
    			$divTable .= "\t\t\t<td class=\"sc\">2V7<span class=\"sc lh_1\">1</span></td>\r\n";
        	}
    
    		if($arrtznum[$i] == 266){
    			$divTable .= "\t\t\t<td class=\"sc\">3V6<span class=\"sc lh_0\">0</span></td>\r\n";
        	}
    		if($arrtznum[$i] == 267){
    			$divTable .= "\t\t\t<td class=\"sc\">3V6<span class=\"sc lh_1\">1</span></td>\r\n";
        	}
    
    		if($arrtznum[$i] == 268){
    			$divTable .= "\t\t\t<td class=\"sc\">4V5<span class=\"sc lh_0\">0</span></td>\r\n";
        	}
    		if($arrtznum[$i] == 269){
    			$divTable .= "\t\t\t<td class=\"sc\">4V5<span class=\"sc lh_1\">1</span></td>\r\n";
        	}
    
    
    		$divTable .= "\t\t\t<td>".sprintf('%.4f',$arrStdOdds[$arrtznum[$i]])."</td>\r\n";
        	$divTable .= "\t\t\t<td class='please'>". Trans($arrtzpoints[$i]) ."</td>\r\n";
        	$divTable .= "\t\t\t<td class='please'>". Trans($arrzjpoints[$i]) ."</td>\r\n";
        	$divTable .= "\t\n</tr>\r\n";
    	}
        $divTable .= "\t</tbody>\r\n";
        $divTable .= "\t</table>\r\n";
        $divTable .= "</div>";
    	return $divTable;
    }
    
    
    
    
    
    function GetTableContentSSC($act,$No)
    {
    	global $db;
    	$tabletz = GetGameTableName($act,'users_tz');
    	$tablegame = GetGameTableName($act,"game");
    	if($tabletz == "")
    		return "提交参数错误！";
    	//取押注情况
    	$sql = "SELECT tznum,tzpoints,points,hdpoints,zjpoints,zjpl FROM {$tabletz} WHERE uid = '{$_SESSION['usersid']}' AND NO = '{$No}'";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
    		$arrtznum = explode("|",$rs['tznum']);
    		$arrtzpoints = explode("|",$rs["tzpoints"]);
    		$arrzjpoints = explode("|",$rs['zjpoints']);
    		$hdpoints = $rs['hdpoints'];
    		$points = $rs['points'];
    		$zjpl=$rs['zjpl'];
    		 
    	}
    	else
    	{
    		return "<p>很抱歉,无投注记录！</p>";
    	}
    
    	//取开奖时间,赔率
    	$sql = "SELECT zjpl,kgtime FROM {$tablegame} WHERE id = {$No}";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
    		$arrStdOdds = explode("|",$rs['zjpl']);
    		$kgtime = $rs['kgtime'];
    	}
    	else
    	{
    		return "无法取得开奖时间";
    	}
    
    
    	//填表
    	$divTable ="<div class='table'>";
    	$divTable .= "\t<table class='table_list table table-hover table-striped table-bordered' >\r\n";
    	$divTable .= "\t<tbody>\r\n";
    	$divTable .= "\t\t<tr><td colspan='5'>第{$No}期投注详细结果</td></tr>\r\n";
    	$divTable .= "\t\t<tr><td colspan='5' class='please'>开奖时间:{$kgtime} 投注:<span>". Trans($points) ."</span> 获得:<span>". Trans($hdpoints) ."</span></td></tr>\r\n";
    
    	$divTable .= "\t\t<tr>\r\n";
    	$divTable .= "\t\t\t<th width='80'>号码</th>\r\n";
    	$divTable .= "\t\t\t<th width='100'>赔率</th>\r\n";
    	$divTable .= "\t\t\t<th width='160'>投注数量</th>\r\n";
    	$divTable .= "\t\t\t<th width='160'>获得数量</th>\r\n";
    	$divTable .= "\t\t</tr>\r\n";
    	for($i = 0; $i < count($arrzjpoints);$i++)
    	{
			$divTable .= "\t\t<tr>\r\n";
    		if($arrtznum[$i] >= 0 && $arrtznum[$i] <= 6){
    			if($arrtznum[$i] == 0) $divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_1\"></span></td>\r\n";
    			if($arrtznum[$i] == 1) $divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_6\"></span></td>\r\n";
    			if($arrtznum[$i] == 2) $divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_0\"></span></td>\r\n";
    			if($arrtznum[$i] == 3) $divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_5\"></span></td>\r\n";
    			if($arrtznum[$i] == 4) $divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_10\"></span></td>\r\n";
    			if($arrtznum[$i] == 5) $divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_11\"></span></td>\r\n";
    			if($arrtznum[$i] == 6) $divTable .= "\t\t\t<td class=\"ds\"><span class=\"ds_13\"></span></td>\r\n";
    		}
        		 
        	if($arrtznum[$i] >= 7 && $arrtznum[$i] <= 10){
        		if($arrtznum[$i] == 7)
        			$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_1\"></span></td>\r\n";
        		elseif($arrtznum[$i] == 8)
        			$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_6\"></span></td>\r\n";
        		elseif($arrtznum[$i] == 9)
        			$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_0\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 10)
        			$divTable .= "\t\t\t<td class=\"ds\">1号<span class=\"ds_5\"></span></td>\r\n";
	    	}
	    	
	    	if($arrtznum[$i] >= 11 && $arrtznum[$i] <= 20){
	    		$j = $arrtznum[$i] - 11;
	    		$divTable .= "\t\t\t<td class=\"ds\">1号<i class=\"mh m{$j}\"></i></td>\r\n";
	    	}
    
	    	
	    	
	    	if($arrtznum[$i] >= 21 && $arrtznum[$i] <= 24){
	    		if($arrtznum[$i] == 21)
	    			$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_1\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 22)
	    			$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_6\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 23)
	    			$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_0\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 24)
	    			$divTable .= "\t\t\t<td class=\"ds\">2号<span class=\"ds_5\"></span></td>\r\n";
	    	}
	    	
	    	if($arrtznum[$i] >= 25 && $arrtznum[$i] <= 34){
	    		$j = $arrtznum[$i] - 25;
	    		$divTable .= "\t\t\t<td class=\"ds\">2号<i class=\"mh m{$j}\"></i></td>\r\n";
	    	}
    
    
	    	
	    	if($arrtznum[$i] >= 35 && $arrtznum[$i] <= 38){
	    		if($arrtznum[$i] == 35)
	    			$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_1\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 36)
	    			$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_6\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 37)
	    			$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_0\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 38)
	    			$divTable .= "\t\t\t<td class=\"ds\">3号<span class=\"ds_5\"></span></td>\r\n";
	    	}
	    	
	    	if($arrtznum[$i] >= 39 && $arrtznum[$i] <= 48){
	    		$j = $arrtznum[$i] - 39;
	    		$divTable .= "\t\t\t<td class=\"ds\">3号<i class=\"mh m{$j}\"></i></td>\r\n";
	    	}
	    	
	    	
	    	
	    	
	    	if($arrtznum[$i] >= 49 && $arrtznum[$i] <= 52){
	    		if($arrtznum[$i] == 49)
	    			$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_1\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 50)
	    			$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_6\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 51)
	    			$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_0\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 52)
	    			$divTable .= "\t\t\t<td class=\"ds\">4号<span class=\"ds_5\"></span></td>\r\n";
	    	}
	    	
	    	if($arrtznum[$i] >= 53 && $arrtznum[$i] <= 62){
	    		$j = $arrtznum[$i] - 53;
	    		$divTable .= "\t\t\t<td class=\"ds\">4号<i class=\"mh m{$j}\"></i></td>\r\n";
	    	}
	    	
	    	
	    	
	    	if($arrtznum[$i] >= 63 && $arrtznum[$i] <= 66){
	    		if($arrtznum[$i] == 63)
	    			$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_1\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 64)
	    			$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_6\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 65)
	    			$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_0\"></span></td>\r\n";
	    		elseif($arrtznum[$i] == 66)
	    			$divTable .= "\t\t\t<td class=\"ds\">5号<span class=\"ds_5\"></span></td>\r\n";
	    	}
	    	
	    	if($arrtznum[$i] >= 67 && $arrtznum[$i] <= 76){
	    		$j = $arrtznum[$i] - 67;
	    		$divTable .= "\t\t\t<td class=\"ds\">5号<i class=\"mh m{$j}\"></i></td>\r\n";
	    	}
	    	
	    	
	    	if($arrtznum[$i] == 77){
	    		$divTable .= "\t\t\t<td class=\"sc\">前三<i class='zh z1'></i></td>\r\n";
	    	}
	    	if($arrtznum[$i] == 78){
	    		$divTable .= "\t\t\t<td class=\"sc\">前三<i class='zh z2'></i></td>\r\n";
	    	}
	    	if($arrtznum[$i] == 79){
	    		$divTable .= "\t\t\t<td class=\"sc\">前三<i class='zh z3'></i></td>\r\n";
	    	}
	    	if($arrtznum[$i] == 80){
	    		$divTable .= "\t\t\t<td class=\"sc\">前三<i class='zh z4'></i></td>\r\n";
	    	}
	    	if($arrtznum[$i] == 81){
	    		$divTable .= "\t\t\t<td class=\"sc\">前三<i class='zh z5'></i></td>\r\n";
	    	}
	    	
	    	
	    	if($arrtznum[$i] == 82){
	    		$divTable .= "\t\t\t<td class=\"sc\">中三<i class='zh z1'></i></td>\r\n";
	    	}
	    	if($arrtznum[$i] == 83){
	    		$divTable .= "\t\t\t<td class=\"sc\">中三<i class='zh z2'></i></td>\r\n";
	    	}
	    	if($arrtznum[$i] == 84){
	    		$divTable .= "\t\t\t<td class=\"sc\">中三<i class='zh z3'></i></td>\r\n";
	    	}
	    	if($arrtznum[$i] == 85){
	    		$divTable .= "\t\t\t<td class=\"sc\">中三<i class='zh z4'></i></td>\r\n";
	    	}
	    	if($arrtznum[$i] == 86){
	    		$divTable .= "\t\t\t<td class=\"sc\">中三<i class='zh z5'></i></td>\r\n";
	    	}
	    	
	    	
	    	if($arrtznum[$i] == 87){
	    		$divTable .= "\t\t\t<td class=\"sc\">后三<i class='zh z1'></i></td>\r\n";
	    	}
	    	if($arrtznum[$i] == 88){
	    		$divTable .= "\t\t\t<td class=\"sc\">后三<i class='zh z2'></i></td>\r\n";
	    	}
	    	if($arrtznum[$i] == 89){
	    		$divTable .= "\t\t\t<td class=\"sc\">后三<i class='zh z3'></i></td>\r\n";
	    	}
	    	if($arrtznum[$i] == 90){
	    		$divTable .= "\t\t\t<td class=\"sc\">后三<i class='zh z4'></i></td>\r\n";
	    	}
	    	if($arrtznum[$i] == 91){
	    		$divTable .= "\t\t\t<td class=\"sc\">后三<i class='zh z5'></i></td>\r\n";
	    	}
	    	
	    	
	    	
    
    
    		$divTable .= "\t\t\t<td>".sprintf('%.4f',$arrStdOdds[$arrtznum[$i]])."</td>\r\n";
        	$divTable .= "\t\t\t<td class='please'>". Trans($arrtzpoints[$i]) ."</td>\r\n";
            $divTable .= "\t\t\t<td class='please'>". Trans($arrzjpoints[$i]) ."</td>\r\n";
    		$divTable .= "\t\n</tr>\r\n";
    	}
    	
    	
    	
    	$divTable .= "\t</tbody>\r\n";
    	$divTable .= "\t</table>\r\n";
        $divTable .= "</div>";
		return $divTable;
    }
    
    
    

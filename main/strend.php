<?php
	include_once("inc/conn.php");
    include_once("inc/function.php");
    
    if(!isset($_SESSION['usersid'])) {
		echo "您还没登录或者链接超时，请先去<a href='/login.php'>登录</a>!";
		exit;
	}
	
	
	$act = intval($_GET['act']);
	//返回界面
	GetGameTrendContent($act);
	
	
	
	function show_num_36($num)
	{
		if($num == 1) return "豹";
		if($num == 2) return "对";
		if($num == 3) return "顺";
		if($num == 4) return "半";
		if($num == 5) return "杂";
	}
	
    /* 取得界面
    * 
    */
    function GetGameTrendContent($act)
    {
		$sid = intval($_GET['sid']);
		$numRec = ($_GET['num'] == "")?100:intval($_GET['num']);
		if($numRec > 500)
			$numRec = 500;
		
		if($act == 29){
			$RetContent .= "<style>
			.trend .table_list td em.regular01{ background-position:0px 0px;}
			.trend .table_list td em.regular02{ background-position:0px -25px;}
			.trend .table_list td em.regular03{ background-position:0px -52px;}
			.trend .table_list td em.regular04{ background-position:0px -78px;}
			.trend .table_list td em.regular05{ background-position:0px -104px;}
			.trend .table_list td em.regular06{ background-position:0px -130px;}
			.trend .table_list td em.regular07{ background-position:0px -156px;}
			.trend .table_list td em.regular08{ background-position:0px -182px;}
			.trend .table_list td em.regular09{ background-position:0px -208px;}
			.trend .table_list td em.regular10{ background-position:0px -234px;}
			.trend .table_list td em.light01{ background-position:0px -260px;}
			.trend .table_list td em.light02{ background-position:0px -286px;}
			.trend .table_list td em.light03{ background-position:0px -312px;}
			.trend .table_list td em.light04{ background-position:0px -338px;}
			.trend .table_list td em.light05{ background-position:0px -364px;}
			.trend .table_list td em.light06{ background-position:0px -390px;}
			.trend .table_list td em.light07{ background-position:0px -416px;}
			.trend .table_list td em.light08{ background-position:0px -441px;}
			.trend .table_list td em.light09{ background-position:0px -468px;}
			.trend .table_list td em.light10{ background-position:0px -494px;}
			.trend .table_list td em.final{background-position:1px -497px; height:23px; width:26px; position:relative;}
			.trend .table_list td em.finals{background-position:0px -497px; height:26px; width:30px; position:relative; top:5px}
			.trend .table_list td em.final i{position:absolute; width:30px; display:block; height:26px; text-align:center; color:#fff; top:0px; left:0; line-height:26px;}
			.trend .table_list td li.finalbig {font-style:normal; color:#fff; font-size:21px; background:url(/img/lottery.fw.png) no-repeat; display:inline-block; width:32px; height:32px; text-indent:0px; line-height:32px; text-align:center; }
			.trend .table_list td em{background:url(/img/word.fw.png) no-repeat; display:inline-block; width:22px; height:26px;*display:inline;*zoom:1;letter-spacing:normal;word-spacing:normal;}
			.table_list td.gno{padding:0 15px;color:#555}
			.table_list td.gtime{padding:0 15px;color:#555}
			.table_list td.he{color:#900;}
			.table_list td.zhedx{color:#018796;}
			.table_list td.zhelh{color:#c00;}
			.table_list td.qs{color:#f00;}
			.table_list td.zs{color:#00f;}
			.table_list td.hs{color:#fd00ff;}
			.table_list td.sc1{color:#f00;}
			.table_list td.sc2{	color:#00c6ff;}
			</style>\r\n";
		}
		
		
		$RetContent .= "<div class='trend'>\r\n";
		$RetContent .= "\t<div class='Content'>\r\n";
		//取得子菜单
		$RetContent .= GetSubMenu($act,$sid);
		
		$RetContent .= "\t</div>\r\n"; //content结束
		
		//取号码表格
		if(in_array($act,[25,27,30,41])){
			$RetContent .= GetTableContentWW($act,$numRec);
		}else if(in_array($act,[26,28,31,42])){
			$RetContent .= GetTableContentDW($act,$numRec);
		}else if(in_array($act,[29])){
			$RetContent .= GetTableContentSC($act,$numRec);
		}else if(in_array($act,[36])){
			$RetContent .= GetTableContentXYNC($act,$numRec);
		}else if(in_array($act,[37])){
			$RetContent .= GetTableContentSSC($act,$numRec);
		}else{
			$RetContent .= GetTableContent($act,$numRec);
		}
		
		
		$RetContent .= "</div>\r\n";
		//取css
		$RetContent .= "";
		//js 定义
		$RetContent .= GetJSContent($act,$sid,$numRec);
		echo $RetContent;
		exit;
    }
    
    
    function GetTableContentWW($act,$numRec){
    	global $db;
    	$tablegame = GetGameTableName($act,"game");
    	
    	$rewardNumCnt = 28;
    	$reward_num_type = 'game28';
    	$step = 0;
    	
    	$sql = "SELECT GROUP_CONCAT(num SEPARATOR '|') AS strnum FROM gameodds WHERE game_type = '{$reward_num_type}' ORDER BY num";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
    		$arrStdNums = explode("|",$rs['strnum']);
    	}
    	else
    	{
    		return "无法取得标准赔率";
    	}
    	
    	//实际次数
    	$arrCurTimes = array();
    	$arrCurTimes[0] = "实际次数";
    	for($i = 0; $i < $rewardNumCnt+10; $i++)
    	{
			$arrCurTimes[$i+1] = 0;
    	}
    	
    	$arrTH = array();
    	$arrTD = array();
    	$arrTH[0] = "<th width='50'>期号</th>";
    	$arrTH[1] = "<th width='60'>时间</th>";
    	$arrTD[0] = "";
    	$arrTD[1] = "";
    	for($i = 0; $i < $rewardNumCnt; $i++)
    	{
    		$arrTH[$i+2] = "<th width='22'>" . $arrStdNums[$i] . "</th>";
			$num = $rewardNumCnt / 4;
			if($i < $num || $i >= 3 * $num - 1)
				$arrTD[$i+2] = "<td class='bgnum'></td>";
			else
				$arrTD[$i+2] = "<td></td>";
    	}
    	 
    	$arrTH[$rewardNumCnt+2] = "<th width='22'>单</th>";
    	$arrTH[$rewardNumCnt+3] = "<th width='22'>双</th>";
    	$arrTH[$rewardNumCnt+4] = "<th width='22'>大</th>";
    	$arrTH[$rewardNumCnt+5] = "<th width='22'>小</th>";
    	$arrTH[$rewardNumCnt+6] = "<th width='22'>小单</th>";
    	$arrTH[$rewardNumCnt+7] = "<th width='22'>小双</th>";
    	$arrTH[$rewardNumCnt+8] = "<th width='22'>大单</th>";
    	$arrTH[$rewardNumCnt+9] = "<th width='22'>大双</th>";
    	$arrTH[$rewardNumCnt+10] = "<th width='22'>极小</th>";
		$arrTH[$rewardNumCnt+11] = "<th width='22'>极大</th>";
    	    			 
		$arrTD[$rewardNumCnt+2] = "<td>单</td>";
		$arrTD[$rewardNumCnt+3] = "<td>双</td>";
		$arrTD[$rewardNumCnt+4] = "<td>大</td>";
		$arrTD[$rewardNumCnt+5] = "<td>小</td>";
		$arrTD[$rewardNumCnt+6] = "<td>小单</td>";
		$arrTD[$rewardNumCnt+7] = "<td>小双</td>";
		$arrTD[$rewardNumCnt+8] = "<td>大单</td>";
		$arrTD[$rewardNumCnt+9] = "<td>大双</td>";
		$arrTD[$rewardNumCnt+10] = "<td>极小</td>";
		$arrTD[$rewardNumCnt+11] = "<td>极大</td>";
		
		
		//取数据
		$sql = "SELECT id,kgtime,kgjg FROM {$tablegame} WHERE kj = 1 ORDER BY kgtime DESC LIMIT {$numRec}";
		$result = $db->query($sql);
		$secondTableBody = "";
		while($rs = $db->fetch_array($result))
		{
			$arrTmpTD = $arrTD;
			$arrTmpTD[0] = "<td class='tdbg3'>" . $rs['id'] . "</td>";
			$arrTmpTD[1] = "<td class='black777' style='color:#000;'>" . date("m-d H:i",strtotime($rs['kgtime'])) . "</td>";
			//中奖号
			$arrkjNum = explode("|",$rs['kgjg']);
			$kjNum = $arrkjNum[count($arrkjNum)-1];
			//写进中奖号
			$num = $rewardNumCnt / 4;
			$index = $kjNum+2-$step;
			$seq = $kjNum - $step;
			
			$arrCurTimes[$index-1]++;
			if($seq < $num + 3 || $seq > 3 * $num - 4 )
				$arrTmpTD[$index] = "<td class='bgnum'><em class='final'><i>{$kjNum}</i></em></td>";
			else
				$arrTmpTD[$index] = "<td><em class='final'><i>{$kjNum}</i></em></td>";

			
			//大小
			$is_max = 0;
			$num = $rewardNumCnt / 2;
			if($kjNum-$step >= $num)
			{
				$is_max = 1;
				$arrTmpTD[$rewardNumCnt+4] = "<td class='bgkai05'>大</td>";
				$arrCurTimes[$rewardNumCnt+3]++;
				if($kjNum >= 22){
					$arrTmpTD[$rewardNumCnt+11] = "<td class='bgkai05'>极大</td>";
					$arrCurTimes[$rewardNumCnt+10]++;
				}
			}
			else
			{
				$arrTmpTD[$rewardNumCnt+5] = "<td class='bgkai06'>小</td>";
				$arrCurTimes[$rewardNumCnt+4]++;
				if($kjNum <= 5){
					$arrTmpTD[$rewardNumCnt+10] = "<td class='bgkai05'>极小</td>";
					$arrCurTimes[$rewardNumCnt+9]++;
				}
			}
			
			//单双
			if($kjNum % 2 != 0)
			{
				$arrTmpTD[$rewardNumCnt+2] = "<td class='bgkai01'>单</td>";
				$arrCurTimes[$rewardNumCnt+1]++;
				if($is_max){
					$arrTmpTD[$rewardNumCnt+8] = "<td class='bgkai01'>大单</td>";
					$arrCurTimes[$rewardNumCnt+7]++;
				}else{
					$arrTmpTD[$rewardNumCnt+6] = "<td class='bgkai01'>小单</td>";
					$arrCurTimes[$rewardNumCnt+5]++;
				}
			}
			else
			{
				$arrTmpTD[$rewardNumCnt+3] = "<td class='bgkai02'>双</td>";
				$arrCurTimes[$rewardNumCnt+2]++;
				if($is_max){
					$arrTmpTD[$rewardNumCnt+9] = "<td class='bgkai01'>大双</td>";
					$arrCurTimes[$rewardNumCnt+8]++;
				}else{
					$arrTmpTD[$rewardNumCnt+7] = "<td class='bgkai01'>小双</td>";
					$arrCurTimes[$rewardNumCnt+6]++;
				}
			}
			

			//写进表
			$secondTableBody .= "\t\t\t<tr>\r\n";
			$secondTableBody .= implode("\r\n",$arrTmpTD) . "\r\n";
			$secondTableBody .= "\t\t\t</tr>\r\n";
		
		}
    	
    	
		//表格数据
		$divTable = "<div class='table'>\r\n";
		$divTable .= "\t<table class='table_list'  cellspacing='0px'>\r\n";
		//第一个tbody
		$divTable .= "\t\t<tbody>\r\n";
		//头
		$divTable .= "\t\t\t<tr bgcolor='#fbfbfb'>\r\n";
		$divTable .= "\t\t\t\t<th colspan='". ($rewardNumCnt+12) ."'>走势图 <select id='sltNum'>\r\n";
		for($i = 1; $i <= 5; $i++)
		{
			$theRecCnt = $i * 100;
			if($theRecCnt == $numRec)
				$divTable .= "\t\t\t\t\t<option value={$theRecCnt} selected='selected'>最新{$theRecCnt}期</option>\r\n";
			else
				$divTable .= "\t\t\t\t\t<option value={$theRecCnt}>最新{$theRecCnt}期</option>\r\n";
		} 
		$divTable .= "\t\t\t\t</select></th>\r\n";
		$divTable .= "\t\t\t</tr>\r\n";
		//实际次数
		$divTable .= "\t\t\t<tr class='timeh'>\r\n"; 
		for($i = 0; $i < count($arrCurTimes); $i++)
		{
			if($i == 0)
				$divTable .= "\t\t\t<th colspan=2><b class='black777'>{$arrCurTimes[$i]}</b></th>\r\n";
			else
				$divTable .= "\t\t\t<th width=\"22\">{$arrCurTimes[$i]}</th>\r\n";
		}
		$divTable .= "\t\t\t</tr>\r\n";
		
		//标题
		$divTable .= "\t\t\t<tr class='font_color_2' bgcolor='#e3f0ff'>\r\n"; 
		$divTable .= "\t\t\t" . implode("\r\n",$arrTH);
		$divTable .= "\t\t\t</tr>\r\n";
		$divTable .= "\t\t\t</tr>\r\n";
		$divTable .= "\t\t</tbody>\r\n";
		
		
		
		//第二个tbody
		$divTable .= "\t\t<tbody>\r\n";
		
		$divTable .= $secondTableBody;
		
		$divTable .= "\t\t</tbody>\r\n";
		$divTable .= "\t</table>";
		$divTable .= "</div>\r\n";
		
		return $divTable;
    }
    
    function GetTableContentDW($act,$numRec){
    	global $db;
    	$tablegame = GetGameTableName($act,"game");
    	
    	$rewardNumCnt = 28;
    	$reward_num_type = 'game28';
    	$step = 0;
    	
    	$sql = "SELECT GROUP_CONCAT(num SEPARATOR '|') AS strnum FROM gameodds WHERE game_type = '{$reward_num_type}' ORDER BY num";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
    		$arrStdNums = explode("|",$rs['strnum']);
    	}
    	else
    	{
    		return "无法取得标准赔率";
    	}
    	
    	//实际次数
    	$arrCurTimes = array();
    	$arrCurTimes[0] = "实际次数";
    	for($i = 0; $i < $rewardNumCnt+13; $i++)
    	{
			$arrCurTimes[$i+1] = 0;
    	}
    	
    	$arrTH = array();
    	$arrTD = array();
    	$arrTH[0] = "<th width='50'>期号</th>";
    	$arrTH[1] = "<th width='60'>时间</th>";
    	$arrTD[0] = "";
    	$arrTD[1] = "";
    	for($i = 0; $i < $rewardNumCnt; $i++)
    	{
    		$arrTH[$i+2] = "<th width='22'>" . $arrStdNums[$i] . "</th>";
			$num = $rewardNumCnt / 4;
			if($i < $num || $i >= 3 * $num - 1)
				$arrTD[$i+2] = "<td class='bgnum'></td>";
			else
				$arrTD[$i+2] = "<td></td>";
    	}
    	 
    	$arrTH[$rewardNumCnt+2] = "<th width='22'>单</th>";
    	$arrTH[$rewardNumCnt+3] = "<th width='22'>双</th>";
    	$arrTH[$rewardNumCnt+4] = "<th width='22'>大</th>";
    	$arrTH[$rewardNumCnt+5] = "<th width='22'>小</th>";
    	$arrTH[$rewardNumCnt+6] = "<th width='22'>小单</th>";
    	$arrTH[$rewardNumCnt+7] = "<th width='22'>小双</th>";
    	$arrTH[$rewardNumCnt+8] = "<th width='22'>大单</th>";
    	$arrTH[$rewardNumCnt+9] = "<th width='22'>大双</th>";
    	$arrTH[$rewardNumCnt+10] = "<th width='22'>极小</th>";
		$arrTH[$rewardNumCnt+11] = "<th width='22'>极大</th>";
		$arrTH[$rewardNumCnt+12] = "<th width='22'>龙</th>";
		$arrTH[$rewardNumCnt+13] = "<th width='22'>虎</th>";
		$arrTH[$rewardNumCnt+14] = "<th width='22'>和</th>";
    	    			 
		$arrTD[$rewardNumCnt+2] = "<td>单</td>";
		$arrTD[$rewardNumCnt+3] = "<td>双</td>";
		$arrTD[$rewardNumCnt+4] = "<td>大</td>";
		$arrTD[$rewardNumCnt+5] = "<td>小</td>";
		$arrTD[$rewardNumCnt+6] = "<td>小单</td>";
		$arrTD[$rewardNumCnt+7] = "<td>小双</td>";
		$arrTD[$rewardNumCnt+8] = "<td>大单</td>";
		$arrTD[$rewardNumCnt+9] = "<td>大双</td>";
		$arrTD[$rewardNumCnt+10] = "<td>极小</td>";
		$arrTD[$rewardNumCnt+11] = "<td>极大</td>";
		$arrTD[$rewardNumCnt+12] = "<td>龙</td>";
		$arrTD[$rewardNumCnt+13] = "<td>虎</td>";
		$arrTD[$rewardNumCnt+14] = "<td>和</td>";
		
		
		//取数据
		$sql = "SELECT id,kgtime,kgjg FROM {$tablegame} WHERE kj = 1 ORDER BY kgtime DESC LIMIT {$numRec}";
		$result = $db->query($sql);
		$secondTableBody = "";
		while($rs = $db->fetch_array($result))
		{
			$arrTmpTD = $arrTD;
			$arrTmpTD[0] = "<td class='tdbg3'>" . $rs['id'] . "</td>";
			$arrTmpTD[1] = "<td class='black777' style='color:#000;'>" . date("m-d H:i",strtotime($rs['kgtime'])) . "</td>";
			//中奖号
			$arrkjNum = explode("|",$rs['kgjg']);
			$kjNum = $arrkjNum[count($arrkjNum)-1];
			$kjA = $arrkjNum[0];
			$kjC = $arrkjNum[2];
			
			if($kjA > $kjC){
				$arrTmpTD[$rewardNumCnt+12] = "<td class='bgkai05'>龙</td>";
				$arrCurTimes[$rewardNumCnt+11]++;
			}
			
			if($kjA < $kjC){
				$arrTmpTD[$rewardNumCnt+13] = "<td class='bgkai05'>虎</td>";
				$arrCurTimes[$rewardNumCnt+12]++;
			}
			
			if($kjA == $kjC){
				$arrTmpTD[$rewardNumCnt+14] = "<td class='bgkai05'>和</td>";
				$arrCurTimes[$rewardNumCnt+13]++;
			}
			
			//写进中奖号
			$num = $rewardNumCnt / 4;
			$index = $kjNum+2-$step;
			$seq = $kjNum - $step;
			
			$arrCurTimes[$index-1]++;
			if($seq < $num + 3 || $seq > 3 * $num - 4 )
				$arrTmpTD[$index] = "<td class='bgnum'><em class='final'><i>{$kjNum}</i></em></td>";
			else
				$arrTmpTD[$index] = "<td><em class='final'><i>{$kjNum}</i></em></td>";

			
			//大小
			$is_max = 0;
			$num = $rewardNumCnt / 2;
			if($kjNum-$step >= $num)
			{
				$is_max = 1;
				$arrTmpTD[$rewardNumCnt+4] = "<td class='bgkai05'>大</td>";
				$arrCurTimes[$rewardNumCnt+3]++;
				if($kjNum >= 22){
					$arrTmpTD[$rewardNumCnt+11] = "<td class='bgkai05'>极大</td>";
					$arrCurTimes[$rewardNumCnt+10]++;
				}
			}
			else
			{
				$arrTmpTD[$rewardNumCnt+5] = "<td class='bgkai06'>小</td>";
				$arrCurTimes[$rewardNumCnt+4]++;
				if($kjNum <= 5){
					$arrTmpTD[$rewardNumCnt+10] = "<td class='bgkai05'>极小</td>";
					$arrCurTimes[$rewardNumCnt+9]++;
				}
			}
			
			//单双
			if($kjNum % 2 != 0)
			{
				$arrTmpTD[$rewardNumCnt+2] = "<td class='bgkai01'>单</td>";
				$arrCurTimes[$rewardNumCnt+1]++;
				if($is_max){
					$arrTmpTD[$rewardNumCnt+8] = "<td class='bgkai01'>大单</td>";
					$arrCurTimes[$rewardNumCnt+7]++;
				}else{
					$arrTmpTD[$rewardNumCnt+6] = "<td class='bgkai01'>小单</td>";
					$arrCurTimes[$rewardNumCnt+5]++;
				}
			}
			else
			{
				$arrTmpTD[$rewardNumCnt+3] = "<td class='bgkai02'>双</td>";
				$arrCurTimes[$rewardNumCnt+2]++;
				if($is_max){
					$arrTmpTD[$rewardNumCnt+9] = "<td class='bgkai01'>大双</td>";
					$arrCurTimes[$rewardNumCnt+8]++;
				}else{
					$arrTmpTD[$rewardNumCnt+7] = "<td class='bgkai01'>小双</td>";
					$arrCurTimes[$rewardNumCnt+6]++;
				}
			}
			

			//写进表
			$secondTableBody .= "\t\t\t<tr>\r\n";
			$secondTableBody .= implode("\r\n",$arrTmpTD) . "\r\n";
			$secondTableBody .= "\t\t\t</tr>\r\n";
		
		}
    	
    	
		//表格数据
		$divTable = "<div class='table'>\r\n";
		$divTable .= "\t<table class='table_list'  cellspacing='0px'>\r\n";
		//第一个tbody
		$divTable .= "\t\t<tbody>\r\n";
		//头
		$divTable .= "\t\t\t<tr bgcolor='#fbfbfb'>\r\n";
		$divTable .= "\t\t\t\t<th colspan='". ($rewardNumCnt+15) ."'>走势图 <select id='sltNum'>\r\n";
		for($i = 1; $i <= 5; $i++)
		{
			$theRecCnt = $i * 100;
			if($theRecCnt == $numRec)
				$divTable .= "\t\t\t\t\t<option value={$theRecCnt} selected='selected'>最新{$theRecCnt}期</option>\r\n";
			else
				$divTable .= "\t\t\t\t\t<option value={$theRecCnt}>最新{$theRecCnt}期</option>\r\n";
		} 
		$divTable .= "\t\t\t\t</select></th>\r\n";
		$divTable .= "\t\t\t</tr>\r\n";
		//实际次数
		$divTable .= "\t\t\t<tr class='timeh'>\r\n"; 
		for($i = 0; $i < count($arrCurTimes); $i++)
		{
			if($i == 0)
				$divTable .= "\t\t\t<th colspan=2><b class='black777'>{$arrCurTimes[$i]}</b></th>\r\n";
			else
				$divTable .= "\t\t\t<th width=\"22\">{$arrCurTimes[$i]}</th>\r\n";
		}
		$divTable .= "\t\t\t</tr>\r\n";
		
		//标题
		$divTable .= "\t\t\t<tr class='font_color_2' bgcolor='#e3f0ff'>\r\n"; 
		$divTable .= "\t\t\t" . implode("\r\n",$arrTH);
		$divTable .= "\t\t\t</tr>\r\n";
		$divTable .= "\t\t\t</tr>\r\n";
		$divTable .= "\t\t</tbody>\r\n";
		
		
		
		//第二个tbody
		$divTable .= "\t\t<tbody>\r\n";
		
		$divTable .= $secondTableBody;
		
		$divTable .= "\t\t</tbody>\r\n";
		$divTable .= "\t</table>";
		$divTable .= "</div>\r\n";
		
		return $divTable;
    }
    
    function GetTableContentSC($act,$numRec){
    	global $db;
    	$tablegame = GetGameTableName($act,"game");
    	
    	$arrTH = array();
    	$arrTH[0] = "<th width='90'>期号</th>";
    	$arrTH[1] = "<th width='120'>时间</th>";
    	$arrTH[2] = "<th width='250'>开奖号码</th>";
    	$arrTH[3] = "<th width='150' colspan='3'>冠亚</th>";
    	$arrTH[4] = "<th width='300' colspan='5'>
    			<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"longhu\">
    					<tbody>
    					<tr>  
    						<td colspan=\"5\">龙虎</td>
    					</tr>
    					<tr class=\"longhunum\">
    						<td>1</td><td>2</td><td>3</td><td>4</td><td>5</td>
    					</tr>
    					</tbody>
    			</table></th>";
		
		
		//取数据
		$sql = "SELECT id,kgtime,kgjg,kgNo FROM {$tablegame} WHERE kj = 1 ORDER BY kgtime DESC LIMIT {$numRec}";
		$result = $db->query($sql);
		$secondTableBody = "";
		while($rs = $db->fetch_array($result))
		{
			$arrTmpTD = array();
			$arrTmpTD[0] = "<td class='tdbg3'>" . $rs['id'] . "</td>";
			$arrTmpTD[1] = "<td class='black777' style='color:#000;'>" . date("m-d H:i",strtotime($rs['kgtime'])) . "</td>";
			//中奖号
			$arrKJResult = explode("|",$rs["kgNo"]);
			if(count($arrKJResult) == 10){
				$tmpStr = "";
				foreach($arrKJResult as $idx=>$kjNum){
					$kjNum = substr("0".$kjNum , -2);
					if($idx >= 2)
						$tmpStr .= "<em class=\"light{$kjNum}\"></em>";
					else 
						$tmpStr .= "<em class=\"regular{$kjNum}\"></em>";
				}
				$arrTmpTD[2] = "<td class=\"regular\">{$tmpStr}</td>";
			}
			
			$arrKJResult2 = explode("|",$rs["kgjg"]);
			if(count($arrKJResult2) == 4){
				$total = $arrKJResult2[0] + $arrKJResult2[1];
				$arrTmpTD[3] = "<td class=\"he\">{$total}</td>";
				
				if($total % 2 == 0)
					$arrTmpTD[4] = "<td class=\"cs1\">双</td>";
				else 
					$arrTmpTD[4] = "<td class=\"cs2\">单</td>";
				
				if($total >= 12)
					$arrTmpTD[5] = "<td class=\"cs1\">大</td>";
				else 
					$arrTmpTD[5] = "<td class=\"cs2\">小</td>";
			}
			
			if(count($arrKJResult) == 10){
				if($arrKJResult[0] > $arrKJResult[9])
					$arrTmpTD[6] = "<td class=\"cs1\">龙</td>";
				else 
					$arrTmpTD[6] = "<td class=\"cs2\">虎</td>";
				
				if($arrKJResult[1] > $arrKJResult[8])
					$arrTmpTD[7] = "<td class=\"cs1\">龙</td>";
				else
					$arrTmpTD[7] = "<td class=\"cs2\">虎</td>";
				
				if($arrKJResult[2] > $arrKJResult[7])
					$arrTmpTD[8] = "<td class=\"cs1\">龙</td>";
				else
					$arrTmpTD[8] = "<td class=\"cs2\">虎</td>";
				
				if($arrKJResult[3] > $arrKJResult[6])
					$arrTmpTD[9] = "<td class=\"cs1\">龙</td>";
				else
					$arrTmpTD[9] = "<td class=\"cs2\">虎</td>";
				
				if($arrKJResult[4] > $arrKJResult[5])
					$arrTmpTD[10] = "<td class=\"cs1\">龙</td>";
				else
					$arrTmpTD[10] = "<td class=\"cs2\">虎</td>";
			}else{
				$arrTmpTD[6] = "<td></td>";
				$arrTmpTD[7] = "<td></td>";
				$arrTmpTD[8] = "<td></td>";
				$arrTmpTD[9] = "<td></td>";
				$arrTmpTD[10] = "<td></td>";
			}

			//写进表
			$secondTableBody .= "\t\t\t<tr>\r\n";
			$secondTableBody .= implode("\r\n",$arrTmpTD) . "\r\n";
			$secondTableBody .= "\t\t\t</tr>\r\n";
		
		}
    	
    	
		//表格数据
		$divTable = "<div class='table'>\r\n";
		$divTable .= "\t<table class='table_list'  cellspacing='0px'>\r\n";
		//第一个tbody
		$divTable .= "\t\t<tbody>\r\n";
		//头
		$divTable .= "\t\t\t<tr bgcolor='#fbfbfb'>\r\n";
		$divTable .= "\t\t\t\t<th colspan='11'>走势图 <select id='sltNum'>\r\n";
		for($i = 1; $i <= 5; $i++)
		{
			$theRecCnt = $i * 100;
			if($theRecCnt == $numRec)
				$divTable .= "\t\t\t\t\t<option value={$theRecCnt} selected='selected'>最新{$theRecCnt}期</option>\r\n";
			else
				$divTable .= "\t\t\t\t\t<option value={$theRecCnt}>最新{$theRecCnt}期</option>\r\n";
		} 
		$divTable .= "\t\t\t\t</select></th>\r\n";
		$divTable .= "\t\t\t</tr>\r\n";
		
		//标题
		$divTable .= "\t\t\t<tr class='font_color_2' bgcolor='#e3f0ff'>\r\n"; 
		$divTable .= "\t\t\t" . implode("\r\n",$arrTH);
		$divTable .= "\t\t\t</tr>\r\n";
		$divTable .= "\t\t\t</tr>\r\n";
		$divTable .= "\t\t</tbody>\r\n";
		
		
		
		//第二个tbody
		$divTable .= "\t\t<tbody>\r\n";
		
		$divTable .= $secondTableBody;
		
		$divTable .= "\t\t</tbody>\r\n";
		$divTable .= "\t</table>";
		$divTable .= "</div>\r\n";
		
		return $divTable;
    }
    
    
    function GetTableContentXYNC($act,$numRec){
    	global $db;
    	$tablegame = GetGameTableName($act,"game");
    	 
    	$arrTH = array();
    	$arrTH[0] = "<th width='90'>期号</th>";
    	$arrTH[1] = "<th width='120'>时间</th>";
    	$arrTH[2] = "<th width='350'>开奖号码</th>";
    	$arrTH[3] = "<th width='200' colspan='4'>两面-和</th>";
    	$arrTH[4] = "<th width='200' colspan='4'>
    			<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"longhu\">
    					<tbody>
    					<tr>
    						<td colspan=\"4\">龙虎</td>
    					</tr>
    					<tr class=\"longhunum\">
    						<td>1</td><td>2</td><td>3</td><td>4</td>
    					</tr>
    					</tbody>
    			</table></th>";
    
    
    	//取数据
    	$sql = "SELECT id,kgtime,kgjg,kgNo FROM {$tablegame} WHERE kj = 1 ORDER BY kgtime DESC LIMIT {$numRec}";
    	$result = $db->query($sql);
    	$secondTableBody = "";
    	while($rs = $db->fetch_array($result))
    	{
    		$arrTmpTD = array();
    		$arrTmpTD[0] = "<td class='tdbg3'>" . $rs['id'] . "</td>";
    		$arrTmpTD[1] = "<td class='black777' style='color:#000;'>" . date("m-d H:i",strtotime($rs['kgtime'])) . "</td>";
    		//中奖号
    		$arrKJResult = explode("|",$rs["kgNo"]);
    		if(count($arrKJResult) == 8){
    			$tmpStr = "";
    			foreach($arrKJResult as $kjNum){
    				$kjNum = substr("0".$kjNum , -2);
    				$tmpStr .= "<em class=\"num{$kjNum} number kjnhidden\"></em>";
    			}
    			$arrTmpTD[2] = "<td class=\"regular\">{$tmpStr}</td>";
    		}
    			
    		$arrKJResult2 = explode("|",$rs["kgjg"]);
    		if(count($arrKJResult2) == 9){
    			$total = $arrKJResult2[8];
    			$arrTmpTD[3] = "<td class=\"he\">{$total}</td>";
    
    			if($total % 2 == 0)
    				$arrTmpTD[4] = "<td class=\"cs1\">双</td>";
    			else
    				$arrTmpTD[4] = "<td class=\"cs2\">单</td>";
    
    			if($total >= 85)
    				$arrTmpTD[5] = "<td class=\"cs1\">大</td>";
    			elseif($total <= 83)
    				$arrTmpTD[5] = "<td class=\"cs2\">小</td>";
    			else
    				$arrTmpTD[5] = "<td class=\"cs2\">和</td>";
    			
    			if($total % 10 >= 5)
    				$arrTmpTD[6] = "<td class=\"cs1\">尾大</td>";
    			else
    				$arrTmpTD[6] = "<td class=\"cs2\">尾小</td>";
    		}
    			
    		if(count($arrKJResult) == 8){
    			if($arrKJResult[0] > $arrKJResult[7])
    				$arrTmpTD[7] = "<td class=\"cs1\">龙</td>";
    			else
    				$arrTmpTD[7] = "<td class=\"cs2\">虎</td>";
    
    			if($arrKJResult[1] > $arrKJResult[6])
    				$arrTmpTD[8] = "<td class=\"cs1\">龙</td>";
    			else
    				$arrTmpTD[8] = "<td class=\"cs2\">虎</td>";
    
    			if($arrKJResult[2] > $arrKJResult[5])
    				$arrTmpTD[9] = "<td class=\"cs1\">龙</td>";
    			else
    				$arrTmpTD[9] = "<td class=\"cs2\">虎</td>";
    
    			if($arrKJResult[3] > $arrKJResult[4])
    				$arrTmpTD[10] = "<td class=\"cs1\">龙</td>";
    			else
    				$arrTmpTD[10] = "<td class=\"cs2\">虎</td>";
    		}else{
    			$arrTmpTD[7] = "<td></td>";
    			$arrTmpTD[8] = "<td></td>";
    			$arrTmpTD[9] = "<td></td>";
    			$arrTmpTD[10] = "<td></td>";
    		}
    
    		//写进表
    		$secondTableBody .= "\t\t\t<tr>\r\n";
    		$secondTableBody .= implode("\r\n",$arrTmpTD) . "\r\n";
    		$secondTableBody .= "\t\t\t</tr>\r\n";
    
    	}
    	 
    	 
    	//表格数据
    	$divTable = "<div class='table'>\r\n";
    	$divTable .= "\t<table class='table_list'  cellspacing='0px'>\r\n";
    	//第一个tbody
    	$divTable .= "\t\t<tbody>\r\n";
    	//头
    	$divTable .= "\t\t\t<tr bgcolor='#fbfbfb'>\r\n";
    	$divTable .= "\t\t\t\t<th colspan='11'>走势图 <select id='sltNum'>\r\n";
    	for($i = 1; $i <= 5; $i++)
    	{
    	$theRecCnt = $i * 100;
    	if($theRecCnt == $numRec)
    		$divTable .= "\t\t\t\t\t<option value={$theRecCnt} selected='selected'>最新{$theRecCnt}期</option>\r\n";
    		else
    	$divTable .= "\t\t\t\t\t<option value={$theRecCnt}>最新{$theRecCnt}期</option>\r\n";
    	}
    	$divTable .= "\t\t\t\t</select></th>\r\n";
		$divTable .= "\t\t\t</tr>\r\n";
    
		//标题
    		$divTable .= "\t\t\t<tr class='font_color_2' bgcolor='#e3f0ff'>\r\n";
		$divTable .= "\t\t\t" . implode("\r\n",$arrTH);
		$divTable .= "\t\t\t</tr>\r\n";
		$divTable .= "\t\t\t</tr>\r\n";
		$divTable .= "\t\t</tbody>\r\n";
    
    
    
		//第二个tbody
    		$divTable .= "\t\t<tbody>\r\n";
    
    		$divTable .= $secondTableBody;
    
    		$divTable .= "\t\t</tbody>\r\n";
    		$divTable .= "\t</table>";
    		$divTable .= "</div>\r\n";
    
    		return $divTable;
    }
    
    
    
    
    function GetTableContentSSC($act,$numRec){
    	global $db;
    	$tablegame = GetGameTableName($act,"game");
    
    	$arrTH = array();
    	$arrTH[0] = "<th width='90'>期号</th>";
    	$arrTH[1] = "<th width='120'>时间</th>";
    	$arrTH[2] = "<th width='350'>开奖号码</th>";
    	$arrTH[3] = "<th width='150' colspan='3'>和值</th>";
    	$arrTH[4] = "<th width='50'>龙虎和</th>";
    	$arrTH[5] = "<th width='50'>前三</th>";
    	$arrTH[6] = "<th width='50'>中三</th>";
    	$arrTH[7] = "<th width='50'>后三</th>";
    
    
    	//取数据
    	$sql = "SELECT id,kgtime,kgjg,kgNo FROM {$tablegame} WHERE kj = 1 ORDER BY kgtime DESC LIMIT {$numRec}";
    	$result = $db->query($sql);
    	$secondTableBody = "";
    	while($rs = $db->fetch_array($result))
    	{
    		$arrTmpTD = array();
    		$arrTmpTD[0] = "<td class='tdbg3'>" . $rs['id'] . "</td>";
    		$arrTmpTD[1] = "<td class='black777' style='color:#000;'>" . date("m-d H:i",strtotime($rs['kgtime'])) . "</td>";
    		//中奖号
    		$arrKJResult = explode("|",$rs["kgNo"]);
    		if(count($arrKJResult) == 5){
    			$tmpStr = "";
    			foreach($arrKJResult as $kjNum){
    				$tmpStr .= "<font style=\"font-size:14px;\">{$kjNum}&nbsp;</font>";
    			}
    			$arrTmpTD[2] = "<td class=\"regular\">{$tmpStr}</td>";
    		}
    		 
    		$arrKJResult2 = explode("|",$rs["kgjg"]);
    		if(count($arrKJResult2) == 6){
    			$total = $arrKJResult2[5];
    			$arrTmpTD[3] = "<td class=\"he\">{$total}</td>";
    
    			if($total % 2 == 0)
    				$arrTmpTD[4] = "<td class=\"cs1\">双</td>";
    			else
    				$arrTmpTD[4] = "<td class=\"cs2\">单</td>";
    
    			if($total >= 23)
    				$arrTmpTD[5] = "<td class=\"cs1\">大</td>";
    			else
    				$arrTmpTD[5] = "<td class=\"cs2\">小</td>";
    			 
    			if($arrKJResult2[0] > $arrKJResult2[4])
    				$arrTmpTD[6] = "<td class=\"cs1\">龙</td>";
    			else if($arrKJResult2[0] < $arrKJResult2[4])
    				$arrTmpTD[6] = "<td class=\"cs2\">虎</td>";
    			else
    				$arrTmpTD[6] = "<td class=\"cs3\">和</td>";
    		}
    		 
    		if(count($arrKJResult) == 5){
					$arrTmpTD[7] = "<td class=\"cs1\">";
					$res_a = getGame36Result($arrKJResult[0],$arrKJResult[1],$arrKJResult[2]);
					$arrTmpTD[7] .= show_num_36($res_a);
					$arrTmpTD[7] .= "</td>";
					
					$arrTmpTD[8] = "<td class=\"cs2\">";
					$res_b = getGame36Result($arrKJResult[1],$arrKJResult[2],$arrKJResult[3]);
					$arrTmpTD[8] .= show_num_36($res_b);
					$arrTmpTD[8] .= "</td>";
					
					$arrTmpTD[9] = "<td class=\"cs3\">";
					$res_c = getGame36Result($arrKJResult[2],$arrKJResult[3],$arrKJResult[4]);
					$arrTmpTD[9] .= show_num_36($res_c);
					$arrTmpTD[9] .= "</td>";
    		}else{
    			$arrTmpTD[8] = "<td></td>";
    			$arrTmpTD[9] = "<td></td>";
    			$arrTmpTD[10] = "<td></td>";
    		}
    
    		//写进表
    		$secondTableBody .= "\t\t\t<tr>\r\n";
    		$secondTableBody .= implode("\r\n",$arrTmpTD) . "\r\n";
    		$secondTableBody .= "\t\t\t</tr>\r\n";
    
    	}
    
    
    	//表格数据
    	$divTable = "<div class='table'>\r\n";
    	$divTable .= "\t<table class='table_list'  cellspacing='0px'>\r\n";
    	//第一个tbody
    	$divTable .= "\t\t<tbody>\r\n";
    	//头
    	$divTable .= "\t\t\t<tr bgcolor='#fbfbfb'>\r\n";
    	$divTable .= "\t\t\t\t<th colspan='11'>走势图 <select id='sltNum'>\r\n";
    	for($i = 1; $i <= 5; $i++)
    	{
    	$theRecCnt = $i * 100;
    	if($theRecCnt == $numRec)
    		$divTable .= "\t\t\t\t\t<option value={$theRecCnt} selected='selected'>最新{$theRecCnt}期</option>\r\n";
    	else
    	$divTable .= "\t\t\t\t\t<option value={$theRecCnt}>最新{$theRecCnt}期</option>\r\n";
    	}
    	$divTable .= "\t\t\t\t</select></th>\r\n";
    	$divTable .= "\t\t\t</tr>\r\n";
    
    	//标题
    	$divTable .= "\t\t\t<tr class='font_color_2' bgcolor='#e3f0ff'>\r\n";
		$divTable .= "\t\t\t" . implode("\r\n",$arrTH);
    		$divTable .= "\t\t\t</tr>\r\n";
    		$divTable .= "\t\t\t</tr>\r\n";
		$divTable .= "\t\t</tbody>\r\n";
    
    
    
    		//第二个tbody
    		$divTable .= "\t\t<tbody>\r\n";
    
    		$divTable .= $secondTableBody;
    
    		$divTable .= "\t\t</tbody>\r\n";
    		$divTable .= "\t</table>";
    		$divTable .= "</div>\r\n";
    
    		return $divTable;
    }
    
    
    
    /* 取号码表格
    *
    */
    function GetTableContent($act,$numRec)
    {
    	global $db;
    	$tablegame = GetGameTableName($act,"game");
    	$divWidthStyle = " style='width:100%;' ";
    	$TableWidthStyle = " style='border-collapse:collapse;width:100%;' ";   
		//标准次数
		if(in_array($act,array(0,3,4,8,18,32,33,34,35)))//28游戏
		{
			$arrStdTimes = array(0,0,1,1,2,2,3,4,5,6,6,7,7,8,8,7,7,6,6,5,4,3,2,2,1,1,0,0);
			$rewardNumCnt = 28;
			$reward_num_type = 'game28';
			$step = 0;
			$divWidthStyle = " style='width:1200px;' ";
    		$TableWidthStyle = " style='border-collapse:collapse;width:1200px;' ";   
		}
    	else if(in_array($act,array(1,5,9,19,40)))//16游戏
    	{
    		 $arrStdTimes = array(0,1,3,5,7,10,12,12,12,12,10,7,5,3,1,0); 
    		 $rewardNumCnt = 16; 
    		 $reward_num_type = 'game16';
    		 $step = 3;
		}
    	else if(in_array($act,array(2,10,20,38,39)))//11游戏 
    	{
    		 $arrStdTimes = array(2,5,8,11,14,16,14,11,8,5,2);
    		 $rewardNumCnt = 11;
    		 $reward_num_type = 'game11';
    		 $step = 2;
		}   
    	else if(in_array($act,array(6,7,15,43,46)))//10游戏 冠军游戏
    	{
    		$arrStdTimes = array(10,10,10,10,10,10,10,10,10,10);
    		$rewardNumCnt = 10;
    		$reward_num_type = 'game10';
    		$step = 1;
		}
		else if (in_array($act,array(14,22,44)))	// 22游戏
		{
			$arrStdTimes = array(0,0,1,1,2,2,3,4,5,6,6,6,6,5,4,3,2,2,1,1,0,0);
			$rewardNumCnt = 22;
			$reward_num_type = 'game22';
			$step = 6;
		}
		else if (in_array($act,array(11,12,13,21,23)))	//36游戏
		{
			$arrStdTimes = array(2,3,5,3,2);
			$rewardNumCnt = 5;
			$reward_num_type = 'game36';
			$step = 1;
		}
		else if (in_array($act,array(16,47)))	// PK龙虎 飞艇龙虎
		{
			$arrStdTimes = array(5,5);
			$rewardNumCnt = 2;
			$reward_num_type = 'gamelh';
			$step = 1;
		}
		else if (in_array($act,array(17,24,45))) //冠亚军
		{
			$arrStdTimes = array(0,1,3,5,7,10,12,12,13,12,12,10,7,5,3,1,0);
			$rewardNumCnt = 17;
			$reward_num_type = 'gamegyj';
			$step = 3;
		}

		//实际次数
		$arrCurTimes = array();
		$arrCurTimes[0] = "实际次数";
		for($i = 0; $i < $rewardNumCnt+7; $i++)
		{
			$arrCurTimes[$i+1] = 0;
		}
		$arrCurTimes[$rewardNumCnt+7] = "尾数";
		$arrCurTimes[$rewardNumCnt+8] = "余数"; 
		
		//表格主体
		$arrTH = array();
		$arrTD = array();
    	$arrTH[0] = "<th width='50'>期号</th>";
    	$arrTH[1] = "<th width='60'>时间</th>";	 
    	$arrTD[0] = "";
    	$arrTD[1] = "";
    	$sql = "SELECT GROUP_CONCAT(num SEPARATOR '|') AS strnum FROM gameodds WHERE game_type = '{$reward_num_type}' ORDER BY num";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
    		$arrStdNums = explode("|",$rs['strnum']);
    	}
    	else
    	{
			return "无法取得标准赔率";
    	}
    	for($i = 0; $i < $rewardNumCnt; $i++)
    	{
			$arrTH[$i+2] = "<th width='22'>" . $arrStdNums[$i] . "</th>";
			$num = $rewardNumCnt / 4;
			if($rewardNumCnt == 10 || $rewardNumCnt == 7)
			{
				if($i < $num || $i >= 3 * $num - 1)
					$arrTD[$i+2] = "<td class='bgnum'></td>";
				else
					$arrTD[$i+2] = "<td></td>";
			}
			else if($rewardNumCnt == 16)
			{
				if($i <= $num || $i >= 3 * $num - 1)
					$arrTD[$i+2] = "<td class='bgnum'></td>";
				else
					$arrTD[$i+2] = "<td></td>";
			}
			else if($rewardNumCnt == 11)
			{
				if($i <= $num || $i > 2 * $num + 2)
					$arrTD[$i+2] = "<td class='bgnum'></td>";
				else
					$arrTD[$i+2] = "<td></td>";
			}
			else if($rewardNumCnt == 22)
			{
				if($i <= $num + 1 || $i >= 3 * $num - 2)
					$arrTD[$i+2] = "<td class='bgnum'></td>";
				else
					$arrTD[$i+2] = "<td></td>";
			}
			else if ($rewardNumCnt == 17)		//冠亚军
			{
				//if($i <= $num + 1 || $i >= 3 * $num - 2)
				if($i < $num || $i > 3 * $num - 1)
					$arrTD[$i+2] = "<td class='bgnum'></td>";
				else
					$arrTD[$i+2] = "<td></td>";
			}
			else if ($rewardNumCnt == 5)
			{
				if($i <= $num + 1 || $i >= 3 * $num - 2)
					$arrTD[$i+2] = "<td class='bgnum'></td>";
				else
					$arrTD[$i+2] = "<td></td>";
			}
			else if ($rewardNumCnt == 2)
			{
				if($i <= $num + 1 || $i >= 3 * $num - 2)
					$arrTD[$i+2] = "<td class='bgnum'></td>";
				else
					$arrTD[$i+2] = "<td></td>";
			}
			else
			{
				if($i < $num + 3 || $i > 3 * $num - 4)
					$arrTD[$i+2] = "<td class='bgnum'></td>";
				else
					$arrTD[$i+2] = "<td></td>";
			}
    	}
    	$arrTH[$rewardNumCnt+2] = "<th width='22'>单</th>";
    	$arrTH[$rewardNumCnt+3] = "<th width='22'>双</th>";
    	$arrTH[$rewardNumCnt+4] = "<th width='22'>中</th>";
    	$arrTH[$rewardNumCnt+5] = "<th width='22'>边</th>";
    	$arrTH[$rewardNumCnt+6] = "<th width='22'>大</th>";
    	$arrTH[$rewardNumCnt+7] = "<th width='22'>小</th>";
    	$arrTH[$rewardNumCnt+8] = "<th width='22'>大</th>";
    	$arrTH[$rewardNumCnt+9] = "<th width='22'>小</th>";
    	$arrTH[$rewardNumCnt+10] = "<th width='22'>3/</th>";
    	$arrTH[$rewardNumCnt+11] = "<th width='22'>4/</th>";
    	$arrTH[$rewardNumCnt+12] = "<th width='22'>5/</th>";
    	
    	$arrTD[$rewardNumCnt+2] = "<td>单</td>";
    	$arrTD[$rewardNumCnt+3] = "<td>双</td>";
    	$arrTD[$rewardNumCnt+4] = "<td>中</td>";
    	$arrTD[$rewardNumCnt+5] = "<td>边</td>";
    	$arrTD[$rewardNumCnt+6] = "<td>大</td>";
    	$arrTD[$rewardNumCnt+7] = "<td>小</td>";
    	$arrTD[$rewardNumCnt+8] = "<td>大</td>";
    	$arrTD[$rewardNumCnt+9] = "<td>小</td>";
    	$arrTD[$rewardNumCnt+10] = "<td></td>";
    	$arrTD[$rewardNumCnt+11] = "<td></td>";
    	$arrTD[$rewardNumCnt+12] = "<td></td>";
    	
    	//取数据
    	$sql = "SELECT id,kgtime,kgjg FROM {$tablegame} WHERE kj = 1 ORDER BY kgtime DESC LIMIT {$numRec}";
    	$result = $db->query($sql);
    	//WriteLog(implode("|",$arrTD));
    	$secondTableBody = "";
    	while($rs = $db->fetch_array($result))
    	{ 
    		
			$arrTmpTD = $arrTD;
			$arrTmpTD[0] = "<td class='tdbg3'>" . $rs['id'] . "</td>";
			$arrTmpTD[1] = "<td class='black777' style='color:#000;'>" . date("m-d H:i",strtotime($rs['kgtime'])) . "</td>";
			//中奖号
			$arrkjNum = explode("|",$rs['kgjg']);
			$kjNum = $arrkjNum[count($arrkjNum)-1];
			//写进中奖号
			$num = $rewardNumCnt / 4;
			$index = $kjNum+2-$step;
			$seq = $kjNum - $step;
			if($rewardNumCnt == 10 || $rewardNumCnt == 7)
			{
				$arrCurTimes[$index-1]++;
				if($seq < $num || $seq >= 3 * $num - 1 )
					$arrTmpTD[$index] = "<td class='bgnum'><em class='final'><i>{$kjNum}</i></em></td>";
				else
					$arrTmpTD[$index] = "<td><em class='final'><i>{$kjNum}</i></em></td>";
			} 
			else if($rewardNumCnt == 16)
			{
				$arrCurTimes[$index-1]++;
				if($seq <= $num || $seq >= 3 * $num - 1 )
					$arrTmpTD[$index] = "<td class='bgnum'><em class='final'><i>{$kjNum}</i></em></td>";
				else
					$arrTmpTD[$index] = "<td><em class='final'><i>{$kjNum}</i></em></td>"; 
			}
			else if($rewardNumCnt == 11)
			{
				$arrCurTimes[$index-1]++;
				if($seq <= $num || $seq > 2 * $num + 2 )
					$arrTmpTD[$index] = "<td class='bgnum'><em class='final'><i>{$kjNum}</i></em></td>";
				else
					$arrTmpTD[$index] = "<td><em class='final'><i>{$kjNum}</i></em></td>"; 
			}
			else if($rewardNumCnt == 22)
			{
				$arrCurTimes[$index-1]++;
				if($seq <= $num + 1 || $seq >= 3 * $num - 2 )
					$arrTmpTD[$index] = "<td class='bgnum'><em class='final'><i>{$kjNum}</i></em></td>";
				else
					$arrTmpTD[$index] = "<td><em class='final'><i>{$kjNum}</i></em></td>"; 
			}
			else if($rewardNumCnt == 17)	//	冠亚军
			{
				$arrCurTimes[$index-1]++;
				//if($seq <= $num + 1 || $seq >= 3 * $num - 2 )
				if($seq < $num || $seq > 3 * $num - 1 )
					$arrTmpTD[$index] = "<td class='bgnum'><em class='final'><i>{$kjNum}</i></em></td>";
				else
					$arrTmpTD[$index] = "<td><em class='final'><i>{$kjNum}</i></em></td>";
			}
			else if($rewardNumCnt == 5)
			{
				$arrCurTimes[$index-1]++;
				if($seq <= $num + 1 || $seq >= 3 * $num - 2 )
					if ($kjNum == 1){
						$arrTmpTD[$index] = "<td class='bgnum'><em class='final'><i>豹</i></em></td>";
					}else if ($kjNum == 2){
						$arrTmpTD[$index] = "<td class='bgnum'><em class='final'><i>对</i></em></td>";
					}else if ($kjNum == 3){
						$arrTmpTD[$index] = "<td class='bgnum'><em class='final'><i>顺</i></em></td>";
					}else if ($kjNum == 4){
						$arrTmpTD[$index] = "<td class='bgnum'><em class='final'><i>半</i></em></td>";
					}else if ($kjNum == 5){
						$arrTmpTD[$index] = "<td class='bgnum'><em class='final'><i>杂</i></em></td>";
					}

				//else
					//$arrTmpTD[$index] = "<td><em class='final'><i>{$kjNum}</i></em></td>";
			}
			else if ($rewardNumCnt == 2)
			{
				$arrCurTimes[$index-1]++;
				if($seq <= $num + 1 || $seq >= 3 * $num - 2 )
					if ($kjNum == 1){
						$arrTmpTD[$index] = "<td class='bgnum'><em class='final'><i>龙</i></em></td>";
					}else{
						$arrTmpTD[$index] = "<td class='bgnum'><em class='final'><i>虎</i></em></td>";
					}

				//else
					//$arrTmpTD[$index] = "<td><em class='final'><i>{$kjNum}</i></em></td>";
			}
			else
			{
				$arrCurTimes[$index-1]++;
				if($seq < $num + 3 || $seq > 3 * $num - 4 )
					$arrTmpTD[$index] = "<td class='bgnum'><em class='final'><i>{$kjNum}</i></em></td>";
				else
					$arrTmpTD[$index] = "<td><em class='final'><i>{$kjNum}</i></em></td>"; 
			}
			
			//单双。。。等
			if($kjNum % 2 != 0)
			{
				$arrTmpTD[$rewardNumCnt+2] = "<td class='bgkai01'>单</td>";
				$arrCurTimes[$rewardNumCnt+1]++;
			}
			else
			{
				$arrTmpTD[$rewardNumCnt+3] = "<td class='bgkai02'>双</td>";
				$arrCurTimes[$rewardNumCnt+2]++;
			}
			//中
			$num = $rewardNumCnt / 3;
			if($rewardNumCnt == 16)
			{  
				if($kjNum-$step >= $num - 1 & $kjNum-$step < 2 * $num)
				{
					$arrTmpTD[$rewardNumCnt+4] = "<td class='bgkai03'>中</td>";
					$arrCurTimes[$rewardNumCnt+3]++;
				}
			}
			else if($rewardNumCnt == 10 || $rewardNumCnt == 7)
			{ 
				if($kjNum-$step >= $num - 1 & $kjNum-$step <= 2 * $num)
				{
					$arrTmpTD[$rewardNumCnt+4] = "<td class='bgkai03'>中</td>";
					$arrCurTimes[$rewardNumCnt+3]++;
				}
			}
			else if($rewardNumCnt == 22)
			{ 
				if($kjNum-$step > $num - 1 && $kjNum-$step < 2 * $num)
				{
					$arrTmpTD[$rewardNumCnt+4] = "<td class='bgkai03'>中</td>";
					$arrCurTimes[$rewardNumCnt+3]++;
				}
			}
			else if($rewardNumCnt == 11)
			{     
				if($kjNum-$step > $num - 1 && $kjNum-$step < 2 * $num)
				{
					$arrTmpTD[$rewardNumCnt+4] = "<td class='bgkai03'>中</td>";
					$arrCurTimes[$rewardNumCnt+3]++;
				}
			}
			else if($rewardNumCnt == 17)//冠亚军
			{
				if($kjNum-$step > $num - 1 && $kjNum-$step < 2 * $num)
				{
					$arrTmpTD[$rewardNumCnt+4] = "<td class='bgkai03'>中</td>";
					$arrCurTimes[$rewardNumCnt+3]++;
				}
			}
			else
			{ 
				if($kjNum-$step >= $num && $kjNum-$step < 2 * $num - 1)
				{
					$arrTmpTD[$rewardNumCnt+4] = "<td class='bgkai03'>中</td>";
					$arrCurTimes[$rewardNumCnt+3]++;
				}
			}
			//边
			$num = $rewardNumCnt / 4;
			if($rewardNumCnt == 10 || $rewardNumCnt == 7)
			{
				if($kjNum-$step < $num || $kjNum-$step >= 3 * $num - 1)
				{
					$arrTmpTD[$rewardNumCnt+5] = "<td class='bgkai04'>边</td>";
					$arrCurTimes[$rewardNumCnt+4]++;
				}
			}
			else if($rewardNumCnt == 16)
			{
				if($kjNum-$step <= $num || $kjNum-$step >= 3 * $num - 1)
				{
					$arrTmpTD[$rewardNumCnt+5] = "<td class='bgkai04'>边</td>";
					$arrCurTimes[$rewardNumCnt+4]++;
				}
			}
			else if($rewardNumCnt == 11)
			{  
				if(($kjNum-$step <= $num || $kjNum-$step > 2 * $num + 1) && $kjNum!=9)
				{
					$arrTmpTD[$rewardNumCnt+5] = "<td class='bgkai04'>边</td>";
					$arrCurTimes[$rewardNumCnt+4]++;
				}
			}
			else if($rewardNumCnt == 17)//冠亚军
			{
				if($kjNum-$step <= $num || $kjNum-$step >= 3 * $num - 1)
				{
					$arrTmpTD[$rewardNumCnt+5] = "<td class='bgkai04'>边</td>";
					$arrCurTimes[$rewardNumCnt+4]++;
				}
			}
			else if($rewardNumCnt == 22)
			{  
				if($kjNum-$step <= $num + 1 || $kjNum-$step >= 3 * $num - 2)
				{
					$arrTmpTD[$rewardNumCnt+5] = "<td class='bgkai04'>边</td>";
					$arrCurTimes[$rewardNumCnt+4]++;  
				}
			}
			elseif($rewardNumCnt!=2)
			{    
				if($kjNum-$step < $num + 3 || $kjNum-$step > 3 * $num - 4)
				{
					$arrTmpTD[$rewardNumCnt+5] = "<td class='bgkai04'>边</td>";
					$arrCurTimes[$rewardNumCnt+4]++;
				}
			}
			//大
			$num = $rewardNumCnt / 2;
			if($rewardNumCnt == 11)
			{   
				if($kjNum-$step >= $num - 1)
				{
					$arrTmpTD[$rewardNumCnt+6] = "<td class='bgkai05'>大</td>";
					$arrCurTimes[$rewardNumCnt+5]++;
				}
			}
			else if($rewardNumCnt == 7)
			{    
				if($kjNum-$step >= $num - 1)
				{
					$arrTmpTD[$rewardNumCnt+6] = "<td class='bgkai05'>大</td>";
					$arrCurTimes[$rewardNumCnt+5]++;
				}
			}
			else
			{  
				if($kjNum-$step >= $num)
				{
					$arrTmpTD[$rewardNumCnt+6] = "<td class='bgkai05'>大</td>";
					$arrCurTimes[$rewardNumCnt+5]++;
				}
			}
			//小
			$num = $rewardNumCnt / 2;
			if($rewardNumCnt == 11)
			{     
				if($kjNum-$step < $num - 1)
				{
					$arrTmpTD[$rewardNumCnt+7] = "<td class='bgkai06'>小</td>";
					$arrCurTimes[$rewardNumCnt+6]++;
				}
			}
			else if($rewardNumCnt == 7)
			{   
				if($kjNum-$step < $num - 1)
				{
					$arrTmpTD[$rewardNumCnt+7] = "<td class='bgkai06'>小</td>";
					$arrCurTimes[$rewardNumCnt+6]++; 
				}
			}
			else
			{   
				if($kjNum-$step < $num)
				{
					$arrTmpTD[$rewardNumCnt+7] = "<td class='bgkai06'>小</td>";
					$arrCurTimes[$rewardNumCnt+6]++;
				}
			}
			//尾数大
			if($kjNum % 10 >= 5)
				$arrTmpTD[$rewardNumCnt+8] = "<td class='bgkai07'>大</td>";
			//尾数小
			if($kjNum % 10 < 5)
				$arrTmpTD[$rewardNumCnt+9] = "<td class='bgkai08'>小</td>";
			//余数3
			$arrTmpTD[$rewardNumCnt+10] = "<td class='black333'>". ($kjNum % 3) ."</td>";
			//余数4
			$arrTmpTD[$rewardNumCnt+11] = "<td class='black333'>". ($kjNum % 4) ."</td>";
			//余数5
			$arrTmpTD[$rewardNumCnt+12] = "<td class='black333'>". ($kjNum % 5) ."</td>";
			//写进表
			$secondTableBody .= "\t\t\t<tr>\r\n";
			$secondTableBody .= implode("\r\n",$arrTmpTD) . "\r\n";
			$secondTableBody .= "\t\t\t</tr>\r\n";
			
    	}
    	
		//表格数据
		//$divTable = "<div class='table table table-striped table-bordered table-hover' {$divWidthStyle}>\r\n";
		//$divTable .= "\t<table class='table_list' cellspacing='0px' {$TableWidthStyle}>\r\n";
		$divTable = "<div class='table'>\r\n";
		$divTable .= "\t<table class='table_list'  cellspacing='0px'>\r\n";
		//第一个tbody
		$divTable .= "\t\t<tbody>\r\n";
		//头
		$divTable .= "\t\t\t<tr bgcolor='#fbfbfb'>\r\n";
		$divTable .= "\t\t\t\t<th colspan='". ($rewardNumCnt+13) ."'>走势图 <select id='sltNum'>\r\n";
		for($i = 1; $i <= 5; $i++)
		{
			$theRecCnt = $i * 100;
			if($theRecCnt == $numRec)
				$divTable .= "\t\t\t\t\t<option value={$theRecCnt} selected='selected'>最新{$theRecCnt}期</option>\r\n";
			else
				$divTable .= "\t\t\t\t\t<option value={$theRecCnt}>最新{$theRecCnt}期</option>\r\n";
		} 
		$divTable .= "\t\t\t\t</select></th>\r\n";
		$divTable .= "\t\t\t</tr>\r\n";
		//标准次数
		$divTable .= "\t\t\t<tr class='timeh'>";
		$divTable .= "\t\t\t\t<th colspan='2'><b class='black777'>标准次数</b></th>";
		foreach($arrStdTimes as $v)
		{
			$divTable .= "\t\t\t\t<th>{$v}</th>\r\n";
		}
		$divTable .= "\t\t\t</tr>\r\n";
		//实际次数
		$divTable .= "\t\t\t<tr class='timeh'>\r\n"; 
		for($i = 0; $i < count($arrCurTimes); $i++)
		{
			if($i == 0 || $i == count($arrCurTimes)-2)
				$divTable .= "\t\t\t<th colspan=2><b class='black777'>{$arrCurTimes[$i]}</b></th>\r\n";
			else if($i == count($arrCurTimes)-1)
				$divTable .= "\t\t\t<th colspan=3><b class='black777'>{$arrCurTimes[$i]}</b></th>\r\n";
			else
				$divTable .= "\t\t\t<th>{$arrCurTimes[$i]}</th>\r\n";
		}
		$divTable .= "\t\t\t</tr>\r\n";
		//标题
		$divTable .= "\t\t\t<tr class='font_color_2' bgcolor='#e3f0ff'>\r\n"; 
		$divTable .= "\t\t\t" . implode("\r\n",$arrTH);
		$divTable .= "\t\t\t</tr>\r\n";
		$divTable .= "\t\t\t</tr>\r\n";
		
		$divTable .= "\t\t</tbody>\r\n";
		//第二个tbody
		$divTable .= "\t\t<tbody>\r\n";
		
		$divTable .= $secondTableBody;
		
		$divTable .= "\t\t</tbody>\r\n";
		$divTable .= "\t</table>";
		$divTable .= "</div>\r\n";
		
		return $divTable;
    }
    
    /* 取得JS
    * 
    */
    function GetJSContent($act,$sid)
    {
		$js = "<script type=\"text/javascript\">";
		$js .= "
			$(document).ready(function(){
				$('#sltNum').change(function(){
					var v = $('#sltNum').val();
					if(v != ''){
						getContent('strend.php?act={$act}&sid={$sid}&num='+ v);
					}
				});
			});  
		";
		
		$js .= "</script>\r\n";
		return $js;
    }

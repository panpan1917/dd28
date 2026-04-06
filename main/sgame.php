<?php
    include_once("inc/conn.php");
    include_once("inc/function.php");
    
    if(!isset($_SESSION['usersid'])) {
		echo "您还没登录，请先去<a href='/login.php'>登录</a>!";
		exit;
	}
	
	
	$act = intval($_GET['act']);
	//更新银行
	RefreshPoints();
	//返回数据
	GetGameData();
	
	
	
	
    
    /* 取得游戏刷新数据
    * 
    */
    function GetGameData()
    {
    	global $db;
    	
		//取得子菜单
		$act = intval($_GET['act']);
		$sid = intval($_GET['sid']);
		$page = isset($_GET['page'])?$_GET['page']:1;
		$page =intval($page);
		$pagesize = 20;
		
		$arrCurNoInfo = array('preno'=>'','prekgtime'=>'','game_kj_delay'=>'','game_tz_close'=>'');
		$RetContent = "<div class='Pattern'>\r\n";
		$RetContent .= "\t<div class='Content'>\r\n";
		
		//开奖头部
		$RetContent .= GetHeadContent($act,$sid,$arrCurNoInfo);
		//游戏菜单
		$RetContent .= GetSubMenu($act,$sid);
		$js=GetRewardJS($act,$arrCurNoInfo,"game");
		//取表格
		$RetContent .= GetTableContent($act,$page,$pagesize,$arrCurNoInfo);
		
		$RetContent .= "\t</div>\r\n";
		$RetContent .= "</div>\r\n";
		//js 定义
		$RetContent .= $js;
		$RetContent .= GetJSContent($act);
		echo $RetContent;
		exit;
    }
    
    function GetTableContent($act,$page,$pagesize,$arrnoinfo)
	{
		global $db;
		$tablegame = GetGameTableName($act,"game");
		$tablegametz = GetGameTableName($act,"users_tz");
		$tablegamekg = GetGameTableName($act,"kg_users_tz");
		$MinuteAdd = 20; //北京数据源
		if(in_array($act,[0,1,2,15,22,23,24])) //急速
			$MinuteAdd = "4";
		else if(in_array($act,[8,9,10,13,27,28,35]))//加拿大源
			$MinuteAdd = "13";
		else if(in_array($act,[18,19,20,21,30,31,34]))//韩国源
			$MinuteAdd = "6";
		else if(in_array($act,[36]))//幸运农场
			$MinuteAdd = "40";
		else if(in_array($act,[37])){//重庆时时彩
			if(date("G")>=10 && date("G")<22)
			$MinuteAdd = "40";
		}
		
		$sql = "SELECT count(id) FROM {$tablegame} WHERE kgtime < DATE_ADD(NOW(),INTERVAL {$MinuteAdd} MINUTE) ORDER BY id desc";
		$TotalRecCount = $db->GetRecordCount($sql);
		$sql = "
		 SELECT a.id,a.kgtime,now() as nowtime,a.kj,a.kgjg,a.kgNo,a.tzpoints,a.tznum,a.zjrnum,IFNULL(b.tzpoints,0) AS ptzpoints,IFNULL(b.hdpoints,0) AS phdpoints
		 FROM 
		 (
			 SELECT id,kgtime,kj,kgjg,kgNo,tzpoints,(zdtz+zdtz_r+sdtz) as tznum,zjrnum
			 FROM {$tablegame} 
			 WHERE  kgtime < DATE_ADD(NOW(),INTERVAL {$MinuteAdd} MINUTE) 
		 ) AS a
		 LEFT OUTER JOIN
		 (
			 SELECT NO,SUM(tzpoints) tzpoints,SUM(hdpoints) hdpoints
			 FROM {$tablegamekg}
			 WHERE uid = '{$_SESSION['usersid']}' 
			 GROUP BY NO
			 UNION
			 SELECT NO,points AS tzpoints,hdpoints 
			 FROM {$tablegametz}
			 WHERE uid = '{$_SESSION['usersid']}'
		 ) AS b
		 ON a.id = b.no
		 ORDER BY id desc ";
		$sql .= GetLimit($page,$pagesize);
		//WriteLog($sql);
		$qihao='';
		$result =  $db->query($sql);
		$divTable = "<div class='table'>\r\n";
		$divTable .= "\t<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;color:#000000;'>\r\n";
		$divTable .= "\t<tbody>\r\n";
		$divTable .= "\t\t<tr>\r\n";
		$divTable .= "\t\t\t<th>期 号</th>\r\n";
		$divTable .= "\t\t\t<th>开奖时间</th>\r\n";
		$divTable .= "\t\t\t<th>开奖结果</th>\r\n";
		
		if(in_array($act,[25,26,27,28,30,31,41,42])){//外围，定位
			$divTable .= "\t\t\t<th>中奖</th>\r\n";
		}else if(in_array($act,[29,36])){//赛车
			$divTable .= "\t\t\t<th colspan='8'>中奖</th>\r\n";
		}else if(in_array($act,[37])){//时时彩
			$divTable .= "\t\t\t<th colspan='7'>中奖</th>\r\n";
		}else if(in_array($act,[32,33,34,35])){//固定
			;
		}else{
			if(!in_array($act , [0,1,2,15,22,23,24])){
				$divTable .= "\t\t\t<th>乐豆总数</th>\r\n";
				$divTable .= "\t\t\t<th>中奖人数</th>\r\n";
			}
		}
		
		$divTable .= "\t\t\t<th width='240'>投注额/中奖额</th>\r\n";
		$divTable .= "\t\t\t<th width='140'>投注</th>\r\n";
		$divTable .= "\t\t</tr>\r\n";
		while(!!$rs=$db->fetch_array($result)){
			$arrTmpKg = explode("|",$rs["kgjg"]);
			$Winner = "{$rs['zjrnum']}/{$rs['tznum']}";
			if($rs['kj'] == 0)
			{
				if( $arrnoinfo['prekgtime'] <= ($arrnoinfo['game_tz_close']) && $rs['id'] == $arrnoinfo['preno'] ) //正在开奖
				{
					//WriteLog($tablegame . "->" . $arrnoinfo['prekgtime']. ":" . $arrnoinfo['game_kj_delay'] . "+" . $arrnoinfo['game_tz_close']);
					$TmpState = "<span class='btn btn-warning btn-block'>开奖中...</span>";
				}
				else
				{
					
					if(DateDiff($rs["kgtime"],$rs["nowtime"],"s") > 0)
					{
						$TmpState = "<a href='javascript:toPress({$rs['id']});'><span class='btn btn-danger btn-block'>立即投注</span></a>";
					}
					else
					{
						
						$TmpState = "<span class='btn btn-warning btn-block'>开奖中...</span>";
					}
				}
				$TmpKaiNum = "";
			}
			else
			{
				if($qihao=='')
				$qihao=$rs['id'];
				if ($rs['zjrnum'] == 0){
					$TmpState = "<a id='state_{$rs['id']}' class='btn btn-default btn-block'>已开奖</a>";
				}else{
					$TmpState = "<a id='state_{$rs['id']}' class='btn btn-default btn-block' href=\"javascript:showrecord('{$rs['id']}','srecdetail.php?act={$act}&no=".$rs['id']."');\">已开奖</a>";
				}

				
				
				if(in_array($act,[0,1,2,3,4,5,8,9,10,11,12,13,18,19,20,21,23,25,26,27,28,30,31,32,33,34,35,38,39,40,41,42]))
				{
					$kjNumberStr = ($arrTmpKg[2] == "-1") ? (show_num($arrTmpKg[0],1)."<i class='hja'></i>".show_num($arrTmpKg[1],1) ) : ( show_num($arrTmpKg[0],1)."<i class='hja'></i>".show_num($arrTmpKg[1],1)."<i class='hja'></i>".show_num($arrTmpKg[2],1)  );
					$kjFinal = $arrTmpKg[3];
					//$value_kj=" = <em class='finals final'><i>{$kjFinal}</i></em>";
					$value_kj="<i class='hdeng'></i>".show_num($arrTmpKg[3],3);
					if(in_array($act,[11,12,13,21,23])) //36游戏
					{
						switch($arrTmpKg[3])
						{
							case 1:
								$kjFinal = "豹";
								$value_kj="<i class='hdeng'></i>".show_num(1,2);
								break;
							case 2:
								$kjFinal = "对";
								$value_kj="<i class='hdeng'></i>".show_num(2,2);
								break;
							case 3:
								$kjFinal = "顺";
								$value_kj="<i class='hdeng'></i>".show_num(3,2);
								break;
							case 4:
								$kjFinal = "半";
								$value_kj="<i class='hdeng'></i>".show_num(4,2);
								break;
							case 5:
								$kjFinal = "杂";
								$value_kj="<i class='hdeng'></i>".show_num(5,2);
								break;
							default:
								$kjFinal = "";
								break;
						}
					}
					//$TmpKaiNum = "{$kjNumberStr} = <em class='finals final'><i>{$kjFinal}</i></em>";
					$TmpKaiNum = "{$kjNumberStr}{$value_kj}";
				}
				else if(in_array($act,[36]))
				{
					$kjNumberStr = "";
					if(count($arrTmpKg) == 9){
						for($i=0;$i<count($arrTmpKg)-1;$i++){
							$num = substr("0" . $arrTmpKg[$i] , -2);
							$kjNumberStr .= "<em class='num{$num} number kjnhidden'></em>";
						}
					}
					
					//$value_kj="<i style='width:20px;'></i>".show_num($arrTmpKg[8],3);
					$TmpKaiNum = "{$kjNumberStr}";//{$value_kj}
				}
				else if(in_array($act,[37]))
				{
					$kjNumberStr = "";
					if(count($arrTmpKg) == 6){
						for($i=0;$i<count($arrTmpKg)-1;$i++){
							$kjNumberStr .= show_num($arrTmpKg[$i],1);
						}
					}
					
					$TmpKaiNum = "{$kjNumberStr}";//{$value_kj}
				}
				else if(in_array($act,[6,7,14,15,16,17,22,24,29,43,44,45,46,47])) //pk类，PK10、PK冠军、PK22、急速10、急速22、急速冠亚军、PK龙虎、PK冠亚军 飞艇10 22 冠军 冠亚军 龙虎
				{
					//取得1-10个开奖结果排列
					$arrKJResult = explode("|",$rs["kgNo"]);
					$TmpKaiNum = "";
					$kjNumPrefix = "";
					for($i = 1; $i <= count($arrKJResult);$i++)
					{
						$kjNumPrefix = "light";
						switch($act)
						{
							case "6": //pk10
								$TmpSuffixNum = $arrTmpKg[0];
								
								if($arrTmpKg[0] == 0) 
									$TmpSuffixNum = 10;
								
								if($i == $TmpSuffixNum)
									$kjNumPrefix = "regular";
								break;
							case "7": //pk冠军
								if($i == 1)
									$kjNumPrefix = "regular";
								break;
							case "14": //pk22
								if($i <= 3)
									$kjNumPrefix = "regular"; 
								break;
							case "15": //急速10
								if($i == 1)
									$kjNumPrefix = "regular";
								break;
							case "16": //pk龙虎
								if($i == 1 || $i == 10)
									$kjNumPrefix = "regular";
								break;
							case "17": //pk冠亚军
								if($i == 1 || $i == 2)
									$kjNumPrefix = "regular";
								break;
							case "22": //急速22
								if($i <= 3)
									$kjNumPrefix = "regular";
									break;
							case "24": //急速冠亚军
								if($i == 1 || $i == 2)
									$kjNumPrefix = "regular";
									break;
							case "29": //PK赛车
								if($i == 1 || $i == 2)
									$kjNumPrefix = "regular";
									break;
									
									
							case "43": //飞艇10
								$TmpSuffixNum = $arrTmpKg[0];
							
								if($arrTmpKg[0] == 0)
									$TmpSuffixNum = 10;
							
								if($i == $TmpSuffixNum)
									$kjNumPrefix = "regular";
								break;
							case "44": //飞艇22
								if($i <= 3)
									$kjNumPrefix = "regular";
									break;
							case "45": //飞艇冠亚军
								if($i == 1 || $i == 2)
									$kjNumPrefix = "regular";
									break;
							case "46": //飞艇冠军
								if($i == 1)
									$kjNumPrefix = "regular";
									break;
							case "47": //飞艇龙虎
								if($i == 1 || $i == 10)
									$kjNumPrefix = "regular";
									break;
											
									
							default:
								break;
						}
						$theKjNumber = substr("0" . $arrKJResult[$i-1],-2); 
						$TmpKaiNum .= "<em class='{$kjNumPrefix}{$theKjNumber}'></em>";
						
					} 
					//取得最后开奖结果 
					//$LastKGResult = "<em class='final'><i>{$arrTmpKg[3]}</i></em>";		急速10后面的开奖结果.
					//$LastKGResult = show_num($arrTmpKg[3],3);
					
					if(in_array($act , [15,22,24,6,14,7,17,43,44,45,46])){//急速10 急速22 急速冠亚军 PK10 PK22 PK冠军 PK冠亚军  飞艇10 22 冠军 冠亚军
						$LastKGResult = " <i class='mh m{$arrTmpKg[3]}'></i>";
					}

					if(in_array($act , [16,47])) //pk龙虎单独处理   飞艇龙虎
					{
						if($arrTmpKg[3] == 1) //龙
						{
							//$LastKGResult = "<em class='final'><i>龙</i></em>";
							$LastKGResult = "<i class='lh n1'></i>";
						}
						else //虎
						{
							//$LastKGResult = "<em class='final'><i>虎</i></em>";
							$LastKGResult = "<i class='lh n2'></i>";
						} 
					}
					$TmpKaiNum .= $LastKGResult;
				}
			}
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>{$rs['id']}</td>\r\n";
			$divTable .= "\t\t\t<td>" . date("m-d H:i:s",strtotime($rs["kgtime"])) . "</td>\r\n";
			
			//if ($rs['kj'] == 0 || $act ==15 || $act == 2 || $act == 1 || $act == 0 || $act == 6 || $act == 14 || $act == 7 || $act == 16 || $act == 17 || $act == 22 || $act == 23 || $act == 24 || $act == 29 || $act == 36 || $act == 37){
			if ($rs['kj'] == 0 || in_array($act , [0,1,2,6,7,14,15,16,17,22,23,24,29,36,37,43,44,45,46,47])){//未开奖或是急速类或PK，飞艇类
				$divTable .= "\t\t\t<td class='regular' valign='middle'>{$TmpKaiNum} </td>\r\n";
			}else{
				$divTable .= "\t\t\t<td class='regular' valign='middle'>{$TmpKaiNum}　<a href=\"javascript:openrecord('{$rs['id']}',840,400,'sgame_open_recode.php?act={$act}&id={$rs['id']}');\" class='btn btn-danger validate' style='outline: medium;' >验证</a></td>\r\n";
			}


			
			if(in_array($act,[25,26,27,28,30,31,41,42])){//外围,定位
				$arrKJResult = explode("|",$rs["kgjg"]);
				$divTable .= "\t\t\t<td><div class=\"ds\">\r\n";
				if(count($arrKJResult) == 4){
					$step = 0;
					$kjNum = $arrKJResult[count($arrKJResult)-1];
					$kjA = $arrKJResult[0];
					$kjC = $arrKJResult[2];
					$rewardNumCnt = 28;
					$is_max = 0;
					$is_dub = 0;
					$num = $rewardNumCnt / 2;
					
	
					
					if($kjNum % 2 != 0){//单
						$class = "class=\"ds_0\"";
					}else{//双
						$class = "class=\"ds_5\"";
						$is_dub = 1;
					}
					$divTable .= "\t\t\t<span {$class}></span>\r\n";
					
					if($kjNum-$step >= $num){//大
						$class = "class=\"ds_1\"";
						$is_max = 1;
					}else{//小
						$class = "class=\"ds_6\"";
					}
					$divTable .= "\t\t\t<span {$class}></span>\r\n";
					
					if($is_max && $is_dub){//大双
						$class = "class=\"ds_8\"";
						$divTable .= "\t\t\t<span {$class}></span>\r\n";
					}
					
					if($is_max && !$is_dub){//大单
						$class = "class=\"ds_3\"";
						$divTable .= "\t\t\t<span {$class}></span>\r\n";
					}
					
					if(!$is_max && $is_dub){//小双
						$class = "class=\"ds_7\"";
						$divTable .= "\t\t\t<span {$class}></span>\r\n";
					}
					
					if(!$is_max && !$is_dub){//小单
						$class = "class=\"ds_2\"";
						$divTable .= "\t\t\t<span {$class}></span>\r\n";
					}
					
					if($kjNum >= 22){
						$class = "class=\"ds_9\"";
						$divTable .= "\t\t\t<span {$class}></span>\r\n";
					}
					
					if($kjNum <= 5){
						$class = "class=\"ds_4\"";
						$divTable .= "\t\t\t<span {$class}></span>\r\n";
					}
					
					
					if(in_array($act,[25,27,30,41])){//外围龙虎豹
						if(in_array($kjNum , [0,3,6,9,12,15,18,21,24,27])) $class = "class=\"ds_10\"";
						if(in_array($kjNum , [1,4,7,10,13,16,19,22,25])) $class = "class=\"ds_11\"";
						if(in_array($kjNum , [2,5,8,11,14,17,20,23,26])) $class = "class=\"ds_12\"";
						$divTable .= "\t\t\t<span {$class}></span>\r\n";
					}
					
					
					if(in_array($act,[26,28,31,42])){//定位龙虎和
						if($kjA > $kjC) $class = "class=\"ds_10\"";
						if($kjA < $kjC) $class = "class=\"ds_11\"";
						if($kjA == $kjC) $class = "class=\"ds_13\"";	
						$divTable .= "\t\t\t<span {$class}></span>\r\n";
					}
					
				}
				
				$divTable .= "\t\t\t</div></td>\r\n";
				
			}else if(in_array($act,[29])){//PK赛车
				$arrKJResult = explode("|",$rs["kgjg"]);
				
				if(count($arrKJResult) == 4){
					$total = $arrKJResult[0] + $arrKJResult[1];
					
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					$divTable .= "\t\t\t<span class=\"sc sc_{$total}\"></span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
					
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					if($total % 2 == 0)
						$divTable .= "\t\t\t<span class=\"sc sc_153\"></span>\r\n";
					else
						$divTable .= "\t\t\t<span class=\"sc sc_b152\"></span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
					
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					if($total >= 12)
						$divTable .= "\t\t\t<span class=\"sc sc_b150\"></span>\r\n";
					else
						$divTable .= "\t\t\t<span class=\"sc sc_151\"></span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
				}else{
					$divTable .= "\t\t\t<td></td><td></td><td></td>\r\n";
				}
				
				
				
				$arrKJResult = explode("|",$rs["kgNo"]);
				
				
				if(count($arrKJResult) == 10){
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					if($arrKJResult[0] > $arrKJResult[9])
						$divTable .= "\t\t\t<span class=\"sc lh_0\"></span>\r\n";
					else
						$divTable .= "\t\t\t<span class=\"sc lh_1\"></span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
					
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					if($arrKJResult[1] > $arrKJResult[8])
						$divTable .= "\t\t\t<span class=\"sc lh_0\"></span>\r\n";
					else
						$divTable .= "\t\t\t<span class=\"sc lh_1\"></span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
					
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					if($arrKJResult[2] > $arrKJResult[7])
						$divTable .= "\t\t\t<span class=\"sc lh_0\"></span>\r\n";
					else
						$divTable .= "\t\t\t<span class=\"sc lh_1\"></span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
					
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					if($arrKJResult[3] > $arrKJResult[6])
						$divTable .= "\t\t\t<span class=\"sc lh_0\"></span>\r\n";
					else
						$divTable .= "\t\t\t<span class=\"sc lh_1\"></span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
					
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					if($arrKJResult[4] > $arrKJResult[5])
						$divTable .= "\t\t\t<span class=\"sc lh_0\"></span>\r\n";
					else
						$divTable .= "\t\t\t<span class=\"sc lh_1\"></span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
				}else{
					$divTable .= "\t\t\t<td></td><td></td><td></td><td></td><td></td>\r\n";
				}
				
				
			}else if(in_array($act,[36])){//幸运农场
				$arrKJResult = explode("|",$rs["kgjg"]);
				
				if(count($arrKJResult) == 9){
					$total = $arrKJResult[8];
					
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					$divTable .= "\t\t\t<span class=\"he\">{$total}</span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
					
					
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					if($total >= 85)
						$divTable .= "\t\t\t<span class=\"sc sc_b150\"></span>\r\n";
					elseif($total <= 83)
						$divTable .= "\t\t\t<span class=\"sc sc_151\"></span>\r\n";
					else
						$divTable .= "\t\t\t<span class=\"sc sc_156\"></span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
					
					
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					if($total % 2 == 0)
						$divTable .= "\t\t\t<span class=\"sc sc_153\"></span>\r\n";
					else
						$divTable .= "\t\t\t<span class=\"sc sc_b152\"></span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
					

					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					if($total % 10 >= 5)
						$divTable .= "\t\t\t<span class=\"sc sc_157\"></span>\r\n";
					else
						$divTable .= "\t\t\t<span class=\"sc sc_158\"></span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
				}else{
					$divTable .= "\t\t\t<td></td><td></td><td></td><td></td>\r\n";
				}
				
				
				
				$arrKJResult = explode("|",$rs["kgNo"]);
				
				
				if(count($arrKJResult) == 8){
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					if($arrKJResult[0] > $arrKJResult[7])
						$divTable .= "\t\t\t<span class=\"sc lh_0\"></span>\r\n";
					else
						$divTable .= "\t\t\t<span class=\"sc lh_1\"></span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
					
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					if($arrKJResult[1] > $arrKJResult[6])
						$divTable .= "\t\t\t<span class=\"sc lh_0\"></span>\r\n";
					else
						$divTable .= "\t\t\t<span class=\"sc lh_1\"></span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
					
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					if($arrKJResult[2] > $arrKJResult[5])
						$divTable .= "\t\t\t<span class=\"sc lh_0\"></span>\r\n";
					else
						$divTable .= "\t\t\t<span class=\"sc lh_1\"></span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
					
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					if($arrKJResult[3] > $arrKJResult[4])
						$divTable .= "\t\t\t<span class=\"sc lh_0\"></span>\r\n";
					else
						$divTable .= "\t\t\t<span class=\"sc lh_1\"></span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
				}else{
					$divTable .= "\t\t\t<td></td><td></td><td></td><td></td>\r\n";
				}
				
				
			}else if(in_array($act,[37])){//重庆时时彩
				$arrKJResult = explode("|",$rs["kgjg"]);
				
				if(count($arrKJResult) == 6){
					$total = $arrKJResult[5];
					
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					$divTable .= "\t\t\t<span class=\"he\">{$total}</span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
					
					
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					if($total >= 23)
						$divTable .= "\t\t\t<span class=\"sc sc_b150\"></span>\r\n";
					else
						$divTable .= "\t\t\t<span class=\"sc sc_151\"></span>\r\n";
						
					$divTable .= "\t\t\t</td>\r\n";
					
					
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					if($total % 2 == 0)
						$divTable .= "\t\t\t<span class=\"sc sc_153\"></span>\r\n";
					else
						$divTable .= "\t\t\t<span class=\"sc sc_b152\"></span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
					

					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					if($arrKJResult[0] > $arrKJResult[4])
						$divTable .= "\t\t\t<span class=\"sc sc_154\"></span>\r\n";
					elseif($arrKJResult[0] < $arrKJResult[4])
						$divTable .= "\t\t\t<span class=\"sc sc_155\"></span>\r\n";
					else
						$divTable .= "\t\t\t<span class=\"sc sc_156\"></span>\r\n";
					$divTable .= "\t\t\t</td>\r\n";
				}else{
					$divTable .= "\t\t\t<td></td><td></td><td></td><td></td>\r\n";
				}
				
				
				
				$arrKJResult = explode("|",$rs["kgNo"]);
				
				
				if(count($arrKJResult) == 5){
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					$res_a = getGame36Result($arrKJResult[0],$arrKJResult[1],$arrKJResult[2]);
					$divTable .= show_num($res_a,2);
					$divTable .= "\t\t\t</td>\r\n";
					
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					$res_b = getGame36Result($arrKJResult[1],$arrKJResult[2],$arrKJResult[3]);
					$divTable .= show_num($res_b,2);
					$divTable .= "\t\t\t</td>\r\n";
					
					$divTable .= "\t\t\t<td class=\"sc\">\r\n";
					$res_c = getGame36Result($arrKJResult[2],$arrKJResult[3],$arrKJResult[4]);
					$divTable .= show_num($res_c,2);
					$divTable .= "\t\t\t</td>\r\n";
				}else{
					$divTable .= "\t\t\t<td></td><td></td><td></td>\r\n";
				}
				
				
			}else if(in_array($act,[32,33,34,35])){//固定
				;
			}else{
				if(!in_array($act , [0,1,2,15,22,23,24])){
					$divTable .= "\t\t\t<td class='please'><span>". Trans($rs['tzpoints']) ."</span></td>\r\n";
					$divTable .= "\t\t\t<td>{$Winner}</td>\r\n";
				}
			}
			
			if($rs['ptzpoints'] == 0)
				$winPoint = "<span>{$rs['ptzpoints']}/{$rs['phdpoints']}</span>";
			else if($rs['phdpoints'] - $rs['ptzpoints'] >= 0)
				$winPoint = "<a title='查看投注记录' href=\"javascript:getContent('sgamerecord.php?act={$act}&sid=3')\"><span style='color:red'>". $rs['ptzpoints'] ."/". $rs['phdpoints'] ."</span></a>";
			else
				$winPoint = "<a title='查看投注记录' href=\"javascript:getContent('sgamerecord.php?act={$act}&sid=3')\"><span style='color:blue'>". $rs['ptzpoints'] ."/". $rs['phdpoints'] ."</span></a>";
				
			$divTable .= "\t\t\t<td class='please'>{$winPoint}</td>\r\n";
			$divTable .= "\t\t\t<td class='state'><span id='scur_{$rs['id']}'>{$TmpState}</span></td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
		}
		$divTable .= "\t</tbody>\r\n";
		$divTable .= "\t</table>\r\n";
		$RetContent .= $divTable;
		if($_SESSION['kj_music'][$act]!=$qihao){
			$RetContent.='<script>is_open=1</script>';
			$_SESSION['kj_music'][$act]=$qihao;
		}
		
		//分页
		if($TotalRecCount > 20)
		{
			$divPage .= "<div class='Paging'>\r\n";
			require_once('inc/fenye.php');
			$ajaxpage=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesize,'ajax'=>"ajax_page",'nowindex' => $page));
			$divPage .= $ajaxpage->show();
			$divPage .= "</div>\r\n";
			$RetContent .= $divPage;
		}
		 
		$RetContent .= "</div>\r\n";  
		return $RetContent;
	}
    
    function GetJSContent($act)
    {
		$js = "<script type=\"text/javascript\">\r\n";
		$js .= "
		$(document).ready(function(){
				var issond = (getCookie('issond') == '')?1:getCookie('issond');
				if(issond==1){
					 $('#sond_offon').attr('src', '/images/S_Open.gif');
				}else{
					$('#sond_offon').attr('src', '/images/S_Close.gif');
				}
		
				if(issond==1){
					if(is_open==1){
					 	$('#jquery_jplayer_1').jPlayer('play',0);
					}
				}
			
			});
			function ajax_page(page)
			{
				getContent('sgame.php?act={$act}&page=' + page + '&t=' +  Math.random());
			}
			function toPress(no)
			{
			   $.post('sgameservice.php?t=' + Math.random(),{act:'checkpress',gtype:{$act},no:no},function(ret){
			   		switch(ret.cmd)
			   		{
			   			case 'ok':
			   				getContent('spress.php?act={$act}&no=' + no);
			   				break;
			   			case 'auto':
			   				if(confirm('您已经设置了自动投注，请先关闭自动投注!')){
			   				 	getContent('sautopress.php?act={$act}' + '&no=' + no);
			   				}
			   				break;
			   			case 'shutdown':
			   				if(confirm(ret.msg)){
			   				 	getContent('sautopress.php?act={$act}' + '&no=' + no);
			   				}
			   				break;
			   			default:
			   				alert(ret.msg);
			   				break;
			   		}
			   },'json');
			}
				
		";
		
		$js .= "</script>\r\n";
		return $js;
    }
    //显示中奖号码
    function show_num($num,$type){
        if($type==1){
            return "<i class='kj kj_".$num."'></i>";
        }
		if($type==2){
            return "<i class='zh z".$num."'></i>";
        }
        if($type==3){
             return "<i class='mh m".$num."'></i>";
            //return "<i class=\"number number_{$num}\"></i>";
        }
    }
    

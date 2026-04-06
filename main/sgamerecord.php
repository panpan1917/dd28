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
		$arrCurNoInfo = array('preno'=>'','prekgtime'=>'','game_kj_delay'=>'','game_tz_close'=>'');
		$RetContent = "<div class='Pattern'>\r\n";
		$RetContent .= "\t<div class='Content'>\r\n";

		//取得开奖头
		$RetContent .= GetHeadContent($act,$sid,$arrCurNoInfo);
		//取得子菜单
		$RetContent .= GetSubMenu($act,$sid);
		
		//取号码表格
		$RetContent .= GetTableContent($act,$No);
		
		$RetContent .= "\t</div>\r\n"; //content结束
		$RetContent .= "</div>\r\n";
		//js 定义
		$RetContent .= GetJSContent($act,$No);
		$RetContent .= GetRewardJS($act,$arrCurNoInfo,"head");
		
		echo $RetContent;
		exit;
    }
    
    /* 取号码表格
    *
    */
    function GetTableContent($act,$No)
    {
    	global $db;
    	$page = isset($_GET['page'])?$_GET['page']:1;
		$page =intval($page);
		$pagesize = 20;
    	$tableuserstz = GetGameTableName($act,"users_tz");
    	$tablegame = GetGameTableName($act,"game");
		//号码表格
		$sql = "SELECT count(id) FROM {$tableuserstz} WHERE uid = {$_SESSION['usersid']} and `time` > DATE_ADD(CURDATE(),INTERVAL -6 DAY)";
		$TotalRecCount = $db->GetRecordCount($sql);
		$sql = "
		 	SELECT a.id,a.no,a.time,a.points,a.hdpoints,b.kgjg,b.kgNo
			FROM {$tableuserstz} a 
			LEFT OUTER JOIN {$tablegame} b
			ON a.no = b.id
			WHERE a.uid = {$_SESSION['usersid']} AND a.`time` > DATE_ADD(CURDATE(),INTERVAL -6 DAY) 
			ORDER BY `time` desc ";
		$sql .= GetLimit($page,$pagesize);
		 
		$result =  $db->query($sql);
		$divTable = "<div class='table'>\r\n";
		$divTable .= "\t<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;color:#000000;'>\r\n";
		$divTable .= "\t<tbody>\r\n";
		$divTable .= "\t\t<tr>\r\n";
		$divTable .= "\t\t\t<th width='70'>期号</th>\r\n";
		$divTable .= "\t\t\t<th width='150'>投注时间</th>\r\n";
		$divTable .= "\t\t\t<th width='300'>开奖结果</th>\r\n";
		$divTable .= "\t\t\t<th width='100'>投注数量</th>\r\n";
		$divTable .= "\t\t\t<th width='100'>获得数量</th>\r\n";
		$divTable .= "\t\t\t<th width='100'>赢取</th>\r\n";
		$divTable .= "\t\t\t<th width='90'>详情</th>\r\n";
		if(!in_array($act,[25,26,27,28,29,30,31,32,33,34,35,36,37,41,42]))
			$divTable .= "\t\t\t<th width='90'>保存模式</th>\r\n";
		$divTable .= "\t\t</tr>\r\n";
		
		while($rs=$db->fetch_array($result)){
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>{$rs['no']}</td>\r\n";
			$divTable .= "\t\t\t<td>{$rs['time']}</td>\r\n";
			//开奖结果
			$arrKg = explode("|",$rs['kgjg']);
			$divTable .= "\t\t\t<td class='regular'>";
			$kgstr = "";
			//if($act != "6" && $act != "7" && $act != "14" && $act != "15" && $act != "16" && $act != "17" && $act != "36" && $act != "37")//排除pk类
			if(!in_array($act,[6,7,14,15,16,17,36,37,43,44,45,46,47]))
			{
				$kjNumberStr = "{$arrKg[0]} + {$arrKg[1]}";
				$kjFinal = $arrKg[3];
				if($arrKg[2] != -1)
				{
					$kjNumberStr = "{$arrKg[0]} + {$arrKg[1]} + {$arrKg[2]}";
					//if($act == "11" || $act == "12" || $act == "13" || $act == "21" || $act == "23") //36游戏
					if(in_array($act,[11,12,13,21,23])) //36游戏
					{
						switch($arrKg[3])
						{
							case 1:
								$kjFinal = "豹";
								break;
							case 2:
								$kjFinal = "对";
								break;
							case 3:
								$kjFinal = "顺";
								break;
							case 4:
								$kjFinal = "半";
								break;
							case 5:
								$kjFinal = "杂";
								break;
							default:
								$kjFinal = "";
								break;
						}
					}
				} 
				$divTable .= "{$kjNumberStr} = {$kjFinal}";//finals final regular".sprintf('%02d',$arrKg[3])."
			}
			elseif($act == "36"){
				$kjFinal = $arrKg[8];
				$kjNumberStr = "{$arrKg[0]} + {$arrKg[1]} + {$arrKg[2]} + {$arrKg[3]} + {$arrKg[4]} + {$arrKg[5]} + {$arrKg[6]} + {$arrKg[7]}";
				$divTable .= "{$kjNumberStr} = {$kjFinal}";
			}
			elseif($act == "37"){
				$kjFinal = $arrKg[5];
				$kjNumberStr = "{$arrKg[0]} + {$arrKg[1]} + {$arrKg[2]} + {$arrKg[3]} + {$arrKg[4]}";
				$divTable .= "{$kjNumberStr} = {$kjFinal}";
			}
			else
			{
				
				$arrKGNo = explode("|",$rs['kgNo']);
				for($i = 1; $i <= count($arrKGNo); $i++)
				{
					$theResult = substr("0" .  $arrKGNo[$i-1],-2);
					$divTable .= "<em class='regular{$theResult}'></em>";	
				}
				//取得最后开奖结果
				$LastKGResult = "{$arrKg[3]}";//final regular{$arrKg[3]}
				if(in_array($act,[16,47])) //pk龙虎 飞艇龙虎单独处理
				{
					if($arrKg[3] == 1) //龙
					{
						$LastKGResult = "<em class='final'><i>龙</i></em>";
						//$LastKGResult = "<em class='slh0'></em>";
					}
					else //虎
					{
						$LastKGResult = "<em class='final'><i>虎</i></em>";
						//$LastKGResult = "<em class='slh1'></em>"; 
					}
				}
				$divTable .= $LastKGResult;
				//unset($arrKGNo);
			}
			$divTable .= "</td>\r\n";
			
			$divTable .= "\t\t\t<td class='please'><span>". Trans($rs['points']) . "</span></td>\r\n";
			$divTable .= "\t\t\t<td class='please'><span>". Trans($rs['hdpoints']) ."</span></td>\r\n";
			$divTable .= "\t\t\t<td class='please'><span><i>". Trans($rs['hdpoints'] - $rs['points']) ."</i></span></td>\r\n";
			$divTable .= "\t\t\t<td><a href=\"javascript:openrecord('{$rs['no']}','srecdetail.php?act={$act}&no={$rs['no']}');\">查看</a></td>\r\n"; 
			if(!in_array($act,[25,26,27,28,29,30,31,32,33,34,35,36,37,41,42]))
				$divTable .= "\t\t\t<td><a href=\"javascript:getContent('smodel.php?act={$act}&sid=4&no={$rs['id']}')\">保存</a></td>\r\n"; 
			$divTable .= "\t\n</tr>\r\n";
			//unset($arrKg);
		}
		$divTable .= "\t</tbody>\r\n";
		$divTable .= "\t</table>\r\n";
		
		//分页
		if($TotalRecCount > 0)
		{
			$divPage = "<div class='pagediv'></div><div class='Paging'>\r\n";
			require_once('inc/fenye.php');
			$ajaxpage=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesize,'ajax'=>"ajax_page",'nowindex' => $page));
			$divPage .= $ajaxpage->show();
			$divPage .= "</div>\r\n";
			$divTable .= $divPage;
		}
		 
		$divTable .= "</div>\r\n";  
		return $divTable;
    }
    
    /* 取得JS
    * 
    */
    function GetJSContent($act,$No)
    {
		$js = "<script type=\"text/javascript\">\r\n";
		$js .= "
			 function ajax_page(page)
			 {
				getContent('sgamerecord.php?act={$act}&page=' + page + '&t=' +  Math.random());
			 }
			 $.setupJMPopups({
		        screenLockerBackground: '#cccccc',
		        screenLockerOpacity: '0.7'
		     });
		    function openrecord(name,url)
			{
				$.openPopupLayer({
					name: name,
					width: 600,
					height: 500,
					url: url
				});	
			}
			function closerecord(name)
			{
				$.closePopupLayer(name);
			}
		";
		
		$js .= "</script>\r\n";
		return $js;
    }

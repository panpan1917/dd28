<?php
	include_once("inc/conn.php");
    include_once("inc/function.php");
    
    if(!isset($_SESSION['usersid'])) {
		echo "您还没登录或者链接超时，请先去<a href='/login.php'>登录</a>!";
		exit;
	}
	
	
	$act = intval($_GET['act']);
	//返回游戏记录
	GetUserWinList($act);
	
    /* 返回游戏记录
    * 
    */
    function GetUserWinList($act)
    {
		$sid = intval($_GET['sid']);
		$No = intval($_GET['no']);
		$arrCurNoInfo = array('preno'=>'','prekgtime'=>'','game_kj_delay'=>'','game_tz_close'=>'');
		
		if($No == 0)
		{
			echo "非法提交数据!";
			exit;
		}
		
		$RetContent = "<div class='Pattern'>\r\n";
		$RetContent .= "\t<div class='Content'>\r\n";
		//取得子菜单
		$RetContent .= GetSubMenu($act,$sid);
		//取得开奖头
		$RetContent .= GetHeadContent($act,$sid,$arrCurNoInfo);
		
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
		//号码表格
		$sql = "SELECT count(id) FROM {$tableuserstz} WHERE  NO = '{$No}' and hdpoints > 0";
		$TotalRecCount = $db->GetRecordCount($sql);
		$sql = "
		 	SELECT a.uid,a.points,a.hdpoints,(a.hdpoints - a.points) as winpoints,b.experience
			FROM {$tableuserstz} a
			LEFT OUTER JOIN users b
			ON a.uid = b.id
			WHERE a.No = '{$No}' AND a.hdpoints > 0 
			ORDER BY winpoints DESC";
		$sql .= GetLimit($page,$pagesize);
		 
		$result =  $db->query($sql);
		$divTable = "<div class='table'>\r\n";
		$divTable = "\t<table class='table_list' cellspacing='0px' style='border-collapse:collapse;'>\r\n";
		$divTable .= "\t<tbody>\r\n";
		$divTable .= "\t\t<tr><td colspan='4'>第 {$No} 期中奖名单</td></tr>\r\n";
		$divTable .= "\t\t<tr>\r\n";
		$divTable .= "\t\t\t<th width='300'>用户ID</th>\r\n";
		$divTable .= "\t\t\t<th>投注数量</th>\r\n";
		$divTable .= "\t\t\t<th>获得数量</th>\r\n";
		$divTable .= "\t\t\t<th>赢取数量</th>\r\n";
		$divTable .= "\t\t</tr>\r\n";
		while($rs=$db->fetch_array($result)){
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>{$rs['uid']}</td>\r\n";
			$divTable .= "\t\t\t<td class='please'><span>". Trans($rs['points']) . "</span></td>\r\n";
			$divTable .= "\t\t\t<td class='please'><span>". Trans($rs['hdpoints']) ."</span></td>\r\n";
			$divTable .= "\t\t\t<td class='please'><span><i>". Trans($rs['winpoints']) ."</i></span></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
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
				getContent('swinresult.php?act={$act}&no={$No}&page=' + page + '&t=' +  Math.random());
			 }
		";
		
		$js .= "</script>\r\n";
		return $js;
    }

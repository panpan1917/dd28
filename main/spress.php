<?php
	include_once("inc/conn.php");
    include_once("inc/function.php");
    
    if(!isset($_SESSION['usersid'])) {
		echo "您还没登录或者链接超时，请先去<a href='/login.php'>登录</a>!";
		exit;
	}
	
	
	RefreshPoints();
	$act = intval($_GET['act']);
	
	//返回投注界面
	GetGamePressContent($act);
	
    /* 取得下注界面
    * 
    */
    function GetGamePressContent($act)
    {
		$sid = intval($_GET['sid']);
		$No = intval($_GET['no']);
		$LastNo = $No - 1;
		$arrCurNoInfo = array('preno'=>'','prekgtime'=>'','game_kj_delay'=>'','game_tz_close'=>'');
		
		if($No == 0)
		{
			echo "非法提交数据!";
			exit;
		}
		
		$RetContent = "<div class='criterion'>\r\n";
		$RetContent .= "\t<div class='Content'>\r\n";

		//取得开奖头
		$RetContent .= GetHeadContent($act,$sid,$arrCurNoInfo);
		//取得子菜单
		$RetContent .= GetSubMenu($act,$sid);
		//投注按钮
		$RetContent .= GetButtonContent($act);
		//取押注倍数
		$RetContent .= GetTimesContent($act,$No,$LastNo);
		
		
		$RetContent .= "\t</div>\r\n"; //content结束
		
		
		//取号码表格
		if(in_array($act,[25,27,30,41])){
			$RetContent .= GetTableContentWW($act,$No);
		}else if(in_array($act,[26,28,31,42])){
			$RetContent .= GetTableContentDW($act,$No);
		}else if(in_array($act,[29])){
			$RetContent .= GetTableContentSC($act,$No);//PK赛车
		}else if(in_array($act,[36])){
			$RetContent .= GetTableContentXYNC($act,$No);//幸运农场
		}else if(in_array($act,[37])){
			$RetContent .= GetTableContentSSC($act,$No);//时时彩
		}else if(in_array($act,[32,33,34,35])){
			$RetContent .= GetTableContentGD28($act,$No);
		}else{
			$RetContent .= GetTableContent($act,$No,$LastNo);
		}
		
		
		$RetContent .= "</div>\r\n";
		//js 定义
		$RetContent .= GetJSContent($act,$No);
		$RetContent .= GetRewardJS($act,$arrCurNoInfo,"head"); 
		$RetContent .= "<script type='text/javascript' src='js/game28.js'>\r\n";
		echo $RetContent;
		exit;
    }
    
    /* 取得押注按钮定义
    *
    */
    function GetButtonContent($act)
    {
    	global $db;
		$tableautotz = GetGameTableName($act,"auto_tz");
		$sql = "SELECT id,tzname FROM {$tableautotz} WHERE uid = '{$_SESSION['usersid']}'";
		$result = $db->query($sql);
		$ModelList = "我的投注模式: ";
		while($rs = $db->fetch_array($result))
		{
			$ModelList .= "<a href=\"javascript:getpressinfo({$rs['id']},'id')\">". ChangeEncodeG2U($rs['tzname']) ."</a>  ";
		}
		$ModelList .= " <a href=\"javascript:getContent('smodel.php?act={$act}&sid=4')\">模式编辑</a>";
		if(in_array($act,array(11,12,13,16,21,23,47))) {//36和龙虎
			$divMode = "<div class='mode'>\r\n";
			$divMode .= "\t<p style='margin:0;'>{$ModelList}</p>\r\n";
			$divMode .= "\t<ul style='height:35px;'>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(2)' >单</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(1)'>双</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(4)'>大</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(3)'>小</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(5)'>中</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(6)'>边</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(0.1)'>0.1倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(0.5)'>0.5倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(0.8)'>0.8倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(1.2)'>1.2倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(1.5)'>1.5倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(2)'>2倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(5)'>5倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(10)'>10倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(50)'>50倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(100)'>100倍</a></li>\r\n";
			$divMode .= "\t</ul>\r\n";
			$divMode .= "</div>\r\n";
		}

				//投注按钮
		if(in_array($act,array(0,1,2,3,4,5,6,7,8,9,10,14,15,17,18,19,20,22,24,32,33,34,35,38,39,40,43,44,45,46))) {  
			$divMode = "<div class='mode'>\r\n";
			$divMode .= "\t<p>{$ModelList}</p>\r\n";
			$divMode .= "\t<ul>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(2)' >单</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(1)'>双</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(4)'>大</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(3)'>小</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(5)'>中</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(6)'>边</a></li>\r\n";

			$divMode .= "\t\t<li><a onclick='javascript:useModel(7)'>大单</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(8)'>小单</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(9)'>大双</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(10)'>小双</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(11)'>大边</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(12)'>小边</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(13)'>0尾</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(14)'>1尾</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(15)'>2尾</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(16)'>3尾</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(17)'>4尾</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(18)'>小尾</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(19)'>5尾</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(20)'>6尾</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(21)'>7尾</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(22)'>8尾</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(23)'>9尾</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(24)'>大尾</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(25)'>3余0</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(26)'>3余1</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(27)'>3余2</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(28)'>4余0</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(29)'>4余1</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(30)'>4余2</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(31)'>4余3</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(32)'>5余0</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(33)'>5余1</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(34)'>5余2</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(35)'>5余3</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(36)'>5余4</a></li>\r\n";
			$divMode .= "\t</ul>\r\n";

			$divMode .= "\t<ul>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(0.1)'>0.1倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(0.5)'>0.5倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(0.8)'>0.8倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(1.2)'>1.2倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(1.5)'>1.5倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(2)'>2倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(5)'>5倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(10)'>10倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(50)'>50倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(100)'>100倍</a></li>\r\n";
			$divMode .= "\t</ul>\r\n";
			$divMode .= "</div>\r\n";
		}
		
		if(in_array($act , [25,26,27,28,29,30,31,36,37,41,42])){//外围 定位 赛车 农场 时时彩
			$divMode = "<div class='mode'>\r\n";
			$divMode .= "\t<p style='margin:0;'>选择倍率</p>\r\n";
			$divMode .= "\t<ul>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(0.1)'>0.1倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(0.5)'>0.5倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(0.8)'>0.8倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(1.2)'>1.2倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(1.5)'>1.5倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(2)'>2倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(5)'>5倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(10)'>10倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(50)'>50倍</a></li>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:chgAllTimes(100)'>100倍</a></li>\r\n";
			$divMode .= "\t</ul>\r\n";
			$divMode .= "</div>\r\n";
		}
		
		return $divMode;
    }
    
    /* 取押注倍数
    *
    */
    function GetTimesContent($act,$No,$LastNo)
    {
    	$divTimes = "";
    	/* if(in_array($act , [25,26,27,28,29,30,31,36,37,41,42])){//外围 定位 赛车 农场 时时彩
    		//倍数
    		$divTimes .= "<div class='times'>\r\n";
    		$divTimes .= "\t<ul class='power'>\r\n";
    		$divTimes .= "\t\t<li><a class='chips1' onclick='javascript:chips3(10000,1)'><img src=\"image/chips_10000.png\" alt=\"1000\" /></a></li>\r\n";
    		$divTimes .= "\t\t<li><a class='chips2' onclick='javascript:chips3(100000,2)'><img src=\"image/chips_100000.png\" alt=\"1000\" /></a></li>\r\n";
    		$divTimes .= "\t\t<li><a class='chips3' onclick='javascript:chips3(500000,3)'><img src=\"image/chips_500000.png\" alt=\"1000\" /></a></li>\r\n";
    		$divTimes .= "\t\t<li><a class='chips4' onclick='javascript:chips3(1000000,4)'><img src=\"image/chips_1000000.png\" alt=\"1000\" /></a></li>\r\n";
    		$divTimes .= "\t\t<li><a class='chips5' onclick='javascript:chips3(5000000,5)'><img src=\"image/chips_5000000.png\" alt=\"1000\" /></a></li>\r\n";
    		$divTimes .= "\t</ul>\r\n";
    		
    		$divTimes .= "\t<ul class='self input-group'>\r\n";
    		$divTimes .= "\t\t<li><input type=\"text\" class=\"form-control\" id=\"yushe\"></li>\r\n";
    		$divTimes .= "\t\t<a onclick=\"javascript:usefenpei_red()\" class=\"input-group-addon\">预设金额</a></li>\r\n";
    		$divTimes .= "\t</ul>\r\n";
    		
    	} */
    	
		//倍数
		$divTimes .= "<div class='times'>\r\n";
		$divTimes .= "\t<input type='hidden' id='hidTimes' value='1' />\r\n"; 
		$divTimes .= "\t<ul class='power'>\r\n";
		$divTimes .= "\t\t<li><a class='chips1' onclick='javascript:chips2(10000,1)'><img src=\"image/chips_10000.png\" alt=\"1000\" /></a></li>\r\n";
		$divTimes .= "\t\t<li><a class='chips2' onclick='javascript:chips2(100000,2)'><img src=\"image/chips_100000.png\" alt=\"1000\" /></a></li>\r\n";
		$divTimes .= "\t\t<li><a class='chips3' onclick='javascript:chips2(500000,3)'><img src=\"image/chips_500000.png\" alt=\"1000\" /></a></li>\r\n";
		$divTimes .= "\t\t<li><a class='chips4' onclick='javascript:chips2(1000000,4)'><img src=\"image/chips_1000000.png\" alt=\"1000\" /></a></li>\r\n";
		$divTimes .= "\t\t<li><a class='chips5' onclick='javascript:chips2(5000000,5)'><img src=\"image/chips_5000000.png\" alt=\"1000\" /></a></li>\r\n";
		$divTimes .= "\t</ul>\r\n";

		/*$divTimes.='<script type="text/javascript">$(function() {
  $(".power a").click(function() {
    $(".power a").removeClass("active");
    $(this).addClass("active");
  });
})</script>';*/
		
		
		
		$divTimes .= "\t<ul class='self input-group'>\r\n";
		$divTimes .= "\t\t<li><input type=\"text\" class=\"form-control\" id=\"betsLeft\"></li>\r\n";
		if(in_array($act , [25,26,27,28,29,30,31,36,37,41,42])){//外围 定位 赛车 农场 时时彩
			$divTimes .= "\t\t<a onclick=\"javascript:usefenpei2()\" class=\"input-group-addon\">定额梭哈</a></li>\r\n";
		}else{
			$divTimes .= "\t\t<a onclick=\"javascript:usefenpei()\" class=\"input-group-addon\">定额梭哈</a></li>\r\n";
		}
		$divTimes .= "\t</ul>\r\n";
		
		$divTimes .= "\t<ul class='depressed'>\r\n";
		$divTimes .= "\t\t<li><a onclick='javascript:RefreshOdds({$act},{$No})'>刷新赔率</a></li>\r\n";
		
		
		
		if(in_array($act , [25,26,27,28,29,30,31,36,37,41,42])){
			$divTimes .= "\t\t<li><a onclick='javascript:LastPress_red({$act},{$LastNo})'>上次投注</a></li>\r\n";
			$divTimes .= "\t\t<li><a onclick='javascript:useModel2(0)'>全 包</a></li>\r\n";
			$divTimes .= "\t\t<li><a onclick='javascript:useSuoha_red()'>梭 哈</a></li>\r\n";
			$divTimes .= "\t\t<li><a onclick='javascript:subSelect_red()'>反 选</a></li>\r\n";
		}else{
			$divTimes .= "\t\t<li><a onclick='javascript:LastPress({$act},{$LastNo})'>上次投注</a></li>\r\n";
			$divTimes .= "\t\t<li><a onclick='javascript:useModel(0)'>全 包</a></li>\r\n";
			$divTimes .= "\t\t<li><a onclick='javascript:useSuoha()'>梭 哈</a></li>\r\n";
			$divTimes .= "\t\t<li><a onclick='javascript:subSelect()'>反 选</a></li>\r\n";
		}
		
		$divTimes .= "\t\t<li class='not'><a href='javascript:init()'>清 除</a></li>\r\n";
		$divTimes .= "\t</ul>\r\n";
						
		$divTimes .= "\t<ul class='total'>\r\n";
		$divTimes .= "\t\t<li class='t'>\r\n";
		$divTimes .= "总投注: <i id='tbTotal'>0</i>";
		$divTimes .= "\t\t</li>\r\n";
		$divTimes .= "\t\t<li><a onclick='javascript:ToSave({$act},{$No})' class=\"btn btn-info\">投注</a></li>\r\n";
		$divTimes .= "\t</ul>\r\n";

		
		$divTimes .= "</div>";
		return $divTimes;
    }
    
    
    function GetTableContentWW($act,$No){
    	global $db;
    	$tablegame = GetGameTableName($act,"game");
    	$tablegamekg = GetGameTableName($act,"kg_users_tz");
    	
    	//取赔率
    	$sql = "select game_std_odds as zjpl from game_config where game_type = {$act} limit 1";
    	$result = $db->query($sql);
    	$curOdds = "";
    	if($rs = $db->fetch_array($result))
    	{
    		$curOdds = $rs['zjpl'];
    	}
    	$arrCurOdds = explode('|',$curOdds);
    	for($i=0;$i<count($arrCurOdds);$i++){
    		$arrCurOdds[$i] = number_format($arrCurOdds[$i], 4, '.', '');
    	}
    	
    	//取已投注
    	$arrHadPress = array();
    	$step = GetFromBeginNumStep($act);
    	for($i = 0; $i < count($arrCurOdds); $i++)
    	{
    		$arrHadPress[] = 0;
    	}
    	$sql = "select tznum,tzpoints from {$tablegamekg} where `NO` = {$No} and uid = {$_SESSION['usersid']}";
    	$result = $db->query($sql);
    	while($rs=$db->fetch_array($result))
    	{
    		$arrHadPress[$rs['tznum']-$step] = $rs['tzpoints'];
    	}
    	
    	$divTable = "<div class='table'>\r\n";
    	$divTable .= "<table class='table_list table table-striped table-bordered table-hover tztable' cellspacing='0px' style='border-collapse:collapse;'>\r\n";
    	$divTable .= "<tbody>\r\n";
    	$divTable .= "<tr>\r\n";
    	$divTable .= "<th width='20%'>号码</th>\r\n";
    	$divTable .= "<th width='20%'>赔率</th>\r\n";
    	$divTable .= "<th width='30%'>已投注</th>\r\n";
    	$divTable .= "<th width='30%'>投注</th>\r\n";
    	$divTable .= "</tr>\r\n";
    	
    	for($i=0;$i<=4;$i++){
    		$divTable .= "<tr attr=\"{$i}\">\r\n";
    		$divTable .= "<td><div class=\"ds\"><span class=\"ds_{$i}\"></span></div></td>\r\n";
    		$divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
    		$divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
    		$divTable .= "<td><input id='tbNum{$i}' type='text' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
    		$divTable .= "</tr>\r\n";
    	}
    	
    	$divTable .= "</tbody>\r\n";
    	$divTable .= "</table>\r\n";
    	
    	$divTable .= "<table class='table_list table table-striped table-bordered table-hover tztable' cellspacing='0px' style='border-collapse:collapse;'>\r\n";
    	$divTable .= "<tbody>\r\n";
    	$divTable .= "<tr>\r\n";
    	$divTable .= "<th width='20%'>号码</th>\r\n";
    	$divTable .= "<th width='20%'>赔率</th>\r\n";
    	$divTable .= "<th width='30%'>已投注</th>\r\n";
    	$divTable .= "<th width='30%'>投注</th>\r\n";
    	$divTable .= "</tr>\r\n";
    	
    	for($i=5;$i<=9;$i++){
    		$divTable .= "<tr attr=\"{$i}\">\r\n";
    		$divTable .= "<td><div class=\"ds\"><span class=\"ds_{$i}\"></span></div></td>\r\n";
    		$divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
    		$divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
    		$divTable .= "<td><input id='tbNum{$i}' type='text' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
    		$divTable .= "</tr>\r\n";
    	}
    	
    	$divTable .= "</tbody>\r\n";
    	$divTable .= "</table>\r\n";
    	
    	
    	$divTable .= "<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;width:100%;margin-bottom: 0;'>\r\n";
    	$divTable .= "<tbody>\r\n";
    	$divTable .= "<tr>\r\n";
    	$divTable .= "<th>龙虎豹</th>\r\n";
    	$divTable .= "</tr>\r\n";
    	$divTable .= "</tbody>\r\n";
    	$divTable .= "</table>\r\n";
    	
    	for($i=10;$i<=12;$i++){
    		$divTable .= "<div style=\"display: inline\">\r\n";
    		$divTable .= "<table class=\"table_list table table-striped table-bordered table-hover tztable\" style=\"width:33.333%\">\r\n";
    		$divTable .= "<tr><th width='20%'>号码</th><th width='20%'>赔率</th><th width='30%'>已投注</th><th width='30%'>投注</th></tr>\r\n";
    		$divTable .= "<tr attr=\"{$i}\">\r\n";
    		$divTable .= "<td><div class=\"ds\"><span class=\"ds_{$i}\"></span></div></td>\r\n";
    		$divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
    		$divTable .= "<td><i id='odds_{$i}'>{$arrHadPress[$i]}</i></td>\r\n";
    		$divTable .= "<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
    		$divTable .= "</tr>\r\n";
    		$divTable .= "</table>\r\n";
    		$divTable .= "</div>\r\n";
    	}
    		
    	$divTable .= "</div>\r\n";
    	
    	return $divTable;
    }
    
    function GetTableContentDW($act,$No){
    	global $db;
    	$tablegame = GetGameTableName($act,"game");
    	$tablegamekg = GetGameTableName($act,"kg_users_tz");
    	 
    	//取赔率
    	$sql = "select game_std_odds as zjpl from game_config where game_type = {$act} limit 1";
    	$result = $db->query($sql);
    	$curOdds = "";
    	if($rs = $db->fetch_array($result))
    	{
    		$curOdds = $rs['zjpl'];
    	}
    	$arrCurOdds = explode('|',$curOdds);
    	for($i=0;$i<count($arrCurOdds);$i++){
    		$arrCurOdds[$i] = number_format($arrCurOdds[$i], 4, '.', '');
    	}
    	 
    	//取已投注
    	$arrHadPress = array();
    	$step = GetFromBeginNumStep($act);
    	for($i = 0; $i < count($arrCurOdds); $i++)
    	{
    		$arrHadPress[] = 0;
    	}
    	$sql = "select tznum,tzpoints from {$tablegamekg} where `NO` = {$No} and uid = {$_SESSION['usersid']}";
    	$result = $db->query($sql);
    	while($rs=$db->fetch_array($result))
    	{
    		$arrHadPress[$rs['tznum']-$step] = $rs['tzpoints'];
    	}
    	 
    	$divTable = "<div class='table'>\r\n";
    	$divTable .= "<table class='table_list table table-striped table-bordered table-hover tztable' cellspacing='0px' style='border-collapse:collapse;'>\r\n";
    	$divTable .= "<tbody>\r\n";
    	$divTable .= "<tr>\r\n";
 	    $divTable .= "<th width='20%'>号码</th>\r\n";
    	$divTable .= "<th width='20%'>赔率</th>\r\n";
    	$divTable .= "<th width='30%'>已投注</th>\r\n";
    	$divTable .= "<th width='30%'>投注</th>\r\n";
    	$divTable .= "</tr>\r\n";
   
    	for($i=0;$i<=4;$i++){
    	    $divTable .= "<tr attr=\"{$i}\">\r\n";
    	    $divTable .= "<td><div class=\"ds\"><span class=\"ds_{$i}\"></span></div></td>\r\n";
    	    $divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
    	    $divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
    	    $divTable .= "<td><input id='tbNum{$i}' type='text' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
    	    $divTable .= "</tr>\r\n";
    	}
    	    							 
    	$divTable .= "</tbody>\r\n";
    	$divTable .= "</table>\r\n";
    	    							 
    	$divTable .= "<table class='table_list table table-striped table-bordered table-hover tztable' cellspacing='0px' style='border-collapse:collapse;'>\r\n";
    	$divTable .= "<tbody>\r\n";
    	$divTable .= "<tr>\r\n";
    	$divTable .= "<th width='20%'>号码</th>\r\n";
    	$divTable .= "<th width='20%'>赔率</th>\r\n";
    	$divTable .= "<th width='30%'>已投注</th>\r\n";
    	$divTable .= "<th width='30%'>投注</th>\r\n";
    	$divTable .= "</tr>\r\n";
    	    				 
    	for($i=5;$i<=9;$i++){
    	    $divTable .= "<tr attr=\"{$i}\">\r\n";
    	    $divTable .= "<td><div class=\"ds\"><span class=\"ds_{$i}\"></span></div></td>\r\n";
    	    $divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
    	    $divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
    	    $divTable .= "<td><input id='tbNum{$i}' type='text' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
    	    $divTable .= "</tr>\r\n";
    	}
    	 
    	$divTable .= "</tbody>\r\n";
    	$divTable .= "</table>\r\n";
   
   
    	$divTable .= "<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;width:100%;margin-bottom: 0;'>\r\n";
    	$divTable .= "<tbody>\r\n";
    	$divTable .= "<tr>\r\n";
    	$divTable .= "<th>龙虎和</th>\r\n";
    	$divTable .= "</tr>\r\n";
    	$divTable .= "</tbody>\r\n";
    	$divTable .= "</table>\r\n";
   
    	for($i=10;$i<=12;$i++){
    		$j = $i;
    		if($i == 12) $j = $i+1;
    	    $divTable .= "<div style=\"display: inline\">\r\n";
    		$divTable .= "<table class=\"table_list table table-striped table-bordered table-hover tztable\" style=\"width:33.333%\">\r\n";
    		$divTable .= "<tr><th width='20%'>号码</th><th width='20%'>赔率</th><th width='30%'>已投注</th><th width='30%'>投注</th></tr>\r\n";
    		$divTable .= "<tr attr=\"{$i}\">\r\n";
    	    $divTable .= "<td><div class=\"ds\"><span class=\"ds_{$j}\"></span></div></td>\r\n";
    	    $divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
    	    $divTable .= "<td><i id='odds_{$i}'>{$arrHadPress[$i]}</i></td>\r\n";
    	    $divTable .= "<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
    	    $divTable .= "</tr>\r\n";
    	    $divTable .= "</table>\r\n";
    	    $divTable .= "</div>\r\n";
    	}
    	
    	
		$divTable .= "<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;width:100%;margin-bottom: 0;'>\r\n";
		$divTable .= "<tbody>\r\n";
		$divTable .= "<tr>\r\n";
		$divTable .= "<th width='33.333%'>号码一</th>\r\n";
		$divTable .= "<th width='33.333%'>号码二</th>\r\n";
		$divTable .= "<th width='33.333%'>号码三</th>\r\n";
		$divTable .= "</tr>\r\n";
		$divTable .= "</tbody>\r\n";
		$divTable .= "</table>\r\n";
    	
		
		$divTable .= "<table border=\"0\" width=\"100%\">\r\n";
		$divTable .= "<tr>\r\n";
		for($n=0;$n<=2;$n++){
			$divTable .= "<td>\r\n";
			$divTable .= "<table class=\"table_list table table-striped table-bordered table-hover tztable\" style=\"width:100%\">\r\n";
			$divTable .= "<tr><th width='20%'>号码</th><th width='20%'>赔率</th><th width='30%'>已投注</th><th width='30%'>投注</th></tr>\r\n";
			for($m=1;$m<=14;$m++){
				$i = 12 + $n*14 + $m;
				
				$j = $m - 1 - 4; //class="number number_0"
				
				if($m == 1) $j = 1;
				if($m == 2) $j = 6;
				if($m == 3) $j = 0;
				if($m == 4) $j = 5;
				
				$divTable .= "<tr attr=\"{$i}\">\r\n";
				if(in_array($m,[1,2,3,4]))
					$divTable .= "<td><div class=\"ds\"><span class=\"ds_{$j}\"></span></div></td>\r\n";
				else 
					$divTable .= "<td><i class=\"mh m{$j}\"></i></td>\r\n";
				
				$divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
				$divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
				$divTable .= "<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
				$divTable .= "</tr>\r\n";
			}
			
			$divTable .= "</table>\r\n";
			$divTable .= "</td>\r\n";
		}
		$divTable .= "</tr>\r\n";
		$divTable .= "</table>\r\n";
		
    	
    	$divTable .= "</div>\r\n";
    	return $divTable;
    }
    
    function GetTableContentXYNC($act,$No){
    	global $db;
    	$tablegame = GetGameTableName($act,"game");
    	$tablegamekg = GetGameTableName($act,"kg_users_tz");
    	
    	//取赔率
    	$sql = "select game_std_odds as zjpl from game_config where game_type = {$act} limit 1";
    	$result = $db->query($sql);
    	$curOdds = "";
    	if($rs = $db->fetch_array($result))
    	{
    		$curOdds = $rs['zjpl'];
    	}
    	$arrCurOdds = explode('|',$curOdds);
    	for($i=0;$i<count($arrCurOdds);$i++){
    		$arrCurOdds[$i] = number_format($arrCurOdds[$i], 4, '.', '');
    	}
    	
    	//取已投注
    	$arrHadPress = array();
    	$step = GetFromBeginNumStep($act);
    	for($i = 0; $i < count($arrCurOdds); $i++)
    	{
    		$arrHadPress[] = 0;
    	}
    	$sql = "select tznum,tzpoints from {$tablegamekg} where `NO` = {$No} and uid = {$_SESSION['usersid']}";
    	$result = $db->query($sql);
    	while($rs=$db->fetch_array($result))
    	{
    		$arrHadPress[$rs['tznum']-$step] = $rs['tzpoints'];
    	}
    	
    	
    	$divTable = "<div class='table'>\r\n";
    	
    	
    	
    	$divTable .= "<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;width:100%;margin-bottom: 0;'>\r\n";
    	$divTable .= "<tbody>\r\n";
    	$divTable .= "<tr>\r\n";
    	$divTable .= "<th>总和-两面</th>\r\n";
    	$divTable .= "</tr>\r\n";
    	$divTable .= "</tbody>\r\n";
    	$divTable .= "</table>\r\n";
    	
    	
    	$divTable .= "<div>\r\n";
    	for($i=1;$i<=2;$i++){
    		$divTable .= "<table class=\"table_list table table-striped table-bordered table-hover tztable\" style=\"width:50%;margin:0\">\r\n";
    		$divTable .= "<tr>\r\n";
    		$divTable .= "<th width='20%'>号码</th>\r\n";
    		$divTable .= "<th width='20%'>赔率</th>\r\n";
    		$divTable .= "<th width='30%'>已投注</th>\r\n";
    		$divTable .= "<th width='30%'>投注</th>\r\n";
    		$divTable .= "</tr>\r\n";
    		$divTable .= "</table>\r\n";
    	}
    	$divTable .= "</div>\r\n";
    	
    	
    	$divTable .= "<div>\r\n";
    	
    	
    	for($i=0;$i<6;$i++){
    		if($i == 0) $j = 1;
    		if($i == 1) $j = 6;
    		if($i == 2) $j = 0;
    		if($i == 3) $j = 5;
    		if($i == 4) $j = 14;
    		if($i == 5) $j = 15;
    		$divTable .= "<table class=\"table_list table table-striped table-bordered table-hover tztable\" style=\"width:50%;margin:0\">\r\n";
	    	$divTable .= "<tr attr=\"{$i}\">\r\n";
	    	$divTable .= "<td width='20%'><div class=\"ds\"><span class=\"ds_{$j}\"></span></div></td>\r\n";
	    	$divTable .= "<td width='20%'>{$arrCurOdds[$i]}</td>\r\n";
	    	$divTable .= "<td width='30%'><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
	    	$divTable .= "<td width='30%'><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
	    	$divTable .= "</tr>\r\n";
	    	$divTable .= "</table>\r\n";
    	}
    	$divTable .= "</div>\r\n";
    	
    	
    	
    	$divTable .= "<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;width:100%;margin-bottom: 0;margin-top:10px;'>\r\n";
    	$divTable .= "<tbody>\r\n";
    	$divTable .= "<tr>\r\n";
    	$divTable .= "<th width='25%'>蔬果一</th>\r\n";
    	$divTable .= "<th width='25%'>蔬果二</th>\r\n";
    	$divTable .= "<th width='25%'>蔬果三</th>\r\n";
    	$divTable .= "<th width='25%'>蔬果四</th>\r\n";
    	$divTable .= "</tr>\r\n";
    	$divTable .= "</tbody>\r\n";
    	$divTable .= "</table>\r\n";
    	
    	
    	
    	$divTable .= "<table border=\"0\" width=\"100%\">\r\n";
    	$divTable .= "<tr>\r\n";
    	
    	for($n=0;$n<4;$n++){
	    	$divTable .= "<td>\r\n";
	    	
	    	$divTable .= "<table class=\"table_list table table-striped table-bordered table-hover tztable\" style=\"width:100%\">\r\n";
	    	$divTable .= "<tr><th width='20%'>号码</th><th width='20%'>赔率</th><th width='30%'>已投注</th><th width='30%'>投注</th></tr>\r\n";

	    	
	    	for($i=6+$n*32;$i<=25+$n*32;$i++){
	    		$j = $i - 6 - $n*32 + 1;
	    		
	    		$divTable .= "<tr attr=\"{$i}\">\r\n";
	    		$divTable .= "<td><i class=\"mh m{$j}\"></i></td>\r\n";
	    		$divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
	    		$divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
	    		$divTable .= "<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
	    		$divTable .= "</tr>\r\n";
	    	}
	    	
	    	for($i=26+$n*32;$i<=37+$n*32;$i++){
	    		if($i - $n*32 == 26) $j = 1;
	    		if($i - $n*32 == 27) $j = 5;
	    		if($i - $n*32 == 28) $j = 14;
	    		if($i - $n*32 == 29) $j = 17;
	    		if($i - $n*32 == 30) $j = 6;
	    		if($i - $n*32 == 31) $j = 0;
	    		if($i - $n*32 == 32) $j = 15;
	    		if($i - $n*32 == 33) $j = 16;
	    		if($i - $n*32 == 34) $j = 18;
	    		if($i - $n*32 == 35) $j = 19;
	    		if($i - $n*32 == 36) $j = 20;
	    		if($i - $n*32 == 37) $j = 21;
	    		 
	    		$divTable .= "<tr attr=\"{$i}\">\r\n";
	    		$divTable .= "<td><div class=\"ds\"><span class=\"ds_{$j}\"></span></div></td>\r\n";
	    		$divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
	    		$divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
	    		$divTable .= "<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
	    		$divTable .= "</tr>\r\n";
	    	}
	    	
	    	
	    	
	    	$divTable .= "</table>\r\n";
	    	
	    	$divTable .= "</td>\r\n";
    	}
    	
    	$divTable .= "</tr>\r\n";
    	$divTable .= "</table>\r\n";
    	
    	
    	$divTable .= "<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;width:100%;margin-bottom: 0;'>\r\n";
    	$divTable .= "<tbody>\r\n";
    	$divTable .= "<tr>\r\n";
    	$divTable .= "<th width='25%'>蔬果五</th>\r\n";
    	$divTable .= "<th width='25%'>蔬果六</th>\r\n";
    	$divTable .= "<th width='25%'>蔬果七</th>\r\n";
    	$divTable .= "<th width='25%'>蔬果八</th>\r\n";
    	$divTable .= "</tr>\r\n";
    	$divTable .= "</tbody>\r\n";
    	$divTable .= "</table>\r\n";
    	 
    	
    	$divTable .= "<table border=\"0\" width=\"100%\">\r\n";
    	$divTable .= "<tr>\r\n";
    	 
    	for($n=0;$n<4;$n++){
    		$divTable .= "<td>\r\n";
    	
    		$divTable .= "<table class=\"table_list table table-striped table-bordered table-hover tztable\" style=\"width:100%\">\r\n";
    		$divTable .= "<tr><th width='20%'>号码</th><th width='20%'>赔率</th><th width='30%'>已投注</th><th width='30%'>投注</th></tr>\r\n";
    		
    		
    		
 			for($i=134+$n*32;$i<=153+$n*32;$i++){
	    		$j = $i - 134 - $n*32 + 1;
	    		
	    		$divTable .= "<tr attr=\"{$i}\">\r\n";
	    		$divTable .= "<td><i class=\"mh m{$j}\"></i></td>\r\n";
	    		$divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
	    		$divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
	    		$divTable .= "<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
	    		$divTable .= "</tr>\r\n";
	    	}
	    	
	    	for($i=154+$n*32;$i<=165+$n*32;$i++){
	    		if($i - $n*32 == 154) $j = 1;
	    		if($i - $n*32 == 155) $j = 5;
	    		if($i - $n*32 == 156) $j = 14;
	    		if($i - $n*32 == 157) $j = 17;
	    		if($i - $n*32 == 158) $j = 6;
	    		if($i - $n*32 == 159) $j = 0;
	    		if($i - $n*32 == 160) $j = 15;
	    		if($i - $n*32 == 161) $j = 16;
	    		if($i - $n*32 == 162) $j = 18;
	    		if($i - $n*32 == 163) $j = 19;
	    		if($i - $n*32 == 164) $j = 20;
	    		if($i - $n*32 == 165) $j = 21;
	    		 
	    		$divTable .= "<tr attr=\"{$i}\">\r\n";
	    		$divTable .= "<td><div class=\"ds\"><span class=\"ds_{$j}\"></span></div></td>\r\n";
	    		$divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
	    		$divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
	    		$divTable .= "<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
	    		$divTable .= "</tr>\r\n";
	    	}
    		
    		
    		
    		
    		$divTable .= "</table>\r\n";
    	
    		$divTable .= "</td>\r\n";
    	}
    	 
    	$divTable .= "</tr>\r\n";
    	$divTable .= "</table>\r\n";
    	
    	
    	$divTable .= "<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;width:100%;margin-bottom: 0;'>\r\n";
    	$divTable .= "<tbody>\r\n";
    	$divTable .= "<tr>\r\n";
    	$divTable .= "<th width='25%'>1V8龙虎</th>\r\n";
    	$divTable .= "<th width='25%'>2V7龙虎</th>\r\n";
    	$divTable .= "<th width='25%'>3V6龙虎</th>\r\n";
    	$divTable .= "<th width='25%'>4V5龙虎</th>\r\n";
    	$divTable .= "</tr>\r\n";
    	$divTable .= "</tbody>\r\n";
    	$divTable .= "</table>\r\n";
    	
    	
    	$divTable .= "<table border=\"0\" width=\"100%\">\r\n";
    	$divTable .= "<tr>\r\n";
    	for($n=0;$n<4;$n++){
    		$i = 261 + $n * 2 + 1;
    		
	    	$divTable .= "<td width='20%'>\r\n";
		    $divTable .= "<table class=\"table_list table table-striped table-bordered table-hover tztable\" style=\"width:100%\">\r\n";
		    $divTable .= "<tr><th width='20%'>号码</th><th width='20%'>赔率</th><th width='30%'>已投注</th><th width='30%'>投注</th></tr>\r\n";
		    $divTable .= "<tr attr=\"{$i}\">\r\n";
		    $divTable .= "<td class=\"sc\"><span class=\"sc lh_0\">0</span></td>\r\n";
		    $divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
		    $divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
		    $divTable .= "<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
		    $divTable .= "</tr>\r\n";
		    
		    
		    $i = 261 + $n * 2 + 2;
		    
		    $divTable .= "<tr attr=\"{$i}\">\r\n";
		    $divTable .= "<td class=\"sc\"><span class=\"sc lh_1\">1</span></td>\r\n";
		    $divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
		    $divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
		    $divTable .= "<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
		    $divTable .= "</tr>\r\n";
		    $divTable .= "</table>\r\n";
	    	$divTable .= "</td>\r\n";
    	}
    	$divTable .= "<tr>\r\n";
    	
    	
    	
    	$divTable .= "</div>\r\n";
    	return $divTable;
    }
    
    
    
    
    function GetTableContentSSC($act,$No){
    	global $db;
    	$tablegame = GetGameTableName($act,"game");
    	$tablegamekg = GetGameTableName($act,"kg_users_tz");
    	 
    	//取赔率
    	$sql = "select game_std_odds as zjpl from game_config where game_type = {$act} limit 1";
    	$result = $db->query($sql);
    	$curOdds = "";
    	if($rs = $db->fetch_array($result))
    	{
    		$curOdds = $rs['zjpl'];
    	}
    	$arrCurOdds = explode('|',$curOdds);
    	for($i=0;$i<count($arrCurOdds);$i++){
    		$arrCurOdds[$i] = number_format($arrCurOdds[$i], 4, '.', '');
    	}
    	 
    	//取已投注
    	$arrHadPress = array();
    	$step = GetFromBeginNumStep($act);
    	for($i = 0; $i < count($arrCurOdds); $i++)
    	{
    		$arrHadPress[] = 0;
    	}
    	$sql = "select tznum,tzpoints from {$tablegamekg} where `NO` = {$No} and uid = {$_SESSION['usersid']}";
    	$result = $db->query($sql);
        while($rs=$db->fetch_array($result))
        {
        	$arrHadPress[$rs['tznum']-$step] = $rs['tzpoints'];
        }
        			 
        	 
        $divTable = "<div class='table'>\r\n";
   
        			 
        	 
        $divTable .= "<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;width:100%;margin-bottom: 0;'>\r\n";
        $divTable .= "<tbody>\r\n";
        $divTable .= "<tr>\r\n";
        $divTable .= "<th>和值-龙虎和</th>\r\n";
        $divTable .= "</tr>\r\n";
        $divTable .= "</tbody>\r\n";
        $divTable .= "</table>\r\n";
        					 
        					 
        $divTable .= "<div>\r\n";
        for($i=1;$i<=2;$i++){
    		$divTable .= "<table class=\"table_list table table-striped table-bordered table-hover tztable\" style=\"width:50%;margin:0\">\r\n";
        	$divTable .= "<tr>\r\n";
        	$divTable .= "<th width='20%'>号码</th>\r\n";
        	$divTable .= "<th width='20%'>赔率</th>\r\n";
        	$divTable .= "<th width='30%'>已投注</th>\r\n";
    		$divTable .= "<th width='30%'>投注</th>\r\n";
        	$divTable .= "</tr>\r\n";
        	$divTable .= "</table>\r\n";
    	}
    	$divTable .= "</div>\r\n";
     
     
    	$divTable .= "<div>\r\n";
     
     
	    for($i=0;$i<7;$i++){
	    	if($i == 0) $j = 1;
	    	if($i == 1) $j = 6;
	    	if($i == 2) $j = 0;
	    	if($i == 3) $j = 5;
	    	if($i == 4) $j = 10;
	    	if($i == 5) $j = 11;
	    	if($i == 6) $j = 13;
	    	$divTable .= "<table class=\"table_list table table-striped table-bordered table-hover tztable\" style=\"width:50%;margin:0\">\r\n";
	        $divTable .= "<tr attr=\"{$i}\">\r\n";
	        $divTable .= "<td width='20%'><div class=\"ds\"><span class=\"ds_{$j}\"></span></div></td>\r\n";
	        $divTable .= "<td width='20%'>{$arrCurOdds[$i]}</td>\r\n";
	        $divTable .= "<td width='30%'><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
	        $divTable .= "<td width='30%'><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
	        $divTable .= "</tr>\r\n";
	        $divTable .= "</table>\r\n";
	    }
        $divTable .= "</div>\r\n";
        	 
        	 
   
    	$divTable .= "<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;width:100%;margin-bottom: 0;margin-top:10px;'>\r\n";
        $divTable .= "<tbody>\r\n";
        $divTable .= "<tr>\r\n";
    	$divTable .= "<th width='20%'>号码一</th>\r\n";
    	$divTable .= "<th width='20%'>号码二</th>\r\n";
    	$divTable .= "<th width='20%'>号码三</th>\r\n";
    	$divTable .= "<th width='20%'>号码四</th>\r\n";
    	$divTable .= "<th width='20%'>号码五</th>\r\n";
        $divTable .= "</tr>\r\n";
        $divTable .= "</tbody>\r\n";
        $divTable .= "</table>\r\n";
        			 
   
   
    	$divTable .= "<table border=\"0\" width=\"100%\">\r\n";
        $divTable .= "<tr>\r\n";
        				 
        for($n=0;$n<5;$n++){
        	$divTable .= "<td>\r\n";
    
	    	$divTable .= "<table class=\"table_list table table-striped table-bordered table-hover tztable\" style=\"width:100%\">\r\n";
	    	$divTable .= "<tr><th width='20%'>号码</th><th width='20%'>赔率</th><th width='30%'>已投注</th><th width='30%'>投注</th></tr>\r\n";
    
	    	for($i=7+$n*14;$i<=10+$n*14;$i++){
	    		if($i - $n*14 == 7) $j = 1;
	    		if($i - $n*14 == 8) $j = 6;
	    		if($i - $n*14 == 9) $j = 0;
	    		if($i - $n*14 == 10) $j = 5;
	    	
	    		$divTable .= "<tr attr=\"{$i}\">\r\n";
	    		$divTable .= "<td><div class=\"ds\"><span class=\"ds_{$j}\"></span></div></td>\r\n";
	    		$divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
	    		$divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
	    		$divTable .= "<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
	    		$divTable .= "</tr>\r\n";
	    	}
    
    	    for($i=11+$n*14;$i<=20+$n*14;$i++){
    	    	$j = $i - 11 - $n*14;
    	    	 
    	    	$divTable .= "<tr attr=\"{$i}\">\r\n";
    	    	$divTable .= "<td><i class=\"mh m{$j}\"></i></td>\r\n";
    	    	$divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
    	    	$divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
    	    	$divTable .= "<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
    	    	$divTable .= "</tr>\r\n";
        	}
    
    
    
        	$divTable .= "</table>\r\n";
    
        	$divTable .= "</td>\r\n";
        }
        			 
        $divTable .= "</tr>\r\n";
        $divTable .= "</table>\r\n";
        			 
        			 
        			 
        $divTable .= "<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;width:100%;margin-bottom: 0;'>\r\n";
        $divTable .= "<tbody>\r\n";
        $divTable .= "<tr>\r\n";
        $divTable .= "<th width='33.333%'>前三</th>\r\n";
        $divTable .= "<th width='33.333%'>中三</th>\r\n";
        $divTable .= "<th width='33.333%'>后三</th>\r\n";
        $divTable .= "</tr>\r\n";
        $divTable .= "</tbody>\r\n";
        $divTable .= "</table>\r\n";
        			 
        			 
        $divTable .= "<table border=\"0\" width=\"100%\">\r\n";
        $divTable .= "<tr>\r\n";
        for($n=0;$n<3;$n++){
        	
    
        	$divTable .= "<td width='20%'>\r\n";
        	$divTable .= "<table class=\"table_list table table-striped table-bordered table-hover tztable\" style=\"width:100%\">\r\n";
        	$divTable .= "<tr><th width='20%'>号码</th><th width='20%'>赔率</th><th width='30%'>已投注</th><th width='30%'>投注</th></tr>\r\n";
        	
        	for($m=1;$m<=5;$m++){
        		$i = 76 + $n * 5 + $m;
        		
	        	$divTable .= "<tr attr=\"{$i}\">\r\n";
	        	$divTable .= "<td class=\"sc\"><i class='zh z{$m}'></i></td>\r\n";
	        	$divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
	        	$divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
	        	$divTable .= "<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
	        	$divTable .= "</tr>\r\n";
        	}
        	
        	$divTable .= "</table>\r\n";
        	$divTable .= "</td>\r\n";
        }
        $divTable .= "<tr>\r\n";
        			 
        			 
        			 
        $divTable .= "</div>\r\n";
        return $divTable;
	}
    
    
    
    
    
    function GetTableContentSC($act,$No){
    	global $db;
    	$tablegame = GetGameTableName($act,"game");
    	$tablegamekg = GetGameTableName($act,"kg_users_tz");
    	
    	//取赔率
    	$sql = "select game_std_odds as zjpl from game_config where game_type = {$act} limit 1";
    	$result = $db->query($sql);
    	$curOdds = "";
    	if($rs = $db->fetch_array($result))
    	{
    		$curOdds = $rs['zjpl'];
    	}
    	$arrCurOdds = explode('|',$curOdds);
    	for($i=0;$i<count($arrCurOdds);$i++){
    		$arrCurOdds[$i] = number_format($arrCurOdds[$i], 4, '.', '');
    	}
    	
    	//取已投注
    	$arrHadPress = array();
    	$step = GetFromBeginNumStep($act);
    	for($i = 0; $i < count($arrCurOdds); $i++)
    	{
    		$arrHadPress[] = 0;
    	}
    	$sql = "select tznum,tzpoints from {$tablegamekg} where `NO` = {$No} and uid = {$_SESSION['usersid']}";
    	$result = $db->query($sql);
    	while($rs=$db->fetch_array($result))
    	{
    		$arrHadPress[$rs['tznum']-$step] = $rs['tzpoints'];
    	}
    	
    	
    	$divTable = "<div class='table'>\r\n";
    	
    	
    	
    	$divTable .= "<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;width:100%;margin-bottom: 0;'>\r\n";
    	$divTable .= "<tbody>\r\n";
    	$divTable .= "<tr>\r\n";
    	$divTable .= "<th>冠亚军和</th>\r\n";
    	$divTable .= "</tr>\r\n";
    	$divTable .= "</tbody>\r\n";
    	$divTable .= "</table>\r\n";
    	
    	
    	$divTable .= "<div>\r\n";
    	for($i=1;$i<=4;$i++){
    		$divTable .= "<table class=\"table_list table table-striped table-bordered table-hover tztable\" style=\"width:25%;margin:0\">\r\n";
    		$divTable .= "<tr>\r\n";
    		$divTable .= "<th width='20%'>号码</th>\r\n";
    		$divTable .= "<th width='20%'>赔率</th>\r\n";
    		$divTable .= "<th width='30%'>已投注</th>\r\n";
    		$divTable .= "<th width='30%'>投注</th>\r\n";
    		$divTable .= "</tr>\r\n";
    		$divTable .= "</table>\r\n";
    	}
    	$divTable .= "</div>\r\n";
    	
    	
    	$divTable .= "<div>\r\n";
    	for($i=0;$i<=16;$i++){
    		$j = $i+3;
	    	
	    	$divTable .= "<table class=\"table_list table table-striped table-bordered table-hover tztable\" style=\"width:25%;margin:0\">\r\n";
	    	$divTable .= "<tr attr=\"{$i}\">\r\n";
	    	$divTable .= "<td width='20%'><i class=\"mh m{$j}\"></i></td>\r\n";
	    	$divTable .= "<td width='20%'>{$arrCurOdds[$i]}</td>\r\n";
	    	$divTable .= "<td width='30%'><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
	    	$divTable .= "<td width='30%'><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
	    	$divTable .= "</tr>\r\n";
	    	$divTable .= "</table>\r\n";
    	}
    	$divTable .= "</div>\r\n";
    	
    	
    	
    	$divTable .= "<div style=\"clear:both\"></div>\r\n";
    	
    	
    	$divTable .= "<div>\r\n";
    	for($i=17;$i<=20;$i++){
    		if($i == 17) $j = 1;
    		if($i == 18) $j = 6;
    		if($i == 19) $j = 0;
    		if($i == 20) $j = 5;
    		
    		$divTable .= "<table class=\"table_list table table-striped table-bordered table-hover tztable\" style=\"width:25%;margin:0\">\r\n";
    		$divTable .= "<tr attr=\"{$i}\">\r\n";
    		$divTable .= "<td width='20%'><div class=\"ds\"><span class=\"ds_{$j}\"></span></div></td>\r\n";
    		$divTable .= "<td width='20%'>{$arrCurOdds[$i]}</td>\r\n";
    		$divTable .= "<td width='30%'><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
    		$divTable .= "<td width='30%'><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
    		$divTable .= "</tr>\r\n";
    		$divTable .= "</table>\r\n";
    	}
    	$divTable .= "</div>\r\n";
    	
    	
    	
    	$divTable .= "<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;width:100%;margin-bottom: 0;margin-top:10px;'>\r\n";
    	$divTable .= "<tbody>\r\n";
    	$divTable .= "<tr>\r\n";
    	$divTable .= "<th width='20%'>赛车一</th>\r\n";
    	$divTable .= "<th width='20%'>赛车二</th>\r\n";
    	$divTable .= "<th width='20%'>赛车三</th>\r\n";
    	$divTable .= "<th width='20%'>赛车四</th>\r\n";
    	$divTable .= "<th width='20%'>赛车五</th>\r\n";
    	$divTable .= "</tr>\r\n";
    	$divTable .= "</tbody>\r\n";
    	$divTable .= "</table>\r\n";
    	
    	
    	
    	$divTable .= "<table border=\"0\" width=\"100%\">\r\n";
    	$divTable .= "<tr>\r\n";
    	
    	for($n=0;$n<5;$n++){
	    	$divTable .= "<td>\r\n";
	    	
	    	$divTable .= "<table class=\"table_list table table-striped table-bordered table-hover tztable\" style=\"width:100%\">\r\n";
	    	$divTable .= "<tr><th width='20%'>号码</th><th width='20%'>赔率</th><th width='30%'>已投注</th><th width='30%'>投注</th></tr>\r\n";
	    	for($i=21+$n*14;$i<=24+$n*14;$i++){
	    		if($i - $n*14 == 21) $j = 1;
	    		if($i - $n*14 == 22) $j = 6;
	    		if($i - $n*14 == 23) $j = 0;
	    		if($i - $n*14 == 24) $j = 5;
	    		
		    	$divTable .= "<tr attr=\"{$i}\">\r\n";
		    	$divTable .= "<td><div class=\"ds\"><span class=\"ds_{$j}\"></span></div></td>\r\n";
		    	$divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
		    	$divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
		    	$divTable .= "<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
		    	$divTable .= "</tr>\r\n";
	    	}
	    	
	    	for($i=25+$n*14;$i<=34+$n*14;$i++){
	    		$j = $i - 25 - $n*14 + 1;
	    		
	    		$divTable .= "<tr attr=\"{$i}\">\r\n";
	    		$divTable .= "<td><i class=\"mh m{$j}\"></i></td>\r\n";
	    		$divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
	    		$divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
	    		$divTable .= "<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
	    		$divTable .= "</tr>\r\n";
	    	}
	    	$divTable .= "</table>\r\n";
	    	
	    	$divTable .= "</td>\r\n";
    	}
    	
    	$divTable .= "</tr>\r\n";
    	$divTable .= "</table>\r\n";
    	
    	
    	$divTable .= "<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;width:100%;margin-bottom: 0;'>\r\n";
    	$divTable .= "<tbody>\r\n";
    	$divTable .= "<tr>\r\n";
    	$divTable .= "<th width='20%'>赛车六</th>\r\n";
    	$divTable .= "<th width='20%'>赛车七</th>\r\n";
    	$divTable .= "<th width='20%'>赛车八</th>\r\n";
    	$divTable .= "<th width='20%'>赛车九</th>\r\n";
    	$divTable .= "<th width='20%'>赛车十</th>\r\n";
    	$divTable .= "</tr>\r\n";
    	$divTable .= "</tbody>\r\n";
    	$divTable .= "</table>\r\n";
    	 
    	
    	$divTable .= "<table border=\"0\" width=\"100%\">\r\n";
    	$divTable .= "<tr>\r\n";
    	 
    	for($n=0;$n<5;$n++){
    		$divTable .= "<td>\r\n";
    	
    		$divTable .= "<table class=\"table_list table table-striped table-bordered table-hover tztable\" style=\"width:100%\">\r\n";
    		$divTable .= "<tr><th width='20%'>号码</th><th width='20%'>赔率</th><th width='30%'>已投注</th><th width='30%'>投注</th></tr>\r\n";
    		for($i=91+$n*14;$i<=94+$n*14;$i++){
    			if($i - $n*14 == 91) $j = 1;
    			if($i - $n*14 == 92) $j = 6;
    			if($i - $n*14 == 93) $j = 0;
    			if($i - $n*14 == 94) $j = 5;
    			
		    	$divTable .= "<tr attr=\"{$i}\">\r\n";
		    	$divTable .= "<td><div class=\"ds\"><span class=\"ds_{$j}\"></span></div></td>\r\n";
		    	$divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
		    	$divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
		    	$divTable .= "<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
		    	$divTable .= "</tr>\r\n";
    		}
    	
    		for($i=95+$n*14;$i<=104+$n*14;$i++){
    			$j = $i - 95 - $n*14 + 1;
    			
	    		$divTable .= "<tr attr=\"{$i}\">\r\n";
	    		$divTable .= "<td><i class=\"mh m{$j}\"></i></td>\r\n";
	    		$divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
	    		$divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
	    		$divTable .= "<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
	    		$divTable .= "</tr>\r\n";
    		}
    		$divTable .= "</table>\r\n";
    	
    		$divTable .= "</td>\r\n";
    	}
    	 
    	$divTable .= "</tr>\r\n";
    	$divTable .= "</table>\r\n";
    	
    	
    	$divTable .= "<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;width:100%;margin-bottom: 0;'>\r\n";
    	$divTable .= "<tbody>\r\n";
    	$divTable .= "<tr>\r\n";
    	$divTable .= "<th width='20%'>1V10龙虎</th>\r\n";
    	$divTable .= "<th width='20%'>2V9龙虎</th>\r\n";
    	$divTable .= "<th width='20%'>3V8龙虎</th>\r\n";
    	$divTable .= "<th width='20%'>4V7龙虎</th>\r\n";
    	$divTable .= "<th width='20%'>5V6龙虎</th>\r\n";
    	$divTable .= "</tr>\r\n";
    	$divTable .= "</tbody>\r\n";
    	$divTable .= "</table>\r\n";
    	
    	
    	$divTable .= "<table border=\"0\" width=\"100%\">\r\n";
    	$divTable .= "<tr>\r\n";
    	for($n=0;$n<5;$n++){
    		$i = 160 + $n * 2 + 1;
    		
	    	$divTable .= "<td width='20%'>\r\n";
		    $divTable .= "<table class=\"table_list table table-striped table-bordered table-hover tztable\" style=\"width:100%\">\r\n";
		    $divTable .= "<tr><th width='20%'>号码</th><th width='20%'>赔率</th><th width='30%'>已投注</th><th width='30%'>投注</th></tr>\r\n";
		    $divTable .= "<tr attr=\"{$i}\">\r\n";
		    $divTable .= "<td class=\"sc\"><span class=\"sc lh_0\">0</span></td>\r\n";
		    $divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
		    $divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
		    $divTable .= "<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
		    $divTable .= "</tr>\r\n";
		    
		    
		    $i = 160 + $n * 2 + 2;
		    
		    $divTable .= "<tr attr=\"{$i}\">\r\n";
		    $divTable .= "<td class=\"sc\"><span class=\"sc lh_1\">1</span></td>\r\n";
		    $divTable .= "<td>{$arrCurOdds[$i]}</td>\r\n";
		    $divTable .= "<td><i id='odds_{$i}' >{$arrHadPress[$i]}</i></td>\r\n";
		    $divTable .= "<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
		    $divTable .= "</tr>\r\n";
		    $divTable .= "</table>\r\n";
	    	$divTable .= "</td>\r\n";
    	}
    	$divTable .= "<tr>\r\n";
    	
    	
    	
    	$divTable .= "</div>\r\n";
    	return $divTable;
    }
    
    
    
    /* 取号码表格
    *
    */
    function GetTableContent($act,$No,$LastNo)
    {
    	global $db;
    	$tablegame = GetGameTableName($act,"game");
    	$tablegamekg = GetGameTableName($act,"kg_users_tz");
		//号码表格
		//取赔率
		
    	//if(in_array($act , [0,1,2,15,22,23,24])){
    		$sql = "SELECT game_std_odds as zjpl,game_go_samples FROM game_config WHERE game_type='{$act}'";
    		$result = $db->query($sql);
    		$rs = $db->fetch_array($result);
    		$arrCurOdds = explode('|',$rs['zjpl']);
    		$arrLastOdds = explode('|',$rs['zjpl']);
    		$game_go_samples = mt_rand(0, $rs['game_go_samples']);
    	/* }else{
			$sql = "select zjpl from {$tablegame} where id in({$No},{$LastNo}) order by id";
			$result = $db->query($sql);
			$curOdds = "";
			$lastOdds = $curOdds;
			if($rs = $db->fetch_array($result))
			{
				 $lastOdds = ($rs['zjpl'] == null) ? $lastOdds : $rs['zjpl'];
				 if($rs = $db->fetch_array($result))
				 {
					 $curOdds = ($rs['zjpl'] == null) ? $curOdds : $rs['zjpl'];
				 }
			}
			$arrCurOdds = explode('|',$curOdds);
			$arrLastOdds = explode('|',$lastOdds);
    	} */
		//WriteLog($curOdds);
		//print_r($arrCurOdds);print_r($arrLastOdds);
		//取已投注
		$arrHadPress = array();
		$step = GetFromBeginNumStep($act);
		for($i = 0; $i < count($arrCurOdds); $i++)
		{
			$arrHadPress[] = 0;
			$arrCurOdds[$i] = number_format($arrCurOdds[$i] * (1 - $game_go_samples/10000), 4, '.', '');
		}
		$sql = "select tznum,tzpoints from {$tablegamekg} where `NO` = {$No} and uid = {$_SESSION['usersid']}";
		$result = $db->query($sql);
		while($rs=$db->fetch_array($result))
		{
			$arrHadPress[$rs['tznum']-$step] = $rs['tzpoints'];
		}
		//
		$trContent1 = "";
		$trContent2 = "";
		
		for($i = 0; $i < count($arrCurOdds); $i++)
		{
			$RewardNum = $i + $step;
				
			if($i < count($arrCurOdds)/2)
			{     
				$trContent1 .= "\t\t\t<tr>\r\n";
				if(in_array($act,array(16,47))) //pk龙虎 飞艇龙虎
				{
					$trContent1 .= "\t\t\t\t<td><i class='lh n1'></i></td>\r\n";
				}
				else if(in_array($act,array(11,12,13,21,23)))
				{
					$NumberNameStr = "";
					switch($RewardNum)
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
					$trContent1 .= "\t\t\t\t<td><i class='zh z$RewardNum'></i></td>\r\n";
				}
				else
				{
					$trContent1 .= "\t\t\t\t<td><i class=\"mh m{$RewardNum}\"></i></td>\r\n";
				}
				
				/* if(!in_array($act , [0,1,2,15,22,23,24])){
					$trContent1 .= "\t\t\t\t<td>{$arrLastOdds[$i]}</td>\r\n";
				} */
				
				$trContent1 .= "\t\t\t\t<td><i id='odds_{$i}' >{$arrCurOdds[$i]}</i></td>\r\n";
				$trContent1 .= "\t\t\t\t<td>{$arrHadPress[$i]}</td>\r\n";
				$trContent1 .= "\t\t\t\t<td><input id='tbChk{$i}' type='checkbox' name='tbChk' onclick=\"insert(this,'tbNum{$i}')\"></td>\r\n";
				$trContent1 .= "\t\t\t\t<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
				$trContent1 .= "\t\t\t\t<td><a class='multiple' href=\"javascript:chgTimes('tbNum{$i}',0.5)\">.5</a>
				                          <a class='multiple' href=\"javascript:chgTimes('tbNum{$i}',2)\">2</a>
				                          <a class='multiple' href=\"javascript:chgTimes('tbNum{$i}',10)\">10</a>
							 </td>\r\n";
				$trContent1 .= "\t\t\t</tr>\r\n";
			}
			else
			{
				$trContent2 .= "\t\t\t<tr>\r\n";
				if(in_array($act,array(16,47))) //pk龙虎 飞艇龙虎
				{
				    $trContent2 .= "\t\t\t\t<td><i class='lh n2'></i></td>\r\n";
				}
				else if(in_array($act,array(11,12,13,21,23)))
				{
					$NumberNameStr = "";
					switch($RewardNum)
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
					$trContent2 .= "\t\t\t\t<td><i class='zh z$RewardNum'></i></td>\r\n";
				}
				else
				{
					$trContent2 .= "\t\t\t\t<td><i class=\"mh m{$RewardNum}\"></i></td>\r\n";
				}
				
				/* if(!in_array($act , [0,1,2,15,22,23,24])){
					$trContent2 .= "\t\t\t\t<td>{$arrLastOdds[$i]}</td>\r\n";
				} */
				
				$trContent2 .= "\t\t\t\t<td><i id='odds_{$i}' >{$arrCurOdds[$i]}</i></td>\r\n";
				$trContent2 .= "\t\t\t\t<td>{$arrHadPress[$i]}</td>\r\n";
				$trContent2 .= "\t\t\t\t<td><input id='tbChk{$i}' type='checkbox' name='tbChk' onclick=\"insert(this,'tbNum{$i}')\"></td>\r\n";
				$trContent2 .= "\t\t\t\t<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
				$trContent2 .= "\t\t\t\t<td><a class='multiple' href=\"javascript:chgTimes('tbNum{$i}',0.5)\">.5</a>
				                          <a class='multiple' href=\"javascript:chgTimes('tbNum{$i}',2)\">2</a>
				                          <a class='multiple' href=\"javascript:chgTimes('tbNum{$i}',10)\">10</a>
							 </td>\r\n";
				$trContent2 .= "\t\t\t</tr>\r\n";
			}
		}
		$divTable = "<div class='table'>\r\n";
		$divTable .= "\t<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;color:#000000;'>\r\n";
		$divTable .= "\t\t<tbody>\r\n";
		$divTable .= "\t\t\t<tr>\r\n";
		$divTable .= "\t\t\t\t<th width='50'>号码</th>\r\n";
		
		//if(in_array($act , [0,1,2,15,22,23,24])){
			$divTable .= "\t\t\t\t<th width='70'>赔率</th>\r\n";
		/* }else{
			$divTable .= "\t\t\t\t<th width='70'>上期赔率</th>\r\n";
			$divTable .= "\t\t\t\t<th width='70'>当前赔率</th>\r\n";
		} */
		

		$divTable .= "\t\t\t\t<th width='60'>已投注</th>\r\n";
		$divTable .= "\t\t\t\t<th width='40'>选择</th>\r\n";
		$divTable .= "\t\t\t\t<th>投注</th>\r\n";
		$divTable .= "\t\t\t\t<th>倍数</th>\r\n";
		$divTable .= "\t\t\t</tr>\r\n";
		//1 tr
		$divTable .= $trContent1;
		$divTable .= "\t\t</tbody>\r\n";
		$divTable .= "\t</table>";
		
		$divTable .= "\t<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;color:#000000;'>\r\n";
		$divTable .= "\t\t<tbody>\r\n";
		$divTable .= "\t\t\t<tr>\r\n";
		$divTable .= "\t\t\t\t<th width='50'>号码</th>\r\n";
		
		//if(in_array($act , [0,1,2,15,22,23,24])){
			$divTable .= "\t\t\t\t<th width='70'>赔率</th>\r\n";
		/* }else{
			$divTable .= "\t\t\t\t<th width='70'>上期赔率</th>\r\n";
			$divTable .= "\t\t\t\t<th width='70'>当前赔率</th>\r\n";
		} */

		$divTable .= "\t\t\t\t<th width='60'>已投注</th>\r\n";
		$divTable .= "\t\t\t\t<th width='40'>选择</th>\r\n";
		$divTable .= "\t\t\t\t<th>投注</th>\r\n";
		$divTable .= "\t\t\t\t<th>倍数</th>\r\n";
		$divTable .= "\t\t\t</tr>\r\n";
		//2 tr
		$divTable .= $trContent2;
		
		$divTable .= "\t\t</tbody>\r\n";
		$divTable .= "\t</table>\r\n";
		$divTable .= "</div>\r\n";
		
		return $divTable;
    }
    
    
    
    /* 取号码表格
    *
    */
    function GetTableContentGD28($act,$No)
    {
    	global $db;
    	$tablegame = GetGameTableName($act,"game");
    	$tablegamekg = GetGameTableName($act,"kg_users_tz");
		//号码表格
		//取赔率
        
    	$sql = "select game_std_odds as zjpl from game_config where game_type = {$act} limit 1";
    	$result = $db->query($sql);
    	$curOdds = "";
    	if($rs = $db->fetch_array($result))
    	{
    		$curOdds = $rs['zjpl'];
    	}
    	$arrCurOdds = explode('|',$curOdds);
    	for($i=0;$i<count($arrCurOdds);$i++){
    		$arrCurOdds[$i] = number_format($arrCurOdds[$i], 4, '.', '');
    	}
		
		
		//取已投注
		$arrHadPress = array();
		$step = GetFromBeginNumStep($act);
		for($i = 0; $i < count($arrCurOdds); $i++)
		{
			$arrHadPress[] = 0;
		}
		$sql = "select tznum,tzpoints from {$tablegamekg} where `NO` = {$No} and uid = {$_SESSION['usersid']}";
		$result = $db->query($sql);
		while($rs=$db->fetch_array($result))
		{
			$arrHadPress[$rs['tznum']-$step] = $rs['tzpoints'];
		}
		//
		$trContent1 = "";
		$trContent2 = "";
		
		for($i = 0; $i < count($arrCurOdds); $i++)
		{
			$RewardNum = $i + $step;
				
			if($i < count($arrCurOdds)/2)
			{     
				$trContent1 .= "\t\t\t<tr>\r\n";
				$trContent1 .= "\t\t\t\t<td><i class=\"mh m{$RewardNum}\"></i></td>\r\n";
				$trContent1 .= "\t\t\t\t<td><i id='odds_{$i}' >{$arrCurOdds[$i]}</i></td>\r\n";
				$trContent1 .= "\t\t\t\t<td>{$arrHadPress[$i]}</td>\r\n";
				$trContent1 .= "\t\t\t\t<td><input id='tbChk{$i}' type='checkbox' name='tbChk' onclick=\"insert(this,'tbNum{$i}')\"></td>\r\n";
				$trContent1 .= "\t\t\t\t<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
				$trContent1 .= "\t\t\t\t<td><a class='multiple' href=\"javascript:chgTimes('tbNum{$i}',0.5)\">.5</a>
				                          <a class='multiple' href=\"javascript:chgTimes('tbNum{$i}',2)\">2</a>
				                          <a class='multiple' href=\"javascript:chgTimes('tbNum{$i}',10)\">10</a>
							 </td>\r\n";
				$trContent1 .= "\t\t\t</tr>\r\n";
			}
			else
			{
				$trContent2 .= "\t\t\t<tr>\r\n";
				$trContent2 .= "\t\t\t\t<td><i class=\"mh m{$RewardNum}\"></i></td>\r\n";
				$trContent2 .= "\t\t\t\t<td><i id='odds_{$i}' >{$arrCurOdds[$i]}</i></td>\r\n";
				$trContent2 .= "\t\t\t\t<td>{$arrHadPress[$i]}</td>\r\n";
				$trContent2 .= "\t\t\t\t<td><input id='tbChk{$i}' type='checkbox' name='tbChk' onclick=\"insert(this,'tbNum{$i}')\"></td>\r\n";
				$trContent2 .= "\t\t\t\t<td><input id='tbNum{$i}' type='text' style='width:60px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
				$trContent2 .= "\t\t\t\t<td><a class='multiple' href=\"javascript:chgTimes('tbNum{$i}',0.5)\">.5</a>
				                          <a class='multiple' href=\"javascript:chgTimes('tbNum{$i}',2)\">2</a>
				                          <a class='multiple' href=\"javascript:chgTimes('tbNum{$i}',10)\">10</a>
							 </td>\r\n";
				$trContent2 .= "\t\t\t</tr>\r\n";
			}
		}
		$divTable = "<div class='table'>\r\n";
		$divTable .= "\t<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;color:#000000;'>\r\n";
		$divTable .= "\t\t<tbody>\r\n";
		$divTable .= "\t\t\t<tr>\r\n";
		$divTable .= "\t\t\t\t<th width='50'>号码</th>\r\n";
		$divTable .= "\t\t\t\t<th width='90'>赔率</th>\r\n";
		$divTable .= "\t\t\t\t<th width='90'>已投注</th>\r\n";
		$divTable .= "\t\t\t\t<th width='40'>选择</th>\r\n";
		$divTable .= "\t\t\t\t<th>投注</th>\r\n";
		$divTable .= "\t\t\t\t<th>倍数</th>\r\n";
		$divTable .= "\t\t\t</tr>\r\n";
		//1 tr
		$divTable .= $trContent1;
		$divTable .= "\t\t</tbody>\r\n";
		$divTable .= "\t</table>";
		
		$divTable .= "\t<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;color:#000000;'>\r\n";
		$divTable .= "\t\t<tbody>\r\n";
		$divTable .= "\t\t\t<tr>\r\n";
		$divTable .= "\t\t\t\t<th width='50'>号码</th>\r\n";
		$divTable .= "\t\t\t\t<th width='90'>赔率</th>\r\n";
		$divTable .= "\t\t\t\t<th width='90'>已投注</th>\r\n";
		$divTable .= "\t\t\t\t<th width='40'>选择</th>\r\n";
		$divTable .= "\t\t\t\t<th>投注</th>\r\n";
		$divTable .= "\t\t\t\t<th>倍数</th>\r\n";
		$divTable .= "\t\t\t</tr>\r\n";
		//2 tr
		$divTable .= $trContent2;
		
		$divTable .= "\t\t</tbody>\r\n";
		$divTable .= "\t</table>\r\n";
		$divTable .= "</div>\r\n";
		
		return $divTable;
    }
    
    /* 取得JS
    * 
    */
    function GetJSContent($act,$no)
    {
    	global $db;
    	$sql = "select game_table_prefix,game_press_min,game_press_max,game_std_press from game_config where game_type = '{$act}'";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
			$gameType = count(explode(',',$rs['game_std_press']));
			$pressNum = $rs['game_std_press'];
			$press_min = $rs['game_press_min'];
			$press_max = $rs['game_press_max'];
			$sql='select SUM(tzpoints) as points from '.$rs['game_table_prefix'].'_kg_users_tz where NO='.$no.' and uid='.$_SESSION['usersid'];
			$res = $db->query($sql);
			if($row = $db->fetch_array($res))
			{
				$press_max-=$row['points'];

			}
    	}

		$js = "<script type=\"text/javascript\">";
		$js .= "
			var MAX_SCORE = {$press_max};
			var MIN_SCORE = {$press_min};
			var MY_SCORE = {$_SESSION['points']};
			var GTYPE = {$gameType};
			var PRESSNUM = '{$pressNum}';
			var game_id='{$act}';
			
			function getpressinfo(id,ftype)
			{
				$.post('sgameservice.php',{act:'getmodelpress',gtype:{$act},id:id,ftype:ftype},function(ret){
			   		switch(ret.cmd)
			   		{
			   			case 'ok':
			   				init();
			   				setPressInfo(ret.msg);
			   				break;
			   			default:
			   				break;
			   		}
			   },'json');
			}
			function setPressInfo(info)
			{
				var tmpTotal = 0;
			   	var arrPress = info.split(',');
			   	for(var i = 0; i < arrPress.length; i++)
			   	{
			   		if(parseInt(arrPress[i]) > 0)
			   		{
			   			$('#tbChk' + i).attr('checked',true);
	    				$('#tbNum' + i).val(arrPress[i]);
	    				tmpTotal += parseInt(arrPress[i]);
			   		}	
			   	}
			   	$('#tbTotal').html(tmpTotal);
			}
		";
		
		if(in_array($act , [25,26,27,28,29,30,31,36,37,41,42])){//外围 定位 赛车 农场
			$js .= '
			$(".tztable tr").click(function(){
			    var theNum = $(this).attr("attr");
			    var data = PRESSNUM.split(",");
				if($(this).hasClass("hover")){
					$(this).removeClass("hover");
					$(this).find(":input").val(0)
				}else{

				$(this).addClass("hover");

				$(this).find(":input").val(data[theNum])
				}
				$(this).find(":input").focus()
				if($("#tbTotal").html() == "")
			        {
			            $("#tbTotal").html(0);
			        }
			        $("#tbTotal").html(parseInt($("#tbTotal").html()) + parseInt(data[theNum]));
			})
					';
		}
		
		$js .= "</script>\r\n";
		return $js;
    }

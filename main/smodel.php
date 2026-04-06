<?php
	include_once("inc/conn.php");
    include_once("inc/function.php");
    
    if(!isset($_SESSION['usersid'])) {
		echo "您还没登录或者链接超时，请先去<a href='/login.php'>登录</a>!";
		exit;
	}
	
	
	$act = intval($_GET['act']);
	//返回界面
	GetGameModelContent($act);
	
    /* 取得界面
    * 
    */
    function GetGameModelContent($act)
    {
		$sid = intval($_GET['sid']);
		$No = intval($_GET['no']);
		
		$RetContent = "<div class='choice'>\r\n";
		$RetContent .= "\t<div class='Content'>\r\n";
		//取得子菜单
		$RetContent .= GetSubMenu($act,$sid);
		//取得投注模式
		$RetContent .= GetModeType($act);
		//投注按钮
		$RetContent .= GetButtonContent($act);
		//取押注倍数
		$RetContent .= GetTimesContent($act,$No,$No-1);
		$RetContent .= "\t</div>\r\n"; //content结束
		//取号码表格
		$RetContent .= GetTableContent($act);
		
		$RetContent .= "</div>\r\n";
		//js 定义
		$RetContent .= GetJSContent($act,$No); 
		$RetContent .= "<script type='text/javascript' src='js/game28.js'>\r\n";
		echo $RetContent;
		exit;
    }
    
    /*取得投注模式头
    *
    */
    function GetModeType($act)
    {
		global $db;
		$tableautotz = GetGameTableName($act,"auto_tz");
		$sql = "SELECT id,tzname FROM {$tableautotz} WHERE uid = '{$_SESSION['usersid']}'";
		$option_text = "\t\t\t\t\t<option value='0' selected='selected'>--新建模式--</option>\r\n";
		$result = $db->query($sql);
		while($rs = $db->fetch_array($result))
		{
			$option_text .= "<option value='{$rs['id']}'>". ChangeEncodeG2U($rs['tzname']) ."</option>\r\n";
		}
		$divMode = "\t\t<div class='titles'>\r\n";
		$divMode .= "\t\t\t<p class='editor'>投注模式编辑</p>\r\n";
		
		$divMode .= "\t\t\t<ul class='new'>\r\n";
		$divMode .= "\t\t\t\t<li>选择投注模式：";
		$divMode .= "\t\t\t\t<select id='sltMode'>{$option_text}</select>\r\n";
		$divMode .= "\t\t\t\t</li>\r\n";
		$divMode .= "\t\t\t\t<li>模式名称：<input id='txtModelName' type='text' maxlength='30' value='模式1' /> <button type='button' id='btnremove'>删除</button>\r\n";
		$divMode .= "\t\t\t\t</li>\r\n";
		$divMode .= "\t\t\t</ul>\r\n";
		$divMode .= "\t\t</div>\r\n";
		
		return $divMode;
    }
    
    /* 取得押注按钮定义
    *
    */
    function GetButtonContent($act)
    {
		if(in_array($act,array(11,12,13,21,16,23))) {
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
		if(!in_array($act,array(11,12,13,21,16,23))) {
			$divMode = "<div class='mode'>\r\n";
			//$divMode = "<p class=\"editor\">标准投注模式</p>";
			$divMode .= "\t<p>标准投注模式</p>\r\n";
			$divMode .= "\t<ul>\r\n";
			$divMode .= "\t\t<li><a onclick='javascript:useModel(2)'>单</a></li>\r\n";
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
		return $divMode;
    }
    
    /* 取押注倍数
    *
    */
    function GetTimesContent($act,$No,$LastNo)
    {
		//倍数
		$divTimes = "<div class='times'>\r\n";
		$divTimes .= "\t<input type='hidden' id='hidTimes' value='1' />\r\n"; 
		$divTimes .= "\t<ul class='power'>\r\n";
		$divTimes .= "\t\t<li><a onclick='javascript:chips(10000,1)'><img src=\"image/chips_10000.png\" alt=\"1000\" /></a></li>\r\n";
		$divTimes .= "\t\t<li><a onclick='javascript:chips(100000,2)'><img src=\"image/chips_100000.png\" alt=\"1000\" /></a></li>\r\n";
		$divTimes .= "\t\t<li><a onclick='javascript:chips(500000,3)'><img src=\"image/chips_500000.png\" alt=\"1000\" /></a></li>\r\n";
		$divTimes .= "\t\t<li><a onclick='javascript:chips(1000000,4)'><img src=\"image/chips_1000000.png\" alt=\"1000\" /></a></li>\r\n";
		$divTimes .= "\t\t<li><a onclick='javascript:chips(5000000,5)'><img src=\"image/chips_5000000.png\" alt=\"1000\" /></a></li>\r\n";
		$divTimes .= "\t</ul>\r\n";
		
		
		
		$divTimes .= "\t<ul class='self input-group'>\r\n";
		$divTimes .= "\t\t<li><input type=\"text\" class=\"form-control\" id=\"betsLeft\"></li>\r\n";
		$divTimes .= "\t\t<a onclick=\"javascript:usefenpei()\" class=\"input-group-addon\">定额梭哈</a></li>\r\n";
		$divTimes .= "\t</ul>\r\n";
		
		$divTimes .= "\t<ul class='depressed'>\r\n";
		$divTimes .= "\t\t<li><a onclick='javascript:RefreshOdds({$act},{$No})'>刷新赔率</a></li>\r\n";
		$divTimes .= "\t\t<li><a onclick='javascript:LastPress({$act},{$LastNo})'>上次投注</a></li>\r\n";
		$divTimes .= "\t\t<li><a onclick='javascript:useModel(0)'>全 包</a></li>\r\n";
		$divTimes .= "\t\t<li><a onclick='javascript:useSuoha()'>梭 哈</a></li>\r\n";
		$divTimes .= "\t\t<li><a onclick='javascript:subSelect()'>反 选</a></li>\r\n";
		$divTimes .= "\t\t<li class='not'><a href='javascript:init()'>清 除</a></li>\r\n";
		$divTimes .= "\t</ul>\r\n";
						
		$divTimes .= "\t<ul class='total'>\r\n";
		$divTimes .= "\t\t<li class='t'>\r\n";
		$divTimes .= "总投注: <i id='tbTotal'>0</i>";
		$divTimes .= "\t\t</li>\r\n";
		$divTimes .= "\t\t<li><a onclick='javascript:SaveModel({$act})' class=\"btn btn-info\">保存</a></li>\r\n";
		$divTimes .= "\t</ul>\r\n";

		
		$divTimes .= "</div>";
		return $divTimes;
    }
    
    /* 取号码表格
    *
    */
    function GetTableContent($act)
    {
    	global $db;
    	$tablegame = GetGameTableName($act,"game");
		//取赔率
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
		//print_r($arrStdNums);print_r($arrStdOdds);
		//表格数据
		$trContent1 = "";
		$trContent2 = "";
		for($i = 0; $i < count($arrStdNums); $i++)
		{  
			if($i < count($arrStdNums)/2)
			{     
				$trContent1 .= "\t\t\t<tr>\r\n";
				if(in_array($act,array(16,47))) //pk龙虎 飞艇龙虎
				{
					//$trContent1 .= "\t\t\t\t<td class='number'>龙</td>\r\n";
					$trContent1 .= "\t\t\t\t<td ><i class='lh n1'></i></td>\r\n";
					
				}
				else if(in_array($act,array(11,12,13,21,23)))
				{
					$NumberNameStr = "";
					$str_num="\t\t\t\t<td class='number'>{$NumberNameStr}</td>\r\n";
					switch($arrStdNums[$i])
					{
						case 1:
							$NumberNameStr = "豹";
							$str_num="\t\t\t\t<td  ><i class='zh z1'></i></td>\r\n";
							break;
						case 2:
							$NumberNameStr = "对";
							$str_num="\t\t\t\t<td  ><i class='zh z2'></i></td>\r\n";
							break;
						case 3:
							$NumberNameStr = "顺";
							$str_num="\t\t\t\t<td  ><i class='zh z3'></i></td>\r\n";
							break;
						case 4:
							$NumberNameStr = "半";
							$str_num="\t\t\t\t<td  ><i class='zh z4'></i></td>\r\n";
							break;
						case 5:
							$NumberNameStr = "杂";
							$str_num="\t\t\t\t<td  ><i class='zh z5'></i></td>\r\n";
							break;
						default:
							break;
					}
					$trContent1 .= $str_num;
				}
				else
				{
					$trContent1 .= "\t\t\t\t<td><i class=\"mh m{$arrStdNums[$i]}\"></i></td>\r\n";
				}
				$trContent1 .= "\t\t\t\t<td>{$arrStdOdds[$i]}</td>\r\n";
				$trContent1 .= "\t\t\t\t<td><input id='tbChk{$i}' type='checkbox' name='tbChk' onclick=\"insert(this,'tbNum{$i}')\"></td>\r\n";
				$trContent1 .= "\t\t\t\t<td><input id='tbNum{$i}' type='text' style='width:80px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
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
					//$trContent2 .= "\t\t\t\t<td class='number'>虎</td>\r\n";
					$trContent2 .= "\t\t\t\t<td ><i class='lh n2'></i></td>\r\n";
				}
				else if(in_array($act,array(11,12,13,21,23)))
				{
					$NumberNameStr = "";
					$NumberCssStr = "\t\t\t\t<td class='number'>{$NumberNameStr}</td>\r\n";
					switch($arrStdNums[$i])
					{
						case 1:
							$NumberNameStr = "豹";
							$NumberCssStr = "\t\t\t\t<td ><i class='zh z1'></i></td>\r\n";
							break;
						case 2:
							$NumberNameStr = "对";
							$NumberCssStr = "\t\t\t\t<td ><i class='zh z2'></i></td>\r\n";
							break;
						case 3:
							$NumberNameStr = "顺";
							$NumberCssStr = "\t\t\t\t<td ><i class='zh z3'></i></td>\r\n";
							break;
						case 4:
							$NumberNameStr = "半";
							$NumberCssStr = "\t\t\t\t<td ><i class='zh z4'></i></td>\r\n";
							break;
						case 5:
							$NumberNameStr = "杂";
							$NumberCssStr = "\t\t\t\t<td ><i class='zh z5'></i></td>\r\n";
							break;
						default:
							break;
					}
					$trContent2 .= $NumberCssStr;
				}
				else
				{
					$trContent2 .= "\t\t\t\t<td><i class=\"mh m{$arrStdNums[$i]}\"></i></td>\r\n";
				}
				$trContent2 .= "\t\t\t\t<td>{$arrStdOdds[$i]}</td>\r\n";
				$trContent2 .= "\t\t\t\t<td><input id='tbChk{$i}' type='checkbox' name='tbChk' onclick=\"insert(this,'tbNum{$i}')\"></td>\r\n";
				$trContent2 .= "\t\t\t\t<td><input id='tbNum{$i}' type='text' style='width:80px;' value='' name='tbNum[]' onBlur=\"input(this,'tbChk{$i}')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\"></td>\r\n";
				$trContent2 .= "\t\t\t\t<td><a class='multiple' href=\"javascript:chgTimes('tbNum{$i}',0.5)\">.5</a>
				                          <a class='multiple' href=\"javascript:chgTimes('tbNum{$i}',2)\">2</a>
				                          <a class='multiple' href=\"javascript:chgTimes('tbNum{$i}',10)\">10</a>
							 </td>\r\n";
				$trContent2 .= "\t\t\t</tr>\r\n";
			}
		}
		$divTable = "<div class='table'>\r\n";
		$divTable .= "\t<table class='table_list table table-striped table-bordered table-hover ' cellspacing='0px' style='border-collapse:collapse;color:#000000;'>\r\n";
		$divTable .= "\t\t<tbody>\r\n";
		$divTable .= "\t\t\t<tr>\r\n";
		$divTable .= "\t\t\t\t<th width='80'>预测号码</th>\r\n";
		$divTable .= "\t\t\t\t<th width='80'>标准赔率</th>\r\n";
		$divTable .= "\t\t\t\t<th width='50'>选择</th>\r\n";
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
		$divTable .= "\t\t\t\t<th width='80'>预测号码</th>\r\n";
		$divTable .= "\t\t\t\t<th width='80'>标准赔率</th>\r\n";
		$divTable .= "\t\t\t\t<th width='50'>选择</th>\r\n";
		$divTable .= "\t\t\t\t<th>投注</th>\r\n";
		$divTable .= "\t\t\t\t<th>倍数</th>\r\n";
		$divTable .= "\t\t\t</tr>\r\n";
		//2 tr
		$divTable .= $trContent2;
		
		$divTable .= "\t\t</tbody>\r\n";
		$divTable .= "\t</table>";
		$divTable .= "</div>\r\n";
		
		return $divTable;
    }
    
    /* 取得JS
    * 
    */
    function GetJSContent($act,$no)
    {
    	global $db;
    	$sql = "select game_press_min,game_press_max,game_std_press from game_config where game_type = '{$act}'";
    	$result = $db->query($sql);
	
    	if($rs = $db->fetch_array($result))
    	{
			$gameType = count(explode(',',$rs['game_std_press']));
			$pressNum = $rs['game_std_press'];
			$press_min = $rs['game_press_min'];
			$press_max = $rs['game_press_max'];
    	}
    	
		$js = "<script type=\"text/javascript\">";
		$js .= "
			var MAX_SCORE = {$press_max};
			var MIN_SCORE = {$press_min};
			var MY_SCORE = {$_SESSION['points']};
			var GTYPE = {$gameType};
			var PRESSNUM = '{$pressNum}';
			var MODEL_NO = '{$no}';
			var game_id='{$act}';
			
			$(document).ready(function(){
				if(MODEL_NO != '0')
					getpressinfo(MODEL_NO,'no');
				$('#sltMode').change(function(){
					var v = $(this).children('option:selected').val();
					if(v == '0'){
						$('#txtModelName').val('模式1');
					} else {
					  	$('#txtModelName').val($(this).children('option:selected').text());
					  	getpressinfo(v,'id');
					}
				});
				$('#btnchangename').click(function(){
				   	var v = $('#sltMode').children('option:selected').val();
				   	var newname = $('#txtModelName').val();
				   	if(newname.length == 0 || newname.length > 30)
				   	{
				   		alert('模式名称长度在30个字以内!');
				   		return false;
				   	}
				   	if(v > 0)
				   	{
				   		$.post('sgameservice.php',{act:'changmodelname',gtype:{$act},id:v,newname:newname},function(ret){
				   			if(ret.cmd == 'ok')
				   			{
				   				alert('修改成功!');
				   				getmodeloption(v);
				   			}
				   			else
				   			{
				   				alert(ret.msg);
				   			}
				   		},'json');
				   	}
				});
				$('#btnremove').click(function(){
				   	var v = $('#sltMode').children('option:selected').val();
				   	if(v > 0)
				   	{
				   		$.post('sgameservice.php',{act:'removemodel',gtype:{$act},id:v},function(ret){
				   			if(ret.cmd == 'ok')
				   			{
				   				alert('删除成功!');
				   				getmodeloption(0);
				   			}
				   			else
				   				alert(ret.msg);
				   		},'json');
				   	}
				});
			});
			function getmodeloption(v)
			{
				$.post('sgameservice.php',{act:'getmodeloption',gtype:{$act}},function(ret){
				   	if(ret.cmd == 'ok')
				   	{
				   	 	$('#sltMode').empty();
				   	 	$(ret.msg).appendTo('#sltMode');
				   	 	$('#sltMode').val(v);
				   	 	$('#txtModelName').val(v==0?'模式1':$('#sltMode').children('option:selected').text());
				   	}
				},'json');
			}
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
			function SaveModel(gtype)
			{
				var totalScore = parseInt($('#tbTotal').html());
				var data = PRESSNUM.split(',');
				var thev = $('#sltMode').children('option:selected').val();
				var thename = $('#txtModelName').val();
				if(thename.length == 0 || thename.length > 20)
				{
				     alert('模式名称长度在20个字以内!');
				   	 return false;
				}
				if(totalScore > MAX_SCORE)
				{
					 alert('您的投注额已大于最大限制' + MAX_SCORE);
					 return;
				}
				if(totalScore < MIN_SCORE)
				{
					 alert('您的投注额小于最小限制' + MIN_SCORE);
					 return;
				}
				var press = '';
				for(var i = 0; i < data.length; i++)
				{
				 if($('#tbChk' + i).attr('checked'))
					press += parseInt($('#tbNum' + i).val()==''?'0':$('#tbNum' + i).val()) + ',';
				 else
					press += ',';
				}
				$.post('sgameservice.php',{act:'savemodel',gtype:gtype,thev:thev,thename:thename,press:press,total:totalScore},function(ret){
					switch(ret.cmd)
					{
						case 'ok':
							alert('保存成功!');
							getmodeloption(0);
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

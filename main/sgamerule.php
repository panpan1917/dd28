<?php
	include_once("inc/conn.php");
    include_once("inc/function.php");
    
    if(!isset($_SESSION['usersid'])) {
		echo "您还没登录或者链接超时，请先去<a href='/login.php'>登录</a>!";
		exit;
	}
	
	
	$act = intval($_GET['act']);
	//返回游戏记录
	GetGameRule($act);
	
    /* 返回游戏记录
    * 
    */
    function GetGameRule($act)
    {
		$sid = intval($_GET['sid']);
		$arrCurNoInfo = array('preno'=>'','prekgtime'=>'','game_kj_delay'=>'','game_tz_close'=>'');
		
		
		$RetContent = "<div class='Pattern'>\r\n";
		$RetContent .= "\t<div class='Content'>\r\n";
		
		//取得开奖头
		$RetContent .= GetHeadContent($act,$sid,$arrCurNoInfo);
		//取得子菜单
		$RetContent .= GetSubMenu($act,$sid);
		
		//取表格
		$RetContent .= GetTableContent($act);
		
		$RetContent .= "\t</div>\r\n"; //content结束
		$RetContent .= "</div>\r\n";
		//js 定义
		$RetContent .= GetRewardJS($act,$arrCurNoInfo,"head");
		
		echo $RetContent;
		exit;
    }
    
    /* 取表格内容
    *
    */
    function GetTableContent($act)
    {  
		$divTable = "<div class='table'>\r\n";
		$divTable .= "\t<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;color:#000000;'>\r\n";
		$divTable .= "\t<tbody>\r\n";
		
		if($act == "0") //急速28
		{
			$divTable .= "\t\t<tr><td colspan='4'>计算机系统随机产生3个数字,每个数字范围0-9,每1分钟一期，24小时开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第449159期</td>\r\n";
			$divTable .= "\t\t\t<td>0-9随机数</td>\r\n";
			$divTable .= "\t\t\t<td>0-9随机数</td>\r\n";
			$divTable .= "\t\t\t<td>0-9随机数</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_0'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_9'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_0'></li><i class=\"hja\"></i><li class='kj kj_9'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hdeng\"></i><li class='mh m12'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "1") //急速16
		{
			$divTable .= "\t\t<tr><td colspan='4'>计算机系统随机产生3个数字,每个数字范围1-6,每1分钟一期，24小时开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第449159期</td>\r\n";
			$divTable .= "\t\t\t<td>1-6随机数</td>\r\n";
			$divTable .= "\t\t\t<td>1-6随机数</td>\r\n";
			$divTable .= "\t\t\t<td>1-6随机数</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_1'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_4'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_1'></li><i class=\"hja\"></i><li class='kj kj_4'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hdeng\"></i><li class='mh m8'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "2") //急速11
		{
			$divTable .= "\t\t<tr><td colspan='3'>计算机系统随机产生2个数字,每个数字范围1-6,每1分钟一期，24小时开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第449159期</td>\r\n";
			$divTable .= "\t\t\t<td>1-6随机数</td>\r\n";
			$divTable .= "\t\t\t<td>1-6随机数</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_1'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_4'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=2><li class='kj kj_1'></li><i class=\"hja\"></i><li class='kj kj_4'></li><i class=\"hdeng\"></i><li class='mh m5'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "3" || $act == "32")
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第654574期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>02,04,07,08,18,22,25,30,35,36,43,49,50,53,59,66,69,71,74,75</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第1/2/3/4/5/6位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第7/8/9/10/11/12位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第13/14/15/16/17/18位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>02,04,07,08,18,22</td>\r\n";
			$divTable .= "\t\t\t<td>25,30,35,36,43,49</td>\r\n";
			$divTable .= "\t\t\t<td>50,53,59,66,69,71</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>61</td>\r\n";
			$divTable .= "\t\t\t<td>218</td>\r\n";
			$divTable .= "\t\t\t<td>368</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_1'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_8'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_8'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_1'></li><i class=\"hja\"></i><li class='kj kj_8'></li><i class=\"hja\"></i><li class='kj kj_8'></li> <i class=\"hdeng\"></i> <li class='mh m17'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "4" || $act == "33")
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第654574期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>02,04,07,08,18,22,25,30,35,36,43,49,50,53,59,66,69,71,74,75(从小到大依次排列)</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>04,18,30,43,53,69</td>\r\n";
			$divTable .= "\t\t\t<td>07,22,35,49,59,71</td>\r\n";
			$divTable .= "\t\t\t<td>08,25,36,50,66,74</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>217</td>\r\n";
			$divTable .= "\t\t\t<td>243</td>\r\n";
			$divTable .= "\t\t\t<td>259</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_7'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_9'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_7'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hja\"></i><li class='kj kj_9'></li><i class=\"hdeng\"></i><li class='mh m19'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "5")
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第654574期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>02,04,07,08,18,22,25,30,35,36,43,49,50,53,59,66,69,71,74,75(从小到大依次排列)</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第1/4/7/10/13/16位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第2/5/8/11/14/17位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第3/6/9/12/15/18位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>02,08,25,36,50,66</td>\r\n";
			$divTable .= "\t\t\t<td>04,18,30,43,53,69</td>\r\n";
			$divTable .= "\t\t\t<td>07,22,35,49,59,71</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>187</td>\r\n";
			$divTable .= "\t\t\t<td>217</td>\r\n";
			$divTable .= "\t\t\t<td>243</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>187除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\t\t<td>217除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\t\t<td>243除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_2'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_2'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_4'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_2'></li><i class=\"hja\"></i><li class='kj kj_2'></li><i class=\"hja\"></i><li class='kj kj_4'></li><i class=\"hdeng\"></i><li class='mh m8'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "6")
		{
			$divTable .= "\t\t<tr><td colspan='2'>采用北京福彩中心PK10数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第449153期</td>\r\n";
			$divTable .= "\t\t\t<td><em class='regular06'></em>
							<em class='regular05'></em>
							<em class='regular01'></em>
							<em class='regular03'></em>
							<em class='regular04'></em>
							<em class='regular10'></em>
							<em class='regular07'></em>
							<em class='regular08'></em>
							<em class='regular09'></em>
							<em class='regular02'></em></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取期号尾数，尾数对应第几位数字为开奖号码，如果尾数为0取第10位</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td>第449153期的期号尾数是3,则取第3位，是1</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='mh m1'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "7")
		{
			$divTable .= "\t\t<tr><td colspan='2'>采用北京福彩中心PK10数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第449159期</td>\r\n";
			$divTable .= "\t\t\t<td><em class='regular06'></em>
							<em class='regular05'></em>
							<em class='regular01'></em>
							<em class='regular03'></em>
							<em class='regular04'></em>
							<em class='regular10'></em>
							<em class='regular07'></em>
							<em class='regular08'></em>
							<em class='regular09'></em>
							<em class='regular02'></em></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>开奖取首位</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='mh m6'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "8" || $act == "35")
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用加拿大快乐8数据，每3分半钟一期，每天336期，每天19:00-20:00暂停开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第1773065期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>06,15,32,40,53,64</td>\r\n";
			$divTable .= "\t\t\t<td>07,28,33,43,57,65</td>\r\n";
			$divTable .= "\t\t\t<td>13,31,35,44,62,68</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>210</td>\r\n";
			$divTable .= "\t\t\t<td>233</td>\r\n";
			$divTable .= "\t\t\t<td>253</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_0'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_0'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hdeng\"></i><li class='mh m6'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "9")
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用加拿大快乐8数据，每3分半钟一期，每天336期，每天19:00-20:00暂停开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第1773065期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第1/4/7/10/13/16位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第2/5/8/11/14/17位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第3/6/9/12/15/18位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>01,13,31,35,44,62</td>\r\n";
			$divTable .= "\t\t\t<td>06,15,32,40,53,64</td>\r\n";
			$divTable .= "\t\t\t<td>07,28,33,43,57,65</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>186</td>\r\n";
			$divTable .= "\t\t\t<td>210</td>\r\n";
			$divTable .= "\t\t\t<td>233</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>186除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\t\t<td>210除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\t\t<td>233除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_1'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_1'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_6'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_1'></li><i class=\"hja\"></i><li class='kj kj_1'></li><i class=\"hja\"></i><li class='kj kj_6'></li><i class=\"hdeng\"></i><li class='mh m8'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "10")
		{
			$divTable .= "\t\t<tr><td colspan='3'>采用加拿大快乐8数据，每3分半钟一期，每天336期，每天19:00-20:00暂停开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第1773065期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='2'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第1/4/7/10/13/16位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第3/6/9/12/15/18位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>01,13,31,35,44,62</td>\r\n";
			$divTable .= "\t\t\t<td>07,28,33,43,57,65</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>186</td>\r\n";
			$divTable .= "\t\t\t<td>233</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>186除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\t\t<td>233除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_1'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_6'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_1'></li><i class=\"hja\"></i><li class='kj kj_6'></li><i class=\"hdeng\"></i><li class='mh m7'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "11") //蛋蛋36
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第654574期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>02,04,07,08,18,22,25,30,35,36,43,49,50,53,59,66,69,71,74,75(从小到大依次排列)</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第1/2/3/4/5/6位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第7/8/9/10/11/12位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第13/14/15/16/17/18位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>02,04,07,08,18,22</td>\r\n";
			$divTable .= "\t\t\t<td>25,30,35,36,43,49</td>\r\n";
			$divTable .= "\t\t\t<td>50,53,59,66,69,71</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>61</td>\r\n";
			$divTable .= "\t\t\t<td>218</td>\r\n";
			$divTable .= "\t\t\t<td>368</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_1'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_8'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_8'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_1'></li><i class=\"hja\"></i><li class='kj kj_8'></li><i class=\"hja\"></i><li class='kj kj_8'></li><i class=\"hdeng\"></i><li class='zh z2'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='color:#F90; font-weight:bold'>游戏结果说明</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;结果优先顺序：豹 > 顺 > 对 > 半 > 杂</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z1'></li>  3个结果号码相同，如222,333,999</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z3'></li>  3个结果号码从小到大排序后，号码都相连，如231,765,645.特例:排序后019算顺子</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z2'></li>  3个结果号码只有两个相同，如535,337,899</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z4'></li>  3个结果号码只有任意两个是相连的,不包含顺、对，如635,367,874.特例:包含0和9也算顺子</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z5'></li>  3个结果号码没有任何关联，如638,942,185</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "12") //北京36
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第654574期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>02,04,07,08,18,22,25,30,35,36,43,49,50,53,59,66,69,71,74,75(从小到大依次排列)</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>04,18,30,43,53,69</td>\r\n";
			$divTable .= "\t\t\t<td>07,22,35,49,59,71</td>\r\n";
			$divTable .= "\t\t\t<td>08,25,36,50,66,74</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>217</td>\r\n";
			$divTable .= "\t\t\t<td>243</td>\r\n";
			$divTable .= "\t\t\t<td>259</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_7'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_9'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_7'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hja\"></i><li class='kj kj_9'></li><i class=\"hdeng\"></i><li class='zh z5'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='color:#F90; font-weight:bold'>游戏结果说明</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;结果优先顺序：豹 > 顺 > 对 > 半 > 杂</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z1'></li>  3个结果号码相同，如222,333,999</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z3'></li>  3个结果号码从小到大排序后，号码都相连，如231,765,645.特例:排序后019算顺子</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z2'></li>  3个结果号码只有两个相同，如535,337,899</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z4'></li>  3个结果号码只有任意两个是相连的,不包含顺、对，如635,367,874.特例:包含0和9也算顺子</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z5'></li>  3个结果号码没有任何关联，如638,942,185</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "13") //加拿大36 
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用加拿大快乐8数据，每3分半钟一期，每天336期，每天19:00-20:00暂停开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第1773065期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>06,15,32,40,53,64</td>\r\n";
			$divTable .= "\t\t\t<td>07,28,33,43,57,65</td>\r\n";
			$divTable .= "\t\t\t<td>13,31,35,44,62,68</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>210</td>\r\n";
			$divTable .= "\t\t\t<td>233</td>\r\n";
			$divTable .= "\t\t\t<td>253</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_0'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_0'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hdeng\"></i><li class='zh z2'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='color:#F90; font-weight:bold'>游戏结果说明</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;结果优先顺序：豹 > 顺 > 对 > 半 > 杂</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z1'></li>  3个结果号码相同，如222,333,999</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z3'></li>  3个结果号码从小到大排序后，号码都相连，如231,765,645.特例:排序后019算顺子</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z2'></li>  3个结果号码只有两个相同，如535,337,899</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z4'></li>  3个结果号码只有任意两个是相连的,不包含顺、对，如635,367,874.特例:包含0和9也算顺子</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z5'></li>  3个结果号码没有任何关联，如638,942,185</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "14") //pk22 
		{
			$divTable .= "\t\t<tr><td colspan='2'>采用北京福彩中心PK10数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第449159期</td>\r\n";
			$divTable .= "\t\t\t<td><em class='regular06'></em>
							<em class='regular05'></em>
							<em class='regular01'></em>
							<em class='regular03'></em>
							<em class='regular04'></em>
							<em class='regular10'></em>
							<em class='regular07'></em>
							<em class='regular08'></em>
							<em class='regular09'></em>
							<em class='regular02'></em></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取开奖号码前3位之和</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><em class='regular06'></em> + <em class='regular05'></em> + <em class='regular01'></em> = <li class='mh m12'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='mh m12'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "15") //急速10
		{
			$divTable .= "\t\t<tr><td colspan='2'>计算机系统随机产生10个数字,数字范围1-10各不相同,每1分钟一期，24小时开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第449159期</td>\r\n";
			$divTable .= "\t\t\t<td><em class='regular06'></em>
							<em class='regular05'></em>
							<em class='regular01'></em>
							<em class='regular03'></em>
							<em class='regular04'></em>
							<em class='regular10'></em>
							<em class='regular07'></em>
							<em class='regular08'></em>
							<em class='regular09'></em>
							<em class='regular02'></em></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>开奖取首位</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='mh m6'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "16") //pk龙虎
		{
			$divTable .= "\t\t<tr><td colspan='2'>采用北京福彩中心PK10数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第449159期</td>\r\n";
			$divTable .= "\t\t\t<td><em class='regular06'></em>
							<em class='regular05'></em>
							<em class='regular01'></em>
							<em class='regular03'></em>
							<em class='regular04'></em>
							<em class='regular10'></em>
							<em class='regular07'></em>
							<em class='regular08'></em>
							<em class='regular09'></em>
							<em class='regular02'></em></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取第一位和最后一位比较，第一位大为龙，最后一位大为虎</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><em class='regular06'></em> 大于 <em class='regular02'></em>，结果为<li class='lh n1'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "17") //pk冠亚军
		{
			$divTable .= "\t\t<tr><td colspan='2'>采用北京福彩中心PK10数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第449159期</td>\r\n";
			$divTable .= "\t\t\t<td><em class='regular06'></em>
							<em class='regular05'></em>
							<em class='regular01'></em>
							<em class='regular03'></em>
							<em class='regular04'></em>
							<em class='regular10'></em>
							<em class='regular07'></em>
							<em class='regular08'></em>
							<em class='regular09'></em>
							<em class='regular02'></em></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取开奖号码前两位之和</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><em class='regular06'></em> + <em class='regular05'></em> = <li class='mh m11'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "18" || $act == "34") //首尔28
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用首尔快乐8数据，每1分半钟一期，每天5:00-7:00暂停开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第1234567期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>06,15,32,40,53,64</td>\r\n";
			$divTable .= "\t\t\t<td>07,28,33,43,57,65</td>\r\n";
			$divTable .= "\t\t\t<td>13,31,35,44,62,68</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>210</td>\r\n";
			$divTable .= "\t\t\t<td>233</td>\r\n";
			$divTable .= "\t\t\t<td>253</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_0'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_0'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hdeng\"></i><li class='mh m6'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "19") //首尔16
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用首尔快乐8数据，每1分半钟一期，每天5:00-7:00暂停开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第1234567期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第1/4/7/10/13/16位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第2/5/8/11/14/17位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第3/6/9/12/15/18位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>01,13,31,35,44,62</td>\r\n";
			$divTable .= "\t\t\t<td>06,15,32,40,53,64</td>\r\n";
			$divTable .= "\t\t\t<td>07,28,33,43,57,65</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>186</td>\r\n";
			$divTable .= "\t\t\t<td>210</td>\r\n";
			$divTable .= "\t\t\t<td>233</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>186除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\t\t<td>210除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\t\t<td>233除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_1'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_1'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_6'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_1'></li><i class=\"hja\"></i><li class='kj kj_1'></li><i class=\"hja\"></i><li class='kj kj_6'></li><i class=\"hdeng\"></i><li class='mh m8'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "20") //首尔11
		{
			$divTable .= "\t\t<tr><td colspan='3'>采用首尔快乐8数据，每1分半钟一期，每天5:00-7:00暂停开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第1234567期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='2'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第1/4/7/10/13/16位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>01,13,31,35,44,62</td>\r\n";
			$divTable .= "\t\t\t<td>07,28,33,43,57,65</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>186</td>\r\n";
			$divTable .= "\t\t\t<td>233</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>186除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\t\t<td>233除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_1'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_6'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_1'></li><i class=\"hja\"></i><li class='kj kj_6'></li><i class=\"hdeng\"></i><li class='mh m7'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "21") //首尔36 
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用首尔快乐8数据，每1分半钟一期，每天5:00-7:00暂停开奖</td></tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第1234567期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>06,15,32,40,53,64</td>\r\n";
			$divTable .= "\t\t\t<td>07,28,33,43,57,65</td>\r\n";
			$divTable .= "\t\t\t<td>13,31,35,44,62,68</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>210</td>\r\n";
			$divTable .= "\t\t\t<td>233</td>\r\n";
			$divTable .= "\t\t\t<td>253</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_0'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_0'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hdeng\"></i><li class='zh z2'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='color:#F90; font-weight:bold'>游戏结果说明</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;结果优先顺序：豹 > 顺 > 对 > 半 > 杂</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z1'></li>  3个结果号码相同，如222,333,999</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z3'></li>  3个结果号码从小到大排序后，号码都相连，如231,765,645.特例:排序后019算顺子</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z2'></li>  3个结果号码只有两个相同，如535,337,899</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z4'></li>  3个结果号码只有任意两个是相连的,不包含顺、对，如635,367,874.特例:包含0和9也算顺子</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z5'></li>  3个结果号码没有任何关联，如638,942,185</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "22") //急速22
		{
			$divTable .= "\t\t<tr><td colspan='2'>计算机系统随机产生10个数字,数字范围1-10各不相同,每1分钟一期，24小时开奖</td></tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第449159期</td>\r\n";
			$divTable .= "\t\t\t<td><em class='regular06'></em>
							<em class='regular05'></em>
							<em class='regular01'></em>
							<em class='regular03'></em>
							<em class='regular04'></em>
							<em class='regular10'></em>
							<em class='regular07'></em>
							<em class='regular08'></em>
							<em class='regular09'></em>
							<em class='regular02'></em></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>开奖取前三位的和</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='mh m12'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "23") //急速36
		{
			$divTable .= "\t\t<tr><td colspan='4'>计算机系统随机产生3个数字,每个数字范围0-9,每1分钟一期，24小时开奖</td></tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第1234567期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>01,01,08(从小到大依次排列)</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_1'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_1'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_8'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_1'></li><i class=\"hja\"></i><li class='kj kj_1'></li><i class=\"hja\"></i><li class='kj kj_8'></li><i class=\"hdeng\"></i><li class='zh z2'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='color:#F90; font-weight:bold'>游戏结果说明</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;结果优先顺序：豹 > 顺 > 对 > 半 > 杂</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z1'></li>  3个结果号码相同，如222,333,999</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z3'></li>  3个结果号码从小到大排序后，号码都相连，如231,765,645.特例:排序后019算顺子</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z2'></li>  3个结果号码只有两个相同，如535,337,899</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z4'></li>  3个结果号码只有任意两个是相连的,不包含顺、对，如635,367,874.特例:包含0和9也算顺子</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z5'></li>  3个结果号码没有任何关联，如638,942,185</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "24") //急速冠亚军
		{
			$divTable .= "\t\t<tr><td colspan='2'>计算机系统随机产生10个数字,数字范围1-10各不相同,每1分钟一期，24小时开奖</td></tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第449159期</td>\r\n";
			$divTable .= "\t\t\t<td><em class='regular06'></em>
							<em class='regular05'></em>
							<em class='regular01'></em>
							<em class='regular03'></em>
							<em class='regular04'></em>
							<em class='regular10'></em>
							<em class='regular07'></em>
							<em class='regular08'></em>
							<em class='regular09'></em>
							<em class='regular02'></em></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取开奖号码前两位之和</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><em class='regular06'></em> + <em class='regular05'></em> = <li class='mh m11'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "25")
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第654574期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>02,04,07,08,18,22,25,30,35,36,43,49,50,53,59,66,69,71,74,75</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第1/2/3/4/5/6位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第7/8/9/10/11/12位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第13/14/15/16/17/18位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>02,04,07,08,18,22</td>\r\n";
			$divTable .= "\t\t\t<td>25,30,35,36,43,49</td>\r\n";
			$divTable .= "\t\t\t<td>50,53,59,66,69,71</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>61</td>\r\n";
			$divTable .= "\t\t\t<td>218</td>\r\n";
			$divTable .= "\t\t\t<td>368</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_1'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_8'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_8'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_1'></li><i class=\"hja\"></i><li class='kj kj_8'></li><i class=\"hja\"></i><li class='kj kj_8'></li> <i class=\"hdeng\"></i> <li class='mh m17'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan=\"4\">游戏总共有0-27共28位数字，0-13为小，14-27为大，奇数为单，偶数为双,以下赔率包含本金</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>大小单双：</td>\r\n";
			$divTable .= "\t\t\t<td>固定赔率2.1倍, 开13,14回本。</td>\r\n";
			$divTable .= "\t\t\t<td>小单</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定4.6倍（包括1,3,5,7,9,11,13）,开13回本。</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>大单：</td>\r\n";
			$divTable .= "\t\t\t<td>固定4.2倍（15,17,19,21,23,25,27）</td>\r\n";
			$divTable .= "\t\t\t<td>小双</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定4.2倍（0.2.4.6.8.10.12)</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>大双：</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定4.6倍（14.16.18.20.22.24.26）,开14回本。</td>\r\n";
			$divTable .= "\t\t\t<td>极小</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定17倍（0,1,2,3,4,5）</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>极大：</td>\r\n";
			$divTable .= "\t\t\t<td   align=\"left\">固定17倍（22,23,24,25,26,27）</td>\r\n";
			$divTable .= "\t\t\t<td>龙：</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定2.9倍（0,3,6,9,12,15,18,21,24,27）</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>虎：</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定2.9倍（1,4,7,10,13,16,19,22,25）</td>\r\n";
			$divTable .= "\t\t\t<td>豹：</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定2.9倍（2,5,8,11,14,17,20,23,26）</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
		}
		else if($act == "26")
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第654574期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>02,04,07,08,18,22,25,30,35,36,43,49,50,53,59,66,69,71,74,75</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第1/2/3/4/5/6位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第7/8/9/10/11/12位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第13/14/15/16/17/18位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>02,04,07,08,18,22</td>\r\n";
			$divTable .= "\t\t\t<td>25,30,35,36,43,49</td>\r\n";
			$divTable .= "\t\t\t<td>50,53,59,66,69,71</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>61</td>\r\n";
			$divTable .= "\t\t\t<td>218</td>\r\n";
			$divTable .= "\t\t\t<td>368</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_1'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_8'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_8'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_1'></li><i class=\"hja\"></i><li class='kj kj_8'></li><i class=\"hja\"></i><li class='kj kj_8'></li> <i class=\"hdeng\"></i> <li class='mh m17'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			
			$divTable .= "\t\t<tr><td colspan=\"4\">游戏总共有0-27共28位数字，0-13为小，14-27为大，奇数为单，偶数为双,以下赔率包含本金</td></tr>\r\n";
			$divTable .= "\t\t<tr><td style=\"border-right:1px solid  #d7d7d7;width:100px\">大小</td><td style=\"border-right:1px solid  #d7d7d7\">固定赔率1.98倍</td><td style=\"border-right:1px solid  #d7d7d7;width:100px\">单双</td><td>固定赔率1.98倍</td></tr>\r\n";
			$divTable .= "\t\t<tr><td style=\"border-right:1px solid  #d7d7d7;\">小单</td><td style=\"border-right:1px solid  #d7d7d7\">固定3.68倍（1.3.5.7.9.11.13）</td><td style=\"border-right:1px solid  #d7d7d7\">大单</td><td>固定4.2倍（15.17.19.21.23.25.27）</td></tr>\r\n";
			$divTable .= "\t\t<tr><td style=\"border-right:1px solid  #d7d7d7\">小双</td><td style=\"border-right:1px solid  #d7d7d7\">固定4.2倍（0.2.4.6.8.10.12)</td><td style=\"border-right:1px solid  #d7d7d7\">大双</td><td>固定3.68倍（14.16.18.20.22.24.26）</td></tr>\r\n";
			$divTable .= "\t\t<tr><td style=\"border-right:1px solid  #d7d7d7\">极小</td><td style=\"border-right:1px solid  #d7d7d7\">固定16倍（0.1.2.3.4.5）</td><td style=\"border-right:1px solid  #d7d7d7\">极大</td><td>固定16倍（22.23.24.25.26.27）</td></tr>\r\n";
			
			
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>龙虎和：</td>\r\n";
			$divTable .= "\t\t\t<td colspan=\"3\" align=\"left\"><strong>龙</strong>：开出号码一大于号码三，如 号码一开出
					<li class=\"finalbig\">3</li>
					号码三开出
					<li class=\"finalbig\">1</li>
					；中奖为龙。 <br>
					<strong>虎</strong>：开出号码一小于号码三。如 号码一开出
					<li class=\"finalbig\">0</li>
					号码三开出
					<li class=\"finalbig\">3</li>
					；中奖为虎。 <br>
					<strong>和</strong>：开出号码一等于号码三,中奖为和。 </td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
		}
		else if($act == "27")
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用加拿大快乐8数据，每3分半钟一期，每天336期，每天19:00-20:00暂停开奖</td></tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第1773065期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>06,15,32,40,53,64</td>\r\n";
			$divTable .= "\t\t\t<td>07,28,33,43,57,65</td>\r\n";
			$divTable .= "\t\t\t<td>13,31,35,44,62,68</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>210</td>\r\n";
			$divTable .= "\t\t\t<td>233</td>\r\n";
			$divTable .= "\t\t\t<td>253</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_0'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_0'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hja\"></i><li class='kj kj_3'></li> <i class=\"hdeng\"></i> <li class='mh m6'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan=\"4\">游戏总共有0-27共28位数字，0-13为小，14-27为大，奇数为单，偶数为双,以下赔率包含本金</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>大小单双：</td>\r\n";
			$divTable .= "\t\t\t<td>固定赔率2.10倍, 开13,14回本。</td>\r\n";
			$divTable .= "\t\t\t<td>小单</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定4.6倍（包括1,3,5,7,9,11,13）,开13回本。</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>大单：</td>\r\n";
			$divTable .= "\t\t\t<td>固定4.2倍（15,17,19,21,23,25,27）</td>\r\n";
			$divTable .= "\t\t\t<td>小双</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定4.2倍（0.2.4.6.8.10.12)</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>大双：</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定4.6倍（14.16.18.20.22.24.26）,开14回本。</td>\r\n";
			$divTable .= "\t\t\t<td>极小</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定17倍（0,1,2,3,4,5）</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>极大：</td>\r\n";
			$divTable .= "\t\t\t<td   align=\"left\">固定17倍（22,23,24,25,26,27）</td>\r\n";
			$divTable .= "\t\t\t<td>龙：</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定2.9倍（0,3,6,9,12,15,18,21,24,27）</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>虎：</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定2.9倍（1,4,7,10,13,16,19,22,25）</td>\r\n";
			$divTable .= "\t\t\t<td>豹：</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定2.9倍（2,5,8,11,14,17,20,23,26）</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
		}
		else if($act == "28")
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用加拿大快乐8数据，每3分半钟一期，每天336期，每天19:00-20:00暂停开奖</td></tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第1773065期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>06,15,32,40,53,64</td>\r\n";
			$divTable .= "\t\t\t<td>07,28,33,43,57,65</td>\r\n";
			$divTable .= "\t\t\t<td>13,31,35,44,62,68</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>210</td>\r\n";
			$divTable .= "\t\t\t<td>233</td>\r\n";
			$divTable .= "\t\t\t<td>253</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_0'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_0'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hja\"></i><li class='kj kj_3'></li> <i class=\"hdeng\"></i> <li class='mh m6'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan=\"4\">游戏总共有0-27共28位数字，0-13为小，14-27为大，奇数为单，偶数为双,以下赔率包含本金</td></tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style=\"border-right:1px solid  #d7d7d7;width:100px\">大小</td>\r\n";
			$divTable .= "\t\t\t<td style=\"border-right:1px solid #d7d7d7\">固定赔率1.98倍</td>\r\n";
			$divTable .= "\t\t\t<td style=\"border-right:1px solid  #d7d7d7;width:100px\">单双</td>\r\n";
			$divTable .= "\t\t\t<td>固定赔率1.98倍</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style=\"border-right:1px solid  #d7d7d7;\">小单</td>\r\n";
			$divTable .= "\t\t\t<td style=\"border-right:1px solid  #d7d7d7\">固定3.68倍（1.3.5.7.9.11.13）</td>\r\n";
			$divTable .= "\t\t\t<td style=\"border-right:1px solid  #d7d7d7\">大单</td>\r\n";
			$divTable .= "\t\t\t<td>固定4.2倍（15.17.19.21.23.25.27）</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr><td style=\"border-right:1px solid  #d7d7d7\">小双</td>\r\n";
			$divTable .= "\t\t\t<td style=\"border-right:1px solid  #d7d7d7\">固定4.2倍（0.2.4.6.8.10.12)</td>\r\n";
			$divTable .= "\t\t\t<td style=\"border-right:1px solid  #d7d7d7\">大双</td>\r\n";
			$divTable .= "\t\t\t<td>固定3.68倍（14.16.18.20.22.24.26）</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style=\"border-right:1px solid  #d7d7d7\">极小</td>\r\n";
			$divTable .= "\t\t\t<td style=\"border-right:1px solid  #d7d7d7\">固定16倍（0.1.2.3.4.5）</td>\r\n";
			$divTable .= "\t\t\t<td style=\"border-right:1px solid  #d7d7d7\">极大</td><td>固定16倍（22.23.24.25.26.27）</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan=\"4\"></td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>龙虎和：</td>\r\n";
			$divTable .= "\t\t\t<td colspan=\"3\" align=\"left\"><strong>龙</strong>：开出号码一大于号码三，如 号码一开出
					<li class=\"finalbig\">3</li>
					号码三开出
					<li class=\"finalbig\">1</li>
					；中奖为龙。 <br>
					<strong>虎</strong>：开出号码一小于号码三。如 号码一开出
					<li class=\"finalbig\">0</li>
					号码三开出
					<li class=\"finalbig\">3</li>
					；中奖为虎。 <br>
					<strong>和</strong>：开出号码一等于号码三,中奖为和。 </td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
		}
		else if($act == "29")
		{
			$divTable .= "\t\t<tr><td><h1>北京赛车规则说明</h1></td></tr><tr><td style=\"font-size:12px;line-height:26px; text-align:left;padding:15px\">该游戏的投注时间、开奖时间和开奖号码与“北京PK拾”完全同步，北京时间（GMT+8）每天白天从上午09:02开到晚上23:57，每5分钟开一次奖,每天开奖179期。
			<br>具体游戏规则如下:
			<br>
			<br><span>1～10 两面：</span>指 单、双；大、小。
			<br><span>单、双：</span>号码为双数叫双，如4、8；号码为单数叫单，如5、9。
			<br><span>大、小：</span>开出之号码大于或等于6为大，小于或等于5为小。
			<br><span>第一名～第十名 车号指定：</span>每一个车号为一投注组合，开奖结果“投注车号”对应所投名次视为中奖，其余情形视为不中奖。
			<br>
			<br><span>1～5龙虎</span>
			<br><span>冠&nbsp;&nbsp;军 龙/虎：</span>“第一名”车号大于“第十名”车号视为【龙】中奖、反之小于视为【虎】中奖，其余情形视为不中奖。
			<br><span>亚&nbsp;&nbsp;军 龙/虎：</span>“第二名”车号大于“第九名”车号视为【龙】中奖、反之小于视为【虎】中奖，其余情形视为不中奖。
			<br><span>第三名 龙/虎：</span>“第三名”车号大于“第八名”车号视为【龙】中奖、反之小于视为【虎】中奖，其余情形视为不中奖。
			<br><span>第四名 龙/虎：</span>“第四名”车号大于“第七名”车号视为【龙】中奖、反之小于视为【虎】中奖，其余情形视为不中奖。
			<br><span>第五名 龙/虎：</span>“第五名”车号大于“第六名”车号视为【龙】中奖、反之小于视为【虎】中奖，其余情形视为不中奖。
			<br>
			<br><span>冠军车号＋亚军车号＝冠亚和值（为3~19)</span>
			<br><span>冠亚和单双：</span>“冠亚和值”为单视为投注“单”的注单视为中奖，为双视为投注“双”的注单视为中奖，其余视为不中奖。
			<br><span>冠亚和大小：</span>“冠亚和值”大于11时投注“大”的注单视为中奖，小于或等于11时投注“小”的注单视为中奖，其余视为不中奖。
			<br><span>冠亚和指定：</span>“冠亚和值”可能出现的结果为3～19， 投中对应“冠亚和值”数字的视为中奖，其余视为不中奖。
			</td>
			</tr>\r\n";
		}
		else if($act == "30")
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用首尔快乐8数据，每1分半钟一期，每天5:00-07:00暂停开奖</td></tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第1674529期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>7 14 18 23 25 28 30 31 39 41 46 50 51 53 57 59 66 71 72 75(从小到大依次排列)</td>\r\n";
	
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
			
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>14,25,31,46,53,66</td>\r\n";
			$divTable .= "\t\t\t<td>18,28,39,50,57,71</td>\r\n";
			$divTable .= "\t\t\t<td>23,30,41,51,59,72</td>\r\n";
			
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>235</td>\r\n";
			$divTable .= "\t\t\t<td>263</td>\r\n";
			$divTable .= "\t\t\t<td>276</td>\r\n";
			
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_5'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_6'></li></td>\r\n";
			
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_5'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hja\"></i><li class='kj kj_6'></li> <i class=\"hdeng\"></i> <li class='mh m14'></li></td>\r\n";
			
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan=\"4\">游戏总共有0-27共28位数字，0-13为小，14-27为大，奇数为单，偶数为双,以下赔率包含本金</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>大小单双：</td>\r\n";
			$divTable .= "\t\t\t<td>固定赔率2.1倍, 开13,14回本。</td>\r\n";
			$divTable .= "\t\t\t<td>小单</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定4.6倍（包括1,3,5,7,9,11,13）,开13回本。</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>大单：</td>\r\n";
			$divTable .= "\t\t\t<td>固定4.2倍（15,17,19,21,23,25,27）</td>\r\n";
			$divTable .= "\t\t\t<td>小双</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定4.2倍（0.2.4.6.8.10.12)</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>大双：</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定4.6倍（14.16.18.20.22.24.26）,开14回本。</td>\r\n";
			$divTable .= "\t\t\t<td>极小</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定17倍（0,1,2,3,4,5）</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>极大：</td>\r\n";
			$divTable .= "\t\t\t<td   align=\"left\">固定17倍（22,23,24,25,26,27）</td>\r\n";
			$divTable .= "\t\t\t<td>龙：</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定2.9倍（0,3,6,9,12,15,18,21,24,27）</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>虎：</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定2.9倍（1,4,7,10,13,16,19,22,25）</td>\r\n";
			$divTable .= "\t\t\t<td>豹：</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定2.9倍（2,5,8,11,14,17,20,23,26）</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
		}
		else if($act == "31")
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用首尔快乐8数据，每1分半钟一期，每天5:00-07:00暂停开奖</td></tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第1674529期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>7 14 18 23 25 28 30 31 39 41 46 50 51 53 57 59 66 71 72 75(从小到大依次排列)</td>\r\n";
	
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
			
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>14,25,31,46,53,66</td>\r\n";
			$divTable .= "\t\t\t<td>18,28,39,50,57,71</td>\r\n";
			$divTable .= "\t\t\t<td>23,30,41,51,59,72</td>\r\n";
			
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>235</td>\r\n";
			$divTable .= "\t\t\t<td>263</td>\r\n";
			$divTable .= "\t\t\t<td>276</td>\r\n";
			
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_5'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_6'></li></td>\r\n";
			
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'><li class='kj kj_5'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hja\"></i><li class='kj kj_6'></li> <i class=\"hdeng\"></i> <li class='mh m14'></li></td>\r\n";
			
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr><td colspan=\"4\">游戏总共有0-27共28位数字，0-13为小，14-27为大，奇数为单，偶数为双,以下赔率包含本金</td></tr>\r\n";
			$divTable .= "\t\t<tr><td style=\"border-right:1px solid  #d7d7d7;width:100px\">大小</td><td style=\"border-right:1px solid  #d7d7d7\">固定赔率1.98倍</td><td style=\"border-right:1px solid  #d7d7d7;width:100px\">单双</td><td>固定赔率1.98倍</td></tr>\r\n";
			$divTable .= "\t\t<tr><td style=\"border-right:1px solid  #d7d7d7;\">小单</td><td style=\"border-right:1px solid  #d7d7d7\">固定3.68倍（1.3.5.7.9.11.13）</td><td style=\"border-right:1px solid  #d7d7d7\">大单</td><td>固定4.2倍（15.17.19.21.23.25.27）</td></tr>\r\n";
			$divTable .= "\t\t<tr><td style=\"border-right:1px solid  #d7d7d7\">小双</td><td style=\"border-right:1px solid  #d7d7d7\">固定4.2倍（0.2.4.6.8.10.12)</td><td style=\"border-right:1px solid  #d7d7d7\">大双</td><td>固定3.68倍（14.16.18.20.22.24.26）</td></tr>\r\n";
			$divTable .= "\t\t<tr><td style=\"border-right:1px solid  #d7d7d7\">极小</td><td style=\"border-right:1px solid  #d7d7d7\">固定16倍（0.1.2.3.4.5）</td><td style=\"border-right:1px solid  #d7d7d7\">极大</td><td>固定16倍（22.23.24.25.26.27）</td></tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan=\"4\"></td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>龙虎和：</td>\r\n";
			$divTable .= "\t\t\t<td colspan=\"3\" align=\"left\"><strong>龙</strong>：开出号码一大于号码三，如 号码一开出
					<li class=\"finalbig\">3</li>
					号码三开出
					<li class=\"finalbig\">1</li>
					；中奖为龙。 <br>
					<strong>虎</strong>：开出号码一小于号码三。如 号码一开出
					<li class=\"finalbig\">0</li>
					号码三开出
					<li class=\"finalbig\">3</li>
					；中奖为虎。 <br>
					<strong>和</strong>：开出号码一等于号码三,中奖为和。 </td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
		}
		else if($act == "36")
		{
			$divTable .= "\t\t<tr><td><h2>幸运农场规则说明</h2></td></tr>
			 	<tr><td class=\"content\" style=\"text-align:left\">
			            
			    <div>
			    
			        <dt>一、第一球~第八球：</dt>
			        <dd>
			            <p>1~8球中，假如投注号码为开奖号码并在所投球的位置，视为中奖，如第一球开奖号码是08，下注第一球为08者视为中奖，第五球开奖号码是12，下注第五球为12者视为中奖，其余情形视为不中奖。</p>
			            <p>指第一球~第八球大、小、单、双、尾大、尾小、合数单双</p>
			            <dl>
			                <dt>1、单、双：</dt>
			                <dd>
			                <p>自第一球至第八球，开奖号码为双数叫双，如08、16；开奖号码为单数叫单，如19、05。</p></dd>
			                <dt>2、大、小：</dt>
			                <dd>
			                <p>自第一球至第八球，开奖号码大于或等于11为大，如13、15；开奖号码小于等于10为小，如05、07。</p></dd>
			                <dt>3、尾大、尾小：</dt>
			                <dd>
			                <p>自第一球至第八球，开奖号码的尾数大于等于5为尾大，如06、18；开出号码的尾数小于等于4为尾小，如03、12。</p></dd>
			                <dt>4、合数单双</dt>
			                <dd>
			                <p>开奖号码的个位和十位相加，所得数值是单数的为合单，如05、12；所得数值是双数的为合双，如08、19。</p></dd>
			            </dl>
			        </dd>
			        <dt>二、两面</dt>
			        <dd>
			            <p>总和大小单双、总和尾大尾小。</p>
			            <dl>
			            <dt>1、总和单双：</dt>
			            <dd>
			            <p>所有8个开奖号码相加的总值是单数的为总和单，如数字总和值是37、51；所有8个开奖号码相加的总值是双数的为总和双，如数字总和值是36、80；假如投注组合符合中奖结果，视为中奖，其余情形视为不中奖。</p></dd>
			            <dt>2、总和大小：</dt>
			            <dd>
			            <p>所有8个开奖号码相加的总值是85到132为总和大；所有8个开奖号码相加的总值是36到83为总和小；所有8个开奖号码相加的总值为84打和，打和不计算输赢,为0；如开奖号码为01、20、02、08、17、09、11，数值总和是68，则总和小。假如投注组合符合中奖结果，视为中奖，其余情形视为不中奖，开和统计注单，报表中不计算结果。</p></dd>
			            <dt>3、总尾大小：</dt>
			            <dd>
			            <p>所有8个开奖号码相加总值的个位数大于等于5、小于等于9为总尾大，大于等于0、小于等于4为总尾小；假如投注组合符合中奖结果，视为中奖，其余情形视为不中奖。</p></dd>
			
			            </dl>
			        </dd>
			        <dt>三、东南西北</dt>  
			        <dd>
			            <p>“东”、“南”、“西”、“北”分别代替20个球号中的5个球号，当开奖号码在所投的球位置并被“东”、“南”、“西”、“北”包含时，视为中奖，其余情形视为不中奖。例如：投注“第一球”的“北”，假设第一球开奖是20号球，那么此注单中奖。</p>
			            <p>东：开出的号码为01、05、09、13、17</p>
			            <p>南：开出的号码为02、06、10、14、18</p>
			            <p>西：开出的号码为03、07、11、15、19</p>
			            <p>北：开出的号码为04、08、12、16、20</p>
			        </dd>
			        <dt>四、1~4龙虎: </dt>
			        <dd>
			            <dl>
			                <dt>第一球龙/虎：</dt>
			                <dd>
			                <p>“第一球”号码大于“第八球”号码视为“龙”中奖、反之小于视为“虎”中奖，其余情形视为不中奖。</p>
			                </dd>
			                <dt>第二球龙/虎：</dt>
			                <dd>
			                <p>“第二球”号码大于“第七球”号码视为“龙”中奖、反之小于视为“虎”中奖，其余情形视为不中奖。</p>
			                </dd>
			                <dt>第三球龙/虎：</dt>
			                <dd>
			                <p>“第三球”号码大于“第六球”号码视为“龙”中奖、反之小于视为“虎”中奖，其余情形视为不中奖。</p>
			                </dd>
			                <dt>第四球龙/虎：</dt>
			                <dd>
			                <p>“第四球”号码大于“第五球”号码视为“龙”中奖、反之小于视为“虎”中奖，其余情形视为不中奖。</p>
			                </dd>
			            </dl>
			        </dd>
			    </div>
			</td></tr>\r\n";
		}else if($act == '37'){
			$divTable .= "\t\t<tr><td colspan='2'>该游戏的投注时间、开奖时间和开奖号码与重庆时时彩完全同步，北京时间（GMT+8）每天白天从上午10：00开到晚上22：00，夜场从22:00至凌晨2点,<br/>每10分钟开一次奖，夜场每5分钟开一次奖,每天开奖120期(白天72期,夜间48期)。具体游戏规则如下:</td></tr>\r\n";

			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120' style='color:red;' colspan='2'>1.第一球~第五球：</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";

			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>第一球特~第五球特：</td>\r\n";
			$divTable .= "\t\t\t<td>第一球、第二球、第三球、第四球、第五球：指下注的每一球与开出之号码其开奖顺序及开奖号码从左到右</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";

			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>单双大小：</td>\r\n";
			$divTable .= "\t\t\t<td>根据相应单项投注第一球特 ~ 第五球特开出的球号，判断胜负。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";

			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>大小：</td>\r\n";
			$divTable .= "\t\t\t<td>根据相应单项投注的第一球特 ~ 第五球特开出的球号大於或等於5为特码大，小於或等於4为特码小。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";

			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>单双：</td>\r\n";
			$divTable .= "\t\t\t<td>根据相应单项投注的第一球特 ~ 第五球特开出的球号为双数叫特双，如2、6；特码为单数叫特单，如1、3。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";

			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;' colspan='2'>2.总和单双大小：</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";

			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>大小：</td>\r\n";
			$divTable .= "\t\t\t<td>根据相应单项投注的第一球特 ~ 第五球特开出的球号大於或等於23为特码大，小於或等於22为特码小。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>单双：</td>\r\n";
			$divTable .= "\t\t\t<td>根据相应单项投注的第一球特 ~ 第五球特开出的球号数字总和值是双数为总和双，数字总和值是单数为总和单。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;' colspan='2' >3.前三特殊玩法： 豹子 > 顺子 > 对子 > 半顺 > 杂六 。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>豹子：</td>\r\n";
			$divTable .= "\t\t\t<td>中奖号码的一二三球都相同。----如中奖号码为000、111、999等，中奖号码的一二三球数字相同，则投注豹子者视为中奖，其它视为不中奖。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>顺子：</td>\r\n";
			$divTable .= "\t\t\t<td>中奖号码的一二三球都相连，不分顺序。（数字9、0、1相连）----如中奖号码为123、901、321、546等，中奖号码一二三球百位数字相连，则投注顺子者视为中奖，其它视为不中奖。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>对子：</td>\r\n";
			$divTable .= "\t\t\t<td>中奖号码的一二三球任意两位数字相同。（不包括豹子）----如中奖号码为001，112、696，中奖号码有两位数字相同，则投注对子者视为中奖，其它视为不中奖。如果开奖号码为豹子,则对子视为不中奖。如中奖号码为001，112、696，中奖号码有两位数字相同，则投注对子者视为中奖，其它视为不中奖。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>半顺：</td>\r\n";
			$divTable .= "\t\t\t<td>中奖号码的一二三球任意两位数字相连，不分顺序。（不包括顺子、对子。）----如中奖号码为125、540、390、706，中奖号码有两位数字相连，则投注半顺者视为中奖，其它视为不中奖。如果开奖号码为顺子、对子,则半顺视为不中奖。--如中奖号码为123、901、556、233，视为不中奖。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>杂六：</td>\r\n";
			$divTable .= "\t\t\t<td>不包括豹子、对子、顺子、半顺的所有中奖号码。----如中奖号码为157，中奖号码位数之间无关联性，则投注杂六者视为中奖，其它视为不中奖。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;' colspan='2' >4.中三特殊玩法： 豹子 > 顺子 > 对子 > 半顺 > 杂六 。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>豹子：</td>\r\n";
			$divTable .= "\t\t\t<td>中奖号码的二三四球都相同。----如中奖号码为000、111、999等，中奖号码的二三四球相同，则投注豹子者视为中奖，其它视为不中奖。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\n<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>顺子：</td>\r\n";
			$divTable .= "\t\t\t<td>中奖号码的二三四球都相连，不分顺序。（数字9、0、1相连）----如中奖号码为123、901、321、546等，中奖号码二三四球相连，则投注顺子者视为中奖，其它视为不中奖。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\n<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>对子：</td>\r\n";
			$divTable .= "\t\t\t<td>中奖号码的二三四球相同。（不包括豹子）----如中奖号码为001，112、696，中奖号码有两位数字相同，则投注对子者视为中奖，其它视为不中奖。如果开奖号码为豹子,则对子视为不中奖。如中奖号码为001，112、696，中奖号码有两位数字相同，则投注对子者视为中奖，其它视为不中奖。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";	
			
			$divTable .= "\t\n<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>半顺：</td>\r\n";
			$divTable .= "\t\t\t<td>中奖号码的二三四球数字相连，不分顺序。（不包括顺子、对子。）----如中奖号码为125、540、390、706，中奖号码有两位数字相连，则投注半顺者视为中奖，其它视为不中奖。如果开奖号码为顺子、对子,则半顺视为不中奖。--如中奖号码为123、901、556、233，视为不中奖。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\n<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>杂六：</td>\r\n";
			$divTable .= "\t\t\t<td>不包括豹子、对子、顺子、半顺的所有中奖号码。----如中奖号码为157，中奖号码位数之间无关联性，则投注杂六者视为中奖，其它视为不中奖。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\n<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;' colspan='2'>6.后三特殊玩法： 豹子 > 顺子 > 对子 > 半顺 > 杂六 。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
						
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>豹子：</td>\r\n";
			$divTable .= "\t\t\t<td>中奖号码的二三四球都相同。----如中奖号码为000、111、999等，中奖号码的二三四球相同，则投注豹子者视为中奖，其它视为不中奖。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\n<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>顺子：</td>\r\n";
			$divTable .= "\t\t\t<td>中奖号码的二三四球都相连，不分顺序。（数字9、0、1相连）----如中奖号码为123、901、321、546等，中奖号码二三四球相连，则投注顺子者视为中奖，其它视为不中奖。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\n<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>对子：</td>\r\n";
			$divTable .= "\t\t\t<td>中奖号码的二三四球相同。（不包括豹子）----如中奖号码为001，112、696，中奖号码有两位数字相同，则投注对子者视为中奖，其它视为不中奖。如果开奖号码为豹子,则对子视为不中奖。如中奖号码为001，112、696，中奖号码有两位数字相同，则投注对子者视为中奖，其它视为不中奖。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";	
			
			$divTable .= "\t\n<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>半顺：</td>\r\n";
			$divTable .= "\t\t\t<td>中奖号码的二三四球数字相连，不分顺序。（不包括顺子、对子。）----如中奖号码为125、540、390、706，中奖号码有两位数字相连，则投注半顺者视为中奖，其它视为不中奖。如果开奖号码为顺子、对子,则半顺视为不中奖。--如中奖号码为123、901、556、233，视为不中奖。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\n<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>杂六：</td>\r\n";
			$divTable .= "\t\t\t<td>不包括豹子、对子、顺子、半顺的所有中奖号码。----如中奖号码为157，中奖号码位数之间无关联性，则投注杂六者视为中奖，其它视为不中奖。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\n<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;' colspan='2'>7.龙虎和特殊玩法： 龙 > 虎 > 和 （0为最小，9为最大）。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";	
			
			$divTable .= "\t\n<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>龙：</td>\r\n";
			$divTable .= "\t\t\t<td>开出之号码第一球（万位）的中奖号码大于第五球（个位）的中奖号码，如 第一球开出4 第五球开出2；第一球开出9 第五球开出8；第一球开出5 第五球开出1...中奖为龙。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\n<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>虎：</td>\r\n";
			$divTable .= "\t\t\t<td>开出之号码第一球（万位）的中奖号码小于第五球（个位）的中奖号码，如 第一球开出7 第五球开出9；第一球开出3 第五球开出5；第一球开出5 第五球开出8...中奖为虎。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
			$divTable .= "\t\n<tr>\r\n";
			$divTable .= "\t\t\t<td style='color:red;'>和：</td>\r\n";
			$divTable .= "\t\t\t<td>开出之号码第一球（万位）的中奖号码等于第五球（个位）的中奖号码，例如开出结果：2***2则投注和局者视为中奖，其它视为不中奖。</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
			
		}
		else if($act == "38") //北京11
		{
			$divTable .= "\t\t<tr><td colspan='3'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第1234567期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='2'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第1/4/7/10/13/16位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>01,13,31,35,44,62</td>\r\n";
			$divTable .= "\t\t\t<td>07,28,33,43,57,65</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>186</td>\r\n";
			$divTable .= "\t\t\t<td>233</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>186除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\t\t<td>233除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_1'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_6'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_1'></li><i class=\"hja\"></i><li class='kj kj_6'></li><i class=\"hdeng\"></i><li class='mh m7'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "39") //蛋蛋11
		{
			$divTable .= "\t\t<tr><td colspan='3'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第1234567期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='2'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第1/2/3/4/5/6位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第13/14/15/16/17/18位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>01,06,07,13,15,28</td>\r\n";
			$divTable .= "\t\t\t<td>44,53,57,62,64,65</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>70</td>\r\n";
			$divTable .= "\t\t\t<td>345</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>70除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\t\t<td>345除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_5'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_4'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_5'></li><i class=\"hja\"></i><li class='kj kj_4'></li><i class=\"hdeng\"></i><li class='mh m9'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "40") //蛋蛋16
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第1234567期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第1/2/3/4/5/6位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第7/8/9/10/11/12位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第13/14/15/16/17/18位数字]</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>01,06,07,13,15,28</td>\r\n";
			$divTable .= "\t\t\t<td>31,32,33,35,40,43</td>\r\n";
			$divTable .= "\t\t\t<td>44,53,57,62,64,65</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>70</td>\r\n";
			$divTable .= "\t\t\t<td>214</td>\r\n";
			$divTable .= "\t\t\t<td>345</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>70除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\t\t<td>214除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\t\t<td>345除以6的余数 + 1</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_5'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_5'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_4'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_5'></li><i class=\"hja\"></i><li class='kj kj_5'></li><i class=\"hja\"></i><li class='kj kj_4'></li><i class=\"hdeng\"></i><li class='mh m14'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "41")
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第1674529期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>7 14 18 23 25 28 30 31 39 41 46 50 51 53 57 59 66 71 72 75(从小到大依次排列)</td>\r\n";
		
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
				
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>14,25,31,46,53,66</td>\r\n";
			$divTable .= "\t\t\t<td>18,28,39,50,57,71</td>\r\n";
			$divTable .= "\t\t\t<td>23,30,41,51,59,72</td>\r\n";
				
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>235</td>\r\n";
			$divTable .= "\t\t\t<td>263</td>\r\n";
			$divTable .= "\t\t\t<td>276</td>\r\n";
				
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
				
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_5'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_6'></li></td>\r\n";
				
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='kj kj_5'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hja\"></i><li class='kj kj_6'></li> <i class=\"hdeng\"></i> <li class='mh m14'></li></td>\r\n";
				
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan=\"4\">游戏总共有0-27共28位数字，0-13为小，14-27为大，奇数为单，偶数为双,以下赔率包含本金</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>大小单双：</td>\r\n";
			$divTable .= "\t\t\t<td>固定赔率2.1倍, 开13,14回本。</td>\r\n";
			$divTable .= "\t\t\t<td>小单</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定4.6倍（包括1,3,5,7,9,11,13）,开13回本。</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>大单：</td>\r\n";
			$divTable .= "\t\t\t<td>固定4.2倍（15,17,19,21,23,25,27）</td>\r\n";
			$divTable .= "\t\t\t<td>小双</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定4.2倍（0.2.4.6.8.10.12)</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>大双：</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定4.6倍（14.16.18.20.22.24.26）,开14回本。</td>\r\n";
			$divTable .= "\t\t\t<td>极小</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定17倍（0,1,2,3,4,5）</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>极大：</td>\r\n";
			$divTable .= "\t\t\t<td   align=\"left\">固定17倍（22,23,24,25,26,27）</td>\r\n";
			$divTable .= "\t\t\t<td>龙：</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定2.9倍（0,3,6,9,12,15,18,21,24,27）</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>虎：</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定2.9倍（1,4,7,10,13,16,19,22,25）</td>\r\n";
			$divTable .= "\t\t\t<td>豹：</td>\r\n";
			$divTable .= "\t\t\t<td  align=\"left\">固定2.9倍（2,5,8,11,14,17,20,23,26）</td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
		}
		else if($act == "42")
		{
			$divTable .= "\t\t<tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第1674529期</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'>7 14 18 23 25 28 30 31 39 41 46 50 51 53 57 59 66 71 72 75(从小到大依次排列)</td>\r\n";
		
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>区位</td>\r\n";
			$divTable .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
			$divTable .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
				
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>数字</td>\r\n";
			$divTable .= "\t\t\t<td>14,25,31,46,53,66</td>\r\n";
			$divTable .= "\t\t\t<td>18,28,39,50,57,71</td>\r\n";
			$divTable .= "\t\t\t<td>23,30,41,51,59,72</td>\r\n";
				
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>求和</td>\r\n";
			$divTable .= "\t\t\t<td>235</td>\r\n";
			$divTable .= "\t\t\t<td>263</td>\r\n";
			$divTable .= "\t\t\t<td>276</td>\r\n";
				
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
			$divTable .= "\t\t\t<td>取尾数</td>\r\n";
				
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_5'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
			$divTable .= "\t\t\t<td><li class='kj kj_6'></li></td>\r\n";
				
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan='3'><li class='kj kj_5'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hja\"></i><li class='kj kj_6'></li> <i class=\"hdeng\"></i> <li class='mh m14'></li></td>\r\n";
				
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr><td colspan=\"4\">游戏总共有0-27共28位数字，0-13为小，14-27为大，奇数为单，偶数为双,以下赔率包含本金</td></tr>\r\n";
			$divTable .= "\t\t<tr><td style=\"border-right:1px solid  #d7d7d7;width:100px\">大小</td><td style=\"border-right:1px solid  #d7d7d7\">固定赔率1.98倍</td><td style=\"border-right:1px solid  #d7d7d7;width:100px\">单双</td><td>固定赔率1.98倍</td></tr>\r\n";
			$divTable .= "\t\t<tr><td style=\"border-right:1px solid  #d7d7d7;\">小单</td><td style=\"border-right:1px solid  #d7d7d7\">固定3.68倍（1.3.5.7.9.11.13）</td><td style=\"border-right:1px solid  #d7d7d7\">大单</td><td>固定4.2倍（15.17.19.21.23.25.27）</td></tr>\r\n";
			$divTable .= "\t\t<tr><td style=\"border-right:1px solid  #d7d7d7\">小双</td><td style=\"border-right:1px solid  #d7d7d7\">固定4.2倍（0.2.4.6.8.10.12)</td><td style=\"border-right:1px solid  #d7d7d7\">大双</td><td>固定3.68倍（14.16.18.20.22.24.26）</td></tr>\r\n";
			$divTable .= "\t\t<tr><td style=\"border-right:1px solid  #d7d7d7\">极小</td><td style=\"border-right:1px solid  #d7d7d7\">固定16倍（0.1.2.3.4.5）</td><td style=\"border-right:1px solid  #d7d7d7\">极大</td><td>固定16倍（22.23.24.25.26.27）</td></tr>\r\n";
		
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td colspan=\"4\"></td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>龙虎和：</td>\r\n";
			$divTable .= "\t\t\t<td colspan=\"3\" align=\"left\"><strong>龙</strong>：开出号码一大于号码三，如 号码一开出
					<li class=\"finalbig\">3</li>
					号码三开出
					<li class=\"finalbig\">1</li>
					；中奖为龙。 <br>
					<strong>虎</strong>：开出号码一小于号码三。如 号码一开出
					<li class=\"finalbig\">0</li>
					号码三开出
					<li class=\"finalbig\">3</li>
					；中奖为虎。 <br>
					<strong>和</strong>：开出号码一等于号码三,中奖为和。 </td>\r\n";
			$divTable .= "\t\t</tr>\r\n";
		}
		else if($act == "43")//飞艇10
		{
			$divTable .= "\t\t<tr><td colspan='2'>采用马耳他飞艇数据，每5分钟一期，每天180期，每天04:04-13:00点暂停开奖</td></tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第20150927131期</td>\r\n";
			$divTable .= "\t\t\t<td><em class='regular06'></em>
							<em class='regular05'></em>
							<em class='regular01'></em>
							<em class='regular03'></em>
							<em class='regular04'></em>
							<em class='regular10'></em>
							<em class='regular07'></em>
							<em class='regular08'></em>
							<em class='regular09'></em>
							<em class='regular02'></em></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取期号尾数，尾数对应第几位数字为开奖号码，如果尾数为0取第10位</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td>第449153期的期号尾数是3,则取第3位，是1</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='mh m1'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "46")//飞艇冠军
		{
			$divTable .= "\t\t<tr><td colspan='2'>采用马耳他飞艇数据，每5分钟一期，每天180期，每天04:04-13:00点暂停开奖</td></tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第20150927131期</td>\r\n";
			$divTable .= "\t\t\t<td><em class='regular06'></em>
							<em class='regular05'></em>
							<em class='regular01'></em>
							<em class='regular03'></em>
							<em class='regular04'></em>
							<em class='regular10'></em>
							<em class='regular07'></em>
							<em class='regular08'></em>
							<em class='regular09'></em>
							<em class='regular02'></em></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>开奖取首位</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='mh m6'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "44")//飞艇22
		{
			$divTable .= "\t\t<tr><td colspan='2'>采用马耳他飞艇数据，每5分钟一期，每天180期，每天04:04-13:00点暂停开奖</td></tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第20150927131期</td>\r\n";
			$divTable .= "\t\t\t<td><em class='regular06'></em>
							<em class='regular05'></em>
							<em class='regular01'></em>
							<em class='regular03'></em>
							<em class='regular04'></em>
							<em class='regular10'></em>
							<em class='regular07'></em>
							<em class='regular08'></em>
							<em class='regular09'></em>
							<em class='regular02'></em></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取开奖号码前3位之和</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>结果</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><em class='regular06'></em> + <em class='regular05'></em> + <em class='regular01'></em> = <li class='mh m12'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><li class='mh m12'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "47") //飞艇龙虎
		{
			$divTable .= "\t\t<tr><td colspan='2'>采用马耳他飞艇数据，每5分钟一期，每天180期，每天04:04-13:00点暂停开奖</td></tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第20150927131期</td>\r\n";
			$divTable .= "\t\t\t<td><em class='regular06'></em>
							<em class='regular05'></em>
							<em class='regular01'></em>
							<em class='regular03'></em>
							<em class='regular04'></em>
							<em class='regular10'></em>
							<em class='regular07'></em>
							<em class='regular08'></em>
							<em class='regular09'></em>
							<em class='regular02'></em></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取第一位和最后一位比较，第一位大为龙，最后一位大为虎</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><em class='regular06'></em> 大于 <em class='regular02'></em>，结果为<li class='lh n1'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		else if($act == "45") //飞艇冠亚军
		{
			$divTable .= "\t\t<tr><td colspan='2'>采用马耳他飞艇数据，每5分钟一期，每天180期，每天04:04-13:00点暂停开奖</td></tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td width='120'>如第20150927131期</td>\r\n";
			$divTable .= "\t\t\t<td><em class='regular06'></em>
							<em class='regular05'></em>
							<em class='regular01'></em>
							<em class='regular03'></em>
							<em class='regular04'></em>
							<em class='regular10'></em>
							<em class='regular07'></em>
							<em class='regular08'></em>
							<em class='regular09'></em>
							<em class='regular02'></em></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>计算</td>\r\n";
			$divTable .= "\t\t\t<td>取开奖号码前两位之和</td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
				
			$divTable .= "\t\t<tr>\r\n";
			$divTable .= "\t\t\t<td>开奖</td>\r\n";
			$divTable .= "\t\t\t<td colspan=3><em class='regular06'></em> + <em class='regular05'></em> = <li class='mh m11'></li></td>\r\n";
			$divTable .= "\t\n</tr>\r\n";
		}
		
		
		$divTable .= "\t</tbody>\r\n";
		$divTable .= "\t</table>\r\n";
		 
		$divTable .= "</div>\r\n";  
		return $divTable;
    }
    
    

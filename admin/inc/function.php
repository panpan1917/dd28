<?php
/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  : RM     */
/*  Comment : 071223 */
/*                   */
/*********************/

/*取得sql语句limit
 * $curPage:当前页
 * $PageSize:页大小
 */
function GetLimit($curPage,$PageSize=20)
{
	if($curPage < 1) $curPage = 1;
	$BeginNum = $PageSize * ($curPage - 1);
	return " limit " . $BeginNum . "," . $PageSize;
}
/*取得sql条件范围
*  $fieldName  列名
*  $from       开始
*  $to         结束
*  $isNum	   是否为数字
*/
function GetSqlBetween($fieldName,$from,$to,$isNum)
{
	$str = "";
	$retstr = "";
	if(!$isNum)
		$str = "'";
	if($from != "" && $to == "")
	{
		$retstr .= " and ({$fieldName} < {$str}{$from}{$str})" ;
	}
	elseif($from == "" && $to != "")
	{
		$retstr .= " and ({$fieldName} > {$str}{$to}{$str})";
	}
	elseif($from != "" && $to != "")
	{
		$retstr .= " and ({$fieldName} between  {$str}{$from}{$str} and {$str}{$to}{$str})";
	}

	return $retstr;
}
function showerr($mess,$url){
	echo $mess;
}
function setPassword($str){
	return md5($GLOBALS['web_pwd_encrypt_prefix'].$str);
}
function FilterStr($str)
{
	if (inject_check($str)) { exit('error parameter!'); }
	if (!get_magic_quotes_gpc()){
		$str=addslashes($str);
	}
	$str=str_replace("%","/%",$str);
	$str=htmldecode($str);
	return $str;
}
function ArrayChangeEncode(& $arrfrom)
{
	foreach($arrfrom as $k => & $arr)
	{
		foreach($arr as $t => & $v)
		{
			$v = ChangeEncodeG2U($v);
		}
	}
}
function ChangeEncodeU2G($s)
{
	return $s;
	return iconv("UTF-8", "GB2312//IGNORE", $s);
}

function ChangeEncodeG2U($s)
{
	return $s;
	return iconv("GB2312", "UTF-8", $s);
}
function Trans($num)
{
	return number_format($num);
}

function WriteLog($Msg,$flag=FILE_APPEND)
{
	$LogFile = dirname(dirname(__FILE__)) . '/log/logtext.txt';
	$Msg = date("Y-m-d H:i:m ") . $Msg ."\r\n";
	file_put_contents($LogFile, $Msg,$flag);
}

function filterKey($str){
	return preg_match('/PHP_EOL|replace|group_concat|table|create|call|drop|database|alter|select|insert|update|delete|name_const|where|having|from|\sand\s|\sor\s|truncate|script|union|into|\'|\/\*|\*|\.\.\/|\.\/|#|load_file|outfile/i',$str,$matches);
}

function global_check($verify = 1){
	foreach($_GET as $key=>$value){
		if(filterKey($value)) exit("access denied!");
	}
	foreach($_POST as $key=>$value){
		if(filterKey($value)) exit("access denied!");
	}
	foreach($_COOKIE as $key=>$value){
		if(filterKey($value)) exit("access denied!");
	}
	foreach($_REQUEST as $key=>$value){
		if(filterKey($value)) exit("access denied!");
	}
	
	if($_SESSION["VerifyCode"] != getVerifyCode() && $verify){
		exit("access denied!");
	}
}


function getVerifyCode(){
	return md5(md5("admin_" . usersip() . "_game28") . "kdy28");
}


function login_check( $groupname  , $verify = 1)
{
	global_check($verify);
	
	global $db;
	global $web_dbtop;
	if ( empty( $_SESSION["Admin_Name"] ) || empty( $_SESSION["Admin_Pwd"] ) )//$_COOKIE['AdminName']
	{
		echo "<script>top.location.href='admin_login.php';</script>";
		exit( );
	}
	else
	{
		$query = $db->query( "Select groupbox From {$web_dbtop}admin where name='".str_check( $_SESSION["Admin_Name"] )."' And password='".str_check( $_SESSION["Admin_Pwd"] )."'" );//$_COOKIE['PassWord']
		if ( !( $rs = $db->fetch_array( $query ) ) )
		{
			echo "<script>top.location.href='admin_login.php';</script>";
			exit( );
		}
		else if ( !stristr( $rs['groupbox'].",", $groupname."," ) && $groupname != 1 )
		{
			if(IS_AJAX){
				$arrReturn[0]["cmd"] = "err";
				$arrReturn[0]["msg"] = "对不起,您没有权限对该项目进行操作";
				ArrayChangeEncode($arrReturn);
				echo json_encode($arrReturn);
			}else{
				echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd\">\r\n";
				echo "<HTML xmlns=\"http://www.w3.org/1999/xhtml\">\r\n";
				echo "<HEAD>\r\n";
				echo "<TITLE>后台管理系统</TITLE>\r\n";
				echo "<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n";
				echo "<LINK href=\"images/css_body.css\" type=text/css rel=stylesheet>\r\n";
				echo "<META content=\"MSHTML 6.00.3790.4275\" name=GENERATOR>\r\n";
				echo "</HEAD>\r\n";
				echo "<BODY>\r\n";
				showerr( "对不起,您没有权限对该项目进行操作", "admin_index.php" );
				echo "</BODY></HTML>";
			}
			exit;
		}
	}
}

function addlog( $content )
{
	global $db;
	global $web_dbtop;
	$AdminName = str_check($_SESSION["Admin_Name"]);//$_COOKIE['AdminName']
	$db->query( "INSERT INTO {$web_dbtop}log (logcontent,logtime,logname,logip) VALUES ('".$content."','".date( "Y-m-d H:i:s" )."','".$AdminName."','".usersip( )."')" );
}

function usersip( )
{
	if ( getenv( "HTTP_CLIENT_IP" ) )
	{
		$ip = getenv( "HTTP_CLIENT_IP" );
	}
	else if ( getenv( "HTTP_X_FORWARDED_FOR" ) )
	{
		$ip = getenv( "HTTP_X_FORWARDED_FOR" );
	}
	else if ( getenv( "HTTP_X_FORWARDED" ) )
	{
		$ip = getenv( "HTTP_X_FORWARDED" );
	}
	else if ( getenv( "HTTP_FORWARDED_FOR" ) )
	{
		$ip = getenv( "HTTP_FORWARDED_FOR" );
	}
	else if ( getenv( "HTTP_FORWARDED" ) )
	{
		$ip = getenv( "HTTP_FORWARDED" );
	}
	else
	{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	
	if(filterKey($ip)) exit("access denied!");
	
	global $db;
	$sql = "select ip from admin_ips where ip='{$ip}' limit 1";
	$result = $db->query($sql);
	$RowCount = $db->num_rows($result);
	if(empty($RowCount)){
		exit("access denied!");
	}
	
	return $ip;
}

function showstars( $num )
{
	$starthreshold = 3;
	$alt = "alt=\"等级: ".$num."级\"";
	if ( empty( $starthreshold ) )
	{
		$i = 0;
		for ( ;	$i < $num;	++$i	)
		{
			echo "<img src=\"../images/score/1.gif\" ".$alt." />";
		}
	}
	else
	{
		$i = 6;
		for ( ;	0 < $i;	--$i	)
		{
			$numlevel = intval( $num / pow( $starthreshold, $i - 1 ) );
			$num %= pow( $starthreshold, $i - 1 );
			$j = 0;
			for ( ;	$j < $numlevel;	++$j	)
			{
				echo "<img src=\"../images/score/".$i.".gif\" ".$alt." />";
			}
		}
	}
}
function userslog( $logtype, $log, $points, $experience, $usersid )
{
	global $db;
	global $web_dbtop;
	$db->query( "INSERT INTO {$web_dbtop}userslog (time,logtype,log,points,experience,usersid) VALUES ('".date( "Y-m-d H:i:s" )."',".intval( $logtype ).",'".str_check( $log )."',".intval( $points ).",".intval( $experience ).",".intval( $usersid ).")" );
}
function fsockurl($httpurl){
	$url=explode("/",$httpurl);
	$urls=$url[2];
	if(stristr($urls,":")){
		$w_url=explode(":",$urls);
		$urls=$w_url[0];
		$port=$w_url[1];
	}else{
		$port=80;
	}
	for($i=3;$i<count($url);$i++){
		$pstr .= "/".$url[$i];
	}
	$fp = @fsockopen($urls,$port);
	if($fp){
		$out = "GET $pstr HTTP/1.1\r\n";
		$out .= "Host: $urls\r\n";
		$out .= "Connection: Close\r\n\r\n";
		fwrite($fp, $out);
		while (!feof($fp)){
			$httpcontent.=fgets($fp, 1024);
		}
		fclose($fp);
	}
	$httpcontent=explode("\r\n\r\n",$httpcontent,2);
	return $httpcontent[1];
}

function createrandstring( $length, $type )
{
	$hash = "";
	$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	switch ( $type )
	{
		case 0 :
			$max = 9;
			break;
		case 1 :
			$max = 35;
			break;
		case 2 :
			$max = strlen( $chars ) - 1;
			break;
		default :
			$max = 9;
			break;
	}
	$i = 0;
	for ( ;	$i < $length;	++$i	)
	{
		$hash .= $chars[mt_rand( 0, $max )];
	}
	return $hash;
}

function admin_log($opr,$amount,$points,$bank,$remark,$uid=0){
	/*
uidbigint(20) NOT NULL用户id
opr_typeint(11) NOT NULL类型，0：存，1：取，2：充值体验卡，3：转账入，4：转账出,5:在线充值,6:领取救济,7:兑奖点卡,8:推荐收益,55:系统会员充值,12:退回提现,10:提现通过,11:提现申请
amountbigint(20) NOT NULL数量
log_timedatetime NOT NULL时间
ipvarchar(15) NOT NULLip
pointsbigint(20) NOT NULL操作后豆
bankpointsbigint(20) NOT NULL操作后银行豆
remarkvarchar(254) NOT NULL备注
	 */
	global $db;
	$uid=$uid?:$_SESSION['users'];
	$ip=usersip();
	$sql="INSERT INTO score_log(uid,opr_type,amount,log_time,ip,points,bankpoints,remark) values ('$uid','$opr','$amount',now(),'$ip','-$points','$bank','$remark')";
	$db->query($sql);
}
function withdrawals_log($opr,$points,$bank,$remark,$uid=0){
	admin_log($opr, $points,0, $bank, $remark,$uid);

}
if(!function_exists('result')){
	function result($code,$msg){
		echo json_encode(['status'=>$code,'message'=>$msg],JSON_UNESCAPED_UNICODE);
	}
}
function cz_type($id){
	$arr=[1=>'支付宝',2=>'微信',3=>'银行卡',4=>'支付宝',5=>'微信',7=>'借贷宝'];
	return $arr[$id];
}

//取赔率类型
function GetGameOddsType($act)
{
	$reward_num_type = "game28";
	if($act == "1" || $act == "5" || $act == "9" || $act == "19")
		$reward_num_type = "game16";
	else if($act == "2" || $act == "10" || $act == "20")
		$reward_num_type = "game11";
	else if($act == "6" || $act == "7" || $act == "15")
		$reward_num_type = "game10";
	else if($act == "11" || $act == "12" || $act == "13" || $act == "21")
		$reward_num_type = "game36";
	else if($act == "14")
		$reward_num_type = "game22";
	else if($act == "16")
		$reward_num_type = "gamelh";
	else if($act == "17")
		$reward_num_type = "gamegyj";

	return  $reward_num_type;
}
?>

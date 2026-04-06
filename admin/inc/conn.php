<?php
session_set_cookie_params(86400 * 30);
session_start();
ini_set("display_errors", "Off");
error_reporting(E_ERROR);
include_once(dirname( __FILE__ ) ."/mysql_class.php");
include_once(dirname( __FILE__ ) . "/config.php");
$db = new db;
$db->connect($web_datahost, $web_datauser, $web_datapassword, $web_database, $web_pconnect);


if(function_exists('date_default_timezone_set')) { 
	date_default_timezone_set('Asia/Chongqing');
}


checkIP();
checkSession();
globalCheckKey();




function checkSession(){
	if(!isset($_SESSION["Admin_UserID"]) && $_SERVER['PHP_SELF'] != "/kdywlist-003.php" && $_SERVER['PHP_SELF'] != "/admin_login.php" && $_SERVER['PHP_SELF'] != "/slogin.php")
	{
		if($_SERVER['PHP_SELF'] == "/index.php"){
			echo "<script>top.location.href='admin_login.php';</script>";
			exit;
		}
		
		if(IS_AJAX){
			$arrNoLogin = array(array());
			$arrNoLogin[0]["cmd"] = "err_nologin";
			$arrNoLogin[0]["msg"] = "页面超时或您还没登录，请重新登录!";
			echo json_encode($arrNoLogin);
			exit;
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
			echo "页面超时或您还没登录，请重新登录!";
			echo "</BODY></HTML>";
			exit;
		}
	}
}



function globalFilterKey($str){
	return preg_match('/PHP_EOL|replace|group_concat|table|create|call|drop|database|alter|select|insert|update|delete|name_const|where|having|from|\sand\s|\sor\s|truncate|script|union|into|\'|\/\*|\*|\.\.\/|\.\/|#|load_file|outfile/i',$str,$matches);
}

function globalCheckKey(){
	foreach($_GET as $key=>$value){
		if(globalFilterKey($value)) exit("kdy28:Illegal operation!");
	}
	foreach($_POST as $key=>$value){
		if(globalFilterKey($value)) exit("kdy28:Illegal operation!");
	}
	foreach($_COOKIE as $key=>$value){
		if(globalFilterKey($value)) exit("kdy28:Illegal operation!");
	}
	foreach($_REQUEST as $key=>$value){
		if(globalFilterKey($value)) exit("kdy28:Illegal operation!");
	}
}


function checkIP()
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
	
	
	if(globalFilterKey($ip)) exit("kdy28:Illegal operation!");


	if($_SERVER['PHP_SELF'] != "/kdywlist-003.php"){
		global $db;
		$sql = "select ip from admin_ips where ip='{$ip}' limit 1";
		$result = $db->query($sql);
		$RowCount = $db->num_rows($result);
		if(empty($RowCount) || empty($ip)){
			
				echo "<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n";
				echo "对不起，你不能访问这里!";
				exit;
		}
	}
	
	if(isRealip($ip)) 
		return $ip;
	else 
		return "";
}


function isRealip($ip){
	if(preg_match('/^((?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1 -9]?\d))))$/', $ip)){
		return true;
	}else{
		return false;
	}
}


function inject_check($sql_str) {     
//return eregi('select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile', $sql_str);    // 进行过滤     
return preg_match('/PHP_EOL|select|insert|update|delete|name_const|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i',$sql_str,$matches);
} 

function str_check($str){
	if (inject_check($str)) { exit('提交的参数非法！'); }
	if (!get_magic_quotes_gpc()){
		$str=addslashes($str);
	}
	//$str=str_replace("_","/_",$str);
	$str=str_replace("%","/%",$str);
	$str=htmldecode($str);
	return $str;
}

function htmldecode($str) {
	 $str = strip_tags ( $str ); 
	 $str = htmlspecialchars ( $str ); 
	 //$str = nl2br ( $str ); 
	 //$str = str_replace ( "?", "", $str ); 
	 $str = str_replace ( "*", "", $str ); 
	 $str = str_replace ( "!", "", $str ); 
	 $str = str_replace ( "~", "", $str ); 
	 $str = str_replace ( "$", "", $str ); 
	 $str = str_replace ( "%", "", $str ); 
	 $str = str_replace ( "^", "", $str ); 
	 $str = str_replace ( "^", "", $str ); 
	 $str = str_replace ( "select", "", $str ); 
	 $str = str_replace ( "join", "", $str ); 
	 $str = str_replace ( "union", "", $str ); 
	 $str = str_replace ( "where", "", $str ); 
	 $str = str_replace ( "insert", "", $str ); 
	 $str = str_replace ( "delete", "", $str ); 
	 $str = str_replace ( "update", "", $str ); 
	 $str = str_replace ( "like", "", $str ); 
	 $str = str_replace ( "drop", "", $str ); 
	 $str = str_replace ( "create", "", $str ); 
	 $str = str_replace ( "modify", "", $str ); 
	 $str = str_replace ( "rename", "", $str ); 
	 $str = str_replace ( "alter", "", $str ); 
	 $str = str_replace ( "cast", "", $str ); 	 
	 $str = str_replace ( "truncate", "", $str ); 
	 $str = str_replace ( "exec", "", $str ); 	
	 $str = str_replace ( ";", "", $str ); 
	 //$str = str_replace ( ",", "", $str );
	 $str = str_replace ( "=", "", $str );
	 
	 $filter = array("/\f\r\t\v/" , "/<(\/?)(script|i?frame|object|meta|\?|\%)([^>]*?)>/isU" , "/(<[^>]*)on[a-zA-Z]\s*=([^>]*>)/isU");
	 $replace = array(" " , "" , "\\1\\2");
	 $str = preg_replace($filter, $replace, $str);
	 //过滤影响页面代码
	 $filter = array("/\f\r\t\v/" , "/<(\/?)(style|html|body|title|link|\?|\%)([^>]*?)>/isU" , "/(<[^>]*)on[a-zA-Z]\s*=([^>]*>)/isU");
	 $replace = array(" " , "&lt;\\1\\2\\3&gt;" , "\\1\\2");
	 $str = preg_replace($filter, $replace, $str);	
	 
	 return $str;
 
 }

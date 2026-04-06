<?php
session_start();
ini_set("display_errors", "Off");
//error_reporting(E_ERROR);
error_reporting(E_ALL ^ E_NOTICE);
include_once ("mysql_class.php");
include_once ("config.php");
include_once (dirname(__DIR__)."/../data/config.php");
include_once (dirname(__DIR__)."/../core/define.php");
$web_datahost=$dbhost;
$web_database=$database;
$web_datauser=$dbuser;
$web_datapassword=$dbpass;
define(CONTROLLER,'b.php' );
$db = new db;
$db->connect($web_datahost, $web_datauser, $web_datapassword, $web_database, $web_pconnect);

if(function_exists('date_default_timezone_set')) { 
	date_default_timezone_set('Asia/Chongqing');
}

//***全局过滤********************************************************************************
global_check();
function global_check()
{
    foreach($_GET as $key=>$value){
    	if(checkSqlKey($value)) exit("didi8888 access denied!");
        StopAttack($key,$value);
    }
    foreach($_POST as $key=>$value){
    	if(checkSqlKey($value)) exit("didi8888 access denied!");
        StopAttack($key,$value,1);
    }
    foreach($_COOKIE as $key=>$value){
    	if(checkSqlKey($value)) exit("didi8888 access denied!");
        StopAttack($key,$value,2);
    }
    foreach($_REQUEST as $key=>$value){
    	if(checkSqlKey($value)) exit("didi8888 access denied!");
    	StopAttack($key,$value,2);
    }
}
function StopAttack($StrFiltKey,$StrFiltValue,$type=0){
    $filter[0]="'|(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?Select|Update.+?SET|Insert\\s+INTO.+?VALUES|(Select|Delete).+?FROM|(Create|Alter|Drop|TRUNCATE)\\s+(TABLE|DATABASE)" ;
    $filter[1]="\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?Select|Update.+?SET|Insert\\s+INTO.+?VALUES|(Select|Delete).+?FROM|(Create|Alter|Drop|TRUNCATE)\\s+(TABLE|DATABASE)" ;
    $filter[2]="\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?Select|Update.+?SET|Insert\\s+INTO.+?VALUES|(Select|Delete).+?FROM|(Create|Alter|Drop|TRUNCATE)\\s+(TABLE|DATABASE)" ;
    $ArrFiltReq=$filter[$type];
        
    if(is_array($StrFiltValue))
    {
        $StrFiltValue=implode($StrFiltValue);
    }
    if (preg_match("/".$ArrFiltReq."/is",$StrFiltValue)==1){
        //slog("<br><br>操作IP: ".$_SERVER["REMOTE_ADDR"]."<br>操作时间: ".strftime("%Y-%m-%d %H:%M:%S")."<br>操作页面:".$_SERVER["PHP_SELF"]."<br>提交方式: ".$_SERVER["REQUEST_METHOD"]."<br>提交参数: ".$StrFiltKey."<br>提交数据: ".$StrFiltValue);
        print "access denied!" ;
        exit();
    }
}
//***************************************************************************************

function checkSqlKey($str){
	return preg_match('/PHP_EOL|replace|group_concat|table|create|call|drop|database|alter|select|insert|update|delete|name_const|where|having|from|\sand\s|\sor\s|truncate|script|\'|\/\*|\*|\.\.\/|\.\/|#|union|into|load_file|outfile/i',$str,$matches);
}

function inject_check($sql_str) {     
    //return eregi('select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile', $sql_str);      
	return preg_match('/PHP_EOL|replace|group_concat|table|create|call|drop|database|alter|select|insert|update|delete|name_const|where|having|from|\sand\s|\sor\s|truncate|script|\'|\/\*|\*|\.\.\/|\.\/|#|union|into|load_file|outfile/i',$sql_str,$matches);
}

function getRefererRoot(){
	$url = $_SERVER['HTTP_REFERER'] . "/";
	preg_match("/((\w*):\/\/)?\w*\.?([\w|-]*\.(com.cn|net.cn|gov.cn|org.cn|com|net|cn|gov|org|asia|tel|mobi|me|tv|biz|cc|name|info))\//", $url, $ohurl);
	if($ohurl[3] == ''){
		preg_match("/((\d+\.){3}\d+)\//", $url, $ohip);
		return $ohip[1];
	}
	return $ohurl[3];
}

function str_check($str){
	if (inject_check($str)) { exit('error parameter!'); }
	if (!get_magic_quotes_gpc()){
		$str=addslashes($str);
	}
	$str=str_replace("_","/_",$str);
	$str=str_replace("%","/%",$str);
	$str=htmldecode($str);
	return $str;
}

function htmldecode($str) {
	 if (empty ( $str ) || "" == $str) { 
		return ""; 
	 } 
	 $str = strip_tags ( $str ); 
	 $str = htmlspecialchars ( $str ); 
	 //$str = nl2br ( $str ); 
	 $str = str_replace ( "?", "", $str ); 
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
	 //����Ӱ��ҳ�����
	 $filter = array("/\f\r\t\v/" , "/<(\/?)(style|html|body|title|link|\?|\%)([^>]*?)>/isU" , "/(<[^>]*)on[a-zA-Z]\s*=([^>]*>)/isU");
	 $replace = array(" " , "&lt;\\1\\2\\3&gt;" , "\\1\\2");
	 $str = preg_replace($filter, $replace, $str);	
	 return $str;
 }


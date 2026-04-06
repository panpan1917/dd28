<?php
if(APP_DEBUG)error_reporting(E_ALL ^ E_NOTICE);
//报告运行时错误
define('KKINC', str_replace("\\", '/', dirname(__FILE__)));
define('TPL', ROOT . '/template/');
defined('APP_PATH')  or define('APP_PATH', '/default/');
defined('EXTENSION') or define('EXTENSION','.inc');
define('MODEL',KKROOT.'/core/model/');
set_include_path(KKROOT."/lib/util");
define('IS_AJAX',((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_POST[('VAR_AJAX_SUBMIT')]) || !empty($_GET[('VAR_AJAX_SUBMIT')])) ? true : false);
require_once 'define.php';
require_once 'func.php';

set_exception_handler("myHandleException");
/* 加载类 */
function __autoload($classname) {
        if (strpos($classname, '\\') !== false) {
            $filename = ROOT . DIRECTORY_SEPARATOR . $classname . '.php';

            if (file_exists($filename)) {
                require_once $filename;
                return true;
            }
        } else {
            $filename = KKINC . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . ucfirst($classname) . '.php';
            if (file_exists($filename)) {
                require_once($filename);
                return true;
            }
            $filename = KKINC . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR . ucfirst($classname) . '.php';
            if (file_exists($filename)) {
                require_once($filename);
                return true;
            }
            $filename = ROOT . DIRECTORY_SEPARATOR. 'lib'. DIRECTORY_SEPARATOR .APP_PATH. DIRECTORY_SEPARATOR .'action'. DIRECTORY_SEPARATOR . ucfirst($classname) . '.php';
            if (file_exists($filename)) {
                require_once($filename);
                return true;
            }
            $filename=ROOT.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.APP_PATH.DIRECTORY_SEPARATOR.'util'.DIRECTORY_SEPARATOR.$classname.'.php';
            if (file_exists($filename)) {
                require_once($filename);
                return true;
            }
            $filename=ROOT.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'util'.DIRECTORY_SEPARATOR.$classname.'.php';
            if (file_exists($filename)) {
                require_once($filename);
                return true;
            }
        }
    if(APP_DEBUG){
        throw new AppException("没有找到类--->".$classname);
    }else{
        die("发生错误了,请联系管理员");
    }

}
if (file_exists(DATA_PATH . '/config.php')) {
    require_once DATA_PATH . '/config.php';
} else {
    exit('找不到数据库配置文件,请运行 <span style="color:#f00">网站域名/install</span> 进行安装');
}
function myHandleException($e){
    $traceInfo='';
    $time = date('y-m-d H:i:m');
    foreach($e->getTrace() as $t) {
        $traceInfo .= '['.$time.'] '.$t['file'].' ('.$t['line'].') ';
        $traceInfo .= $t['class'].$t['type'].$t['function'].'(';
        $traceInfo .= implode(', ', $t['args']);
        $traceInfo .=")<br>\n";
    }
    $result='<h1>'.$e->getMessage().'</h1>';
    //$result.=$e->class.':'.$e->function;
    $result.='<p>'.$traceInfo.'</p>';
    echo $result;
}

//定义日志
define('MOD_DEVICE', 90);

function customError($errno, $errstr, $errfile, $errline)
{
    echo "<b>Error number:</b> [$errno],error on line $errline in $errfile<br />" ;
    die();
}
set_error_handler("customError",E_ERROR);

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
        print "didi8888 access denied!" ; 
        exit();
    }
}

function checkSqlKey($str){
	return preg_match('/PHP_EOL|replace|group_concat|table|select|create|call|drop|database|insert|update|delete|name_const|where|from|and|truncate|script|union|into|\sand\s|\sor\s|\'|\/\*|\*|\.\.\/|\.\/|#|load_file|outfile/i',$str,$matches);
}

//$ArrPGC=array_merge($_GET,$_POST,$_COOKIE);
foreach($_GET as $key=>$value){
	if(checkSqlKey($value)) exit("didi8888.net access denied!");
    StopAttack($key,$value);
}
foreach($_POST as $key=>$value){
	if(checkSqlKey($value)) exit("didi8888.net access denied!");
    StopAttack($key,$value,1);
}
foreach($_COOKIE as $key=>$value){
	if(checkSqlKey($value)) exit("didi8888.net access denied!");
    StopAttack($key,$value,2);
}
foreach($_REQUEST as $key=>$value){
	if(checkSqlKey($value)) exit("didi8888.net access denied!");
	StopAttack($key,$value,2);
}

function slog($logs)
{
    $toppath=$_SERVER["DOCUMENT_ROOT"]."/log.htm";
    $Ts=fopen($toppath,"a+");
    fputs($Ts,$logs."\r\n");
    fclose($Ts);
}

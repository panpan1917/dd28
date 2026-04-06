<?php
if (!defined('KKINC')) exit('Request Error!');
function url($s,$params=array()){
   $s=explode('/',$s );
    //if(count(1)){}
    $url='';
    if(is_array($s)){
        $count=count($s);
        for($i=0;$i<$count;$i++){
            if($i==0){
                $url.='?c='.$s[$i];
            }elseif($i==1){
                $url.='&a='.$s[$i];
            }else{
                $url.='&'.$s[$i].'='.$s[++$i];
            }

        }
    }
    foreach ($params as $k=>$v){
        $url.='&'.$k.'='.$v;
    }
    return $url;
}
/*
function url($params=array()){
    $url=$params['name'].'?';
    foreach($params as $k=>$v){
        if($k!='name' || $k!='c' || $k!='a')$url.='&'.$k.'='.$v;
    }
    return $url;
}*/
function U($types, $params)
{
    $url = '';
    $arg = array(
        'static'=>null,
        'aid' => 0,
        'tid' => 0,
        'page' => 0,
        'domain' => '',
        'time'=>0,
        'goods'=>0
    );
    if($arg['static']===null)$arg['static']=$GLOBALS['k_static'];
    $arg=array_merge($arg, $params);
    switch ($types) {
        case 'admin':
            if(is_array($params)){

            }else{

            }
            break;
        case 'list':
            if (!$arg['tid']) return 'not tid';
            switch ($arg['static']) {
                case 1:
                case 2:
                    $url='/list/'.$arg['tid'];
                    if ($arg['page']>1) $url .= '_'.$arg['page'];
                    $url.='.html';
                    //兼容以前版本
                    if($arg['listrule']){
                        if ($arg['page'] <= 1) $arg['listrule'] = str_replace('_{page}', '', $arg['listrule']);
                        $url = str_replace(array('{sitepath}', '{tid}', '{page}'), array($arg['domain'], $arg['tid'], $arg['page']), $arg['listrule']);
                    }
                    break;
                default:
                    $url = 'list.php?tid='. $arg['tid'];
                    if ($arg['page']) $url .= '&amp;page=' . $arg['page'];
                    break;
            }
            break;
        case 'view':
            if (!$arg['aid']) return 'not aid';
            switch ($arg['static']) {
                case 1:

                case 2:
                    if($arg['time']) {
                        $url = '/view/' . date('Y', $arg['time']) . '/' . date('m', $arg['time']) . '/' . $arg['aid'];
                    }else{
                        $url='/view/'.$arg['aid'];
                    }
                    if ($arg['page']) $url .='_'.$arg['page'];
                    $url.='.html';
                    //兼容以前版本
                    if($arg['viewrule']){
                        $url=str_replace(array('{sitepath}', '{tid}', '{y}', '{m}', '{d}', '{aid}', '{page}'), array($arg['domain'], $arg['tid'], date('Y', $arg['time']), date('m', $arg['time']), date('d', $arg['time']), $arg['id'], $arg['page']), strtolower($arg['viewrule']));
                    }
                    break;
                default:
                    $url .= 'view.php?aid='. $arg['aid'];
                    if ($arg['page']) $url .= '&amp;page=' . $arg['page'];
                    break;
            }
            break;
        case 'goods':
            return '?c=goods&id='.$arg['id'];
        default:
            return false;
            break;
    }
    return $url;
}

/* * ==============
 * 读取文件
 * 
 * @param string $filename 文件名称
 *  @return    string
  ============== */
function read_file($filename)
{
    $handle = fopen($filename, 'r');
    $contents = fread($handle, filesize($filename));
    fclose($handle);
    return $contents;
}

/* * ==============
 * 写入文件
 * 
 * @param string 文件名称
 *  @param string  内容
  ============== */
function writer_file($filename, $con)
{
    create_folder(dirname($filename));
    $handle = fopen($filename, 'w');
    if (!$handle) show_msg('创建文件失败' . $filename, -1, 9000);
    fwrite($handle, $con);
    fclose($handle);
    return true;
}

/* * ==============
 * 循环创建文件
 * @param string $cdir 文件路径
 * @return string 文件路径
  ============== */
function create_folder($path)
{
    if (!file_exists($path)) {
        create_folder(dirname($path));
        if (!mkdir($path, 0777)) {
            exit('创建目录失败,请设权限为777--<br>' . $path);
        }
    }
}

function memory_size($size)
{
    $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

/* * ==============
 * location
 * @param int $tid
 * @return string
  ============== */
function get_location($tid,$is_ton=0)
{
    global $k_weburl,$base;
    $string='';
    $c=$base->get_arctype($tid);
    if($c['fid'])$string.=get_location($c['fid'],1);
    $string .= ' > <a href="'.U('list',array('tid'=>$c['id'])).'">' . $c['typename'] . '</a> ';
    if(!$is_ton)$string='<a href="'.$k_weburl.'">首页</a> '.$string;
    return $string;
}

/* * ==============
 *  获取执行时间
 *  例如:$t1 = ExecTime();
 *       在一段内容处理之后:
 *       $t2 = ExecTime();
 *  我们可以将2个时间的差值输出:echo $t2-$t1;
 *
 *  @return    int
  ============== */
if (!function_exists('ExecTime')) {

    function ExecTime()
    {
        $time = explode(" ", microtime());
        $usec = (double)$time[0];
        $sec = (double)$time[1];
        return $sec + $usec;
    }

}

/* * ==============
 * 获取 IP  
 * @Return: string
  ============== */
function get_ip()
{
    if ($_SERVER["HTTP_CLIENT_IP"]) $ip = $_SERVER["HTTP_CLIENT_IP"];
    else if ($_SERVER["HTTP_X_FORWARDED_FOR"]) $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else if ($_SERVER["REMOTE_ADDR"]) $ip = $_SERVER["REMOTE_ADDR"];
    else if (getenv("HTTP_X_FORWARDED_FOR")) $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("HTTP_CLIENT_IP")) $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("REMOTE_ADDR")) $ip = getenv("REMOTE_ADDR");
    else $ip = "1.1.1.1";
    
	if(strpos($ip,",")){
        $ip_data=explode(",",$ip);
        $ip = $ip_data[1];
    }
    
    StopAttack('ip',str_replace('.','',$ip));
    
    if(strlen($ip) > 15) $ip = ""; 
    
    return $ip;
}

/* * ==============
 * 获取 IP  地理位置
 * 淘宝IP接口
 * @Return: array
  ============== */
function get_city($ip)
{
    $url = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip;

    if (!$con = file_get_contents($url)) return 0;
    $ip = json_decode($con);
    if ((string)$ip->code == '1') {
        return false;
    }
    $data = (array)$ip->data;
    return $data;
}

/* * ==============
 *  *  获取首页模板名称
 *  * @return    string
 * ============= */
function get_tpl_index()
{
    $res = db::get_one('select `values` from __config where `group`=2 and `name`=\'k_tplindex\'');
    return $res->values;
}

/* * ==============
 *  *  获取文件列表
 *
 * @param     string  $dir      目录
 * @return    array
 * ============= */
function read_list_file($dir)
{
    $fileArray[] = NULL;
    if (false != ($handle = opendir($dir))) {
        $i = 0;
        while (false !== ($file = readdir($handle))) {
            //去掉"“.”、“..”以及带“.xxx”后缀的文件
            if ($file != "." && $file != ".." && strpos($file, ".")) {
                $fileArray[$i] = $file;
                if ($i == 100) {
                    break;
                }
                $i++;
            }
        }
        //关闭句柄
        closedir($handle);
    }
    return $fileArray;
}

function read_list_dir($dir)
{
    $fileArray[] = NULL;
    if (false != ($handle = opendir($dir))) {
        $i = 0;
        while (false !== ($file = readdir($handle))) {
            //去掉"“.”、“..”以及带“.xxx”后缀的文件
            if ($file != "." && $file != "..") {
                $fileArray[$i] = $file;
                if ($i == 100) {
                    break;
                }
                $i++;
            }
        }
        //关闭句柄
        closedir($handle);
    }
    return $fileArray;
}
function msg($status,$message,$url='',$limittime=2000,$type='json'){
    if($type=='json'){
        exit(json_encode(array('status'=>$status,'message'=>$message,'url'=>$url,'time'=>$limittime)));
    }
}
/* * ==============================================
 *  短消息函数,可以在某个动作处理后友好的提示信息
 *
 * @param     string  $msg      消息提示信息
 * @param     string  $gourl    跳转地址
 * @param     int     $limittime  限制时间(毫秒)
 * @return    void
  =============================================== */
function show_msg($msg, $gourl = '-1', $limittime = 1000)
{
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html;charset=' . $GLOBALS['coding'] . '" /><meta http-equiv="X-UA-Compatible" content="IE=7" /><title>提示信息</title><style type="text/css"> <!--
*{padding:0;margin:0;font-size:12px}a:link,a:visited{text-decoration:none;color:#0068a6}a:hover,a:active{color:#ff6600;text-decoration:underline}ul{list-style:none;}#error_tips{border:1px solid #d4d4d4;background:#fff;-webkit-box-shadow:#ccc 0 1px 5px;-moz-box-shadow:#ccc 0 1px 5px;-o-box-shadow:#ccc 0 1px 5px;box-shadow:#ccc 0 1px 5px;filter:progid:DXImageTransform.Microsoft.Shadow(Strength=3,Direction=180,Color=\'#ccc\');width:500px;margin:50px auto;}#error_tips h2{background:#f9f9f9;background-repeat:no-repeat;background-image:-webkit-gradient(linear,0 0,0 100%,from(#ffffff),color-stop(25%,#ffffff),to(#f4f4f4));background-image:-webkit-linear-gradient(#ffffff,#ffffff 25%,#f4f4f4);background-image:-moz-linear-gradient(top,#ffffff,#ffffff 25%,#f4f4f4);background-image:-ms-linear-gradient(#ffffff,#ffffff 25%,#f4f4f4);background-image:-o-linear-gradient(#ffffff,#ffffff 25%,#f4f4f4);background-image:linear-gradient(#ffffff,#ffffff 25%,#f4f4f4);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#ffffff\',endColorstr=\'#f4f4f4\',GradientType=0);border-bottom:1px solid #dfdfdf;}#error_tips h2{font:bold 14px/40px Arial;height:40px;padding:0 20px;color:#666;}.error_cont{padding:20px 20px 30px 80px;background-image:url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAMAAABg3Am1AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAwBQTFRF+s1O2oEE9Y0J+ME896Ii/9tK//Ri+MZB+9FV/s5d+bA05JQT9p4Z9qMe96Eh+a0v/MVR240O++1b+slM97Mu+rY8+a4x+e9O+LQy+asv/uR19ZAM5Zsb+Kkr/NNc97Yx954e3qFL9rAq+MQ+qamp+LEu+sZI/dtl96Yl96sm9qcj+Lgz+sRG9qEc9OHF68WQ+bo5/d5s/NJZ+clG25g6+cNE/9hqrq6u/tpS5IYH9poX/9RM7I0K+7tD/dpm+shJ+71G+chC/MJN/+Z40HoA9qwo/ddh/8I2/Nhf+cNA/95e/r42/sM6/clW9ZkU96gm/uBt+85V+L86+81S3JkV7pQT/dNR14oL/dxp/eVJ/9lX/Nde9qQg9ZYS3psa+L8++bw8968r1H0C/dpi+9FT/9NC+75I85cT+b49+rU8+bI33IUI9JUS/uZZ/NVe/NRd+9Za+bw6+7hA+rg++Kco9pgV7p4c9qkkw8PD6urq4eHh9qYh19fX////xMTE964pwMDAu7u7yMjI57p7t7e3+L04+Ls19qgk/v38s7Oz1IUV/uNz0X0G9ZQP7cuc2ZUz7MqZy8vL/MRP0NDQ+9Zc97Er9PT07qkm96wn/d5p/uFw5eXl/d9r5LJs8929+9BY6cCH140k2pY25rd19Kcj9K4q8di01ooe/uJw+b0+9qol+Lg1+Lo32ZMw5rl4+85T5LRv57x+36Ad+OxR+s9P9p8c/fnz+spQ4q5j/+pg/8Y896Ag/MVB98c++Ksr9uNE9eNJ9pcU7okH8JsX/MBL9pkX8p0Z9psX2JAq4asg9sE0138E96gl/stD/MtE/85H9ZoV9+RR9ZsW+eRT2n8C/8k8/Lcv+Kop+s1J4IkK/NVa6L+E/Lgw+Lw2/70z+b449uTM/dlk/+tQ/tVl/+Bb/+Ff/tNj+b8/7tCl+9NW+Lg33JUT5LEq6L8v+etW+8xQ7MiV/s9P+Lc2/Ndc/9A//+h647Fp/9do//pV//1g/NdB/9VF68Q4zMzMmZmZ////+FQ4bwAAAQB0Uk5T////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////AFP3ByUAAANLSURBVHjaYvhPImCghga95kULXVwWLmrWI0rDguVxfy6++/lz84G3IcsXENQwYe0bIbadT6LCwqxZH/0QP7l2An4NzYKXd3p5eHwueJmy5YbxOdZH+8Ob8WmYuyn3aXKyunqyx62XWxiMHR3PskaGzMWtoTku18tDfeacOTPVPW6lADWwszv+jnzdjEtDX7hF1JTkiIAVKwIikqdwMBizKwNB6q/wPhwa+vdavIxWD+h2du6eox4N1sDMzKyceqofuwZ9GeuPHEANUl+/SgXYATWoeTIHAYHPF259bBo6XO6fFw7MszME2WD4MH+9sClzUCsIXG1z6cCiYZ7grj32743cIgJmzQpQdzN6r+YZdLcNBLzdBedh0bBYxidDRzhQzs0uIsLOzWi9vWnGGm0IcJdZjEWDyXTvNc9NhYPny+Xlyc0PFjaNX6MtDwH3pptg0dC19I68QYap/fbgwMDg7WqmK9fIi0DBtaVdWDT0KE0VkV+d8NxUR01Nx/R5obbEVDhQ6sGmwbW+PlFV4pVBwsqVCQafJFQT6+HAFZuGriWampozQHqAAKh6hiYCLMHmJJOychBYpekKBJqrypFBGTZPf+OuhgAtLa12ENCqhoEY7nVYNCzL5oWBai1/f/8zWjFwgWw9rEnjmC4E8Gr4X9+377p/NS9U4PAJrInv9k0eMNDl9d/HLybGf12DdytEJPsDVg0dLqHHQWAriyw/V1YWF38JCw9YINQER36YcDrdz8/vkq4Gf5ZocXEm1z6NrReAAulJL3Bl0XWMObEHrXbLconapjnYioqVbC2Njc1h3Ii7ENiQ3ZsD1JBlm+bklFbEVaJQ2ttruQFfMaNo02vFwi+aNsnXd5JDluzu0l4bl234NCxLkj7KIlbk5MvJ6XskU5LvoDTjB/wl3w5LaT4x20mcj59xOolKKjAd6idQVD5IYmIqsRXgNPvOOaloNxPTlT4CGir+oYEKAhr+TatFAdP+EbTBvA4JmBO0oeJf518k0EnYSZVogAgnNSABWjipCg0QdtI/lUY4UCEcD0At5hOboGCieQUR1e6/GiTwjwgNIEe1gAEWB+FoOsz+C7Tm39/ZJLQ1/k2e/I+kxkkFNteQ15oBCDAA5QPXCaaTZBQAAAAASUVORK5CYII=");background-repeat:no-repeat;background-position:20px 20px;line-height:1.8;}.error_return{padding:10px 0 0 0;}-->
</style></head><body>';
    if ($gourl == '-1') $gourl = "javascript:history.go(-1);";
    if ($gourl == '') {
        echo $msg = "<script>alert(\"" . str_replace("\"", "\"", $msg) . "\");</script>";
        return;
    } else {
        if($gourl==1)$gourl=$_SERVER['PHP_SELF'];
        if($limittime==1)header('Location:'.$gourl);
        $go = 'window.location.href=\'' . $gourl . '\';';
        $html .= '<div id="error_tips"><h2>信息提示</h2>	<div class="error_cont"><ul><li>' . $msg . '</li></ul><div class="error_return"><a href="' . $gourl . '" class="btn">如果您的浏览器没自动跳转，请点击这里</a></div></div></div><script>setTimeout("' . $go . '",' . $limittime . ');</script>';
    }
    $html .= '</body></html>';
    exit($html);
}

function exit_msg($msg, $gourl = '-1', $limittime = 0)
{
    exit(show_msg($msg, $gourl, $limittime));
}

/**
 *  中文截取2，单字节截取模式
 *
 * @access    public
 * @param     string $str 需要截取的字符串
 * @param     int $slen 截取的长度
 * @param     int $startdd 开始标记处
 * @return    string
 */
function cn_substr($str, $length, $start = 0)
{
    if (strlen($str) < $start + 1) {
        return '';
    }
    preg_match_all("/./su", $str, $ar);
    $str = '';
    $tstr = '';
    //为了兼容mysql4.1以下版本,与数据库varchar一致,这里使用按字节截取
    for ($i = 0; isset($ar[0][$i]); $i++) {
        if (strlen($tstr) < $start) {
            $tstr .= $ar[0][$i];
        } else {
            if (strlen($str) < $length + strlen($ar[0][$i])) {
                $str .= $ar[0][$i];
            } else {
                break;
            }
        }
    }
    return $str;
}

function handleMissedException($e){
    show_msg('错误: '.$e->getMessage().'<Br>在文件 '.$e->getFile().' 中的行 '.$e->getLine(),-1,3000);
}

function set_error_handlers($errno, $errstr, $errfile, $errline){
    echo ('错误: '.$errstr.'<Br>在文件 '.$errfile.' 中的行 '.$errline);
}

function ini_check($data){
    if(is_array($data)){
       return _RunMagicQuotes($data);
    }
    return $data;
}
function _RunMagicQuotes(&$svar) {
    if (!get_magic_quotes_gpc()) {
        if (is_array($svar)) {
            foreach ($svar as $_k => $_v)
                $svar[$_k] = _RunMagicQuotes($_v);
        } else {
            if (strlen($svar) > 0 && preg_match('#^(GLOBALS|_GET|_POST|_COOKIE|_REQUEST)#', $svar)) {
                exit('Request var not allow!');
            }
            $svar = addslashes($svar);
        }
    }
    return $svar;
}
function setPassword($str){
    return md5($GLOBALS['web_pwd_encrypt_prefix'].$str);
}

function file_exists_case($filename) {
    if (is_file($filename)) {
        if (IS_WIN && C('APP_FILE_CASE')) {
            if (basename(realpath($filename)) != basename($filename))
                return false;
        }
        return true;
    }
    return false;
}

function is_mobile() {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$mobile_agents = array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi",
			"android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio",
			"au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu",
			"cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ",
			"fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","honor",
			"htc","huawei","hutchison","inno","ipad","ipaq","iphone","ipod","jbrowser","kddi",
			"kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo",
			"mercator","meridian","mi ","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-",
			"moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia",
			"nook","novarra","obigo","oppo","palm","panasonic","pantech","philips","phone","pg-",
			"playstation","pocket","pt-","qc-","qtek","redmi","rover","sagem","sama","samu","sanyo",
			"samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank",
			"sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit",
			"tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vivo",
			"vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce",
			"wireless","xda","xde","zte");
	$is_mobile = false;
	foreach ($mobile_agents as $device) {
		if (stristr($user_agent, $device)) {
			$is_mobile = true;
			break;
		}
	}
	return $is_mobile;
}


function curPageURL()
{
	$pageURL = 'http';

	if ($_SERVER["HTTPS"] == "on")
	{
		$pageURL .= "s";
	}
	$pageURL .= "://";

	if ($_SERVER["SERVER_PORT"] != "80")
	{
		$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
	}
	else
	{
		$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}


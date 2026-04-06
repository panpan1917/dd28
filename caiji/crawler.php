<?php
set_time_limit(0);
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL ^ E_NOTICE);

if(count($argv) > 0){
	foreach($argv as $arg){
		$p=explode("=",$arg);
		if(count($p) == 2)
			$_REQUEST[$p[0]] = $p[1];
		else
			$_REQUEST[$p[0]] = null;
	}
}

$source = $_REQUEST['source'];
if(empty($source)) exit;
$crawlerClass = $source . "Crawler";
include_once(dirname( __FILE__ ) ."/" . $crawlerClass . ".class.php");

$No = (int)$_REQUEST['No'];
$resultStr = (string)$_REQUEST['resultStr'];
$crawler = new $crawlerClass;
$startTime = time();
while(!$crawler->stop()){
	$count = $crawler->crawler();
	
	if($No > 0){
		$crawler->saveCrawlerData($No , $resultStr);
		$crawler->open($No);
		break;
	}

	if($count > 0){
		for($i=0;$i<$count;$i++){
			$crawler->open();
		}
	}else{
		$crawler->open();
	}
	
	$endTime = time();
	if($endTime - $startTime > 900){
		$crawler->close();
	}
}



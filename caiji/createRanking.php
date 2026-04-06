<?php
set_time_limit(0);
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL ^ E_NOTICE);

include_once(dirname( __FILE__ ) ."/Mysql.class.php");

$argv[1] = (int)$argv[1];

$topSize = 30;//排行榜单top30

//SELECT uid FROM users_inner ORDER BY RAND() LIMIT 20;
$users[0] = [6784234,489720,557205,6900493,757563,177679,6841956,722801,6840429,6745107,6963017,6947276,835664,6876767,6885344,6857824,6834244,396691,6921669,6853869];
$users[1] = [6782151,6834244,835664,6746326,6921669,6882027,177679,548338,6963017,489720,557205,6878828,396691,6804688,6947276,278519,151994,6745107,722801,6857824];
$users[2] = [177679,722801,6990332,548338,6841956,6746326,6853869,6784234,151994,6829721,6782151,557205,6804688,396691,6834244,835664,6840429,6745107,6876767,6947276];
$users[3] = [722801,6921669,6882027,396691,6878828,6990332,6963017,6885344,6834244,6876767,6746326,489720,757563,151994,835664,177679,6804688,6840429,6782151,6900493];
$users[4] = [6857824,6990332,177679,557205,6876767,6947276,278519,6784234,835664,151994,489720,6745107,6963017,757563,6804688,722801,6921669,548338,6841956,6829721];
$users[5] = [6840429,835664,6876767,548338,6882027,6921669,6746326,6900493,6782151,6804688,151994,6947276,396691,6834244,6784234,6841956,278519,6963017,6745107,6885344];
$users[6] = [6840429,757563,6876767,177679,6882027,6990332,6857824,6782151,6885344,6947276,6853869,6829721,489720,722801,396691,557205,835664,6804688,6834244,151994];

$idx = (int)date("w",strtotime("-{$argv[1]} day"));
$time = date("Y-m-d",strtotime("-{$argv[1]} day"));

$minpoints = 0;
$maxpoints = 0;

$db = new db();

$sql = "SELECT DATE_ADD(NOW(),INTERVAL -0 DAY)";
$time = $db->getOne($sql);
$hourminute = substr($time , 11 , 5);
$time = substr($time , 0 , 10);
if($hourminute == "00:00"){//00:00时分统计前一天的数据
	$sql = "SELECT DATE_ADD(CURDATE(),INTERVAL -1 DAY)";
	$time = $db->getOne($sql);
}


//删除10天前记录
$time_10days_before = date("Y-m-d",strtotime("-10 day"));
$sql = "delete from rank_log where time < '{$time_10days_before}'";
$db->execute($sql);

$sql = "delete from rank_log where time = '{$time}'";
$db->execute($sql);

//统计真实玩家top30的分数
$sql = "SELECT SUM(points) AS sumpoints,uid,`time` FROM game_day_static WHERE `time`='{$time}' GROUP BY uid,`time` ORDER BY sumpoints DESC LIMIT {$topSize}";
$rows = $db->getAll($sql);
for($i=0;$i<count($rows);$i++){
	$uid = $rows[$i]['uid'];
	$sumpoints = $rows[$i]['sumpoints'];
	if($i == 0) $maxpoints = $sumpoints;
	if($i > 9) $minpoints = $sumpoints;
	
	$sql = "select count(*) from rank_log where uid={$uid} and time='{$time}'";
	$cnt = $db->getOne($sql);
	if($cnt == 0){
		$sql = "insert into rank_log(uid,points,time) values({$uid},{$sumpoints},'{$time}')";
		$db->execute($sql);
	}else{
		$sql = "update rank_log set points={$sumpoints} where uid={$uid} and time='{$time}'";
		$db->execute($sql);
	}
}


//生成20个机器人的分数
for($i=0;$i<count($users[$idx]);$i++){
	$uid = $users[$idx][$i];
	
	//if($i <= 9) $sumpoints = $maxpoints + rand(2000000,50000000);//top10设置为机器人
	//else $sumpoints = rand($minpoints , $minpoints+5000000);//$maxpoints
	if($maxpoints < 200000000){
		if($i <= 9) $sumpoints = $maxpoints + rand(2000000,50000000);//top10设置为机器人
		else $sumpoints = rand($minpoints , $minpoints+5000000);//$maxpoints
	}else{
		if($i <= 2) $sumpoints = $maxpoints + rand(2000000,50000000);//top5设置为机器人
		else $sumpoints = rand($minpoints , $minpoints+5000000);//$maxpoints
	}
	
	$sql = "select count(*) from rank_log where uid={$uid} and time='{$time}'";
	$cnt = $db->getOne($sql);
	if($cnt == 0){
		$sql = "insert into rank_log(uid,points,time) values({$uid},{$sumpoints},'{$time}')";
		$db->execute($sql);
	}else{
		$sql = "update rank_log set points={$sumpoints} where uid={$uid} and time='{$time}'";
		$db->execute($sql);
	}
}



/* 
CREATE TABLE `rank_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `points` bigint(20) NOT NULL DEFAULT '0' COMMENT '分数',
  `time` date NOT NULL DEFAULT '0000-00-00' COMMENT '日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk` (`uid`,`time`),
  KEY `time` (`time`),
  KEY `points` (`points`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 */


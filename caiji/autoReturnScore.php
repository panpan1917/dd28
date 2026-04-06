<?php
set_time_limit(0);
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL ^ E_NOTICE);

include_once(dirname( __FILE__ ) ."/Mysql.class.php");

define('MULTIPLE_LOSS' , 3);//亏损返利需要3倍亏损额流水
define('MULTIPLE_LOSS_W' , 8);//周亏损返利需要8倍亏损额流水
define('REBATE_MONEY',0.02);//每日亏损返利百分比
define('REBATE_MONEY_W',0.10);//每周亏损返利百分比
define('FIRST_REBATE_MONEY',0.10);//每日首充返利百分比
define('FIRST_REBATE_RATE',10);//每日首充返利所需流水倍数
define('COMMISSION_RATE',0.0011);//推荐奖励千分比

$db = new db();

returnRecommRebate();
returnLossRebate();
//returnLossRebateW();
//returnRechargeRebate();
returnRankRebate();


/* 推荐奖励(有效流水计算) */
function returnRecommRebate(){
	global $db;
	
	$file = dirname( __FILE__ ) ."/".date("Ymd")."_RecommRebate.log";
	
	$sql = "SELECT game_table_prefix,reward_num FROM game_config WHERE state=1";
	$rowsgame = $db->getAll($sql);
	$rowsgamecnt = count($rowsgame);
	
	$recommRebateArr = array();
	
	$sql = "SELECT DISTINCT a.uid,b.`tjid` FROM presslog a,users b WHERE a.uid=b.id AND b.tjid > 0 AND to_days(NOW())-to_days(a.`presstime`)=1";
	$rows = $db->getAll($sql);
	$rowscnt = count($rows);
	for($i=0;$i<$rowscnt;$i++){
		$uid = $rows[$i]['uid'];
		$tjid = $rows[$i]['tjid'];
		
		$sql = "select ifnull(sum(tzpoints),0) as validpoints from game_day_static where uid={$uid} and to_days(now())-to_days(time)=1";
		$validpoints = $db->getOne($sql);
		
		if($validpoints > 0){
			if(empty($recommRebateArr[$tjid]))
				$recommRebateArr[$tjid] = floor($validpoints * COMMISSION_RATE);
			else 
				$recommRebateArr[$tjid] = $recommRebateArr[$tjid] + floor($validpoints * COMMISSION_RATE);
		}
	}
	
	if(count($recommRebateArr) > 0){
		foreach($recommRebateArr as $key=>$val){
			
			$sql = "select dj_extension from users where id={$key}";
			$dj_extension = $db->getOne($sql);
			if($dj_extension > 0) continue;
			
			$sql = "select count(*) as cnt from score_log where opr_type=21 and uid={$key} and to_days(now())=to_days(log_time)";
			$logcnt = $db->getOne($sql);
			if($logcnt > 0) continue;
			
			$db->execute('SET AUTOCOMMIT=0');
			$db->execute('begin');
			
			$sql = "update users set `back`=`back`+{$val} where id={$key}";//更新用户银行分
			$ret = $db->execute($sql);
			if($ret===FALSE){
				$db->execute("rollback");
				continue;
			}
			$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
			@file_put_contents($file, $content,FILE_APPEND);
			
			$sql = "update centerbank set `score`=`score`-{$val} where bankIdx ='7'";//更新中央银行活动分
			$ret = $db->execute($sql);
			if($ret===FALSE){
				$db->execute("rollback");
				continue;
			}
			$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
			@file_put_contents($file, $content,FILE_APPEND);
			
			$sql = "insert into game_static(uid,typeid,points) values({$key},104, {$val}) on duplicate key update points=points+{$val}";//更新用户每日统计
			$ret = $db->execute($sql);
			if($ret===FALSE){
				$db->execute("rollback");
				continue;
			}
			$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
			@file_put_contents($file, $content,FILE_APPEND);
			
			$sql = "insert into webtj(`time`,tgpoints) values(now(),{$val}) on duplicate key update tgpoints=tgpoints+{$val}";//更新站点每日统计
			$ret = $db->execute($sql);
			if($ret===FALSE){
				$db->execute("rollback");
				continue;
			}
			$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
			@file_put_contents($file, $content,FILE_APPEND);
			
			$sql="insert into score_log(uid,opr_type,amount,log_time,ip,points,bankpoints,remark) select id,21,{$val},now(),'0.0.0.0',points,back,'推荐奖励' from users where id={$key}";//记录自动领取日志
			$ret = $db->execute($sql);
			if($ret===FALSE){
				$db->execute("rollback");
				continue;
			}
			$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
			@file_put_contents($file, $content,FILE_APPEND);
			
			$db->execute('commit');
			$db->execute('SET AUTOCOMMIT=1');
			
		}
	}
	
}

/* 亏损返利 */
function returnLossRebate(){
	global $db;
	
	$file = dirname( __FILE__ ) ."/".date("Ymd")."_LossRebate.log";
	
	$sql = "select sum(a.points) as points,sum(a.tzpoints) as tzpoints,a.uid from game_day_static a,users b where a.uid=b.id and b.dj_rebate=0 and to_days(now())-to_days(a.`time`)=1 group by a.uid";
	$rows = $db->getAll($sql);
	$rowscnt = count($rows);
	for($i=0;$i<$rowscnt;$i++){
		$uid = $rows[$i]['uid'];
		$tzpoints = (int)$rows[$i]['tzpoints'];
		$points = (int)$rows[$i]['points'];
		if($points >= 0) continue;
		
		if($tzpoints >= abs($points) * MULTIPLE_LOSS)
			$lossRebate = abs($points) * REBATE_MONEY;
		else
			$lossRebate = abs($points) * REBATE_MONEY/2;
			
		$lossRebate=floor($lossRebate);
		
		if($lossRebate <= 0) continue;
		
		$sql = "select dj_rebate from users where id={$uid}";
		$dj_rebate = $db->getOne($sql);
		if($dj_rebate > 0) continue;
		
		$sql = "select count(*) as cnt from score_log where opr_type=20 and uid={$uid} and to_days(now())=to_days(log_time)";
		$logcnt = $db->getOne($sql);
		if($logcnt > 0) continue;
		
		$db->execute('SET AUTOCOMMIT=0');
		$db->execute('begin');
		
		$sql = "update users set `back`=`back`+{$lossRebate} where id={$uid}";//更新用户银行分
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
		
		$sql = "update centerbank set `score`=`score`-{$lossRebate} where bankIdx ='9'";//更新中央银行活动分
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
		
		$sql = "insert into game_static(uid,typeid,points) values({$uid},142, {$lossRebate}) on duplicate key update points=points+{$lossRebate}";//更新用户每日统计
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
		
		$sql = "insert into webtj(`time`,rebate) values(now(),{$lossRebate}) on duplicate key update rebate=rebate+{$lossRebate}";//更新站点每日统计
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
		
		$sql="insert into score_log(uid,opr_type,amount,log_time,ip,points,bankpoints,remark) select id,20,{$lossRebate},now(),'0.0.0.0',points,back,'日亏损返利' from users where id={$uid}";//记录自动领取日志
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
		
		$db->execute('commit');
		$db->execute('SET AUTOCOMMIT=1');
	}
}


/* 周亏损返利 */
function returnLossRebateW(){
	global $db;

	$file = dirname( __FILE__ ) ."/".date("Ymd")."_LossRebateW.log";
	
	$dayNo = date("w") > 0 ? date("w") : 7;

	$Mon = date("Y-m-d",mktime(0, 0 , 0,date("m"),date("d")-$dayNo+1-7,date("Y")));
	$SUN = date("Y-m-d",mktime(0, 0 , 0,date("m"),date("d")-$dayNo+7-7,date("Y")));

	$sql = "select sum(a.points) as points,sum(a.tzpoints) as tzpoints,a.uid from game_day_static a,users b where a.uid=b.id and b.dj_rebate=0 and a.`time`>='{$Mon}' and a.`time`<='{$SUN}' group by a.uid";
	$rows = $db->getAll($sql);
	$rowscnt = count($rows);
	for($i=0;$i<$rowscnt;$i++){
		$uid = $rows[$i]['uid'];
		$tzpoints = (int)$rows[$i]['tzpoints'];
		$points = (int)$rows[$i]['points'];
		if($points >= 0) continue;

		if($tzpoints >= abs($points) * MULTIPLE_LOSS_W){
			$lossRebate = abs($points) * REBATE_MONEY_W;
		}else if(($tzpoints < abs($points) * MULTIPLE_LOSS_W) && ($tzpoints >= abs($points) * (MULTIPLE_LOSS_W - 3))){
			$lossRebate = abs($points) * REBATE_MONEY_W/2;
		}else{
			$lossRebate = 0;
		}

		$lossRebate=floor($lossRebate/7);

		if($lossRebate <= 0) continue;

		$sql = "select dj_rebate from users where id={$uid}";
		$dj_rebate = $db->getOne($sql);
		if($dj_rebate > 0) continue;

		$sql = "select count(*) as cnt from score_log where opr_type=30 and uid={$uid} and to_days(now())=to_days(log_time)";
		$logcnt = $db->getOne($sql);
		if($logcnt > 0) continue;

		$db->execute('SET AUTOCOMMIT=0');
		$db->execute('begin');

		$sql = "update users set `back`=`back`+{$lossRebate} where id={$uid}";//更新用户银行分
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);

		$sql = "update centerbank set `score`=`score`-{$lossRebate} where bankIdx ='9'";//更新中央银行活动分
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);

		$sql = "insert into game_static(uid,typeid,points) values({$uid},142, {$lossRebate}) on duplicate key update points=points+{$lossRebate}";//更新用户每日统计
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);

		$sql = "insert into webtj(`time`,rebate) values(now(),{$lossRebate}) on duplicate key update rebate=rebate+{$lossRebate}";//更新站点每日统计
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);

		$sql="insert into score_log(uid,opr_type,amount,log_time,ip,points,bankpoints,remark) select id,30,{$lossRebate},now(),'0.0.0.0',points,back,'周亏损返利' from users where id={$uid}";//记录自动领取日志
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);

		$db->execute('commit');
		$db->execute('SET AUTOCOMMIT=1');
	}
}


/* 首充返利 */
function returnRechargeRebate(){
	global $db;
	
	$file = dirname( __FILE__ ) ."/".date("Ymd")."_RechargeRebate.log";
	
	$sql = "select distinct uid from pay_online where state=1 and to_days(now())-to_days(pay_time)=1";
	$rows = $db->getAll($sql);
	$rowscnt = count($rows);
	for($i=0;$i<$rowscnt;$i++){
		$uid = $rows[$i]['uid'];
		
		$sql = "select count(*) as cnt from score_log where opr_type=210 and uid={$uid} and to_days(now())=to_days(log_time)";
		$logcnt = $db->getOne($sql);
		if($logcnt > 0) continue;
		
		$sql = "select point from pay_online where uid={$uid} and state=1 and to_days(now())-to_days(pay_time)=1 order by id asc limit 1";
		$res = $db->getRow($sql);
		if(empty($res['point'])) continue;
		
		$rechargeRebate = floor($res['point'] * FIRST_REBATE_MONEY);
		
		if($rechargeRebate <= 0) continue;

		$sql = "select sum(tzpoints) as tzpoints from game_day_static where uid={$uid} and to_days(now())-to_days(`time`)=1";
		$tzrows = $db->getRow($sql);
		if(empty($tzrows['tzpoints'])) continue;
		if($tzrows['tzpoints'] < ($res['point'] * FIRST_REBATE_RATE)) continue;
		
		
		$db->execute('SET AUTOCOMMIT=0');
		$db->execute('begin');
		
		$sql = "update users set `back`=`back`+{$rechargeRebate} where id={$uid}";//更新用户抵扣分
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
		
		$sql = "update centerbank set `score`=`score`-{$rechargeRebate} where bankIdx ='9'";//更新中央银行活动分
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
		
		
		$sql = "insert into game_static(uid,typeid,points) values({$uid},142, {$rechargeRebate}) on duplicate key update points=points+{$rechargeRebate}";//更新用户每日统计
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
		
		$sql = "insert into webtj(`time`,rebate) values(now(),{$rechargeRebate}) on duplicate key update rebate=rebate+{$rechargeRebate}";//更新站点每日统计
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
			
		$sql="insert into score_log(uid,opr_type,amount,log_time,ip,points,bankpoints,remark) select id,210,{$rechargeRebate},now(),'0.0.0.0',points,back,'首充返利' from users where id={$uid}";//记录自动领取日志
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
			
		$db->execute('commit');
		$db->execute('SET AUTOCOMMIT=1');
		
	}
	
	
}


/* 排行奖励 */
function returnRankRebate(){
	global $db;
	
	$file = dirname( __FILE__ ) ."/".date("Ymd")."_RankRebate.log";
	
	$sql = "select a.prize_points as points,a.uid from rank_list a,users b where a.uid=b.id and b.dj_rankrebate=0 and b.usertype=0 and a.rank_type=1 and a.state=0 and to_days(now())-to_days(a.theday)=1 group by a.uid";
	$rows = $db->getAll($sql);
	$rowscnt = count($rows);
	for($i=0;$i<$rowscnt;$i++){
		$uid = $rows[$i]['uid'];
		$points = (int)$rows[$i]['points'];
		if($points <= 0) continue;
		
		$sql = "select dj_rankrebate from users where id={$uid}";
		$dj_rankrebate = $db->getOne($sql);
		if($dj_rankrebate > 0) continue;
		
		$sql = "select count(*) as cnt from score_log where opr_type=80 and uid={$uid} and to_days(now())=to_days(log_time)";
		$logcnt = $db->getOne($sql);
		if($logcnt > 0) continue;
		
		$rankRebate = $points;
		
		$db->execute('SET AUTOCOMMIT=0');
		$db->execute('begin');
			
		$sql = "update users set `back`=`back`+{$rankRebate} where id={$uid}";//更新用户银行分
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
			
		$sql = "update centerbank set `score`=`score`-{$rankRebate} where bankIdx ='9'";//更新中央银行活动分
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
			
		$sql = "insert into game_static(uid,typeid,points) values({$uid},105, {$rankRebate}) on duplicate key update points=points+{$rankRebate}";//更新用户每日统计
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
			
		$sql = "insert into webtj(`time`,rankingpoints) values(now(),{$rankRebate}) on duplicate key update rankingpoints=rankingpoints+{$rankRebate}";//更新站点每日统计
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
			
		$sql="insert into score_log(uid,opr_type,amount,log_time,ip,points,bankpoints,remark) select id,80,{$rankRebate},now(),'0.0.0.0',points,back,'排行奖励' from users where id={$uid}";//记录自动领取日志
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
			
		$db->execute('commit');
		$db->execute('SET AUTOCOMMIT=1');
	}
}




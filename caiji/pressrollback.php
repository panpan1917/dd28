<?php
set_time_limit(0);
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL ^ E_NOTICE);

include_once(dirname( __FILE__ ) ."/Mysql.class.php");


$db = new db();
$sql = "select game_type,game_table_prefix from game_config order by id";
$gameres = $db->getAll($sql);
foreach($gameres as $game){
	$game_type = $game['game_type'];
	$game_table_prefix = $game['game_table_prefix'];
	$table = $game_table_prefix;
	$table_kg_users_tz = $game_table_prefix."_kg_users_tz";
	$table_users_tz = $game_table_prefix."_users_tz";
	
	$file = dirname( __FILE__ ) ."/".date("Ymd")."_pressrollback_".$game_type.".log";
	
	$minSec = 1800;
	$maxSec = 86400;
	
	if(in_array($game_type , [8,9,10,13,27,28,35]) && date("H:i") >= "18:50" && date("H") <= "21:20"){
		$minSec = 9000;//加拿大暂停期间
	}
	
	if(in_array($game_type , [18,19,20,21,30,31,34]) && date("H:i") >= "04:58" && date("H") <= "07:05"){
		$minSec = 7400;//韩国暂停期间
	}
	
	$sql = "SELECT sum(a.tzpoints) as tzpoints,a.NO,a.uid FROM {$table_kg_users_tz} a,{$table} b,users u
				WHERE a.NO=b.`id` AND b.`kj` = 0 
				AND a.`uid` = u.`id` AND u.`usertype`=0 
				AND a.tzpoints > 0
				AND UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(b.kgtime) >= {$minSec}
				AND UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(b.kgtime) <= {$maxSec}
				GROUP BY a.NO,a.uid";
	$tzres = $db->getAll($sql);
	$tzrescnt = count($tzres);
	for($i=0;$i<$tzrescnt;$i++){
		$uid= $tzres[$i]['uid'];
		$tzpoints = $tzres[$i]['tzpoints'];
		$gameno = $tzres[$i]['NO'];
		
		
		$db->execute("SET AUTOCOMMIT=0");
		$db->execute("begin");
		
		//$table
		/* $sql = "update {$table} set kj=1 where id={$gameno}";
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND); */
		
		//user_score_changelog
		$sql = "delete from user_score_changelog where uid={$uid} and gametype='{$game_type}' and gameno='{$gameno}'";
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
		
		//$table_kg_users_tz
		$sql = "delete from {$table_kg_users_tz} where uid={$uid} and NO={$gameno}";
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
		
		
		//$table_users_tz
		$sql = "delete from {$table_users_tz} where uid={$uid} and NO={$gameno}";
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
		
		
		//game_static
		$sql = "update game_static set points=points+{$tzpoints} where uid={$uid} and typeid='{$game_type}'";
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
		
		//presslog
		$sql = "delete from presslog where uid={$uid} and no={$gameno} and gametype='{$game_type}'";
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
		
		//users
		$sql = "update users set points=points+{$tzpoints},lock_points=lock_points-{$tzpoints} where id={$uid}";
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
		
		
		//score_log
		$sql = "insert score_log(uid,opr_type,amount,log_time,ip,points,bankpoints,remark) select id,9,{$tzpoints},now(),'',points,back,'投注返还' from users where id={$uid}";
		$ret = $db->execute($sql);
		if($ret===FALSE){
			$db->execute("rollback");
			continue;
		}
		$content = date("Y-m-d H:i:s") . ":" . $sql . "\n";
		@file_put_contents($file, $content,FILE_APPEND);
		
		
		$db->execute("commit");
		$db->execute("SET AUTOCOMMIT=1");
	}
	
}




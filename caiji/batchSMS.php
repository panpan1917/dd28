<?php
set_time_limit(0);
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL ^ E_NOTICE);

include_once(dirname( __FILE__ ) ."/Mysql.class.php");


$db = new db();
$sql = "SELECT username FROM users WHERE usertype=0 AND dj=0";
$rows = $db->getAll($sql);
foreach($rows as $row){
	if(!empty($row['username'])){
		//$content = "滴滴全面升级，回馈新老玩家大幅提升赔率，提现免手续大额无忧，欢迎新玩家入驻老玩家回归";
		$content = "中秋佳节，圆月皎洁，短信不缺，祝福跳跃，快乐如雪，纷飞不歇，忧愁全解，烦恼逃曳，好运真切，幸福的确，滴滴祝：中秋快乐!";
		$url="http://utf8.sms.webchinese.cn/?Uid=xuhui888&Key=Thgj1708qq123123&smsMob={$row['username']}&smsText={$content}";
		file_get_contents($url);
		sleep(2);
	}
}



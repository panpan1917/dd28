<?php
session_start();
ini_set("display_errors", "On");
error_reporting(E_ERROR);
include_once(dirname( __FILE__ ) ."/inc/mysql_class.php");
include_once(dirname( __FILE__ ) . "/inc/config.php");
$db = new db;
$db->connect($web_datahost, $web_datauser, $web_datapassword, $web_database, $web_pconnect);

$ret = array();
$sql = "select * from game_result where gametype in('gamebj','gamepk','gamecan') order by id desc limit 20";
$result = $db->query($sql);
while($row=$db->fetch_array($result)){
	$ret[] = $row;
}
echo json_encode($ret);

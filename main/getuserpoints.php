<?php
	include_once("inc/conn.php");
	include_once("inc/function.php");
	$usersid=$_SESSION['usersid'];
	$sql = "SELECT points,back as bankpoints FROM users WHERE id='{$usersid}' LIMIT 1";
	$result = $db->query($sql);
	$users = $db->fetch_row($result);
	if(!empty($users)){
		echo json_encode(array('points'=>$users[0],'bankpoints'=>$users[1]));
	}else{
		echo json_encode(array('points'=>'','bankpoints'=>''));
	}

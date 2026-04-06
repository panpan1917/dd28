<?php
	include_once("inc/conn.php");
	include_once("inc/function.php"); 
	
	$userid = (int)$_SESSION['usersid'];
	$sql = "update users set alertmsg='' where id = '{$userid}'";
	$result = $db->query($sql);
	
	echo json_encode(array());
?>

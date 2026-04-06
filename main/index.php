<?php
error_reporting(E_ALL ^ E_NOTICE);
include_once("inc/function.php");

if(is_mobile() && !$_GET['pc']) {
	header('location:mobile.php?tj='.$_GET['tj'].'&referer='.$_GET['referer']);
}else{ 
	header('location:pcindex.php?tj='.$_GET['tj'].'&referer='.$_GET['referer']);
}


exit;



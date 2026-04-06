<?php
	include_once("inc/conn.php");
	include_once("inc/function.php");
	session_check();
?>
<!Doctype html>
<html>
<head>
<title><?php echo $web_name;?> - 幸运轮盘</title>
<?php require_once("public/title.inc.php");?>
</head>
<body>
<?php $_SESSION['curpage'] = "rotate";?>
<?php require_once("top.php");?>
<?php require_once("public/notice.inc.php");?>

<div style="line-height:0px;">
<iframe src="/rotate/index.html" frameborder="0" border="0" marginwidth="0" marginheight="0" width="100%" height="765"></iframe>
</div>

<?php include_once("footer.php");?>
</body>
</html>

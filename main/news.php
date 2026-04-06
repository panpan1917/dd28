<?php
include_once("inc/conn.php");
include_once("inc/function.php");
if(isset($_GET['id'])){
	$id = intval($_GET['id']);
	$result = $db->query("Select id,title,content,time from news WHERE id = '$id'");
	$_html = array();
	if($rs=$db->fetch_array($result)){
		$_html['title'] = ChangeEncodeG2U($rs["title"]);
		$_html['content'] = ChangeEncodeG2U($rs["content"]);
		$_html['time'] = $rs["time"];
	}else{
		echo '<script type="text/javascript">alert("不存在此条公告!");history.back();</script>';
	}
}else{
	echo '<script type="text/javascript">alert("请不要非法登录!");history.back();</script>';
}

?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $web_name;?> - 新闻专区</title>
<?php require_once("public/title.inc.php");?>
<link rel="stylesheet" type="text/css" href="style/news.css" />
<script type="text/javascript" src="js/login.js"></script>
</head>
<body>
<?php $_SESSION['curpage'] = "";?>
<?php require_once("top.php");?>
<?php require_once("public/notice.inc.php");?>
<div class="news">
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">新闻详情</div>
			<div class="panel-body">
				<dl>
					<dt><h4><?php echo $_html['title'];?><span><?php echo $_html['time'];?></span></h4></dt>
					<dd>
						<p><?php echo $_html['content']; ?></p>
					</dd>
				</dl>
			</div>
		</div>
	</div>

</div>

<?php include_once("footer.php");?>
</body>
</html>


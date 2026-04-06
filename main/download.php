<?php
	include_once("inc/conn.php");
	include_once("inc/function.php"); 
?>

<!Doctype html>
<html>
<head>
<title><?php echo $web_keywords;?> - 滴滴下载</title>
<?php require_once("public/title.inc.php");?>
<script type="text/javascript" src="js/login.js"></script>
</head>
<body>
<?php $_SESSION['curpage'] = "download";?>
<?php require_once("top.php");?>
<?php require_once("public/notice.inc.php");?>

<div id="active">
    <div class="active_list width_980">
            <div class="panel-body">
                <div class="tab-content tab_active">
                    <div class="tab-pane active" id="ing">
                            <div class="panel panel-default">
                                <div class="panel-heading">App下载</div>
                                <div class="panel-body">
								  	<div style="text-align:center;font-size:22px;padding-top:40px;">
								  		<a href="itms-services://?action=download-manifest&url=https://<?php echo $_SERVER['HTTP_HOST'];?>/download/didi28.plist"><img src="./img2/download-ios.jpg" height="67" width="160" /></a>
								  	</div>
								  	
								  	<div style="text-align:center;font-size:22px;padding-top:40px;padding-bottom:40px;">
								        <a id="andrioddownload" href="https://<?php echo $_SERVER['HTTP_HOST'];?>/download/didi28.apk"><img src="./img2/download-andriod.jpg" height="67" width="160" /></a>
								  	</div>
                                </div>
                            </div>
                    </div>
            </div>
        </div>
   </div>
</div>

<?php include_once("footer.php");?>
</body>
</html>

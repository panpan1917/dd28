<?php
include_once("inc/conn.php");
include_once("inc/function.php"); 
define("Page_name","friend");
?>
<!DOCTYPE>
<html>
<head>
<title><?php echo $web_keywords;?> - 推荐好友</title>
<?php require_once("public/title.inc.php");?>
<script type="text/javascript" src="js/login.js"></script>
<script type="text/javascript" src="js/clipboard.min.js"></script>
<script type="text/javascript" src="/js/jquery.qrcode.min.js"></script>
<script type= "text/javascript">
    $(document).ready(function() {
    	//var clipboard = new Clipboard('#copybtn');

    	var str = "http://<?php echo $_SERVER['HTTP_HOST'];?>/reg.php?tj=<?php echo $_SESSION['usersid'];?>";
    	$("#code").qrcode({
    		render: "table",
    		width: 250,
    		height:250,
    		text: str
    	});
    	
    });
</script>
    
</head>
<body>
<?php $_SESSION['curpage'] = "friend";?>
<?php require_once("top.php");?>
<?php require_once("public/notice.inc.php");?>



<div id="active">
    <div class="active_list width_980">
            <div class="panel-body">
                <div class="tab-content tab_active">
                    <div class="tab-pane active" id="ing">
                            <div class="panel panel-default">
                                <div class="panel-heading">推荐好友</div>
                                <div class="panel-body">
                                    <div class="media">
                                        <div class="media-left">
                                            <p class="tc f1b"><img src="images/a1.jpg" alt="推荐朋友可获得朋友游戏下注额的0.08%提成"></p>
                                        </div>
                                        <div style="position:relative;">
											<h4>注册链接推广<span>(登录后,复制注册地址发给您的好友,丰厚回报等着你!)</span></h4>
											<p style="color:red;font-size:16px;">注册地址: <span id="regurl">http://<?php echo $_SERVER['HTTP_HOST'];?>/reg.php?tj=<?php echo $_SESSION['usersid'];?></span></p>
											<div class="copy" style="padding:10px 0;">
												<input id="copybtn" class="btn btn-danger" type="button" data-clipboard-action="copy" data-clipboard-target="#regurl" value="复制链接">
											</div>
											<div style="text-align: center" id="code"></div>
                                        </div>
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

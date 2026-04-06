<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    {tpl style}
    <script type="text/javascript" src="js/jquery.qrcode.min.js"></script> 
    <link rel="stylesheet" type="text/css" href="{$skin}style/member_total.css" />
    <title>{$webname}</title>
</head>
<script type='text/javascript'>
$(document).ready(function(){
		var regurl = "https://<?php echo $_SERVER['HTTP_HOST'];?>/mobile.php?c=users&a=reg&tj={$info.usersid}";
		$('#qrcode').qrcode(regurl);
    });
</script>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header nav_header">
            <a href="javascript:void(0);" class="navbar-brand" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left icon"></span></a>
            <a href="javascript:void(0);" class="btn refresh" onclick="window.location.reload();"><span class="glyphicon glyphicon-refresh"></span></a>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #fff;">邀请好友</p>
    </div>
</nav>
<div id="main">
    {tpl info}
    <div class="container-fluid">
        <div class="row member_ex white_bg member_gift member_way">
            <div class="media">
                <div class="media-left media-middle">
                    <span class="glyphicon glyphicon-plus"></span>
                </div>
                <div class="media-body">
                    <h4>邀请好友</h4>
                    <p>把邀请链接，复制后通过QQ或微信等发给好友。</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row white_bg member_mycard">
            <div class="panel panel-default member_way member_yq" style="height:480px;">
                <div class="panel-heading">
                    <h4>邀请链接</h4>
                    <p>把邀请链接，复制后通过QQ或微信等发给好友,也可让好友扫描下面的二维码。</p>
                </div>
                <div class="panel-body">
                    <p class="panel-border"><span class="red">https://<?php echo $_SERVER['HTTP_HOST'];?>/mobile.php?c=users&a=reg&tj={$info.usersid}</span></p>
                    <p>成功推荐一位好友后可获得<span class="red">【好友投注返点】</span></p>
                </div>
                <div class="panel-body" id="qrcode">
            	</div>
            </div>

        </div>
    </div>
</div>
{tpl foot}
</body>
</html>
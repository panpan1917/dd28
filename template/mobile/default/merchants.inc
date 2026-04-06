
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <title>商务代理-{$webname}</title>
    <link rel="stylesheet" type="text/css" href="{$skin}css/member_basic.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}style/member_total.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}style/merchants.css" />
    {tpl style}
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header nav_header">
            <a href="javascript:void(0);" class="navbar-brand" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left icon"></span></a>
            <a href="javascript:void(0);" class="btn refresh" onclick="window.location.reload();"><span class="glyphicon glyphicon-refresh"></span></a>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">商务代理</p>
    </div>
</nav>

<div id="main">

    <div class="container-fluid" style="padding:0px;">
		
		<div id="Content">
		    <div class="merchants">
		        <ul>   
		        
		        	<?php foreach($rows as $idx=>$row){ ?>
	        			<?php if($is_weixin){?>
	               		<a href="http://wpa.qq.com/msgrd?v=3&uin={$row['qq']}&site=qq&menu=yes" target="_blank">
	               		<?php }else{?>
	               		<a href="mqqwpa://im/chat?chat_type=wpa&uin={$row['qq']}&version=1&src_type=web&web_src=oicqzone.com" target="_blank">
	               		<?php }?>
		               		<li>
		                    	<span class="n">{$row['agent_name']}</span>
		                    	<span class="q">QQ:{$row['qq']}</span>
		                    </li>
		                </a>
		            <?php }?>
		            
		        </ul>
		    </div>
		</div> 
    </div>
    
</div>


{tpl foot}
</body>
</html>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <title>每日救济-{$webname}</title>
    <link rel="stylesheet" type="text/css" href="{$skin}css/member_basic.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}style/member_total.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}style/gift.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}style/lv.css" />
    {tpl style}
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header nav_header">
            <a href="javascript:void(0);" class="navbar-brand" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left icon"></span></a>
            <a href="javascript:void(0);" class="btn refresh" onclick="window.location.reload();"><span class="glyphicon glyphicon-refresh"></span></a>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">每日救济</p>
    </div>
</nav>

<div id="main">
    {tpl info}
    <div class="container-fluid">
        <div class="row member_ex white_bg member_gift">
            <div class="media">
                <div class="media-left media-middle">
                    <span class="glyphicon glyphicon-xbt"></span>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">每日救济</h4>
                    <p>救济条件</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row white_bg member_mycard">
            <table class="table table-hover table-striped table-bordered text-center">
			<tbody>
			     <tr height="30">
					<th width="150" align="left">等级</th>
					<th width="120" align="left">经验起步</th>
					<th width="120" align="left">经验截止</th>
					<th align="left" width="120">救济乐豆</th>
				</tr>
				<tr height="25">
					<td><a class="lvspan0" title="等级: 0级">&nbsp;</a></td>
					<td>0</td>
					<td>200</td>
					<td>100</td>
				</tr>
				<tr height="25">
					<td><a class="lvspan1" title="等级: 1级">&nbsp;</a></td>
					<td>201</td>
					<td>1,000</td>
					<td>200</td>
				</tr>
				<tr height="25">
					<td><a class="lvspan2" title="等级: 2级">&nbsp;</a></td>
					<td>1,001</td>
					<td>3,000</td>
					<td>300</td>
				</tr>
				<tr height="25">
					<td><a class="lvspan3" title="等级: 3级">&nbsp;</a></td>
					<td>3,001</td>
					<td>8,000</td>
					<td>400</td>
				</tr>
				<tr height="25">
					<td><a class="lvspan4" title="等级: 4级">&nbsp;</a></td>
					<td>8,001</td>
					<td>10,000</td>
					<td>500</td>
				</tr>
				<tr height="25">
					<td><a class="lvspan5" title="等级: 5级">&nbsp;</a></td>
					<td>10,001</td>
					<td>20,000</td>
					<td>600</td>
				</tr>
				<tr height="25">
					<td><a class="lvspan6" title="等级: 6级">&nbsp;</a></td>
					<td>20,001</td>
					<td>30,000</td>
					<td>700</td>
				</tr>
				<tr height="25">
					<td><a class="lvspan7" title="等级: 7级">&nbsp;</a></td>
					<td>30,001</td>
					<td>40,000</td>
					<td>800</td>
				</tr>
				<tr height="25">
					<td><a class="lvspan8" title="等级: 8级">&nbsp;</a></td>
					<td>40,001</td>
					<td>50,000</td>
					<td>900</td>
				</tr>
				<tr height="25">
					<td><a class="lvspan9" title="等级: 9级">&nbsp;</a></td>
					<td>50,001</td>
					<td>60,000</td>
					<td>1,000</td>
				</tr>
				<tr height="25">
					<td><a class="lvspan10" title="等级: 10级">&nbsp;</a></td>
					<td>60,001</td>
					<td>999,999,999,999</td>
					<td>1,100</td>
				</tr>
			</tbody>
            </table>
            
			<div class="btn">
			<input id='getjiuji' type='button' class='btn btn-danger' style='margin:0 auto;' value='领取救济'>
				<!-- <ul class="Button">
					<li><a href="javascript:GetJiuji();">领取救济</a></li>				
				</ul> -->
			</div>
            
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$("#getjiuji").click(function(){
		$.get("ajax.php",{action:"get_jiuji"},function(ret){
			alert(ret.msg);
		},"json");
	});	
});	
</script>

{tpl foot}


</body>
</html>
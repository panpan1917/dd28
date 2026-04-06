
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <title>兑换记录-{$webname}</title>
    <link rel="stylesheet" type="text/css" href="{$skin}css/member_basic.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}style/member_total.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}style/gift.css" />
    {tpl style}
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header nav_header">
            <a href="javascript:void(0);" class="navbar-brand" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left icon"></span></a>
            <a href="javascript:void(0);" class="btn refresh" onclick="window.location.reload();"><span class="glyphicon glyphicon-refresh"></span></a>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">兑换记录</p>
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
                    <h4 class="media-heading">兑换记录</h4>
                    <p>点卡兑换的明细记录</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
		<div class="search_input">
			<ul>
				<li class="cbxtime">
				<input type="radio" name="search_day" <?php if($day == "7"){echo "checked='true'";}?> value="7" id="search_day1"><label for="search_day1">7天    </label>
				<input type="radio" name="search_day" <?php if($day == "30"){echo "checked='true'";}?> value="30" id="search_day2"><label for="search_day2">30天    </label>
				<input type="radio" name="search_day" <?php if($day == "180"){echo "checked='true'";}?> value="180" id="search_day3"><label for="search_day3">半年    </label>
				<input type="radio" name="search_day" <?php if($day == "365"){echo "checked='true'";}?> value="365" id="search_day4"><label for="search_day4">一年    </label>
				<input type="button" value="查询" id="exchange_log_btnATSearch">
				</li>
			</ul>
		</div>
        <div class="row white_bg member_mycard">
            <table class="table table-hover table-striped table-bordered text-center">
                <?php foreach($rows as $idx=>$row){?>
                <tr>
                    <td style="text-align:left;">
                    <strong> 时间 : </strong><?php echo $row['add_time'];?><br/>
                    <strong> 卡号 : </strong><?php echo $row['card_no'];?><br/>
                    <strong> 卡密 : </strong><?php echo $row['card_pwd'];?><br/>
                    <strong> 类型 : </strong><?php echo $row['card_name'];?><br/>
                    <strong> 状态 : </strong><?php if($row['state']=="0"){echo "生成";}elseif($row['state']=="1"){echo "回收";}elseif($row['state']=="2"){echo "冻结";}?><br/>
                    <strong> 点数 : </strong><?php echo $row['card_points'];?></td>
                    
                </tr>
                <?php }?>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$("#exchange_log_btnATSearch").click(function(){
		var day= $('input[name="search_day"]:checked').val(); 
		window.location="{:url('gift/exchanglist')}&day="+day;
	});	
});	
</script>

{tpl foot}


</body>
</html>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    {tpl style}
<style>
    .news{ background: #fff}
    .zone h1 {
        position: relative;
        padding-left: 16px;
        border-bottom: 1px solid #ccc;
        font-size: 16px;
        font-weight: 400;
        line-height: 40px;
    }.area-list a, .list-nolink {
         display: block;
         color:#000;
         line-height: 30px;
         padding-left: 14px;
         padding: 0 24px 0 12px;
         background-size: 24px 12px; }
.title{
	margin-left:10px;
	margin-right:10px;
	color:#000;
}
.rank{
	text-align:left;
	margin-left:5px;
	width:20%;
	float:left;
}
.nickname{
	text-align:left;
	width:40%;
	float:left;
}
.point{
	text-align:right;
	width:35%;
	float:left;
	margin-right:5px;
}
</style>
    <title>{$webname}</title>
</head>
<body>

{tpl head}

<div class="side" style="margin-bottom:60px;height:960px;">
    <div class="zone news">
        <h1 style="margin-top: 60px;text-align:center;"><a class="title" onclick="javascript:showrankings('today');">今日排行</a><a class="title" onclick="javascript:showrankings('yestday');">昨日排行</a><a class="title" onclick="javascript:showrankings('week');">七日排行</a></h1>
        <ul id="today" class="zone-con area-list" style="padding:0px;">
                <?php for($i=0;$i<count($todayrankings);$i++){?>
                <li>
	                <a class="rank"><?php echo $i+1;?></a>
		            <a class="nickname"><?php echo $todayrankings[$i]['nickname'];?></a>
		            <a class="point"><?php echo $todayrankings[$i]['rank_points'];?></a>
                </li>
                <?php }?>
        </ul>
        <ul id="yestday" class="zone-con area-list" style="padding:0px;display:none;">
                <?php for($i=0;$i<count($yestadayrankings);$i++){?>
                <li>
	                <a class="rank"><?php echo $i+1;?></a>
		            <a class="nickname"><?php echo $yestadayrankings[$i]['nickname'];?></a>
		            <a class="point"><?php echo $yestadayrankings[$i]['rank_points'];?></a>
                </li>
                <?php }?>
         </ul>
        <ul id="week" class="zone-con area-list" style="padding:0px;display:none;">
                <?php for($i=0;$i<count($weekrankings);$i++){?>
                <li>
	                <a class="rank"><?php echo $i+1;?></a>
		            <a class="nickname"><?php echo $weekrankings[$i]['nickname'];?></a>
		            <a class="point"><?php echo $weekrankings[$i]['rank_points'];?></a>
                </li>
                <?php }?>
         </ul>
    </div>
</div>


{tpl foot}

<script>
function showrankings(rankType){
	if(rankType == 'today'){
		$("#today").show();
		$("#yestday").hide();
		$("#week").hide();
	}else if(rankType == 'yestday'){
		$("#today").hide();
		$("#yestday").show();
		$("#week").hide();
	}else if(rankType == 'week'){
		$("#today").hide();
		$("#yestday").hide();
		$("#week").show();
	}
}
</script>

</body>

</html>

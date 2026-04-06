<?php
include_once("inc/conn.php");
include_once("inc/function.php");

$id=intval($_GET['tj']);
if($id)setcookie('tj',$id);
$referer=str_check($_GET['referer']);
if(!empty($referer))setcookie('referer',$referer);
?>

<!Doctype html>
<html lang="zh-cn">
<head>
<title><?php echo $web_keywords;?> - 首页</title>
<?php include_once("public/title.inc.php");?>
<script type="text/javascript" src="js/login.js"></script>
</head>

<script type="text/javascript" src="/js/jquery.qrcode.min.js"></script>
<script type= "text/javascript">
    $(document).ready(function() {

    	var str = "https://<?php echo $_SERVER['HTTP_HOST'];?>/download.php";
    	$("#code").qrcode({
    		render: "table",
    		width: 240,
    		height:240,
    		text: str
    	});

    	var str2 = "https://<?php echo $_SERVER['HTTP_HOST'];?>/mobile.php";
    	$("#code2").qrcode({
    		render: "table",
    		width: 240,
    		height:240,
    		text: str2
    	});
    });
</script>

<body>
<?php $_SESSION['curpage'] = "index";?>
<?php include_once("top.php");?>

<div class="fullSlide">
    <div class="bd">
        <ul>
            <li style="background:url('/images/001.png') center 0 no-repeat;"><a href="javascript:void();"></a></li>
        </ul>
    </div>
</div>


<div id="three_union" style="display:none;position: absolute;z-index:533333;top: 200px;width:300px;height:300px;background : rgba(0, 0, 0, 0) url('./img2/threeunion.png') no-repeat scroll 0 0;">
</div>


<?php 
$sql = "select id,title,time,content from news where pop=1 order by id desc limit 1";
$newsrow=$db->fetch_all($sql,60);
if(!empty($newsrow))
{
?>

<script type="text/javascript">
setInterval(closeNotice,20000);
function closeNotice(){
	$("#notice").attr('style','display:none');
}
</script>
<div id="notice" style="background:url('/img/newsbk.jpg') center 0 no-repeat;width: 680px; height: 360px; border:0px solid #000;position: absolute; z-index:3333333; top:260px;left:50%;margin-left:-340px;">
	<table style="border: 0 solid #e7e3e7;border-collapse: collapse;width:100%;">
		<tr>
			<td height="15" valign="middle">
				<div align="right"></div>
			</td>
		</tr>
		<tr>
			<td style="height:250px;">
				<div style="margin-left:40px;margin-right:40px;font-size:18px;color:white;font-weight:bold;"><?php echo strip_tags($newsrow[0]['content']);?></div>
			</td>
		</tr>
		<tr>
			<td height="15" valign="middle">
				<div align="right" style="margin-left:40px;margin-right:40px;"><input class="btn btn-danger" style="width:80px;" value="关闭" onclick="closeNotice();" type="button"></div>
				
			</td>
		</tr>
	</table>
</div>

<?php 
}
?>



<div id="rank_list">
    <div class="list width_1000" style="padding-top: 20px;">
        <div class="list_left" style="width: 725px;">
            <div class="panel panel-default">
                <div class="panel-heading new"><strong>新闻公告</strong></div>
                <div class="panel-body">
                    <dl>
                        <?php
                        $result=$db->fetch_all('Select id,title,time from news Order by top desc,id desc limit 15',60);
                        foreach ($result as $key => $value) {                     
                            ?>
                            <dd style="line-height: 40px;font-size:16px;border-bottom: 1px dashed #000;"><a title="<?php echo $value["title"]?>" href="news.php?id=<?php echo $value["id"]?>"><?php echo $value["title"]?><span><?php echo $value["time"]?></span></a> </dd>
                            <?php
                        }
                        ?>
                    </dl>
                </div>
            </div>
        </div>
        <div class="list_right" style="width: 265px;">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>APP下载(请用手机浏览器扫一扫)</strong></div>
	            <div class="panel-body">
	                <div class="media">
	                    <div id="code" style="padding:10px 10px 10px 10px;">
	                    </div>
	                </div>
	            </div>
            </div>
            
            <div class="panel panel-default">
                <div class="panel-heading"><strong>手机版(请用手机浏览器扫一扫)</strong></div>
	            <div class="panel-body">
	                <div class="media">
	                    <div id="code2" style="padding:10px 10px 10px 10px;">
	                    </div>
	                </div>
	            </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>


<div class="active_type">
    <div class="active_list width_1000">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>友情链接 </strong> </div>
			<div class="f_imgbg" style="text-align: center;">
				<a href="#"><img src="/img2/com-7.png"></a>
				<a href="#"><img src="/img2/com-6.png"></a>
				<a href="#"><img src="/img2/com-5.png"></a>
				<a href="#"><img src="/img2/com-4.png"></a>
				<a href="#"><img src="/img2/com-3.png"></a>
				<a href="#"><img src="/img2/com-2.png"></a>
				<a href="#"><img src="/img2/com-1.png"></a>
			</div>
        </div>
    </div>
</div>



<?php include_once("footer.php");?>
</body>
<script type="text/javascript">
    $(document).ready(function(){

        $(".prev,.next").hover(function(){
            $(this).stop(true,false).fadeTo("show",0.9);
        },function(){
            $(this).stop(true,false).fadeTo("show",0.4);
        });

        $(".banner-box").slide({
            titCell:".hd ul",
            mainCell:".bd ul",
            effect:"fold",
            interTime:3500,
            delayTime:500,
            autoPlay:true,
            autoPage:true,
            trigger:"click"
        });

    });
</script>


</html>



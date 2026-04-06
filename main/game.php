<?php
	include_once("inc/conn.php");
	include_once("inc/function.php");
	session_check();
	$is_check_mobile=0;
	$act=intval($_GET['act'])?intval($_GET['act']):0;
	$usersid=$_SESSION['usersid'];
	$sql = "select is_check_mobile from users where id = '{$usersid}' limit 1";
	$result = $db->query($sql);
	$users = $db->fetch_row($result);
	if(empty($users)){
		header("Location: login.php");
		exit();
	}
 
	if($users[0]==0){
		echo '<meta charset="utf-8" />';
		echo ChangeEncodeU2G('<script language="javascript">
				alert("为了帐号安全，你需要先绑定手机再进入游戏!");
				window.location = "/member.php";
			 </script>');
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $web_keywords;?> - 游戏乐园</title>
<?php require_once("public/title.inc.php");?>
<script type="text/javascript" src="js/cookie.js"></script>
<script type="text/javascript" src="js/jquery.jplayer.min.js"></script>
<script type="text/javascript">
	var timerid;

	$(document).ready(function(){
		var id = (getCookie("showid") == "")?"0":getCookie("showid");
		var act=<?php echo $act;?>;
		show(act>0?act:id);
		$("#jquery_jplayer_1").jPlayer({
			ready: function () {
				$(this).jPlayer("setMedia", {
					title: "Bubble",
					mp3: "/images/operateRemind.mp3"
				});
			},
			swfPath: "/images/jplayer",
			supplied: "mp3",
			wmode: "window"
		});
	});
	function show(num){
		$("[id^=game_]").removeClass("pick_fast");
		$("[id^=game_]").removeClass("pick_beijing");
		$("[id^=game_]").removeClass("pick_korea");
		$("[id^=game_]").removeClass("pick_canada");
		$("[id^=game_]").removeClass("pick_pk");
		$("[id^=game_]").removeClass("pick_dandan");
		$("[id^=game_]").removeClass("pick_xync");
		$("[id^=game_]").removeClass("pick_airship");


		if(num == 36 || num == 37){//幸运农场
			$("#game_" + num).addClass("pick_xync");
		}
		
		if(num == 0 || num == 1 || num == 2 || num == 15 || num == 22 || num == 23 || num == 24){
			$("#game_" + num).addClass("pick_fast");
		}

		if(num == 5 || num == 4 || num == 12 || num == 33 || num == 38 || num == 41 || num == 42){
			$("#game_" + num).addClass("pick_beijing");
		}

		if(num == 18 || num == 19 || num == 20 || num == 21 || num == 30 || num == 31 || num == 34){
			$("#game_" + num).addClass("pick_korea");
		}

		if(num == 8 || num == 9 || num == 10 || num == 13 || num == 27 || num == 28 || num == 35){
			$("#game_" + num).addClass("pick_canada");
		}

		if(num == 6 || num == 7 || num == 14 || num == 16 || num == 17 || num == 29){
			$("#game_" + num).addClass("pick_pk");
		}

		if(num == 3 || num == 11 || num == 25 || num == 26 || num == 32 || num == 39 || num == 40){
			$("#game_" + num).addClass("pick_dandan");
		}

		if(num == 43 || num == 44 || num == 45 || num == 46 || num == 47){
			$("#game_" + num).addClass("pick_airship");
		}
		
		//$("[id^=game_]").removeClass("pick");
		//$("#game_" + num).removeClass("pick").addClass("pick");
		setCookie("showid",num);
		getContent("sgame.php?act=" + num + "&t=" + Math.random());
		get_dou();
	}
	function getContent(url){
		if(typeof(timerid) != "undefined")
			clearInterval(timerid);
		var timestamp=new Date().getTime();
		$.get(url,{timestamp:timestamp},function(ret){
			$("#content").html(ret);
		});	
	}
	function get_dou() {
		var timestamp=new Date().getTime();
		$.get('b.php?c=user&a=get_ledou&',{timestamp:timestamp},function(ret){
			$("#dou").html(ret);
		});
	}
	function showrecord(name,url)
	{
		$.openPopupLayer({
			name: name,
			width: 840,
			height: 500,
			url: url
		});
	}
	function openrecord(name,w_width,w_height,url)
	{
		$.openPopupLayer({
			name: name,
			width: w_width,
			height: w_height,
			url: url
		});
	}
	function closerecord(name)
	{
		clearInterval(timerid);
		$.closePopupLayer(name);
	}
	issond = 1;
	function sondclick(t)
	{
		var src=$(t)[0].src;
		if(src.indexOf("S_Close.gif")>0){
			issond = 1;
			$(t).attr("src", "/images/S_Open.gif");
			setCookie("issond",1);
		} else {
			issond = 0;
			$(t).attr("src", "/images/S_Close.gif");
			setCookie("issond",0);
		}
	}

</script>
</head>
<?php $_SESSION['curpage'] = "game";?>
<?php require_once("top.php");?>
<?php require_once("public/notice.inc.php");?>
<div id="jquery_jplayer_1"  ></div>

<div id="game_titles">
	<div class="game_titles width_1200">
		<div class="btn-group btn_nav">
		  <a class="btn btn-default btn_fast" onclick="javascript:show(15)" id="game_15">急速10</a>
		  <a class="btn btn-default btn_fast" onclick="javascript:show(2)" id="game_2">急速11</a>
		  <a class="btn btn-default btn_fast" onclick="javascript:show(1)" id="game_1">急速16</a>
		  <a class="btn btn-default btn_fast" onclick="javascript:show(22)" id="game_22">急速22</a>
		  <a class="btn btn-default btn_fast" onclick="javascript:show(0)" id="game_0">急速28</a>
		  <a class="btn btn-default btn_fast" onclick="javascript:show(23)" id="game_23">急速36</a>
		  <a class="btn btn-default btn_fast" onclick="javascript:show(24)" id="game_24">急速冠亚军</a>
		  <a class="btn btn-default btn_pk" onclick="javascript:show(6)" id="game_6">PK10</a>
		  <a class="btn btn-default btn_pk" onclick="javascript:show(14)" id="game_14">PK22</a>
		  <a class="btn btn-default btn_pk" onclick="javascript:show(7)" id="game_7">PK冠军</a>
		</div>
		<div class="btn-group btn_nav">
		  <a class="btn btn-default btn_dandan" onclick="javascript:show(39)" id="game_39">蛋蛋11</a>
		  <a class="btn btn-default btn_dandan" onclick="javascript:show(40)" id="game_40">蛋蛋16</a>
		  <a class="btn btn-default btn_dandan" onclick="javascript:show(3)" id="game_3">蛋蛋28</a>
		  <a class="btn btn-default btn_dandan" onclick="javascript:show(11)" id="game_11">蛋蛋36</a>
		  <a class="btn btn-default btn_beijing" onclick="javascript:show(38)" id="game_38">北京11</a>
		  <a class="btn btn-default btn_beijing" onclick="javascript:show(5)" id="game_5">北京16</a>
		  <a class="btn btn-default btn_beijing" onclick="javascript:show(4)" id="game_4">北京28</a>
		  <a class="btn btn-default btn_pk" onclick="javascript:show(29)" id="game_29">北京赛车</a>
		  <a class="btn btn-default btn_pk" onclick="javascript:show(16)" id="game_16">PK龙虎</a>
		  <a class="btn btn-default btn_pk" onclick="javascript:show(17)" id="game_17">PK冠亚军</a>
		</div>
		<div class="btn-group btn_nav">
		  <a class="btn btn-default btn_dandan" onclick="javascript:show(25)" id="game_25">蛋蛋外围</a>
		  <a class="btn btn-default btn_dandan" onclick="javascript:show(26)" id="game_26">蛋蛋定位</a>
		  <a class="btn btn-default btn_dandan" onclick="javascript:show(32)" id="game_32">蛋蛋28固定</a>
		  <a class="btn btn-default btn_beijing" onclick="javascript:show(12)" id="game_12">北京36</a>
		  <a class="btn btn-default btn_beijing" onclick="javascript:show(41)" id="game_41">北京外围</a>
		  <a class="btn btn-default btn_beijing" onclick="javascript:show(42)" id="game_42">北京定位</a>
		  <a class="btn btn-default btn_beijing" onclick="javascript:show(33)" id="game_33">北京28固定</a>
		  <a class="btn btn-default btn_canada" onclick="javascript:show(10)" id="game_10">加拿大11</a>
		  <a class="btn btn-default btn_canada" onclick="javascript:show(9)" id="game_9">加拿大16</a>
		  <a class="btn btn-default btn_xync" onclick="javascript:show(36)" id="game_36">幸运农场</a>
		</div>
		<div class="btn-group btn_nav">
		  <!-- <a class="btn btn-default btn_korea" onclick="javascript:show(20)" id="game_20">首尔11</a>
		  <a class="btn btn-default btn_korea" onclick="javascript:show(19)" id="game_19">首尔16</a>
		  <a class="btn btn-default btn_korea" onclick="javascript:show(18)" id="game_18">首尔28</a>
		  <a class="btn btn-default btn_korea" onclick="javascript:show(21)" id="game_21">首尔36</a> -->
		  
		  <a class="btn btn-default btn_airship" onclick="javascript:show(43)" id="game_43">飞艇10</a>
		  <a class="btn btn-default btn_airship" onclick="javascript:show(44)" id="game_44">飞艇22</a>
		  <a class="btn btn-default btn_airship" onclick="javascript:show(45)" id="game_45">飞艇冠亚军</a>
		  <a class="btn btn-default btn_airship" onclick="javascript:show(46)" id="game_46">飞艇冠军</a>
		  
		  <a class="btn btn-default btn_canada" onclick="javascript:show(8)"  id="game_8">加拿大28</a>
		  <a class="btn btn-default btn_canada" onclick="javascript:show(13)" id="game_13">加拿大36</a>
		  <a class="btn btn-default btn_canada" onclick="javascript:show(35)" id="game_35">加拿大28固定</a>
		  <a class="btn btn-default btn_canada" onclick="javascript:show(27)" id="game_27">加拿大外围</a>
		  <a class="btn btn-default btn_canada" onclick="javascript:show(28)" id="game_28">加拿大定位</a>
		  <a class="btn btn-default btn_xync" onclick="javascript:show(37)" id="game_37">重庆时时彩</a>
		</div>
		<div class="btn-group btn_nav">
		  <!-- <a class="btn btn-default btn_korea" onclick="javascript:show(30)" id="game_30">首尔外围</a>
		  <a class="btn btn-default btn_korea" onclick="javascript:show(31)" id="game_31">首尔定位</a>
		  <a class="btn btn-default btn_korea" onclick="javascript:show(34)" id="game_34">首尔28固定</a> -->
		  
		  <a class="btn btn-default btn_airship" onclick="javascript:show(47)" id="game_47">飞艇龙虎</a>
		</div>
	</div>
</div>
<div id="divContent">
	<div class="width_1200" id="content">
		
	</div>
</div>
<?php 

$newsresult=$db->fetch_all('Select id,title from news Order by top desc,id desc limit 5',60);
$newsresultJson = json_encode($newsresult,JSON_UNESCAPED_UNICODE);
?>

<script language="javascript">
var xlblist = <?php echo $newsresultJson;?>;
var dqshow_xlb = 0;
function qhxlb_gg() {
	if (dqshow_xlb > xlblist.length-1) {
		dqshow_xlb = 0;
	}
	$("#xlb_gongshowdiv").html(xlblist[dqshow_xlb].title);
	$("#xlb_gongshowdiv").attr("href", "news.php?id=" + xlblist[dqshow_xlb].id);
	$("#xlb_gongshowdiv").attr("style", "color:#000");
	dqshow_xlb = dqshow_xlb + 1;
	setTimeout(qhxlb_gg, 5000);	
}	
qhxlb_gg();
</script> 

<?php include_once("footer.php"); ?>
</body>
</html>

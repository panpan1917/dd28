<?php
	include_once("inc/conn.php");
	include_once("inc/function.php"); 
?>

<!Doctype html>
<html>
<head>
<title><?php echo $web_keywords;?> - 玩家排行榜</title>
<?php require_once("public/title.inc.php");?>
<link rel="stylesheet" type="text/css" href="css/1/web.css" />
<script type="text/javascript" src="js/login.js"></script>
</head>
<body>
<?php $_SESSION['curpage'] = "rankings";?>
<?php require_once("top.php");?>
<?php require_once("public/notice.inc.php");?>

<div id="Content">
	<div class="list">
		<div class="list_top"><img src="./img/rank/i_tit_pic1.png"></div>
		<div id="zrbh">
			<?php
				$sql = "select u.nickname,r.points from users u,rank_log r where u.id=r.uid and r.time=CURDATE() order by r.points desc limit 30";
				$result=$db->fetch_all($sql);
				foreach ($result as $key => $value) {   
					$key2 = $key + 1;
					if($key < 3) $rankclass = "rankings" . $key2;    
					else $rankclass = "rankings0";
					
					$nicknamelen = mb_strlen($value['nickname'] , 'UTF-8');
					if($nicknamelen > 6)
						$nickname = mb_substr($value['nickname'] , 0 , $nicknamelen-2 , 'UTF-8') . "**";
					else 
						$nickname = mb_substr($value['nickname'] , 0 , 3 , 'UTF-8') . "**";
			?>
			<div class="list_Content">
                <li class="pm"><div class="<?php echo $rankclass;?>"><?php echo $key2;?></div></li>
                <li class="nc"><?php echo $nickname;?></li>
                <li class="yl"><?php echo number_format($value['points']);?></li>
            </div>
            <?php }?>
		</div>
	</div>
	
	<div class="rankingsxian"></div>

	<div class="list">
		<div class="list_top"><img src="/img/rank/i_tit_pic2.png"></div>
		<div id="zrbh">
			<?php
				$sql = "select u.nickname,r.rank_points,prize_points from users u,rank_list r where u.id=r.uid and r.rank_type=1 order by r.rank_num asc limit 30";
				$result=$db->fetch_all($sql);
				foreach ($result as $key => $value) {   
					$key2 = $key + 1;
					if($key < 3) $rankclass = "rankings" . $key2;    
					else $rankclass = "rankings0";
					
					$nicknamelen = mb_strlen($value['nickname'] , 'UTF-8');
					if($nicknamelen > 6)
						$nickname = mb_substr($value['nickname'] , 0 , $nicknamelen-2 , 'UTF-8') . "**";
					else 
						$nickname = mb_substr($value['nickname'] , 0 , 3 , 'UTF-8') . "**";
			?>
			<div class="list_Content">
                <li class="pm"><div class="<?php echo $rankclass;?>"><?php echo $key2;?></div></li>
                <li class="nc"><?php echo $nickname;?><font color="red">(奖 : <?php echo number_format($value['prize_points']);?>)</font></li>
                <li class="yl"><?php echo number_format($value['rank_points']);?></li>
            </div>
            <?php }?>
		</div>
	</div>
	
	<div class="rankingsxian"></div>

	<div class="list">
		<div class="list_top"><img src="/img/rank/i_tit_pic3.png"></div>
		<div id="zrbh">
			<?php
				$sql = "select u.nickname,r.rank_points from users u,rank_list r where u.id=r.uid and r.rank_type=2 order by r.rank_num asc limit 30";
				$result=$db->fetch_all($sql);
				foreach ($result as $key => $value) {   
					$key2 = $key + 1;
					if($key < 3) $rankclass = "rankings" . $key2;    
					else $rankclass = "rankings0";
					
					$nicknamelen = mb_strlen($value['nickname'] , 'UTF-8');
					if($nicknamelen > 6)
						$nickname = mb_substr($value['nickname'] , 0 , $nicknamelen-2 , 'UTF-8') . "**";
					else 
						$nickname = mb_substr($value['nickname'] , 0 , 3 , 'UTF-8') . "**";
			?>
			<div class="list_Content">
                <li class="pm"><div class="<?php echo $rankclass;?>"><?php echo $key2;?></div></li>
                <li class="nc"><?php echo $nickname;?></li>
                <li class="yl"><?php echo number_format($value['rank_points']);?></li>
            </div>
            <?php }?>
		</div>
	</div>

</div>

<?php include_once("footer.php");?>
</body>
</html>

<?php
include_once("inc/conn.php");
include_once("inc/function.php"); 
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $web_keywords ;?> - 活动专区</title>
<?php require_once("public/title.inc.php");?>
<script type="text/javascript" src="js/login.js"></script>
</head>
<script type="text/javascript">
function show_a(divDisplay){
    if(document.getElementById(divDisplay).style.display != "block"){
        document.getElementById(divDisplay).style.display = "block";
    }else{
        document.getElementById(divDisplay).style.display = "none";
        }
	
}
function showjj(v){
    $("#hdjjdiv"+v).toggle();
}
</script>
<body>
<?php $_SESSION['curpage'] = "active";?>
<?php require_once("top.php");?>
<?php require_once("public/notice.inc.php");?>
<div id="active">
    <div class="active_list width_980">
            <div class="panel-body">
                <div class="tab-content tab_active">
                    <div class="tab-pane active" id="ing">
                        <?php
                        $_result = $db->query("SELECT tg_title,tg_img,tg_content,tg_start_time,tg_last_time FROM game_active WHERE tg_active = 1 ORDER BY tg_top DESC");
                        $_html = array();
                        $i = 1;
                        while($rs = $db->fetch_array($_result)) {
                            $_html['title'] = $rs['tg_title'];
                            $_html['img'] = $rs['tg_img'];
                            $_html['content'] = $rs['tg_content'];
                            $_html['start_time'] = $rs['tg_start_time'];
                            $_html['last_time'] = $rs['tg_last_time'];
                            ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">最新活动 >><?php echo $_html['title'];?> <span class="pull-right hidden-xs" style="color:#000;">活动时间：<?php echo $_html['start_time'];?>至<?php echo $_html['last_time']; ?> </span></div>
                                <div class="panel-body">
                                    <div class="media">
                                        <div class="media-left">
                                            <a href="#"><img class="media-object img-thumbnail" src="<?php echo $_html['img']; ?>" alt="<?php echo $_html['title'];?>"></a>
                                        </div>
                                        <div class="media-body" style="position:relative;">
                                            <h4 class="media-heading"><?php echo $_html['title'];?></h4>
                                            <p>参加对象： 所有认证会员 </p>
                                            <a class="btn btn-danger" onclick="showjj(<?php echo $i;?>)">点击查看活动详情</a>
                                            <div id="hdjjdiv<?php echo $i;?>" style="display:none;">
                                                <p><?php echo $_html['content']; ?></p>
                                            </div>
                                            <span class="jxactive hidden-sm hidden-xs"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $i++;
                        }
                        ?>
                        <!-- <nav style="padding:0; margin:0;" class="text-center">
                            <ul class="pagination" style="padding:0; margin:0;">
                                <li><a href="#" class="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                                <li class="active"><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                                </li>
                            </ul>
                        </nav> -->
                    </div>
            </div>
        </div>
	</div>
</div>
<?php include_once("footer.php"); ?>
</body>
</html>

<nav class="navbar-default navbar-fixed-bottom nav_bg">
    <div class="container-fluid nav-li">
        <ul class="nav navbar-nav">
            <li><a href="{:url('index/index')}" style="padding-bottom:5px;padding-top:5px;"><span class="glyphicon glyphicon-home"></span> <b>首页</b></a></li>
            <li><a href="{:url('game/index')}" style="padding-bottom:5px;padding-top:5px;"><span class="glyphicon glyphicon-th"></span> <b>游戏乐园</b></a></li>
            <li><a href="{:url('index/rankings')}" style="padding-bottom:5px;padding-top:5px;"><span class="glyphicon glyphicon-stats"></span> <b>排行榜</b></a></li>
            <li><a href="{:url('users/index')}" style="padding-bottom:5px;padding-top:5px;"><span class="glyphicon glyphicon-user"></span> <b>会员</b></a></li>
        </ul>
    </div>
</nav>
<div style="display: none">
<script>
var lainframe;

$(document).ready(function(){
	function checkAuth()
	{
	   $.post('./refreshstatus.php',{},function(ret){
	   		if(ret.status != 0)
	   		{
	   			alert(ret.msg);
	   			if(ret.status == 1){
	   				window.location='./mobile.php?c=users&a=login';
	   			}

   				if(ret.status == 2){
   					$.post('confirmmsg.php',{},function(data){
						
   	   	   			},'json');
   	   	   		}
	   		}
	   },'json');
	}

	var sessuserid = "<?php echo $_SESSION['usersid']?>";
	if(sessuserid > 0)
		setInterval(checkAuth , 3000);
}); 


</script>

<!-- 
<script src="https://s19.cnzz.com/z_stat.php?id=1264645851&web_id=1264645851" language="JavaScript"></script>
 -->
           
</div>
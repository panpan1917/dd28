





<div id="footer">
	<div class="footer_center width_1000">
		<dl>
			<dt><a href="javascript:;">关于我们</a>　|　</dt>
			<dt><a href="/agreement.php">服务条款</a>　|　</dt>
			<dt><a href="javascript:;">广告服务</a>　|　</dt>
			<dt><a href="javascript:;">合作商家</a>　|　</dt>
			<dt><a href="javascript:;">乐友生活</a>　|　</dt>
			<!-- <dt><script src="https://s19.cnzz.com/z_stat.php?id=1264645851&web_id=1264645851" language="JavaScript"></script></dt> -->
		</dl>
		<p>滴滴28公司版权所有 &copy;2015-2018</p>
	</div>
</div>

<script>
$(document).ready(function(){
	function checkAuth()
	{
	   $.post('refreshstatus.php',{},function(ret){
	   		if(ret.status != 0)
	   		{
	   			alert(ret.msg);
	   			if(ret.status == 1){
	   				window.location='login.php';
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
		setInterval(checkAuth , 20000);
}); 

</script>



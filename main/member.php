<?php
include_once("inc/conn.php");
include_once("inc/function.php");
session_check();
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $web_keywords ;?> - 用户中心</title>
<?php require_once("public/title.inc.php");?>
<script type="text/javascript">
var action = "<?php echo $_REQUEST['action'];?>";
var controller = "<?php echo $_REQUEST['controller'];?>";
$(document).ready(function(){
	if(controller != "" && action != ""){
		getContents(controller,action);
	}
});
</script>
<script type="text/javascript" src="js/member.js"></script>
<script type="text/javascript">
function getContents(c, a)
{
	var timestamp=new Date().getTime();
	cssChange(a);
	$.get('b.php',{c:c,a:a,timestamp:timestamp},function(ret){
		$("#divContent").empty();
		$("#divContent").html(ret);
	});
}

function getPresslogContent(url, o , d)
{
	cssChange(o);
	$.get(url,{act:o,d:d},function(ret){
		$("#divContent").empty();
		$("#divContent").html(ret);
	});
}
</script>
</head>
<body>
<?php $_SESSION['curpage'] = "member";?>
<?php require_once("top.php");?>
<?php require_once("public/notice.inc.php");?>
<div id="member">
    <div class="member_center width_980">
        <div class="uese">
            <div class="tops">
                <div class="media">
                    <div class="media-left" style="padding-right:0px;">
                        <img src="<?php if($_SESSION['head']){echo $_SESSION['head'];}else{echo 'img/head/1_0.jpg';}?>" alt="" class="media-object" style="width:162px;height:162px;">
                    </div>
                </div>
                
                <!-- <div class="media">
                    <div class="media-body" style="padding:0px 5px;">
                        <p>您好，<span><?=$_SESSION['nickname']?></span></p>
                        <p>乐豆：<span><?=Trans($_SESSION['points'])?></span></p>
                        <p>银行：<span><?=Trans($_SESSION['bankpoints'])?></span></p>
                    </div>
                </div> -->
                

                
                <?php if(!AGENT_MODEL){?>
                <div class="btns">
                    <p><a class="btn btn-danger" href="javascript:getContents('user','onlinepay')">　充 值　</a> </p>
                    <p><a class="btn btn-danger" href="javascript:getContent('smbinfo.php','Withdrawals')">　提 现　</a></p>
                </div>
                <?php }?>
            </div>

            <div class="title">


            	
            
            
                <ul>

                    <li><a id='menu_mydetail' class="btn btn-default btn-block" href="javascript:getContent('smbinfo.php','mydetail')">个人资料</a></li>
                    <li><a id='menu_mybank' class="btn btn-default btn-block" href="javascript:getContent('smbinfo.php','mybank')">我的银行</a></li>
                    <?php if(isset($_SESSION['isagent']) && !$_SESSION['isagent'] && !AGENT_MODEL){?><li><a id='menu_binding' class="btn btn-default btn-block" href="javascript:getContent('smbinfo.php','binding')">绑定帐号</a></li><?php }?>
                    
           			<?php if(isset($_SESSION['isagent']) && $_SESSION['isagent'] && AGENT_MODEL){?>
                    <li><a id='menu_agent_recharge' class="btn btn-default btn-block" href="javascript:getContent('smbinfo.php','agent_recharge')">代理充值</a></li>
					<li><a id='menu_agent_experience_card' class="btn btn-default btn-block" href="javascript:getContent('smbinfo.php','agent_experience_card')">回收卡密</a></li>
					<li><a id='menu_agent_change' class="btn btn-default btn-block" href="javascript:getContent('smbinfo.php','agent_change')">额度转换</a></li>
					<li><a id='menu_agent_statistics' class="btn btn-default btn-block" href="javascript:getContent('smbinfo.php','agent_statistics')">统计信息</a></li>
					<li><a id='menu_agent_agent_information' class="btn btn-default btn-block" href="javascript:getContent('smbinfo.php','agent_information')">代理资料</a></li>
					<li><a id='menu_agent_withdraw' class="btn btn-default btn-block" href="javascript:getContent('smbinfo.php','agent_withdraw')">申请提现</a></li>
                    <li><a id='menu_agent_log' class="btn btn-default btn-block" href="javascript:getContent('smbinfo.php','agent_log')">操作日志</a></li>
                	<?php }?>
                    
                    <?php if(isset($_SESSION['isagent']) && !$_SESSION['isagent']){?><li><a id='menu_get_press_log' class="btn btn-default btn-block" href="javascript:getPresslogContent('smbinfo.php','get_press_log',1)">投注流水</a></li><?php }?>
                    <?php if(isset($_SESSION['isagent']) && !$_SESSION['isagent']){?><li><a id='menu_index' class="btn btn-default btn-block" href="javascript:getContents('activity','lossRebate')">亏损返利</a></li><?php }?>
					
					<?php if(isset($_SESSION['isagent']) && !$_SESSION['isagent'] && AGENT_MODEL){?><li><a id='menu_exchangelist' class="btn btn-default btn-block" href="javascript:getContent('smbinfo.php','exchangelist')">兑奖记录</a></li><?php }?>
                    
                    <li><a id='menu_extension' class="btn btn-default btn-block" href="javascript:getContents('activity','recomRebate')" >推荐奖励</a></li>
                    
                    <?php if(isset($_SESSION['isagent']) && !$_SESSION['isagent'] && !AGENT_MODEL){?><li><a id='menu_rechargeRebate' class="btn btn-default btn-block" href="javascript:getContents('activity','rechargeRebate')" >首充返利</a></li><?php }?>
                    
                    <?php if(isset($_SESSION['isagent']) && !$_SESSION['isagent']){?><li><a id='menu_get_pack' class="btn btn-default btn-block" href="javascript:getContents('activity','redPack')" >领取红包</a></li><?php }?>
                    <?php if(isset($_SESSION['isagent']) && !$_SESSION['isagent']){?><li><a id='menu_get_rank' class="btn btn-default btn-block" href="javascript:getContents('activity','rankRebate')" >排行奖励</a></li><?php }?>
                </ul>

            </div>
        </div>
        <div id="divContent" class="su"></div>
    </div>
    <div class="clearfix"></div>
</div>

<?php include_once("footer.php"); ?>
</body>


</html>

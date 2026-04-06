<?php
	include_once("inc/conn.php");
    include_once("inc/function.php");
    
    if(!isset($_SESSION['usersid'])) {
		echo "您还没登录或者链接超时，请先去<a href='/login.php'>登录</a>!";
		exit;
	}
	
	
	$act = intval($_GET['act']);
	
	//返回页面信息
	if(!in_array($act,[25,26,27,28,29,30,31,32,33,34,35,36,37,41,42]))
		GetAutoPressContent($act);
	
    /* 返回页面信息
    * 
    */
    function GetAutoPressContent($act)
    {
		$sid = intval($_GET['sid']);
		$arrCurNoInfo = array('preno'=>'','prekgtime'=>'','game_kj_delay'=>'','game_tz_close'=>'');

		
		$RetContent = "<div class='Edit'>\r\n";
		
		
		//取得开奖头
		$RetContent .= GetHeadContent($act,$sid,$arrCurNoInfo);
		//取得子菜单
		$RetContent .= GetSubMenu($act,$sid);
		
		//取得自动投注设置
		$RetContent .= GetAutoSetContent($act,$arrCurNoInfo);
		
		
		//取得表格内容
		$RetContent .= GetTableContent($act);
		//取游戏帮助
		$RetContent .= GetHelpInfo();
		
		$RetContent .= "</div>\r\n";
		//js 定义
		$RetContent .= GetJSContent($act,$arrCurNoInfo['preno']);
		$RetContent .= GetRewardJS($act,$arrCurNoInfo,"head");
		
		echo $RetContent;
		exit;
    }
    
    /* 取号码表格
    *
    */
    function GetAutoSetContent($act,$arrCurNo)
    {
    	global $db;
    	$tableautotz = GetGameTableName($act,"auto_tz");
    	$tableauto = GetGameTableName($act,"auto");
    	
    	$modeloption = "";
    	$curAutoID = "";
    	$curStartNo = 0;
    	$curEndNo = 0;
    	$curMinG = 0;
    	$curMaxG = 0;
    	//取当前自动下注配置
    	$sql = "SELECT autoid,startNO,endNO,minG,maxG,start_auto_id FROM {$tableauto} WHERE uid = '{$_SESSION['usersid']}'";
    	$result = $db->query($sql);
    	if($rs = $db->fetch_array($result))
    	{
			$curAutoID = $rs['start_auto_id'];
			$curStartNo = $rs['startNO'];
			$curEndNo = $rs['endNO'];
			$curMinG = $rs['minG'];
			$curMaxG = $rs['maxG'];
		}
    	$sql = "SELECT id,tzname FROM {$tableautotz} WHERE uid = '{$_SESSION['usersid']}'";
    	$result = $db->query($sql);
    	while($rs = $db->fetch_array($result))
    	{
			if($rs["id"] == $curAutoID)
				$modeloption .= "\t\t\t<option value='{$rs['id']}' selected='selected'>". ChangeEncodeG2U($rs['tzname']) ."</option>\r\n";
			else
				$modeloption .= "\t\t\t<option value='{$rs['id']}'>". ChangeEncodeG2U($rs['tzname']) ."</option>\r\n";
    	}
    	$divAutoSet .= "<p class='editor'>自动投注设置</p>\r\n";
    	$divAutoSet .= "<ul class='new'>\r\n";
    	$divAutoSet .= "\t<li>开始模式: \r\n";
    	$divAutoSet .= "\t\t<select id='sltcurModel' name='select'>{$modeloption}</select>\r\n";
    	$divAutoSet .= "\t</li>\r\n";
    	$divAutoSet .= "\t<li>开始期号: <input id='txtbeginno' name='txt' maxlength='12' type='text' value='". (($curStartNo==0) ? $arrCurNo['preno'] + 3 : $curStartNo) ."' onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\" /></li>\r\n";
    	$divAutoSet .= "\t<li>期数: <input id='txttzcount' name='txt' maxlength='8' type='text' value='". (($curStartNo==0) ? 3000 : ($curEndNo-$curStartNo)) ."' onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\" /></li>\r\n";
    	$divAutoSet .= "\t<li>乐豆上限: <input id='txtmaxG' name='txt' maxlength='9' type='text' value='". (($curMaxG==0) ? 999999999 : $curMaxG) ."' onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\" /></li>\r\n";
    	$divAutoSet .= "\t<li>下限: <input id='txtminG' type='text' maxlength='9' name='txt' value='". (($curMinG==0) ? 100 : $curMinG) ."' onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" onkeyup=\"this.value=this.value.replace(/\D/g,'')\" /></li>\r\n";
    	
    	$divAutoSet .= "</ul>\r\n";
		return $divAutoSet;
    }
    
    /* 取表格内容
    *
    */
    function GetTableContent($act)
    {
		global $db;
    	$tableautotz = GetGameTableName($act,"auto_tz"); 
    	$tableauto = GetGameTableName($act,"auto");
    	
    	$divTable = "<div class='table'>\r\n";
    	$divTable .= "\t<table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;color:#000000;'>\r\n";
		$divTable .= "\t\t<tbody>\r\n";
		$divTable .= "\t\t\t<tr>\r\n";
		$divTable .= "\t\t\t\t<th width='150'>投注模式</th>\r\n";
		$divTable .= "\t\t\t\t<th width='150'>投注乐豆</th>\r\n";
		$divTable .= "\t\t\t\t<th width='350'>赢后使用投注模式</th>\r\n";
		$divTable .= "\t\t\t\t<th width='350'>输后使用投注模式</th>\r\n";
		$divTable .= "\t\t\t</tr>\r\n";
		
		$sql = "select id,tzname FROM {$tableautotz} WHERE uid = '{$_SESSION['usersid']}'";
		$result_option = $db->query($sql);
		$arrID = array();
		$arrTzName = array();
		while($rs_option = $db->fetch_array($result_option))
		{
			$arrID[] = $rs_option['id'];
			$arrTzName[] = ChangeEncodeG2U($rs_option['tzname']);
		}	
	    if(count($arrID) > 0)
	    {
			$sql = "SELECT id,tzname,tzpoints,tzid,winid,lossid FROM {$tableautotz} WHERE uid = '{$_SESSION['usersid']}'";
			$result = $db->query($sql);
			while($rs = $db->fetch_array($result))
			{
				$divTable .= "\t\t\t<tr>\r\n";
				$divTable .= "\t\t\t\t<td>". ChangeEncodeG2U($rs['tzname']) ."</td>\r\n";
				$divTable .= "\t\t\t\t<td>". Trans($rs['tzpoints']) ."</td>\r\n";
				$divTable .= "\t\t\t\t<td>". GetModelSelect('win',$rs['id'],$rs['winid'],$arrID,$arrTzName) ."</td>\r\n";
				$divTable .= "\t\t\t\t<td>". GetModelSelect('loss',$rs['id'],$rs['lossid'],$arrID,$arrTzName) ."</td>\r\n";
				$divTable .= "\t\t\t</tr>\r\n";
			}
		}
		
		$divTable .= "\t\t</tbody>\r\n";
		$divTable .= "\t</table>\r\n";
		//取按钮
		//取当前自动下注配置
    	$sql = "SELECT count(id) cnt FROM {$tableauto} WHERE uid = '{$_SESSION['usersid']}'";
    	$result = $db->query($sql);
    	$rs = $db->fetch_array($result);
    	if($rs['cnt'] > 0)
    	{
    		$isHadAuto = true;
    		$toAction = "cancel";
		}
    	else
    	{
    		$isHadAuto = false;
    		$toAction = "submit";
		}
    		
		$divTable .= "\t<p class='tial'>\r\n";
		$divTable .= "\t\t<span class='btn btn-danger' id='sSubmit' ca='{$toAction}'>". ($isHadAuto?"取消自动投注":"确定提交") ."</span>\r\n";
		$divTable .= "\t\t\t<a class=\"btn btn-warning\" href=\"javascript:getContent('sgame.php?act={$act}')\">返回游戏首页</a>\r\n";
		$divTable .= "\t</p>";
		
    	$divTable .= "</div>\r\n";
    	
    	return $divTable;
    }
    /*取得select控件内容
    *
    */
    function GetModelSelect($t,$rid,$wlid,$arrid,$arrname)
    {
		$select = "<select id='slt_{$rid}' onchange='changemodel(this)' name='select' ct='{$t}' cid='{$rid}'>";
		for($i = 0; $i < count($arrid); $i++)
		{   
			if($wlid == $arrid[$i])
				$select .= "<option value='{$arrid[$i]}' selected='selected'>". $arrname[$i] ."</option>";
			else
				$select .= "<option value='{$arrid[$i]}'>". $arrname[$i] ."</option>";
		}
		$select .= "</select>";
		return $select;
    }
    
    /* 取得游戏帮助内容
    *
    */
    function GetHelpInfo()
    {
		$divHelp .= "<div class='tims'>\r\n";
		$divHelp .= "\t<p>设置方法：</p>\r\n";
		$divHelp .= "\t<p>1．“开始模式”选择第一次投注的模式.</p>\r\n";
		$divHelp .= "\t<p>2．设置“开始期号”与“期数”.</p>\r\n";
		$divHelp .= "\t<p>3．设置“乐豆上限”与“下限”限制,帐号内乐豆数达到限制数量后,自助投注自动停止.</p>\r\n";
		$divHelp .= "\t<p>4．确认并开始自动投注后，系统将会在您指定的期数内帮您自动投注，不论你离线或在线都会持续运行,直到期数终止或您关闭为止.</p>\r\n";
		$divHelp .= "\t<p>5．银行内的乐豆不能用于投注.</p>\r\n";
		$divHelp .= "</div>";
		
		return $divHelp; 
    }
    
    /* 取得JS
    * 
    */
    function GetJSContent($act,$no)
    {
    	global $db;
		$js = "<script type=\"text/javascript\">";
		$js .= "  
			$(document).ready(function(){
				if($('#sSubmit').attr('ca') == 'cancel')
					changestat(0);
					
				$('#sSubmit').click(function(){
					var toaction = $('#sSubmit').attr('ca');
					if(toaction == 'submit')
					{
					    var bno = $('#txtbeginno').val();
					    var cnt = $('#txttzcount').val();
					    var maxg = $('#txtmaxG').val();
					    var ming = $('#txtminG').val();
					    var cid = $('#sltcurModel').val();
					    if(cid != '')
					    {
					       	$.post('sgameservice.php',{act:'saveautomodel',gtype:{$act},curno:{$no},bno:bno,cnt:cnt,maxg:maxg,ming:ming,cid:cid},function(ret){
				   				if(ret.cmd == 'ok')
				   					changestat(0);
				   					
				   				alert(ret.msg);
				   			},'json');
					    }
					    else
					    {
					    	alert('您还没有设置投注模式，请先设置!');
					    }
					}
					else
					{
					    $.post('sgameservice.php',{act:'removeautomodel',gtype:{$act}},function(ret){
				   				if(ret.cmd == 'ok')
				   					changestat(1);
				   			},'json');
					}
				});
			});
			function changemodel(o)
			{
			    var v = $(o).val();
				var cid = $(o).attr('cid');
				var ct = $(o).attr('ct');
				if(v > 0)
				{
				   	$.post('sgameservice.php',{act:'changautomodel',gtype:{$act},cid:cid,ct:ct,v:v},function(ret){
				   		alert(ret.msg);
				   	},'json');
				}
			}
			function changestat(t) 
			{
				if(t == 0)
				{
				     $(\"select[name='select']\").each(function(){
				     	$(this).attr('disabled','disabled');
				     });
				     $(\"input[name='txt']\").each(function(){
				        $(this).attr('disabled','disabled');
				     });
				     $('#sSubmit').attr('ca','cancel');
				     $('#sSubmit').html('取消自动投注');
				}
				else if(t == 1)
				{
				   	$(\"select[name='select']\").each(function(){
				     	$(this).removeAttr('disabled');
				     });
				     $(\"input[name='txt']\").each(function(){
				        $(this).removeAttr('disabled');
				     });
				     $('#sSubmit').attr('ca','submit');
				     $('#sSubmit').html('确定提交');
				}
			}
		";
		
		$js .= "</script>\r\n";
		return $js;
    }

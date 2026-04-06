<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>用户管理--中央银行</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta name="GENERATOR" content="MSHTML 6.00.3790.4275">
	<link rel="stylesheet" type="text/css" href="images/css_body.css">
	<link rel="stylesheet" type="text/css" href="images/window.css">
	<link rel="Stylesheet" type="text/css" href="images/jquery_ui.css" />
	<script type="text/javascript" src="images/jquery.js"></script> 
	<script type="text/javascript" src="images/jquery_ui.js"></script>
</head>
<body>
	<div class="bodytitle">
		<div class="bodytitleleft"></div>
		<div class="bodytitletxt">中央银行</div>
	</div>
	<!-- 菜单 -->
	<div>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">				
			<tr bgcolor="#FFFFFF">
				<td>
					<input type="button" value="中央银行" id="btnScheduleInfo" class="btn-1" />
					<input type="button" value="调度银行" id="btnChangeSchedule" class="btn-1" />
					<input type="button" value="会员充值" id="btnAdminTrans" class="btn-1" />
					<input type="button" value="可疑帐号" id="btnDoubtUser" class="btn-1" />
					<input type="button" value="负分用户" id="btnNegativeUser" class="btn-1" />
					<input type="button" value="按天统计" id="btnStatsDayLog" class="btn-1" />
				</td>
			</tr>				
		</table>
	</div>
    <!-- 中央银行 -->
    <div class="categorylist" id="div_ScheduleInfo">
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
            <tr bgcolor="#FFFFFF">
                <td colspan="7">中央银行实时统计</td>
                <td style="text-align: center;">
                    <input type="button" name="btnRefresh" value="刷新" id="btnRefresh" class="btn-1"/>                    </td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td width="100">系统(供调度):</td>
                <td>
                    <label id="lblSystemScore"></label>                    </td>
                <td width="100">救济领取:</td>
                <td>
                    <label id="lblReliefScore"></label>                    </td>
              <td width="100">错误填平:</td>
              <td>
                    <label id="lblErrorScore"></label>                    </td>
              <td width="100" style="color:red">被封总分:</td>
              <td> <label id="lblBlockScore"></label>                   </td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td>机器人平衡:</td>
                <td>
                    <label id="lblRobotScore"></label>                    </td>
                <td>下线奖励:</td>
                <td>
                    <label id="lblUnderManScore"></label>                    </td>
                <td>其他用途:</td>
                <td>
                    <label id="lblOtherScore"></label>                    </td>
                <td style="color:red">流通总分:</td>
                <td> <label id="lblCirScore"></label>                   </td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td>管理员充值:</td>
                <td>
                    <label id="lblAdminScore"></label>                    </td>
                <td>兑奖奖励:</td>
                <td>
                    <label id="lblRewardScore"></label>                    </td>
                <td style="color:red">转账扣税累积:</td>
                <td>
                    <label id="lblTransTaxScore"></label>                    </td>
                <td style="color:red">机器人总分:</td>
                <td style="color:red">
                    <label id="lblRobotTotalScore"></label>                    </td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td>在线充值:</td>
                <td>
                    <label id="lblOnlinePayScore"></label>                    </td>
                <td>活动奖励:</td>
                <td>
                    <label id="lblActivityScore"></label>                    </td>
                <td style="color:red">游戏税累积:</td>
                <td>
                    <label id="lblGameTaxScore"></label>                    </td>
                <td style="color:red">用户总分:</td>
                <td style="color:red">
                    <label id="lblUserScore"></label>                    </td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td>卡充值:</td>
                <td>
                    <label id="lblCardPayScore"></label>                    </td>
                <td>道具奖励:</td>
                <td>
                    <label id="lblPropScore"></label>                    </td>
                <td style="color:red">当前总经验:</td>
                <td>
                    <label id="lblTotalExp"></label>                    </td>
                <td style="color:red">中央银行:</td>
                <td style="color:red">
                    <label id="lblCenterBank"></label>                    </td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td>系统输赢:</td>
                <td>
                    <label id="lblUserWinLoseScore"></label>                    </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
            <tr bgcolor="#FFFFFF">
                <td>中央银行每半小时快照</td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td>
                    <input id="cbxSICheckTime" type="checkbox" />时间
                   <input id="txtSITimeBegin" type="text" style="width:120px" value="<?php echo date('Y-m-d',strtotime('-1 week')); ?>" />&nbsp;
                   <input id="txtSITimeEnd" type="text" style="width:120px" value="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" />
                   &nbsp;&nbsp;
					<input type="button" name="btnSISearch" value="查询" id="btnSISearch" class="btn-1"/>
                    &nbsp;&nbsp;
                    页大小
                   <input id="txtSIPageSize" type="text" value="20" style="width:30px" />
                </td>
            </tr>
        </table>
		<table id='tblSIResult' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			<tr bgcolor="#f5fafe">
                <td >时间</td>
				<!--<td >经验</td>-->
				<td >管理员</td>
				<td >在线充值</td>
				<td >卡充值</td>
				<td >救济</td>
				<td  >下线</td>
                <td  >兑奖</td>
                <td  >活动</td>
                <td  >道具</td>
                <td  >填平</td>
                <td  >其他</td>
                <td  >游戏抽税</td>
                <td  >封锁分</td>
                <td  >用户总</td>
                <td  >机器总</td>
				<td  >流通总</td>
				<td  >系统输赢</td>
				<td  >中央银行</td>
			</tr>			    
		</table>
		<div class="fenyebar" id="SIPageInfo"></div>
	</div>
    <!-- 修改中央银行 -->
    <div class="categorylist" id="div_ChangeSchedule" style="display: none;">
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
            <tr bgcolor="#FFFFFF">
                <td colspan="9">调配各子帐号信息(当前中央银行:
                  <label id="lblCCenterBank"></label>)</td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td width="100">系统(供调度):</td>
                <td>
                    <label id="lblCSystemScore"></label>                    </td>
                <td>
                    加减:<input id="txtSystemScore" type="text" style="width:80px" />
                    <span style="text-align: center;">
                    <input type="button" onClick="javascript:ChangeCenterValue('SystemScore')"   value="马上调配" id="btnToChange" class="btn-1"/>
                    </span> </td>
                <td width="100">救济领取:</td>
                <td>
                    <label id="lblCReliefScore"></label>                    </td>
                <td>
                    加减:<input id="txtReliefScore" type="text" style="width:80px" />
                    <span style="text-align: center;">
                    <input type="button" onClick="javascript:ChangeCenterValue('ReliefScore')" value="马上调配" id="btnToChange9" class="btn-1"/>
                    </span> </td>
                <td width="100">错误填平:</td>
                <td>
                    <label id="lblCErrorScore"></label>                    </td>
                <td>
                    加减:<input id="txtErrorScore" type="text" style="width:80px" />
                    <span style="text-align: center;">
                    <input type="button" onClick="javascript:ChangeCenterValue('ErrorScore')" value="马上调配" id="btnToChange16" class="btn-1"/>
                    </span> </td>                    
            </tr>
            <tr bgcolor="#FFFFFF">
                <td width="100">机器人平衡:</td>
                <td>
                    <label id="lblCRobotScore"></label>                    </td>
                <td>
                    加减:<input id="txtRobotScore" type="text" style="width:80px" />
                    <span style="text-align: center;">
                    <input type="button" onClick="javascript:ChangeCenterValue('RobotScore')" value="马上调配" id="btnToChange3" class="btn-1"/>
                    </span> </td>
                <td>下线奖励:</td>
                <td>
                    <label id="lblCUnderManScore"></label>                    </td>
                <td>
                    加减:<input id="txtUnderManScore" type="text" style="width:80px" />
                    <span style="text-align: center;">
                    <input type="button" onClick="javascript:ChangeCenterValue('UnderManScore')" value="马上调配" id="btnToChange10" class="btn-1"/>
                    </span> </td>
                <td>其他用途:</td>
                <td>
                    <label id="lblCOtherScore"></label>                    </td>
                <td>
                    加减:<input id="txtOtherScore" type="text" style="width:80px" />
                    <span style="text-align: center;">
                    <input type="button" onClick="javascript:ChangeCenterValue('OtherScore')" value="马上调配" id="btnToChange17" class="btn-1"/>
                    </span> </td>                    
            </tr>
            <tr bgcolor="#FFFFFF">
                <td>管理员充值:</td>
                <td>
                    <label id="lblCAdminScore"></label>                    </td>
                <td>
                    加减:<input id="txtAdminScore" type="text" style="width:80px" />
                    <span style="text-align: center;">
                    <input type="button" onClick="javascript:ChangeCenterValue('AdminScore')" value="马上调配" id="btnToChange4" class="btn-1"/>
                    </span> </td>
                <td>兑奖奖励:</td>
                <td>
                    <label id="lblCRewardScore"></label>                    </td>
                <td>
                    加减:<input id="txtRewardScore" type="text" style="width:80px" />
                    <span style="text-align: center;">
                    <input type="button" onClick="javascript:ChangeCenterValue('RewardScore')" value="马上调配" id="btnToChange11" class="btn-1"/>
                    </span> </td>
                <td>转账扣税累积:</td>
                <td>
                    <label id="lblCTransTaxScore"></label>                    </td>
                <td>&nbsp;</td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td>在线充值:</td>
                <td>
                    <label id="lblCOnlinePayScore"></label>                    </td>
                <td>
                    加减:<input id="txtOnlinePayScore" type="text" style="width:80px" />
                    <span style="text-align: center;">
                    <input type="button" onClick="javascript:ChangeCenterValue('OnlinePayScore')" value="马上调配" id="btnToChange5" class="btn-1"/>
                    </span> </td>
                <td>活动奖励</td>
                <td>
                    <label id="lblCActivityScore"></label>                    </td>
                <td>
                    加减:<input id="txtActivityScore" type="text" style="width:80px" />
                    <span style="text-align: center;">
                    <input type="button" onClick="javascript:ChangeCenterValue('ActivityScore')" value="马上调配" id="btnToChange12" class="btn-1"/>
                    </span> </td>
                <td>游戏税累积:</td>
                <td>
                    <label id="lblCGameTaxScore"></label>                    </td>
                <td>&nbsp;</td>
            </tr>
			<tr bgcolor="#FFFFFF">
                <td>卡充值:</td>
                <td>
                    <label id="lblCCardPayScore"></label>                    </td>
                <td>
                    加减:<input id="txtCardPayScore" type="text" style="width:80px" />
                    <span style="text-align: center;">
                    <input type="button" onClick="javascript:ChangeCenterValue('CardPayScore')" value="马上调配" id="btnToChange6" class="btn-1"/>
                    </span> </td>
                <td>道具奖励:</td>
                <td>
                    <label id="lblCPropScore"></label>                    </td>
                <td>
                    加减:<input id="txtPropScore" type="text" style="width:80px" />
                    <span style="text-align: center;">
                    <input type="button" onClick="javascript:ChangeCenterValue('PropScore')" value="马上调配" id="btnToChange13" class="btn-1"/>
                    </span> </td>
                <td>&nbsp;</td>
                <td>
                    <label id="lblCurRMBFee"></label>                    </td>
                <td>&nbsp;</td>
            </tr>
			
			
            <tr bgcolor="#FFFFFF">
                <td colspan="9" style="text-align: center;">&nbsp;</td>
            </tr>
        </table>
        <table id='tbllist' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
            <tr bgcolor="#FFFFFF">
                <td colspan="2">调配历史记录</td>
            </tr>
            <tr bgcolor="#FFFFFF">
              <td>
                    <input id="cbxCSChangeTime" type="checkbox" />时间
                   <input id="txtCSTimeBegin" type="text" style="width:120px" value="<?php echo date('Y-m-d',strtotime('-1 week')); ?>" />&nbsp;
                   <input id="txtCSTimeEnd" type="text" style="width:120px" value="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" />
                &nbsp;&nbsp;
                <input type="button" name="btnCSSearch" value="查询" id="btnCSSearch" class="btn-1"/>
                &nbsp;&nbsp;
                页大小
               <input id="txtCSPageSize" type="text" value="20" style="width:30px" />
              </td>
              <td width="160">
                  <select id = "sltCSOrder">
						<option value="opr_time">操作时间</option>
						<option value="bankIdx">子帐号</option>  
						<option value="opr_user">操作人</option>
				</select>
					<select id = "sltCSOrderType">
						<option value="desc">降序</option>
						<option value="">升序</option>
					</select>
              </td>
            </tr>
        </table>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" id="tblCSResult">
			<tr bgcolor="#f5fafe">
                <td >操作时间</td>
				<td >子帐号</td>
				<td  >原值</td>
                <td  >加减值</td>
                <td  >新值</td>
                <td  >操作用户</td>
			</tr>
		</table>
		<div class="fenyebar" id="CSPageInfo"></div>
	</div>
	<!-- 会员充值 -->
	<div class="categorylist" id="div_AdminTrans" style="display:none">
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
            <tr bgcolor="#FFFFFF">
                <td  colspan="2" style="text-align:left">给会员充值</td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td width="100px">
                    充值类型:
                </td>
                <td>
                    <label>
                        <input name="rgpATType" id="rgpATType" type="radio" value="0" checked >
                        充值可用分
					</label>
                    &nbsp;
                    <label>
                        <input name="rgpATType" id="rgpATType" type="radio" value="1">
                        充值银行分
                    </label>
                    &nbsp;
                    <label>
                        <input type="radio" name="rgpATType" id="rgpATType" value="2" >
                        充值经验
                    </label>
                    &nbsp;
                    <!--  
                    <label>
                        <input type="radio" name="rgpATType" id="rgpATType" value="3" >
                        投注分
                    </label>
                    -->
                </td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td>
                    用户ID:
                </td>
                <td>
                    <input id="txtATMemberIdx" type="text" style="width:100px" />
                    &nbsp;&nbsp;
                    <input type="button" value="检测用户" id="btnATCheckUser" class="btn-1"/>
                    &nbsp;&nbsp;
                    <label id="lblATUserInfo"></label>
                </td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td>
                    数量:
                </td>
                <td>
                    <input id="txtATAmount" type="text" style="width:200px" />负数为扣除<br>
                    <label id="lblATAmount"></label>
                </td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td>
                    备注类型:
                </td>
                <td>
                	<select id="sltATRemarkType">
                		<option value="0">会员充值</option>
                		<option value="1">错误填平</option>
                	</select>
                </td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td>
                    密码:
                </td>
                <td>
                    <input id="txtATPwd" type="password" style="width:200px" />
                </td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td>
                    备注:
                </td>
                <td>
                    <input id="txtATRemark" type="text" style="width:200px" />
                </td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td>
                </td>
                <td>
                    <input type="button" value="提交" id="btnATToTrans" class="btn-1"/>
                </td>
            </tr>
        </table>
        <!-- 查询 -->
        <table width="99%" border="0" align="center" cellpadding="0" cellspacing="1" class="tbtitle" style="BACKGROUND: #cad9ea;">
            <tr bgcolor="#FFFFFF">
                <td  colspan="3" style="text-align:left">查询历史记录</td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td width="80px">
                    <select id="sltATSearchType">
						<option value="1">汇长号</option>
					</select>
                </td>
                <td><input id="txtATSearchWord" type="text" style="width:100px" />
                    &nbsp;
                    <input id="cbxATTime" type="checkbox" />时间
                   <input id="txtATTimeBegin" type="text" style="width:80px" value="<?php echo date('Y-m-d',strtotime('-1 week')); ?>" />&nbsp;
                   <input id="txtATTimeEnd" type="text" style="width:80px" value="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" />
                   页大小
                   <input id="txtATPageSize" type="text" value="20" style="width:30px" />                        
                    充值类型
                    <select id="sltATOprType">
						<option value="-1">所有</option>
						<option value="0">充值可用分</option>
                        <option value="1">充值银行分</option>
                        <option value="2">充值经验</option>
                        <option value="3">投注分</option> 
					</select>
                    &nbsp;&nbsp;
                    <input type="button" value="查询" id="btnATSearch" class="btn-1"/>
                </td>
                <td width="180">
                  <select id = "sltATOrder">
						<option value="opr_time">操作时间</option>
						<option value="opr_type">类型</option>
                        <option value="amount">数量</option>
                        <option value="uid">会员ID</option>
                        <option value="opr_user">操作用户</option>
                        <option value="remark_type">备注类型</option>
						<option value="reason">备注</option>
				  </select>
					&nbsp;&nbsp;
					<select id = "sltATOrderType">
						<option value="desc">降序</option>
						<option value="">升序</option>
					</select>
                </td>
            </tr>
      </table>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" id="tblATResult">
		  	<tr bgcolor="#f5fafe">
				<td  >操作人</td>
				<td  >操作时间</td>
				<td  >会员ID</td>
                <td  >昵称</td>
                <td  >充值类型</td>
                <td  >数量</td>
                <td  >备注类型</td>
                <td  >备注</td>
			</tr>			    
		</table>
		<div class="fenyebar" id="ATPageInfo"></div>
	</div>
	<!-- 可疑帐号 -->
    <div class="categorylist" id="div_DoubtUser" style="display:none;">
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
        	<tr bgcolor="#FFFFFF">
        		 <td colspan="2"> 可疑帐号(游戏分+银行分-合计分的绝对值大于100000视为可疑)</td>
        	</tr>
            <tr bgcolor="#FFFFFF">
                <td>
                    用户ID
                    <input id="txtDUMemberIdx" type="text" style="width:80px" />
                    <input id="cbxExceptInner" type="checkbox" checked="checked">排除内部号
                    <input id="cbxDUAddTime" type="checkbox" />检测时间
                    <input id="txtDUTimeBegin" type="text" style="width:80px" value="<?php echo date('Y-m-d',strtotime('-1 week')); ?>" />&nbsp;
                    <input id="txtDUTimeEnd" type="text" style="width:80px" value="<?php echo date('Y-m-d',strtotime('+1 week')); ?>" />
                    页大小
                    <input id="txtDUPageSize" type="text" value="20" style="width:30px" />
                    &nbsp;&nbsp;
					<input type="button" value="查询" id="btnDUSearch" class="btn-1"/>
                </td>
                <td width="160">
                  <select id = "sltDUOrder">
                        <option value="check_time">检测时间</option>
						<option value="uid">用户ID</option>
						<option value="diff_points">分相差</option>
						<option value="points">当时分</option>
						<option value="back">当时银行</option>
						<option value="lock_points">投注分</option> 
						<option value="opr_user">处理者</option>
						<option value="remark">备注</option>
						<option value="status">状态</option> 
					</select>
					&nbsp;&nbsp;
					<select id = "sltDUOrderType">
						<option value="desc">降序</option>
						<option value="">升序</option>
					</select>
                </td>
            </tr>
        </table>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" id="tblDUResult">
		  	<tr bgcolor="#f5fafe">
				<td  >用户ID</td>
				<td  >昵称</td>
                <td  >当时分</td>
                <td  >当时银行</td>
                <td  >投注分</td>
				<td  >当时总分</td>
                <td  >合计分</td>
                <td  ><font color="red">分相差</font></td>
                <td  >检测时间</td>
                <td  >状态</td>
				<td  >备注</td>
				<td  >处理者</td>
				<td  >操作</td>
			</tr>			    
		</table>
		<div class="fenyebar" id="DUPageInfo"></div>
	</div>
	<!-- 负分帐号 -->
    <div class="categorylist" id="div_NegativeUser" style="display:none;">
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" id="tblList">
        	<tr bgcolor="#FFFFFF">
        		 <td colspan="2"> 负分用户 </td>
        	</tr>
            <tr bgcolor="#FFFFFF">
                <td>
                    用户ID
                    <input id="txtNUMemberIdx" type="text" style="width:80px" />
                    负分类型
                    <select id = "sltNUKind">
                        <option value="0">所有</option>
						<option value="1">可用分</option>
						<option value="2">银行分</option>
						<option value="3">投注分</option>
					</select>
					页大小
                    <input id="txtNUPageSize" type="text" value="20" style="width:30px" />
                    &nbsp;&nbsp;
					<input type="button" value="查询" id="btnNUSearch" class="btn-1"/>
                </td>
                <td width="160">
                  <select id = "sltNUOrder">
                        <option value="points">可用分</option>
						<option value="back">银行分</option>
						<option value="lock_points">投注分</option> 
					</select>
					&nbsp;&nbsp;
					<select id = "sltNUOrderType">
						<option value="">升序</option> 
						<option value="desc">降序</option>
					</select>
                </td>
            </tr>
        </table>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" id="tblNUResult">
		  	<tr bgcolor="#f5fafe">
				<td  >用户ID</td>
				<td  >昵称</td>
                <td  >负分类型</td>
                <td  >可用分</td>
                <td  >银行分</td>
                <td  >投注分</td>
                <td  >最后登录</td>
                <td  >登录IP</td>
			</tr>			    
		</table>
		<div class="fenyebar" id="NUPageInfo"></div>
	</div>
	<!-- 按天统计 -->
    <div class="categorylist" id="div_StatsDay" style="display:none;">
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
        	<tr bgcolor="#FFFFFF">
        		 <td colspan="2"> 按天统计(注意:游戏部分是指真实用户输赢) </td>
        	</tr>
            <tr bgcolor="#FFFFFF">
                <td>
                    <input id="cbxSDAddTime" type="checkbox" checked/>日期
                    <input id="txtSDTimeBegin" type="text" style="width:80px" value="<?php echo date('Y-m-01', strtotime(date("Y-m-d"))); ?>" />&nbsp;
                    <input id="txtSDTimeEnd" type="text" style="width:80px" value="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" />
                    页大小
                    <input id="txtSDPageSize" type="text" value="30" style="width:30px" />
                    &nbsp;&nbsp;
					<input type="button" value="查询" id="btnSDSearch" class="btn-1"/>
                </td>
                <td width="160">
                  <select id = "sltSDOrder">
                        <option value="time">日期</option>
						<option value="regnum">注册数</option>
						<option value="card">充卡分</option>
						<option value="transtax">转账抽税</option>
						<option value="gametax">游戏抽税</option>
						<option value="jjpoints">救济分</option> 
						<option value="exchangepoints">提现分</option>
					</select>
					&nbsp;&nbsp;
					<select id = "sltSDOrderType">
						<option value="desc">降序</option>
						<option value="">升序</option>
					</select>
                </td>
            </tr>
        </table>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" id="tblSDResult">
		  	<tr bgcolor="#f5fafe">
				<td  >日期</td>
				<td  >注册</td>
				<td  >论坛注册</td>
				<td  >广告注册</td>
				<td  >线下注册</td>
                <td  >注册分</td>
                <td  >返水分</td>
                <td  >提现分</td>
                <td  >提现手续费</td>
				<td  >红包分</td>
                <td  >充值分</td>
				<td  >充值送分</td>
                <td  >推广收益</td>
                <td  >游戏税</td>
				<td  >游戏总输赢</td>
			</tr>			    
		</table>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" id="tblSDResult2">
		  	<tr bgcolor="#f5fafe">
				<td  >日期</td>
				<td  >急速10</td>
				<td  >急速11</td>
				<td  >急速16</td>
				<td  >急速22</td>
                <td  >急速28</td>
                <td  >急速36</td>
                <td  >急速冠亚军</td>
                <td  >pk10</td>
                <td  >pk冠军</td>
                <td  >pk22</td>
				<td  >pk龙虎</td>
                <td  >pk冠亚军</td>
                <td  >pk赛车</td>
                <td  >幸运农场</td>
			</tr>			    
		</table>
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" id="tblSDResult3">
		  	<tr bgcolor="#f5fafe">
				<td  >日期</td>
                <td  >蛋蛋11</td>
                <td  >蛋蛋16</td>
                <td  >蛋蛋28</td>
                <td  >固定蛋蛋28</td>
                <td  >蛋蛋36</td>
                <td  >蛋蛋外围</td>
                <td  >蛋蛋定位</td>
				<td  >加拿大11</td>
				<td  >加拿大16</td>
				<td  >加拿大28</td>
				<td  >固定加拿大28</td>
				<td  >加拿大36</td>
				<td  >加拿大外围</td>
				<td  >加拿大定位</td>
			</tr>			    
		</table>
		
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" id="tblSDResult4">
		  	<tr bgcolor="#f5fafe">
				<td  >日期</td>
				<td  >韩国11</td>
				<td  >韩国16</td>
				<td  >韩国28</td>
				<td  >固定韩国28</td>
				<td  >韩国36</td>
				<td  >韩国外围</td>
				<td  >韩国定位</td>
				<td  >北京11</td>
				<td  >北京16</td>
                <td  >北京28</td>
                <td  >固定北京28</td>
				<td  >北京36</td>	
				<td  >北京外围</td>	
				<td  >北京定位</td>
			</tr>			    
		</table>
		
		
		<table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" id="tblSDResult5">
		  	<tr bgcolor="#f5fafe">
				<td  >日期</td>
				<td  >飞艇10</td>
				<td  >飞艇22</td>
				<td  >飞艇冠军</td>
				<td  >飞艇冠亚军</td>
				<td  >飞艇龙虎</td>
				<td  >重庆时时彩</td>
			</tr>			    
		</table>
		
		<div class="fenyebar" id="SDPageInfo"></div>
	</div>
</form>    
</body>
<script type= "text/javascript" language ="javascript">
    $(window.parent.document).attr("title","中央银行");
    $(document).ready(function() {
        InitDatePicker("txtSITimeBegin");
        InitDatePicker("txtSITimeEnd");
        InitDatePicker("txtCSTimeBegin");
        InitDatePicker("txtCSTimeEnd");
        InitDatePicker("txtATTimeBegin");
        InitDatePicker("txtATTimeEnd");
        InitDatePicker("txtDUTimeBegin");
        InitDatePicker("txtDUTimeEnd");
        InitDatePicker("txtSDTimeBegin");
        InitDatePicker("txtSDTimeEnd");
        GetSchduleCurInfo();
        GetSchduleHisInfo();
        //***************************************************************
		//中央银行信息
		$("#btnScheduleInfo").click(function(){
			showdiv("div_ScheduleInfo");
			//取数据
			GetSchduleCurInfo();
            GetSchduleHisInfo();
		});
		//刷新
		$("#btnRefresh").click(function(){
            GetSchduleCurInfo();
		});
        //历史记录查询
		$("#btnSISearch").click(function(){
            GetSchduleHisInfo();
		});
		//***************************************************************
        //修改调度
		$("#btnChangeSchedule").click(function(){
			showdiv("div_ChangeSchedule");
			//取数据
			GetCurCenterItem();
            GetChangeLog();
		});
        //查询调配历史记录
		$("#btnCSSearch").click(function(){
            GetChangeLog();
		});
		//***************************************************************
		//会员充值
		$("#btnAdminTrans").click(function(){
			showdiv("div_AdminTrans");
			//取数据
            GetAdminTransLog();
		});
        //查询
		$("#btnATSearch").click(function(){
            GetAdminTransLog();
		});
		//***************************************************************
		//可疑帐号
		$("#btnDoubtUser").click(function(){
			showdiv("div_DoubtUser");
			//取数据
            GetDoubtUser();
		});
        //查询
		$("#btnDUSearch").click(function(){
            GetDoubtUser();
		});
		//***************************************************************
		//负分帐号
		$("#btnNegativeUser").click(function(){
			showdiv("div_NegativeUser");
			//取数据
            GetNegativeUser();
		});
        //查询
		$("#btnNUSearch").click(function(){
            GetNegativeUser();
		});
		//***************************************************************
		//按天统计
		$("#btnStatsDayLog").click(function(){
			showdiv("div_StatsDay");
			//取数据
            GetStatsDayLog();
		});
        //查询
		$("#btnSDSearch").click(function(){
            GetStatsDayLog();
		});
	});
    //***************************************************************************************************
    //取得中央银行当前信息
    function GetSchduleCurInfo()
    {
        var data = "action=get_sc_info";
        SendAjax(data);
    }
    //取得中央银行历史信息
    function GetSchduleHisInfo()
    {
        var data = GetSIPara();
        $("#tblSIResult tr:gt(0)").remove();
        SendAjax(data);
    }
    //取得调度历史查询参数
    function GetSIPara()
    {
        $("#tblSIResult tr:gt(0)").remove();
        $("#SIPageInfo").html('');
        var data = "action=get_sh_info";
        var DateBegin = $("#txtSITimeBegin").val();
		var DateEnd = $("#txtSITimeEnd").val();
        var PageSize = $.trim($("#txtSIPageSize").val());
        
        if(PageSize == "" || isNaN(PageSize))
        {
            PageSize = "20";
            $("#txtSIPageSize").val("20");
        }
        data += "&PageSize=" + PageSize;
        if($("#cbxSICheckTime").is(":checked"))
		{
			if(DateBegin != "")
			{
				if(!ValidDate(DateBegin))
				{
					$("#txtSITimeBegin").val("");
				}
                else
                {
                    data += "&timebegin=" + DateBegin;
                }
			}
			if(DateEnd != "")
			{
				if(!ValidDate(DateEnd))
				{
					$("#txtSITimeEnd").val("");
				}
                else
                {
                    data += "&timeend=" + DateEnd;
                }
			}
		}
        return data;
    }
    //记录分页
    function ajax_page_SI(page)
    {
        var data = GetSIPara();
        data += "&Page=" + page;
        SendAjax(data);
    }
    //***************************************************************************************************
	//修改调度
	function ChangeCenterValue(itemtype)
	{
		var o = "#txt" + itemtype;
        var increment = $.trim($(o).val());
        if(increment == "")
        {
            alert("请输入加减值");
            return;
        }
        if(isNaN(increment))
        {
            alert("加减值必须为正负数");
            return;
        }
        var data = "action=change_center_item&field=" + itemtype + "&inc=" + parseInt(increment);
        SendAjax(data);
        GetCurCenterItem();
        GetChangeLog();
	}
    //取得当前各项调度数目
    function GetCurCenterItem()
    {
        var data = "action=get_cc_info";
        SendAjax(data);
    }
    //*************************************************************************************************** 
    //查询调度历史
    function GetChangeLog()
    {
        var data = GetCSPara();
        SendAjax(data);
    }
    //取得修改历史查询参数
    function GetCSPara()
    {
        $("#tblCSResult tr:gt(0)").remove();
        $("#CSPageInfo").html('');
        var data = "action=get_changelog";
        var DateBegin = $("#txtCSTimeBegin").val();
		var DateEnd = $("#txtCSTimeEnd").val();
		var PageSize = $.trim($("#txtCSPageSize").val());
        
        if(PageSize == "" || isNaN(PageSize))
        {
            PageSize = "20";
            $("#txtCSPageSize").val("20");
        }
        data += "&PageSize=" + PageSize;
        if($("#cbxCSChangeTime").is(":checked"))
		{
			if(DateBegin != "")
			{
				if(!ValidDate(DateBegin))
				{
					$("#txtCSTimeBegin").val("");
				}
                else
                {
                    data += "&timebegin=" + DateBegin;
                }
			}
			if(DateEnd != "")
			{
				if(!ValidDate(DateEnd))
				{
					$("#txtCSTimeEnd").val("");
				}
                else
                {
                    data += "&timeend=" + DateEnd;
                }
			}
		}
		data += "&order=" + $("#sltCSOrder").val() + "&ordertype=" + $("#sltCSOrderType").val(); 
        return data;
    }
    //记录分页
    function ajax_page_CS(page)
    {
        var data = GetCSPara();
        data += "&Page=" + page;
        SendAjax(data);
    }
	//***************************************************************************************************
	//会员充值
	//检测用户
	$("#btnATCheckUser").click(function(){
        ToCheckUser("check_user_at");
	});
	//提交充值
    $("#btnATToTrans").click(function(){
        var MemberIdx = $.trim($("#txtATMemberIdx").val())
        var TransType = $(":radio[name='rgpATType']:checked").val();
        var RemarkType = $("#sltATRemarkType").val();
        var Amount = $.trim($("#txtATAmount").val());
        var Pwd = $.trim($("#txtATPwd").val());
        var Remark = $.trim($("#txtATRemark").val());

        if(MemberIdx == "" || isNaN(MemberIdx))
        {
            alert("用户ID必须为数字");
            return false;
        }
        if(Amount == "" || isNaN(Amount))
        {
            alert("充值数量必须为数字");
            return false;
        }
        if(Pwd == "")
        {
            alert("必须输入密码");
            return false;
        }
        if(Remark == "")
        {
            alert("必须输入备注");
            return false;
        }
        var data = "action=admin_trans&memberidx=" + MemberIdx + "&transtype=" + TransType + "&remarktype=" + RemarkType
                    + "&amount=" + Amount + "&pwd=" + Pwd + "&remark=" + Remark;
        if(confirm("您确定要提交吗?"))
        {
            SendAjax(data);
            ToCheckUser("check_user_at");
            GetAdminTransLog();
        }
        
	});
    //数目转大写
    $("#txtATAmount").blur(function(){
        GetAmountInWords();
	});
    //数目转大写
    $("#txtATAmount").keyup(function(){
        GetAmountInWords();
	});
	//取得大写
    function GetAmountInWords()
    {
        var Amount = $.trim($("#txtATAmount").val());
        var Words = AmountInWords(Amount,8)
        $("#lblATAmount").html(Words);
    }
    //检测用户
    function ToCheckUser(act)
    {
    	var lbluser = "#lblATUserInfo";
    	var txtuser = "#txtATMemberIdx";
        $(lbluser).html("");
        var MemberIdx = $.trim($(txtuser).val());
        if(MemberIdx == "")
        {
            alert("请输入用户ID");
            return false;
        }
        if(isNaN(MemberIdx))
        {
            alert("用户ID必须为数字");
            return false;
        }
        var data = "action="+ act +"&memberidx=" + MemberIdx;
        SendAjax(data);
    }
    //取得充值历史
    function GetAdminTransLog()
    {
        var data = GetATPara();
        SendAjax(data);
    }
    //取得查询参数
    function GetATPara()
    {
    	$("#tblATResult tr:gt(0)").remove();
        $("#ATPageInfo").html('');
        var data = "action=get_admintranslog";
        var SearchType = $("#sltATSearchType").val();
        var SearchWord = $("#txtATSearchWord").val();
        var DateBegin = $.trim($("#txtATTimeBegin").val());
        var DateEnd = $.trim($("#txtATTimeEnd").val());
        var PageSize = $.trim($("#txtATPageSize").val());
		var OprType = $("#sltATOprType").val();
        
        if(SearchWord != "")
        {
            if(SearchType == "1")
            {
                if(isNaN(SearchWord))
                {
                    $("#txtATSearchWord").val("");
                    SearchWord = "";
                }
            }
        }
        data += "&type=" + SearchType + "&word=" + SearchWord + "&oprtype=" + OprType;
        if($("#cbxATTime").is(":checked"))
		{
			if(DateBegin != "")
			{
				if(!ValidDate(DateBegin))
				{
					$("#txtATTimeBegin").val("");
				}
                else
                {
                    data += "&timebegin=" + DateBegin;
                }
			}
			if(DateEnd != "")
			{
				if(!ValidDate(DateEnd))
				{
					$("#txtATTimeEnd").val("");
				}
                else
                {
                    data += "&timeend=" + DateEnd;
                }
			}
		}
        
        data += "&PageSize=" + PageSize + "&order=" + $("#sltATOrder").val() + "&ordertype=" + $("#sltATOrderType").val();
        return data;
    }
    //分页
    function ajax_page_AT(page)
    {
        var data = GetATPara();
        data += "&Page=" + page;
        SendAjax(data);
    }
	//***************************************************************************************************
	//取可疑用户
	function GetDoubtUser()
	{
		var data = GetDUPara();
        SendAjax(data);
	}
	//取得查询参数
    function GetDUPara()
    {
    	$("#tblDUResult tr:gt(0)").remove();
        $("#DUPageInfo").html('');
        var data = "action=get_doubt_user";
        var MemberIdx = $("#txtDUMemberIdx").val();
        var DateBegin = $.trim($("#txtDUTimeBegin").val());
        var DateEnd = $.trim($("#txtDUTimeEnd").val());
        var PageSize = $.trim($("#txtDUPageSize").val());
        
        if(MemberIdx != "" && isNaN(MemberIdx))
        { 
            $("#txtDUMemberIdx").val("");
            MemberIdx = "";
        }
        var isExceptInner = 0;
        if($("#cbxExceptInner").is(":checked"))
        	isExceptInner = 1;
        data += "&isexceptinner=" + isExceptInner;
        
        data += "&memberidx=" + MemberIdx;
        if($("#cbxDUAddTime").is(":checked"))
		{
			if(DateBegin != "")
			{
				if(!ValidDate(DateBegin))
				{
					$("#txtDUTimeBegin").val("");
				}
                else
                {
                    data += "&timebegin=" + DateBegin;
                }
			}
			if(DateEnd != "")
			{
				if(!ValidDate(DateEnd))
				{
					$("#txtDUTimeEnd").val("");
				}
                else
                {
                    data += "&timeend=" + DateEnd;
                }
			}
		}
        
        data += "&PageSize=" + PageSize + "&order=" + $("#sltDUOrder").val() + "&ordertype=" + $("#sltDUOrderType").val();
        return data;
    }
    //分页
    function ajax_page_DU(page)
    {
        var data = GetDUPara();
        data += "&Page=" + page;
        SendAjax(data);
    }  
    //删除可疑用户日志
	function RemoveDoubtUser(id)
	{
		var data = "action=remove_doubtuser&id=" + id
		if(confirm("您确定要移除该用户吗(注:只是移除日志，不会真正删除用户)"))
		{
			SendAjax(data);
			GetDoubtUser();
		}
	}
	//备注
	function ToRemark(ID)
    {
        var msg = $("#"+ID + "_remark").val();
        if(msg.length == 0)
        {
            alert("请输入备注!");
            return false;
        }
        if(confirm("您确定要备注吗?"))
        {
            var data = "action=doubtuser_remark&id=" + ID + "&remark=" + msg;
            SendAjax(data);
            GetDoubtUser();
        }
    }
	//***************************************************************************************************
	//取负分用户
	function GetNegativeUser()
	{
		var data = GetNUPara();
        SendAjax(data);
	}
	//取得查询参数
    function GetNUPara()
    {
    	$("#tblNUResult tr:gt(0)").remove();
        $("#NUPageInfo").html('');
        var data = "action=get_negative_user";
        var Kind = $("#sltNUKind").val();
        var MemberIdx = $("#txtNUMemberIdx").val();
        var PageSize = $.trim($("#txtNUPageSize").val());
        
        if(MemberIdx != "" && isNaN(MemberIdx))
        { 
            $("#txtNUMemberIdx").val("");
            MemberIdx = "";
        }
        data += "&memberidx=" + MemberIdx + "&kind=" + Kind;
        
        data += "&PageSize=" + PageSize + "&order=" + $("#sltNUOrder").val() + "&ordertype=" + $("#sltNUOrderType").val();
        return data;
    }
    //分页
    function ajax_page_NU(page)
    {
        var data = GetNUPara();
        data += "&Page=" + page;
        SendAjax(data);
    }
    //***************************************************************************************************
    //取按天统计
	function GetStatsDayLog()
	{
		var data = GetSDPara();
        SendAjax(data);
	}
	//取得查询参数
    function GetSDPara()
    {
    	$("#tblSDResult tr:gt(0)").remove();
    	$("#tblSDResult2 tr:gt(0)").remove();
        $("#SDPageInfo").html('');
        var data = "action=get_stats_daylog";
        var DateBegin = $.trim($("#txtSDTimeBegin").val());
        var DateEnd = $.trim($("#txtSDTimeEnd").val());
        var PageSize = $.trim($("#txtSDPageSize").val());
        
        if($("#cbxSDAddTime").is(":checked"))
		{
			if(DateBegin != "")
			{
				if(!ValidDate(DateBegin))
				{
					$("#txtSDTimeBegin").val("");
				}
                else
                {
                    data += "&timebegin=" + DateBegin;
                }
			}
			if(DateEnd != "")
			{
				if(!ValidDate(DateEnd))
				{
					$("#txtSDTimeEnd").val("");
				}
                else
                {
                    data += "&timeend=" + DateEnd;
                }
			}
		}
        
        data += "&PageSize=" + PageSize + "&order=" + $("#sltSDOrder").val() + "&ordertype=" + $("#sltSDOrderType").val();
        return data;
    }
    //分页
    function ajax_page_SD(page)
    {
        var data = GetSDPara();
        data += "&Page=" + page;
        SendAjax(data);
    }  
    //***************************************************************************************************
    //显示层
	function showdiv(divName)
	{
		$("div[id*='div_']").hide();
		$("#" + divName).show();
	}
    //取得勾选ID
    function GetCheckID(id)
    {
        var IDs = "";
        $("input[name='"+ id +"']:checked").each(function(){
            IDs += $(this).val() + ",";
        });
        if(IDs.length > 0)
        {
            IDs = IDs.substr(0,IDs.length-1);
        }
        return IDs;
    }
    //初始化日期控件
    function InitDatePicker(o)
    {
        $("#" + o).datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,  //可以选择月份  
            changeYear: true,   //可以选择年份 
            dayNamesMin : ["日", "一", "二", "三", "四", "五", "六"], 
            firstDay : 1, 
            monthNamesShort: ["1", "2", "3", "4", "5", "6","7", "8", "9", "10", "11", "12"],
            yearRange: 'c-60:c+20'
        });  
    }
    //验证日期正确是否，如2012-06-22
	function ValidDate(str)
    {
		 var r = str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
		 if(r==null)return false;
		 var d= new Date(r[1], r[3]-1, r[4]);
		 return (d.getFullYear()==r[1]&&(d.getMonth()+1)==r[3]&&d.getDate()==r[4]);
    }
    //小写转大写
    function AmountInWords(dValue, maxDec)
    {
       //dValue 金额数值或数值字符串
       //maxDec 精确小数位数（值域0~8，不指定则默认为2，超限归为默认）。
       // 验证输入金额数值或数值字符串：
       dValue = dValue.toString().replace(/,/g, "");
       dValue = dValue.replace(/^0+/, ""); // 金额数值转字符、移除逗号、移除前导零
       if (dValue == "") { return ""; } // （错误：金额为空！）
       else if (isNaN(dValue)) { return "必须数字"; }
       
       var minus = ""; // 负数的符号“-”的大写：“负”字。可自定义字符，如“（负）”。
       var CN_SYMBOL = ""; // 币种名称（如“人民币”，默认空）
       if (dValue.length > 1)
       {
       if (dValue.indexOf('-') == 0) { dValue = dValue.replace("-", ""); minus = "负"; } // 处理负数符号“-”
       if (dValue.indexOf('+') == 0) { dValue = dValue.replace("+", ""); } // 处理前导正数符号“+”（无实际意义）
       }

      // 变量定义：
       var vInt = ""; var vDec = ""; // 字符串：金额的整数部分、小数部分
       var resAIW; // 字符串：要输出的结果
       var parts; // 数组（整数部分.小数部分），length=1时则仅为整数。
       var digits, radices, bigRadices, decimals; // 数组：数字（0~9——零~玖）；基（十进制记数系统中每个数字位的基是10——拾,佰,仟）；大基（万,亿,兆,京,垓,杼,穰,沟,涧,正）；辅币（元以下，角/分/厘/毫/丝）。
       var zeroCount; // 零计数
       var i, p, d; // 循环因子；前一位数字；当前位数字。
       var quotient, modulus; // 整数部分计算用：商数、模数。

       // 金额数值转换为字符，分割整数部分和小数部分：整数、小数分开来搞（小数部分有可能四舍五入后对整数部分有进位）。
       var NoneDecLen = (typeof(maxDec) == "undefined" || maxDec == null || Number(maxDec) < 0 || Number(maxDec) > 8); // 是否未指定有效小数位（true/false）
       parts = dValue.split('.'); // 数组赋值：（整数部分.小数部分），Array的length=1则仅为整数。
       if (parts.length > 1)
      {
       vInt = parts[0]; vDec = parts[1]; // 变量赋值：金额的整数部分、小数部分

      if(NoneDecLen) { maxDec = vDec.length > 8 ? 8 : vDec.length; } // 未指定有效小数位参数值时，自动取实际小数位长但不超8。
       var rDec = Number("0." + vDec);
      rDec *= Math.pow(10, maxDec); rDec = Math.round(Math.abs(rDec)); rDec /= Math.pow(10, maxDec); // 小数四舍五入
       var aIntDec = rDec.toString().split('.');
       if(Number(aIntDec[0]) == 1) { vInt = (Number(vInt) + 1).toString(); } // 小数部分四舍五入后有可能向整数部分的个位进位（值1）
       if(aIntDec.length > 1) { vDec = aIntDec[1]; } else { vDec = ""; }
       }
       else { vInt = dValue; vDec = ""; if(NoneDecLen) { maxDec = 0; } }
      if(vInt.length > 44) { return "错误：数值太大了！整数位长【" + vInt.length.toString() + "】超过了上限——44位/千正/10^43（注：1正=1万涧=1亿亿亿亿亿，10^40）！"; }

      // 准备各字符数组 Prepare the characters corresponding to the digits:
       digits = new Array("零", "壹", "贰", "叁", "肆", "伍", "陆", "柒", "捌", "玖"); // 零~玖
       radices = new Array("", "拾", "佰", "仟"); // 拾,佰,仟
       bigRadices = new Array("", "万", "亿", "兆", "京", "垓", "杼", "穰" ,"沟", "涧", "正"); // 万,亿,兆,京,垓,杼,穰,沟,涧,正
       decimals = new Array("角", "分", "厘", "毫", "丝"); // 角/分/厘/毫/丝

      resAIW = ""; // 开始处理

      // 处理整数部分（如果有）
       if (Number(vInt) > 0)
      {
       zeroCount = 0;
       for (i = 0; i < vInt.length; i++)
      {
       p = vInt.length - i - 1; d = vInt.substr(i, 1); quotient = p / 4; modulus = p % 4;
       if (d == "0") { zeroCount++; }
       else
      {
       if (zeroCount > 0) { resAIW += digits[0]; }
       zeroCount = 0; resAIW += digits[Number(d)] + radices[modulus];
       }
       if (modulus == 0 && zeroCount < 4) { resAIW += bigRadices[quotient]; }
       }
       resAIW += ""; //"元"
       }
       if(vInt == 0) {resAIW += "零";}
       if(vDec.length > 0) {resAIW += "点";}
      // 处理小数部分（如果有）
       for (i = 0; i < vDec.length; i++) { d = vDec.substr(i, 1); if (d != "0") { resAIW += digits[Number(d)]; } }

      // 处理结果
       if (resAIW == "") { 
           resAIW = "请输入";//"零" + "元";
       } // 零元
       if (vDec == "") { resAIW += "整"; } // ...元整
       resAIW = CN_SYMBOL + minus + resAIW; // 人民币/负......元角分/整
       return resAIW;
    }
	//ajax处理
	function SendAjax(SendData)
	{
		var PostURL = "scenterbank.php";
		$.ajax({
		       type: "POST",
		       async:false,
		       dataType: "json",
		       url: PostURL,
		       data: SendData,
		       success: function(data) {DataSuccess(data);}
		});
	}
	//数据成功后
	function DataSuccess(json)
	{ 
		var tbody = "";
		var tbody2 = "";
		var tbody3 = "";
		var tbody4 = "";
		var tbody5 = "";
		var pageinfo = "";
        var InfoType = "";
		$.each(json,function(i,item){
			if(i == 0)
			{
                InfoType = item.cmd;
				switch(item.cmd)
				{ 
					case "get_negative_user":
						pageinfo = item.msg;
                        break;
					case "get_doubt_user":
						pageinfo = item.msg;
                        break;
					case "check_user_at":
						$("#lblATUserInfo").html(item.msg);
						return;
					case "get_sc_info":
                        break;
                    case "get_sh_info":
                        pageinfo = item.msg;
                        break;
                    case "get_cc_info":
                        break;  
                    case "get_changelog":
                    	pageinfo = item.msg;
                        break;   
                    case "get_admintranslog":
                    	pageinfo = item.msg;
                        break;
                    case "get_stats_daylog":
                    	pageinfo = item.msg;
                        break;
					default:
						alert(item.msg);
						return;
						break;
				}
			}
			else
			{
				if(InfoType == "get_sc_info")
                {
                    $("#lblSystemScore").html(item.SystemScore);
                    $("#lblRobotScore").html(item.RobotScore);
                    $("#lblAdminScore").html(item.AdminScore);
                    $("#lblOnlinePayScore").html(item.OnlinePayScore);
                    $("#lblCardPayScore").html(item.CardPayScore);
                    
                    $("#lblReliefScore").html(item.ReliefScore);
                    $("#lblUnderManScore").html(item.UnderManScore);
                    $("#lblRewardScore").html(item.RewardScore);
                    $("#lblActivityScore").html(item.ActivityScore);
                    $("#lblPropScore").html(item.PropScore);
                    
                    $("#lblErrorScore").html(item.ErrorScore);
                    $("#lblOtherScore").html(item.OtherScore);
                    $("#lblTransTaxScore").html(item.TransTaxScore);
					$("#lblGameTaxScore").html(item.GameTaxScore);
					$("#lblTotalExp").html(item.TotalExp);
                    
                    $("#lblBlockScore").html(item.BlockScore);
                    $("#lblCirScore").html(item.CirScore);
                    $("#lblRobotTotalScore").html(item.RobotTotalScore);
                    $("#lblUserScore").html("<a href='admin_patchuser.php?order=totalpoint'>"+item.UserScore+"</a>"); 
                    $("#lblUserWinLoseScore").html(item.UserWinLoseScore);
                    $("#lblCenterBank").html(item.CenterBank);
                 }
                else if(InfoType == "get_sh_info")
                 {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                                "<td>" + item.LogTime + "</td>" +
                                //"<td>" + item.TotalExp + "</td>" +
								"<td>" + item.AdminScore + "</td>" +
								"<td>" + item.OnlinePayScore + "</td>" +
                                "<td>" + item.CardPayScore + "</td>" +
                                "<td>" + item.ReliefScore + "</td>" +
                                "<td>" + item.UnderManScore + "</td>" +
                                "<td>" + item.RewardScore + "</td>" +
                                "<td>" + item.ActivityScore + "</td>" +
                                "<td>" + item.PropScore + "</td>" +
                                "<td>" + item.ErrorScore + "</td>" +
                                "<td>" + item.OtherScore + "</td>" +
                                "<td>" + item.GameTaxScore + "</td>" +
                                "<td>" + item.BlockScore + "</td>" +
                                "<td><a href='admin_patchuser.php?order=totalpoint'>" + item.UserScore + "</a></td>" +
                                "<td>" + item.RobotScore + "</td>" +
                                "<td>" + item.CirScore + "</td>" +
                                "<td>" + item.UserWinLoseScore + "</td>" +
                                "<td>" + item.CenterBank + "</td>" +
                            "</tr>";
                 }
                else if(InfoType == "get_cc_info")
                {
                    $("#lblCSystemScore").html(item.SystemScore);
                    $("#lblCRobotScore").html(item.RobotScore);
                    $("#lblCAdminScore").html(item.AdminScore);
                    $("#lblCOnlinePayScore").html(item.OnlinePayScore);
                    $("#lblCCardPayScore").html(item.CardPayScore);
                    
                    $("#lblCReliefScore").html(item.ReliefScore);
                    $("#lblCUnderManScore").html(item.UnderManScore);
                    $("#lblCRewardScore").html(item.RewardScore);
                    $("#lblCActivityScore").html(item.ActivityScore);
                    $("#lblCPropScore").html(item.PropScore);
                    
                    $("#lblCErrorScore").html(item.ErrorScore);
                    $("#lblCOtherScore").html(item.OtherScore);
                    $("#lblCTransTaxScore").html(item.TransTaxScore); 
					$("#lblCGameTaxScore").html(item.GameTaxScore);
                    
                    $("#lblCCenterBank").html(item.CenterBank);                 
                 }    
                else if(InfoType == "get_changelog")
                 {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                                "<td>" + item.OprTime + "</td>" +
                                "<td>" + item.OprItem + "</td>" +
                                "<td>" + item.OldValue + "</td>" +
                                "<td>" + item.AddValue + "</td>" +
                                "<td>" + item.NewValue + "</td>" +
                                "<td>" + item.OprUser + "</td>" + 
                            "</tr>";
                 }  
                else if(InfoType == "get_admintranslog")
                 {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                    			"<td>" + item.OprUser + "</td>" +
                                "<td>" + item.OprTime + "</td>" +
                                "<td>" + item.UserID + "</td>" +
                                "<td>" + item.NickName + "</td>" +
                                "<td>" + item.TransType + "</td>" +
                                "<td>" + item.Amount + "</td>" +
                                "<td>" + item.RemarkType + "</td>" + 
                                "<td>" + item.Remark + "</td>" + 
                            "</tr>";
                 } 
                else if(InfoType == "get_doubt_user")
                 {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                    			"<td>" + item.UserID + "</td>" +
                                "<td>" + item.NickName + "</td>" +
                                "<td>" + item.Points + "</td>" +
                                "<td>" + item.Back + "</td>" +
                                "<td>" + item.LockPoints + "</td>" +
                                "<td>" + item.TotalPoints + "</td>" +
								"<td>" + item.SumPoints + "</td>" +
                                "<td>" + item.DiffPoints + "</td>" + 
                                "<td>" + item.CheckTime + "</td>" + 
                                "<td>" + item.Status + "</td>" + 
                                "<td>" + item.Remark + "</td>" + 
                                "<td>" + item.OprUser + "</td>" +
                                "<td>" + item.Opr + "</td>" + 
                            "</tr>";
                 }         
                else if(InfoType == "get_negative_user")
                 {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                    			"<td>" + item.UserID + "</td>" +
                                "<td>" + item.NickName + "</td>" +
                                "<td>" + item.NegativeType + "</td>" +
                                "<td>" + item.Points + "</td>" +
                                "<td>" + item.Back + "</td>" +
                                "<td>" + item.LockPoints + "</td>" + 
                                "<td>" + item.LastLogin + "</td>" +
                                "<td>" + item.LoginIP + "</td>" +
                            "</tr>";
                 }
                 else if(InfoType == "get_stats_daylog")
                 {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                    			"<td>" + item.LogDate + "</td>" +
                                "<td><a href='admin_reg.php?date="+item.LogDate+"'>" + item.RegNum + "</a></td>" +
								"<td><a href='admin_reg.php?tjid=908639&date="+item.LogDate+"'>" + item.ForumNum + "</a></td>" +
								"<td><a href='admin_reg.php?tjid=826423&date="+item.LogDate+"'>" + item.OnlineNum + "</a></td>" +
								"<td><a href='admin_reg.php?tjid=0&date="+item.LogDate+"'>" + item.xxtg + "</a></td>" +
                                "<td><a href='admin_reg.php?date="+item.LogDate+"'>" + item.RegPoints + "</a></td>" +
                                "<td>" + item.Rebate + "</td>" +
                                "<td><a href='admin_withdrawals.php'>" + item.ExchangePoints + "</a></td>" +
                                "<td>￥" + item.CashFee + "</td>" + 
                                "<td>" + item.Card + "</td>" + 
                                "<td><a href='admin_pay.php'>" + item.PayOnline + "</td>" +
								"<td><a href='admin_pay.php'>" + item.Give_cz_point + "</td>" +
                                "<td>" + item.TransTax + "</td>" +
                                "<td>" + item.GameTax + "</td>" + 
                                "<td>" + item.gamewinlose + "</td>" + 
                            "</tr>";
                    tbody2 += "<tr bgcolor='#FFFFFF'>" +
                    			"<td>" + item.LogDate + "</td>" +
                    			"<td>" + item.gamefast10 + "</td>" +
                    			"<td>" + item.gamefast11 + "</td>" +
                    			"<td>" + item.gamefast16 + "</td>" + 
                    			"<td>" + item.gamefast22 + "</td>" +
                                "<td>" + item.gamefast28 + "</td>" + 
                                "<td>" + item.gamefast36 + "</td>" +
                                "<td>" + item.gamefastgyj + "</td>" +
			                    "<td>" + item.gamepk10 + "</td>" +
			                    "<td>" + item.gamegj10 + "</td>" +
			                    "<td>" + item.gamepk22 + "</td>" + 
			                    "<td>" + item.gamepklh + "</td>" +
			                    "<td>" + item.gamepkgyj + "</td>" +
			                    "<td>" + item.gamepksc + "</td>" +
			                    "<td>" + item.gamexync + "</td>" +
                            "</tr>";

                    tbody3 += "<tr bgcolor='#FFFFFF'>" +
			        			"<td>" + item.LogDate + "</td>" +
			        			"<td>" + item.game11 + "</td>" + 
			        			"<td>" + item.game16 + "</td>" + 
			        			"<td>" + item.game28 + "</td>" + 
			        			"<td>" + item.game28gd + "</td>" + 
                                "<td>" + item.game36 + "</td>" + 
                                "<td>" + item.gameww + "</td>" +
                                "<td>" + item.gamedw + "</td>" +
                                "<td>" + item.gamecan11 + "</td>" + 
                                "<td>" + item.gamecan16 + "</td>" + 
			                    "<td>" + item.gamecan28 + "</td>" + 
			                    "<td>" + item.gamecan28gd + "</td>" + 
			                    "<td>" + item.gamecan36 + "</td>" +
			                    "<td>" + item.gamecanww + "</td>" +
			                    "<td>" + item.gamecandw + "</td>" +
			                "</tr>";

                    tbody4 += "<tr bgcolor='#FFFFFF'>" +
			        			"<td>" + item.LogDate + "</td>" +
			        			"<td>" + item.gamehg11 + "</td>" + 
			        			"<td>" + item.gamehg16 + "</td>" + 
			                    "<td>" + item.gamehg28 + "</td>" +
			                    "<td>" + item.gamehg28gd + "</td>" +
			                    "<td>" + item.gamehg36 + "</td>" + 
			                    "<td>" + item.gamehgww + "</td>" +
			                    "<td>" + item.gamehgdw + "</td>" +
			                    
			                    "<td>" + item.gamebj11 + "</td>" +
			                    "<td>" + item.gamebj16 + "</td>" +
                                "<td>" + item.gameself28 + "</td>" + 
                                "<td>" + item.gamebj28gd + "</td>" + 
                                "<td>" + item.gamebj36 + "</td>" +
                                "<td>" + item.gamebjww + "</td>" +
                                "<td>" + item.gamebjdw + "</td>" +
			                "</tr>";

                    tbody5 += "<tr bgcolor='#FFFFFF'>" +
			        			"<td>" + item.LogDate + "</td>" +
			        			"<td>" + item.gameairship10 + "</td>" + 
			        			"<td>" + item.gameairship22 + "</td>" + 
			                    "<td>" + item.gameairshipgj10 + "</td>" +
			                    "<td>" + item.gameairshipgyj + "</td>" +
			                    "<td>" + item.gameairshiplh + "</td>" + 
			                    "<td>" + item.gamecqssc + "</td>" +
			                "</tr>";
	                
                 }
			}

		});
		if(tbody != "")
		{
			if(InfoType == "get_sh_info")
            {
                $("#tblSIResult tr:gt(0)").remove();
                $("#tblSIResult").append(tbody);
                $("#SIPageInfo").html(pageinfo);
            }  
            else if(InfoType == "get_changelog")
            {
                $("#tblCSResult tr:gt(0)").remove();
                $("#tblCSResult").append(tbody);
                $("#CSPageInfo").html(pageinfo);
            }  
            else if(InfoType == "get_admintranslog")
            {
                $("#tblATResult tr:gt(0)").remove();
                $("#tblATResult").append(tbody);
                $("#ATPageInfo").html(pageinfo);
            }
            else if(InfoType == "get_doubt_user")
            {
                $("#tblDUResult tr:gt(0)").remove();
                $("#tblDUResult").append(tbody);
                $("#DUPageInfo").html(pageinfo);
            }   
            else if(InfoType == "get_negative_user")
            {
                $("#tblNUResult tr:gt(0)").remove();
                $("#tblNUResult").append(tbody);
                $("#NUPageInfo").html(pageinfo);
            }
            else if(InfoType == "get_stats_daylog")
            {
                $("#tblSDResult tr:gt(0)").remove();
                $("#tblSDResult").append(tbody);
                
                $("#tblSDResult2 tr:gt(0)").remove();
                $("#tblSDResult2").append(tbody2);
                $("#tblSDResult3 tr:gt(0)").remove();
                $("#tblSDResult3").append(tbody3);
                $("#tblSDResult4 tr:gt(0)").remove();
                $("#tblSDResult4").append(tbody4);

                $("#tblSDResult5 tr:gt(0)").remove();
                $("#tblSDResult5").append(tbody5);
                
                $("#SDPageInfo").html(pageinfo);
            }
		}
	}
    
</script>

</html>

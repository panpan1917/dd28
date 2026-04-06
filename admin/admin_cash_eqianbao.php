<?php
/*********************/
/*                   */
/*  Version : 1.0  */
/*  Author  : XMB     */
/*  Comment : 07-04-08 19:33 */
/*                   */
/*********************/

include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );

function sadd( )
{
	login_check( "system" );
	
	include_once( dirname( __FILE__ )."/inc/payment.php" );
	
		global $db;
		$return = array();
		$ordernum = time() . rand(1000,9999);
		$cashtype = (int)$_REQUEST['cashtype'];
		$adminid = $_SESSION['Admin_UserID'];
		$sql ="INSERT INTO eqianbao_cashlog(ordernum,cashtype,addtime,adminid) 
				VALUES ('{$ordernum}','{$cashtype}',Now(),'{$adminid}')";
		$res = $db->query($sql);
		if($res){
			$payment = new payment();
			$callbackurl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].'/eqianbaocashCallback.php';
			$payment->setDrawCallBackUrl($callbackurl);
			$return = $payment->drawRequest($cashtype, $ordernum , null);
		}
		
		return $return;
}



function main( )
{
		global $db;
		global $web_dbtop;
		echo "<TABLE width=\"99%\" border=0 align=center cellpadding=\"5\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">  
				<TBODY>    
				<TR bgColor=\"#f5fafe\">      
				<TD align=\"center\">提现订单号</TD>     
				<TD align=\"center\">提现类型</TD>      
				<TD align=\"center\">金额</TD>     
				<TD align=\"center\">提现手续费</TD>   
				<TD align=\"center\">交易手续费</TD>   
				<TD align=\"center\">成功提现时间</TD>   
				<TD align=\"center\">状态</TD>    
				<TD align=\"center\">返回信息</TD> 
				<TD align=\"center\">提交时间</TD> 
				<TD align=\"center\">管理员</TD> 
				</TR>";
		
		$intpage = 20;
		if ( isset( $_GET['page'] ) )
		{
				$rsnum = ( $_GET['page'] - 1 ) * $intpage;
		}
		else
		{
				$rsnum = 0;
		}
		
		$query = $db->query( "Select count(*) as cnt from eqianbao_cashlog" );
		if ( $rs = $db->fetch_array( $query ) )
		{
				$intnum = $rs['cnt'];
		}
		
		$query = $db->query( "Select a.*,b.name as adminname from eqianbao_cashlog a,admin b where a.adminid=b.id Order by a.id desc limit {$rsnum},{$intpage}" );
		while ( $rs = $db->fetch_array( $query ) )
		{
			if($rs['status'] == 1) $rs['status'] = "处理中";
			if($rs['status'] == 2) $rs['status'] = "交易成功";
			if($rs['status'] == 3) $rs['status'] = "交易失败";
			
			if($rs['cashtype'] == 1) $rs['cashtype'] = "微信";
			if($rs['cashtype'] == 2) $rs['cashtype'] = "支付宝";
			if($rs['cashtype'] == 3) $rs['cashtype'] = "QQ钱包";
			
			echo "<TR bgcolor=\"#FFFFFF\">";
			echo "<TD align=\"center\">" . $rs['ordernum'] . "</TD>";
			echo "<TD align=\"center\">" . $rs['cashtype'] . "</TD>";
			echo "<TD align=\"right\">" . $rs['amount'] . "</TD>";
			echo "<TD align=\"right\">" . $rs['fee'] . "</TD>";
			echo "<TD align=\"right\">" . $rs['tradefee'] . "</TD>";
			echo "<TD align=\"center\">" . $rs['cashtime'] . "</TD>";
			echo "<TD align=\"center\">" . $rs['status'] . "</TD>";
			echo "<TD align=\"left\">" . $rs['resmsg'] . "</TD>";
			echo "<TD align=\"center\">" . $rs['addtime'] . "</TD>";
			echo "<TD align=\"center\">" . $rs['adminname'] . "</TD>";
			echo "</TR>";
		}
		echo "<TR align=\"center\" bgcolor=\"#f8fbfb\">
					<TD colspan=\"10\">";
		include_once( dirname( __FILE__ )."/inc/page_class.php" );
		$page = new page( array(
				"total" => $intnum,
				"perpage" => $intpage
		) );
		echo $page->show( 4, "page", "curr" );
		echo "</TD>
				</TR>
				</TBODY>
				</TABLE>";
}

function add( )
{
		echo "<form action=\"?action=sadd\" method=\"post\">
				<TABLE width=\"99%\" border=0 align=center cellpadding=\"4\" cellSpacing=1 class=tbtitle style=\"BACKGROUND: #cad9ea;\">
				<TBODY>
				<TR>
					<TD vAlign=center width=\"20%\" bgColor=#f5fafe>提现类型：</TD>
					<TD bgColor=#ffffff>
						<INPUT type=\"radio\" id=\"cashtype\" value=\"1\" checked name=\"cashtype\" dataType=\"Require\">微信
						<INPUT type=\"radio\" id=\"cashtype\" value=\"2\" name=\"cashtype\" dataType=\"Require\">支付宝
						<INPUT type=\"radio\" id=\"cashtype\" value=\"3\" name=\"cashtype\" dataType=\"Require\">QQ钱包
					</TD>
				</TR>
				<TR align=\"center\" bgcolor=\"#f8fbfb\">
					<TD colspan=\"2\"><INPUT class=\"inputbut\" type=\"submit\" value=\"申请提现\"></TD>
				</TR>
				</TBODY>
				</TABLE>
				</form>
				";
}


echo "<!DOCTYPE HTML>\r\n<HTML>\r\n<HEAD>
		<TITLE>易钱宝提现</TITLE>
		<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">
		<LINK href=\"images/css_body.css\" type=text/css rel=stylesheet>
		<META content=\"MSHTML 6.00.3790.4275\" name=GENERATOR>
		</HEAD>
		<BODY>
		<DIV class=bodytitle>
		<DIV class=bodytitleleft></DIV>
		<DIV class=bodytitletxt>易钱宝提现</DIV>
		<DIV class=bodytitletxt2>
		<a href=\"admin_cash_eqianbao.php?action=add\">申请提现</a></DIV>
		</DIV>";


switch ( $_GET['action'] )
{
case "add" :
		add( );
		break;
case "sadd" :
		$return = sadd( );
		
		echo "<script type='text/javascript'>alert('申请提交成功');history.back();</script>";
		break;
default :
		main( );
}

echo "</BODY>
		</HTML>";

?>


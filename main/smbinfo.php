<?php
  	include_once("inc/conn.php");
    include_once("inc/function.php");
    include_once("inc/agent.php"); 
    $arrRet = array('cmd'=>'','msg'=>'');
    if(!isset($_SESSION['usersid'])) {
    	$arrRet['cmd'] = "notlogin";
    	$arrRet['msg'] = "您还没登录或者链接超时,请先登录!";
		echo json_encode($arrRet);
		exit;
	}
	
	
    if(isset($_REQUEST['act'])){
        if($_REQUEST['act']=="get_back_pwd_step2"){
            Get_Back_Pwd_step2();   //找回安全密码
            exit();
        }
        
        if($_REQUEST['act']=="get_back_pwd"){
            Get_Back_Pwd();   //找回安全密码
            exit();
        }
    }
    
    if(!isset($_SESSION['Login_Confirm'])) {
        Login_Confirm();
        exit;
    } 

        
	switch($_REQUEST['act'])
	{
		
		case "agent_recharge": //代理充值
			Recharge();
			exit();
			break;
		case "agent_experience_card": //回收体验卡
			Experience_Card();
			exit();
			break;
		case "agent_change": //额度转换
			Agent_Change();
			exit();
			break;
		case "agent_statistics": //统计信息
			agent_statistics();
			exit();
			break;
		case "agent_statistics_page": //取代理统计信息分页
			echo GetAgentDayStat();
			exit();
			break;
		case "agent_information": //代理资料
			Information();
			exit();
			break;
		case "agent_withdraw": //代理提现
			agent_withdraw();
			exit();
			break;
		case "agent_log": //代理操作日志
			agent_log();
			exit();
			break;
		case "agent_get_recharge_log": //代理充值日志
			echo Get_Recharge_Log();
			exit();
			break;
		case "agent_get_agent_log": //代理操作记录
			echo Get_agent_Log_List();
			exit();
			break;
		case "agent_get_rexperience_log": //卡回收记录
			echo Get_Exchange_Log();
			exit();
			break;
       
            //break;
        case "rebate":	// 领取返利
            include_once 'rebate.php';
            break;
       
        //代理
        case 'recharge_order_pay':
            include_once 'recharge_order_pay.php';
            exit();
            break;
        case "withdraw_log": //提现记录
            echo Get_withdraw_Log();
            exit();
            break;
            
            
		case "exchangecard": //兑奖点卡
			ExchangeCard();
			break;
		case "exchangelist": //取得兑奖点卡列表
			GetExchangeList();
			break;
		/* case "getjiuji"://取得救济
			GetJiuji();
			break;
		case "jiujilist": //获得救济页面
			GetJiuJiList();
			break; */
            
		case "rewardrank": //获得领取排行奖励页面
			RewardRank();
			break;
            
       
		
        case "binding" :	// 绑定收款账号
        	binding();
        	break;
			
		case "unbindemail": //解绑邮箱
			BindUnBindEmail('unbind');
			break;
		case "bindemail": //绑定邮箱
			BindUnBindEmail('bind');
			break;
		case "unbindmobile": //解绑手机
			UnBindMobile();
			break;
		case "bindmobile": // 绑定手机
			BindMobile();
			break;
		case "sendmobilevalid": // 发送手机验证码
			SendMobileValidCode();
			break;
		case "bindmobileinfo": // 取得绑定/解绑手机信息
			GetBindMobileInfo();
			break;
			
			
			
			
		/*
		case "sendmsgbox": //发送消息列表
			GetSendMsgBox();
			break; 
		case "msgusercontent": //取用户消息内容
			GetUserMessageContent();
			break;*/
		case "msgsyscontent": //取系统消息内容
			GetSysMessageContent();
			break;
		/*case "extencontent": // 推广人数
			extenContent();
			break;*/
		case "messagelist": //消息
			GetMessageList();
			break;
		case "get_press_log": //投注列表
			GetPressLogList();
			break;
		case "changebankpwd": //修改银行密码
			ChangePwd("bank");
			break;
		case "changeloginpwd": //修改登录密码
			ChangePwd("login");
			break;  
		case "changpwddetail": //取得密码修改页面
			GetChangePwdDetail();
			break;
		case "actionlog": //操作记录
			GetActionLog();
			break;
		case "transscore": // 转账
			TransScore();
			break;
		case "checktargetid": //检测对方ID
			CheckTargetID();
			break;
		case "paycard": // 充值体验卡
			PayCards();
			break;
		case "paycarddetail": // 体验卡记录
			GetPayCardDetail();
			break;
		case "scoredetail": //取得乐豆明细
			GetScoreDetail();
			break;
		/* case "transaction" : // 交易记录
			transaction();
			break; */
			
			
		case "scoredetail": //取得乐豆明细
			GetScoreDetail();
			break;
		case "getscore": //取豆
			ProcessScore("get");
			break;
		case "savescore": //存豆
			ProcessScore("save");
			break;
		case "getallscore":
			GetAllScore();
			break;
		case "mybank": //我的银行
			GetUserBankInfo();
			break;
		case "changedetail": //修改资料
			ChangeDetail();
			break;
		case "mydetail": //我的资料
			GetMyDetail();
			break;
		case "baseinfo": //基本信息
			GetMyDetail();
			break;
		case "Withdrawals" :	// 提现
			Withdrawals();
			break;
        case "user_exchange_log": //我的点卡分页
            echo Get_card_Log();
            exit();
            break;
        /*case "recommend_earnings": //推广收益
            echo Recommend_Earnings();
            exit();
            break;
        case "agent_recommend_tjid": //推广用户
            echo Get_Recommend_Users_List();
            exit();
            break;
         case "red_bag": //我的红包
            echo Send_Red();
            exit();
            break;
         case "get_my_red_bag": //我发的红包记录
            echo Get_My_Send_Red_Bag();
            exit();
            break;

         case "show_get_red_bag": //抢得红包用户
            echo show_get_red_bag_user();
            exit();
            break; 
         case "my_receive_red_bag": //我收到的红包
            echo my_receive_red_bag();
            exit();
            break; 
         case "my_send_red_bag": //我发的红包
            echo my_send_red_bag();
            exit();
            break;   */
		default:
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "无效的命令!";
			echo json_encode($arrRet);
			exit;
	}


  /* 我发的红包*/
function my_send_red_bag(){
    global $db; 
    global $web_url ;
    $usersid=$_SESSION['usersid'];
    $arrRet = array('cmd'=>'','msg'=>'');  
    if(!isset($_SESSION['usersid'])) {
        $arrRet['cmd'] = "err";
        $arrRet['msg'] = "登录超时!";
        echo json_encode($arrRet);
        exit();
    }
    $jq='$';   
    $RetContent="
    <script type='text/javascript' src='/images/fancybox/jquery.mousewheel-3.0.4.pack.js'></script>
    <script type='text/javascript' src='/images/fancybox/jquery.fancybox-1.3.4.pack.js'></script>
    <script type='text/javascript' src='js/agent.js'></script>
    <link rel='stylesheet' type='text/css' href='/images/fancybox/jquery.fancybox-1.3.4.css' media='screen' />
    <script type='text/javascript' >
        {$jq}(document).ready(function() {
            {$jq}('.show_red_bag').fancybox({
                type        : 'iframe',
                fitToView    : false,
                width        :700,
                height        : '100%',
                autoSize    : true,
                closeClick    : false,
                openEffect    : 'none',
                closeEffect    : 'none',
                autoDimensions:false
            }); 
  
        });
        
     </script> 
        <div class='message Bank  recharge'>
        <div class='r_nav'>
            <img src='img/banner.png' />
            <p class='title'>发红包</p>
            <p class='cen'>发起红包,收获红包查询</p>
        </div>
        <div class='r_list_t'>
            <ul>
                <li ><a href=\"javascript:getContent('smbinfo.php','red_bag')\">发起红包</a></li>
                <li ><a href=\"javascript:getContent('smbinfo.php','my_receive_red_bag')\">我收到的红包</a></li>
                <li class='pitch'><a href=\"javascript:getContent('smbinfo.php','my_send_red_bag')\">我发的红包</a></li>
            </ul>
        </div>
            <div class='m_list'>
  
            <div class='list redbag my_red_bag_list'>
                    <table class='table_list ' cellspacing='0px' style='border-collapse:collapse;'>
                        <tr height='30'>
                             <th>红包标题</th>
                             <th>总额</th>
                             <th>数量</th>
                             <th>已领数量</th>
                             <th>已领额</th>
                             <th>红包类型</th>
                             <th>作废时间</th>
                             <th>生成时间</th>
                             <th>操作</th>
                        </tr>
                        ".Get_My_Send_Red_Bag()."
                    </table>
              </div>
             </div>
    </div>";
    echo $RetContent;
}
    

  /* 我收到的红包*/
function my_receive_red_bag(){
    global $db; 
    global $web_url ;
    $usersid=$_SESSION['usersid'];
    $arrRet = array('cmd'=>'','msg'=>'');  
    if(!isset($_SESSION['usersid'])) {
        $arrRet['cmd'] = "err";
        $arrRet['msg'] = "登录超时!";
        echo json_encode($arrRet);
        exit();
    }
    $jq='$';   
    $RetContent="
    <script type='text/javascript' src='js/agent.js'></script>
        <div class='message  Bank recharge'>
        <div class='r_nav'>
            <img src='img/banner.png' />
            <p class='title'>发红包</p>
            <p class='cen'>发起红包,收获红包查询</p>
        </div>
        <div class='r_list_t'>
            <ul>
                <li ><a href=\"javascript:getContent('smbinfo.php','red_bag')\">发起红包</a></li>
                <li class='pitch'><a href=\"javascript:getContent('smbinfo.php','my_receive_red_bag')\">我收到的红包</a></li>
                <li ><a href=\"javascript:getContent('smbinfo.php','my_send_red_bag')\">我发的红包</a></li>
            </ul>
        </div>
        <div class='m_list'>  
            <div class='list redbag recharge_log_list'>
                <table class='table_list ' cellspacing='0px' style='border-collapse:collapse;'>
                    <tr height='30'>
                        <th  style='130px  !important;'  align='left'>红包名</th>
                        <th>抢得时间</th>    
                        <th width=70  align='left'>金额</th>
                        <th >红包类型</th> 
                        <th >发起人</th>
                        <th align='left'  >留言</th>
                    </tr>
                    ".Get_My_recv_Red_Bag()."
                </table>
            </div>
        </div>
    </div>";
    echo $RetContent;
}
    

/* 查询获得红包用户
    *
    */
    function show_get_red_bag_user()
    {
        global $db;
        $bagid = isset($_GET['bagid'])?str_check($_GET['bagid']):"";
        if(empty($bagid)) {
            echo "红包不存在";
            exit();
        }
        if(!isset($_SESSION['usersid'])) {
            echo "<script >alert('未登录或登录超时!');
                    window.location = '/login.php';
                </script>";
            exit();
        }
        $data_tr="
            <tr height='30'>
                    <th  >领取用户</th>
                    <th  >抢得金额</th>
                    <th >抢得时间</th>
                    <th >用户留言</th>
            </tr>
        ";
        $usersid=$_SESSION['usersid'];
        $sql = "SELECT  recv_time,recv_msg,r.points,nickname,u.id
            FROM redbag_recv_log r
            LEFT JOIN  redbag_send_log s on(r.bagno=s.bagno)
            LEFT JOIN users u on (u.id=r.recv_uid)
            WHERE   send_uid='{$usersid}' and r.bagno='{$bagid}' and r.state=1
            ";
        $query = $db->query($sql);
        $reward=array();
        while($rs=$db->fetch_array($query)){
            $rs['nickname']=ChangeEncodeG2U($rs['nickname']);
            $rs['recv_msg']=ChangeEncodeG2U($rs['recv_msg']);
            $rs['nickname']=$rs['nickname']."(".$rs['id'].")";
            $rs['points']=number_format($rs['points'])."(￥".number_format(($rs['points']/1000),2).")";
            $data_tr .="
                <tr  >
                    <td align='left'>".$rs["nickname"]."</td>
                    <td align='left'>".$rs["points"]."</td>
                    <td align='left'>".$rs["recv_time"]."</td>
                    <td align='left'>".$rs["recv_msg"]."</td>
                </tr>
            ";
        }
        $RetContent = "
        <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
        <html xmlns='http://www.w3.org/1999/xhtml'>
        <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <meta http-equiv='x-ua-compatible' content='IE=EmulateIE7'>
        <link type='image/x-icon' rel='shortcut icon' href='../images/favicon.ico' />
        <title>滴滴网</title>
        <link rel='stylesheet' type='text/css' href='style/member.css' />
        <link rel='stylesheet' type='text/css' href='style/com.css' />
        </head> 
            <body>
               
                    <div class='message'>
                        <div class='r_nav'>
                            <img src='img/banner.png' />
                            <p class='title'>抢得红包用户</p>
                            <p class='cen'>查看抢得红包用户</p>
                        </div>
                        <div class='m_list'>
                            <div class='m_val'>
                                <span class='title'>抢得红包用户</span>        </div>
                            <div class='list'>
                             <table class='table_list' cellspacing='0px' style='border-collapse:collapse;'>
                            ".$data_tr."
                             </table>
                            </div>
                        </div>    
                    </div>
               
            </body>
        </html>
        ";
        echo $RetContent;
        exit;
    }
    
    
 
/* 我的红包*/
function Send_Red(){
    global $db; 
    global $web_url ;
    $usersid=$_SESSION['usersid'];
    $arrRet = array('cmd'=>'','msg'=>'');  
    if(!isset($_SESSION['usersid'])) {
        $arrRet['cmd'] = "err";
        $arrRet['msg'] = "登录超时!";
        echo json_encode($arrRet);
        exit();
    }
    $sql = "SELECT   back as total 
    FROM users   
    WHERE id={$usersid}";
    $query = $db->query($sql);
    $agent=array();
    $total_rmb=$total=0;
    while($rs=$db->fetch_array($query)){
        $total=number_format($rs["total"]);
        $total_rmb=number_format($rs["total"]/1000,2);
    }

    $jq='$';   
    $RetContent="
    <script type='text/javascript' src='js/jquery.zclip.js'></script>
     <script type='text/javascript' >
        var web_url='".$web_url."';
     </script>
    <script type='text/javascript' src='js/agent.js'></script>
        <div class='message  Bank  recharge'>
        <div class='r_nav'>
            <img src='img/banner.png' />
            <p class='title'>发红包</p>
            <p class='cen'>发起红包,收获红包查询</p>
        </div>
        <div class='r_list_t'>
            <ul>
                <li class='pitch'><a href=\"javascript:getContent('smbinfo.php','red_bag')\">发起红包</a></li>
                <li ><a href=\"javascript:getContent('smbinfo.php','my_receive_red_bag')\">收到红包记录</a></li>
                <li ><a href=\"javascript:getContent('smbinfo.php','my_send_red_bag')\">我发的红包</a></li>
            </ul>
        </div>
            <div class='m_list'>
            <div class='list redbag'>
                    <table class='table_list' cellspacing='0px' style='border-collapse:collapse;'>
                        <tr>
                           <td  >当前银行余额： </td>
                           <td  >".$total."(￥".$total_rmb.")  </td>
                        </tr>
                        <tr class='radio_tr'>
                            <td>红包方式:</td>
                            <td align='left'>
                            <div class='radio_but'>
                                <ul>
                                <li class='radio_li'><input name='type' type='radio'   id='radio_type1' value=0 checked='checked' ></li>
                                <li><label for='radio_type1'>普通红包</label></li>
                                <li class='radio_li'><input type='radio' name='type' id='radio_type2' value=1  ></li>
                                <li><label for='radio_type2'>手气红包</label></li>
                            </div>
                        </td>
                        </tr>
                        <tr>
                            <td id='red_money_name'>每个红包金额:</td>
                            <td align='left'><input name='red_bag_money'  maxlength='8' id='red_bag_money' type='text'> 元 &nbsp;<span id='red_bag_money_ms'></span></td>
                        </tr>
                        <tr>
                            <td>发起红包数量:</td>
                            <td align='left'><input name='red_bag_num'  maxlength='8' id='red_bag_num' type='text'> &nbsp;<span id='red_bag_num_ms'></span></td>
                        </tr>
                        <tr>
                            <td>红包标题:</td>
                            <td align='left'>
                            <input name='red_bag_title'  maxlength='30' id='red_bag_title' type='text'></td>
                        </tr>
                        <tr >
                            <td colspan='2'  class='but_td'><input  id='send_bag_but' type='button' class='btn-1' value='生成红包'></td>
                        </tr>
                        <tr class='copytoclip_tr' >
                            <td colspan='2' class='textarea_td' >
                            </td>
                        </tr>
                    </table>
                    <div class='copytoclip_div'></div>
             </div>
        </div>
    </div>";
    echo $RetContent;
}
    


//我收到的红包
function Get_My_recv_Red_Bag()
{
    global $db;
    $page = isset($_POST['page'])?intval($_POST['page']):1;
    if(!isset($_SESSION['usersid'])) {
        $arrRet['cmd'] = "timeout";
        return $arrRet['cmd'];
    }
    $userid =$_SESSION['usersid'];
    $pagesize = 15;
    $RetContent="";
    //表内容
    $sql = "select   count(1)
    from  redbag_recv_log r
    LEFT JOIN redbag_send_log s on(r.bagno=s.bagno)
    LEFT JOIN users u  on(u.id=s.send_uid)
    where  r.recv_uid='{$userid}' and r.state=1 
    ";

    $TotalRecCount = $db->GetRecordCount($sql);
    
    $sql = "select  recv_time,recv_msg,r.points,nickname,u.id,bagtype,send_title
    from  redbag_recv_log r
    LEFT JOIN redbag_send_log s on(r.bagno=s.bagno)
    LEFT JOIN users u  on(u.id=s.send_uid)
    where  r.recv_uid='{$userid}' and r.state=1 
    ";
    $sql = "select  recv_time,recv_msg,r.points,nickname,u.id,bagtype,send_title
    from  redbag_recv_log r
    LEFT JOIN redbag_send_log s on(r.bagno=s.bagno)
    LEFT JOIN users u  on(u.id=s.send_uid)
    where  r.recv_uid='318360' and r.state=1 
    ";
   
    $sql=$sql." order by r.id desc ";
    $sql .= GetLimit($page,$pagesize);
    $result =  $db->query($sql);
    while($rs=$db->fetch_array($result)){
        $rs['send_title']=ChangeEncodeG2U($rs['send_title']);
        $rs['recv_msg']=ChangeEncodeG2U($rs['recv_msg']);
        $rs['nickname']=ChangeEncodeG2U($rs['nickname'])."(".$rs['id'].")";
        if($rs['bagtype']==0){
            $rs['bagtype']='普通红包';
        }else{
            $rs['bagtype']='手气红包';
        }
        $rs['points']= number_format($rs['points'])."(￥".number_format($rs['points']/1000,2).")";
 
        $RetContent .= "
            <tr>
                <td style='width:160px!important;'>{$rs['send_title']}</td>
                <td style='width:120px!important;'>{$rs['recv_time']}</td> 
                <td>{$rs['points']}</td>
                <td  >{$rs['bagtype']}</td>
                <td  >{$rs['nickname']}</td>
                <td>{$rs['recv_msg']}</td>
          </tr>";
    }

    //分页
    if($TotalRecCount > $pagesize)
    {
        $divPage = "<div class='pagediv'></div><div class='Paging'>\r\n";
        require_once('inc/fenye.php');
        $ajaxpage_1=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesize,'ajax'=>"ajax_page_my_send_red_bag",'nowindex' => $page));
        $divPage .= $ajaxpage_1->show();
        $divPage .= "</div>\r\n";
        $RetContent .= "<tr><td colspan=6>".$divPage."</td></tr>";
    }
    return $RetContent;
}

//我发的红包
function Get_My_Send_Red_Bag()
{
    global $db;
    $page = isset($_POST['page'])?intval($_POST['page']):1;
    if(!isset($_SESSION['usersid'])) {
        $arrRet['cmd'] = "timeout";
        return $arrRet['cmd'];
    }
    $userid =$_SESSION['usersid'];
    $pagesize = 15;
    $RetContent="";
    //表内容
    $sql = "select count(*) 
    from redbag_send_log
    where  send_uid='{$userid}'";
    $TotalRecCount = $db->GetRecordCount($sql);
    $sql = "select  bagno,bagtype,send_time,send_title,points,send_cnt,had_recv_points,end_time,state,had_recv_cnt
    from  redbag_send_log
    where  send_uid='{$userid}'";
    $sql=$sql." order by bagid desc ";
    $sql .= GetLimit($page,$pagesize); 
    $result =  $db->query($sql);
    while($rs=$db->fetch_array($result)){
        $rs['send_title']=ChangeEncodeG2U($rs['send_title']);
        if($rs['bagtype']==0){
            $rs['bagtype']='普通红包';
        }else{
            $rs['bagtype']='手气红包';
        }
        $rs['points']= number_format($rs['points'])."(￥".number_format($rs['points']/1000,2).")";
        $rs['had_recv_points']= number_format($rs['had_recv_points']);
        $show="";
        if($rs['had_recv_cnt']>0){
            $show="<a class='show_red_bag' href='smbinfo.php?act=show_get_red_bag&bagid=".$rs['bagno']."'>查看</a>";
           
        }
        $RetContent .= "
            <tr>
                <td style='width:160px!important;'>{$rs['send_title']}</td>
                <td>{$rs['points']}</td>
                <td style='width:20px!important;'>{$rs['send_cnt']}</td>
                <td style='width:50px!important;'>{$rs['had_recv_cnt']}</td>
                <td style='width:50px!important;'>{$rs['had_recv_points']}</td>
                <td style='width:50px!important;'>{$rs['bagtype']}</td>
                <td>{$rs['end_time']}</td>
                <td>{$rs['send_time']}</td>
                <td style='width:30px!important;'>{$show}</td>
          </tr>";
    }

    //分页
    if($TotalRecCount > $pagesize)
    {
        $divPage = "<div class='pagediv'></div><div class='Paging'>\r\n";
        require_once('inc/fenye.php');
        $ajaxpage=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesize,'ajax'=>"ajax_page_my_send_red_bag",'nowindex' => $page));
        $divPage .= $ajaxpage->show();
        $divPage .= "</div>\r\n";
        $RetContent .= "<tr><td colspan=9>".$divPage."</td></tr>";
    }
    return $RetContent;
}
 
    
/* 推广收益
    *
    */
    function Recommend_Earnings()
    {
    	
/*    	
        global $db;
        $usersid=$_SESSION['usersid'];
        $arrRet = array('cmd'=>'','msg'=>'');  
        if(!isset($_SESSION['usersid'])) {
            $arrRet['cmd'] = "err";
            $arrRet['msg'] = "登录超时!";
            echo json_encode($arrRet);
            exit();
        }
        
        $sql = "SELECT tj_next_leve1_points,tj_next_leve2_points,tj_next_leve3_points,last_uid,last_paytime,nickname
            FROM user_tj_reward t
            LEFT JOIN users u on (t.last_uid=u.id)
            WHERE  t.uid={$usersid}
            limit 1";
        $query = $db->query($sql);
        $reward=array();
        while($rs=$db->fetch_array($query)){
            $reward=$rs;
        }        
        $reward_str="<div  align='center'>你没有推广收益</div>";
        if(!empty($reward)){
            $reward['nickname']=ChangeEncodeG2U($reward['nickname']);
            $reward['nickname']=$reward['nickname']."(".$reward['last_uid'].")";
            $reward_str="
                <tr height='30'>
                    <th align='left'>1层总收益</th>
                    <th align='left'>2层总收益</th>
                    <th align='left'>3层总收益</th>
                    <th align='left'>最后充值用户</th>
                    <th align='left'>最后充值时间</th>
                </tr>
                <tr  >
                    <td align='left'>".$reward["tj_next_leve1_points"]."</td>
                    <th align='left'>".$reward["tj_next_leve2_points"]."</th>
                    <th align='left'>".$reward["tj_next_leve3_points"]."</th>
                    <th align='left'>".$reward["nickname"]."</th>
                    <th align='left'>".$reward["last_paytime"]."</th>
                </tr>
                ";
        }
        $RetContent = "
            <script type='text/javascript' src='js/agent.js'></script>
            <div class='message'>
                <div class='r_nav'>
                    <img src='img/banner.png' />
                    <p class='title'>推广收益</p>
                    <p class='cen'>查看推广收益，查询推荐的用户</p>
                </div>
                <div class='m_list'>
                    <div class='m_val'>
                        <span class='title'>收益统计</span>        </div>
                    <div class='list'>
                    ".$reward_str."
                </div>
            <div class='m_list recommend_list'>
                <div class='m_val'>
                    <span class='title'>推荐用户</span>        </div>
                <div class='list '>
                <table class='table_list ' cellspacing='0px' style='border-collapse:collapse;'>
                <tr height='30'>
                        <th align='left'>推广用户</th>
                        <th align='left'>推荐1层人数</th>
                        <th align='left'>推荐2层人数</th>
                        <th align='left'>推荐3层人数</th>
                    </tr>
                </div>
                ".Get_Recommend_Users_List()."
            </div>
        </div>";
*/
        $RetContent = "<script type='text/javascript' src='js/agent.js'></script>";
        $RetContent .= "<div class=\"panel panel-default\">";
		$RetContent .= "<div class=\"panel-heading\">收益统计</div>";
		$RetContent .= "<div class=\"panel-body\">";
		$RetContent .= "<table class='table table-striped table-hover table-bordered'>";
		$RetContent .= "<tr>";
		$RetContent .= "<th>用户</th>";
		$RetContent .= "<th>时间</th>";
		$RetContent .= "<th>收益额</th>";
		$RetContent .= "<th>备注</th>";
		$RetContent .= "</tr>";
		$RetContent .= "<tr>";
		$RetContent .= "<td colspan='4'>暂无任何数据!</td>";
		$RetContent .= "</tr>";
		$RetContent .= "</table>";
		$RetContent .= "</div>";
		$RetContent .= "</div>";
		
		$RetContent .= "<div class=\"panel panel-default\">";
		$RetContent .= "<div class=\"panel-heading\">推荐用户</div>";
		$RetContent .= "<div class=\"panel-body\">";
		$RetContent .= "<table class='table table-striped table-hover table-bordered'>";
		$RetContent .= "<tr>";
		$RetContent .= "<th>推荐人</th>";
		$RetContent .= "<th>一级推荐人数(1~10)/收益率</th>";
		$RetContent .= "<th>二级推荐人数(11~30)/收益率</th>";
		$RetContent .= "<th>三级推荐人数(~)/收益率</th>";
		$RetContent .= "</tr>";
		$RetContent .= "<tr>";
		$RetContent .= "<td>xuhui</td>";
		$RetContent .= "<td><a href=\"javascript:openrecord('2',600,200,'smbinfo.php?act=msgsyscontent&id=2');\">10</a> / 0.2%</td>";
		$RetContent .= "<td>12 / 0.3%</td>";
		$RetContent .= "<td>20 / 0.5%</td>";
		$RetContent .= "</tr>";
		$RetContent .= "</table>";
		$RetContent .= "</div>";
		$RetContent .= "</div>";
        echo $RetContent;
        exit;
    }

// 付款扫描    
function recharge_order_pay(){
	$RetContent .= "<div class='panel panel-default'>";
	$RetContent .= "<div class='panel-heading'>客服直充</div>";
	$RetContent .= "<div class='panel-body'>";
	$RetContent .= "<p style='font-size:18px;'><strong>充值流程:</strong>　<strong>1.创建订单　>></strong>　<strong style='color:#f00;'>2.扫描付款</strong></p>";
	$RetContent .= "<div class='pay_money'>";
	$RetContent .= "<table class='table table-striped table-hover table-bordered'>";
	$RetContent .= "<tr>";
	$RetContent .= "<td>付款方式：</td>";
	$RetContent .= "<td>支付宝</td>";
	$RetContent .= "<td rowspan='7'><img src='img/taobao_lby.png' style='width:300px; height:300px; padding:0; margin:0;'/></td>";
	$RetContent .= "</tr>";
	$RetContent .= "<tr>";
	$RetContent .= "<td>滴滴收款账号：</td>";
	$RetContent .= "<td>ozc88@qq.com</td>";
	$RetContent .= "</tr>";	
	$RetContent .= "<tr>";
	$RetContent .= "<td>收款人姓名：</td>";
	$RetContent .= "<td>林碧亚</td>";
	$RetContent .= "</tr>";
	$RetContent .= "<tr>";
	$RetContent .= "<td>支付备注：</td>";
	$RetContent .= "<td> 9ca661 </td>";
	$RetContent .= "</tr>";	
	$RetContent .= "<tr>";
	$RetContent .= "<td>充值金额：</td>";
	$RetContent .= "<td>10 ￥</td>";
	$RetContent .= "</tr>";	
	$RetContent .= "<tr>";
	$RetContent .= "<td>充值订单号：</td>";
	$RetContent .= "<td>9ca6619de991f998</td>";
	$RetContent .= "</tr>";
	$RetContent .= "<tr>";
	$RetContent .= "<td>订单状态：</td>";
	$RetContent .= "<td>未完成</td>";
	$RetContent .= "</tr>";	
	$RetContent .= "<tr>";
	$RetContent .= "<td>付款人姓名:</td>";
	$RetContent .= "<td colspan='2'>小先生</td>";
	$RetContent .= "</tr>";
	$RetContent .= "<tr>";
	$RetContent .= "<td>付款帐号:</td>";
	$RetContent .= "<td colspan='2'>123456</td>";
	$RetContent .= "</tr>";
	$RetContent .= "<tr>";
	$RetContent .= "<td colspan='3'><a href=\"#\" class='btn btn-danger btn-block;' >刷新订单状态</td>";
	$RetContent .= "</tr>";	
	$RetContent .= "</table>";
	$RetContent .= "</div>";
	$RetContent .= "</div>";
	$RetContent .= "</div>";
	echo $RetContent;
	exit;	
}    
    
    
    

//推荐用户
function Get_Recommend_Users_List()
    {
        global $db;
        $page = isset($_POST['page'])?intval($_POST['page']):1;
        $userid = isset($_POST['userid'])?str_check($_POST['userid']):0;
        $page =intval($page);
        if(!isset($_SESSION['usersid'])) {
            $arrRet['cmd'] = "timeout";
            return $arrRet['cmd'];
        }
        if(empty($userid)){
            $userid=$_SESSION['usersid'];
        }

        $pagesize = 15;
        $RetContent="";
        //表内容
        $sql = "select count(*) 
        from users  
        where   tjid = '{$userid}' ";
        $TotalRecCount = $db->GetRecordCount($sql);
        $sql = "select nickname,id,tj_level1_count,tj_level2_count,tj_level3_count
        from users   
        where  tjid = '{$userid}' ";
        $sql=$sql." order by tj_level1_count desc ";
        $sql .= GetLimit($page,$pagesize); 

        if($TotalRecCount>0){
            $result =  $db->query($sql);
            while($rs=$db->fetch_array($result)){
                $rs['nickname']=ChangeEncodeG2U($rs['nickname']);
                $rs['nickname']=$rs['nickname']."(".$rs['id'].")";
                if($rs['tj_level1_count']>0){
                    $rs['nickname']=" <a onClick='get_recommend_list(".$rs['id'].");' href='javascript:void(0)' >".$rs['nickname']."</a>";
                }
                $RetContent .= "
                    <tr>
                        <td style='width:160px!important;'>{$rs['nickname']}</td>
                        <td>{$rs['tj_level1_count']}</td>
                        <td>{$rs['tj_level2_count']}</td>
                        <td>{$rs['tj_level3_count']}</td>
                    </tr>";
            }
        }else{
            $RetContent .= "
                    <tr>
                        <td  colspan=4> <div  align='center'>没有数据</div></td>
                    </tr>";
        }

        //分页
        if($TotalRecCount > $pagesize)
        {
            $divPage = "<div class='pagediv'></div><div class='Paging'>\r\n";
            require_once('inc/fenye.php');
            $ajaxpage=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesize,'ajax'=>"ajax_page_recommend_list",'nowindex' => $page));
            $divPage .= $ajaxpage->show();
            $divPage .= "</div>\r\n";
            $RetContent .= "<tr><td colspan=4>".$divPage."</td></tr>";
        }
        return $RetContent;
    }

    
    function Get_Back_Pwd_step2()
    { 
        echo "<script type='text/javascript' src='js/get_back_pwd.js'></script>
                <div class='Bank'>
                    <div class='r_nav'>
                        <!--<img src='img/banner.png' />-->
                        <p class='title'>修改安全密码</p>
                    </div>     
                    <div class='Money'>
                        <ul>
                            <li></li>
                            <li><span>安全密码:</span><input type='password' id='pass' class='form-control' maxlength='20' placeholder='请输入新密码' /></li>
                            <li><span>确认密码:</span><input type='password' id='pass1' class='form-control' maxlength='20' placeholder='请再次输入新密码' /></li>
                            <li class='text'><span> </span><button class='btn btn-danger' id='safe_pwd_but'>下一步</button></li>
                        </ul>
                    </div>
                </div>";
        
    }
	function Get_Back_Pwd()
	{ 
		/*
        echo "<script type='text/javascript' src='js/get_back_pwd.js'></script>
            <div class='Bank'>
            <div class='r_nav'>
                <img src='img/banner.png' />
                <p class='title'>找回安全密码</p>
                <p class='cen'>通过绑定手机短信验证码找回安全密码</p>
            </div>     
            <div class='Money'>
                <ul>
                    <li></li>
                    <li><span></span><input type='button' id='get_check_sms_code'  value='获取手机短信验证码'   /></li>
                    <li><span>短信验证码:</span><input type='text' id='sms_code' maxlength='6'/></li>
                    <li class='text'><span> </span><input type='button' id='back_pwd_but' class='btn-1' value='下一步' /></li>
                </ul>
            </div>
        </div>";
      */  
	 echo "<script type='text/javascript' src='js/get_back_pwd.js'></script>
	 	<div class='panel panel-default'>
	 		<div class='panel-heading'>找回安全密码(通过绑定手机短信验证码找回安全密码)</div>
	 		<div class='panel-body'>
	 			<div class='input-group'>
	 				<span class='input-group-addon'>短信验证码:</span>
	 				<input type='text' class='form-control' id='sms_code' maxlength='6' placeholder='请输入短信验证码' />
	 				<a href='#' class='input-group-addon' id='get_check_sms_code'>发送短信</a>
	 			</div>
	 			<p style='text-align:center; padding:10px 0 0 0;'><button class='btn btn-danger' id='back_pwd_but'>下一步</button></p>	
	 		</div>
	 	</div>";
	}
	function Login_Confirm()
	{ 
        $text='
        		<dt>需要确认登录(安全密码)</dt>
                <dd>
                    <div class="input-group">
                        <span class="input-group-addon">安全密码</span>
                        <input type="password" id="pass" class="form-control" t="安全密码" maxlength="20" placeholder="请输入安全密码" />
                        <span class="input-group-addon">*默认跟登录密码一样</span>
                    </div>
                </dd>
                <dd>
                    <span><a href="javascript:getContent(\'smbinfo.php\',\'get_back_pwd\')">忘记安全密码</a></span>
                </dd>
                ';
        if (isset($_SESSION['isagent'])){ 
             $text ='
             <dt>需要确认登录(安全密码)</dt>
             <dd>
                <div class="input-group">
                    <span class="input-group-addon">安全密码</span>
                    <input type="password" id="pass" t="安全密码" class="form-control" maxlength="20" placeholder="请输入安全密码" />
                    <span class="input-group-addon">*默认跟登录密码一样</span>
                </div>
            </dd>
                <dd>
                    <span><a href="javascript:getContent(\'smbinfo.php\',\'get_back_pwd\')">忘记安全密码</a></span>
                </dd>
             		';
        }
        
		echo '
				<div class="com">
				<dl class="text">
                    '.$text.'
					<dd><input type="button" id="login" class="btn btn-danger btn-block" value="确 认"></dd>
				</dl>
			</div>
			<script language="javascript">
			$(document).ready(function(){
				$("#pass").keydown(function(e){
					if(e.keyCode  == 13){
						$("#login").click();
					}		
				});
                var setInterval_time=0;
                var timer_sms ;
                function checkTime()    
                {    
                    setInterval_time=setInterval_time+1;
                    var s=100-setInterval_time;
                    $("#agent_send_sms").val(" "+s+"秒后再次获取验证码 ");
                    if(setInterval_time>100){
                        clearInterval(timer_sms);
                        $("#agent_send_sms").attr("disabled",false);
                        $("#agent_send_sms").val(" 获取验证码 ");
                    }    
                }


                $("#agent_send_sms").click(function(){
                    var get_type= $("input[name=\"get_type\"]:checked").val();
                    setInterval_time=0;
                    $.ajax({ 
                        type: "post", 
                        url: "ajax.php?action=send_agent_login_code", 
                        dataType: "json", 
                        data:{type:get_type},
                        success: function (data) { 
                            alert(data.msg);
                            if(data.cmd=="ok"){
                                $("#agent_send_sms").attr("disabled",true);
                                $("#agent_send_sms").css("background-color","#989795");
                                $("#agent_send_sms").css("color","#FFF");
                                $("#agent_send_sms").css("width","160px");
                                timer_sms= window.setInterval("checkTime()",1000);
                            }
                        }, 
                        error: function (XMLHttpRequest, textStatus, errorThrown) { 
                                alert("检验失败,未知错误!");
                        } 
                    });
                });

				$("#login").click(function(){
					var pass = $("#pass").val();
					if(pass == "")
					{
						alert("请输入"+$("#pass").attr("t")+"!");
						return false;
					}
					$.ajax({
						type: "get", 
						url: "ajax.php?action=login_confirm", 
                    	timeout : 5000,
						dataType: "json",
                        data:{pass:pass},
						success: function (data) { 
							if(data.cmd=="ok"){
								getContent(\'smbinfo.php\',\''.$_REQUEST['act'].'\');
							}else{
                                alert(data.msg);
                            }
                            
						}, 
						error: function (XMLHttpRequest, textStatus, errorThrown) { 
								alert("检验失败,未知错误!");
						}
					});
					return false;
				});
			});
		</script>';
	}
	
function Withdrawals(){
	@include_once 'withdrawals.php';
        exit;
}
	
// 绑定收款账号

function binding(){
    @include_once 'binding.php';  
    exit;   
}





	
	/* 取得在线充值页面
	*
	*/
	function GetOnlinePayInfo()
	{ 
        $RetContent .= "<script type='text/javascript' src='js/agent.js'></script>";
        $RetContent .= "<script type='text/javascript'>
       $(document).ready(function(){
            $('#pay_web').change(function() {
                var t=$('#remark_'+$(this).val()).html();
                    $('#pay_account_msg').text(t);
            });
                //领取返利奖励
                $('#create_order').click(function(){
                    var payer= $('#payer').val();
                    var pay_web= $('#pay_web').val();
                    var pay_account= $('#pay_account').val();
                    var money = $('#money').val();
                    if(money!=''){
                        if(isNaN(money ) || money <1){
                            alert('请输入正确充值金额！');
                            return;
                        }
                    }
                    
                    if(money <10){
                        alert('金额过小，10元充！');
                        return;
                    }
                    if(payer=='' || pay_account==''){
                        alert('为了方便客服对帐，付款人姓名与付款帐号必须填写项!');
                        return;
                    }
                    $.ajax({
                        type: 'post', 
                        url: 'ajax.php?action=add_recharge_order', 
                        data:{payer:payer,pay_web:pay_web,pay_account:pay_account,money:money},
                        dataType: 'json',
                        success: function (data) { 
                            if(data.cmd=='timeout' || data.cmd=='notlogin'){
                                alert('你登录超时!');
                                window.location = '/login.php';
                                return;
                            }
                            if(data.cmd=='ok'){
                                getContent('smbinfo.php?orderid='+data.orderid,'recharge_order_pay');
                            }else{
                                alert(data.msg);
                            }
                           
                        }, 
                        error: function (XMLHttpRequest, textStatus, errorThrown) { 
                                alert('检验失败,未知错误!');
                        } 
                    });
                });
            });
        </script>";
        $RetContent .= "<div class='panel panel-default'>";
        $RetContent .= "<div class='panel-heading'>创建充值订单</div>";
        $RetContent .= "<div class='panel-body'>";
//        $RetContent .= "<p><img src=\"img/buzou.png\" /></p>";
        $RetContent .= "<p style='font-size:18px;'><strong>充值流程:</strong>　<strong style='color:#f00;'>1.创建订单　>></strong>　<strong>2.扫描付款</strong></p>";
        $RetContent .= "<table class='table table-striped table-bordered table-hover'>";
        $RetContent .= "<tr>";
        $RetContent .= "<td colspan='2'>输入你的充值金额和付款帐号创建支付订单！ </td>";
        $RetContent .= "</tr>";
        $RetContent .= "<tr>";
        $RetContent .= "<td colspan='2'>
            <div class=\"input-group\">
                <span class='input-group-addon'>充值金额:　</span>
                <select class='form-control' id='money'>
                    <option value='10'>10元</option>
                    <option value='100'>100元</option>
                    <option value='200'>200元</option>
                    <option value='500'>500元</option>
                    <option value='1000'>1000元</option>
                    <option value='5000'>5000元</option>
                    <option value='10000'>10000元</option>
                </select>
            </div>
        </td>";
        $RetContent .= "</tr>";
        $RetContent .= "<tr>";
        $RetContent .= "<td>
            <div class=\"input-group\">
                <span class='input-group-addon'>付款方式:　</span>
                <select class='form-control' id=\"pay_web\" name=\"pay_web\">
                    <option value=\"1\">支付宝</option>
                    <option value=\"2\">微信</option>
                    <option value=\"4\">中国工商银行</option>
                    <option value=\"5\">中国农业银行</option>
                    <option value=\"6\">中国建设银行</option>
                </select>
            </div>
        </td>";
        $RetContent .= "</tr>";   
        $RetContent .= "<tr>";
        $RetContent .= "<td>
            <div class=\"input-group\">
                <span class='input-group-addon'>付款人姓名:</span>
                <input type='text' class='form-control' id='payer' placeholder='输入付款人的姓名' />
            </div>
        </td>";
        $RetContent .= "</tr>";    
        $RetContent .= "<tr>";
        $RetContent .= "<td>
            <div class=\"input-group\">
                <span class='input-group-addon'>付款帐号:　</span>
                <input type='text' class='form-control' id='pay_account' placeholder='输入付款的账号、如微信填写微信昵称' />
            </div>
        </td>";
        $RetContent .= "</tr>";  
        $RetContent .= "</tr>";    
        $RetContent .= "<tr>";
        $RetContent .= "<td colspan='2'><a href=\"javascript:getContent('smbinfo.php','recharge_order_pay')\" id='create_order' class='btn btn-danger'>创建订单</a></td>";
        $RetContent .= "</tr>";          
        $RetContent .= "</table>";
        $RetContent .= "</div>";
        $RetContent .= "</div>";

        $RetContent .= "<div class='pay_recored'>";
        $RetContent .= "<p>充值记录</p>";
        $RetContent .= "<p>
            <label><input type='radio' name='search_day' value='7' />7天</label>  
            <label><input type='radio' name='search_day' value='30' />30天</label>   
            <label><input type='radio' name='search_day' value='180' />半年</label>   
            <label><input type='radio' name='search_day' value='360' />一年</label> 
            <input type='button' value='查询' class='btn btn-danger' />
        </p>";    
        $RetContent .= "<table class='table table-striped table-hover table-bordered'>";
        $RetContent .= "<tr>";
        $RetContent .= "<th>订单号</th>";
        $RetContent .= "<th>金额度</th>";
        $RetContent .= "<th>状态</th>";
        $RetContent .= "<th>时间</th>";
        $RetContent .= "<th>支付方式</th>";
        $RetContent .= "<th>支付帐号</th>";
        $RetContent .= "<th>付款人</th>";
        $RetContent .= "<th>操作</th>";
        $RetContent .= "</tr>";
        $RetContent .= "<tr>";
        $RetContent .= "<td>123456789</td>";
        $RetContent .= "<td>100,000,000</td>";
        $RetContent .= "<td>未完成</td>";
        $RetContent .= "<td>2016-06-10 14:07:46</td>";
        $RetContent .= "<td>支付宝</th>";
        $RetContent .= "<td>123456</th>";
        $RetContent .= "<td>小徐</td>";
        $RetContent .= "<td><a href=\"#\">撤消</a></td>";
        $RetContent .= "</tr>";        
        $RetContent .= "</table>";
        $RetContent .= "</div>";


/*
		$RetContent = "<div class='Bank'>\r\n";//Bank开始
		$RetContent .= "\t<div class='r_list_t'>\r\n";
		$RetContent .= "\t\t<ul>\r\n";
		$RetContent .= "\t\t\t<li class='pitch'>网银充值</li>\r\n";
		$RetContent .= "\t\t</ul>\r\n";
		$RetContent .= "\t</div>\r\n";

		
		$RetContent .= "\t<div class='Money'>\r\n";
		$RetContent .= "\t\t<ul>\r\n";
		$RetContent .= "\t\t\t<li><span>充值金额:</span>
							<select id='sltPayRMB'>
			                    <option value='10'>10元</option>
			                    <option value='100'>100元</option>
			                    <option value='200'>200元</option>
			                    <option value='500'>500元</option>
			                    <option value='1000'>1000元</option>
			                    <option value='5000'>5000元</option>
			                    <option value='10000'>10000元</option>
							</select>
							</li>\r\n";
		$RetContent .= "\t\t\t<li><span>可得乐豆:</span><input type='text' id='txtPayPoint' maxlength='20' value='10000'  disabled /></li>\r\n";
		$RetContent .= "\t\t\t<li class='text'><span> </span><input type='button' id='btnGoPay' class='btn-1' value='取充值' /></li>\r\n";
		
		$RetContent .= "\t\t</ul>\r\n";
		$RetContent .= "\t</div>\r\n";
		
		
		$RetContent .= "</div>\r\n";//Bank结束
*/		
		echo $RetContent;
		exit;
	}
	/* 兑奖点卡
	*
	*/
	function ExchangeCard()
	{
		global $db;  
		$CardType = isset($_POST['cardtype'])?str_check($_POST['cardtype']):"";
		$Amount = isset($_POST['amount'])?str_check($_POST['amount']):"";
		$ip = usersip();
		$arrRet = array('cmd'=>'','msg'=>'');
		
		if($CardType == "" || !is_numeric($CardType) || $CardType <= 0)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "无效参数";
			echo json_encode($arrRet);  
			return;
		}
		$CardType = intval($CardType);
		if($Amount == "" || !is_numeric($Amount) || $Amount <= 0)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "请输入整数的兑奖卡数量";
			echo json_encode($arrRet);  
			return;
		}
		$Amount = intval($Amount);
		if($Amount == 0)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "请输入正整数的兑奖卡数量";
			echo json_encode($arrRet);  
			return;
		}
		//检测是否绑定邮箱
		$sql = "select email,is_check_email,mobile,is_check_mobile from users where id = {$_SESSION['usersid']} ";
		$result = $db->query($sql);
		$rs = $db->fetch_array( $result );
		/* if($rs['is_check_email'] == 0)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "您未绑定邮箱，请先绑定邮箱!";
			echo json_encode($arrRet);  
			return;
		} */
		if($rs['is_check_mobile'] == 0)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "您未绑定手机，请先绑定手机!";
			echo json_encode($arrRet);
			return;
		}
		$mobile = $rs['mobile'];
		$email = $rs['email'];
		//取得卡信息
		$sql = "Select cardname,cardtop,cardlen,cardtype,cardprice from cardtype where id='{$CardType}'";
		$result = $db->query($sql);
		if ( $rs = $db->fetch_array( $result ) )
		{
			$cardname = $rs['cardname'];
			$cardtop = $rs['cardtop'];
			$cardlen = $rs['cardlen'];
			$cardtype = $rs['cardtype'];
		}
		else
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "没有该点卡，请与客服联系";
			echo json_encode($arrRet);  
			return;
		}
		$cardlist = "";
		for ($i = 0; $i < $Amount;	++$i)
		{
			$cardlist .= $cardtop.createrandstring($cardlen,$cardtype) . "," . createrandstring($cardlen,$cardtype) . "|";
		}
		$cardlist = substr( $cardlist, 0, -1 );
		
		$sql = "call web_exchange_card({$_SESSION['usersid']},{$CardType},{$Amount},'{$cardlist}','{$ip}')";
        //WriteLog($sql);
        $arr = $db->Mysqli_Multi_Query($sql);
        $arrRet['cmd'] = "ok";
        switch($arr[0][0]["result"])
        {
			case '0': //成功
				/* $msg = "成功生成" . $Amount . "张点卡，已发送到邮箱请查收";
				$mailTitle = "成功生成点卡" . $Amount . "张";
    			$mailContent = "您成功生成" . ChangeEncodeG2U($cardname) . "点卡" . $Amount ."张:<br><br>";
    			$arrCard = explode("|",$cardlist);
    			foreach($arrCard as $k => $v)
    			{
					$mailContent .= $v . "<br>";
					$msgcard .= $v . "\n";
    			}
    			$SendMailRet = SendMailToUser($email,$mailTitle,$mailContent);
    			if($SendMailRet)
    				$msg = "成功生成" . $Amount . "张点卡，已发送到邮箱请查收:\n";
    			else
    			    $msg = "成功生成" . $Amount . "张点卡，不过发送到邮箱失败:\n";
    			
				$arrRet['msg'] = $msg . $msgcard;
				 */
    			$arrRet['msg'] = "兑换点卡生成成功,请查看您的会员中心的兑换记录!";
				RefreshPoints();
				break;
			case '1':
				$arrRet['msg'] = "您的经验没有达到可兑奖条件，无法兑奖!";
				break;
			case '2':
				$arrRet['msg'] = "您的可用乐豆不足(不包含银行乐豆)，无法兑奖!";
				break;
			default:
				$arrRet['msg'] = "系统错误，执行失败!";
				break;
        }
        
        echo json_encode($arrRet);
		exit;
	}

    
/* 取得兑奖点卡列表*/
function GetExchangeList(){
    global $db; 
    $usersid=$_SESSION['usersid'];
    $arrRet = array('cmd'=>'','msg'=>'');  
    if(!isset($_SESSION['usersid'])) {
        $arrRet['cmd'] = "err";
        $arrRet['msg'] = "登录超时!";
        echo json_encode($arrRet);
        exit();
    }
   
    $RetContent="
        <script type='text/javascript' >var buycard_rate='".$agent["buycard_rate"]."'; </script>
    <script type='text/javascript' src='js/agent.js'></script>";
        		
    $RetContent .= "<div class='panel panel-default'>";
    $RetContent .= "<div class='panel-heading'>兑换记录</div>";
    $RetContent .= "<div class='panel-body'>";	
        		
    $RetContent .= "<div class='message   recharge' style='width:100%'>
            <div class='m_list' style='width:100%'>
            <div class='list user_rexperience_log_list' style='width:100%;padding:0px;border-style: none;'>
                    <table class='table_list ' cellspacing='0px' style='border-collapse:collapse;width:100%'>
                        <tr class='none_tr'>
                           <td colspan='6' >
                            <div class='search_input'>
                            <ul>
                              	<li class='cbxtime2'><input type='radio' name='search_day' checked  value='7' id='search_day1'><label for='search_day1'>7天</label>
                              	<input type='radio' name='search_day'  value='30'  id='search_day2'><label for='search_day2'>30天</label>
                              	<input type='radio' name='search_day'  value='180' id='search_day3'><label for='search_day3'>180天</label>
                              	<input type='radio' name='search_day'  value='365' id='search_day4'><label for='search_day4'>一年</label>
                             	<input type='button' value='查    询' id='exchange_btnATSearch' style='text-align:center;height:32px;width:80px;border:0px;' class='btn btn-danger'><li>
            				</ul>                 
    						</div>
                             
                            </td>
                        </tr>
                        
                        <tr height='30'>
                            <td width='150'>卡号</th>
                            <td width='60'>卡密</th>
                            <td width='80'>卡类型</th>
                            <td width='50'>卡状态</th>
                            <td width='60'>点数</th>
                            <td width='60'>回收代理</th>
                        </tr>
                         ".Get_card_Log()."
                    </table>
              </div>
             </div>
              </div>
             </div>      		
        </div>
    </div>";
    echo $RetContent;
}


//卡回收记录
function Get_card_Log()
    {
        global $db;
        $page = isset($_POST['page'])?intval($_POST['page']):1;
        $day = isset($_POST['day'])?intval($_POST['day']):7;
        if($day==0){
            $day=7;
        }
        if(!isset($_SESSION['usersid'])) {
            $arrRet['cmd'] = "timeout";
            return $arrRet['cmd'];
        }
        $userid =$_SESSION['usersid'];
        $pagesize = 15;
        $RetContent="";
        //表内容
        $sql = "select count(*) 
        from exchange_cards  
        where uid = '{$userid}' and add_time > date_add(now(),interval -{$day} day)  ";
        $TotalRecCount = $db->GetRecordCount($sql);
        
        $sql = "select  a.agent_name,card_no,card_points,used_time,used_ip,e.state,card_name,card_pwd
        from exchange_cards  e
        LEFT JOIN   agent a  on (e.agentid=a.id)
         LEFT JOIN   exchange_cardtype  t  on (t.card_type=e.card_type)
        where    e.uid = '{$userid}' and e.add_time > date_add(now(),interval -{$day} day) ";
    
        $sql=$sql." order by e.id desc ";//used_time
        $sql .= GetLimit($page,$pagesize); 
        $result =  $db->query($sql);
        while($rs=$db->fetch_array($result)){
            $str=substr($rs["card_no"],10,10);
            //$rs["card_no"]=str_replace($str,"**********",$rs["card_no"]);
            $str=substr($rs["card_pwd"],3,4);
           // $rs["card_pwd"]=str_replace($str,"****",$rs["card_pwd"]);
            
            $rs['card_points']=number_format($rs['card_points'])."(￥".number_format(($rs['card_points']/1000),2).")";
            $rs['content']=ChangeEncodeG2U($rs['content']);
            $rs['card_name']=ChangeEncodeG2U($rs['card_name']);
            $rs['remark']=ChangeEncodeG2U($rs['remark']);
            $rs['agent_name']=ChangeEncodeG2U($rs['agent_name']);
            $rs['nickname']=$rs['nickname']."(".$rs['uid'].")";
            if($rs['state']==0){
                $rs['state']="未回收";
            }else if($rs['state']==1){
                $rs['state']="已回收";
            }else if($rs['state']==2){
                $rs['state']="已冻结";
            }
            $RetContent .= "<tr>
                                <td>{$rs['card_no']}</td>
                                <td>{$rs['card_pwd']}</td>
                                <td>{$rs['card_name']}</td>
                                <td>{$rs['state']}</td>
                                <td>{$rs['card_points']}</td>
                                <td>{$rs['agent_name']}</td>
                          </tr>";
        }

        //分页
        if($TotalRecCount > $pagesize)
        {
            $divPage = "<div class='pagediv'></div><div class='Paging'>\r\n";
            require_once('inc/fenye.php');
            $ajaxpage=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesize,'ajax'=>"ajax_page_user_exchange",'nowindex' => $page));
            $divPage .= $ajaxpage->show();
            $divPage .= "</div>\r\n";
            $RetContent .= "<tr><td colspan=6>".$divPage."</td></tr>";
        }
        return $RetContent;

    }




    
	/* 取得兑奖点卡列表
	*
	*/
	function GetExchangeList_back()
	{
		global $db;
		$RetContent = "<div class='Exchange'>\r\n";//Exchange开始
		$RetContent .= "\t<div class='r_nav'>\r\n";//r_nav开始
		$RetContent .= "\t\t<img src='img/banner.png' />\r\n";
		$RetContent .= "\t\t<p class='title'>兑奖点卡</p>\r\n";
		$RetContent .= "\t\t<p class='cen'>可把乐豆兑换成点卡(输入数量点击点卡)，点卡将发送到邮箱中，请保持邮箱绑定并畅通</p>\r\n";
		$RetContent .= "\t</div>";//r_nav结束
		
		$RetContent .= "\t<div class='award'>\r\n";
		$RetContent .= "\t\t<div class='award_list'>\r\n";
		
		//取点卡列表
		$sql = "SELECT `name`,pic,points,cardtype FROM commodities WHERE cardtype > 0 ORDER BY points";
		$result =  $db->query($sql);
		while($row=$db->fetch_array($result))
		{
			$RetContent .= "\t\t\t<ul>\r\n";
			$RetContent .= "\t\t\t\t<a href=\"javascript:ExchangeCard({$row['cardtype']})\">\r\n";
			$RetContent .= "\t\t\t\t\t<img src='{$row['pic']}' />\r\n";
			$RetContent .= "\t\t\t\t</a>\r\n"; 
			$RetContent .= "\t\t\t\t\t<li class='award_name'>" . ChangeEncodeG2U($row['name']) . "</li>\r\n";
			$RetContent .= "\t\t\t\t\t<li class='award_money'><i>{$row['points']}</i></li>\r\n";
			$RetContent .= "\t\t\t\t\t<li class='award_money'>兑奖<input id='txtcardtype_{$row['cardtype']}' type='text' value='1' style='width:50px'>张</li>";
			
			$RetContent .= "\t\t\t</ul>\r\n";
		}
		
		$RetContent .= "\t\t</div>\r\n";
		$RetContent .= "\t</div>\r\n";
		
		
		$RetContent .= "</div>\r\n";//Exchange结束
		
		echo $RetContent;
		exit;
	}
	/* 取得救济
	*
	*/
	function GetJiuji()
	{
		return;
		
		global $db;
		$ip = usersip();
		$arrRet = array('cmd'=>'','msg'=>'');
		$sql = "call web_reward_jiuji({$_SESSION['usersid']},'{$ip}')";
        //WriteLog($sql);
        $arr = $db->Mysqli_Multi_Query($sql);
        $arrRet['cmd'] = "ok";
        switch($arr[0][0]["result"])
        {
			case '0': //成功
				$arrRet['msg'] = "成功获得救济" . $arr[0][0]["points"];
				RefreshPoints();
				break;
			case '1':
				$arrRet['msg'] = "今日已领取过了!";
				break;
			case '2':
				$arrRet['msg'] = "该等级无救济分!";
				break;
			default:
				$arrRet['msg'] = "系统错误，执行失败!";
				break;
        }
        
        echo json_encode($arrRet);
		exit;
	}
	
	
    /* 领取排行奖励
    *
    */
    function RewardRank()
    {
        global $db;
        $usersid=$_SESSION['usersid'];
        // 昨天排行
        $sql = "SELECT a.rank_num,a.rank_points,a.prize_points,a.uid,b.nickname,prize_time
            FROM rank_list a
            LEFT OUTER JOIN users b ON( a.uid = b.id)
            LEFT JOIN rank_prizelog r  ON (r.uid=b.id and to_days(prize_time) = to_days(now()))
            WHERE a.rank_type = 1 and a.uid={$usersid}
            ORDER BY rank_num
            limit 1";
        $query = $db->query($sql);
        $data_yesterday=array();
        while($rs=$db->fetch_array($query)){
            $data_yesterday=$rs;
        }
        $jq='$';
        $RetContent = "
        <script type='text/javascript'>
        {$jq}(document).ready(function(){
                //排行榜奖励
                {$jq}('#reward_rank_but').click(function(){
                    {$jq}.ajax({ 
                        type: 'post', 
                        url: 'ajax.php?action=get_reward_rank', 
                        dataType: 'json', 
                        success: function (data) { 
                            if(data.cmd=='timeout' || data.cmd=='notlogin'){
                                alert('你登录超时!');
                                window.location = '/login.php';
                                return;
                            }
                            if(data.cmd=='ok'){
                                getContent('smbinfo.php','rewardrank');
                            }
                            alert(data.msg);
                        }, 
                        error: function (XMLHttpRequest, textStatus, errorThrown) { 
                                alert('检验失败,未知错误!');
                        } 
                    });
                });
            });
            </script>
            <div class='message'>
                <div class='r_nav'>
                    <img src='img/banner.png' />
                    <p class='title'>排行榜奖励</p>
                    <p class='cen'>排行榜可每日可领排行榜奖励</p>
                </div>
                <div class='m_list'>
                    <div class='m_val'>
                        <span class='title'>领奖</span>        </div>
                    <div class='list'>";
        if(empty( $data_yesterday)){
            $RetContent .= "<div  align='center'>你没有进排行榜</div>";
        }else{
            if(empty($data_yesterday["prize_time"])){
                $val="<input id='reward_rank_but' type='button' class='btn-1' value='领取领奖'>";
            }else{
                   $val="<b style='color:#F00'>奖励已领取</b>";
            }
            $RetContent .= "
              <table class='table_list' cellspacing='0px' style='border-collapse:collapse;'>
                <tr>
                   <td  >恭喜你进排行榜第".$data_yesterday["rank_num"]."名 ,可领取".$data_yesterday["prize_points"]." 领奖</td>
                </tr>
                  <tr>
                  <td>{$val}</td>
                  </tr>
              </table>
            ";
        }
        $RetContent .= "
                </div>
                <div class='m_list'>
                    <div class='m_val'>
                        <span class='title'>领奖记录</span>        </div>
                    <div class='list'>
                    <table class='table_list ' cellspacing='0px' style='border-collapse:collapse;'>
                    <tr height='30'>
                            <th   width='50%'  align='left'>领奖时间</th>
                             <th width='50%'  align='left'>领奖金额</th>
                        </tr>
                    ".Get_rewardrank_Log()."
                    </div>
            </div>
        </div>";
        echo $RetContent;
        exit;
    }
    


//领奖记录
function Get_rewardrank_Log()
    {
        global $db;
        $page = isset($_POST['page'])?intval($_POST['page']):1;
        $day = isset($_POST['day'])?intval($_POST['day']):7;
        if($day==0){
            $day=7;
        }
        
        if(!isset($_SESSION['usersid'])) {
            $arrRet['cmd'] = "timeout";
            return $arrRet['cmd'];
        }
        $userid =$_SESSION['usersid'];
        $pagesize = 15;
        $RetContent="";
        //表内容
        $sql = "select count(*) 
        from score_log  
        where    uid = '{$userid}'  and opr_type=6 ";
        $TotalRecCount = $db->GetRecordCount($sql);
        $sql = "select  date_format(log_time,'%Y-%m-%d') as log_time  ,points
        from score_log
        where      uid = '{$userid}'  and opr_type=6 ";
        $sql=$sql." order by log_time desc ";
        $sql .= GetLimit($page,$pagesize); 
        $result =  $db->query($sql);
        while($rs=$db->fetch_array($result)){
            $rs['points']=number_format($rs['points'])."(￥".number_format(($rs['points']/1000),2).")";   
            $RetContent .= "
                <tr>
                    <td style='width:160px!important;'>{$rs['log_time']}</td>
                    <td>{$rs['points']}</td>
               </tr>";
        }
        if($TotalRecCount==0){
             $RetContent .= "<tr>
                                <td  colspan='2'>你没有领奖记录！</td>
                          </tr>";
        }
        //分页
        if($TotalRecCount > $pagesize)
        {
            $divPage = "<div class='pagediv'></div><div class='Paging'>\r\n";
            require_once('inc/fenye.php');
            $ajaxpage=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesize,'ajax'=>"ajax_page_rewardrank",'nowindex' => $page));
            $divPage .= $ajaxpage->show();
            $divPage .= "</div>\r\n";
            $RetContent .= "<tr><td colspan=6>".$divPage."</td></tr>";
        }
        return $RetContent;

    }

    
    
	/* 取得救济列表
	*
	*/
	function GetJiuJiList()
	{
		global $db;
		
		$RetContent .= "<div class='panel panel-default'>";
		$RetContent .= "<div class='panel-heading'>每日救济,每日可领取系统救济</div>";
		$RetContent .= "<div class='panel-body'>";
		
		$RetContent .= "<div class='message   recharge' style='width:100%'>";
		$RetContent .= "<div class='m_list' style='width:100%'>";
		$RetContent .= "\t<div class='list' style='width:100%;padding:0px;border-style: none;'>\r\n";
		$RetContent .= "\t\t\t<table class='table_list '  style='width:100%' cellspacing='0px' style='border-collapse:collapse;'>";
		$RetContent .= "\t\t\t\t<tr height='30'>\r\n";
		$RetContent .= "\t\t\t\t\t<th width='150' align='center' >等级</th>\r\n";
		$RetContent .= "\t\t\t\t\t<th width='120' align='center' >经验起步</th>\r\n";
		$RetContent .= "\t\t\t\t\t<th width='120' align='center' >经验截止</th>\r\n";
		$RetContent .= "\t\t\t\t\t<th align='center' width='120' >救济乐豆</th>\r\n";
		$RetContent .= "\t\t\t\t</tr>\r\n";
		
		$sql = "select name,creditshigher,creditslower,day_jiuji_point,id from usergroups ";
		$result =  $db->query($sql);
		while($rs=$db->fetch_array($result)){
			$rs['id']=showstars($rs['id']);
			$RetContent .= "\t\t\t\t<tr height='25'>\r\n";
			$RetContent .= "\t\t\t\t\t<td>{$rs['id']}</td>\r\n";
			$RetContent .= "\t\t\t\t\t<td>". Trans($rs['creditslower']) ."</td>\r\n";
			$RetContent .= "\t\t\t\t\t<td>". Trans($rs['creditshigher']) ."</td>\r\n";
			$RetContent .= "\t\t\t\t\t<td>". Trans($rs['day_jiuji_point']) ."</td>\r\n";
			$RetContent .= "\t\t\t\t</tr>\r\n";
		}
		$RetContent .= "\t\t\t</table>\r\n";
		
		$RetContent .= "\t\t\t<div>\r\n";
		$RetContent .= "\t\t\t\t</br><input onclick=\"javascript:GetJiuji();\" class=\"btn btn-danger\" style=\"margin:0 auto;background-color: #b40001;\" value=\"领取救济\" type=\"button\">\r\n";
		$RetContent .= "\t\t\t</div>\r\n";
			
		$RetContent .= "\t\t</div>\r\n";
		$RetContent .= "\t</div>\r\n";
		$RetContent .= "\t</div>\r\n";
		$RetContent .= "\t</div>\r\n";
		$RetContent .= "</div>\r\n";//message结束
		
		echo $RetContent;
		exit;
	} 	
	/* 绑定、解绑邮箱
	*
	*/
	function BindUnBindEmail($t)
	{
		global $db,$web_pwd_encrypt_prefix;
		$smtpserver = "";
		$smtpserverport = "";
		$smtpusername = "";
		$smtpusermail = "";
		$smtpuser = "";
		$smtppass = "";
		$mailtype = "";
		$Email = isset($_POST['email'])?str_check($_POST['email']):"";
		$arrRet = array('cmd'=>'','msg'=>'');
		
		$sql = "select smtp_server,smtp_serverport,smtp_username,smtp_usermail,smtp_user,smtp_pass,smtp_mailtype
				from web_config where id = 1";
		$result =  $db->query($sql);
		if($row = $db->fetch_array($result))
		{
			$smtpserver = $row["smtp_server"];
			$smtpserverport = $row["smtp_serverport"];
			$smtpusername = $row["smtp_username"];
			$smtpusermail = $row["smtp_usermail"];
			$smtpuser = $row["smtp_user"];
			$smtppass = $row["smtp_pass"];
			$mailtype = $row["smtp_mailtype"];
		}		
		
		require_once("inc/smtp.php");
		if($t == "bind")
		{
			$mailTitle = "通知确认信息";
			$href = "http://".$_SERVER["HTTP_HOST"]."/checkemail.php?id=" . $_SESSION['usersid'] . "&t=bind&mail=". $Email ."&code=" . md5($_SESSION['usersid'] . "bind" . $Email . $web_pwd_encrypt_prefix);
    		$mailContent = "您好,感谢您选择绑定邮箱！<BR><BR>请点击下面的链接完成验证：<a href=\"{$href}\">{$href}</a>。 谢谢合作。<BR>(如果链接无法直接点击，请复制链接到您的浏览器地址栏打开。)"; 
    		
		}
		else
		{
			$mailTitle = "通知确认信息";
    		$href = "http://".$_SERVER["HTTP_HOST"]."/checkemail.php?id=" . $_SESSION['usersid'] . "&t=unbind&code=" . md5($_SESSION['usersid'] . "unbind" .  $web_pwd_encrypt_prefix);
    		$mailContent = "您好！<BR><BR>请点击下面的链接完成解绑邮箱：<a href=\"{$href}\">{$href}</a>。 谢谢合作。<BR>(如果链接无法直接点击，请复制链接到您的浏览器地址栏打开。)"; 
    		
		}
		$sM = ChangeEncodeU2G($Email);
		$sC = ChangeEncodeU2G($mailContent);
		$sql = "insert into validcodelog(userid,code_type,account,content,add_time,state)
				values({$_SESSION['usersid']},1,'{$sM}','{$sC}',now(),0)";
		$result =  $db->query($sql);
		$insertID = $db->insert_id();
		$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证. 
		//$smtp->debug = TRUE;//是否显示发送的调试信息 
		$smtpusername = ChangeEncodeG2U($smtpusername);
		if($smtp->sendmail($Email, $smtpusername, $smtpusermail, $mailTitle, $mailContent, $mailtype))
		{
			$arrRet['cmd'] = "ok";
			$arrRet['msg'] = "邮件已发出";
			echo json_encode($arrRet);
			exit;
		} 
		else
		{
			$sR = ChangeEncodeU2G("邮件未能正常发出");
			$sql = "update validcodelog set state = 1,err_msg='{$sR}' where id = {$insertID}"; 
			$result =  $db->query($sql); 
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "邮件未能正常发出，请稍后再试";
			echo json_encode($arrRet);
			exit;
		}
	}
	/* 解绑手机
	*
	*/
	function UnBindMobile()
	{
		global $db;
		$code = isset($_POST['code'])?str_check($_POST['code']):"";
		
		$arrRet = array('cmd'=>'','msg'=>''); 
		if(isset($_SESSION['mobilecodecount']))
		{
			$_SESSION['mobilecodecount'] = $_SESSION['mobilecodecount'] + 1;
		}
		else
		{
			$_SESSION['mobilecodecount'] = 1;
		}
		if($_SESSION['mobilecodecount'] > 10)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "您不能频繁验证，请明天再试";
			echo json_encode($arrRet);
			exit;
		}
		if($code != $_SESSION['mobilesmscode'])
		{
			
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "验证码错误";
			echo json_encode($arrRet);
			exit;
		}
		$sql = "update users set is_check_mobile = 0 where id = {$_SESSION['usersid']}";
		$result =  $db->query($sql);
		$arrRet['cmd'] = "ok";
		$arrRet['msg'] = "解绑成功";
		echo json_encode($arrRet);
		exit;
	}
	/* 绑定手机
	*
	*/
	function BindMobile()
	{
		global $db;
		$mobile = isset($_POST['mobile'])?str_check($_POST['mobile']):"";
		$code = isset($_POST['code'])?str_check($_POST['code']):"";
		
		$arrRet = array('cmd'=>'','msg'=>'');
		if($mobile == "" || !is_numeric($mobile))
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "手机号码错误";
			echo json_encode($arrRet);
			exit;
		}
		if(isset($_SESSION['mobilecodecount']))
		{
			$_SESSION['mobilecodecount'] = $_SESSION['mobilecodecount'] + 1;
		}
		else
		{
			$_SESSION['mobilecodecount'] = 1;
		}
		if($_SESSION['mobilecodecount'] > 10)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "您不能频繁验证，请明天再试";
			echo json_encode($arrRet);
			exit;
		}
		if($code != $_SESSION['mobilesmscode'])
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "验证码错误";
			echo json_encode($arrRet);
			exit;
		}
		$sql = "select count(*) from users where mobile = '{$mobile}' and is_check_mobile = 1";
		$MobileCount = $db->GetRecordCount($sql); 
		if($MobileCount > 0)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "该手机已绑定了其他帐号!";
			echo json_encode($arrRet);
			exit;
		}
		$sql = "update users set mobile = '{$mobile}',is_check_mobile = 1 where id = '{$_SESSION['usersid']}'";
		$result =  $db->query($sql);
		$arrRet['cmd'] = "ok";
		$arrRet['msg'] = "绑定成功";
		echo json_encode($arrRet);
		exit;
	}
	
   
	/* 发送手机验证码
	*
	*/
	function SendMobileValidCode()
	{
		global $db;
		$mobile = isset($_POST['mobile'])?str_check($_POST['mobile']):"";
		
		$arrRet = array('cmd'=>'','msg'=>'');
		if($mobile == "" || !is_numeric($mobile))
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "请输入常用的手机号码";
			echo json_encode($arrRet);
			exit;
		}
		$sql = "select count(*) from validcodelog where code_type = 0 and state = 0 and account = '{$mobile}' and to_days(add_time) = to_days(now())";
		$TodayMsgCount = $db->GetRecordCount($sql);
		if($TodayMsgCount >= 5)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "您今天发送的验证码次数已用完，请明天再试,有疑问请联系客服!";
			echo json_encode($arrRet);
			exit;
		}
		
		include_once("class/Sms.php");
		$Sms = new Sms();
		
		if($validcode = $Sms->send($mobile))
		{
			$arrRet['cmd'] = "ok";
			$arrRet['msg'] = "发送成功";
			$_SESSION['mobilesmscode'] = $validcode;
		}
		else
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "短信未能发出，请稍后再试!";
		}
		
		echo json_encode($arrRet);
		exit;
	}
	/* 取得绑定/解绑手机信息
	*
	*/
	function GetBindMobileInfo()
	{
		global $db;
		$BindType = str_check($_GET['t']);
		
		$sql = "select id,mobile from users where id = '{$_SESSION['usersid']}'";
		$result =  $db->query($sql);
		if($rs=$db->fetch_array($result))
		{
			$Mobile = $rs['mobile'];
		}
		if(isset($_SESSION['mobilecodecount']))
			unset($_SESSION['mobilecodecount']);
		$RetContent = "<div class='popup'>\r\n";
		//header
		$RetContent .= "\t<div class='popup-header'>\r\n";
		$RetContent .= "\t\t\t<h2>绑定/解绑手机</h2>\r\n";
		$RetContent .= "\t\t\t<a href='javascript:;' onclick='closerecord(-1)' title='关闭' class='close-link'>[关闭]</a>\r\n";
		$RetContent .= "\t\t\t<br clear='both' /> \r\n";
		$RetContent .= "\t</div>";
		//body
		$RetContent .= "\t<div class='popup-body'>\r\n";
		$RetContent .= "\t\t<div class='table'>\r\n";
		$RetContent .= "\t\t\t<table class='table_list' cellspacing='0px' style='border-collapse:collapse;border:1px;width:500;height:400;'>\r\n";
		if($BindType == "bind")
			$RetContent .= "\t\t\t\t<tr><td width=100>手机</td><td width=350 style='text-align:left;'><input id='txtBindMobile' value='{$Mobile}' maxlength=11 >11位手机号码</td>";
		else 
			$RetContent .= "\t\t\t\t<tr><td width=100>手机</td><td width=350 style='text-align:left;'><input id='txtBindMobile' value='{$Mobile}' maxlength=11 disabled='disabled'></td>";
		$RetContent .= "\t\t\t\t<tr><td>验证码</td><td style='text-align:left;'><input type='text' style='width:50px' id='txtMobileValidCode' maxlength=4><input type='button' id='btnGetMobileValid' style='width:200' value='获取验证码' /><label id='lblBindtype' style='display:none'>{$BindType}</label></td></tr>\r\n";
		if($BindType == "bind")
			$RetContent .= "\t\t\t\t<tr><td></td><td><input type='button' id='btnBindMobile' style='width:100' value='马上绑定' /></td></tr>\r\n"; 
		else
			$RetContent .= "\t\t\t\t<tr><td></td><td><input type='button' id='btnUnBindMobile' style='width:100' value='解绑' /></td></tr>\r\n";
		
		$RetContent .= "\t\t\t</table>\r\n";
		
		$RetContent .= "\t\t</div>\r\n";
		$RetContent .= "\t</div>";
		
		echo $RetContent;
		exit;
	
	}
	/* 删除消息
	*
	*/
	function RemoveMsg()
	{
		global $db;
		$ID = isset($_POST['id'])?str_check($_POST['id']):"";
		$MsgType = isset($_POST['t'])?str_check($_POST['t']):"";
		
		$arrRet = array('cmd'=>'','msg'=>'');
		if($ID == "")
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "请勾选ID";
			echo json_encode($arrRet);
			exit;
		}
		
		if($MsgType == "send")
		{
			$sql = "delete from msg where id in({$ID}) and usersid = '{$_SESSION['usersid']}'";
		}
		else
		{
			$sql = "delete from msg where id in({$ID}) and mid = {$_SESSION['usersid']}";
		}
		$result =  $db->query($sql);
			
		$arrRet['cmd'] = "ok";
		$arrRet['msg'] = "删除成功";
		echo json_encode($arrRet);
		exit;
		
	}
	/* 发送消息
	*
	*/
	function SendMsg()
	{
		global $db;
		$TargetUser = isset($_POST['user'])?str_check($_POST['user']):"";
		$TargetTitle = isset($_POST['title'])?str_check($_POST['title']):"";
		$TargetMsg = isset($_POST['msg'])?str_check($_POST['msg']):"";
		
		$arrRet = array('cmd'=>'','msg'=>'');
		if($TargetUser == "" || !is_numeric($TargetUser))
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "请输入对方数字ID";
			echo json_encode($arrRet);
			exit;
		}
		if($TargetUser == $_SESSION['usersid'])
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "不能自己给自己发消息";
			echo json_encode($arrRet);
			exit;
		}
		if($TargetTitle == "" || strlen($TargetTitle) > 50)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "标题长度需在50字符以内";
			echo json_encode($arrRet);
			exit;
		}
		if($TargetMsg == "" || strlen($TargetTitle) > 200)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "内容长度需在200字符以内";
			echo json_encode($arrRet);
			exit;
		}
		$TargetUser = intval($TargetUser);
		$sql = "select 1 from users where id = '{$TargetUser}'";
		
		$result =  $db->query($sql);
		if($rs=$db->fetch_array($result))
		{
			$title = ChangeEncodeU2G($TargetTitle);
			$msg = ChangeEncodeU2G($TargetMsg);
			$sql = "insert into msg(usersid,title,mag,`mid`,`time`)
						values({$_SESSION['usersid']},'{$title}','{$msg}',{$TargetUser},now())";
						
			$result =  $db->query($sql);
			
			$arrRet['cmd'] = "ok";
			$arrRet['msg'] = "发送成功";
			echo json_encode($arrRet);
			exit;
		}
		else
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "对方id不存在";
			echo json_encode($arrRet);
			exit;
		}
		
	}
	/* 取得发送消息
	*
	*/
	function GetSendMsgBox()
	{
		global $db;
		$id = intval($_GET['id']);
		
		$targetUser = "";
		$targetTitle = "";
		if($id > 0)
		{
			$sql = "select title,usersid as userid from msg where id = '{$id}'";
			$result =  $db->query($sql);
			if($rs=$db->fetch_array($result))
			{
				$targetUser = $rs['userid'];
				$targetTitle = "[回复]" . ChangeEncodeG2U($rs['title']);
			}
		}
		
		
		$RetContent = "<div class='popup'>\r\n";
		//header
		$RetContent .= "\t<div class='popup-header'>\r\n";
		$RetContent .= "\t\t\t<h2>发送消息</h2>\r\n";
		$RetContent .= "\t\t\t<a href='javascript:;' onclick='closerecord(0)' title='关闭' class='close-link'>[关闭]</a>\r\n";
		$RetContent .= "\t\t\t<br clear='both' /> \r\n";
		$RetContent .= "\t</div>";
		//body
		$RetContent .= "\t<div class='popup-body'>\r\n";
		$RetContent .= "\t\t<div class='table'>\r\n";
		$RetContent .= "\t\t\t<table class='table_list' cellspacing='0px' style='border-collapse:collapse;border:1px;width:500;height:400;'>\r\n";
		$RetContent .= "\t\t\t\t<tr><td width=100>发送给</td><td width=350 style='text-align:left;'><input id='txtTargetUser' value='{$targetUser}' >请输入对方数字id</td>";
		$RetContent .= "\t\t\t\t<tr><td>标题</td><td style='text-align:left;'><input style='width:300px' id='txtTargetTitle' value='{$targetTitle}' ></td></tr>\r\n";
		$RetContent .= "\t\t\t\t<tr><td>内容</td><td style='text-align:left;'><textarea cols='40' rows='5'  id='txtTargetMsg'></textarea></td></tr>\r\n";
		
		$RetContent .= "\t\t\t\t<tr><td></td><td><input type='button' id='btnSendTargetMsg' class='btn-1' value='发送' /></td></tr>\r\n"; 
		
		$RetContent .= "\t\t\t</table>\r\n";
		
		$RetContent .= "\t\t</div>\r\n";
		$RetContent .= "\t</div>";
		
		echo $RetContent;
		exit;
	}
	/* 取用户消息
	*
	*/
	function GetUserMessageContent()
	{
		global $db;
		$id = intval($_GET['id']);
		$t = str_check($_GET['t']);
		
		if($t == "send")
		{
			$sql = "select title,mag,time,mid as userid,look from msg where id = '{$id}' and usersid = {$_SESSION['usersid']} and del=0 ";
			$usertips = "发送给";
		}
		else
		{
			$sql = "select title,mag,time,usersid as userid,look from msg where id = '{$id}' and mid = {$_SESSION['usersid']} and del=0 ";
			$usertips = "来自";
		}
		$result =  $db->query($sql);
		$rs=$db->fetch_array($result);
		
		$RetContent = "<div class='popup'>\r\n";
		//header
		$RetContent .= "\t<div class='popup-header'>\r\n";
		$RetContent .= "\t\t\t<h2>查看消息</h2>\r\n";
		$RetContent .= "\t\t\t<a href='javascript:;' onclick='closerecord({$id})' title='关闭' class='close-link'>[关闭]</a>\r\n";
		$RetContent .= "\t\t\t<br clear='both' /> \r\n";
		$RetContent .= "\t</div>";
		//body
		$RetContent .= "\t<div class='popup-body'>\r\n";
		$RetContent .= "\t\t<div class='table'>\r\n";
		$RetContent .= "\t\t\t<table class='table_list' cellspacing='0px' style='border-collapse:collapse;border:1px;width:500;height:400;'>\r\n";
		$RetContent .= "\t\t\t\t<tr><td width=100>时间</td><td width='350' style='text-align:left;'>{$rs['time']}</td>";
		$RetContent .= "\t\t\t\t<tr><td>{$usertips}</td><td style='text-align:left;'>{$rs['userid']}</td>";
		$RetContent .= "\t\t\t\t<tr><td>标题</td><td style='text-align:left;'>" . ChangeEncodeG2U($rs['title']) ."</td></tr>\r\n";
		$RetContent .= "\t\t\t\t<tr><td>内容</td><td style='text-align:left;'>" . ChangeEncodeG2U($rs['mag']) ."</td></tr>\r\n";
		if($t != "send")
		{
			 $RetContent .= "\t\t\t\t<tr><td></td><td><a href=\"javascript:closerecord({$id});openrecord('0',500,400,'smbinfo.php?act=sendmsgbox&id={$id}')\"><input type='button' id='btnReplyMsg' class='btn-1' value='回复' /></a></td></tr>\r\n"; 
		}
		$RetContent .= "\t\t\t</table>\r\n";
		
		$RetContent .= "\t\t</div>\r\n";
		$RetContent .= "\t</div>";
		//更新已读
		if($rs['look'] == 0)
		{
			$sql = "update msg set look = 1 where id = '{$id}'";
			$result =  $db->query($sql);
		}
		
		echo $RetContent;
		exit;
	}
	/* 取系统消息内容
	*
	*/
	function GetSysMessageContent()
	{
		global $db;
		$id = intval($_GET['id']);
		
		$sql = "select title,mag,time from msg where id = '{$id}'";
		$result =  $db->query($sql);
		$rs=$db->fetch_array($result);
		
		$RetContent = "<div class='popup'>\r\n";
		//header
		$RetContent .= "\t<div class='popup-header'>\r\n";
		$RetContent .= "\t\t\t<h2>" . ChangeEncodeG2U($rs['title']) . "</h2>\r\n";
		$RetContent .= "\t\t\t<a href='javascript:;' onclick='closerecord({$id})' title='关闭' class='close-link'>[关闭]</a>\r\n";
		$RetContent .= "\t\t\t<br clear='both' /> \r\n";
		$RetContent .= "\t</div>";
		//body
		$RetContent .= "\t<div class='popup-body'>\r\n";
		$RetContent .= "\t\t<div>\r\n";
		$RetContent .= "\t\t\t<table class='table table-hover table-striped table-bordered'>\r\n";
		$RetContent .= "\t\t\t\t<tr><td><strong>" . ChangeEncodeG2U($rs['title']) ."</strong> <span class='pull-right'>".date("Y-m-d",strtotime($rs['time']))."</span></td></tr>\r\n";
		$RetContent .= "\t\t\t\t<tr><td style='text-align:left; text-indent:20px;'>".ChangeEncodeG2U($rs['mag'])."</td></tr>\r\n";
		$RetContent .= "\t\t\t</table>\r\n";
		
		$RetContent .= "\t\t</div>\r\n";
		$RetContent .= "\t</div>";
		
		echo $RetContent;
		exit;
	}
	/*
	 * 推广人数
	 * 
	 */
	function extenContent()
	{
		global $db;
		$id = intval($_GET['id']);
	
		$sql = "select title,mag,time from msg where id = '{$id}'";
		$result =  $db->query($sql);
		$rs=$db->fetch_array($result);
	
		$RetContent = "<div class='popup'>\r\n";
		//header
		$RetContent .= "\t<div class='popup-header'>\r\n";
		$RetContent .= "\t\t\t<h2>" . ChangeEncodeG2U($rs['title']) . "</h2>\r\n";
		$RetContent .= "\t\t\t<a href='javascript:;' onclick='closerecord({$id})' title='关闭' class='close-link'>[关闭]</a>\r\n";
		$RetContent .= "\t\t\t<br clear='both' /> \r\n";
		$RetContent .= "\t</div>";
		//body
		$RetContent .= "\t<div class='popup-body'>\r\n";
		$RetContent .= "\t\t<div>\r\n";
		$RetContent .= "\t\t\t<table class='table table-hover table-striped table-bordered'>\r\n";
		$RetContent .= "\t\t\t\t<tr><td><strong>" . ChangeEncodeG2U($rs['title']) ."</strong> <span class='pull-right'>".date("Y-m-d",strtotime($rs['time']))."</span></td></tr>\r\n";
		$RetContent .= "\t\t\t\t<tr><td style='text-align:left; text-indent:20px;'>".ChangeEncodeG2U($rs['mag'])."</td></tr>\r\n";
		$RetContent .= "\t\t\t</table>\r\n";
	
		$RetContent .= "\t\t</div>\r\n";
		$RetContent .= "\t</div>";
	
		echo $RetContent;
		exit;
	}
	
	
	/* 取得投注流水
	 *
	*/
	function GetPressLogList()
	{
		global $db;
		$pages = isset($_GET['pages'])?$_GET['pages']:1;
		$pagem = isset($_GET['pagem'])?$_GET['pagem']:1;
		$pages = intval($pages);
		$pagem = intval($pagem);
		$pagesizes = 20;
		$pagesizem = 6;
		$d = empty($_GET['d'])?0:1;
		$ischked_0 = $d==0?"checked":"";
		$ischked_1 = $d==1?"checked":"";
		
		//表内容
		$gameNames = GetGameNames();
		$sql = "SELECT count(*) as cnt,sum(totalscore) as totalscore FROM `presslog` WHERE uid={$_SESSION['usersid']} and to_days(now())-to_days(presstime)={$d}";
		$result = $db->query($sql);
		if($rs=$db->fetch_array($result)){
			$TotalRecCount = $rs['cnt'];
			$totalamount = $rs['totalscore'];
		}else{
			$TotalRecCount = 0;
			$totalamount = 0;
		}
		
		
		$sql = "SELECT gametype,no,totalscore,presstime FROM `presslog` WHERE uid={$_SESSION['usersid']} and to_days(now())-to_days(presstime)={$d} ";
		$sql .= GetLimit($pages,$pagesizes);
		$result =  $db->query($sql);
		$RetContentTmp = "";
		while($rs=$db->fetch_array($result)){
			$RetContentTmp .= "\t\t\t\t<tr>\r\n";
			$RetContentTmp .= "\t\t\t\t\t<td>".$gameNames[$rs['gametype']] . "</td>\r\n";
			$RetContentTmp .= "\t\t\t\t\t<td>{$rs['no']}</td>\r\n";
			$RetContentTmp .= "\t\t\t\t\t<td style='text-align:right;'>".number_format($rs['totalscore'])."</td>\r\n";
			$RetContentTmp .= "\t\t\t\t\t<td>{$rs['presstime']}</td>\r\n";
			$RetContentTmp .= "\t\t\t\t</tr>\r\n";
		}
		
		$RetContent .= "<div class='panel panel-default'>";
		$RetContent .= "<div class='panel-heading'><label><input type='radio' name='d' id='d' value='1' onclick=\"getPresslogContent('smbinfo.php','get_press_log' ,1)\"  ".$ischked_1.">昨天(00:00:00~23:59:59)</label>  <label><input type='radio' name='d' id='d' value='0' onclick=\"getPresslogContent('smbinfo.php','get_press_log' ,0)\" ".$ischked_0.">今天(00:00:00~23:59:59)</label>   投注流水,总额：<font style='color:red'><strong>" . number_format($totalamount) . "</strong></font></div>";
		$RetContent .= "<div class='panel-body'>";
		$RetContent .= "<table class='table table-striped table-hover table-bordered'>";
		$RetContent .= "<tr>";
		$RetContent .= "<th>游戏类型</th>";
		$RetContent .= "<th>期号</th>";
		$RetContent .= "<th>投注分数</th>";
		$RetContent .= "<th>投注时间</th>";
		$RetContent .= "</tr>";
		
		$RetContent .= $RetContentTmp;
		
		$RetContent .= "</table>";
		//分页
		if($TotalRecCount > $pagesizes)
		{
			$divPage = "<div class='pagediv'></div><div class='Paging'>\r\n";
			require_once('inc/fenye.php');
			$ajaxpage=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesizes,'ajax'=>"ajax_page_press",'nowindex' => $pages));
			$divPage .= $ajaxpage->show();
			$divPage .= "</div>\r\n";
			$RetContent .= $divPage;
		}
		$RetContent .= "</div>";
		$RetContent .= "</div>";
	
		echo $RetContent;
		exit;
	
	}
	
	
	
	
	/* 取得消息页面
	*
	*/
	function GetMessageList()
	{
		global $db;
		$pages = isset($_GET['pages'])?$_GET['pages']:1;
		$pagem = isset($_GET['pagem'])?$_GET['pagem']:1;
		$pages = intval($pages);
		$pagem = intval($pagem);
		$pagesizes = 5;
		$pagesizem = 6;

/*        
		//系统信息
		$RetContent = "<div class='message'>\r\n";//message开始
		$RetContent .= "\t<div class='r_nav'>\r\n";//r_nav开始
		$RetContent .= "\t\t<img src='img/banner.png' />\r\n";
		$RetContent .= "\t\t<p class='title'>站内消息</p>\r\n";
		$RetContent .= "\t\t<p class='cen'>系统消息查阅</p>\r\n";
		$RetContent .= "\t</div>";//r_nav结束
		
		$RetContent .= "\t<div class='m_list'>\r\n";//m_list开始
		$RetContent .= "\t\t<div class='m_val'>\r\n";
		$RetContent .= "\t\t\t<span class='title'>系统消息</span>";
		$RetContent .= "\t\t\t<span class='pist'></span>";
		$RetContent .= "\t\t</div>\r\n";
		//表头
		$RetContent .= "\t\t<div class='list'>\r\n";
		$RetContent .= "\t\t\t<table width='664' cellspacing='0px' style='border-collapse:collapse;'>";
		$RetContent .= "\t\t\t\t<tr height='30'>\r\n";
		$RetContent .= "\t\t\t\t\t<th width='520' align='left'>标题</th>\r\n";
		$RetContent .= "\t\t\t\t\t<th align='left' >时间</th>\r\n";
		$RetContent .= "\t\t\t\t</tr>\r\n";
		//表内容
		$sql = "SELECT count(*) FROM msg WHERE usersid = 0";
		$TotalRecCount = $db->GetRecordCount($sql);
		$sql = "SELECT id,title,`time`,look FROM msg WHERE usersid = 0 order by time desc ";
		$sql .= GetLimit($pages,$pagesizes); 
		$result =  $db->query($sql);
		while($rs=$db->fetch_array($result)){
			$RetContent .= "\t\t\t\t<tr height='25'>\r\n";
			$RetContent .= "\t\t\t\t\t<td class='indent'><a href=\"javascript:openrecord('{$rs['id']}',600,400,'smbinfo.php?act=msgsyscontent&id={$rs['id']}');\">". ChangeEncodeG2U($rs['title']) . "</a></td>\r\n";
			$RetContent .= "\t\t\t\t\t<td class='center'>{$rs['time']}</td>\r\n";
			$RetContent .= "\t\t\t\t</tr>\r\n";
		}
		$RetContent .= "\t\t\t</table>\r\n";
		//分页
		if($TotalRecCount > $pagesizes)
		{
			$divPage = "<div class='pagediv'></div><div class='Paging'>\r\n";
			require_once('inc/fenye.php');
			$ajaxpage=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesizes,'ajax'=>"ajax_page_sm",'nowindex' => $pages));
			$divPage .= $ajaxpage->show();
			$divPage .= "</div>\r\n";
			$RetContent .= $divPage;
		}
		
		$RetContent .= "\t\t</div>\r\n";
		$RetContent .= "\t</div>\r\n"; //m_list结束
		//用户信息
		$RetContent .= "\t<div class='m_list'>\r\n"; //m_list开始
		$RetContent .= "\t\t<div class='m_val'>\r\n";
		$RetContent .= "\t\t\t<span class='title'>用户消息</span>";
		$RetContent .= "\t\t\t<span class='link'><a href=\"javascript:getContent('smbinfo.php?t=receive','messagelist')\">收件箱</a>|<a href=\"javascript:getContent('smbinfo.php?t=send','messagelist')\">发件箱</a>|<a href=\"javascript:openrecord('0',500,400,'smbinfo.php?act=sendmsgbox&id=0');\">写信息</a></span>";
		$RetContent .= "\t\t</div>\r\n";
		
		$tipFromUser = "来自";
		$sqlCount = "select count(*) from msg where `mid` = '{$_SESSION['usersid']}' and `time` > date_add(now(),interval -30 day) and del=0";
		$sql = "select id,usersid as theuserid,title,look,`time` from msg where `mid` = {$_SESSION['usersid']} and `time` > date_add(now(),interval -30 day) and del=0 order by time desc";
		if($_GET["t"] == "send")
		{
			$tipFromUser = "发给";
			$sqlCount = "select count(*) from msg where `usersid` = '{$_SESSION['usersid']}' and `time` > date_add(now(),interval -30 day)";
			$sql = "select id,`mid` as theuserid,title,look,`time` from msg where `usersid` = '{$_SESSION['usersid']}' and `time` > date_add(now(),interval -30 day) and del=0 order by time desc";
		}
		//表头
		$RetContent .= "\t\t<div class='list'>\r\n";
		$RetContent .= "\t\t\t<table width='664' cellspacing='0px' style='border-collapse:collapse;'>";
		$RetContent .= "\t\t\t\t<tr height='30'>\r\n";
		$RetContent .= "\t\t\t\t\t<th width='400' align='left' colspan='3' >标题</th>\r\n";
		$RetContent .= "\t\t\t\t\t<th width='120' align='left'>{$tipFromUser}</th>\r\n";
		$RetContent .= "\t\t\t\t\t<th >时间<label id='msgtype' style='display:none'>" . str_check($_GET['t']) . "</label></th>\r\n";
		$RetContent .= "\t\t\t\t</tr>\r\n";
		//表内容
		$TotalRecCount = $db->GetRecordCount($sqlCount);
		$sql .= GetLimit($pagem,$pagesizem); 
		$result =  $db->query($sql);
		while($rs=$db->fetch_array($result)){
			$RetContent .= "\t\t\t\t<tr height='25'>\r\n";
			$RetContent .= "\t\t\t\t\t<td width='20'><input type='checkbox' name='cbxUMID' id='cbxUMID' value='{$rs['id']}' /></td>\r\n";
			$img = "img/mailxx.gif";
			if($rs['look'] == 1) $img = "img/message.png"; 
			$RetContent .= "\t\t\t\t\t<td width='20'><img id='imgMsg_{$rs['id']}' src='{$img}' /></td>\r\n";
			$RetContent .= "\t\t\t\t\t<td class='indent'><a href=\"javascript:$('#imgMsg_{$rs['id']}').attr('src','img/message.png');openrecord('{$rs['id']}',500,400,'smbinfo.php?act=msgusercontent&id={$rs['id']}&t=" . str_check($_GET["t"]) . "');\">". ChangeEncodeG2U($rs['title']) . "</a></td>\r\n";
			$RetContent .= "\t\t\t\t\t<td class='left'>{$rs['theuserid']}</td>\r\n"; 
			$RetContent .= "\t\t\t\t\t<td class='center'>{$rs['time']}</td>\r\n";
			$RetContent .= "\t\t\t\t</tr>\r\n";
		}
		$RetContent .= "\t\t\t</table>\r\n";

        
		//分页
		if($TotalRecCount > $pagesizem)
		{
			$divPage = "<div class='pagediv'></div><div class='Paging'>\r\n";
			require_once('inc/fenye.php');
			$ajaxpage=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesizem,'ajax'=>"ajax_page_um",'nowindex' => $pagem));
			$divPage .= $ajaxpage->show();
			$divPage .= "</div>\r\n";
			$RetContent .= $divPage;
		}
		//按钮
		if($TotalRecCount > 0)
		{
			$RetContent .= "\t\t\t<div class='btn'>\r\n";
			$RetContent .= "\t\t\t\t<ul class='Button'>\r\n";
			$RetContent .= "\t\t\t\t\t<li><a href=\"javascript:selectAll('cbxUMID',1);\">全选</a></li>";
			$RetContent .= "\t\t\t\t\t<li><a href=\"javascript:selectAll('cbxUMID',0);\">反选</a></li>"; 
			$RetContent .= "\t\t\t\t\t<li><a href=\"javascript:removeMsg('". str_check($_GET['t']) . "');\">删除</a></li>";
			$RetContent .= "\t\t\t\t</ul>\r\n";
			$RetContent .= "\t\t\t</div>\r\n";
		}
		
		$RetContent .= "\t</div>\r\n"; //m_list结束
		
		$RetContent .= "</div>\r\n";//message结束
		
*/
       $RetContent .= "<div class='panel panel-default'>"; 
       $RetContent .= "<div class='panel-heading'>系统消息</div>";
       $RetContent .= "<div class='panel-body'>";
       $RetContent .= "<table class='table table-striped table-hover table-bordered'>";
       $RetContent .= "<tr>";
       $RetContent .= "<th>标题</th>";
       $RetContent .= "<th>时间</th>";
       $RetContent .= "</tr>";
        //表内容
        $sql = "SELECT count(*) FROM msg WHERE usersid = 0";
        $TotalRecCount = $db->GetRecordCount($sql);
        $sql = "SELECT id,title,`time`,look FROM msg WHERE usersid = 0 order by time desc ";
        $sql .= GetLimit($pages,$pagesizes); 
        $result =  $db->query($sql);
        while($rs=$db->fetch_array($result)){
            $RetContent .= "\t\t\t\t<tr>\r\n";
            $RetContent .= "\t\t\t\t\t<td><a href=\"javascript:openrecord('{$rs['id']}',600,200,'smbinfo.php?act=msgsyscontent&id={$rs['id']}');\">". ChangeEncodeG2U($rs['title']) . "</a></td>\r\n";
            $RetContent .= "\t\t\t\t\t<td>{$rs['time']}</td>\r\n";
            $RetContent .= "\t\t\t\t</tr>\r\n";
        }     
       $RetContent .= "</table>";
        //分页
        if($TotalRecCount > $pagesizes)
        {
            $divPage = "<div class='pagediv'></div><div class='Paging'>\r\n";
            require_once('inc/fenye.php');
            $ajaxpage=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesizes,'ajax'=>"ajax_page_sm",'nowindex' => $pages));
            $divPage .= $ajaxpage->show();
            $divPage .= "</div>\r\n";
            $RetContent .= $divPage;
        }
       $RetContent .= "</div>";
       $RetContent .= "</div>";

       $RetContent .= "<div class='panel panel-default'>"; 
       $RetContent .= "<div class='panel-heading'>用户消息 <span class='pull-right'><a href=\"javascript:getContent('smbinfo.php?t=receive','messagelist')\">收件箱</a> | <a href=\"javascript:getContent('smbinfo.php?t=send','messagelist')\">发件箱</a></span></div>"; // | <a href=\"javascript:openrecord('0',500,400,'smbinfo.php?act=sendmsgbox&id=0');\">写信息</a>
       $RetContent .= "<div class='panel-body'>";

       $tipFromUser = "来自";
       $sqlCount = "select count(*) from msg where `mid` = '{$_SESSION['usersid']}' and `time` > date_add(now(),interval -30 day) and del=0";
       $sql = "select id,usersid as theuserid,title,look,`time` from msg where `mid` = {$_SESSION['usersid']} and `time` > date_add(now(),interval -30 day) and del=0 order by time desc";
       if($_GET["t"] == "send")
       {
       	$tipFromUser = "发给";
       	$sqlCount = "select count(*) from msg where `usersid` = '{$_SESSION['usersid']}' and `time` > date_add(now(),interval -30 day)";
       	$sql = "select id,`mid` as theuserid,title,look,`time` from msg where `usersid` = '{$_SESSION['usersid']}' and `time` > date_add(now(),interval -30 day) and del=0 order by time desc";
       }
       
       $RetContent .= "<table class='table table-striped table-hover table-bordered'>";
       $RetContent .= "<tr>";
       $RetContent .= "<th>标题</th>";
       $RetContent .= "<th>{$tipFromUser}</th>";
       $RetContent .= "<th>时间</th>";
       $RetContent .= "</tr>";
		//表内容
		$TotalRecCount = $db->GetRecordCount($sqlCount);
		$sql .= GetLimit($pagem,$pagesizem); 
		$result =  $db->query($sql);
		while($rs=$db->fetch_array($result)){
			$RetContent .= "\t\t\t\t<tr>\r\n";
			$RetContent .= "\t\t\t\t\t<td><input type='checkbox' name='cbxUMID' id='cbxUMID' value='{$rs['id']}' /></td>\r\n";
			$img = "img/mailxx.gif";
			if($rs['look'] == 1) $img = "img/message.png"; 
			$RetContent .= "\t\t\t\t\t<td><img id='imgMsg_{$rs['id']}' src='{$img}' /></td>\r\n";
			$RetContent .= "\t\t\t\t\t<td><a href=\"javascript:$('#imgMsg_{$rs['id']}').attr('src','img/message.png');openrecord('{$rs['id']}',500,400,'smbinfo.php?act=msgusercontent&id={$rs['id']}&t=" . str_check($_GET["t"]) . "');\">". ChangeEncodeG2U($rs['title']) . "</a></td>\r\n";
			$RetContent .= "\t\t\t\t\t<td>{$rs['theuserid']}</td>\r\n"; 
			$RetContent .= "\t\t\t\t\t<td>{$rs['time']}</td>\r\n";
			$RetContent .= "\t\t\t\t</tr>\r\n";
		}
		$RetContent .= "\t\t\t</table>\r\n";
		//分页
		if($TotalRecCount > $pagesizem)
		{
			$divPage = "<div class='pagediv'></div><div class='Paging'>\r\n";
			require_once('inc/fenye.php');
			$ajaxpage=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesizem,'ajax'=>"ajax_page_um",'nowindex' => $pagem));
			$divPage .= $ajaxpage->show();
			$divPage .= "</div>\r\n";
			$RetContent .= $divPage;
		}
		//按钮
		if($TotalRecCount > 0)
		{
			
			$RetContent .= "<div class=\"btn-group\">";
			$RetContent .= "<a class=\"btn btn-default\" href='javascript:selectAll('cbxUMID',1);' >全选</a>";
			$RetContent .= "<a class=\"btn btn-default\" href='javascript:selectAll('cbxUMID',0);' >反选</a>";
			$RetContent .= "<a class=\"btn btn-default\" href='javascript:removeMsg(" . str_check($_GET['t']) . "}');' >删除</a>";
			$RetContent .= "</div>";
		}
		echo $RetContent;
		exit;

	}



	/* 修改密码
	*
	*/
	function ChangePwd($t)
	{
		global $db,$web_pwd_encrypt_prefix;
		$OldPwd = isset($_POST['oldpwd'])?$_POST['oldpwd']:"";
		$NewPwd = isset($_POST['newpwd'])?$_POST['newpwd']:""; 
		$ip = usersip();
		$arrRet = array('cmd'=>'','msg'=>'');
		$pwdType = ($t=="login")?0:1;
		 
		if($OldPwd == "" || strlen($OldPwd) > 30)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "请输入原密码(0-30位)!";
			echo json_encode($arrRet);
			exit;
		}
		if($NewPwd == "" || strlen($NewPwd) > 30)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "请输入新密码(0-30位)!";
			echo json_encode($arrRet);
			exit;
		}
		$OldPwd = md5($web_pwd_encrypt_prefix . $OldPwd);
		$NewPwd = md5($web_pwd_encrypt_prefix . $NewPwd);
		$sql = "call web_user_changepwd({$_SESSION['usersid']},{$pwdType},'{$_SESSION['usersid']}','{$OldPwd}','{$NewPwd}','{$ip}')";
        //WriteLog($sql);
        $arr = $db->Mysqli_Multi_Query($sql);
        switch($arr[0][0]["result"])
        {
			case '0': //成功
				$arrRet['cmd'] = "ok";
				$arrRet['msg'] = "修改成功!";
				RefreshPoints();
				break;
			case '1': 
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = "原密码错误!";
				break;
			default:
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = "系统错误，执行失败!";
				break;
        }
        
        echo json_encode($arrRet);
		exit;
	}
	/* 取得修改密码界面
	*
	*/
	function GetChangePwdDetail()
	{
		global $db;
		$t = isset($_GET['t'])?str_check($_GET['t']):"login";
		$LoginClass = "";
		$BankClass = "";
		if($t == "login") 
			$LoginClass = "class='pitch'";
		else
			$BankClass = "class='pitch'";
		$RetContent = "<div class='Bank'>\r\n";//Bank开始
		$RetContent .= "\t<div class='r_nav'>\r\n";//r_nav开始
		$RetContent .= "\t\t<img src='img/banner.png' />\r\n";
		$RetContent .= "\t\t<p class='title'>密码修改</p>\r\n";
		$RetContent .= "\t\t<p class='cen'>可修改登录密码和安全密码</p>\r\n";
		$RetContent .= "\t</div>";//r_nav结束
		
		$RetContent .= "\t<div class='r_list_t'>\r\n";
		$RetContent .= "\t\t<ul>\r\n";
		$RetContent .= "\t\t\t<li {$LoginClass}><a href=\"javascript:getContent('smbinfo.php?t=login','changpwddetail')\">登录密码</a></li>\r\n";
		$RetContent .= "\t\t\t<li {$BankClass}><a href=\"javascript:getContent('smbinfo.php?t=bank','changpwddetail')\">安全密码</a></li>\r\n";
		$RetContent .= "\t\t</ul>\r\n";
		$RetContent .= "\t</div>\r\n";
		
		$RetContent .= "\t<div class='Money'>\r\n";
		$RetContent .= "\t\t<ul>\r\n";
		if($t == "login")
		{
			$RetContent .= "\t\t\t<li><span>原登录密码:</span><input type='password' id='txtOldLoginPwd' maxlength='50' /></li>\r\n";
			$RetContent .= "\t\t\t<li><span>新登录密码:</span><input type='password' id='txtNewLoginPwd' maxlength='50'/></li>\r\n";
			$RetContent .= "\t\t\t<li><span>确认新密码:</span><input type='password' id='txtRNewLoginPwd' maxlength='50'/></li>\r\n";
			$RetContent .= "\t\t\t<li class='text-center'><input type='button' id='btnChangeLoginPwd' class='btn-1' value='马上修改' /></li>\r\n";
		}
		else
		{
			$RetContent .= "\t\t\t<li><span>原安全密码:</span><input type='password' id='txtOldBankPwd' maxlength='50' />原始密码与登录密码相同
            <br><a   href=\"javascript:getContent('smbinfo.php','get_back_pwd')\">忘记安全密码</a></li>\r\n";
			$RetContent .= "\t\t\t<li><span>新安全密码:</span><input type='password' id='txtNewBankPwd' maxlength='50'/></li>\r\n";
			$RetContent .= "\t\t\t<li><span>确认新密码:</span><input type='password' id='txtRNewBankPwd' maxlength='50'/></li>\r\n";
			$RetContent .= "\t\t\t<li class='text'><span> </span><input type='button' id='btnChangeBankPwd' class='btn-1' value='马上修改' /></li>\r\n";
		}
		$RetContent .= "\t\t</ul>\r\n";
		$RetContent .= "\t</div>\r\n";
		
		
		$RetContent .= "</div>\r\n";//Bank结束
		
		echo $RetContent;
		exit;
	}
	/* 操作记录
	*
	*/
	function GetActionLog()
	{
		global $db;
		$page = isset($_GET['page'])?$_GET['page']:1;
		$page =intval($page);
		$pagesize = 15;
		$RetContent = "<div class='message'>\r\n";//message开始
		$RetContent .= "\t<div class='r_nav'>\r\n";//r_nav开始
		$RetContent .= "\t\t<img src='img/banner.png' />\r\n";
		$RetContent .= "\t\t<p class='title'>操作记录</p>\r\n";
		$RetContent .= "\t\t<p class='cen'>详细操作记录</p>\r\n";
		$RetContent .= "\t</div>";//r_nav结束
		
		$RetContent .= "\t<div class='m_list'>\r\n";
		$RetContent .= "\t\t<div class='m_val'>\r\n";
		$RetContent .= "\t\t\t<span class='title'>操作记录</span>";
		$RetContent .= "\t\t\t<span class='pist'>注:仅保留30天记录</span>";
		$RetContent .= "\t\t</div>\r\n";
		//表头
		$RetContent .= "\t\t<div class='list'>\r\n";
		$RetContent .= "\t\t\t<table width='664' cellspacing='0px' style='border-collapse:collapse;'>";
		$RetContent .= "\t\t\t\t<tr height='30'>\r\n";
		$RetContent .= "\t\t\t\t\t<th width='150' align='left' >时间</th>\r\n";
		$RetContent .= "\t\t\t\t\t<th align='left' width='120' >操作IP</th>\r\n";
		$RetContent .= "\t\t\t\t\t<th width='100' align='left' >类型</th>\r\n";
		$RetContent .= "\t\t\t\t\t<th align='left' >动作</th>\r\n";
		$RetContent .= "\t\t\t\t</tr>\r\n";
		//表内容
		$sql = "select count(*) from userslog where usersid = '{$_SESSION['usersid']}' and time > date_add(now(),interval -30 day)";
		$TotalRecCount = $db->GetRecordCount($sql);
		$sql = "select logtype,time,ip,log from userslog where usersid = '{$_SESSION['usersid']}' and time > date_add(now(),interval -30 day) order by time desc ";
		$sql .= GetLimit($page,$pagesize); 
		$result =  $db->query($sql);
		while($rs=$db->fetch_array($result)){
			$RetContent .= "\t\t\t\t<tr height='25'>\r\n";
			$RetContent .= "\t\t\t\t\t<td>{$rs['time']}</td>\r\n";
			$RetContent .= "\t\t\t\t\t<td><a href='http://www.ip138.com/ips.asp?ip={$rs['ip']}' target='_blank'>". $rs['ip'] ."</a></td>\r\n"; 
			
			$OprType = "";
			switch($rs['logtype'])
			{
				case 1:
					$OprType = "广告奖励";
					break;
				case 2:
					$OprType = "在线充值";
					break;
				case 3:
					$OprType = "奖罚记录";
					break;
				case 4:
					$OprType = "登录";
					break;
				case 10:
					$OprType = "改密码";
					break;
				case 11:
					$OprType = "银行存取";
					break;
				case 12:
					$OprType = "充值体验卡";
					break;
				case 13:
					$OprType = "银行转账";
					break;
				case 14:
					$OprType = "领取救济";
					break;
				case 15:
					$OprType = "兑奖点卡";
					break;
				default:
					$OprType = "其他";
					break;
			}
			$RetContent .= "\t\t\t\t\t<td>". $OprType ."</td>\r\n";
			$RetContent .= "\t\t\t\t\t<td>". ChangeEncodeG2U($rs['log']) ."</td>\r\n";
			$RetContent .= "\t\t\t\t</tr>\r\n";
		}
		$RetContent .= "\t\t\t</table>\r\n";
		//分页
		if($TotalRecCount > 15)
		{
			$divPage = "<div class='pagediv'></div><div class='Paging'>\r\n";
			require_once('inc/fenye.php');
			$ajaxpage=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesize,'ajax'=>"ajax_page_al",'nowindex' => $page));
			$divPage .= $ajaxpage->show();
			$divPage .= "</div>\r\n";
			$RetContent .= $divPage;
		}
		
		$RetContent .= "\t\t</div>\r\n";
		$RetContent .= "\t</div>\r\n";
		
		$RetContent .= "</div>\r\n";//message结束
		
		echo $RetContent;
		exit;
	}
	
	/*
	 * 交易记录
	 */
	function transaction(){
		$RetContent .= "\t\t<div class='panel panel-default'>\r\n";
        $RetContent .= "<div class='panel-heading'>交易明细</div>";
        $RetContent .= "<div class='panel-body'>";
        $RetContent .= "<table class='table table-striped table-hover table-hover table-bordered'>";
        $RetContent .= "<tr>";
        $RetContent .= "<th>时间</th>";
        $RetContent .= "<th>类型</th>";
        $RetContent .= "<th>数量</th>";
        $RetContent .= "<th>操作后额度</th>";
        $RetContent .= "</tr>";
        $RetContent .= "<tr>";
        $RetContent .= "<td>2015-10-10</td>";
        $RetContent .= "<td>充值</td>";
        $RetContent .= "<td>100，000</td>";
        $RetContent .= "<td>100，000</td>";
        $RetContent .= "</tr>";
        $RetContent .= "</table>\r\n";
        $RetContent .= "</div>\r\n";
        $RetContent .= "</div>\r\n";
        echo $RetContent;
        exit;
	}
	
	
	/* 转账
	*
	*/
	function TransScore()
	{
		global $db,$web_pwd_encrypt_prefix;
		$Score = isset($_POST['score'])?str_check($_POST['score']):"0"; 
		$TargetID = isset($_POST['targetid'])?str_check($_POST['targetid']):"0";
		$Pwd = isset($_POST['pwd'])?str_check($_POST['pwd']):"";
		$Pwd = md5($web_pwd_encrypt_prefix . $Pwd);
		
		$Score = intval($Score);
		$TargetID = intval($TargetID);
		$ip = usersip();
		$arrRet = array('cmd'=>'ok','msg'=>'');
		
		if(!is_numeric($Score) || $Score < 0)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "数量必须为正整数!";
			echo json_encode($arrRet);
			exit;
		}
		if($Score > 99999999999)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "转账数量超出上限!";
			echo json_encode($arrRet);
			exit;
		}
		if(!is_numeric($TargetID) || $TargetID > 9999999999)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "对方数字ID错误!";
			echo json_encode($arrRet);
			exit;
		}
		if($TargetID == $_SESSION['usersid'])
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "不能自己给自己转账!";
			echo json_encode($arrRet);
			exit;
		}
		
        $sql = "call web_trans_score({$_SESSION['usersid']},{$TargetID},'{$Pwd}',{$Score},'{$ip}')";
        //WriteLog($sql);
        $arr = $db->Mysqli_Multi_Query($sql);
        switch($arr[0][0]["result"])
        {
			case '0': //成功
				$arrRet['cmd'] = "ok";
				$arrRet['msg'] = "转账成功!";
				RefreshPoints();
				break;
			case '1': 
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = "对方数字ID不存在!";
				break;
			case '2': 
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = "银行余额不足!";
				break;
			case '3':
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = "银行密码错误(注:初始密码与登录密码相同)!";
				break;
			case '4':
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = "转账值小于最小值!";
				break;
			default:
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = "系统错误，执行失败!";
				break;
        }
        
        echo json_encode($arrRet);
		exit;
	}
	/* 检测对方ID
	*
	*/
	function CheckTargetID()
	{
		global $db;
		$TargetID = isset($_POST['targetid'])?str_check($_POST['targetid']):"";
		$TargetID = intval($TargetID);
		$sql = "select nickname from users where id = '{$TargetID}'";
		$result =  $db->query($sql);
		$msg = "对方数字ID错误!";
		if($rs=$db->fetch_array($result)){
			$msg = "对方数字ID可用，昵称是:" . ChangeEncodeG2U($rs['nickname']);
		}
		$arrRet = array('cmd'=>'','msg'=>'');
		$arrRet['cmd'] = "err";
        $arrRet['msg'] = $msg;
        echo json_encode($arrRet);
		exit; 
	}
	/* 充值体验卡
	*
	*/
	function PayCards()
	{
		global $db;
		$CardIDs = isset($_POST['cardids'])?str_check($_POST['cardids']):""; 
		$Vcode = isset($_POST['vcode'])?str_check($_POST['vcode']):"";
		$ip = usersip();
		$arrRet = array('cmd'=>'','msg'=>''); 
		if($Vcode != $_SESSION["CheckNum"]) //验证码错误
        {
            $arrRet['cmd'] = "err";
            $arrRet['msg'] = "验证码错误!";
            echo json_encode($arrRet);
			exit; 
        }
        $msg = "";
        $okCount = 0;
        $errCount = 0;
        $arrCards = explode("\n",$CardIDs);
        for($i = 0; $i < count($arrCards); $i++)
        {
			$arrOneCard = explode(" ",$arrCards[$i]);
			if(count($arrOneCard) == 2)
			{
				$cardID = $arrOneCard[0];
				$cardPwd = $arrOneCard[1];
				$sql = "call web_pay_cards({$_SESSION['usersid']},'{$cardID}','{$cardPwd}','{$ip}')";
		        WriteLog($sql);
		        $arr = $db->Mysqli_Multi_Query($sql);
		        switch($arr[0][0]["result"])
		        {
					case '0': //成功
						$msg .= $arrCards[$i] . " 充值成功\n";
						$okCount++;
						break;
					case '1': 
						$msg .= $arrCards[$i] . " 卡号错误\n";
						$errCount++;
						break;
					case '2':
						$msg .= $arrCards[$i] . " 卡密错误\n";
						$errCount++;
						break;
					case '3':
						$msg .= $arrCards[$i] . " 卡已使用过\n";
						$errCount++;
						break;
					case '4':
						$msg .= $arrCards[$i] . " 卡已被冻结\n";
						$errCount++;
						break;
					case '5':
						$msg .= $arrCards[$i] . " 卡有效期已过\n";
						$errCount++;
						break;
					case '6':
						$msg .= $arrCards[$i] . " 卡类型错误\n";
						$errCount++;
						break;
					case '7':
						$msg .= $arrCards[$i] . " 卡只允许vip充值\n";
						$errCount++;
						break;
					default:
						$msg .= $arrCards[$i] . " 系统错误，充值失败\n";
						$errCount++;
						break;
		        }
		        unset($arr);
			}
			else
			{
				$msg .= $arrCards[$i] . " 卡格式错误!\n";
				$errCount++;
			}
			unset($arrOneCard);
        }
        $msg = "总共". count($arrCards) . "张卡，充值成功" . $okCount . "张，失败". $errCount . "张\n" . $msg; 
        $arrRet['cmd'] = "err";
        $arrRet['msg'] = $msg;
        echo json_encode($arrRet);
		exit; 
	}
	/* 取得充值卡界面
	*
	*/ 
	function GetPayCardDetail()
	{
		$RetContent = "<div class='replace'>\r\n";//replace开始
		$RetContent .= "\t<div class='r_nav'>\r\n";//r_nav开始
		$RetContent .= "\t\t<img src='img/banner.png' />\r\n";
		$RetContent .= "\t\t<p class='title'>体验卡充值</p>\r\n";
		$RetContent .= "\t\t<p class='cen'>可对单张或多张体验卡一次性充值</p>\r\n";
		$RetContent .= "\t</div>";//r_nav结束 
		
		$RetContent .= "\t<div class='r_list'>\r\n";
		$RetContent .= "\t\t<ul>\r\n";
		$RetContent .= "\t\t\t<li class='Asecret'><span>卡密输入:</span><textarea id='txtCardIDs' ></textarea></li>\r\n";
		$RetContent .= "\t\t\t<li class='yzm'><span>验证码:</span><input type='text' id='txtValidCode' maxlength='4' ><img alt='看不清请点击更换'  src='vcode.php'  onclick=\"this.src='vcode.php?t='+ Math.round(Math.random() * 10000)\" /></li>\r\n";
		$RetContent .= "\t\t\t<li class='button'><span>　 </span><input type='button' id='btnPayCards' class='btn-1' value='确定使用' /></li>\r\n";
		$RetContent .= "\t\t</ul>\r\n";
		$RetContent .= "\t\t<img src='img/Example.png' class='Example'>";
		$RetContent .= "\t</div>\r\n";
		
		
		$RetContent .= "</div>\r\n";//replace结束
		
		echo $RetContent;
		exit;
	}
	/* 取得乐豆明细
	*
	*/
	function GetScoreDetail()
	{
		global $db;
		$page = isset($_GET['page'])?$_GET['page']:1;
		$page =intval($page);
		$pagesize = 15;
		
		$RetContent .= "<div class='panel panel-default'>";
		$RetContent .= "<div class='panel-heading'>乐豆明细,银行存取、充值、转账等记录</div>";
		$RetContent .= "<div class='panel-body'>";
		
		
		$RetContent .= "<div class='message   recharge' style='width:100%'>";
		$RetContent .= "<div class='m_list' style='width:100%'><span class='pist'>注:仅保留30天记录</span>";
		$RetContent .= "\t<div class='list' style='width:100%;padding:0px;border-style: none;'>\r\n";
		$RetContent .= "\t\t\t<table class='table_list' style='width:100%' cellspacing='0px' style='border-collapse:collapse;'>";
		$RetContent .= "\t\t\t\t<tr height='30'>\r\n";
		$RetContent .= "\t\t\t\t\t<th width='120' align='left' >时间</th>\r\n";
		$RetContent .= "\t\t\t\t\t<th width='100' align='left' >类型</th>\r\n";
		$RetContent .= "\t\t\t\t\t<th width='60' align='left' >数量</th>\r\n";
		$RetContent .= "\t\t\t\t\t<th width='100' align='left' >操作后账户额度</th>\r\n";
		$RetContent .= "\t\t\t\t\t<th width='100' align='left' >操作后银行额度</th>\r\n";
		$RetContent .= "\t\t\t\t\t<th align='left'>备注</th>\r\n";
		$RetContent .= "\t\t\t\t</tr>\r\n";
		//表内容
		$sql = "select count(*) from score_log where uid = '{$_SESSION['usersid']}' and log_time > date_add(now(),interval -30 day)";
		$TotalRecCount = $db->GetRecordCount($sql);
		$sql = "select opr_type,amount,log_time,ip,remark,points,bankpoints from score_log where uid = '{$_SESSION['usersid']}' and log_time > date_add(now(),interval -30 day) order by log_time desc ";
		$sql .= GetLimit($page,$pagesize); 
		$result =  $db->query($sql);
		while($rs=$db->fetch_array($result)){
			$rs['points']=number_format($rs['points']);
			$RetContent .= "\t\t\t\t<tr height='25'>\r\n";
			$RetContent .= "\t\t\t\t\t<td>{$rs['log_time']}</td>\r\n";
			$OprType = "";
			switch($rs['opr_type'])
			{
				case 0:
					$OprType = "<font color='#0000FF'>存豆</font>";
					break;
				case 1:
					$OprType = "<font color='#FF3300'>取豆</font>";
					break;
				case 2:
					$OprType = "<font color='#FF66CC'>充值体验卡</font>";
					break;
				case 3:
					$OprType = "<font color='#FF44CC'>转账入</font>";
					break;
				case 4:
					$OprType = "<font color='#FF55CC'>转账出</font>";
					break;
				case 5:
					$OprType = "<font color='red'>在线充值</font>";
					break;
				case 6:
					$OprType = "<font color='red'>领取救济</font>";
					break;
				case 7:
					$OprType = "<font color='red'>兑奖点卡</font>";
					break;
				case 9:
					$OprType = "<font color='red'>投注退还</font>";
					break;
				case 10:
					$OprType = "<font color='red'>在线提现</font>";
					break;
				case 12:
					$OprType = "<font color='red'>提现退回</font>";
					break;
				case 20:
					$OprType = "<font color='red'>亏损返利</font>";
					break;
				case 21:
					$OprType = "<font color='red'>推荐收益</font>";
					break;
				case 40:
					$OprType = "<font color='red'>收发红包</font>";
					break;
				case 55:
					$OprType = "<font color='red'>系统充值</font>";
					break;
				case 70:
					$OprType = "<font color='red'>轮盘抽奖</font>";
					break;
				case 80:
					$OprType = "<font color='red'>排行榜奖励</font>";
					break;	
				default:
					$OprType = "<font color='#FF7733'>其他</font>";
					break;
			}
			$RetContent .= "\t\t\t\t\t<td>". $OprType ."</td>\r\n"; 
			$RetContent .= "\t\t\t\t\t<td>". Trans($rs['amount']) ."</td>\r\n";
			$RetContent .= "\t\t\t\t\t<td>". $rs['points'] ."</td>\r\n";
			$RetContent .= "\t\t\t\t\t<td>". $rs['bankpoints'] ."</td>\r\n"; 
			$RetContent .= "\t\t\t\t\t<td>". ChangeEncodeG2U($rs['remark']) ."</td>\r\n";
			$RetContent .= "\t\t\t\t</tr>\r\n";
		}
		$RetContent .= "\t\t\t</table>\r\n";
		//分页
		if($TotalRecCount > 15)
		{
			$divPage = "<div class='pagediv'></div><div class='Paging'>\r\n";
			require_once('inc/fenye.php');
			$ajaxpage=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesize,'ajax'=>"ajax_page_sl",'nowindex' => $page));
			$divPage .= $ajaxpage->show();
			$divPage .= "</div>\r\n";
			$RetContent .= $divPage;
		}
		
		$RetContent .= "\t\t</div>\r\n";
		$RetContent .= "\t</div>\r\n";
		$RetContent .= "\t</div>\r\n";
		$RetContent .= "\t</div>\r\n";
		$RetContent .= "</div>\r\n";//message结束
		
		echo $RetContent;
		exit;
	}
	/* 处理存取豆
	*
	*/
	function ProcessScore($t)
	{
		global $db,$web_pwd_encrypt_prefix;
		$Score = isset($_POST['score'])?str_check($_POST['score']):"0"; 
		$Pwd = isset($_POST['pwd'])?str_check($_POST['pwd']):"";
		$Pwd = md5($web_pwd_encrypt_prefix . $Pwd); 
		$oprType = ($t=="save")?0:1;
		$ip = usersip();
		$arrRet = array('cmd'=>'ok','msg'=>'');
		
		$Score = intval($Score);
		if(!is_numeric($Score) || $Score < 0)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "数量必须为正整数!";
			echo json_encode($arrRet);
			exit;
		}
		
        $sql = "call web_score_process({$_SESSION['usersid']},{$oprType},'{$Pwd}',{$Score},'{$ip}')";
        //WriteLog($sql);
        $arr = $db->Mysqli_Multi_Query($sql);
        switch($arr[0][0]["result"])
        {
			case '0': //成功
				$arrRet['cmd'] = "ok";
				$arrRet['msg'] = "操作成功!";
				RefreshPoints();
				break;
			case '1': 
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = "余额不足!";
				break;
			case '2':
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = "银行密码错误(注:初始密码与登录密码相同)!";
				break;
			default:
				$arrRet['cmd'] = "err";
				$arrRet['msg'] = "系统错误，执行失败!";
				break;
        }
        
        echo json_encode($arrRet);
		exit;
	}
	
	function GetAllScore(){
		global $db;
		$uid = (int)$_SESSION['usersid'];
		$sql = "select points,back from users where id={$uid} LIMIT 1";
		$result =  $db->query($sql);
		$row=$db->fetch_array($result);
		if(empty($row)){
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "执行失败!";
		}else{
			$arrRet['cmd'] = "ok";
			$arrRet['data']['points'] = $row['points'];
			$arrRet['data']['back'] = $row['back'];
		}
	
		echo json_encode($arrRet);
		exit;
	}
	
	/* 取得我的银行资料
	*
	*/
	function GetUserBankInfo()
	 {

		global $db;
         RefreshPoints();
        $RetContent .= "<div id='bank'>";
        $RetContent .= "<div class='panel panel-default'>";
        $RetContent .= "<div class='panel-heading'>我的银行存取、转账</div>";   
        //$RetContent .= "<div class='panel-body'></div>";
        $RetContent .= "<p>您当前账户流动乐豆为:<i>". Trans($_SESSION['points']) ."</i>乐豆</p>";    
        $RetContent .= "<p>银行所存乐豆为:<i>" . Trans($_SESSION['bankpoints']) . "</i>乐豆</p>";  
        $RetContent .= "<p>温馨提示:<span>银行只能存取乐豆、转账乐豆</span></p>"; 
        $RetContent .= "</div>";
        
        //取手续费
        $odds = 0;
        $sql = "SELECT fldValue FROM sys_config WHERE fldVar = 'bank_trans_odds' LIMIT 1";
        $result =  $db->query($sql);
        if($row=$db->fetch_array($result))
        {
            $odds = $row['fldValue'];
        }
        
        $trans_score_min = "最小10000";
        $sql = "SELECT fldValue FROM sys_config WHERE fldVar = 'bank_trans_min' LIMIT 1";
        $result =  $db->query($sql);
        if($row=$db->fetch_array($result))
        {
            $trans_score_min = "最小" . $row['fldValue'];
        }
        $RetContent .= "<dl>";
        $RetContent .= "<dd>
            <div class=\"input-group\">
                <span class='input-group-addon'>要存乐豆:</span>
                <input type='text' id='txtSaveScore' class='form-control' placeholder='输入存豆数量' />
            </div>
        </dd>";
        $RetContent .= "<dd class='text-center'>
                <input type=\"button\"  class=\"btn btn-danger ff-in\" value=\"50元\" />
                <input type=\"button\"  class=\"btn btn-danger ff-in\" value=\"100元\" />
                <input type=\"button\"  class=\"btn btn-danger ff-in\" value=\"500元\" />
                <input type=\"button\"  class=\"btn btn-danger ff-in\" value=\"1000元\" />
                <input type=\"button\"  class=\"btn btn-danger ff-in\" value=\"5000元\" />
        		<input type=\"button\"  class=\"btn btn-danger ff-in\" value=\"10000元\" />
                <input type=\"button\"  class=\"btn btn-danger ff-in\" value=\"全部\" />
                <input type=\"button\"  class=\"btn btn-danger ff-in\" value=\"清除\" />
            	<input type=\"button\" id=\"btnSaveScore\" class=\"btn btn-danger\" value=\"存乐豆\" />
        </dd>";  
        $RetContent .= "<dd>
            <div class=\"input-group\">
                <span class='input-group-addon'>要取乐豆:</span>
                <input type='text' id='txtGetScore' class='form-control' placeholder='输入取豆数量' />
            </div>
        </dd>";      
        $RetContent .= "<dd>
            <div class=\"input-group\">
                <span class='input-group-addon'>银行密码:</span>
                <input type='password' id='txtBankPwd' class='form-control' placeholder='输入银行密码' />
            </div>
        </dd>"; 
        $RetContent .= "<dd class='text-center'>
                <input type=\"button\"  class=\"btn btn-danger ff-out\" value=\"50元\" />
                <input type=\"button\"  class=\"btn btn-danger ff-out\" value=\"100元\" />
                <input type=\"button\"  class=\"btn btn-danger ff-out\" value=\"500元\" />
                <input type=\"button\"  class=\"btn btn-danger ff-out\" value=\"1000元\" />
                <input type=\"button\"  class=\"btn btn-danger ff-out\" value=\"5000元\" />
        		<input type=\"button\"  class=\"btn btn-danger ff-out\" value=\"10000元\" />
                <input type=\"button\"  class=\"btn btn-danger ff-out\" value=\"全部\" />
                <input type=\"button\"  class=\"btn btn-danger ff-out\" value=\"清除\" />
        		<input type=\"button\" id=\"btnGetScore\" class=\"btn btn-danger\" value=\"取乐豆\" />
        </dd>"; 
        $RetContent .= "</dl>";
		$RetContent .= "</div>";
		
		
		$RetContent .= "<script type='text/javascript'>
				$(document).ready(function(){
		
				    $('.ff-in').click(function () {
				        var txtSaveScore=parseFloat($('#txtSaveScore').val());
				        if(isNaN(txtSaveScore))txtSaveScore=0;
		
				        var val=$(this).attr('value');
		
		
			            if(val=='清除'){
			                $('#txtSaveScore').val('');
			                return false;
			            }
		
				        if(val=='全部'){
				
				        	$.post('smbinfo.php',{act:'getallscore'},function(ret){
				        		switch(ret.cmd)
				        		{
				        			case 'ok':
				        				$('#txtSaveScore').val(ret.data.points);
				        				return;
				        			default:
				        				alert(ret.msg);
				        				return;
				        		}
				        	},'json');
				
							return;
				        }
		
				        $('#txtSaveScore').val(parseFloat(val.replace('元','')) * 1000 + txtSaveScore);
				    });
		
		
				    $('.ff-out').click(function () {
				        var txtGetScore=parseFloat($('#txtGetScore').val());
				        if(isNaN(txtGetScore))txtGetScore=0;
		
				        var val=$(this).attr('value');
		
		
			            if(val=='清除'){
			                $('#txtGetScore').val('');
			                return false;
			            }
		
				        if(val=='全部'){
				
				        	$.post('smbinfo.php',{act:'getallscore'},function(ret){
				        		switch(ret.cmd)
				        		{
				        			case 'ok':
				        				$('#txtGetScore').val(ret.data.back);
				        				return;
				        			default:
				        				alert(ret.msg);
				        				return;
				        		}
				        	},'json');
				
							return;
				        }
		
				        $('#txtGetScore').val(parseFloat(val.replace('元','')) * 1000 + txtGetScore);
				    });
		
		
				});
				</script>";
		
		echo $RetContent;
		exit;
	}
	/* 修改资料
	*
	*/
	function ChangeDetail()
	{
		global $db;  
		$NickName = isset($_POST['nickname'])?(str_check($_POST['nickname'])):"";
		$Head = isset($_POST['head'])?$_POST['head']:"1_0.jpg";
		$Mobile = isset($_POST['mobile'])?str_check($_POST['mobile']):"";
		$Email = isset($_POST['email'])?(str_check($_POST['email'])):"";
		$QQ = isset($_POST['qq'])?str_check($_POST['qq']):"";
		$Caption = isset($_POST['caption'])?(str_check($_POST['caption'])):"";
		$arrRet = array('cmd'=>'ok','msg'=>'');
		$recv_cash_name = isset($_POST['recv_cash_name'])?(str_check($_POST['recv_cash_name'])):"";
		$card = isset($_POST['card'])?(str_check($_POST['card'])):"";
		if($NickName == "" || strlen($NickName) > 20)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "昵称错误,长度不超过20位!";
			echo json_encode($arrRet);
			exit;
		}

		if($Mobile == "" || !is_numeric($Mobile) || strlen($Mobile) > 11)
		{
			$arrRet['cmd'] = "err";
			$arrRet['msg'] = "请输入常用的手机号码";
			echo json_encode($arrRet);
			exit;
		}
		$QQ = (strlen($QQ) > 20) ? substr($QQ,0,20) : $QQ;
        $Head=str_replace("1/","1",$Head);
        $Head=str_replace("0/","0",$Head);
        //if(strlen($Head)!=7)$Head='1_0.jpg';
		$Head = "img/head/" . $Head;
		$sql = "update users set nickname='{$NickName}',head='{$Head}',mobile='{$Mobile}',email='{$Email}',caption='{$Caption}' where id = '{$_SESSION['usersid']}'";
		//WriteLog($sql);
		 $_SESSION["head"] = $Head;
		$result =  $db->query($sql);
		if($recv_cash_name!=""){
			$sql = "update users set recv_cash_name='{$recv_cash_name}' where id = '{$_SESSION['usersid']}' and recv_cash_name='' ";
			$db->query($sql);
		}
		if($card!=""){
			$sql = "update users set card='{$card}' where id = '{$_SESSION['usersid']}' and card='' ";
			$db->query($sql);
		}
        if($QQ!=""){
            $sql = "update users set qq='{$QQ}' where id = '{$_SESSION['usersid']}' and qq='' ";
            $db->query($sql);
        }
		$arrRet['cmd'] = "ok";
		$arrRet['msg'] = "修改成功!";
		echo json_encode($arrRet);
		exit;
	}
	/* 取得我的资料
	*
	*/
	function GetMyDetail()
	{
		global $db;
		//取用户基本信息
		$sql = "select id,nickname,email,is_check_email,mobile,is_check_mobile,head,qq,points,back,card,
						caption,username,recv_cash_name,logintime,loginip,experience
				from users
				where id = '{$_SESSION['usersid']}'";
		$result =  $db->query($sql);
		$row=$db->fetch_array($result);
		
		$RetContent ="\t<ul class=\"nav nav-tabs\" style=\"margin:-1px 0 0 -1px;\">\r\n";
		$RetContent .="<li class=\"active\"><a href=\"#infomation\" data-toggle=\"tab\">个人资料</a></li>";
		$RetContent .="<li><a href=\"#login_pass\" data-toggle=\"tab\">修改登录密码</a></li>";
		$RetContent .="<li><a href=\"#safe_pass\" data-toggle=\"tab\">修改安全密码</a></li>";
		//$RetContent .="<li><a href=\"#area\" data-toggle=\"tab\">地区限制</a></li>";
		$RetContent .="</ul>";

		$RetContent .="<div class=\"tab-content\">";
        
        $RetContent .="<div class=\"tab-pane active\" id=\"infomation\">";
        $RetContent .= "<div class='info'>\r\n";//info开始
        $RetContent .= "\t<p class='greet'><span>您好，{$row['nickname']}</span><i> (ID:{$row['id']} 帐号:{$row['username']})</i></p>\r\n";

        $RetContent .= "</div>";//info结束
        

		
		$RetContent .= "\t<ul class='current'>\r\n";
		$RetContent .= "\t\t<li>您的当前乐豆:<i>". Trans($row['points']) . "</i> <a id='menu_scoredetail' href=\"javascript:getContent('smbinfo.php','transaction')\" style='display:none'>消费明细</a></li>\r\n";
        $RetContent .= "\t\t<li>最后一次登录时间:<em>{$row['logintime']}</em></li>\r\n";
		$RetContent .= "\t\t<li>您当前银行乐豆:<i>". Trans($row['back']) ."</i> 当前经验:<i>{$row['experience']}</i>\r\n";
        $RetContent .= "\t\t<li>最后一次登录IP:<em>{$row['loginip']}</em></li>\r\n";
		$RetContent .= "\t</ul>\r\n";
		
		$RetContent .= "<div class='replace'>\r\n";//replace开始

		
		if(empty($row["email"])){
			$row["email"]=$row["username"];
		}
        $row["recv_cash_name"]=ChangeEncodeG2U($row["recv_cash_name"]);
        $str=substr($row["recv_cash_name"],3,strlen($row["recv_cash_name"]));
        $row["recv_cash_name_show"]=str_replace($str,"**",$row["recv_cash_name"]);
        $row["recv_cash_name"]=str_replace($str,"aa",$row["recv_cash_name"]);
        
        $str=substr($row["card"],10,strlen($row["card"]));
        $row["card_show"]=str_replace($str,"**",$row["card"]);
        $row["card"]=str_replace($str,"0000",$row["card"]);
        
        $str=substr($row["qq"],4,strlen($row["qq"]));
        $row["qq_show"]=str_replace($str,"*****",$row["qq"]);
        $row["qq"]=str_replace($str,"0000",$row["qq"]);

		$RetContent .= "\t\t<table class='table table_list table-striped table-hover table-bordered' cellspacing='0px' style='border-collapse:collapse;'>\r\n";
		$RetContent .= "\t\t\t<tr>\r\n";
		$RetContent .= "\t\t\t\t<td>昵　　称 <font style='color:red;'>*</font>:</td>\r\n";
		$RetContent .= "\t\t\t\t<td colspan='2'><input id='txtNickName' value='" . ChangeEncodeG2U($row['nickname']) . "' class=\"form-control\" placeholder='6-50字符以内' /></td>\r\n";
		$RetContent .= "\t\t\t</tr>\r\n"; 
		
		$RetContent .= "\t\t\t<tr>\r\n";
		$RetContent .= "\t\t\t\t<td>头　　像 :</td>\r\n";
		$headInfo .= ($row['head'] == null)?"<img width='60' height='60' id='imgHead' src='img/head/1_0.jpg' />":"<img width='60' height='60' id='imgHead' src='{$row['head']}' />";
		$headInfo .= "　<select id='sltHead'>";
		for($i = 0; $i <= 8; $i++ )
		{
			$headInfo .= "<option value='1_{$i}.jpg'>男头像". ($i+1) ."</option>";
		} 
		for($i = 0; $i <= 8; $i++ )
		{
			$headInfo .= "<option value='0_{$i}.jpg'>女头像". ($i+1) ."</option>";
		}
		$headInfo .= "</select>";
		$RetContent .= "\t\t\t\t<td colspan='2'>{$headInfo}</td>\r\n";
		$RetContent .= "\t\t\t</tr>\r\n";
		
		$RetContent .= "\t\t\t<tr>\r\n";
		$RetContent .= "\t\t\t\t<td>手　　机<font style='color:red;'>*</font> :</td>\r\n";
		$RetContent .= "\t\t\t\t<td colspan='2'>";
		if($row['is_check_mobile'] == 1){
			$RetContent .= "<input id='txtMobile' value='{$row['mobile']}' disabled='disabled'  style='display:none' />" . $row['mobile'];
		}else{
			$RetContent .= "<input id='txtMobile' maxlength='11' value='{$row['mobile']}' class='form-control' placeholder='11位手机号码'/>";
		}
			
		$RetContent .= "</td>\r\n";
		$RetContent .= "\t\t\t</tr>\r\n";
		
		$RetContent .= "\t\t\t<tr>\r\n";
		$RetContent .= "\t\t\t\t<td>手机状态 :</td>\r\n";
		if($row['is_check_mobile'] == 0)
			$RetContent .= "\t\t\t\t<td colspan='2'>未绑定&nbsp;&nbsp;<a href=\"javascript:openrecord('-1',500,400,'smbinfo.php?act=bindmobileinfo&t=bind');\"><input type='button' id='btnToBindMobile' class='btn-1' value='绑定手机' ></a></td>\r\n";
		else
			$RetContent .= "\t\t\t\t<td colspan='2'>已绑定&nbsp;&nbsp;</td>\r\n";
		$RetContent .= "\t\t\t</tr>\r\n";
		
		
		
		$RetContent .= "\t\t\t<tr>\r\n";
		$RetContent .= "\t\t\t\t<td>邮　　箱:</td>\r\n";
		$RetContent .= "\t\t\t\t<td colspan='2'>";
		if($row['is_check_email'] == 1){
			$RetContent .= "<input id='txtEmail' value='{$row['email']}' disabled='disabled'  style='display:none' />" . $row['email'];
		}else{
			$RetContent .= "<input id='txtEmail' value='{$row['email']}' class='form-control' placeholder='常用邮箱地址'/>";
		}
		
		$RetContent .= "</td>\r\n";
		$RetContent .= "\t\t\t</tr>\r\n";
		
		$RetContent .= "\t\t\t<tr>\r\n";
		$RetContent .= "\t\t\t\t<td>邮箱状态:</td>\r\n";
		if($row['is_check_email'] == 0)
			$RetContent .= "\t\t\t\t<td>未绑定&nbsp;&nbsp;<input type='button' id='btnToBindEmail' class='btn btn-danger' value='绑定邮箱' ></td>\r\n";
		else
			$RetContent .= "\t\t\t\t<td>已绑定&nbsp;&nbsp;</td>\r\n";
		$RetContent .= "\t\t\t</tr>\r\n";
		
		
		
		

		$RetContent .= "\t\t\t<tr>\r\n";
		$RetContent .= "\t\t\t\t<td>收款人名字<font style='color:red;'>*</font>:</td>\r\n";
		if(empty($row['recv_cash_name'])){//填写过后不再能修改收款人名字
			$RetContent .= "\t\t\t\t<td colspan='2'><input id='recv_cash_name' maxlength='10' name='recv_cash_name' value='' class='form-control' placeholder='填写后不能再修改' /></td>\r\n";
		}else{
			$RetContent .= "\t\t\t\t<td colspan='2'><input id='recv_cash_name' maxlength='10' name='recv_cash_name' disabled='disabled'  style='display:none' maxlength='20' value='" . $row['recv_cash_name'] . "' class='form-control'  />".$row['recv_cash_name_show']."</td>\r\n";
             
		}
		$RetContent .= "\t\t\t</tr>\r\n";
		
		
		$RetContent .= "\t\t\t<tr>\r\n";
		$RetContent .= "\t\t\t\t<td>身份证号码<font style='color:red;'>*</font>:</td>\r\n";
		if(empty($row['card'])){//填写过后不再能修改身份证号码
			$RetContent .= "\t\t\t\t<td colspan='2'><input id='card' maxlength='18' name='card' value='' class='form-control' placeholder='填写后不能再修改' /></td>\r\n";
		}else{
			$RetContent .= "\t\t\t\t<td colspan='2'><input id='card' maxlength='18' name='card' disabled='disabled'  style='display:none' maxlength='20' value='" . $row['card'] . "' class='form-control'  />".$row['card_show']."</td>\r\n";
			 
		}
		$RetContent .= "\t\t\t</tr>\r\n";
		
		
		$RetContent .= "\t\t\t<tr>\r\n";
		$RetContent .= "\t\t\t\t<td>QQ:</td>\r\n";
        if(empty($row['qq'])){//填写过后不再能修改QQ
		    $RetContent .= "\t\t\t\t<td colspan='2'><input id='txtQQ' maxlength='20' value='" . ChangeEncodeG2U($row['qq']) . "' class='form-control' placeholder='填写后不能再修改' /></td>\r\n";
        }else{
            $RetContent .= "\t\t\t\t<td colspan='2'><input id='txtQQ' maxlength='20' value='" . ChangeEncodeG2U($row['qq']) . "'   style='display:none' class='form-control' placeholder='填写后不能再修改'  />" . ChangeEncodeG2U($row['qq_show']) . "</td>\r\n";
            
        }
		$RetContent .= "\t\t\t</tr>\r\n";
		

		
		$RetContent .= "\t\t\t<tr>\r\n";
		$RetContent .= "\t\t\t\t<td colspan='3'><input id='btnSubmit' type='button' class='btn btn-danger' style='margin:0 auto;' value='确定提交'></td>\r\n";
		$RetContent .= "\t\t\t</tr>\r\n";
		
		
		$RetContent .= "\t\t</table>\r\n";
        $RetContent .= "\t</div>\r\n";
		$RetContent .= "</div>\r\n";

        $RetContent .="<div class=\"tab-pane\" id=\"login_pass\">";
        $RetContent .= "\t\t<dl>\r\n";
        $RetContent .= "\t\t\t<dd>
            <div class=\"input-group\">
                <span class='input-group-addon'>原登录密码:</span>
                <input type='password' id='txtOldLoginPwd' maxlength='50' class='form-control' placeholder='输入登录密码' />
            </div>
        </dd>\r\n";
        $RetContent .= "\t\t\t<dd>
            <div class=\"input-group\">
                <span class='input-group-addon'>新登录密码:</span>
                <input type='password' id='txtNewLoginPwd' maxlength='50' class='form-control' placeholder='输入新登录密码' />
            </div>
        </dd>\r\n";
        $RetContent .= "\t\t\t<dd>
            <div class=\"input-group\">
                <span class='input-group-addon'>确认新密码:</span>  
                <input type='password' id='txtRNewLoginPwd' maxlength='50' class='form-control' placeholder='确认新密码' />
            </div>
        </dd>\r\n";
        $RetContent .= "\t\t\t<dd class='text-center'><input type='button' id='btnChangeLoginPwd' class='btn btn-danger' value='马上修改' /></dd>\r\n";
        $RetContent .= "\t\t</dl>\r\n";
        $RetContent .= "</div>\r\n";

        $RetContent .="<div class=\"tab-pane\" id=\"safe_pass\">";
        $RetContent .= "\t\t<dl>\r\n";
        $RetContent .= "\t\t\t<dd>
            <div class=\"input-group\">
                <span class='input-group-addon'>原安全密码:</span>
                <input type='password' id='txtOldBankPwd' class='form-control' maxlength='50' placeholder='原始密码与登录密码相同' />
            </div>
        </dd>\r\n";
        $RetContent .= "\t\t\t<dd>
            <div class=\"input-group\">
                <span class='input-group-addon'>新安全密码:</span>
                <input type='password' id='txtNewBankPwd' class='form-control' maxlength='50' placeholder='输入新安全密码'/>
            </div>
            </dd>\r\n";
        $RetContent .= "\t\t\t<dd>
            <div class=\"input-group\">
                <span class='input-group-addon'>确认新密码:</span>
                <input type='password' id='txtRNewBankPwd' maxlength='50' class='form-control' placeholder='输入确认新密码' />
            </div>
            </dd>\r\n";
        $RetContent .= "\t\t\t<dd class='text-center'><input type='button' id='btnChangeBankPwd' class='btn btn-danger' value='马上修改' /></dd>\r\n";
        $RetContent .= "\t\t\t<dd><a href=\"javascript:getContent('smbinfo.php','get_back_pwd')\">忘记安全密码</a></dd>\r\n";
        $RetContent .= "\t\t</dl>\r\n";
        $RetContent .= "</div>\r\n";       
        
        
        /* $sql="select time,ip,log from userslog where usersid={$_SESSION['usersid']} and logtype=4 order by id desc limit 10";
        $res=$db->query($sql);
        $login=array();
        while($log=$db->fetch_array($res)) {
            $login[] = $log;
        }

        $RetContent .="<div class=\"tab-pane\" id=\"area\">";
        $RetContent .="<div class=\"panel panel-default\">";
        $RetContent .="<div class=\"panel-heading\">登录地区限制</div>";
        $RetContent .="<div class=\"panel-body\">";
        $RetContent .="<table class=\"table table-striped table-hover table-bordered\">";
        $RetContent .="<tr>";
        $RetContent .="<td colspan=\"3\">两个常用登录地区都设置成“--”即取消常用登录地区限制，不设置“市”即省内都可登录.</td>";
        $RetContent .="</tr>";
        $RetContent .="<tr>";
        $RetContent .="<td>我当前的登录地区:</td>";
        $RetContent .="<td colspan=\"2\">".ip2address($login[0]['ip'])."</td>";
        $RetContent .="</tr>";
        $RetContent .="<tr>";
        $RetContent .="<td>是否开启登录地区限制：</td>";
        $RetContent .="<td><select><option value=\"1\">是</option><option value=\"0\">否</option></select></td>";
        $RetContent .="</tr>";

        $RetContent .="<tr>";
        $RetContent .="<td colspan=\"3\"><a href=\"#\" class=\"btn btn-danger\"> 确 认 </a></td>";
        $RetContent .="</tr>";    
        $RetContent .="</table>";
        $RetContent .="</div>";
        $RetContent .="</div>";

        $RetContent .="<div class=\"panel panel-default\">";
        $RetContent .="<div class=\"panel-heading\">登录地区限制</div>";
        $RetContent .="<div class=\"panel-body\">";
        $RetContent .="<table class=\"table table-striped table-hover table-bordered\">";

        $RetContent .="<tr>";
        $RetContent .="<th>时间</th>";
        $RetContent .="<th>IP</th>";
        $RetContent .="<th>地区</th>";
        $RetContent .="<th>成败</th>";
        $RetContent .="</tr>";

        foreach ($login as $log){
            $RetContent .= "<tr>";
            $RetContent .= "<td>".$log['time']."</td>";
            $RetContent .= "<td>".$log['ip']."</td>";
            $RetContent .= "<td>".ip2address($log['ip'])."</td>";
            $RetContent .= "<td>".$log['log']."</td>";
            $RetContent .= "</tr>";
        }
        $RetContent .="</table>";
        $RetContent .="</div>";
        $RetContent .="</div>";
        $RetContent .= "</div>\r\n"; */    

        $RetContent .= "</div>\r\n";
        
        $RetContent .= "<script language=\"javascript\">TopRefreshPoints();</script>\r\n";
        
		echo $RetContent;
		exit;
	}
	/* 取得基本信息
	*
	*/
	function GetUserBaseInfo()
	{
		global $db;
        $RetContent .= "<div class='info'>\r\n";//info开始
		$RetContent .= "\t<p class='greet'><span>您好，{$_SESSION['nickname']}</span><i>(ID:{$_SESSION['usersid']} 帐号:{$_SESSION['username']})</i></p>\r\n";
		//是否验证手机、邮箱、身份证
		$RetContent .= "\t<ul>\r\n";
		$is_check_mobile = 0;
		$is_check_email = 0;
		$is_check_card = 0;
		$sql = "select is_check_mobile,is_check_email,is_check_card,logintime,loginip,experience,maxexperience from users where id = '{$_SESSION['usersid']}' ";
		$result = $db->query($sql);
		$rs = $db->fetch_array($result);
		if($rs)
		{
			$is_check_mobile = $rs['is_check_mobile'];
			$is_check_email = $rs['is_check_email'];
			$is_check_card = $rs['is_check_card'];
		}
        $vip_level=$_SESSION['vip_level'];
		$RetContent .= "\t\t<li class='". (($is_check_mobile == 0) ? "phone'><a id='menu_mydetail' href=\"javascript:getContent('smbinfo.php','mydetail')\">未验证</a>" : "phones'>已验证") . "</li>\r\n";
		//$RetContent .= "\t\t<li class='". (($is_check_email == 0) ? "email'>未验证" : "emails'>已验证") . "</li>\r\n";
		//$RetContent .= "\t\t<li class='". (($is_check_card == 0) ? "degree'>未验证" : "degrees'>已验证") . "</li>\r\n";
		$RetContent .= "\t</ul>\r\n";
		//$RetContent .= "\t<a href='/slogin.php?act=logout'>[退出]</a>\r\n";
		$RetContent .= "</div>";//info结束
		
		//$RetContent .= "\t<h3>会员等级 ". showstars($vip_level) ."</h3>\r\n";
		
		$RetContent .= "\t<ul class='current'>\r\n";
		$RetContent .= "\t\t<li>您的当前乐豆:<i>". Trans($_SESSION['points']) . "</i><a id='menu_scoredetail' href=\"javascript:getContent('smbinfo.php','scoredetail')\">消费明细</a></li>\r\n";
		$RetContent .= "\t\t<li>最后一次登录时间:<em>{$rs['logintime']}</em></li>\r\n";
		$RetContent .= "\t\t<li>您当前银行乐豆:<i>". Trans($_SESSION['bankpoints']) ."</i>&nbsp;&nbsp当前经验:<i>{$rs['experience']}</i>\r\n";
		$RetContent .= "\t\t<li>最后一次登录IP:<em></em>{$rs['loginip']}</li>\r\n";
		$RetContent .= "\t</ul>\r\n";

		echo $RetContent;
		exit;
	}
	

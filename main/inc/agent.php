<?php

 

/* 代理统计*/
function agent_statistics(){
    global $db; 
    $arrRet = array('cmd'=>'','msg'=>'');  
    if(!isset($_SESSION['usersid'])) {
        $arrRet['cmd'] = "err";
        $arrRet['msg'] = "登录超时!";
        echo json_encode($arrRet);
		exit();
    }
    if(! isset($_SESSION['Agent_Id'])){
        $arrRet['cmd'] = "err";
        $arrRet['msg'] = "代理登录超时!";
        echo json_encode($arrRet);
		exit();
    }
    $Agent_Id=$_SESSION['Agent_Id'];
    $sql = "
        SELECT DATE_FORMAT(a.thedate,'%Y-%m') AS themonth,SUM(a.out_points) AS sum_out_points,
        SUM(a.out_points * (1-b.buycard_rate)) AS sum_sellprofit,
        SUM(a.in_points) AS sum_in_points,
        SUM(a.in_points * (1-b.reccard_rate)) AS sum_recprofit
        FROM agent_day_static a
        LEFT OUTER JOIN agent b
        ON a.agentid = b.id 
        where agentid='{$Agent_Id}'
        GROUP BY themonth
        ORDER BY themonth DESC
        limit 12;
        ";
        $arrRows = array();
        $result = $db->query($sql);
        //取得返回记录数
        $RowCount = $db->num_rows($result);
        $total_sellrmb = 0;
        $total_sellprofit = 0;
        $total_recrmb = 0;
        $total_recprofit = 0;
        $total_totalprofit = 0;
        for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
        { 
            $data=array();
            //对返回数据进行包装
            $data["theMonth"] = $row["themonth"]; 
            $data["SellRMB"] = Trans($row["sum_out_points"]/1000);
            $data["SellProfit"] = Trans($row["sum_sellprofit"]/1000);
            $data["RecRMB"] = Trans($row["sum_in_points"]/1000);
            $data["RecProfit"] = Trans($row["sum_recprofit"]/1000);
            $data["TotalProfit"] = Trans($row["sum_out_points"]/1000 + $row["sum_recprofit"]/1000);
            $arrRows[]=$data;
            $total_sellrmb += $row["sum_out_points"]/1000;
            $total_sellprofit += $row["sum_sellprofit"]/1000;
            $total_recrmb += $row["sum_in_points"]/1000;
            $total_recprofit += $row["sum_recprofit"]/1000; 
            $total_totalprofit += $row["sum_out_points"]/1000 + $row["sum_recprofit"]/1000;
        }
        
        if($RowCount > 0)
        {
            $data=array();
            $data["theMonth"] = "页小计:"; 
            $data["SellRMB"] = Trans($total_sellrmb);
            $data["SellProfit"] = Trans($total_sellprofit);
            $data["RecRMB"] = Trans($total_recrmb);
            $data["RecProfit"] = Trans($total_recprofit);
            $data["TotalProfit"] = Trans($total_totalprofit);
            $arrRows[]=$data;
        }
    $tr="";
    foreach( $arrRows as $data){
        $tr .="
            <tr>
                <td  style='width:60px!important;'  >".$data["theMonth"]."</td>
                <td>".$data["SellRMB"]."</td>
                <!--<td>".$data["SellProfit"]."</td>-->
                <td>".$data["RecRMB"]."</td>
                <!--<td>".$data["RecProfit"]."</td>-->
                <!--<td>".$data["TotalProfit"]."</td>-->
          </tr>";
    }
    $jq='$';
    $RetContent="
    <link href='images/jquery_ui.css' rel='stylesheet' type='text/css'/> 
    <script type='text/javascript' src='js/jquery.v1.10.2.min.js'></script>
    <script type='text/javascript' src='js/jquery_ui.js'></script>
    <script type='text/javascript' src='js/agent.js'></script>
    <script type='text/javascript'  >
    {$jq}(document).ready(function(){
            {$jq}('#txtTimeBegin,#txtTimeEnd').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,   
                changeYear: true,    
                dayNamesMin : ['日', '一', '二', '三', '四', '五', '六'], 
                firstDay : 1, 
                monthNamesShort: ['1', '2', '3', '4', '5', '6','7', '8', '9', '10', '11', '12'],
                yearRange: 'c-60:c+20'
         });
     });
    </script>";
            
            $RetContent .= "<div class='panel panel-default'>";
            $RetContent .= "<div class='panel-heading'>统计信息,统计收益</div>";
            $RetContent .= "<div class='panel-body'>";
            
			$RetContent .= "<div class='message   recharge' style='width:100%'>
            <div class='m_list' style='width:100%'>
            <div class='m_val' style='width:100%'>
                <span class='title' style='color:#000'>月度统计</span>             
            </div>
            <div class='list agent_log_list' style='width:100%;border-style: none;'>
                    <table class='table_list '  style='width:100%' cellspacing='0px' style='border-collapse:collapse;'>
                        <tr height='30'>
                            <th style='width:60px !important;'  align='left'>月份</th>
                            <th align='left'>销售总额(￥)</th>
                            <!--<th align='left'>销售利润(￥)</th>-->  
                            <th align='left'>回收总额(￥)</th>
                            <!--<th align='left'>回收利润(￥)</th>-->
                            <!--<th align='left'>总利润(￥)</th>-->
                        </tr>
                        ".$tr."
                    </table>
              </div>
        </div>
        
        <div class='m_list' style='width:100%'>
            <div class='m_val' style='width:100%'>
                <span class='title' style='color:#000'>按天统计</span>             
            </div>
            <div class='list statistics_list' style='width:100%;border-style: none;'>
                    <table class='table_list '  style='width:100%' cellspacing='0px' style='border-collapse:collapse;'>
                     <tr class='none_tr'>
                           <td colspan='8' >
                                <div class='search_input'>
                                     <ul>
                                       <li class='cbxtime'><input id='cbxTime' type='checkbox'>
					                   	时间
					                   <input id='txtTimeBegin' type='text' style='width:90px' value='".date('Y-m-d')."' >&nbsp;
					                   <input id='txtTimeEnd' type='text' style='width:90px' value='".date('Y-m-d',strtotime('+7 day'))."'   >
					                   &nbsp;
					                   <input type='button' value='查询' id='statistics_btnSearch' class='btn btn-danger'>
					                   </li>   
                                 	  </ul>
                             	</div>
                            </td>
                        </tr>
                        <tr height='30'>
                            <th style='width:90px !important;'  align='left'>日期</th>
                            <th align='left'>销售额(￥)</th>
                            <th align='left'>销售折扣</th>  
                            <!--<th align='left'>销售利润(￥)</th>-->
                            <th align='left'>回收额(￥)</th>
                            <th align='left'>回收折扣</th>
                            <!--<th align='left'>回收利润(￥)</th>-->
                            <!--<th align='left'>总利润(￥)</th>-->
                        </tr>
                        ".GetAgentDayStat()."
                    </table>
              </div>
        </div>
    </div>";
    echo $RetContent;
     
    exit();
    
}

    /* 取得代理日统计
    *
    */
    function GetAgentDayStat()
    {
        global $db;
        $arrRet = array('cmd'=>'','msg'=>'');  
        if(!isset($_SESSION['usersid'])) {
            $arrRet['cmd'] = "err";
            $arrRet['msg'] = "登录超时!";
            echo json_encode($arrRet);
			exit();
        }
        if(! isset($_SESSION['Agent_Id'])){
            $arrRet['cmd'] = "err";
            $arrRet['msg'] = "代理登录超时!";
            echo json_encode($arrRet);
			exit();
        }
        $Agent_Id=$_SESSION['Agent_Id'];
        $TimeBegin = isset($_POST['begin'])?FilterStr($_POST['begin']):"";
        $TimeEnd = isset($_POST['end'])?FilterStr($_POST['end']):"";
        $sqlCount = "select Count(*) ";
        $sqlCol = "SELECT a.thedate,b.buycard_rate,b.reccard_rate,a.out_points,a.out_points * (1-b.buycard_rate) AS sellprofit,
                        a.in_points,a.in_points * (1-b.reccard_rate) AS recprofit";
        $sqlFrom = " FROM agent_day_static a
                    LEFT OUTER JOIN agent b
                    ON a.agentid = b.id
                    WHERE  a.agentid={$Agent_Id}  ";
        $sqlWhere = "";
        $sqlOrder = "";
        $sql = "";
        //页大小
        $PageSize = isset($_POST['PageSize'])?$_POST['PageSize']:20;
        $PageSize = intval($PageSize);
        //页码
        $page = isset($_POST['page'])?$_POST['page']:1;
        $page =intval($page);
        $arrReturn = array(array());
        //时间
        $TimeField = "a.thedate";
        $sqlWhere .= GetSqlBetween($TimeField,$TimeBegin,$TimeEnd,false);
        //取得排序
        $sqlOrder =  " order by thedate desc ";
        //取得总记录数
        $TotalCount = $db->GetRecordCount($sqlCount.$sqlFrom.$sqlWhere);
        //取记录
        $sql = $sqlCol . $sqlFrom . $sqlWhere . $sqlOrder . GetLimit($page,$PageSize);
        $RowCount = 0;
        $arrRows = array();
        $result = $db->query($sql);
        //取得返回记录数
        $RowCount = $db->num_rows($result);
        if($RowCount == 0)
        {
            $arrReturn[0]["cmd"] = "norecord";
            $arrReturn[0]["msg"] = "没有记录!";
            ArrayChangeEncode($arrReturn);
            //echo json_encode($arrReturn);
            return;
        }
        $total_sellrmb = 0;
        $total_sellprofit = 0;
        $total_recrmb = 0;
        $total_recprofit = 0;
        $total_totalprofit = 0;
        for($i=1,$row=$db->fetch_array($result);$i <= $RowCount;$i++,$row = $row=$db->fetch_array($result))
        {   
            //对返回数据进行包装
            $data=array();
            $data["TheDay"] = date("Y-m-d",strtotime($row["thedate"]));
            $data["SellRMB"] = Trans($row["out_points"]/1000);
            $data["SellRate"] = $row["buycard_rate"];
            $data["SellProfit"] = Trans($row["sellprofit"]/1000);
            $data["RecRMB"] = Trans($row["in_points"]/1000);
            $data["RecRate"] = $row["reccard_rate"];
            $data["RecProfit"] = Trans($row["recprofit"]/1000);
            $data["TotalProfit"] = Trans($row["sellprofit"]/1000 + $row["recprofit"]/1000);
            $arrRows[]=$data;
            $total_sellrmb += $row["out_points"]/1000;
            $total_sellprofit += $row["sellprofit"]/1000;
            $total_recrmb += $row["in_points"]/1000;
            $total_recprofit += $row["recprofit"]/1000; 
            $total_totalprofit += $row["out_points"]/1000 + $row["recprofit"]/1000; 
        }
        
        if($RowCount > 0)
        {
            $data=array();
            $data["TheDay"] = "页小计:"; 
            $data["SellRMB"] = Trans($total_sellrmb);
            $data["SellRate"] = ""; 
            $data["SellProfit"] = Trans($total_sellprofit);
            $data["RecRMB"] = Trans($total_recrmb);
            $data["RecRate"] = ""; 
            $data["RecProfit"] = Trans($total_recprofit);
            $data["TotalProfit"] = Trans($total_totalprofit);
            $arrRows[]=$data;
        }
        $tr="";
        foreach( $arrRows as $data){
            $tr .="
                <tr>
                    <td  style='width:60px!important;'  >".$data["TheDay"]."</td>
                    <td>".$data["SellRMB"]."</td>
                    <td>".$data["SellRate"]."</td>
                    <!--<td>".$data["SellProfit"]."</td>-->
                    <td>".$data["RecRMB"]."</td>
                    <td>".$data["RecRate"]."</td>
                    <!--<td>".$data["RecProfit"]."</td>-->
                    <!--<td>".$data["TotalProfit"]."</td>-->
              </tr>";
        }
        if($TotalRecCount > $PageSize)
        {
            $divPage = "<div class='pagediv'></div><div class='Paging'>\r\n";
            require_once('inc/fenye.php');
            $ajaxpage=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesize,'ajax'=>"ajax_page_statistics",'nowindex' => $page));
            $divPage .= $ajaxpage->show();
            $divPage .= "</div>\r\n";
            $tr .= "<tr><td colspan=8>".$divPage."</td></tr>";
        }
        return $tr;
    }



 

/* 代理操作日志*/
function agent_log(){
    global $db; 
    $arrRet = array('cmd'=>'','msg'=>'');  
    if(!isset($_SESSION['usersid'])) {
        $arrRet['cmd'] = "err";
        $arrRet['msg'] = "登录超时!";
        echo json_encode($arrRet);
		exit();
    }
    if(! isset($_SESSION['Agent_Id'])){
        $arrRet['cmd'] = "err";
        $arrRet['msg'] = "代理登录超时!";
        echo json_encode($arrRet);
		exit();
    }
        
    
    $RetContent="
    <script type='text/javascript' src='js/agent.js'></script>";
    
    
    $RetContent .= "<div class='panel panel-default'>";
    $RetContent .= "<div class='panel-heading'>代理操作日志</div>";
    $RetContent .= "<div class='panel-body'>";
    
    $RetContent .= "<div class='message   recharge' style='width:100%'>
            <div class='m_list' style='width:100%'>
            <div class='list agent_log_list' style='width:100%;border-style: none;'>
                    <table class='table_list '  style='width:100%' cellspacing='0px' style='border-collapse:collapse;'>
                    <tr class='none_tr'>
                           <td colspan='6' >
                            <div class='search_input'>
                                 <ul>
                                 <li><input name='search_userid' class='search_txt' id='search_userid' type='text' value='输入用户名或ID'>
                                 <input type='radio' name='search_day' checked  value='7' id='search_day1'><label for='search_day1'>7天</label>
                                 <input type='radio' name='search_day'  value='30'  id='search_day2'><label for='search_day2'>30天</label>
                                 <input type='radio' name='search_day'  value='180' id='search_day3'><label for='search_day3'>180天</label>
                                 <input type='radio' name='search_day'  value='365' id='search_day4'><label for='search_day4'>一年</label>
                                 <input type='button' value='查询' id='agent_log_btnATSearch'  class='btn btn-danger'></li>
                                </ul>
                             </div>
                            </td>
                        </tr>
                        <tr height='30'>
                            <th  style='width:130px !important;'  align='left'>时间</th>
                            <th   align='left'>操作类型</th>
                            <th align='left'>操作分数</th> 
                            <th      align='left'>操作后分数</th>   
                            <th align='left'  >内容</th>
                        </tr>
                          ".Get_agent_Log_List()."
                    </table>
              </div>
        </div></div></div>
    </div>";
    echo $RetContent;
    exit();
    
}


//领奖记录
function Get_agent_Log_List()
    {
        global $db;
        $userid = isset($_POST['userid'])?str_check($_POST['userid']):0;
        $page = isset($_POST['page'])?$_POST['page']:1;
        $day = isset($_POST['day'])?intval($_POST['day']):7;
        $page =intval($page);
        if($day==0){
            $day=7;
        }
        if(! isset($_SESSION['Agent_Id'])){
            $arrRet['cmd'] = "timeout";
            return $arrRet['cmd'];
        }
        if(!isset($_SESSION['usersid'])) {
            $arrRet['cmd'] = "timeout";
            return $arrRet['cmd'];
        }
        $Agent_Id =$_SESSION['Agent_Id'];
        $pagesize = 15;
        $RetContent="";
        //表内容
        $sql = "select count(*) 
        from agent_oprlog  l
        LEFT JOIN users u on(u.id=l.touserid)
        where   l.agentid = '{$Agent_Id}'  and opr_time > date_add(now(),interval -{$day} day)  ";
        if(!empty($userid)){
            $sql=$sql."  and  u.id = '{$userid}' or u.username = '{$userid}' ";
        }
        $TotalRecCount = $db->GetRecordCount($sql);
        $sql = "select  opr_time,opr_points,content,touserid,opr_name,cur_totalpoints
        from agent_oprlog l 
        LEFT JOIN users u on(u.id=l.touserid)
       LEFT JOIN  agent_oprtype t on( l.opr_type=t.opr_type)
        where   l.agentid= '{$Agent_Id}'  and opr_time > date_add(now(),interval -{$day} day)  ";
        if(!empty($userid)){
            $sql=$sql."  and  u.id = '{$userid}' or u.username = '{$userid}' ";
        }
        $sql=$sql." order by opr_time desc ";
        $sql .= GetLimit($page,$pagesize); 
        $result =  $db->query($sql);
        while($rs=$db->fetch_array($result)){
            $rs['opr_name']=ChangeEncodeG2U($rs['opr_name']);
            $rs['content']=ChangeEncodeG2U($rs['content']);
            $rs['opr_points']=number_format($rs['opr_points'])."<br>(￥". number_format($rs["opr_points"]/1000,2).")";
            $rs['cur_totalpoints']=number_format($rs['cur_totalpoints'])."<br>(￥". number_format($rs["cur_totalpoints"]/1000,2).")";
            $RetContent .= "<tr>
                                <td style='width:130px!important;'>{$rs['opr_time']}</td>
                                <td    style='width:90px !important;' >{$rs['opr_name']}</td>
                                <td>{$rs['opr_points']}</td>
                                <td style='width:130px !important;'>{$rs['cur_totalpoints']}</td>
                                <td>{$rs['content']}</td>
                          </tr>";
        }
        if($TotalRecCount==0){
            $RetContent .= "
                <tr>
                    <td  colspan='5'>你没有操作记录！</td>
                </tr>";
        }
        //分页
        if($TotalRecCount > $pagesize)
        {
            $divPage = "<div class='pagediv'></div><div class='Paging'>\r\n";
            require_once('inc/fenye.php');
            $ajaxpage=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesize,'ajax'=>"ajax_page_agent_log",'nowindex' => $page));
            $divPage .= $ajaxpage->show();
            $divPage .= "</div>\r\n";
            $RetContent .= "<tr><td colspan=6>".$divPage."</td></tr>";
        }
        return $RetContent;

    }


/* 回收体验卡*/
function Experience_Card(){
    global $db; 
    $usersid=$_SESSION['usersid'];
    $arrRet = array('cmd'=>'','msg'=>'');  
    if(!isset($_SESSION['usersid'])) {
        $arrRet['cmd'] = "err";
        $arrRet['msg'] = "登录超时!";
        echo json_encode($arrRet);
		exit();
    }
    if(! isset($_SESSION['Agent_Id'])){
        $arrRet['cmd'] = "err";
        $arrRet['msg'] = "代理登录超时!";
        echo json_encode($arrRet);
		exit();
    }
    $sql = "SELECT  reccard_rate FROM users u,agent a 
    WHERE  u.id=a.uid and u.isagent=1 and   a.uid={$usersid}";
    $query = $db->query($sql);
    $agent=array();
    while($rs=$db->fetch_array($query)){
        $agent=$rs;
    }
    $reccard_rate=0;
    if(!empty($agent)){
        $reccard_rate=($agent["reccard_rate"]*10)."折";
    }
    $jq='$';   
    $RetContent="
        <script type='text/javascript' >var buycard_rate='".$agent["buycard_rate"]."'; </script>
    <script type='text/javascript' src='js/agent.js'></script>";
        		
        		
    $RetContent .= "<div class='panel panel-default'>";
    $RetContent .= "<div class='panel-heading'>回收体验卡</div>";
    $RetContent .= "<div class='panel-body'>";
        		
    $RetContent .= "<div class='replace'>\r\n";//replace开始
    $RetContent .= "<table class='table_list' cellspacing='0px' style='border-collapse:collapse;'>
                          <tr>
                                <td>收卡折扣：</td>
                                <td>".$reccard_rate."</td>
                          </tr>
                        <tr>
                            <td>卡号与卡密:</td>
                            <td align='left' id='check_acount'>
                             <textarea name='card_list' id='card_list' cols='50' rows='9'></textarea>
                             <div class='card_ms'>
                             *可以输入多行,每行一个卡
                             <br>*格式：卡号 空格 卡密  如： 10075198159 01490591
                             </div>
                            </td>
                        </tr>
                        <tr >
                            <td colspan='2'  class='but_td'>
                            <input  id='submit_card_but' type='button'  class='btn btn-danger'  value='确定回收'>
                            <input  id='cetection_card_but' type='button'  class='btn btn-danger'  value='检测是否可用'>
                            </td>
                        </tr>
                         <tr class='cart_check_ret'></tr>
                            <tr class='cart_check_submit_tr'>
                            <td colspan='2'  class='but_td'></td>
                        </tr>
                    </table>
             </div>";
             
             
             $RetContent .= "<div class='message recharge' style='width:100%;'>
             
             <div class='m_list' style='width:100%;'>
             <div class='m_val' style='width:100%;'>
             <span class='title' style='color:#000'>回收记录</span>
             </div>
             <div class='list user_rexperience_log_list' style='border-style: none;width:100%;'>
             <table class='table_list' cellspacing='0px' style='border-collapse:collapse;width:100%;'>
                        <tr class='none_tr'>
                           <td colspan='7' >
                            <div class='search_input'>
                                 <ul>
                                 <li><input name='search_userid' class='search_txt' id='search_userid' type='text' value='输入用户名或ID'>
                              			<input type='radio' name='search_day' checked  value='7' id='search_day1'><label for='search_day1'>7天</label>
                              			<input type='radio' name='search_day'  value='30'  id='search_day2'><label for='search_day2'>30天</label>
                              			<input type='radio' name='search_day'  value='180' id='search_day3'><label for='search_day3'>180天</label>
                              			<input type='radio' name='search_day'  value='365' id='search_day4'><label for='search_day4'>一年</label>
                             			<input type='button' value='查询' id='rexperience_btnATSearch'  class='btn btn-danger'><li>
             					</ul>
                             </div>
                             
                            </td>
                        </tr>
                        
                        <tr height='30'>
                            <th   align='left'>卡号</th>
                            <th    width=60  align='left'>点数</th>
                            <th    width=60  align='left'>用户昵称</th>
                            <th align='left'  >备注</th>
                             
                        </tr>
                         ".Get_Exchange_Log()."
                    </table>
              </div>
             </div>
        </div>";
    echo $RetContent;
}


//卡回收记录
function Get_Exchange_Log()
    {
        global $db;
        $page = isset($_POST['page'])?$_POST['page']:1;
        $userid = isset($_POST['userid'])?str_check($_POST['userid']):0;
        $day = isset($_POST['day'])?intval($_POST['day']):7;
        $page =intval($page);
        if($day==0){
            $day=7;
        }
        
        if(!isset($_SESSION['usersid'])) {
            $arrRet['cmd'] = "timeout";
            return $arrRet['cmd'];
        }
        if(! isset($_SESSION['Agent_Id'])){
            $arrRet['cmd'] = "timeout";
            return $arrRet['cmd'];
        }
        $pagesize = 15;
        $RetContent="";
        //表内容
        $sql = "select count(*) 
        from exchange_cards a,users u 
        where u.id=a.uid AND agentid = '{$_SESSION['Agent_Id']}' and used_time > date_add(now(),interval -{$day} day)  ";
        if(!empty($userid)){
            $sql=$sql."  and  u.id = '{$userid}' or u.username = '{$userid}' ";
        }
        
        $TotalRecCount = $db->GetRecordCount($sql);
        $sql = "select a.uid,a.nickname,card_no,card_points,used_time,used_ip,a.remark
        from exchange_cards  a
        LEFT JOIN   users u  on (u.id=a.uid)
        where     agentid = '{$_SESSION['Agent_Id']}' and used_time > date_add(now(),interval -{$day} day) ";
        if(!empty($userid)){
            $sql=$sql."  and  u.id = '{$userid}' or u.username = '{$userid}' ";
        }
        $sql=$sql." order by used_time desc ";
        $sql .= GetLimit($page,$pagesize); 
        $result =  $db->query($sql);
        while($rs=$db->fetch_array($result)){
            $rs['card_points']=number_format($rs['card_points'])."(￥".number_format( $rs['card_points']/1000,2).")";
            $rs['content']=ChangeEncodeG2U($rs['content']);
            $rs['card_name']=ChangeEncodeG2U($rs['card_name']);
            $rs['remark']=ChangeEncodeG2U($rs['remark']);
            $rs['nickname']=ChangeEncodeG2U($rs['nickname']);
            $rs['nickname']=$rs['nickname']."(".$rs['uid'].")";
            $RetContent .= "<tr>
                                <td style='width:160px!important;'>{$rs['card_no']}</td>
                                <td>{$rs['card_points']}</td>
                                <td>{$rs['nickname']}</td>
                                <td>{$rs['remark']}</td>
                          </tr>";
        }

        //分页
        if($TotalRecCount > $pagesize)
        {
            $divPage = "<div class='pagediv'></div><div class='Paging'>\r\n";
            require_once('inc/fenye.php');
            $ajaxpage=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesize,'ajax'=>"ajax_page_rexperience",'nowindex' => $page));
            $divPage .= $ajaxpage->show();
            $divPage .= "</div>\r\n";
            $RetContent .= "<tr><td colspan=4>".$divPage."</td></tr>";
        }
        return $RetContent;

    }

/* 额度转换*/
function Agent_Change(){
    global $db; 
    $usersid=$_SESSION['usersid'];
    $sql = "SELECT back  as total,distribute_money FROM users u,agent a 
    WHERE  u.id=a.uid and u.isagent=1  and a.uid={$usersid}";
    $query = $db->query($sql);
    $agent=array();
    while($rs=$db->fetch_array($query)){
        $agent=$rs;
    }
    $arrRet = array('cmd'=>'','msg'=>'');  
    if(!isset($_SESSION['usersid'])) {
        $arrRet['cmd'] = "err";
        $arrRet['msg'] = "登录超时!";
        echo json_encode($arrRet);
		exit();
    }
    if(! isset($_SESSION['Agent_Id'])){
        $arrRet['cmd'] = "err";
        $arrRet['msg'] = "代理登录超时!";
        echo json_encode($arrRet);
		exit();
    }
    $sql = "SELECT  u.id,u.qq,a.agent_name ,recv_cash_name,a.id as agent_id
    FROM users u,agent a 
    WHERE  u.id=a.uid AND a.state=1  AND u.isagent=1 
    and u.id<>{$usersid}";
    $query = $db->query($sql);
    $data_agent=array();
    $select='
        <select name="agent_select" id="agent_select">
            <option value="">选择转换代理</option>
    ';
    while($rs=$db->fetch_array($query)){
        $data_agent[]=$rs;
        $select .='<option value="'.$rs["agent_id"].'">'.ChangeEncodeG2U($rs["agent_name"]).'('.ChangeEncodeG2U($rs["recv_cash_name"]).')</option>';
    }
    $select .='</select>';
        
    $change_rmb=$distribute_money=$buycard_rate=$back=$total=$back_rmb=$total_rmb=0;
    $is_change=false;
    if(!empty($agent)){
        $total_rmb=floor($agent["total"]/1000);//number_format($agent["total"]/1000,2);
        $change_rmb=floor(($agent["total"]-$agent["distribute_money"])/1000);//number_format(($agent["total"]-$agent["distribute_money"])/1000,2);
        $change=floor($agent["total"]-$agent["distribute_money"]);//number_format($agent["total"]-$agent["distribute_money"]);
        //$change_rmb=str_replace(".0","",$change_rmb);
        //$total_rmb=str_replace(".0","",$total_rmb);
        if($agent["total"]>$agent["distribute_money"]){
            $is_change=true;
        }
        $distribute_money=$agent["distribute_money"];
        $total=number_format($agent["total"]);
    }
    $jq='$';   
    $RetContent="
    <script type='text/javascript' src='js/agent.js'></script>";
    
    
    $RetContent .= "<div class='panel panel-default'>";
    $RetContent .= "<div class='panel-heading'>额度转换,代理额度转换</div>";
    $RetContent .= "<div class='panel-body'>";

    
    $RetContent .= "<div class='replace'>\r\n";//replace开始
    $RetContent .= "\t\t<table class='table table_list table-striped table-hover table-bordered' cellspacing='0px' style='border-collapse:collapse;'>\r\n";
    $RetContent .= "\t\t\t<tr>\r\n";
    $RetContent .= "\t\t\t\t<td>银行余额 :</td>\r\n";
    $RetContent .= "\t\t\t\t<td>".$change."(￥".$change_rmb.")</td>\r\n";
    $RetContent .= "\t\t\t</tr>\r\n";
    
    $RetContent .= "\t\t\t<tr>\r\n";
    $RetContent .= "\t\t\t\t<td>转换额度 :</td>\r\n";
    $RetContent .= "\t\t\t\t<td align='left'><input name='change_money'  style='width: 100px;' maxlength='8' id='change_money' type='text'> &nbsp;<span id='change_money_ms'></span></td>\r\n";
    $RetContent .= "\t\t\t</tr>\r\n";
    
    $RetContent .= "\t\t\t<tr>\r\n";
    $RetContent .= "\t\t\t\t<td>选择转换代理 :</td>\r\n";
    $RetContent .= "\t\t\t\t<td align='left'>{$select}</td>\r\n";
    $RetContent .= "\t\t\t</tr>\r\n";
    
    $RetContent .= "\t\t\t<tr>\r\n";
    $RetContent .= "\t\t\t\t<td colspan='2'  class='but_td'><input  id='agent_change_but' type='button' class='btn btn-danger' value='转换'></td>\r\n";
    $RetContent .= "\t\t\t</tr>\r\n";
    
    $RetContent .= "\t\t</table>\r\n";
    $RetContent .= "\t</div></div></div>\r\n";
    
    echo $RetContent; 
}

/* 代理提现*/
function agent_withdraw(){
    global $db; 
    $usersid=$_SESSION['usersid'];
    $sql = "SELECT  back  as total,distribute_money FROM users u,agent a 
    WHERE  u.id=a.uid and u.isagent=1 and a.uid={$usersid}";
    $query = $db->query($sql);
    $agent=array();
    while($rs=$db->fetch_array($query)){
        $agent=$rs;
    }
    $arrRet = array('cmd'=>'','msg'=>'');  
    if(!isset($_SESSION['usersid'])) {
        $arrRet['cmd'] = "err";
        $arrRet['msg'] = "登录超时!";
        echo json_encode($arrRet);
		exit();
    }
    if(! isset($_SESSION['Agent_Id'])){
        $arrRet['cmd'] = "err";
        $arrRet['msg'] = "代理登录超时!";
        echo json_encode($arrRet);
		exit();
    }
        
    $buycard_rate=$back=$total=$back_rmb=$total_rmb=0;
    if(!empty($agent)){
        $total_rmb=floor($agent["total"]/1000);//number_format($agent["total"]/1000,2);
        $distribute_money=$agent["distribute_money"];
        $distribute_rmb=floor($agent["distribute_money"]/1000);//number_format($agent["distribute_money"]/1000,2);
        //$distribute_rmb=str_replace(".0","",$distribute_rmb);
        //$total_rmb=str_replace(".0","",$total_rmb);
        $total=number_format($agent["total"]);
        $get_money=floor(($agent["total"]-$agent["distribute_money"])/1000);//number_format((($agent["total"]-$agent["distribute_money"])/1000),1);

    }
    $RetContent="
        <script type='text/javascript' >var buycard_rate='".$agent["buycard_rate"]."'; </script>
    <script type='text/javascript' src='js/agent.js'></script>";
    
    
    $RetContent .= "<div class='panel panel-default'>";
    $RetContent .= "<div class='panel-heading'>代理提现，查询提现记录</div>";
    $RetContent .= "<div class='panel-body'>";    
    
    $RetContent .= "<div class='replace'>\r\n";//replace开始
    $RetContent .= "\t\t<table class='table table_list table-striped table-hover table-bordered' cellspacing='0px' style='border-collapse:collapse;'>\r\n";
    $RetContent .= "\t\t\t<tr>\r\n";
    $RetContent .= "\t\t\t\t<td>银行余额 :</td>\r\n";
    $RetContent .= "\t\t\t\t<td>".$total."(￥".$total_rmb.") </td>\r\n";
    $RetContent .= "\t\t\t</tr>\r\n";
    
    $RetContent .= "\t\t\t<tr>\r\n";
    $RetContent .= "\t\t\t\t<td>铺货金额 :</td>\r\n";
    $RetContent .= "\t\t\t\t<td>".$distribute_rmb."￥</td>\r\n";
    $RetContent .= "\t\t\t</tr>\r\n";
    
    $RetContent .= "\t\t\t<tr>\r\n";
    $RetContent .= "\t\t\t\t<td>可提现金额 :</td>\r\n";
    $RetContent .= "\t\t\t\t<td>".$get_money."￥</td>\r\n";
    $RetContent .= "\t\t\t</tr>\r\n";
    
    $RetContent .= "\t\t\t<tr>\r\n";
    $RetContent .= "\t\t\t\t<td>提现金额(￥) :</td>\r\n";
    $RetContent .= "\t\t\t\t<td align='left'><input name='withdraw_money'  maxlength='8' id='withdraw_money' type='text'>元 &nbsp;<span id='recharge_money_ms'>只能是100的倍数</span></td>\r\n";
    $RetContent .= "\t\t\t</tr>\r\n";
    
    $RetContent .= "\t\t\t<tr>\r\n";
    $RetContent .= "\t\t\t\t<td colspan='2'  class='but_td'><input  id='withdraw_but' type='button' class='btn btn-danger' style='margin:0 auto;' value='提交申请'></td>\r\n";
    $RetContent .= "\t\t\t</tr>\r\n";
    
    $RetContent .= "\t\t</table>\r\n";
    $RetContent .= "\t</div>\r\n";
    
    $RetContent .= "<div class='message recharge' style='width:100%;'>
            
            <div class='m_list' style='width:100%'>
            <div class='m_val' style='width:100%'>
                <span class='title' style='color:#000'>提现记录</span>             
            </div>
            <div class='list agent_log_list' style='width:100%;border-style: none;'>
                    <table class='table_list '  style='width:100%' cellspacing='0px' style='border-collapse:collapse;'>
    		
                        <tr height='30'>
                            <th  style='130px !important;'  align='left'>申请时间</th>
                             <th width=70  align='left'>金额</th>
                            <th    width=80  align='left'>状态</th>
                            <th    width=130  align='left'>处理时间</th>  
                            <th align='left'  >处理结果</th>
                            <th align='left' width=50 >撤销</th>
                        </tr>
                          ".Get_withdraw_Log()."
                    </table>
              </div>
             </div>
    </div>";
    echo $RetContent;
    exit();
    
}


function Get_withdraw_Log()
    {
        global $db;
        $page = isset($_POST['page'])?$_POST['page']:1;
        $page =intval($page);
        if(!isset($_SESSION['usersid'])) {
            $arrRet['cmd'] = "timeout";
            return $arrRet['cmd'];
        }
        if(! isset($_SESSION['Agent_Id'])){
            $arrRet['cmd'] = "timeout";
            return $arrRet['cmd'];
        }
        $pagesize = 15;
        $RetContent="";
        //表内容
        $sql = "select count(*) 
        from agent_withdraw  
        where   agentid = '{$_SESSION['Agent_Id']}' ";
         
        
        $TotalRecCount = $db->GetRecordCount($sql);
        $sql = "select id,agentid,add_time,state,opr_time,msg,points
        from agent_withdraw  
        where   agentid = '{$_SESSION['Agent_Id']}'   ";
   
        $sql=$sql." order by add_time desc ";
        $sql .= GetLimit($page,$pagesize); 
        $result =  $db->query($sql);
        while($rs=$db->fetch_array($result)){
            $rs['points']=number_format($rs['points'])."(￥".number_format(($rs['points']/1000),2).")";    
            $rs['msg']=ChangeEncodeG2U($rs['msg']);
            $str="";
            if($rs['state']==0){
                $str="<input type='button'  onclick='withdraw_Revocation(". $rs['id'].")' value='撤销' />";
            }
            switch($rs['state'])
            {
                case 0:
                $rs['state']="未处理";
                break;
                case 1:
                $rs['state']="已处理";
                break;
                case 2:
                $rs['state']="用户已撤销";
                break;
                case 3:
                $rs['state']="管理员撤销";
                break;
            }
 
            $RetContent .= "<tr>
                                <td>{$rs['add_time']}</td>
                                <td>{$rs['points']}</td>
                                <td>{$rs['state']}</td>
                                <td>{$rs['opr_time']}</td>
                                <td>{$rs['msg']}</td>
                                <td>{$str}</td>
                          </tr>";
        }

        //分页
        if($TotalRecCount > $pagesize)
        {
            $divPage = "<div class='pagediv'></div><div class='Paging'>\r\n";
            require_once('inc/fenye.php');
            $ajaxpage=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesize,'ajax'=>"ajax_page_withdraw_log",'nowindex' => $page));
            $divPage .= $ajaxpage->show();
            $divPage .= "</div>\r\n";
            $RetContent .= "<tr><td colspan=6>".$divPage."</td></tr>";
        }
        
        return $RetContent;

    }

/* 代理充值*/
function Recharge(){
    global $db; 
    $usersid=$_SESSION['usersid'];
    $sql = "SELECT back, back+points+lock_points as total,buycard_rate FROM users u,agent a 
    WHERE  u.id=a.uid and u.isagent=1 and a.uid={$usersid}";
    $query = $db->query($sql);
    $agent=array();
    while($rs=$db->fetch_array($query)){
        $agent=$rs;
    }
    $arrRet = array('cmd'=>'','msg'=>'');  
    if(!isset($_SESSION['usersid'])) {
        $arrRet['cmd'] = "err";
        $arrRet['msg'] = "登录超时!";
        echo json_encode($arrRet);
		exit();
    }
    if(! isset($_SESSION['Agent_Id'])){
        $arrRet['cmd'] = "err";
        $arrRet['msg'] = "代理登录超时!";
        echo json_encode($arrRet);
		exit();
    }
        
    $buycard_rate=$back=$total=$back_rmb=$total_rmb=0;
    if(!empty($agent)){
        $total_rmb=number_format($agent["total"]/1000,2);
        $back_rmb=number_format($agent["back"]/1000,2);
        $back_rmb=str_replace(".0","",$back_rmb);
        $total_rmb=str_replace(".0","",$total_rmb);
        $back=number_format($agent["back"]);
        $total=number_format($agent["total"]);
        $buycard_rate=($agent["buycard_rate"]*10)."折";
    }
    $jq='$';   
    $RetContent="
        <script type='text/javascript' >var buycard_rate='".$agent["buycard_rate"]."'; </script>
        <script type='text/javascript' src='js/agent.js'></script>";
    
    $RetContent .= "<div class='panel panel-default'>";
    $RetContent .= "<div class='panel-heading'>代理充值,给玩家充值</div>";
    $RetContent .= "<div class='panel-body'>";
    
    
    
    $RetContent .= "<div class='replace'>\r\n";//replace开始
    $RetContent .= "\t\t<table class='table table_list table-striped table-hover table-bordered' cellspacing='0px' style='border-collapse:collapse;'>\r\n";
    $RetContent .= "\t\t\t<tr>\r\n";
    $RetContent .= "\t\t\t\t<td>银行余额 :</td>\r\n";
    $RetContent .= "\t\t\t\t<td>".$back."(￥".$back_rmb.")</td>\r\n";
    $RetContent .= "\t\t\t</tr>\r\n";
    
    //$RetContent .= "\t\t\t<tr>\r\n";
    //$RetContent .= "\t\t\t\t<td>折扣 :</td>\r\n";
    //$RetContent .= "\t\t\t\t<td>".$buycard_rate."</td>\r\n";
    //$RetContent .= "\t\t\t</tr>\r\n";
    
    $RetContent .= "\t\t\t<tr>\r\n";
    $RetContent .= "\t\t\t\t<td>用户ID或用户名 :</td>\r\n";
    $RetContent .= "\t\t\t\t<td align='left' id='check_acount'><input name='userid' id='recharge_userid' type='text'>
                            <input  id='recharge_check_but' type='button' class='btn btn-danger' style='margin:0 auto;' value='检测帐号'>
                            </td>\r\n";
    $RetContent .= "\t\t\t</tr>\r\n";
    
    $RetContent .= "\t\t\t<tr>\r\n";
    $RetContent .= "\t\t\t\t<td>充值金额(￥) :</td>\r\n";
    $RetContent .= "\t\t\t\t<td align='left'><input name='recharge_money'  maxlength='8' id='recharge_money' type='text'>元 &nbsp;<span id='recharge_money_ms'></span></td>\r\n";
    $RetContent .= "\t\t\t</tr>\r\n";
    
    $RetContent .= "\t\t\t<tr>\r\n";
    $RetContent .= "\t\t\t\t<td colspan='2'  class='but_td'><input  id='recharge_recharge_but' type='button' class='btn btn-danger' style='margin:0 auto;' value='充值'></td>\r\n";
    $RetContent .= "\t\t\t</tr>\r\n";
    
    $RetContent .= "\t\t</table>\r\n";
    $RetContent .= "\t</div>\r\n";
    
    
    
    
        		
    $RetContent .= "<div class='message recharge' style='width:100%;'>
            
            <div class='m_list' style='width:100%;'>
            <div class='m_val' style='width:100%;'>
                <span class='title' style='color:#000'>充值记录</span>             
            </div>
            <div class='list recharge_log_list' style='border-style: none;width:100%;'>
                    <table class='table_list' cellspacing='0px' style='border-collapse:collapse;width:100%;'>
                        <tr class='none_tr'>
                           <td colspan='5' >
                            <div class='search_input'>
                                 <ul>
                                 <li><input name='search_userid' class='search_txt' id='search_userid' type='text' value='输入用户名或ID'>
                              			<input type='radio' name='search_day' checked  value='7' id='search_day1'><label for='search_day1'>7天</label>
                             			<input type='radio' name='search_day'  value='30'  id='search_day2'><label for='search_day2'>30天</label>
                             			<input type='radio' name='search_day'  value='180' id='search_day3'><label for='search_day3'>180天</label>
                             			<input type='radio' name='search_day'  value='365' id='search_day4'><label for='search_day4'>一年</label>
                             			<input type='button' value='查询' id='recharge_btnATSearch' class='btn btn-danger'><li>
    							</ul>
                             </div>
                             
                            </td>
                        </tr>
                        
                        <tr height='30'>
                            <th  style='width:180px  !important;'  align='left'>时间</th>
                            <th width=120  align='left'>金额</th>
 
                            <th    width=120  align='left'>用户QQ</th>
                            <th    width=120  align='left'>收款人</th>  
                            <th align='left'  >操作内容</th>
                        </tr>
                          ".Get_Recharge_Log()."
                    </table>
              </div>
             </div>
    </div>";
    echo $RetContent;

    
}

function Get_Recharge_Log()
    {
        global $db;
        $page = isset($_POST['page'])?$_POST['page']:1;
        $userid = isset($_POST['userid'])?str_check($_POST['userid']):0;
        $day = isset($_POST['day'])?intval($_POST['day']):7;
        $page =intval($page);
        if($day==0){
            $day=7;
        }
        
        if(!isset($_SESSION['usersid'])) {
            $arrRet['cmd'] = "timeout";
            return $arrRet['cmd'];
        }
        if(! isset($_SESSION['Agent_Id'])){
            $arrRet['cmd'] = "timeout";
            return $arrRet['cmd'];
        }
        $pagesize = 15;
        $RetContent="";
        //表内容
        $sql = "select count(*) 
        from agent_oprlog a,users u 
        where u.id=a.touserid AND agentid = '{$_SESSION['Agent_Id']}' and opr_time > date_add(now(),interval -{$day} day)  ";
        if(!empty($userid)){
            $sql=$sql."  and  u.id = '{$userid}' or u.username = '{$userid}' ";
        }
        
        $TotalRecCount = $db->GetRecordCount($sql);
        $sql = "select agentid,opr_time,opr_points,content,qq,recv_cash_name
        from agent_oprlog  a,users u 
        where  u.id=a.touserid AND agentid = '{$_SESSION['Agent_Id']}' and opr_time > date_add(now(),interval -{$day} day) ";
        if(!empty($userid)){
            $sql=$sql."  and  u.id = '{$userid}' or u.username = '{$userid}' ";
        }
        $sql=$sql." order by opr_time desc ";
        $sql .= GetLimit($page,$pagesize); 
        $result =  $db->query($sql);
        while($rs=$db->fetch_array($result)){
            $rs['opr_points']=number_format($rs['opr_points'])."(￥".number_format(($rs['opr_points']/1000),2).")"; 
            $rs['content']=ChangeEncodeG2U($rs['content']);
            $rs['recv_cash_name']=ChangeEncodeG2U($rs['recv_cash_name']);
            $RetContent .= "
                <tr>
                    <td>{$rs['opr_time']}</td>
                    <td>{$rs['opr_points']}</td>
                    <td>{$rs['qq']}</td>
                    <td>{$rs['recv_cash_name']}</td>
                    <td>{$rs['content']}</td>
                </tr>";
        }

        //分页
        if($TotalRecCount > $pagesize)
        {
            $divPage = "<div class='pagediv'></div><div class='Paging'>\r\n";
            require_once('inc/fenye.php');
            $ajaxpage=new page(array('total'=>$TotalRecCount,'perpage'=>$pagesize,'ajax'=>"ajax_page_recharge_log",'nowindex' => $page));
            $divPage .= $ajaxpage->show();
            $divPage .= "</div>\r\n";
            $RetContent .= "<tr><td colspan=6>".$divPage."</td></tr>";
        }
        
        return $RetContent;

    }

  	 /* 代理页面*/
function Information(){
    global $db; 
    $usersid=$_SESSION['usersid'];
    $sql = "SELECT a.*,u.logintime,u.loginip FROM users u,agent a 
    WHERE  u.id=a.uid  and a.uid={$usersid}";
    $query = $db->query($sql);
    $agent=array();
    while($rs=$db->fetch_array($query)){
        $agent=$rs;
    }
    $agent["agent_name"]=ChangeEncodeG2U($agent["agent_name"]);
    if($agent["state"]==1){
        $agent["state"]="正常";
    }else{
        $agent["state"]="冻结";
    }
    if($agent["is_recommend"]==1){
        $agent["is_recommend"]="推荐";
    }else{
        $agent["is_recommend"]="否";
    }
    $agent["buycard_rate"]= ($agent["buycard_rate"]*10)."折";
    $agent["reccard_rate"]= ($agent["reccard_rate"]*10)."折";
    
    
    
    $RetContent .= "<div class='panel panel-default'>";
    $RetContent .= "<div class='panel-heading'>代理资料</div>";
    $RetContent .= "<div class='panel-body'>";
    $RetContent .= "\t<ul class='current'>\r\n";
    $RetContent .= "\t\t<li>代理ID:<i>". $agent["uid"] . "</i></li>\r\n";
    $RetContent .= "\t\t<li>代理名称:<em>{$agent["agent_name"]}</em></li>\r\n";
    $RetContent .= "\t\t<li>进卡折扣:<i>". $agent["buycard_rate"] ."</i>\r\n";
    $RetContent .= "\t\t<li>收卡折扣:<i>".$agent["reccard_rate"] ."</i>\r\n";
    $RetContent .= "\t\t<li>收卡利润:<i>".$agent["reccard_profit_rate"] ."</i>\r\n";
    $RetContent .= "\t\t<li>铺货分:<i>".$agent["distribute_money"] ."</i>\r\n";
    $RetContent .= "\t\t<li>是否推荐:<i>{$agent["is_recommend"]}</i></li>\r\n";
    $RetContent .= "\t\t<li>状态:<i>{$agent["state"]}</i></li>\r\n";
    $RetContent .= "\t\t<li>最后登录时间:<em>{$agent["logintime"]}</em></li>\r\n";
    $RetContent .= "\t\t<li>最后一次登录IP:<em>{$agent["loginip"]}</em></li>\r\n";
    $RetContent .= "\t</ul>\r\n";
    
    
    
    
    /* 
    $RetContent="<div class='replace information'>
        <div class='r_nav'>
            <img src='img/banner.png' />
            <p class='title'>代理资料</p>
            <p class='cen'>查看代理资料</p>
        </div>    <div class='table'>
            <table class='table_list' cellspacing='0px' style='border-collapse:collapse;'>
                <tr>
                    <td width='100px'>代理ID:</td>
                    <td width='300px' align='left'>".$agent["uid"]."</td>
                </tr>
                <tr>
                    <td>代理名称:</td>
                    <td>".$agent["agent_name"]."</td>
                </tr>
                <tr>
                    <td>进卡折扣:</td>
                    <td>".$agent["buycard_rate"]."</td>
                </tr>
                <tr>
                    <td>收卡折扣:</td>
                    <td>".$agent["reccard_rate"]."</td>
                </tr>
                <tr>
                    <td>收卡利润:</td>
                    <td>".$agent["reccard_profit_rate"]."</td>
                </tr>
                <tr>
                    <td>铺货分:</td>
                    <td>".number_format($agent["distribute_money"])."</td>
                </tr>
                <tr>
                    <td>是否推荐:</td>
                    <td>".$agent["is_recommend"]."</td>
                </tr>
                <tr>
                    <td>状态:</td>
                    <td>".$agent["state"]."</td>
                </tr>
                <tr>
                    <td>最后登录时间:</td>
                    <td>".$agent["last_logintime"]."</td>
                </tr>
                <tr>
                    <td>最后登录ip:</td>
                    <td>".$agent["last_loginip"]."</td>
                </tr>
            </table>
        </div>
    </div>";
     */
    
    
    
    
    echo $RetContent;
    
    
    
}

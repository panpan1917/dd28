<?php
/**
 * Date: 2015/7/12
 */
session_start();

class UserAction extends BaseAction
{
    function __construct()
    {
    	$this->nologin=array('reg','login','doReg','chargeProcBack','getPaymentQRCode');
        parent::__construct();
    }

    function index()
    {
        $this->display('user_index');
    }

    function login()
    {

        if(IS_AJAX){
            return $this->doLogin();
        }
        echo 'login';

    }
    
    private function doLogin(){
        $username = Req::post('username');
        $pwds = Req::post('pass');
        $pwd = (strlen($pwds) > 30) ? substr($pwds,0,30) : $pwds;
        $pwd = setPassword($pwd);
        $vcode = Req::post('vcode');
        $isKeepLogin = Req::post('iskeep');

        if(!is_numeric($username) || strlen($username) > 50)
        {
            return $this->result('fault');
        }

        if($vcode != $_SESSION["CheckNum"] || $_SESSION['CheckNum']=='') //验证码错误
        {
            return $this->result('vcode');
        }
        if(isset($_SESSION['username'])) //用户正在使用
        {
            return $this->result("use");
        }
        $ip = get_ip();
        $sql = "call web_user_login('{$username}','{$pwd}','{$ip}')";
        $arr =db::get_one($sql);
        switch($arr->result)
        {
            case '0': //成功
                $_SESSION["usersid"] = $arr->userid;
                $_SESSION["username"] = $arr->username;
                $_SESSION['check']=$arr->check;
                $_SESSION["password"] = $pwd;
                $_SESSION["nickname"] = $arr->nickname;
                $_SESSION["points"] = $arr->points;
                $_SESSION["bankpoints"] = $arr->bankpoint;
                $_SESSION["exp"] = $arr->experience;
                $_SESSION["head"] = $arr->head;
                $_SESSION['freeze'] = 0;
                $_SESSION['logintime'] = $arr->logintime;
                $usersid = $arr->userid;
                $sql = "select u.isagent,a.id from users u, agent a where a.uid=u.id and  u.id = '{$usersid}' limit 1";
                $users = db::get_one($sql,'assoc');
                if(!empty($users)){
                	$_SESSION['isagent'] = $users['isagent'];
                	$_SESSION['Agent_Id'] = $users['id'];
                }
                
                $sql = "SELECT id FROM usergroups
                WHERE (SELECT experience FROM  users WHERE id={$usersid})
                BETWEEN creditslower AND creditshigher LIMIT 1";
                $users = db::get_one($sql,'assoc');
                $_SESSION['level'] = $users['id'];
                
                $arrRet = 'ok';
                break;
            case '1': //用户名或密码错误
                $arrRet = 'fault';
                break;
            case '2': //帐号被冻结
                $arrRet = 'dj_001';
                break;
            case '99': //数据库错误
                $arrRet = 'dataerr';
                break;
            default:
                $arrRet = 'other';
                break;
        }
        return $this->result($arrRet);
    }
    function log_login_fail(){
        if(!$_SESSION['num']){
            //$_SESSION['login']
        }
        $_SESSION['num']=$_SESSION['num']+1;
    }
    function reg()
    {
        if (IS_AJAX) return $this->doReg();
        echo 'reg';
    }

    private function doReg()
    {
        $username = Req::post('username');
        $pass = Req::post('pass');
        $nickname = Req::post('nickname');
        $tjid = Req::post('tjid', 'intval');
        $nickname = str_replace("http", "", $nickname);
        $nickname = str_replace("href", "", $nickname);
        $source=$_COOKIE["referer"];//Req::post('t','intval')?:0;

        if (!preg_match("/^1[345789]{1}\d{9}$/", $username)) {
            return $this->result(1, '请输入正确的手机号码');
        }
        if (strlen($username) != 11) {
            return $this->result(1, '请输入正确的手机号码');
        }
        if ($nickname == "" || strlen($nickname) > 20) {
            return $this->result(1, "昵称错误,长度不超过20位!");
        }
        if (strlen($pass) == 0 || strlen($pass) > 30) {
            return $this->result(1, "密码错误,长度不超过30位!");
        }
        if (strlen($tjid) > 20) {
            return $this->result(1, "推荐人ID错误,长度不超过20位!");
        }
        
        
        $HangShiForbid = ['1303440','1303441','1303442','1303443','1304270','1308520','1309275','1309276','1309277','1309278','1309279','1309839','1311442','1311701','1311702','1311703','1313592','1313593','1314723','1316567','1316568','1317730','1317731','1317732','1317733','1319700','1319701','1319702','1319703','1319704','1321714','1323723','1323724','1323725','1324723','1329423','1329721','1329722','1329723','1329724','1329764','1329765','1329766','1330723','1331723','1332992','1332993','1333990','1333991','1333992','1334982','1336714','1337791','1338526','1338710','1339723','1345104','1345105','1345106','1345107','1347678','1347773','1347774','1347775','1350723','1354549','1354550','1354551','1354552','1354553','1354554','1354555','1354556','1358128','1358129','1359761','1359762','1359763','1359764','1359765','1359766','1359767','1359768','1359769','1359770','1359771','1359772','1359773','1359774','1361714','1361721','1364714','1365714','1366904','1367714','1368718','1370723','1379777','1379778','1380723','1387205','1387206','1387207','1387208','1387209','1387210','1387211','1387212','1387213','1387214','1388645','1388646','1388647','1388648','1388649','1390723','1397175','1397176','1397177','1397178','1397276','1397277','1397278','1397279','1397280','1398657','1398658','1398659','1398660','1399595','1399596','1399597','1399598','1399599','1500723','1507201','1507202','1507203','1507204','1507205','1507206','1507207','1507293','1510723','1517200','1517201','1517202','1517203','1517204','1517205','1517206','1517207','1517208','1517209','1517210','1517211','1520723','1527165','1527166','1527203','1527204','1527205','1530723','1532787','1532788','1533427','1533428','1533588','1533738','1534729','1554970','1554971','1554972','1557148','1557291','1557292','1557293','1557294','1557295','1557296','1557297','1557298','1557299','1560723','1561499','1562939','1562959','1562970','1562971','1562990','1567122','1567123','1567175','1567176','1567177','1567178','1569723','1580723','1582694','1582695','1582696','1582697','1582698','1587113','1587114','1587115','1587116','1587117','1587118','1587119','1587120','1587121','1589775','1589776','1589777','1589778','1589779','1590723','1592690','1592691','1597151','1597152','1597153','1597154','1597155','1597235','1597236','1597237','1597252','1597253','1597254','1599710','1599711','1599712','1599713','1599714','1860723','1867163','1880723','1887275','1887276','1887277','1890723','1897175','1897176','1897177','1897178','1897276','1897277','1897278','1897279','1897280','1898657','1898658','1898659','1898660','1899577','1899578','1899579','1899580'];
        if(in_array(substr(trim($username),0,7) , $HangShiForbid)){
        	return $this->result(1, "注册失败!");
        }
        
        
        $Sms = new sms();
        $code = $Sms->get_code($username);
        if((int)$code['verifytimes'] >= 5){
        	return $this->result(1, "验证码输错了5次,请重发验证码!");
        }
        
        if ($_REQUEST['vcode'] == '' || $_REQUEST['vcode'] != $code['code']) {
        	//累加验证码错误次数
        	$Sms->cumulate_verifytimes($code['id']);
            return $this->result(1,"手机跟验证码不匹配");
        }else{
            $_SESSION['mobilesmscode']='';
        }
        $pass=$this->setPassword($pass);
        
        
        $ip = get_ip();
        
        $sql = "call web_user_mobile_reg(0,0,'{$username}','{$nickname}','{$pass}','{$ip}',{$tjid},'{$source}')";
        $arr = db::get_all($sql,'assoc');
        $status=1;
        switch($arr[0]["result"])
        {
            case '0': //成功
                $_SESSION["usersid"] = $arr[0]["userid"];
                $_SESSION["username"] = $arr[0]["username"];
                $_SESSION["password"] = $pass;
                $_SESSION["nickname"] = $arr[0]["nickname"];
                $_SESSION["points"] = $arr[0]["points"];
                $_SESSION["bankpoints"] = 0;
                $_SESSION["exp"] = $arr[0]["experience"];
                $arrRet['cmd'] = 'ok';
                $status=0;
                break;
            case '1': //用户名重名
                $arrRet['cmd'] = '很抱歉！帐号重名了，请更改！';
                break;
            case '2': //
                $arrRet['cmd'] = '很抱歉!用户名含有不允许字符，请更改!';
                break;
            case '3': //
                $arrRet['cmd'] = '很抱歉!用户数字ID已存在，请重新注册!';
                break;
            case '4': //
                $arrRet['cmd'] = '很抱歉!昵称重名，请更改！';
                break;
            case '99': //数据库错误
                $arrRet['cmd'] = '很抱歉！系统错误，请与客服联系！';
                break;
            default:
                $arrRet['cmd'] = '未知错误';
                break;
        }
        
        return $this->result($status,$arrRet['cmd']);
    }
    function set_uid(){
        $sql='SELECT FLOOR(100000 + RAND()*900000) AS random_num FROM users WHERE "random_num" !=id LIMIT 1';
        $uid=db::get_one($sql);
        if(!$uid)return $this->set_uid();
        return $uid->random_num;
    }
    function right()
    {
        $this->display('right');
    }

    function logout()
    {
        unset($_SESSION['usersid']);
        session_destroy();
        setcookie("usersid");
        setcookie("username");
        setcookie("password");
        echo "<script language=javascript>window.location='index.php';</script>";
    }
    function get_ledou(){
        if($_SESSION['usersid']) {
            $sql = 'select points from users where id=' . (int)$_SESSION['usersid'];
            $point = db::get_one($sql);
            echo $this->Trans($point->points);
        }else{
        	echo '0';
        }
        
        return true;
    }
    function login_confirm(){
        $this->display('Login_Confirm');
    }
    function Trans($num)
    {
        return number_format($num);
    }
    
    public function getLastPayAccount(){
    	if(!isset($_SESSION['Login_Confirm'])) {
    		return $this->login_confirm();
    		exit;
    	}
    	
    	$sql = "SELECT uid,account,`name`,cz_type FROM pay_online WHERE uid='{$_SESSION['usersid']}' AND cz_type='{$_GET['cz_type']}' ORDER BY id DESC LIMIT 1";
    	$res=db::get_one($sql,'assoc');
    	if((int)$res['uid'] > 0){
    		echo json_encode($res);
    	}else{
    		echo json_encode([]);
    	}
    	exit;
    }
    
    
    function getPaymentList(){
    	$ret = "";
    	$grouptype = (int)$_REQUEST['grouptype'];
    	//if($grouptype == 3 || $grouptype == 4)
    		$sql="select id,name,marks,is_pic,maxamount,grouptype from recharge_type where is_show=1 and grouptype={$grouptype} order by autocharge,rank";
    	//else
    	//	$sql="select id,name,marks,is_pic,maxamount,grouptype from recharge_type where is_show=1 and grouptype in(1,2) order by autocharge,rank";
    	$rows = db::get_all($sql,'assoc');
    	foreach($rows as $row){
    		$ret = $ret . "<option value=\"{$row['id']}\">{$row['name']}{$row['marks']}</option>";
    	}
    	echo $ret;
    	exit;
    }
    
    //用户充值
    function onlinepay(){
        $day = $_POST['day'];
        if(empty($day)) $day = 7;
        $sql='select a.*,b.name as pay_type from pay_online a left join recharge_type b on a.cz_type=b.id where a.uid='.$_SESSION['usersid'].' and a.state <3 ';
        if($day){
            $sql.=" and  TO_DAYS(NOW())-TO_DAYS(a.add_time)<{$day}";
        }
        $sql.=' order by a.id desc limit 30';
        $list=db::get_all($sql);
        $this->assign('list',$list);
        
        $whereStr = "";
        /* $sql = "SELECT DISTINCT id FROM (
					SELECT u.`id`,COUNT(*) AS cnt FROM users u,pay_online p WHERE u.id=p.uid AND p.`rmb`>=100.00 AND p.`state`=1 AND u.dj=0 and u.`id`={$_SESSION['usersid']} 
					GROUP BY u.`id` HAVING cnt >= 10 
					UNION 
					SELECT u.`id`,COUNT(*) AS cnt FROM users u,pay_online p WHERE u.id=p.uid AND p.`rmb`>=50.00 AND p.`rmb`<100.00 AND p.`state`=1 AND u.dj=0 and u.`id`={$_SESSION['usersid']} 
					GROUP BY u.`id` HAVING cnt >= 15 
				) a";
        $resid=db::get_one($sql,'assoc');
        $uid = (int)$resid['id'];
        if(!$uid) $whereStr = " and autocharge=1 "; */
        
        $sql="select * from recharge_type where is_show=1 {$whereStr} order by autocharge,rank";
        $this->assign('cz_list',db::get_all($sql));
        
        $sql = "select recv_cash_name from users where id={$_SESSION['usersid']}";
        $res=db::get_one($sql,'assoc');
        $recv_cash_name = $res['recv_cash_name'];
        $this->assign('recv_cash_name',$recv_cash_name);
        
        $this->assign('min_recharge',MIN_RECHARGE);
        $this->assign('day',$day);
        $this->display('onlinepay');
    }
    
    //普通用户充值
    function userrecharge(){
    	if(!isset($_SESSION['Login_Confirm'])) {
    		return $this->login_confirm();
    		exit;
    	}
    	$day = $_REQUEST['day'];
    	$sql='select * from pay_online where uid='.$_SESSION['usersid'].' and state <3 ';
    	if($day){
    		$sql.=' and  TO_DAYS(NOW())-TO_DAYS(add_time)<7';
    	}
    	$sql.=' order by id desc limit 30';
    	$list=db::get_all($sql);
    	foreach ($list as $k=>$v){
    		$list[$k]->cz_type=$this->cz_type($v->cz_type);
    	}
    	
    	
    	$this->assign('list',$list);
    	$this->assign('min_recharge',MIN_RECHARGE);
    	$this->display('userrecharge');
    }
    
    
    
    /* function extension(){
    	return;
        $ajax=Req::get('ajax')?1:0;
        if($ajax)return $this->ajax_extension_list($ajax);
        
        $g=Req::get('g','intval')?:0;
        $sql = 'select sum(s.tzpoints) as point , GROUP_CONCAT(u.id) AS uids 
        		from game_day_static s left join users u on u.id=s.uid 
        		where u.tjid=' . $_SESSION['usersid'] . ' 
        		and to_days(now())-to_days(s.time)=1';//浮动赔率游戏才给提成  and s.kindid <= 24
        $res = db::get_one($sql,'assoc');
        $point = (int)$res['point'];
        if ($point > 0) {
            $point *= COMMISSION_RATE;
            $uids = $res['uids'];
        }else{
            $point= 0;
            $uids = "";
        }
        $point=(int)$point;
        
        db::_query('SET AUTOCOMMIT=0');
        db::_query('begin');
        $sql="select count(*) as cnt from score_log where opr_type=" . L_EXTENSION_REBATE . " and uid={$_SESSION['usersid']} and to_days(now())=to_days(log_time)";
        $res=db::get_one($sql,'assoc');
        if((int)$res['cnt'] > 0){
        	db::_query('rollback');
        	db::_query('SET AUTOCOMMIT=1');
            $this->assign('id',$res['cnt']);
        }elseif($g && $point>0){
        	$sql = "select dj_extension from users where id = {$_SESSION['usersid']}";
        	$res=db::get_one($sql);
        	if($res->dj_extension == 1){
        		db::_query('rollback');
        		return $this->result(1,'领取推荐奖已经被冻结');
        	}
        	
            $sql="update users set `points`=`points`+{$point} where id={$_SESSION['usersid']}";
            if(!db::_query($sql , false)){
                db::_query('rollback');
                return $this->result(1, '领取失败[1],请联系管理员');
            }
            
            $result = $this->update_centerbank($point,7);
            if(!$result){
            	db::_query('rollback');
            	return $this->result(1, '领取失败[2],请联系管理员');
            }
            
            $sql = "INSERT game_static (uid,typeid,points) values ({$_SESSION['usersid']}, " . STATIC_EXTENSION . ", {$point}) ON DUPLICATE KEY UPDATE points=points+{$point}";
            if(!db::_query($sql , false)){
            	db::_query('rollback');
            	return $this->result(1, '领取失败[3],请联系管理员');
            }
            
            
            $sql = "INSERT webtj (time,tgpoints) values (now(),{$point}) ON DUPLICATE KEY UPDATE tgpoints=tgpoints+{$point}";
            if(!db::_query($sql , false)){
            	db::_query('rollback');
            	return $this->result(1, '领取失败[4],请联系管理员');
            }
            
            $_SESSION['points'] = $_SESSION['points'] + $point;
            $result = $this->score_log($_SESSION['usersid'],L_EXTENSION_REBATE,$point,$_SESSION['points'],$_SESSION['bankpoints'],"推荐奖励:".$uids);
            if(!$result){
            	db::_query('rollback');
            	$_SESSION['points'] = $_SESSION['points'] - $point;
            	return $this->result(1, '领取失败[5],请联系管理员');
            }
            
            
            db::_query('commit');
            db::_query('SET AUTOCOMMIT=1');
            
            $this->RefreshPoints();
            return $this->result(0, '领取成功');
        }
        
        

        $this->assign('point',floor(abs($point)));
        $sql='select count(*) as total from users where tjid='.$_SESSION['usersid'];
        $count=db::get_one($sql);
        $this->assign('count',$count->total);
        $sql='select amount,log_time from score_log where opr_type=' . L_EXTENSION_REBATE . ' and uid='.$_SESSION['usersid'].' order by id desc limit 30';
        $list=db::get_all($sql);
        $this->assign('list',$list);

        $this->ajax_extension_list($ajax);
        $this->display('extension');
    }
    
    
    function ajax_extension_list($ajax){
        $page=Req::get('page','intval')?:1;

        $sql='select count(id) as total from users where tjid='.$_SESSION['usersid'];
        $total=db::get_one($sql);
        $pagesize=10;
        $sql='select u.id,u.nickname,u.logintime,ifnull(sum(g.tzpoints),0) as points from users u left join game_day_static g on g.uid=u.id and (TO_DAYS(now())-to_days(g.time)=1) where u.tjid='.$_SESSION['usersid'].' group by u.id limit '.(($page-1)*$pagesize).',10';
        $user_list=db::get_all($sql);//u.tjid='.$_SESSION['usersid'].'
        $ajaxpage=new Apage(array('total'=>$total->total,'perpage'=>$pagesize,'ajax'=>"page_list",'nowindex' => $page));
        $show= $ajaxpage->show();
        if($ajax){
            echo json_encode(['list'=>$user_list,'page'=>$show],JSON_UNESCAPED_UNICODE);
            return true;
        }
        $this->assign('user_list',$user_list);
        $this->assign('page',$show);
    }
    
    
    
    function get_pack(){
    	$num=trim(Req::get('num'));
    	
        if(!empty($num)){
            	$status=1;
            
                db::_query("set autocommit=0");
                db::_query("begin");
                $sql = "SELECT p.*,pl.id as pid FROM pack p LEFT JOIN pack_list pl ON pl.`typeid`=p.id and pl.uid={$_SESSION['usersid']} WHERE p.num='{$num}'";
                $pack = db::get_one($sql);
                if (!$pack->id || $pack->status == 0) {
                	db::_query('rollback');
                    $mess = '不存在此红包!';
                    return $this->result($status,$mess);
                } elseif ($pack->endcount >= $pack->count) {
                	db::_query('rollback');
                    $mess = '您来晚了,红包已经抢完了!';
                    return $this->result($status,$mess);
                } elseif ($pack->pid > 0) {
                	db::_query('rollback');
                    $mess = '已经领取过了!';
                    return $this->result($status,$mess);
                } else {
                    $ledou = rand($pack->min, $pack->max);
                    $sql = "update pack set endcount=endcount+1 where id=" . $pack->id . " and count>endcount";
                    if(!db::_query($sql, false)) {
                        db::_query('rollback');
                        $mess = '您来晚了,红包已经抢完了!';
                        return $this->result($status,$mess);
                    } else {
                        $ip = get_ip();
                        $sql = "insert into pack_list(typeid,uid,ledou,time,ip)values(" . $pack->id . ",{$_SESSION['usersid']},{$ledou},UNIX_TIMESTAMP(),INET_ATON('{$ip}'))";
                        if(!db::_query($sql, false)) {
                            db::_query('rollback');
                            $mess = '抢红包失败[1]!';
                            return $this->result($status,$mess);
                        } else {
							$sql = "update users set points=points+{$ledou} where id={$_SESSION['usersid']}";
                            if(!db::_query($sql, false)) {
                                db::_query("rollback");
                                $mess = '抢红包失败[2]!';
                                return $this->result($status,$mess);
                            } else {
                                $result = $this->update_centerbank($ledou, 9);//-- 更新中央银行
                                if(!$result) {
                                	db::_query("rollback");
                                	$mess = '抢红包失败[3]!';
                                	return $this->result($status,$mess);
                                }
                                
                                $sql = "INSERT game_static (uid,typeid,points) values ({$_SESSION['usersid']},141, {$ledou}) ON DUPLICATE KEY UPDATE points=points+{$ledou}";
                                if(!db::_query($sql, false)) {
                                	db::_query('rollback');
                                	$mess = '抢红包失败[4]!';
                                	return $this->result($status,$mess);
                                }
                                
                                $sql = "INSERT webtj (time,pack) values (now(),{$ledou}) ON DUPLICATE KEY UPDATE pack=pack+{$ledou}";
                                if(!db::_query($sql, false)) {
                                	db::_query('rollback');
                                	$mess = '抢红包失败[5]!';
                                	return $this->result($status,$mess);
                                }
                                
                                $_SESSION['points'] = $_SESSION['points'] + $ledou;
                                $result = $this->score_log($_SESSION['usersid'], 40, $ledou, $_SESSION['points'], $_SESSION['bankpoints'], "红包id=" . $pack->id);
                                if(!$result) {
                                	db::_query("rollback");
                                	$_SESSION['points'] = $_SESSION['points'] - $ledou;
                                	$mess = '抢红包失败[6]!';
                                	return $this->result($status,$mess);
                                }
                                
                                $status = 0;
                                $mess = '恭喜您,获得乐豆: ' . $ledou;
                            }
                        }
                    }
            	}
            
	            db::_query('commit');
	            db::_query('set autocommit=1');
	            
	            $this->RefreshPoints();
	            return $this->result($status,$mess);
        }
        
        
        
        $sql='select * from pack_list where uid='.$_SESSION['usersid'].'  order by id desc limit 30 ';
        $list=db::get_all($sql);
        $this->assign('list',$list);
        $this->display('pack');
    } */
    
}
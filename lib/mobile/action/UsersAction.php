<?php
class UsersAction extends BaseAction
{
    function __construct()
    {
        $this->no_login=['reg','login','resetpwd'];
        parent::__construct();
    }
    function index(){
        $this->RefreshPoints();
        $sql='select ifnull(sum(tzpoints),0) as tzpoints from game_day_static where TO_DAYS(TIME)=TO_DAYS(NOW()) and uid='.$_SESSION['usersid'];
        $tzpoint=db::get_one($sql);
        $this->assign('tzpoint',number_format($tzpoint->tzpoints));
        $sql='select ifnull(sum(rmb),0) as rmb from pay_online where state=1 and TO_DAYS(pay_time)=TO_DAYS(NOW()) and uid='.$_SESSION['usersid'];
        $rmb=db::get_one($sql);
        $this->assign('rmb',number_format($rmb->rmb));
        $this->display('user_index');
    }
    function out(){
        unset($_SESSION['usersid']);
        @session_destroy();
        @setcookie("usersid");
        @setcookie("password");
        header("location:/mobile.php");
        exit;
    }
    
    public function resetpwd(){
    	if($_POST)return $this->act_resetpwd();
    	
    	$this->display('resetpwd');
    }
    
    private function act_resetpwd(){
        $data=[];
        $data['mobile']=Req::post('mobile');
        $data['password']=Req::post('password');
        $data['cpassword']=Req::post('cpassword');
        $data['vercode']=(int)Req::post('vercode');

		$username = $data['mobile'];
        
        if(!$this->is_mobile($data['mobile']) || !is_numeric($username)){
            return $this->result(1,'请输入正确的手机号码');
        }
        if(strlen($data['password'])<6 || strlen($data['password'])>20){
            return $this->result(1,'请保持密码在6-20位之间');
        }
        if($data['password']!=$data['cpassword']){
            return $this->result(1,'两次密码不一致');
        }
        
        $HangShiForbid = ['1303440','1303441','1303442','1303443','1304270','1308520','1309275','1309276','1309277','1309278','1309279','1309839','1311442','1311701','1311702','1311703','1313592','1313593','1314723','1316567','1316568','1317730','1317731','1317732','1317733','1319700','1319701','1319702','1319703','1319704','1321714','1323723','1323724','1323725','1324723','1329423','1329721','1329722','1329723','1329724','1329764','1329765','1329766','1330723','1331723','1332992','1332993','1333990','1333991','1333992','1334982','1336714','1337791','1338526','1338710','1339723','1345104','1345105','1345106','1345107','1347678','1347773','1347774','1347775','1350723','1354549','1354550','1354551','1354552','1354553','1354554','1354555','1354556','1358128','1358129','1359761','1359762','1359763','1359764','1359765','1359766','1359767','1359768','1359769','1359770','1359771','1359772','1359773','1359774','1361714','1361721','1364714','1365714','1366904','1367714','1368718','1370723','1379777','1379778','1380723','1387205','1387206','1387207','1387208','1387209','1387210','1387211','1387212','1387213','1387214','1388645','1388646','1388647','1388648','1388649','1390723','1397175','1397176','1397177','1397178','1397276','1397277','1397278','1397279','1397280','1398657','1398658','1398659','1398660','1399595','1399596','1399597','1399598','1399599','1500723','1507201','1507202','1507203','1507204','1507205','1507206','1507207','1507293','1510723','1517200','1517201','1517202','1517203','1517204','1517205','1517206','1517207','1517208','1517209','1517210','1517211','1520723','1527165','1527166','1527203','1527204','1527205','1530723','1532787','1532788','1533427','1533428','1533588','1533738','1534729','1554970','1554971','1554972','1557148','1557291','1557292','1557293','1557294','1557295','1557296','1557297','1557298','1557299','1560723','1561499','1562939','1562959','1562970','1562971','1562990','1567122','1567123','1567175','1567176','1567177','1567178','1569723','1580723','1582694','1582695','1582696','1582697','1582698','1587113','1587114','1587115','1587116','1587117','1587118','1587119','1587120','1587121','1589775','1589776','1589777','1589778','1589779','1590723','1592690','1592691','1597151','1597152','1597153','1597154','1597155','1597235','1597236','1597237','1597252','1597253','1597254','1599710','1599711','1599712','1599713','1599714','1860723','1867163','1880723','1887275','1887276','1887277','1890723','1897175','1897176','1897177','1897178','1897276','1897277','1897278','1897279','1897280','1898657','1898658','1898659','1898660','1899577','1899578','1899579','1899580'];
        if(in_array(substr(trim($username),0,7) , $HangShiForbid)){
        	return $this->result(1, "重置失败!");
        }
        
        $Sms = new sms();
        $code = $Sms->get_code($username);
        if((int)$code['verifytimes'] >= 5){
        	return $this->result(1, "验证码输错了5次,请重发验证码!");
        }
        
        if($data['vercode'] == '' || $data['vercode'] != $code['code']){
        	$Sms->cumulate_verifytimes($code['id']);
            return $this->result(1,'手机验证码不对' );
        }else{
            $_SESSION['mobilesmscode']='';
        }
        $pass=$this->setPassword($data['password']);
        
        	
        $sql = "call web_user_resetpwd('{$username}','{$pass}')";
        $arr = db::get_one($sql,'assoc');
        $status=1;
        switch($arr["result"])
        {
            case '0': //成功
                $arrRet['cmd'] = 'ok';
                $status=0;
                break;
            case '1': //用户不存在
                $arrRet['cmd'] = '很抱歉！帐号不存在，请更改！';
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
    
    
    function login(){

        if($_POST)return $this->act_login();
        if($_COOKIE['username'])$this->assign('username',$_COOKIE['username']);
        $this->display('login');
    }
    private function act_login(){

        $data=[];
        $data['mobile']=Req::post('mobile');
        $data['password']=Req::post('password');
        $data['captcha']=Req::post('captcha');
        $isKeepLogin = Req::post('iskeep');
        if(!$this->is_mobile($data['mobile'])){
            return $this->result(1,'请输入正确的手机号码');
        }
        if(strlen($data['password'])<6 || strlen($data['password'])>20){
            return $this->result(1,'请保持密码在6-20位之间');
        }

        if (strlen($data['captcha']) != 4 || $_SESSION['CheckNum']=='' || $_SESSION['CheckNum']!=$data['captcha']) {
            return $this->result(1,'验证码不对');
        }
        $data['password']=$this->setPassword($data['password']);
        if($_SESSION['username']) //用户正在使用
        {
            //return $this->result(1,'用户正在使用');

        }
        $ip=get_ip();
        $sql = "call web_user_login('" . $data['mobile'] . "','" . $data['password'] . "','{$ip}')";
        $arr=db::get_one($sql,'assoc');
        switch($arr["result"])
        {
            case '0': //成功
                $_SESSION["usersid"]=$arr["userid"];
                $_SESSION["username"]= $arr["username"];
                $_SESSION["password"]= $data['password'];
                $_SESSION["nickname"]= $arr["nickname"];
                $_SESSION["points"]=$arr["points"];
                $_SESSION["bankpoints"]=$arr["bankpoint"];
                $_SESSION["exp"]= $arr["experience"];
                $_SESSION["head"]= $arr["head"];
                $_SESSION['freeze']= 0;
                $_SESSION['logintime'] = $arr["logintime"];
                
                $usersid = $arr["userid"];
                $sql = "select u.isagent,a.id from users u, agent a where a.uid=u.id and  u.id = '{$usersid}' limit 1";
                $users = db::get_one($sql,'assoc');
                if(!empty($users)){
                	$_SESSION['isagent'] = $users['isagent'];
                	$_SESSION['Agent_Id'] = $users['id'];
                }
                
                $message = 'ok';
                break;
            case '1': //用户名或密码错误
                $message = '用户名或密码错误';
                break;
            case '2': //帐号被冻结
                $message = '帐号被冻结';
                break;
            case '99': //数据库错误
                $message = '登录失败';
                break;
            default:
                $message = 'other';
                break;
        }

        return $this->result($arr['result'],$message);
    }
    function reg(){
        if($_POST)return $this->act_reg();
        $tj=Req::get('tj','intval');
        if(!$tj)$tj=$_COOKIE['tj']?:'';
        $this->assign('tj',$tj);
        setcookie('tj',$tj,3600);

        return $this->display('user_reg');
    }
    private function act_reg(){
        $data=[];
        $data['mobile']=Req::post('mobile');
        $data['password']=Req::post('password');
        $data['cpassword']=Req::post('cpassword');
        $data['vercode']=Req::post('vercode');

        $nickname = Req::post('nickname');
        $username = Req::post('mobile');
        $tjid = Req::post('tjid', 'intval')?:0;
        $nickname = str_replace("http", "", $nickname);
        $nickname = str_replace("href", "", $nickname);
        $source=$_COOKIE["referer"];//Req::post('t','intval')?:0;
        
        if(!$this->is_mobile($data['mobile'])){
            return $this->result(1,'请输入正确的手机号码');
        }
        if(strlen($data['password'])<6 || strlen($data['password'])>20){
            return $this->result(1,'请保持密码在6-20位之间');
        }
        if($data['password']!=$data['cpassword']){
            return $this->result(1,'两次密码不一致');
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
        
        if($data['vercode'] == '' || $data['vercode'] != $code['code']){
        	$Sms->cumulate_verifytimes($code['id']);
            return $this->result(1,'手机验证码不对' );
        }else{
            //$Sms->cumulate_verifytimes($code['id'] , 5);
            $_SESSION['mobilesmscode']='';
        }
        $pass=$this->setPassword($data['password']);
        
        //unset($data['cpassword']);
        $ip=get_ip();
        
        	
        $sql = "call web_user_mobile_reg(0,0,'{$username}','{$nickname}','{$pass}','{$ip}',{$tjid},'{$source}')";
        $arr = db::get_one($sql,'assoc');
        $status=1;
        switch($arr["result"])
        {
            case '0': //成功
                $_SESSION["usersid"] = $arr["userid"];
                $_SESSION["username"] = $arr["username"];
                $_SESSION["password"] = $pass;
                $_SESSION["nickname"] = $arr["nickname"];
                $_SESSION["points"] = $arr["points"];
                $_SESSION["bankpoints"] = 0;
                $_SESSION["exp"] = $arr["experience"];
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


        $sql = "SELECT id,username,mobile,nickname FROM users WHERE username ='{$username}' or nickname='{$nickname}'";
        $user = db::get_one($sql);

        if ($user->id && $user->username == $username) return $this->result(1, '很抱歉！帐号重名了，请更改！');

        if ($user->nickname == $nickname) return $this->result(1, '很抱歉!昵称重名，请更改！');

        $sql = "SELECT 1 FROM deny_words WHERE deny_type = 'b' AND keyword LIKE '%{$nickname}%'";
        $check_user = db::get_one($sql);
        if ($check_user) return $this->result(1, '很抱歉!用户名含有不允许字符，请更改!');
        //-- 注册赠送豆
        $sql = 'SELECT reg_points,web_loginperience FROM web_config WHERE id = 1';
        $point = db::get_one($sql);
        //uid
        $uid=$this->set_uid();
        //注册
        $pass =$this->setPassword($pass);
        $data = array(
            'id'=>$uid,
            'username' => $username,
            'nickname' => $nickname,
            'password' => $pass,
            'is_check_mobile' => 1,
            'mobile' => $username,
            'bankpwd' => $pass,
            'points' => $point->reg_points,
            'experience' => $point->web_loginperience,
            'maxexperience' => $point->web_loginperience,
            'time' => date('Y-m-d H:i:s'),
            'regip' => $ip,
            'tjid' => $tjid,
            'usertype' => 0,
            'loginip' => $ip,
            'logintime' => date('Y-m-d H:i:s'));
        db::_insert('users', $data);
        if(!$uid)return $this->result(1,'注册失败' );
        //-- 个人统计
        $data = array('uid' => $uid, 'typeid' => 120, 'points' => $point->reg_points);
        db::_insert('game_static', $data);

        //-- 记录中央银行
        if ($point->reg_points > 0) {
            db::_query('UPDATE centerbank SET score = score -' . $point->reg_points . ' WHERE bankIdx = 6');
        }

        //-- 记录统计
        $sql = 'SELECT 1 FROM webtj WHERE `time` = CURDATE()';
        $tj = db::get_one($sql);
        if ($tj) {
            db::_query('UPDATE webtj SET regnum = regnum + 1,regpoints = regpoints + ' . $point->reg_points . '
				WHERE `time` = CURDATE();');
        } else {
            db::_query('INSERT INTO webtj(`time`,regnum,regpoints) VALUES(CURDATE(),1,' . $point->reg_points . ');');
        }

        //-- 记录经验变化日志
        if ($point->web_loginperience > 0) {
            db::_query("INSERT INTO userslog(usersid, `time`, experience, logtype, `log`)
				VALUES({$uid}, NOW(), " . $point->web_loginperience . ", 4, CONCAT('登录奖励', " . $point->web_loginperience . ", '经验值'))");
        }
        //-- 记录登录日志
        db::_insert('login_success', array('uid' => $uid, 'username' => $username,
            'nickname' => $nickname, 'point' => $point->reg_points,
            'bankpoint' => 0,
            'exp' => $point->web_loginperience,
            'loginip' => $ip,
            'login_time' => date('Y-m-d H:i:s')));

        //-- 推荐人数统计
        if ($tjid > 0) {
            db::_query('UPDATE users SET tj_level1_count = tj_level1_count + 1 WHERE id = ' . $tjid);
            db::_query('UPDATE users SET tj_level2_count = tj_level2_count + 1
			WHERE id IN(SELECT * FROM(SELECT tjid FROM users WHERE id = ' . $tjid . ') t');
            db::_query('UPDATE users SET tj_level3_count = tj_level3_count + 1 WHERE id IN(                SELECT * FROM(
                    SELECT tjid FROM users WHERE id IN(SELECT tjid FROM users WHERE id = ' . $tjid . ')) t)');
        }

        $_SESSION["usersid"] = $uid;
        $_SESSION["username"] = $username;
        $_SESSION["password"] = $pass;
        $_SESSION["nickname"] = $nickname;
        $_SESSION["points"] = $point->reg_points;
        $_SESSION["bankpoints"] = 0;
        $_SESSION["exp"] = $point->web_loginperience;
        setcookie("usersid", $uid);
        setcookie("username", $username);
        setcookie("password", $pass);
        setcookie("reg", 1, time() + 8640);
        return $this->result(0, 'ok');
        
    }
    function set_uid(){
        $sql='SELECT FLOOR(100000 + RAND()*900000) AS random_num FROM users WHERE "random_num" !=id LIMIT 1';
        $uid=db::get_one($sql);
        if(!$uid)return $this->set_uid();
        return $uid->random_num;
    }
    function changePass(){
        if(IS_AJAX){
            return $this->AjaxChangePass();
        }
        $this->display('change_pass');
    }
    private function AjaxChangePass(){
        $t=Req::post('t')?1:0;
        $old=Req::post('old');
        $news=Req::post('news');
        $cnew=Req::post('cnew');
        if(strlen($old)<6){
            return $this->result(1,'密码必须大于6位');
        }
        if(strlen($news)<6 || strlen($news)>20){
            return $this->result(1,'密码在6-20位之间');
        }
        if($news!=$cnew){
            return $this->result(1,'两次密码不一致');
        }
        
        $old=$this->setPassword($old);
        $sql='select password,bankpwd from users where id='.$_SESSION['usersid'];
        $info=db::get_one($sql);
        if($t){
            if($info->password!=$old){
                return $this->result(1,'原密码不对!');
            }
            $sql='update users set password=\''.$this->setPassword($news).'\' where id='.$_SESSION['usersid'];
            db::_query($sql,false);
        }else{
            if($info->bankpwd!=$old){
                return $this->result(1,'原密码不对!');
            }
            db::_update('users',['bankpwd'=>$this->setPassword($news)],'id='.$_SESSION['usersid']);
        }
        return $this->result(0,'修改成功!');
    }
    
    function getmymoney(){
    	$usersid = (int)$_SESSION['usersid'];
    	$sql='select points,back from users where id='.$usersid;
    	$scoreinfo=db::get_one($sql,'assoc');
    	if(!empty($scoreinfo)){
    		$scoreinfo['status'] = 0;
    	}else{
    		$scoreinfo['status'] = 1;
    	}
    	 
    	echo json_encode($scoreinfo,JSON_UNESCAPED_UNICODE);
    }
    
    function mybank(){
        if(IS_AJAX)return $this->Ajax_mybank();

        $this->display('user_mybank');
    }
    private function Ajax_mybank(){
        $Score =Req::post('point','intval')?:0;
        $Score*=1000;
        $Pwd = Req::post('pwd')?:"";

        $Pwd =$this->setPassword($Pwd);
        $t=Req::post('t');
        $oprType = ($t=="save")?0:1;
        $ip = get_ip();
        $arrRet = array('cmd'=>'ok','msg'=>'');

        $Score = intval($Score);
        if(!is_numeric($Score) || $Score < 0){
            return $this->result(1,"数量必须为正整数!");
        }

        $sql = "call web_score_process({$_SESSION['usersid']},{$oprType},'{$Pwd}',{$Score},'{$ip}')";
        $arr=db::get_all($sql,'assoc');
        switch($arr[0]["result"])
        {
            case '0': //成功
                $arrRet['cmd'] = "0";
                $arrRet['msg'] = "操作成功!";
                $this->RefreshPoints();
                break;
            case '1':
                $arrRet['cmd'] = "1";
                $arrRet['msg'] = "余额不足!";
                break;
            case '2':
                $arrRet['cmd'] = "1";
                $arrRet['msg'] = "银行密码错误(注:初始密码与登录密码相同)!";
                break;
            default:
                $arrRet['cmd'] = "1";
                $arrRet['msg'] = "系统错误，执行失败!";
                break;
        }
        return $this->result($arrRet['cmd'],$arrRet['msg']);
    }
    
    
    public function getLastPayAccount(){
    	$sql = "SELECT uid,account,`name`,cz_type FROM pay_online WHERE uid='{$_SESSION['usersid']}' AND cz_type='{$_GET['cz_type']}' ORDER BY id DESC LIMIT 1";
    	$res=db::get_one($sql,'assoc');
    	if((int)$res['uid'] > 0){
    		echo json_encode($res);
    	}else{
    		echo json_encode([]);
    	}
    	exit;
    }
    
    

    function pay(){
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
        $this->display('user_pay');
    }
    function pay_order(){
        $order_id=Req::get('orderid');
        if(!is_numeric($order_id)){
            echo '订单错误';
            return $this->display();
        }

        $sql = 'select * from pay_online where uid=' . $_SESSION['usersid'] . ' and order_id=\'' . $order_id . '\'';
        $sql = 'select p.*,rt.is_pic,rt.name as cz_name,rt.remarks,rt.acc_name,rt.id as paytypeid from pay_online p left join recharge_type rt on rt.id=p.cz_type where p.uid=\'' . $_SESSION['usersid'] . '\' and p.order_id=\'' . $order_id . '\'';
        $order_info=db::get_one($sql,'assoc');

        if($order_info['is_pic']==0){
            $order_info['user']=explode('#',$order_info['remarks']);
            $url='';
        }else{
            $url='/pic/'.$order_info['cz_type'].'.png';
        }
	    
        
        $this->assign('order_info',$order_info);
        $this->assign('url',$url);
        $this->display('pay_order');
    }
    
    
    public function pay_order_list(){
    	$sql='select p.order_id,p.rmb,p.add_time,p.pay_time,p.state,w.name AS charge_type from pay_online p left join recharge_type w on w.id=p.cz_type 
    			where p.state in(0,1,2) and p.uid='.$_SESSION['usersid'].' order by p.id desc limit 20';
    	$list=db::get_all($sql);
    	foreach ($list as $k=>$v){
    		$list[$k]->order_id=$v->order_id;
    		$list[$k]->rmb=sprintf('%d',$v->rmb*1);
    		$list[$k]->type=$v->charge_type;
    		$list[$k]->add_time=date('m-d',strtotime($v->add_time));
    		$list[$k]->pay_time=($v->pay_time=="0000-00-00 00:00:00")?"":date('m-d',strtotime($v->pay_time));
    	}
    	$this->assign('list',$list);
    	$this->display('pay_order_list');
    }
    
    
    public function cancelorder(){
    	$order_id = (int)$_POST['order_id'];
    	$sql="update pay_online set state=3 where uid='{$_SESSION['usersid']}' and order_id='{$order_id}' and state=0";
    	if (!db::_query($sql,false)) {
    		return $this->result(1,'撤销失败!');
    	}else{
    		return $this->result(0,'撤销成功!');
    	}
    }
    
    
    function bind(){
        $this->display('user_bind');
    }
    function info(){
        if(IS_AJAX)return $this->ajax_info();
        $sql = "select nickname,email,is_check_email,mobile,is_check_mobile,head,qq,caption,username,recv_cash_name,card
            from users
            where id = '{$_SESSION['usersid']}'";
        $row=db::get_one($sql);

        $str=substr($row->recv_cash_name,3,strlen($row->recv_cash_name));
        $row->recv_cash_name_show=str_replace($str,"***",$row->recv_cash_name);

        $str=substr($row->qq,4,strlen($row->qq));
        $row->qq_show=str_replace($str,"*****",$row->qq);
        
        $this->assign('row',$row);
        $this->display('user_info');
    }
    
    private function ajax_info(){
        $NickName = isset($_POST['nickname'])?(Req::post('nickname')):"";
        $Head = isset($_POST['head'])?Req::post('head'):"";
        $Mobile = isset($_POST['mobile'])?Req::post('mobile'):"";
        $Email = isset($_POST['email'])?(Req::post('email')):"";
        $QQ = isset($_POST['qq'])?Req::post('qq'):"";
        $Caption = isset($_POST['caption'])?(Req::post('caption')):"";
        $arrRet = array('cmd'=>'ok','msg'=>'');
        $recv_cash_name = isset($_POST['recv_cash_name'])?(Req::post('recv_cash_name')):"";
        $card = isset($_POST['card'])?(Req::post('card')):"";
        
        $preg='/^[a-z0-9](\w|\.|-)*@([a-z0-9]+-?[a-z0-9]+\.){1,3}[a-z]{2,4}$/i';
        if($Email != "" && !preg_match($preg,$Email))
        {
            return $this->result(1,'请正确输入邮箱号!');
        }
        
        if($NickName == "" || strlen($NickName) > 20){
            return $this->result(1,'昵称错误,长度不超过20位!');
        }
        if($Mobile == "" || !is_numeric($Mobile) || strlen($Mobile) > 11) {
            return $this->result(1,'请输入常用的手机号码');
        }
        $QQ = (strlen($QQ) > 20) ? substr($QQ,0,20) : $QQ;
        
        if($recv_cash_name == ""){
        	return $this->result(1,'收款人不能为空!');
        }
        
        if(empty($Head)) $Head = "img/head/1_0.jpg";
        
        $sql = "update users set nickname='{$NickName}',head='{$Head}',mobile='{$Mobile}',email='{$Email}',caption='{$Caption}' where id = '{$_SESSION['usersid']}'";
        $_SESSION["head"] = $Head;
        $result =  db::_query($sql);
        if($recv_cash_name!=""){
            $sql = "update users set recv_cash_name='{$recv_cash_name}' where id = '{$_SESSION['usersid']}' and recv_cash_name='' ";
            db::_query($sql);
        }
        if($card!=""){
        	$sql = "update users set card='{$card}' where id = '{$_SESSION['usersid']}' and card='' ";
        	db::_query($sql);
        }
        if($QQ!=""){
            $sql = "update users set qq='{$QQ}' where id = '{$_SESSION['usersid']}' and qq='' ";
            db::_query($sql);
        }
        $this->result(0,'修改成功!');
    }
    
    
    private function jiujilist(){
    	$this->display('jiujilist');
    }
    
    function scoredetail(){
    	
    	$sql = "select opr_type,amount,log_time,ip,remark,points from score_log where uid = '{$_SESSION['usersid']}' and log_time > date_add(now(),interval -30 day) order by log_time desc ";
    	$rows =  db::get_all($sql,'assoc');
    	if(!empty($rows)){
    		foreach($rows as &$row){
    			$row['points']=number_format($row['points']);
    			switch($row['opr_type'])
    			{
    				case 0:
    					$row['opr_type'] = "<font color='#0000FF'>存豆</font>";
    					break;
    				case 1:
    					$row['opr_type'] = "<font color='#FF3300'>取豆</font>";
    					break;
    				case 2:
    					$row['opr_type'] = "<font color='#FF66CC'>充值体验卡</font>";
    					break;
    				case 3:
    					$row['opr_type'] = "<font color='#FF44CC'>转账入</font>";
    					break;
    				case 4:
    					$row['opr_type'] = "<font color='#FF55CC'>转账出</font>";
    					break;
    				case 5:
    					$row['opr_type'] = "<font color='red'>在线充值</font>";
    					break;
    				case 6:
    					$row['opr_type'] = "<font color='red'>领取救济</font>";
    					break;
    				case 7:
    					$row['opr_type'] = "<font color='red'>兑奖点卡</font>";
    					break;
    				case 9:
    					$row['opr_type'] = "<font color='red'>投注返还</font>";
    					break;
    				case 10:
    					$row['opr_type'] = "<font color='red'>在线提现</font>";
    					break;
    				case 12:
    					$row['opr_type'] = "<font color='red'>提现退回</font>";
    					break;
    				case 20:
    					$row['opr_type'] = "<font color='red'>领取返利</font>";
    					break;
    				case 21:
    					$row['opr_type'] = "<font color='red'>推荐收益</font>";
    					break;
    				case 40:
    					$row['opr_type'] = "<font color='red'>收发红包</font>";
    					break;
    				case 55:
    					$row['opr_type'] = "<font color='red'>系统充值</font>";
    					break;
    				case 70:
    					$row['opr_type'] = "<font color='red'>轮盘抽奖</font>";
    					break;
    				case 80:
    					$row['opr_type'] = "<font color='red'>排行榜奖励</font>";
    					break;
    				default:
    					$row['opr_type'] = "<font color='#FF7733'>其他</font>";
    					break;
    			}
    		}
    	}
    	
    	$this->assign('rows',$rows);
    	$this->display('scoredetail');
    }
}
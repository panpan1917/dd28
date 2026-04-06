<?php
/**

 * Date: 2015/7/24

 */
//session_start();
class BaseAction extends Action{
    protected $info=array();
    protected $webname;
    protected $no_login=[];
    function _initialize(){
        $this->info=mangerlogin::account();
        if(!in_array(Req::request('a')?:'index',$this->no_login )) $this->login_check();
        $this->assign('info',$this->info);
        $this->assign('webname',$this->config['k_webname']);
        $this->assign('c',Req::get('c'));
        $this->infos();
        
        if($this->is_weixin()){
        	$this->assign('is_weixin',1);
        }else{
        	$this->assign('is_weixin',0);
        }
        
        $this->global_check();
        if(!empty($_GET['referer']))setcookie('referer',$_GET['referer']);
        
        
        
        if(isset($_SESSION['logintime']) && !empty($_SESSION['logintime'])){
        	$sql = "select logintime from users where id = '{$_SESSION['usersid']}'";
        	$result=db::get_one($sql,'assoc');
        	if(!empty($result))
        	{
        		if($result['logintime'] != $_SESSION['logintime']){
        			session_destroy();
        			echo "<meta charset=\"utf-8\" />";
        			echo "<script>alert('您的账号在异地或其他客户端登录！');window.location='./mobile.php?c=users&a=login';</script>";
        			exit;
        		}
        	}
        }
    }
    
    
    protected function global_check(){
    	foreach($_GET as $key=>$value){
    		if($this->filterKey($value)) exit("didi8888 access denied!");
    	}
    	foreach($_POST as $key=>$value){
    		if($this->filterKey($value)) exit("didi8888 access denied!");
    	}
    	foreach($_COOKIE as $key=>$value){
    		if($this->filterKey($value)) exit("didi8888 access denied!");
    	}
    	foreach($_REQUEST as $key=>$value){
    		if($this->filterKey($value)) exit("didi8888 access denied!");
    	}
    }
    
    protected function filterKey($str){
    	return preg_match('/PHP_EOL|replace|group_concat|table|create|call|drop|database|alter|select|insert|update|delete|name_const|where|having|from|\sand\s|\sor\s|truncate|script|union|into|\'|\/\*|\*|\.\.\/|\.\/|#|load_file|outfile/i',$str,$matches);
    }
    
    
    protected function setPassword($str){
        return md5($GLOBALS['web_pwd_encrypt_prefix'].$str);
    }
    protected function log($uid,$type,$con){
        db::_insert('log',array('masterid'=>$this->info['masterid'],'bmasterid'=>$uid,'createdate'=>date('Y-m-d H:i:s'),'ip'=>get_ip(),'con'=>$con));
    }
    function is_login(){
        if(!$_SESSION['usersid']){
            header('location:'.url('users/login'));
        }
    }
    function login_check(){

        if (empty($_SESSION["usersid"]) || empty($_SESSION["username"])){
            session_destroy();
            echo "<script>window.location='./mobile.php?c=users&a=login';</script>";
            exit;
        }else{
            if(!isset($_SESSION['usersid']))
            {
                $ip = get_ip();
                $username = Req::request($_COOKIE["username"]);

                $pwd = $_COOKIE["password"];
                $pwd = (strlen($pwd) > 30) ? substr($pwd,0,30) : $pwd;
                $pwd = $this->setPassword($pwd);

                $username = (strlen($username) > 50) ? substr($username,0,50) : $username;


                $sql = "call web_user_login('{$username}','{$pwd}','{$ip}')";
                $arr = db::get_all($sql,'assoc');
                switch($arr[0][0]["result"])
                {
                    case '0': //成功
                        $_SESSION["usersid"] = $arr[0][0]["userid"];
                        $_SESSION["username"] = $arr[0][0]["username"];
                        $_SESSION["password"] = $pwd;
                        $_SESSION["nickname"] = $arr[0][0]["nickname"];
                        $_SESSION["points"] = $arr[0][0]["points"];
                        $_SESSION["bankpoints"] = $arr[0][0]["bankpoint"];
                        $_SESSION["exp"] = $arr[0][0]["experience"];
                        $_SESSION['freeze'] = 0;
                        $_SESSION['logintime'] = $arr[0][0]["logintime"];
                        setcookie("usersid",$arr[0][0]["userid"]);
                        setcookie("username",$arr[0][0]["username"]);
                        setcookie("password",$pwd);
                    case '2': //帐号被冻结
                        setcookie("usersid");
                        setcookie("username");
                        setcookie("password");
                        echo "<meta charset=\"utf-8\" />";
                        echo "<script language=javascript>alert('很抱歉!您帐号被冻结无法登录，请与客服联系!');window.location='mobile.php?c=users&a=login';</script>";
                        exit;
                    case '99': //数据库错误
                    	echo "<meta charset=\"utf-8\" />";
                        echo "<script language=javascript>alert('很抱歉!由于系统故障暂时无法登录，请与客服联系!');window.location='mobile.php?c=users&a=login';</script>";
                        exit;
                    default:
                        echo "<script language=javascript>window.location='mobile.php?c=users&a=login';</script>";
                        exit;
                }
            }
            //如果帐号被冻结，立即注销退出
            if(isset($_SESSION['freeze']) && $_SESSION['freeze'] == 1)
            {
                //退出
                session_destroy();
                setcookie("usersid");
                setcookie("username");
                setcookie("password");
	            echo "<meta charset=\"utf-8\" />";
	            echo "<script language=javascript>
	            		alert('您的账号已经被冻结！');
	            		window.location='mobile.php?c=users&a=login';
	            	  </script>";
	            exit;
            }
        }
    }
	function is_weixin(){ 
		if ( stripos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
				return true;
		}	
		return false;
	}
    function infos(){
        $data=['usersid'=>0];
        if($_SESSION['usersid']){
            $data['usersid']=$_SESSION['usersid'];
            $data['username']=$_SESSION['username'];
            $data['nickname']=$_SESSION['nickname'];
            $data['points']=sprintf('%.2f',$_SESSION['points']/1000);
            $data['bankpoints']=sprintf('%.2f',$_SESSION['bankpoints']/1000);
            $data['exp']=$_SESSION['exp'];
            $data['isagent']=$_SESSION['isagent'];
            $data['Agent_Id']=$_SESSION['Agent_Id'];
            $data['vip_level']=$_SESSION['vip_level'];
            $data['head']=$_SESSION['head'];
        }
        $this->info=$data;
        //var_dump($this->info);exit;
        $this->assign('info',(object)$data);
    }
    function Post($curlPost,$url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }
    function is_mobile($m){
        if(preg_match("/^1[345789]{1}\d{9}$/",$m)){
            return true;
        }else{
            return false;
        }
    }
    function result($status,$msg){
        echo json_encode(['status'=>$status,'message'=>$msg],JSON_UNESCAPED_UNICODE);
    }
    /* 更新分数
      *
     */
    function RefreshPoints()
    {
        $sql = "select points,back,dj from users where id = '{$_SESSION['usersid']}'";
        $info=db::get_one($sql,'assoc');
        if($info['points']){
            //$info['points']=sprintf('%.2f',$info['points']/1000);
            //$info['back']=sprintf('%.2f',$info['back']/1000);
            $_SESSION['points'] = $info['points'];
            $_SESSION['bankpoints'] = $info['back'];
            $_SESSION['freeze'] = $info['dj'];
            $this->infos();
        }
        //如果帐号被冻结，立即注销退出
        if(isset($_SESSION['freeze']) && $_SESSION['freeze'] == 1){
            //退出
            session_destroy();
            setcookie("usersid");
            setcookie("username");
            setcookie("password");
            echo "<meta charset=\"utf-8\" />";
            echo "<script language=javascript>
            		alert('您的账号已经被冻结！');
            		window.location='mobile.php?c=users&a=login';
            	  </script>";
            exit;
        }
        return array('points'=>$info['points'],'bank'=>$info['back']);
    }


    function admin_log($opr,$amount,$points,$bank,$remark,$uid=0){
        /*
    uidbigint(20) NOT NULL用户id
    opr_typeint(11) NOT NULL类型，0：存，1：取，2：充值体验卡，3：转账入，4：转账出,5:在线充值,6:领取救济,7:兑奖点卡,8:推荐收益,55:系统会员充值,12:退回提现,10:提现通过,11:提现申请
    amountbigint(20) NOT NULL数量
    log_timedatetime NOT NULL时间
    ipvarchar(15) NOT NULLip
    pointsbigint(20) NOT NULL操作后豆
    bankpointsbigint(20) NOT NULL操作后银行豆
    remarkvarchar(254) NOT NULL备注
         */
        global $db;
        $uid=$uid?$uid:$_SESSION['usersid'];
        $ip=get_ip();
        $sql="INSERT INTO score_log(uid,opr_type,amount,log_time,ip,points,bankpoints,remark) values ('{$uid}','{$opr}','{$amount}',now(),'{$ip}','{$points}','{$bank}','{$remark}')";
        db::_query($sql);
    }
    function withdrawals_log($opr,$amount,$points,$bank,$remark){
        $this->admin_log($opr,$amount, $points, $bank, $remark,$_SESSION['usersid']);

    }
    function cz_type($id){
        $arr=[1=>'支付宝',2=>'微信',3=>'银行卡',4=>'支付宝',5=>'微信',7=>'借贷宝'];
        return $arr[$id];
    }
    protected function update_centerbank($point,$type){
        //-- 更新中央银行
        $sql='UPDATE centerbank SET score = score - '.$point.' WHERE bankIdx = '.$type;
        return db::_query($sql);
    }
    
    protected function score_log($uid,$type,$amount,$points,$bankpoints,$remark=''){
        $data=array('uid'=>$uid,'opr_type'=>$type,//类型，0：存，1：取，2：充值体验卡，3：转账入，4：转账出,5:在线充值,6:领取救济,7:兑奖点卡,8:推荐收益,55:系统会员充值\',
            'amount'=>$amount,
            'log_time'=>date('Y-m-d H:i:s'),
            'ip'=>get_ip(),
            'points'=>$points,
            'bankpoints'=>$bankpoints,
            'remark'=>$remark);
        return db::_insert('score_log',$data );
    }
}
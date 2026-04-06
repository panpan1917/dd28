<?php
/**
 * Date: 2015/7/24
 */
session_start();
class BaseAction extends Action{
    protected $info=array();
    protected $webname;
    protected $nologin=array();
    function _initialize(){
        if(!in_array(Req::request('a'),$this->nologin ))return $this->login_check();
        $this->assign('c',Req::get('c'));
        
        $this->global_check();
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
    
    
    protected function log($uid,$type,$con){
        db::_insert('log',array('masterid'=>$this->info['masterid'],'bmasterid'=>$uid,'createdate'=>date('Y-m-d H:i:s'),'ip'=>get_ip(),'con'=>$con));
    }
    function result($status=0,$msg=''){
        echo json_encode(['status'=>$status,'message'=>$msg],JSON_UNESCAPED_UNICODE);
        exit;
    }
    function login_check(){
        if (empty($_SESSION["usersid"]) || empty($_SESSION["password"]) || empty($_SESSION["username"])){
            session_destroy();
        
            //echo "<script>location.href='/login.php';</script>";
            exit;
        }else{
            //如果帐号被冻结，立即注销退出
            if(isset($_SESSION['freeze']) && $_SESSION['freeze'] == 1)
            {
                //退出
                session_destroy();
		            echo "<meta charset=\"utf-8\" />";
		            echo "<script language=javascript>
		            		alert('您的账号已经被冻结！');
		            		window.location='index.php';
		            	  </script>";
		            exit;
            }
        }
    }
    
    function parameterCheck($str) {
    	return preg_match('/select|insert|update|delete|name_const|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i',$str,$matches);
    }
    
    protected function setPassword($str){
        return md5($GLOBALS['web_pwd_encrypt_prefix'].$str);
    }
    /* 更新分数
 *
*/
    function RefreshPoints()
    {
        $sql = "select points,back,dj from users where id = '{$_SESSION['usersid']}'";
        $list=db::get_all($sql,'assoc');
        foreach ($list as $rs)
        {
            $_SESSION['points'] = $rs['points'];
            $_SESSION['bankpoints'] = $rs['back'];
            $_SESSION['freeze'] = $rs['dj'];
        }
        //如果帐号被冻结，立即注销退出
        if(isset($_SESSION['freeze']) && $_SESSION['freeze'] == 1)
        {
            //退出
            session_destroy();
            echo "<meta charset=\"utf-8\" />";
            echo "<script language=javascript>
            		alert('您的账号已经被冻结！');
            		window.location='index.php';
            	  </script>";
            exit;
        }
    }
    protected function update_centerbank($point,$type){
        //-- 更新中央银行
        $sql='UPDATE centerbank SET score = score - '.$point.' WHERE bankIdx = '.$type;
        return db::_query($sql);
    }

    function cz_type($id){
        $arr=[1=>'支付宝',2=>'微信',3=>'银行卡',4=>'支付宝',5=>'微信',7=>'借贷宝'];
        return $arr[$id];
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
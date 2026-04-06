<?php
class BankingAction extends BaseAction
{
    private $uid;
    function __construct()
    {
    	//echo "维护中......";exit;
        $this->no_login=['reg','login'];
        parent::__construct();
        $this->uid=intval($_SESSION['usersid']);
        $this->RefreshPoints();
    }
    
    function index(){
        $sql='select recv_cash_name from users where id='.$_SESSION['usersid'];
        $user_info=db::get_one($sql);
        $this->assign('recv_cash_name',$user_info->recv_cash_name);
        $sql="select * from withdrawals where uid=".$_SESSION['usersid'];
        $list=db::get_all($sql);
        foreach ($list as $k=>$v){
            $list[$k]->account=str_replace('/','',$v->account);
            $list[$k]->type=$this->cz_type($v->type);
        }
        $this->assign('list',$list);
        return;
        $this->display('banking_index');
    }

    function withdrawals(){
    	$this->RefreshPoints();
        if(IS_AJAX)return $this->ajax_withdrawals();
        $sql='select id,type from withdrawals where uid=\''.$_SESSION['usersid'].'\'';
        $list=db::get_all($sql);
        foreach ($list as $k=>$v){
            if($v->type==2 || $v->type==5){unset($list[$k]);continue;}
            $list[$k]->cz_type=$this->cz_type($v->type);
        }
        $this->assign('list',$list);
        $this->display('banking_withdrawals');
    }
    private function ajax_withdrawals(){
        $pass =Req::post('pass');
        $money=Req::post('money');
        $select=Req::post('select','intval');
        if(empty($pass)) //密码空
        {
            return $this->result(1,'请输入安全密码！');
        }
        if($money<MIN_CASH){
            return $this->result(1,'单笔最小提款额：' . MIN_CASH . ' RMB');
        }
        if($money%100 != 0){
        	return $this->result(1,'单笔提款额必须是100的整数倍');
        }
        
        $sql = "select recv_cash_name from users where id={$_SESSION['usersid']}";
        $res=db::get_one($sql,'assoc');
        if(empty($res['recv_cash_name'])){
        	return $this->result(3,'还没进行实名认证。');
        }
        
        $pass =$this->setPassword($pass);
        
        
        $money*=1000;
        $ip=get_ip();
        $sql='call withdrawals(0,'.$this->uid.','.$money.','.$select.',\''.$ip.'\',\''.$pass.'\')';
        
		//判断是否免费提现过两次
        $sql2 = "SELECT count(*) as cnt,SUM(point/1000) AS cashmoney FROM `pay_online` WHERE uid={$_SESSION['usersid']} and state=32 and to_days(now())-to_days(pay_time)=0";
        $cashrow=db::get_one($sql2,'assoc');
        if($cashrow['cnt'] >= 2){
        	$sql='call withdrawals2(0,'.$this->uid.','.$money.','.$select.',\''.$ip.'\',\''.$pass.'\','.CASH_FEE_RATE.')';
        }else{
        	$sql2 = "SELECT count(*) as cnt FROM `pay_online` WHERE uid={$_SESSION['usersid']} and state=1 and to_days(now())-to_days(pay_time)=0";
        	$chargerow=db::get_one($sql2,'assoc');
        	$chargetimes = (int)$chargerow['cnt'];
        	
        	$sql2 = "SELECT sum(totalscore) as totalscore FROM `presslog` WHERE uid={$_SESSION['usersid']} and to_days(now())-to_days(presstime)=0";
        	$rs = db::get_one($sql2,'assoc');
        	$totalscore = (int)$rs['totalscore'];//当天总投注分数
        	if($chargetimes > 0){
        		//$cashmoney = (int)$cashrow['cashmoney'];//当天已经提现的金额
        		//$cashmoney = abs($cashmoney);//当天已经提现的金额绝对值
        		//$allowcash = floor($totalscore/1000/FREE_CASH_FEE_RATE/100)*100 - $cashmoney;//当天前两次可免费提现金额
        		$allowcash = floor($totalscore/1000/FREE_CASH_FEE_RATE/100)*100;
        		if($allowcash < 0)$allowcash = 0;
        		
	        	if($money > $allowcash*1000){
	        		$cashfee = FLOOR((($money - $allowcash*1000)*CASH_FEE_RATE/1000)*100)/100;//手续费
	        		$cashfee = abs($cashfee);
	        		//$money = abs($money - $cashfee*1000);//实际提现分数
	        		$sql='call withdrawals3(0,'.$this->uid.','.$money.','.$select.',\''.$ip.'\',\''.$pass.'\','.$cashfee.')';
	        	}
        	}
        }
        
        $res=db::get_one($sql);
        return $this->result($res->result,$res->msg);
    }
    function withdrawals_list(){
        $sql='select p.rmb,p.add_time,p.pay_time,p.state,w.type from pay_online p left join withdrawals w on w.uid=p.uid and p.cz_type=w.id where p.state in(30,31,32) and p.uid='.$_SESSION['usersid'].' order by p.id desc limit 20';
        $list=db::get_all($sql);
        foreach ($list as $k=>$v){
            $list[$k]->rmb=sprintf('%d',$v->rmb*-1);
            $list[$k]->type=$this->cz_type($v->type);
            $list[$k]->add_time=date('m-d',strtotime($v->add_time));
            $list[$k]->pay_time=date('m-d',strtotime($v->pay_time));
        }
        $this->assign('list',$list);
        $this->display('banking_withdrawals_list');
    }
    
}
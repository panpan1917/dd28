<?php
class BankingAction extends BaseAction
{
    private $uid;
    function __construct()
    {
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
        $this->display('banking_index');
    }
    function bind(){
        $id=Req::get('id','intval')?:0;
        $sql='select * from withdrawals where id='.$id.' and uid='.$_SESSION['usersid'];
        $withdrawals=db::get_one($sql);
        if($withdrawals->type==3){
            $ex=explode('|',$withdrawals->address);
            $withdrawals->province=$ex[0];
            $withdrawals->city=$ex[1];
            $withdrawals->bank_name=$ex[2];
        }
        
        $sql="select recv_cash_name from users where id='".$_SESSION['usersid']."'";
        $rename=db::get_one($sql,'assoc');
        $this->assign('cashname',$rename['recv_cash_name']);
        
        $this->assign('withdrawals',$withdrawals);
        $this->assign('id',$id);
        $this->display('banking_bind');
    }
    function add_account(){
        $type=Req::post('type');
        $id=Req::post('id','intval')?:0;
        if($type=='alipay' || $type=='weichat'){
            $acc=Req::post('acc');
            if($acc==''){
                $this->result(1,'账户不能为空');
                return;
            }
            if($type=='alipay'){
                $s_type=1;
            }elseif($type=='weichat'){
                $s_type=7;
            }
            
            $sql="select recv_cash_name from users where id='".$_SESSION['usersid']."'";
            $rename=db::get_one($sql,'assoc');
            if($rename['recv_cash_name']==''){
                return $this->result(1,'请先去绑定收款人姓名' );
            }
            
            $sql='select id from withdrawals where uid=\''.$_SESSION['usersid'].'\' and type=\''.$s_type.'\'';
            $res=db::get_one($sql,'assoc');
            if($id>0){
                if($res['id'] == $id){
                    $sql="update withdrawals set account='$acc',add_time=now() where id='".$id."'";
                    db::_query($sql);
                    return $this->result(0,'修改成功');
                }
            }else{

                if($res)return $this->result(1,'您已经添加过了' );
                $sql="insert into withdrawals (uid,type,account,add_time) values(".$_SESSION['usersid'].",'$s_type','$acc',now())";
                db::_query($sql);
                return $this->result(0,'添加成功' );
            }
        }elseif ($type=='bank'){
            $acc=Req::post('acc');
            if($acc==''){
                $this->result(1,'账户不能为空');
                return;
            }
            $bank_type=Req::post('bank_type');
            if($type==''){
                $this->result(1,'请选择银行');
                return;
            }
            $province=Req::post('province');
            if($province==''){
                return $this->result(1,'开户行所在省份不能为空' );
            }
            $city=Req::post('city');
            if($city==''){
                return $this->result(1,'开户行所在市不能为空' );
            }
            $bank_name=Req::post('bank_name');
            if($bank_name==''){
                return $this->result(1,'开户行不能为空' );
            }
            $sql="select recv_cash_name from users where id='".$_SESSION['usersid']."'";
            $rename=db::get_one($sql,'assoc');
            if($rename['recv_cash_name']==''){
                return $this->result(1,'请先去绑定收款人姓名' );
            }
            
            $sql='select id from withdrawals where uid=\''.$_SESSION['usersid'].'\' and type=3';
            $res=db::get_one($sql,'assoc');
            if($id>0){
                if($res['id'] == $id){
                    $sql="update withdrawals set name='$bank_type',account='$acc',address='".$province .'|'. $city.'|' . $bank_name."',add_time=now() where id=$id;";
                    db::_query($sql);
                    return $this->result(0,'修改成功');
                }
            }else {
                if ($res) {
                    return $this->result(1, '您已经添加过了');

                }
                $sql = "insert into withdrawals (uid,type,account,name,address,add_time) values(" . $_SESSION['usersid'] . ",3,'$acc','$bank_type','" . $province .'|'. $city.'|' . $bank_name . "',now())";
                db::_query($sql);
                return $this->result(0, '添加成功');
            }
        }
    }
    function withdrawals(){
    	//echo "系统临时维护";exit;
    	$this->RefreshPoints();
        if(IS_AJAX)return $this->ajax_withdrawals();
        $sql='select id,type,name,account from withdrawals where uid=\''.$_SESSION['usersid'].'\'';
        $list=db::get_all($sql);
        foreach ($list as $k=>$v){
            if($v->type==2 || $v->type==5){unset($list[$k]);continue;}
            $list[$k]->cz_type=$this->cz_type($v->type);
        }
        $this->assign('list',$list);

        $sql = "SELECT count(*) as cnt FROM `pay_online` WHERE uid={$_SESSION['usersid']} and state=32 and to_days(now())-to_days(pay_time)=0";
        $cashrow=db::get_one($sql,'assoc');
        $this->assign('cashtimes',(int)$cashrow['cnt']);
        $this->assign('min_cash',MIN_CASH);
        
        $sql = "SELECT count(*) as cnt FROM `pay_online` WHERE uid={$_SESSION['usersid']} and state=1 and to_days(now())-to_days(pay_time)=0";
        $chargerow=db::get_one($sql,'assoc');
        $chargetimes = (int)$chargerow['cnt'];
        
        $sql = "SELECT sum(totalscore) as totalscore FROM `presslog` WHERE uid={$_SESSION['usersid']} and to_days(now())-to_days(presstime)=0";
        $rs = db::get_one($sql,'assoc');
        $totalscore = (int)$rs['totalscore'];//当天总投注分数
        if($chargetimes > 0){
        	$allowcash = floor($totalscore/1000/FREE_CASH_FEE_RATE/100)*100;//当天前两次可免费提现金额
        	$allowcash = number_format($allowcash);
        }else{
        	$allowcash = "不限";
        }
        $this->assign('totalscore',number_format($totalscore));
        $this->assign('allowcash',$allowcash);
        
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
        
        $pass =$this->setPassword($pass);
        
        $money*=1000;
        $ip=get_ip();
        $sql='call withdrawals(1,'.$this->uid.','.$money.','.$select.',\''.$ip.'\',\''.$pass.'\')';
        
		//判断是否免费提现过两次
        $sql2 = "SELECT count(*) as cnt,SUM(point/1000) AS cashmoney FROM `pay_online` WHERE uid={$_SESSION['usersid']} and state=32 and to_days(now())-to_days(pay_time)=0";
        $cashrow=db::get_one($sql2,'assoc');
        if($cashrow['cnt'] >= 2){
        	$sql='call withdrawals2(1,'.$this->uid.','.$money.','.$select.',\''.$ip.'\',\''.$pass.'\','.CASH_FEE_RATE.')';
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
	        		$sql='call withdrawals3(1,'.$this->uid.','.$money.','.$select.',\''.$ip.'\',\''.$pass.'\','.$cashfee.')';
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
            $list[$k]->pay_time=($v->pay_time=="0000-00-00 00:00:00")?"":date('m-d',strtotime($v->pay_time));
        }
        $this->assign('list',$list);
        $this->display('banking_withdrawals_list');
    }
    
}
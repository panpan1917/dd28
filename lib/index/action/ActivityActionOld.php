<?php
/**
 * Date: 2016/8/3
 * Time: 11:48
 */
class ActivityAction extends BaseAction
{
	function _initialize(){
		parent::_initialize();
		$this->RefreshPoints();
	}

    function index()
    {
        //$Log=new Log();
        //$id=$Log->has_get_integral(L_REBATE,$_SESSION['usersid']);
        
        $opr_type = L_REBATE;
        $sql = "select count(*) as cnt from score_log where opr_type='{$opr_type}' and uid='{$_SESSION['usersid']}' and to_days(now())=to_days(log_time)";
        $res = db::get_one($sql,'assoc');
        if($res['cnt'])
        	$id = $res['cnt'];
        else
        	$id = 0;
        
        $id = (int)$id;

        if($id){
            $pointArr=['status'=>1,'message'=>'已领取','point'=>0];
        }else{
            $pointArr = $this->get_rebate();
        }
        if($_GET['g'] && IS_AJAX)return $this->ajax_get($id,$pointArr['point']);
        $this->assign('point',(object)$pointArr);
        $sql='select * from score_log where opr_type='.L_REBATE.' and uid='.$_SESSION['usersid'].' order by id desc limit 20';
        $list=db::get_all($sql);
        $this->assign('list',$list);
        $this->assign('id',$id);
        $this->display('activity_rebate');

    }
    
    private function ajax_get($id,$point){
    	$sql = "select dj_rebate from users where id = {$_SESSION['usersid']}";
    	$res=db::get_one($sql);
    	if($res->dj_rebate == 1){
    		return $this->result(1,'领取返利已经被冻结');
    	}
    	
        if($id){
            return $this->result(1,'已经领取过了');
        }
        
        
        $point=intval($point);
        if (!$id && $point > 0) {
            $uid=$_SESSION['usersid'];
            
            	db::_query('SET AUTOCOMMIT=0');
                db::_query('begin');
                
                $opr_type = L_REBATE;
                $sql = "select count(*) as cnt from score_log where opr_type={$opr_type} and uid={$_SESSION['usersid']} and to_days(now())=to_days(log_time)";
                $res = db::get_one($sql,'assoc');
                if((int)$res['cnt'] > 0){
                	db::_query('rollback');
                	return $this->result(1,'已经领取过了');
                }

                $Bank = new Centerbank();
                
                //更新用户积分
                $ret = $Bank->update_users_point($uid, $point);
                if(!$ret){
                	return $this->result(1, '领取失败[1],请联系管理员');
                }
                
                //删除中央银行活动积分
                $ret = $Bank->update_bank(CEN_ACTIVITY_SCORE, $point, '-');
                if(!$ret){
                	return $this->result(1, '领取失败[2],请联系管理员');
                }
                
                //每日统计
                $ret = $Bank->update_day_static($uid, STATIC_REBATE, $point);
                if(!$ret){
                	return $this->result(1, '领取失败[3],请联系管理员');
                }
                
                //每日亏损返利
                $ret = $Bank->update_tj($point, TJ_REBATE);
                if(!$ret){
                	return $this->result(1, '领取失败[4],请联系管理员');
                }
                
                $ip=get_ip();
                $opr_type = L_REBATE;
                $sql="INSERT INTO score_log(uid,opr_type,amount,log_time,ip,points,bankpoints,remark) 
                		select id,{$opr_type},{$point},now(),'{$ip}',points,back,'' from users where id={$_SESSION['usersid']}";
                if(!db::_query($sql)){
                	db::_query('rollback');
                	return $this->result(1, '领取失败[5],请联系管理员');
                }
                
                db::_query('commit');
                db::_query('SET AUTOCOMMIT=1');
                
                $this->RefreshPoints();
                
                return $this->result(0, '领取成功');
        } else {
            return $this->result(1, "您没有可以领取的返利");
        }
    }
    
    
    private function get_rebate()
    {
    	//$sql = "SELECT sum(lock_points) as presspoints  FROM `user_score_changelog` WHERE uid={$_SESSION['usersid']} and lock_points>0 and to_days(now())-to_days(thetime)=1";
    	//$sql = "SELECT SUM(totalscore) as presspoints FROM `presslog` WHERE uid={$_SESSION['usersid']} AND TO_DAYS(NOW())-TO_DAYS(presstime)=1";
    	$sql = "SELECT SUM(tzpoints) AS presspoints FROM game_day_static WHERE uid={$_SESSION['usersid']} AND TO_DAYS(NOW())-TO_DAYS(TIME)=1";
    	$ret = db::get_one($sql,'assoc');
    	$presspoints = (int)$ret['presspoints'];
    	
    	$sql = "select multiple_loss from users where id={$_SESSION['usersid']}";
    	$userrow = db::get_one($sql,'assoc');
    	$multiple_loss = (int)$userrow['multiple_loss'];
    	
    	$multiple = ($multiple_loss > 0) ? $multiple_loss : MULTIPLE_LOSS;
    	
    	//TODO
    	//&& $presspoints >= abs($point * $multiple)
    	
        $sql = 'select sum(points) as point from game_day_static where uid=' . $_SESSION['usersid'] . ' and to_days(now())-to_days(time)=1';
        $res = db::get_one($sql,'assoc');
        $point = (int)$res['point'];
		if ($point < 0) {// && $presspoints >= abs($point * $multiple)
        	if($presspoints >= abs($point * $multiple))
            	$point *= REBATE_MONEY;
        	else 
        		$point *= REBATE_MONEY/2;
        }else{
            $point = 0;
        }
        $point=floor(abs($point));
        return ['status'=>0,'message'=>'','point'=>(int)$point];
    }


    private function get_first(){
        //$Log=new Log();
        //$id=$Log->has_get_integral(L_FIRSTR_REBATE,$_SESSION['usersid']);
        
        
        $opr_type = L_FIRSTR_REBATE;
        $sql = "select id from score_log where opr_type='{$opr_type}' and uid='{$_SESSION['usersid']}' and to_days(now())=to_days(log_time)";
        $res = db::get_one($sql,'assoc');
        if($res['id'])
        	$id = $res['id'];
        else
        	$id = 0;
        
        $id = (int)$id;
        

        if($id){
            $point=['status'=>1,'message'=>'已领取','point'=>0];
        }else{
            //$point = $this->get_first_rebate();
            $point = $this->get_first_rebate_new();
        }
        if($_GET['g'] && IS_AJAX)return $this->ajax_first($point['point'],$id);
        $this->assign('point',(object)$point);
        $sql='select * from score_log where opr_type='.L_FIRSTR_REBATE.' and uid='.$_SESSION['usersid'].' order by id desc limit 20';
        $list=db::get_all($sql);
        $this->assign('list',$list);
        $this->display('activity_first_rebate');
    }
    private function ajax_first($point,$id){
    	if(!FIRST_REBATE)return $this->result(1,'首充返利已经关闭了');
    	
        $sql = "select dj_rebate from users where id = {$_SESSION['usersid']}";
    	$res=db::get_one($sql);
    	if($res->dj_rebate == 1){
    		return $this->result(1,'领取返利已经被冻结');
    	}
            
            $point=intval($point);
            if (!$id && $point > 0) {
                $uid=$_SESSION['usersid'];
                try {
                    db::_query('begin');
                    db::_query('SET AUTOCOMMIT=0');

                    $Bank = new Centerbank();
                    
                    //更新用户积分
                    $ret = $Bank->update_users_point($uid, $point);
                    if(!$ret){
                    	return $this->result(1, '失败了,请联系管理员');
                    }
                    
                    //删除中央银行活动积分
                    $ret = $Bank->update_bank(CEN_ACTIVITY_SCORE, $point, '-');
                    if(!$ret){
                    	return $this->result(1, '失败了,请联系管理员');
                    }
                    
                    //每日统计
                    $ret = $Bank->update_day_static($uid, STATIC_REBATE, $point);
                    if(!$ret){
                    	return $this->result(1, '失败了,请联系管理员');
                    }
                    
                    //首充每日返利
                    $ret = $Bank->update_tj($point, TJ_REBATE);
                    if(!$ret){
                    	return $this->result(1, '失败了,请联系管理员');
                    }
                    
                    $ip=get_ip();
                    $opr_type = L_FIRSTR_REBATE;
                    $sql="INSERT INTO score_log(uid,opr_type,amount,log_time,ip,points,bankpoints,remark) 
                    		select id,{$opr_type},{$point},now(),'{$ip}',points,back,'' from users where id={$_SESSION['usersid']}";
                    if(!db::_query($sql)){
                    	db::_query('rollback');
                    	return $this->result(1, '失败了,请联系管理员');
                    }
                    
                    db::_query('commit');
                    db::_query('SET AUTOCOMMIT=1');
                    
                    $this->RefreshPoints();
                    
                    return $this->result(0, '领取成功');
                }catch (Exception $e){
                    if(APP_DEBUG){
                        return $this->result(1,$e->getMessage());
                    }
                    return $this->result(1,'未领取成功,请联系管理员!');
                }
            } else {
                return $this->result(1, "您没有可以领取的返利");
            }
            return $this->result(1, "出错了");
    }
    
    
    private function get_first_rebate_new($uid=0)
    {
    	$rebate_points = 0;
    	if($uid==0)$uid=(int)$_SESSION['usersid'];
    	$sql='SELECT point FROM pay_online WHERE state=1 AND TO_DAYS( NOW( ) ) - TO_DAYS(pay_time)=1 AND uid='.$uid.' ORDER BY id ASC LIMIT 1';
    	$point=db::get_one($sql);//首充的分数
    
    	if(!$point->point)$result=['status'=>1,'message'=>'昨天没有充值'];
    
    	$sql = 'select SUM(tzpoints) AS tzpoints,SUM(points) AS points from game_day_static where uid=' . $uid. ' and to_days(now())-to_days(time)=1';
    	$Surplus = db::get_one($sql);
    	//tzpoints:投注分数 ， points:收入豆数
    	if($Surplus->tzpoints >= $point->point*FIRST_REBATE_MONEY_B){
    		$rebate_points = $rebate_points + $point->point * FIRST_REBATE_MONEY;
    	}
    	 
    	$sql = "SELECT point,to_days(pay_time) as pay_time,TO_DAYS( NOW( ) ) as now_time FROM pay_online WHERE state=1 AND uid={$uid} order by id asc limit 1";
    	$PayRow = db::get_one($sql);
    	if($PayRow->now_time - $PayRow->pay_time == 1){
    		if($PayRow->point >= 100*1000){
    			if($Surplus->tzpoints >= 100*1000*FIRST_REBATE_MONEY_B){
    				$rebate_points = $rebate_points + 100*1000;
    			}
    		}else{
    			if($Surplus->tzpoints >= $PayRow->point*FIRST_REBATE_MONEY_B && $PayRow->point > 0){
    				$rebate_points = $rebate_points + $PayRow->point;
    			}
    		}
    	}
    
    	if($rebate_points > 0){
    		$result=['status'=>0,'message'=>'','point'=>$rebate_points];//首充返利
    	}else{
    		$result=['status'=>1,'message'=>'没有达到标准'];
    	}
    	return $result;
    }

    private function get_first_rebate($uid=0)
    {
        if($uid==0)$uid=(int)$_SESSION['usersid'];
        $sql='SELECT point FROM pay_online WHERE state=1 AND TO_DAYS( NOW( ) ) - TO_DAYS(pay_time)=1 AND uid=\''.$uid.'\' ORDER BY id ASC LIMIT 1;';
        $point=db::get_one($sql);//首充的分数
		
        if(!$point->point)$result=['status'=>1,'message'=>'昨天没有充值'];
        
        $sql = 'select SUM(CASE WHEN points>0  THEN points ELSE 0 END) AS points,SUM(CASE WHEN points<0  THEN points ELSE 0 END) AS point,sum(tzpoints) as tzpoints from game_day_static where uid=\'' . $uid. '\' and to_days(now())-to_days(time)=1';
        $Surplus = db::get_one($sql);
        //tzpoints:投注分数 ， points:收入豆数
        
        
        if($Surplus->point<0)$Surplus->point*=-1;//负数转正
        if($Surplus->points>$Surplus->point)$Surplus->point=$Surplus->points;
		$Surplus->point*=FIRST_REBATE_MONEY_B;
		
        
        if($Surplus->point>=$point->point){
            $result=['status'=>0,'message'=>'','point'=>intval($point->point*FIRST_REBATE_MONEY)];//5%的首充返利
        }else{
            $result=['status'=>1,'message'=>'没有达到标准'];
        }
        return $result;
    }
    
    
    public function rankrebate(){
    	$opr_type = L_RANKREBATE;
    	$sql = "select count(*) as cnt from score_log where opr_type='{$opr_type}' and uid='{$_SESSION['usersid']}' and to_days(now())=to_days(log_time)";
    	$res = db::get_one($sql,'assoc');
    	if($res['cnt'])
    		$id = $res['cnt'];
    	else
    		$id = 0;
    	
    	$id = (int)$id;
    	
    	if($id){
    		$pointArr=['status'=>1,'message'=>'已领取','point'=>0];
    	}else{
    		$pointArr = $this->get_rankrebate();
    	}
    	if($_GET['g'] && IS_AJAX)return $this->ajax_rankrebate($id,$pointArr['point']);
    	$this->assign('point',(object)$pointArr);
    	$sql='select * from score_log where opr_type='.L_RANKREBATE.' and uid='.$_SESSION['usersid'].' order by id desc limit 20';
    	$list=db::get_all($sql);
    	$this->assign('list',$list);
    	$this->assign('id',$id);
    	$this->display('rank_rebate');
    }
    
    
    private function get_rankrebate(){
    	$sql = 'select prize_points as point from rank_list where uid=' . $_SESSION['usersid'] . ' and rank_type=1 and state=0 and to_days(now())-to_days(theday)=1';
    	$res = db::get_one($sql,'assoc');
    	$point = (int)$res['point'];
    	$point=abs($point);
    	return ['status'=>0,'message'=>'','point'=>(int)$point];
    }
    
    private function ajax_rankrebate($id,$point){
    	$sql = "select dj_rankrebate from users where id = {$_SESSION['usersid']}";
    	$res=db::get_one($sql);
    	if($res->dj_rankrebate == 1){
    		return $this->result(1,'领取排行奖已经被冻结');
    	}
    	
    	if($id){
    		return $this->result(1,'已经领取过了');
    	}
    	
    	
    	$point=intval($point);
    	if (!$id && $point > 0) {
    		$uid=$_SESSION['usersid'];
    	
    		db::_query('SET AUTOCOMMIT=0');
    		db::_query('begin');
    	
    		$opr_type = L_RANKREBATE;
    		$sql = "select count(*) as cnt from score_log where opr_type={$opr_type} and uid={$_SESSION['usersid']} and to_days(now())=to_days(log_time)";
    		$res = db::get_one($sql,'assoc');
    		if((int)$res['cnt'] > 0){
    			db::_query('rollback');
    			return $this->result(1,'已经领取过了');
    		}
    	
    		$Bank = new Centerbank();
    	
    		//更新用户积分
    		$ret = $Bank->update_users_point($uid, $point);
    		if(!$ret){
    			return $this->result(1, '领取失败[1],请联系管理员');
    		}
    	
    		//删除中央银行活动积分
    		$ret = $Bank->update_bank(CEN_ACTIVITY_SCORE, $point, '-');
    		if(!$ret){
    			return $this->result(1, '领取失败[2],请联系管理员');
    		}
    	
    		//每日统计
    		$ret = $Bank->update_day_static($uid, STATIC_ACTIVITY, $point);
    		if(!$ret){
    			return $this->result(1, '领取失败[3],请联系管理员');
    		}
    	
    		//每日排行榜奖励
    		$ret = $Bank->update_tj($point, TJ_RANKPOINTS);
    		if(!$ret){
    			return $this->result(1, '领取失败[4],请联系管理员');
    		}
    	
    		$ip=get_ip();
    		$opr_type = L_RANKREBATE;
    		$sql="INSERT INTO score_log(uid,opr_type,amount,log_time,ip,points,bankpoints,remark) 
    				select id,{$opr_type},{$point},now(),'{$ip}',points,back,'' from users where id={$_SESSION['usersid']}";
    		if(!db::_query($sql)){
    			db::_query('rollback');
    			return $this->result(1, '领取失败[5],请联系管理员');
    		}
    		
    		$sql = 'select rank_num,rank_points,prize_points from rank_list where uid=' . $_SESSION['usersid'] . ' and rank_type=1 and state=0 and to_days(now())-to_days(theday)=1 limit 1';
    		$res = db::get_one($sql,'assoc');
    		if(empty($res)){
    			db::_query('rollback');
    			return $this->result(1, '您没有可以领取的奖励');
    		}
    		
    		$sql="INSERT INTO rank_prizelog(uid,prize_points,prize_time,prize_ip,rank_num,rank_points)
    							values ({$_SESSION['usersid']},{$point},now(),'{$ip}','{$res['rank_num']}','{$res['rank_points']}')";
    		if(!db::_query($sql)){
    			db::_query('rollback');
    			return $this->result(1, '领取失败[7],请联系管理员');
    		}
    		
    		
    		$sql = "update rank_list set state=1 where uid='{$_SESSION['usersid']}' and rank_type=1 and state=0 and to_days(now())-to_days(theday)=1";
    		if(!db::_query($sql)){
    			db::_query('rollback');
    			return $this->result(1, '领取失败[6],请联系管理员');
    		}
    		
    		db::_query('commit');
    		db::_query('SET AUTOCOMMIT=1');
    	
    		$this->RefreshPoints();
    	
    		return $this->result(0, '领取成功');
    	} else {
    		return $this->result(1, "您没有可以领取的奖励");
    	} 
    }
    
    
	/*
     * 轮盘抽奖的奖品
     */
    public function getprize(){
        $Roulette=new Roulette();
        $surplus=$Roulette->get_surplus_roulette();
        //获取 奖品列表
        if(Req::get('list')){
            $list=[];
            $Roulette_list=$Roulette->get_roulette_list();
            foreach ($Roulette_list as $v){
                $list[]=$v['point'];
            }
            $receive_list =$Roulette->get_today_list();
            $big_list =$Roulette->get_today_list(8000);
            die(json_encode(['list'=>$list,'receive'=>$receive_list,'congratulate'=>$big_list,'surplus'=>$surplus,'person'=>$Roulette->get_roulette_total()]));
        }
        if($surplus<1){
            $this->result(1,'您今天已经没有机会参与了');
        }
        //抽奖
        $this->result(0, $Roulette->prize());
    }
}
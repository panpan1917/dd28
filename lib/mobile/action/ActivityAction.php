<?php
/**
 * Date: 2016/8/3
 * Time: 11:48
 */
class ActivityAction extends BaseAction
{
	public function _initialize(){
		parent::_initialize();
		$this->RefreshPoints();
	}
	
	private function GetGameNames(){
		$ret = [];
		$sql="select game_type,game_name from game_config";
		$result=db::get_all($sql,'assoc');
		foreach($result as $row){
			$game_type = $row['game_type'];
			$ret[$game_type] = $row['game_name'];
		}
		return $ret;
	}
	
	//投注记录
	public function getPressLog(){
		$start = (int)$_GET['start'];
		$d = (int)$_GET['d'];
		$d = $d > 0 ? 1 : 0;
		$class_0 = $d == 0 ? " style='color:red;' " : " style='color:#5c6570;' ";
		$class_1 = $d > 0 ? " style='color:red;' " : " style='color:#5c6570;' ";
		 
		$totalpress = 0;
		$sql = "SELECT gametype,no,totalscore,presstime FROM `presslog` WHERE uid={$_SESSION['usersid']} and to_days(now())-to_days(presstime)={$d} order by id desc";
		$presslist = db::get_all($sql);
		$gameNames = $this->GetGameNames();
		if(count($presslist)>0){
			foreach($presslist as &$row){
				$row->gamename = $gameNames[$row->gametype];
				$totalpress = $totalpress + $row->totalscore;
			}
		}
		 
		$this->assign('d',$d);
		$this->assign('class_0',$class_0);
		$this->assign('class_1',$class_1);
		$this->assign('totalpress',$totalpress);
		$this->assign('presslist',$presslist);
		$this->display('presslog');
	}

	/* 亏损返利 */
    public function lossRebate()
    {
        $sql="select *,case opr_type when 20 then '日返利' else '周返利' end as type from score_log where opr_type in(20,30) and uid={$_SESSION['usersid']} order by id desc limit 30";
        $list=db::get_all($sql);
        $this->assign('list',$list);
        $this->display('lossRebate');

    }

    /* 首充返利 */
    public function rechargeRebate(){
        $sql='select * from score_log where opr_type='.L_FIRSTR_REBATE.' and uid='.$_SESSION['usersid'].' order by id desc limit 30';
        $list=db::get_all($sql);
        $this->assign('list',$list);
        $this->display('rechargeRebate');
    }
    
    /* 排行榜奖励 */
    public function rankRebate(){
    	$sql='select * from score_log where opr_type='.L_RANKREBATE.' and uid='.$_SESSION['usersid'].' order by id desc limit 30';
    	$list=db::get_all($sql);
    	$this->assign('list',$list);
    	$this->display('rankRebate');
    }
    
    
    /* 推荐好友 */
    public function firends(){
        $this->display('firends');
    }
    
    
    /* 我的好友(下线) */
    public function myFirends(){
    	$ajax=Req::get('ajax')?1:0;
    	if($ajax)return $this->getValidScoreList($ajax);
    	$this->getValidScoreList($ajax);
    	$this->display('myfriends');
    }
    
    
    /* 推荐奖励 */
    public function recomRebate(){
    	$ajax=Req::get('ajax')?1:0;
    	if($ajax)return $this->getValidScoreList($ajax);
    	
    	$sql='select count(*) as total from users where tjid='.$_SESSION['usersid'];
    	$count=db::get_one($sql);
    	$this->assign('count',$count->total);
    	$sql='select * from score_log where opr_type=' . L_EXTENSION_REBATE . ' and uid='.$_SESSION['usersid'].' order by id desc limit 30';
    	$list=db::get_all($sql);
    	$this->assign('list',$list);
    
    	//$this->getValidScoreList($ajax);
    	$this->display('recomRebate');
    }
    
    
    private function getValidScoreList($ajax){
    	$page=Req::get('page','intval')?:1;
    
    	$sql='select count(id) as total from users where tjid='.$_SESSION['usersid'];
    	$total=db::get_one($sql);
    	$pagesize=10;
    	
    	
    	$sql='select u.id,u.nickname,u.logintime,ifnull(sum(g.tzpoints),0) as points 
    			from users u left join game_day_static g on g.uid=u.id and to_days(now())-to_days(g.time)=1 
    			where u.tjid='.$_SESSION['usersid'].' group by u.id limit '.(($page-1)*$pagesize).',10';
    	
    	
    	$user_list=db::get_all($sql);
    	$ajaxpage=new Apage(array('total'=>$total->total,'perpage'=>$pagesize,'ajax'=>"page_list",'nowindex' => $page));
    	$show= $ajaxpage->show();
    	if($ajax){
    		echo json_encode(['list'=>$user_list,'page'=>$show],JSON_UNESCAPED_UNICODE);
    		return true;
    	}
    	$this->assign('user_list',$user_list);
    	$this->assign('page',$show);
    }
    
    
    
    public function redPack(){
    	$sql='select * from score_log where opr_type=40 and uid='.$_SESSION['usersid'].' order by id desc limit 30';
    	$list=db::get_all($sql);
    	$this->assign('list',$list);
    	$this->display('redPack');
    }
    
    /* 领取红包 */
    public function getRedPack(){
    	$num=trim(Req::get('num'));
    
    	if(!empty($num)){
    		$status=1;
    		
    		/* $sql = "select id,username from users where id={$_SESSION['usersid']} and time < '2018-07-20 12:00:00'";
    		$userrow = db::get_one($sql,'assoc');
    		$userid = (int)$userrow['id'];
    		if(empty($userid) || $userid==0){
    			$mess = '您来晚了,红包已经抢完了!';
    			return $this->result($status,$mess);
    		} */
    		
    		
    		$sql = "select id from pack where num='{$num}'";
    		$packrow = db::get_one($sql);
    		$packid = (int)$packrow->id;
    		sleep(1);
    
    		db::_query("set autocommit=0");
    		db::_query("begin");
    		$sql = "SELECT p.*,pl.id as pid FROM pack p LEFT JOIN pack_list pl ON pl.`typeid`=p.id and pl.uid={$_SESSION['usersid']} WHERE p.id={$packid}";
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
    					$sql = "update users set back=back+{$ledou} where id={$_SESSION['usersid']}";
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
    
    						$sql="insert into score_log(uid,opr_type,amount,log_time,ip,points,bankpoints,remark) select id,40,{$ledou},now(),'{$ip}',points,back,'红包id=".$pack->id."' from users where id={$_SESSION['usersid']}";
    						if(!db::_query($sql, false)) {
    							db::_query("rollback");
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
    }
    
    
}
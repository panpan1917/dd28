<?php

class PackAction extends BaseAction
{
	function _initialize(){
		parent::_initialize();
		$this->RefreshPoints();
	}
	
    function index(){
        $num=trim(Req::get('num'));
        
        /* if($num == "0SEirSrAuu"){
        	//0SEirSrAuu
        	$sql = "select time from users where id={$_SESSION['usersid']}";
        	$res=db::get_one($sql,'assoc');
        	if($res['time'] > "2017-05-10"){
        		$mess = '您来晚了,红包已经抢完了!';
        		return $this->result(1,$mess);
        	}
        } */
        
        
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
            } elseif ($pack->pid) {
            	db::_query('rollback');
                $mess = '已经领取过了';
                return $this->result($status,$mess);
            } else {
                $ledou = rand($pack->min, $pack->max);
                $sql = 'update pack set endcount=endcount+1 where id=' . $pack->id . ' and count>endcount';
                if (!db::_query($sql, false)) {
                    db::_query('rollback');
                    $mess = '您来晚了,红包已经抢完了!';
                    return $this->result($status,$mess);
                } else {
                    $ip = get_ip();
                    $sql = "insert into pack_list (typeid,uid,ledou,time,ip)values(" . $pack->id . ",{$_SESSION['usersid']},{$ledou},UNIX_TIMESTAMP(),INET_ATON('{$ip}'))";
                    if (!db::_query($sql)) {
                        db::_query('rollback');
                        $mess = '抢红包失败[1]!';
                        return $this->result($status,$mess);
                    } else {
                    	$sql = "update users set points=points+{$ledou} where id={$_SESSION['usersid']}";
                        if (!db::_query($sql)) {
                            db::_query('rollback');
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
        $this->display('pack_get');
    }
    
}
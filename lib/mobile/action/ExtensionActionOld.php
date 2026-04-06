<?php

class ExtensionAction extends BaseAction
{
	function _initialize(){
		parent::_initialize();
		$this->RefreshPoints();
	}
	
    function index(){
        $ajax=Req::get('ajax')?1:0;
        if($ajax)return $this->ajax_extension_list($ajax);
        $this->ajax_extension_list($ajax);
        $this->display('extension_index');
    }
    function firends(){
        $this->display('extension_firends');
    }
    function money(){
        $g=Req::get('g','intval')?:0;
        $sql = 'select sum(s.tzpoints) as point , GROUP_CONCAT(u.id) AS uids 
        		from game_day_static s left join users u on u.id=s.uid 
        		where u.tjid=' . $_SESSION['usersid'] . ' 
        		and to_days(now())-to_days(s.time)=1';//浮动赔率游戏才给提成 and s.kindid <= 24
        $res = db::get_one($sql,'assoc');
        $point = (int)$res['point'];
        if ($point > 0) {
            $point *= COMMISSION_RATE;
            $uids = $res['uids'];
        }else{
            $point= 0;
            $uids = "";
        }
        $point=intval($point);
        
        $sql = "select dj_extension from users where id = {$_SESSION['usersid']}";
        $res=db::get_one($sql);
        if($res->dj_extension == 1){
        	return $this->result(1,'领取推荐奖已经被冻结');
        }
        
        db::_query('SET AUTOCOMMIT=0');
        db::_query('begin');
        $sql="select count(*) as cnt from score_log where opr_type=" . L_EXTENSION_REBATE . " and uid={$_SESSION['usersid']} and to_days(now())=to_days(log_time)";
        $res=db::get_one($sql,'assoc');
        if((int)$res['cnt'] > 0){
        	db::_query('rollback');
        	db::_query('SET AUTOCOMMIT=1');
            $this->assign('id',$res['cnt']);
        }elseif($g && $point>0){
            $sql="update users set `points`=`points`+{$point} where id={$_SESSION['usersid']}";
            if(!db::_query($sql)){
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
            
            
            $sql = "INSERT webtj (time,tgpoints) values (now(),$point) ON DUPLICATE KEY UPDATE tgpoints=tgpoints+{$point}";
        	if(!db::_query($sql , false)){
            	db::_query('rollback');
            	return $this->result(1, '领取失败[4],请联系管理员');
            }
            
            $_SESSION['points'] = $_SESSION['points'] + $point;
            $result = $this->score_log($_SESSION['usersid'],L_EXTENSION_REBATE,$point,$_SESSION['points'],$_SESSION['bankpoints'],'推荐奖励:'.$uids);
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
        $this->display('extension_get');
    }
    function ajax_extension_list($ajax){
        $page=Req::get('page','intval')?:1;

        $sql='select count(id) as total from users where tjid='.$_SESSION['usersid'];
        $total=db::get_one($sql);
        $pagesize=10;
        $sql='select u.id,u.nickname,u.logintime,ifnull(sum(g.tzpoints),0) as tzpoints from users u left join game_day_static g on g.uid=u.id and (TO_DAYS(now())-to_days(g.time)=1) where u.tjid='.$_SESSION['usersid'].' group by u.id limit '.(($page-1)*$pagesize).',10';
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
}
<?php

/**

 * Date: 2015/7/12

 */
session_start();
class IndexAction extends BaseAction
{
    function __construct()
    {
        $this->no_login=['index','lp','verify','login','news','newslist','activitylist','rankings'];
        parent::__construct();
    }
    
    public function index()
    {
    	$sql = "select id,title,time from news Order by top desc,id desc limit 10";
		$news_list=db::get_all($sql);
		$this->assign('news_list',$news_list);
		$this->display();
    }
    
    public function rankings()
    {
    	$sql = "select u.nickname,r.points as rank_points from users u,rank_log r where u.id=r.uid and r.time=CURDATE() order by r.points desc limit 30";
    	$todayrankings=db::get_all($sql,'assoc');
    	foreach ($todayrankings as $key => &$value) {
    		$value['rank_points'] = number_format(ceil($value['rank_points']/1000));
    		$nicknamelen = mb_strlen($value['nickname'] , 'UTF-8');
    		if($nicknamelen > 6)
    			$value['nickname'] = mb_substr($value['nickname'] , 0 , $nicknamelen-2 , 'UTF-8') . "**";
    		else
    			$value['nickname'] = mb_substr($value['nickname'] , 0 , 3 , 'UTF-8') . "**";
    	}
    	$this->assign('todayrankings',$todayrankings);
    	
    	$sql = "select u.nickname,r.rank_points,prize_points from users u,rank_list r where u.id=r.uid and r.rank_type=1 order by r.rank_num asc limit 30";
    	$yestadayrankings=db::get_all($sql,'assoc');
    	foreach ($yestadayrankings as $key => &$value) {
    		$value['rank_points'] = number_format(ceil($value['rank_points']/1000));
    		$nicknamelen = mb_strlen($value['nickname'] , 'UTF-8');
    		if($nicknamelen > 6)
    			$value['nickname'] = mb_substr($value['nickname'] , 0 , $nicknamelen-2 , 'UTF-8') . "**";
    		else
    			$value['nickname'] = mb_substr($value['nickname'] , 0 , 3 , 'UTF-8') . "**";
    	}
    	$this->assign('yestadayrankings',$yestadayrankings);
    	
    	$sql = "select u.nickname,r.rank_points from users u,rank_list r where u.id=r.uid and r.rank_type=2 order by r.rank_num asc limit 30";
    	$weekrankings=db::get_all($sql,'assoc');
    	foreach ($weekrankings as $key => &$value) {
    		$value['rank_points'] = number_format(ceil($value['rank_points']/1000));
    		$nicknamelen = mb_strlen($value['nickname'] , 'UTF-8');
    		if($nicknamelen > 6)
    			$value['nickname'] = mb_substr($value['nickname'] , 0 , $nicknamelen-2 , 'UTF-8') . "**";
    		else
    			$value['nickname'] = mb_substr($value['nickname'] , 0 , 3 , 'UTF-8') . "**";
    	}
    	$this->assign('weekrankings',$weekrankings);
    	
    	
    	$this->display('rankings');
    }
    
    public function newslist()
    {
    	$sql = "select id,title,time from news Order by top desc,id desc limit 20";
    	$news_list=db::get_all($sql);
    	$this->assign('news_list',$news_list);
    	$this->display('newslist');
    }
    
    public function news()
    {
    	$id = (int)$_GET['id'];
    	$sql = "select * from news where id={$id}";
    	$news=db::get_one($sql,'assoc');
    	$this->assign('content',$news['content']);
    	$this->display('news');
    }
    
    public function activitylist()
    {
    	return;
    }
    
    public function lp(){
    	$referer=$_GET['referer'];
		if(!empty($referer))setcookie('referer',$referer);

    	if($this->is_weixin()){
    		$qqurl = "http://wpa.qq.com/msgrd?v=3&uin=2111111728&site=qq&menu=yes";
    	}else{
    		if(is_mobile()){
    			$qqurl = "mqqwpa://im/chat?chat_type=wpa&uin=2111111728&version=1&src_type=web&web_src=oicqzone.com";
    		}else{
    			$qqurl = "http://wpa.qq.com/msgrd?v=3&uin=2111111728&site=qq&menu=yes";
    		}
    	}
    	$this->assign('qqurl',$qqurl);
    	$this->display("lp2");
    }
    
    
    public function verify(){
        vcode::ImageCode();
    }
    function login(){
        if(Req::post('action')=='login_act'){
            $svali =strcmp(strtolower($_SESSION['verif']),Req::post('yz'));
            if ($svali || !$_SESSION['verif'])
                show_msg('请不要从外部提交数据!','?a=login',2000);

            $user = new mangerlogin();
            $r=$user->login(Req::post('username'), Req::post('password'));
            //var_dump($r);
            if ($r == 1) {
                show_msg('成功登录，正在转向管理管理主页！', 1, 1);
            }else {
                show_msg('登录失败,请重新登录',"?a=login",3000);
            }
        }
        $_SESSION['verif'] = $yz = sha1(uniqid(rand(), true));
        $this->assign('yz',$yz);
        $this->assign('title',$this->webname.'登录');
        $this->display('login');

    }
    function right(){
        $this->display('right');
    }
    function out(){
        $user=new mangerlogin();
        $user->exitSys();
        show_msg('退出',1,1);
    }
}
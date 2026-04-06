<?php
class GiftAction extends BaseAction
{
	private $uid;
	
    function __construct()
    {
        $this->no_login=['reg','login'];
        parent::__construct();
        $this->uid=intval($_SESSION['usersid']);
		if(empty($this->uid)){
        	$this->jumptologin();
        }
        
        $this->RefreshPoints();
    }
    
    private function jumptologin(){
    	echo '<meta charset="utf-8"></meta>
        		  <script language="javascript">
					alert("兑换中心错误或是你登录超时，请联系客服!");
					window.location = "/mobile.php?c=users&a=login";
				 </script>';
    	exit();
    }

    
    public function giftlist(){
    	$this->display('giftlist');
    }
    
    
    public function product(){
    	$sql = "SELECT reward_discount FROM usergroups
		    	WHERE (SELECT experience FROM  users WHERE id={$this->uid})
		    	BETWEEN creditslower AND creditshigher LIMIT 1";
    	$users = db::get_one($sql,'assoc');
        if(empty($users)){
        	$this->jumptologin();
        }
        $reward_discount=$users['reward_discount'];
    	
    	$card_id=2;
    	if(isset($_GET["card_id"])){
    		$card_id=(int)$_GET["card_id"];
    	}
    	if($card_id<1){
    		$card_id=2;
    	}
    	if($card_id>9){
    		$card_id=9;
    	}
    	
    	$cart_list = $GLOBALS['cart_list'];
    	
    	//未达到两倍流水要收取2%手续费
    	//$sql='select ifnull(sum(tzpoints),0) as tzpoints from game_day_static where to_days(now())=to_days(time) and uid='.$this->uid;
    	$sql='select ifnull(sum(tzpoints),0) as tzpoints from game_day_static where uid='.$this->uid;
    	$point=db::get_one($sql,'assoc');
    	if($reward_discount <= 1.00 && $point['tzpoints'] < $cart_list["cart_".$card_id]["price"][3] * 2){
    		$reward_discount = 1.02;
    	}
    	
    	$my_price=	 ($cart_list["cart_".$card_id]["price"][3]*$reward_discount);
    	
    	
    	$this->assign('card_id',$card_id);
    	$this->assign('cart_list',$cart_list);
    	$this->assign('my_price',$my_price);
    	$this->display('product');
    }
    
    public function proexchang(){
    	$sql = "SELECT reward_discount FROM usergroups
		    	WHERE (SELECT experience FROM  users WHERE id={$this->uid})
		    	BETWEEN creditslower AND creditshigher LIMIT 1";
    	$users = db::get_one($sql,'assoc');
		if(empty($users)){
        	$this->jumptologin();
        }
        $reward_discount=$users['reward_discount'];
    	
    	
    	$sql = "select is_check_mobile,is_check_email,email,mobile from users where id = '{$this->uid}' limit 1";
    	$users = db::get_one($sql,'assoc');
		if(empty($users)){
        	$this->jumptologin();
        }
    	
        $card_id=2;
        if(isset($_GET["card_id"])){
        	$card_id=(int)$_GET["card_id"];
        }
        if($card_id<1){
        	$card_id=2;
        }
        if($card_id>9){
        	$card_id=9;
        }
         
        $cart_list = $GLOBALS['cart_list'];
        
        //未达到两倍流水要收取2%手续费
        //$sql='select ifnull(sum(tzpoints),0) as tzpoints from game_day_static where to_days(now())=to_days(time) and uid='.$this->uid;
        $sql='select ifnull(sum(tzpoints),0) as tzpoints from game_day_static where uid='.$this->uid;
        $point=db::get_one($sql,'assoc');
        if($reward_discount <= 1.00 && $point['tzpoints'] < $cart_list["cart_".$card_id]["price"][3] * 2){
        	$reward_discount = 1.02;
        }
        
        $my_price=	 ($cart_list["cart_".$card_id]["price"][3]*$reward_discount);
    	
    	$this->assign('card_id',$card_id);
    	$this->assign('cart_list',$cart_list);
    	$this->assign('my_price',$my_price);
    	$this->display('proexchang');
    }
    
    public function exchanglist(){
    	$day = (int)$_GET['day'];
    	if(empty($day)) $day = 7;
    	
    	$sql = "select  a.agent_name,card_no,card_points,e.add_time,used_time,used_ip,e.state,card_name,card_pwd
    	from exchange_cards  e
    	LEFT JOIN   agent a  on (e.agentid=a.id)
    	LEFT JOIN   exchange_cardtype  t  on (t.card_type=e.card_type)
    	where e.uid = '{$this->uid}' and e.add_time > date_add(now(),interval -{$day} day) ";
    	
    	$sql=$sql." order by e.id desc ";
    	$rows =  db::get_all($sql,'assoc');
    	//var_dump($rows);exit;
    	$this->assign('rows',$rows);
    	$this->assign('day',$day);
    	$this->display('exchanglist');
    }
}
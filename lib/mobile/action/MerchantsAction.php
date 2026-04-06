<?php
class MerchantsAction extends Action
{
    function __construct()
    {
        parent::__construct();
        $this->RefreshPoints();
    }
    
    private function index(){
    	$sql = "SELECT  u.id,u.qq,a.agent_name FROM users u,agent a
        WHERE  u.id=a.uid AND a.state=1 AND a.is_recommend=1 AND u.isagent=1
        ORDER BY RAND()";
    	$rows=db::get_all($sql , 'assoc');
    	
    	$this->assign('rows',$rows);
    	$this->assign('webname',$this->config['k_webname']);
    	$this->assign('c',Req::get('c'));
    	$this->display('merchants');
    }
}
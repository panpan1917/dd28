<?php
/**

 * Date: 2015/7/13

 */



class RequestRegistry extends Registry
{
    private $values=array();
    private static $instance;
    private function __construct(){}
    static function instance(){
        if(!isset(self::$instance))self::$instance=new self();
        return self::$instance;
    }
    protected function get($key){
        if(isset($this->values[$key]))return $this->values[$key];
        return null;
    }
    protected function set($key,$val){
        $this->values[$key]=$val;
    }
    static function getRequest(){
        return self::instance()->get('request');
    }
    static function setRequest(\core\controller\Request $request){
        return self::instance()->set('request',$request);
    }
}
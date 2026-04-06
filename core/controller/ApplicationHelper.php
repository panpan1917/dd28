<?php
class ApplicationHelper
{
    private static $instance=array();
    private function __construct(){}

    static function instance($class,$method='',$par=array()){
        $identify   =   $class.$method;
        if(!self::$instance[$identify]){
            if(class_exists($class)){
                $o = new $class();
                if(!empty($method) && method_exists($o,$method)) {
                    self::$instance[$identify] = call_user_func_array(array(&$o, $method),$par);
                }else {
                    self::$instance[$identify] = $o;
                }
            }
            else
                throw new AppException("不存在类");
        }
        return self::$instance[$identify];
    }
    function int(){
        $this->conf();
    }
    function conf() {
        $conf=new Conf();
        ApplicationRegistry::setConf($conf->get_config());
    }
    private function ensure($expr,$message){
        if(!$expr)throw new \core\base\AppException($message);
    }

}
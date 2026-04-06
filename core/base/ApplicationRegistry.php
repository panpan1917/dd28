<?php
class ApplicationRegistry extends Registry
{
    private $values=array();
    private $mtimes=array();
    private $dir='data';
    private $ext='.inc';
    private static $instance;

    private function __construct(){}

    static function instance(){
        if(!isset(self::$instance))self::$instance=new self();
        return self::$instance;
    }
    //更改后辍名
    function set_ext($ext){
        $this->ext=$ext;
    }
    //设置路径
    private function set_dir($dir){
        $this->dir=$dir;
    }
    protected function get($key){
        $path=$this->dir.DIRECTORY_SEPARATOR.$key;
        if(file_exists($path)){
            clearstatcache();//清除文件状态缓存;
            $mtime=filemtime($path);
            if(!isset($this->mtimes[$key]))$this->mtimes[$key]=0;
            if($mtime>$this->mtimes[$key]){
                $data=file_get_contents($path.$this->ext);
                $this->mtimes[$key]=$mtime;
                return ($this->values[$key]=unserialize($data));
            }
        }
        if(isset($this->values[$key]))return $this->values[$key];

        return null;
    }
    protected function set($key,$val){
        $this->values[$key]=$val;
        $path=$this->dir.DIRECTORY_SEPARATOR.$key.$this->ext;
        file_put_contents($path,serialize($val));
        $this->mtimes[$key]=time();
    }
    static function getCache($filename){
        self::instance()->set_dir(DATA_PATH.'cache/');
        return self::instance()->get($filename);
    }
    static function setCache($filename,$con){
        self::instance()->set_dir(DATA_PATH.'cache/');
        return self::instance()->set($filename,$con);
    }
    static function setConf($con){
        return self::instance()->setCache('config',$con);
    }
}
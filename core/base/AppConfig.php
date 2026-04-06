<?php
class appConfig
{
    private $config_path;
    private $catalog_base;

    function __construct() {
        $this->config_path = DATA_PATH . 'cache/config.php';
        $this->catalog_base = DATA_PATH . 'cache/base_type.inc';
    }

    /* ===================
     *  读取网站配置缓存
     *
     * @access    public
     * @return    string
      =================== */
    function getConfig() {
        if (file_exists($this->config_path)) {
            $result = unserialize(read_file($this->config_path));
        } else {
            $result = $this->updateConfig();
        }
        return $result;
    }

    /* ===================
     *  更新网站配置缓存
     *
     * @access    public
     * @return    string
      =================== */
    function updateConfig() {
        $result=array();
        $res = db::_query('select * from `__config`');
        while ($row = db::_object($res)) {
            $result[$row->name] = $row->values;
        }
        writer_file($this->config_path, serialize($result));
        if (!is_writeable($this->config_path)) {
            show_msg('配置文件没有修改权限,请查看文件<br><span style="color:#f00;">' . $this->config_path . '</span><Br>是否具有,修改权限', -1, 9000);
        }
        return $result;
    }
    /* ===================
         *  更新网站配置缓存
         *
         * @access    public
         * @return    string
    =================== */
    function setConfig($key,$value){
        db::_update('config',array('values'=>$value),"name='$key'");
        $this->updateConfig();
    }
}
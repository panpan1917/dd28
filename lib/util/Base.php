<?php
if (!defined('KKINC')) exit('Request Error!');
class base {

    private $config_path;
    private $catalog_base;

    function __construct() {
        $this->config_path = DATA_PATH . '/cache/config.php';
        $this->catalog_base = DATA_PATH . '/cache/base_type.inc';
    }

    /* ===================
     *  读取网站配置缓存
     *
     * @access    public
     * @return    string
      =================== */
    function get_config() {
        if (file_exists($this->config_path)) {
            $result = unserialize(read_file($this->config_path));
        } else {
            $result = $this->update_config();
        }
        return $result;
    }

    /* ===================
     *  更新网站配置缓存
     *
     * @access    public
     * @return    string
      =================== */
    function update_config() {
        $result=array();
        $res = db::_query('select * from `__config`');
        while ($row = db::get_all($res)) {
            $result[$row->name] = $row->values;
        }
        writer_file($this->config_path, serialize($result));
        if (!is_writeable($this->config_path)) {            
            show_msg('配置文件没有修改权限,请查看文件<br><span style="color:#f00;">' . $this->config_path . '</span><Br>是否具有,修改权限', -1, 9000);
        }        
        return $result;
    }

    /* ===================
     *  更新栏目缓存
     *
     * @access    public
     * @return    void
      =================== */
    function update_arctype_cache() {
        $res = db::_query('SELECT id,fid,topid,typename,shortname,listtpl,viewtpl,listrule,viewrule,jumpurl FROM `__arctype`');
        while ($row = db::_object($res)) {
            $result[$row->id] = $row;
        }
        writer_file($this->catalog_base, json_encode($result));
        if (!is_writeable($this->catalog_base)) {            
            show_msg('配置文件没有修改权限,请查看文件<br><span style="color:#f00;">' . $this->config_path . '</span><Br>是否具有,修改权限', -1, 9000);
        }        
        return $result;
    }

    /* ===================
     *  读取静态栏目文件
     *
     * @access    public
     * @return    void
      =================== */
    function get_arctype($tid = 0) {
        if (!file_exists($this->catalog_base)) {
            $result=$this->update_arctype_cache();
        }else{
            $result=  json_decode(read_file($this->catalog_base),true);
        }
        if (!$tid) {
            return $result;
        }
        return $result[$tid];
    }

    function get_arctype_name($tid = 0) {
        if (!$tid) {
            return false;
        }
        $result = $this->get_arctype($tid);
        return $result[$tid]['typename'];
    }

}
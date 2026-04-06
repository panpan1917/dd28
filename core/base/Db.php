<?php
if (!defined('KKINC')) exit('Request Error!');
class Db {

    static private $querynum = 0;
    private static  $link=null;
    public static function getInstance() {
        if(!(self::$link instanceof db)) {
            if($GLOBALS['db_type']=='dbmysqli') {
                self::$link = new Dbmysqli();
            }else{
                self::$link = new dbmysql();
            }
       }
        return self::$link;
    }
    static function _query($sql,$showErr=true) {
        self::getInstance();
        return self::$link->_query($sql,$showErr);
    }

    static function version() {
        self::getInstance();
        return self::$link->version();
    }

    static function _object($sql) {
        self::getInstance();
        return self::$link->_object($sql);
    }

    static function _assoc($sql) {
        self::getInstance();
        return self::$link->_assoc($sql);
    }
    static function _row($sql) {
        self::getInstance();
        return self::$link->_row($sql);
    }
    static function _array($sql) {
    self::getInstance();
    return self::$link->_array($sql);
}
    static function get_all($query, $c = 'obj') {
        self::getInstance();
        return self::$link->get_all($query,$c);
    }

    static function get_one($sql, $c = 'obj') {
        switch ($c) {
            case 'obj':
                return self::_object($sql);
                break;
            case 'assoc':
                return self::_assoc($sql);
                break;
            case 'array':
                return self::_array($sql);
                break;
            case 'row':
                return self::_row($sql);
                break;
        }
    }

    static function get_total($sql) {
        $res = self::get_one($sql, 'row');
        return $res[0];
    }

    static function _update($table, $array, $where) {
        $sql = "update `__$table` set ";
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $sql.="`$key`='$value',";
            }
        }
        return self::_query(substr($sql, 0, -1) . ' where ' . $where,false);
    }

    static function _insert($table, $array,$showErr=true) {
        $sql = "insert into `__$table` (";
        $left=$right='';
        foreach ($array as $key => $value) {
            $left.="`$key`,";
            if(is_array($value)){
                if($value['type']==1)$right.=$value['sql'].',';
            }else {
                $right .= '\'' . $value . '\',';
            }
        }
        return self::_query($sql . substr($left, 0, -1) . ') values (' . substr($right, 0, -1) . ')',$showErr);
    }
    static function _log($data){
        $n=array('createdate'=>date('Y-m-d H:i:s'),'ip'=>get_ip());
        $data=array_merge($data,$n);
        db::_insert('log',$data);
    }
    static function _del($table,$where){
        return self::_query('delete from __'.$table.' where '.$where);
    }
    /**
     * 取得结果集中行的数目
     * @return int
     */
    function num_rows($query) {
        return mysql_num_rows($query);
    }

    /**
     * 取得上一步 INSERT 操作产生的 ID 
     * @return int
     */
    static function last_id() {
        self::getInstance();
        return self::$link->last_id();
    }
    static function row_count(){
        self::getInstance();
        return self::$link->row_count();
    }
    static function _exec($sql){
        self::getInstance();
        return self::$link->_exec($sql);
    }

}
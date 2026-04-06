<?php
if(!defined('KKINC')) exit('Request Error!');
class Dbmysqli extends Db
{
    static private $link;
    function __construct()
    {
        try {
            self::$link = new pdo('mysql:host=' . $GLOBALS['dbhost'] . ';dbname=' . $GLOBALS['database'] . ';', $GLOBALS['dbuser'], $GLOBALS['dbpass']);
        } catch (PDOException $e) {
            echo '未连上数据库,请检查数据库配置是否正确<br>'.$e->getMessage();
        }

        return self::$link;
    }

    static function _query($sql,$showErr=true){
        $sql = str_replace('__', TABLE, $sql);
        $sql = str_replace('\_\_', '__', $sql);		
        if (($result=self::$link->query($sql))===false) {
            if(!$showErr)return false;
            echo '<div style="font-family:Microsoft YaHei,Arial;text-align:left; border:1px solid #f00; padding:1px 4px;color:#666;font-size:12px;"><b>查询语句 : </b> ';
            echo $sql;
            echo ' <br><b> 错误原因 : </b>';
            echo self::$link->errorInfo()[2];
            echo '<br><b> 错误代码 : </b>' . self::$link->errorInfo()[1] . ' </div>';
        }
        if(!$showErr)return true;
        return $result;
    }

    static function version() {
        $rs =self::_object('select version() as v;');
        return str_replace('-log','',$rs->v);
    }

    static function _object($sql) {
        if(is_object($sql))return $sql->fetch(PDO::FETCH_OBJ);//兼容部分早期写法;
        return self::_query($sql)->fetch(PDO::FETCH_OBJ);
    }

    static function _assoc($sql) {
        if(is_object($sql))return $sql->fetch(PDO::FETCH_ASSOC);
        return self::_query($sql)->fetch(PDO::FETCH_ASSOC);
    }
    static function _array($sql) {
        if(is_object($sql))return $sql->fetch(PDO::FETCH_BOTH);
        return self::_query($sql)->fetch(PDO::FETCH_BOTH);
    }
    static function _row($sql) {
        if(is_object($sql))return $sql->fetch(PDO::FETCH_NUM);
        return self::_query($sql)->fetch(PDO::FETCH_NUM);
    }
    static function get_all($sql, $c = 'obj') {
        switch ($c) {
            case 'obj':
                return self::_query($sql)->fetchAll(PDO::FETCH_OBJ);
                break;
            case 'assoc':
                $result=self::$link->prepare($sql);     // prepare()方法准备查询语句
                $result->execute();                // execute()方法执行查询语句，并返回结果集
                return $result->fetchAll(PDO::FETCH_ASSOC);
                return self::_query($sql)->fetchAll(PDO::FETCH_ASSOC);
                break;
            case 'array':
                return self::_query($sql)->fetchAll(PDO::FETCH_BOTH);
                break;
            case 'row':
                return self::_query($sql)->fetchAll(PDO::FETCH_NUM);
                break;
        }
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
                return self::_query($sql)->fetch(PDO::FETCH_BOTH);
                break;
            case 'row':
                return self::_query($sql)->fetch(PDO::FETCH_NUM);
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

        return self::_query(substr($sql, 0, -1) . ' where ' . $where);
    }

    static function _insert($table, $array,$showErr=true) {
        $sql = "insert into `__$table` (";
        $left=$right='';
        foreach ($array as $key => $value) {
            $left.="`$key`,";
            if($value=='time()'){
                $right.=time().',';
            }else{
                $right.="'$value',";
            }
        }
        return self::_query($sql . substr($left, 0, -1) . ') values (' . substr($right, 0, -1) . ')');
    }

    /**
     * 取得结果集中行的数目
     * @return int
     */
    function num_rows($query) {
        return mysql_num_rows($query);
    }
    static function _exec($sql){
        return self::$link->exec($sql);
    }
    /**
     * 取得上一步 INSERT 操作产生的 ID
     * @return int
     */
    static function last_id() {
        return self::$link->lastInsertId();
    }
    static function row_count(){
        return self::$link->rowCount();
    }
}
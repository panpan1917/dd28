<?php
if(!defined('KKINC')) exit('Request Error!');

 class dbmysql extends db {
     static private $link;
     function __construct()
     {
             self::$link = @mysql_connect($GLOBALS['dbhost'],$GLOBALS['dbuser'],$GLOBALS['dbpass']) or die('数据库连接不上--请确认账号密码是否填写正确.<Br> '.mysql_error());
             mysql_query('SET NAMES '.$GLOBALS['db_language']);
             mysql_select_db($GLOBALS['database'],self::$link) or die("没有找到数据库->".$GLOBALS['database']);
         return self::$link;
     }

     static function _query($sql,$showErr=true){
         $sql = str_replace('__', TABLE, $sql);
         $sql = str_replace('\_\_', '__', $sql);
         if (($result=mysql_query($sql,self::$link))===false && APP_DEBUG) {
             echo '<div style="font-family:Microsoft YaHei,Arial;text-align:left; border:1px solid #f00; padding:1px 4px;color:#666;font-size:12px;"><b>查询语句 : </b> ';
             echo $sql;
             echo ' <br><b> 错误原因 : </b>';
             echo mysql_error();
             echo '<br><b> 错误代码 : </b>' . mysql_errno() . ' </div>';
         }
         return $result;
     }

     static function version() {
         $rs=  @mysql_query('select version();');
         $row= @mysql_fetch_array($rs);
         return str_replace('-log','',$row[0]);
     }
     static function _object($sql) {
         if(is_resource($sql))return mysql_fetch_object($sql);//兼容部分早期写法;
         return mysql_fetch_object(self::_query($sql));
     }

     static function _assoc($sql) {
         if(is_resource($sql))return mysql_fetch_assoc($sql);
         return mysql_fetch_assoc(self::_query($sql));
     }
     static function _array($sql) {
         if(is_resource($sql))return mysql_fetch_array($sql);
         return mysql_fetch_array(self::_query($sql));
     }
     static function _row($sql) {
         if(is_resource($sql))return mysql_fetch_row($sql);
         return mysql_fetch_row(self::_query($sql));
     }
     static function get_all($sql, $c = 'obj') {
         switch ($c) {
             case 'obj':
                 $res=self::_query($sql);
                 $result=array();
                 while($row=db::_object($res)){
                     $result[]=$row;
                 }
                 return $result;
                 break;
             case 'assoc':
                 $res=self::_query($sql);
                 $result=array();
                 while($row=self::_assoc($res)){

                     var_dump($row);
                     $result[]=$row;
                 }
                 return $result;
                 break;
             case 'array':
                 $res=self::_query($sql);
                 $result=array();
                 while($row=db::_array($res)){
                     $result[]=$row;
                 }
                 return $result;
                 break;
             case 'row':
                 $res=self::_query($sql);
                 $result=array();
                 while($row=db::_row($res)){
                     $result[]=$row;
                 }
                 return $result;
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
         return mysql_insert_id(self::$link);
     }

 }
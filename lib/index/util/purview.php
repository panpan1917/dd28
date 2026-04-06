<?php

if (!defined('KKINC'))	 exit('Request Error!') ;

class purview {
	 static function check($rights,$unique) {
		 global $info;

		 $unique=str_replace('Action','',$unique);
		 if($rights=='All')return true;//全权限
		 $pur=explode(',',$rights);
		 foreach($pur as $v){
			 if(strtolower($v)===strtolower($unique))return true;
		 }
		 exit_msg('对不起,您没有此权限!', '-1', 3000) ;
		 return false;

	 }
	 static function get_allActionColumn($where='') {
		 if($where)$where='where '.$where;
		  $sql = 'select columnid,columnname from __actioncolumn '.$where.' order by columnrank asc,columnid asc' ;
		  return db::get_all($sql);
	 }
	 static function get_allRole() {
		  return db::get_all('select roleid,rolename from `__role`');
	 }
}

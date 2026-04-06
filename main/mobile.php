<?php
header('Content-type:text/html;charset=utf-8');
define('KKROOT', dirname(__FILE__));
define('ROOT', dirname(__DIR__));

error_reporting(E_ERROR | E_WARNING | E_PARSE);
define('APP_NAME','mobile');
define('APP_PATH','mobile');
define('RUNTIME_PATH','./Cache/');
define('TPL_PATH','./template/tpl/');
define('DATA_PATH',ROOT.'/data/');
define('APP_DEBUG',false);

//定义权限
define('READ', 1<< 0);    // 把可读权限放在最右边
define('WRITE', 1<<1);    // 可读权限向左移一位
define('DEL', 1<<2);   // 可执行权限向左移两位
include(dirname(__DIR__)."/core/ini.php");
Controller::run();


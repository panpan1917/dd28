<?php
//数据库连接信息
$dbhost = '172.19.95.147';//10.27.120.130
$database = 'kdy28';
$dbuser = 'kdy28';
$dbpass = 'kdy28*87902204';
define('M_KEY','rete');   //
define('TABLE','');
$db_language='utf8';
$coding='utf-8';
$db_type='dbmysqli';
$k_host = 'http://'.$_SERVER['HTTP_HOST'].'/';
$ali_acc='阿里账户';
$ali_name='阿里姓名';
$wx_acc='微信账户';
$wx_name='微信姓名';
$web_pwd_encrypt_prefix="Tmd##123##0783#Tmd";


$web_name="滴滴";

$qq_service = "";
$qq_discuss = "";

$cart_list=array();//卡类型
$cart_list["cart_1"]=array("name"=>"滴滴体验卡50元","price"=>array(52500,51500,51000,50000));
$cart_list["cart_2"]=array("name"=>"滴滴体验卡100元","price"=>array(105000,103000,102000,100000));
$cart_list["cart_4"]=array("name"=>"滴滴体验卡500元","price"=>array(525000,515000,510000,500000));
$cart_list["cart_6"]=array("name"=>"滴滴体验卡1000元","price"=>array(1050000,1030000,1020000,1000000));
$cart_list["cart_7"]=array("name"=>"滴滴体验卡5000元","price"=>array(5250000,5150000,5100000,5000000));
$cart_list["cart_8"]=array("name"=>"滴滴体验卡10000元","price"=>array(10500000,10300000,10200000,10000000));
$cart_list["cart_9"]=array("name"=>"滴滴体验卡50000元","price"=>array(52500000,51500000,51000000,50000000));


?>
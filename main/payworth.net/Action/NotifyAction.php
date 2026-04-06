<?php
require_once '../Config/init.php';
$ParmList = array("body","discount","gmt_create","gmt_logistics_modify","gmt_payment","is_success","is_total_fee_adjust","notify_id","notify_time","notify_type","order_no","payment_type","price","quantity","seller_actions","seller_email","seller_id","title","total_fee","trade_no","trade_status","ext_param2");
$DataContentParms =ARRAY();
foreach ($ParmList as $keyWord){
    $DataContentParms[$keyWord]=  isset($_POST[$keyWord])? trim($_POST[$keyWord]):"";
}
$SignStr =trim($_POST["sign"]);
$Teststr = Util::GetMd5str($DataContentParms, $GLOBALS["key"]);
Log::LogWirte("本地计算结果（sign）"+$Teststr);
if($Teststr == $SignStr){
    Log::LogWirte("验签通过！");
    //TODO 商户逻辑
}
echo "success";//输出响应值







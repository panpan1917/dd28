<?php
/**
 * Date: 2016/8/3
 * Time: 15:01
 */
if (!defined('KKINC')) exit('Request Error!');
class Log{
    //获取当前用户积分
    function get_curr_user_today_integral($type,$uid=0){

    }
    function has_get_integral($type,$uid=0){
        $uid=$uid?$uid:(int)$_SESSION['usersid'];
        $sql = "select id from score_log where opr_type='{$type}' and uid='{$uid}' and to_days(now())=to_days(log_time)";
        $res = db::get_one($sql);
        if($res->id)return $res->id;
        return false;
    }
    
    /* function has_first_rebate($type,$uid=0){
    	$uid=$uid?$uid:(int)$_SESSION['usersid'];
    	$sql = 'select id from score_log where opr_type='.$type.' and uid=' . $uid; //判断是否已经领取首充返利
    	$res = db::get_one($sql);
    	if($res->id)return $res->id;
    	return false;
    } */
    
    
    function i_score_log($uid,$opr,$amount,$points,$bank,$remark=""){
        /*
    uidbigint(20) NOT NULL用户id
    opr_typeint(11) NOT NULL类型，0：存，1：取，2：充值体验卡，3：转账入，4：转账出,5:在线充值,6:领取救济,7:兑奖点卡,8:推荐收益,55:系统会员充值,12:退回提现,10:提现通过,11:提现申请
    amountbigint(20) NOT NULL数量
    log_timedatetime NOT NULL时间
    ipvarchar(15) NOT NULLip
    pointsbigint(20) NOT NULL操作后豆
    bankpointsbigint(20) NOT NULL操作后银行豆
    remarkvarchar(254) NOT NULL备注
         */
        $uid=$uid?$uid:(int)$_SESSION['usersid'];
        $ip=get_ip();
        $sql="INSERT INTO score_log(uid,opr_type,amount,log_time,ip,points,bankpoints,remark) values ('{$uid}','{$opr}','{$amount}',now(),'{$ip}','{$points}','{$bank}','{$remark}')";
        $ret = db::_query($sql);
        return $ret;
    }
    function result($status=0,$msg=''){
        echo json_encode(['status'=>$status,'message'=>$msg],JSON_UNESCAPED_UNICODE);
    }
}
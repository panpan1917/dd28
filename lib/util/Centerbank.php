<?php

/**
 * Date: 2016/8/3
 * Time: 15:10
 */
class Centerbank
{
    function update_bank($type,$point,$oper='+'){
        $sql = "UPDATE centerbank SET `score` = `score`".$oper.$point." WHERE bankIdx ='{$type}'";
        if (!db::_query($sql,false)) {
            db::_query('rollback',false);
            throw new Exception('更新银行');
            return false;
        }
        return true;
    }
    
    //每日统计
    function update_day_static($uid,$type,$point){
        $sql = "INSERT game_static (uid,typeid,points) values ({$uid},{$type}, {$point}) ON DUPLICATE KEY UPDATE points=points+{$point}";
        if (!db::_query($sql,false)) {
            db::_query('rollback');
            throw new Exception('每日统计');
            return false;
        }
        return true;
    }
    //所有统计
    function update_tj($point,$type){
        $sql = "INSERT webtj (time,{$type}) values (now(),{$point}) ON DUPLICATE KEY UPDATE {$type}={$type}+{$point}";
        if (!db::_query($sql,false)) {
            db::_query('rollback');
            throw new Exception('更新统计');
            return false;
        }
        return true;
    }
    //更新用户积分
    function update_users_point($uid,$point){
        $sql = "update users set `points`=`points`+{$point} where id='{$uid}'";
        if (!db::_query($sql,false)) {
            db::_query('rollback');
            throw new Exception('更新用户积分');
            return false;
        }
        return true;
    }
    function result($status=0,$msg=''){
        echo json_encode(['status'=>$status,'message'=>$msg],JSON_UNESCAPED_UNICODE);
    }
}
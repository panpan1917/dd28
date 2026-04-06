<?php

/**
 * Date: 2016/8/23
 * Time: 17:37
 */
class Roulette
{

    /*
     * gailv
     */
    private function get_rand($proArr) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }

    public function prize()
    {
        $prize_arr=$this->get_roulette_list();
        foreach ($prize_arr as $key => $val) {
            $arr[$val['id']] = $val['probability'];
        }
        $result= $this->get_rand($arr); //根据概率获取奖项id
        $point=0;
        foreach ($prize_arr as $v){
            if($v['id']==$result)$point=$v['point'];
        }
        $status=$this->insert_point($point);
        if($status->result>0)$result=-1;
        return $result;
    }

    /*
     * 取中奖概率列表
     */
    public function get_roulette_list(){
        $sql='select id,point,probability from roulette order by id';
        return db::get_all($sql,'assoc');
    }
	/*
     * 中奖人数
     */
    public function get_roulette_total(){
        $sql='select count(id) as total from score_log where to_days(now())=to_days(log_time) and opr_type='.L_ROULETTE.'';
        $total=db::get_total($sql);
        if($total>10 && $total<=50){
            $total=$total*2;
        }elseif($total>50 && $total<=500){
            $total=$total*3;
        }elseif($total>500 && $total<1000){
            $total=$total*5;
        }
        return $total;
    }
    /*
     * 剩余中奖次数
     */
    public function get_surplus_roulette(){
        $uid=intval($_SESSION['usersid']);
        $sql='select id from pay_online where state=1 and to_days(now())-to_days(pay_time)=1 and uid=\''.$uid.'\' union SELECT count(id) FROM game_day_static WHERE TO_DAYS(NOW())-TO_DAYS(TIME)=1 AND uid=\''.$uid.'\'';
        $res=db::get_all($sql);
        if(count($res)<2)return 0;
        $Log=new Log();
        if($Log->has_get_integral(L_ROULETTE)){
            return 0;
        }
        return 1;
    }
    /*
     * 今天领取记录
     */
    public function get_today_list($god=0){
        if($god>0){
            $where=' and s.amount>='.$god;
        }
        $result=db::get_all('select u.nickname as name,s.amount,TIME_FORMAT(s.log_time,\'%H:%i\') as time from score_log s,users u where u.id=s.uid and to_days(now())=to_days(log_time) and opr_type='.L_ROULETTE.$where.'  order by s.id desc limit 30');
        return $result;
    }
	
    /*
     * 插入记录
     */
    private function insert_point($point){
        $uid=$_SESSION['usersid'];
        $ip=get_ip();
        $res=db::get_one('call insert_roulette('.$uid.','.$point.',\''.$ip.'\')');
        return $res;
    }
}
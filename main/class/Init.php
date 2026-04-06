<?php
include_once 'Base.php';
class Init extends Base
{
    private $game28;
    private $game16;
    private $gamehg;
    private $gamecan;


    function truncate(){
        $this->db->query('TRUNCATE TABLE `game_result`');
        $list=$this->get_all("select game_table_prefix from game_config");
        foreach ($list as $v){
            $this->db->query("truncate table ".$v['game_table_prefix']);
        }
        return true;
    }

    function add_start_all(){
        $list=$this->get_all("select game_table_prefix as pre,game_std_odds from game_config");
        foreach ($list as $v){
            //$this->db->query("truncate table ".$v['game_table_prefix']);
            if(strpos($v['pre'],'fast')>0)continue;
            $this->insert($v['pre'],$v['game_std_odds']);
        }
        //$this->game28();
        return true;
    }
    function game1(){
        if($this->game28)return $this->game28;
        $url = "http://www.168kai.net/Open/CurrentOpenOne?code=10014&_=" . rand(100000,999999);
        $kjcontent = file_get_contents ($url);
        $arr = json_decode($kjcontent,true);
        $this->game28=$arr;
        return $arr;
    }
    function gamepk(){
        if($this->game16)return $this->game16;
        $url = "http://www.168kai.net/Open/CurrentOpenOne?code=10016&_=" . rand(100000,999999);
        $kjcontent = file_get_contents ($url);
        $arr = json_decode($kjcontent,true);
        $this->game16=$arr;
        return $arr;
    }
    function insert($table,$zjpl){
        switch ($table){
            case 'game28':
            case 'game36':
            case 'gameself28':
            case 'gamebj16':
            case 'gamebj36':
                $fun=$table;
                $arr=$this->game1();
            case 'gamepk10':
            case 'gamepk22':
            case 'gamepkgyj':
            case 'gamegj10':
            case 'gamepklh':
                $fun=$table;
                $arr=$this->gamepk();
            case 'gamecan11':
            case 'gamecan16':
            case 'gamecan28':
            case 'gamecan36':
            $fun=$table;
            $arr=$this->can();
            case 'gamehg11':
            case 'gamehg16':
            case 'gamehg28':
            case 'gamehg36':
            $fun=$table;
            default:
                echo $table."\r\n";

        }
        $sql="insert into ".$fun." (id,kgtime,zjpl,gfid) values ('$arr[c_t]','$arr[c_d]','$zjpl','$arr[c_t]')";
        echo $sql."\r\n";
        if($arr['c_t'])
        $this->db->query($sql);
    }
    function hg(){
            $kjcontent = fsockurl("http://www.kenolotto.kr/kenoWinNoList.php");//http://www.knlotto.kr/keno.aspx?method=kenoWinNoList
        //var_dump($kjcontent);
            return $kjcontent;
    }
    function can(){
        if($this->gamecan)return $this->gamecan;
        $url = "http://203.88.161.240:899/jndcj/jndcj.php";
        $kjcontent = file_get_contents( $url );
        $arr = json_decode($kjcontent,true);
        $day = date("Y-m-d",strtotime($arr[0]['drawDate']));
        $tim = date("H:i:s",strtotime($arr[0]['drawTime'] ));
        $openTime = $day." ". $tim;
        $arr=array('c_t'=>$arr[0]['drawNbr'],'c_d'=>$openTime);
        $this->gamecan=$arr;
        return $arr;
    }


}
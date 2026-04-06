<?php
class Base
{
    protected $db;
    function __construct()
    {

        $this->db=$GLOBALS['db'];
    }
    function result($data='',$code=0,$type='json'){
        $result=array('data' => $data, 'code' => $code);
        if($type=='array') {
            return $result;
        }elseif($type=='json'){
            return json_encode($result);
        }
    }
    function get_all($sql){
        $res=$this->db->query($sql);
        $list=array();
        while ($arr=$this->db->fetch_array($res)){
            $list[]=$arr;
        }
        return $list;
    }
}
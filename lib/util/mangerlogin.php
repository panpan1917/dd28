<?php
if (!defined('KKINC')) exit('Request Error!');
session_start();
class mangerlogin {
    function login($user,$pass){
        self::exitSys();
        if (empty($user) or empty($pass)) return '请输入账号或密码';
        $pass = $this->enPass($pass);
        $sql = sprintf("select m.masterid,m.name,m.nick,r.status,r.rights,r.roleid,r.rolename from __master m left join __masterrole mr on m.masterid=mr.masterid left join __role r on mr.roleid=r.roleid where r.`status`=1 and m.`name`='%s' and m.`pwd`='%s'", $user, $pass);
        $result = db::_object($sql);
        if ($result->masterid) {
            $this->inLog($result,'登录成功');
            return $this->inSession($result);
        }
        $this->inLog($result,$user.'--'.$pass.' 尝试登录失败');
        return '账号或密码不对';
    }
    function enPass($pass) {return substr(md5(M_KEY . $pass), 5, 20);}

    function inSession($arr) {
        $_SESSION['k_user'] = $arr->name;
        $_SESSION['k_key'] = substr(md5($arr->name . $arr->pass), 0, 20);
        $_SESSION['k_masterid'] = $arr->masterid;
        $_SESSION['k_nick'] = $arr->nick;
        $_SESSION['k_rolename'] = $arr->rolename;
        $_SESSION['k_roleid'] = $arr->roleid;
        $_SESSION['k_rights']=$arr->rights;
        return 1;
    }

    static function account() {
        if (!$_SESSION['k_user'] or ! $_SESSION['k_key'] or ! $_SESSION['k_masterid'] or ! $_SESSION['k_nick'])
            return 0;
        return self::keep();
    }

    function keep() {
        $info['user'] = $_SESSION['k_user'];
        $info['key'] = $_SESSION['k_key'];
        $info['masterid'] = $_SESSION['k_masterid'];
        $info['nick'] = $_SESSION['k_nick'];
        $info['roleid'] = $_SESSION['k_roleid'];
        $info['rolename'] = $_SESSION['k_rolename'];
        $info['rights']=$_SESSION['k_rights'];
        return $info;
    }

    function inLog($arr,$con) {
        $masterid=$arr->masterid?$arr->masterid:0;
        $array = array('intype' => 1, 'createdate' => date('Y-m-d H:i:s'), 'masterid' => '0', 'bmasterid' => $masterid , 'ip' => get_ip(), 'con' =>$con);
        db::_insert('log', $array);
    }

    static function exitSys() {
        unset($_SESSION['k_user']);
        unset($_SESSION['k_key']);
        unset($_SESSION['k_masterid']);
        unset($_SESSION['k_nick']);
        unset($_SESSION['k_roleid']);
        unset($_SESSION['k_rolename']);
        unset($_SESSION['k_right']);
    }

}
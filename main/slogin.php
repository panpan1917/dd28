<?php
include_once("inc/conn.php");
include_once("inc/function.php");

switch($_GET['act'])
{
    case "logout":
        unset($_SESSION['usersid']);
        session_destroy();
        setcookie("usersid");
        setcookie("username");
        setcookie("password");
        if(is_mobile() && !$_GET['pc'])
        	echo "<script language=javascript>window.location='mobile.php';</script>";
        else
        	echo "<script language=javascript>window.location='pcindex.php';</script>";
        exit;
    case "login":
        $username = str_check($_POST['username']);
        $pwd = $_POST['pass'];
        $pwd = (strlen($pwd) > 30) ? substr($pwd,0,30) : $pwd;
        $pwd = md5($web_pwd_encrypt_prefix . $_POST['pass']);
        $vcode = trim($_POST['vcode']);
        $isKeepLogin = $_POST['iskeep'];
        $arrRet = array('cmd'=>'');

        if(!is_numeric($username))
        {
            echo 'fault';
            exit;
        }
        if(strlen($username) > 50)
        {
            echo 'fault';
            exit;
        }

        if($vcode != $_SESSION["CheckNum"] || empty($vcode)) //验证码错误
        {
            $arrRet['cmd'] = "vcode";
            echo $arrRet['cmd'];
            exit;
        }
        if(isset($_SESSION['username'])) //用户正在使用
        {
            $arrRet['cmd'] = "use";
            echo $arrRet['cmd'];
            exit;
        }
        $ip = usersip();
        $sql = "call web_user_login('{$username}','{$pwd}','{$ip}')";
        $arr = $db->Mysqli_Multi_Query($sql);
        //var_dump($arr);
        switch($arr[0][0]["result"])
        {
            case '0': //成功
                $_SESSION["usersid"] = $arr[0][0]["userid"];
                $_SESSION["username"] = $arr[0][0]["username"];
                $_SESSION["password"] = $pwd;
                $_SESSION["nickname"] = $arr[0][0]["nickname"];
                $_SESSION["points"] = $arr[0][0]["points"];
                $_SESSION["bankpoints"] = $arr[0][0]["bankpoint"];
                $_SESSION["exp"] = $arr[0][0]["experience"];
                $_SESSION["head"] = $arr[0][0]["head"];
                $_SESSION['freeze'] = 0;
                $_SESSION['logintime'] = $arr[0][0]["logintime"];
                $usersid=$arr[0][0]["userid"];
                $sql = "select isagent,a.id from users u, agent a where a.uid=u.id and  u.id = '{$usersid}' limit 1";
                $result = $db->query($sql);
                $users = $db->fetch_row($result);
                if(!empty($users)){
                    $_SESSION['isagent'] = $users[0];
                    $_SESSION['Agent_Id'] = $users[1];
                }
                $vip_level=0;
                $sql = "SELECT id FROM usergroups
					 WHERE (SELECT experience FROM  users WHERE id='{$_SESSION['usersid']}' )
					 BETWEEN creditslower AND creditshigher LIMIT 1";
                $result = $db->query($sql);
                $rs_ = $db->fetch_array($result);
                if($rs_)
                {
                    $vip_level = $rs_['id'];
                }
                $_SESSION['vip_level'] = $vip_level;
                if($isKeepLogin == "1")
                {
                    setcookie("usersid",$arr[0][0]["userid"],time()+3600000);
                    setcookie("username",$arr[0][0]["username"],time()+3600000);
                    setcookie("password",$pwd,time()+3600000);
                }
                else
                {
                    setcookie("usersid",$arr[0][0]["userid"]);
                    setcookie("username",$arr[0][0]["username"]);
                    setcookie("password",$pwd);
                }
                $arrRet['cmd'] = 'ok';
                break;
            case '1': //用户名或密码错误
                $arrRet['cmd'] = 'fault';
                break;
            case '2': //帐号被冻结
                $arrRet['cmd'] = 'dj_001';
                break;
            case '99': //数据库错误
                $arrRet['cmd'] = 'dataerr';
                break;
            default:
                $arrRet['cmd'] = 'other';
                break;
        }

        echo $arrRet['cmd'];
        exit;
    default:
        exit;
}


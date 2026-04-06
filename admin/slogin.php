<?php
    include_once( "inc/conn.php" );
    include_once( "inc/function.php" );

    switch($_GET['act'])
    {
        case "logout":
            addlog( "退出登录" );
            setcookie( "AdminName" );
            setcookie( "PassWord" );
            session_destroy();
            echo "<script>top.location.href='admin_login.php';</script>";
            exit( );
        case "login":
            $username = str_check($_POST['username']);
            $pwd = md5(md5($_POST['pass']));
            $vcode = $_POST['vcode'];
            //WriteLog($_SESSION["CheckCode"]);
            $arr = array('cmd'=>'');

            if($vcode != $_SESSION["CheckCode"])
            {
                $arr['cmd'] = "1";
                echo $arr['cmd'];
                exit;  
            }
            
            $sql = "select `id`,`name`,`password` from admin where `name`='{$username}' and `password`='{$pwd}'";
            $query = $db->query($sql);
            if ( $rs = $db->fetch_array( $query ) )
            {
                //setcookie( "AdminName", $rs['name']);
                //setcookie( "PassWord", $rs['password']);
                $_SESSION["Admin_UserID"] = $rs['id'];
                $_SESSION["Admin_Name"] = $rs['name'];
                $_SESSION["Admin_Pwd"] = $rs['password'];
                $_SESSION["VerifyCode"] = getVerifyCode();
                $sql = "UPDATE admin SET `time`='".date( "Y-m-d H:i:s" )."',`ip`='".usersip( )."' where `name`='{$username}'";
                $db->query($sql);
                addlog( "登录成功" );
                $arr['cmd'] = "0";
            }
            else
            {
                $arr['cmd'] = "2";
            }
            //echo json_encode($arr);
            echo $arr['cmd'];
            exit;
        default:
            exit;
    }


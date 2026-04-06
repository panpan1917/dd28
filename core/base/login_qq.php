<?php
/**

 * Date: 2015/8/23

 */
session_start();
class login_qq{
    function login($appid,$appkey,$callback){
        //申请到的appid
        $_SESSION["appid"]    = $appid;

        //申请到的appkey
        $_SESSION["appkey"]   = $appkey;

        //QQ登录成功后跳转的地址,请确保地址真实可用，否则会导致登录失败。
        $_SESSION["callback"] = $callback;

        //QQ授权api接口.按需调用
        $_SESSION["scope"] = "get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo";

        $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
        $login_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id="
            . $_SESSION["appid"] . "&redirect_uri=" . urlencode($_SESSION["callback"])
            . "&state=" . $_SESSION['state']
            . "&scope=".$_SESSION["scope"];
        return $login_url;
    }
    function qq_callback()
    {
        //debug
       // print_r($_REQUEST);
       // print_r($_SESSION);

        if($_REQUEST['state'] == $_SESSION['state']) //csrf
        {
            $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
                . "client_id=" . $_SESSION["appid"]. "&redirect_uri=" . urlencode($_SESSION["callback"])
                . "&client_secret=" . $_SESSION["appkey"]. "&code=" . $_REQUEST["code"];

            $response = file_get_contents($token_url);
            if (strpos($response, "callback") !== false)
            {
                $lpos = strpos($response, "(");
                $rpos = strrpos($response, ")");
                $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
                $msg = json_decode($response);
                if (isset($msg->error))
                {
                    echo "<h3>error:</h3>" . $msg->error;
                    echo "<h3>msg  :</h3>" . $msg->error_description;
                    exit;
                }
            }

            $params = array();
            parse_str($response, $params);

            return $params['access_token'];
            //debug
            //print_r($params);

            //set access token to session
            $_SESSION["access_token"] = $params["access_token"];

        }
        else
        {
            echo("The state does not match. You may be a victim of CSRF.");
        }
    }

    function get_openid($token)
    {
        $graph_url = "https://graph.qq.com/oauth2.0/me?access_token="
            .$token;// $_SESSION['access_token'];

        $str  = file_get_contents($graph_url);
        if (strpos($str, "callback") !== false)
        {
            $lpos = strpos($str, "(");
            $rpos = strrpos($str, ")");
            $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
        }

        $user = json_decode($str);
        if (isset($user->error))
        {
            echo "<h3>error:</h3>" . $user->error;
            echo "<h3>msg  :</h3>" . $user->error_description;
            exit;
        }

        //debug
        //echo("Hello " . $user->openid);

        //set openid to session
        $_SESSION["openid"] = $user->openid;
        return $user->openid;
    }
    function get_info($appid,$token,$openid){
        $url='https://graph.qq.com/user/get_user_info?
access_token='.$token.'&oauth_consumer_key='.$appid.'&openid='.$openid;
       $result= CURL::get($url);
        return $result;
    }


}
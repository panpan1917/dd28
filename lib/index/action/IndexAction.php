<?php

/**

 * Date: 2015/7/12

 */
session_start();
class IndexAction extends BaseAction
{
    function index(){


      //  $this->display();
    }
    
    function right(){
        $this->display('right');
    }
    function out(){
        $user=new mangerlogin();
        $user->exitSys();
        show_msg('退出',1,1);
    }
}
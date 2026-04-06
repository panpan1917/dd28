<?php

/**

 * Date: 2015/7/13

 */
class Controller
{
    private $applicationHelper;
    public $conf;

    static function run()
    {
        $instance = new Controller();
        $instance->int();
    }

    function int()
    {
        $c = ucwords((Req::request('c')? : 'index'). 'Action');
        $a = Req::get('a')?Req::get('a'):(Req::post('a')?Req::post('a'):'index');
        $app = new $c();
        $app->$a();
    }
}
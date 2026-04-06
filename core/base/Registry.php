<?php

/**

 * Date: 2015/7/13

 */
abstract class Registry
{
    abstract protected function get($key);
    abstract protected function set($key,$val);
}
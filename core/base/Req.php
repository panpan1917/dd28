<?php
/**

 * Date: 2015/7/19

 */
class Req
{
    private static $instance;

    private function __construct(){}

    static function instance(){
        if(!isset(self::$instance))self::$instance=new self();
        return self::$instance;
    }
    /**
     * 魔术方法 有不存在的操作的时候执行
     * @access public
     * @param string $method 方法名
     * @param array $args 参数
     * @return mixed
     */
    public function __call($method, $args)
    {
        switch (strtolower($method)) {
            // 判断提交方式
            case 'ispost'   :
            case 'isget'    :
            case 'ishead'   :
            case 'isdelete' :
            case 'isput'    :
                return strtolower($_SERVER['REQUEST_METHOD']) == strtolower(substr($method, 2));
            // 获取变量 支持过滤和默认值 调用方式 $this->_post($key,$filter,$default);
            case '_get'     :
                $input =& $_GET;
                break;
            case '_post'    :
                $input =& $_POST;
                break;
            case '_put'     :
                parse_str(file_get_contents('php://input'), $input);
                break;
            case '_param'   :
                switch ($_SERVER['REQUEST_METHOD']) {
                    case 'POST':
                        $input = $_POST;
                        break;
                    case 'PUT':
                        parse_str(file_get_contents('php://input'), $input);
                        break;
                    default:
                        $input = $_GET;
                }
                if (C('VAR_URL_PARAMS')) {
                    $params = $_GET[C('VAR_URL_PARAMS')];
                    $input = array_merge($input, $params);
                }
                break;
            case '_request' :
                $input =& $_REQUEST;
                break;
            case '_session' :
                $input =& $_SESSION;
                break;
            case '_cookie'  :
                $input =& $_COOKIE;
                break;
            case '_server'  :
                $input =& $_SERVER;
                break;
            case '_globals' :
                $input =& $GLOBALS;
                break;
            default:
                throw_exception(__CLASS__ . ':' . $method . L('_METHOD_NOT_EXIST_'));
        }
        if (!isset($args[0])) { // 获取全局变量
            $data = $input; // 由VAR_FILTERS配置进行过滤
        } elseif (isset($input[$args[0]])) { // 取值操作
            $data = $input[$args[0]];
            $filters = isset($args[1]) ? $args[1] : $this->default_filter();
            if ($filters) {// 2012/3/23 增加多方法过滤支持
                $filters = explode(',', $filters);
                foreach ($filters as $filter) {
                    if (function_exists($filter)) {
                        $data = is_array($data) ? array_map('check', $data) : $filter($data); // 参数过滤
                    }
                }
            }
        } else { // 变量默认值
            $data = isset($args[2]) ? $args[2] : NULL;
        }
        return $data;
    }
    static public function get($key,$val=null){
        return self::instance()->_get($key,$val);
    }
    static public function post($key,$val=null){
        return self::instance()->_post($key,$val);
    }
    static public function request($key,$val=null){
        return self::instance()->_request($key,$val);
    }
    function default_filter(){
        if (!get_magic_quotes_gpc()) return 'addslashes';
        return null;
        return 'ini_check';
    }

}
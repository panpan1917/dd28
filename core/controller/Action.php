<?php

/**

 * Date: 2015/7/13

 */
abstract class Action
{
    /**
     * 视图实例对象
     * @var view
     * @access protected
     */
    protected $view     =  null;

    /**
     * 当前控制器名称
     * @var name
     * @access protected
     */
    private   $name     =  '';

    /**
     * 模板变量
     * @var tVar
     * @access protected
     */
    protected $tVar     =   array();

    /**
     * 控制器参数
     * @var config
     * @access protected
     */
    protected $config   =   array();

    public function __construct() {
        $this->config=ApplicationHelper::instance('appConfig','getConfig');
        //控制器初始化
        if(method_exists($this,'_initialize'))$this->_initialize();
    }
    /**
     * 模板显示 调用内置的模板引擎显示方法，
     * @access protected
     * @param string $templateFile 指定要调用的模板文件
     * 默认为空 由系统自动定位模板文件
     * @param string $charset 输出编码
     * @param string $contentType 输出类型
     * @param string $content 输出内容
     * @param string $prefix 模板缓存前缀
     * @return void
     */
    protected function display($templateFile='index',$charset='',$contentType='',$content='',$prefix='') {
        $this->initView();
        $this->view->display($templateFile.EXTENSION,$charset,$contentType,$content,$prefix);
    }

    /**
     * 输出内容文本可以包括Html 并支持内容解析
     * @access protected
     * @param string $content 输出内容
     * @param string $charset 模板输出字符集
     * @param string $contentType 输出类型
     * @param string $prefix 模板缓存前缀
     * @return mixed
     */
    protected function show($content,$charset='',$contentType='',$prefix='') {
        $this->initView();
        $this->view->display('',$charset,$contentType,$content,$prefix);
    }

    /**
     *  获取输出页面内容
     * 调用内置的模板引擎fetch方法，
     * @access protected
     * @param string $templateFile 指定要调用的模板文件
     * 默认为空 由系统自动定位模板文件
     * @param string $content 模板输出内容
     * @param string $prefix 模板缓存前缀*
     * @return string
     */
    protected function fetch($templateFile='',$content='',$prefix='') {
        $this->initView();
        return $this->view->fetch($templateFile,$content,$prefix);
    }
    private function initView(){
        //实例化视图类
        if(!$this->view)    $this->view     = ApplicationHelper::instance('View');
        if(APP_NAME=='index'){
            //$this->view->templatePath =TPL.APP_NAME.DIRECTORY_SEPARATOR.$this->config['k_tpl']. DIRECTORY_SEPARATOR;
            //$this->tVar['skin']='template/'.APP_NAME.'/'.$this->config['k_tpl'].'/';
            
            $this->view->templatePath =TPL.APP_NAME.DIRECTORY_SEPARATOR.'default'. DIRECTORY_SEPARATOR;
            $this->tVar['skin']='template/'.APP_NAME.'/default/';
        }elseif(APP_NAME=='admin'){
            $this->view->templatePath =TPL.APP_NAME.DIRECTORY_SEPARATOR.$this->config['k_admin_tpl']. DIRECTORY_SEPARATOR;
            $this->tVar['skin']='template'.'/'.APP_NAME.'/'.$this->config['k_admin_tpl']. '/';
        }elseif(APP_NAME=='wap'){
            $this->view->templatePath =TPL.APP_NAME.DIRECTORY_SEPARATOR.$this->config['k_wap_tpl']. DIRECTORY_SEPARATOR;
            $this->tVar['skin']='template'.'/'.APP_NAME.'/'.$this->config['k_wap_tpl']. '/';
        }elseif(APP_NAME=='mobile'){
            //$this->view->templatePath =TPL.APP_NAME.DIRECTORY_SEPARATOR.$this->config['k_mobile_tpl']. DIRECTORY_SEPARATOR;
            //$this->tVar['skin']='template'.'/'.APP_NAME.'/'.$this->config['k_mobile_tpl']. '/';
            
            $this->view->templatePath =TPL.APP_NAME.DIRECTORY_SEPARATOR.'default'. DIRECTORY_SEPARATOR;
            $this->tVar['skin']='template'.'/'.APP_NAME.'/default/';
        }
        $this->view->compiledPath=DATA_PATH.DIRECTORY_SEPARATOR.'compiled'.DIRECTORY_SEPARATOR.APP_NAME.DIRECTORY_SEPARATOR;
        // 模板变量传值
        if($this->tVar)     $this->view->var=$this->tVar;
    }

    /**
     *  创建静态页面
     * @access protected
     * @htmlfile 生成的静态文件名称
     * @htmlpath 生成的静态文件路径
     * @param string $templateFile 指定要调用的模板文件
     * 默认为空 由系统自动定位模板文件
     * @return string
     */
    protected function buildHtml($htmlfile='',$htmlpath='',$templateFile='') {
        $content = $this->fetch($templateFile);
        $htmlpath   = !empty($htmlpath)?$htmlpath:HTML_PATH;
        $htmlfile =  $htmlpath.$htmlfile.C('HTML_FILE_SUFFIX');
        if(!is_dir(dirname($htmlfile)))
            // 如果静态目录不存在 则创建
            mkdir(dirname($htmlfile),0755,true);
        if(false === file_put_contents($htmlfile,$content))
            throw_exception(L('_CACHE_WRITE_ERROR_').':'.$htmlfile);
        return $content;
    }

    /**
     * 模板变量赋值
     * @access protected
     * @param mixed $name 要显示的模板变量
     * @param mixed $value 变量的值
     * @return void
     */
    protected function assign($name,$value='') {
        /*if(is_array($name)) {
            $this->tVar   =  array_merge($this->tVar,$name);
        }else {
            $this->tVar[$name] = $value;
        }*/
        $this->tVar[$name]   = $value;
        //$this->view->assign($name,$value);
    }

    public function __set($name,$value) {
        $this->assign($name,$value);
    }

    /**
     * 取得模板显示变量的值
     * @access protected
     * @param string $name 模板显示变量
     * @return mixed
     */
    public function get($name='') {
        if('' === $name) {
            return $this->tVar;
        }
        return isset($this->tVar[$name])?$this->tVar[$name]:false;
    }

    public function __get($name) {
        return $this->get($name);
    }

    /**
     * 检测模板变量的值
     * @access public
     * @param string $name 名称
     * @return boolean
     */
    public function __isset($name) {
        return isset($this->tVar[$name]);
    }
    /**
     * 魔术方法 有不存在的操作的时候执行
     * @access public
     * @param string $method 方法名
     * @param array $args 参数
     * @return mixed
     */
    public function __call($method,$args) {
        if( 0 === strcasecmp($method,'index')) {
            if(method_exists($this,'_empty')) {
                // 如果定义了_empty操作 则调用
                $this->_empty($method,$args);
            }elseif(file_exists_case(C('TEMPLATE_NAME'))){
                // 检查是否存在默认模版 如果有直接输出模版
                $this->display();
            }elseif(function_exists('__hack_action')) {
                // hack 方式定义扩展操作
                __hack_action();
            }else{
                _404(L('_ERROR_ACTION_').':'.ACTION_NAME);
            }
        }else{
            switch(strtolower($method)) {
                // 判断提交方式
                case 'ispost'   :
                case 'isget'    :
                case 'ishead'   :
                case 'isdelete' :
                case 'isput'    :
                    return strtolower($_SERVER['REQUEST_METHOD']) == strtolower(substr($method,2));
                // 获取变量 支持过滤和默认值 调用方式 $this->_post($key,$filter,$default);
                case '_get'     :   $input =& $_GET;break;
                case '_post'    :   $input =& $_POST;break;
                case '_put'     :   parse_str(file_get_contents('php://input'), $input);break;
                case '_param'   :
                    switch($_SERVER['REQUEST_METHOD']) {
                        case 'POST':
                            $input  =  $_POST;
                            break;
                        case 'PUT':
                            parse_str(file_get_contents('php://input'), $input);
                            break;
                        default:
                            $input  =  $_GET;
                    }
                    if(C('VAR_URL_PARAMS')){
                        $params = $_GET[C('VAR_URL_PARAMS')];
                        $input  =   array_merge($input,$params);
                    }
                    break;
                case '_request' :   $input =& $_REQUEST;   break;
                case '_session' :   $input =& $_SESSION;   break;
                case '_cookie'  :   $input =& $_COOKIE;    break;
                case '_server'  :   $input =& $_SERVER;    break;
                case '_globals' :   $input =& $GLOBALS;    break;
                default:
                	exit;
                    echo '229 229';
                    throw new AppException($method."您所请求的方法不存在！",0,true);
                    //throw_exception(__CLASS__.':'.$method.L('_METHOD_NOT_EXIST_'));
            }
            if(!isset($args[0])) { // 获取全局变量
                $data       =   $input; // 由VAR_FILTERS配置进行过滤
            }elseif(isset($input[$args[0]])) { // 取值操作
                $data       =	$input[$args[0]];
                $filters    =   isset($args[1])?$args[1]:C('DEFAULT_FILTER');
                if($filters) {// 2012/3/23 增加多方法过滤支持
                    $filters    =   explode(',',$filters);
                    foreach($filters as $filter){
                        if(function_exists($filter)) {
                            $data   =   is_array($data)?array_map($filter,$data):$filter($data); // 参数过滤
                        }
                    }
                }
            }else{ // 变量默认值
                $data       =	 isset($args[2])?$args[2]:NULL;
            }
            return $data;
        }
    }
    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param string $message 错误信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    protected function error($message,$jumpUrl='',$ajax=true) {
        $this->dispatchJump($message,0,$jumpUrl,$ajax);
    }
    /**
     * 默认跳转操作 支持错误导向和正确跳转
     * 调用模板显示 默认为public目录下面的success页面
     * 提示页面为可配置 支持模板标签
     * @param string $message 提示信息
     * @param Boolean $status 状态
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @access private
     * @return void
     */
    private function dispatchJump($message,$status=1,$jumpUrl='',$limittime=2000,$ajax=true) {
        if($ajax){
            exit(json_encode(array('status'=>$status,'message'=>$message,'url'=>$jumpUrl,'time'=>$limittime)));
        }
    }

}
<?php
class AppException extends Exception
{
    /**
     * 异常类型
     * @var string
     * @access private
     */
    private $type;

    // 是否存在多余调试信息
    private $extra;

    /**
     * 架构函数
     * @access public
     * @param string $message  异常信息
     */
    public function __construct($message,$code=0,$extra=true) {
        parent::__construct($message,$code);
        //$this->type = get_class($this);
        $this->extra = $extra;
    }

    /**
     * 异常输出 所有异常处理类均通过__toString方法输出错误
     * 每次异常都会写入系统日志
     * 该方法可以被子类重载
     * @access public
     * @return array
     */
    public function __toString() {
        $trace = $this->getTrace();
        //var_dump($this);
        if($this->extra)
            // 通过throw_exception抛出的异常要去掉多余的调试信息
            array_shift($trace);
        $this->class    =   isset($trace[0]['class'])?$trace[0]['class']:'';
        $this->function =   isset($trace[0]['function'])?$trace[0]['function']:'';
        $traceInfo      =   '';
        $time = date('y-m-d H:i:m');
        foreach($trace as $t) {
            $traceInfo .= '['.$time.'] '.$t['file'].' ('.$t['line'].') ';
            $traceInfo .= $t['class'].$t['type'].$t['function'].'(';
            $traceInfo .= implode(', ', $t['args']);
            $traceInfo .=")<br>\n";
        }
        // 记录 Exception 日志
        /*if(C('LOG_EXCEPTION_RECORD')) {
            Log::Write('('.$this->type.') '.$this->message);
        }*/
        $result='<h1>'.$this->message.'</h1>';
        $result.=$this->class.':'.$this->function;
        $result.='<p>'.$traceInfo.'</p>';
        echo $result;
        return $result;
    }
}
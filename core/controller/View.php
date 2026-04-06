<?php
class View
{
    public $templatePath        = '';
    public $fileName            = '';
    public $compiledPath        = '';
    public $theme               ='default';
    public $cacheCompiled       = false;
    public $var                 = array();
    private $compiledName       = '';

    function __construct($templatePath = '', $compiledPath = '') {
        global $k_tpl, $k_tplcache;
        $this->templatePath = $templatePath ? $templatePath : TPL . $this->theme . DIRECTORY_SEPARATOR;
        $this->compiledPath = $compiledPath ? $compiledPath : KKROOT . '/data/compiled/f/';
        $this->cacheCompiled = $k_tplcache;
    }

    function get_compiled_name($filename) {
        return $this->compiledPath . $this->get_filename($filename);
    }

    function fetch($filename) {
        $tplFileName = $this->templatePath . $filename;
        if (!$filename || !$this->is_filename($tplFileName)) exit('找不到模板文件: ' . $tplFileName);
        $compiledName = $this->get_compiled_name($filename);
        if (APP_DEBUG || !$this->is_filename($compiledName)) {
            $tpl = $this->parse(read_file($tplFileName));
            writer_file($compiledName, $tpl);
        }
    }

    function get_con($filename){
        $tplFileName = $this->templatePath . $filename;
        if (!$filename || !$this->is_filename($tplFileName)) exit('找不到模板文件: ' . $tplFileName);
        $compiledName = $this->get_compiled_name($filename);
        return $this->parse(read_file($tplFileName));
    }
    /**
     * 判断模板文件是否存在
     * @param type $filename
     */
    function is_filename($filename) {
        if (file_exists($filename)) return true;
        return false;
    }

    /**
     * 得到无后戳名的文件名称
     * @param type $filename
     * @return type
     */
    function get_filename($filename) {
        $array = explode('.', $filename);
        return $array[0] . '.php';
    }
    function compiled($compiledName) {
        extract($this->var);
        unset($this->var);
        include $compiledName;
    }

    function display($templateFile) {

        $this->fetch($templateFile);
        $this->compiled($this->get_compiled_name($templateFile));
    }

    function save_html($file_name, $file_url) {
        $this->fetch($file_name);
        ob_start();
        $this->compiled($this->get_compiled_name($file_name));
        $con = ob_get_contents();
        writer_file($file_url, $con);
        ob_end_clean();
    }
    /*
     * 解析函数
     */
    function parse($tpl) {
        return preg_replace_callback('/{(\/?)(\$|tpl|if|elseif|else|loop|U|arclist|list|arctype|sql|ad|likearc|pagelist|set|skin|function|:)\s*([^}]*)}/i', array($this,'replace_callback'), $tpl);
    }
    function replace_callback($matches){
    if($matches[1]!=='/')
    {
        switch($matches[2])
        {
            case '$':
            {
                $str = trim($matches[3]);
                $first = $str[0];
                if($first != '.' && $first != '(')
                {
                    $str=str_replace('.','->',$str);
                    return '<?php echo isset($'.$str.')?$'.$str.':"";?>';
                }
                else return $matches[0];
            }
            case 'tpl':
                    return $this->get_con('public'.DIRECTORY_SEPARATOR.$matches[3].EXTENSION);
            break;
            case ':':
            case 'function':
                $matches[3]=str_replace('.','->',$matches[3]);
                if(strpos($matches[3],'=')===0 || strpos($matches[3],'url' )===0)return '<?php echo '.str_replace('=','',$matches[3]).';?>';
                return '<?php '.$matches[3].';?>';
                break;
            case 'loop':
                preg_match_all('#(\S+)#',$matches[3],$str);
                if(count($str[0])==2){
                    return '<?php if(is_array('.$str[0][0].'))  foreach('.$str[0][0].' as '.$str[0][1].') { ?>';
                }elseif(count($str[0])==3){
                    return '<?php if(is_array('.$str[0][0].'))  foreach('.$str[0][0].' as '.$str[0][1].'=>'.$str[0][2].') { ?>';
                }
            case 'U':
                return '<?php echo U'.str_replace('.', '->',$matches[3]).';?>';

            case 'if': return '<?php if('.str_replace('.', '->',$matches[3]).'){?>';
            case 'elseif': return '<?php }elseif('.str_replace('.', '->',$matches[3]).'){?>';
            case 'else': return '<?php }else{?>';
            case 'set':
            {
                return '<?php '.$matches[3].'?>';
            }
            case 'pagelist':
                return '<?php $p=new page(); echo $p->pagelist("page=$page&total=$total&' . $matches[3] . '"); ?>';

            default:
            {
                return $matches[0];
            }
        }
    }
    else
    {
        if($matches[2] =='code') return '?>';
        else return '<?php }?>';
    }
}
}
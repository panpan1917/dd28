<?php
/**

 * Date: 2015/8/16

 */
class http{
    protected $_useragent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1';
    protected $_url;
    protected $_followlocation;
    protected $_timeout;
    protected $_maxRedirects;
    protected $_cookieFileLocation = './cookie.txt';
    protected $_post;
    protected $_postFields;
    protected $_referer ="";
    protected $_hosts='';
    protected $_session;
    protected $_webpage;
    protected $_includeHeader;
    protected $_noBody;
    protected $_status;
    protected $_binaryTransfer;
    public    $authentication = 0;
    public    $auth_name      = '';
    public    $auth_pass      = '';
    public static $_instance = null;

    public function useAuth($use){
        $this->authentication = 0;
        if($use == true) $this->authentication = 1;
    }

    public function setName($name){
        $this->auth_name = $name;
    }
    public function setPass($pass){
        $this->auth_pass = $pass;
    }

    public function __construct($url,$followlocation = true,$timeOut = 30,$maxRedirecs = 4,$binaryTransfer = false,$includeHeader = false,$noBody = false){
        $this->_url = $url;
        $this->_followlocation = $followlocation;
        $this->_timeout = $timeOut;
        $this->_maxRedirects = $maxRedirecs;
        $this->_noBody = $noBody;
        $this->_includeHeader = $includeHeader;
        $this->_binaryTransfer = $binaryTransfer;
        $this->_cookieFileLocation = dirname(__FILE__).'/cookie.txt';
    }
    public static function getInstance($url,$followlocation = true,$timeOut = 30,$maxRedirecs = 4,$binaryTransfer = false,$includeHeader = false,$noBody = false){
        if(!(self::$_instance instanceof DCurl)){
            self::$_instance = new DCurl($url,$followlocation,$timeOut,$maxRedirecs,$binaryTransfer,$includeHeader,$noBody);
        }
        return self::$_instance;
    }
    public function setReferer($referer){
        $this->_referer = $referer;
    }
    /**
     * 要配置的虚拟域名
     * @param string $domain，如admin.ncms.dailypad.cn
     */
    public function setHosts($domain){
        $this->_hosts=$domain;
    }
    public function setCookiFileLocation($path)
    {
        $this->_cookieFileLocation = $path;
    }
    /**
     *Post请求的字段
     * @param array $postFields 請求數據為數組
     */
    public function setPost ($postFields)
    {
        $this->_post = true;
        $this->_postFields = $postFields;
    }

    public function setUserAgent($userAgent)
    {
        $this->_useragent = $userAgent;
    }
    //向url地址发送请求
    public function createCurl($url = 'null')
    {
        if($url != 'null'){
            $this->_url = $url;
        }

        $s = curl_init();
        //需要获取的URL地址，也可以在curl_init()函数中设置
        curl_setopt($s,CURLOPT_URL,$this->_url);
        //一个用来设置HTTP头字段的数组。使用如下的形式的数组进行设置： array('Content-type: text/plain', 'Content-length: 100')
        curl_setopt($s,CURLOPT_HTTPHEADER,array('Expect:')); //This will remove the expect http header
        //设置cURL允许执行的最长秒数。
        curl_setopt($s,CURLOPT_TIMEOUT,$this->_timeout);
        //指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的。
        curl_setopt($s,CURLOPT_MAXREDIRS,$this->_maxRedirects);
        //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
        if(!empty($this->_hosts)){
            curl_setopt($s,CURLOPT_HTTPHEADER, array('Host:'.$this->_hosts) );
        }
        //启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。
        curl_setopt($s,CURLOPT_FOLLOWLOCATION,$this->_followlocation);
        //连接结束后保存cookie信息的文件。
        curl_setopt($s,CURLOPT_COOKIEJAR,$this->_cookieFileLocation);
        //包含cookie数据的文件名，cookie文件的格式可以是Netscape格式，或者只是纯HTTP头部信息存入文件。
        curl_setopt($s,CURLOPT_COOKIEFILE,$this->_cookieFileLocation);

        if($this->authentication == 1){
            //传递一个连接中需要的用户名和密码，格式为："[username]:[password]"。
            curl_setopt($s, CURLOPT_USERPWD, $this->auth_name.':'.$this->auth_pass);
        }
        if($this->_post)
        {
            //启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
            curl_setopt($s,CURLOPT_POST,true); 			 //全部数据使用HTTP协议中的"POST"操作来发送。要发送文件，在文件名前面加上@前缀并使用完整路径。这个参数可以通过urlencoded后的字符串类似'para1=val1&para2=val2&...'或使用一个以字段名为键值，字段数据为值的数组。如果value是一个数组，Content-Type头将会被设置成multipart/form-data。
            curl_setopt($s,CURLOPT_POSTFIELDS,$this->_postFields);

        }

        if($this->_includeHeader)
        {
            //启用时会将头文件的信息作为数据流输出。
            curl_setopt($s,CURLOPT_HEADER,true);
        }

        if($this->_noBody)
        {
            //启用时将不对HTML中的BODY部分进行输出。
            curl_setopt($s,CURLOPT_NOBODY,true);
        }
        /*
        if($this->_binary)
        {
           //在启用CURLOPT_RETURNTRANSFER的时候，返回原生的（Raw）输出。
            curl_setopt($s,CURLOPT_BINARYTRANSFER,true);
        }
        */
        //在HTTP请求中包含一个"User-Agent: "头的字符串。
        curl_setopt($s,CURLOPT_USERAGENT,$this->_useragent);
        //在HTTP请求头中"Referer: "的内容。
        curl_setopt($s,CURLOPT_REFERER,$this->_referer);

        $this->_webpage = curl_exec($s);
        $this->_status = curl_getinfo($s,CURLINFO_HTTP_CODE);
        curl_close($s);

    }
    //得到状态码
    public function getHttpStatus()
    {
        return $this->_status;
    }
    //得到内容，直接 echo $obj_name
    public function __tostring(){
        return $this->_webpage;
    }

    /**
     * 通过Curl向远程地址提交Post数据
     * @param array $data 提交的数据
     * @param string $url 远程URL地址
     * @param array $key 可能存在的用于验证合法性的符号
     * @example
     * $data=json_encode($data);<br/>
     * $data=array('action'=>'get','data'=>$data,'table'=>'admin');<br/>
     * $return=curlPost($data);<br/>
     * p($return);
     */
    public function post($data=array()){
        if(!is_array($data)) die('$data format error！');
        $this->setPost($data);
        $this->createCurl();
        return $this->__tostring();
    }
}
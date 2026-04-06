<?php
 
// ######(以下配置为入网测试环境用)#######
// 签名证书路径
const IELPM_PRIVATE_CERT_PATH = '/alidata/www/kdy28dev/core/gwpay/cert/CS20170504000851_20170513150744010.pfx';//C13521166690_20151209102818959.pfx

// 签名证书密码
const IELPM_PRIVATE_CERT_PWD = 'yk28999';//111111a  yk28999a

// 公钥证书
const IELPM_PUBLIC_CERT_PATH = '/alidata/www/kdy28dev/core/gwpay/cert/SS20170504000851_20170513150744010.cer';//S13521166690_20151209102818959.cer


// 支付
const IELPM_PAY_URL = 'https://cashier.ielpm.com/paygate/v1/web/cashier';
const IELPM_PAY_URL_H5 = 'https://cashier.ielpm.com/paygate/v1/web/h5/cashier';

//商户接收前台通知地址
const MER_RETURN_URL = "https://vip.didi8888.com/gwreturn.php";

//商户接收后台通知地址
const MER_NOTIFY_URL = "https://vip.didi8888.com/gwnotify.php";

//退款地址
const IELPM_REFUND_URL = 'https://cashier.ielpm.com/paygate/v1/web/refund';

//查询地址
const IELPM_QUERY_URL = 'https://cashier.ielpm.com/paygate/v1/web/query';

//商户号
const MERCHANTNO = 'S20170504000851';//S20170504000851

//渠道号
const CHANNELNO = '03';
const CHANNELNO_H5 = '02';

//版本号
const VERSION = 'v1.1';

//日志 目录 
const SDK_LOG_FILE_PATH = '/alidata/www/kdy28dev/data/gwpaylog/';

//日志级别
const SDK_LOG_LEVEL = 'DEBUG';

?>
<?php

/**
 * 　* 名称 返回页面
 * 　* 功能  支付宝外部服务接口控制
 * 　* 版本  0.6
 * 　* 日期  2006-6-10
 * 　* 作者   http://www.buybay.org
 * 联系   Email： raftcham@hotmail.com  Homepage：http://www.buybay.org
 * 　* 版权   Copyright2006 Buybay NetTech
 * 　*/
require_once __DIR__ . '/alipay_notify.php';
require_once __DIR__ . '/alipay_config.php';
$alipay        = new alipay_notify($partner, $security_code, $sign_type, $_input_charset, $transport);
$verify_result = $alipay->notify_verify();
if ($verify_result) {
    echo 'success';
    //这里放入你自定义代码,比如根据不同的trade_status进行不同操作
    log_result('verify_success'); //将验证结果存入文件
} else {
    echo 'fail';
    //这里放入你自定义代码，这里放入你自定义代码,比如根据不同的trade_status进行不同操作
    log_result('verify_failed');
}
function log_result($word)
{
    $fp = fopen('log.txt', 'a');
    flock($fp, LOCK_EX);
    fwrite($fp, $word . '：执行日期：' . strftime('%Y%m%d%H%I%S', time()) . "\t\n");
    flock($fp, LOCK_UN);
    fclose($fp);
}

<?php

/**
 * 　* 类名 alipay_notify
 * 　* 功能  支付宝外部服务接口控制
 * 　* 版本  0.6
 * 　* 日期  2006-6-10
 * 　* 作者   http://www.buybay.org
 * 联系   Email： raftcham@hotmail.com  Homepage：http://www.buybay.org
 * 　* 版权   Copyright2006 Buybay NetTech
 * 　*/
class alipay_notify
{
    public $gateway;
    public $security_code;     //安全校验码
    public $partner;
    public $sign_type;
    public $mysign;
    public $_input_charset;
    public $transport;

    public function __construct(
        $partner,
        $security_code,
        $sign_type = 'MD5',
        $_input_charset = 'utf-8',
        $transport = 'https'
    ) {
        $this->partner        = $partner;
        $this->security_code  = $security_code;
        $this->sign_type      = $sign_type;
        $this->mysign         = '';
        $this->_input_charset = $_input_charset;
        $this->transport      = $transport;
        if ('https' === $this->transport) {
            $this->gateway = 'https://www.alipay.com/cooperate/gateway.do?';
        } else {
            $this->gateway = 'http://notify.alipay.com/trade/notify_query.do?';
        }
    }

    public function notify_verify()
    {   //对notify_url的认证
        if ('https' === $this->transport) {
            $veryfy_url = $this->gateway . 'service=notify_verify' . '&partner=' . $this->partner . '&notify_id=' . $_POST['notify_id'];
        } else {
            $veryfy_url = $this->gateway . 'notify_id=' . $_POST['notify_id'] . '&partner=' . $this->partner;
        }
        $veryfy_result = $this->get_verify($veryfy_url);
        $post          = $this->para_filter($_POST);
        $sort_post     = $this->arg_sort($post);
        //        while (list($key, $val) = each($sort_post)) {
        foreach ($sort_post as $key => $val) {
            $arg .= $key . '=' . $val . '&';
        }
        $prestr       = mb_substr($arg, 0, count($arg) - 2);  //去掉最后一个&号
        $this->mysign = $this->sign($prestr . $this->security_code);
        if (preg_match('/true$/i', $veryfy_result) && $this->mysign == $_POST['sign']) {
            return true;
        }

        return false;
    }

    public function return_verify()
    {   //对return_url的认证
        if ('https' === $this->transport) {
            $veryfy_url = $this->gateway . 'service=notify_verify' . '&partner=' . $this->partner . '&notify_id=' . $_GET['notify_id'];
        } else {
            $veryfy_url = $this->gateway . 'notify_id=' . $_GET['notify_id'] . '&partner=' . $this->partner;
        }
        $veryfy_result = $this->get_verify($veryfy_url);
        $GET           = $this->para_filter($_GET);
        $sort_get      = $this->arg_sort($_GET);
        //        while (list($key, $val) = each($sort_get)) {
        foreach ($sort_get as $key => $val) {
            if ('sign' !== $key && 'sign_type' !== $key) {
                $arg .= $key . '=' . $val . '&';
            }
        }
        $prestr       = mb_substr($arg, 0, count($arg) - 2);  //去掉最后一个&号
        $this->mysign = $this->sign($prestr . $this->security_code);

        log_result('return_url_log=' . $_GET['sign'] . '-------------------' . $this->mysign . '&' . $this->charset_decode(implode(',', $_GET), $this->_input_charset));
        //**********************************上面写日志
        if (preg_match('/true$/i', $veryfy_result) && $this->mysign == $_GET['sign']) {
            return true;
        }

        return false;
    }

    public function get_verify($url, $time_out = '60')
    {
        $urlarr     = parse_url($url);
        $errno      = '';
        $errstr     = '';
        $transports = '';
        if ('https' === $urlarr['scheme']) {
            $transports     = 'ssl://';
            $urlarr['port'] = '443';
        } else {
            $transports     = 'tcp://';
            $urlarr['port'] = '80';
        }
        $fp = @fsockopen($transports . $urlarr['host'], $urlarr['port'], $errno, $errstr, $time_out);
        if (!$fp) {
            die("ERROR: $errno - $errstr<br>\n");
        }
        fwrite($fp, 'POST ' . $urlarr['path'] . " HTTP/1.1\r\n");
        fwrite($fp, 'Host: ' . $urlarr['host'] . "\r\n");
        fwrite($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fwrite($fp, 'Content-length: ' . mb_strlen($urlarr['query']) . "\r\n");
        fwrite($fp, "Connection: close\r\n\r\n");
        fwrite($fp, $urlarr['query'] . "\r\n\r\n");
        while (!feof($fp)) {
            $info[] = @fgets($fp, 1024);
        }

        fclose($fp);
        $info = implode(',', $info);
        //            while (list($key, $val) = each($_POST)) {
        foreach ($_POST as $key => $val) {
            $arg .= $key . '=' . $val . '&';
        }

        log_result('return_url_log=' . $url . $this->charset_decode($info, $this->_input_charset));
        log_result('return_url_log=' . $this->charset_decode($arg, $this->_input_charset));

        return $info;
    }

    public function arg_sort($array)
    {
        ksort($array);
        reset($array);

        return $array;
    }

    public function sign($prestr)
    {
        $sign = '';
        if ('MD5' === $this->sign_type) {
            $sign = md5($prestr);
        } elseif ('DSA' === $this->sign_type) {
            //DSA 签名方法待后续开发
            die('DSA 签名方法待后续开发，请先使用MD5签名方式');
        } else {
            die('支付宝暂不支持' . $this->sign_type . '类型的签名方式');
        }

        return $sign;
    }

    public function para_filter($parameter)
    { //除去数组中的空值和签名模式
        $para = [];
        //        while (list($key, $val) = each($parameter)) {
        foreach ($parameter as $key => $val) {
            if ('sign' === $key || 'sign_type' === $key || '' == $val) {
                continue;
            }
            $para[$key] = $parameter[$key];
        }

        return $para;
    }

    //实现多种字符编码方式
    public function charset_encode($input, $_output_charset, $_input_charset = 'utf-8')
    {
        $output = '';
        if (!isset($_output_charset)) {
            $_output_charset = $this->parameter['_input_charset '];
        }
        if ($_input_charset == $_output_charset || null === $input) {
            $output = $input;
        } elseif (function_exists('mb_convert_encoding')) {
            $output = mb_convert_encoding($input, $_output_charset, $_input_charset);
        } elseif (function_exists('iconv')) {
            $output = iconv($_input_charset, $_output_charset, $input);
        } else {
            die('sorry, you have no libs support for charset change.');
        }

        return $output;
    }

    //实现多种字符解码方式
    public function charset_decode($input, $_input_charset, $_output_charset = 'utf-8')
    {
        $output = '';
        if (!isset($_input_charset)) {
            $_input_charset = $this->_input_charset;
        }
        if ($_input_charset == $_output_charset || null === $input) {
            $output = $input;
        } elseif (function_exists('mb_convert_encoding')) {
            $output = mb_convert_encoding($input, $_output_charset, $_input_charset);
        } elseif (function_exists('iconv')) {
            $output = iconv($_input_charset, $_output_charset, $input);
        } else {
            die('sorry, you have no libs support for charset changes.');
        }

        return $output;
    }
}

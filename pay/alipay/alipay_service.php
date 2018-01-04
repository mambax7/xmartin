<?php

/**
 * 　* 类名 alipay_service
 * 　* 功能  支付宝外部服务接口控制
 * 　* 版本  0.6
 * 　* 日期  2006-6-10
 * 　* 作者   http://www.buybay.org
 * 联系   Email： raftcham@hotmail.com  Homepage：http://www.buybay.org
 * 　* 版权   Copyright2006 Buybay NetTech
 * 　*/
class alipay_service
{
    public $gateway = 'http://www.alipay3.net/cooperate/gateway.do?';         //支付接口
    public $parameter;       //全部需要传递的参数
    public $security_code;     //安全校验码
    public $mysign;             //签名

    //构造支付宝外部服务接口控制
    public function __construct($parameter, $security_code, $sign_type = 'MD5', $transport = 'https')
    {
        $this->parameter     = $this->para_filter($parameter);
        $this->security_code = $security_code;
        $this->sign_type     = $sign_type;
        $this->mysign        = '';
        $this->transport     = $transport;
        if ('' == $parameter['_input_charset']) {
            $this->parameter['_input_charset'] = 'utf-8';
        }
        if ('https' === $this->transport) {
            $this->gateway = 'https://www.alipay.com/cooperate/gateway.do?';
        } else {
            $this->gateway = 'httsp://www.alipay.com/cooperate/gateway.do?';
        }
        $sort_array = [];
        $arg        = '';
        $sort_array = $this->arg_sort($this->parameter);
        //        while (list($key, $val) = each($sort_array)) {
        foreach ($sort_array as $key => $val) {
            $arg .= $key . '=' . $this->charset_encode($val, $this->parameter['_input_charset']) . '&';
        }
        $prestr       = substr($arg, 0, count($arg) - 2);  //去掉最后一个问号
        $this->mysign = $this->sign($prestr . $this->security_code);
    }

    public function create_url()
    {
        $url        = $this->gateway;
        $sort_array = [];
        $arg        = '';
        $sort_array = $this->arg_sort($this->parameter);
        //        while (list($key, $val) = each($sort_array)) {
        foreach ($sort_array as $key => $val) {
            $arg .= $key . '=' . urlencode($this->charset_encode($val, $this->parameter['_input_charset'])) . '&';
        }
        $url .= $arg . 'sign=' . $this->mysign . '&sign_type=' . $this->sign_type;

        return $url;
    }

    public function signParams()
    {
        $url        = $this->gateway;
        $sort_array = [];
        $arg        = '';
        $sort_array = $this->arg_sort($this->parameter);
        //        while (list($key, $val) = each($sort_array)) {
        foreach ($sort_array as $key => $val) {
            $arg .= $key . '=' . urlencode($this->charset_encode($val, $this->parameter['_input_charset'])) . '&';
        }

        return $this->mysign;
    }

    public function arg_sort($array)
    {
        ksort($array);
        reset($array);

        return $array;
    }

    public function sign($prestr)
    {
        $mysign = '';
        if ('MD5' === $this->sign_type) {
            $mysign = md5($prestr);
        } elseif ('DSA' === $this->sign_type) {
            //DSA 签名方法待后续开发
            die('DSA 签名方法待后续开发，请先使用MD5签名方式');
        } else {
            die('支付宝暂不支持' . $this->sign_type . '类型的签名方式');
        }

        return $mysign;
    }

    public function para_filter($parameter)
    { //除去数组中的空值和签名模式
        $para = [];
        //        while (list($key, $val) = each($parameter)) {
        foreach ($parameter as $key => $val) {
            if ('sign' === $key || 'sign_type' === $key || '' == $val) {
                continue;
            } else {
                $para[$key] = $parameter[$key];
            }
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
        if ($_input_charset == $_output_charset || null == $input) {
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
}

<?php
/**
 * ��* ���� ��������ҳ��
 * ��* ����  ֧�����ⲿ����ӿڿ���
 * ��* �汾  0.6
 * ��* ����  2006-6-10
 * ��* ����   http://www.buybay.org
 * ��ϵ   Email�� raftcham@hotmail.com  Homepage��http://www.buybay.org
 * ��* ��Ȩ   Copyright2006 Buybay NetTech
 * ��*/
require_once __DIR__ . '/alipay_service.php';
require_once __DIR__ . '/alipay_config.php';
$parameter = [
    'service'            => 'create_direct_pay_by_user',
    //�������ͣ�����ʵ�ｻ�ף�trade_create_by_buyer����Ҫ��д������ ������Ʒ���ף�create_digital_goods_trade_p ������create_donate_trade_p
    'partner'            => $partner,
    //�����̻���
    'agent'              => $agent,
    'return_url'         => $return_url,
    //ͬ������
    'notify_url'         => $notify_url,
    //�첽����
    '_input_charset'     => $_input_charset,
    //�ַ�����Ĭ��ΪGBK
    'subject'            => '�������',
    //��Ʒ���ƣ�����
    'body'               => 'jhshs����1234567',
    //��Ʒ����������
    'out_trade_no'       => time(),
    //��Ʒ�ⲿ���׺ţ�����,ÿ�β��Զ����޸�
    'total_fee'          => '0.01',
    //��Ʒ���ۣ�����
    'payment_type'       => '1',
    // ��Ʒ֧������ 1 ����Ʒ���� 2�������� 3���������� 4������ 5���ʷѲ��� 6������
    'show_url'           => 'http://www.buyaby.org/',
    //��Ʒ�����վ��˾
    'seller_email'       => $seller_email,
    //�������䣬����
    'paymethod'          => 'bankPay',
    //"directPay"
    'defaultbank'        => 'CMB',
    'royalty_type'       => '10',
    'royalty_parameters' => 'yao2857@yahoo.com.cn^0.01^��Ʊ����',

];
$alipay    = new alipay_service($parameter, $security_code, $sign_type);
print_r($parameter);
$link = $alipay->create_url();
print <<<EOT
<b>
<a href= $link  target ="_blank">submit</a>
EOT;

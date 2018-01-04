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
    'service'        => 'refund_fastpay_by_platform_pwd',
    //�������ͣ�
    'partner'        => $partner,
    //�����̻���
    'notify_url'     => $notify_url,
    //�첽����
    '_input_charset' => $_input_charset,
    //�ַ�����Ĭ��ΪGBK
    'batch_no'       => '200806170001',
    //����+��ˮ�ţ�����200806170001
    'refund_date'    => '2008-06-17 19:13:13',
    //��Ʒ����������
    'batch_num'      => '1',
    //��Ʒ���ۣ�����
    'detail_data'    => '2008051946355333^0.01^��׿����Э���˿�|ap1@itour.cc^^ap4@itour.cc^^0.01^�����˿�|ap2@itour.cc^^ap4@itour.cc^^0.01^�����˿�',
    // Ʊ��+�շ�+����   ��ʽ����
    'return_type'    => 'html',
    //��Ʒ�����վ��˾

];
$alipay    = new alipay_service($parameter, $security_code, $sign_type);
print_r($parameter);
$link = $alipay->create_url();
print <<<EOT
<b>
<a href= $link  target ="_blank">submit</a>
EOT;




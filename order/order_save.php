<?php
include __DIR__ . '/../../../mainfile.php';
include XOOPS_ROOT_PATH . '/modules/martin/include/common.php';
if (!defined('MODULE_URL')) {
    define('MODULE_URL', XOOPS_URL . '/modules/martin/');
}

global $xoopsUser, $xoopsdModule;
if (!$xoopsUser) {
    redirect_header(XOOPS_URL, 3, _AM_MARTIN_UNAUTHORIZED_ACCESS);
}

$dateNumber      = isset($_POST['dateNumber']) ? $_POST['dateNumber'] : null;
$service         = isset($_POST['serviceNum']) ? $_POST['serviceNum'] : null;
$person_exchange = isset($_POST['person_exchange']) ? $_POST['person_exchange'] : null;
$room_id         = \Xmf\Request::getInt('room_id', 0, 'POST');
$hotel_id        = \Xmf\Request::getInt('hotel_id', 0, 'POST');
$check_in_date   = \Xmf\Request::getInt('check_in_date', 0, 'POST');
$check_out_date  = \Xmf\Request::getInt('check_out_date', 0, 'POST');
$extra_person    = isset($_POST['extra_person']) ? trim($_POST['extra_person']) : '';
$User            = isset($_POST['user']) ? array_filter($_POST['user']) : null;
if (is_null($User)) {
    redirect_header(XOOPS_URL, 2, '非法.');
}

$cartHandler    = xoops_getModuleHandler('cart', 'martin');
$roomHandler    = xoops_getModuleHandler('room', 'martin');
$serviceHandler = xoops_getModuleHandler('hotelservice', 'martin');
$cartObj        = $cartHandler->create();

$order_total_price   = 0;
$order_pay_money     = 0;
$order_sented_coupon = 0;
$service_total       = 0;
$hotel_service       = $serviceHandler->getHotelService($hotel_id);
$room_price          = $roomHandler->GetRoomDatePrie($room_id, $check_in_date, $check_out_date);

//价格计算
//var_dump($room_price);exit;
foreach ($room_price as $key => $row) {
    $order_total_price   += $dateNumber[$key] * $row['room_price'];
    $order_sented_coupon += $dateNumber[$key] * $row['room_sented_coupon'];
}
//var_dump($hotel_service);
foreach ($hotel_service as $key => $row) {
    $service_total += $service[$row['service_id']] * $row['service_extra_price'];
}
$order_total_price += $service_total;
$order_pay_money   = $order_total_price - $person_exchange;
//价格计算

//order datas
$cartObj->setVar('order_type', 1);
$cartObj->setVar('order_mode', 1);
$cartObj->setVar('order_uid', $xoopsUser->uid());
$cartObj->setVar('order_status', 6);
$cartObj->setVar('order_total_price', $order_total_price);
$cartObj->setVar('order_pay_money', $order_pay_money);
$cartObj->setVar('order_coupon', $person_exchange);
$cartObj->setVar('order_sented_coupon', $order_sented_coupon);
$cartObj->setVar('order_real_name', $User['name']);
$cartObj->setVar('order_document_type', $User['document']);
$cartObj->setVar('order_document', $User['document_value']);
$cartObj->setVar('order_telephone', $User['telephone']);
$cartObj->setVar('order_phone', $User['phone']);
$cartObj->setVar('order_extra_persons', $extra_person);
$cartObj->setVar('order_status_time', time());
$cartObj->setVar('order_submit_time', time());
$cartObj->setVar('room_date_count', $dateNumber);
$cartObj->setVar('service_date_count', $service);
//order datas
//var_dump($cartObj);

if ($order_id = $cartHandler->saveCart($cartObj)) {
    if (is_array($dateNumber) && !empty($dateNumber)) {
        if (!$cartHandler->InsertOrderRoom($order_id, [$room_id], $dateNumber)) {
            redirect_header('javascript:history.go(-1);', 2, '房间写入失败,订单保存失败.');
        }
    }
    if (is_array($service) && !empty($service)) {
        if (!$cartHandler->InsertOrderService($order_id, $service)) {
            redirect_header('javascript:history.go(-1);', 2, '服务写入失败，订单保存失败.');
        }
    }
    redirect_header(MODULE_URL . 'pay.php?order_id=' . $order_id, 2, '订单提成功.');
} else {
    redirect_header('javascript:history.go(-1);', 2, '订单保存失败.');
}

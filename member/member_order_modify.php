<?php

/**
 * @
 * @method:
 * @license   http://www.blags.org/
 * @created   :2010年07月19日 20时40分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
if (empty($order_id)) {
    redirect_header('javascript:history.go(-1);', 1, '没有该订单.');
}

require_once XMARTIN_ROOT_PATH . 'HotelSearchLeft.php';

$xoopsOption['xoops_pagetitle'] = '查询预定 ' . $order_id . '  - 用户中心';

$searchHandler    = $helper->getHandler('Search');
$orderHandler     = $helper->getHandler('Order');
$memberHandler    = $helper->getHandler('Member');
$hotelHandler     = $helper->getHandler('Hotel');
$promotionHandler = $helper->getHandler('Promotion');

$OrderObj = $orderHandler->get($order_id);
foreach ($OrderObj->vars as $k => $v) {
    $order[$k] = $v['value'];
}

$xoopsUser->cleanVars();
$user = &$xoopsUser->cleanVars;
//var_dump($order);
//var_dump($OrderObj->rooms);

$OrderType         = getModuleArray('order_type', 'order_type', true);
$OrderMode         = getModuleArray('order_mode', 'order_mode', true);
$line_pays         = getModuleArray('line_pays', 'line_pays', true);
$online_pays       = getModuleArray('online_pays', 'online_pays', true);
$OrderStatus       = getModuleArray('order_status', 'order_status', true);
$OrderDocumentType = getModuleArray('order_document_type', 'order_document_type', true);
$pays              = array_merge($online_pays, $line_pays);
$hotel_name        = $OrderObj->rooms[0]['hotel_name'] ?: $OrderObj->qrooms[0]['hotel_name'];
$hotel_id          = $OrderObj->rooms[0]['hotel_id'] ?: $OrderObj->qrooms[0]['hotel_id'];
$room_name         = $OrderObj->rooms[0]['room_name'] ?: $OrderObj->qrooms[0]['room_name'];

$rooms = empty($OrderObj->rooms) ? $OrderObj->qrooms : $OrderObj->rooms;

//var_dump($rooms);
$hotel_info = $hotelHandler->get($hotel_id);
foreach ($rooms as $key => $room) {
    if (empty($key)) {
        $date = $room['room_date'];
    }
}
$promotion = $promotionHandler->getHotelPromotion($hotel_id, (int)$date);
//var_dump($promotion);

$services = $memberHandler->getOrderService($order_id, $hotel_id);
//var_dump($services);

$xoopsTpl->assign('user', $user);
$xoopsTpl->assign('pays', $pays);
$xoopsTpl->assign('order', $order);
$xoopsTpl->assign('order_id', $order_id);
$xoopsTpl->assign('room_id', $room_id);
$xoopsTpl->assign('hotel_id', $hotel_id);
$xoopsTpl->assign('rooms', $rooms);
$xoopsTpl->assign('hotel_name', $hotel_name);
$xoopsTpl->assign('hotel_info', $hotel_info);
$xoopsTpl->assign('room_name', $room_name);
$xoopsTpl->assign('promotion', $promotion);
$xoopsTpl->assign('services', $services);
$xoopsTpl->assign('order_document_type', $OrderDocumentType);
$xoopsTpl->assign('person_exchange_price', $xoopsUser->total_coupon());

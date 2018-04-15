<?php

use XoopsModules\Xmartin;

include  dirname(dirname(__DIR__)) . '/mainfile.php';
include XOOPS_ROOT_PATH . '/modules/martin/include/common.php';
if (!defined('MODULE_URL')) {
    define('MODULE_URL', XOOPS_URL . '/modules/martin/');
}

/** @var Xmartin\Helper $helper */
$helper = Xmartin\Helper::getInstance();
global $xoopsUser;
if (!$xoopsUser) {
    redirect_header(XOOPS_URL . '/user.php?xoops_redirect=/' . $_SERVER['REQUEST_URI'], 1, '您还没有登录.');
}

$cartHandler  = xoops_getModuleHandler('cart', 'martin');
$hotelHandler = xoops_getModuleHandler('hotel', 'martin');

$order_id  = \Xmf\Request::getInt('order_id', 0, 'GET');
$order_id  = \Xmf\Request::getInt('order_id', $order_id, 'POST');
$order_pay = isset($_POST['order_pay']) ? trim($_POST['order_pay']) : null;
if (!$order_id) {
    redirect_header(XOOPS_URL, 1, _AM_MARTIN_ILLEGAL_OPERATION);
}
if ($_POST && !$order_pay) {
    redirect_header('javascript:history.go(-1);', 1, _AM_MARTIN_NO_CHOICE_OF_PAYMENT);
}
$order_pay_method = is_numeric($order_pay) ? 2 : 1;

$order = $cartHandler->GetOrderInfo($order_id);
if (!$order) {
    redirect_header(XOOPS_URL, 1, _AM_MARTIN_ILLEGAL_OPERATION);
}
if ($cartHandler->CheckOrderClose($order_id)) {
    redirect_header(XOOPS_URL . '/hotel/', 1, _AM_MARTIN_ORDER_PAID);
}

//var_dump($order);

if ($order_id > 0 && !empty($order_pay) && $order_pay_method > 0) {
    if ($cartHandler->ChangeOrderPay($order_id, $order_pay_method, $order_pay)) {
        if (2 == $order_pay_method) {
            $msg        = _AM_MARTIN_ORDER_RECEIVED_BOOKING_AFTER_PAYMENT;
            $change_url = XOOPS_URL . '/hotel/';
        } else {
            $pay_file = MARTIN_ROOT_PATH . "pay/$order_pay/$order_pay.php";
            if (file_exists($pay_file)) {
                include $pay_file;
            }
            exit;
            $msg        = _AM_MARTIN_GOING_TO_PAYMENT_PAGE;
            $change_url = XOOPS_URL . '/hotel/';
        }
        redirect_header($change_url, 2, $msg);
    } else {
        redirect_header('javascript:history.go(-1);', 1, _AM_MARTIN_NO_CHOICE_OF_PAYMENT);
    }
}

//var_dump($order['order_pay']);
$GLOBALS['xoopsOption']['template_main'] = 'martin_hotel_pay.tpl';
include XOOPS_ROOT_PATH . '/header.php';
include XOOPS_ROOT_PATH . '/modules/martin/HotelSearchLeft.php';

$xoopsOption['xoops_pagetitle'] = _AM_MARTIN_PAYMENT_OPTIONS;// - '.$xoopsConfig['sitename'];
$xoopsTpl->assign('xoops_pagetitle', $xoopsOption['xoops_pagetitle']);
$xoopsTpl->assign('hotel_static_prefix', $helper->getConfig('hotel_static_prefix'));
$xoopsTpl->assign('module_url', MODULE_URL);
$xoopsTpl->assign('order_id', $order_id);
$xoopsTpl->assign('order_pay', $order['order_pay']);
$xoopsTpl->assign('order_pay_str', $order['order_pay_str']);
$xoopsTpl->assign('line_pays', getModuleArray('line_pays', 'line_pays', true));
$xoopsTpl->assign('online_pays', getModuleArray('online_pays', 'online_pays', true));

include XOOPS_ROOT_PATH . '/footer.php';

<?php
/**
 * @member    coupon list
 * @license   http://www.blags.org/
 * @created   :2010年07月15日 20时25分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */

use XoopsModules\Xmartin;

/** @var Xmartin\Helper $helper */
$helper = Xmartin\Helper::getInstance();

$hotelHandler  = xoops_getModuleHandler('hotel', 'martin');
$searchHandler = xoops_getModuleHandler('search', 'martin');
$memberHandler = xoops_getModuleHandler('member', 'martin');

$xoopsOption['xoops_pagetitle'] = '我的现金卷 - 用户中心';

$coupons      = $memberHandler->GetCouponList($start);
$coupon_types = getModuleArray('coupon_types', 'coupon_types', true);
//var_dump($hotels);
$Count = $coupons['count'];
unset($coupons['count']);
//分页
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
$pagenav = new \XoopsPageNav($Count, $helper->getConfig('front_perpage'), $start, 'start', '');

$xoopsTpl->assign('coupons', $coupons);
$xoopsTpl->assign('coupon_types', $coupon_types);
$xoopsTpl->assign('pagenav', $pagenav->MartinNav(4, MEMBER_URL . "?$action&amp;start="));

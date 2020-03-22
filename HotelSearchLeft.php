<?php

use XoopsModules\Xmartin;

global $xoopsModule, $xoopsTpl, $hotelHandler;

$isNewsModule = false;
//新闻
if ('xmartin' !== $xoopsModule->dirname()) {
    if (!is_array($aliasurl)) {
        $isNewsModule = true;
    }
    $xoopsModule = $moduleHandler->getByDirname('martin');
    //    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));
}

require_once XOOPS_ROOT_PATH . '/modules/xmartin/include/functions.php';

/** @var Xmartin\Helper $helper */
$helper = Xmartin\Helper::getInstance();

if (empty($hotelHandler)) {
    $hotelHandler = $helper->getHandler('Hotel');
}
//$roomHandler = $helper->getHandler("room");
//$promotionHandler = $helper->getHandler("hotelpromotion");
//$serviceHandler = $helper->getHandler("hotelservice");
$groupHandler   = $helper->getHandler('Group');
$auctionHandler = $helper->getHandler('Auction');
$newsHandler    = $helper->getHandler('Sews');

$ViewedhotelIDs = array_filter(explode(',', $_COOKIE['ViewedHotels']));
if (is_array($ViewedhotelArr)) {
    $ViewedHotels = [];
    foreach ($ViewedhotelArr as $key => $value) {
        $ViewedhotelIDs[$key]    = explode('||', $value);
        $ViewedhotelIDs[$key][1] = XOOPS_URL . urldecode($ViewedhotelIDs[$key][1]);
        $ViewedHotels[]          = $value;
        if (5 == $key) {
            break;
        }
    }
}

$hotel_guide         = explode(',', $helper->getConfig('hotel_guide'));
$hotel_today_special = explode(',', $helper->getConfig('hotel_today_special'));
$hotel_news_ids      = (is_array($hotel_guide) && is_array($hotel_today_special)) ? array_merge($hotel_guide, $hotel_today_special) : null;
$hotel_news_ids      = array_filter($hotel_news_ids);
$hotelnews           = $newsHandler->getHotelNews($hotel_news_ids);

$hotel_guide_rows         = [];
$hotel_today_special_rows = [];
foreach ($hotelnews as $key => $row) {
    if (in_array($key, $hotel_guide) && count($hotel_guide_rows) < 8) {
        $hotel_guide_rows[] = $row;
    }
    if (in_array($key, $hotel_today_special) && count($hotel_today_special_rows) < 8) {
        $hotel_today_special_rows[] = $row;
    }
}

ob_start();
$Tpl = new \XoopsTpl();
$Tpl->assign('module_url', XOOPS_URL . '/modules/xmartin/');
$Tpl->assign('hotelrank', getModuleArray('hotelrank', 'hotelrank', true));
$Tpl->assign('hotelrankcount', $hotelHandler->getHotelRankCount());
$Tpl->assign('ViewedHotels', $hotelHandler->getViewedHotels($ViewedhotelIDs));
$Tpl->assign('groupList', $groupHandler->getGroupList());
$Tpl->assign('auctionList', $auctionHandler->getAuctionList());
$Tpl->assign('hotel_guide_rows', $hotel_guide_rows);
$Tpl->assign('hotel_today_special_rows', $hotel_today_special_rows);
$Tpl->assign('cityList', $hotelHandler->getCityList('WHERE city_parentid = 0'));
$Tpl->assign('hotel_static_prefix', $helper->getConfig('hotel_static_prefix'));
$Tpl->assign('isNewsModule', $isNewsModule);
$Tpl->assign('module_config', $xoopsModuleConfig);
$Tpl->display('db:martin_hotel_search_left.tpl');
$xoopsTpl->assign('martin_hotel_search_left', ob_get_contents());

ob_end_clean();

unset($Tpl, $newsHandler, $hotel_guide_rows, $hotel_today_special_rows);

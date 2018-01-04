<?php
global $xoopsModuleConfig, $xoopsModule, $xoopsTpl, $hotelHandler;

$isNewsModule = false;
//新闻
if ('martin' !== $xoopsModule->dirname()) {
    if (!is_array($aliasurl)) {
        $isNewsModule = true;
    }
    $xoopsModule       = $moduleHandler->getByDirname('martin');
    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));
}

require_once XOOPS_ROOT_PATH . '/modules/martin/include/functions.php';
if (empty($hotelHandler)) {
    $hotelHandler = xoops_getModuleHandler('hotel', 'martin');
}
//$roomHandler = xoops_getModuleHandler("room", 'martin');
//$promotionHandler = xoops_getModuleHandler("hotelpromotion", 'martin');
//$serviceHandler = xoops_getModuleHandler("hotelservice", 'martin');
$groupHandler   = xoops_getModuleHandler('group', 'martin');
$auctionHandler = xoops_getModuleHandler('auction', 'martin');
$newsHandler    = xoops_getModuleHandler('hotelnews', 'martin');

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

$hotel_guide         = explode(',', $xoopsModuleConfig['hotel_guide']);
$hotel_today_special = explode(',', $xoopsModuleConfig['hotel_today_special']);
$hotel_news_ids      = (is_array($hotel_guide) && is_array($hotel_today_special)) ? array_merge($hotel_guide, $hotel_today_special) : null;
$hotel_news_ids      = array_filter($hotel_news_ids);
$hotelnews           = $newsHandler->GetHotelNews($hotel_news_ids);

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
$Tpl = new xoopsTpl();
$Tpl->assign('module_url', XOOPS_URL . '/modules/martin/');
$Tpl->assign('hotelrank', getModuleArray('hotelrank', 'hotelrank', true));
$Tpl->assign('hotelrankcount', $hotelHandler->getHotelRankCount());
$Tpl->assign('ViewedHotels', $hotelHandler->GetViewedHotels($ViewedhotelIDs));
$Tpl->assign('groupList', $groupHandler->GetGroupList());
$Tpl->assign('auctionList', $auctionHandler->GetAuctionList());
$Tpl->assign('hotel_guide_rows', $hotel_guide_rows);
$Tpl->assign('hotel_today_special_rows', $hotel_today_special_rows);
$Tpl->assign('cityList', $hotelHandler->GetCityList('WHERE city_parentid = 0'));
$Tpl->assign('hotel_static_prefix', $xoopsModuleConfig['hotel_static_prefix']);
$Tpl->assign('isNewsModule', $isNewsModule);
$Tpl->assign('module_config', $xoopsModuleConfig);
$Tpl->display('db:martin_hotel_search_left.tpl');
$xoopsTpl->assign('martin_hotel_search_left', ob_get_contents());

ob_end_clean();

unset($Tpl, $newsHandler, $hotel_guide_rows, $hotel_today_special_rows);

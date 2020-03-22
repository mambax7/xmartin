<?php

use XoopsModules\Xmartin;

include dirname(__DIR__) . '/preloads/autoloader.php';

/** @var \XoopsModules\Xmartin\Helper $helper */
$helper = \XoopsModules\Xmartin\Helper::getInstance();
$helper->loadLanguage('common');
$helper->loadLanguage('feedback');

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
if (is_object($helper->getModule())) {
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');
}

$adminmenu[] = [
    'title' => _MI_XMARTIN_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png',
];

$adminmenu[] = [
    'title' => _MI_XMARTIN_MENU_02,
    'link'  => 'admin/hotel.php',
    'icon'  => $pathModIcon32 . '/house_two.png',
];

$adminmenu[] = [
    'title' => _MI_XMARTIN_MENU_01,
    'link'  => 'admin/order.php',
    'icon'  => $pathModIcon32 . '/calendar-blue.png',
];

$adminmenu[] = [
    'title' => _MI_XMARTIN_MENU_03,
    'link'  => 'admin/service.php',
    'icon'  => $pathModIcon32 . '/account_functions.png',
];

$adminmenu[] = [
    'title' => _MI_XMARTIN_MENU_04,
    'link'  => 'admin/promotion.php',
    'icon'  => $pathIcon32 . '/fileshare.png',
];

$adminmenu[] = [
    'title' => _MI_XMARTIN_MENU_05,
    'link'  => 'admin/city.php',
    'icon'  => $pathModIcon32 . '/google_map_satellite.png',
];

$adminmenu[] = [
    'title' => _MI_XMARTIN_MENU_06,
    'link'  => 'admin/room.php',
    'icon'  => $pathModIcon32 . '/bed.png',
];

$adminmenu[] = [
    'title' => _MI_XMARTIN_MENU_07,
    'link'  => 'admin/group.php',
    'icon'  => $pathIcon32 . '/users.png',
];

$adminmenu[] = [
    'title' => _MI_XMARTIN_MENU_08,
    'link'  => 'admin/auction.php',
    'icon'  => $pathIcon32 . '/cash_stack.png',
];

// Blocks Admin
$adminmenu[] = [
    'title' => _MI_XMARTIN_BLOCKS,
    'link'  => 'admin/blocksadmin.php',
    'icon'  => $pathIcon32 . '/block.png',
];

$adminmenu[] = [
    'title' => _MI_XMARTIN_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png',
];

/*
global $adminObject;

$adminmenu = [];

$adminmenu[] = array("link" => "admin/index.php", "title" => "Home");

$adminmenu[] = array("link" => "admin/order.php", "title" => "Orders");

$adminmenu[] = array("link" => "admin/hotel.php", "title" => "Hotels");

$adminmenu[] = array("link" => "admin/service.php", "title" => "Services");

$adminmenu[] = array("link" => "admin/promotion.php", "title" => "Promotions");

$adminmenu[] = array("link" => "admin/city.php", "title" => "Cities");
//add by martin
$adminmenu[] = array("link" => "admin/room.php", "title" => "Rooms");

$adminmenu[] = array("link" => "admin/group.php", "title" => "Groups");

$adminmenu[] = array("link" => "admin/auction.php", "title" => "Auctions");

$adminmenu[] = array("link" => "admin/martin.about.php", "title" => "About");

if (isset($xoopsModule)) {
    $i                       = 0;
    $headermenu[$i]['title'] = "module parameter";
    $headermenu[$i]['link']  = '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar('mid');

    $i++;
    $headermenu[$i]['title'] = 'Reservation block management';
    $headermenu[$i]['link']  = 'block.php';

    $i++;
    $headermenu[$i]['title'] = "Update Module";
    $headermenu[$i]['link']  = XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&op=update&module=" . $xoopsModule->getVar('dirname');

    $i++;
    $headermenu[$i]['title'] = "Payment Configuration";
    $headermenu[$i]['link']  = XOOPS_URL . "/modules/xmartin/admin/pay.php";
}


*/

// misc: comments, synchronize, achive, batch import

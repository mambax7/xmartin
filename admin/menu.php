<?php

use XoopsModules\Xmartin;

require_once __DIR__ . '/../class/Helper.php';
//require_once __DIR__ . '/../include/common.php';
$helper = Xmartin\Helper::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');


$adminmenu[] = [
    'title' => _MI_MARTIN_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png'
];

$adminmenu[] = [
    'title' => _MI_MARTIN_MENU_02,
    'link'  => 'admin/martin.hotel.php',
    'icon'  => $pathModIcon32 . '/house_two.png'
];

$adminmenu[] = [
    'title' => _MI_MARTIN_MENU_01,
    'link'  => 'admin/martin.order.php',
    'icon'  => $pathModIcon32 . '/calendar-blue.png'
];

$adminmenu[] = [
    'title' => _MI_MARTIN_MENU_03,
    'link'  => 'admin/martin.hotel.service.php',
    'icon'  => $pathModIcon32 . '/account_functions.png'
];

$adminmenu[] = [
    'title' => _MI_MARTIN_MENU_04,
    'link'  => 'admin/martin.hotel.promotion.php',
    'icon'  => $pathIcon32 . '/fileshare.png'
];

$adminmenu[] = [
    'title' => _MI_MARTIN_MENU_05,
    'link'  => 'admin/martin.hotel.city.php',
    'icon'  => $pathModIcon32 . '/google_map_satellite.png'
];

$adminmenu[] = [
    'title' => _MI_MARTIN_MENU_06,
    'link'  => 'admin/martin.room.php',
    'icon'  => $pathModIcon32 . '/bed.png'
];

$adminmenu[] = [
    'title' => _MI_MARTIN_MENU_07,
    'link'  => 'admin/martin.group.php',
    'icon'  => $pathIcon32 . '/users.png'
];

$adminmenu[] = [
    'title' => _MI_MARTIN_MENU_08,
    'link'  => 'admin/martin.auction.php',
    'icon'  => $pathIcon32 . '/cash_stack.png'
];

$adminmenu[] = [
    'title' => _MI_MARTIN_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png'
];

/*
global $adminObject;

$adminmenu = array();

$adminmenu[] = array("link" => "admin/index.php", "title" => "Home");

$adminmenu[] = array("link" => "admin/martin.order.php", "title" => "Orders");

$adminmenu[] = array("link" => "admin/martin.hotel.php", "title" => "Hotels");

$adminmenu[] = array("link" => "admin/martin.hotel.service.php", "title" => "Services");

$adminmenu[] = array("link" => "admin/martin.hotel.promotion.php", "title" => "Promotions");

$adminmenu[] = array("link" => "admin/martin.hotel.city.php", "title" => "Cities");
//add by martin
$adminmenu[] = array("link" => "admin/martin.room.php", "title" => "Rooms");

$adminmenu[] = array("link" => "admin/martin.group.php", "title" => "Groups");

$adminmenu[] = array("link" => "admin/martin.auction.php", "title" => "Auctions");

$adminmenu[] = array("link" => "admin/martin.about.php", "title" => "About");

if (isset($xoopsModule)) {
    $i                       = 0;
    $headermenu[$i]['title'] = "module parameter";
    $headermenu[$i]['link']  = '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar('mid');

    $i++;
    $headermenu[$i]['title'] = 'Reservation block management';
    $headermenu[$i]['link']  = 'martin.block.php';

    $i++;
    $headermenu[$i]['title'] = "Update Module";
    $headermenu[$i]['link']  = XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&op=update&module=" . $xoopsModule->getVar('dirname');

    $i++;
    $headermenu[$i]['title'] = "Payment Configuration";
    $headermenu[$i]['link']  = XOOPS_URL . "/modules/martin/admin/martin.pay.php";
}


*/

// misc: comments, synchronize, achive, batch import

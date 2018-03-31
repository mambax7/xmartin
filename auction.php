<?php

use XoopsModules\Xmartin;
/** @var Xmartin\Helper $helper */
$helper = Xmartin\Helper::getInstance();

include __DIR__ . '/../../mainfile.php';
include XOOPS_ROOT_PATH . '/modules/martin/include/common.php';

global $xoopsUser;
if (!$xoopsUser) {
    redirect_header(XOOPS_URL . '/user.php?xoops_redirect=/' . $_SERVER['REQUEST_URI'], 1, '您还没有登录.');
}

$auction_id = \Xmf\Request::getInt('auction_id', $id, 'GET');
$auction_id = \Xmf\Request::getInt('auction_id', $auction_id, 'POST');
if (!$auction_id) {
    redirect_header(XOOPS_URL, 3, _AM_MARTIN_UNAUTHORIZED_ACCESS);
}

$hotelHandler   = xoops_getModuleHandler('hotel', 'martin');
$auctionHandler = xoops_getModuleHandler('auction', 'martin');
$auction_obj    = $auctionHandler->get($auction_id);

//保存数据
$action = isset($_GET['action']) ? trim($_GET['action']) : null;
global $xoopsUser;
$uid = $xoopsUser->getVar('uid');
if ('save' === $action) {
    $AuctionData = [
        'uid'            => (int)$uid,
        'auction_id'     => $auction_id,
        'bid_count'      => isset($_POST['RoomCount']) ? (int)trim($_POST['RoomCount']) : 0,
        'real_name'      => isset($_POST['user']['name']) ? trim($_POST['user']['name']) : '',
        'telephone'      => isset($_POST['user']['telephone']) ? trim($_POST['user']['telephone']) : '',
        'phone'          => isset($_POST['user']['phone']) ? trim($_POST['user']['phone']) : '',
        'bid_price'      => isset($_POST['AuctionPrice']) ? round(trim($_POST['AuctionPrice']), 2) : 0.00,
        'check_in_time'  => isset($_POST['Check_in_date']) ? strtotime(trim($_POST['Check_in_date'])) : 0,
        'check_out_time' => isset($_POST['Check_out_date']) ? strtotime(trim($_POST['Check_out_date'])) : 0,
        'bid_time'       => time(),
        'bid_status'     => 1,
    ];
    if ($auctionHandler->AddUserAuction($AuctionData)) {
        redirect_header(XOOPS_URL . '/modules/martin/auction.php/auction-' . $auction_id . $helper->getConfig('hotel_static_prefix'), 2, '提交成功.');
    } else {
        redirect_header('javascript:history.go(-1);', 2, '提交失败.');
    }
    exit();
}

//判断是否存在
if (!$auction_obj->auction_id()) {
    redirect_header(XOOPS_URL, 3, _AM_MARTIN_UNAUTHORIZED_ACCESS);
}
//是否结束
if ($auction_obj->apply_end_date() < time()) {
    redirect_header(XOOPS_URL, 3, '该竞拍已经结束.');
}

$auction_data = [];
foreach ($auction_obj->vars as $key => $var) {
    $auction_data[$key] = $auction_obj->$key();
}

$rooms     = $auctionHandler->GetAuctionRooms($auction_id);
$CityAlias = $searchHandler->GetCityAlias();
//var_dump($rooms);

// max user price
$auction_data['max'] = $auctionHandler->GetMaxAuctionPrice($auction_id);

//user
$xoopsUser->cleanVars();
$user =& $xoopsUser->cleanVars;

$AuctionDate = [
    'min' => (int)($auction_obj->check_in_date() - strtotime(date('Y-m-d'))) / (3600 * 24),
    'max' => (int)($auction_obj->check_out_date() - strtotime(date('Y-m-d'))) / (3600 * 24),
];
//var_dump($AuctionDate);

$AuctionStatus = time() < $auction_data['apply_start_date'] ? ['title' => '召集中', 'status' => false] : '';
$AuctionStatus = (time() <= $auction_data['apply_end_date']
                  && time() >= $auction_data['apply_start_date']) ? [
    'title'  => '进行中',
    'status' => true
] : $AuctionStatus;
$AuctionStatus = time() >= $auction_data['apply_end_date'] ? [
    'title'  => '已结束',
    'status' => false
] : $AuctionStatus;

$GLOBALS['xoopsOption']['template_main'] = 'martin_auction.tpl';

include XOOPS_ROOT_PATH . '/header.php';
include XOOPS_ROOT_PATH . '/modules/martin/HotelSearchLeft.php';

$xoopsOption['xoops_pagetitle'] = $auction_obj->auction_name() . ' - 竞拍';// - '.$xoopsConfig['sitename'];

$xoopsTpl->assign('xoops_pagetitle', $xoopsOption['xoops_pagetitle']);
$xoopsTpl->assign('module_url', XOOPS_URL . '/modules/martin/');
$xoopsTpl->assign('auction_id', $auction_id);
$xoopsTpl->assign('auction', $auction_data);
$xoopsTpl->assign('auctiondate', $AuctionDate);
$xoopsTpl->assign('rooms', $rooms);
$xoopsTpl->assign('AuctionStatus', $AuctionStatus);
$xoopsTpl->assign('user', $user);
$xoopsTpl->assign('alias', $CityAlias);
$xoopsTpl->assign('bids', $auctionHandler->getAuctionBidList($auction_id));
$xoopsTpl->assign('hotel_static_prefix', $helper->getConfig('hotel_static_prefix'));

include XOOPS_ROOT_PATH . '/footer.php';

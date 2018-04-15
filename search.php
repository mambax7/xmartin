<?php

use XoopsModules\Xmartin;

if (file_exists(__DIR__ . '/../../mainfile.php')) {
    require_once  dirname(dirname(__DIR__)) . '/mainfile.php';
}
include XOOPS_ROOT_PATH . '/modules/martin/include/common.php';

/** @var Xmartin\Helper $helper */
$helper = Xmartin\Helper::getInstance();

$searchHandler = xoops_getModuleHandler('search', 'martin');
$hotelHandler  = xoops_getModuleHandler('hotel', 'martin');

//var_dump($_GET);
//paramerters
$city_id = isset($city_id) ? $city_id : 0;
$city_id = \Xmf\Request::getInt('City', $city_id, 'GET');
//时间处理
$check_date = isset($_GET['CheckDate']) ? [
    strtotime($_GET['CheckDate'][0]),
    strtotime($_GET['CheckDate'][1])
] : null;
//$check_date[0] = $check_date[0] < time() ? time() : $check_date[0];
//$check_date[1] = $check_date[1] < time() ? time() : $check_date[1];
//$check_date[1] = $check_date[1] < $check_date[0] ? $check_date[0] : $check_date[1];
//时间处理
$price         = isset($_GET['price']) ? $_GET['price'] : null;
$hotel_address = isset($_GET['HotelAddress']) ? trim($_GET['HotelAddress']) : null;
$hotel_name    = isset($_GET['HotelName']) ? trim($_GET['HotelName']) : null;
$hotel_star    = \Xmf\Request::getInt('HotelStar', 0, 'GET');
$p             = \Xmf\Request::getInt('p', 0, 'GET');
$order         = isset($_GET['Order']) ? trim($_GET['Order']) : null;
$by            = isset($_GET['By']) ? strtoupper(trim($_GET['By'])) : null;
$by            = in_array($by, ['ASC', 'DESC']) ? $by : '';
//paramerters
//$helper->getConfig('perpage') = 2;
$Data      = [
    'city_id'       => $city_id,
    'check_date'    => $check_date,
    'price'         => $price,
    'hotel_address' => $hotel_address,
    'hotel_name'    => $hotel_name,
    'hotel_star'    => $hotel_star,
    'start'         => $p * $helper->getConfig('perpage'),
    'order'         => $order,
    'by'            => $by
];
$searchObj = $searchHandler->create();
$HotelData = $searchHandler->Search($Data);
//var_dump($searchHandler->hotel_ids);
$rooms = $searchHandler->GethotelRooms($check_date);

//$Hotel_Google = unserialize($HotelDatap['Hotel_Google']);
//list($HotelData['lat'],$HotelData['lng']) = $Hotel_Google;

//echo '<pre>';print_r($rooms);
//var_dump($HotelData);

$hotelrank    = getModuleArray('hotelrank', 'hotelrank', true);
$select_title = $city_id > 0 ? $searchHandler->GetCityName($city_id) : @$hotelrank[$hotel_star];
$select_title = empty($select_title) ? '所有' : $select_title;

$this_url = XOOPS_URL . '/modules/martin/search.php?' . $_SERVER['QUERY_STRING'];
$this_url = str_replace('&Order=' . $order, '', $this_url);
$this_url = str_replace('&By=' . strtolower($by), '', $this_url);
$this_url = str_replace('&p=' . strtolower($p), '', $this_url);

//分页处理
$total_p  = ceil($HotelData['count'] / $helper->getConfig('perpage'));
$prev_url = $p - 1;
$prev_url = $prev_url < 0 ? -1 : $prev_url;
$prev_url = $prev_url < 0 ? 0 : $this_url . '&amp;p=' . $prev_url;
$next_url = $p + 1;
$next_url = $next_url >= $total_p ? 0 : $next_url;
$next_url = 0 == $next_url ? $next_url : $this_url . '&amp;p=' . $next_url;

$GLOBALS['xoopsOption']['template_main'] = 'martin_hotel_search.tpl';

include XOOPS_ROOT_PATH . '/header.php';
include XOOPS_ROOT_PATH . '/modules/martin/HotelSearchLeft.php';

$xoopsOption['xoops_pagetitle'] = $select_title . ' - 酒店搜索预定';// - '.$xoopsConfig['sitename'];

$xoopsTpl->assign('check_in_date_count', (int)(($check_date[1] - $check_date[0]) / (3600 * 24)));
$xoopsTpl->assign('xoops_pagetitle', $xoopsOption['xoops_pagetitle']);
$xoopsTpl->assign('hotel_static_prefix', $helper->getConfig('hotel_static_prefix'));
$xoopsTpl->assign('check_date', isset($_GET['CheckDate']) ? $_GET['CheckDate'] : '');
$xoopsTpl->assign('check_in_date', strtotime(isset($_GET['CheckDate']) ? $_GET['CheckDate'][0] : ''));
$xoopsTpl->assign('check_out_date', strtotime(isset($_GET['CheckDate']) ? $_GET['CheckDate'][1] : ''));
$xoopsTpl->assign('module_url', XOOPS_URL . '/modules/martin/');
$xoopsTpl->assign('hotelrank', $hotelrank);
$xoopsTpl->assign('bedtype', getModuleArray('room_bed_type', 'room_bed_type', true));
$xoopsTpl->assign('select_title', $select_title);
$xoopsTpl->assign('count', $HotelData['count']);
unset($HotelData['count']);
$xoopsTpl->assign('hotels', $HotelData);
$xoopsTpl->assign('rooms', $rooms);
$xoopsTpl->assign('this_url', $this_url);
$xoopsTpl->assign('prev_url', $prev_url);
$xoopsTpl->assign('next_url', $next_url);
$xoopsTpl->assign('by', $by);
$xoopsTpl->assign('order', $order);
$xoopsTpl->assign('this_by', 'DESC' === $by ? 'asc' : 'desc');
$xoopsTpl->assign('check_date_str', "?check_in_date={$check_date[0]}&amp;check_out_date={$check_date[1]}");
$xoopsTpl->assign('googleApi', $helper->getConfig('google_api'));
$xoopsTpl->assign('google_w_h', array_filter(explode('|', $helper->getConfig('google_width_height'))));
$xoopsTpl->assign('module_config', $xoopsModuleConfig); //TODO
$xoopsTpl->assign('isModule', 1);

include XOOPS_ROOT_PATH . '/footer.php';

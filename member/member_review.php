<?php

/**
 * @member    review
 * @license   http://www.blags.org/
 * @created   :2010年07月15日 20时25分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
$hotelHandler  = $helper->getHandler('Hotel');
$searchHandler = $helper->getHandler('Search');
$memberHandler = $helper->getHandler('Member');
$reviewHandler = $helper->getHandler('Review');

//$hotel_id = isset($_GET['hotel_id']) ? (int)$_GET['hotel_id'] : (int)$_POST['hotel_id'];
$hotel_id = \Xmf\Request::getInt('hotel_id', 0);
$hotel    = $hotelHandler->get($hotel_id);
if (!$hotel_id || !$hotel) {
    redirect_header('javascript:history.go(-1);', 2, '该酒店不存在.');
}

$xoopsOption['xoops_pagetitle'] = '点评 - ' . $hotel->hotel_name() . ' - 用户中心';

if (is_array($_POST) && !empty($_POST)) {
    $review_type     = $_POST['review_type'];
    $review_purpose  = \Xmf\Request::getInt('review_purpose', 0, 'POST');
    $review_content  = trim($_POST['review_content']);
    $review_type_avg = round(array_sum($review_type) / count($review_type), 2);
    $uid             = $xoopsUser->uid();
    $Data            = [
        'hotel_id'        => $hotel_id,
        'uid'             => $uid,
        'review_type_1'   => $review_type[1],
        'review_type_2'   => $review_type[2],
        'review_type_3'   => $review_type[3],
        'review_type_4'   => $review_type[4],
        'review_type_avg' => $review_type_avg,
        'review_purpose'  => $review_purpose,
        'review_content'  => "'" . $review_content . "'",
        'submit_time'     => time(),
    ];
    //var_dump($review_type);exit;
    if ($reviewHandler->saveReview($Data)) {
        redirect_header(MEMBER_URL . '?lived', 2, '点评成功.');
    }
}
//$hotels = $memberHandler->getHotelList($start);
//var_dump($hotels);
//$Count = $hotels['count'];
//unset($hotels['count']);
//分页
$xoopsTpl->assign('review', $reviewHandler->getReview($hotel_id));
$xoopsTpl->assign('hotel', $hotel);
$xoopsTpl->assign('hotel_review_type', getModuleArray('hotel_review_type', 'hotel_review_type', true));
$xoopsTpl->assign('hotel_trip_purpose', getModuleArray('hotel_trip_purpose', 'hotel_trip_purpose', true));

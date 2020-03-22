<?php

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

require_once __DIR__ . '/preloads/autoloader.php';

$moduleDirName      = basename(__DIR__);
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

$modversion = [
    'version'       => 1.0,
    'module_status' => 'Beta 1',
    'release_date'  => '2017/07/19',
    'name'          => _MI_XMARTIN_NAME,
    'description'   => _MI_XMARTIN_DESC,
    'credits'       => 'The Xoops Project, The WF-projects, The martin Community',
    'image'         => 'assets/images/logoModule.png',
    'author'        => 'Martin',
    'help'          => 'http://www.blags.org',
];

$modversion['help']        = 'page=help';
$modversion['license']     = 'GNU GPL 2.0 or later';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html';

$moduleDirName         = basename(__DIR__);
$modversion['dirname'] = $moduleDirName;
//$modversion['name'] = ucfirst(basename(__DIR__)); //no, because if somebody wants to clone, it will not work?

//$modversion['dirmoduleadmin'] = 'Frameworks/moduleclasses/moduleadmin';
//$modversion['sysicons16']     = 'Frameworks/moduleclasses/icons/16';
//$modversion['sysicons32']     = 'Frameworks/moduleclasses/icons/32';
$modversion['modicons16'] = 'assets/images/icons/16';
$modversion['modicons32'] = 'assets/images/icons/32';

$modversion['module_website_url']  = 'www.xoops.org';
$modversion['module_website_name'] = 'XOOPS';
$modversion['min_php']             = '5.6';
$modversion['min_xoops']           = '2.5.10';
$modversion['min_admin']           = '1.2';
$modversion['min_db']              = ['mysql' => '5.5'];

$modversion['sqlfile']['mysql'] = 'sql/martin_2.0.sql';

$modversion['tables'] = [
     $moduleDirName . '_' . 'auction_bid',
     $moduleDirName . '_' . 'auction_room',
     $moduleDirName . '_' . 'auction',
     $moduleDirName . '_' . 'group_join',
     $moduleDirName . '_' . 'group_room',
     $moduleDirName . '_' . 'group',
     $moduleDirName . '_' . 'hotel_city',
     $moduleDirName . '_' . 'hotel_promotions',
     $moduleDirName . '_' . 'hotel_service_relation',
     $moduleDirName . '_' . 'hotel_service_type',
     $moduleDirName . '_' . 'hotel_service',
     $moduleDirName . '_' . 'hotel',
     $moduleDirName . '_' . 'order_query_room',
     $moduleDirName . '_' . 'order_room',
     $moduleDirName . '_' . 'order_service',
     $moduleDirName . '_' . 'order',
     $moduleDirName . '_' . 'room_price',
     $moduleDirName . '_' . 'room_type',
     $moduleDirName . '_' . 'room',
];

// Admin things
$modversion['system_menu'] = 1;
$modversion['hasAdmin']    = 1;
$modversion['adminindex']  = 'admin/index.php';
$modversion['adminmenu']   = 'admin/menu.php';

//是否有前台
$modversion['hasMain'] = 1;

// ------------------- Help files ------------------- //
$modversion['helpsection'] = [
    ['name' => _MI_XMARTIN_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_XMARTIN_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_XMARTIN_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_XMARTIN_SUPPORT, 'link' => 'page=support'],
];

//blocks
$modversion['blocks'] = [];
$i                    = 0;
$i++;
$modversion['blocks'][$i]['file']        = 'hotel.php';
$modversion['blocks'][$i]['name']        = 'hotel search';
$modversion['blocks'][$i]['description'] = 'hotel Search';
$modversion['blocks'][$i]['show_func']   = 'martin_hotel_search_show';
$modversion['blocks'][$i]['edit_func']   = 'martin_hotel_search_edit';
$modversion['blocks'][$i]['options']     = '';
$modversion['blocks'][$i]['template']    = 'martin_block_hotel.tpl';

//blocks

//templates
$i = 0;
$i++;
$modversion['templates'][$i]['file']        = 'martin_admin_menu.tpl';
$modversion['templates'][$i]['description'] = 'admin menu in martin';
$i++;
$modversion['templates'][$i]['file']        = 'martin_auction.tpl';
$modversion['templates'][$i]['description'] = 'martin auction';
$i++;
$modversion['templates'][$i]['file']        = 'martin_group.tpl';
$modversion['templates'][$i]['description'] = 'martin group';
$i++;
$modversion['templates'][$i]['file']        = 'martin_hotel.tpl';
$modversion['templates'][$i]['description'] = 'martin hotel';
$i++;
$modversion['templates'][$i]['file']        = 'martin_hotel_book.tpl';
$modversion['templates'][$i]['description'] = 'martin hotel book';
$i++;
$modversion['templates'][$i]['file']        = 'martin_hotel_pay.tpl';
$modversion['templates'][$i]['description'] = 'martin hotel pay';
$i++;
$modversion['templates'][$i]['file']        = 'martin_hotel_search.tpl';
$modversion['templates'][$i]['description'] = 'martin hotel search';
$i++;
$modversion['templates'][$i]['file']        = 'martin_hotel_search_left.tpl';
$modversion['templates'][$i]['description'] = 'martin search left';

//-----------------member-------------//
$i++;
$modversion['templates'][$i]['file']        = 'martin_member_index.tpl';
$modversion['templates'][$i]['description'] = 'martin member index';
$i++;
$modversion['templates'][$i]['file']        = 'martin_member_info.tpl';
$modversion['templates'][$i]['description'] = 'martin member info';
$i++;
$modversion['templates'][$i]['file']        = 'martin_member_order.tpl';
$modversion['templates'][$i]['description'] = 'martin member order';
$i++;
$modversion['templates'][$i]['file']        = 'martin_member_order_info.tpl';
$modversion['templates'][$i]['description'] = 'martin member order info';
$i++;
$modversion['templates'][$i]['file']        = 'martin_member_lived.tpl';
$modversion['templates'][$i]['description'] = 'martin member lived';
$i++;
$modversion['templates'][$i]['file']        = 'martin_member_review.tpl';
$modversion['templates'][$i]['description'] = 'martin member review';
$i++;
$modversion['templates'][$i]['file']        = 'martin_member_favorite.tpl';
$modversion['templates'][$i]['description'] = 'martin member favorite';
$i++;
$modversion['templates'][$i]['file']        = 'martin_member_coupon.tpl';
$modversion['templates'][$i]['description'] = 'martin member coupon';
$i++;
$modversion['templates'][$i]['file']        = 'martin_member_order_modify.tpl';
$modversion['templates'][$i]['description'] = 'martin member order modify';
//-----------------member-------------//

//-----------------martin_redirect-------------//
$i++;
$modversion['templates'][$i]['file']        = 'martin_redirect.tpl';
$modversion['templates'][$i]['description'] = 'martin redirect';

//------------------find book--------------------//
$i++;
$modversion['templates'][$i]['file']        = 'martin_hotel_find_book.tpl';
$modversion['templates'][$i]['description'] = 'martin hotel find book';

$i++;
$modversion['templates'][$i]['file']        = 'martin_hotel_search_city.tpl';
$modversion['templates'][$i]['description'] = 'martin search city';

//end templates

// module Settings
$i = 0;
$i++;
$modversion['config'][$i]['name']        = 'google_api';
$modversion['config'][$i]['title']       = 'XMARTIN_GOOGLE_API_TITLE';
$modversion['config'][$i]['description'] = 'XMARTIN_GOOGLE_API_DESCRIPTION';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '';
//$modversion['config'][$i]['options'] = '';
$i++;
$modversion['config'][$i]['name']        = 'perpage';
$modversion['config'][$i]['title']       = 'XMARTIN_PERPAGE_TITLE';
$modversion['config'][$i]['description'] = 'XMARTIN_PERPAGE_DESCRIPTION';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '20';
$i++;
$modversion['config'][$i]['name']        = 'hotelrank';
$modversion['config'][$i]['title']       = 'XMARTIN_HOTELRANK_TITLE';
$modversion['config'][$i]['description'] = 'XMARTIN_HOTELRANK_DESCRIPTION';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_XMARTIN_HOTEL_STAR3 . chr(13) . _MI_XMARTIN_HOTEL_STAR4 . chr(13) . _MI_XMARTIN_HOTEL_STAR5;
$i++;
$modversion['config'][$i]['name']        = 'thumbnail_width';
$modversion['config'][$i]['title']       = 'XMARTIN_THUMBNAIL_WIDTH_TITLE';
$modversion['config'][$i]['description'] = 'XMARTIN_THUMBNAIL_WIDTH_DESCRIPTION';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '1000';
$i++;
$modversion['config'][$i]['name']        = 'thumbnail_height';
$modversion['config'][$i]['title']       = 'XMARTIN_THUMBNAIL_HEIGHT_TITLE';
$modversion['config'][$i]['description'] = 'XMARTIN_THUMBNAIL_HEIGHT_DESCRIPTION';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '1000';
$i++;
$modversion['config'][$i]['name']        = 'order_type';
$modversion['config'][$i]['title']       = 'XMARTIN_ORDER_TYPE_TITLE';
$modversion['config'][$i]['description'] = 'XMARTIN_ORDER_TYPE_DESCRIPTION';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_XMARTIN_ORDER_TYPE1 . chr(13) . _MI_XMARTIN_ORDER_TYPE2;
$i++;
$modversion['config'][$i]['name']        = 'order_mode';
$modversion['config'][$i]['title']       = 'XMARTIN_ORDER_MODE_TITLE';
$modversion['config'][$i]['description'] = 'XMARTIN_ORDER_MODE_DESCRIPTION';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_XMARTIN_ORDER_MODE1 . chr(13) . _MI_XMARTIN_ORDER_MODE2 . chr(13) . _MI_XMARTIN_ORDER_MODE3;
$i++;
$modversion['config'][$i]['name']        = 'order_status';
$modversion['config'][$i]['title']       = 'XMARTIN_ORDER_STATUS_TITLE';
$modversion['config'][$i]['description'] = 'XMARTIN_ORDER_STATUS_INTRO';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_XMARTIN_ORDER_STATUS1 . chr(13) . _MI_XMARTIN_ORDER_STATUS2 . chr(13) . _MI_XMARTIN_ORDER_STATUS3;
$i++;
$modversion['config'][$i]['name']        = 'order_pay_method';
$modversion['config'][$i]['title']       = 'XMARTIN_PAY_METHOD_TITLE';
$modversion['config'][$i]['description'] = 'XMARTIN_PAY_METHOD_INTRO';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_XMARTIN_PAY_METHOD1 . chr(13) . _MI_XMARTIN_PAY_METHOD2 . chr(13) . _MI_XMARTIN_PAY_METHOD3;
$i++;
$modversion['config'][$i]['name']        = 'order_document_type';
$modversion['config'][$i]['title']       = 'XMARTIN_DOCUMENT_TYPE_TITLE';
$modversion['config'][$i]['description'] = 'XMARTIN_DOCUMENT_TYPE_INTRO';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '';
$i++;
$modversion['config'][$i]['name']      = 'hotel_static_prefix';
$modversion['config'][$i]['title']     = 'XMARTIN_HOTELSTATIC_TITLE';
$modversion['config'][$i]['formtype']  = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default']   = '.tpl';
$i++;
$modversion['config'][$i]['name']        = 'room_bed_type';
$modversion['config'][$i]['title']       = 'XMARTIN_BEDTYPE_TITLE';
$modversion['config'][$i]['description'] = 'XMARTIN_BEDTYPE_DESCRIPTION';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_XMARTIN_BED_TYPE1 . chr(13) . _MI_XMARTIN_BED_TYPE2 . chr(13) . _MI_XMARTIN_BED_TYPE3;
$i++;
$modversion['config'][$i]['name']        = 'user_nationality';
$modversion['config'][$i]['title']       = 'XMARTIN_NATIONALITY_TITLE';
$modversion['config'][$i]['description'] = 'XMARTIN_NATIONALITY_DESCRIPTION';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_XMARTIN_CUSTOMER_NATIONALITY1 . chr(13) . _MI_XMARTIN_CUSTOMER_NATIONALITY2 . chr(13) . _MI_XMARTIN_CUSTOMER_NATIONALITY3;
$i++;
$modversion['config'][$i]['name']        = 'hotel_recommend';
$modversion['config'][$i]['title']       = 'XMARTIN_RECOMMEND_TITLE';
$modversion['config'][$i]['description'] = 'XMARTIN_RECOMMEND_DESCRIPTION';
$modversion['config'][$i]['formtype']    = 'text';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '';
$i++;
$modversion['config'][$i]['name']      = 'register_point';
$modversion['config'][$i]['title']     = 'XMARTIN_REGISTER_POINT';
$modversion['config'][$i]['formtype']  = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default']   = '';
$i++;
$modversion['config'][$i]['name']      = 'hotel_guide';
$modversion['config'][$i]['title']     = 'XMARTIN_HOTEL_GUIDE';
$modversion['config'][$i]['formtype']  = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default']   = '';
$i++;
$modversion['config'][$i]['name']      = 'hotel_today_special';
$modversion['config'][$i]['title']     = 'XMARTIN_TODAY_SPECIAL';
$modversion['config'][$i]['formtype']  = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default']   = '';
$i++;
$modversion['config'][$i]['name']        = 'online_pays';
$modversion['config'][$i]['title']       = 'XMARTIN_ONLINE_PAY';
$modversion['config'][$i]['description'] = 'XMARTIN_ONLINE_PAY_DESCRIPTION';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '';
$i++;
$modversion['config'][$i]['name']        = 'line_pays';
$modversion['config'][$i]['title']       = 'XMARTIN_LINE_PAY';
$modversion['config'][$i]['description'] = 'XMARTIN_LINE_PAY_DESCRIPTION';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '';
$i++;
$modversion['config'][$i]['name']      = 'google_width_height';
$modversion['config'][$i]['title']     = 'XMARTIN_GOOGLE_WIDTH_HEIGHT';
$modversion['config'][$i]['formtype']  = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default']   = '750|400';

$i++;
$modversion['config'][$i]['name']        = 'coupon_types';
$modversion['config'][$i]['title']       = 'XMARTIN_COUPON_TYPES';
$modversion['config'][$i]['description'] = 'XMARTIN_COUPON_DESCRIPTION';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '';
//------------member-----------//
$i++;
$modversion['config'][$i]['name']        = 'hotel_review_type';
$modversion['config'][$i]['title']       = 'XMARTIN_HOTELREVIEW_TYPE';
$modversion['config'][$i]['description'] = 'XMARTIN_REVIEW_DESCRIPTION';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '';
$i++;
$modversion['config'][$i]['name']        = 'hotel_trip_purpose';
$modversion['config'][$i]['title']       = 'XMARTIN_TRIP_PURPOSE';
$modversion['config'][$i]['description'] = 'XMARTIN_PURPOSE_DESCRIPTION';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '';

//------------member-----------//
$i++;
$modversion['config'][$i]['name']  = 'online_url';
$modversion['config'][$i]['title'] = 'XMARTIN_ONLINEURL_TITLE';
//$modversion['config'][$i]['description'] = 'XMARTIN_ONLINEURL_DESCRIPTION';
$modversion['config'][$i]['formtype']  = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default']   = 'http://chat.53kf.com/company.php?arg=gjlmo&style=1';
//$modversion['config'][$i]['options'] = '';
$i++;
$modversion['config'][$i]['name']      = 'front_perpage';
$modversion['config'][$i]['title']     = 'XMARTIN_FRONT_PERPAGE_TITLE';
$modversion['config'][$i]['formtype']  = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default']   = '10';
$i++;
$modversion['config'][$i]['name']      = 'order_send_email';
$modversion['config'][$i]['title']     = 'XMARTIN_SEND_EMAIL';
$modversion['config'][$i]['formtype']  = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default']   = '';

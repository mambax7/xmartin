<?php

use XoopsModules\Xmartin;
/** @var Xmartin\Helper $helper */
$helper = Xmartin\Helper::getInstance();

require_once __DIR__ . '/admin_header.php';
/*
 * 处理
 **/

//头部
include __DIR__ . '/martin.header.php';
$currentFile   = basename(__FILE__);
$myModuleAdmin = \Xmf\Module\Admin::getInstance();
echo $myModuleAdmin->displayNavigation($currentFile);

//parameter 参数
$action    = isset($_POST['action']) ? $_POST['action'] : @$_GET['action'];
$action    = empty($action) ? 'list' : $action;
$action    = trim(strtolower($action));
$id        = !empty($_POST['id']) ? $_POST['id'] : @$_GET['id'];
$id        = (int)$id;
$room_id   = isset($_GET['room_id']) ? (int)$_GET['room_id'] : 0;
$room_date = isset($_GET['room_date']) ? trim($_GET['room_date']) : 0;
$hotel_id  = isset($_GET['hotel_id']) ? (int)$_GET['hotel_id'] : 0;
$typeid    = isset($_GET['typeid']) ? (int)$_GET['typeid'] : 0;
$start     = isset($_GET['start']) ? (int)$_GET['start'] : 0;
//确认删除

$confirm = isset($_POST['confirm']) ? $_POST['confirm'] : 0;
//parameter 参数
// martin_adminMenu(6, "订房后台 > 客房管理");

$roomHandler         = xoops_getModuleHandler('room', MARTIN_DIRNAME, true);
$hotelserviceHandler = xoops_getModuleHandler('hotelservice', MARTIN_DIRNAME, true);

//$HotelServiceObj = $hotelserviceHandler->create();
$RoomObj = $id > 0 ? $roomHandler->get($id) : $roomHandler->create();

switch ($action) {
    case 'add':
        include MARTIN_ROOT_PATH . 'include/form.room.php';
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_ADD_ROOM, _AM_MARTIN_ADD_ROOM);
        CreateButton();
        $TypeList  = $roomHandler->getRoomTypeList();
        $hotelList = $hotelserviceHandler->getHotelList($hotel_id);
        $form      = new form_room($RoomObj, $hotelList, $TypeList);
        $form->display();
        martin_close_collapsable('createtable', 'createtableicon');
        break;
    case 'typeadd':
        include MARTIN_ROOT_PATH . 'include/form.room.type.php';
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_ADD_ROOM_CATEGORIES, _AM_MARTIN_ADD_ROOM_CATEGORIES);
        CreateButton();
        $roomType = [];
        if ($typeid > 0) {
            $roomType = $roomHandler->getRoomTypeList($typeid);
            $roomType = ['room_type_id' => $typeid, 'room_type_info' => $roomType[$typeid]];
        }
        $form = new form_room_type($roomType);
        $form->display();
        martin_close_collapsable('createtable', 'createtableicon');
        break;
    case 'addprice':
        include MARTIN_ROOT_PATH . 'include/form.room.price.php';
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_ADDING_RATES, _AM_MARTIN_ADDING_RATES);
        CreateButton();
        $room_date = isset($_GET['room_date']) ? trim($_GET['room_date']) : null;
        $RoomPrice = ($room_id > 0 && $room_date) ? $roomHandler->getRoomPrice($room_id, $room_date) : [];
        $RoomPrice = ($room_id > 0 && empty($RoomPrice)) ? $roomHandler->getRoomPrice($room_id) : $RoomPrice;
        $RoomList  = $roomHandler->getRoomList($room_id);
        //var_dump($RoomPrice);
        $form = new form_room_price($RoomPrice, $RoomList);
        $form->display();
        martin_close_collapsable('createtable', 'createtableicon');
        break;
    case 'save':
        $RoomObj->setVar('room_id', $id);
        $RoomObj->setVar('room_type_id', isset($_POST['room_type_id']) ? (int)$_POST['room_type_id'] : 0);
        $RoomObj->setVar('hotel_id', isset($_POST['hotel_id']) ? (int)$_POST['hotel_id'] : 0);
        $RoomObj->setVar('room_count', isset($_POST['room_count']) ? (int)$_POST['room_count'] : 0);
        $RoomObj->setVar('room_bed_type', isset($_POST['room_bed_type']) ? (int)$_POST['room_bed_type'] : 0);
        $RoomObj->setVar('room_name', isset($_POST['room_name']) ? addslashes($_POST['room_name']) : '');
        $RoomObj->setVar('room_area', isset($_POST['room_area']) ? (int)$_POST['room_area'] : 0);
        $RoomObj->setVar('room_floor', isset($_POST['room_floor']) ? addslashes($_POST['room_floor']) : '');
        $RoomObj->setVar('room_initial_price', isset($_POST['room_initial_price']) ? round($_POST['room_initial_price'], 2) : 0);
        $RoomObj->setVar('room_is_add_bed', isset($_POST['room_is_add_bed']) ? (int)$_POST['room_is_add_bed'] : 0);
        $RoomObj->setVar('room_add_money', isset($_POST['room_add_money']) ? (int)$_POST['room_add_money'] : 0);
        $RoomObj->setVar('room_bed_info', isset($_POST['room_bed_info']) ? addslashes($_POST['room_bed_info']) : '');
        $RoomObj->setVar('room_status', isset($_POST['room_status']) ? (int)$_POST['room_status'] : 0);
        $RoomObj->setVar('room_sented_coupon', isset($_POST['room_sented_coupon']) ? round($_POST['room_sented_coupon'], 2) : 0);
        if (!$id) {
            $RoomObj->setNew();
        }
        if ($RoomObj->isNew()) {
            $redirect_msg = _AM_MARTIN_ADDED_SUCCESSFULLY;
        } else {
            $redirect_msg = _AM_MARTIN_MODIFIED_SUCCESSFULLY;
        }
        $redirect_to = 'martin.room.php?action=list';
        if ($roomHandler->CheckHotelRoomExist($RoomObj)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_MARTIN_HOTEL_ADDED_TO_ROOM);
        }
        if (!$roomHandler->insert($RoomObj)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_MARTIN_OPERATION_FAILED);
        }
        redirect_header($redirect_to, 2, $redirect_msg);
        break;
    case 'typesave':
        $typeData = ['room_type_id' => $typeid, 'room_type_info' => trim($_POST['room_type_info'])];

        if (!$typeid) {
            $redirect_msg = _AM_MARTIN_ADDED_SUCCESSFULLY;
        } else {
            $redirect_msg = _AM_MARTIN_MODIFIED_SUCCESSFULLY;
        }
        $redirect_to = 'martin.room.php?action=typelist';
        if (!$roomHandler->insertType($typeData)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_MARTIN_OPERATION_FAILED);
        }
        redirect_header($redirect_to, 2, $redirect_msg);
        break;
    case 'pricesave':
        $room_prices                = $_POST['room_price'];
        $room_is_totay_specials     = $_POST['room_is_today_special'];
        $room_advisory_range_smalls = $_POST['room_advisory_range_small'];
        $room_advisory_range_maxs   = $_POST['room_advisory_range_max'];
        $room_sented_coupons        = $_POST['room_sented_coupon'];
        $room_dates                 = $_POST['room_date'];
        //var_dump($_POST['room_is_today_special']);exit;

        $Data = [];
        foreach ($room_prices as $key => $room_price) {
            $dateTime = strtotime($room_dates[$key]);
            $Data[]   = [
                'room_id'                   => (int)$_POST['room_id'],
                'room_price'                => $room_prices[$key],
                'room_is_today_special'     => isset($room_is_totay_specials[$dateTime]) ? (int)$room_is_totay_specials[$dateTime] : 0,
                'room_advisory_range_small' => round($room_advisory_range_smalls[$key], 2),
                'room_advisory_range_max'   => round($room_advisory_range_maxs[$key], 2),
                'room_sented_coupon'        => round($room_sented_coupons[$key], 2),
                'room_date'                 => strtotime($room_dates[$key])
            ];
        }

        $IsOld        = false;
        $redirect_msg = _AM_MARTIN_ADDED_SUCCESSFULLY;
        if ($room_id && $room_date) {
            $IsOld        = true;
            $redirect_msg = _AM_MARTIN_MODIFIED_SUCCESSFULLY;
            $Data         = [
                'room_id'                   => $room_id,
                'room_price'                => (int)$_POST['room_price'],
                'room_is_today_special'     => (int)$_POST['room_is_today_special'],
                'room_advisory_range_small' => round($_POST['room_advisory_range_small'], 2),
                'room_advisory_range_max'   => round($_POST['room_advisory_range_max'], 2),
                'room_sented_coupon'        => round($_POST['room_sented_coupon'], 2),
                'room_date'                 => strtotime($room_date)
            ];
        }
        $redirect_to = 'martin.room.php?action=pricelist';

        //var_dump($IsOld);
        //var_dump($Data);exit;

        if (!$roomHandler->InsertRoomPrice($Data, $IsOld)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_MARTIN_OPERATION_FAILED . '<br>' . _AM_MARTIN_ERROR_DUPLICATION);
        }
        redirect_header($redirect_to, 2, $redirect_msg);

        break;
    case 'del':
        if (!$confirm) {
            xoops_confirm(['op' => 'del', 'id' => $id, 'confirm' => 1, 'name' => $RoomObj->room_name()], '?action=del', __DELETE . " '" . $RoomObj->room_name() . "'. <br> <br> " . _AM_MARTIN_OK_TO_DELETE_ROOM, _DELETE);
        } else {
            if ($roomHandler->delete($RoomObj)) {
                $redirect_msg = _AM_MARTIN_OK_TO_DELETE_THE_ORDER;
                $redirect_to  = 'martin.room.php';
            } else {
                $redirect_msg = _AM_MARTIN_DELETE_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }
        break;
    case 'typedel':
        $roomType = $roomHandler->getRoomTypeList($typeid);
        if (!$confirm) {
            xoops_confirm(['op' => 'del', 'typeid' => $typeid, 'confirm' => 1, 'name' => $roomType[$typeid]], '?action=typedel&typeid=' . $typeid, __DELETE . " '" . $roomType[$typeid] . "'. <br> <br> " . _AM_MARTIN_OK_TO_DELETE_ROOM_CATEGORY, _DELETE);
        } else {
            if ($roomHandler->deleteRoomType($typeid)) {
                $redirect_msg = _AM_MARTIN_OK_TO_DELETE_THE_ORDER;
                $redirect_to  = 'martin.room.php?action=typelist';
            } else {
                $redirect_msg = _AM_MARTIN_DELETE_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }
        break;
    case 'pricedel':
        $RoomPrice = ($room_id > 0 && $room_date) ? $roomHandler->getRoomPrice($room_id, $room_date) : [];
        if (!$confirm) {
            xoops_confirm([
                              'op'       => 'del',
                              'hotel_id' => $hotel_id,
                              'confirm'  => 1,
                              'name'     => $RoomPrice['room_name']
                          ], "?action=pricedel&room_id=$room_id&room_date=" . date('Y-m-d', $RoomPrice['room_date']), _DELETE . " '" . $RoomPrice['room_name'] . ' : ' . date('Y-m-d', $RoomPrice['room_date']) . "'. <br> <br> " . _AM_MARTIN_OK_TO_DELETE_PRICE, _DELETE);
        } else {
            if ($roomHandler->deleteRoomPrice($room_id, date('Y-m-d', $RoomPrice['room_date']))) {
                $redirect_msg = _AM_MARTIN_OK_TO_DELETE_THE_ORDER;
                $redirect_to  = 'martin.room.php?action=pricelist';
            } else {
                $redirect_msg = _AM_MARTIN_DELETE_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }
        break;
    case 'deletepassdata':
        if (!$confirm) {
            xoops_confirm(['op' => 'del', 'hotel_id' => $hotel_id, 'confirm' => 1], '?action=deletepassdata', _AM_MARTIN_OK_TO_DELETE_DATA_NO_WAY_BACK, _DELETE);
        } else {
            if ($roomHandler->TruncatePassData($date)) {
                $redirect_msg = _AM_MARTIN_CLEARING_SUCCESSFUL;
                $redirect_to  = 'martin.room.php?action=pricelist';
            } else {
                $redirect_msg = _AM_MARTIN_CLEARING_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }
        break;
    case 'list':
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_ROOMS_LIST, _AM_MARTIN_ROOMS_LIST);
        CreateButton();
        $RoomObjs = $roomHandler->getRooms($helper->getConfig('perpage'), $start, 0);
        $Cout     = $roomHandler->getCount();
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new \XoopsPageNav($Cout, $helper->getConfig('perpage'), $start, 'action=' . $action . '&start');
        $pavStr  = '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

        echo $pavStr . "<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
        echo '<tr>';
        echo "<td class='bg3' align='left'><b>ID</b></td>";
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_ROOM_CATEGORY_NAME . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_HOTEL_NAME . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_ROOM_COUNT . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_ROOM_AREA . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_ROOM_FLOOR . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_BUY_PRICE . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_CASH . '</b></td>';
        echo "<td width='100' class='bg3' align='center'><b>" . _AM_MARTIN_ACTIONS . '</b></td>';
        echo '</tr>';
        $Status = [
            '<div style="background-color:#FF0000">' . _AM_MARTIN_DRAFT . '</div>',
            '<div style="background-color:#00FF00">' . _AM_MARTIN_PUBLISHED . '</div>'
        ];
        if (count($RoomObjs) > 0) {
            foreach ($RoomObjs as $key => $thiscat) {
                $modify   = "<a href='?action=add&id=" . $thiscat->room_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/edit.gif'></a>";
                $addPrice = "<a href='?action=addprice&room_id=" . $thiscat->room_id() . "' title='" . _AM_MARTIN_ADD_PRICES . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/add.jpg'></a>";
                $delete   = "<a href='?action=del&id=" . $thiscat->room_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/delete.gif'></a>";
                echo "<tr><td class='even' align='left'>" . $thiscat->room_id() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->room_type_info() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->hotel_name() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->room_name() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->room_area() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->room_floor() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->room_sented_coupon() . '</td>';
                echo "<td class='even' align='left'>" . $Status[$thiscat->room_status()] . '</td>';
                echo "<td class='even' align='center'> $addPrice $modify $delete </td></tr>";
            }
        } else {
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '9'>" . MARTIN_IS_NUll . '</td>';
            echo '</tr>';
        }
        echo "</table>\n";
        echo '<div style="text-align:right;">' . $pavStr . '</div>';
        echo '<br>';
        martin_close_collapsable('createtable', 'createtableicon');
        echo '<br>';
        break;
    case 'typelist':
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_ROOM_CATEGORY_LIST, _AM_MARTIN_ROOM_CATEGORY_LIST);
        CreateButton();
        $roomTypeList = $roomHandler->getRoomTypeList();

        echo "<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
        echo '<tr>';
        echo "<td class='bg3' align='left'><b>ID</b></td>";
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_ROOM_CATEGORY_NAME . '</b></td>';
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_MARTIN_ACTIONS . '</b></td>';
        echo '</tr>';
        if (count($roomTypeList) > 0) {
            foreach ($roomTypeList as $key => $thiscat) {
                $modify = "<a href='?action=typeadd&typeid=" . $key . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/edit.gif'></a>";
                $delete = "<a href='?action=typedel&typeid=" . $key . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/delete.gif'></a>";
                echo "<tr><td class='even' align='lefet'>" . $key . '</td>';
                echo "<td class='even' align='lefet'>" . $thiscat . '</td>';
                echo "<td class='even' align='center'> $modify $delete </td></tr>";
            }
        } else {
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '3'>" . MARTIN_IS_NUll . '</td>';
            echo '</tr>';
        }
        echo "</table>\n";
        /*nclude_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new \XoopsPageNav($Cout, $helper->getConfig('perpage'), 0, 'start');
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
        echo "<br>";*/
        martin_close_collapsable('createtable', 'createtableicon');
        echo '<br>';
        break;
    case 'pricelist':
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_RESERVATION_LIST, _AM_MARTIN_RESERVATION_LIST);
        CreateButton();
        $Prices = $roomHandler->GetRoomPriceList($helper->getConfig('perpage'), $start);

        echo "<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
        echo '<tr>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_ROOM_COUNT . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_PRICE . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_HOTEL_CONSULT_PRICE . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_HOTEL_PRICE_TIME . '</b></td>';
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_MARTIN_ACTIONS . '</b></td>';
        echo '</tr>';
        $Cout = $roomHandler->GetRoomPriceCount();
        if ($Cout > 0) {
            foreach ($Prices as $key => $price) {
                $modify = "<a href='?action=addprice&room_id={$price['room_id']}&room_date={$price['room_date']}'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/edit.gif'></a>";
                $delete = "<a href='?action=pricedel&room_id={$price['room_id']}&room_date={$price['room_date']}'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/delete.gif'></a>";
                echo "<td class='even' align='left'>" . $price['room_name'] . '</td>';
                echo "<td class='even' align='left'>" . $price['room_price'] . '</td>';
                echo "<td class='even' align='left'>" . $price['room_advisory_range_small'] . '-' . $price['room_advisory_range_max'] . '  </td>';
                echo "<td class='even' align='left'>" . $price['room_date'] . '  </td>';
                echo "<td class='even' align='center'> $modify $delete </td></tr>";
            }
        } else {
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '5'>" . MARTIN_IS_NUll . '</td>';
            echo '</tr>';
        }
        echo "</table>\n";
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new \XoopsPageNav($Cout, $helper->getConfig('perpage'), $start, 'action=' . $action . '&start');
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
        echo '<br>';

        martin_close_collapsable('createtable', 'createtableicon');
        break;
    default:
        redirect_header(XOOPS_URL, 2, _AM_MARTIN_UNAUTHORIZED_ACCESS);
        break;
}

function CreateButton()
{
    global $action;
    $arr = [
        'addservicetype'  => [
            'url'   => 'martin.room.php?action=typeadd',
            'value' => _AM_MARTIN_ADD_ROOM_CATEGORIES
        ],
        'servicetypelist' => [
            'url'   => 'martin.room.php?action=typelist',
            'value' => _AM_MARTIN_ROOM_CATEGORY_LIST
        ],
        'addservice'      => ['url' => 'martin.room.php?action=add', 'value' => _AM_MARTIN_ADD_ROOM],
        'servicetype'     => ['url' => 'martin.room.php?action=list', 'value' => _AM_MARTIN_ROOMS_LIST],
        'addprice'        => ['url' => 'martin.room.php?action=addprice', 'value' => _AM_MARTIN_ADDING_RATES],
        'price'           => ['url' => 'martin.room.php?action=pricelist', 'value' => _AM_MARTIN_RESERVATION_LIST],
    ];
    $arr = 'pricelist' === $action ? array_merge($arr, [
        'delte_pass_data' => [
            'url'   => 'martin.room.php?action=deletepassdata',
            'value' => _AM_MARTIN_DELETE_EXPIRED_DATA
        ]
    ]) : $arr;
    Create_button($arr);
}

//底部
require_once __DIR__ . '/admin_footer.php';

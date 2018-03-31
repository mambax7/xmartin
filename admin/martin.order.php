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
$action = isset($_POST['action']) ? $_POST['action'] : @$_GET['action'];
$action = empty($action) ? 'list' : $action;
$action = trim(strtolower($action));
$id     = !empty($_POST['id']) ? $_POST['id'] : @$_GET['id'];
$id     = (int)$id;
$start  = \Xmf\Request::getInt('start', 0, 'GET');
//确认删除
$confirm = \Xmf\Request::getInt('confirm', 0, POST);
//parameter 参数

$orderHandler = xoops_getModuleHandler('order', MARTIN_DIRNAME, true);

//hotel city
$hotelcityHandler = xoops_getModuleHandler('hotelcity', MARTIN_DIRNAME, true);
$HotelCityObj     = $hotelcityHandler->create();

//hotel
$hotelHandler = xoops_getModuleHandler('hotel', MARTIN_DIRNAME, true);

if ($id) {
    $OrderObj = $orderHandler->get($id);
} else {
    $OrderObj = $orderHandler->create();
}

// martin_adminMenu(1, "订房后台 > 订单");

switch ($action) {
    case 'edit':
        include MARTIN_ROOT_PATH . 'include/form.order.php';
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_HOTEL_BOOKINGS_MODIFICATION, _AM_MARTIN_HOTEL_BOOKINGS_MODIFICATION);
        CreateButton();
        if (!$OrderObj->order_id()) {
            redirect_header(XOOPS_URL, 2, _AM_MARTIN_UNAUTHORIZED_ACCESS);
        }
        $form = new form_order($OrderObj);

        $form->display();
        martin_close_collapsable('createtable', 'createtableicon');
        break;
    /*case "info":

        break;*/
    case 'save':
        $OrderObj->setVar('order_id', $id);
        $OrderObj->setVar('order_status', (int)$_POST['order_status']);
        $room_price = $_POST['room_price'];

        if (!$id) {
            $OrderObj->setNew();
        }

        if ($OrderObj->isNew()) {
            $redirect_msg = _AM_MARTIN_ADDED_SUCCESSFULLY;
            $redirect_to  = 'martin.order.php';
        } else {
            $redirect_msg = _AM_MARTIN_MODIFIED_SUCCESSFULLY;
            $redirect_to  = 'martin.order.php';
        }
        if (!$orderHandler->updateOrder($OrderObj, $room_price)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_MARTIN_OPERATION_FAILED);
        }
        redirect_header($redirect_to, 2, $redirect_msg);
        break;
    case 'del':
        if (!$confirm) {
            xoops_confirm(['op' => 'del', 'id' => $id, 'confirm' => 1, 'name' => ''], '?action=del', _AM_MARTIN_DELETE_ORDERS . "'" . $id . "'. <br> <br> " . _AM_MARTIN_OK_TO_DELETE_THE_ORDER . '?', _DELETE);
        } else {
            if ($orderHandler->delete($OrderObj)) {
                $redirect_msg = _AM_MARTIN_OK_TO_DELETE_THE_ORDER;
                $redirect_to  = 'martin.order.php';
            } else {
                $redirect_msg = _AM_MARTIN_DELETE_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }
        break;
    case 'list':
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_HOTEL_ORDER_LIST, _AM_MARTIN_HOTEL_ORDER_LIST);
        CreateButton();

        //searchData
        $searchData = isset($_POST['s']) ? $_POST['s'] : null;
        $searchData = isset($_GET['s']) ? $_GET['s'] : $searchData;

        $hotel_name = isset($_GET['hotel_name']) ? $_GET['hotel_name'] : null;

        //分页
        $Count = $orderHandler->getCount($searchData);

        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

        $searchStr  = '';
        $searchData = array_filter($searchData);
        if (is_array($searchData)) {
            foreach ($searchData as $key => $value) {
                $searchStr .= 's[' . $key . ']' . '=' . $value . '&amp;';
                ${$key}    = (int)$value;
            }
        }

        $pagenav = new \XoopsPageNav($Count, $helper->getConfig('perpage'), $start, 'start', $searchStr);
        $pavStr  = '<div style="text-align:left;">' . $pagenav->renderNav() . '</div>';

        //html
        $htmlStar           = getModuleArray('hotelrank', 'hotel_star');
        $htmlOrderType      = getModuleArray('order_type', 's[order_type]', false, $order_type);
        $htmlOrderMode      = getModuleArray('order_mode', 's[order_mode]', false, $order_mode);
        $htmlOrderPayMethod = getModuleArray('order_pay_method', 's[order_pay_method]', false, $order_pay_method);
        $htmlOrderStatus    = getModuleArray('order_status', 's[order_status]', false, $order_status);
        //array
        $OrderType      = getModuleArray('order_type', 'order_type', true);
        $OrderMode      = getModuleArray('order_mode', 'order_mode', true);
        $OrderPayMethod = getModuleArray('order_pay_method', 'order_pay_method', true);
        $OrderStatus    = getModuleArray('order_status', 'order_status', true);

        $selectedHotel = is_null($hotel_name) ? '' : "\n<option value='{$hotel_id}' selected>$hotel_name</option>";
        $htmlHotel     = "<span id='hotel_name_div'><SELECT name='s[hotel_id]' onchange='hotel_select(this)'><option value='0'>----</option>$selectedHotel</SELECT></span><span id='hotel_name'></span>";
        $Status        = [
            '<div style="background-color:#FF0000">' . _AM_MARTIN_DRAFT . '</div>',
            '<div style="background-color:#00FF00">' . _AM_MARTIN_PUBLISHED . '</div>'
        ];
        //$htmlStar = getModuleArray('hotelrank','hotel_star');

        $OrderObjs = $Count > 0 ? $orderHandler->getOrders($searchData, $helper->getConfig('perpage'), $start, 0) : null;
        // Creating the objects for top categories

        echo "$pavStr<table width='100%' cellspacing=1 cellpadding=9 border=0 class = outer>";
        echo "<tr><td class='bg3' align='left'>
            <form action='' id='orderSearch' method='get'>
            " . _AM_MARTIN_HOTEL_AREA . ":{$hotelcityHandler->getTree('hotel_city_id', $_GET['hotel_city_id'])}
            " . _AM_MARTIN_HOTEL_STARS . ":$htmlStar
            " . _AM_MARTIN_HOTEL_NAME . ":$htmlHotel
            " . _AM_MARTIN_PREDETERMINED_MANNER . ":$htmlOrderType
            " . _AM_MARTIN_PAY_BY . ":$htmlOrderPayMethod
            " . MARTIN_ORDER_MODE_TITLE . ":$htmlOrderMode
            " . _AM_MARTIN_ORDER_STATUS . ":$htmlOrderStatus
            </td></tr><tr><td class='bg3' align='right'>
            <input type='submit' value=" . _AM_MARTIN_SEARCH . '></td>
            </form></tr>';
        echo '</table>';
        echo "<table width='100%' cellspacing=1 cellpadding=14 border=0 class = outer>";
        echo "<td class='bg3' width=10 align='left'><b>ID</b></td>";
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_PREDETERMINED_MANNER . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_ORDER_MODE . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_PAY_BY . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_STATUS . '</b></td>';
        echo "<td class='bg3' width=30 align='left'><b>" . _AM_MARTIN_TOTAL_PRICE . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_PAYMENT_AMOUNT . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_USE_COUPONS . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_USER . '</b></td>';
        //echo "<td class='bg3' align='left'><b>"._AM_MARTIN_FULL_NAME."</b></td>";
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_PHONE . '</b></td>';
        //echo "<td class='bg3' align='left'><b>"._AM_MARTIN_ORDER_MODIFICATION_TIME."</b></td>";
        //echo "<td class='bg3' align='left'><b>"._AM_MARTIN_SUBMIT_TIME."</b></td>";
        echo "<td class='bg3' align='center'><b>" . _AM_MARTIN_ACTIONS . '</b></td>';
        echo '</tr>';
        if ($Count > 0) {
            foreach ($OrderObjs as $order) {
                $modify = "<a href='?action=edit&id=" . $order->order_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/edit.gif'></a>";
                $delete = "<a href='?action=del&id=" . $order->order_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/delete.gif'></a>";
                echo '<tr>';
                echo "<td class='even' align='left'>
                    <a href='?action=edit&amp;id={$order->order_id()}' title='" . _AM_MARTIN_SEE_DETAILS . "'>{$order->order_id()}</a></td>";
                echo "<td class='even' align='left'>{$OrderType[$order->order_type()]}</td>";
                echo "<td class='even' align='left'>{$OrderMode[$order->order_mode()]}</td>";
                echo "<td class='even' align='left'>{$OrderPayMethod[$order->order_pay_method()]}</td>";
                echo "<td class='even' align='left'>{$OrderStatus[$order->order_status()]}</td>";
                echo "<td class='even' align='left'>{$order->order_total_price()}</td>";
                echo "<td class='even' align='left'>{$order->order_pay_money()}</td>";
                echo "<td class='even' align='left'>{$order->order_coupon()}</td>";
                echo "<td class='even' align='left'>
                    <a href='" . XOOPS_URL . "/userinfo.php?uid={$order->order_uid()}' title='" . _AM_MARTIN_VIEW_USER_INFORMATION . "' target='_blank'>{$order->uname()}</a>&nbsp;({$order->order_real_name()})</td>";
                //echo "<td class='even' align='left'>{$order->order_real_name()}</td>";
                echo "<td class='even' align='left'>{$order->order_phone()}<br>{$order->order_telephone()}</td>";
                //echo "<td class='even' align='left'>".date('Y-m-d H:i:s',$order->order_status_time())."</td>";
                //echo "<td class='even' align='left'>".date('Y-m-d H:i:s',$order->order_submit_time())."</td>";
                echo "<td class='even' align='center'> $modify $delete </td>";
                echo '</tr>';
            }
        } else {
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '14'>" . MARTIN_IS_NUll . '</td>';
            echo '</tr>';
            $categoryid = '0';
        }
        echo "</table></form>\n";
        echo "$pavStr<br>";
        martin_close_collapsable('createtable', 'createtableicon');
        martin_order_list_js();
        echo '<br>';

        break;
    default:
        redirect_header(XOOPS_URL, 2, _AM_MARTIN_UNAUTHORIZED_ACCESS);
        break;
}

function CreateButton()
{
    Create_button([
                      'servicetypelist' => [
                          'url'   => 'martin.order.php?action=list',
                          'value' => _AM_MARTIN_HOTEL_ORDER_LIST
                      ],
                  ]);
}

//底部
require_once __DIR__ . '/admin_footer.php';

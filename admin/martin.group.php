<?php

use XoopsModules\Xmartin;

require_once __DIR__ . '/admin_header.php';

/** @var \XoopsModules\Xmartin\Helper $helper */
$helper = \XoopsModules\Xmartin\Helper::getInstance();

/*
 * 处理
 **/

//头部
require_once __DIR__ . '/martin.header.php';
$currentFile = basename(__FILE__);
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation($currentFile);

//parameter 参数
$action = isset($_POST['action']) ? $_POST['action'] : @$_GET['action'];
$action = empty($action) ? 'list' : $action;
$action = trim(mb_strtolower($action));
$id     = !empty($_POST['id']) ? $_POST['id'] : @$_GET['id'];
$id     = (int)$id;
$start  = \Xmf\Request::getInt('start', 0, 'GET');
//确认删除
$confirm = \Xmf\Request::getInt('confirm', 0, 'POST');
//parameter 参数

// martin_adminMenu(7, "订房后台 > 团购管理");

$groupHandler        = $helper->getHandler('Group');
$hotelserviceHandler = $helper->getHandler('HotelService');

//$hotelServiceObj = $hotelserviceHandler->create();
$groupObj = $id > 0 ? $groupHandler->get($id) : $groupHandler->create();

switch ($action) {
    case 'add':
        martin_collapsableBar('createtable', 'createtableicon', _AM_XMARTIN_ADD_CUSTOMERS, _AM_XMARTIN_ADD_CUSTOMERS);
        CreateButton();
        //Create_button(array('addcity'=>array('url'=>'mconfirmartin.hotel.city.php?action=add','value'=>_AM_XMARTIN_CITY_NAME)));
        $form = new Xmartin\Form\FormGroup($groupObj, $groupHandler->getRoomList($id), $hotelserviceHandler->getHotelList());

        $form->display();
        martin_close_collapsable('createtable', 'createtableicon');
        break;
    case 'save':
        //var_dump(($_POST['group_info']));exit;
        $groupObj->setVar('group_id', $id);
        $groupObj->setVar('group_name', (isset($_POST['group_name']) ? addslashes($_POST['group_name']) : ''));
        $groupObj->setVar('group_info', (isset($_POST['group_info']) ? $_POST['group_info'] : ''));
        $groupObj->setVar('check_in_date', isset($_POST['check_in_date']) ? strtotime($_POST['check_in_date']) : 0);
        $groupObj->setVar('check_out_date', isset($_POST['check_out_date']) ? strtotime($_POST['check_out_date']) : 0);
        //$groupObj->setVar('apply_start_date', (isset($_POST['apply_start_date'])) ? strtotime($_POST['apply_start_date']) : 0);
        //$groupObj->setVar('apply_end_date', (isset($_POST['apply_end_date'])) ? strtotime($_POST['apply_end_date']) : 0);

        $groupObj->setVar('apply_start_date', isset($_POST['apply_start_date']) ? strtotime($_POST['apply_start_date']['date']) + \Xmf\Request::getInt('apply_start_date', 0, 'POST')['time'] : 0);
        $groupObj->setVar('apply_end_date', isset($_POST['apply_end_date']) ? strtotime($_POST['apply_end_date']['date']) + \Xmf\Request::getInt('apply_end_date', 0, 'POST')['time'] : 0);

        $groupObj->setVar('group_price', isset($_POST['group_price']) ? round($_POST['group_price'], 2) : 0);
        $groupObj->setVar('group_can_use_coupon', isset($_POST['group_can_use_coupon']) ? intval($_POST['group_can_use_coupon'], 2) : 0);
        $groupObj->setVar('group_sented_coupon', isset($_POST['group_sented_coupon']) ? round($_POST['group_sented_coupon'], 2) : 0);
        $groupObj->setVar('group_status', isset($_POST['group_status']) ? intval($_POST['group_status'], 2) : 0);
        $groupObj->setVar('group_add_time', time());

        $room_counts = [];
        $room_ids    = $_POST['room_id'];
        foreach ($room_ids as $room_id) {
            $room_counts[] = $_POST['room_count_' . $room_id];
        }

        //var_dump($groupObj);exit;
        $isNew = false;
        if (!$id) {
            $isNew = true;
            $groupObj->setNew();
        }
        if ($groupObj->isNew()) {
            $redirect_msg = _AM_XMARTIN_ADDED_SUCCESSFULLY;
            $redirect_to  = 'martin.group.php';
        } else {
            $redirect_msg = _AM_XMARTIN_MODIFIED_SUCCESSFULLY;
            $redirect_to  = 'martin.group.php';
        }

        if (!is_array($room_ids) || empty($room_ids)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_XMARTIN_FAILED_TO_ADD_ROOM . '<br>' . _AM_XMARTIN_NO_ROOM_CHOSEN);
        }

        if (!$group_id = $groupHandler->insert($groupObj)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_XMARTIN_OPERATION_FAILED);
        }

        //$group_id = $id > 0 ? $id : $groupObj->group_id();

        //var_dump($group_id);
        if ($group_id > 0) {
            if (!$groupHandler->InsertGroupRoom($group_id, $room_ids, $room_counts, $isNew)) {
                redirect_header('javascript:history.go(-1);', 2, _AM_XMARTIN_FAILED_TO_ADD_ROOM);
            }
        } else {
            redirect_header('javascript:history.go(-1);', 2, _AM_XMARTIN_FAILED_TO_ADD_ROOM);
        }

        redirect_header($redirect_to, 2, $redirect_msg);
        break;
    case 'del':
        if (!$confirm) {
            xoops_confirm(
                [
                    'op'      => 'del',
                    'id'      => $groupObj->group_id(),
                    'confirm' => 1,
                    'name'    => $groupObj->group_name(),
                ],
                '?action=del',
                "删除 '" . $groupObj->group_name() . "'. <br> <br> 确定删除该团购吗?",
                _DELETE
            );
        } else {
            if ($groupHandler->delete($groupObj)) {
                $redirect_msg = _AM_XMARTIN_OK_TO_DELETE_THE_ORDER;
                $redirect_to  = 'martin.group.php';
            } else {
                $redirect_msg = _AM_XMARTIN_DELETE_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }
        break;
    case 'list':
        martin_collapsableBar('createtable', 'createtableicon', _AM_XMARTIN_CUSTOMERS_LIST, _AM_XMARTIN_CUSTOMERS_LIST);
        CreateButton();
        $Status       = [
            '<div style="background-color:#FF0000">' . _AM_XMARTIN_DRAFT . '</div>',
            '<div style="background-color:#00FF00">' . _AM_XMARTIN_PUBLISHED . '</div>',
        ];
        $groupObjects = $groupHandler->getGroups($helper->getConfig('perpage'), $start, 0);
        $Cout         = $groupHandler->getCount();
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new \XoopsPageNav($Cout, $helper->getConfig('perpage'), $start, 'start');
        $pavStr  = '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

        // Creating the objects for top categories
        echo $pavStr . "<table width='100%' cellspacing=1 cellpadding=10 border=0 class = outer>";
        echo '<tr>';
        echo "<td class='bg3' align='left'><b>ID</b></td>";
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_TITLE . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_CHECK_IN . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_CHECK_OUT . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_CUSTOMER_START_TIME . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_CUSTOMER_END_TIME . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_BUY_PRICE . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_CASH . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_PUBLIC_STATUS . '</b></td>';
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_XMARTIN_ACTIONS . '</b></td>';
        echo '</tr>';
        if (count($groupObjects) > 0) {
            foreach ($groupObjects as $key => $thiscat) {
                $StatusStr = time() < $thiscat->apply_end_date() ? '<div style="background-color: rgb(0, 255, 0);">%s</div>' : '<div style="background-color: rgb(255, 0, 0);">%s</div>';
                $modify    = "<a href='?action=add&id=" . $thiscat->group_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/edit.gif'></a>";
                $delete    = "<a href='?action=del&id=" . $thiscat->group_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/delete.gif'></a>";
                echo "<tr><td class='even' align='left'>" . $thiscat->group_id() . '</td>';
                echo "<td class='even' align='left' width=50><a href='../group.php/group-" . $thiscat->group_id() . $helper->getConfig('hotel_static_prefix') . "'>" . $thiscat->group_name() . '</a></td>';
                echo "<td class='even' align='left'>" . date('Y-m-d', $thiscat->check_in_date()) . '</td>';
                echo "<td class='even' align='left'>" . date('Y-m-d', $thiscat->check_out_date()) . '</td>';
                echo "<td class='even' align='left'>" . date('Y-m-d H:i:s', $thiscat->apply_start_date()) . '</td>';
                echo "<td class='even' align='left'>" . sprintf($StatusStr, date('Y-m-d H:i:s', $thiscat->apply_end_date())) . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->group_price() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->group_sented_coupon() . '</td>';
                echo "<td class='even' align='left'>" . $Status[$thiscat->group_status()] . '</td>';
                echo "<td class='even' align='center'>  $modify $delete </td></tr>";
            }
        } else {
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '10'>" . XMARTIN_IS_NUll . '</td>';
            echo '</tr>';
            $categoryid = '0';
        }
        echo "</table>\n";
        echo '<div style="text-align:right;">' . $pavStr . '</div>';
        echo '<br>';
        martin_close_collapsable('createtable', 'createtableicon');
        break;
    default:
        redirect_header(XOOPS_URL, 2, _AM_XMARTIN_UNAUTHORIZED_ACCESS);
        break;
}

function CreateButton()
{
    $arr = [
        'addservicetype'  => ['url' => 'martin.group.php?action=add', 'value' => _AM_XMARTIN_ADD_CUSTOMERS],
        'servicetypelist' => ['url' => 'martin.group.php?action=list', 'value' => _AM_XMARTIN_CUSTOMERS_LIST],
    ];
    Create_button($arr);
}

//底部
require_once __DIR__ . '/admin_footer.php';

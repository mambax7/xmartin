<?php
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
$start  = isset($_GET['start']) ? (int)$_GET['start'] : 0;
//确认删除
$confirm = isset($_POST['confirm']) ? $_POST['confirm'] : 0;
//parameter 参数

// martin_adminMenu(7, "订房后台 > 团购管理");

$groupHandler        = xoops_getModuleHandler('group', MARTIN_DIRNAME, true);
$hotelserviceHandler = xoops_getModuleHandler('hotelservice', MARTIN_DIRNAME, true);

//$HotelServiceObj = $hotelserviceHandler->create();
$GroupObj = $id > 0 ? $groupHandler->get($id) : $groupHandler->create();

switch ($action) {
    case 'add':
        include MARTIN_ROOT_PATH . 'include/form.group.php';
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_ADD_CUSTOMERS, _AM_MARTIN_ADD_CUSTOMERS);
        CreateButton();
        //Create_button(array('addcity'=>array('url'=>'mconfirmartin.hotel.city.php?action=add','value'=>_AM_MARTIN_CITY_NAME)));
        $form = new form_group($GroupObj, $groupHandler->getRoomList($id), $hotelserviceHandler->GetHotelList());

        $form->display();
        martin_close_collapsable('createtable', 'createtableicon');
        break;
    case 'save':
        //var_dump(($_POST['group_info']));exit;
        $GroupObj->setVar('group_id', $id);
        $GroupObj->setVar('group_name', (isset($_POST['group_name']) ? addslashes($_POST['group_name']) : ''));
        $GroupObj->setVar('group_info', (isset($_POST['group_info']) ? $_POST['group_info'] : ''));
        $GroupObj->setVar('check_in_date', isset($_POST['check_in_date']) ? strtotime($_POST['check_in_date']) : 0);
        $GroupObj->setVar('check_out_date', isset($_POST['check_out_date']) ? strtotime($_POST['check_out_date']) : 0);
        //$GroupObj->setVar('apply_start_date', (isset($_POST['apply_start_date'])) ? strtotime($_POST['apply_start_date']) : 0);
        //$GroupObj->setVar('apply_end_date', (isset($_POST['apply_end_date'])) ? strtotime($_POST['apply_end_date']) : 0);

        $GroupObj->setVar('apply_start_date', isset($_POST['apply_start_date']) ? strtotime($_POST['apply_start_date']['date']) + (int)$_POST['apply_start_date']['time'] : 0);
        $GroupObj->setVar('apply_end_date', isset($_POST['apply_end_date']) ? strtotime($_POST['apply_end_date']['date']) + (int)$_POST['apply_end_date']['time'] : 0);

        $GroupObj->setVar('group_price', isset($_POST['group_price']) ? round($_POST['group_price'], 2) : 0);
        $GroupObj->setVar('group_can_use_coupon', isset($_POST['group_can_use_coupon']) ? intval($_POST['group_can_use_coupon'], 2) : 0);
        $GroupObj->setVar('group_sented_coupon', isset($_POST['group_sented_coupon']) ? round($_POST['group_sented_coupon'], 2) : 0);
        $GroupObj->setVar('group_status', isset($_POST['group_status']) ? intval($_POST['group_status'], 2) : 0);
        $GroupObj->setVar('group_add_time', time());

        $room_counts = [];
        $room_ids    = $_POST['room_id'];
        foreach ($room_ids as $room_id) {
            $room_counts[] = $_POST['room_count_' . $room_id];
        }

        //var_dump($GroupObj);exit;
        $isNew = false;
        if (!$id) {
            $isNew = true;
            $GroupObj->setNew();
        }
        if ($GroupObj->isNew()) {
            $redirect_msg = _AM_MARTIN_ADDED_SUCCESSFULLY;
            $redirect_to  = 'martin.group.php';
        } else {
            $redirect_msg = _AM_MARTIN_MODIFIED_SUCCESSFULLY;
            $redirect_to  = 'martin.group.php';
        }

        if (!is_array($room_ids) || empty($room_ids)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_MARTIN_FAILED_TO_ADD_ROOM . '<br>' . _AM_MARTIN_NO_ROOM_CHOSEN);
        }

        if (!$group_id = $groupHandler->insert($GroupObj)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_MARTIN_OPERATION_FAILED);
        }

        //$group_id = $id > 0 ? $id : $GroupObj->group_id();

        //var_dump($group_id);
        if ($group_id > 0) {
            if (!$groupHandler->InsertGroupRoom($group_id, $room_ids, $room_counts, $isNew)) {
                redirect_header('javascript:history.go(-1);', 2, _AM_MARTIN_FAILED_TO_ADD_ROOM);
            }
        } else {
            redirect_header('javascript:history.go(-1);', 2, _AM_MARTIN_FAILED_TO_ADD_ROOM);
        }

        redirect_header($redirect_to, 2, $redirect_msg);
        break;
    case 'del':
        if (!$confirm) {
            xoops_confirm([
                              'op'      => 'del',
                              'id'      => $GroupObj->group_id(),
                              'confirm' => 1,
                              'name'    => $GroupObj->group_name()
                          ], '?action=del', "删除 '" . $GroupObj->group_name() . "'. <br> <br> 确定删除该团购吗?", _DELETE);
        } else {
            if ($groupHandler->delete($GroupObj)) {
                $redirect_msg = _AM_MARTIN_OK_TO_DELETE_THE_ORDER;
                $redirect_to  = 'martin.group.php';
            } else {
                $redirect_msg = _AM_MARTIN_DELETE_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }
        break;
    case 'list':
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_CUSTOMERS_LIST, _AM_MARTIN_CUSTOMERS_LIST);
        CreateButton();
        $Status    = [
            '<div style="background-color:#FF0000">' . _AM_MARTIN_DRAFT . '</div>',
            '<div style="background-color:#00FF00">' . _AM_MARTIN_PUBLISHED . '</div>'
        ];
        $GroupObjs = $groupHandler->getGroups($xoopsModuleConfig['perpage'], $start, 0);
        $Cout      = $groupHandler->getCount();
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new XoopsPageNav($Cout, $xoopsModuleConfig['perpage'], $start, 'start');
        $pavStr  = '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

        // Creating the objects for top categories
        echo $pavStr . "<table width='100%' cellspacing=1 cellpadding=10 border=0 class = outer>";
        echo '<tr>';
        echo "<td class='bg3' align='left'><b>ID</b></td>";
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_TITLE . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_CHECK_IN . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_CHECK_OUT . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_CUSTOMER_START_TIME . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_CUSTOMER_END_TIME . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_BUY_PRICE . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_CASH . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_PUBLIC_STATUS . '</b></td>';
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_MARTIN_ACTIONS . '</b></td>';
        echo '</tr>';
        if (count($GroupObjs) > 0) {
            foreach ($GroupObjs as $key => $thiscat) {
                $StatusStr = time() < $thiscat->apply_end_date() ? '<div style="background-color: rgb(0, 255, 0);">%s</div>' : '<div style="background-color: rgb(255, 0, 0);">%s</div>';
                $modify    = "<a href='?action=add&id=" . $thiscat->group_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/edit.gif'></a>";
                $delete    = "<a href='?action=del&id=" . $thiscat->group_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/delete.gif'></a>";
                echo "<tr><td class='even' align='left'>" . $thiscat->group_id() . '</td>';
                echo "<td class='even' align='left' width=50><a href='../group.php/group-" . $thiscat->group_id() . $xoopsModuleConfig['hotel_static_prefix'] . "'>" . $thiscat->group_name() . '</a></td>';
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
            echo "<td class='head' align='center' colspan= '10'>" . MARTIN_IS_NUll . '</td>';
            echo '</tr>';
            $categoryid = '0';
        }
        echo "</table>\n";
        echo '<div style="text-align:right;">' . $pavStr . '</div>';
        echo '<br>';
        martin_close_collapsable('createtable', 'createtableicon');
        break;
    default:
        redirect_header(XOOPS_URL, 2, _AM_MARTIN_UNAUTHORIZED_ACCESS);
        break;
}

function CreateButton()
{
    $arr = [
        'addservicetype'  => ['url' => 'martin.group.php?action=add', 'value' => _AM_MARTIN_ADD_CUSTOMERS],
        'servicetypelist' => ['url' => 'martin.group.php?action=list', 'value' => _AM_MARTIN_CUSTOMERS_LIST],
    ];
    Create_button($arr);
}

//底部
require_once __DIR__ . '/admin_footer.php';

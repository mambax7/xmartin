<?php

use XoopsModules\Xmartin;

require_once __DIR__ . '/admin_header.php';

/** @var Xmartin\Helper $helper */
$helper = Xmartin\Helper::getInstance();

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

// martin_adminMenu(8, "订房后台 > 竞价管理");

$auctionHandler      = xoops_getModuleHandler('auction', MARTIN_DIRNAME, true);
$hotelserviceHandler = xoops_getModuleHandler('hotelservice', MARTIN_DIRNAME, true);

//$HotelServiceObj = $hotelserviceHandler->create();
$auctionObj = $id > 0 ? $auctionHandler->get($id) : $auctionHandler->create();

switch ($action) {
    case 'add':
        include MARTIN_ROOT_PATH . 'include/form.auction.php';
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_ADDING_BID, _AM_MARTIN_ADDING_BID);
        CreateButton();
        //Create_button(array('addcity'=>array('url'=>'mconfirmartin.hotel.city.php?action=add','value'=>_AM_MARTIN_CITY_NAME)));
        $form = new form_auction($auctionObj, $auctionHandler->getRoomList($id), $hotelserviceHandler->GetHotelList());

        $form->display();
        martin_close_collapsable('createtable', 'createtableicon');
        break;
    case 'save':
        //var_dump(($_POST['auction_info']));exit;
        $auctionObj->setVar('auction_id', $id);
        $auctionObj->setVar('auction_name', (isset($_POST['auction_name']) ? addslashes($_POST['auction_name']) : ''));
        $auctionObj->setVar('auction_info', (isset($_POST['auction_info']) ? $_POST['auction_info'] : ''));
        $auctionObj->setVar('check_in_date', isset($_POST['check_in_date']) ? strtotime($_POST['check_in_date']) : 0);
        $auctionObj->setVar('check_out_date', isset($_POST['check_out_date']) ? strtotime($_POST['check_out_date']) : 0);
        $auctionObj->setVar('apply_start_date', isset($_POST['apply_start_date']) ? strtotime($_POST['apply_start_date']) : 0);
        $auctionObj->setVar('apply_end_date', isset($_POST['apply_end_date']) ? strtotime($_POST['apply_end_date']) : 0);
        $auctionObj->setVar('auction_price', isset($_POST['auction_price']) ? round($_POST['auction_price'], 2) : 0);
        $auctionObj->setVar('auction_low_price', isset($_POST['auction_low_price']) ? round($_POST['auction_low_price'], 2) : 0);
        $auctionObj->setVar('auction_add_price', isset($_POST['auction_add_price']) ? round($_POST['auction_add_price'], 2) : 0);
        $auctionObj->setVar('auction_can_use_coupon', isset($_POST['auction_can_use_coupon']) ? intval($_POST['auction_can_use_coupon'], 2) : 0);
        $auctionObj->setVar('auction_sented_coupon', isset($_POST['auction_sented_coupon']) ? round($_POST['auction_sented_coupon'], 2) : 0);
        $auctionObj->setVar('auction_status', isset($_POST['auction_status']) ? intval($_POST['auction_status'], 2) : 0);
        $auctionObj->setVar('auction_add_time', time());

        $room_counts = [];
        $room_ids    = $_POST['room_id'];
        foreach ($room_ids as $room_id) {
            $room_counts[] = $_POST['room_count_' . $room_id];
        }

        //var_dump($auctionObj);exit;
        $isNew = false;
        if (!$id) {
            $isNew = true;
            $auctionObj->setNew();
        }
        if ($auctionObj->isNew()) {
            $redirect_msg = _AM_MARTIN_ADDED_SUCCESSFULLY;
            $redirect_to  = 'martin.auction.php';
        } else {
            $redirect_msg = _AM_MARTIN_MODIFIED_SUCCESSFULLY;
            $redirect_to  = 'martin.auction.php';
        }

        if (!is_array($room_ids) || empty($room_ids)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_MARTIN_FAILED_TO_ADD_ROOM . '<br>' . _AM_MARTIN_NO_ROOM_CHOSEN);
        }

        if (!$auction_id = $auctionHandler->insert($auctionObj)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_MARTIN_OPERATION_FAILED);
        }

        //$auction_id = $id > 0 ? $id : $auctionObj->auction_id();

        //var_dump($auction_id);
        if ($auction_id > 0) {
            if (!$auctionHandler->InsertAuctionRoom($auction_id, $room_ids, $room_counts, $isNew)) {
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
                              'id'      => $auctionObj->auction_id(),
                              'confirm' => 1,
                              'name'    => $auctionObj->auction_name()
                          ], '?action=del', "删除 '" . $auctionObj->auction_name() . "'. <br> <br> " . _AM_MARTIN_OK_TO_DELETE_THE_BID, _DELETE);
        } else {
            if ($auctionHandler->delete($auctionObj)) {
                $redirect_msg = _AM_MARTIN_OK_TO_DELETE_THE_ORDER;
                $redirect_to  = 'martin.auction.php';
            } else {
                $redirect_msg = _AM_MARTIN_DELETE_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }
        break;
    case 'list':
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_AUCTION_LIST, _AM_MARTIN_AUCTION_LIST);
        CreateButton();
        $Status      = [
            '<div style="background-color:#FF0000">' . _AM_MARTIN_DRAFT . '</div>',
            '<div style="background-color:#00FF00">' . _AM_MARTIN_PUBLISHED . '</div>'
        ];
        $AuctionObjs = $auctionHandler->getAuctions($helper->getConfig('perpage'), $start, 0);
        $Cout        = $auctionHandler->getCount();
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new \XoopsPageNav($Cout, $helper->getConfig('perpage'), $start, 'start');
        $pavStr  = '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

        // Creating the objects for top categories
        echo $pavStr . "<table width='100%' cellspacing=1 cellpadding=12 border=0 class = outer>";
        echo '<tr>';
        echo "<td class='bg3' align='left'><b>ID</b></td>";
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_TITLE . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_AUCTION_START_TIME . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_AUCTION_END_TIME . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_CHECK_IN . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_CHECK_OUT . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_STARTING_PRICE . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_CHEAP . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_BID_INCREMENT . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_BUY_PRICE . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_CASH . '</b></td>';
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_MARTIN_ACTIONS . '</b></td>';
        echo '</tr>';
        if (count($AuctionObjs) > 0) {
            foreach ($AuctionObjs as $key => $thiscat) {
                $modify = "<a href='?action=add&id=" . $thiscat->auction_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/edit.gif'></a>";
                $delete = "<a href='?action=del&id=" . $thiscat->auction_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/delete.gif'></a>";
                echo "<tr><td class='even' align='left'>" . $thiscat->auction_id() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->auction_name() . '</td>';
                echo "<td class='even' align='left'>" . date('Y-m-d', $thiscat->check_in_date()) . '</td>';
                echo "<td class='even' align='left'>" . date('Y-m-d', $thiscat->check_out_date()) . '</td>';
                echo "<td class='even' align='left'>" . date('Y-m-d', $thiscat->apply_start_date()) . '</td>';
                echo "<td class='even' align='left'>" . date('Y-m-d', $thiscat->apply_end_date()) . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->auction_price() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->auction_low_price() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->auction_add_price() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->auction_sented_coupon() . '</td>';
                echo "<td class='even' align='left'>" . $Status[$thiscat->auction_status()] . '</td>';
                echo "<td class='even' align='center'>  $modify $delete </td></tr>";
            }
        } else {
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '12'>" . MARTIN_IS_NUll . '</td>';
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
        'addservicetype'  => ['url' => 'martin.auction.php?action=add', 'value' => _AM_MARTIN_ADDING_BID],
        'servicetypelist' => ['url' => 'martin.auction.php?action=list', 'value' => _AM_MARTIN_AUCTION_LIST],
    ];
    Create_button($arr);
}

//底部
require_once __DIR__ . '/admin_footer.php';

<?php

use XoopsModules\Xmartin;

require_once __DIR__ . '/admin_header.php';

//xoops_cp_header();

/*
 * 处理
 **/
//头部

/** @var Xmartin\Helper $helper */
//$helper = Xmartin\Helper::getInstance();

//require_once __DIR__   . '/martin.header.php';

xoops_cp_header();

$currentFile = basename(__FILE__);
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation($currentFile);

//parameter 参数
//$action = isset($_POST['action']) ? $_POST['action'] : @$_GET['action'];
//$action = empty($action) ? 'list' : $action;
//$action = trim(strtolower($action));

$action = \Xmf\Request::getCmd('action', 'list');

//$id     = !empty($_POST['id']) ? $_POST['id'] : @$_GET['id'];
//$id     = (int)$id;

$id = \Xmf\Request::getInt('id', 0, 'POST');

$start = \Xmf\Request::getInt('start', 0, 'GET');
//确认删除
$confirm = \Xmf\Request::getInt('confirm', 0, 'POST');
//parameter 参数

// martin_adminMenu(4, "订房后台 > 酒店促销");

$promotionHandler    = $helper->getHandler('Promotion');
$hotelserviceHandler = $helper->getHandler('HotelService');

//$hotelServiceObj = $hotelserviceHandler->create();
$promotionObj = $id > 0 ? $promotionHandler->get($id) : $promotionHandler->create();

switch ($action) {
    case 'add':
        martin_collapsableBar('createtable', 'createtableicon', _AM_XMARTIN_PROMO_ADD, _AM_XMARTIN_PROMO_ADD);
        CreateButton();
        //Create_button(array('addcity'=>array('url'=>'mconfirmartin.hotel.city.php?action=add','value'=>_AM_XMARTIN_CITY_NAME)));

        $form = new Xmartin\Form\FormPromotion($promotionObj, $hotelserviceHandler->getHotelList());
        $form->display();

        martin_close_collapsable('createtable', 'createtableicon');
        break;
    case 'save':
        //var_dump(($_POST['group_info']));exit;
        $promotionObj->setVar('promotion_id', $id);
        $promotionObj->setVar('hotel_id', \Xmf\Request::getInt('hotel_id', 0, 'POST'));
        $promotionObj->setVar('promotion_description', (isset($_POST['promotion_description']) ? $_POST['promotion_description'] : ''));
        $promotionObj->setVar('promotion_start_date', isset($_POST['promotion_start_date']) ? strtotime($_POST['promotion_start_date']) : 0);
        $promotionObj->setVar('promotion_end_date', isset($_POST['promotion_end_date']) ? strtotime($_POST['promotion_end_date']) : 0);
        $promotionObj->setVar('promotion_add_time', time());

        //var_dump($promotionObj);exit;
        $isNew = false;
        if (!$id) {
            $isNew = true;
            $promotionObj->setNew();
        }
        if ($promotionObj->isNew()) {
            $redirect_msg = _AM_XMARTIN_ADDED_SUCCESSFULLY;
            $redirect_to  = 'martin.hotel.promotion.php';
        } else {
            $redirect_msg = _AM_XMARTIN_MODIFIED_SUCCESSFULLY;
            $redirect_to  = 'martin.hotel.promotion.php';
        }

        if (!$promotionHandler->insert($promotionObj)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_XMARTIN_OPERATION_FAILED);
        }

        //$promotion_id = $id > 0 ? $id : $promotionObj->promotion_id();

        redirect_header($redirect_to, 2, $redirect_msg);
        break;
    case 'del':
        if (!$confirm) {
            xoops_confirm(
                [
                    'op'      => 'del',
                    'id'      => $promotionObj->promotion_id(),
                    'confirm' => 1,
                    'name'    => $promotionObj->hotel_name(),
                ],
                '?action=del',
                "删除 '" . $promotionObj->hotel_name() . "'. <br> <br> 确定删除该促销吗?",
                _DELETE
            );
        } else {
            if ($promotionHandler->delete($promotionObj)) {
                $redirect_msg = _AM_XMARTIN_OK_TO_DELETE_THE_ORDER;
                $redirect_to  = 'martin.hotel.promotion.php';
            } else {
                $redirect_msg = _AM_XMARTIN_DELETE_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }
        break;
    case 'list':
        martin_collapsableBar('createtable', 'createtableicon', _AM_XMARTIN_PROMO_LIST, _AM_XMARTIN_PROMO_LIST);
        CreateButton();
        $Status = [
            '<div style="background-color:#FF0000">' . _AM_XMARTIN_DRAFT . '</div>',
            '<div style="background-color:#00FF00">' . _AM_XMARTIN_PUBLISHED . '</div>',
        ];

        $Cout             = $promotionHandler->getCount();
        $promotionObjects = $promotionHandler->getPromotions($helper->getConfig('perpage'), $start, 0);

        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new \XoopsPageNav($Cout, $helper->getConfig('perpage'), $start, 'start');
        $pavStr  = '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

        // Creating the objects for top categories
        echo $pavStr . "<table width='100%' cellspacing=1 cellpadding=5 border=0 class = outer>";
        echo '<tr>';
        echo "<td class='bg3' align='left'><b>ID</b></td>";
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_PROMO_HOTELS . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_PROMO_START . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_PROMO_END . '</b></td>';
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_XMARTIN_ACTIONS . '</b></td>';
        echo '</tr>';
        if (count($promotionObjects) > 0) {
            foreach ($promotionObjects as $key => $thiscat) {
                $modify = "<a href='?action=add&id=" . $thiscat->promotion_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/edit.gif'></a>";
                $delete = "<a href='?action=del&id=" . $thiscat->promotion_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/delete.gif'></a>";
                echo "<tr><td class='even' align='left'>" . $thiscat->promotion_id() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->hotel_name() . '</td>';
                echo "<td class='even' align='left'>" . date('Y-m-d', $thiscat->promotion_start_date()) . '</td>';
                echo "<td class='even' align='left'>" . date('Y-m-d', $thiscat->promotion_end_date()) . '</td>';
                echo "<td class='even' align='center'>  $modify $delete </td></tr>";
            }
        } else {
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '5'>" . XMARTIN_IS_NUll . '</td>';
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
        'addservicetype'  => ['url' => 'martin.hotel.promotion.php?action=add', 'value' => _AM_XMARTIN_PROMO_ADD],
        'servicetypelist' => ['url' => 'martin.hotel.promotion.php?action=list', 'value' => _AM_XMARTIN_PROMO_LIST],
    ];
    Create_button($arr);
}

//底部
//require_once martin.footer.php;

require_once __DIR__ . '/admin_footer.php';

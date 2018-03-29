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
$start  = isset($_GET['start']) ? (int)$_GET['start'] : 0;
//确认删除
$confirm = isset($_POST['confirm']) ? $_POST['confirm'] : 0;
//parameter 参数

// martin_adminMenu(4, "订房后台 > 酒店促销");

$promotionHandler    = xoops_getModuleHandler('hotelpromotion', MARTIN_DIRNAME, true);
$hotelserviceHandler = xoops_getModuleHandler('hotelservice', MARTIN_DIRNAME, true);

//$HotelServiceObj = $hotelserviceHandler->create();
$PromotionObj = $id > 0 ? $promotionHandler->get($id) : $promotionHandler->create();

switch ($action) {
    case 'add':
        include MARTIN_ROOT_PATH . 'include/form.hotel.promotion.php';
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_PROMO_ADD, _AM_MARTIN_PROMO_ADD);
        CreateButton();
        //Create_button(array('addcity'=>array('url'=>'mconfirmartin.hotel.city.php?action=add','value'=>_AM_MARTIN_CITY_NAME)));
        $form = new form_hotel_promotion($PromotionObj, $hotelserviceHandler->GetHotelList());

        $form->display();
        martin_close_collapsable('createtable', 'createtableicon');
        break;
    case 'save':
        //var_dump(($_POST['group_info']));exit;
        $PromotionObj->setVar('promotion_id', $id);
        $PromotionObj->setVar('hotel_id', (int)$_POST['hotel_id']);
        $PromotionObj->setVar('promotion_description', (isset($_POST['promotion_description']) ? $_POST['promotion_description'] : ''));
        $PromotionObj->setVar('promotion_start_date', isset($_POST['promotion_start_date']) ? strtotime($_POST['promotion_start_date']) : 0);
        $PromotionObj->setVar('promotion_end_date', isset($_POST['promotion_end_date']) ? strtotime($_POST['promotion_end_date']) : 0);
        $PromotionObj->setVar('promotion_add_time', time());

        //var_dump($PromotionObj);exit;
        $isNew = false;
        if (!$id) {
            $isNew = true;
            $PromotionObj->setNew();
        }
        if ($PromotionObj->isNew()) {
            $redirect_msg = _AM_MARTIN_ADDED_SUCCESSFULLY;
            $redirect_to  = 'martin.hotel.promotion.php';
        } else {
            $redirect_msg = _AM_MARTIN_MODIFIED_SUCCESSFULLY;
            $redirect_to  = 'martin.hotel.promotion.php';
        }

        if (!$promotionHandler->insert($PromotionObj)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_MARTIN_OPERATION_FAILED);
        }

        //$promotion_id = $id > 0 ? $id : $PromotionObj->promotion_id();

        redirect_header($redirect_to, 2, $redirect_msg);
        break;
    case 'del':
        if (!$confirm) {
            xoops_confirm([
                              'op'      => 'del',
                              'id'      => $PromotionObj->promotion_id(),
                              'confirm' => 1,
                              'name'    => $PromotionObj->hotel_name()
                          ], '?action=del', "删除 '" . $PromotionObj->hotel_name() . "'. <br> <br> 确定删除该促销吗?", _DELETE);
        } else {
            if ($promotionHandler->delete($PromotionObj)) {
                $redirect_msg = _AM_MARTIN_OK_TO_DELETE_THE_ORDER;
                $redirect_to  = 'martin.hotel.promotion.php';
            } else {
                $redirect_msg = _AM_MARTIN_DELETE_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }
        break;
    case 'list':
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_PROMO_LIST, _AM_MARTIN_PROMO_LIST);
        CreateButton();
        $Status = [
            '<div style="background-color:#FF0000">' . _AM_MARTIN_DRAFT . '</div>',
            '<div style="background-color:#00FF00">' . _AM_MARTIN_PUBLISHED . '</div>'
        ];

        $Cout          = $promotionHandler->getCount();
        $PromotionObjs = $promotionHandler->getPromotions($helper->getConfig('perpage'), $start, 0);

        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new \XoopsPageNav($Cout, $helper->getConfig('perpage'), $start, 'start');
        $pavStr  = '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

        // Creating the objects for top categories
        echo $pavStr . "<table width='100%' cellspacing=1 cellpadding=5 border=0 class = outer>";
        echo '<tr>';
        echo "<td class='bg3' align='left'><b>ID</b></td>";
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_PROMO_HOTELS . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_PROMO_START . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_PROMO_END . '</b></td>';
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_MARTIN_ACTIONS . '</b></td>';
        echo '</tr>';
        if (count($PromotionObjs) > 0) {
            foreach ($PromotionObjs as $key => $thiscat) {
                $modify = "<a href='?action=add&id=" . $thiscat->promotion_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/edit.gif'></a>";
                $delete = "<a href='?action=del&id=" . $thiscat->promotion_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/delete.gif'></a>";
                echo "<tr><td class='even' align='left'>" . $thiscat->promotion_id() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->hotel_name() . '</td>';
                echo "<td class='even' align='left'>" . date('Y-m-d', $thiscat->promotion_start_date()) . '</td>';
                echo "<td class='even' align='left'>" . date('Y-m-d', $thiscat->promotion_end_date()) . '</td>';
                echo "<td class='even' align='center'>  $modify $delete </td></tr>";
            }
        } else {
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '5'>" . MARTIN_IS_NUll . '</td>';
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
        'addservicetype'  => ['url' => 'martin.hotel.promotion.php?action=add', 'value' => _AM_MARTIN_PROMO_ADD],
        'servicetypelist' => ['url' => 'martin.hotel.promotion.php?action=list', 'value' => _AM_MARTIN_PROMO_LIST],
    ];
    Create_button($arr);
}

//底部
//require_once martin.footer.php;
require_once __DIR__ . '/admin_footer.php';

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
$action        = isset($_POST['action']) ? $_POST['action'] : @$_GET['action'];
$action        = empty($action) ? 'list' : $action;
$action        = trim(strtolower($action));
$id            = !empty($_POST['id']) ? $_POST['id'] : @$_GET['id'];
$id            = (int)$id;
$start         = isset($_GET['start']) ? (int)$_GET['start'] : 0;
$city_parentid = isset($_GET['city_parentid']) ? (int)$_GET['city_parentid'] : 0;
//确认删除
$confirm = isset($_POST['confirm']) ? $_POST['confirm'] : 0;
//parameter 参数

// martin_adminMenu(5, "订房后台 > 城市管理");

$hotelcityHandler = xoops_getModuleHandler('hotelcity', MARTIN_DIRNAME, true);

if ($id) {
    $HotelCityObj = $hotelcityHandler->get($id);
} else {
    $HotelCityObj = $hotelcityHandler->create();
}
//var_dump($HotelCityObj);
//var_dump($hotelcityHandler);
//var_dump($hotelcityHandler->city_name());

switch ($action) {
    case 'add':
        include MARTIN_ROOT_PATH . 'include/form.hotel.city.php';
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_ADD_HOTEL_CITY, _AM_MARTIN_ADD_HOTEL_CITY);
        //Create_button(array('addcity'=>array('url'=>'mconfirmartin.hotel.city.php?action=add','value'=>_AM_MARTIN_CITY_NAME)));
        $form = new form_hotel_city($HotelCityObj);

        $form->display();
        martin_close_collapsable('createtable', 'createtableicon');
        break;

    case 'save':
        $city_alias    = isset($_POST['city_alias']) ? addslashes($_POST['city_alias']) : '';
        $city_parentid = isset($_POST['city_parentid']) ? (int)$_POST['city_parentid'] : 0;
        $city_alias    = $city_parentid ? '' : $city_alias;
        $HotelCityObj->setVar('city_id', $id);
        $HotelCityObj->setVar('city_parentid', $city_parentid);
        $HotelCityObj->setVar('city_name', isset($_POST['city_name']) ? addslashes($_POST['city_name']) : '');
        $HotelCityObj->setVar('city_alias', $city_alias);
        $HotelCityObj->setVar('city_level', 0);

        if (!$id) {
            $HotelCityObj->setNew();
        }

        if ($HotelCityObj->isNew()) {
            $redirect_msg = _AM_MARTIN_ADDED_SUCCESSFULLY;
            $redirect_to  = 'martin.hotel.city.php';
        } else {
            $redirect_msg = _AM_MARTIN_MODIFIED_SUCCESSFULLY;
            $redirect_to  = 'martin.hotel.city.php';
        }
        if (!$hotelcityHandler->insert($HotelCityObj)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_MARTIN_OPERATION_FAILED);
        }
        redirect_header($redirect_to, 2, $redirect_msg);
        break;
    case 'del':
        if (!$confirm) {
            xoops_confirm([
                              'op'      => 'del',
                              'id'      => $HotelCityObj->city_id(),
                              'confirm' => 1,
                              'name'    => $HotelCityObj->city_name()
                          ], '?action=del', _DELETE . " '" . $HotelCityObj->city_name() . "'. <br> <br>" . _AM_MARTIN_OK_TO_DELETE_THE_DISTRICTS, _DELETE);
        } else {
            if ($hotelcityHandler->delete($HotelCityObj)) {
                $redirect_msg = _AM_MARTIN_OK_TO_DELETE_THE_ORDER;
                $redirect_to  = 'martin.hotel.city.php';
            } else {
                $redirect_msg = _AM_MARTIN_DELETE_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }
        break;
    case 'list':
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_ADD_CITY, _AM_MARTIN_ADD_CITY);
        Create_button([
                          'addcity' => [
                              'url'   => 'martin.hotel.city.php?action=add',
                              'value' => _AM_MARTIN_CITY_NAME
                          ]
                      ]);

        $HoteCityObjs = $hotelcityHandler->getHotelCitys($helper->getConfig('perpage'), $start, 0);

        // Creating the objects for top categories
        echo "<br>\n<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
        echo '<tr>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_CITY_NAME . '</b></td>';
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_MARTIN_ACTIONS . '</b></td>';
        echo '</tr>';
        $CityCout = $hotelcityHandler->getCount();
        if (count($HoteCityObjs) > 0) {
            foreach ($HoteCityObjs as $key => $thiscat) {
                display($thiscat);
            }
        } else {
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '7'>" . MARTIN_IS_NUll . '</td>';
            echo '</tr>';
            $categoryid = '0';
        }
        echo "</table>\n";
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new \XoopsPageNav($CityCout, $helper->getConfig('perpage'), 0, 'start');
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
        echo '<br>';
        martin_close_collapsable('createtable', 'createtableicon');
        echo '<br>';

        break;
    default:
        redirect_header(XOOPS_URL, 2, _AM_MARTIN_UNAUTHORIZED_ACCESS);
        break;
}

/**
 * @param     $HotelCityObj
 * @param int $level
 */
function display($HotelCityObj, $level = 0)
{
    global $xoopsModule, $hotelcityHandler;
    $modify = "<a href='?action=add&id=" . $HotelCityObj->city_id() . '&city_parentid=' . $HotelCityObj->city_parentid() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/edit.gif'></a>";
    $delete = "<a href='?action=del&id=" . $HotelCityObj->city_id() . '&city_parentid=' . $HotelCityObj->city_parentid() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/delete.gif'></a>";

    $spaces = '';
    for ($j = 0; $j < $level; $j++) {
        $spaces .= '&nbsp;&nbsp;&nbsp;';
    }
    echo '<tr>';
    echo "<td class='even' align='lefet'>" . $spaces . "<a href='" . XOOPS_URL . '/hotel/' . $HotelCityObj->city_alias() . "'><img src='" . XOOPS_URL . "/modules/martin/images/icon/subcat.gif' alt=''>&nbsp;" . $HotelCityObj->city_name() . '</a></td>';
    echo "<td class='even' align='center'> $modify $delete </td>";
    echo '</tr>';
    $subObj = $hotelcityHandler->getHotelCitys(0, 0, $HotelCityObj->city_id());
    if (count($subObj) > 0) {
        $level++;
        foreach ($subObj as $key => $thiscat) {
            display($thiscat, $level);
        }
    }
    unset($HotelCityObj);
}

//底部
require_once __DIR__ . '/admin_footer.php';

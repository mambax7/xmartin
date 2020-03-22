<?php

use XoopsModules\Xmartin;

require_once __DIR__ . '/admin_header.php';
/*
 * 处理
 **/

//头部
//require_once __DIR__ . '/martin.header.php';

/** @var Xmartin\Helper $helper */
$helper = Xmartin\Helper::getInstance();

$currentFile = basename(__FILE__);
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation($currentFile);

//parameter 参数
$action        = isset($_POST['action']) ? $_POST['action'] : @$_GET['action'];
$action        = empty($action) ? 'list' : $action;
$action        = trim(mb_strtolower($action));
$id            = !empty($_POST['id']) ? $_POST['id'] : @$_GET['id'];
$id            = (int)$id;
$start         = \Xmf\Request::getInt('start', 0, 'GET');
$city_parentid = \Xmf\Request::getInt('city_parentid', 0, 'GET');
//确认删除
$confirm = \Xmf\Request::getInt('confirm', 0, 'POST');
//parameter 参数

// martin_adminMenu(5, "订房后台 > 城市管理");

$cityHandler = $helper->getHandler('City');

if ($id) {
    $cityObj = $cityHandler->get($id);
} else {
    $cityObj = $cityHandler->create();
}
//var_dump($cityObj);
//var_dump($cityHandler);
//var_dump($cityHandler->city_name());

switch ($action) {
    case 'add':
        martin_collapsableBar('createtable', 'createtableicon', _AM_XMARTIN_ADD_HOTEL_CITY, _AM_XMARTIN_ADD_HOTEL_CITY);
        //Create_button(array('addcity'=>array('url'=>'mconfircity.php?action=add','value'=>_AM_XMARTIN_CITY_NAME)));
        $form = new Xmartin\Form\FormCity($cityObj);

        $form->display();
        martin_close_collapsable('createtable', 'createtableicon');
        break;
    case 'save':
        $city_alias    = isset($_POST['city_alias']) ? addslashes($_POST['city_alias']) : '';
        $city_parentid = \Xmf\Request::getInt('city_parentid', 0, 'POST');
        $city_alias    = $city_parentid ? '' : $city_alias;
        $cityObj->setVar('city_id', $id);
        $cityObj->setVar('city_parentid', $city_parentid);
        $cityObj->setVar('city_name', isset($_POST['city_name']) ? addslashes($_POST['city_name']) : '');
        $cityObj->setVar('city_alias', $city_alias);
        $cityObj->setVar('city_level', 0);

        if (!$id) {
            $cityObj->setNew();
        }

        if ($cityObj->isNew()) {
            $redirect_msg = _AM_XMARTIN_ADDED_SUCCESSFULLY;
            $redirect_to  = 'city.php';
        } else {
            $redirect_msg = _AM_XMARTIN_MODIFIED_SUCCESSFULLY;
            $redirect_to  = 'city.php';
        }
        if (!$cityHandler->insert($cityObj)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_XMARTIN_OPERATION_FAILED);
        }
        redirect_header($redirect_to, 2, $redirect_msg);
        break;
    case 'del':
        if (!$confirm) {
            xoops_confirm(
                [
                    'op'      => 'del',
                    'id'      => $cityObj->city_id(),
                    'confirm' => 1,
                    'name'    => $cityObj->city_name(),
                ],
                '?action=del',
                _DELETE . " '" . $cityObj->city_name() . "'. <br> <br>" . _AM_XMARTIN_OK_TO_DELETE_THE_DISTRICTS,
                _DELETE
            );
        } else {
            if ($cityHandler->delete($cityObj)) {
                $redirect_msg = _AM_XMARTIN_OK_TO_DELETE_THE_ORDER;
                $redirect_to  = 'city.php';
            } else {
                $redirect_msg = _AM_XMARTIN_DELETE_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }
        break;
    case 'list':
        martin_collapsableBar('createtable', 'createtableicon', _AM_XMARTIN_ADD_CITY, _AM_XMARTIN_ADD_CITY);
        Create_button(
            [
                'addcity' => [
                    'url'   => 'city.php?action=add',
                    'value' => _AM_XMARTIN_CITY_NAME,
                ],
            ]
        );

        $cityObjects = $cityHandler->getHotelCitys($helper->getConfig('perpage'), $start, 0);

        // Creating the objects for top categories
        echo "<br>\n<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
        echo '<tr>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_CITY_NAME . '</b></td>';
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_XMARTIN_ACTIONS . '</b></td>';
        echo '</tr>';
        $cityCount = $cityHandler->getCount();
        if (count($cityObjects) > 0) {
            foreach ($cityObjects as $key => $thiscat) {
                display($thiscat);
            }
        } else {
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '7'>" . XMARTIN_IS_NUll . '</td>';
            echo '</tr>';
            $categoryid = '0';
        }
        echo "</table>\n";
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new \XoopsPageNav($cityCount, $helper->getConfig('perpage'), 0, 'start');
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
        echo '<br>';
        martin_close_collapsable('createtable', 'createtableicon');
        echo '<br>';

        break;
    default:
        redirect_header(XOOPS_URL, 2, _AM_XMARTIN_UNAUTHORIZED_ACCESS);
        break;
}

/**
 * @param     $cityObj
 * @param int $level
 */
function display($cityObj, $level = 0)
{
    global $xoopsModule, $cityHandler;
    $modify = "<a href='?action=add&id=" . $cityObj->city_id() . '&city_parentid=' . $cityObj->city_parentid() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/edit.gif'></a>";
    $delete = "<a href='?action=del&id=" . $cityObj->city_id() . '&city_parentid=' . $cityObj->city_parentid() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/delete.gif'></a>";

    $spaces = '';
    for ($j = 0; $j < $level; $j++) {
        $spaces .= '&nbsp;&nbsp;&nbsp;';
    }
    echo '<tr>';
    echo "<td class='even' align='lefet'>" . $spaces . "<a href='" . XOOPS_URL . '/hotel/' . $cityObj->city_alias() . "'><img src='" . XOOPS_URL . "/modules/xmartin/images/icon/subcat.gif' alt=''>&nbsp;" . $cityObj->city_name() . '</a></td>';
    echo "<td class='even' align='center'> $modify $delete </td>";
    echo '</tr>';
    $subObj = $cityHandler->getHotelCitys(0, 0, $cityObj->city_id());
    if (count($subObj) > 0) {
        $level++;
        foreach ($subObj as $key => $thiscat) {
            display($thiscat, $level);
        }
    }
    unset($cityObj);
}

//底部
require_once __DIR__ . '/admin_footer.php';

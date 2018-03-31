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
$action     = isset($_POST['action']) ? $_POST['action'] : @$_GET['action'];
$action     = empty($action) ? 'list' : $action;
$action     = trim(strtolower($action));
$id         = !empty($_POST['id']) ? $_POST['id'] : @$_GET['id'];
$id         = (int)$id;
$typeid     = !empty($_POST['typeid']) ? (int)$_POST['typeid'] : (int)(@$_GET['typeid']);
$hotel_id   = \Xmf\Request::getInt('hotel_id', 0, 'GET');
$service_id = \Xmf\Request::getInt('service_id', 0, 'GET');
$start      = \Xmf\Request::getInt('start', 0, 'GET');
//确认删除
$confirm = \Xmf\Request::getInt('confirm', 0, POST);
//parameter 参数

// martin_adminMenu(3, "订房后台 > 酒店服务");

$hotelserviceHandler     = xoops_getModuleHandler('hotelservice', MARTIN_DIRNAME, true);
$hotelservicetypeHandler = xoops_getModuleHandler('hotelservicetype', MARTIN_DIRNAME, true);

$HotelServiceObj     = $id > 0 ? $hotelserviceHandler->get($id) : $hotelserviceHandler->create();
$HotelServiceTypeObj = $typeid > 0 ? $hotelservicetypeHandler->get($typeid) : $hotelservicetypeHandler->create();

switch ($action) {
    case 'add':
        include MARTIN_ROOT_PATH . 'include/form.hotel.service.php';
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_ADD_SERVICE, _AM_MARTIN_ADD_SERVICE);
        CreateButton();
        $TypeList = $hotelservicetypeHandler->GetList();
        $form     = new form_hotel_service($HotelServiceObj, $TypeList);
        $form->display();
        martin_close_collapsable('createtable', 'createtableicon');
        break;
    case 'typeadd':
        include MARTIN_ROOT_PATH . 'include/form.hotel.service.type.php';
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_ADD_SERVICE_TYPE, _AM_MARTIN_ADD_SERVICE_TYPE);
        CreateButton();
        $form = new form_hotel_service_type($HotelServiceTypeObj);
        $form->display();
        martin_close_collapsable('createtable', 'createtableicon');
        break;
    case 'addhotel':
        include MARTIN_ROOT_PATH . 'include/form.hotel.service.relation.php';
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_ADD_HOTEL_SERVICE, _AM_MARTIN_ADD_HOTEL_SERVICE);
        CreateButton();
        $serviceList = $hotelserviceHandler->getServiceList($service_id);
        $hotelList   = $hotelserviceHandler->getHotelList($hotel_id);
        $Relation    = $hotelserviceHandler->getHotelServiceRelation($hotel_id, $service_id);
        $form        = new form_hotel_service_relation($Relation, $serviceList, $hotelList);
        $form->display();
        martin_close_collapsable('createtable', 'createtableicon');
        break;
    case 'save':
        $HotelServiceObj->setVar('service_id', $id);
        $HotelServiceObj->setVar('service_type_id', isset($_POST['service_type_id']) ? (int)$_POST['service_type_id'] : 0);
        $HotelServiceObj->setVar('service_unit', isset($_POST['service_unit']) ? addslashes($_POST['service_unit']) : '');
        $HotelServiceObj->setVar('service_name', isset($_POST['service_name']) ? addslashes($_POST['service_name']) : '');
        $HotelServiceObj->setVar('service_instruction', isset($_POST['service_instruction']) ? addslashes($_POST['service_instruction']) : '');
        if (!$id) {
            $HotelServiceObj->setNew();
        }
        if ($HotelServiceObj->isNew()) {
            $redirect_msg = _AM_MARTIN_ADDED_SUCCESSFULLY;
        } else {
            $redirect_msg = _AM_MARTIN_MODIFIED_SUCCESSFULLY;
        }
        $redirect_to = 'martin.hotel.service.php?action=list';
        if (!$hotelserviceHandler->insert($HotelServiceObj)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_MARTIN_OPERATION_FAILED);
        }
        redirect_header($redirect_to, 2, $redirect_msg);
        break;
    case 'typesave':
        $HotelServiceTypeObj->setVar('service_type_id', $typeid);
        $HotelServiceTypeObj->setVar('service_type_name', isset($_POST['service_type_name']) ? addslashes($_POST['service_type_name']) : '');
        if (!$typeid) {
            $HotelServiceTypeObj->setNew();
        }
        if ($HotelServiceTypeObj->isNew()) {
            $redirect_msg = _AM_MARTIN_ADDED_SUCCESSFULLY;
        } else {
            $redirect_msg = _AM_MARTIN_MODIFIED_SUCCESSFULLY;
        }
        $redirect_to = 'martin.hotel.service.php?action=typelist';
        if (!$hotelservicetypeHandler->insert($HotelServiceTypeObj)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_MARTIN_OPERATION_FAILED);
        }
        redirect_header($redirect_to, 2, $redirect_msg);
        break;
    case 'hotelsave':
        $RelationData = [
            'hotel_id'            => (int)$_POST['hotel_id'],
            'service_id'          => (int)$_POST['service_id'],
            'service_extra_price' => (int)$_POST['service_extra_price']
        ];

        $IsOld        = false;
        $redirect_msg = _AM_MARTIN_ADDED_SUCCESSFULLY;
        if ($hotel_id && $service_id) {
            $IsOld        = true;
            $redirect_msg = _AM_MARTIN_MODIFIED_SUCCESSFULLY;
            $RelationData = [
                'hotel_id'            => $hotel_id,
                'service_id'          => $service_id,
                'service_extra_price' => (int)$_POST['service_extra_price']
            ];
        }
        $redirect_to = 'martin.hotel.service.php?action=hotellist';

        //var_dump($IsOld);
        //var_dump($RelationData);exit;

        if (!$hotelserviceHandler->InsertRelation($RelationData, $IsOld)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_MARTIN_OPERATION_FAILED . '<br>' . _AM_MARTIN_ERROR_DUPLICATION);
        }
        redirect_header($redirect_to, 2, $redirect_msg);

        break;
    case 'del':
        if (!$confirm) {
            xoops_confirm(['op' => 'del', 'id' => $id, 'confirm' => 1, 'name' => $HotelServiceObj->service_name()], '?action=del', _DELETE . " '" . $HotelServiceObj->service_name() . "'. <br> <br> " . _AM_MARTIN_OK_TO_DELETE_SERVICE, _DELETE);
        } else {
            if ($hotelserviceHandler->delete($HotelServiceObj)) {
                $redirect_msg = _AM_MARTIN_OK_TO_DELETE_THE_ORDER;
                $redirect_to  = 'martin.hotel.service.php';
            } else {
                $redirect_msg = _AM_MARTIN_DELETE_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }
        break;
    case 'typedel':
        if (!$confirm) {
            xoops_confirm([
                              'op'      => 'del',
                              'typeid'  => $typeid,
                              'confirm' => 1,
                              'name'    => $HotelServiceTypeObj->service_type_name()
                          ], '?action=typedel', _DELETE . " '" . $HotelServiceTypeObj->service_type_name() . "'. <br> <br> " . _AM_MARTIN_OK_TO_DELETE_SERVICE_CATEGORY, _DELETE);
        } else {
            if ($hotelservicetypeHandler->delete($HotelServiceTypeObj)) {
                $redirect_msg = _AM_MARTIN_OK_TO_DELETE_THE_ORDER;
                $redirect_to  = 'martin.hotel.service.php?action=typelist';
            } else {
                $redirect_msg = _AM_MARTIN_DELETE_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }
        break;
    case 'hoteldel':
        $Relation = $hotelserviceHandler->getHotelServiceRelation($hotel_id, $service_id);
        if (!$confirm) {
            xoops_confirm([
                              'op'       => 'del',
                              'hotel_id' => $hotel_id,
                              'confirm'  => 1,
                              'name'     => $Relation['hotel_name']
                          ], "?action=hoteldel&hotel_id=$hotel_id&service_id=$service_id", _DELETE . " '" . $Relation['hotel_name'] . ' : ' . $Relation['service_name'] . "'. <br> <br>" . _AM_MARTIN_SURE_TO_DELETE_RELATION, _DELETE);
        } else {
            if ($hotelserviceHandler->DeleteServiceRelation($hotel_id, $service_id)) {
                $redirect_msg = _AM_MARTIN_OK_TO_DELETE_THE_ORDER;
                $redirect_to  = 'martin.hotel.service.php?action=hotellist';
            } else {
                $redirect_msg = _AM_MARTIN_DELETE_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }
        break;
    case 'list':
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_SERVICE_LIST, _AM_MARTIN_SERVICE_LIST);
        CreateButton();
        $HotelServiceObjs = $hotelserviceHandler->getHotelServices($helper->getConfig('perpage'), $start, 0);

        echo "<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
        echo '<tr>';
        echo "<td class='bg3' align='left'><b>ID</b></td>";
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_SERVICE_TYPE . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_SERVICE_UNIT . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_SERVICE_NAME . '</b></td>';
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_MARTIN_ACTIONS . '</b></td>';
        echo '</tr>';
        $Cout = $hotelserviceHandler->getCount();
        if (count($HotelServiceObjs) > 0) {
            foreach ($HotelServiceObjs as $key => $thiscat) {
                $modify = "<a href='?action=add&id=" . $thiscat->service_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/edit.gif'></a>";
                $delete = "<a href='?action=del&id=" . $thiscat->service_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/delete.gif'></a>";
                echo "<tr><td class='even' align='left'>" . $thiscat->service_id() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->service_type_name() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->service_unit() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->service_name() . '</td>';
                echo "<td class='even' align='center'> $modify $delete </td></tr>";
            }
        } else {
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '4'>" . MARTIN_IS_NUll . '</td>';
            echo '</tr>';
        }
        echo "</table>\n";
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new \XoopsPageNav($Cout, $helper->getConfig('perpage'), $start, "action=$action&start");
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
        echo '<br>';
        martin_close_collapsable('createtable', 'createtableicon');
        echo '<br>';
        break;
    case 'typelist':
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_SERVICE_TYPE_LIST, _AM_MARTIN_SERVICE_TYPE_LIST);
        CreateButton();
        $HotelServiceTypeObjs = $hotelservicetypeHandler->getHotelServiceTypes($helper->getConfig('perpage'), $start, 0);

        echo "<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
        echo '<tr>';
        echo "<td class='bg3' align='left'><b>ID</b></td>";
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_SERVICE_TYPE . '</b></td>';
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_MARTIN_ACTIONS . '</b></td>';
        echo '</tr>';
        $Cout = $hotelservicetypeHandler->getCount();
        if (count($HotelServiceTypeObjs) > 0) {
            foreach ($HotelServiceTypeObjs as $key => $thiscat) {
                $modify = "<a href='?action=typeadd&typeid=" . $thiscat->service_type_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/edit.gif'></a>";
                $delete = "<a href='?action=typedel&typeid=" . $thiscat->service_type_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/delete.gif'></a>";
                echo "<tr><td class='even' align='lefet'>" . $thiscat->service_type_id() . '</td>';
                echo "<td class='even' align='lefet'>" . $thiscat->service_type_name() . '</td>';
                echo "<td class='even' align='center'> $modify $delete </td></tr>";
            }
        } else {
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '3'>" . MARTIN_IS_NUll . '</td>';
            echo '</tr>';
        }
        echo "</table>\n";
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new \XoopsPageNav($Cout, $helper->getConfig('perpage'), $start, "action=$action&start");
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
        echo '<br>';
        martin_close_collapsable('createtable', 'createtableicon');
        echo '<br>';
        break;
    case 'hotellist':
        martin_collapsableBar('createtable', 'createtableicon', _AM_MARTIN_HOTEL_SERVICE_LIST, _AM_MARTIN_HOTEL_SERVICE_LIST);
        CreateButton();
        $HotelServiceRelations = $hotelserviceHandler->getHotelServiceRelations($helper->getConfig('perpage'), $start);

        echo "<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
        echo '<tr>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_HOTEL_NAME . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_SERVICE_NAME . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_MARTIN_SERVICE_PRICES . '</b></td>';
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_MARTIN_ACTIONS . '</b></td>';
        echo '</tr>';
        $Cout = $hotelserviceHandler->GetRelationCount();
        if (count($HotelServiceRelations) > 0) {
            foreach ($HotelServiceRelations as $key => $relation) {
                $modify = "<a href='?action=addhotel&hotel_id={$relation['hotel_id']}&service_id={$relation['service_id']}'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/edit.gif'></a>";
                $delete = "<a href='?action=hoteldel&hotel_id={$relation['hotel_id']}&service_id={$relation['service_id']}'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/delete.gif'></a>";
                echo "<td class='even' align='left'>" . $relation['hotel_name'] . '</td>';
                echo "<td class='even' align='left'>" . $relation['service_name'] . '</td>';
                echo "<td class='even' align='left'>" . $relation['service_extra_price'] . '  </td>';
                echo "<td class='even' align='center'> $modify $delete </td></tr>";
            }
        } else {
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '4'>" . MARTIN_IS_NUll . '</td>';
            echo '</tr>';
        }
        echo "</table>\n";
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new \XoopsPageNav($Cout, $helper->getConfig('perpage'), $start, "action=$action&start");
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
    Create_button([
                      'addservicetype'  => [
                          'url'   => 'martin.hotel.service.php?action=typeadd',
                          'value' => _AM_MARTIN_ADD_SERVICE_TYPE
                      ],
                      'servicetypelist' => [
                          'url'   => 'martin.hotel.service.php?action=typelist',
                          'value' => _AM_MARTIN_SERVICE_TYPE_LIST
                      ],
                      'addservice'      => [
                          'url'   => 'martin.hotel.service.php?action=add',
                          'value' => _AM_MARTIN_ADD_SERVICE
                      ],
                      'servicetype'     => [
                          'url'   => 'martin.hotel.service.php?action=list',
                          'value' => _AM_MARTIN_SERVICE_LIST
                      ],
                      'addhotel'        => [
                          'url'   => 'martin.hotel.service.php?action=addhotel',
                          'value' => _AM_MARTIN_ADD_HOTEL_SERVICE
                      ],
                      'hotelservice'    => [
                          'url'   => 'martin.hotel.service.php?action=hotellist',
                          'value' => _AM_MARTIN_HOTEL_SERVICE_LIST
                      ]
                  ]);
}

//底部
require_once __DIR__ . '/admin_footer.php';

<?php

use XoopsModules\Xmartin;

require_once __DIR__ . '/admin_header.php';

/** @var Xmartin\Helper $helper */
$helper = Xmartin\Helper::getInstance();

/*
 * 处理
 **/

//头部
require_once __DIR__ . '/martin.header.php';
$currentFile = basename(__FILE__);
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation($currentFile);

//parameter 参数
$action     = isset($_POST['action']) ? $_POST['action'] : @$_GET['action'];
$action     = empty($action) ? 'list' : $action;
$action     = trim(mb_strtolower($action));
$id         = !empty($_POST['id']) ? $_POST['id'] : @$_GET['id'];
$id         = (int)$id;
$typeid     = \Xmf\Request::getInt('typeid', 0);
$hotel_id   = \Xmf\Request::getInt('hotel_id', 0, 'GET');
$service_id = \Xmf\Request::getInt('service_id', 0, 'GET');
$start      = \Xmf\Request::getInt('start', 0, 'GET');
//确认删除
$confirm = \Xmf\Request::getInt('confirm', 0, 'POST');
//parameter 参数

// martin_adminMenu(3, "订房后台 > 酒店服务");

$hotelserviceHandler     = $helper->getHandler('HotelService');
$hotelservicetypeHandler = $helper->getHandler('ServiceType');

$hotelServiceObj     = $id > 0 ? $hotelserviceHandler->get($id) : $hotelserviceHandler->create();
$hotelServiceTypeObj = $typeid > 0 ? $hotelservicetypeHandler->get($typeid) : $hotelservicetypeHandler->create();

switch ($action) {
    case 'add':
        martin_collapsableBar('createtable', 'createtableicon', _AM_XMARTIN_ADD_SERVICE, _AM_XMARTIN_ADD_SERVICE);
        CreateButton();
        $TypeList = $hotelservicetypeHandler->getList();
        $form     = new Xmartin\Form\FormHotelService($hotelServiceObj, $TypeList);
        $form->display();
        martin_close_collapsable('createtable', 'createtableicon');
        break;
    case 'typeadd':
        martin_collapsableBar('createtable', 'createtableicon', _AM_XMARTIN_ADD_SERVICE_TYPE, _AM_XMARTIN_ADD_SERVICE_TYPE);
        CreateButton();
        $form = new Xmartin\Form\FormServiceType($hotelServiceTypeObj);
        $form->display();
        martin_close_collapsable('createtable', 'createtableicon');
        break;
    case 'addhotel':
        martin_collapsableBar('createtable', 'createtableicon', _AM_XMARTIN_ADD_HOTEL_SERVICE, _AM_XMARTIN_ADD_HOTEL_SERVICE);
        CreateButton();
        $serviceList = $hotelserviceHandler->getServiceList($service_id);
        $hotelList   = $hotelserviceHandler->getHotelList($hotel_id);
        $Relation    = $hotelserviceHandler->getHotelServiceRelation($hotel_id, $service_id);
        $form        = new Xmartin\Form\FormHotelServiceRelation($Relation, $serviceList, $hotelList);
        $form->display();
        martin_close_collapsable('createtable', 'createtableicon');
        break;
    case 'save':
        $hotelServiceObj->setVar('service_id', $id);
        $hotelServiceObj->setVar('service_type_id', \Xmf\Request::getInt('service_type_id', 0, 'POST'));
        $hotelServiceObj->setVar('service_unit', isset($_POST['service_unit']) ? addslashes($_POST['service_unit']) : '');
        $hotelServiceObj->setVar('service_name', isset($_POST['service_name']) ? addslashes($_POST['service_name']) : '');
        $hotelServiceObj->setVar('service_instruction', isset($_POST['service_instruction']) ? addslashes($_POST['service_instruction']) : '');
        if (!$id) {
            $hotelServiceObj->setNew();
        }
        if ($hotelServiceObj->isNew()) {
            $redirect_msg = _AM_XMARTIN_ADDED_SUCCESSFULLY;
        } else {
            $redirect_msg = _AM_XMARTIN_MODIFIED_SUCCESSFULLY;
        }
        $redirect_to = 'martin.hotel.service.php?action=list';
        if (!$hotelserviceHandler->insert($hotelServiceObj)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_XMARTIN_OPERATION_FAILED);
        }
        redirect_header($redirect_to, 2, $redirect_msg);
        break;
    case 'typesave':
        $hotelServiceTypeObj->setVar('service_type_id', $typeid);
        $hotelServiceTypeObj->setVar('service_type_name', isset($_POST['service_type_name']) ? addslashes($_POST['service_type_name']) : '');
        if (!$typeid) {
            $hotelServiceTypeObj->setNew();
        }
        if ($hotelServiceTypeObj->isNew()) {
            $redirect_msg = _AM_XMARTIN_ADDED_SUCCESSFULLY;
        } else {
            $redirect_msg = _AM_XMARTIN_MODIFIED_SUCCESSFULLY;
        }
        $redirect_to = 'martin.hotel.service.php?action=typelist';
        if (!$hotelservicetypeHandler->insert($hotelServiceTypeObj)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_XMARTIN_OPERATION_FAILED);
        }
        redirect_header($redirect_to, 2, $redirect_msg);
        break;
    case 'hotelsave':
        $RelationData = [
            'hotel_id'            => \Xmf\Request::getInt('hotel_id', 0, 'POST'),
            'service_id'          => \Xmf\Request::getInt('service_id', 0, 'POST'),
            'service_extra_price' => \Xmf\Request::getInt('service_extra_price', 0, 'POST'),
        ];

        $IsOld        = false;
        $redirect_msg = _AM_XMARTIN_ADDED_SUCCESSFULLY;
        if ($hotel_id && $service_id) {
            $IsOld        = true;
            $redirect_msg = _AM_XMARTIN_MODIFIED_SUCCESSFULLY;
            $RelationData = [
                'hotel_id'            => $hotel_id,
                'service_id'          => $service_id,
                'service_extra_price' => \Xmf\Request::getInt('service_extra_price', 0, 'POST'),
            ];
        }
        $redirect_to = 'martin.hotel.service.php?action=hotellist';

        //var_dump($IsOld);
        //var_dump($RelationData);exit;

        if (!$hotelserviceHandler->insertRelation($RelationData, $IsOld)) {
            redirect_header('javascript:history.go(-1);', 2, _AM_XMARTIN_OPERATION_FAILED . '<br>' . _AM_XMARTIN_ERROR_DUPLICATION);
        }
        redirect_header($redirect_to, 2, $redirect_msg);

        break;
    case 'del':
        if (!$confirm) {
            xoops_confirm(['op' => 'del', 'id' => $id, 'confirm' => 1, 'name' => $hotelServiceObj->service_name()], '?action=del', _DELETE . " '" . $hotelServiceObj->service_name() . "'. <br> <br> " . _AM_XMARTIN_OK_TO_DELETE_SERVICE, _DELETE);
        } else {
            if ($hotelserviceHandler->delete($hotelServiceObj)) {
                $redirect_msg = _AM_XMARTIN_OK_TO_DELETE_THE_ORDER;
                $redirect_to  = 'martin.hotel.service.php';
            } else {
                $redirect_msg = _AM_XMARTIN_DELETE_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }
        break;
    case 'typedel':
        if (!$confirm) {
            xoops_confirm(
                [
                    'op'      => 'del',
                    'typeid'  => $typeid,
                    'confirm' => 1,
                    'name'    => $hotelServiceTypeObj->service_type_name(),
                ],
                '?action=typedel',
                _DELETE . " '" . $hotelServiceTypeObj->service_type_name() . "'. <br> <br> " . _AM_XMARTIN_OK_TO_DELETE_SERVICE_CATEGORY,
                _DELETE
            );
        } else {
            if ($hotelservicetypeHandler->delete($hotelServiceTypeObj)) {
                $redirect_msg = _AM_XMARTIN_OK_TO_DELETE_THE_ORDER;
                $redirect_to  = 'martin.hotel.service.php?action=typelist';
            } else {
                $redirect_msg = _AM_XMARTIN_DELETE_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }
        break;
    case 'hoteldel':
        $Relation = $hotelserviceHandler->getHotelServiceRelation($hotel_id, $service_id);
        if (!$confirm) {
            xoops_confirm(
                [
                    'op'       => 'del',
                    'hotel_id' => $hotel_id,
                    'confirm'  => 1,
                    'name'     => $Relation['hotel_name'],
                ],
                "?action=hoteldel&hotel_id=$hotel_id&service_id=$service_id",
                _DELETE . " '" . $Relation['hotel_name'] . ' : ' . $Relation['service_name'] . "'. <br> <br>" . _AM_XMARTIN_SURE_TO_DELETE_RELATION,
                _DELETE
            );
        } else {
            if ($hotelserviceHandler->deleteServiceRelation($hotel_id, $service_id)) {
                $redirect_msg = _AM_XMARTIN_OK_TO_DELETE_THE_ORDER;
                $redirect_to  = 'martin.hotel.service.php?action=hotellist';
            } else {
                $redirect_msg = _AM_XMARTIN_DELETE_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }
        break;
    case 'list':
        martin_collapsableBar('createtable', 'createtableicon', _AM_XMARTIN_SERVICE_LIST, _AM_XMARTIN_SERVICE_LIST);
        CreateButton();
        $hotelServiceObjects = $hotelserviceHandler->getHotelServices($helper->getConfig('perpage'), $start, 0);

        echo "<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
        echo '<tr>';
        echo "<td class='bg3' align='left'><b>ID</b></td>";
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_SERVICE_TYPE . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_SERVICE_UNIT . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_SERVICE_NAME . '</b></td>';
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_XMARTIN_ACTIONS . '</b></td>';
        echo '</tr>';
        $Cout = $hotelserviceHandler->getCount();
        if (count($hotelServiceObjects) > 0) {
            foreach ($hotelServiceObjects as $key => $thiscat) {
                $modify = "<a href='?action=add&id=" . $thiscat->service_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/edit.gif'></a>";
                $delete = "<a href='?action=del&id=" . $thiscat->service_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/delete.gif'></a>";
                echo "<tr><td class='even' align='left'>" . $thiscat->service_id() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->service_type_name() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->service_unit() . '</td>';
                echo "<td class='even' align='left'>" . $thiscat->service_name() . '</td>';
                echo "<td class='even' align='center'> $modify $delete </td></tr>";
            }
        } else {
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '4'>" . XMARTIN_IS_NUll . '</td>';
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
        martin_collapsableBar('createtable', 'createtableicon', _AM_XMARTIN_SERVICE_TYPE_LIST, _AM_XMARTIN_SERVICE_TYPE_LIST);
        CreateButton();
        $hotelServiceTypeObjects = $hotelservicetypeHandler->getHotelServiceTypes($helper->getConfig('perpage'), $start, 0);

        echo "<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
        echo '<tr>';
        echo "<td class='bg3' align='left'><b>ID</b></td>";
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_SERVICE_TYPE . '</b></td>';
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_XMARTIN_ACTIONS . '</b></td>';
        echo '</tr>';
        $Cout = $hotelservicetypeHandler->getCount();
        if (count($hotelServiceTypeObjects) > 0) {
            foreach ($hotelServiceTypeObjects as $key => $thiscat) {
                $modify = "<a href='?action=typeadd&typeid=" . $thiscat->service_type_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/edit.gif'></a>";
                $delete = "<a href='?action=typedel&typeid=" . $thiscat->service_type_id() . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/delete.gif'></a>";
                echo "<tr><td class='even' align='lefet'>" . $thiscat->service_type_id() . '</td>';
                echo "<td class='even' align='lefet'>" . $thiscat->service_type_name() . '</td>';
                echo "<td class='even' align='center'> $modify $delete </td></tr>";
            }
        } else {
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '3'>" . XMARTIN_IS_NUll . '</td>';
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
        martin_collapsableBar('createtable', 'createtableicon', _AM_XMARTIN_HOTEL_SERVICE_LIST, _AM_XMARTIN_HOTEL_SERVICE_LIST);
        CreateButton();
        $hotelServiceRelations = $hotelserviceHandler->getHotelServiceRelations($helper->getConfig('perpage'), $start);

        echo "<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
        echo '<tr>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_HOTEL_NAME . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_SERVICE_NAME . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_SERVICE_PRICES . '</b></td>';
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_XMARTIN_ACTIONS . '</b></td>';
        echo '</tr>';
        $Cout = $hotelserviceHandler->getRelationCount();
        if (count($hotelServiceRelations) > 0) {
            foreach ($hotelServiceRelations as $key => $relation) {
                $modify = "<a href='?action=addhotel&hotel_id={$relation['hotel_id']}&service_id={$relation['service_id']}'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/edit.gif'></a>";
                $delete = "<a href='?action=hoteldel&hotel_id={$relation['hotel_id']}&service_id={$relation['service_id']}'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/delete.gif'></a>";
                echo "<td class='even' align='left'>" . $relation['hotel_name'] . '</td>';
                echo "<td class='even' align='left'>" . $relation['service_name'] . '</td>';
                echo "<td class='even' align='left'>" . $relation['service_extra_price'] . '  </td>';
                echo "<td class='even' align='center'> $modify $delete </td></tr>";
            }
        } else {
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '4'>" . XMARTIN_IS_NUll . '</td>';
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
        redirect_header(XOOPS_URL, 2, _AM_XMARTIN_UNAUTHORIZED_ACCESS);
        break;
}

function CreateButton()
{
    Create_button(
        [
            'addservicetype'  => [
                'url'   => 'martin.hotel.service.php?action=typeadd',
                'value' => _AM_XMARTIN_ADD_SERVICE_TYPE,
            ],
            'servicetypelist' => [
                'url'   => 'martin.hotel.service.php?action=typelist',
                'value' => _AM_XMARTIN_SERVICE_TYPE_LIST,
            ],
            'addservice'      => [
                'url'   => 'martin.hotel.service.php?action=add',
                'value' => _AM_XMARTIN_ADD_SERVICE,
            ],
            'servicetype'     => [
                'url'   => 'martin.hotel.service.php?action=list',
                'value' => _AM_XMARTIN_SERVICE_LIST,
            ],
            'addhotel'        => [
                'url'   => 'martin.hotel.service.php?action=addhotel',
                'value' => _AM_XMARTIN_ADD_HOTEL_SERVICE,
            ],
            'hotelservice'    => [
                'url'   => 'martin.hotel.service.php?action=hotellist',
                'value' => _AM_XMARTIN_HOTEL_SERVICE_LIST,
            ],
        ]
    );
}

//底部
require_once __DIR__ . '/admin_footer.php';

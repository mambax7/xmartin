<?php

namespace XoopsModules\Xmartin\Form;

/**
 * @城市表单
 * @license   http://www.blags.org/
 * @created   :2010年05月20日 23时52分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */

//defined('XOOPS_ROOT_PATH') || exit('Restricted access');

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

/**
 * Class FormCity
 */
class FormCity extends \XoopsThemeForm
{
    /**
     * FormCity constructor.
     * @param $cityObj
     */
    public function __construct($cityObj)
    {
        $this->Obj = $cityObj;
        parent::__construct(_AM_XMARTIN_HOTEL_CITY, 'op', xoops_getenv('SCRIPT_NAME') . '?action=save');
        $this->setExtra('enctype="multipart/form-data"');

        $this->createElements();
        $this->createButtons();
    }

    /**
     * created elements
     * @license   http://www.blags.org/
     * @created   :2010年05月21日 20时40分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * */
    public function createElements()
    {
        global $xoopsDB;
        xoops_load('XoopsTree');
        $mytree = new \XoopsTree($xoopsDB->prefix('xmartin_hotel_city'), 'city_id', 'city_parentid');
        // Parent Category
        ob_start();
        $mytree->makeMySelBox('city_name', '', $this->Obj->city_parentid(), 1, 'city_parentid');
        //makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
        $this->addElement(new \XoopsFormLabel(_AM_XMARTIN_PARENT, ob_get_contents()));
        ob_end_clean();
        // City Name
        $this->addElement(new \XoopsFormText(_AM_XMARTIN_CITY_NAME, 'city_name', 50, 255, $this->Obj->city_name()), true);
        $this->addElement(new \XoopsFormText(_AM_XMARTIN_CITY_ALIAS, 'city_alias', 50, 255, $this->Obj->city_alias()), true);
        $this->addElement(new \XoopsFormHidden('id', $this->Obj->city_id()));
    }

    /**
     * @创建按钮
     * @license   http://www.blags.org/
     * @created   :2010年05月20日 23时52分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * */
    public function createButtons()
    {
        $buttonTray = new \XoopsFormElementTray('', '');
        // No ID for category -- then it's new category, button says 'Create'
        if (!$this->Obj->city_id()) {
            $butt_create = new \XoopsFormButton('', '', _SUBMIT, 'submit');
            $butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
            $buttonTray->addElement($butt_create);

            $butt_clear = new \XoopsFormButton('', '', _RESET, 'reset');
            $buttonTray->addElement($butt_clear);

            $butt_cancel = new \XoopsFormButton('', '', _CANCEL, 'button');
            $butt_cancel->setExtra('onclick="history.go(-1)"');
            $buttonTray->addElement($butt_cancel);

            $this->addElement($buttonTray);
        } else {
            // button says 'Update'
            $butt_create = new \XoopsFormButton('', '', _SUBMIT, 'submit');
            $butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
            $buttonTray->addElement($butt_create);

            $butt_clear = new \XoopsFormButton('', '', _RESET, 'reset');
            $buttonTray->addElement($butt_clear);

            $butt_cancel = new \XoopsFormButton('', '', _CANCEL, 'button');
            $butt_cancel->setExtra('onclick="history.go(-1)"');
            $buttonTray->addElement($butt_cancel);

            $this->addElement($buttonTray);
        }
    }
}

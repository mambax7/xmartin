<?php

namespace XoopsModules\Xmartin\Form;

/**
 * @城市表单
 * @license   http://www.blags.org/
 * @created   :2010年05月20日 23时52分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

/**
 * Class FormServiceType
 */
class FormServiceType extends \XoopsThemeForm
{
    /**
     * FormServiceType constructor.
     * @param $hotelServiceTypeObj
     */
    public function __construct($hotelServiceTypeObj)
    {
        $this->Obj = $hotelServiceTypeObj;
        parent::__construct(_AM_XMARTIN_SERVICE_CATEGORY, 'op', xoops_getenv('SCRIPT_NAME') . '?action=typesave');
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
        $Form              = new \XoopsFormElementTray($this->Formjs() . _AM_XMARTIN_SERVICE_TYPE);
        $service_type_name = new \XoopsFormText('', 'service_type_name', 50, 255, $this->Obj->service_type_name());
        $service_type_name->setExtra('class="required"');
        $Form->addElement($service_type_name, true);
        $this->addElement($Form, false);
        $this->addElement(new \XoopsFormHidden('typeid', $this->Obj->service_type_id()));
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
        if (!$this->Obj->service_type_id()) {
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
            $butt_create = new \XoopsFormButton('', '', _EDIT, 'submit');
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

    /**
     * @return string
     */
    public function Formjs()
    {
        return '  <script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.validate/1.17.0/jquery.validate.js"></script>
<style type="text/css">
label { width: 10em; float: left; }
label.error { float: none; color: red; padding-left: .5em; vertical-align: top; }
p { clear: both; }
.submit { margin-left: 12em; }
em { font-weight: bold; padding-right: 1em; vertical-align: top; }
</style>
  <script>
  $(document).ready(function(){
    $("#op").validate();
  });
  </script>';
    }
}

<?php namespace XoopsModules\Xmartin;

use Xmf\Request;
use XoopsModules\Xmartin;
use XoopsModules\Xmartin\Common;

/**
 * Class Utility
 */
class Utility
{
    use Common\VersionChecks; //checkVerXoops, checkVerPhp Traits

    use Common\ServerStats; // getServerStats Trait

    use Common\FilesManagement; // Files Management Trait

    //--------------- Custom module methods -----------------------------
}

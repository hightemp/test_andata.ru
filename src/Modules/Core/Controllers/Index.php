<?php

namespace Hightemp\WappFramework\Modules\Core\Controllers;

use Hightemp\WappFramework\Modules\Core\Lib\Controllers\BaseController;
use Hightemp\WappFramework\Modules\Core\View;

class Index extends BaseController
{
    public static $sDefaultViewClass = View::class;

    public function fnIndexHTML()
    {
        $aAliases = BaseController::fnGetAllAliasesLinks();

        View::fnAddVars([
            "aAliases" => $aAliases
        ]);
    }
}
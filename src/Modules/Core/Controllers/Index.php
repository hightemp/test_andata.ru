<?php

namespace Hightemp\AndataRu\Modules\Core\Controllers;

use Hightemp\AndataRu\Modules\Core\Lib\Controllers\BaseController;
use Hightemp\AndataRu\Modules\Core\Lib\View;

class Index extends BaseController
{
    public static $sDefaultViewClass = View::class;

    public function fnIndexHTML()
    {
        View::fnAddVars([
            "sTitle" => "Очень новая статья"
        ]);
    }
}
<?php

namespace Hightemp\AndataRu\Modules\Core;

class Module
{
    const NAME = "Core";

    public static $sDefaultController = \Hightemp\AndataRu\Modules\Core\Controllers\Index::class;
    public static $sDefaultMethod = "fnIndexHTML";

    public static $aControllers = [
        \Hightemp\AndataRu\Modules\Core\Controllers\Index::class
    ];

    public static $aModels = [
        
    ];

}
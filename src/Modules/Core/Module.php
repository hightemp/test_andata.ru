<?php

namespace Hightemp\AndataRu\Modules\Core;

class Module
{
    const NAME = "Core";

    public static string $sDefaultController = \Hightemp\AndataRu\Modules\Core\Controllers\Index::class;
    public static string $sDefaultMethod = "fnIndexHTML";

    /** @var array $aControllers список контроллеров для подгрузки */
    public static array $aControllers = [
        \Hightemp\AndataRu\Modules\Core\Controllers\Index::class
    ];
}
<?php

namespace Hightemp\AndataRu;

use Hightemp\AndataRu\Modules\Core\Lib\BaseProject;

class Project extends BaseProject
{
    public static $sProjectClassPath = __NAMESPACE__;
    public static $sProjectRootPath = __DIR__;

    /** @var string|BaseLogger $sLoggerClass */
    public static $sLoggerClass = \Hightemp\AndataRu\Modules\Debug\Loggers\SimpleJSONLLogger::class;

    public static $aPreload = [
        \Hightemp\AndataRu\Modules\Core\Module::class,
    ];

    public static $aModules = [
        \Hightemp\AndataRu\Modules\Core\Module::class,
    ];
}

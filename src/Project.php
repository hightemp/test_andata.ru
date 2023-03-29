<?php
declare(strict_types=1);

namespace Hightemp\AndataRu;

use Hightemp\AndataRu\Modules\Core\Lib\BaseProject;

class Project extends BaseProject
{
    public static array $aLoggers = [
        \Hightemp\AndataRu\Modules\Core\Lib\Loggers\SimpleJSONLLogger::class
    ];

    public static array $aPreload = [
        \Hightemp\AndataRu\Modules\Core\Module::class,
    ];

    public static array $aModules = [
        \Hightemp\AndataRu\Modules\Core\Module::class,
    ];
}

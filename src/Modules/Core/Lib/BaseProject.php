<?php

namespace Hightemp\AndataRu\Modules\Core\Lib;

use Hightemp\AndataRu\Modules\Core\Lib\Logger;
use RedBeanPHP\Facade as R;

class BaseProject 
{
    public static $sProjectClassPath = __NAMESPACE__;
    public static $sProjectRootPath = __DIR__;

    public static $mShutdownFunction = [self::class,'fnShutdownFunction'];

    public static $aLoggers = [

    ];

    /** 
     * @var array $aPreload Предварительная загрузка файлов, выполнение методов модулей
     * 
     * ```php
     * [
     *   // Подключить файл
     *   "file/path/to_include.php",
     *   // Выполнить метод
     *   [\Hightemp\AndataRu\Modules\Debug\Module::class, "fnMethod"],
     *   // Подключить модуль
     *   \Hightemp\AndataRu\Modules\Debug\Module::class
     * ]
     * ```
     * */
    public static $aPreload = [
    ];

    public static $aModules = [
    ];

    public static $aControllers = [
    ];

    public static $aPreloadViews = [
    ];

    public static function fnInit()
    {
        static::fnPreload();
        static::fnInitLogger();
        static::fnInitDatabase();

        Logger::fnWriteMessage("==START==");

        static::fnRegisterShutdownFunction();
    }

    public static function fnInitLogger()
    {
        Logger::fnInit();
    }

    public static function fnInitDatabase()
    {
        R::setup('sqlite:'.DB_PATH.'/db.db');
        if(!R::testConnection()) throw new \Exception("<h1>No db connection</h1>");
    }

    public static function fnRegisterShutdownFunction()
    {
        register_shutdown_function(static::$mShutdownFunction);
    }
    
    /**
     * Предварительная загрузка файлов, выполнение методов модулей
     *
     * @return void
     */
    public static function fnPreload()
    {
        foreach (static::$aPreload as $mElement) {
            if (is_string($mElement)) {
                if (class_exists($mElement)) {
                    static::fnPreloadModule($mElement);
                } else {
                    $aResult = require_once($mElement);
                }
            } else {
                $mElement[0]::$mElement[1]();
            }
        }
    }

    public static function fnPreloadModule($sClass)
    {
        static::$aModules = array_merge(static::$aModules, (array) $sClass);
        static::$aControllers = array_merge(static::$aControllers, (array) $sClass::$aControllers);

        static::$aModules = array_unique(static::$aModules);
        static::$aControllers = array_unique(static::$aControllers);
    }

    public static function fnShutdownFunction()
    {
        Logger::fnWriteMessage("==SHUTDOWN==");
    }
}

<?php

namespace Hightemp\AndataRu\Modules\Core\Lib;

use Hightemp\AndataRu\Project;

class Logger {

    public static array $aLoggersIntsances = [];
    
    /**
     * Инициализация всех логгеров
     *
     * @return void
     */
    public static function fnInit() {
        foreach (Project::$aLoggers as $sLoggerClass) {
            static::$aLoggersIntsances[] = $sLoggerClass::fnBuild();
        }
    }

    
    public static function fnWrite(string $sType, string $sMessage, array $aData=[])
    {
        foreach (static::$aLoggersIntsances as $oLogger) {
            $oLogger->fnWrite($sType, $sMessage, $aData=[]);
        }
    }

    public static function fnWriteMessage(string $sMessage, array $aData=[])
    {
        foreach (static::$aLoggersIntsances as $oLogger) {
            $oLogger->fnWriteMessage($sMessage, $aData=[]);
        }
    }

    public static function fnWriteError(string $sMessage, array $aData=[])
    {
        foreach (static::$aLoggersIntsances as $oLogger) {
            $oLogger->fnWriteError($sMessage, $aData=[]);
        }
    }

    public static function fnWriteWarning(string $sMessage, array $aData=[])
    {
        foreach (static::$aLoggersIntsances as $oLogger) {
            $oLogger->fnWriteWarning($sMessage, $aData=[]);
        }
    }

    public static function fnWriteInfo(string $sMessage, array $aData=[])
    {
        foreach (static::$aLoggersIntsances as $oLogger) {
            $oLogger->fnWriteInfo($sMessage, $aData=[]);
        }
    }
}
<?php

namespace Hightemp\AndataRu\Modules\Core\Lib;

use Hightemp\AndataRu\Project;

class Logger {

    public static $aLoggersIntsances = [];

    public static function fnInit() {
        foreach (Project::$aLoggers as $sLoggerClass) {
            static::$aLoggersIntsances[] = $sLoggerClass::fnBuild();
        }
    }

    
    public static function fnWrite($sType, $sMessage, $aData=[])
    {
        foreach (static::$aLoggersIntsances as $oLogger) {
            $oLogger->fnWrite($sType, $sMessage, $aData=[]);
        }
    }

    public static function fnWriteMessage($sMessage, $aData=[])
    {
        foreach (static::$aLoggersIntsances as $oLogger) {
            $oLogger->fnWriteMessage($sMessage, $aData=[]);
        }
    }

    public static function fnWriteError($sMessage, $aData=[])
    {
        foreach (static::$aLoggersIntsances as $oLogger) {
            $oLogger->fnWriteError($sMessage, $aData=[]);
        }
    }

    public static function fnWriteWarning($sMessage, $aData=[])
    {
        foreach (static::$aLoggersIntsances as $oLogger) {
            $oLogger->fnWriteWarning($sMessage, $aData=[]);
        }
    }

    public static function fnWriteInfo($sMessage, $aData=[])
    {
        foreach (static::$aLoggersIntsances as $oLogger) {
            $oLogger->fnWriteInfo($sMessage, $aData=[]);
        }
    }
}
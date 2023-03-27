<?php

namespace Hightemp\AndataRu\Modules\Core\Lib\Loggers;

class BaseLogger
{
    const MT_MESSAGE = 'message';
    const MT_ERROR = 'error';
    const MT_WARNING = 'warning';
    const MT_INFO = 'info';
    const MT_DEPRECATED = 'deprecated';
    const MT_NOTICE = 'notice';

    public static $oInstance = null;

    public static function fnBuild()
    {
        return (static::$oInstance = new static());
    }

    public function fnUpdateHeader($aData)
    {
    }

    public function fnWrite($sType, $sMessage, $aData=[])
    {

    }

    public function fnWriteMessage($sMessage, $aData=[])
    {
        $this->fnWrite(static::MT_MESSAGE, $sMessage, $aData);
    }

    public function fnWriteError($sMessage, $aData=[])
    {
        $this->fnWrite(static::MT_ERROR, $sMessage, $aData);
    }

    public function fnWriteWarning($sMessage, $aData=[])
    {
        $this->fnWrite(static::MT_WARNING, $sMessage, $aData);
    }

    public function fnWriteInfo($sMessage, $aData=[])
    {
        $this->fnWrite(static::MT_INFO, $sMessage, $aData);
    }

    public function fnRemoveOld()
    {
    }

    public function fnClean()
    {
    }

    public function fnGetMicrotime()
    {
        return microtime(true);
    }

    public function fnGetCurrentDate()
    {
        return date("Y-m-d H:i:s");
    }
}
<?php

namespace Hightemp\AndataRu\Modules\Core\Lib\Loggers;

use Hightemp\AndataRu\Modules\Core\Lib\Controllers\BaseController;
use Hightemp\AndataRu\Modules\Core\Lib\Loggers\BaseLogger;
use Hightemp\AndataRu\Modules\Core\Lib\Request;

class SimpleJSONLLogger extends BaseLogger
{
    public $sLoggerPath = "";
    public $sFileName = "";
    public $sLoggerFilePath = "";
    public $sLoggerFilePathMask = "";
    public $iLifeTime = 1000;
    public $oRequest = null;
    public $bHeaderWritten = false;
    public $aHeader = [];
    
    /**
     * fnBuild
     * 
     * @uses Config::$aConfig["sLoggerPath"]
     * @uses Config::$aConfig["iLoggerFilesCacheTime"]
     * @uses BaseController::$oGlobalRequest
     *
     * @return void
     */
    public static function fnBuild()
    {
        $sLoggerPath = ROOT_PATH."";
        $sFileName = time().".jsonl";
        $iLifeTime = 1000;

        return (static::$oInstance = new SimpleJSONLLogger(
            $sLoggerPath, 
            $sFileName,
            $iLifeTime,
            null
        ));
    }

    public static function fnPrepareFilePath($sFileName)
    {
        return LOGS_PATH."/".$sFileName;
    }

    public static function fnGetFiles()
    {
        $sLoggerFilePathMask = static::fnPrepareFilePath("*");

        return glob($sLoggerFilePathMask);
    }

    public static function fnCleanFiles()
    {
        $sLoggerFilePathMask = static::fnPrepareFilePath("*");

        shell_exec("rm -f {$sLoggerFilePathMask}");
    }
    
    /**
     * __construct
     * 
     * @param string $sLoggerPath
     * @param string $sFileName
     * @param int $iLifeTime
     * @param Request $oRequest
     *
     * @return void
     */
    public function __construct(
        $sLoggerPath,
        $sFileName,
        $iLifeTime,
        $oRequest = null
    )
    {
        $this->sLoggerPath = $sLoggerPath;
        $this->sFileName = $sFileName;
        $this->sLoggerFilePath = $sLoggerPath."/".$sFileName;
        $this->sLoggerFilePathMask = $sLoggerPath."/*";
        $this->iLifeTime = $iLifeTime;
        $this->oRequest = $oRequest;

        $this->fnRemoveOld();
        $this->fnUpdateHeaderByRequest();
    }
    
    /**
     * fnUpdateHeaderByRequest
     *
     * @uses BaseController::$oGlobalRequest
     * 
     * @return void
     */
    public function fnUpdateHeaderByRequest()
    {
        if ($this->bHeaderWritten) return;

        if (!$this->oRequest && !BaseController::$oGlobalRequest) {
            // NOTE: Если запроса нет оставляем пустым заголовок в логе до его появления
            $this->aHeader["iTimestamp"] = time();
            $this->fnUpdateHeader();
            return;
        }

        if (!$this->oRequest) {
            $this->oRequest = BaseController::$oGlobalRequest;
        }
        
        $this->aHeader = [
            "iTimestamp" => $this->oRequest->iTimestamp,
            "aGet" => $this->oRequest->aGet,
            "aPost" => $this->oRequest->aPost,
            "aFiles" => $this->oRequest->aFiles,
            "aCookie" => $this->oRequest->aCookie,
            "aServer" => $this->oRequest->aServer,
            "aSession" => $this->oRequest->aSession,
        ];

        $this->fnUpdateHeader();
        $this->bHeaderWritten = true;
    }

    public function fnUpdateHeader($aData=null)
    {
        if (is_null($aData)) $aData = $this->aHeader;
        $rH = fopen($this->sLoggerFilePath, "w+");
        fseek($rH, 0);
        $sJSON = json_encode($aData);
        $sJSON = $sJSON.str_repeat(" ", 5000-strlen($sJSON))."\n";
        fwrite($rH, $sJSON, strlen($sJSON));
        fclose($rH);
    }

    public function fnPrepareData($sType, $sMicroTime, $sDate, $sMessage, $aData)
    {
        $sJSON = json_encode([$sType, $sMicroTime, $sDate, $sMessage, $aData])."\n";
        return $sJSON;
    }

    public function fnWrite($sType, $sMessage, $aData=[])
    {
        $this->fnUpdateHeaderByRequest();
        $sJSON = static::fnPrepareData($sType, $this->fnGetMicrotime(), $this->fnGetCurrentDate(), $sMessage, $aData);
        file_put_contents($this->sLoggerFilePath, $sJSON, FILE_APPEND);
    }

    public function fnRemoveOld()
    {
        if (!$this->iLifeTime) return;

        $files = $this->fnGetFilesList();
        $now   = time();

        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= $this->iLifeTime) {
                    unlink($file);
                }
            }
        }
    }

    public function fnGetFilesList()
    {
        return glob($this->sLoggerFilePathMask);
    }

    public function fnClean()
    {
        shell_exec("rm -f {$this->sLoggerFilePathMask}");
    }
}
<?php

namespace Hightemp\AndataRu\Modules\Core\Lib\Loggers;

use Hightemp\AndataRu\Modules\Core\Lib\Controllers\BaseController;
use Hightemp\AndataRu\Modules\Core\Lib\Loggers\BaseLogger;
use Hightemp\AndataRu\Modules\Core\Lib\Request;

class SimpleJSONLLogger extends BaseLogger
{
    public string $sLoggerPath = "";
    public string $sFileName = "";
    public string $sLoggerFilePath = "";
    public string $sLoggerFilePathMask = "";
    public int $iLifeTime = 1000;
    public ?Request $oRequest = null;
    public bool $bHeaderWritten = false;
    public array $aHeader = [];
    
    /**
     * Фабричный метод для создания сущности
     * 
     * @uses BaseController::$oGlobalRequest
     *
     * @return BaseLogger
     */
    public static function fnBuild(): BaseLogger
    {
        $sLoggerPath = LOGS_PATH."";
        $sFileName = time().".jsonl";
        $iLifeTime = 1000;

        return (static::$oInstance = new SimpleJSONLLogger(
            $sLoggerPath, 
            $sFileName,
            $iLifeTime,
            null
        ));
    }

    public static function fnPrepareFilePath($sFileName): string
    {
        return LOGS_PATH."/".$sFileName;
    }

    public static function fnGetFiles(): array
    {
        $sLoggerFilePathMask = static::fnPrepareFilePath("*.jsonl");

        return glob($sLoggerFilePathMask);
    }

    public static function fnCleanFiles(): void
    {
        $sLoggerFilePathMask = static::fnPrepareFilePath("*.jsonl");

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
        string $sLoggerPath,
        string $sFileName,
        int $iLifeTime,
        ?Request $oRequest = null
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

    /**
     * Метод для обновления заголовка лога
     *
     * @param  array $aData
     * @return void
     */
    public function fnUpdateHeader(?array $aData=null): void
    {
        if (is_null($aData)) $aData = $this->aHeader;
        $rH = fopen($this->sLoggerFilePath, "w+");
        fseek($rH, 0);
        $sJSON = json_encode($aData);
        $sJSON = $sJSON.str_repeat(" ", 5000-strlen($sJSON))."\n";
        fwrite($rH, $sJSON, strlen($sJSON));
        fclose($rH);
    }
    
    /**
     * Подготовка данных
     *
     * @param  string $sType
     * @param  string $sMicroTime
     * @param  string $sDate
     * @param  string $sMessage
     * @param  array $aData
     * @return string
     */
    public function fnPrepareData(string $sType, string $sMicroTime, string $sDate, string $sMessage, array $aData): string
    {
        $sJSON = json_encode([$sType, $sMicroTime, $sDate, $sMessage, $aData])."\n";
        return $sJSON;
    }

    /**
     * Метод для записи в лог
     *
     * @param  string $sType
     * @param  string $sMessage
     * @param  array $aData
     * @return void
     */
    public function fnWrite(string $sType, string $sMessage, array $aData=[]): void
    {
        $this->fnUpdateHeaderByRequest();
        $sJSON = static::fnPrepareData($sType, $this->fnGetMicrotime(), $this->fnGetCurrentDate(), $sMessage, $aData);
        file_put_contents($this->sLoggerFilePath, $sJSON, FILE_APPEND);
    }

    public function fnRemoveOld(): void
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

    public function fnGetFilesList(): array
    {
        return glob($this->sLoggerFilePathMask);
    }

    public function fnClean(): void
    {
        shell_exec("rm -f {$this->sLoggerFilePathMask}");
    }
}
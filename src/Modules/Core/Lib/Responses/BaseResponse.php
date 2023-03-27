<?php

namespace Hightemp\AndataRu\Modules\Core\Lib\Responses;

class BaseResponse
{
    public $sContent = "";
    public $aHeaders = [];
    public $iCode = 200;
    public $sContentType = "text/plain";

    public function fnSetContent($sContent)
    {
        $this->sContent = $sContent;
    }

    public function fnGetContent()
    {
        return $this->sContent;
    }

    public function fnPrintOutputAndExit()
    {
        if ($this->iCode) {
            http_response_code($this->iCode);
        }

        header("Content-Type: {$this->sContentType}");
        
        if ($this->aHeaders) {
            foreach ($this->aHeaders as $sHeader) {
                header($sHeader);
            }
        }
        die($this->fnGetContent());
    }
}
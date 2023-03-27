<?php

namespace Hightemp\AndataRu\Modules\Core\Lib\Responses;

use Hightemp\AndataRu\Modules\Core\Lib\Responses\BaseResponse;

class JSON extends BaseResponse
{
    public $sContentType = "application/json";

    public function fnSetContent($mContent)
    {
        $this->sContent = json_encode($mContent, JSON_UNESCAPED_UNICODE);
    }
}
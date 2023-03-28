<?php

namespace Hightemp\AndataRu\Modules\Core\Lib\Responses;

use Hightemp\AndataRu\Modules\Core\Lib\Responses\BaseResponse;

class JSON extends BaseResponse
{
    public string $sContentType = "application/json";

    public function fnSetContent(mixed $mContent): void
    {
        $this->sContent = json_encode($mContent, JSON_UNESCAPED_UNICODE);
    }
}
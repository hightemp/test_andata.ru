<?php

namespace Hightemp\AndataRu\Modules\Core\Lib\Responses;

use Hightemp\AndataRu\Modules\Core\Lib\Responses\BaseResponse;

class Forward301 extends BaseResponse
{
    public int $iCode = 301;

    public function __construct($sURL)
    {
        $this->aHeaders[] = "Location: {$sURL}";
    }
}
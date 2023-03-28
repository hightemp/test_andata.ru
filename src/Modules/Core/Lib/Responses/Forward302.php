<?php

namespace Hightemp\AndataRu\Modules\Core\Lib\Responses;

use Hightemp\AndataRu\Modules\Core\Lib\Responses\BaseResponse;

class Forward302 extends BaseResponse
{
    public int $iCode = 302;

    public function __construct($sURL)
    {
        $this->aHeaders[] = "Location: {$sURL}";
    }
}
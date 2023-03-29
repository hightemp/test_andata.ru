<?php
declare(strict_types=1);

namespace Hightemp\AndataRu\Modules\Core\Lib\Responses;

use Hightemp\AndataRu\Modules\Core\Lib\Responses\BaseResponse;

class HTML extends BaseResponse
{
    public string $sContentType = "text/html";
}
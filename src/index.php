<?php
declare(strict_types=1);

use Hightemp\AndataRu\Modules\Core\Lib\Controllers\BaseController;
use Hightemp\AndataRu\Modules\Core\Lib\Request;
use Hightemp\AndataRu\Project;

use Hightemp\AndataRu\Modules\Core\Helpers\Utils;

Project::fnInit();

try {
    $oRequest = Request::fnBuild();
    $oResponse = BaseController::fnFindAndExecuteMethod($oRequest);
    $oResponse->fnPrintOutputAndExit();
} catch (\Exception $oException) {
    http_response_code(500);
    die($oException->getMessage());
}

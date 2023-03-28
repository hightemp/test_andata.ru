<?php

use Hightemp\AndataRu\Modules\Core\Lib\Controllers\BaseController;
use Hightemp\AndataRu\Modules\Core\Lib\Request;
use Hightemp\AndataRu\Project;

use Hightemp\AndataRu\Modules\Core\Helpers\Utils;

Project::fnInit();

die("123");
try {
    $oRequest = Request::fnBuild();
    $oResponse = BaseController::fnFindAndExecuteMethod($oRequest);
    $oResponse->fnPrintOutputAndExit();
} catch (\Exception $oException) {
    http_response_code(500);
    die($oException->getMessage());
}

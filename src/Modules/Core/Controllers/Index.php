<?php

namespace Hightemp\AndataRu\Modules\Core\Controllers;

use Hightemp\AndataRu\Modules\Core\Lib\Controllers\BaseController;
use Hightemp\AndataRu\Modules\Core\Lib\View;
use Hightemp\AndataRu\Modules\Core\Services\CommentsService;

class Index extends BaseController
{
    public static string $sDefaultViewClass = View::class;

    public function fnIndexHTML()
    {
        View::fnAddVars([
            "sTitle" => "Очень новая статья"
        ]);
    }

    public function fnPostCommentJSON()
    {
        $oService = new CommentsService();
        try {
            $oService->fnSaveComment($this->oRequest->aPost);
        } catch (\Exception $oE) {
            return [
                "error" => $oE->getMessage()
            ];
        }
    }
}
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
            $aSavedFields = $oService->fnSaveComment($this->oRequest->fnGetInputAsJSON());
            return [
                "code" => "success",
                "message" => "Комментарий сохранен",
                "fields" => $aSavedFields
            ];
        } catch (\Exception $oE) {
            return [
                "code" => "error",
                "message" => $oE->getMessage()
            ];
        }
    }

    public function fnGetCommentsJSON()
    {
        $oService = new CommentsService();
        return $oService->fnGetComments();
    }
}
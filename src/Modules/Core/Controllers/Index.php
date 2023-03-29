<?php
declare(strict_types=1);

namespace Hightemp\AndataRu\Modules\Core\Controllers;

use Hightemp\AndataRu\Modules\Core\Lib\Controllers\BaseController;
use Hightemp\AndataRu\Modules\Core\Lib\View;
use Hightemp\AndataRu\Modules\Core\Services\CommentsService;

class Index extends BaseController
{
    public static string $sDefaultViewClass = View::class;
    
    /**
     * Метод для отображения стратовой страницы
     *
     * @return mixed
     */
    public function fnIndexHTML(): mixed
    {
        View::fnAddVars([
            "sTitle" => "Очень новая статья"
        ]);

        return [];
    }
    
    /**
     * Метод публикации комментария
     *
     * @return mixed
     */
    public function fnPostCommentJSON(): mixed
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
    
    /**
     * Метод для получения списка комментариев
     *
     * @return mixed
     */
    public function fnGetCommentsJSON(): mixed
    {
        $oService = new CommentsService();
        return $oService->fnGetComments();
    }
}
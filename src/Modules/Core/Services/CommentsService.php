<?php 
declare(strict_types=1);

namespace Hightemp\AndataRu\Modules\Core\Services;

use Hightemp\AndataRu\Modules\Core\Lib\BaseService;
use Hightemp\AndataRu\Modules\Core\Models\Comments;

/**
 * Класс сервис для работы с моделью комментариев
 */
class CommentsService extends BaseService {
    
    /**
     * Метод сохранения комментария
     *
     * @param  array $aFields
     * @return array
     */
    function fnSaveComment(array $aFields): array {
        if (empty($aFields['username'])) {
            throw new \Exception("Поле username должно быть заполено");
        }
        if (empty($aFields['email'])) {
            throw new \Exception("Поле email должно быть заполено");
        }
        if (empty($aFields['title'])) {
            throw new \Exception("Поле title должно быть заполено");
        }
        if (empty($aFields['comment'])) {
            throw new \Exception("Поле comment должно быть заполено");
        }

        if (mb_strlen($aFields['username'])<4) {
            throw new \Exception("Поле username должно содержать минимум 4 символа");
        }
        if (mb_strlen($aFields['email'])<4) {
            throw new \Exception("Поле email должно содержать минимум 4 символа");
        }
        if (mb_strlen($aFields['title'])<4) {
            throw new \Exception("Поле title должно содержать минимум 4 символа");
        }
        if (mb_strlen($aFields['comment'])<4) {
            throw new \Exception("Поле comment должно содержать минимум 4 символа");
        }

        if (!preg_match("/[^@]+@[^@]+\.[^@]+/", $aFields['email'])) {
            throw new \Exception("Поле email должно быть формата aaa@aaa.aaa");
        }

        $oModel = new Comments();
        return $oModel->fnSave($aFields);
    }
    
    /**
     * Метод возращает список комментариев
     *
     * @return array
     */
    function fnGetComments(): array {
        $oModel = new Comments();
        return $oModel->fnGetAll();
    }
}
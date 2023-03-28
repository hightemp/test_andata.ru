<?php 

namespace Hightemp\AndataRu\Modules\Core\Services;

use Hightemp\AndataRu\Modules\Core\Lib\BaseService;
use Hightemp\AndataRu\Modules\Core\Models\Comments;

class CommentsService extends BaseService {

    function fnSaveComment(array $aFields): void {
        if (empty($aFields['username'])) {
            throw new \Exception("Поле name должно быть заполено");
        }
        if (empty($aFields['email'])) {
            throw new \Exception("Поле name должно быть заполено");
        }
        if (empty($aFields['title'])) {
            throw new \Exception("Поле name должно быть заполено");
        }
        if (empty($aFields['comment'])) {
            throw new \Exception("Поле name должно быть заполено");
        }

        if (mb_strlen($aFields['username'])<4) {
            throw new \Exception("Поле name должно содержать минимум 4 символа");
        }
        if (mb_strlen($aFields['email'])<4) {
            throw new \Exception("Поле name должно содержать минимум 4 символа");
        }
        if (mb_strlen($aFields['title'])<4) {
            throw new \Exception("Поле name должно содержать минимум 4 символа");
        }
        if (mb_strlen($aFields['comment'])<4) {
            throw new \Exception("Поле name должно содержать минимум 4 символа");
        }

        $oModel = new Comments();
        $oModel->fnSave($aFields);
    }

    function fnGetComments(): array {
        $oModel = new Comments();
        return $oModel->fnGetAll();
    }
}
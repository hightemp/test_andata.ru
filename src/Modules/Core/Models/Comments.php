<?php

namespace Hightemp\AndataRu\Modules\Core\Models;

use Hightemp\AndataRu\Modules\Core\Lib\BaseModel;
use RedBeanPHP\Facade as R;
use Hightemp\AndataRu\Modules\Core\Lib\Logger;

class Comments extends BaseModel {
        
    /**
     * Метод сохраняет комментарий в БД
     *
     * @param  array $aFields
     * @return array
     */
    function fnSave(array $aFields): array {
        $oComment = R::dispense('comments');
        $aFields["published"] = date('Y-m-d H:i:s');
        $oComment->import($aFields);
        R::store($oComment);
        return $aFields;
    }
    
    /**
     * Метод возвращает список комментариев
     *
     * @return array
     */
    function fnGetAll(): array {
        $aRows = R::findAll('comments');
        if (!$aRows) return [];
        return R::exportAll($aRows);
    }
    
}
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
     * @return void
     */
    function fnSave(array $aFields): void {
        $oComment = R::dispense('comments');
        $oComment->import($aFields);
        R::store($oComment);
    }

    function fnGetAll(): array {
        if (R::inspect('comments')['type'] !== 'table') {
            return [];
        }
        $aRows = R::findAll('comments');
        if (!$aRows) return [];
        return R::exportAll($aRows);
    }
    
}
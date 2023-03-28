<?php

namespace Hightemp\AndataRu\Modules\Core\Models;

use Hightemp\AndataRu\Modules\Core\Lib\Models\BaseModel;
use RedBeanPHP\Facade as R;

class Comments extends BaseModel {
        
    /**
     * Метод сохраняет комментарий в БД
     *
     * @param  array $aFields
     * @return void
     */
    function fnSave(array $aFields): void {
        $comment = R::dispense('comments');
        $comment->import($aFields);
        R::store($comment);
    }
    
}
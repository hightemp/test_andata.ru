<?php

namespace Hightemp\AndataRu\Modules\Core\Lib\Responses;

class NotFound extends HTML
{
    public int $iCode = 404;

    public function fnGetContent(): string
    {
        return "<h1>Page not found</h1>";
    }
}
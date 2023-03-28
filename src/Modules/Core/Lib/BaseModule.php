<?php

namespace Hightemp\AndataRu\Modules\Core\Lib;

class BaseModule 
{
    /** @var string Название модуля */
    const NAME = "[NONAME]";
    const DESCRIPTION = "";
    const VERSION = "1.0.0";

    /** @var string|BaseController Дефолтный контроллер */
    public static ?string $sDefaultController = null;
    /** @var string Дефолтный метод */
    public static ?string $sDefaultMethod = null;

    /** @var string[]|BaseController[] Список контроллеров */
    public static array $aControllers = [];

    /** @var string[]|View[] Загрузка переменных и доп. html */
    public static array $aPreloadViews = [];
}
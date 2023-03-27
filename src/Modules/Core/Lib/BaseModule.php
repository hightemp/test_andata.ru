<?php

namespace Hightemp\AndataRu\Modules\Core\Lib;

class BaseModule 
{
    /** @var string Название модуля */
    const NAME = "[NONAME]";
    const DESCRIPTION = "";
    const VERSION = "1.0.0";

    /** @var string|BaseController Дефолтный контроллер */
    public static $sDefaultController = null;
    /** @var string Дефолтный метод */
    public static $sDefaultMethod = null;

    /** @var string[]|BaseController[] Список контроллеров */
    public static $aControllers = [];

    /** @var string[]|View[] Загрузка переменных и доп. html */
    public static $aPreloadViews = [];

    /** @var string[]|BaseModel[] $aModels Список моделей */
    public static $aModels = [];

    /** @var string[]|BaseMiddleware[] $aMiddlewares Прослойка для выполнения кода */
    public static $aMiddlewares = [];
}
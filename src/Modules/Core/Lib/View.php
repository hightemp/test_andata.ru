<?php

namespace Hightemp\AndataRu\Modules\Core\Lib;

use Hightemp\AndataRu\Modules\Core\Helpers\Utils;
use Hightemp\AndataRu\Modules\Core\Lib\View\Helpers\Vars;
use Hightemp\AndataRu\Project;

class View
{
    const STATIC_PATH = "static";
    const STATIC_IMAGES_PATH = "static/images";
    const STATIC_ASSETS_PATH = "static/vendor";

    const TEMPLATES_PATH = "views";

    /** @var string $sCurrentViewClass Класс шаблонизатора для прокидывания в шаблон */
    public static ?string $sCurrentViewClass = null;

    /** @var string $sLayoutTemplate Шаблон вывода по умолчанию */
    public static string $sLayoutTemplate = "layout.php";
    /** @var string $sContentTemplate Шаблон контента по умолчанию */
    public static string $sContentTemplate = "index.php";

    /** @var string[] $aVars Список переменных. Ключ - значение */
    public static array $aVars = [];
    /** @var string $sHTMLHeader Это код html->head блока */
    public static string $sHTMLHeader = '';
    
    /**
     * Добавление переменных
     *
     * @param  array $aVars
     * @return void
     */
    public static function fnAddVars(array $aVars): void
    {
        self::$aVars = array_merge(self::$aVars, $aVars);
    }
    
    /**
     * Метод добавлеят переменные для шаблона перед его использованием
     * 
     * - sHTMLHeader - Дополнительные тэги в head
     * - sStaticPath - Путь к статике
     * - sTitle - Заголовок
     * 
     * @return void
     */
    public static function fnPrepareVars(): void
    {
        self::$aVars['sHTMLHeader'] = self::$sHTMLHeader;
        self::$aVars['sStaticPath'] = static::STATIC_PATH;
        isset(self::$aVars['sTitle']) ?: self::$aVars['sTitle'] = '';
    }
    
    /**
     * Проверка существования шаблона
     *
     * @param  string $sTemplatePath
     * @return bool
     */
    public static function fnIsTemplate(string $sTemplatePath): bool
    {
        return is_file(static::fnGetTemplatesPath($sTemplatePath));
    }
    
    /**
     * Метод рендрит шаблоны для переменной sContent, которая вставляется в layout шаблона
     *
     * @param  string $sContentTemplate Файл шаблона
     * @param  string[] $aVars Переменные шаблона
     * @return string|false
     */
    public static function fnRenderContent(string $sContentTemplate=null, array $aVars=[]): string
    {
        if (is_null($sContentTemplate)) {
            if (static::fnIsTemplate(static::$sContentTemplate)) {
                return static::fnRenderTemplate(static::$sContentTemplate, $aVars);
            }
        } else {
            if (static::fnIsTemplate($sContentTemplate)) {
                return static::fnRenderTemplate($sContentTemplate, $aVars);
            }
        }
    }
    
    /**
     * Подготовка переменной sContent которая рендриться в layout
     *
     * @param  string|null $sContentTemplate
     * @return void
     */
    public static function fnPrepareContentVar(?string $sContentTemplate=null): void
    {
        isset(self::$aVars['sContent']) ?: self::$aVars['sContent'] = static::fnRenderContent($sContentTemplate);
    }
    
    /**
     * Получение пути модуля
     *
     * @param  string $sExtPath
     * @return string
     */
    public static function fnGetModuleGlobalPath(string $sExtPath=""): string
    {
        return dirname(__DIR__)."/".$sExtPath;
    }
        
    /**
     * Получение пути к шаблонам
     *
     * @param  string $sExtPath
     * @return string
     */
    public static function fnGetTemplatesPath(string $sExtPath=""): string
    {
        return static::fnGetModuleGlobalPath(static::TEMPLATES_PATH."/".ltrim($sExtPath, "/"));
    }
    
    /**
     * Метод рендринга шаблона
     *
     * @param  string $sTemplatePath
     * @param  array $aVars
     * @return mixed
     */
    public static function fnRenderTemplate(string $sTemplatePath, array $aVars=[]): mixed
    {
        static::$sCurrentViewClass = static::class;
        $aVars['sCurrentViewClass'] = static::$sCurrentViewClass;

        if ($aVars) static::fnAddVars($aVars);
        ob_start();
        {
            extract(self::$aVars);
            require_once(static::fnGetTemplatesPath($sTemplatePath));
        }
        return ob_get_clean();
    }
    
    /**
     * Метод рендринга layout
     *
     * @param  array $aVars
     * @return mixed
     */
    public static function fnRenderLayout(array $aVars=[]): mixed
    {
        return static::fnRenderTemplate(static::$sLayoutTemplate, $aVars);
    }
    
    /**
     * Метод рендринга
     *
     * @return mixed
     */
    public static function fnRender(): mixed
    {
        static::fnPrepareVars();
        return static::fnRenderLayout();
    }
}
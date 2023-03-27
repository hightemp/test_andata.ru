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

    const THEME = "";

    public static $sCurrentViewClass = null;

    public static $sLayoutTemplate = "layout.php";
    public static $sContentTemplate = "index.php";
    public static $sHeaderTemplate = "header.php";

    /** @var string[] $aVars Список переменных. Ключ - значение */
    public static $aVars = [];
    /** @var string $sHTMLHeader Это код html->head блока */
    public static $sHTMLHeader = '';

    public static function fnAddVars($aVars)
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
    public static function fnPrepareVars()
    {
        self::$aVars['sHTMLHeader'] = self::$sHTMLHeader;
        self::$aVars['sStaticPath'] = static::STATIC_PATH;
        isset(self::$aVars['sTitle']) ?: self::$aVars['sTitle'] = '';
    }

    public static function fnIsTemplate($sTemplatePath)
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
     * fnPrepareContentVar
     *
     * @param  mixed $sContentTemplate
     * @return void
     */
    public static function fnPrepareContentVar($sContentTemplate=null)
    {
        isset(self::$aVars['sContent']) ?: self::$aVars['sContent'] = static::fnRenderContent($sContentTemplate);
    }

    public static function fnGetModuleGlobalPath($sExtPath="")
    {
        return dirname(__DIR__)."/".$sExtPath;
    }
    
    public static function fnGetTemplatesPath($sExtPath="")
    {
        return static::fnGetModuleGlobalPath(static::TEMPLATES_PATH."/".ltrim($sExtPath, "/"));
    }

    public static function fnRenderTemplate($sTemplatePath, $aVars=[])
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

    public static function fnRenderLayout($aVars=[])
    {
        return static::fnRenderTemplate(static::$sLayoutTemplate, $aVars);
    }

    public static function fnRender()
    {
        static::fnPrepareVars();
        return static::fnRenderLayout();
    }
}
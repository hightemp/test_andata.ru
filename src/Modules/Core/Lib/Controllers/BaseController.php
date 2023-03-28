<?php

namespace Hightemp\AndataRu\Modules\Core\Lib\Controllers;

use Hightemp\AndataRu\Modules\Core\Lib\Request;
use Hightemp\AndataRu\Modules\Core\Lib\View;
use Hightemp\AndataRu\Project;
use Hightemp\AndataRu\Modules\Core\Lib\Responses\BaseResponse;
use Hightemp\AndataRu\Modules\Core\Lib\Responses\HTML as HTMLResponse;
use Hightemp\AndataRu\Modules\Core\Lib\Responses\JSON as JSONResponse;
use Hightemp\AndataRu\Modules\Core\Lib\Responses\NotFound as NotFoundResponse;

/**
 * Класс базового контроллера
 */
class BaseController
{
    const METHOD_KEY = "method";
    const CONTROLLER_KEY = "controller";
    const MODULE_KEY = "module";
    const ALIAS_KEY = "alias";

    const DEFAULT_MODULE = "Core";

    const CC_FORWARD_302 = 302;
    const CC_FORWARD_301 = 301;
    const CP_FORWARD = "forward";

    public static $aAvailableMethodTypes = ['JSON', 'HTML'];

    /** @var Request|null $oRequest Глобальный запрос */
    public static ?Request $oGlobalRequest = null;
    /** @var Request|null $oRequest Запрос переданный контроллеру */
    public ?Request $oRequest = null;
    /** @var string|null $sViewClass Класс шаблонизатора для отображения, по умолчанию используется $sDefaultViewClass */
    public ?string $sViewClass = null;
    /** @var string $sDefaultViewClass Класс шаблонизатора по умолчанию */
    public static string $sDefaultViewClass = View::class;

    /** @var string[] $aPreloadViews список View для предварительной загрузки head html и переменных */
    public static $aPreloadViews = [];
    public static $aMiddlewaresBefore = [];
    public static $aMiddlewaresAfter = [];

    public function __construct($oRequest=new Request(), $sViewClass=null)
    {
        $this->oRequest = $oRequest;
        $this->sViewClass = is_null($sViewClass) ? static::$sDefaultViewClass : $sViewClass;
    }

    public static function fnGetMethodValidatorRegExp()
    {
        $sReg = '/^fn(.*)('.join('|', static::$aAvailableMethodTypes).')$/';
        return $sReg;
    }

    public static function fnIsMethodValid($sMethod)
    {
        $sReg = static::fnGetMethodValidatorRegExp();
        return preg_match($sReg, $sMethod);
    }

    public static function fnGetValidMethods()
    {
        $aMethods = get_class_methods(static::class);

        return array_filter($aMethods, function($sI) { return static::fnIsMethodValid($sI); });
    }
    
    /**
     * Получение списка контроллеров из массивов 
     * 
     * Из списков 
     * - `Project::$aModules`
     * - `$sModule::$aControllers`
     *
     * @return string[]
     */
    public static function fnGetControllersByModules(): array
    {
        static $aControllers = [];

        if ($aControllers) return $aControllers;

        $aModules = Project::$aModules;
        $aControllers = [];
        foreach ($aModules as $sModule) {
            isset($aControllers[$sModule]) ?: $aControllers[$sModule] = [];
            $aControllers[$sModule] = array_merge(
                $aControllers[$sModule], 
                $sModule::$aControllers
            );
        }

        return $aControllers;
    }

    /**
     * Получить текущие шаблоны для метода контроллера
     *
     * @param  string $sViewClass
     * @param  string $sControllerClass
     * @param  string $sMethod
     * @return string[] `['content.php','layout.php','title']`
     */
    public static function fnGetTemplate($sViewClass, $sControllerClass, $sMethod, $bFullPath=false)
    {
        $aTemplates = null;

        if (is_null($aTemplates)) {
            // NOTE: Иначе используем шабоны по умолчанию из контроллера
            if (isset($sControllerClass::$aDefaultTemplates[$sMethod])) {
                $aTemplates = $sControllerClass::$aDefaultTemplates[$sMethod];
            }
        }

        if (!is_null($aTemplates)) {
            isset($aTemplates[0]) ?: $aTemplates[0] = null;
            isset($aTemplates[1]) ?: $aTemplates[1] = null;
            // NOTE: sTitle - подстановка заголовока из aTemplates
            isset($aTemplates[2]) ?: $aTemplates[2] = '';

            if (!$sViewClass) {
                $sViewClass = View::class;
            }

            if ($bFullPath && $sViewClass) {
                if ($aTemplates[0]) {
                    $aTemplates[0] = $sViewClass::fnGetTemplatesPath($aTemplates[0]);
                }
                if ($aTemplates[1]) {
                    $aTemplates[1] = $sViewClass::fnGetTemplatesPath($aTemplates[1]);
                }
            }
        }

        return $aTemplates;
    }
    
    /**
     * fnPrepareAllViewsForController
     *
     * @param  mixed $oController
     * @param  mixed $sMethod
     * @param  mixed $aControllers
     * @return void
     */
    public static function fnPrepareAllViewsForController($oController, $sMethod, $aControllers=null)
    {
        if (is_null($aControllers)) {
            $aControllers = static::fnGetControllersByModules();
        }

        $aViewsList = [];
        
        // NOTE: предзагруза View из контроллеров
        $sControllerClass = get_class($oController);
        $aPreloadViews = (array) $sControllerClass::$aPreloadViews;
        $aViewsList = array_merge($aViewsList, $aPreloadViews);

        $aViewsList = array_unique($aViewsList);

        // NOTE: Подключаем переменные
        foreach ($aViewsList as $sViewClass) {
            $sViewClass::fnPrepareVars();
        }
        
        // NOTE: Рендрим все header.php шаблоны
        foreach ($aViewsList as $sViewClass) {
            $sViewClass::fnPrepareHTMLHeader();
        }

        $sViewClass = $oController->sViewClass;
        
        $aTemplates = static::fnGetTemplate($sViewClass, $sControllerClass, $sMethod);

        if (!is_null($aTemplates)) {
            $sViewClass::fnSetParams(
                [
                    "sTitle" => $aTemplates[2]
                ], 
                $aTemplates[0], 
                $aTemplates[1]
            );
        }

        $sViewClass::fnPrepareContentVar();
    }
    
    /**
     * fnGetResponseFromController
     *
     * @param  mixed $aAlias
     * @param  mixed $oRequest
     * @return Response
     */
    public static function fnGetResponseFromController( $aAlias, $oRequest)
    {
        if (!$aAlias) return;

        isset($aAlias[0]) ?: $aAlias[0]='';
        isset($aAlias[1]) ?: $aAlias[1]='';
        isset($aAlias[2]) ?: $aAlias[2]='';

        list($sController, $sMethod) = $aAlias;
        
        $oResponse = null;
        $sController = "\\".$sController;
        $oController = new $sController($oRequest);

        $mResult = $oController->$sMethod();

        if (preg_match('/HTML$/i', $sMethod)) {
            // NOTE: Подготовка $sContent и переменных для текущего контроллера
            static::fnPrepareAllViewsForController($oController, $sMethod);

            $sViewClass = $oController->sViewClass;
            $mResult = $sViewClass::fnRender();

            $oResponse = new HTMLResponse();
        } else if (preg_match('/json$/i', $sMethod)) {
            $oResponse = new JSONResponse();
        }

        $oResponse->fnSetContent($mResult);

        return $oResponse;
    }
    
    /**
     * Метод ищет в списке контроллеров всех модулей из `Project` и возвращает ответ сервера
     *
     * @param  Request $oRequest
     * @param  string[][] $aControllers
     * @return BaseResponse
     */
    public static function fnFindAndExecuteMethod(Request $oRequest, array $aControllers=null): BaseResponse
    {
        static::$oGlobalRequest = $oRequest;

        View::$aVars['oRequest'] = $oRequest;

        $oResponse = null;
        $sCurrentMethod = isset($oRequest->aGet[static::METHOD_KEY]) ? $oRequest->aGet[static::METHOD_KEY] : '';
        $sCurrentController = isset($oRequest->aGet[static::CONTROLLER_KEY]) ? $oRequest->aGet[static::CONTROLLER_KEY] : '';

        $aURI = parse_url($oRequest->aServer['REQUEST_URI']);
        $sCurrentAlias = isset($oRequest->aGet[static::ALIAS_KEY]) ? $oRequest->aGet[static::ALIAS_KEY] : $aURI['path'];
        $bIsRoot = trim($sCurrentAlias, "/") == "";

        if (!$oResponse) {
            if (is_null($aControllers)) {
                $aControllers = static::fnGetControllersByModules();
            }

            // NOTE: Пока других модулей нет, перебираем без проверки модулей
            foreach ($aControllers as $sModuleClass => $aControllers) {
                foreach ($aControllers as $sController) {
                    $aController = explode("\\", $sController);
                    $sControllerName = array_pop($aController);

                    // NOTE: Метод и контроллер по умолчанию первый попавшийся
                    if (!$sCurrentController && !$sCurrentMethod && $bIsRoot)  {
                        $sCurrentController = $sModuleClass::$sDefaultController;
                        $sCurrentMethod = $sModuleClass::$sDefaultMethod;
                    }

                    if ($sController == $sCurrentController || $sControllerName == $sCurrentController) {
                        if (method_exists($sController, $sCurrentMethod)) {
                            $aAlias = [$sController, $sCurrentMethod];

                            Request::$sCurrentModuleClass = $sModuleClass;
                            Request::$sCurrentMethod = $sCurrentMethod;
                            Request::$sCurrentControllerClass = $sCurrentController;

                            $oResponse = static::fnGetResponseFromController($aAlias, $oRequest);
                            break 2;
                        }
                    }
                }
            }
        }

        if (!$oResponse) {
            $oResponse = new NotFoundResponse();
        }

        return $oResponse;
    }
}
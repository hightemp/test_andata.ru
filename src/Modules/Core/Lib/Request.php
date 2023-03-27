<?php

namespace Hightemp\AndataRu\Modules\Core\Lib;

use \League\Url\Url;

class Request
{
    const INPUT = "php://input";

    public $iTimestamp = 0;
    
    public $aRequest = [];
    public $aGet = [];
    public $aPost = [];
    public $aFiles = [];
    public $aCookie = [];
    public $aServer = [];
    public $aSession = [];
    public $sInput = "";

    /** @var Url $oCurrentURL */
    public $oCurrentURL = null;
    /** @var Url $oBaseURL */
    public $oBaseURL = null;

    public static $sCurrentAlias = "";
    public static $sCurrentModuleClass = "";
    public static $sCurrentControllerClass = "";
    public static $sCurrentMethod = "";

    public function __construct(
        &$aRequest=[],
        &$aGet=[], 
        &$aPost=[], 
        &$aFiles=[],
        &$aCookie=[],
        &$aServer=[],
        &$aSession=[]
    )
    {
        $this->iTimestamp = time();

        $this->aRequest = &$aRequest;
        $this->aGet = &$aGet;
        $this->aPost = &$aPost;
        $this->aFiles = &$aFiles;
        $this->aCookie = &$aCookie;
        $this->aServer = &$aServer;
        $this->aSession = &$aSession;
        $this->fnGetInput();

        $this->oURL = Url::createFromUrl($this->fnGetCurrentURL());
        $this->oCurrentURL = Url::createFromServer($this->aServer);
        $this->oBaseURL = $this->fnCopyURL($this->oCurrentURL);
        
        $this->oBaseURL->setQuery("");
    }

    public function fnCopyURL($oURL)
    {
        return Url::createFromUrl((string) $oURL);
    }

    public function fnPrepareURL($sPath, $aArgs=[], $bAddCurrentURL=false)
    {
        $oURL = $this->fnCopyURL($this->oBaseURL);
        $oURL->setPath($sPath);
        if ($bAddCurrentURL) {
            $aArgs['redirect_url'] = $this->oCurrentURL->__toString();
        }
        $oURL->setQuery($aArgs);
        return $oURL;
    }

    public function fnPrepareURLFromCurrent($aArgs=[], $bAddCurrentURL=false)
    {
        $oURL = $this->fnCopyURL($this->oCurrentURL);
        $oQuery = $oURL->getQuery();
        // $aQueryArgs = array_replace_recursive($aQueryArgs, $aArgs);
        if ($bAddCurrentURL) {
            $aArgs['redirect_url'] = $this->oCurrentURL->__toString();
        }
        $oQuery->modify($aArgs);
        return $oURL;
    }

    public function fnGetCurrentURL()
    {
        $sURL = (isset($this->aServer['HTTPS']) && $this->aServer['HTTPS'] === 'on' ? "https" : "http");
        $sURL .= "://".$this->aServer['HTTP_HOST'].$this->aServer['REQUEST_URI'];
        return $sURL;
    }

    public static function fnBuild()
    {
        return new static(
            $_REQUEST,
            $_GET, 
            $_POST, 
            $_FILES,
            $_COOKIE,
            $_SERVER,
            $_SESSION
        );
    }

    public function fnGetInput()
    {
        return $this->sInput = ($this->sInput ?: file_get_contents(static::INPUT));
    }

    public function fnGetInputAsJSON()
    {
        return json_decode($this->sInput, true);
    }
}
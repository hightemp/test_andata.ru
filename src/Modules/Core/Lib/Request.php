<?php
declare(strict_types=1);

namespace Hightemp\AndataRu\Modules\Core\Lib;

use \League\Url\Url;

class Request
{
    const INPUT = "php://input";

    public int $iTimestamp = 0;
    
    public ?array $aRequest = [];
    public ?array $aGet = [];
    public ?array $aPost = [];
    public ?array $aFiles = [];
    public ?array $aCookie = [];
    public ?array $aServer = [];
    public ?array $aSession = [];
    public ?string $sInput = "";

    /** @var Url $oCurrentURL */
    public ?Url $oCurrentURL = null;
    /** @var Url $oBaseURL */
    public ?Url $oBaseURL = null;

    public static string $sCurrentAlias = "";
    public static string $sCurrentModuleClass = "";
    public static string $sCurrentControllerClass = "";
    public static string $sCurrentMethod = "";

    public function __construct(
        mixed &$aRequest=[],
        mixed &$aGet=[], 
        mixed &$aPost=[], 
        mixed &$aFiles=[],
        mixed &$aCookie=[],
        mixed &$aServer=[],
        mixed &$aSession=[]
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

    public function fnCopyURL(Url $oURL)
    {
        return Url::createFromUrl((string) $oURL);
    }

    public function fnPrepareURL(string $sPath, array $aArgs=[], bool $bAddCurrentURL=false)
    {
        $oURL = $this->fnCopyURL($this->oBaseURL);
        $oURL->setPath($sPath);
        if ($bAddCurrentURL) {
            $aArgs['redirect_url'] = $this->oCurrentURL->__toString();
        }
        $oURL->setQuery($aArgs);
        return $oURL;
    }

    public function fnPrepareURLFromCurrent(array $aArgs=[], bool $bAddCurrentURL=false)
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

    public function fnGetCurrentURL(): string
    {
        $sURL = (isset($this->aServer['HTTPS']) && $this->aServer['HTTPS'] === 'on' ? "https" : "http");
        $sURL .= "://".$this->aServer['HTTP_HOST'].$this->aServer['REQUEST_URI'];
        return $sURL;
    }

    public static function fnBuild(): Request
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

    public function fnGetInput(): string
    {
        return $this->sInput = ($this->sInput ?: file_get_contents(static::INPUT));
    }

    public function fnGetInputAsJSON(): array
    {
        return json_decode($this->sInput, true);
    }
}
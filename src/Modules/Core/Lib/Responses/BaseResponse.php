<?php
declare(strict_types=1);

namespace Hightemp\AndataRu\Modules\Core\Lib\Responses;

/**
 * Базвый класс ответа сервера
 */
class BaseResponse
{    
    /** @var string $sContent Содержимое ответа */
    public string $sContent = "";
    /** @var array $aHeaders Заголовки */
    public array $aHeaders = [];
    /** @var int $iCode Код ответа */
    public int $iCode = 200;
    /** @var string $sContentType Содержимое заголовка Content-Type */
    public string $sContentType = "text/plain";
    
    /**
     * Метод устанавливает содержимое ответа
     *
     * @param  string $sContent
     * @return void
     */
    public function fnSetContent(string $sContent): void
    {
        $this->sContent = $sContent;
    }
    
    /**
     * Метод возвращает содержимое ответа
     *
     * @return string
     */
    public function fnGetContent(): string
    {
        return $this->sContent;
    }
    
    /**
     * Метод выставляет код ответа, заголовок `Content-Type`, 
     * так же дополнительные заголовки из `$this->aHeaders`
     * и возвращает содержимое ответа через `die`
     *
     * @return void
     */
    public function fnPrintOutputAndExit(): void
    {
        if ($this->iCode) {
            http_response_code($this->iCode);
        }

        header("Content-Type: {$this->sContentType}");
        
        if ($this->aHeaders) {
            foreach ($this->aHeaders as $sHeader) {
                header($sHeader);
            }
        }
        die($this->fnGetContent());
    }
}
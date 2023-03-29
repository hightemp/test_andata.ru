<?php
declare(strict_types=1);

namespace Hightemp\AndataRu\Modules\Core\Lib\Loggers;

class BaseLogger
{
    const MT_MESSAGE = 'message';
    const MT_ERROR = 'error';
    const MT_WARNING = 'warning';
    const MT_INFO = 'info';
    const MT_DEPRECATED = 'deprecated';
    const MT_NOTICE = 'notice';

    public static $oInstance = null;
    
    /**
     * Фабричный метод создание сущности логгера
     *
     * @return BaseLogger
     */
    public static function fnBuild(): BaseLogger
    {
        return (static::$oInstance = new static());
    }
    
    /**
     * Метод заглушка, для обновления заголовка лога
     *
     * @param  array $aData
     * @return void
     */
    public function fnUpdateHeader(?array $aData):void
    {
    }

    /**
     * Метод заглушка, для записи в лог
     *
     * @param  string $sType
     * @param  string $sMessage
     * @param  array $aData
     * @return void
     */
    public function fnWrite(string $sType, string $sMessage, array $aData=[]): void
    {

    }
    
    /**
     * Метод записи сообщения
     *
     * @param  string $sMessage
     * @param  array $aData
     * @return void
     */
    public function fnWriteMessage(string $sMessage, array $aData=[]): void
    {
        $this->fnWrite(static::MT_MESSAGE, $sMessage, $aData);
    }
    
    /**
     * Метод записи ошибки
     *
     * @param  string $sMessage
     * @param  array $aData
     * @return void
     */
    public function fnWriteError(string $sMessage, array $aData=[]): void
    {
        $this->fnWrite(static::MT_ERROR, $sMessage, $aData);
    }
    
    /**
     * Метод записи сообщения
     *
     * @param  string $sMessage
     * @param  array $aData
     * @return void
     */
    public function fnWriteWarning(string $sMessage, array $aData=[]): void
    {
        $this->fnWrite(static::MT_WARNING, $sMessage, $aData);
    }
    
    /**
     * Метод записи сообщения
     *
     * @param  string $sMessage
     * @param  array $aData
     * @return void
     */
    public function fnWriteInfo(string $sMessage, array $aData=[]): void
    {
        $this->fnWrite(static::MT_INFO, $sMessage, $aData);
    }
    
    /**
     * Метод заглушка, удаление старых сообщений
     *
     * @return void
     */
    public function fnRemoveOld(): void
    {
    }
    
    /**
     * Метод заглушка, очистить старые сообщения
     *
     * @return void
     */
    public function fnClean(): void
    {
    }
    
    /**
     * Метод для получения времени
     *
     * @return float
     */
    public function fnGetMicrotime(): float
    {
        return microtime(true);
    }
    
    /**
     * Метод для получения даты
     *
     * @return string
     */
    public function fnGetCurrentDate(): string
    {
        return date("Y-m-d H:i:s");
    }
}
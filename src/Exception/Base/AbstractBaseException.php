<?php

namespace Varena\SDK\Exception\Base;

abstract class AbstractBaseException extends \Exception
{
    abstract public function getLevel(): int;

    /**
     * 错误日志需要包含的信息
     *
     * @return string
     */
    public function getLogMessage(): string
    {
        return $this->getMessage();
    }

    /**
     * 错误信息，该信息直接展示给调用方
     *
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->getMessage();
    }
}

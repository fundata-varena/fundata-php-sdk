<?php

namespace Varena\SDK\Exception\Base;

trait HideErrorMessageTrait
{
    /**
     * @return string
     *
     * @see AbstractBaseException::getErrorMessage()
     */
    final public function getErrorMessage(): string
    {
        return '系统异常，请稍后再试';
    }
}

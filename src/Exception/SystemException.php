<?php

namespace Varena\SDK\Exception;

use Varena\SDK\Exception\Base\ErrorException;
use Varena\SDK\Exception\Base\HideErrorMessageTrait;

class SystemException extends ErrorException
{
    use HideErrorMessageTrait;
}

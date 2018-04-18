<?php

namespace Varena\SDK\Exception\Base;

use Varena\SDK\Common\InfoLevel;

class NoticeException extends AbstractBaseException
{
    final public function getLevel(): int
    {
        return InfoLevel::NOTICE;
    }
}

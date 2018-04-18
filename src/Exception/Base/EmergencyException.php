<?php

namespace Varena\SDK\Exception\Base;

use Varena\SDK\Common\InfoLevel;

class EmergencyException extends AbstractBaseException
{
    final public function getLevel(): int
    {
        return InfoLevel::EMERGENCY;
    }
}

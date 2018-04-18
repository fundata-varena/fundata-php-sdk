<?php

namespace Varena\SDK\Request\Helper;

class UniqueRequest
{
    /**
     * @var string
     */
    private static $uniqueRequestId = null;

    public static function getId(): string
    {
        if (is_null(self::$uniqueRequestId)) {
            self::$uniqueRequestId = self::generateId();
        }

        return self::$uniqueRequestId;
    }

    /**
     * 规则:
     * ip + pid + timestamp
     */
    private static function generateId(): string
    {
        $host = gethostname();
        $pid = getmypid();
        $time = time();

        return "$host-$pid-$time";
    }
}

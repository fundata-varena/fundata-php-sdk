<?php

namespace Varena\SDK\Common;

class InfoLevel
{
    /**
     * 按信息的重要性排序，不使用连续数字，以方便后续在中间插入自定义等级
     *
     * @see https://tools.ietf.org/html/rfc5424
     */
    const DEBUG = 100;
    const INFO = 200;
    const NOTICE = 300;
    const WARNING = 400;
    const ERROR = 500;
    const CRITICAL = 600;
    const ALERT = 700;
    const EMERGENCY = 800;

    private static $arr = [
        self::DEBUG => 'debug',
        self::INFO => 'info',
        self::NOTICE => 'notice',
        self::WARNING => 'warning',
        self::ERROR => 'error',
        self::CRITICAL => 'critical',
        self::ALERT => 'alert',
        self::EMERGENCY => 'emergency',
    ];

    public static function label(int $level): string
    {
        return self::exists($level) ? self::$arr[$level] : '未知等级';
    }

    public static function exists(int $level): bool
    {
        return isset(self::$arr[$level]);
    }
}

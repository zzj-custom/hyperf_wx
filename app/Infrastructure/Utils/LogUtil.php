<?php


namespace App\Infrastructure\Utils;


use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;
use Psr\Log\LoggerInterface;

class LogUtil
{
    /**
     * 日志处理
     *
     * @param string $name
     * @return LoggerInterface
     */
    public static function get(string $name = 'app'): LoggerInterface
    {
        return ApplicationContext::getContainer()->get(LoggerFactory::class)->get($name);
    }
}

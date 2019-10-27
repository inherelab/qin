<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/9/19
 * Time: 下午11:56
 */

use Psr\Http\Server\RequestHandlerInterface;
use Qin\Application;
use Toolkit\Traits\LogShortTrait;
use Toolkit\Traits\PathAliasTrait;

/**
 * Class Qin
 */
class Qin
{
    use PathAliasTrait, LogShortTrait;

    public const MODE_WEB = 1;
    public const MODE_CLI = 2;

    /**
     * @var \Toolkit\DI\Container
     */
    public static $di;

    /**
     * @var Application
     */
    public static $app;

    /**
     * @var \Swoole\Server
     */
    public static $srv;

    /**
     * @return Application
     */
    public static function app(): Application
    {
        return self::$app;
    }

    public static function get(string $id)
    {
        return self::$di->get($id);
    }

    /**
     * @see ExtraLogger::log()
     * {@inheritDoc}
     * @throws \InvalidArgumentException
     */
    public static function log($level, $message, array $context = [])
    {
        /** @see \Psr\Log\LoggerInterface::log() */
        self::$di->get('logger')->log($level, $message, $context);
    }
}

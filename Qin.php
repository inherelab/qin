<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/9/19
 * Time: 下午11:56
 */

use Psr\Http\Server\RequestHandlerInterface;
use Toolkit\Traits\LogShortTrait;
use Toolkit\Traits\PathAliasTrait;

/**
 * Class Qin
 */
class Qin
{
    use PathAliasTrait, LogShortTrait;

    /**
     * @var \Toolkit\DI\Container
     */
    public static $di;

    /**
     * @var \Qin\Http\App
     */
    public static $app;

    /**
     * @var \Swoole\Server
     */
    public static $srv;

    public static function get(string $id)
    {
        return null;
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

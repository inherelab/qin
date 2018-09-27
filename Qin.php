<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/9/19
 * Time: 下午11:56
 */

use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class Qin
 */
class Qin
{
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
}

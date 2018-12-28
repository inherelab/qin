<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/9/20
 * Time: 上午12:10
 */

namespace Qin;

use Psr\Http\Server\RequestHandlerInterface;
use SwoKit\Http\Server\HttpServer;

/**
 * Class Server
 * @package Qin
 */
class Server
{
    /**
     * @param RequestHandlerInterface $handler
     * @param string $listenOn
     * @throws \Throwable
     */
    public static function runHTTPServe(RequestHandlerInterface $handler, $listenOn = ':9501')
    {
        $http = new HttpServer();
        $http->setHandler($handler);
        $http->start();
    }


}

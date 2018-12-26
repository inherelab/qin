<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/9/27
 * Time: 下午11:19
 */

namespace Qin\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Qin\Http\Context;
use Qin\Http\MiddlewareInterface;

/**
 * Class IgnoreFavIcon
 * @package Qin\Http\Middleware
 */
class IgnoreFavIcon implements MiddlewareInterface
{
    const FAV_ICON = '/favicon.ico';

    /**
     * @param Context $ctx
     * @param \Closure $next
     * @return ResponseInterface
     */
    public function process(Context $ctx, \Closure $next): ResponseInterface
    {
        if ($ctx->req->getUri()->getPath() === self::FAV_ICON) {
            return $ctx->res->withStatus(204);
        }

        return $next($ctx);
    }
}

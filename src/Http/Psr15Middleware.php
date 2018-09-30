<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/9/29
 * Time: 下午6:54
 */

namespace Qin\Http;

use Psr\Http\Message\ResponseInterface;

/**
 * Class WrapPsr15Middleware
 * @package Qin\Http
 */
class Psr15Middleware implements MiddlewareInterface
{
    /**
     * @var \Psr\Http\Server\MiddlewareInterface|\Closure
     */
    protected $middleware;

    /**
     * @param \Psr\Http\Server\MiddlewareInterface|\Closure $handler
     * @return Psr15Middleware
     */
    public static function wrap($handler)
    {
        return new self($handler);
    }

    public function __construct($psr15Handler)
    {
        if (\is_string($psr15Handler) && \class_exists($psr15Handler)) {
            $psr15Handler = new $psr15Handler;
        }

        $this->middleware = $psr15Handler;
    }

    /**
     * @param Context $ctx
     * @param \Closure $next
     * @return ResponseInterface
     */
    public function process(Context $ctx, \Closure $next): ResponseInterface
    {
        if ($this->middleware instanceof \Psr\Http\Server\MiddlewareInterface) {
            // $this->middleware->process($request, $handler);
        }

        // $result = $this->middleware
    }
}

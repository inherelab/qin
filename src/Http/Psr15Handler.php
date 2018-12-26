<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/9/29
 * Time: 下午7:08
 */

namespace Qin\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class Psr15Handler
 * @package Qin\Http
 */
class Psr15Handler implements RequestHandlerInterface
{
    /**
     * @var RequestHandlerInterface|\Closure
     */
    protected $handler;

    public static function wrap($handler)
    {
        return new self($handler);
    }

    public function __construct($handler)
    {
        if (\is_string($handler) && \class_exists($handler)) {
            $handler = new $handler;
        }

        $this->handler = $handler;
    }

    /**
     * @param Context $ctx
     * @return ResponseInterface
     */
    // public function handle(Context $ctx): ResponseInterface
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->handler instanceof RequestHandlerInterface) {
            return $this->handler->handle($request);
        }

        // Function or Closure
        return ($this->handler)($request);
    }
}

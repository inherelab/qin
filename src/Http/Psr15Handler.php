<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/9/29
 * Time: 下午7:08
 */

namespace Qin\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class Psr15Handler
 * @package Qin\Http
 */
class Psr15Handler implements HandlerInterface
{
    /**
     * @var RequestHandlerInterface
     */
    protected $handler;

    /**
     * @param Context $ctx
     * @return ResponseInterface
     */
    public function handle(Context $ctx): ResponseInterface
    {
        return $this->handler->handle($ctx->req);
    }
}

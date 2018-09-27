<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/9/27
 * Time: 下午11:10
 */

namespace Qin\Http;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface MiddlewareInterface
 * @package Qin\Http
 */
interface MiddlewareInterface
{
    /**
     * @param Context $ctx
     * @param \Closure $next
     * @return ResponseInterface
     */
    public function process(Context $ctx, \Closure $next): ResponseInterface;
}

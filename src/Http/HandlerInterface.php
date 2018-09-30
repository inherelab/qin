<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/9/29
 * Time: 下午7:05
 */

namespace Qin\Http;

use Psr\Http\Message\ResponseInterface;

/**
 * Class HandlerInterface
 * @package Qin\Http
 */
interface HandlerInterface
{
    /**
     * @param Context $ctx
     * @return ResponseInterface
     */
    public function handle(Context $ctx): ResponseInterface;
}

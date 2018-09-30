<?php
/**
 * Created by PhpStorm.
 * User: Inhere
 * Date: 2018/5/8 0008
 * Time: 20:09
 */

namespace Qin\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class CrossMiddleware
 * @package Qin\Http\Middleware
 */
class CrossMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ('OPTIONS' === $request->getMethod()) {
            return $this->configResponse(\response());
        }

        $response = $handler->handle($request);

        return $this->configResponse($response);
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     */
    private function configResponse(ResponseInterface $response): ResponseInterface
    {
        return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://mysite')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    }
}

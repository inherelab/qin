<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/9/20
 * Time: 上午12:04
 */

namespace Qin\Http;

use Inhere\Middleware\CallableResolverInterface;
use Inhere\Middleware\MiddlewareStackAwareTrait;
use PhpComp\Http\Message\HttpFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class Dispatcher
 * @package Qin\Http
 */
class Dispatcher implements RequestHandlerInterface
{
    use MiddlewareStackAwareTrait;

    /** @var callable */
    private $coreHandler;

    /**
     * RequestHandler constructor.
     * @param MiddlewareInterface[] $stack
     * @param CallableResolverInterface|null $callableResolver
     * @throws \RuntimeException
     */
    public function __construct(array $stack = [], CallableResolverInterface $callableResolver = null)
    {
        $this->add(...$stack);
        $this->callableResolver = $callableResolver;
    }

    /**
     * @return callable
     */
    public function getCoreHandler(): callable
    {
        return $this->coreHandler;
    }

    /**
     * @param callable $coreHandler
     */
    public function setCoreHandler(callable $coreHandler)
    {
        $this->coreHandler = $coreHandler;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    protected function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        return ($this->coreHandler)($request);
    }

    /**
     * Dispatch the next available middleware and return the response.
     * This method duplicates `next()` to provide backwards compatibility with non-PSR 15 middleware.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function __invoke(ServerRequestInterface $request)
    {
        return $this->callStack($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function internal404Handler(ServerRequestInterface $request): ResponseInterface
    {
        $res = HttpFactory::createResponse();

        return $res->withStatus(404);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function internal405Handler(ServerRequestInterface $request): ResponseInterface
    {
        $res = HttpFactory::createResponse();

        if ($request->getMethod() === 'OPTIONS') {
            return $res->withStatus(200);
        }

        $methods = (array)$request->getAttribute(self::CTXAllowedMethodsKey);
        return $res->withStatus(405)->withHeader('Allow', \implode(',', $methods));
    }

}

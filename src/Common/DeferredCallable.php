<?php

namespace Qin\Component;

use Closure;
use Qin\CallableResolverAwareTrait;
use Psr\Container\ContainerInterface;

/**
 * Class DeferredCallable
 * @package Qin\Component
 */
class DeferredCallable
{
    use CallableResolverAwareTrait;

    private $callable;

    /** @var  ContainerInterface */
    private $container;

    /**
     * DeferredMiddleware constructor.
     * @param callable|string $callable
     * @param ContainerInterface $container
     */
    public function __construct($callable, ContainerInterface $container = null)
    {
        $this->callable = $callable;
        $this->container = $container;
    }

    /**
     * @param array $args
     * @return mixed
     * @throws \RuntimeException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(...$args)
    {
        $callable = $this->resolveCallable($this->callable);

        if ($callable instanceof Closure) {
            $callable = $callable->bindTo($this->container);
        }

        return $callable(...$args);
    }
}

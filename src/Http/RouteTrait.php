<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/9/29
 * Time: 下午6:18
 */

namespace Qin\Http;

use Inhere\Route\Router;
use Qin\Http\Router\Route;

/**
 * Trait RouteTrait
 * @package Qin\Http
 */
trait RouteTrait
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var Route[]
     */
    private $routes = [];

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return new Router();
    }

    public function get(string $path, $handler): Route
    {
        $key = 'GET#' . $path;
        $route = new Route('GET', $path, $handler, []);
        $this->routes[$key] = $route;

        return $route;
    }

    public function match(string $path, string $method)
    {
        return $this->router->match($path, $method);
    }

    public function complete()
    {
        foreach ($this->routes as $route) {
            $this->router->map($route->method, $route->pattern, $route->handler, $route->getOptions());
        }
    }
}

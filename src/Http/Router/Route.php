<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/9/27
 * Time: 下午11:57
 */

namespace Qin\Http\Router;

/**
 * Class Route
 * @package Qin\Http\Router
 */
class Route
{
    /**
     * @var string route pattern
     */
    public $pattern;

    /**
     * @var mixed route handler
     */
    public $handler;

    /**
     * @var string[] map where parameter name => regular expression pattern (or symbol name)
     */
    public $params;

    /**
     * @var array
     */
    public $options = [];

    /**
     * @var array
     */
    public $chain = [];

    /**
     * @param string $pattern
     * @param $handler
     * @param array $params
     * @param array $options
     * @return Route
     */
    public static function create(string $pattern, $handler, array $params, array $options = []): Route
    {
        return new self($pattern, $handler, $params, $options);
    }

    /**
     * Route constructor.
     * @param string $pattern
     * @param $handler
     * @param array $params
     * @param array $options
     */
    public function __construct(string $pattern, $handler, array $params, array $options = [])
    {
        $this->pattern = $pattern;
        $this->handler = $handler;
        $this->params = $params;
        $this->options = $options;
    }

    /**
     * @param string $name
     * @param string $pattern
     * @return $this
     */
    public function param(string $name, string $pattern): self
    {
        $this->params[$name] = $pattern;
        return $this;
    }
}

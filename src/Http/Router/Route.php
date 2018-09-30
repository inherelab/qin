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
     * @var string route method
     */
    public $method;

    /**
     * @var mixed route handler
     */
    public $handler;

    /**
     * @var array map where parameter name => regular expression pattern (or symbol name)
     */
    public $params = [];

    /**
     * @var array
     */
    public $options = [];

    /**
     * @var array
     */
    public $chain = [];

    /**
     * @param string $method
     * @param string $pattern
     * @param $handler
     * @param array $options
     * @return Route
     */
    public static function create(string $method, string $pattern, $handler, array $options = []): Route
    {
        return new self($method, $pattern, $handler, $options);
    }

    /**
     * Route constructor.
     * @param string $method
     * @param string $pattern
     * @param $handler
     * @param array $options
     */
    public function __construct(string $method, string $pattern, $handler, array $options = [])
    {
        $this->method = $method;
        $this->pattern = $pattern;
        $this->handler = $handler;
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

    public function getOptions(bool $containsParams = true): array
    {
        $options = $this->options;

        if ($containsParams) {
            $options['params'] = $this->params;
        }

        return $options;
    }
}

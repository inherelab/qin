<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/9/27
 * Time: 下午10:57
 */

namespace Qin\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Context
 * @package Qin\Http
 */
class Context
{
    /**
     * @var ServerRequestInterface
     */
    public $req;

    /**
     * @var ResponseInterface
     */
    public $res;

    /**
     * data of the context
     * @var array
     */
    public $data = [];

    /**
     * params of the route match
     * @var array
     */
    public $params = [];

    public function init(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->req = $request;
        $this->res = $response;
    }

    /**
     * set context value by key
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * get context value by key
     * @param string $key
     * @param null|mixed $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * get route param by name
     * @param string $name
     * @param string $default
     * @return string
     */
    public function param(string $name, string $default = ''): string
    {
        return $this->params[$name] ?? $default;
    }

    public function reset()
    {
        $this->req = $this->res = null;
        $this->data = $this->params = [];
    }
}

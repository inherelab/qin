<?php
/**
 * Created by PhpStorm.
 * User: Inhere
 * Date: 2018/6/19 0019
 * Time: 18:43
 */

namespace Qin\Http;

use PhpComp\Http\Message\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ApiController
 * @package Mco\Http
 */
class ApiController
{
    /** @var string id */
    protected $id;

    /** @var Context */
    protected $ctx;

    /** @var string Action name */
    protected $action;

    /**
     * BaseController constructor.
     * @param string|null $id
     */
    public function __construct(string $id = null)
    {
        $this->id = $id ?: \get_class($this);
    }

    /**
     * @param string $action
     * @param Context $ctx
     */
    public function init(string $action, Context $ctx)
    {
        $this->ctx = $ctx;
        $this->action = $action;
    }

    /**
     * @return bool
     */
    public function isPjax(): bool
    {
        return $this->ctx->isPjax();
    }

    /**
     * @param string $action
     */
    public function setAction(string $action)
    {
        $this->action = $action;
    }

    /**
     * @param Context $ctx
     */
    public function setCtx(Context $ctx)
    {
        $this->ctx = $ctx;
    }

    /**
     * @param string $url
     * @param int $status
     * @param Response $response
     * @return mixed
     */
    public function redirect($url, int $status = 302, $response = null)
    {
        $response = $response ?: \Mco::$di['response'];

        return $response->redirect($url, $status);
    }

    /**
     * @param int $code
     * @param string $msg
     * @param array $data
     * @param Response $response
     * @return ResponseInterface
     */
    public function jsonRes(int $code = 0, string $msg = 'successful', array $data = [], $response = null): ResponseInterface
    {
        /** @var Response $res */
        $res = $response ?: \mco('response');

        return $res->json([
            'code' => $code,
            'msg'  => $msg,
            'data' => $data
        ]);
    }

    /**
     * @param int $code
     * @param string $msg
     * @param Response $response
     * @return ResponseInterface
     */
    public function errRes(int $code, string $msg = 'operation failure!', $response = null): ResponseInterface
    {
        return $this->jsonRes($code, $msg, [], $response);
    }
}

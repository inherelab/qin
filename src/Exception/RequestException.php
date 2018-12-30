<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-19
 * Time: 14:23
 */

namespace Qin\Exception;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RequestException - This is a Stop Run exception and contains the response
 * @package Qin\Exception
 */
class RequestException extends \Exception
{
    /**
     * A request object
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * A response object to send to the HTTP client
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Create new exception
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        parent::__construct();

        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Get request
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * Get response
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}

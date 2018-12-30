<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-19
 * Time: 14:27
 */

namespace Qin\Exception;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class MethodNotAllowedException
 * @package Qin\Exception
 */
class MethodNotAllowedException extends RequestException
{
    /**
     * HTTP methods allowed
     * @var string[]
     */
    protected $allowedMethods;

    /**
     * Create new exception
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param string[] $allowedMethods
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response, array $allowedMethods)
    {
        parent::__construct($request, $response);
        $this->allowedMethods = $allowedMethods;
    }

    /**
     * Get allowed methods
     * @return string[]
     */
    public function getAllowedMethods(): array
    {
        return $this->allowedMethods;
    }
}

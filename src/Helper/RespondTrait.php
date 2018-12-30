<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/5
 * Time: 16:49
 */

namespace Qin\Helper;

use PhpComp\Http\Message\Response;
use Psr\Http\Message\ResponseInterface;
use Toolkit\Collection\Language;

/**
 * Class RespondTrait
 * @package Qin\Helper
 */
trait RespondTrait
{
    /**
     * @var string
     */
    public static $defaultMsg = 'successful';

    /**
     * @param mixed $data
     * @param int $code
     * @param string $msg
     *
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     */
    public static function json($data = null, int $code = 0, string $msg = ''): ResponseInterface
    {
        return self::jsonRes($code, $msg, $data);
    }

    /**
     * @param int $code
     * @param string $msg
     *
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     */
    public static function errJson(int $code, string $msg = ''): ResponseInterface
    {
        return self::jsonRes($code, $msg);
    }

    /**
     * @param mixed $data
     * @param Response $response
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     */
    public static function rawJson($data, $response = null): ResponseInterface
    {
        /** @var Response $res */
        $res = $response ?: \mco('response');

        return $res->json($data);
    }

    /**
     * @param int $code
     * @param string $msg
     * @param array|mixed $data
     * @param Response $response
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     */
    public static function jsonRes(int $code = 0, string $msg = 'successful', $data = null, $response = null): ResponseInterface
    {
        /** @var Response $res */
        $res = $response ?: \mco('response');

        return $res->json([
            'code' => $code,
            'msg'  => self::getMsgByCode($code),
            'data' => $data ?? new \stdClass()
        ]);
    }

    /**
     * @param int $code
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public static function getMsgByCode($code)
    {
        /** @var Language $lang */
        if ($lang = \Qin::$di->getIfExist('lang')) {
            return $lang->tl('response.'.$code);
        }

        return self::$defaultMsg;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-11-29
 * Time: 13:24
 */

namespace Qin\Component;

use Toolkit\PhpUtil\PhpHelper;

/**
 * Class EnvDetector
 * @package Qin\Component
 *
 * ```php
 * $env = EnvDetector::getByHost();
 * $env = EnvDetector::getByDomain();
 * ```
 */
class EnvDetector
{
    /**
     * @var array
     */
    private static $domain2env = [
        // domain keywords => env name
        'pre' => 'pre',
        'test' => 'test',
        '127.0.0.1' => 'dev',
        'dev' => 'dev'
    ];

    /**
     * @var array
     */
    private static $host2env = [
        // host keywords => env name
        // 'myPc' => 'dev',
    ];

    /**
     * get Env Name By Host
     * @param null|string $hostname
     * @param string $defaultEnv
     * @return string
     */
    public static function getByHost(string $defaultEnv = '', string $hostname = ''): string
    {
        $hostname = $hostname ?: \gethostname();

        if (!$hostname) {
            return $defaultEnv;
        }

        foreach (self::$host2env as $kw => $env) {
            if (false !== strpos($hostname, $kw)) {
                return $env;
            }
        }

        return $defaultEnv;
    }

    /**
     * get Env Name By Domain
     * @param string $defaultEnv
     * @param null|string $domain
     * @return string
     */
    public static function getByDomain(string $defaultEnv = '', string $domain = ''): string
    {
        $domain = $domain ?: PhpHelper::serverParam('HTTP_HOST');

        if (!$domain) {
            return $defaultEnv;
        }

        foreach (self::$domain2env as $kw => $env) {
            if (false !== strpos($domain, $kw)) {
                return $env;
            }
        }

        return $defaultEnv;
    }

    /**
     * @return array
     */
    public static function getDomain2env(): array
    {
        return self::$domain2env;
    }

    /**
     * @param array $domain2env
     */
    public static function setDomain2env(array $domain2env)
    {
        self::$domain2env = $domain2env;
    }

    /**
     * @return array
     */
    public static function getHost2env(): array
    {
        return self::$host2env;
    }

    /**
     * @param array $host2env
     */
    public static function setHost2env(array $host2env)
    {
        self::$host2env = $host2env;
    }
}

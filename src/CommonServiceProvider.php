<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/11/27
 * Time: 下午11:53
 */

namespace Qin;

use Inhere\Event\EventManager;
use Toolkit\Collection\Language;
use Toolkit\DI\Container;
use Toolkit\DI\ServiceProviderInterface;
use Toolkit\Error\Handler\ErrorRenderer;

/**
 * Class CommonServiceProvider
 * @package Qin
 */
class CommonServiceProvider implements ServiceProviderInterface
{
    /**
     * 注册一项服务(可能含有多个服务)提供者到容器中
     * @param Container $di
     * @throws \RangeException
     * @throws \Toolkit\DI\Exception\DependencyResolutionException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function register(Container $di)
    {
        if (!isset($di['eventManager'])) {
            $di->set('eventManager', function () {
                return new EventManager();
            });
        }

        if (!isset($di['errorHandler'])) {
            /**
             * This service MUST return a callable
             * that accepts three arguments:
             * - Instance of \Exception
             * The callable MUST return an instance of
             * \Psr\Http\Message\ResponseInterface.
             * @param Container $di
             * @return callable
             */
            $di['errorHandler'] = function ($di) {
                return new ErrorRenderer(config('errorRender'), $di->get('logger'));
            };
        }

        $di->set('language', function ($di) {
            return new Language(\config('language', []));
        }, [
            'aliases' => ['lang']
        ]);
    }
}

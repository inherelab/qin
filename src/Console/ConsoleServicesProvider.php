<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-18
 * Time: 18:58
 */
namespace Qin\Console;

use Inhere\Console\IO\Input;
use Inhere\Console\IO\InputInterface;
use Inhere\Console\IO\Output;
use Inhere\Console\IO\OutputInterface;
use Toolkit\DI\Container;
use Toolkit\DI\ServiceProviderInterface;
use Qin\Base\CallableResolver;
use Qin\Base\CallableResolverInterface;
use Toolkit\Error\Handler\ErrorRenderer;

/**
 * the default Service Provider.
 */
class ConsoleServicesProvider implements ServiceProviderInterface
{
    /**
     * Register default services.
     *
     * @param Container $di A DI container implementing ArrayAccess and ContainerInterface
     * @throws \InvalidArgumentException
     */
    public function register(Container $di)
    {
        if (!isset($di['input'])) {
            /**
             * Console input object
             * @return InputInterface
             */
            $di['input'] = function () {
                return new Input();
            };
        }

        if (!isset($di['output'])) {
            /**
             * Console output object
             * @return OutputInterface
             */
            $di['output'] = function () {
                return new Output();
            };
        }

        if (!isset($di['callableResolver'])) {
            /**
             * Instance of CallableResolverInterface
             * @param Container $di
             * @return CallableResolverInterface
             */
            $di['callableResolver'] = function ($di) {
                return new CallableResolver($di);
            };
        }
    }
}

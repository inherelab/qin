<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/9/3
 * Time: 上午12:57
 */

namespace Qin;

use Toolkit\DI\Container;

/**
 * interface AppInterface
 * @package Sws
 */
interface AppInterface
{
    const ON_START = 'app.start';
    const ON_STOP = 'app.stop';
    const ON_BEFORE_REQUEST = 'app.beforeRequest';
    const ON_AFTER_REQUEST = 'app.afterRequest';
    const ON_SHUTDOWN = 'app.shutdown';

    /**
     * @param $id
     * @return mixed
     */
    public function get($id);

    /**
     * @param $id
     * @return mixed
     */
    public function has($id);

    /**
     * @param Container $di
     */
    public function setDi(Container $di);

    /**
     * @return Container
     */
    public function getDi(): Container;

    /**
     * @return string
     */
    public function getName(): string;
}

<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018-12-31
 * Time: 21:05
 */

namespace Qin;

use Qin\Concern\AppTrait;

/**
 * Class App
 * @package Qin
 */
class Application
{
    use AppTrait;

    protected $basePath = '';

    public function createApp(): void
    {

    }

    public function initConfig(array $config): void
    {

    }

    public function createContainer(): void
    {

    }

    public function registerProviders(): void
    {
        $this->di->registerServiceProviders($this->providers);
    }

    public function run(int $mode= \Qin::MODE_WEB): void
    {

    }
}

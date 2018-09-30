<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-09-19
 * Time: 15:06
 */
namespace Qin;

use Toolkit\ArrUtil\Arr;
use Toolkit\Collection\Configuration;
use Toolkit\DI\Container;

/**
 * Class AppTrait
 * @package Qin
 */
trait AppTrait
{
    /**
     * @var Container
     */
    protected $di;

    /**
     * @var array
     */
    protected $providers = [
        CommonServiceProvider::class,
    ];

    /**
     * @param array $config
     * @throws \Toolkit\DI\Exception\DependencyResolutionException
     * @throws \InvalidArgumentException
     * @return Container
     */
    protected function initContainer(array $config): Container
    {
        \Qin::$app = $this;
        \Qin::$di = $di = new Container;

        \define('APP_DEBUG', (bool)$config['debug']);
        \Qin::setAlias('@qin', \dirname(__DIR__));

        // Register the global configuration as config
        $di['config'] = new Configuration($config);

        // register providers
        $providers = Arr::remove($config, 'serviceProviders');
        $this->providers = \array_merge($this->providers, $providers);

        $di->registerServiceProviders($this->providers);

        // register user services
        $services = Arr::remove($config, 'services');

        $di->sets($services);
        $di->set('app', $this);

        // on runtime end
        \register_shutdown_function([$this, 'onShutdown']);

        return ($this->di = $di);
    }

    /**
     * 注册一个 callback ，它会在脚本执行完成或者 exit() 后被调用
     * @see http://php.net/manual/zh/function.register-shutdown-function.php
     */
    public function onShutdown()
    {
        \trigger(AppInterface::ON_SHUTDOWN);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function get($id)
    {
        return $this->di->get($id);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function getIfExist($id)
    {
        return $this->di->getIfExist($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function has($id)
    {
        return $this->di->has($id);
    }

    /**
     * @return Container
     */
    public function getDi(): Container
    {
        return $this->di;
    }

    /**
     * @param Container $di
     */
    public function setDi(Container $di)
    {
        $this->di = $di;
    }

    /**
     * @return array
     */
    public function getProviders(): array
    {
        return $this->providers;
    }
}

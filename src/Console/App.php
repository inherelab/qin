<?php

namespace Qin\Console;

use Inhere\Console\Application;
use Qin\Console\Command\RouteCommand;
use Qin\Console\ConsoleServicesProvider;
use Qin\Console\Controller\GenController;
use Qin\AppInterface;
use Qin\AppTrait;

/**
 * Class Console App
 * @package Qin\Console
 */
class App extends Application implements AppInterface
{
    use AppTrait;

    /**
     * @var array
     */
    protected static $bootstraps = [
        'commands' => [
            // CommandUpdateCommand::class,
            RouteCommand::class,
        ],
        'controllers' => [
            GenController::class,
        ],
    ];

    /**
     * Constructor.
     * @param array $config
     * @throws \InvalidArgumentException
     */
    public function __construct(array $config = [])
    {
        $this->providers[] = ConsoleServicesProvider::class;

        $di = $this->initDI($config);

        $meta = [
            'name' => 'Micro Console',
            'version' => '0.0.1',
            'publishAt' => '2017.10.19',
            'debug' => $di->get('config')['debug'],
            'rootPath' => \BASE_PATH,
        ];

        parent::__construct($meta, $di->get('input'), $di->get('output'));

        // $config->loadArray($this->config);
        $this->loadBootstrapCommands();
    }

    protected function init()
    {
        parent::init();

        //$this->prepare();
       // $errHandler = new ErrorHandler();
    }

    /**
     * loadBuiltInCommands
     * @throws \InvalidArgumentException
     */
    public function loadBootstrapCommands()
    {
        /** @var \inhere\console\Command $command */
        foreach ((array)static::$bootstraps['commands'] as $command) {
            $this->command($command::getName(), $command);
        }

        /** @var \inhere\console\Controller $controller */
        foreach ((array)static::$bootstraps['controllers'] as $controller) {
            $this->controller($controller::getName(), $controller);
        }
    }
}

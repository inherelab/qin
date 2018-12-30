<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-18
 * Time: 18:58
 */

namespace Qin\Plugins;

use Qin\Exception\PluginException;

/**
 * Class PluginManager
 * @package Qin\Plugins
 */
class PluginManager
{
    const DEFAULT_THEME = 'official';

    /**
     * @var PluginInterface[]
     */
    private $plugins = [];

    /**
     * @var array
     */
    private $enabledPlugins = [];

    /**
     * @var array
     */
    private $disabledPlugins = [];

    /**
     * These are always activated and cannot be deactivated
     * @var array
     */
    protected $alwaysEnables = [
        self::DEFAULT_THEME,
    ];

    /**
     * PluginManager constructor.
     * @param array $plugins
     */
    public function __construct(array $plugins = [])
    {
        $this->setPlugins($plugins);
    }

    public function add($definition)
    {
        return $this;
    }

    public function loadPlugins(array $plugins)
    {

    }

    public function unloadPlugins()
    {

    }

    public function getPlugin($name)
    {

    }

    public function isPlugin($pluginName)
    {

    }

    public function install(PluginInterface $plugin)
    {
        try {
            $plugin->install();
        } catch (\Exception $e) {
            throw new PluginException($plugin->getName(), $e->getMessage());
        }
    }

    public function uninstall($pluginName)
    {

    }

    /**
     * Activate the specified plugin and install (if needed)
     *
     * @param string $pluginName Name of plugin
     * @throws \Exception
     */
    public function enablePlugin($pluginName)
    {

    }
    public function activatePlugin($pluginName)
    {
        $this->enablePlugin($pluginName);
    }

    public function isEnabled($pluginName)
    {

    }

    public function isDisabled($pluginName)
    {

    }

    /**
     * @param string $pluginName
     * @return bool
     */
    public function isValidName($pluginName): bool
    {
        return (bool) preg_match('/^[a-zA-Z]([\w]*)$/D', $pluginName);
    }

    /**
     * @return array
     */
    public function getNames(): array
    {
        return [];
    }

    /**
     * @return PluginInterface[]
     */
    public function getPlugins(): array
    {
        return $this->plugins;
    }

    /**
     * @param PluginInterface[] $plugins
     */
    public function setPlugins(array $plugins)
    {
        foreach ($plugins as $plugin) {
            $this->add($plugin);
        }
    }

    /**
     * @return array
     */
    public function getDisabledPlugins(): array
    {
        return $this->disabledPlugins;
    }

    /**
     * @return array
     */
    public function getEnabledPlugins(): array
    {
        return $this->enabledPlugins;
    }
}

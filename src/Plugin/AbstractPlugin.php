<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-11-01
 * Time: 16:33
 */

namespace Qin\Plugins;

/**
 * Class AbstractPlugin
 * @package Qin\Plugins
 */
abstract class AbstractPlugin implements PluginInterface
{
    /**
     * Name of this plugin.
     * @var string
     */
    protected $name;

    /**
     * Info of this plugin.
     * @var array
     */
    protected $info;

    /**
     * AbstractPlugin constructor.
     * @param string $name
     */
    public function __construct(string $name = null)
    {
        if (!$name) {
            $nodes = explode('\\', \get_class($this));
            $name = end($nodes);
        }

        $this->name = $name;

        $cacheId = 'Plugin:' . $name . ':Metadata';
        $cache = \Qin::get('cache');

        if ($cache->contains($cacheId)) {
            $this->info = $cache->fetch($cacheId);
        } else {
            $this->loadInformation();
            $cache->save($cacheId, $this->info);
        }
    }

    /**
     * the plugin bootstrap init
     */
    public function init()
    {
        $this->registerEvents();

        $this->loadTranslates();

        $this->registerAssets();
    }

    /**
     * Returns a list of events with associated event observers.
     * @return array
     */
    public function registerEvents(): array
    {
        return [
//            'AssetManager.getStylesheetFiles' => 'getStylesheetFiles',
//            'AssetManager.getJavaScriptFiles' => 'getJsFiles',
//            'Translate.getClientSideTranslationKeys' => 'getClientSideTranslationKeys',
        ];
    }

    /**
     * @return array
     */
    public function registerAssets(): array
    {
        return [
            'cssFiles' => [],
            'jsFiles' => [],
        ];
    }

    /**
     * @return array
     */
    public function loadTranslates(): array
    {
        return [
            //
        ];
    }

    /**
     * Installs the plugin. Derived classes should implement this class if the plugin
     * needs to:
     * - create tables
     * - update existing tables
     * - etc.
     * @throws \Exception if installation of fails for some reason.
     */
    public function install()
    {
        // do something ...
    }

    /**
     * do something on plugin loaded
     */
    public function onLoaded()
    {
    }

    /**
     * Uninstalls the plugin. Derived classes should implement this method if the changes
     * made in {@link install()} need to be undone during uninstallation.
     * In most cases, if you have an {@link install()} method, you should provide an {@link uninstall()} method.
     * @throws \Exception if uninstall of fails for some reason.
     */
    public function uninstall()
    {
        // do something ...
    }

    public function enable()
    {
        // do something ...
    }

    public function disable()
    {
        // do something ...
    }

    protected function loadInformation()
    {
        $this->info = [];
    }

    protected function pluginValidate()
    {

    }

    /**
     * Returns the plugin version number.
     * @return string
     */
    final public function getVersion(): string
    {
        return $this->getInfo('version');
    }

    /**
     * Returns `true` if this plugin is a theme, `false` if otherwise.
     * @return bool
     */
    public function isTheme(): bool
    {
        return (bool)$this->getInfo('isTheme', false);
    }

    /**
     * @return string
     */
    final public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    final public function getInfo(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->info;
        }

        return $this->info[$key] ?? $default;
    }

    /**
     * @param array $info
     */
    public function setInfo(array $info)
    {
        $this->info = $info;
    }

}

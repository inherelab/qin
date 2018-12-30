<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-11-01
 * Time: 16:32
 */

namespace Qin\Plugins;

/**
 * Interface PluginInterface
 * @package Qin\Plugins
 */
interface PluginInterface
{
    public function init();

//    public function load();

    public function run();

    /**
     * Returns a list of events with associated event observers.
     * @return array
     */
    public function registerEvents(): array ;

    /**
     * Installs the plugin. Derived classes should implement this class if the plugin
     * needs to:
     * - create tables
     * - update existing tables
     * - etc.
     * @throws \Exception if installation of fails for some reason.
     */
    public function install();

    /**
     * Uninstalls the plugin. Derived classes should implement this method if the changes
     * made in {@link install()} need to be undone during uninstallation.
     * In most cases, if you have an {@link install()} method, you should provide an {@link uninstall()} method.
     * @throws \Exception if uninstall of fails for some reason.
     */
    public function uninstall();

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function getInfo(string $key = null, $default = null);
}

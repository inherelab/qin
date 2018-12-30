<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-18
 * Time: 18:58
 */

namespace Qin\Web;

use Qin\Exception\InvalidConfigException;

/**
 * Class Theme
 * @package Qin\Web
 */
class Theme
{
    /**
     * @var array the mapping between view directories and their corresponding themed versions.
     * This property is used by [[applyTo()]] when a view is trying to apply the theme.
     * Path aliases can be used when specifying directories.
     * If this property is empty or not set, a mapping [[Application::basePath]] to [[basePath]] will be used.
     */
    public $pathMap;

    private $_baseUrl;


    /**
     * @return string the base URL (without ending slash) for this theme. All resources of this theme are considered
     * to be under this base URL.
     */
    public function getBaseUrl(): string
    {
        return $this->_baseUrl;
    }

    /**
     * @param string $url the base URL or path alias for this theme. All resources of this theme are considered
     * to be under this base URL.
     */
    public function setBaseUrl($url)
    {
        $this->_baseUrl = rtrim(Yii::getAlias($url), '/');
    }

    private $_basePath;

    /**
     * @return string the root path of this theme. All resources of this theme are located under this directory.
     * @see pathMap
     */
    public function getBasePath(): string
    {
        return $this->_basePath;
    }

    /**
     * @param string $path the root path or path alias of this theme. All resources of this theme are located
     * under this directory.
     * @see pathMap
     */
    public function setBasePath($path)
    {
        $this->_basePath = \Qin::alias($path);
    }

    /**
     * Converts a file to a themed file if possible.
     * If there is no corresponding themed file, the original file will be returned.
     * @param string $path the file to be themed
     * @return string the themed file, or the original file if the themed version is not available.
     * @throws InvalidConfigException if [[basePath]] is not set
     */
    public function applyTo($path): string
    {
        $pathMap = $this->pathMap;
        if (empty($pathMap)) {
            if (($basePath = $this->getBasePath()) === null) {
                throw new InvalidConfigException('The "basePath" property must be set.');
            }
            $pathMap = [Yii::$app->getBasePath() => [$basePath]];
        }

        $path = FileHelper::normalizePath($path);

        foreach ($pathMap as $from => $tos) {
            $from = FileHelper::normalizePath(Yii::getAlias($from)) . DIRECTORY_SEPARATOR;
            if (strpos($path, $from) === 0) {
                $n = \strlen($from);
                foreach ((array) $tos as $to) {
                    $to = FileHelper::normalizePath(Yii::getAlias($to)) . DIRECTORY_SEPARATOR;
                    $file = $to . substr($path, $n);
                    if (is_file($file)) {
                        return $file;
                    }
                }
            }
        }

        return $path;
    }

    /**
     * Converts a relative URL into an absolute URL using [[baseUrl]].
     * @param string $url the relative URL to be converted.
     * @return string the absolute URL
     * @throws InvalidConfigException if [[baseUrl]] is not set
     */
    public function getUrl($url)
    {
        if (($baseUrl = $this->getBaseUrl()) !== null) {
            return $baseUrl . '/' . ltrim($url, '/');
        }

        throw new InvalidConfigException('The "baseUrl" property must be set.');
    }

    /**
     * Converts a relative file path into an absolute one using [[basePath]].
     * @param string $path the relative file path to be converted.
     * @return string the absolute file path
     * @throws InvalidConfigException if [[baseUrl]] is not set
     */
    public function getPath($path)
    {
        if (($basePath = $this->getBasePath()) !== null) {
            return $basePath . DIRECTORY_SEPARATOR . ltrim($path, '/\\');
        }

        throw new InvalidConfigException('The "basePath" property must be set.');
    }
}

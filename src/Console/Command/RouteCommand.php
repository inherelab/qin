<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-12-18
 * Time: 10:54
 */

namespace Qin\Console\Command;

use Inhere\Console\Command;
use Toolkit\File\FileFinder;
use Ulue\Annotations\Annotations;

/**
 * Class RouteCommand
 * @package Qin\Console\Command
 */
class RouteCommand extends Command
{
    protected static $name = 'route:build';
    protected static $description = 'parse all controllers and collect routes, write to app/routes.php';

    /**
     * @var array
     */
    private $map = [
        // namespace  => relative path
        'App\Http\Controller' => 'app/Http/Controller',
        //'App\Modules\Api\Controllers' => 'app/Modules/Api/Controllers',
        //'App\Modules\Admin\Controllers' => 'app/Modules/Admin/Controllers',
    ];

    /**
     * @var array
     */
    private $routes = [];

    /**
     * @var string
     */
    private $basePath = BASE_PATH;

    /** @var string */
    private $routeFile = 'app/Http/routes.php';

    /** @var string */
    private $tplFile = 'res/templates/routes.stub';

    /**
     * $router->get('/', App\Http\Controllers\Content::class . '@index')
     *  ->setName('profile')
     *  ->add(AuthCheck::class);
     * @var string
     */
    private $routeTpl = "\$router->map({{methods}}, '{{route}}', '{{handler}}', {{options}});";

    /**
     * @options
     *  --output STRING    Setting the routes file(<comment>app/routes.php</comment>)
     *  -y, --yes BOOL     Whether display goon tips message.
     * @param  \Inhere\Console\IO\Input $in
     * @param  \Inhere\Console\IO\Output $out
     * @return int|mixed
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     */
    protected function execute($in, $out)
    {
        if ($file = $in->getOpt('output')) {
            $this->routeFile = $file;
        }

        $reader = new Annotations();

        foreach ($this->map as $np => $dir) {
            $path = $this->basePath . DIRECTORY_SEPARATOR . $dir;
            $finder = FileFinder::create()
                ->files()
                ->in($path)
                ->name('*.php')
                ->notName('Base.php');

            $this->output->write("Will find and parse controller in the dir:\n    <comment>$path</comment>");

            foreach ($finder as $file) {
                $class = $np . '\\' . $this->getClassPart($file, $path);
                $refClass = $reader::createReflection($class);

                if ($refClass->isAbstract()) {
                    $out->write('Skip: ' . $class);
                    continue;
                }

                $prefix = $this->getRoutePrefix($reader->getClassAnnotations($class), \basename($file, '.php'));

                if ($prefix === '') {
                    $out->write('Skip: ' . $class);
                    continue;
                }

                if ($this->getApp()->isDebug()) {
                    $this->output->write("Parse class $class");
                }

                // record class name
                $this->routes[] = "@see \\$class";
                // $routeClass = substr($class, 0, -\strlen('Controller'));
                $prefix = '/' . \trim($prefix, '/ ');

                $this->collectRoutes(
                    $prefix,
                    $class,
                    $reader->yieldMethodsAnnotations($class, \ReflectionMethod::IS_PUBLIC)
                );
            }
        }

        $found = \count($this->routes);
        $out->info('Routes collect completed, found: ' . $found);

        if (!$found) {
            $out->write('   Bye!');
            return 0;
        }

        $yes = $in->getSameOpt(['y', 'yes'], false);
        $file = $this->basePath . '/' . $this->routeFile;

        $out->write("Now, will dump collected routes data to routes file:\n    $file");

        if (!$yes && !$this->confirm('Continue generate routes file')) {
            $out->write('   Bye!');
            return 0;
        }

        $this->generateRoutesFile($file, $out);

        unset($reader);
        return 0;
    }

    /**
     * @param array $annotations
     * @param string $className
     * @return string
     */
    private function getRoutePrefix(array $annotations, string $className): string
    {
        if (!$annotations) {
            return '';
        }

        foreach ($annotations as list($name, $args)) {
            /**
             * e.g
             * @Controller()
             * @Controller("/prefix")
             * @Controller(prefix="/prefix")
             */
            if ($name === 'Controller') {
                if (!empty($args['prefix'])) {
                    return $args['prefix'];
                }

                $prefix = isset($args[0]) ? \trim($args[0]) : '';

                return $prefix ?: \lcfirst(\substr($className, 0, -10));
            }
        }

        return '';
    }

    /**
     * @param $prefix
     * @param $routeClass
     * @param array[]|\Generator $collections
     */
    private function collectRoutes(string $prefix, string $routeClass, $collections)
    {
        foreach ($collections as $action => $collection) {
            $actionName = $action;

            foreach ($collection as list($name, $args)) {
                /**
                 * @Route(method="GET") - use action name
                 * @Route("/path", method="GET")
                 * @Route(path="/path", method="GET")
                 */
                if ($name === 'Route') {
                    if (isset($args['path'])) {
                        $path = \trim($args['path']);
                    } else {
                        $path = \trim(!empty($args[0]) ? $args[0] : $actionName);
                    }

                    // use prefix as route path
                    if ($path === '@') {
                        $route = $prefix;
                    } else {
                        $route = $path[0] === '/' ? $path : $prefix . '/' . $path;
                    }


                    $handler = '\\' . $routeClass . '@' . $actionName;
                    $methods = $args['method'] ?? 'GET';
                    $options = [];

                    if (!empty($args['params'])) {
                        $options['params'] = $args['params'];
                    }

                    if ($this->getApp()->isDebug()) {
                        $this->output->write(" + <info>$route</info>");
                    }

                    $this->routes[] = [
                        '{{route}}' => $route === '/' ? $route : \rtrim($route, '/'),
                        '{{handler}}' => $handler,
                        '{{methods}}' => $this->formatArrayData((array)$methods),
                        '{{options}}' => $this->formatMapData($options),
                    ];
                }
            }
        }
    }

    /**
     * @param array $data
     * @return string
     */
    private function formatArrayData(array $data): string
    {
        return \str_replace('"', '\'', \json_encode($data));
    }

    /**
     * @param array $data
     * @return string
     */
    private function formatMapData(array $data): string
    {
        if (!$data) {
            return '[]';
        }

        return \str_replace(
            ["=> \n", '"', '\\\\'],
            ['=>', '\'', '\\'],
            \var_export($data, true)
        );
    }

    /**
     * @param \SplFileInfo $file
     * @param string $basePath
     * @return string
     */
    private function getClassPart($file, string $basePath): string
    {
        $realPath = $file->getRealPath();
        $pathPrefix = \str_replace('/', DIRECTORY_SEPARATOR, $basePath) . DIRECTORY_SEPARATOR;

        $pos = \strpos($realPath, $pathPrefix);
        $relativePath = ($pos !== false) ? \substr_replace($realPath, '', $pos, \strlen($pathPrefix)) : $realPath;

        return \str_replace('/', '\\', \substr($relativePath, 0, -4));
    }

    /**
     * @param string $file
     * @param \Inhere\Console\IO\Output $out
     */
    private function generateRoutesFile(string $file, $out)
    {
        $routes = "// The following information is automatically generated by the command `route:build`\n";
        $tplFile = $this->basePath . '/' . $this->tplFile;
        $content = \file_get_contents($tplFile);

        foreach ($this->routes as $info) {
            if (\is_string($info)) {
                $routes .= "\n/** $info */\n";
            } else {
                $routes .= \strtr($this->routeTpl, $info) . "\n";
            }
        }

        $content = \strtr($content, [
            '{{date}}' => \date('Y-m-d'),
            '{{time}}' => \date('H:i'),
            '{{tplFile}}' => $this->tplFile,
            '{{routesData}}' => $routes,
        ]);

        if (false === \file_put_contents($file, $content)) {
            $out->error("Write content to file error! File: $file", 1);
        }

        $out->success('OK, routes file generated');
    }
}

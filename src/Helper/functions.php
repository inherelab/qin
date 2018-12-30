<?php
/**
 * Created by PhpStorm.
 * User: Inhere
 * Date: 2018/3/7 0007
 * Time: 23:15
 */

if (!function_exists('config')) {
    /**
     * @param string $key
     * @param null|mixed $default
     * @return mixed
     */
    function config(string $key, $default = null)
    {
        /** @see \Toolkit\Collection\Configuration::get() */
        return Qin::$di['config']->get($key, $default);
    }
}

if (!function_exists('mco')) {
    /**
     * @param string $id
     * @return mixed
     */
    function mco(string $id)
    {
        return Qin::get($id);
    }
}

if (!function_exists('app')) {
    /**
     * @param string $id
     * @return mixed
     */
    function app(string $id = 'app')
    {
        return Qin::get($id);
    }
}

if (!function_exists('auth')) {
    /**
     * @return \ToolkitPlus\Auth\AuthManager
     */
    function auth()
    {
        return Qin::get('auth');
    }
}

if (!function_exists('flash')) {
    /**
     * @param string|null $key
     * @param string|null $message
     * @return \Toolkit\Web\Util\Flash
     */
    function flash(string $key = null, string $message = null)
    {
        if ($key && $message) {
            Qin::get('flash')->info($key, $message);
        }

        return Qin::get('flash');
    }
}

if (!function_exists('alias')) {
    /**
     * @param string $name
     * @return mixed|string
     */
    function alias(string $name)
    {
        return Qin::alias($name);
    }
}

if (!function_exists('path')) {
    /**
     * @param null|string $subPath
     * @return string
     */
    function path(string $subPath = null)
    {
        return $subPath ? (BASE_PATH . '/' . $subPath) : BASE_PATH;
    }
}

if (!function_exists('defer')) {
    /**
     * @param callable $cb
     */
    function defer(callable $cb)
    {
        /** @see \Qin\Component\DeferStack::add() */
        Qin::get('defer')->add($cb);
    }
}

/**
 * translate
 * @param $key
 * @param array $args
 * @param null $lang
 * @return mixed
 */
function trans($key, array $args = [], $lang = null)
{
    /** @see \Toolkit\Collection\Language::translate() */
    return \Qin::get('lang')->translate($key, $args, $lang);
}

if (!function_exists('trigger')) {
    /**
     * @param mixed $event
     * @param null $target
     * @param array $params
     * @return \Inhere\Event\EventInterface
     */
    function trigger($event, $target = null, array $params = [])
    {
        /** @see \Inhere\Event\EventManager::trigger() */
        return app('eventManager')->trigger($event, $target, $params);
    }
}

if (!function_exists('logger')) {
    /**
     * @return \Psr\Log\LoggerInterface
     */
    function logger(): \Psr\Log\LoggerInterface
    {
        return Qin::get('logger');
    }
}

if (!function_exists('cache')) {
    /**
     * @return \Psr\SimpleCache\CacheInterface
     */
    function cache(): \Psr\SimpleCache\CacheInterface
    {
        return Qin::get('cache');
    }
}

if (!function_exists('request')) {
    /**
     * @return \PhpComp\Http\Message\ServerRequest
     */
    function request(): \PhpComp\Http\Message\ServerRequest
    {
        return Qin::get('request');
    }
}

if (!function_exists('response')) {
    /**
     * @return \PhpComp\Http\Message\Response
     */
    function response(): \PhpComp\Http\Message\Response
    {
        return Qin::get('response');
    }
}

if (!function_exists('view')) {
    /**
     * @param string $template
     * @param array $data
     * @param string $layout
     * @return \Psr\Http\Message\ResponseInterface
     */
    function view(string $template, array $data = [], $layout = ''): \Psr\Http\Message\ResponseInterface
    {
        $layout = $layout ? alias($layout) : $layout;
        /** @see \Toolkit\Web\ViewRenderer::render() */
        $content = Qin::get('renderer')->render(alias($template), $data, $layout);

        $body = new \PhpComp\Http\Message\Body();
        $body->write($content);

        return Qin::get('response')->withBody($body);
    }
}

if (!function_exists('render')) {
    /**
     * @param string $template
     * @param array $data
     * @param null $layout
     * @return string
     */
    function render(string $template, array $data = [], $layout = null): string
    {
        /** @see \Toolkit\Web\ViewRenderer::render() */
        return Qin::get('renderer')->render($template, $data, $layout);
    }
}

if (!function_exists('collect')) {
    function collect(array $values)
    {
        return new \Toolkit\Collection\LiteCollection($values);
    }
}

if (!function_exists('msleep')) {
    function msleep($ms)
    {
        usleep($ms * 1000);
    }
}

if (!function_exists('retry')) {
    /**
     * Retry an operation a given number of times.
     * @param  int $times
     * @param  callable $callback
     * @param  int $sleep
     * @return mixed
     * @throws \Exception
     */
    function retry($times, callable $callback, $sleep = 0)
    {
        $times--;

        beginning:
        try {
            return $callback();
        } catch (Exception $e) {
            if (!$times) {
                throw $e;
            }

            $times--;

            if ($sleep) {
                usleep($sleep * 1000);
            }

            goto beginning;
        }
    }
}

if (!function_exists('create_salt')) {
    function create_salt()
    {
        return \Toolkit\StrUtil\Str::genSalt();
    }
}

if (!function_exists('value')) {
    /**
     * @param $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}


if (!function_exists('with')) {
    /**
     * Return the given value. Useful for chaining.
     *   with(new Class)->xxx
     * @param  mixed $value
     * @return mixed
     */
    function with($value)
    {
        return $value;
    }
}

if (!function_exists('tap')) {
    function tap($value, callable $callback)
    {

        $callback($value);

        return $value;
    }
}

if (!function_exists('cookie')) {
    /**
     * cookie get
     * @param  string|array $name
     * @param  mixed $default
     * @return mixed
     */
    function cookie($name, $default = null)
    {
        // get
        if ($name && is_string($name)) {
            return $_COOKIE[$name] ?? $default;
        }

        return $default;
    }
}

if (!\function_exists('fn_match')) {
    function fn_match(string $pattern, string $string)
    {
        $pattern = \strtr(\preg_quote($pattern, '#'), ['\*' => '.*', '\?' => '.', '\[' => '[', '\]' => ']']);

        return \preg_match(
            '#^' . $pattern . '$#i',
            $string
        );
    }
}

if (!function_exists('session')) {
    /**
     * session get or set
     * @param  string|array $name
     * @param  mixed $default
     * @return mixed
     */
    function session($name, $default = null)
    {
        if (null === $_SESSION) {
            throw new \RuntimeException('session set or get failed. Session don\'t start.');
        }

        // set, when $name is array
        if ($name && \is_array($name)) {
            foreach ((array)$name as $key => $value) {
                if (is_string($key)) {
                    $_SESSION[$key] = $value;
                }
            }

            return $name;
        }

        // get
        if ($name && is_string($name)) {
            return $_SESSION[$name] ?? $default;
        }

        return $default;
    }
}

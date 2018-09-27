<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/9/27
 * Time: 下午10:24
 */

use Psr\Http\Message\ResponseInterface;
use Qin\Http\Context;

$rootDir = dirname(__DIR__);
// $app = Qin::newApp($rootDir);
$app = new \Qin\Http\App($rootDir);

// $qin = Qin::newServer($rootDir);
$app->loadConfig();

class DemoMiddleware implements \Qin\Http\MiddlewareInterface
{
    /**
     * @param Context $ctx
     * @return ResponseInterface
     */
    public function process(Context $ctx, \Closure $next): ResponseInterface
    {
        // before
        return $next($ctx);
        // after
    }
}

$middleware1 = new class implements \Qin\Http\MiddlewareInterface {
    /**
     * @param Context $ctx
     * @param \Closure $next
     * @return ResponseInterface
     */
    public function process(Context $ctx, \Closure $next): ResponseInterface
    {
        // before
        return $next($ctx);
        // after
    }

    public function __invoke(Context $ctx, \Closure $next): ResponseInterface
    {
        return $this->process($ctx, $next);
    }
};

$app->use($middleware1, DemoMiddleware::class);

\Qin\Server::runHTTPServe($app, ':8090');

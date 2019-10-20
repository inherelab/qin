<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/11/28
 * Time: 下午11:21
 */

namespace Mco\Http\Controllers;

use Qin\Http\HttpController;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ApiDocController
 * @package Mco\Http\Controllers
 */
class ApiDocController extends HttpController
{
    /** @var string  */
    public $docUrl = '/docs/swagger-%s.json';

    /**
     * display api docs by swagger-ui
     * @param Context $ctx
     * @return string
     * @throws \Throwable
     */
    public function indexAction(Context $ctx): string
    {
        $env = $ctx->req->getQueryParam('env', APP_ENV);
        $refresh = (bool)$ctx->req->getQueryParam('refresh', 0);
        $docUrl = sprintf($this->docUrl, $env);
        $docFile = path('web' . $docUrl);

        if ($refresh || !is_file($docFile)) {
            file_put_contents($docFile, $this->scanAndGenerate($env));
        }

        return $this->renderPartial(\Mco::alias('@mco/Resources/swagger-ui.phtml'), [
            'host' => container('config')->application['host'],
            'assetPath' => '/swagger-ui',
            'jsonFile' => $docUrl,
        ]);
    }

    /**
     * gen swagger api json
     * @param Context $ctx
     * @return ResponseInterface
     * @throws \RuntimeException
     */
    public function genAction(Context $ctx): ResponseInterface
    {
        $res = $ctx->res;
        $echo = (bool)$ctx->req->getQueryParam('echo', 0);
        $env = $ctx->req->getQueryParam('env', APP_ENV);
        $sTime = date('Y-m-d H:i:s');
        $swagger = $this->scanAndGenerate($env);
        $eTime = date('Y-m-d H:i:s');
        // Setting a header
        $res->setHeader('Content-Type', 'application/json');

        if ($echo) {
            $res->write($swagger);
        } else {
            $docUrl = sprintf($this->docUrl, $env);
            $docFile = path('web' . $docUrl);
            $writeLen = file_put_contents($docFile, $swagger);

            $res->write(json_encode([
                'start' => $sTime,
                'done' => $eTime,
                'writeLen' => $writeLen,
            ]));
        }

        return $res;
    }

    /**
     * @param string $env
     * @return \Swagger\Annotations\Swagger
     */
    private function scanAndGenerate($env): \Swagger\Annotations\Swagger
    {
        $dirs = [
            path('res/swagger/env/' . $env . '.php'),
            path('res/swagger/api-v1'),
            path('app/Http'),
        ];

        return \Swagger\scan($dirs);
    }
}

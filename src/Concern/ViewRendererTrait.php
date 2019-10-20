<?php
/**
 * Created by PhpStorm.
 * User: Inhere
 * Date: 2018/5/31 0031
 * Time: 00:24
 */

namespace Qin\Concern;

use PhpComp\Http\Message\Body;
use Psr\Http\Message\ResponseInterface;
use Toolkit\Web\ViewRenderer;

/**
 * Trait ViewRendererTrait
 * @package Mco\Http
 */
trait ViewRendererTrait
{
    /**
     * @var string
     */
    public $layout = '';

    /**
     * @return ViewRenderer
     * @throws \InvalidArgumentException
     */
    public function getRenderer(): ViewRenderer
    {
        return \Mco::$di->get('renderer');
    }

    /**
     * @param string $view
     * @return string
     */
    protected function resolveView(string $view): string
    {
        return \alias($view);
    }

    /*********************************************************************************
     * view method
     *********************************************************************************/

    /**
     * @param string $view
     * @param array $data
     * @param null|string|false $layout
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function render(string $view, array $data = [], $layout = null): ResponseInterface
    {
        if ($this->isPjax()) {
            // X-PJAX-URL: https://github.com/inhere/library
            // X-PJAX-Version: 23434
            $response = \response()
                ->withHeader('X-PJAX-URL', (string)$this->ctx->req->getUri())
                ->withHeader('X-PJAX-Version', '1.0');

            $layout = false;
        } else {
            $response = \response();
        }

        if (!$layout && $layout !== false) {
            $layout = $this->layout;
        }

        $html = $this->getRenderer()->render($this->resolveView($view), $data, $layout);

        $body = new Body();
        $body->write($html);

        return $response->withBody($body);
    }

    /**
     * @param string $view
     * @param array $data
     * @return string
     * @throws \InvalidArgumentException
     */
    public function renderPartial($view, array $data = []): string
    {
        return $this->getRenderer()->fetch($this->resolveView($view), $data);
    }

    /**
     * @param string $string
     * @param array $data
     * @param null|string $layout
     * @return string
     * @throws \InvalidArgumentException
     */
    public function renderContent($string, array $data = [], $layout = null): string
    {
        return $this->getRenderer()->renderContent($string, $data, $layout);
    }
}

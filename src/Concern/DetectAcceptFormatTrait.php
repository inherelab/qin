<?php
/**
 * Created by PhpStorm.
 * User: Inhere
 * Date: 2018/5/8 0008
 * Time: 20:04
 */

namespace Qin\Concern;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Trait DetectAcceptFormatTrait
 * @package Mco\Http
 */
trait DetectAcceptFormatTrait
{
    /**
     * @var array[] Available formats with MIME types
     */
    private static $formats = [
        'html' => ['text/html', 'application/xhtml+xml'],
        'json' => ['application/json', 'text/json', 'application/x-json'],
        'xml' => ['text/xml', 'application/xml', 'application/x-xml'],
        'txt' => ['text/plain']
    ];

    /**
     * Returns the preferred format based on the Accept header
     *
     * @param ServerRequestInterface $request
     * @return string
     */
    public static function getPreferredFormat(ServerRequestInterface $request): string
    {
        $acceptTypes = $request->getHeader('accept');

        if (\count($acceptTypes) > 0) {
            $acceptType = $acceptTypes[0];
            // As many formats may match for a given Accept header, let's try to find the one that fits the best
            $counters = [];

            foreach (self::$formats as $format => $values) {
                foreach ($values as $value) {
                    $counters[$format] = $counters[$format] ?? 0;

                    if (\strpos($acceptType, $value) !== false) {
                        $counters[$format] ++;
                    }
                }
            }

            // Sort the array to retrieve the format that best matches the Accept header
            asort($counters);
            end($counters);

            return key($counters);
        }

        return 'html';
    }
}

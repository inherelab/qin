<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/11/28
 * Time: 下午11:18
 */

namespace Qin\Log;

use Toolkit\PhpUtil\PhpHelper;

/**
 * Class JsonFormatter
 * @package Qin\Log
 */
class JsonFormatter
{
    /**
     * Applies a format to a message before sent it to the internal log
     * @param string $message
     * @param int $type
     * @param int $timestamp
     * @param array|null $context
     * @return string|array
     */
    public function format($message, $type, $timestamp, $context = null)
    {
        $context = $context ?: [];

        return json_encode([
                'time' => date('Y-m-d H:i:s', $timestamp),
                'type' => $this->getTypeString($type),
                'category' => $this->removeKey($context, 'category','application'),
                'message' => $message,
                'log_id' => PhpHelper::serverParam('LOG_ID'),
                'uri'    => PhpHelper::serverParam('REQUEST_URI', 'Unknown'),
                'method' => PhpHelper::serverParam('REQUEST_METHOD', 'Unknown'),
                'hostname' => HOSTNAME,
                'server_ip' => PhpHelper::serverParam('SERVER_ADDR'),
                'timestamp' => $timestamp,
                'context' => $context,
            ]) . PHP_EOL;
    }

    private function removeKey(array &$context, $key, $default = null)
    {
        $value = $default;
        if (isset($context[$key])) {
            $value = $context[$key];
            unset($context[$key]);
        }
        return $value;
    }
}

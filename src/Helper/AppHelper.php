<?php
/**
 * Created by PhpStorm.
 * User: Inhere
 * Date: 2018/5/1 0001
 * Time: 16:34
 */

namespace Qin\Helper;

use Toolkit\Helper\FormatHelper;

/**
 * Class AppHelper
 * @package Qin\Helper
 */
class AppHelper
{
    /**
     * 根据服务器设置得到文件上传大小的最大值
     * @param int $max_size optional max file size
     * @return int max file size in bytes
     */
    public static function getMaxUploadSize($max_size = 0): int
    {
        $post_max_size = FormatHelper::convertBytes(\ini_get('post_max_size'));
        $upload_max_fileSize = FormatHelper::convertBytes(\ini_get('upload_max_filesize'));

        if ($max_size > 0) {
            $result = min($post_max_size, $upload_max_fileSize, $max_size);
        } else {
            $result = min($post_max_size, $upload_max_fileSize);
        }

        return $result;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-19
 * Time: 18:06
 */

namespace Qin\Base;


use PhpComp\LiteDb\LitePdo;

/**
 * Class DbModel
 * @package Qin\Base
 */
abstract class RecordModel extends \PhpComp\LiteActiveRecord\RecordModel
{
    const PREFIX = '{@pfx}';

    public static function getDb(): LitePdo
    {
        return \mco('db');
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Inhere
 * Date: 2018/5/28 0028
 * Time: 00:45
 */

namespace Qin\Base;

/**
 * Class DatabaseLogic
 * @package Qin\Base
 */
class DatabaseLogic
{
    /**
     * @var array  logic map
     */
    private static $logicMap = [];

    /**
     * @var string
     */
    protected static $modelClass = '';

    /**
     * @return static
     */
    public static function new()
    {
        $id = static::class;

        if (!isset(self::$logicMap[$id])) {
            self::$logicMap[$id] = new static();
        }

        return self::$logicMap[$id];
    }

    /**
     * @return RecordModel
     * @throws \InvalidArgumentException
     */
    public static function newModel(): RecordModel
    {
        /** @var RecordModel $class */
        if (!$class = self::$modelClass) {
            throw new \InvalidArgumentException('Please setting the property: modelClass');
        }

        return $class::make();
    }

    /**
     * @return string
     */
    public static function getModelClass(): string
    {
        return self::$modelClass;
    }

    /**
     * @param string $modelClass
     */
    public static function setModelClass(string $modelClass)
    {
        self::$modelClass = $modelClass;
    }

    /**
     * @param array $params
     */
    protected function beforeQueryPageList(array &$params)
    {
        //
    }

    /**
     * @param int $page
     * @param int $size
     * @param array $params
     * [
     *  page => 1,
     *  size => 10,
     * ]
     * @param string $select
     * @param array $options
     * [
     *   useCache => bool,
     *   class => 'model class',
     *   count => bool, // count total
     * ]
     * @return array[]
     * @throws \InvalidArgumentException
     */
    public function getPageList(int $page, int $size, array $params, string $select = '*', array $options = []): array
    {
        $this->beforeQueryPageList($params);

        $limit = $size < 1 ? 1 : $size;
        $start= $page < 1 ? 0 : ($page - 1) * $limit;
        $wheres = $this->buildWheres($params);

        // 多查询一条
        $options['limit'] = [$start, $limit + 1];

        /** @var RecordModel $class */
        $class = $options['class'] ?? static::$modelClass;
        $records = $class::findAll($wheres, $select, $options);

        $founded = \count($records);
        $metadata = [
            'page' => $page,
            'size' => $size,
            'more' => $founded > $limit,
            'find' => $founded,
        ];

        // count total
        if ($options['count'] ?? null) {
            $metadata['total'] = $class::counts($wheres);
        }

        if ($metadata['more']) {
            \array_pop($records);
            $metadata['find'] = $founded - 1;
        }

        return [
            'meta' => $metadata,
            'records' => $records,
        ];
    }

    /**
     * build Wheres
     * @param array $params
     * @return array
     */
    protected function buildWheres(array $params): array
    {
        return [];
    }

    /**
     * @param string $fields
     * @param array $data
     */
    protected function filterFields(string $fields, array &$data)
    {
        // filter fields
        if ($fields !== '*' && ($fields = \trim($fields, ', '))) {
            $list = \array_map('trim', \explode(',', $fields));
            $data = \array_filter($data, function ($field) use ($list) {
                return \in_array($field, $list, true);
            }, \ARRAY_FILTER_USE_KEY);
        }
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/9/27
 * Time: 下午11:00
 */

namespace Qin\Component;

/**
 * Class ObjectPool
 * @package Qin\Component
 */
class ObjectPool
{
    /**
     * @var \SplQueue
     */
    private $queue;

    /**
     * @var \Closure
     */
    private $creator;

    /**
     * ObjectPool constructor.
     */
    public function __construct()
    {
        $this->queue = new \SplQueue();
    }

    /**
     * @param \Closure $creator
     * @return ObjectPool
     */
    public function setCreator(\Closure $creator): ObjectPool
    {
        $this->creator = $creator;
        return $this;
    }

    /**
     * get object from pool
     * @return mixed
     */
    public function get()
    {
        if (!$this->creator) {
            throw new \RuntimeException('must be setting the property creator by setCreator().');
        }

        if (!$this->queue->isEmpty()) {
            return $this->queue->dequeue();
        }

        // create new
        $obj = ($this->creator)();
        $this->queue->enqueue($obj);

        return $obj;
    }

    /**
     * release object to the pool.
     * @param $obj
     */
    public function put($obj)
    {
        $this->queue->enqueue($obj);
    }
}

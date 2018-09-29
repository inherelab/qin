<?php
/**
 * Created by PhpStorm.
 * User: Inhere
 * Date: 2018/6/16 0016
 * Time: 13:20
 */

namespace Qin\Component;

/**
 * Class DeferStack
 * @package Qin\Component
 */
class DeferStack
{
    /**
     * @var \SplStack
     */
    private $stack;

    /**
     * DeferStack constructor.
     * @param callable|null $kernel
     * @throws \RuntimeException
     */
    public function __construct(callable $kernel = null)
    {
        $this->prepareStack($kernel);
    }

    /**
     * @param array ...$handlers
     * @return DeferStack
     */
    public function add(...$handlers): self
    {
        foreach ($handlers as $handler) {
            $this->stack[] = $handler;
        }

        return $this;
    }

    /**
     * run
     */
    public function run()
    {
        $that = clone $this;
        $that->callStack();
    }

    /**
     * call handlers
     */
    public function callStack()
    {
        while (!$this->stack->isEmpty()) {
            $handler = $this->stack->shift();
            $handler();
        }
    }

    /**
     * @param callable|null $kernel
     * @throws \RuntimeException
     */
    protected function prepareStack(callable $kernel = null)
    {
        if (null !== $this->stack) {
            throw new \RuntimeException('Defer stack can only be seeded once.');
        }

        $this->stack = new \SplStack;
        $this->stack->setIteratorMode(\SplDoublyLinkedList::IT_MODE_LIFO | \SplDoublyLinkedList::IT_MODE_KEEP);

        if ($kernel) {
            $this->stack[] = $kernel;
        }
    }

    /**
     * @return \SplStack
     */
    public function getStack(): \SplStack
    {
        return $this->stack;
    }

}

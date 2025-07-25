<?php

namespace Programster\Queues;


abstract class AbstractCompletableTaskQueue implements CompletableTaskQueueInterface,CompletableInterface
{
    protected array $m_runnables;

    protected ?RunnableInterface $m_callback; # callback to execute when depleted. Can be null.


    /**
     * Construct a queue for executing some runnable items.
     * @param ?RunnableInterface $callback - a runnable task to run when the queue completes..
     */
    public function __construct(?RunnableInterface $callback = null)
    {
        $this->m_callback = $callback;
    }


    /**
     * Add a runnable element to the queue
     * @param CompletableInterface&RunnableInterface $item
     * @return void
     */
    public function add(RunnableInterface & CompletableInterface $item) : void
    {
        $this->m_runnables[] = $item;
    }
    
    
    /**
     * Fetch the number of query objects we have left to process.
     * @return int - the number of items in the queue
     */
    public function count() : int
    {
        return count($this->m_runnables);
    }


    public function isCompleted(): bool
    {
        return ($this->count() === 0);
    }
}
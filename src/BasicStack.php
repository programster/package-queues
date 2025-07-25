<?php

/*
 * A very basic queue that simply takes a bunch of runnable objects and executes them when this queue is told to run,
 * running them in the order they were added.
 */

namespace Programster\Queues;


class BasicStack implements RunnableInterface
{
    protected array $m_runnables;


    /**
     * Create a queue of things to run.
     * @param RunnableInterface ...$items
     */
    public function __construct(RunnableInterface ...$items)
    {
        $this->m_runnables = $items;
    }


    /**
     * Add a runnable element to the queue.
     * If adding this item puts the queue over the threshold, then this will self-invoke.
     * @param CompletableInterface&RunnableInterface $runnable
     */
    public function add(RunnableInterface $runnable) : void
    {
        $this->m_runnables[] = $runnable;
    }
    
    
    public function count() : int { return count($this->m_runnables); }

    public function run() : void
    {
        while (count($this->m_runnables) > 0)
        {
            /* @var $runnable RunnableInterface */
            $runnable = array_pop($this->m_runnables);
            $runnable->run();
        }
    }
}
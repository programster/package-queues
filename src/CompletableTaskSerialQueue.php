<?php

/*
 * A FIFO queue of runnable items to execute serially, only running the next task once the task before it has
 * managed to complete.
 */

namespace Programster\Queues;

class CompletableTaskSerialQueue extends AbstractCompletableTaskQueue
{
    public function run() : void
    {
        if ($this->count() > 0)
        {
            /* @var $firstRunnable RunnableInterface & CompletableInterface */
            $firstRunnable = array_values($this->m_runnables)[0];
            $firstRunnable->run();
            
            if ($firstRunnable->isCompleted())
            {
                array_shift($this->m_runnables);
                
                if (count($this->m_runnables) === 0 && $this->m_callback !== null)
                {
                    /* @var $callback \Programster\Queues\RunnableInterface */
                    $this->m_callback->run();
                }
            }
        }
    }
}
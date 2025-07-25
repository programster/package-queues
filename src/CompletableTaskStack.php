<?php

/*
 * A stack of RunnableInterface objects. This will process items that were last added to the 
 * collection first. E.g. the reverse order of a FIFO queue.
 */

namespace Programster\Queues;


class CompletableTaskStack extends AbstractCompletableTaskQueue
{
    /**
     * Call this method to check if the asynchronous queries have returned results, and handle
     * them if they have. If connections free up, and there are pending queries, this will
     * send them off to the database.
     * @return void - true if everything has been completed, false otherwise.
     */
    public function run() : void
    {
        if ($this->count() > 0)
        {
            $runnable = array_pop($this->m_runnables);
            
            /* @var $runnable RunnableInterface & CompletableInterface */
            $runnable->run();
            
            if ($runnable->isCompleted())
            {
                if ($this->count() === 0 && $this->m_callback != null)
                {
                    if ($this->m_callback !== null)
                    {
                        $this->m_callback->run();
                    }
                }
            }
            else
            {
                array_push($this->m_runnables, $runnable);
            }
        }
    }
}

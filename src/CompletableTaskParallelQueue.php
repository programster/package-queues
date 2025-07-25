<?php

/*
 * Create a queue that calls the run method on all runnable items in order every time it is invoked, not waiting
 * for any tasks to have marked themselves as completed before calling the next task.
 */

namespace Programster\Queues;

class CompletableTaskParallelQueue extends AbstractCompletableTaskQueue
{
    public function run() : void
    {
        if (count($this->m_runnables) > 0)
        {
            foreach ($this->m_runnables as $index => $runnable)
            {
                /* @var $runnable RunnableInterface & CompletableInterface */
                $runnable->run();
                
                if ($runnable->isCompleted())
                {
                    unset($this->m_runnables[$index]);
                }
            }
        }
        
        # Return whether we are "handled" (empty) or not.
        if ($this->isCompleted() && $this->m_callback !== null)
        {
            $this->m_callback->run();
        }
    }
}
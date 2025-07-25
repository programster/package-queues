<?php

/*
 * An interface for a queue that runnable jobs/tasks that are completable, so the queue needs to
 * not remove them until the task has completed.
 */

namespace Programster\Queues;

use Programster\Runnable\Runnable;

interface CompletableTaskQueueInterface extends RunnableInterface
{
    /**
     * Add a runnable element to the queue
     * @param RunnableInterface & CompletableInterface $item
     * @return void
     */
    public function add(RunnableInterface & CompletableInterface $item) : void;
    
    
    /**
     * Fetch the number of query objects we have left to process.
     * @return int - the number of outstanding queries.
     */
    public function count() : int;
}

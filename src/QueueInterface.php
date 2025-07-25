<?php

namespace Programster\Queues;

interface QueueInterface extends \iRAP\Interfaces\RunnableInterface
{
    /**
     * Add a runnable element to the queue
     * @param \iRAP\Queues\RunnableInterface $item
     */
    public function add(\iRAP\Interfaces\RunnableInterface $item);
    
    
    /**
     * Fetch the number of query objects we have left to process.
     * @return int - the number of outstanding queries.
     */
    public function count();
}
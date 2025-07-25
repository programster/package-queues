<?php

namespace Programster\Queues;

interface CompletableInterface
{
    /**
     * Return whether this item has completed its job/task.
     * @return bool
     */
    public function isCompleted() : bool;
}
<?php

/*
 * A bucket queue will fill up until reaches a certain point. When you reach this point
 * the queue will self-initiate and keep running until it contains fewer elements than the
 * threshold. This is just a decorator around another the queue interface, so you can
 * add this "bucket" behaviour to any of the existing QueueInterface objects.
 */

namespace Programster\Queues;

class CompletableTaskBucketQueue implements CompletableTaskQueueInterface,CompletableInterface
{
    private bool $m_isRunning;
    private int $m_startThreshold;
    private int $m_stopThreshold;
    private int $m_sleepTime;
    private CompletableTaskQueueInterface $m_underlyingQueue; # the queue that this object elaborates.


    /**
     * Construct a bucket queue object to manage runnable elements.
     * @param CompletableTaskQueueInterface $queue
     * @param int $startThreshold - max number of elements to take before self-invokation.
     * @param int $stopThreshold - the threshold at which to stop running the queue after having been self-invoked.
     * @param int $sleepTime - the amount of time in microseconds to sleep between re-running the queue of tasks if
     * the queue has not met the required stopThreshold
     */
    public function __construct(CompletableTaskQueueInterface $queue, int $startThreshold, int $stopThreshold, int $sleepTime=0)
    {
        $this->m_startThreshold = $startThreshold;
        $this->m_sleepTime = $sleepTime;
        $this->m_underlyingQueue = $queue;
        $this->m_stopThreshold = $stopThreshold;
        $this->m_isRunning = false;
    }


    /**
     * Add a runnable element to the queue.
     * If adding this item puts the queue over the threshold, then this will self-invoke.
     * @param CompletableInterface&RunnableInterface $item
     */
    public function add(RunnableInterface & CompletableInterface $item) : void
    {
        $this->m_underlyingQueue->add($item);
        
        if ($this->count() > $this->m_startThreshold && !$this->m_isRunning)
        {
            while ($this->count() > $this->m_startThreshold)
            {
                $this->m_isRunning = true;
                $this->run();
                
                if ($this->count() > $this->m_stopThreshold)
                {
                    if ($this->m_sleepTime > 0)
                    {
                        usleep($this->m_sleepTime);
                    }
                }
                else
                {
                    break;
                }
            }
            
            $this->m_isRunning = false;
        }
    }
    
    
    public function count() : int { return $this->m_underlyingQueue->count(); }
    public function run() : void { $this->m_underlyingQueue->run(); }
    public function isCompleted(): bool { return $this->m_underlyingQueue->isCompleted(); }
}
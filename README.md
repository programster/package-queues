PHP Queues Package
==================

This package was created to make it simple to create queues of tasks, such as for sending 
[asynchronus MySQL queries](https://github.com/iRAP-software/package-async-query) or 
[CURL requests](https://github.com/programster/curl-easier).

## Requirements

* This package was built/tested using PHP 5.6. It may or may not work on earlier versions, but 
  please be aware that
  [any PHP version lower than 5.6 is deprecated](https://secure.php.net/supported-versions.php).


## Queue Types

### BasicQueue
A very basic queue that takes runnable items, and calls run() on them when the queu itself is run().

### CompletableTaskSerialQueue
Run items one after each other (FIFO), waiting for each one to complete before moving onto the next.

### CompletableTaskParallelQueue
Run the items in parallel. These items could be executed/completed *in any order*. This is 
where performance benefits are realized.

### CompletableTaskStack
Execute items in the same manner as `SerialRunnableQueue` except that instead of being FIFO, 
items are executed in reverse order with the last item being added being executed first.

### CompletableTaskStack
Execute items in the same manner as `SerialRunnableQueue` except that instead of being FIFO,
items are executed in reverse order with the last item being added being executed first.

## Interfaces

In order to use the queus, the objects you put into them need to implement the  `RunnableInterface` 
which simply means the objects have a `run` method that returns true when the task has completed.
This method will keep being called by the queue until it returns true. This may seem silly but 
is necessary for asynchronous requests which may take some time to complete, and thus you need 
to keep track of the state in the object and return true once you have the result back.

### Queues Within Queues
Due to the fact that almost all queues implement the `RunnableInterface` and `CompletableInterface`, 
interface, you can create queues of other queues to create any combinations you need. For 
example, you may have several tasks that have to be executed in a certain order, however each 
of these tasks might  consist of several hundred sub-tasks that can be executed in *any* 
order. In such a case, you  would want to create a `CompletableTaskParallelQueue` to place 
each group of subtasks into. Then you  would place each of these parallel queues into a single 
`CompletableTaskSerialQueue` object to make sure the groups get executed in the correct order.

Every queue can take an optional runnable callback in its constructor. This callback is executed 
whenever the queue is completed/emptied, allowing the developer to run logic immediately when
that queue is finished (even when it is a queue within a queue).

Queues are not a fixed size, you can use the `add` method to add to them whenever you want, 
including whilst they are running. However, remember that the queue's callback will be invoked 
every time the queue is depleted.


### Example Usage:
```php

$asyncQuery1 = new \iRAP\AsyncQuery\AsyncQuery(
    $sql1,
    $queryCallback1,
    $connectionPool
);

$asyncQuery2 = new \iRAP\AsyncQuery\AsyncQuery(
    $sql2,
    $queryCallback2,
    $connectionPool2 # <-- different pool, perhaps for a different database?
);

$parallelRunnableQueue = new \iRAP\Queues\ParallelRunnableQueue($queueCallback);

$parallelRunnableQueue->add($asyncQuery1);
$parallelRunnableQueue->add($asyncQuery2);

# Run until the queue has completed all of the tasks.
while ($parallelRunnableQueue->run() !== TRUE)
{
    usleep(1);
}
```


## Automated Tests
Simply go to the `testing` directory and execute the `main.php` script. All code contributions 
that add new functionality should provide a relevant test case. It may be a good idea to read 
through the automated tests to get example usages.

<?php

namespace Programster\Queues;

interface RunnableInterface
{
    public function run() : void;
}
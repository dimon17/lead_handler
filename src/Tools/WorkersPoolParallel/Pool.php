<?php

namespace Tools\WorkersPoolParallel;

class Pool
{
    protected int $workerIndex;
    protected int $size;
    protected array $workers;

    public function __construct($size, $bootstrap = null)
    {
        $this->size = $size;
        $this->workerIndex = 0;
        $this->workers = [];
        for ($i = 0; $i < $size; $i++) {
            $this->workers[] = new Worker($bootstrap);
        }
    }

    public function runAll(callable $task, $arguments = [])
    {
        foreach ($this->workers as $worker) {
            $worker->run($task, $arguments);
        }
    }

    public function run(callable $task, $arguments = [])
    {
        // give task for free worker
        foreach ($this->workers as $worker) {
            if (!$worker->isBusy()) {
                return $worker->run($task, $arguments);
            }
        }

        // if there is no free - give by sequence
        $i = $this->workerIndex++ % $this->size;
        return $this->workers[$i]->run($task, $arguments);
    }

    public function stop(): void
    {
        foreach ($this->workers as $worker) {
            $worker->stop();
        }
        unset($this->workers);
    }

    public function kill(): void
    {
        foreach ($this->workers as $worker) {
            $worker->kill();
        }
        unset($this->workers);
    }

    public function waitAllTasksComplete(): void
    {
        foreach ($this->workers as $worker) {
            $worker->future->value();
        }
    }
}

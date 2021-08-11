<?php

namespace Tools\WorkersPoolParallel;

use parallel\Future;
use parallel\Runtime;

class Worker
{
    protected $id;
    protected Runtime $runtime;
    public Future $future;

    public function __construct($bootstrap = null)
    {
        $this->id = md5(uniqid());
        if (is_null($bootstrap)) {
            $this->runtime = new Runtime();
        } else {
            $this->runtime = new Runtime($bootstrap);
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function run(callable $closure, array $arguments)
    {
          // Внедрение кода в задачу перед выполнением
//        $task = function (...$arg) use ($closure) {
//            $res = $closure(...$arg);
//            return $res;
//        };

        return $this->future = $this->runtime->run($closure, array_merge(
            [$this->id], $arguments
        ));
    }

    public function isBusy(): bool
    {
        return isset($this->future) ? !$this->future->done() : FALSE;
    }

    public function stop(): void
    {
        $this->runtime->close();
    }

    public function kill(): void
    {
        $this->runtime->kill();
    }
}

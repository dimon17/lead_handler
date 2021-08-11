<?php

namespace Tools\WorkersPoolParallel\WorkerBootstrap;

use Tools\WorkersPoolParallel\IWorkerBootstrap;

class Common implements IWorkerBootstrap
{
    public static function getBootstrapFilename(): string
    {
        return __DIR__ . "/scripts/common.php";
    }
}
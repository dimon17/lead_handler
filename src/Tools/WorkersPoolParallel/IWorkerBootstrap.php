<?php

namespace Tools\WorkersPoolParallel;

interface IWorkerBootstrap
{
    public static function getBootstrapFilename(): string;
}
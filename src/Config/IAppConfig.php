<?php

namespace Config;

use Tools\WorkersPoolParallel\IWorkerBootstrap;

interface IAppConfig extends IConfig
{
    public static function getPoolWorkersCount(): int;
    public static function getPoolWorkersBootstrap(): IWorkerBootstrap;
    public static function getLeadsCount(): int;
    public static function getLeadsAllowedCategories(): array;
}
<?php

namespace Config\App;

use Config\IAppConfig;
use Tools\WorkersPoolParallel\IWorkerBootstrap;
use Tools\WorkersPoolParallel\WorkerBootstrap\Common;

class Dev implements IAppConfig
{
    private static int $poolWorkersCount = 100;
    private static IWorkerBootstrap $workersBootstrap;
    private static int $leadsCount = 1000;
    private static array $leadsAllowedCategories = [];

    public static function getPoolWorkersCount(): int
    {
        return self::$poolWorkersCount;
    }

    public static function getPoolWorkersBootstrap(): IWorkerBootstrap
    {
        if (!isset(self::$workersBootstrap)) self::$workersBootstrap = new Common();
        return self::$workersBootstrap;
    }

    public static function getLeadsCount(): int
    {
        return self::$leadsCount;
    }

    public static function getLeadsAllowedCategories(): array
    {
        # leads allowed categories may be changed while runtime
        # (i know, that using DB will be more suitable for such things in future, but for simple example this is enough)
        self::$leadsAllowedCategories = require DOC_ROOT . 'src/Config/App/scripts/leadsAllowedCategoriesDev.php';
        return self::$leadsAllowedCategories;
    }

    public static function getAllConfigsAsArray(): array
    {
        return [
            'pool_workers_count' => self::getPoolWorkersCount(),
            'workers_bootstrap' => self::getPoolWorkersBootstrap(),
            'leads_count' => self::getLeadsCount(),
            'leads_allowed_categories' => self::getLeadsAllowedCategories()
        ];
    }
}
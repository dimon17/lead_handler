<?php

namespace Config\App;

use Config\IAppConfig;
use Tools\WorkersPoolParallel\IWorkerBootstrap;
use Tools\WorkersPoolParallel\WorkerBootstrap\Common;

class Prod implements IAppConfig
{
    private static int $poolWorkersCount = 400;
    private static IWorkerBootstrap $workersBootstrap;
    private static int $leadsCount = 10000;
    private static array $leadsAllowedCategories = [
        'Buy auto',
        'Buy house',
        'Get loan',
        'Cleaning',
        'Learning',
        'Car wash',
        'Repair smth',
        'Barbershop',
        'Pizza',
        'Car insurance',
        'Life insurance'
    ];

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
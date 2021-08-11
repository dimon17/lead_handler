<?php

namespace Application;

use Config\IAppConfig;
use LeadGenerator\{Generator, Lead};
use Tools\WorkersPoolParallel\Pool;

class App
{
    private static Generator $leadGenerator;
    private static Pool $workersPool;
    private static int $leadsCount;
    private static IAppConfig $config;

    public static function initialize(IAppConfig $config)
    {
        self::$config = $config;
        self::$leadGenerator = new Generator();
        self::$leadsCount = $config::getLeadsCount();
        self::$workersPool = new Pool(
            $config::getPoolWorkersCount(),
            $config::getPoolWorkersBootstrap()::getBootstrapFilename()
        );
    }

    public static function run()
    {
        self::$leadGenerator->generateLeads(self::$leadsCount, function (Lead $lead) {
            $allowedLeadsCategories = self::$config::getLeadsAllowedCategories();
            if (in_array($lead->categoryName, $allowedLeadsCategories)) {
                $task = function ($workerId, Lead $lead) {

                    sleep(2);
                    file_put_contents(
                        "log.txt",
                        "{$lead->id} | {$lead->categoryName} | " . date('Y-m-d H:i:s') . PHP_EOL,
                        FILE_APPEND
                    );
                };
                return self::$workersPool->run($task, [$lead]);
            }
            return FALSE;
        });

        self::$workersPool->waitAllTasksComplete();
    }

    public static function reinitialize(IAppConfig $config)
    {
        self::$workersPool->stop(); # дождёмся, когда воркеры выполнят оставшиеся задачи и остановим пул
        self::initialize($config);
    }
}
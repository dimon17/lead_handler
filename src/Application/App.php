<?php

namespace Application;

use Config\IAppConfig;
use LeadGenerator\{Generator, Lead};
use Throwable;
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

            $task = function ($workerId, Lead $lead, $getArrayOfLeadsAllowedCategoriesMethod) {
                try {
                    $leadsAllowedCategories = call_user_func($getArrayOfLeadsAllowedCategoriesMethod);
                } catch (Throwable $e) {
                    $msg = date('Y-m-d H:i:s ') . "ERROR: worker (id: {$workerId}) can not get leads allowed"
                        . " categories to handle lead (id: {$lead->id}): {$e->getMessage()}. Skipping the lead handling.";
                    echo $msg . PHP_EOL; // print message to console and return the same with 'code' and 'success' keys
                    return [
                        'success' => FALSE,
                        'code' => 1,
                        'message' => $msg
                    ];
                }

                if (in_array($lead->categoryName, $leadsAllowedCategories)) {
                    sleep(2); // simulating hard working (processing lead)..
                    if (file_put_contents(
                        "log.txt",
                        "{$lead->id} | {$lead->categoryName} | " . date('Y-m-d H:i:s') . PHP_EOL,
                        FILE_APPEND
                    )) {
                        return ['success' => TRUE];
                    } else {
                        // error writing to file
                        $msg = date('Y-m-d H:i:s ') . "ERROR: worker (id: {$workerId}) can not write write "
                            ." result of handling lead (id: {$lead->id}) to file.";
                        echo $msg . PHP_EOL;
                        return [
                            'success' => FALSE,
                            'code' => 2,
                            'message' => $msg
                        ];
                    }
                } else {
                    // lead is from not allowed category
                    return ['success' => TRUE];
                }
            };
            return self::$workersPool->run($task, [$lead, get_class(self::$config) . '::getLeadsAllowedCategories']);
        });

        self::$workersPool->waitAllTasksComplete();
    }

    public static function reinitialize(IAppConfig $config)
    {
        self::$workersPool->stop(); # wait, when all past tasks will be done and stop the pool
        self::initialize($config);
    }
}
<?php

namespace App\Logging;

use Monolog\Logger;

class CustomLogger
{
    public function __invoke(array $config)
    {
        $logger = new Logger('database');
        $logger->pushHandler(new DatabaseLogger());
        return $logger;
    }
}

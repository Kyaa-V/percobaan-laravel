<?php

namespace App\Logging;

use App\Models\Log;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;

class DatabaseLogger extends AbstractProcessingHandler
{
    public function __construct($level = Level::Debug, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        $logData = $record->toArray();

        Log::create([
            'channel' => $logData['channel'],
            'level' => $logData['level_name'],
            'message' => $logData['message'],
            'context' => !empty($logData['context']) ? json_encode($logData['context']) : null,
        ]);
    }
}

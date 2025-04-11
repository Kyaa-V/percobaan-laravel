<?php

namespace App\Trait;

use Illuminate\Support\Facades\Log;

trait MonitoringLong
{
    protected function logToDatabase(string $message, array $context = [], string $level = 'info')
    {
        Log::channel('database')->{$level}($message, $context);
    }

    protected function logLongProcess(string $processName, float $startTime, int $threshold = 6)
    {
        $executionTime = microtime(true) - $startTime;

        if ($executionTime > $threshold) {
            $this->logToDatabase("Slow process detected: $processName", [
                'execution_time' => $executionTime,
                'threshold' => $threshold,
                'url' => request()?->fullUrl(),
                'method' => request()?->method(),
            ], 'warning');
        }
    }
}

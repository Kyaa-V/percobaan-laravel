<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogsController
{
    public function getLogs()
    {
        try {
            $logs = Log::paginate(15);

            return response()->json([
                "success" => true,
                "amount_users" => $logs->total(),
                "data" => [
                    "message" => "Berhasil get data logs",
                    "logs" => $logs
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get logs error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                "success" => false,
                "message" => "Terjadi kesalahan saat mengambil data user",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}

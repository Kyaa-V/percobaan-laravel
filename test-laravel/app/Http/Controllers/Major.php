<?php

namespace App\Http\Controllers;

use App\Models\Major as ModelsMajor;
use Illuminate\Http\Request;
use App\Trait\MonitoringLong;
use Illuminate\Support\Facades\Log;
use League\Config\Exception\ValidationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Major
{
    use AuthorizesRequests, MonitoringLong;

    public function postMajor(Request $request)
    {
        $start = microtime(true);
        try {
            $validation =  $request->validate([
                'name' => 'required|string'
            ]);

            $major = ModelsMajor::create($validation);
            $this->logLongProcess("post data major", $start);

            return response()->json([
                'success' => true,
                'message' => 'Data major berhasil ditambahkan',
                'data' => $major
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi form pengalaman gagal',
                'errors' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Form Education error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat melakukan post form experience',
                'error' => $e
            ], 500);
        }
    }

}

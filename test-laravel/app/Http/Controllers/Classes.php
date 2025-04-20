<?php

namespace App\Http\Controllers;

use App\Models\Classes as ModelsClasses;
use Illuminate\Http\Request;
use App\Trait\MonitoringLong;
use Illuminate\Support\Facades\Log;
use League\Config\Exception\ValidationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Classes
{
    use AuthorizesRequests, MonitoringLong;

    public function postClass(Request $request)
    {
        $start = microtime(true);
        try {
            $validation =  $request->validate([
                'name_class' => 'required|string',
                'classles_id' => 'required|string',
                'majors-id' => 'required|string',
            ]);

            $class = ModelsClasses::create($validation);
            $this->logLongProcess("post data class", $start);

            return response()->json([
                'success' => true,
                'message' => 'Data personal berhasil disimpan',
                'data' => $class
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

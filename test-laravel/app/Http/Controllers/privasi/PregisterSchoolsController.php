<?php

namespace App\Http\Controllers;

use App\Http\Requests\privasi\StorePregisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\privasi\Pregister_schools;
use App\Trait\MonitoringLong;
use Illuminate\Validation\ValidationException;

class PregisterSchoolsController
{
    use MonitoringLong;

    public function postEducation(StorePregisterRequest $request)
    {
        $start = microtime(true);
        try {
            $validationRequest = $request->validated();


            $checkDataExists = Pregister_schools::where('users_id', $request->users_id)
                ->where('name', $request->name)
                ->where('emails', $request->emails)
                ->where('schools', $request->schools)
                ->where('NPSN', $request->NPSN)
                ->where('NISN', $request->NISN)->first();

            if ($checkDataExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim data yang sudah ada',
                ], 409);
            }

            $educations = Pregister_schools::create($validationRequest);
            $this->logLongProcess("get data education", $start);
            return response()->json([
                'success' => true,
                'message' => 'Data personal berhasil disimpan',
                'data' => $educations
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi form pengalaman gagal',
                'errors' => $e->errors()
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

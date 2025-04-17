<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trait\MonitoringLong;
use Illuminate\Support\Facades\Log;
use App\Models\privasi\Pregister_schools;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\privasi\StorePregisterRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PregisterSchoolsController
{
    use MonitoringLong;

    public function postPregister(Request $request)
    {
        $start = microtime(true);
        try {
            $validationRequest = $request->validate([
                "name" => "required|string",
                "emails" => "required|email",
                "schools" => "required|string",
                "photo" => "required|image",
                "SKL" => "required|image",
                "KTP" => "required|image",
                "AKTA_KELAHIRAN" => "required|image",
                "RAPORT" => "required|image",
                "NISN" => "required|string",
                "NPSN" => "required|string",
                "major" => "required|string|exists:majors,name",
                "PRESTASI" => "required|image",
                "status" => "required|string|in:PREDAFTAR,SISWA,LULUS",
                "users_id" => "required|string|max:36",
            ]);


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
                'message' => 'Data pregister berhasil disimpan',
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
    public function getDataPregisterById($id)
    {
        $start = microtime(true);
        try {
            $pregister = Pregister_schools::findOrFail($id);
            $this->logLongProcess("get data pregister error", $start);

            return response()->json([
                "success" => true,
                'message' => 'Data Berhasil Di Load',
                "datas" => $pregister
            ]);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                "success" => false,
                "message" => "Data Tidak Di Temukan",
                "error" => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            Log::error('Get data by ID error: ' . $e->getMessage(), [
                'user_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                "success" => false,
                "message" => "Terjadi kesalahan saat mengambil data yang  ada"
            ], 500);
        }
    }
    public function getDataPregister()
    {
        $start = microtime(true);
        try {
            $pregister = Pregister_schools::paginate(15);
            $this->logLongProcess("get user", $start);

            return response()->json([
                "success" => true,
                "amount"=>$pregister->total(),
                "data" => [
                    "message" => "Berhasil get data user",
                    "data" => $pregister
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get pregister error: ' . $e->getMessage(), [
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

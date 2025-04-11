<?php

namespace App\Http\Controllers\privasi;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\privasi\PersonalData;
use App\Trait\MonitoringLong;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PersonalDataController extends Controller
{
    use MonitoringLong;

    public function postDataPersonal(Request $request)
    {
        $start = microtime(true);
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'your_field' => 'required|string|max:255',
                'birthdays' => 'required|string|regex:/^\d{1,2} [A-Za-z]+ \d{4}$/',
                'your_address' => 'required|string|max:500',
                'name_country' => 'required|string|max:255|exists:countries,name',
                'name_province' => 'required|string|max:255|exists:states,name',
                'name_city' => 'required|string|max:255|exists:cities,name',
                'city_id' => 'required|string|max:255',
                'code' => 'required|string|max:20',
                'users_id' => 'required|string',
                'numberOfPhone' => [
                    'required',
                    'string',
                    'max:20',
                    'regex:/^\+[0-9]+$/'
                ],
            ]);

            $checkDataExists = PersonalData::where('name', $validatedData['name'])
                ->where('your_address', $validatedData['your_address'])
                ->where('name_city', $validatedData['name_city'])
                ->where('numberOfPhone', $validatedData['numberOfPhone'])
                ->first();

            if ($checkDataExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data yang sama sudah ada dalam sistem',
                ], 409);
            }

            $personalData = PersonalData::create($validatedData);
            $this->logLongProcess("post data personal error", $start);

            return response()->json([
                'success' => true,
                'message' => 'Data personal berhasil disimpan',
                'data' => $personalData
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi data gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error menyimpan data personal: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menyimpan data',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }
    public function getDataPersonalById($id)
    {
        $start = microtime(true);
        try {
            $personal = PersonalData::findOrFail($id);
            $this->logLongProcess("get data personal error", $start);

            return response()->json([
                "success" => true,
                'message' => 'Data Berhasil Di Load',
                "datas" => $personal
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
    public function editDataPersonalById(Request $request, $id)
    {
        $start = microtime(true);
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'your_field' => 'required|string|max:255',
                'birthdays' => 'required|string|regex:/^\d{1,2} [A-Za-z]+ \d{4}$/',
                'your_address' => 'required|string|max:500',
                'name_city' => 'required|string|max:255',
                'name_province' => 'required|string|max:255',
                'code' => 'required|string|max:20',
                'numberOfPhone' => 'required|string|max:20|regex:/^[0-9]+$/',
                'name_country' => 'required|string|max:255',
            ]);

            $personal = PersonalData::findOrFail($id);
            $this->authorize('update', $personal);

            $personal->update([
                'name' =>  $request->name ?? $personal->name,
                'your_field' =>  $request->your_field ?? $personal->your_field,
                'birthdays' =>  $request->birthdays ?? $personal->birthdays,
                'your_address' =>  $request->your_address ?? $personal->your_address,
                'name_city' =>  $request->name_city ?? $personal->name_city,
                'name_province' =>  $request->name_province ?? $personal->name_province,
                'code' =>  $request->code ?? $personal->code,
                'numberOfPhone' =>  $request->numberOfPhone ?? $personal->numberOfPhone,
                'name_country' =>  $request->name_country ?? $personal->name_country,
            ]);
            $this->logLongProcess("update data personal error", $start);

            return response()->json([
                'success' => true,
                'data' => [
                    'message' => 'Data berhasil diperbarui',
                    'data' => $personal
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengedit Data ini'
            ], 403);
        } catch (\Throwable $th) {
            Log::error('error in table personal data: ' . $th->getMessage(), [
                'user_id' => $id,
                'trace' => $th->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui Data',
                'error' => $th->getMessage() //env('APP_DEBUG') ? $th->getMessage() : null
            ], 500);
        }
    }
}

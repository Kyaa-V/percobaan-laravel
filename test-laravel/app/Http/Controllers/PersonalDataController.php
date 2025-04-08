<?php

namespace App\Http\Controllers;

use App\Models\PersonalData;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PersonalDataController extends Controller
{
    public function postDataPersonal(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'birthdays' => 'required|string|regex:/^\d{1,2} [A-Za-z]+ \d{4}$/',
                'your_address' => 'required|string|max:500',
                'name_city' => 'required|string|max:255',
                'name_province' => 'required|string|max:255',
                'code' => 'required|string|max:20',
                'numberOfPhone' => 'required|string|max:20|regex:/^[0-9]+$/',
                'name_country' => 'required|string|max:255',
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
        try {
            $personal = PersonalData::findOrFail($id);

            return response()->json([
                "success" => true,
                'message' => 'Data Berhasil Di Load'
            ]);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                "success" => false,
                "message" => "Data Tidak Di Temukan"
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
        try {
            $request->validate([
                'name' => 'required|string|max:255',
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
                'birthdays' =>  $request->birthdays ?? $personal->birthdays,
                'your_address' =>  $request->your_address ?? $personal->your_address,
                'name_city' =>  $request->name_city ?? $personal->name_city,
                'name_province' =>  $request->name_province ?? $personal->name_province,
                'code' =>  $request->code ?? $personal->code,
                'numberOfPhone' =>  $request->numberOfPhone ?? $personal->numberOfPhone,
                'name_country' =>  $request->name_country ?? $personal->name_country,
            ]);

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
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui Data',
                'error' => $th->getMessage() //env('APP_DEBUG') ? $th->getMessage() : null
            ], 500);
        }
    }
}

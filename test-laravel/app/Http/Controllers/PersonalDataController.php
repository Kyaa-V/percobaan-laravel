<?php

namespace App\Http\Controllers;

use App\Models\PersonalData;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PersonalDataController extends Controller
{
    public function postExperience(Request $request)
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
}
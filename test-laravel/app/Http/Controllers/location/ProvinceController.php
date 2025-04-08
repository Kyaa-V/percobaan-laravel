<?php

namespace App\Http\Controllers\location;

use App\Http\Resources\ProvinceResource;
use Illuminate\Http\Request;
use App\Models\location\States;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProvinceController
{
    public function getProvince()
    {
        try {
            $province = States::all();

            return response()->json([
                "success" => true,
                "data" => [
                    "message" => "Berhasil get data province",
                    "province"=> new ProvinceResource($province)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get province error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                "success" => false,
                "message" => "Terjadi kesalahan saat mengambil data province",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function getProvinceByCountryId($id)
    {
        try {
            $province = States::where('country_id',$id)->get();

            return response()->json([
                "success" => true,
                "data" => new ProvinceResource($province)
            ]);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                "success" => false,
                "message" => "province tidak ditemukan"
            ], 404);
        } catch (\Exception $e) {
            Log::error('Get province by ID error: ' . $e->getMessage(), [
                'user_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                "success" => false,
                "message" => "Terjadi kesalahan saat mengambil data province",
                "error"=> $e->getMessage()
            ], 500);
        }
    }
}

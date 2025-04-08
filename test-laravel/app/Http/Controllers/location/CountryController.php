<?php

namespace App\Http\Controllers\location;

use App\Http\Resources\CountryResource;
use Illuminate\Http\Request;
use App\Models\location\Country;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CountryController
{
    public function getCountry()
    {
        try {
            $country = Country::all();
            return response()->json([
                "success" => true,
                "data" => [
                    "message" => "Berhasil get data country",
                    "country"=> new CountryResource($country)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get country error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                "success" => false,
                "message" => "Terjadi kesalahan saat mengambil data country",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}

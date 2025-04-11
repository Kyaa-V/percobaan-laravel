<?php

namespace App\Http\Controllers\location;

use Illuminate\Http\Request;
use App\Models\location\City;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\CityResource;
use App\Trait\MonitoringLong;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CityController
{
    use MonitoringLong;

    public function getCity()
    {
        $start = microtime(true);
        try {
            $city = City::select('idCountry', 'name', 'state_id', 'state_code', 'country_id', 'country_code')->paginate(15);
            Log::info('Masuk ke method get-city');
            $this->logLongProcess("get city", $start);
            return response()->json([
                "success" => true,
                "data" => [
                    "success" => true,
                    "message" => "Berhasil get data city",
                    "city" => new CityResource($city)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get city error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                "success" => false,
                "message" => "Terjadi kesalahan saat mengambil data city",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function getCityByProvinceId($idCountry, $idProvince)
    {
        $start = microtime(true);
        try {
            $city = City::where('country_id', $idCountry)->where('state_id', $idProvince)->get();
     
            $this->logLongProcess("get city id", $start);
  
            return response()->json([
                "success" => true,
                "message" => "Berhasil get data city",
                "data" => new CityResource($city)
            ]);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                "success" => false,
                "message" => "city tidak ditemukan"
            ], 404);
        } catch (\Exception $e) {
            Log::error('Get city by ID error: ' . $e->getMessage(), [
                'user_id' => $idCountry,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                "success" => false,
                "message" => "Terjadi kesalahan saat mengambil data city"
            ], 500);
        }
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CountryResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($country) {
                return [
                    'id' => $country->id,
                    'name' => $country->name,
                    'region' => $country->region,
                    'subregion' => $country->subregion,
                    'phonecode' => $country->phonecode,
                    'currency_name' => $country->currency_name,
                    'created_at' => $country->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $country->updated_at->format('Y-m-d H:i:s')
                ];
            })
        ];
    }
}

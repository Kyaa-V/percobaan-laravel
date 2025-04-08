<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CityResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'city' => $this->collection->transform(function ($city) {
                return [
                    'id' => $city->id,
                    'name' => $city->name,
                    'state_id' => $city->state_id,
                    'state_code' => $city->state_code,
                    'country_id' => $city->country_id,
                    'country_code' => $city->country_code,
                    'created_at' => $city->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $city->updated_at->format('Y-m-d H:i:s')
                ];
            })
        ];
    }
}

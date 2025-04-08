<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProvinceResource extends ResourceCollection
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
                    'country_id' => $country->country_id,
                    'parent_id' => $country->parent_id,
                    'level' => $country->level,
                    'fips_code' => $country->fips_code,
                    'created_at' => $country->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $country->updated_at->format('Y-m-d H:i:s')
                ];
            })
        ];
    }
}

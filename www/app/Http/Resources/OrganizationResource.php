<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'building' => [
                'id' => $this->building->id,
                'address' => $this->building->address,
                'latitude' => $this->building->latitude,
                'longitude' => $this->building->longitude,
            ],
            'phones' => $this->phones->pluck('phone_number'), // список номеров телефонов
            'activities' => $this->activities->pluck('name'), // список названий видов деятельности
        ];
    }
}

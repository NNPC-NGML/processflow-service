<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\UnitResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentUnitResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="DepartmentUnitResource",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="created_at", type="date"),
     *     @OA\Property(property="updated_at", type="date"),
     *     @OA\Property(property="unit", ref="#/components/schemas/UnitResource"),
     *  
     *     
     * )
     */

    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'units' => UnitResource::collection($this->units),
        ];
    }
}

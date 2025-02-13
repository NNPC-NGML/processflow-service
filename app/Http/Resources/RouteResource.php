<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    /**
     * @OA\Schema(
     *     schema="RouteResource",
     *     @OA\Property(property="id", type="string"),
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="link", type="string"),
     *     @OA\Property(property="dynamic_content", type="string"),
     *     @OA\Property(property="status", type="boolean"),
     *
     *
     * )
     */

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'link' => $this->link,
            'dynamic_content' => $this->dynamic_content,
            'status' => $this->status,
        ];
    }
}

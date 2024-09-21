<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProcessFlowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    /**
     * @OA\Schema(
     *     schema="ProcessFlowResource",
     *     @OA\Property(property="id", type="string"),
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="start_step_id", type="integer"),
     *     @OA\Property(property="frequency", type="string"),
     *     @OA\Property(property="status", type="boolean"),
     *     @OA\Property(property="frequency_for", type="string"),
     *     @OA\Property(property="day", type="string"),
     *     @OA\Property(property="week", type="string"),
     *     @OA\Property(
     *         property="steps",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/ProcessFlowStepResource"),
     *     ),
     * )
     */

    public function toArray($request): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'start_step_id' => $this->start_step_id,
            'frequency' => $this->frequency,
            'status' => (bool) $this->status,
            'frequency_for' => $this->frequency_for,
            'day' => $this->day,
            'week' => $this->week,
            "start_user_designation" => $this->start_user_designation,
            "start_user_department" => $this->start_user_department,
            "start_user_unit" => $this->start_user_unit,
            'steps' => ProcessFlowStepResource::collection(
                $this->steps
            ),
        ];
    }
}

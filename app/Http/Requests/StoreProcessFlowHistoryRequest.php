<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcessFlowHistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @OA\Schema(
     *      schema="StoreWorkflowHistoryRequest",
     *     title="StoreWorkflowHistoryRequest",
     *     description="Store Workflow History Request body data",
     *     type="object",
     *     required={"user_id", "task_id", "step_id", "process_flow_id", "status"},
     *     @OA\Property(
     *         property="user_id",
     *         type="integer",
     *         description="User ID",
     *         example=1
     *     ),
     *     @OA\Property(
     *         property="task_id",
     *         type="integer",
     *         description="Task ID",
     *         example=1
     *     ),
     *     @OA\Property(
     *         property="step_id",
     *         type="integer",
     *         description="Step ID",
     *         example=1
     *     ),
     *     @OA\Property(
     *         property="process_flow_id",
     *         type="integer",
     *         description="Process Flow ID",
     *         example=1
     *     ),
     *     @OA\Property(
     *         property="status",
     *         type="boolean",
     *         description="Status",
     *         example=true
     *     ),
     * )
     */
    public function rules(): array
    {
        return [
            "user_id" => "required|integer",
            "task_id" => "required|integer",
            "step_id" => "required|integer",
            "process_flow_id" => "required|integer",
            "status" => "required|boolean",
        ];
    }
}

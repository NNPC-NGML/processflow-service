<?php

namespace App\Service;

use App\Models\ProcessFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProcessFlowService
{

    /**
     * Create a new process flow.
     *
     * @param  Request  $request
     * @return object|ProcessFlow
     */
    public function createProcessFlow(Request $request): object
    {

        // Validate the request data
        $validator = Validator::make($request->all(), [

            "name" => "required|string|max:255",
            "start_step_id" => "nullable|integer",
            "frequency" => "required|in:daily,weekly,hourly,monthly,yearly,none",
            "status" => "sometimes|boolean",
            "frequency_for" => "required|in:users,customers,suppliers,contractors,none",
            "day" => "nullable|string",
            "week" => "nullable|string",
        ]);
        // Return false if validation fails
        if ($validator->fails()) {
            return $validator->errors();
        }
        //Create a new process flow and return true if successful, otherwise return false
        return ProcessFlow::create($request->all());
    }

    /**
     * Retrieve a ProcessFlow by its ID.
     *
     * @param int $id The ID of the ProcessFlow to retrieve.
     *
     * @return \App\Models\ProcessFlow|null The retrieved ProcessFlow, or null if not found.
     */

    public function getProcessFlow(int $id): ProcessFlow
    {

        try {
            return ProcessFlow::with(['steps'])->findOrFail($id);

            // Do something with $processFlow
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Update an existing ProcessFlow.
     *
     * @param int $id The ID of the ProcessFlow to update.
     * @param \Illuminate\Http\Request $request The HTTP request containing the updated data.
     *
     * @return \App\Models\ProcessFlow The updated ProcessFlow instance.
     *
     * @throws \Exception If validation fails or if an error occurs during the update.
     */

    public function updateProcessflow(int $id, Request $request): ProcessFlow
    {
        $model = ProcessFlow::findOrFail($id);
        // validation
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|nullable',
            "start_step_id" => "sometimes|nullable|integer",
            "frequency" => "sometimes|nullable|in:daily,weekly,hourly,monthly,yearly,none",
            "status" => "sometimes|nullable|boolean",
            "frequency_for" => "sometimes|nullable|in:users,customers,suppliers,contractors,none",
            "day" => "sometimes|nullable|string",
            "week" => "sometimes|nullable|string",

        ]);
        if ($validator->fails()) {
            throw new \Exception($validator->errors());
        }

        if ($model) {
            if ($model->update($request->all())) {
                return $model;
            }
            throw new \Exception('Something went wrong.');
        }
        throw new \Exception('Something went wrong.');
    }

    /**
     * Delete a ProcessFlow by its ID.
     *
     * @param int $id The ID of the ProcessFlow to delete.
     *
     * @return bool True if the deletion is successful, false otherwise.
     */

    public function deleteProcessflow(int $id): bool
    {
        $model = ProcessFlow::find($id);
        if ($model) {
            if ($model->delete()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Retrieve all processflow with status set to true.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\ProcessFlow
     */

    public function getAllProcessFlow()
    {
        return (new ProcessFlow())->where(["status" => ProcessFlow::ACTIVE])->get();
    }
}

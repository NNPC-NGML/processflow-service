<?php

namespace App\Service;

use App\Models\ProcessFlowHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProcessFlowHistoryService
{
    /**
     * Retrieve all processflow histories.
     *
     * This method retrieves all processflow histories from the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\ProcessFlowHistory[]
     *
     * @throws \Exception If an error occurs while retrieving the processflow histories.
     */
    public function getProcessFlowHistories()
    {
        // return processflowHistory::where(["status" => 1])->get();
        return (new ProcessFlowHistory())->where(["status" => 1])->get();
    }

    /**
     * This Method is used to create a new processflow history in the database .
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool True if the processflow history is created successfully, false otherwise.
     * @throws bool False  has an error.
     */

    public function createProcessFlowHistory(Request $request): object
    {
        $model = new ProcessFlowHistory();

        $validator = Validator::make($request->all(), [
            "step_id" => "required",
            "process_flow_id" => "required",
            "user_id" => "required",
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return $model->create($request->all());
    }

    /**
     * Retrieve a processflowHistory by its ID.
     *
     * @param int $id The ID of the processflowHistory to retrieve.
     *
     * @return \App\Models\ProcessFlowHistory|null The retrieved processflowHistory, or null if not found.
     */

    public function getProcessFlowHistory(int $id): ?ProcessFlowHistory
    {

        return ProcessFlowHistory::findOrFail($id);
    }

    /**
     * Update an existing processflow History.
     *
     * @param Request $request The request containing the updated data
     * @param int $id The ID of the processflow history to update
     * @return object The updated processflow History model
     * @throws ModelNotFoundException If no processflow History with the given ID is found
     */
    public function updateProcessFlowHistory(Request $request, int $id): ProcessFlowHistory
    {
        $processFlowHistory = $this->getProcessFlowHistory($id);

        if (!$processFlowHistory) {
            throw new ModelNotFoundException("ID $id not found");
        }

        $validator = Validator::make($request->all(), [
            'user_id'            => 'sometimes|integer',
            'task_id'            => 'sometimes|integer',
            'step_id'            => 'sometimes|integer',
            'process_flow_id'    => 'sometimes|integer',
            'status'             => 'sometimes|boolean',
        ]);



        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        $processFlowHistory->update($request->all());

        return $processFlowHistory;
    }

    /**
     * Delete a processflowHistory by its ID.
     *
     * @param int $id The ID of the processflowHistory to delete.
     *
     * @return bool True if the deletion is successful, false otherwise.
     */

    public function deleteProcessFlowHistory(int $id): bool
    {
        $model = ProcessFlowHistory::find($id);
        if ($model) {
            if ($model->delete()) {
                return true;
            }
        }
        return false;
    }
}

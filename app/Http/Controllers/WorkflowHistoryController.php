<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkflowHistoryRequest;
use App\Http\Resources\WorkflowHistoryResource;
use App\Service\WorkflowHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\WorkflowHistoryCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
class WorkflowHistoryController extends Controller
{
 /**
     * The WorkflowHistoryService instance that will handle the business logic.
     *
     * The constructor injects the service into the controller so it can be used
     * in the controller methods.
     */
    protected $workflowHistoryService;

    public function __construct(WorkflowHistoryService $workflowHistoryService)
    {
        $this->workflowHistoryService = $workflowHistoryService;
    }

/**
 * @OA\Put(
 *      path="/workflow-histories/{id}",
 *      operationId="updateWorkflowHistory",
 *      tags={"Workflow Histories"},
 *      summary="Update a workflow history",
 *      description="Updates an existing workflow history record.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the workflow history to update",
 *          required=true,
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="Updated workflow history data",
 *          @OA\JsonContent(
 *              required={"user_id", "task_id", "step_id", "process_flow_id", "status"},
 *              @OA\Property(property="user_id", type="integer", example=1),
 *              @OA\Property(property="task_id", type="integer", example=1),
 *              @OA\Property(property="step_id", type="integer", example=1),
 *              @OA\Property(property="process_flow_id", type="integer", example=1),
 *              @OA\Property(property="status", type="boolean", example=true),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/WorkflowHistory"
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Workflow history not found"
 *      )
 * )
 *
 * @param \App\Http\Requests\UpdateWorkflowHistoryRequest $request
 * @param int $id
 * @return \Illuminate\Http\JsonResponse
 */

    public function index(Request $request)
    {
     $workflowHistories = $this->workflowHistoryService->getWorkflowHistories($request);
    if ($workflowHistories === null || $workflowHistories->isEmpty()) {
        return new WorkflowHistoryCollection([]);
    }
    return new WorkflowHistoryCollection($workflowHistories);
    }
    
    /**
     * Store a new workflow history.
     *
     * This method takes a request and creates a new workflow history.
     *
     * @param StoreWorkflowHistoryRequest $request The request containing the workflow history data.
     * @return WorkflowHistoryResource The created workflow history resource.
     */

    public function store(StoreWorkflowHistoryRequest $request)
    {
        return DB::transaction(function () use ($request) 
        {
            $storedWorkflowHistory = $this->workflowHistoryService->createWorkflowHistory($request);
            return new WorkflowHistoryResource($storedWorkflowHistory); 
        }, 5);   
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ProcessFlow;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\DB;
use App\Service\ProcessFlowService;
use App\Http\Controllers\Controller;
use App\Service\ProcessflowStepService;
use App\Http\Resources\ProcessFlowResource;
use App\Jobs\ProcessFlow\ProcessFlowCreated;
use App\Jobs\ProcessFlow\ProcessFlowDeleted;
use App\Jobs\ProcessFlow\ProcessFlowUpdated;
use App\Http\Requests\StoreProcessFlowRequest;
use App\Http\Requests\UpdateProcessFlowRequest;

/**
 * @OA\Tag(name="Process Flows")
 */

class ProcessFlowController extends Controller
{
    /**
     * The ProcessFlowService instance that will handle the business logic.
     *
     * The constructor injects the service into the controller so it can be used
     * in the controller methods.
     */
    protected $processFlowService, $processflowStepService;

    public function __construct(ProcessFlowService $processFlowService, ProcessflowStepService $processflowStepService)
    {
        $this->processFlowService = $processFlowService;
        $this->processflowStepService = $processflowStepService;
    }

    /**
     * @OA\Get(
     *      path="/process-flows",
     *      tags={"Process Flows"},
     *      summary="Get all process flow",
     *      description="Returns a list of all process flow",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/ProcessFlowResource")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *      )
     * )
     */

    public function index()
    {
        $data = $this->processFlowService->getAllProcessFlow();
        return ProcessFlowResource::collection($data);
    }

    /**
     * Store a new process flow and its steps(optional).
     *
     * This method takes a request with steps data and creates a new process flow and its associated steps.
     * It handles setting up the relationships between the steps like next_step_id and process_flow_id.
     * It also handles setting the start_step_id on the process flow.
     *
     * @param StoreProcessFlowRequest $request The request containing the process flow data.
     * @return ProcessFlowResource The created process flow resource.
     */

    /**
     * @OA\Post(
     *     path="/process-flows",
     *     summary="Creates a new process flow",
     *     tags={"Process Flows"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Process flow creation request",
     *         @OA\JsonContent(ref="#/components/schemas/StoreProcessFlowRequest")
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Process flow created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ProcessFlowResource")
     *     ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *    @OA\Response(
     * response="422",
     * description="Validation errors"),
     *    @OA\Response(
     * response="404",
     * description="Not Found"),
     *    @OA\Response(
     * response="500",
     * description="Server Error"),
     *
     *     security={
     *         {"BearerAuth": {}}
     *     }
     * )
     */

    public function store(StoreProcessFlowRequest $request)
    {

        return DB::transaction(function () use ($request) {

            if ($request->has('steps')) {
                $steps = $request->steps;
                $createdSteps = [];
                $processFlowId = null;

                foreach ($steps as $index => $step) {
                    $createdStep = $this->processflowStepService->createProcessFlowStep(new Request($step));
                    if ($index === 0) {
                        $request['start_step_id'] = $createdStep->id;
                        $storedProcessFlow = $this->processFlowService->createProcessFlow($request);
                        $processFlowId = $storedProcessFlow->id;
                    }
                    $createdSteps[] = $createdStep;
                }
                foreach ($createdSteps as $index => $step) {
                    $next_step_id = $index === count($createdSteps) - 1 ? null : $createdSteps[$index + 1]->id;
                    $this->processflowStepService->updateProcessFlowStep(new Request(['process_flow_id' => $processFlowId, 'next_step_id' => $next_step_id]), $step->id);
                }
            } else {
                $storedProcessFlow = $this->processFlowService->createProcessFlow($request);
            }
            ProcessFlowCreated::dispatch($storedProcessFlow->toArray());
            return new ProcessFlowResource($storedProcessFlow);
        }, 5);
    }

    /**
     * Get the specified process flow.
     *
     * @param string $id The ID of the process flow to retrieve.
     * @return ProcessFlowResource The process flow resource.
     */

    /**
     * @OA\Get(
     *     path="/process-flows/{id}",
     *     tags={"Process Flows"},
     *     summary="Get a process flow",
     *     description="Returns the details of a single process flow with its associated steps",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the process flow",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Process Flow found",
     *         @OA\JsonContent(ref="#/components/schemas/ProcessFlowResource")
     *     ),
     * @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     * @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * @OA\Response(
     *          response=404,
     *          description="Not Found",
     *      ),
     * @OA\Response(
     *          response=500,
     *          description="Server Error",
     *      ),
     * )
     */
    public function show(string $id)
    {
        $processFlow = $this->processFlowService->getProcessFlow($id);
        return new ProcessFlowResource($processFlow);
    }

    /**
     * @OA\Put(
     *     path="/process-flows/{id}",
     *     tags={"Process Flows"},
     *     summary="Update a process flow",
     *     description="Updates the details of an existing process flow with it's associated steps if provided",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the process flow to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateProcessFlowRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Process Flow updated",
     *         @OA\JsonContent(ref="#/components/schemas/ProcessFlowResource")),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response="422",description="Validation errors"),
     *     @OA\Response(response=404,description="Not found"),
     *     @OA\Response(response=500, description="Server error"),
     *
     * )
     */

    public function update(UpdateProcessFlowRequest $request, int $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $storedProcessFlow = $this->processFlowService->updateProcessFlow($id, $request);

            ProcessFlowUpdated::dispatch($storedProcessFlow->toArray());
            return new ProcessFlowResource($storedProcessFlow);
        }, 5);
    }

    /**
     * @OA\Delete(
     *      path="/process-flows/{id}",
     *      tags={"Process Flows"},
     *      summary="Delete a process flow and its steps",
     *      description="Deletes a process flow identified by ID along with all associated steps.",
     *      security={
     *         {"BearerAuth": {}}
     *     },
     *      @OA\Parameter(
     *          name="id",
     *          description="ID of the process flow to delete",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(response=204, description="No content"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *         ),
     *      @OA\Response(response=500, description="Server error")
     *      ),
     * )
     *
     */

    public function destroy(int $id)
    {
        try {

            return DB::transaction(function () use ($id) {

                if ($this->processFlowService->getProcessFlow($id)) {
                    $this->processFlowService->deleteProcessflow($id);
                    ProcessFlowDeleted::dispatch($id);
                    return response()->noContent();
                }
            }, 5); // Setting 5 seconds timeout
        } catch (\Throwable $e) {

            throw $e;
            // Handle any exceptions

            // return response()->json(['error' => 'Failed to delete process flow'], 500);
        }
    }
}

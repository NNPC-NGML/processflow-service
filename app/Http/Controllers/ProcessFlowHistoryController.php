<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Service\ProcessFlowHistoryService;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProcessFlowHistoryResource;
use App\Http\Resources\ProcessFlowHistoryCollection;
use App\Http\Requests\StoreProcessFlowHistoryRequest;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Jobs\ProcessFlowHistory\ProcessFlowHistoryCreated;
use App\Jobs\ProcessFlowHistory\ProcessFlowHistoryDeleted;
use App\Jobs\ProcessFlowHistory\ProcessFlowHistoryUpdated;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProcessFlowHistoryController extends Controller
{
    /**
     * The ProcessFlowHistoryService instance that will handle the business logic.
     *
     * The constructor injects the service into the controller so it can be used
     * in the controller methods.
     */
    protected $processFlowHistoryService;

    public function __construct(ProcessFlowHistoryService $processFlowHistoryService)
    {
        $this->processFlowHistoryService = $processFlowHistoryService;
    }

    /**
     * @OA\Get(
     *     path="/processflowhistory",
     *     summary="Fetch all processflow histories",
     *     tags={"processflow History"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ProcessFlowHistoryResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     )
     * )
     *
     * @param \App\Http\Requests\UpdateProcessFlowHistoryRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */



    public function index()
    {
        $processFlowHistory = $this->processFlowHistoryService->getProcessFlowHistories();
        return ProcessFlowHistoryResource::collection($processFlowHistory);
    }


    /**
     * @OA\Post(
     *     path="/processflowhistory/create",
     *     summary="Create a new processflow history",
     *     tags={"processflow History"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\Items(ref="#/components/schemas/StoreProcessFlowHistoryRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\Items(ref="#/components/schemas/ProcessFlowHistoryResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\Items(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid."
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "user_id": {"The user id field is required."},
     *                     "task_id": {"The task id field is required."},
     *                     "step_id": {"The step id field is required."},
     *                     "process_flow_id": {"The process flow id field is required."},
     *                     "status": {"The status field is required."}
     *                 }
     *             )
     *         )
     *     )
     * )

     * @param StoreProcessFlowHistoryRequest $request The request containing the processflow history data.
     * @return ProcessFlowHistoryResource The created processflow history resource.

     */

    public function store(StoreProcessFlowHistoryRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $storedProcessFlowHistory = $this->processFlowHistoryService->createProcessFlowHistory($request);

            ProcessFlowHistoryCreated::dispatch($storedProcessFlowHistory->toArray());
            return new ProcessFlowHistoryResource($storedProcessFlowHistory);
        }, 5);
    }

    /**
     * Display the specified resource.
     */

    /**
     * @OA\Get(
     *     path="/processflowhistory/{id}",
     *     summary="Fetch a processflow history",
     *     tags={"processflow History"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ProcessFlowHistoryResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     )
     * )
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $processFlowHistory = $this->processFlowHistoryService->getProcessFlowHistory($id);
        return new ProcessFlowHistoryResource($processFlowHistory);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        ProcessFlowHistoryUpdated::dispatch($request->toArray());
    }

    /**
     * @OA\Delete(
     *      path="/processflowhistory/{id}",
     *      operationId="deleteprocessflowHistory",
     *      tags={"processflow History"},
     *      summary="Delete a processflow history",
     *      description="Deletes a processflowhistory by its ID.",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the route to delete",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Route not found",
     *
     *      )
     * )
     *
     * @param string $id The ID of the processflow history to delete.
     *
     */
    public function destroy(string $id)
    {
        try {
            $deleted = $this->processFlowHistoryService->deleteProcessFlowHistory($id);
        } catch (\Exception $e) {
            throw $e;
        }

        if ($deleted) {
            ProcessFlowHistoryDeleted::dispatch($id);
            return response()->noContent();
        }

        throw new NotFoundHttpException('processflow history not found.');
    }
}

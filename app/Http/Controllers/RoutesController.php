<?php

namespace App\Http\Controllers;

use Skillz\UserService;
use Illuminate\Http\Request;
use App\Service\RouteService;
use App\Jobs\Route\RouteCreated;
use App\Jobs\Route\RouteDeleted;
use App\Jobs\Route\RouteUpdated;
use App\Http\Resources\RouteResource;
use Illuminate\Support\Facades\Validator;

class RoutesController extends Controller
{

    protected $routeService;

    public function __construct(RouteService $routeService)
    {
        $this->routeService = $routeService;
    }

    /**
     * @OA\Get(
     *      path="/routes",
     *      operationId="getRoutes",
     *      tags={"Routes"},
     *      summary="Get all routes",
     *      description="Returns a list of all routes",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/RouteResource")
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
        $data = $this->routeService->getAllRoute();
        return RouteResource::collection($data);
    }

    /**
     * @OA\Post(
     *      path="/routes/create",
     *      operationId="storeRoute",
     *      tags={"Routes"},
     *      summary="Create a new route",
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Route Name",
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Parameter(
     *         name="link",
     *         in="query",
     *         required=true,
     *         description="Route Path",
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=true,
     *         description="This is the active or inactive status of a route , Default as true or 1 , to indicate the route is active",
     *         @OA\Schema(type="boolean")
     *     ),
     *      @OA\Response(
     *          response="200",
     *          description="Route created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/RouteResource")
     *      ),
     *      @OA\Response(
     *          response="400",
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="name", type="array",
     *                      @OA\Items(type="string", example="The name field is required.")
     *                  ),
     *                  @OA\Property(property="link", type="array",
     *                      @OA\Items(type="string", example="The link field is required.")
     *                  ),
     *                  @OA\Property(property="status", type="array",
     *                      @OA\Items(type="string", example="The status field is required.")
     *                  ),
     *              )
     *          )
     *      )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\RouteResource
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'link' => 'required|string',
            'dynamic_content' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "error", "data" => $validator->errors()], 400);
        }

        $result = $this->routeService->createRoute($request);

        // RouteCreated::dispatch($result->toArray());
        return new RouteResource($result);
    }

    /**
     * @OA\Get(
     *      path="/routes/view/{id}",
     *      operationId="getRouteById",
     *      tags={"Routes"},
     *      summary="Get route by ID",
     *      description="Returns a single route by its ID",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the route to retrieve",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/RouteResource"
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Route not found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error"
     *      )
     * )
     *
     * @param string $id The ID of the route to retrieve.
     * @return \App\Http\Resources\RouteResource
     */
    public function show(string $id)
    {
        $data = $this->routeService->getRoute($id);
        return new RouteResource($data);
    }

    /**
     * @OA\Put(
     *      path="/routes/update/{id}",
     *      operationId="updateRoute",
     *      tags={"Routes"},
     *      summary="Update a route",
     *      description="Updates a route by its ID.",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the route to update",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Route data to update",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="link", type="string"),
     *              @OA\Property(property="status", type="boolean"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Route was updated")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Route not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Route not found")
     *          )
     *      )
     * )
     *
     * @param \Illuminate\Http\Request $request The request containing the data to update.
     * @param string $id The ID of the route to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        $model = $this->routeService->UpdateRoute($id, $request);
        if ($model) {
            $routeUpdated = $this->routeService->getRoute($id);
            RouteUpdated::dispatch($routeUpdated->toArray());
            return response()->json(["status" => "success", "message" => "Route was updated"], 200);
        }
        return response()->json(["status" => "success", "message" => "page not found."], 404);
    }

    /**
     * @OA\Delete(
     *      path="/routes/delete/{id}",
     *      operationId="deleteRoute",
     *      tags={"Routes"},
     *      summary="Delete a route",
     *      description="Deletes a route by its ID.",
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
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Route has been deleted")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Route not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Route not found")
     *          )
     *      )
     * )
     *
     * @param string $id The ID of the route to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $model = $this->routeService->deleteRoute($id);
        if ($model) {
            RouteDeleted::dispatch($id);
            return response()->json(["status" => "success", "message" => "Route has been deleted"], 200);
        }
        return response()->json(["status" => "success", "message" => "page not found."], 404);
    }

    public function route()
    {

        return request()->cookie('jwt');
        // $response = (new UserService)->getRequest('get', 'todos/1');

        // return $response;
        // https://jsonplaceholder.typicode.com/todos/1
        // return 'route ok';
    }
}

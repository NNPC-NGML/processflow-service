<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Service\DepartmentService;
use App\Http\Resources\DepartmentResource;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentUnitResource;

/**
 * @OA\Tag(name="Department")
 */


class DepartmentController extends Controller
{
    private DepartmentService $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }
    /**
     * @OA\Get(
     *     path="/departments",
     *     tags={"Department"},
     *     summary="Get all departments in the system",
     *     description="Returns all available departments",
     *     @OA\Response(
     *         response=200,
     *         description="Departments found",
     *         @OA\JsonContent(ref="#/components/schemas/DepartmentResource")
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
     *  security={
     *         {"BearerAuth": {}}
     *     }
     * )
     */

    /**
     * Display a listing of the units.
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $departments = $this->departmentService->getAllDepartments();
        return DepartmentResource::collection($departments);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request)
    {
        //
    }


    /**
     * @OA\Get(
     *     path="/departments/{id}",
     *     tags={"Department"},
     *     summary="Get a Department",
     *     description="returns the details of a single Department",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the department",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Unit found",
     *         @OA\Items(ref="#/components/schemas/DepartmentResource"),
     *         
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
     *  security={
     *         {"BearerAuth": {}}
     *     }
     * )
     */
    public function show(int $id)
    {
        $department = $this->departmentService->getSingleDepartment($id);

        return new DepartmentResource($department);
    }



    /**
     * @OA\Get(
     *     path="/department_units/{id}",
     *     tags={"Department"},
     *     summary="Get a Department",
     *     description="Returns the details of a single Department",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the department",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Unit found",
     *         @OA\JsonContent(ref="#/components/schemas/DepartmentUnitResource")
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
     *  security={
     *         {"BearerAuth": {}}
     *     }
     * )
     */
    public function department_units(int $id)
    {
        $department = $this->departmentService->getDepartmentUnit($id);

        return new DepartmentUnitResource($department);
    }
}

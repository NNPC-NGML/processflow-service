<?php

namespace App\Service;

use App\Models\Routes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RouteService
{
    /**
     * Create a new route.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\MessageBag|array
     */

    public function createRoute(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'link' => 'required|string',
            'dynamic_content' => 'nulllable|string',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $model = new Routes();
        return $model->create($request->all());
    }

    /**
     * Retrieve all routes with status set to true.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Routes
     */

    public function getAllRoute()
    {
        return $model = (new Routes())->where(["status" => true])->get();
    }

    /**
     * Get a route by its ID.
     *
     * @param int $id The ID of the route to retrieve.
     * @return \App\Models\Routes The retrieved route.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the route with the provided ID is not found.
     */
    public function getRoute($id)
    {
        return $this->model()->findOrFail($id);
    }

    /**
     * Update a route by ID.
     *
     * @param int|string $id The ID of the route to update.
     * @param \Illuminate\Http\Request $data The data containing the fields to update.
     * @return bool|array Returns true if the route was updated successfully, otherwise returns an array of validation errors or false if the route was not found.
     */
    public function UpdateRoute($id, Request $data)
    {
        $validator = Validator::make($data->all(), [
            'name' => 'sometimes|string',
            'link' => 'sometimes|string',
            'dynamic_content' => 'sometimes|string',
            'status' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }
        try {
            $model = $this->model()->findOrFail($id);
            return $model->update($data->all());
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Delete a route by ID.
     *
     * @param int|string $id The ID of the route to delete.
     * @return bool Returns true if the route was deleted successfully, otherwise returns false.
     */
    public function DeleteRoute($id)
    {
        try {
            $model = $this->model()->findOrFail($id);
            return $model->delete();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the model instance used for retrieving routes.
     *
     * @return \App\Models\Routes The model instance.
     */
    public function model()
    {
        return new Routes();
    }
}

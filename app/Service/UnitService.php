<?php

namespace App\Service;

use App\Models\Unit;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class UnitService
{
 public function createUnit(array $data): ?Unit
    {

        $this->validateData($data);
        return Unit::create([
            'id' => $data['id'],
            'name' => $data['name'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at'],
            'department_id'=>$data['department_id']
        ]);
    }

     protected function validateData(array $data): void
    {
        $validator = Validator::make($data, [
            'id' => 'required',
            'name' => 'required|string|max:255',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
            'department_id'=>'required',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function deleteUnit(int $id): bool
    {
        $unit = Unit::find($id);
        if (!$unit) {
            return false;
        }
        $unit->delete();
        return true;
    }

    /**
     * Update a unit.
     *
     * @param array $data
     * @param int $id
     * @return bool
     * @throws ValidationException
     */
    public function updateUnit(array $data, int $id): ?bool
    {
        $this->validateData($data);
        $unit = Unit::find($id);
        if (!$unit) {
            return false;
        }
        return $unit->update([
        'name' => $data['name'],
           'created_at' => $data['created_at'],
        'updated_at' => $data['updated_at'],
        'department_id'=>$data['department_id']
        ]);
    }


    /**
     * Get a single unit by ID.
     *
     * @param int $id
     * @return Unit
     * @throws ModelNotFoundException
     */
    public function getSingleUnit(int $id): Unit
    {
        return Unit::findOrFail($id);
    }


    /**
     * Get all units.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUnits(): \Illuminate\Database\Eloquent\Collection
    {
        return Unit::all();
    }
}
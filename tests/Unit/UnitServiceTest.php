<?php

namespace Tests\Unit;

use App\Models\Unit;
use App\Service\UnitService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UnitServiceTest extends TestCase
{
    use RefreshDatabase;

    private UnitService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new UnitService();
    }


    public function test_it_can_create_a_unit(): void
    {
        $request = [
            'name' => 'Finance and Accounts',
            'id' => 888,
            'created_at' => '',
            'updated_at' => '',
        ];

        $result = $this->service->createUnit($request);

        $this->assertInstanceOf(Unit::class, $result);
    }

    public function test_it_throws_validation_exception_when_creating_unit_with_invalid_data(): void
    {
        $this->expectException(ValidationException::class);

        $request = ['id' => 475];

        $this->service->createUnit($request);
    }

    public function test_it_can_delete_a_unit(): void
    {
        $unit = Unit::factory()->create();

        $result = $this->service->deleteUnit($unit->id);

        $this->assertDatabaseMissing('units', ['id' => $unit->id]);
        $this->assertTrue($result);
    }


    public function test_it_returns_false_when_deleting_a_non_existent_unit(): void
    {
        $result = $this->service->deleteUnit(8349);

        $this->assertFalse($result);
    }

    //TODO:update Unit
     public function test_it_can_update_a_unit(): void
    {

        // $unit = Unit::factory()->create();

        $request = [
            'name' => 'Finance and Accounts',
            'id' => 888,
            'created_at' => '',
            'updated_at' => '',
        ];

        $created = $this->service->createUnit($request);

        $this->assertInstanceOf(Unit::class, $created);

        $update_request = [
            'name' => 'New Name Updated',
            'created_at' => '',
            'updated_at' => '',
            'id'=> $created->id
        ];

        $result = $this->service->updateUnit($update_request, $created->id);

        $this->assertTrue($result);
        $this->assertDatabaseHas('units', [
            'id' => $created->id,
            'name' => $update_request['name'],
        ]);
    }

    public function test_it_throws_validation_exception_when_updating_unit_with_invalid_data(): void
    {
        $this->expectException(ValidationException::class);

        $unit = Unit::factory()->create();
        $request = ['name' => ''];
        $this->service->updateUnit($request, $unit->id);
    }

    public function test_it_returns_false_when_updating_a_non_existent_unit(): void
    {
        $request = [
            'name' => 'Finance and Accounts',
            'id' => 888,
            'created_at' => '',
            'updated_at' => '',
        ];

        $result = $this->service->updateUnit($request, 9999);

        $this->assertFalse($result);
    }
}
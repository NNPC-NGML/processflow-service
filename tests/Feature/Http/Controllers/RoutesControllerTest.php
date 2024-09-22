<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Routes;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoutesControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_that_a_route_can_be_created(): void
    {
        $this->actingAsAuthenticatedTestUser();
        $data = [
            "name" => "home",
            "link" => "http://routename.com/home",
            "status" => 1,
        ];

        $result = $this->postJson("/api/route/create", $data);
        $this->assertDatabaseHas("routes", $data);
        $result->assertStatus(201)->assertJson([
            "data" => [
                "name" => "home",
                "link" => "http://routename.com/home",
                "status" => 1,
            ],
        ]);
    }

    public function test_that_validation_works_when_a_route_is_created()
    {
        $this->actingAsAuthenticatedTestUser();
        $data = [
            "name" => "home",
            "link" => "http://routename.com/home",
        ];

        $result = $this->postJson("/api/route/create", $data);
        $this->assertDatabaseMissing("routes", $data);
        $this->assertArrayHasKey('status', $result);
        $result->assertStatus(400);
    }

    public function test_that_all_Active_routes_can_be_fetched()
    {
        $this->actingAsAuthenticatedTestUser();
        Routes::factory(6)->create();
        $result = $this->getJson("/api/route");
        $result->assertOk()->assertJsonStructure([
            "data" => [
                [
                    "name",
                    "link",
                    "status",
                ],

            ],
        ]);
        $this->assertEquals(6, count($result["data"]));
    }

    public function test_to_see_if_fetched_records_would_ignore_inactive_routes()
    {
        $this->actingAsAuthenticatedTestUser();
        Routes::factory(3)->create();
        Routes::factory(3)->create(["status" => false]);

        $result = $this->getJson("/api/route");
        $result->assertOk()->assertJsonStructure([
            "data" => [
                [
                    "name",
                    "link",
                    "status",
                ],

            ],
        ]);
        $this->assertEquals(3, count($result["data"]));
    }

    public function test_to_see_no_item_is_returned_when_there_is_no_active_record()
    {
        $this->actingAsAuthenticatedTestUser();
        Routes::factory(3)->create(["status" => false]);

        $result = $this->getJson("/api/route");
        $result->assertOk()->assertJsonStructure([
            "data",
        ]);
        $this->assertEquals(0, count($result["data"]));
    }

    public function test_to_see_if_view_route_can_fetch_a_single_route()
    {
        $this->actingAsAuthenticatedTestUser();
        Routes::factory(3)->create();
        $result = $this->getJson("/api/route/view/1");
        $result->assertOk()->assertJsonStructure([
            "data" => [
                "name",
                "link",
                "status"
            ]
        ]);
    }

    //FIXME:

    // public function test_to_see_if_an_exception_would_be_thrown_when_a_wrong_id_is_provided_for_route()
    // {
    //     $this->actingAsAuthenticatedTestUser();
    //     $result = $this->getJson("/api/route/view/1");
    //     $result->assertStatus(404)->assertJson(['message' => 'No query results for model [App\\Models\\Routes] 1']);
    // }

    public function test_to_see_if_a_route_can_be_updated()
    {
        $this->actingAsAuthenticatedTestUser();
        Routes::factory(3)->create();
        $data = [
            "link" => "data.com"
        ];
        $result = $this->putJson("/api/route/update/1", $data);
        $result->assertStatus(200)->assertJsonStructure(['status', "message"]);
        $this->assertDatabaseHas("routes", $data);
    }
    public function test_to_see_if_route_id_to_be_updated_does_not_exist()
    {
        $this->actingAsAuthenticatedTestUser();
        $data = [
            "link" => "data.com"
        ];
        $result = $this->putJson("/api/route/update/1", $data);
        $result->assertStatus(404)->assertJsonStructure(['status', "message"]);
        $this->assertDatabaseMissing("routes", $data);
    }

    public function test_to_see_if_a_route_can_be_deleted()
    {
        $this->actingAsAuthenticatedTestUser();
        Routes::factory(3)->create();
        $result = $this->deleteJson("/api/route/delete/1");
        $result->assertStatus(200)->assertJsonStructure(['status', "message"]);
        $this->assertDatabaseMissing("routes", ["id" => 1]);
    }

    public function test_to_see_if_a_wrong_id_would_return_a_404_error()
    {
        $this->actingAsAuthenticatedTestUser();
        $result = $this->deleteJson("/api/route/delete/1");
        $result->assertStatus(404)->assertJsonStructure(['status', "message"]);
    }
}

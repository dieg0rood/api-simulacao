<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SimulationControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_simulation_get_endpoint(): void
    {
        $response = $this->getJson('/api/simulation/11111111111');

        $response->assertStatus(200);
    }
}

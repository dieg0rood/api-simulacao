<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
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
        $response->assertJsonCount(3);

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(
                '0.instituicaoFinanceira',
                '0.modalidadeCredito',
                '0.valorAPagar',
                '0.valorSolicitado',
                '0.taxaJuros',
                '0.qntParcelas'
            );
            $json->whereAllType([
                '0.instituicaoFinanceira' => 'string',
                '0.modalidadeCredito' => 'string',
                '0.valorAPagar' => 'string',
                '0.valorSolicitado' => 'string',
                '0.taxaJuros' => 'string',
                '0.qntParcelas' => 'string'
            ]);
        });
    }
}

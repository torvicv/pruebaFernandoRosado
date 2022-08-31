<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ActividadTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_anyadir_incidencia_a_actividad()
    {
        $this->post('/guardar-actividad', ['proyecto_name' => 'Proyecto 1', 'proyecto_id' => 1, 'actividad_name' => 'Actividad 1 Proyecto 1']);

        $response = $this->post('/guardar-incidencia', ['actividad_id' => 1, 'incidencia_name' => 'Incidencia 2 Actividad 1']);

        $response->assertStatus(200);

        $this->assertEquals(1, $response['id']);
    }
}

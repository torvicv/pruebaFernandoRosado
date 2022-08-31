<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Proyecto;
use App\Models\Actividad;
use App\Models\Incidencia;

class AsignarUsuariosTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_asignar_usuario_a_proyecto()
    {
        $this->post('/guardar-actividad', ['proyecto_name' => 'Proyecto 1', 'proyecto_id' => 1, 'actividad_name' => 'Actividad 1 Proyecto 1']);

        $this->post('/guardar-incidencia', ['actividad_id' => 1, 'incidencia_name' => 'Incidencia 2 Actividad 1']);

        $response = $this->post('/asignar-usuario-proyecto', ['usuario_name' => 'Victor', 'proyecto_id' => 1, 'rol_usuario' => 'participante']);

        $response->assertStatus(200);

        $this->assertEquals(1, $response['id']);

        $proyecto = Proyecto::find($response['id']);

        $this->assertEquals('Victor', $proyecto->usuarios[0]->name);
    }

    /**
     * Asigna un usuario a una actividad
     *
     * @return void
     */
    public function test_asignar_usuario_a_actividad()
    {
        $this->post('/guardar-actividad', ['proyecto_name' => 'Proyecto 1', 'proyecto_id' => 1, 'actividad_name' => 'Actividad 1 Proyecto 1']);

        $this->post('/guardar-incidencia', ['actividad_id' => 1, 'incidencia_name' => 'Incidencia 2 Actividad 1']);

        $this->post('/asignar-usuario-proyecto', ['usuario_name' => 'Victor', 'proyecto_id' => 1, 'rol_usuario' => 'participante']);

        $response = $this->post('/asignar-usuario-actividad', ['usuario_name' => 'Victor', 'actividad_id' => 1, 'proyecto_id' => 1, 'rol_usuario' => 'participante', 'id_usuario' => 1]);

        $response->assertStatus(200);

        $this->assertEquals(1, $response['id']);

        $actividad = Actividad::find($response['id']);

        $name = $actividad->usuarios->filter(function ($item, $key) {
            return $item->name == 'Victor';
        });

        $this->assertEquals('Victor', $name[0]->name);
    }

    /**
     * Asigna un usuario a una actividad
     *
     * @return void
     */
    public function test_asignar_usuario_a_incidencia()
    {
        $this->post('/guardar-actividad', ['proyecto_name' => 'Proyecto 1', 'proyecto_id' => 1, 'actividad_name' => 'Actividad 1 Proyecto 1']);

        $this->post('/guardar-incidencia', ['actividad_id' => 1, 'incidencia_name' => 'Incidencia 2 Actividad 1']);

        $this->post('/asignar-usuario-proyecto', ['usuario_name' => 'Victor', 'proyecto_id' => 1, 'rol_usuario' => 'participante']);

        $this->post('/asignar-usuario-actividad', ['usuario_name' => 'Victor', 'actividad_id' => 1, 'proyecto_id' => 1, 'rol_usuario' => 'participante', 'id_usuario' => 1]);

        $response = $this->post('/asignar-usuario-incidencia', ['usuario_name' => 'Victor', 'actividad_id' => 1, 'proyecto_id' => 1, 'id_usuario' => 1, 'id_incidencia' => 1]);

        $response->assertStatus(200);

        $this->assertEquals(1, $response['id']);

        $incidencia = Incidencia::find($response['id']);

        $name = $incidencia->usuarios->filter(function ($item, $key) {
            return $item->name == 'Victor';
        });

        $this->assertEquals('Victor', $name[0]->name);
    }
}

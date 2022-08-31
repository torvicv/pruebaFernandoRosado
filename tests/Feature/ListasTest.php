<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListasTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Listar actividades de un usuario.
     *
     * @return void
     */
    public function test_listar_actividades_un_usuario()
    {
        $this->post('/guardar-actividad', ['proyecto_name' => 'Proyecto 1', 'proyecto_id' => 1, 'actividad_name' => 'Actividad 1 Proyecto 1']);

        $this->post('/guardar-actividad', ['proyecto_name' => 'Proyecto 1', 'proyecto_id' => 1, 'actividad_name' => 'Actividad 2 Proyecto 1']);

        $this->post('/guardar-incidencia', ['actividad_id' => 1, 'incidencia_name' => 'Incidencia 2 Actividad 1']);

        $this->post('/asignar-usuario-proyecto', ['usuario_name' => 'Victor', 'proyecto_id' => 1, 'rol_usuario' => 'participante']);

        $this->post('/asignar-usuario-actividad', ['usuario_name' => 'Victor', 'actividad_id' => 1, 'proyecto_id' => 1, 'rol_usuario' => 'participante', 'id_usuario' => 1]);

        $this->post('/asignar-usuario-actividad', ['usuario_name' => 'Victor', 'actividad_id' => 2, 'proyecto_id' => 1, 'rol_usuario' => 'participante', 'id_usuario' => 1]);

        $idUsuario = 1;

        // conseguimos las actividades del usuario con id = 1
        $response = $this->get('/listar-actividades-usuario/'.$idUsuario);

        $response->assertStatus(200);

        $responseDecode = \json_decode($response->getContent());

        // comprobamos que el usuario tiene dos actividades.
        $this->assertEquals(\count($responseDecode), 2);

    }

    /**
     * Listar incidencias a las que un usuario tiene acceso.
     *
     * @return void
     */
    public function test_listar_incidencias_que_usuario_puede_acceder()
    {
        $this->post('/guardar-actividad', ['proyecto_name' => 'Proyecto 1', 'proyecto_id' => 1, 'actividad_name' => 'Actividad 1 Proyecto 1']);

        $this->post('/guardar-actividad', ['proyecto_name' => 'Proyecto 1', 'proyecto_id' => 1, 'actividad_name' => 'Actividad 2 Proyecto 1']);

        $this->post('/guardar-incidencia', ['actividad_id' => 1, 'incidencia_name' => 'Incidencia 1 Actividad 1']);

        $this->post('/guardar-incidencia', ['actividad_id' => 1, 'incidencia_name' => 'Incidencia 2 Actividad 1']);

        $this->post('/asignar-usuario-proyecto', ['usuario_name' => 'Victor', 'proyecto_id' => 1, 'rol_usuario' => 'participante', 'id_usuario' => 1]);

        $this->post('/asignar-usuario-proyecto', ['usuario_name' => 'Pepe', 'proyecto_id' => 1, 'rol_usuario' => 'responsable', 'id_usuario' => 2]);

        $this->post('/asignar-usuario-actividad', ['usuario_name' => 'Victor', 'actividad_id' => 1, 'proyecto_id' => 1, 'rol_usuario' => 'participante', 'id_usuario' => 1]);

        $this->post('/asignar-usuario-actividad', ['usuario_name' => 'Pepe', 'actividad_id' => 2, 'proyecto_id' => 1, 'rol_usuario' => 'responsable', 'id_usuario' => 2]);

        $this->post('/asignar-usuario-incidencia', ['usuario_name' => 'Victor', 'actividad_id' => 1, 'proyecto_id' => 1, 'id_usuario' => 1, 'id_incidencia' => 1]);

        $this->post('/asignar-usuario-incidencia', ['usuario_name' => 'Victor', 'actividad_id' => 1, 'proyecto_id' => 1, 'id_usuario' => 1, 'id_incidencia' => 1]);

        $idUsuario = 2;

        // conseguimos las actividades del usuario con id = 1
        $response = $this->get('/listar-incidencias-acceso/'.$idUsuario);

        $response->assertStatus(200);

        $responseDecode = \json_decode($response->getContent());

        // comprobamos que el usuario tiene dos actividades.
        $this->assertEquals(\count($responseDecode), 2);

    }

    /**
     * Listar participantes de un proyecto.
     *
     * @return void
     */
    public function test_listar_participantes_proyecto()
    {
        $this->post('/guardar-actividad', ['proyecto_name' => 'Proyecto 1', 'proyecto_id' => 1, 'actividad_name' => 'Actividad 1 Proyecto 1']);

        $this->post('/guardar-actividad', ['proyecto_name' => 'Proyecto 1', 'proyecto_id' => 1, 'actividad_name' => 'Actividad 2 Proyecto 1']);

        $this->post('/guardar-incidencia', ['actividad_id' => 1, 'incidencia_name' => 'Incidencia 1 Actividad 1']);

        $this->post('/guardar-incidencia', ['actividad_id' => 1, 'incidencia_name' => 'Incidencia 2 Actividad 1']);

        $this->post('/asignar-usuario-proyecto', ['usuario_name' => 'Victor', 'proyecto_id' => 1, 'rol_usuario' => 'participante', 'id_usuario' => 1]);

        $this->post('/asignar-usuario-proyecto', ['usuario_name' => 'Pepe', 'proyecto_id' => 1, 'rol_usuario' => 'responsable', 'id_usuario' => 2]);

        $this->post('/asignar-usuario-proyecto', ['usuario_name' => 'Victor Cabral', 'proyecto_id' => 1, 'rol_usuario' => 'participante', 'id_usuario' => 3]);

        /*$this->post('/asignar-usuario-actividad', ['usuario_name' => 'Victor', 'actividad_id' => 1, 'proyecto_id' => 1, 'rol_usuario' => 'participante', 'id_usuario' => 1]);

        $this->post('/asignar-usuario-actividad', ['usuario_name' => 'Pepe', 'actividad_id' => 2, 'proyecto_id' => 1, 'rol_usuario' => 'responsable', 'id_usuario' => 2]);

        $this->post('/asignar-usuario-incidencia', ['usuario_name' => 'Victor', 'actividad_id' => 1, 'proyecto_id' => 1, 'id_usuario' => 1, 'id_incidencia' => 1]);

        $this->post('/asignar-usuario-incidencia', ['usuario_name' => 'Victor', 'actividad_id' => 1, 'proyecto_id' => 1, 'id_usuario' => 1, 'id_incidencia' => 1]);*/

        $idProyecto = 1;

        // conseguimos las actividades del usuario con id = 1
        $response = $this->get('/listar-participantes-proyecto/'.$idProyecto);

        $response->assertStatus(200);

        $responseDecode = \json_decode($response->getContent());

        // comprobamos que el usuario tiene dos actividades.
        $this->assertEquals(\count($responseDecode), 2);

    }


}

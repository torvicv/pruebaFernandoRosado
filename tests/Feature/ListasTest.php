<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Incidencia;

class ListasTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Listar actividades de un usuario.
     * Creamos un proyecto con dos actividades, creamos otro proyecto con una actividad
     * creamos una incidencia, asignamos dos
     * usuarios a un proyecto (proyecto 1) (usuarios 1 y 2), asignamos el usuario con
     * id 1 al proyecto 2 con una actividad, asignamos actividad 1 y 2 al usuario con
     * id 1, y actividad 2 al usuario con id 2. Conseguimos la lista de actividades
     * del usuario con id 1, deberían ser 3 actividades (proyecto 1, actividad 1 y 2;
     * y en el proyecto actividad 1, el cual tiene un id = 3 en la base de datos.).
     *
     * @return void
     */
    public function test_listar_actividades_un_usuario()
    {
        $this->post('/guardar-actividad', ['proyecto_name' => 'Proyecto 1', 'proyecto_id' => 1, 'actividad_name' => 'Actividad 1 Proyecto 1']);

        $this->post('/guardar-actividad', ['proyecto_name' => 'Proyecto 1', 'proyecto_id' => 1, 'actividad_name' => 'Actividad 2 Proyecto 1']);

        $this->post('/guardar-actividad', ['proyecto_name' => 'Proyecto 2', 'proyecto_id' => 2, 'actividad_name' => 'Actividad 1 Proyecto 2']);

        $this->post('/guardar-incidencia', ['actividad_id' => 1, 'incidencia_name' => 'Incidencia 2 Actividad 1']);

        $this->post('/asignar-usuario-proyecto', ['usuario_name' => 'Victor', 'proyecto_id' => 1, 'rol_usuario' => 'participante']);

        $this->post('/asignar-usuario-proyecto', ['usuario_name' => 'Pepe', 'proyecto_id' => 1, 'rol_usuario' => 'participante']);

        $this->post('/asignar-usuario-actividad', ['usuario_name' => 'Victor', 'actividad_id' => 1, 'proyecto_id' => 1, 'rol_usuario' => 'participante', 'id_usuario' => 1]);

        $this->post('/asignar-usuario-actividad', ['usuario_name' => 'Victor', 'actividad_id' => 2, 'proyecto_id' => 1, 'rol_usuario' => 'participante', 'id_usuario' => 1]);

        $this->post('/asignar-usuario-actividad', ['usuario_name' => 'Victor', 'actividad_id' => 3, 'proyecto_id' => 2, 'rol_usuario' => 'participante', 'id_usuario' => 1]);

        $this->post('/asignar-usuario-actividad', ['usuario_name' => 'Pepe', 'actividad_id' => 2, 'proyecto_id' => 1, 'rol_usuario' => 'participante', 'id_usuario' => 2]);

        $idUsuario = 1;

        // conseguimos las actividades del usuario con id = 1
        $response = $this->get('/listar-actividades-usuario/'.$idUsuario);

        $response->assertStatus(200);

        $responseDecode = \json_decode($response->getContent());

        // comprobamos el nombre de cada incidencia.
        $this->assertEquals($responseDecode[0]->name, 'Actividad 1 Proyecto 1');
        $this->assertEquals($responseDecode[1]->name, 'Actividad 2 Proyecto 1');

        // comprobamos el id del usuario, su rol y el id de la actividad.
        $this->assertEquals($responseDecode[0]->pivot->usuarios_id, 1);
        $this->assertEquals($responseDecode[0]->pivot->actividades_id, 1);
        $this->assertEquals($responseDecode[0]->pivot->rol_usuario, 'participante');

        // comprobamos que el usuario tiene dos actividades.
        $this->assertEquals(\count($responseDecode), 3);

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

        // id del usuario Pepe
        $idUsuario = 2;

        // conseguimos las actividades del usuario con id = 1
        $response = $this->get('/listar-incidencias-acceso/'.$idUsuario);

        $response->assertStatus(200);

        $responseDecode = \json_decode($response->getContent());

        // comprobamos que son instancias de stdClass la respuesta.
        foreach ($responseDecode as $instance) {
            $this->assertInstanceOf(\stdClass::class, $instance);
        }

        // comprobamos el nombre de cada incidencia.
        $this->assertEquals($responseDecode[0]->name, 'Incidencia 1 Actividad 1');
        $this->assertEquals($responseDecode[1]->name, 'Incidencia 2 Actividad 1');

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

        $response1 = $this->post('/asignar-usuario-proyecto', ['usuario_name' => 'Juan', 'proyecto_id' => 1, 'rol_usuario' => ['1' => 'participante', '2' => 'responsable'], 'id_usuario' => 4]);

        $response1->assertStatus(200);

        $idProyecto = 1;

        // conseguimos la respuesta por el id del proyecto.
        $response = $this->get('/listar-participantes-proyecto/'.$idProyecto);

        $response->assertStatus(200);

        $responseDecode = \json_decode($response->getContent());

        // comprobamos que hay 3 usuarios con el rol de participante en el proyecto.
        $this->assertEquals(\count($responseDecode), 3);

        // array de nombres para comprobar.
        $arrayNombre = ['Victor', 'Victor Cabral', 'Juan'];
        // comprobamos los nombres de los usuarios con un array.
        collect($responseDecode)->each(function ($item, $key) use ($arrayNombre) {
            $this->assertEquals($arrayNombre[$key], $item->name);
        });

    }


}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Proyecto;
use App\Models\Actividad;
use App\Models\Usuario;
use Illuminate\Support\Facades\Schema;

class AnyadirActividadAProyectoTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_anyadir_actividad_a_proyecto()
    {

        /*$proyectos = Proyecto::factory()->count(5)->create();

        $usuarios = Usuario::factory()->count(5)->create();

        $proyecto1 = $proyectos->find(1);

        $actividad1Proyecto1 = new Actividad;

        $actividad1Proyecto1->name = "actividad 1 del proyecto 1";

        $actividad1Proyecto1->save();

        $proyecto1->actividades->save($actividad1Proyecto1);

        $this->assertDatabaseCount('proyecto', 5);

        $this->assertDatabaseCount('actividad', 1);*/

    }
}

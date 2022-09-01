<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Actividad;

class ProyectoController extends Controller
{

    /**
     * Método para crear un proyecto y una actividad, entonces asignamos la actividad
     * al proyecto según el id del proyecto enviado en la request.
     * @param Request
     * @return Proyecto
     */
    public function store(Request $request) {

        $proyectoNuevo = new Proyecto;

        $proyectoNuevo->name = $request->proyecto_name;

        $proyectoNuevo->save();

        $proyecto = Proyecto::find($request->proyecto_id);

        $actividad = new Actividad;

        $actividad->name = $request->actividad_name;

        $proyecto->actividades()->save($actividad);

        return $proyecto;
    }
}

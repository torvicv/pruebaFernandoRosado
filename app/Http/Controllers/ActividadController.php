<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Actividad;
use App\Models\Incidencia;

class ActividadController extends Controller
{
    /**
     * Método para guardar incidencia y asignarla a una actividad.
     * @param Request
     * @return Actividad.
     */
    public function store(Request $request) {

        $actividad = Actividad::find($request->actividad_id);

        $incidencia = new Incidencia;

        $incidencia->name = $request->incidencia_name;

        $actividad->incidencias()->save($incidencia);

        return $actividad;
    }
}

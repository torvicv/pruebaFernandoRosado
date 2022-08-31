<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Actividad;
use App\Models\Incidencia;

class ActividadController extends Controller
{
    public function store(Request $request) {

        $actividad = Actividad::find($request->actividad_id);

        $incidencia = new Incidencia;

        $incidencia->name = $request->incidencia_name;//'Incidencia 1 Actividad 1';

        $actividad->incidencias()->save($incidencia);

        return $actividad;
    }
}

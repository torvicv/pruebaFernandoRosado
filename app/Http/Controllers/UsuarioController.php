<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Proyecto;
use App\Models\Actividad;
use App\Models\Incidencia;

class UsuarioController extends Controller
{
    //

    public function asignarUsuarioProyecto(Request $request) {
        $usuario = new Usuario;

        $usuario->name = $request->usuario_name;

        $usuario->save();

        $proyecto = Proyecto::find($request->proyecto_id);

        $proyecto->usuarios()->attach($usuario, ['rol_usuario' => $request->rol_usuario]);

        return $proyecto;
    }

    public function asignarUsuarioActividad(Request $request) {
        /*$usuario = new Usuario;

        $usuario->name = $request->usuario_name;

        $usuario->save();*/

        $actividad = Actividad::find($request->actividad_id);

        $proyectoPorId = Proyecto::find($request->proyecto_id);

        $usuarioId = Usuario::find($request->id_usuario);
        // hay que pasar el id del proyecto en la request
        /*$proyecto = $proyectoPorId->whereHas('usuarios', function($q) use ($usuarioId) {
            $q->where('rol_usuario', 'participante')
                ->where('usuario_id', $usuarioId->id);
        })
        ->first();

        if ($actividad->proyecto_id == $proyecto->id) {*/
            $actividad->usuarios()->attach($usuarioId, ['rol_usuario' => $request->rol_usuario]);
        // }

        return $actividad;
    }

    public function asignarUsuarioIncidencia(Request $request) {
        /*$usuario = new Usuario;

        $usuario->name = $request->usuario_name;

        $usuario->save();*/

        $actividadPorId = Actividad::find($request->actividad_id);

        $proyectoPorId = Proyecto::find($request->proyecto_id);

        $incidenciaPorId = Incidencia::find($request->id_incidencia);

        $usuarioId = Usuario::find($request->id_usuario);
        // hay que pasar el id del proyecto en la request
        $proyecto = $proyectoPorId->whereHas('usuarios', function($q) use ($usuarioId) {
            $q->where('rol_usuario', 'participante')
                ->where('usuario_id', $usuarioId->id);
        })
        ->first();

        /*if ($actividadPorId->proyecto_id == $proyecto->id) {
            $actividadPorId->usuarios()->attach($usuarioId, ['rol_usuario' => $request->rol_usuario]);
        }*/

        $actividad = $actividadPorId->whereHas('usuarios', function($q) use ($usuarioId) {
            $q->where('rol_usuario', 'participante')
                ->where('usuarios_id', $usuarioId->id);
        })
        ->first();

        if ($actividad) {
            $incidenciaPorId->usuarios()->attach($usuarioId);
        }

        return $incidenciaPorId;
    }

    public function listarActividadesUsuario($id) {

        $idUsuario = \intval($id);

        $usuario = Usuario::find($idUsuario);

        return $usuario->actividades()->get();
    }

    public function listarIncidenciasAccesoUsuario($id) {

        $incidencias = Incidencia::all();

        $actividadesUsuario = $incidencias->filter(function($value, $key) use ($id) {
            $usuarioId = \intval($id);
            $actividadId = $value->actividades->id;
            $actividadPorId = Actividad::find($actividadId);
            $actividad = $actividadPorId->whereHas('usuarios', function($q) use ($usuarioId) {
                $q->where('rol_usuario', 'responsable')
                    ->where('usuarios_id', $usuarioId);
            })
            ->first();
            return $actividad;
        });

        return $actividadesUsuario;
    }

    public function listarParticipantesProyecto($id) {

        $idProyecto = \intval($id);
        $proyectoPorId = Proyecto::find($idProyecto);

        $usuariosProyecto = $proyectoPorId->usuarios()->get();

        $usuarios = $usuariosProyecto->filter(function ($value) {
            // return $value->rol_usuario == 'participante';
            return $value->pivot->rol_usuario == 'participante';
        })->values();

        /*$actividadesUsuario = $incidencias->filter(function($value, $key) use ($id) {
            $actividadId = $value->actividades->id;
            $actividadPorId = Actividad::find($actividadId);
            $actividad = $actividadPorId->whereHas('usuarios', function($q) use ($usuarioId) {
                $q->where('rol_usuario', 'responsable')
                    ->where('usuarios_id', $usuarioId);
            })
            ->first();
            return $actividad;
        });*/

        return $usuarios;
    }
}

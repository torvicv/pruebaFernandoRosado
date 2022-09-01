<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Proyecto;
use App\Models\Actividad;
use App\Models\Incidencia;

class UsuarioController extends Controller
{
    /**
     * Método para asignar un usuario a un proyecto.
     * @param Request
     * @return Proyecto
     */
    public function asignarUsuarioProyecto(Request $request) {
        $usuario = new Usuario;

        $usuario->name = $request->usuario_name;

        $usuario->save();

        $proyecto = Proyecto::find($request->proyecto_id);

        if (is_array($request->rol_usuario)) {
            $proyecto->usuarios()->attach($usuario, ['rol_usuario' => json_encode($request->rol_usuario)]);
            $proyecto = $proyecto;
        } else {
            $proyecto->usuarios()->attach($usuario, ['rol_usuario' => $request->rol_usuario]);
        }


        return $proyecto;
    }

    /**
     * Método para asignar un usuario a una actividad.
     * @param Request
     * @return Actividad
     */
    public function asignarUsuarioActividad(Request $request) {

        $actividad = Actividad::find($request->actividad_id);

        $proyectoPorId = Proyecto::find($request->proyecto_id);

        $usuarioId = Usuario::find($request->id_usuario);

        $actividad->usuarios()->attach($usuarioId, ['rol_usuario' => $request->rol_usuario]);

        return $actividad;
    }

    /**
     * Método para asignar incidencia a un usuario.
     * @param Request
     * @return Incidencia
     */
    public function asignarUsuarioIncidencia(Request $request) {

        $actividadPorId = Actividad::find($request->actividad_id);

        $proyectoPorId = Proyecto::find($request->proyecto_id);

        $incidenciaPorId = Incidencia::find($request->id_incidencia);

        $usuarioId = Usuario::find($request->id_usuario);

        $proyecto = $proyectoPorId->whereHas('usuarios', function($q) use ($usuarioId) {
            $q->where('rol_usuario', 'participante')
                ->where('usuario_id', $usuarioId->id);
        })
        ->first();

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

    /**
     * Método para conseguir las actividades de un usuario por su id.
     * @param $id
     * @return collection Actividades
     */
    public function listarActividadesUsuario($id) {

        $idUsuario = \intval($id);

        $usuario = Usuario::find($idUsuario);

        return $usuario->actividades()->get();
    }

    /**
     * Método para conseguir las incidencias de un usuario por su id.
     * @param $id
     * @return collection Incidencia
     */
    public function listarIncidenciasAccesoUsuario($id) {

        $incidencias = Incidencia::all();

        $incidenciasUsuario = $incidencias->filter(function($incidencia) use ($id) {
            $usuarioId = \intval($id);
            $actividadId = $incidencia->actividad->id;
            $actividadPorId = Actividad::find($actividadId);
            $actividad = $actividadPorId->whereHas('usuarios', function($q) use ($usuarioId) {
                $q->where('rol_usuario', 'responsable')
                    ->where('usuarios_id', $usuarioId);
            })
            ->first();
            return $actividad;
        });

        return $incidenciasUsuario;
    }

    /**
     * Método para conseguir los usuarios con el rol participante en un proyecto.
     * @param $id
     * @return collection Usuario
     */
    public function listarParticipantesProyecto($id) {

        $idProyecto = \intval($id);
        $proyectoPorId = Proyecto::find($idProyecto);

        $usuariosProyecto = $proyectoPorId->usuarios()->get();

        $usuarios = $usuariosProyecto->filter(function ($usuario) {
            return $usuario->pivot->rol_usuario == 'participante' || $this->isJson($usuario->pivot->rol_usuario);
        })->values();

        return $usuarios;
    }

    /**
     * Método para comprobar que el parámetro es un json.
     * @param $string.
     */
    private function isJson($string) {
        json_decode($string);
        if (json_last_error() != JSON_ERROR_NONE)
            return false;
        return true;
     }
}

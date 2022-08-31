<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    /**
     * Atributos asignables.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    protected $table = 'usuarios';

    public $timestamps = false;

    /**
     * Relación muchos a muchos con la clase Proyecto.
     * @return Usuario
     */
    public function proyectos()
    {
        return $this->belongsToMany(Proyecto::class,  'usuario_proyecto')->withPivot('rol_usuario');
    }

    public function actividades()
    {
        return $this->belongsToMany(Actividad::class,  'usuario_actividad', 'usuarios_id', 'actividades_id')->withPivot('rol_usuario');
    }
}

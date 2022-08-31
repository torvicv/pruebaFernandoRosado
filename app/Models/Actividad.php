<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
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

    protected $table = 'actividades';

    public $timestamps = false;

    public function usuarios()
    {
        /*return $this->hasMany(Usuario::class, 'usuario_actividad')
            ->withPivot([
                'rol_usuario'
            ]);*/
        return $this->belongsToMany(Usuario::class, 'usuario_actividad', 'actividades_id', 'usuarios_id')
            ->withPivot([
                'rol_usuario'
            ]);
    }

    public function proyectos()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class);
    }
}

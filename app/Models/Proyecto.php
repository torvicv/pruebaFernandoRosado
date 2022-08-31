<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
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

    protected $table = 'proyectos';

    public $timestamps = false;


    /**
     * Relación uno a muchos con la clase Actividad.
     * @return [] Actividad
     */
    public function actividad()
    {
        return $this->hasMany(Actividad::class);
    }

    /**
     * Relación muchos a muchos con la clase Usuario.
     * @return Usuario
     */
    public function usuarios()
    {
        /*return $this->hasMany(Usuario::class, 'usuario_proyecto')
            ->withPivot([
                'rol_usuario'
            ]);*/
        return $this->belongsToMany(Usuario::class, 'usuario_proyecto')->withPivot('rol_usuario');
    }
}

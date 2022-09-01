<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
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

    protected $table = 'incidencias';

    public $timestamps = false;

    /**
     * Relación muchos a muchos con la clase Usuario.
     * @return Usuario
     */
    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'usuario_incidencia', 'incidencia_id', 'usuario_id');
    }

    /**
     * Relación uno a muchos con la clase Actividad.
     * @return Actividad
     */
    public function actividad() {
        return $this->belongsTo(Actividad::class, 'id');
    }
}

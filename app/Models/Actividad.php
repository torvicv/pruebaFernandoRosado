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

    /**
     * Relación muchos a muchos con Usuario.
     * @return collection Usuario
     */
    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'usuario_actividad', 'actividades_id', 'usuarios_id')
            ->withPivot([
                'rol_usuario'
            ]);
    }

    /**
     * Relación de muchos a uno con Proyecto.
     * @return proyecto
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    /**
     * Relación uno a muchos con Incidencia.
     * @return collection Incidencia
     */
    public function incidencias()
    {
        return $this->hasMany(Incidencia::class);
    }
}

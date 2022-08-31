<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_actividad', function (Blueprint $table) {
            $table->foreignId('actividades_id')->constrained();
            $table->foreignId('usuarios_id')->constrained();
            $table->enum('rol_usuario', ['participante', 'responsable']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_actividad');
    }
};

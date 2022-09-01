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
        Schema::create('usuario_proyecto', function (Blueprint $table) {
            $table->foreignId('proyecto_id')->constrained();
            $table->foreignId('usuario_id')->constrained();
            $table->enum('rol_usuario', ['participante', 'responsable', json_encode(['1' => 'participante', '2' => 'responsable'])]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_proyecto');
    }
};

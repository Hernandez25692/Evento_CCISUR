<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('capacitaciones')) { // Verifica si la tabla ya existe
            Schema::create('capacitaciones', function (Blueprint $table) {
                $table->id();
                $table->string('nombre')->unique(); // Evita duplicados en nombres
                $table->string('lugar');
                $table->date('fecha');
                $table->string('impartido_por');
                $table->text('descripcion')->nullable();
                $table->string('imagen')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('capacitaciones');
    }
};

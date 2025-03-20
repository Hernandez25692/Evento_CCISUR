<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('plantillas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('capacitacion_id')->constrained('capacitaciones')->onDelete('cascade');
            $table->string('firma');
            $table->string('fondo');
            $table->date('fecha_emision');
            $table->enum('orientacion', ['vertical', 'horizontal'])->default('horizontal'); // Agregar esta línea
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plantillas');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('participantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo');
            $table->string('correo')->unique();
            $table->string('telefono');
            $table->string('empresa')->nullable();
            $table->string('puesto')->nullable();
            $table->integer('edad');
            $table->string('identidad')->unique();
            $table->enum('nivel_educativo', ['Primaria', 'Secundaria', 'Universidad', 'Postgrado']);
            $table->enum('genero', ['Masculino', 'Femenino', 'Otro']);
            $table->string('municipio');
            $table->string('ciudad');
            $table->foreignId('capacitacion_id')->constrained('capacitaciones')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('participantes');
    }
};

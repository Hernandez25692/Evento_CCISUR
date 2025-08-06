<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plantillas_globales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('fondo');
            $table->string('firma_1')->nullable();
            $table->string('firma_2')->nullable();
            $table->string('nombre_firma_1')->nullable();
            $table->string('nombre_firma_2')->nullable();
            $table->enum('orientacion', ['vertical', 'horizontal'])->default('horizontal');
            $table->enum('tipo_certificado', ['generico', 'convenio'])->default('generico');
            $table->string('titulo_convenio')->nullable();
            $table->date('fecha_emision');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantillas_globales');
    }
};

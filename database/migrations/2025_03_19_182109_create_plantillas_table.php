<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plantillas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('capacitacion_id')->constrained('capacitaciones')->onDelete('cascade');
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

    public function down(): void
    {
        Schema::dropIfExists('plantillas');
    }
};

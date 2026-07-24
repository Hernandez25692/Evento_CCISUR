<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Registro de auditoría de cada descarga pública de diploma: permite
        // detectar patrones de abuso (scraping, IPs con volumen anómalo) y
        // responder con datos concretos si alguien reporta un mal uso.
        Schema::create('descargas_diplomas_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('capacitacion_id')->constrained('capacitaciones')->cascadeOnDelete();
            $table->foreignId('participante_id')->constrained('participantes')->cascadeOnDelete();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['capacitacion_id', 'created_at']);
            $table->index(['ip', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('descargas_diplomas_log');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('capacitacion_participante', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('capacitacion_id');
            $table->unsignedBigInteger('participante_id');
            $table->timestamps();

            $table->foreign('capacitacion_id')
                ->references('id')->on('capacitaciones')
                ->onDelete('cascade');

            $table->foreign('participante_id')
                ->references('id')->on('participantes')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('capacitacion_participante');
    }
};

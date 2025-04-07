<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('capacitaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('lugar');
            $table->date('fecha');
            $table->string('impartido_por');
            $table->text('descripcion')->nullable();
            $table->string('imagen')->nullable();

            // Nuevos campos
            $table->string('tipo_formacion')->nullable();
            $table->string('duracion')->nullable();
            $table->string('forma')->nullable(); // Presencial, Virtual, Híbrida
            $table->enum('cupos', ['limitado', 'ilimitado'])->default('ilimitado');
            $table->integer('limite_participantes')->nullable();
            $table->enum('medio', ['gratis', 'pago'])->default('gratis');

            // Precios por tipo de persona
            $table->decimal('precio_afiliado', 10, 2)->nullable();
            $table->decimal('isv_afiliado', 10, 2)->nullable();
            $table->decimal('precio_no_afiliado', 10, 2)->nullable();
            $table->decimal('isv_no_afiliado', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('capacitaciones');
    }
};

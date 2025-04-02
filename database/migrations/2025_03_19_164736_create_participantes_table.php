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
            $table->enum('nivel_educativo', [
                'Primaria Completa',
                'Primaria Incompleta',
                'Secundaria Completa',
                'Secundaria Incompleta',
                'Tecnico Completo',
                'Tecnico Incompleto',
                'Universitaria Completa',
                'Universitaria Incompleta'
            ]);
            $table->enum('genero', ['Masculino', 'Femenino', 'Otro']);
            $table->string('municipio');
            $table->string('ciudad');

            // 🔄 Nuevos campos para formaciones de pago
            $table->boolean('afiliado')->default(false);              // ¿Es afiliado?
            $table->decimal('precio', 8, 2)->nullable();              // Precio base
            $table->decimal('isv', 8, 2)->nullable();                 // ISV
            $table->decimal('total', 8, 2)->nullable();               // Total a pagar
            $table->string('comprobante')->nullable();               // Ruta comprobante de pago

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('participantes');
    }
};

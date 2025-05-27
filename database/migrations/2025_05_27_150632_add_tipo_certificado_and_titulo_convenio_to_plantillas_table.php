<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('plantillas', function (Blueprint $table) {
            $table->enum('tipo_certificado', ['generico', 'convenio'])->default('generico');
            $table->string('titulo_convenio')->nullable(); // Solo se usa si es "convenio"
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plantillas', function (Blueprint $table) {
            //
        });
    }
};

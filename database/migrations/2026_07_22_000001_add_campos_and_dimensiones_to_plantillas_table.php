<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plantillas', function (Blueprint $table) {
            $table->unsignedInteger('fondo_width')->nullable()->after('fondo');
            $table->unsignedInteger('fondo_height')->nullable()->after('fondo_width');
            $table->json('campos')->nullable()->after('titulo_convenio');
        });
    }

    public function down(): void
    {
        Schema::table('plantillas', function (Blueprint $table) {
            $table->dropColumn(['fondo_width', 'fondo_height', 'campos']);
        });
    }
};

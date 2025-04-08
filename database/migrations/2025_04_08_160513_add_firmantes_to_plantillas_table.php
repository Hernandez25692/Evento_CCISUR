<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('plantillas', function (Blueprint $table) {
            $table->string('nombre_firma_1')->nullable()->after('firma_1');
            $table->string('nombre_firma_2')->nullable()->after('firma_2');
        });
    }

    public function down(): void {
        Schema::table('plantillas', function (Blueprint $table) {
            $table->dropColumn(['nombre_firma_1', 'nombre_firma_2']);
        });
    }
};

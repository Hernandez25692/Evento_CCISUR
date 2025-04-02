<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('plantillas', function (Blueprint $table) {
            $table->string('firma_1')->nullable()->after('fondo');
            $table->string('firma_2')->nullable()->after('firma_1');
        });
    }

    public function down(): void {
        Schema::table('plantillas', function (Blueprint $table) {
            $table->dropColumn(['firma_1', 'firma_2']);
        });
    }
};

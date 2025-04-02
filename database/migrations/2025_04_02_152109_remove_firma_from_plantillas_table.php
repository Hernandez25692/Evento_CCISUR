<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('plantillas', function (Blueprint $table) {
            $table->dropColumn('firma');
        });
    }

    public function down(): void {
        Schema::table('plantillas', function (Blueprint $table) {
            $table->string('firma')->nullable();
        });
    }
};

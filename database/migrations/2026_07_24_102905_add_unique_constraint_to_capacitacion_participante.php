<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Antes de aplicar la restricción única, elimina duplicados que
        // hayan podido colarse (condiciones de carrera, imports, etc.),
        // conservando la fila más antigua de cada par capacitación/participante.
        DB::statement('
            DELETE FROM capacitacion_participante
            WHERE id NOT IN (
                SELECT id FROM (
                    SELECT MIN(id) AS id
                    FROM capacitacion_participante
                    GROUP BY capacitacion_id, participante_id
                ) AS conservar
            )
        ');

        Schema::table('capacitacion_participante', function (Blueprint $table) {
            $table->unique(['capacitacion_id', 'participante_id'], 'capacitacion_participante_unico');
        });
    }

    public function down(): void
    {
        Schema::table('capacitacion_participante', function (Blueprint $table) {
            $table->dropUnique('capacitacion_participante_unico');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('capacitaciones', function (Blueprint $table) {
            // Interruptor manual del admin: hasta que no se active, la vista
            // pública no muestra el botón de descarga para nadie, sin
            // importar el estado individual (habilitado_diploma) de cada
            // participante. Evita que el botón aparezca antes de tener una
            // plantilla lista o antes de que el organizador quiera publicar.
            $table->boolean('diplomas_publicados')->default(false)->after('medio');
        });
    }

    public function down(): void
    {
        Schema::table('capacitaciones', function (Blueprint $table) {
            $table->dropColumn('diplomas_publicados');
        });
    }
};

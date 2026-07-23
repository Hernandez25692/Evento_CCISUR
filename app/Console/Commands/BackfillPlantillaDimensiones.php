<?php

namespace App\Console\Commands;

use App\Models\Plantilla;
use App\Models\PlantillaGlobal;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackfillPlantillaDimensiones extends Command
{
    protected $signature = 'diplomas:backfill-dimensiones';

    protected $description = 'Calcula fondo_width/fondo_height para plantillas y plantillas globales que aún no las tienen, para que el PDF respete la proporción real de la imagen';

    public function handle(): int
    {
        foreach ([Plantilla::class, PlantillaGlobal::class] as $modelo) {
            $modelo::whereNull('fondo_width')
                ->orWhereNull('fondo_height')
                ->get()
                ->each(function ($plantilla) {
                    if (!$plantilla->fondo || !Storage::disk('public')->exists($plantilla->fondo)) {
                        $this->warn("Sin archivo de fondo válido: {$plantilla->getTable()}#{$plantilla->id}");
                        return;
                    }

                    $dimensiones = getimagesize(Storage::disk('public')->path($plantilla->fondo));

                    if (!$dimensiones) {
                        $this->warn("No se pudo leer la imagen: {$plantilla->getTable()}#{$plantilla->id}");
                        return;
                    }

                    [$ancho, $alto] = $dimensiones;
                    $plantilla->forceFill(['fondo_width' => $ancho, 'fondo_height' => $alto])->save();

                    $this->info("Actualizada {$plantilla->getTable()}#{$plantilla->id}: {$ancho}x{$alto}");
                });
        }

        return self::SUCCESS;
    }
}
